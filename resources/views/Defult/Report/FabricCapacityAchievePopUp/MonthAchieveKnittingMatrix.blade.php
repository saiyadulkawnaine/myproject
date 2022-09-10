<?php
$summery_array=array();
$i=0;
?>
@foreach($data as $produced_company_id=>$rows)
<?php
$company_name=$produced_company_id?$company[$produced_company_id]:"Sub-Contract";
?>
<strong> {{$company_name}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong>
<table border="1" cellspacing="0" cellpadding="2">
    <tr align="center" style="background-color: #EAE9E9">
            <th align="center" class="text-center">1</th>
            <th align="center" class="text-center">2</th>
            <th align="center" class="text-center">3</th>
            <th align="center" class="text-center">4</th>
            <th align="center" class="text-center">5</th>
            <th align="center" class="text-center">6</th>
            <th align="center" class="text-center">7</th>
            <th align="center" class="text-center">8</th>
            <th align="center" class="text-center">9</th>
            <th align="center" class="text-center">10</th>
            <th align="center" class="text-center">11</th>
            <th align="center" class="text-center">12</th>
            <th align="center" class="text-center">13</th>
            <th align="center" class="text-center">14</th>
            <th align="center" class="text-center">15</th>
    </tr>
    <tr style="background-color: #EAE9E9">
        <td align="center" width="20px">SL</td>
        <td align="center" width="100px">Beneficiary</td>
        <td align="center" width="100px">Buyer</td>
        <td align="center" width="100px">Style Ref</td>
        <td align="center" width="100px">Order No</td>
        <td align="center" width="100px">Image</td>
        <td align="center" width="100px">Req.Qty</td>
        <td align="center" width="100px">In-house<br/>This Month</td>
        <td align="center" width="100px">In-house<br/>Last Month</td>
        <td align="center" width="100px">In-house<br/>Total</td>
        <td align="center" width="100px">Outside <br/>This Month</td>
        <td align="center" width="100px">Outside <br/>Last Month</td>
        <td align="center" width="100px">Outside<br/>Total</td>
        <td align="center" width="100px">Total<br/>Knitted</td>
        <td align="center" width="100px">Yet to<br/>Knit</td>    
    </tr>
    <?php
    $total_inhouse_this_month=0;
    $total_inhouse_lastmonth_roll_weight=0;
    $total_total_inhouse=0;
    $total_subcon_this_month=0;
    $total_subcon_lastmonth_roll_weight=0;
    $total_sub_con=0;
    $total_knit=0;
    ?>

    @foreach($rows as $row)
    <?php
    $inhouse=$row->inhouse_this_month+$row->inhouse_lastmonth_roll_weight;
    $subcon=$row->subcon_this_month+$row->subcon_lastmonth_roll_weight;
    $kint=$inhouse+$subcon;

    $total_inhouse_this_month+=$row->inhouse_this_month;
    $total_inhouse_lastmonth_roll_weight+=$row->inhouse_lastmonth_roll_weight;
    $total_total_inhouse+=$inhouse;
    $total_subcon_this_month+=$row->inhouse_this_month;
    $total_subcon_lastmonth_roll_weight+=$row->subcon_lastmonth_roll_weight;
    $total_sub_con+=$subcon;
    $total_knit+=$kint;
    $summery_array[$row->produced_company_id][]=$kint;
    $i++;
    ?>
    <tr>
        <td align="center" width="20px">{{$i}}</td>
        <td align="center" width="100px">{{$row->b_company_name}}</td>
        <td align="center" width="100px">{{$row->buyer_name}}</td>
        <td align="center" width="100px">{{$row->style_ref}}</td>
        <td align="center" width="100px">{{$row->sale_order_no}}</td>
        <td align="center" width="100px"> <img src="<?php echo url('/')."/images/".$row->flie_src?>" width="25" height="25" onClick="MsProdFabricCapacityAchievement.imageWindow('{{$row->flie_src}}')" /></td>
        <td align="center" width="100px"></td>
        <td width="100px" align="right">{{number_format($row->inhouse_this_month,0)}}</td>
        <td width="100px" align="right">{{number_format($row->inhouse_lastmonth_roll_weight,0)}}</td>
        <td width="100px" align="right">{{number_format($inhouse,0)}}</td>
        <td width="100px" align="right">{{number_format($row->subcon_this_month,0)}}</td>
        <td width="100px" align="right">{{number_format($row->subcon_lastmonth_roll_weight,0)}}</td>
        <td width="100px" align="right">{{number_format($subcon,0)}}</td>
        <td width="100px" align="right">{{number_format($kint,0)}}</td>
        <td width="100px" align="right"></td>
    </tr>
    @endforeach
    <tr style="background-color: #3c8b3c; color: #FFFFFF">
        <td align="right" width="20px" colspan="6">Total</td>
        
        <td align="center" width="100px"></td>

        <td width="100px" align="right">{{number_format($total_inhouse_this_month,0)}}</td>
        <td width="100px" align="right">{{number_format($total_inhouse_lastmonth_roll_weight,0)}}</td>
        <td width="100px" align="right">{{number_format($total_total_inhouse,0)}}</td>
        <td width="100px" align="right">{{number_format($total_subcon_this_month,0)}}</td>
        <td width="100px" align="right">{{number_format($total_subcon_lastmonth_roll_weight,0)}}</td>
        <td width="100px" align="right">{{number_format($total_sub_con,0)}}</td>
        <td width="100px" align="right">{{number_format($total_knit,0)}}</td>
        <td width="100px" align="right"></td>
    </tr>
</table>
@endforeach

<strong> Knitting Summary &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong>
<table border="1" cellspacing="0" cellpadding="4">
    <tr align="center" style="background-color: #EAE9E9">
        <td class="text-center">1</td>
        <td class="text-center">2</td>
        <td class="text-center">3</td>
        <td class="text-center">4</td>
        <td class="text-center">5</td>
    </tr>
    <tr align="center" style="background-color: #EAE9E9">
        <td>SL</td>
        <td>Sewing Company</td>
        <td>Grey Required</td>
        <td>Knitted</td>
        <td>Yet to<br/>Knit</td>
    </tr>
    <?php 
    $sum_kint_total=0;
    $sum_i=0;
    ?>
    @foreach($summery_array as $company_id=>$value)
    <?php
    $sum_knit=array_sum($value);
    $sum_kint_total+=$sum_knit;
    $sum_i++;
    $sum_company_name=$company_id?$company[$company_id]:"Sub-Contract";
    ?>
    <tr>
        <td  class="text-center"ss>{{$sum_i}}</td>
        <td>{{ $sum_company_name }}</td>
        <td></td>
        <td align="right">{{number_format($sum_knit,0)}}</td>
        <td></td>
    </tr>
    @endforeach

    <tr style="background-color: #3c8b3c; color: #FFFFFF">
        <td colspan="2">Total</td>
        <td></td>
        <td>{{number_format($sum_kint_total,0)}}</td>
        <td></td>
    </tr>
</table>