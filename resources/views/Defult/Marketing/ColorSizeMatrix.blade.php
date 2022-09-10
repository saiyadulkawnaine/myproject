<table border="1">
        <tr align="center">
               <td colspan="3">Color</td>
               <td colspan="3">Size</td>
               <td colspan="3"><input type="checkbox" name="is_copy" id="is_copy" checked/>Copy</td>
        </tr>
        <tr align="center">
               <td width="100px">Name</td>
               <td width="100px">Code</td>
               <td width="70px">Sequence</td>
               <td width="100px">Name</td>
               <td width="100px">Code</td>
               <td width="70px">Sequence</td>
               <td width="70px">Qty</td>
               <td width="70px">Rate</td>
               <td width="80px">Amount</td>
           
        </tr>
    <tbody>
      <?php $i=1;?>
        @foreach($colorsizes as $colorsize)
                    <tr align="center">
                    <td width="100px">
                    {{ $colorsize->color_name }}
                     <input type="hidden" name="style_gmt_color_size_id[{{ $i }}]" id="style_gmt_color_size_id_{{ $i }}" value="{{ $colorsize->style_gmt_color_size_id }}"/>
                    <input type="hidden" name="color[{{ $i }}]" id="color_{{ $i }}" value="{{ $colorsize->stylecolor }}"/>
                    </td>
                    <td width="100px">{{ $colorsize->code }}</td>
                    <td width="100px">{{ $colorsize->sort_id }}</td>
                    <td width="100px">
                    {{ $colorsize->name }}
                     <input type="hidden" name="size[{{ $i }}]" id="size_{{ $i }}" value="{{ $colorsize->stylesize }}"/>
                    </td>
                    <td width="100px">{{ $colorsize->code }}</td>
                    <td width="100px">{{ $colorsize->sort_id }} </td>
                    <td width="70px">
                    <input type="text" name="qty[{{ $i }}]" id="qty_{{ $i }}" class="number integer" onchange="MsStyleSampleCs.calculate({{ $i }},{{ $loop->count}},'qty')" value="{{ $colorsize->qty }}"/>
                    </td>
                    <td>
                    <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer" onchange="MsStyleSampleCs.calculate({{ $i }},{{$loop->count}},'rate')" value="{{ $colorsize->rate }}"/>
                    </td>
                    <td>
                    <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number" value="{{ $colorsize->amount }}" readonly/>
                    </td>
                    </tr>
                      <?php $i++;?>
        @endforeach
    </tbody>
</table>
<script>
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>