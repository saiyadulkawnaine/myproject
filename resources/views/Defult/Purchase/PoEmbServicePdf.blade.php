<?php
//print_r($purOrder['yarn_array']);
//die;
?>
<h2 align="center" style="font-size:50px">Embelishment Work Order</h2>
<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="748">To,<br/>
        {{ $purOrder['master']->supplier_name }}<br/>
        {{ $purOrder['master']->supplier_address }}
        </td>
        <td width="120">
            <br/>
            WO No:<br/>
            WO Date:<br/>
            Delivery Start:<br/>
            Delivery End:<br/>
        </td>
        <td width="80">
            <br/>
             {{ $purOrder['master']->po_no }}<br/>
             {{ $purOrder['master']->po_date }}<br/>
             {{ $purOrder['master']->delv_start_date }}<br/>
             {{ $purOrder['master']->delv_end_date }}<br/>
        </td>
    </tr>
    <tr>
        <td width="948">Remarks: {{ $purOrder['master']->remarks}} </td>
    </tr>
</table>
<br/>
<table cellspacing="0" cellpadding="2" border="1">
    <thead>
        <tr>
            <td width="28" align="center">#</td>
            <td width="100" align="center">Sales Order</td>
            <td width="100" align="center">GMT. Part</td>
            <td width="100" align="center">GMT. Item</td>
            <td width="60" align="center">GMT. Color</td>
            <td width="60" align="center">GMT. Size</td>
            <td width="80" align="center">Emb. Name</td>
            <td width="80" align="center">Emb. Type</td>
            <td width="60" align="center">Emb. Size</td>
            <td width="60" align="center">Qty</td>
            <td width="60" align="center">Rate</td>
            <td width="60" align="center">Amount {{ $purOrder['master']->currency_name}}</td>
            <td width="80" align="center">Remarks</td>
        </tr>
    </thead>
    <tbody>
        <?php
            $totQty=0;
            $totPcsQty=0;
            $totAmount=0;
        ?>
        @foreach($purOrder['details'] as $row)
        <?php
            $totQty+=$row->qty;
            $totAmount+=$row->amount;
        ?>
         <tr nobr="true">
            <td width="28" align="center">{{ $loop->iteration }}</td>
            <td width="100" align="center">{{$row->sale_order_no}}</td>
            <td width="100" align="center">{{$row->gmtspart_name}}</td>
            <td width="100" align="center">{{$row->item_description}}</td>
            <td width="60" align="center">{{$row->color_name}}</td>
            <td width="60" align="center">{{$row->size_name}}</td>
            <td width="80" align="center">{{$row->embelishment_name}}</td>
            <td width="80" align="center">{{$row->embelishment_type}}</td>
            <td width="60" align="center">{{$row->embelishment_size}}</td>
            <td width="60" align="right">{{$row->qty}}</td>
            <td width="60" align="right">{{$row->rate}}</td>
            <td width="60" align="right">{{number_format($row->amount,2)}}</td>
            <td width="80" align="center">{{$row->remarks}}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td width="28" align="center"></td>
            <td width="100" align="center">Total</td>
            <td width="100" align="center"></td>
            <td width="100" align="center"></td>
            <td width="60" align="center"></td>
            <td width="60" align="center"></td>
            <td width="80" align="center"></td>
            <td width="80" align="center"></td>
            <td width="60" align="center"></td>
            <td width="60" align="right">{{$totQty}}</td>
            <td width="60" align="center"></td>
            <td width="60" align="right">{{number_format($totAmount,2)}}</td>
            <td width="80" align="center"></td>
        </tr>
    </tfoot>
</table>
<p></p>
<p><strong>In Words : {{ $purOrder['master']->inword }}.</strong></p>
<table>
    <tr>
    <td width="948"><strong>Terms & Conditions:</strong>
    </td>
    </tr>
    <?php
    $i=1;
    ?>
    @foreach($purOrder['purchasetermscondition'] as $terms)
    <tr>
        <td width="48">
            {{$i}}.
        </td>
        <td width="900">
            <strong>{{$terms->term}}</strong>
        </td>
    </tr>
    <?php
    $i++;
    ?>
@endforeach
</table>
<p></p>
<p>@if (!$purOrder['master']->approved_by)
    <h3 align="right" style="font-stretch: ultra-expanded">UNAPPROVED&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h3>
@endif</p>
<table>
    <tr align="center">
        <td width="158"></td>
        <td width="158"></td>
        <td width="158"></td>
        <td width="158"></td>
        <td width="158"></td>
        <td width="158">@if ($purOrder['master']->approved_user_signature)<img src="{{ $purOrder['master']->approved_user_signature }}" width="100", height="40"/>
                @endif</td>
    </tr>
    <tr align="center">
        <td width="158">
            Prepared By
        </td>
        <td width="158">
            Department Manager
        </td>
        <td width="158">
            Head of Department
        </td>
        <td width="158">
            GM Finance & Accounts
        </td>
        <td width="158">
            Director/C.O.O
        </td>
        <td width="158">
            Approved By
        </td>
    </tr>
    <tr align="center">
        <td width="158">{{$purOrder['master']->user_name}}
        </td>
        <td width="158">
        </td>
        <td width="158">
        </td>
        <td width="158">
        </td>
        <td width="158">
        </td>
        <td width="158">{{ $purOrder['master']->approval_emp_name }}<br/>
                {{ $purOrder['master']->approval_emp_contact }}<br/>
                {{ $purOrder['master']->approval_emp_designation }}<br/>
                {{ $purOrder['master']->approved_at }}
        </td>
    </tr>
</table>



