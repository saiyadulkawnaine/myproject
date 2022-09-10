<table border="1">
    <tr align="center">
    <td width="200px"></td>
    <td colspan="3">Color</td>
    <td colspan="3">Size</td>
    <td width="80px"><input type="checkbox" name="is_copy" id="is_copy" checked/>Copy</td>
    </tr>
    <tr align="center">
    <td width="250px">GMT Item</td>
    <td width="200px">Name</td>
    <td width="100px">Code</td>
    <td width="50px">Sequence</td>
    <td width="180px">Name</td>
    <td width="100px">Code</td>
    <td width="50px">Sequence</td>
    <td width="100px">Article No</td>
    <td width="70px">Qty</td>
    <td width="60px">Rate</td>
    <td width="80px">Amount</td>
    </tr>
    <tbody>
     <?php 
	 $i=1;
	 $val=''; 
	 ?>
        @foreach($colors as $color)
          @foreach($sizes as $size)
          @if (isset($matrix[$color->style_gmt_id][$color->style_color_id][$size->style_size_id]['article_no']))
                    <?php $article_no=$matrix[$color->style_gmt_id][$color->style_color_id][$size->style_size_id]['article_no'];?>
                    @else
                      <?php $article_no='';?>
                    @endif
                   @if (isset($matrix[$color->style_gmt_id][$color->style_color_id][$size->style_size_id]['qty']))
                    <?php $qty=$matrix[$color->style_gmt_id][$color->style_color_id][$size->style_size_id]['qty'];?>
                    @else
                      <?php $qty='';?>
                    @endif
                    @if (isset($matrix[$color->style_gmt_id][$color->style_color_id][$size->style_size_id]['rate']))
                    <?php $rate=$matrix[$color->style_gmt_id][$color->style_color_id][$size->style_size_id]['rate'];?>
                    @else
                      <?php $rate='';?>
                    @endif
                    @if (isset($matrix[$color->style_gmt_id][$color->style_color_id][$size->style_size_id]['amount']))
                    <?php $amount=$matrix[$color->style_gmt_id][$color->style_color_id][$size->style_size_id]['amount'];?>
                    @else
                      <?php $amount='';?>
                    @endif
            <tr>
                    <td width="250px" align="center">
                    {{ $color->item_description }}
                    <input type="hidden" name="style_gmt_id[{{ $i }}]" id="qty_{{ $i }}"  value="{{ $color->style_gmt_id }}"/>
                    </td>
                    
                    <td width="200px" align="center">
                    {{ $color->name }}
                     <input type="hidden" name="style_color_id[{{ $i }}]" id="style_color_id_{{ $i }}"  value="{{ $color->style_color_id }}"/>
                    </td>
                    
                     <td width="100px" align="center">{{ $color->code }}</td>
                     <td width="50px" align="center">{{ $color->sort_id }}</td>
                    <td width="180px" align="center">
                    {{ $size->name }}
                     <input type="hidden" name="style_size_id[{{ $i }}]" id="style_size_id_{{ $i }}"  value="{{ $size->style_size_id  }}"/>
                    </td>
                   
                    <td width="100px" align="center">{{ $size->code }}</td>
                     <td width="50px" align="center">{{ $size->sort_id }}</td>
                     <td width="100px"><input type="text" name="article_no[{{ $i }}]" id="article_no_{{ $i }}" value="{{ $article_no }}" /></td>
                    <td width="70px"><input type="text" name="qty[{{ $i }}]" id="qty_{{ $i }}" class="number integer" onChange="MsSalesOrderGmtColorSize.calculate({{ $i }},{{ $loop->parent->count*$loop->count}},'qty')" value="{{ $qty }}"/></td>
                    <td width="60px"><input type="text" name="rate[{{ $i }}]" id="rate_{{ $i }}" class="number integer" onChange="MsSalesOrderGmtColorSize.calculate({{ $i }},{{ $loop->parent->count*$loop->count}},'rate')" value="{{ $rate }}"/></td>
                    <td width="80px" style="background-color:#FFFFFF"><input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number integer" style="border:none" value="{{ $amount }}" /></td>
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