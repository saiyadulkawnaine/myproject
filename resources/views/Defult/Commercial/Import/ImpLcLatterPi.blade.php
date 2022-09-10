<p>Date: {{date('d-m-Y',strtotime($implc->lc_application_date))}}</p>
<p>To<br/>{{strtoupper($implc->contact)}}<br/>{{strtoupper($implc->bank_name)}}<br/>
		{{strtoupper($implc->branch_name)}}<br/>{{strtoupper($implc->bank_address)}}</p>
@if($implc->lc_type_id==1 || $implc->lc_type_id==3 || $implc->lc_type_id==5)
<p style='text-align:justify;'>{{$sub}}</p>
@endif
@if($implc->lc_type_id==2)
<p style='text-align:justify;'>{{$sub_margin_lc}}</p>
@endif
<p></p>
<p>DEAR SIR,</p>
@if($implc->lc_type_id==1 || $implc->lc_type_id==3 || $implc->lc_type_id==5)
	<p>{{$body}}</p>
	@if($implc->lc_type_id==3 || $implc->lc_type_id==5)
	<p>{{$ttp1}}</p>
	<p>{{$ttp2}}</p>
	@endif

	<table  border="1">
	<thead>
	<tr>
	<th align="center" width="150" class="text-center">PI /INDENT NO</th>
	<th align="center" width="150" class="text-center">PI /INDENT DATE</th>
	<th align="center" width="150" class="text-center">VALUE ({{ $implc->currency_code }})</th>
	<th align="center" width="188" class="text-center">SUPPLIER</th>
	</tr>
	</thead>
	<?php
	$t_amount=0;
	?>
	@foreach($pi as $row)
	<?php
	$t_amount+=$row->amount;
	?>
	<tr>
	<td align="center" width="150" class="text-center">{{$row->pi_no}}</td>
	<td align="center" width="150" class="text-center">{{$row->pi_date}}</td>
	<td align="center" width="150" class="text-center">{{number_format($row->amount,3)}}</td>
	<td align="center" width="188" class="text-center">{{$row->supplier_name}}</td>
	</tr>
	@endforeach
	<tr>
	<td align="center" width="150" class="text-center"></td>
	<td align="center" width="150" class="text-center">TOTAL</td>
	<td align="center" width="150" class="text-center">{{number_format($t_amount,3)}}</td>
	<td align="center" width="188" class="text-center"></td>
	</tr>
	</table>
@endif

@if ($implc->lc_type_id==2)
	<p>{{ $body_margin_lc }}</p>
	<table  border="1">
		<thead>
		<tr>
		<th align="center" width="214" class="text-center">PI /INDENT NO</th>
		<th align="center" width="212" class="text-center">PI /INDENT DATE</th>
		<th align="center" width="212" class="text-center">VALUE ({{ $implc->currency_code }})</th>
		</tr>
		</thead>
		<?php
		$t_amount=0;
		?>
		@foreach($pi as $row)
		<?php
		$t_amount+=$row->amount;
		?>
		<tr>
		<td align="center" width="214" class="text-center">{{$row->pi_no}}</td>
		<td align="center" width="212" class="text-center">{{$row->pi_date}}</td>
		<td align="center" width="212" class="text-center">{{number_format($row->amount,3)}}</td>
		</tr>
		@endforeach
		<tr>
		<td align="center" width="214" class="text-center"></td>
		<td align="center" width="212" class="text-center">TOTAL</td>
		<td align="center" width="212" class="text-center">{{number_format($t_amount,3)}}</td>
		</tr>
	</table>
	<p></p>
	<p>{{ $ttp1_margin_lc }}</p>
	<p>{{ $ttp2_margin_lc }}</p>
	<p>{{ $ttp3_margin_lc }}</p>
@endif

<p></p>
@if ($implc->lc_type_id==1 && $implc->bank_id!=62)
<p>PAYMENT TO BE MADE {{ strtoupper($implc->doc_release) }}</p>
@endif
<p>YOUR KIND CO-OPERATION IN THIS REGARD WOULD BE HIGHLY APPRECIATED.</p>
<p></p>
<p></p>

<table>
<tr>
<td width="319">
<p>THANKING YOU.</p>
<p></p>
<p>YOURS FAITHFULLY,</p>
</td>
<td width="319">
	@if($implc->lc_type_id==3 || $implc->lc_type_id==5)
	<p>Bank Details</p>
	<p>{{$implc->suppliers_bank}}</p>
	@endif
</td>
</tr>
</table>
 
