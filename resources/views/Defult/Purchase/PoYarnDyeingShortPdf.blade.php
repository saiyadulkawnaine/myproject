<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="878"></td>
    </tr>
    <tr>
        <td width="878"></td>
    </tr>
    <tr>
        <td width="878" align="center" style="font-size:50px"><strong><u>{{ $purOrder['master']->remarks}}</u></strong>
        </td>
    </tr>
    <tr>
        <td width="878"></td>
    </tr>
    <tr>
        <td width="700">To,<br />
            {{ $purOrder['master']->supplier_name }}<br />
            {{ $purOrder['master']->supplier_address }}
        </td>
        <td width="98">
            <br />
            WO No:<br />
            WO Date:<br />
            Delivery Start:<br />
            Delivery End:<br />
        </td>
        <td width="80">
            <br />
            {{ $purOrder['master']->po_no }}<br />
            {{ $purOrder['master']->po_date }}<br />
            {{ $purOrder['master']->delv_start_date }}<br />
            {{ $purOrder['master']->delv_end_date }}<br />
        </td>
    </tr>
    <tr>
        <td width="878">
            <br />
            Contact Details: {{ $purOrder['master']->contact_detail}}
        </td>
    </tr>
</table>
<br />
<table cellspacing="0" cellpadding="2" border="1">
    <thead>
        <tr>
            <td width="30" align="center">#</td>
            <td width="180" align="center">Sales Order & Style & Buyer</td>
            <td width="80" align="center">Yarn</td>
            <td width="50" align="center">Lot</td>
            <td width="50" align="center">Brand</td>
            <td width="50" align="center">Dyeing<br /> Color</td>
            <td width="80" align="center">Qty</td>
            <td width="60" align="center">Rate</td>
            <td width="70" align="center">Amount {{ $purOrder['master']->currency_name}}</td>
            <td width="50" align="center">Process<br /> Loss</td>
            <td width="50" align="center">Required<br />Cone</td>
            <td width="40" align="center">Wgt/<br />Cone</td>
            <td width="100" align="center">Remarks</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $totQty=0;
        //$totPcsQty=0;
        $totAmount=0;
        ?>

        @foreach($data as $row)

        <tr nobr="true">
            <td width="30" align="center">{{ $loop->iteration }}</td>
            <td width="180" align="center">
                {{$row->sale_order_no}}, {{$row->style_ref}}, {{$row->buyer_name}}
            </td>
            <td width="80" align="center">{{$row->yarn_desc}}</td>
            <td width="50" align="center">{{$row->lot}}</td>
            <td width="50" align="center">{{$row->brand}}</td>
            <td width="50" align="center">{{$row->yarn_color_name}}</td>
            <td width="80" align="right">{{ number_format($row->qty,2) }}</td>
            <td width="60" align="right">{{ number_format($row->rate,4) }}</td>
            <td width="70" align="right">{{ number_format($row->amount,2) }}</td>
            <td width="50" align="right">{{$row->process_loss_per}}</td>
            <td width="50" align="right">{{$row->req_cone}}</td>
            <td width="40" align="right">{{$row->wgt_per_cone}}</td>
            <td width="100" align="center">{{ $row->qty_remarks }}</td>
        </tr>
        <?php
            $totQty+=$row->qty;
            //$totPcsQty+=$row->pcs_qty;
            $totAmount+=$row->amount;
        ?>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td width="30" align="center"></td>
            <td width="180" align="center"></td>
            <td width="80" align="center"></td>
            <td width="50" align="center"></td>
            <td width="50" align="center"></td>
            <td width="50" align="center"></td>
            <td width="80" align="right">{{ number_format($totQty,2) }}</td>
            <td width="60" align="right"></td>
            <td width="70" align="right">{{ number_format($totAmount,2) }}</td>
            <td width="50" align="right"></td>
            <td width="50" align="right"></td>
            <td width="40" align="right"></td>
            <td width="100" align="center"></td>
        </tr>
    </tfoot>
</table>
<br />
<strong>In Words : {{ $purOrder['master']->inword }}.</strong>
<br />
<br />
<table>
    <tr>
        <td width="878">
            <strong>Terms & Conditions:</strong>
        </td>
    </tr>
    <?php
        $i=1;
        ?>
    @foreach($purOrder['purchasetermscondition'] as $terms)
    <tr>
        <td width="38">
            {{$i}}.
        </td>
        <td width="840">
            <strong>{{$terms->term}}</strong>
        </td>
    </tr>
    <?php
        $i++;
        ?>
    @endforeach
</table>
<br />
<br />
<br />
<br />
<br />
@if (!$purOrder['master']->approved_by)
<h3 align="right" style="font-stretch: ultra-expanded">UNAPPROVED&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h3>
@endif
<table>
    <tr align="center">
        <td width="176">
            Prepared By
        </td>
        <td width="176">
            Department Manager
        </td>
        <td width="176">
            Head Of Department
        </td>
        <td width="176">
            Director/C.O.O
        </td>
        <td width="174">
            Managing Director
        </td>
    </tr>
    <tr align="center">
        <td width="176">
            {{$purOrder['master']->user_name}}
        </td>
        <td width="176">
        </td>
        <td width="176">
        </td>
        <td width="176">
        </td>
        <td width="174">
        </td>
    </tr>
</table>