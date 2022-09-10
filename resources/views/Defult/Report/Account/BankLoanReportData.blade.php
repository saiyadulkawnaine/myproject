<div style="width: 100%; text-align: center; font-weight: bold;font-size: 20px">Bank Liablity Position as on {{date('d-M-Y',strtotime($date_to))}}</div>
@foreach ($loans as $company_id=>$companyData)
@foreach ($companyData as $bank_id=>$bankData)
@foreach ($bankData as $bank_branch_id=>$branchData)
<table  border="1" style="margin: 0 auto;">
    <caption style="font-weight: bold;font-size: 14px">{{$company[$company_id]}}</caption>
<thead>
<tr>
<td colspan="7"><strong>Bank:</strong> {{$bank[$bank_id]}} <strong> Branch:</strong>{{$bankbranch[$bank_branch_id]}}</td>
</tr>
<tr style="background-color: #ccc">
<th width="20" class="text-center">#</th>
<th width="150" class="text-center">Particulars</th>
<th width="100" class="text-center">Limit</th>
<th width="150" class="text-center">Outstandings</th>
<th width="150" class="text-center">No of Installment</th>
<th width="100" class="text-center">Overdue</th>
<th width="150" class="text-center">No of Overdue Ins.</th>
</tr>
</thead>
<body>
<?php
$i=1;
$tot_outstandings=0;
$tot_no_installment_outs=0;
$tot_due_outstandings=0;
$tot_no_due_installment_outs=0;
?>
@foreach ($branchData as $data)
<tr>
<td>{{$i}}</td>
<td align="left">{{$data->commercial_head_name}}</td>
<td align="right"></td>
<td align="right">{{number_format($data->outstandings,0)}}</td>
<td align="right">{{number_format($data->no_installment_outs,0)}}</td>
<td align="right">{{number_format($data->due_outstandings,0)}}</td>
<td align="right">{{number_format($data->no_due_installment_outs,0)}}</td>
</tr>
<?php
$i++;
$tot_outstandings+=$data->outstandings;
$tot_no_installment_outs+=$data->no_installment_outs;
$tot_due_outstandings+=$data->due_outstandings;
$tot_no_due_installment_outs+=$data->no_due_installment_outs;
?>
@endforeach
</body>
<tfoot>
<tr style="font-weight: bold; background-color: #ccc">
<td></td>
<td>Total</td>
<td align="right"></td>

<td align="right">{{number_format($tot_outstandings,0)}}</td>
<td align="right">{{number_format($tot_no_installment_outs,0)}}</td>
<td align="right">{{number_format($tot_due_outstandings,0)}}</td>
<td align="right">{{number_format($tot_no_due_installment_outs,0)}}</td>
</tr>
</tfoot>
</table>
<br/>
@endforeach
@endforeach
@endforeach
