<table border="1" style="border-style:dotted">
    <tr align="center">
    <td width="200px"></td>
    <td colspan="3">Color</td>
    <td colspan="3">Size</td>
    <td colspan="4"><input type="checkbox" name="is_copy" id="is_copy" checked/>Copy</td>
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
     $totqty=0;
     $totamount=0;
     $totrate=0;
     ?>
        @foreach($colorsizes as $colorsize)
            <tr>
                    <td width="250px">
                    {{ $colorsize->item_description }}
                     <input type="hidden" name="style_gmt_id[{{ $i }}]" id="style_gmt_id_{{ $i }}"  value="{{ $colorsize->style_gmt_id }}"/>
                    <input type="hidden" name="style_gmt_color_size_id[{{ $i }}]" id="style_gmt_color_size_id_{{ $i }}"  value="{{ $colorsize->style_gmt_color_size_id }}"/>
                    </td>
                    
                    <td width="200px">
                    {{ $colorsize->color_name }}
                     <input type="hidden" name="style_color_id[{{ $i }}]" id="style_color_id_{{ $i }}"  value="{{ $colorsize->style_color_id }}"/>
                    </td>
                    
                     <td width="100px" align="center">{{ $colorsize->color_code }}</td>
                     <td width="50px" align="center">{{ $colorsize->color_sort_id }}</td>
                    <td width="180px" >
                    {{ $colorsize->name }}
                     <input type="hidden" name="style_size_id[{{ $i }}]" id="style_size_id_{{ $i }}"  value="{{ $colorsize->style_size_id  }}"/>
                    </td>
                   
                    <td width="100px" align="center">{{ $colorsize->code }}</td>
                     <td width="50px" align="center">{{ $colorsize->sort_id }}</td>
                     <td width="100px"><input type="text" name="article_no[{{ $i }}]" id="article_no_{{ $i }}" value="{{ $colorsize->article_no }}" style="background-color:#FFFFFF;border:none" /></td>
                    <td width="70px"><input type="text" name="qty[{{ $i }}]" id="qty_{{ $i }}" class="number integer" onChange="MsSalesOrderGmtColorSize.calculate({{ $i }},{{ $loop->count}},'qty')" value="{{ $colorsize->qty }}" style="background-color:#FFFFFF;border:none"/></td>
                    <td width="60px"><input type="text" name="rate[{{ $i }}]" id="rate_{{ $i }}" class="number integer" onChange="MsSalesOrderGmtColorSize.calculate({{ $i }},{{ $loop->count}},'rate')" value="{{ $colorsize->rate }}" style="background-color:#FFFFFF;border:none"/></td>
                    <td width="80px" style="background-color:#FFFFFF"><input type="text" name="amount[{{ $i }}]" id="amount_{{ $i }}" class="number integer" value="{{ $colorsize->amount }}" style="background-color:#FFFFFF;border:none"/></td>
            </tr>
            <?php
            $totqty+=$colorsize->qty ;
            $totamount+=$colorsize->amount; 
            if ($totqty) {
                $totrate=$totamount/$totqty;
            }
            
            $i++;
            ?>
        @endforeach
    </tbody>
    <tr align="center" style="background-color: #449d44; color: #FFFFFF; font-weight: bold;">
    <td width="250px"></td>
    <td width="200px"></td>
    <td width="100px"></td>
    <td width="50px"></td>
    <td width="180px"></td>
    <td width="100px"></td>
    <td width="50px"></td>
    <td width="100px"> Total</td>
    <td width="70px" align="right">{{number_format($totqty,0)}}</td>
    <td width="60px" align="right">{{number_format($totrate,2)}}</td>
    <td width="80px" align="right">{{number_format($totamount,2)}}</td>
    </tr>
</table>
<script>
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>