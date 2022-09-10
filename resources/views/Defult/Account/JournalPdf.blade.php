<table cellspacing="0" cellpadding="2" border="0">
<tr align="center">
<td width="638"><strong>{{ $transprnt['trans_type'] }}</strong></td>
</tr>
<tr>
<td width="107"></td>
<td width="107"></td>
<td width="106"></td>
<td width="106"></td>
<td width="106">Journal No</td>
<td width="106">{{ $transprnt['trans_no'] }}</td>
</tr>
<tr>
<td width="107"></td>
<td width="107"></td>
<td width="106"></td>
<td width="106"></td>
<td width="106">Journal Date</td>
<td width="106">{{ $transprnt['trans_date'] }}</td>
</tr>
</table>

<table cellspacing="0" cellpadding="2" border="1">
<tr align="center">
<td width="40">A/C Code</td>
<td width="100">Account Head</td>
<td width="80">Party/ Employee Name</td>
<td width="130">Narration</td>
<td width="80">Invoice / Bill No</td>
<td width="69">Debit</td>
<td width="69">Credit</td>
<td width="70">Profit Center</td>

</tr>
<?php 
$exchangeRate=0;
?>
@foreach($transprnt['transchld'] as $row=>$value)
<?php
if(!$exchangeRate){
     $exchangeRate=$value['exch_rate'];
}
?>
<tr>
<td width="40">{{ $value['code'] }}</td>
<td width="100">{{ $value['acc_chart_ctrl_head_name'] }}</td>
<td width="80">{{ $value['emp_party_name'] }}</td>
<td width="130">
{{ $value['chld_narration'] }} @if($transprnt['instrument_no']), Cheq. No : {{ $transprnt['instrument_no'] }} @endif @if($value['loan_ref_no']), Loan Ref. No : {{ $value['loan_ref_no'] }} @endif @if($value['import_lc_ref_no']), Import LC Ref. No : {{ $value['import_lc_ref_no'] }} @endif @if($value['export_lc_ref_no']), Export LC Ref. No : {{ $value['export_lc_ref_no'] }} @endif @if($value['other_ref_no']), Other Ref. No : {{ $value['other_ref_no'] }} @endif
<?php



if($value['location_name']){
    echo ";".$value['location_name'];
}
if($value['division_name']){
    echo ";".$value['division_name'];
}
if($value['department_name']){
    echo ";".$value['department_name'];
}
if($value['section_name']){
    echo ";".$value['section_name'];
}
?>

</td>
<td width="80">{{ $value['bill_no'] }}</td>
<td width="69" align="right">
{{ 
    number_format($value['amount_debit'],2,'.',',') 
}}
</td>
<td width="69" align="right">{{ number_format($value['amount_credit'],2,'.',',') }}</td>
<td width="70">{{ $value['profitcenter_name'] }}</td>
</tr>
@endforeach
<tr>
<td width="430" align="right">Total:</td>

<td width="69" align="right">{{ $transprnt['total_amount_debit'] }}</td>
<td width="69" align="right">{{ $transprnt['total_amount_credit'] }}</td>
<td width="70"></td>
</tr>
</table>

<table cellspacing="0" cellpadding="2" border="0">
<tr>
<td width="638"> </td>
</tr>
<tr>
<td width="638"> <strong>In Word : {{ $transprnt['inword'] }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; @if($exchangeRate) Exchange Rate : {{ $exchangeRate }} @endif</strong></td>
</tr>
</table>

<table cellspacing="0" cellpadding="2" border="0">
<tr>
<td width="638"></td>
</tr>
<tr>
<td width="638">Narration : {{ $transprnt['narration'] }}</td>
</tr>
</table>
<p></p>
<table cellspacing="0" cellpadding="2" border="0">
<tr align="center">
<td width="128">Prepared By</td>
<td width="128">Received By</td>
<td width="128">Checked By</td>
<td width="127">Audited By</td>
<td width="127">Approved By</td>
</tr>
<tr align="center">
<td width="128">{{ $transprnt['created_by'] }}</td>
<td width="128"></td>
<td width="128"></td>
<td width="127"></td>
<td width="127"></td>
</tr>
<tr align="center">
<td width="128">{{ $transprnt['created_at'] }}</td>
<td width="128"></td>
<td width="128"></td>
<td width="127"></td>
<td width="127"></td>
</tr>
<tr align="center">
    <td width="128"></td>
    <td width="128"></td>
    <td width="128"></td>
    <td width="127"></td>
    <td width="127"></td>
</tr>
<tr align="center">
    <td width="256" colspan="1">@if($transprnt['updated_by'])Edited By :&nbsp; {{ $transprnt['updated_by'] }} &nbsp;&nbsp;&nbsp;&nbsp;Date:&nbsp; {{ $transprnt['updated_at'] }}@endif</td>
    <td width="128"></td>
    <td width="127"></td>
    <td width="127"></td>
</tr>
</table>