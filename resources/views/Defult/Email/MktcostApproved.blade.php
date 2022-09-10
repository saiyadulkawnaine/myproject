<!DOCTYPE html>
<html>
<head>
<title>Mkt Cost</title>
</head>
<body>

<table  border="1" style="margin: 0 auto;">
  
<thead>
<tr style="background-color: #ccc">
<th width="20" class="text-center">#</th>
<th width="100" class="text-center">Team Leader</th>
<th width="100" class="text-center">Buyer</th>
<th width="100" class="text-center">Style No</th>
<th width="100" class="text-center">Style Desc.</th>
<th width="100" class="text-center">Season</th>
<th width="100" class="text-center">Prod. Dept</th>
<th width="100" class="text-center">Offered Qty</th>
<th width="100" class="text-center">UOM</th>
<th width="100" class="text-center">Est. Ship Date</th>
<th width="100" class="text-center">Costing date</th>
<th width="100" class="text-center">Cost/Unit</th>
<th width="100" class="text-center">Quote Price/Unit</th>
<th width="100" class="text-center">CM/Dzn</th>
	
</th>
</tr>
</thead>
<body>
<?php
$i=1;
?>
@foreach ($rows as $row)
<tr>
<td>{{$i}}</td>
<td align="center">{{$row->team_name}}</td>
<td align="center">{{$row->buyer_name}}</td>
<td align="center">{{$row->style_ref}}</td>
<td align="center">{{$row->style_description}}</td>
<td align="center">{{$row->season_name}}</td>
<td align="center">{{$row->department_name}}</td>
<td align="center">{{$row->offer_qty}}</td>
<td align="center">{{$row->uom_code}}</td>
<td align="center">{{$row->est_ship_date}}</td>
<td align="center">{{$row->quot_date}}</td>
<td align="right">{{number_format($row->cost_per_pcs,2)}}</td>
<td align="right">{{number_format($row->price,2)}}</td>
<td align="right">{{number_format($row->cm_amount,2)}}</td>

</tr>
<?php
$i++;
?>
@endforeach
</body>
<tfoot>
</tfoot>
</table>
</body>
</html> 