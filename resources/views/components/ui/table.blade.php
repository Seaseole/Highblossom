@props([
    'headers' => [],
    'rows' => [],
    'paginate' => false,
])

<div class="admin-table overflow-x-auto shadow-2xl shadow-black/20">
    <table class="min-w-full divide-y divide-white/5">
        <thead>
            <tr>
                @foreach($headers as $header)
                    <th class="px-6 py-4 text-left text-xs font-bold text-[#FAFAFA] uppercase tracking-widest font-headline">
                        {{ $header }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
            @forelse($rows as $row)
                <tr class="group hover:bg-[#DC2626]/5 transition-colors duration-200">
                    @foreach($row as $cell)
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-[#A1A1AA] group-hover:text-[#FAFAFA] transition-colors duration-200">
                            {{ $cell }}
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headers) }}" class="px-6 py-12 text-center text-[#A1A1AA] italic">
                        No results found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($paginate && method_exists($rows, 'hasPages') && $rows->hasPages())
    <div class="mt-8">
        {{ $rows->links() }}
    </div>
@endif
