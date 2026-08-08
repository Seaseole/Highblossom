@props([
    'headers' => [],
    'rows' => [],
    'paginate' => false,
])

<div class="admin-table overflow-x-auto shadow-2xl shadow-black/20">
    <table class="min-w-full divide-y divide-white/5">
        <thead>
            <tr>
                @foreach ($headers as $header)
                    <th class="font-headline px-6 py-4 text-left text-xs font-bold tracking-widest text-[#FAFAFA] uppercase">
                        {{ $header }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
            @forelse ($rows as $row)
                <tr class="group transition-colors duration-200 hover:bg-[#DC2626]/5">
                    @foreach ($row as $cell)
                        <td class="px-6 py-4 text-sm whitespace-nowrap text-[#A1A1AA] transition-colors duration-200 group-hover:text-[#FAFAFA]">
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

@if ($paginate && method_exists($rows, 'hasPages') && $rows->hasPages())
    <div class="mt-8">{{ $rows->links() }}</div>
@endif
