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
        @foreach($colors as $color)
                    <?php 
					$qty=''; 
					$rate=''; 
					$amount=''; 
					?>
                @foreach($sizes as $size)
                    @if (isset($matrixvalue[$color->stylecolor][$size->stylesize]['qty']))
                    <?php $qty=$matrixvalue[$color->stylecolor][$size->stylesize]['qty'];?>
                    @else
                      <?php $qty='';?>
                    @endif
                    @if (isset($matrixvalue[$color->stylecolor][$size->stylesize]['rate']))
                    <?php $rate=$matrixvalue[$color->stylecolor][$size->stylesize]['rate'];?>
                    @else
                      <?php $rate='';?>
                    @endif
                    @if (isset($matrixvalue[$color->stylecolor][$size->stylesize]['amount']))
                    <?php $amount=$matrixvalue[$color->stylecolor][$size->stylesize]['amount'];?>
                    @else
                      <?php $amount='';?>
                    @endif
                    <tr align="center">
                    <td width="100px">
                    {{ $color->name }}
                    <input type="hidden" name="color[{{ $i }}]" id="color_{{ $i }}" value="{{ $color->stylecolor }}"/>
                    </td>
                    <td width="100px">{{ $color->code }}</td>
                    <td width="100px">{{ $color->sort_id }}</td>
                    <td width="100px">
                    {{ $size->name }}
                     <input type="hidden" name="size[{{ $i }}]" id="size_{{ $i }}" value="{{ $size->stylesize }}"/>
                    </td>
                    <td width="100px">{{ $size->code }}</td>
                    <td width="100px">{{ $size->sort_id }} </td>
                    <td width="70px">
                    <input type="text" name="qty[{{ $i }}]" id="qty_{{ $i }}" class="number integer" onchange="MsStyleSampleCs.calculate({{ $i }},{{ $loop->parent->count*$loop->count}},'qty')" value="{{ $qty }}"/>
                    </td>
                    <td>
                    <input type="text" name="rate[{{ $i  }}]" id="rate_{{ $i }}" class="number integer" onchange="MsStyleSampleCs.calculate({{ $i }},{{ $loop->parent->count*$loop->count}},'rate')" value="{{ $rate }}"/>
                    </td>
                    <td>
                    <input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number" value="{{ $amount }}" readonly/>
                    </td>
                    </tr>
                      <?php $i++;?>
                @endforeach
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