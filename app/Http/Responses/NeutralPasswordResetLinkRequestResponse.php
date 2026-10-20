<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Fortify\Http\Responses\FailedPasswordResetLinkRequestResponse as FortifyFailedPasswordResetLinkRequestResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Answer a reset request for an unknown account with the same sentence as a known one.
 *
 * Fortify distinguishes the two: a missing address is answered on the email key with
 * "We can't find a user with that email address.", which tells whoever is typing that no
 * account exists there, in the same shape as the success case. That is the enumeration
 * oracle this closes, and it is the one that matters most now that the field also accepts
 * usernames — a username is guessable in a way an address usually is not.
 *
 * Only the missing-account case is neutralised. A throttled request still reports the
 * lockout, because that is a real answer the person needs and hiding it would just make
 * the retry look ignored.
 *
 * Bound over Fortify's own implementation of the contract in FortifyServiceProvider.
 */
class NeutralPasswordResetLinkRequestResponse extends FortifyFailedPasswordResetLinkRequestResponse
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param Request $request
     */
    public function toResponse($request): Response
    {
        if ($this->status !== 'passwords.user') {
            return parent::toResponse($request);
        }

        // Deliberately the success path's own key, not a lookalike sentence: the two
        // responses have to stay identical as the copy is edited, and a second string is a
        // second thing to drift.
        $message = trans('passwords.sent');

        if ($request->wantsJson()) {
            return new JsonResponse(['message' => $message], 200);
        }

        return back()->with('status', $message);
    }
}
