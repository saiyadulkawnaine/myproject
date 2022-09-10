<!DOCTYPE html>
<html>
<head>
<title>MRI Report</title>
</head>
<body>

<table  border="1" style="margin: 0 auto;">
  
<thead>
<tr style="background-color: #ccc">
<th width="20" class="text-center">#</th>
<th width="100" class="text-center">Company</th>
<th width="100" class="text-center">Team Leader</th>
<th width="100" class="text-center">Buyer</th>
<th width="100" class="text-center">Style No</th>
<th width="100" class="text-center">Order No</th>

<th width="100" class="text-center">Season</th>
<th width="100" class="text-center">Prod. Dept</th>
<th width="100" class="text-center">Ship Date</th>
<th width="100" class="text-center">Lead Days</th>

<th width="100" class="text-center">Order Qty (Pcs)</th>
<th width="100" class="text-center">Rate</th>
<th width="100" class="text-center">Amount</th>
</tr>
</thead>
<body>
<?php
$i=1;
$qty=0;
$amount=0;

?>
@foreach ($rows as $row)
<?php

$qty+=$row->qty;
$amount+=$row->amount;

?>
<tr>
<td>{{$i}}</td>
<td align="center">{{$row->company_code}}</td>
<td align="center">{{$row->team_name}}</td>
<td align="center">{{$row->buyer_name}}</td>
<td align="center">{{$row->style_ref}}</td>
<td align="center">{{$row->sale_order_no}}</td>

<td align="center">{{$row->season_name}}</td>
<td align="center">{{$row->department_name}}</td>
<td align="center">{{$row->ship_date}}</td>
<td align="center">{{$row->lead_days}}</td>

<td align="center">{{number_format($row->qty,2)}}</td>
<td align="center">{{number_format($row->rate,2)}}</td>
<td align="center">{{number_format($row->amount,2)}}</td>

</tr>
<?php
$i++;
?>
@endforeach
</body>
<tfoot>
<tr style="background-color: #ccc">
<td></td>
<td align="center"></td>
<td align="center"></td>
<td align="center"></td>
<td align="center"></td>
<td align="center"></td>

<td align="center"></td>
<td align="center"></td>
<td align="center"></td>
<td align="center"></td>

<td align="center">{{number_format($qty,0)}} Pcs</td>
<td align="center"></td>
<td align="center">${{number_format($amount,0)}}</td>

</tr>
</tfoot>
</table>
</body>
</html> 