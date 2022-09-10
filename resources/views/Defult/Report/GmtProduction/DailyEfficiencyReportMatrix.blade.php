<table  border="1" style="margin: 0 auto;">
    <caption style="font-weight: bold;font-size: 24px">Summary</caption>
<thead>
<tr>
<th width="20" class="text-center">#</th>
<th width="60" class="text-center">Company</th>
<th width="60" class="text-center">Total Line</th>
<th width="60" class="text-center">No of Line</th>
<th width="80" class="text-center">MP Engaged</th>
<th width="60" class="text-center">MP/Line</th>
<th width="120" class="text-center">Allocated Minutes</th>
<th width="120" class="text-center">Produced Minutes</th>
<th width="120" class="text-center">Varience</th>
<th width="120" class="text-center">Day TGT</th>
<th width="120" class="text-center">Produced Qty</th>
<th width="120" class="text-center">Produced Amount</th>
@if($user->id==1 || $user->id==3 || $user->id==21 || $user->id==122 || $user->id==3563 || $user->id==123 )
<th width="120" class="text-center">Mkt. CM</th>
<th width="120" class="text-center">Earned CM</th>
<th width="120" class="text-center">CM Varience</th>
@endif
<th width="60" class="text-center">Achv %</th>
<th width="60" class="text-center">Effi %</th>
<th width="150" class="text-center">Allocated SMV/ Pcs</th>
<th width="150" class="text-center">Used SMV/ Pcs</th>
<th width="100" class="text-center">Varience/Pcs </th>

<th width="150" class="text-center">Contact Person</th>
</tr>
</thead>
<body>
<?php
$i=1;
$no_of_line=0;
$mp_engaged=0;
$mp_per_line=0;
$used_mint=0;
$produced_mint=0;
$day_target=0;
$sew_qty=0;
$prodused_fob=0;
$achv_per=0;
$effi_per=0;
$total_line=0;
$cm_earned_usd=0;
$mkt_cm=0;
?>
@foreach ($summary as $key=>$value)
<?php
$mpPerLine=$value['mp_engaged']/$value['no_of_line'];
$achvPer=($value['sew_qty']/$value['day_target'])*100;
$effiPer=($value['produced_mint']/$value['used_mint'])*100;
$allocatedSmvPcs=$value['produced_mint']/$value['sew_qty'];
$usedSmvPcs=$value['used_mint']/$value['sew_qty'];
$varSmvPcs=$allocatedSmvPcs-$usedSmvPcs;
$varCm=$value['cm_earned_usd']-$value['mkt_cm'];
if($varSmvPcs<0){
    $bgcolor="#ff0000";
}
else{
    $bgcolor="";
}

$mint_var=$value['produced_mint']-$value['used_mint'];
if($mint_var<0){
    $mbgcolor="#ff0000";
}
else{
    $mbgcolor="";
}
?>
<tr>
<td width="20" align="center" >{{$i}}</td>
<td width="60" align="center" >{{$company[$value['company_name']]}}</td>
<td width="60" align="right" >{{$value['total_line']}}</td>
<td width="60" align="right">{{$value['no_of_line']}}</td>
<td width="80" align="right">{{$value['mp_engaged']}}</td>
<td width="60" align="right">{{ number_format($mpPerLine,0)}}</td>
<td width="120" align="right">{{number_format($value['used_mint'],0)}}</td>
<td width="120" align="right">{{number_format($value['produced_mint'],0)}}</td>
<td width="120" align="right" style="background-color:{{$mbgcolor}} ">{{number_format($mint_var,0)}}</td>
<td width="120" align="right">{{number_format($value['day_target'],0)}}</td>
<td width="120" align="right">{{number_format($value['sew_qty'],0)}}</td>
<td width="120" align="right">{{number_format($value['prodused_fob'],0)}}</td>
@if($user->id==1 || $user->id==3 || $user->id==21 || $user->id==122 || $user->id==3563 || $user->id==123 )
<td width="120" align="right">{{number_format($value['mkt_cm'],0)}}</td>
<td width="120" align="right">{{number_format($value['cm_earned_usd'],0)}}</td>
<td width="120" align="right">{{number_format($varCm,0)}}</td>
@endif
<td width="60" align="center">{{number_format($achvPer,0)}} %</td>
<td width="60" align="center">{{number_format($effiPer,0)}} %</td>
<td width="60" align="center">{{number_format($allocatedSmvPcs,2)}} </td>
<td width="60" align="center">{{number_format($usedSmvPcs,2)}} </td>
<td width="60" align="center" style="background-color:{{$bgcolor}} ">{{number_format($varSmvPcs,2)}} </td>
<td width="150" align="left">{{$value['company_ceo']}} {{$value['company_contact']}}</td>
</tr>
<?php
$no_of_line+=$value['no_of_line'];
$mp_engaged+=$value['mp_engaged'];
$mp_per_line+=$mpPerLine;
$used_mint+=$value['used_mint'];
$produced_mint+=$value['produced_mint'];
$day_target+=$value['day_target'];
$sew_qty+=$value['sew_qty'];
$prodused_fob+=$value['prodused_fob'];
$achv_per+=$achvPer;
$effi_per+=$effiPer;
$total_line+=$value['total_line'];
$cm_earned_usd+=$value['cm_earned_usd'];
$mkt_cm+=$value['mkt_cm'];
$i++;
?>
@endforeach
<?php
$totallocatedSmvPcs=$produced_mint/$sew_qty;
$totusedSmvPcs=$used_mint/$sew_qty;
$totvarSmvPcs=$totallocatedSmvPcs-$totusedSmvPcs;
if($totvarSmvPcs<0){
    $tbgcolor="#ff0000";
}
else{
    $tbgcolor="";
}
$tmint_var=$produced_mint-$used_mint;
if($mint_var<0){
    $tmbgcolor="#ff0000";
}
else{
    $tmbgcolor="";
}
?>
</body>
<tfoot>
<tr style="font-weight: bold; background-color: #ccc">
<td width="20" class="text-center"></td>
<td width="60" class="text-center"></td>
<td width="60" align="right">{{number_format($total_line,0)}}</td>
<td width="60" align="right">{{number_format($no_of_line,0)}}</td>
<td width="80" align="right">{{number_format($mp_engaged,0)}}</td>
<td width="60" align="right">{{number_format($mp_per_line,0)}}</td>
<td width="120" align="right">{{number_format($used_mint,0)}}</td>
<td width="120" align="right">{{number_format($produced_mint,0)}}</td>
<td width="120" align="right" style="background-color:{{$tmbgcolor}} ">{{number_format($tmint_var,0)}}</td>
<td width="120" align="right">{{number_format($day_target,0)}}</td>
<td width="120" align="right">{{number_format($sew_qty,0)}}</td>
<td width="120" align="right">{{number_format($prodused_fob,0)}}</td>
@if($user->id==1 || $user->id==3 || $user->id==21 || $user->id==122 || $user->id==3563 || $user->id==123 )
<td width="120" align="right">{{number_format($mkt_cm,0)}}</td>
<td width="120" align="right">{{number_format($cm_earned_usd,0)}}</td>
<td width="120" align="right">{{number_format($cm_earned_usd-$mkt_cm,0)}}</td>
@endif
<td width="60" align="center">{{number_format(($sew_qty/$day_target)*100,0)}} %</td>
<td width="60" align="center">{{number_format(($produced_mint/$used_mint)*100,0)}} %</td>
<td width="60" align="center">{{number_format(($totallocatedSmvPcs),2)}} </td>
<td width="60" align="center">{{number_format(($totusedSmvPcs),2)}} </td>
<td width="60" align="center" style="background-color:{{$tbgcolor}} ">{{number_format(($totvarSmvPcs),2)}}</td>
<td width="150" align="center"></td>
</tr>
</tfoot>
</table>
<br/>
@foreach($dtldata as $company_id=>$rows)

<table id="prodgmtlinewisehourlyTbl" border="1" style="width:4000px">
    <caption style="font-weight: bold;font-size: 24px">{{$company[$company_id]}}</caption>
<thead>
<tr>
<th width="25" class="text-center">#</th>
<th width="200" class="text-center">Supervisor</th>
<th width="100" class="text-center">Line</th>
<th width="200" class="text-center">Buyer</th>
<th width="80" class="text-center">Order no.</th>
<th width="80" class="text-center">Running Day</th>
<th width="100" class="text-center" >GMT Item </th>
<th width="40" class="text-center">Used <br/>Opt.</th>
<th width="40" class="text-center">Used <br/>Hlp.</th>
<th width="40" class="text-center">TTL.<br/> Mnp  </th>
<th width="40" class="text-center" >WH </th>
<th width="60" class="text-center">Req.Tgt/Hr</th>
<th width="60" class="text-center">Tgt/Hr </th>

<th width="60" class="text-center">7am</th>
<th width="60" class="text-center">8am</th>
<th width="60" class="text-center">9am</th> 
<th width="60" class="text-center">10am</th>
<th width="60" class="text-center">11am</th>
<th width="60" class="text-center">12pm</th>
<th width="60" class="text-center">1pm</th>
<th width="60" class="text-center">2pm</th>
<th width="60" class="text-center">3pm</th>
<th width="60" class="text-center">4pm</th>
<th width="60" class="text-center">5pm</th>
<th width="60" class="text-center">6pm</th>
<th width="60" class="text-center">7pm</th>
<th width="60" class="text-center">8pm</th>
<th width="60" class="text-center">9pm</th>
<th width="60" class="text-center">10pm
<th width="60" class="text-center">11pm
<th width="60" class="text-center">12am</th>

<th width="80" class="text-center">Line.<br/> Achievement</th>
<th width="80" class="text-center">Day<br/> Target</th>
<th width="80" class="text-center">TGT<br/> Variance</th>
<th width="80" class="text-center">TGT<br/> Achieve %</th>



<th width="60" class="text-center">Sewing <br/>FOB Value</th>
@if($user->id==1 || $user->id==3 || $user->id==21 || $user->id==122 || $user->id==3563 || $user->id==123 )
<th width="60" class="text-center">Mkt. CM</th>
<th width="60" class="text-center">Earned CM</th>
<th width="60" class="text-center">CM Variance</th>
@endif
<th width="60" class="text-center">SMV</th>
<th width="60" class="text-center"> Used <br/> SMV/ Pcs</th>
<th width="60" class="text-center"> Allowed <br/> SMV/ Pcs</th>
<th width="50" class="text-center">Effi. %</th>  
<th width="80" class="text-center">Prod.<br/> Mnts. </th>  
<th width="100" class="text-center">Available Mnts.</th>
<th width="100" class="text-center">Adjusted Mnts.</th>
<th width="" class="text-center">Comments</th>    
</tr>
</thead>
<tbody>
<?php
$i=1;
$operator=0;
$helper=0;
$manpower=0;
$wh=0;
$actual_terget=0;
$target_per_hour=0;
$sew7am_qty=0;
$sew8am_qty=0;
$sew9am_qty=0;
$sew10am_qty=0;
$sew11am_qty=0;
$sew12pm_qty=0;
$sew1pm_qty=0;
$sew2pm_qty=0;
$sew3pm_qty=0;
$sew4pm_qty=0;
$sew5pm_qty=0;
$sew6pm_qty=0;
$sew7pm_qty=0;
$sew8pm_qty=0;
$sew9pm_qty=0;
$sew10pm_qty=0;
$sew11pm_qty=0;
$sew12am_qty=0;
$sew_qty=0;
$day_target=0;
$target_per_hour_var=0;
$target_per_hour_ach=0;
$capacity_qty=0;
$capacity_dev=0;
$capacity_ach=0;
$prodused_fob=0;
$smv_used=0;
$effi_per=0;
$produced_mint=0;
$used_mint=0;
$minute_addjust=0;
$cm_earned_usd=0;
$mkt_cm=0;

?>
@foreach($rows as $row)
<?php
$sew7am_color='';
if($row->sew7am_qty!==NULL && $row->sew7am_qty < $row->target_per_hour){
    $sew7am_color='#ff00004d';
}
$sew8am_color='';
if($row->sew8am_qty!==NULL && $row->sew8am_qty < $row->target_per_hour){
    $sew8am_color='#ff00004d';
}
$sew9am_color='';
if($row->sew9am_qty!==NULL && $row->sew9am_qty < $row->target_per_hour){
    $sew9am_color='#ff00004d';
}
$sew10am_color='';
if($row->sew10am_qty!==NULL && $row->sew10am_qty < $row->target_per_hour){
    $sew10am_color='#ff00004d';
}
$sew11am_color='';
if($row->sew11am_qty!==NULL && $row->sew11am_qty < $row->target_per_hour){
    $sew11am_color='#ff00004d';
}
$sew12pm_color='';
if($row->sew12pm_qty!==NULL && $row->sew12pm_qty < $row->target_per_hour){
    $sew12pm_color='#ff00004d';
}
$sew1pm_color='';
if($row->sew1pm_qty!==NULL && $row->sew1pm_qty < $row->target_per_hour){
    $sew1pm_color='#ff00004d';
}
$sew2pm_color='';
if($row->sew2pm_qty!==NULL && $row->sew2pm_qty < $row->target_per_hour){
    $sew2pm_color='#ff00004d';
}
$sew3pm_color='';
if($row->sew3pm_qty!==NULL && $row->sew3pm_qty < $row->target_per_hour){
    $sew3pm_color='#ff00004d';
}
$sew4pm_color='';
if($row->sew4pm_qty!==NULL && $row->sew4pm_qty < $row->target_per_hour){
    $sew4pm_color='#ff00004d';
}
$sew5pm_color='';
if($row->sew5pm_qty!==NULL && $row->sew5pm_qty < $row->target_per_hour){
    $sew5pm_color='#ff00004d';
}
$sew6pm_color='';
if($row->sew6pm_qty!==NULL && $row->sew6pm_qty < $row->target_per_hour){
    $sew6pm_color='#ff00004d';
}
$sew7pm_color='';
if($row->sew7pm_qty!==NULL && $row->sew7pm_qty < $row->target_per_hour){
    $sew7pm_color='#ff00004d';
}
$sew8pm_color='';
if($row->sew8pm_qty!==NULL && $row->sew8pm_qty < $row->target_per_hour){
    $sew8pm_color='#ff00004d';
}
$sew9pm_color='';
if($row->sew9pm_qty!==NULL && $row->sew9pm_qty < $row->target_per_hour){
    $sew9pm_color='#ff00004d';
}
$sew10pm_color='';
if($row->sew10pm_qty!==NULL && $row->sew10pm_qty < $row->target_per_hour){
    $sew10pm_color='#ff00004d';
}
$sew11pm_color='';
if($row->sew11pm_qty!==NULL && $row->sew11pm_qty < $row->target_per_hour){
    $sew11pm_color='#ff00004d';
}
$sew12am_color='';
if($row->sew12am_qty!==NULL && $row->sew12am_qty < $row->target_per_hour){
    $sew12am_color='#ff00004d';
}
?>
<tr>
<td width="25" align="center">{{$i}}</td>
<td width="200">{{$row->apm}}</td>
<td width="100">{{$row->line}}</td>
<td width="200">{{$row->buyer_code}}</td>
<td width="80">{{$row->sale_order_no}}</td>
<td width="80">{{$row->totday}}</td>
<td width="100" align="left"><a href="javascript:void(0)" onClick="MsDailyEfficiencyReport.imageWindow('{{$row->flie_src}}')">{{$row->item_description}}</a> </td>
<td width="40" align="right">{{$row->operator}}</td>
<td width="40" align="right">{{$row->helper}}</td>
<td width="40" align="right">{{$row->manpower}} </td>
<td width="40" align="right" >{{$row->wh}} </td>
<td width="60" align="right">{{number_format($row->actual_terget,0)}}</td>
<td width="60" align="right">{{$row->target_per_hour}} </td>

<td width="60" align="right" style="background-color:{{$sew7am_color}}"><a href="javascript:void(0)" onClick="MsDailyEfficiencyReport.detailWindow('7:00am',{{$row->id}},'{{$row->sew_qc_date}}')">{{$row->sew7am_qty}}</a></td>
<td width="60" align="right" style="background-color:{{$sew8am_color}}"><a href="javascript:void(0)" onClick="MsDailyEfficiencyReport.detailWindow('8:00am',{{$row->id}},'{{$row->sew_qc_date}}')">{{$row->sew8am_qty}}</a></td>
<td width="60" align="right" style="background-color:{{$sew9am_color}}"><a href="javascript:void(0)" onClick="MsDailyEfficiencyReport.detailWindow('9:00am',{{$row->id}},'{{$row->sew_qc_date}}')">{{$row->sew9am_qty}}</a></td> 
<td width="60" align="right" style="background-color:{{$sew10am_color}}"><a href="javascript:void(0)" onClick="MsDailyEfficiencyReport.detailWindow('10:00am',{{$row->id}},'{{$row->sew_qc_date}}')">{{$row->sew10am_qty}}</a></td>
<td width="60" align="right" style="background-color:{{$sew11am_color}}"><a href="javascript:void(0)" onClick="MsDailyEfficiencyReport.detailWindow('11:00am',{{$row->id}},'{{$row->sew_qc_date}}')">{{$row->sew11am_qty}}</a></td>
<td width="60" align="right" style="background-color:{{$sew12pm_color}}"><a href="javascript:void(0)" onClick="MsDailyEfficiencyReport.detailWindow('12:00pm',{{$row->id}},'{{$row->sew_qc_date}}')">{{$row->sew12pm_qty}}</a></td>
<td width="60" align="right" style="background-color:{{$sew1pm_color}}"><a href="javascript:void(0)" onClick="MsDailyEfficiencyReport.detailWindow('1:00pm',{{$row->id}},'{{$row->sew_qc_date}}')">{{$row->sew1pm_qty}}</a></td>
<td width="60" align="right" style="background-color:{{$sew2pm_color}}"><a href="javascript:void(0)" onClick="MsDailyEfficiencyReport.detailWindow('2:00pm',{{$row->id}},'{{$row->sew_qc_date}}')">{{$row->sew2pm_qty}}</a></td>
<td width="60" align="right" style="background-color:{{$sew3pm_color}}"><a href="javascript:void(0)" onClick="MsDailyEfficiencyReport.detailWindow('3:00pm',{{$row->id}},'{{$row->sew_qc_date}}')">{{$row->sew3pm_qty}}</a></td>
<td width="60" align="right" style="background-color:{{$sew4pm_color}}"><a href="javascript:void(0)" onClick="MsDailyEfficiencyReport.detailWindow('4:00pm',{{$row->id}},'{{$row->sew_qc_date}}')">{{$row->sew4pm_qty}}</a></td>
<td width="60" align="right" style="background-color:{{$sew5pm_color}}"><a href="javascript:void(0)" onClick="MsDailyEfficiencyReport.detailWindow('5:00pm',{{$row->id}},'{{$row->sew_qc_date}}')">{{$row->sew5pm_qty}}</a></td>
<td width="60" align="right" style="background-color:{{$sew6pm_color}}"><a href="javascript:void(0)" onClick="MsDailyEfficiencyReport.detailWindow('6:00pm',{{$row->id}},'{{$row->sew_qc_date}}')">{{$row->sew6pm_qty}}</a></td>
<td width="60" align="right" style="background-color:{{$sew7pm_color}}"><a href="javascript:void(0)" onClick="MsDailyEfficiencyReport.detailWindow('7:00pm',{{$row->id}},'{{$row->sew_qc_date}}')">{{$row->sew7pm_qty}}</a></td>
<td width="60" align="right" style="background-color:{{$sew8pm_color}}"><a href="javascript:void(0)" onClick="MsDailyEfficiencyReport.detailWindow('8:00pm',{{$row->id}},'{{$row->sew_qc_date}}')">{{$row->sew8pm_qty}}</a></td>
<td width="60" align="right" style="background-color:{{$sew9pm_color}}"><a href="javascript:void(0)" onClick="MsDailyEfficiencyReport.detailWindow('9:00pm',{{$row->id}},'{{$row->sew_qc_date}}')">{{$row->sew9pm_qty}}</a></td>
<td width="60" align="right" style="background-color:{{$sew10pm_color}}"><a href="javascript:void(0)" onClick="MsDailyEfficiencyReport.detailWindow('10:00pm',{{$row->id}},'{{$row->sew_qc_date}}')">{{$row->sew10pm_qty}}</a></td>
<td width="60" align="right" style="background-color:{{$sew11pm_color}}"><a href="javascript:void(0)" onClick="MsDailyEfficiencyReport.detailWindow('11:00pm',{{$row->id}},'{{$row->sew_qc_date}}')">{{$row->sew11pm_qty}}</a></td>
<td width="60" align="right" style="background-color:{{$sew12am_color}}"><a href="javascript:void(0)" onClick="MsDailyEfficiencyReport.detailWindow('12:00am',{{$row->id}},'{{$row->sew_qc_date}}')">{{$row->sew12am_qty}}</a></td>

<td width="80" align="right" ><a href="javascript:void(0)" onClick="MsDailyEfficiencyReport.detailWindow('',{{$row->id}},'{{$row->sew_qc_date}}')">{{number_format($row->sew_qty,0)}}</a></td>
<td width="80" align="right" >{{number_format($row->day_target,0)}}</td>
<td width="80" align="right" >{{number_format($row->target_per_hour_var,0)}}</td>
<td width="80" align="right" >{{number_format($row->target_per_hour_ach,2)}} %</td>


<td width="60" align="right">{{number_format($row->prodused_fob,0)}}</td>
@if($user->id==1 || $user->id==3 || $user->id==21 || $user->id==122 || $user->id==3563 || $user->id==123 )
<td width="60" align="right">{{number_format($row->mkt_cm,0)}}</td>
<td width="60" align="right">{{number_format($row->cm_earned_usd,0)}}</td>
<td width="60" align="right">{{number_format($row->cm_earned_usd-$row->mkt_cm,0)}}</td>
@endif
<td width="60" align="center">{{$row->smv}}</td>
<td width="60" align="right"> {{number_format($row->smv_used,2)}}</td>
<td width="60" align="right"> {{number_format($row->avg_smv_pcs,2)}}</td>
<td width="50" align="right">{{number_format($row->effi_per,2)}} %</td>  
<td width="80" align="right">{{number_format($row->produced_mint,0)}} </td>  
<td width="100" align="right">{{number_format($row->used_mint,0)}}</th>
<td width="100" align="right">{{number_format($row->minute_addjust,0)}}</th>
<td width="" align="left" title="{{$row->hourUpto}}">{{$row->remarks}}</td>    
</tr>
<?php
$operator+=$row->operator;
$helper+=$row->helper;
$manpower+=$row->manpower;
$wh+=$row->wh;
$actual_terget+=$row->actual_terget;
$target_per_hour+=$row->target_per_hour;
$sew7am_qty+=$row->sew7am_qty;
$sew8am_qty+=$row->sew8am_qty;
$sew9am_qty+=$row->sew9am_qty;
$sew10am_qty+=$row->sew10am_qty;
$sew11am_qty+=$row->sew11am_qty;
$sew12pm_qty+=$row->sew12pm_qty;
$sew1pm_qty+=$row->sew1pm_qty;
$sew2pm_qty+=$row->sew2pm_qty;
$sew3pm_qty+=$row->sew3pm_qty;
$sew4pm_qty+=$row->sew4pm_qty;
$sew5pm_qty+=$row->sew5pm_qty;
$sew6pm_qty+=$row->sew6pm_qty;
$sew7pm_qty+=$row->sew7pm_qty;
$sew8pm_qty+=$row->sew8pm_qty;
$sew9pm_qty+=$row->sew9pm_qty;
$sew10pm_qty+=$row->sew10pm_qty;
$sew11pm_qty+=$row->sew11pm_qty;
$sew12am_qty+=$row->sew12am_qty;
$sew_qty+=$row->sew_qty;
$day_target+=$row->day_target;
$target_per_hour_var+=$row->target_per_hour_var;
$target_per_hour_ach+=$row->target_per_hour_ach;
$capacity_qty+=$row->capacity_qty;
$capacity_dev+=$row->capacity_dev;
$capacity_ach+=$row->capacity_ach;
$prodused_fob+=$row->prodused_fob;
//$smv_used=$used_mint/$sew_qty;
$effi_per+=$row->effi_per;
$produced_mint+=$row->produced_mint;
$used_mint+=$row->used_mint;
$minute_addjust+=$row->minute_addjust;
$cm_earned_usd+=$row->cm_earned_usd;
$mkt_cm+=$row->mkt_cm;
$i++;
?>
@endforeach
</tbody>
<tfoot>
 <tr style="font-weight: bold; background-color: #ccc">
<td width="25" align="center"></td>
<td width="200"></td>
<td width="100"></td>
<td width="200"></td>
<td width="80"></td>
<td width="80"></td>
<td width="100" align="left" formatter="MsProdGmtLineWiseHourly.formatimage"></td>
<td width="40" align="right">{{$operator}}</td>
<td width="40" align="right">{{$helper}}</td>
<td width="40" align="right">{{$manpower}} </td>
<td width="40" align="right" >{{$wh}} </td>
<td width="60" align="right">{{number_format($actual_terget,0)}}</td>
<td width="60" align="right">{{$target_per_hour}} </td>

<td width="60" align="right">{{number_format($sew7am_qty,0)}}</td>
<td width="60" align="right">{{number_format($sew8am_qty,0)}}</td>
<td width="60" align="right">{{number_format($sew9am_qty,0)}}</td> 
<td width="60" align="right">{{number_format($sew10am_qty,0)}}</td>
<td width="60" align="right">{{number_format($sew11am_qty,0)}}</td>
<td width="60" align="right">{{number_format($sew12pm_qty,0)}}</td>
<td width="60" align="right">{{number_format($sew1pm_qty,0)}}</td>
<td width="60" align="right">{{number_format($sew2pm_qty,0)}}</td>
<td width="60" align="right">{{number_format($sew3pm_qty,0)}}</td>
<td width="60" align="right">{{number_format($sew4pm_qty,0)}}</td>
<td width="60" align="right">{{number_format($sew5pm_qty,0)}}</td>
<td width="60" align="right">{{number_format($sew6pm_qty,0)}}</td>
<td width="60" align="right">{{number_format($sew7pm_qty,0)}}</td>
<td width="60" align="right">{{number_format($sew8pm_qty,0)}}</td>
<td width="60" align="right">{{number_format($sew9pm_qty,0)}}</td>
<td width="60" align="right" >{{number_format($sew10pm_qty,0)}}</td>
<td width="60" align="right" >{{number_format($sew11pm_qty,0)}}</td>
<td width="60" align="right" >{{number_format($sew12am_qty,0)}}</td>

<td width="80" align="right" >{{number_format($sew_qty,0)}}</td>
<td width="80" align="right" >{{number_format($day_target,0)}}</td>
<td width="80" align="right" >{{number_format($target_per_hour_var,0)}}</td>
<td width="80" align="right" >{{number_format(($sew_qty/$day_target)*100,2)}} %</td>


<td width="60" align="right">{{number_format($prodused_fob,0)}}</td>
@if($user->id==1 || $user->id==3 || $user->id==21 || $user->id==122 || $user->id==3563 || $user->id==123 )
<td width="60" align="right">{{number_format($mkt_cm,0)}}</td>
<td width="60" align="right">{{number_format($cm_earned_usd,0)}}</td>
<td width="60" align="right">{{number_format($cm_earned_usd-$mkt_cm,0)}}</td>
@endif
<td width="60" align="center"></td>
<td width="60" align="right"> {{number_format($used_mint/$sew_qty,2)}}</td>
<td width="60" align="right"> {{number_format($produced_mint/$sew_qty,2)}}</td>
<td width="50" align="right">{{number_format(($produced_mint/$used_mint)*100,2)}} %</td>  
<td width="80" align="right">{{number_format($produced_mint,0)}} </td>  
<td width="100" align="right">{{number_format($used_mint,0)}}</th>
<td width="100" align="right">{{number_format($minute_addjust,0)}}</th>
<td width="" align="left"></td>    
</tr>   
</tfoot>
</table>
@endforeach