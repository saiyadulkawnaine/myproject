<table border="1" align="center" style="width:100%">
    <tbody>
    <tr align="center">
            <td colspan="6"><input type="checkbox" name="is_copy" id="is_copy" checked/>Copy</td>

        </tr>
        <tr align="center">
            <td>Size</td>
            <td>Size Code</td>
            <td>Sequence</td>
            <td>Qty</td>
            <td>Rate</td>
            <td>Amount</td>
        </tr>
        @foreach ($stylesize as $size)
            <tr>
                <td width="100" align="center">
                {{ $size->name }}
                <input type="hidden" name="size[{{ $loop->iteration}}]" id="size"  value="{{ $size->stylesize }}"/>

                </td>
                <td align="center">
                {{ $size->size_code }}
                </td>
                <td align="center">
               {{ $size->size_sequence }}
                </td>
                <td>
                <input type="text" name="qty[{{ $loop->iteration }}]" id="qty" class="number integer" onChange="MsSalesOrderSize.calculate({{ $loop->iteration }},{{ $loop->count }},'qty')" value="{{ $size->qty }}" />
                </td>
                <td>
                <input type="text" name="rate[{{ $loop->iteration }}]" id="rate" class="number integer" onChange="MsSalesOrderSize.calculate({{ $loop->iteration }},{{ $loop->count }},'rate')" value="{{ $size->rate }}"/>
                </td>
                <td>
                <input type="text" name="amount[{{ $loop->iteration }}]" id="amount" class="number integer" value="{{ $size->amount }}" readonly/>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
