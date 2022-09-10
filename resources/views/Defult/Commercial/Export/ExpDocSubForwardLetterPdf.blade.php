<p></p>
<p></p>
<p>Date: {{date('d-m-Y',strtotime($rows['submission_date']))}}</p>
<p>To<br/>{{$rows['contact']}}<br/>{{$rows['bank_name']}}<br/>{{$rows['branch_name']}}<br/>{{$rows['bank_address']}}</p>
<p style='text-align:justify;'>{{$sub}}</p>
<p>Dear Sir,</p>
<p>{{$body}}</p>
<table border="1" width="400" cellpadding="1" style="margin: 5 auto">
    <thead>
        <tr>
            <th align="center">SL</th>
            <th align="center">Document No</th>
            <th align="center">Invoice Qty</th>
            <th align="center">Invoice Value</th>
            <th align="center">Date</th>
        </tr>
    </thead>
    <?php
        $i=1;
        $total_invoice_qty=0;
        $total_invoice_amount=0;
    ?>
    @foreach ($invoice_detail as $invoice)
    <tbody>
        <tr>
            <td align="center">{{ $i++ }}</td>
            <td align="center">{{ $invoice->invoice_no }}</td>
            <td align="right">{{ number_format($invoice->cumulative_qty,2) }}</td>
            <td align="right">{{ number_format($invoice->net_inv_value,2) }}</td>
            <td align="center">{{ date('d-M-Y',strtotime($invoice->invoice_date)) }}</td>
        </tr>
    </tbody>
    <?php
        $total_invoice_qty+=$invoice->cumulative_qty;
        $total_invoice_amount+=$invoice->net_inv_value;
    ?>
    @endforeach
    <tfoot>
        <tr>
            <td colspan="2"><strong>Total</strong></td>
            <td align="right">{{ number_format($total_invoice_qty,2) }}</td>
            <td align="right">{{ number_format($total_invoice_amount,2) }}</td>
            <td></td>
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
        <td><br/><br/><br/></td>
        <td></td><td></td>
    </tr>
    <tr>
        <td>Authorized Signature</td>
        <td></td><td></td>
    </tr>
    <tr>
        <td><br/><br/><br/></td>
        <td></td><td></td>
    </tr>
    <tr>
        <td><p><u>ENCL:-</u></p></td>
        <td></td><td></td>
    </tr>
    <tr>
        <td><p></p></td>
        <td></td><td></td>
    </tr>
    @if ($rows['submission_type_id']==3)
        <tr>
            <td width="20px">1.</td>
            <td>Commercial Invoice</td>
            <td> : 1 Copy</td>
        </tr>
        <tr>
            <td width="20px">2.</td>
            <td>Packing List & Weight List</td>
            <td> : 1 Copy</td>
        </tr>
        <tr>
            <td width="20px">3.</td>
            <td>Bill Of Lading</td>
            <td> : 1 Set</td>
        </tr>
        <tr>
            <td width="20px">4.</td>
            <td>Certificate Of Origin GSP Form - A</td>
            <td> : 1 Copy</td>
        </tr>
        <tr>
            <td width="20px">5.</td>
            <td>Courier Receipt</td>
            <td> : 1 Copy</td>
        </tr>
        <tr>
            <td width="20px">6.</td>
            <td>EXP Set</td>
            <td> : 1 Set</td>
        </tr>   
    @else
        <tr>
            <td width="20px">1.</td>
            <td>Commercial Invoice</td>
            <td> : 3 Copy</td>
        </tr>
        <tr>
            <td width="20px">2.</td>
            <td>Packing List & Weight List</td>
            <td> : 3 Copy</td>
        </tr>
        <tr>
            <td width="20px">3.</td>
            <td>Bill Of Lading</td>
            <td> : 1+3 Set</td>
        </tr>
        <tr>
            <td width="20px">4.</td>
            <td>Certificate Of Origin GSP Form - A</td>
            <td> : 3 Copy</td>
        </tr>
        <tr>
            <td width="20px">5.</td>
            <td>Courier Receipt</td>
            <td> : 2 Copy</td>
        </tr>
        <tr>
            <td width="20px">6.</td>
            <td>EXP Set</td>
            <td> : 1 Set</td>
        </tr> 
    @endif
    
</table>