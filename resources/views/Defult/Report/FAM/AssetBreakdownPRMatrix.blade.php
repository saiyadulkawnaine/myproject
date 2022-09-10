<h3 align="center">Requisition Status Of Breakdown</h3>
<p></p>
<p></p>
<table>
    <tr>
        <td width="250"><strong>Reason:</strong> {{ $rows->reason }}<br/>
            <strong>Remarks:</strong> {{ $rows->remarks }}
        </td>
        <td width="200"><strong><strong>Asset No:</strong> {{ $rows->custom_no }}<br/>Entry ID:</strong> {{ $rows->id }}
            
        </td>
        <td width="200"><strong>Requisition Amount:</strong> {{ number_format($rows->amount,2) }}<br/>
            <strong>Paid Amount:</strong> {{ number_format($rows->paid_amount,2) }}<br/>
            <strong>Balance Amount:</strong> {{ number_format($rows->balance_amount,2) }}<br/>
        </td>
    </tr>
</table>
<p></p>
<p></p>
<table border="1" cellpadding="2">
    <tr>
        <td align="center" width="20"><strong>Sl</strong></td>
        <td align="center" width="100"><strong>Requisition No</strong></td>
        <td align="center" width="100"><strong>Paymode</strong></td>
        <td align="center" width="120"><strong>Item Description</strong></td>
        <td align="center" width="80"><strong>Reqn. Qty</strong></td>
        <td align="center" width="60"><strong>UOM</strong></td>
        <td align="center" width="80"><strong>Reqn. Rate</strong></td>
        <td align="center" width="80"><strong>Reqn. Amount</strong></td>
        <td align="center" width="80"><strong>PO Qty</strong></td>
        <td align="center" width="80"><strong>PO Rate</strong></td>
        <td align="center" width="80"><strong>PO Amount</strong></td>
        <td align="center" width="80"><strong>Rcv Qty</strong></td>
    </tr>
    <?php
        $i=1;
        $tReqQty=0;
        $tReqAmount=0;
        $tPoQty=0;
        $tPoAmount=0;
        $tRcvQty=0;
    ?>
    @foreach ($invpurreq as $data)
    <tr>
        <td align="center" width="20">{{ $i++ }}</td>
        <td align="left" width="100">{{ $data->requisition_no }}</td>
        <td align="left" width="100">{{ $data->pay_mode }}</td>
        <td align="left" width="120">{{ $data->item_desc }}</td>
        <td align="right" width="80">{{ number_format($data->req_qty,2) }}</td>
        <td align="center" width="60">{{ $data->uom_code }}</td>
        <td align="right" width="80">{{ number_format($data->req_rate,2) }}</td>
        <td align="right" width="80">{{ number_format($data->req_amount,2) }}</td>
        <td align="right" width="80">{{ number_format($data->po_qty,2) }}</td>
        <td align="right" width="80">{{ number_format($data->po_rate,2) }}</td>
        <td align="right" width="80">{{ number_format($data->po_amount,2) }}</td>
        <td align="right" width="80">{{ number_format($data->rcv_qty,2) }}</td>
    </tr>
    <?php
        $tReqQty+=$data->req_qty;
        $tReqAmount+=$data->req_amount;
        $tPoQty+=$data->po_qty;
        $tPoAmount+=$data->po_amount;
        $tRcvQty+=$data->rcv_qty;
    ?>
    @endforeach
    <tr>
        <td align="center" width="20"></td>
        <td align="left" width="320" colspan="3"></td>
        <td align="right" width="80">{{ $tReqQty }}</td>
        <td align="center" width="60"></td>
        <td align="right" width="80"></td>
        <td align="right" width="80">{{ $tReqAmount }}</td>
        <td align="right" width="80">{{ $tPoQty }}</td>
        <td align="right" width="80"></td>
        <td align="right" width="80">{{ $tPoAmount }}</td>
        <td align="right" width="80">{{ $tRcvQty }}</td>
    </tr>
</table>