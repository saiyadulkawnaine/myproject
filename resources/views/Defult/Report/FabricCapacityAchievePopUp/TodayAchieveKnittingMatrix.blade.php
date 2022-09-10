<?php
$summery_inhouse_array=array();
$summery_subcon_array=array();
$i=0;
?>

@foreach($data as $supplier_id=>$value)
<?php
$total_qty=0;
?>
<strong> {{$supplier[$supplier_id]}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong>
<table border="1" cellspacing="0" cellpadding="2">
    <tr style="background-color: #EAE9E9">
        <th width="20px" class="text-center">1</th>
        <th width="100px" class="text-center">2</th>
        <th width="100px" class="text-center"> 3</th>
        <th width="100px" class="text-center">4</th>
        <th width="100px" class="text-center">5</th>
        <th width="100px" class="text-center">6</th>
        <th width="100px" class="text-center">7</th>
        <th width="100px" class="text-center">8</th>
        <th width="100px" class="text-center">9</th>
        <th width="100px" class="text-center">10</th>
        <th width="100px" class="text-center">11</th>
        <th width="50px"  class="text-center">12</th>
        <th width="50px"  class="text-center">13</th>
        <th width="60px"  class="text-center">14</th>
        <th width="80px"  class="text-center">15</th>
        <th width="100px" class="text-center">16</th>
        <th width="100px" class="text-center">17</th>
        <th width="100px" class="text-center">18</th>
        <th width="100px" class="text-center">19</th>
        
    </tr>
    <tr style="background-color: #EAE9E9">
        <th width="20px" class="text-center">SL</th>
        <th width="100px" class="text-center">Operator</th>
        <th width="100px" class="text-center"> M/C No</th>
        <th width="100px" class="text-center">M/C Dia x Gauge</th>
        <th width="100px" class="text-center">Beneficiary<br/>Company</th>
        <th width="100px" class="text-center">Production<br/>Company</th>
        <th width="100px" class="text-center">Buyer</th>
        <th width="100px" class="text-center">Style Ref</th>
        <th width="100px" class="text-center">Order No</th>
        <th width="100px" class="text-center">Image</th>
        <th width="100px" class="text-center">Fabrication</th>
        <th width="50px" class="text-center">GSM</th>
        <th width="50px" class="text-center">DIA</th>
        <th width="60px" class="text-center">S.Lenght</th>
        <th width="80px" class="text-center">Color Range</th>
        <th width="100px" class="text-center">Default<br/>Capacity</th>
        <th width="100px" class="text-center">Day <br/>Target</th>
        <th width="100px" class="text-center">Today <br/>Knitted</th>
        <th width="100px" class="text-center">Tgt <br/>Variance</th>
    </tr>
    @foreach($value as $shift_id=>$rows)
    <tr style="background-color: #EAE9E9">
        <th width="100px" colspan="19">{{$shiftname[$shift_id]}}</th>
        
    </tr>
    <?php
    $shift_total_qty=0;
    ?>
    @foreach($rows as $row)
    <?php
    $total_qty+=$row->roll_weight;
    $shift_total_qty+=$row->roll_weight;
    if($row->p_company_name)
    {
         $company_name=$row->p_company_name;
    }
    else
    {
       $company_name='Sub-Contract';
    }
   
    if($row->company_id)
    {
        $summery_inhouse_array[$company_name]['inhouse'][]=$row->roll_weight;
    }
    else 
    {
        $summery_inhouse_array[$company_name]['subcon'][]=$row->roll_weight;

    }
    $i++;
    ?>
    <tr>
        <td width="20px">{{$i}}</td>
        <td width="100px">{{$row->operator_name}}</td>
        <td width="100px" align="center">{{$row->custom_no}}</td>
        <td width="100px" align="center">{{$row->machine_dia}}X{{$row->machine_gg}}</td>
        <td width="100px">{{$row->b_company_name}}</td>
        <td width="100px">{{$row->p_company_name}}</td>
        <td width="100px">{{$row->buyer_name}}</td>
        <td width="100px">{{$row->style_ref}}</td>
        <td width="100px">{{$row->sale_order_no}}</td>
        <td width="100px" align="center">
            <img src="<?php echo url('/')."/images/".$row->flie_src?>" width="25" height="25" onClick="MsProdFabricCapacityAchievement.imageWindow('{{$row->flie_src}}')" />
        </td>
        <td width="100px">{{$desDropdown[$row->autoyarn_id]}}</td>
        <td width="50px">{{$row->gsm_weight}}</td>
        <td width="50px" align="center">{{$row->dia}}</td>
        <td width="60px" align="center">{{$row->stitch_length}}</td>
        <td width="80px">{{$row->colorrange_name}}</td>
        <td width="100px" align="right">{{$row->prod_capacity}}</td>
        <td width="100px"></td>
        <td width="100px" align="right">{{ number_format($row->roll_weight,0)}}</td>
        <td width="100px"></td>
    </tr>
    @endforeach
    <tr align="right" style="background-color: #EAE9E9">
        <td width="100px" colspan="17">Shift Total</td>
        <td width="100px" align="right">{{number_format($shift_total_qty,0)}}</td>
        <td width="100px"></td>
        
    </tr>
    @endforeach
    <tr align="right" style="background-color: #3c8b3c; color: #FFFFFF">
        <td width="20px" colspan="17">Total</td>
        
        <td width="100px">{{number_format($total_qty,0)}}</td>
        <td width="100px"></td>
    </tr>
</table>
@endforeach



<strong> Today Knitting Summery &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
<table border="1" cellspacing="0" cellpadding="2">
    <tr style="background-color: #EAE9E9">
        <th width="20px" align="center" class="text-center">SL</td>
        <th width="100px" align="center" class="text-center">Sewing<br/> Company</th>
        <th width="100px" align="center" class="text-center">In-house<br/>Knitting</th>
        <th width="100px" align="center" class="text-center">Outside<br/>Knitting</th>
        <th width="100px" align="center" class="text-center">Total <br/>Knitting</th>
    </tr>
    <?php
    $sum_i=0;
    $sum_inh_total=0;
    $sum_sub_total=0;
    $sum_total=0;
    ?>
    @foreach($summery_inhouse_array as $company_name=>$value)
    <?php
    $inhouse=isset($value['inhouse'])?array_sum($value['inhouse']):0;
    $subcon=isset($value['subcon'])?array_sum($value['subcon']):0;
    $rowtot=$inhouse+$subcon;

    $sum_inh_total+=$inhouse;
    $sum_sub_total+=$subcon;
    $sum_total+=$rowtot;
    $sum_i++;
    ?>
    <tr align="right">
        <td width="20px" align="center">{{$sum_i}}</td>
        <td width="100px" align="left">{{$company_name}}</td>
        <td width="100px" >{{number_format($inhouse,0)}}</td>
        <td width="100px" >{{number_format($subcon,0)}}</td>
        <td width="100px" >{{number_format($rowtot,0)}}</td>
    </tr>
   @endforeach 
   <tr align="right" style="background-color: #3c8b3c; color: #FFFFFF">
        <td width="20px" align="center"></td>
        <td width="100px" >Total</td>
        <td width="100px" >{{number_format($sum_inh_total,0)}}</td>
        <td width="100px" >{{number_format($sum_sub_total,0)}}</td>
        <td width="100px" >{{number_format($sum_total,0)}}</td>
    </tr>
</table>