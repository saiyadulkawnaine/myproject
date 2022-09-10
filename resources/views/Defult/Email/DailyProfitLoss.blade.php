<!DOCTYPE html>
<html>
<head>
<title>Daily Profit/Loss</title>
</head>
<body>

<table  border="1" style="margin: 0 auto;">
    <caption style="font-weight: bold;font-size: 24px">Garments Profit/ Loss Of Day {{date('d-M-Y',strtotime("-1 days"))}}</caption>
<thead>
<tr style="background-color: #ccc">

<th width="200" class="text-center">Particulars</th>
@foreach ($rows['companies'] as $key=>$value)
<th width="100" class="text-center">{{$value}}</th>
@endforeach
<th width="100" class="text-center">Knitting</th>
<th width="100" class="text-center">Dyeing</th>
<th width="100" class="text-center">AOP</th>
<th width="100" class="text-center">Total</th>

</tr>
</thead>
<body>

<tr>
<td align="left">Revenue</td>
<?php
$tot_day_amount=0;
?>
@foreach ($rows['companies'] as $key=>$value)
<?php
$day_sew_cm=$rows['sew_data_array']['day_cm'][$key];
$tot_day_amount+=$day_sew_cm;
?>
<td align="right">{{number_format($day_sew_cm,0)}}</td>
@endforeach
<?php
$day_knit_amount=$rows['knit_data_array']['amount'];
$day_dyeing_amount=$rows['dyeing_data_array']['amount'];
$day_aop_amount=$rows['aop_data_array']['amount'];
$tot_day_amount=$tot_day_amount+$day_knit_amount+$day_dyeing_amount+$day_aop_amount;
?>
<td width="100" align="right">{{number_format($day_knit_amount,0)}}</td>
<td width="100" align="right">{{number_format($day_dyeing_amount,0)}}</td>
<td width="100" align="right">{{number_format($day_aop_amount,0)}}</td>
<td align="right">{{number_format($tot_day_amount,0)}}</td>

</tr>
<tr>

<td align="left">Raw Material Cost</td>
@foreach ($rows['companies'] as $key=>$value)
<td align="right">0</td>
@endforeach
<?php
//$day_knit_ve=$rows['knit_data_array']['day_mtr'];
$day_dyeing_mtr=$rows['dyeing_data_array']['day_mtr'];
$day_aop_mtr=$rows['aop_data_array']['day_mtr'];
$tot_day_mtr=$day_dyeing_mtr+$day_aop_mtr;
?>
<td align="right">0</td>
<td align="right">{{number_format($day_dyeing_mtr,0)}}</td>
<td align="right">{{number_format($day_aop_mtr,0)}}</td>
<td align="right">{{number_format($tot_day_mtr,0)}}</td>

</tr>
<tr>

<td align="left">Variable Expenses</td>
<?php
$tot_day_ve=0;
?>
@foreach ($rows['companies'] as $key=>$value)
<?php
$day_sew_ve=$rows['sew_data_array']['day_ve'][$key];
$tot_day_ve+=$day_sew_ve;
?>
<td align="right">{{number_format($day_sew_ve,0)}}</td>
@endforeach
<?php
$day_knit_ve=$rows['knit_data_array']['day_ve'];
$day_dyeing_ve=$rows['dyeing_data_array']['day_ve'];
$day_aop_ve=$rows['aop_data_array']['day_ve'];
$tot_day_ve=$tot_day_ve+$day_knit_ve+$day_dyeing_ve+$day_aop_ve;
?>
<td width="100" align="right">{{number_format($day_knit_ve,0)}}</td>
<td width="100" align="right">{{number_format($day_dyeing_ve,0)}}</td>
<td width="100" align="right">{{number_format($day_aop_ve,0)}}</td>
<td align="right">{{number_format($tot_day_ve,0)}}</td>
</tr>
<tr>

<td align="left">Contribution Margin</td>
<?php
$tot_day_cront_m=0;
?>
@foreach ($rows['companies'] as $key=>$value)
<?php
$day_sew_cront_m=$rows['sew_data_array']['day_cront_m'][$key];
$tot_day_cront_m+=$day_sew_cront_m;
?>
<td align="right">{{number_format($day_sew_cront_m,0)}}</td>
@endforeach
<?php
$day_knit_cront_m=$rows['knit_data_array']['day_cront_m'];
$day_dyeing_cront_m=$rows['dyeing_data_array']['day_cront_m'];
$day_aop_cront_m=$rows['aop_data_array']['day_cront_m'];
$tot_day_cront_m=$tot_day_amount-($tot_day_ve+$tot_day_mtr);
?>
<td width="100" align="right">{{number_format($day_knit_cront_m,0)}}</td>
<td width="100" align="right">{{number_format($day_dyeing_cront_m,0)}}</td>
<td width="100" align="right">{{number_format($day_aop_cront_m,0)}}</td>
<td align="right">{{number_format($tot_day_cront_m,0)}}</td>

</tr>
<tr>

<td align="left">Contribution Margin %</td>
@foreach ($rows['companies'] as $key=>$value)
<?php
$day_cront_m_per=$rows['sew_data_array']['day_cront_m_per'][$key];
?>
<td align="right">{{number_format($day_cront_m_per,0)}}</td>
@endforeach
<?php
$day_knit_cront_m_per=$rows['knit_data_array']['day_cront_m_per'];
$day_dyeing_cront_m_per=$rows['dyeing_data_array']['day_cront_m_per'];
$day_aop_cront_m_per=$rows['aop_data_array']['day_cront_m_per'];
$day_cront_m_per=($tot_day_ve/$tot_day_amount)*100;

?>
<td width="100" align="right">{{number_format($day_knit_cront_m_per,0)}}</td>
<td width="100" align="right">{{number_format($day_dyeing_cront_m_per,0)}}</td>
<td width="100" align="right">{{number_format($day_aop_cront_m_per,0)}}</td>
<td align="right">{{number_format($day_cront_m_per,0)}}</td>
</tr>
<tr>

<td align="left">Fixed Expenses</td>
<?php
$tot_day_fe=0;
?>
@foreach ($rows['companies'] as $key=>$value)
<?php
$day_sew_fe=$rows['sew_data_array']['day_fe'][$key];
$tot_day_fe+=$day_sew_fe;
?>
<td align="right">{{number_format($day_sew_fe,0)}}</td>
@endforeach
<?php
$day_knit_fe=$rows['knit_data_array']['day_fe'];
$day_dyeing_fe=$rows['dyeing_data_array']['day_fe'];
$day_aop_fe=$rows['aop_data_array']['day_fe'];
$tot_day_fe=$tot_day_fe+$day_knit_fe+$day_dyeing_fe+$day_aop_fe;
?>
<td width="100" align="right">{{number_format($day_knit_fe,0)}}</td>
<td width="100" align="right">{{number_format($day_dyeing_fe,0)}}</td>
<td width="100" align="right">{{number_format($day_aop_fe,0)}}</td>
<td align="right">{{number_format($tot_day_fe,0)}}</td>
</tr>
<tr>

<td align="left"> Profit & Loss</td>
<?php
$tot_day_pl=0;
?>
@foreach ($rows['companies'] as $key=>$value)
<?php
$day_sew_pl=$rows['sew_data_array']['day_pl'][$key];
$tot_day_pl+=$day_sew_pl;
?>
<td align="right">{{number_format($day_sew_pl,0)}}</td>
@endforeach
<?php
$day_knit_pl=$rows['knit_data_array']['day_pl'];
$day_dyeing_pl=$rows['dyeing_data_array']['day_pl'];
$day_aop_pl=$rows['aop_data_array']['day_pl'];
$tot_day_pl=$tot_day_cront_m-$tot_day_fe;
?>
<td width="100" align="right">{{number_format($day_knit_pl,0)}}</td>
<td width="100" align="right">{{number_format($day_dyeing_pl,0)}}</td>
<td width="100" align="right">{{number_format($day_aop_pl,0)}}</td>
<td align="right">{{number_format($tot_day_pl,0)}}</td>
</tr>

</body>
<tfoot>
</tfoot>
</table>

</body>
</html> 