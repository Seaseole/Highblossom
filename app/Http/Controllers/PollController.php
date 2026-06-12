<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\PollVote;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PollController extends Controller
{
    public function vote(Request $request, Poll $poll)
    {
        Log::info('Poll vote attempt', [
            'poll_id' => $poll->id,
            'request_options' => $request->options,
            'poll_options' => $poll->options,
            'ip' => $request->ip(),
        ]);

        $validator = Validator::make($request->all(), [
            'options' => 'required|array|min:1',
            'options.*' => [
                'integer',
                'min:0',
                'max:'.(count($poll->options) - 1),
            ],
        ]);

        if ($validator->fails()) {
            Log::error('Poll vote validation failed', [
                'errors' => $validator->errors()->toArray(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'error' => 'Validation failed',
                'details' => $validator->errors(),
            ], 422);
        }

        if (! $poll->allow_multiple && count($request->options) > 1) {
            return response()->json([
                'error' => 'This poll only allows one answer.',
            ], 422);
        }

        $ipAddress = $request->ip();
        $userId = auth()->id();

        if (! $poll->is_active) {
            return response()->json(['error' => 'This poll is no longer accepting votes.'], 403);
        }

        $votesCreated = 0;
        foreach ($request->options as $optionIndex) {
            $index = (int) $optionIndex;
            try {
                PollVote::create([
                    'poll_id' => $poll->id,
                    'option_index' => $index,
                    'ip_address' => $ipAddress,
                    'user_id' => $userId,
                ]);
                $votesCreated++;
            } catch (UniqueConstraintViolationException $e) {
                Log::warning('Poll vote rejected: already voted', ['poll_id' => $poll->id, 'ip' => $ipAddress]);

                return response()->json([
                    'error' => 'You have already voted in this poll.',
                    'results' => $poll->results,
                ], 422);
            }
        }

        Log::info('Poll voting complete', [
            'poll_id' => $poll->id,
            'votes_created' => $votesCreated,
        ]);

        cache()->forget("poll_results_{$poll->id}");
        cache()->forget("poll_counts_{$poll->id}");

        session(["poll_voted_{$poll->id}" => true]);

        return response()->json([
            'success' => true,
            'results' => $poll->show_results ? $poll->results : [],
        ]);
    }

    public function results(Poll $poll)
    {
        return response()->json([
            'results' => $poll->show_results ? $poll->results : [],
        ]);
    }
}
