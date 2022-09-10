<table border="1">
    <tr align="center">
    <td width="200px"></td>
    <td width="100px" colspan="3">Color</td>
    <td width="100px" colspan="3">Size</td>
    <td width="100px"><input type="checkbox" name="is_copy" id="is_copy"/>Copy</td>
    </tr>
    <tr align="center">
    <td width="200px">GMT Item</td>
    <td width="150px">Name</td>
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
	 ?>
        @foreach($colorsizes as $colorsize)
            <tr>
                    <td width="200px">
                    {{ $colorsize->item_description }}
                    <input type="hidden" name="style_gmt_color_size_id[{{ $i }}]" id="style_gmt_color_size_id_{{ $i }}"  value="{{ $colorsize->style_gmt_color_size_id }}"/>
                    <input type="hidden" name="style_gmt_id[{{ $i }}]" id="style_gmt_id_{{ $i }}"  value="{{ $colorsize->style_gmt_id }}"/>
                    </td>
                    
                    <td width="150px">
                    {{ $colorsize->color_name }}
                     <input type="hidden" name="style_color_id[{{ $i }}]" id="style_color_id_{{ $i }}"  value="{{ $colorsize->style_color_id }}"/>
                    </td>
                     <td width="100px" align="center">{{ $colorsize->color_code }}</td>
                     <td width="100px" align="center">{{ $colorsize->color_sort_id }}</td>
                    <td width="100px">
                    {{ $colorsize->name }}
                     <input type="hidden" name="style_size_id[{{ $i }}]" id="style_size_id_{{ $i }}"  value="{{ $colorsize->style_size_id  }}"/>
                    </td>
                   
                    <td width="100px" align="center">{{ $colorsize->code }}</td>
                     <td width="100px" align="center">{{ $colorsize->sort_id }}</td>
                    <td width="100px"><input type="text" name="qty[{{ $i }}]" id="qty_{{ $i }}" class="number integer" onChange="MsStylePkgRatio.copyQty(this.value,{{ $i }},{{ $loop->count}})" value="{{ $colorsize->qty }}"/></td>
            </tr>
			<?php 
            $i++;
            ?>
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