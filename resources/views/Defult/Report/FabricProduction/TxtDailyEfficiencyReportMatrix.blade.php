<table  border="1" style="margin: 0 auto;">
    <caption style="font-weight: bold;font-size: 24px">Summary</caption>
<thead>
<tr style="background-color: #ccc">
<th width="30" class="text-center">#</th>
<th width="150" class="text-center">Revenue Line</th>
<th width="60" class="text-center">No. of M/C</th>
<th width="60" class="text-center">M/C Capacity</th>
<th width="80" class="text-center">Day Tgt</th>
<th width="60" class="text-center">Prod. Qty</th>

<th width="100" class="text-center">Allocated Minutes</th>
<th width="100" class="text-center">Produced Minutes</th>
<th width="60" class="text-center">Effi. %</th>
<th width="60" class="text-center">Achv.%</th>
<th width="150" class="text-center">HOD</th>
</tr>
</thead>
<body>
<tr>
<td align="center">1</td>
<td align="center">Knitting</td>
<td align="right">{{number_format($summary['knit']['no_of_mc'],0)}}</td>
<td align="right">{{number_format($summary['knit']['mc_capacity'],0)}}</td>
<td align="right">{{number_format($summary['knit']['day_target'],0)}}</td>
<td align="right">{{number_format($summary['knit']['prod_qty'],0)}}</td>

<td align="right">{{number_format($summary['knit']['used_mint'],0)}}</td>
<td align="right">{{number_format($summary['knit']['produced_mint'],0)}}</td>
<td align="right">{{number_format($summary['knit']['effi_per'],0)}}%</td>
<td align="right">{{number_format($summary['knit']['achv_per'],0)}}%</td>
<td>Jahid(DGM), IP: 213</td>
</tr>
<tr>
<td align="center">2</td>
<td align="center">Dyeing</td>
<td align="right">{{number_format($summary['dyeing']['no_of_mc'],0)}}</td>
<td align="right">{{number_format($summary['dyeing']['mc_capacity'],0)}}</td>
<td align="right">{{number_format($summary['dyeing']['day_target'],0)}}</td>
<td align="right">{{number_format($summary['dyeing']['prod_qty'],0)}}</td>

<td align="right">{{number_format($summary['dyeing']['used_mint'],0)}}</td>
<td align="right">{{number_format($summary['dyeing']['produced_mint'],0)}}</td>
<td align="right">{{number_format($summary['dyeing']['effi_per'],0)}}%</td>
<td align="right">{{number_format($summary['dyeing']['achv_per'],0)}}%</td>
<td>Habib, IP: 251</td>
</tr>
<tr>
<td align="center">3</td>
<td align="center">Dyeing Finishing</td>
<td align="right">{{number_format($summary['fabfin']['no_of_mc'],0)}}</td>
<td align="right">{{number_format($summary['fabfin']['mc_capacity'],0)}}</td>
<td align="right">{{number_format($summary['fabfin']['day_target'],0)}}</td>
<td align="right">{{number_format($summary['fabfin']['prod_qty'],0)}}</td>

<td align="right">{{number_format($summary['fabfin']['used_mint'],0)}}</td>
<td align="right">{{number_format($summary['fabfin']['produced_mint'],0)}}</td>
<td align="right">{{number_format($summary['fabfin']['effi_per'],0)}}%</td>
<td align="right">{{number_format($summary['fabfin']['achv_per'],0)}}%</td>
<td>Farid(GM), IP: 212</td>
</tr>
<tr>
<td align="center">4</td>
<td align="center">AOP</td>
<td align="right">{{number_format($summary['aop']['no_of_mc'],0)}}</td>
<td align="right">{{number_format($summary['aop']['mc_capacity'],0)}}</td>
<td align="right">{{number_format($summary['aop']['day_target'],0)}}</td>
<td align="right">{{number_format($summary['aop']['prod_qty'],0)}}</td>

<td align="right">{{number_format($summary['aop']['used_mint'],0)}}</td>
<td align="right">{{number_format($summary['aop']['produced_mint'],0)}}</td>
<td align="right">{{number_format($summary['aop']['effi_per'],0)}}%</td>
<td align="right">{{number_format($summary['aop']['achv_per'],0)}}%</td>
<td>Sumon(DGM), IP: 235</td>
</tr>
<tr>
<td align="center">5</td>
<td align="center">AOP Finishing</td>
<td align="right">{{number_format($summary['aopfin']['no_of_mc'],0)}}</td>
<td align="right">{{number_format($summary['aopfin']['mc_capacity'],0)}}</td>
<td align="right">{{number_format($summary['aopfin']['day_target'],0)}}</td>
<td align="right">{{number_format($summary['aopfin']['prod_qty'],0)}}</td>

<td align="right">{{number_format($summary['aopfin']['used_mint'],0)}}</td>
<td align="right">{{number_format($summary['aopfin']['produced_mint'],0)}}</td>
<td align="right">{{number_format($summary['aopfin']['effi_per'],0)}}%</td>
<td align="right">{{number_format($summary['aopfin']['achv_per'],0)}}%</td>
<td>Sumon(DGM), IP: 235</td>
</tr>
</body>
<tfoot>

</tfoot>
</table>
<br/>

<table  border="1" style="margin: 0 auto;">
    <caption style="font-weight: bold;font-size: 24px">Knitting</caption>
<thead>
<tr style="background-color: #ccc">
<th width="20" class="text-center">#</th>
<th width="60" class="text-center">M/C No</th>
<th width="100" class="text-center">Group</th>
<th width="100" class="text-center">Brand</th>
<th width="60" class="text-center">M/C Capacity</th>
<th width="80" class="text-center">Day Tgt</th>
<th width="60" class="text-center">Prod. Qty</th>
<th width="80" class="text-center">Allocated Minutes</th>
<th width="80" class="text-center">Produced Minutes</th>
<th width="60" class="text-center">Effi. %</th>
<th width="60" class="text-center">Achv.%</th>
</tr>
</thead>
<body>
<?php
$i=1;
?>
@foreach ($knitings as $kniting)
<tr>
<td>{{$i}}</td>
<td align="center">{{$kniting->custom_no}}</td>
<td align="center">{{$kniting->asset_group}}</td>
<td align="center">{{$kniting->brand}}</td>
<td align="right">{{number_format($kniting->mc_capacity,0)}}</td>
<td align="right">{{number_format($kniting->day_target,0)}}</td>
<td align="right">{{number_format($kniting->prod_qty,0)}}</td>
<td align="right">{{number_format($kniting->used_mint,0)}}</td>
<td align="right">{{number_format($kniting->produced_mint,0)}}</td>
<td align="right">{{number_format($kniting->effi_per,0)}}%</td>
<td align="right">{{number_format($kniting->achv_per,0)}}%</td>
</tr>
<?php
$i++;
?>
@endforeach
</body>
<tfoot>
<tr style="font-weight: bold; background-color: #ccc">
<td>#</td>
<td></td>
<td></td>
<td></td>
<td align="right">{{number_format($summary['knit']['mc_capacity'],0)}}</td>
<td align="right">{{number_format($summary['knit']['day_target'],0)}}</td>
<td align="right">{{number_format($summary['knit']['prod_qty'],0)}}</td>
<td align="right">{{number_format($summary['knit']['used_mint'],0)}}</td>
<td align="right">{{number_format($summary['knit']['produced_mint'],0)}}</td>
<td align="right">{{number_format($summary['knit']['effi_per'],0)}}%</td>
<td align="right">{{number_format($summary['knit']['achv_per'],0)}}%</td>
</tr>
</tfoot>
</table>
<br/>
<table  border="1" style="margin: 0 auto;">
    <caption style="font-weight: bold;font-size: 24px">Dyeing</caption>
<thead>
<tr style="background-color: #ccc">
<th width="20" class="text-center">#</th>
<th width="60" class="text-center">M/C No</th>
<th width="100" class="text-center">Group</th>
<th width="100" class="text-center">Brand</th>
<th width="60" class="text-center">M/C Capacity</th>
<th width="80" class="text-center">Day Tgt</th>
<th width="60" class="text-center">Prod. Qty</th>
<th width="80" class="text-center">Allocated Minutes</th>
<th width="80" class="text-center">Produced Minutes</th>
<th width="60" class="text-center">Effi. %</th>
<th width="60" class="text-center">Achv.%</th>
</tr>
</thead>
<body>
<?php
$i=1;
?>
@foreach ($dyeings as $dyeing)
<tr>
<td>{{$i}}</td>
<td align="center">{{$dyeing->custom_no}}</td>
<td align="center">{{$dyeing->asset_group}}</td>
<td align="center">{{$dyeing->brand}}</td>
<td align="right">{{number_format($dyeing->mc_capacity,0)}}</td>
<td align="right">{{number_format($dyeing->day_target,0)}}</td>
<td align="right">{{number_format($dyeing->prod_qty,0)}}</td>
<td align="right">{{number_format($dyeing->used_mint,0)}}</td>
<td align="right">{{number_format($dyeing->produced_mint,0)}}</td>
<td align="right">{{number_format($dyeing->effi_per,0)}}%</td>
<td align="right">{{number_format($dyeing->achv_per,0)}}%</td>
</tr>
<?php
$i++;
?>
@endforeach
</body>
<tfoot>
<tr style="font-weight: bold; background-color: #ccc">
<td>#</td>
<td></td>
<td></td>
<td></td>
<td align="right">{{number_format($summary['dyeing']['mc_capacity'],0)}}</td>
<td align="right">{{number_format($summary['dyeing']['day_target'],0)}}</td>
<td align="right">{{number_format($summary['dyeing']['prod_qty'],0)}}</td>
<td align="right">{{number_format($summary['dyeing']['used_mint'],0)}}</td>
<td align="right">{{number_format($summary['dyeing']['produced_mint'],0)}}</td>
<td align="right">{{number_format($summary['dyeing']['effi_per'],0)}}%</td>
<td align="right">{{number_format($summary['dyeing']['achv_per'],0)}}%</td>
</tr>
</tfoot>
</table>
<br/>
<table  border="1" style="margin: 0 auto;">
    <caption style="font-weight: bold;font-size: 24px">Dyeing Finishing</caption>
<thead>
<tr style="background-color: #ccc">
<th width="20" class="text-center">#</th>
<th width="60" class="text-center">M/C No</th>
<th width="100" class="text-center">Group</th>
<th width="100" class="text-center">Brand</th>
<th width="60" class="text-center">M/C Capacity</th>
<th width="80" class="text-center">Day Tgt</th>
<th width="60" class="text-center">Prod. Qty</th>
<th width="80" class="text-center">Allocated Minutes</th>
<th width="80" class="text-center">Produced Minutes</th>
<th width="60" class="text-center">Effi. %</th>
<th width="60" class="text-center">Achv.%</th>
</tr>
</thead>
<body>
<?php
$i=1;
?>
@foreach ($fabfins as $fabfin)
<tr>
<td>{{$i}}</td>
<td align="center">{{$fabfin->custom_no}}</td>
<td align="center">{{$fabfin->asset_group}}</td>
<td align="center">{{$fabfin->brand}}</td>
<td align="right">{{number_format($fabfin->mc_capacity,0)}}</td>
<td align="right">{{number_format($fabfin->day_target,0)}}</td>
<td align="right">{{number_format($fabfin->prod_qty,0)}}</td>
<td align="right">{{number_format($fabfin->used_mint,0)}}</td>
<td align="right">{{number_format($fabfin->produced_mint,0)}}</td>
<td align="right">{{number_format($fabfin->effi_per,0)}}%</td>
<td align="right">{{number_format($fabfin->achv_per,0)}}%</td>
</tr>
<?php
$i++;
?>
@endforeach
</body>
<tfoot>
<tr style="font-weight: bold; background-color: #ccc">
<td>#</td>
<td></td>
<td></td>
<td></td>
<td align="right">{{number_format($summary['fabfin']['mc_capacity'],0)}}</td>
<td align="right">{{number_format($summary['fabfin']['day_target'],0)}}</td>
<td align="right">{{number_format($summary['fabfin']['prod_qty'],0)}}</td>
<td align="right">{{number_format($summary['fabfin']['used_mint'],0)}}</td>
<td align="right">{{number_format($summary['fabfin']['produced_mint'],0)}}</td>
<td align="right">{{number_format($summary['fabfin']['effi_per'],0)}}%</td>
<td align="right">{{number_format($summary['fabfin']['achv_per'],0)}}%</td>
</tr>
</tfoot>
</table>

<br/>
<table  border="1" style="margin: 0 auto;">
    <caption style="font-weight: bold;font-size: 24px">AOP</caption>
<thead>
<tr style="background-color: #ccc">
<th width="20" class="text-center">#</th>
<th width="60" class="text-center">M/C No</th>
<th width="100" class="text-center">Group</th>
<th width="100" class="text-center">Brand</th>
<th width="60" class="text-center">M/C Capacity</th>
<th width="80" class="text-center">Day Tgt</th>
<th width="60" class="text-center">Prod. Qty</th>
<th width="80" class="text-center">Allocated Minutes</th>
<th width="80" class="text-center">Produced Minutes</th>
<th width="60" class="text-center">Effi. %</th>
<th width="60" class="text-center">Achv.%</th>
</tr>
</thead>
<body>
<?php
$i=1;
?>
@foreach ($aops as $aop)
<tr>
<td>{{$i}}</td>
<td align="center">{{$aop->custom_no}}</td>
<td align="center">{{$aop->asset_group}}</td>
<td align="center">{{$aop->brand}}</td>
<td align="right">{{number_format($aop->mc_capacity,0)}}</td>
<td align="right">{{number_format($aop->day_target,0)}}</td>
<td align="right">{{number_format($aop->prod_qty,0)}}</td>
<td align="right">{{number_format($aop->used_mint,0)}}</td>
<td align="right">{{number_format($aop->produced_mint,0)}}</td>
<td align="right">{{number_format($aop->effi_per,0)}}%</td>
<td align="right">{{number_format($aop->achv_per,0)}}%</td>
</tr>
<?php
$i++;
?>
@endforeach
</body>
<tfoot>
<tr style="font-weight: bold; background-color: #ccc">
<td>#</td>
<td></td>
<td></td>
<td></td>
<td align="right">{{number_format($summary['aop']['mc_capacity'],0)}}</td>
<td align="right">{{number_format($summary['aop']['day_target'],0)}}</td>
<td align="right">{{number_format($summary['aop']['prod_qty'],0)}}</td>
<td align="right">{{number_format($summary['aop']['used_mint'],0)}}</td>
<td align="right">{{number_format($summary['aop']['produced_mint'],0)}}</td>
<td align="right">{{number_format($summary['aop']['effi_per'],0)}}%</td>
<td align="right">{{number_format($summary['aop']['achv_per'],0)}}%</td>
</tr>
</tfoot>
</table>

<br/>
<table  border="1" style="margin: 0 auto;">
    <caption style="font-weight: bold;font-size: 24px">AOP Finishing</caption>
<thead>
<tr style="background-color: #ccc">
<th width="20" class="text-center">#</th>
<th width="60" class="text-center">M/C No</th>
<th width="100" class="text-center">Group</th>
<th width="100" class="text-center">Brand</th>
<th width="60" class="text-center">M/C Capacity</th>
<th width="80" class="text-center">Day Tgt</th>
<th width="60" class="text-center">Prod. Qty</th>
<th width="80" class="text-center">Allocated Minutes</th>
<th width="80" class="text-center">Produced Minutes</th>
<th width="60" class="text-center">Effi. %</th>
<th width="60" class="text-center">Achv.%</th>
</tr>
</thead>
<body>
<?php
$i=1;
?>
@foreach ($aopfins as $aopfin)
<tr>
<td>{{$i}}</td>
<td align="center">{{$aopfin->custom_no}}</td>
<td align="center">{{$aopfin->asset_group}}</td>
<td align="center">{{$aopfin->brand}}</td>
<td align="right">{{number_format($aopfin->mc_capacity,0)}}</td>
<td align="right">{{number_format($aopfin->day_target,0)}}</td>
<td align="right">{{number_format($aopfin->prod_qty,0)}}</td>
<td align="right">{{number_format($aopfin->used_mint,0)}}</td>
<td align="right">{{number_format($aopfin->produced_mint,0)}}</td>
<td align="right">{{number_format($aopfin->effi_per,0)}}%</td>
<td align="right">{{number_format($aopfin->achv_per,0)}}%</td>
</tr>
<?php
$i++;
?>
@endforeach
</body>
<tfoot>
<tr style="font-weight: bold; background-color: #ccc">
<td>#</td>
<td></td>
<td></td>
<td></td>
<td align="right">{{number_format($summary['aopfin']['mc_capacity'],0)}}</td>
<td align="right">{{number_format($summary['aopfin']['day_target'],0)}}</td>
<td align="right">{{number_format($summary['aopfin']['prod_qty'],0)}}</td>
<td align="right">{{number_format($summary['aopfin']['used_mint'],0)}}</td>
<td align="right">{{number_format($summary['aopfin']['produced_mint'],0)}}</td>
<td align="right">{{number_format($summary['aopfin']['effi_per'],0)}}%</td>
<td align="right">{{number_format($summary['aopfin']['achv_per'],0)}}%</td>
</tr>
</tfoot>
</table>
