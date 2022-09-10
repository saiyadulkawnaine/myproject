<table border="1">
    <tr align="center">
    <td width="100px"></td>
    <td width="100px" colspan="3">Color</td>
    <td width="100px" colspan="3">Size</td>
    <td width="100px"><input type="checkbox" name="is_copy" id="is_copy" checked/>Copy</td>
    </tr>
    <tr align="center">
    <td width="100px">GMT Item</td>
    <td width="100px">Name</td>
    <td width="100px">Code</td>
    <td width="100px">Sequence</td>
    <td width="100px">Name</td>
    <td width="100px">Code</td>
    <td width="100px">Sequence</td>
    <td width="100px">Qty</td>
    </tr>
    <tbody>
     <?php 
	 $i=1;
	 $val=''; 
	 ?>
        @foreach($colors as $color)
          @foreach($sizes as $size)
            @if(isset($matrixvalue[$color->style_gmt_id ][$color->style_color_id][$size->style_size_id]))
            <?php $val=$matrixvalue[$color->style_gmt_id][$color->style_color_id][$size->style_size_id];?>
            @else
            <?php $val='';?>
            @endif
            <tr>
                    <td width="100px" align="center">
                    {{ $color->item_description }}
                    <input type="hidden" name="style_gmt_id[{{ $i }}]" id="qty_{{ $i }}"  value="{{ $color->style_gmt_id }}"/>
                    </td>
                    
                    <td width="100px" align="center">
                    {{ $color->name }}
                     <input type="hidden" name="style_color_id[{{ $i }}]" id="style_color_id_{{ $i }}"  value="{{ $color->style_color_id }}"/>
                    </td>
                    
                     <td width="100px" align="center">{{ $color->code }}</td>
                     <td width="100px" align="center">{{ $color->sort_id }}</td>
                    <td width="100px" align="center">
                    {{ $size->name }}
                     <input type="hidden" name="style_size_id[{{ $i }}]" id="style_size_id_{{ $i }}"  value="{{ $size->style_size_id  }}"/>
                    </td>
                   
                    <td width="100px" align="center">{{ $size->code }}</td>
                     <td width="100px" align="center">{{ $size->sort_id }}</td>
                    <td width="100px"><input type="text" name="qty[{{ $i }}]" id="qty_{{ $i }}" class="number integer" onChange="MsStylePkgRatio.copyQty(this.value,{{ $i }},{{ $loop->parent->count*$loop->count}})" value="{{ $val }}"/></td>
            </tr>
			<?php 
            $i++;
            ?>
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