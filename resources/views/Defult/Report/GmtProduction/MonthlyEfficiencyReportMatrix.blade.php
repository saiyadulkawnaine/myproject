<table  border="1" width="2650" style="margin: 0 auto;">
    <caption style="font-weight: bold;font-size: 24px">Efficiency %</caption>
<thead>
<tr style="background-color: #ccc">
<th width="60" class="text-center">Company</th>
@foreach ($dataDates as $dataDate)
<th width="80" class="text-center">{{$dataDate }}</th>
@endforeach
<th width="80" class="text-center">Total</th>
<th width="" class="text-center"></th>
</tr>
</thead>
<body>
@foreach($summary['sew_qty'] as $company_id=>$data )
<tr>
<td width="60" align="center" >{{$company[$company_id]}}</td>
@foreach ($dataDates as $dataDate)
<?php
$produced_mint=isset($summary['produced_mint'][$company_id][$dataDate])?$summary['produced_mint'][$company_id][$dataDate]:0;
$used_mint=isset($summary['used_mint'][$company_id][$dataDate])?$summary['used_mint'][$company_id][$dataDate]:0;
if($produced_mint && $used_mint ){
	$effiPer=($produced_mint/$used_mint)*100;
}
else{
	$effiPer=0;
}

?>
<td width="80" align="right">{{number_format($effiPer,0)}}%</td>
@endforeach
<?php
$d_produced_mint=array_sum($summary['produced_mint'][$company_id]);
$d_used_mint=array_sum($summary['used_mint'][$company_id]);
if($d_produced_mint && $d_used_mint ){
	$d_effiPer=($d_produced_mint/$d_used_mint)*100;
}
else{
	$d_effiPer=0;
}
?>
<td width="80" align="right">{{number_format($d_effiPer,0)}}%</td>
<td align="right"></td>

</tr>
@endforeach
<tr style="background-color: #ccc">
<td width="60" align="center" >Total</td>
@foreach ($dataDates as $dataDate)
<?php
$t_used_mint=isset($dataSum['used_mint'][$dataDate])?$dataSum['used_mint'][$dataDate]:0;
$t_produced_mint=isset($dataSum['produced_mint'][$dataDate])?$dataSum['produced_mint'][$dataDate]:0;
if($t_produced_mint && $t_used_mint ){
	$t_effiPer=($t_produced_mint/$t_used_mint)*100;
}
else{
	$t_effiPer=0;
}
?>
<td width="80" align="right">{{number_format($t_effiPer,0)}}%</td>
@endforeach
<?php
$gt_used_mint=array_sum($dataSum['used_mint']);
$gt_produced_mint=array_sum($dataSum['produced_mint']);
if($gt_produced_mint && $gt_used_mint ){
	$gt_effiPer=($gt_produced_mint/$gt_used_mint)*100;
}
else{
	$gt_effiPer=0;
}
?>
<td width="80" align="right">{{number_format($gt_effiPer)}}%</td>
<td align="right"></td>
</tr>
</body>
</table>

<table  border="1" width="2650" style="margin: 0 auto;">
    <caption style="font-weight: bold;font-size: 24px">Achievment %</caption>
<thead>
<tr style="background-color: #ccc">
<th width="60" class="text-center">Company</th>
@foreach ($dataDates as $dataDate)
<th width="80" class="text-center">{{$dataDate }}</th>
@endforeach
<th width="80" class="text-center">Total</th>
<th width="" class="text-center"></th>
</tr>
</thead>
<body>
@foreach($summary['sew_qty'] as $company_id=>$data )
<tr>
<td width="60" align="center" >{{$company[$company_id]}}</td>
@foreach ($dataDates as $dataDate)
<?php
$sew_qty=isset($data[$dataDate])?$data[$dataDate]:0;
$day_target=isset($summary['day_target'][$company_id][$dataDate])?$summary['day_target'][$company_id][$dataDate]:0;
if($day_target && $sew_qty){
	$achvPer=($sew_qty/$day_target)*100;
}
else{
	$achvPer=0;
}

?>
<td width="80" align="right">{{number_format($achvPer,0)}}%</td>
@endforeach
<?php
$d_sew_qty=array_sum($summary['sew_qty'][$company_id]);
$d_day_target=array_sum($summary['day_target'][$company_id]);
if($d_day_target && $d_sew_qty ){
	$d_achvPer=($d_sew_qty/$d_day_target)*100;
}
else{
	$d_achvPer=0;
}
?>
<td width="80" align="right">{{number_format($d_achvPer,0)}}%</td>
<td align="right"></td>

</tr>
@endforeach
<tr style="background-color: #ccc">
<td width="60" align="center" >Total</td>
@foreach ($dataDates as $dataDate)
<?php
$t_sew_qty=isset($dataSum['sew_qty'][$dataDate])?$dataSum['sew_qty'][$dataDate]:0;
$t_day_target=isset($dataSum['day_target'][$dataDate])?$dataSum['day_target'][$dataDate]:0;
if($t_day_target && $t_sew_qty ){
	$t_achvPer=($t_sew_qty/$t_day_target)*100;
}
else{
	$t_achvPer=0;
}
?>
<td width="80" align="right">{{number_format($t_achvPer,0)}}%</td>
@endforeach
<?php
$gt_sew_qty=array_sum($dataSum['sew_qty']);
$gt_day_target=array_sum($dataSum['day_target']);
if($gt_day_target && $gt_sew_qty ){
	$gt_achvPer=($gt_day_target/$gt_sew_qty)*100;
}
else{
	$gt_effiPer=0;
}
?>
<td width="80" align="right">{{number_format($gt_achvPer)}}%</td>
<td align="right"></td>
</tr>
</body>
</table>

<table  border="1" width="2650" style="margin: 0 auto;">
    <caption style="font-weight: bold;font-size: 24px">Allocated Minutes</caption>
<thead>
<tr style="background-color: #ccc">
<th width="60" class="text-center">Company</th>
@foreach ($dataDates as $dataDate)
<th width="80" class="text-center">{{$dataDate }}</th>
@endforeach
<th width="80" class="text-center">Total</th>
<th width="" class="text-center"></th>
</tr>
</thead>
<body>
@foreach($summary['used_mint'] as $company_id=>$data )
<tr>
<td width="60" align="center" >{{$company[$company_id]}}</td>
@foreach ($dataDates as $dataDate)
<?php
$used_mint=isset($data[$dataDate])?$data[$dataDate]:0;
?>
<td width="80" align="right">{{number_format($used_mint,0)}}</td>
@endforeach
<td width="80" align="right">{{number_format(array_sum($summary['used_mint'][$company_id]))}}</td>
<td align="right"></td>

</tr>
@endforeach
<tr style="background-color: #ccc">
<td width="60" align="center" >Total</td>
@foreach ($dataDates as $dataDate)
<?php
$t_used_mint=isset($dataSum['used_mint'][$dataDate])?$dataSum['used_mint'][$dataDate]:0;
?>
<td width="80" align="right">{{number_format($t_used_mint,0)}}</td>
@endforeach
<td width="80" align="right">{{number_format(array_sum($dataSum['used_mint']))}}</td>
<td align="right"></td>
</tr>
</body>
</table>

<table  border="1" width="2650" style="margin: 0 auto;">
    <caption style="font-weight: bold;font-size: 24px">Produced Minutes</caption>
<thead>
<tr style="background-color: #ccc">
<th width="60" class="text-center">Company</th>
@foreach ($dataDates as $dataDate)
<th width="80" class="text-center">{{$dataDate }}</th>
@endforeach
<th width="80" class="text-center">Total</th>
<th width="" class="text-center"></th>
</tr>
</thead>
<body>
@foreach($summary['produced_mint'] as $company_id=>$data )
<tr>
<td width="60" align="center" >{{$company[$company_id]}}</td>
@foreach ($dataDates as $dataDate)
<?php
$produced_mint=isset($data[$dataDate])?$data[$dataDate]:0;
?>
<td width="80" align="right">{{number_format($produced_mint,0)}}</td>
@endforeach
<td width="80" align="right">{{number_format(array_sum($summary['produced_mint'][$company_id]))}}</td>
<td align="right"></td>

</tr>
@endforeach
<tr style="background-color: #ccc">
<td width="60" align="center" >Total</td>
@foreach ($dataDates as $dataDate)
<?php
$t_produced_mint=isset($dataSum['produced_mint'][$dataDate])?$dataSum['produced_mint'][$dataDate]:0;
?>
<td width="80" align="right">{{number_format($t_produced_mint,0)}}</td>
@endforeach
<td width="80" align="right">{{number_format(array_sum($dataSum['produced_mint']))}}</td>
<td align="right"></td>
</tr>
</body>
</table>

<table  border="1" width="2650" style="margin: 0 auto;">
    <caption style="font-weight: bold;font-size: 24px">Sewing Qty</caption>
<thead>
<tr style="background-color: #ccc">
<th width="60" class="text-center">Company</th>
@foreach ($dataDates as $dataDate)
<th width="80" class="text-center">{{$dataDate }}</th>
@endforeach
<th width="80" class="text-center">Total</th>
<th width="" class="text-center"></th>
</tr>
</thead>
<body>
@foreach($summary['sew_qty'] as $company_id=>$data )
<tr>
<td width="60" align="center" >{{$company[$company_id]}}</td>
@foreach ($dataDates as $dataDate)
<?php
$sew_qty=isset($data[$dataDate])?$data[$dataDate]:0;
?>
<td width="80" align="right">{{number_format($sew_qty,0)}}</td>
@endforeach

<td width="80" align="right">{{number_format(array_sum($summary['sew_qty'][$company_id]))}}</td>
<td align="right"></td>

</tr>
@endforeach
<tr style="background-color: #ccc">
<td width="60" align="center" >Total</td>
@foreach ($dataDates as $dataDate)
<?php
$sew_qty=isset($dataSum['sew_qty'][$dataDate])?$dataSum['sew_qty'][$dataDate]:0;
?>
<td width="80" align="right">{{number_format($sew_qty,0)}}</td>
@endforeach
<td width="80" align="right">{{number_format(array_sum($dataSum['sew_qty']))}}</td>
<td align="right"></td>
</tr>
</body>
</table>

<table  border="1" width="2650" style="margin: 0 auto;">
    <caption style="font-weight: bold;font-size: 24px">Sewing FOB</caption>
<thead>
<tr style="background-color: #ccc">
<th width="60" class="text-center">Company</th>
@foreach ($dataDates as $dataDate)
<th width="80" class="text-center">{{$dataDate }}</th>
@endforeach
<th width="80" class="text-center">Total</th>
<th width="" class="text-center"></th>
</tr>
</thead>
<body>
@foreach($summary['prodused_fob'] as $company_id=>$data )
<tr>
<td width="60" align="center" >{{$company[$company_id]}}</td>
@foreach ($dataDates as $dataDate)
<?php
$prodused_fob=isset($data[$dataDate])?$data[$dataDate]:0;
?>
<td width="80" align="right">{{number_format($prodused_fob,0)}}</td>
@endforeach
<td width="80" align="right">{{number_format(array_sum($summary['prodused_fob'][$company_id]))}}</td>
<td align="right"></td>
</tr>
@endforeach
<tr style="background-color: #ccc">
<td width="60" align="center" >Total</td>
@foreach ($dataDates as $dataDate)
<?php
$prodused_fob=isset($dataSum['prodused_fob'][$dataDate])?$dataSum['prodused_fob'][$dataDate]:0;
?>
<td width="80" align="right">{{number_format($prodused_fob,0)}}</td>
@endforeach
<td width="80" align="right">{{number_format(array_sum($dataSum['prodused_fob']))}}</td>
<td align="right"></td>
</tr>
</body>
</table>
@if($user->id==1 || $user->id==3 || $user->id==21 || $user->id==122 || $user->id==3563 || $user->id==123 )

<table  border="1" width="2650" style="margin: 0 auto;">
    <caption style="font-weight: bold;font-size: 24px">Mkt. CM</caption>
<thead>
<tr style="background-color: #ccc">
<th width="60" class="text-center">Company</th>
@foreach ($dataDates as $dataDate)
<th width="80" class="text-center">{{$dataDate }}</th>
@endforeach
<th width="80" class="text-center">Total</th>
<th width="" class="text-center"></th>
</tr>
</thead>
<body>
@foreach($summary['mkt_cm'] as $company_id=>$data )
<tr>
<td width="60" align="center" >{{$company[$company_id]}}</td>
@foreach ($dataDates as $dataDate)
<?php
$mkt_cm=isset($data[$dataDate])?$data[$dataDate]:0;
?>
<td width="80" align="right">{{number_format($mkt_cm,0)}}</td>
@endforeach
<td width="80" align="right">{{number_format(array_sum($summary['mkt_cm'][$company_id]))}}</td>
<td align="right"></td>
</tr>
@endforeach
<tr style="background-color: #ccc">
<td width="60" align="center" >Total</td>
@foreach ($dataDates as $dataDate)
<?php
$mkt_cm=isset($dataSum['mkt_cm'][$dataDate])?$dataSum['mkt_cm'][$dataDate]:0;
?>
<td width="80" align="right">{{number_format($mkt_cm,0)}}</td>
@endforeach
<td width="80" align="right">{{number_format(array_sum($dataSum['mkt_cm']))}}</td>
<td align="right"></td>
</tr>
</body>
</table>

<table  border="1" width="2650" style="margin: 0 auto;">
    <caption style="font-weight: bold;font-size: 24px">Earned CM</caption>
<thead>
<tr style="background-color: #ccc">
<th width="60" class="text-center">Company</th>
@foreach ($dataDates as $dataDate)
<th width="80" class="text-center">{{$dataDate }}</th>
@endforeach
<th width="80" class="text-center">Total</th>
<th width="" class="text-center"></th>
</tr>
</thead>
<body>
@foreach($summary['cm_earned_usd'] as $company_id=>$data )
<tr>
<td width="60" align="center" >{{$company[$company_id]}}</td>
@foreach ($dataDates as $dataDate)
<?php
$cm_earned_usd=isset($data[$dataDate])?$data[$dataDate]:0;
?>
<td width="80" align="right">{{number_format($cm_earned_usd,0)}}</td>
@endforeach
<td width="80" align="right">{{number_format(array_sum($summary['cm_earned_usd'][$company_id]))}}</td>
<td align="right"></td>
</tr>
@endforeach
<tr style="background-color: #ccc">
<td width="60" align="center" >Total</td>
@foreach ($dataDates as $dataDate)
<?php
$cm_earned_usd=isset($dataSum['cm_earned_usd'][$dataDate])?$dataSum['cm_earned_usd'][$dataDate]:0;
?>
<td width="80" align="right">{{number_format($cm_earned_usd,0)}}</td>
@endforeach
<td width="80" align="right">{{number_format(array_sum($dataSum['cm_earned_usd']))}}</td>
<td align="right"></td>
</tr>
</body>
</table>
@endif




