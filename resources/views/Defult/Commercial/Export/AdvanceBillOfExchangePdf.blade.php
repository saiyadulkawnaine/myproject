<p align="right">Date: {{ date('d.m.Y') }}</p>
<h3 align="center">BILL OF EXCHANGE</h3>
<h4 align="center">Exchange for {{ $rows->currency_code }} {{ $rows->currency_symbol }}{{ number_format($rows->total_invoice_net_inv_value,2) }}</h4>
<p>{{ $rows->pay_term_id }} of this first bill of exchange (second of the same tenor and date being unpaid) to the order of {{ $rows->bank_name }}, {{ $rows->branch_name }}, {{ $rows->bank_address }}</p>
<h4 align="center">1</h4>
<p>The sum of (Say {{ $rows->inword }})</p>
<p>Value received against invoice no. {{ $rows->invoice_no }} drawn under {{ $rows->sc_or_lc_name }} no: {{ $rows->lc_sc_no }} date:{{ $rows->lc_sc_date }}</p>
<p></p>
<table>
    <tr>
        <td>To</td>
        <td></td>
    </tr>
    <tr>
        <td>{{ $rows->buyers_bank }}</td>
        <td></td>
    </tr>
</table>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<table>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td align="right"><hr>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Authorised Signature</td>
    </tr>
</table>
<p></p>
<p></p>
<p align="center"><img src="{{$image_file}}" width="260", height="40"/></p>
<p align="center" style="padding-top:0;"><span>{{ $rows['company_address'] }}</span></p>
<p align="right">Date: {{ date('d.m.Y') }}</p>
<h3 align="center">BILL OF EXCHANGE</h3>
<h4 align="center">Exchange for {{ $rows->currency_code }} {{ $rows->currency_symbol }}{{ number_format($rows->total_invoice_net_inv_value,2) }}</h4>
<p>{{ $rows->pay_term_id }} of this first bill of exchange (second of the same tenor and date being unpaid) to the order of {{ $rows->bank_name }}, {{ $rows->branch_name }}, {{ $rows->bank_address }}</p>
<h4 align="center">2</h4>
<p>The sum of (Say {{ $rows->inword }})</p>
<p>Value received against invoice no. {{ $rows->invoice_no }} drawn under {{ $rows->sc_or_lc_name }} no: {{ $rows->lc_sc_no }} date:{{ $rows->lc_sc_date }}</p>
<p></p>
<table>
    <tr>
        <td>To</td>
        <td></td>
    </tr>
    <tr>
        <td>{{ $rows->buyers_bank }}</td>
        <td></td>
    </tr>
</table>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<table>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td align="right"><hr>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Authorised Signature</td>
    </tr>
</table>