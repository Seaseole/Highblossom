@php
    $headers = $headers ?? [];
    $rows = $rows ?? [];
    $caption = $caption ?? null;
@endphp

<div class="cb-table">
    @if($caption)
        <caption class="cb-table__caption">{{ $caption }}</caption>
    @endif

    <table>
        <thead class="cb-table__thead">
            <tr class="cb-table__tr">
                @foreach($headers as $header)
                    <th class="cb-table__th" scope="col">{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="cb-table__tbody">
            @foreach($rows as $row)
                <tr class="cb-table__tr">
                    @foreach($row as $cell)
                        <td class="cb-table__td">{{ $cell }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
