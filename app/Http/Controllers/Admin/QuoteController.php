<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\QuoteStatusRequest;
use App\Models\Quote;
use App\Services\QuoteService;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class QuoteController
{
    public function __construct(
        private readonly QuoteService $quoteService,
    ) {}

    public function index(Request $request): View
    {
        $status = $request->get('status');
        $query = Quote::with(['glassType', 'serviceType'])->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $quotes = $query->paginate(20);

        return view('admin.quotes.index', compact('quotes', 'status'));
    }

    public function show(Quote $quote): View
    {
        $quote->load(['glassType', 'serviceType']);

        return view('admin.quotes.show', compact('quote'));
    }

    public function updateStatus(QuoteStatusRequest $request, Quote $quote)
    {
        $this->quoteService->updateStatus($quote, $request->input('status'));

        return redirect()->back()->with('success', __('messages.quote_status_updated'));
    }

    public function destroy(Quote $quote)
    {
        $this->quoteService->delete($quote);

        return redirect()->route('admin.quotes.index')->with('success', __('messages.quote_deleted'));
    }
}
