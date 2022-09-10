<p>Date: {{date('d-M-Y',strtotime($master->submission_date))}}</p>
<p>To<br/>
	{{$master->contact}}<br/>
	{{$master->bank_name}}<br/>
	{{$master->branch_name}}<br/>
	{{$master->bank_address}}
</p>
<p style='text-align:justify;'>{{$sub}}</p>
<p>Dear Sir,</p>
<p>We request you to purchase/negotiate export document as advice given below.</p>

<table>
<tr align="center">
<td width="638">
	<strong>
File No: {{$master->file_no}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Buyer: {{$master->buyer_id}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;System ID: {{$master->id}}</strong>
</td>
</tr>
<tr align="center">
<td width="638">
	&nbsp;
</td>
</tr>

<tr>
<td width="300">
	Sales Contract No<br/>
	<table border="1" cellpadding="2">
		<thead>
			<tr align="center">
				<td width="150">Number</td>
				<td width="150">Value</td>
			</tr>
		</thead>
		<?php
		$rsc_total=0;
		?>
		@foreach($replacablesc as $rsc)
		<tr>
				<td width="150">{{$rsc->lc_sc_no}}</td>
				<td width="150" align="right">{{number_format($rsc->lc_sc_value,2)}}</td>
		</tr>
		<?php
		$rsc_total+=$rsc->lc_sc_value;
		?>
		@endforeach
		<tr>
				<td width="150" align="right">Sub Total:</td>
				<td width="150" align="right">{{number_format($rsc_total,2)}}</td>
		</tr>
	</table>
	<br/>
	Direct Export LC / Contract<br/>
	<table border="1" cellpadding="2">
		<thead>
			<tr align="center">
				<td width="150">Number</td>
				<td width="150">Value</td>
			</tr>
		</thead>
		<?php
		$dlcsc_total=0;
		?>
		@foreach($direct_lc_sc as $dlcsc)
		<tr>
				<td width="150">{{$dlcsc->lc_sc_no}}</td>
				<td width="150" align="right">{{number_format($dlcsc->lc_sc_value,2)}}</td>
		</tr>
		<?php
		$dlcsc_total+=$dlcsc->lc_sc_value;
		?>
		@endforeach
		<tr>
				<td width="150" align="right">Sub Total:</td>
				<td width="150" align="right">{{number_format($dlcsc_total,2)}}</td>
		</tr>

		<tr>
				<td width="150" align="right">Total:</td>
				<td width="150" align="right">{{number_format($rsc_total+$dlcsc_total,2)}}</td>
		</tr>
	</table>
</td>
<td width="38"></td>
<td width="300">
	Net LC / Contract Value<br/>
	<table border="1" cellpadding="2">
			<tr>
				<td width="150">Foreign Commission</td>
				<td width="150" align="right">{{number_format($master->foreign_commission,2)}}</td>
			</tr>
			<tr>
				<td width="150">Local Commission</td>
				<td width="150" align="right">{{number_format($master->local_commission,2)}}</td>
			</tr>
			<tr>
				<td width="150">Freight</td>
				<td width="150" align="right">{{number_format($master->freight,2)}}</td>
			</tr>
			<tr>
				<td width="150" align="right">Total:</td>
				<td width="150" align="right">{{number_format($master->freight_l_commission_f_commission,2)}}</td>
			</tr>

			<tr>
				<td width="150" align="right">Net File Value</td>
				<td width="150" align="right">{{number_format($master->net_file_value,2)}}</td>
			</tr>
	</table>
	<br/>
	Facilities Availed<br/>
	<table border="1" cellpadding="2">
			<tr>
				<td width="150">BTB LC Openable</td>
				<td width="100" align="right">{{number_format($master->btb_openable,2)}}</td>
				<td width="50" align="right">70.00%</td>
			</tr>
			<tr>
				<td width="150">BTB LC Opened</td>
				<td width="100" align="right">{{number_format($btbpc->btb_open_amount,2)}}</td>
				<td width="50" align="right">{{number_format($btbpc->btb_open_amount_per,2)}}%</td>
			</tr>
			<tr>
				<td width="150">Yet to Open BTB LC</td>
				<td width="100" align="right">{{number_format($btbpc->yet_to_btb_open_amount,2)}}</td>
				<td width="50" align="right">{{number_format($btbpc->yet_to_btb_open_amount_per,2)}}%</td>
			</tr>
			<tr>
				<td width="150">Packing Credit Taken</td>
				<td width="100" align="right">{{number_format($btbpc->pc_taken_mount,2)}}</td>
				<td width="50" align="right">{{number_format($btbpc->pc_taken_mount_per,2)}}%</td>
			</tr>
			<tr>
				<td width="150">Cost of Packing Credit</td>
				<td width="100" align="right"></td>
				<td width="50" align="right">{{number_format($btbpc->pc_taken_rate,2)}}%</td>
			</tr>
	</table>
	
</td>
</tr>
</table>
<p></p>
Submitted Invoice Details<br/>
<table border="1" cellpadding="2">
	<thead>
		<tr align="center">
			<td width="18">SL</td>
			<td width="100">LC/SC No</td>
			<td width="30">Type</td>
			<td width="70">Invoice No</td>
			<td width="70">Invoice Date</td>
			<td width="70">Invoice Value</td>
			<td width="60">Deduction</td>
			<td width="50">Fgn. Commn.</td>
			<td width="50">Local Commn.</td>
			<td width="50">Freight</td>
			<td width="70">Net Inv. Value</td>
		</tr>
	</thead>
	<?php
	$i=1; 
	?>
	@foreach($invoice_detail as $invoice)
	<tr align="center">
	<td width="18">{{$i}}</td>
	<td width="100">{{$invoice->lc_sc_no}}</td>
	<td width="30">{{$invoice->sc_or_lc_name}}</td>
	<td width="70">{{$invoice->invoice_no}}</td>
	<td width="70">{{$invoice->invoice_date}}</td>
	<td width="70" align="right">{{number_format($invoice->invoice_value,2)}}</td>
	<td width="60" align="right">{{number_format($invoice->deduction,2)}}</td>
	<td width="50" align="right">{{number_format($invoice->local_commission,2)}}</td>
	<td width="50" align="right">{{number_format($invoice->foreign_commission,2)}}</td>
	<td width="50" align="right">{{number_format($invoice->freight,2)}}</td>
	<td width="70" align="right">{{number_format($invoice->net_inv_value,2)}}</td>
	</tr>
	<?php
	$i++; 
	?>
	@endforeach
	<tr align="center">
	<td width="18"></td>
	<td width="100"></td>
	<td width="30"></td>
	<td width="70"></td>
	<td width="70"></td>
	<td width="70" align="right">{{number_format($master->total_invoice_value,2)}}</td>
	<td width="60" align="right">{{number_format($master->total_invoice_deduction,2)}}</td>
	<td width="50" align="right">{{number_format($master->total_invoice_local_commission,2)}}</td>
	<td width="50" align="right">{{number_format($master->total_invoice_foreign_commission,2)}}</td>
	<td width="50" align="right">{{number_format($master->total_invoice_freight,2)}}</td>
	<td width="70" align="right">{{number_format($master->total_invoice_net_inv_value,2)}}</td>
	</tr>
</table>
<p></p>
Value Distribution Sheet<br/>
<table border="1" cellpadding="2">
	<thead>
		<tr align="center">
			<td width="18">SL</td>
			<td width="200">Head</td>
			<td width="120">Amount</td>
			<td width="100">%</td>
		</tr>
	</thead>
	<tr>
		<td width="18">1</td>
		<td width="200">FC Held BB LC</td>
		<td width="120" align="right">{{number_format($master->fc_held_bb_lc,2)}}</td>
		<td width="100" align="right">{{number_format($master->btb_open_amount_per,2)}}%</td>
	</tr>
	<tr>
		<td width="18">2</td>
		<td width="200">ERQ Account</td>
		<td width="120" align="right">{{number_format($master->erq_account,2)}}</td>
		<td width="100" align="right">1.00%</td>
	</tr>
	<tr>
		<td width="18">3</td>
		<td width="200">Packing Credit</td>
		<td width="120" align="right">{{number_format($master->packing_credit_invoice,2)}}</td>
		<td width="100" align="right">{{number_format(16,2)}}%</td>
	</tr>
	
	

	<tr>
		<td width="18">4</td>
		<td width="200">MDA Normal</td>
		<td width="120" align="right">{{number_format($master->mda_normal,2)}}</td>
		<td width="100" align="right">1.00%</td>
	</tr>
	<tr>
		<td width="18">5</td>
		<td width="200">Income/Interest on Purchase</td>
		<td width="120" align="right">{{number_format($master->interest_on_purchase,2)}}</td>
		<td width="100" align="right">1.00%</td>
	</tr>
	<tr>
		<td width="18">6</td>
		<td width="200">Source Tax</td>
		<td width="120"align="right">{{number_format($master->source_tax,2)}}</td>
		<td width="100"align="right">1.00%</td>
	</tr>
	<tr>
		<td width="18">7</td>
		<td width="200">Foreign Bank Charge</td>
		<td width="120" align="right">{{number_format($master->frg_bank_charge,2)}}</td>
		<td width="100" align="right">3.00%</td>
	</tr>
	
	<tr>
		<td width="18">8</td>
		<td width="200">Current Account</td>
		<td width="120" align="right">{{number_format($master->current_account,2)}}</td>
		<td width="100" align="right">{{number_format($master->current_account_per,2)}}%</td>
	</tr>
</table>
<p></p>
<p>Thanks in advence for nice cooperation.</p>
<p></p>

<p>Authorised By</p>

