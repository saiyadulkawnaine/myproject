

<table  border="1" style="margin: 0 auto;">
    <caption style="font-weight: bold;font-size: 24px">PLAN-A</caption>
<thead>
<tr style="background-color: #ccc">
<th width="20" class="text-center">#</th>
<th width="100" class="text-center">Production Process</th>
<th width="100" class="text-center">Target</th>
<th width="100" class="text-center">Achivement</th>
<th width="100" class="text-center">Balance</th>
<th width="100" class="text-center">Achivement %</th>
</tr>
</thead>
<body>
<?php
$i=1;
?>
@foreach ($rows as $row)
<tr>
<td>{{$i}}</td>
<td align="center">{{$row->process}}</td>
<td align="right">{{number_format($row->target_qty,2)}}</td>
<td align="right">{{number_format($row->prod_qty,2)}}</td>
<td align="right">{{number_format($row->target_qty-$row->prod_qty,2)}}</td>
<td align="right">{{number_format($row->achv_per,2)}}</td>
</tr>
<?php
$i++;
?>
@endforeach
</body>
<tfoot>
</tfoot>
</table>
<br/>

<table  border="1" style="margin: 0 auto;">
    <caption style="font-weight: bold;font-size: 24px">PLAN-B</caption>
<thead>
<tr style="background-color: #ccc">
<th width="20" class="text-center">#</th>
<th width="100" class="text-center">Production Process</th>
<th width="100" class="text-center">Target</th>
<th width="100" class="text-center">Achivement</th>
<th width="100" class="text-center">Balance</th>
<th width="100" class="text-center">Achivement %</th>
</tr>
</thead>
<body>
<?php
$i=1;
?>
@foreach ($rowbs as $rowb)
<tr>
<td>{{$i}}</td>
<td align="center">{{$rowb->process}}</td>
<td align="right">{{number_format($rowb->target_qty,2)}}</td>
<td align="right">{{number_format($rowb->prod_qty,2)}}</td>
<td align="right">{{number_format($rowb->target_qty-$rowb->prod_qty,2)}}</td>
<td align="right">{{number_format($rowb->achv_per,2)}}</td>
</tr>
<?php
$i++;
?>
@endforeach
</body>
<tfoot>
</tfoot>
</table>
<br/>
