<!DOCTYPE html>
<html>
<head>
<title>Shipment Pending</title>
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
<th width="100" class="text-center">Delay Days</th>
<th width="100" class="text-center">Order Qty (Pcs)</th>
<th width="100" class="text-center">Rate</th>
<th width="100" class="text-center">Amount</th>
<th width="100" class="text-center">Ship Qty</th>
<th width="100" class="text-center">Ship Balance</th>
<th width="100" class="text-center">Remarks</th>


	
</th>
</tr>
</thead>
<body>
<?php
$i=1;
$qty=0;
$amount=0;
$ship_qty=0;
$ship_balance=0;
?>
@foreach ($rows as $row)
<?php
if($row->delay_days>=1){
	$bgcolor='red';
}else{
	$bgcolor='green';
}
$qty+=$row->qty;
$amount+=$row->amount;
$ship_qty+=$row->ship_qty;
$ship_balance+=$row->ship_balance;
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
<td align="center" style="color: {{$bgcolor}}">{{$row->delay_days}}</td>
<td align="center">{{number_format($row->qty,2)}}</td>
<td align="center">{{number_format($row->rate,2)}}</td>
<td align="center">{{number_format($row->amount,2)}}</td>
<td align="center">{{number_format($row->ship_qty,2)}}</td>
<td align="center">{{number_format($row->ship_balance,2)}}</td>
<td align="center">{{$row->remarks}}</td>
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
<td align="center"></td>
<td align="center">{{number_format($qty,0)}} Pcs</td>
<td align="center"></td>
<td align="center">${{number_format($amount,0)}}</td>
<td align="center">{{number_format($ship_qty,0)}} Pcs</td>
<td align="center">{{number_format($ship_balance,0)}} Pcs</td>
<td align="center">{{$row->remarks}}</td>
</tr>
</tfoot>
</table>
</body>
</html> 