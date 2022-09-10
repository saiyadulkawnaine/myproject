<p>Date: {{date('d-m-Y')}}</p>
<p>To<br/>{{$rows['contact']}}<br/>{{$rows['bank_name']}}<br/>{{$rows['branch_name']}}<br/>{{$rows['bank_address']}}</p>
<p style='text-align:justify;'>{{$sub}}</p>
<p>Dear Sir,</p>
<p>{{$body}}</p>
<table border="1" cellpadding="1">
    <thead>
        <tr>
            <th width="30" align="center">SL</th>
            <th width="130" align="center">BTB L/C No</th>
            <th width="70" align="center">Invoice Date</th>
            <th width="130" align="center">Invoice No</th>
            <th width="80" align="center">Invoice Value</th>
            <th width="150" align="center">Name of Beneficiary</th>
        </tr>
    </thead>
    <?php
        $i=1;
        $total_invoice_value=0;
    ?>
    @foreach ($invoice_detail as $invoice)
    <tbody>
        <tr>
            <th width="30" align="center">{{ $i++ }}</th>
            <th width="130" align="center">{{ $invoice->lc_no }}</th>
            <th width="70" align="center">{{ $invoice->invoice_date }}</th>
            <th width="130" align="center">{{ $invoice->invoice_no }}</th>
            <th width="80" align="right">{{  number_format($invoice->doc_value,2) }}</th>
            <th width="150" align="center">{{ $invoice->beneficiary }}</th>
        </tr>
    </tbody>
    <?php
        $total_invoice_value+=$invoice->doc_value;
    ?>
    @endforeach
    <tfoot>
        <tr>
            <td width="360" colspan="4" align="center"><strong>Total</strong></td>
            <td width="80" align="right"><strong>{{ number_format($total_invoice_value,2) }}</strong></td>
            <td width="150"></td>
        </tr>
    </tfoot>
</table>
<p></p>
<p>{{$ttp2}}</p>
<table>
    <tr>
        <td>Thanking You.<br/>
        Yours Faithfully,<br/>
        {{$rows['beneficiary_id']}}
        <br/><br/><br/><br/>
        </td>
        <td></td><td></td>
    </tr>
    <tr>
        <td><br/><br/><br/></td>
        <td></td><td></td>
    </tr>
    <tr>
        <td><p></p></td>
        <td></td><td></td>
    </tr>
    <tr>
        <td>Authorised By</td>
        <td></td><td></td>
    </tr>
</table>