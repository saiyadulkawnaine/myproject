<p></p>
<h3 align="center">Bill of Exchange</h3>
<p align="center"><code>(First)</code></p>

<table border="0" cellpadding="1" cellspacing="0">
    <tr>
        <td width="330"></td>
        <td width="120"></td>
        <td width="100" align="left">&nbsp;Invoice Date:</td>
        <td width="80" align="left">{{ $localexpdocaccept['local_invoice_date'] }}</td>
    </tr>
</table>
<table  border="0" cellpadding="2">
    <tr>
        <td width=630>For US:&nbsp;&nbsp;<strong>{{ $localexpdocaccept['currency_symbol'] }}&nbsp;{{ number_format($localexpdocaccept['cumulative_amount'],2) }}</strong>
        </td>
    </tr>
    <tr>
        <td width=630>Invoice No {{ $localexpdocaccept['invoice_no'] }},</td>
    </tr>
    <tr>
        <td width="640"><p>{{ $localexpdocaccept['tenor'] }}&nbsp;Days Sight of this First Bill of Exchange (Second of the same tenor and date beind unpaid) pay to <strong>{{ $localexpdocaccept['exporter_bank_branch'] }}</strong> the sum of&nbsp;<strong>{{ $localexpdocaccept['inword'] }}</strong> only. </p>
        <p>Value received and charge to account of {{ $localexpdocaccept['buyer_name'] }}, {{ $localexpdocaccept['buyer_address'] }}{{-- , {{ $localexpdocaccept['buyer_email'] }} --}} drawn under {{ $localexpdocaccept['buyers_bank'] }}</p>
        <p>Merchandise is of Bangladesh Origin, for 100% Export Oriented Readymade Garments Factory Details as per <br/>
        Proforma Invoice No:&nbsp;&nbsp;<strong>{{ $localexpdocaccept['pi_no'] }}</strong><br/>
        L/C No.& Date:&nbsp;<strong>{{ $localexpdocaccept['local_lc_no'] }}, Date:{{ $localexpdocaccept['lc_date'] }}</strong><br/>
        Export L/C Cont. No. & Date :&nbsp;<strong>{{ $localexpdocaccept['customer_lc_sc'] }}</strong>
    </p>
        </td>
    </tr>
    <tr>
        <td width="640">To,<br/>
            {!! nl2br(e($localexpdocaccept['buyers_bank'])) !!}
        </td>
    </tr>
</table> 
<br pagebreak="true"/>
<p></p>
<p align="center"><img src="{{$image_file}}" width="260", height="45"/></p>
<p align="center" style="padding-top:0;font-weight:bold"><span>{{ $localexpdocaccept['company_address'] }}</span></p> 
<p></p>

<h3 align="center">Bill of Exchange</h3>
<p align="center">(Second)</p>

<table border="0" cellpadding="1" cellspacing="0">
    <tr>
        <td width="330"></td>
        <td width="120"></td>
        <td width="100" align="left">&nbsp;Invoice Date:</td>
        <td width="80">{{ $localexpdocaccept['local_invoice_date'] }}</td>
    </tr>
</table>
<table border="0" cellpadding="2">
    <tr>
        <td width=630>To Whom:&nbsp;&nbsp;<strong>{{ $localexpdocaccept['currency_symbol'] }}&nbsp;{{ number_format($localexpdocaccept['cumulative_amount'],2) }}</strong>
        </td>
    </tr>
    <tr>
        <td width=630>Invoice No {{ $localexpdocaccept['invoice_no'] }},</td>
    </tr>
    <tr>
        <td width="640"><p>{{ $localexpdocaccept['tenor'] }}&nbsp;Days Sight of this Second Bill of Exchange (First of the same tenor and date beind unpaid) pay to <strong>{{ $localexpdocaccept['exporter_bank_branch'] }}</strong> the sum of&nbsp;<strong>{{ $localexpdocaccept['inword'] }}</strong> only. </p>
        <p>Value received and charge to account of {{ $localexpdocaccept['buyer_name'] }}, {{ $localexpdocaccept['buyer_address'] }}{{-- , {{ $localexpdocaccept['buyer_email'] }} --}} drawn under {{ $localexpdocaccept['buyers_bank'] }}</p>
        <p>Merchandise is of Bangladesh Origin, for 100% Export Oriented Readymade Garments Factory Details as per <br/>
        Proforma Invoice No:&nbsp;&nbsp;<strong>{{ $localexpdocaccept['pi_no'] }}</strong><br/>
        L/C No.& Date:&nbsp;<strong>{{ $localexpdocaccept['local_lc_no'] }}, Date:{{ $localexpdocaccept['lc_date'] }}</strong><br/>
        Export L/C Cont. No. & Date :&nbsp;<strong>{{ $localexpdocaccept['customer_lc_sc'] }}</strong>
    </p>
        </td>
    </tr>
    <tr>
        <td width="640">To,<br/>
            {!! nl2br(e($localexpdocaccept['buyers_bank'])) !!}
        </td>
    </tr>
</table>