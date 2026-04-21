<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domains\Bookings\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class QuoteController
{
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

    public function updateStatus(Request $request, Quote $quote)
    {
        $request->validate([
            'status' => 'required|in:pending,contacted,completed,cancelled',
        ]);

        $quote->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Quote status updated successfully.');
    }

    public function destroy(Quote $quote)
    {
        $quote->delete();

        return redirect()->route('admin.quotes.index')->with('success', 'Quote deleted successfully.');
    }
}
