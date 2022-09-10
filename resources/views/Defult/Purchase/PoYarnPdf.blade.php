<h2 align="center">Yarn Purchase Order</h2>
<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="250">To,
        <br/>
        {{ $purOrder['master']->supplier_name }}<br/>
        {{ $purOrder['master']->supplier_address }}
        </td>
        <td width="188" align="left">
            <br/><br/>
            Pay Mode:&nbsp;{{ $purOrder['master']->pay_mode }}<br/>Currency:&nbsp;&nbsp;&nbsp;{{ $purOrder['master']->currency_name }}
        </td>
        <td width="120">
            <br/>
            PO No:<br/>
            PO Date:<br/>
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
        <td width="638">
            <br/>
            Contact Details: {{ $purOrder['master']->contact_detail}}
        </td>
    </tr>
    <tr>
        <td width="638">
            <br/>
            Remarks: {{ $purOrder['master']->remarks}}
        </td>
    </tr>
</table>
<br/>
<table cellspacing="0" cellpadding="2" border="1">
    <thead>
        <tr>
            <td width="28" align="center">#</td>
            <td width="220" align="center">Item description</td>
            <td width="100" align="center">Remarks</td>
            <td width="80" align="center">Qty</td>
            <td width="30" align="center">UOM</td>
            <td width="50" align="center">Rate</td>
            <td width="130" align="center">Amount {{ $purOrder['master']->currency_name}}</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $grandAmount=0;
        ?>
        @foreach($purOrder['details'] as $key=>$datas)
        <?php
        $qty=0;
        $amount=0;
        ?>
        @foreach ($datas as $row)
        <tr>
            <td width="28" align="center">{{ $loop->iteration }}</td>
            <td width="220" align="left">{{$row->item_description}}</td>
            <td width="100" align="center">{{ $row->item_remarks }}</td>
            <td width="80" align="right">{{number_format($row->qty,2)}}</td>
            <td width="30" align="center">{{$row->uom_code}}</td>
            <td width="50" align="right">{{number_format($row->rate,2)}}</td>
            <td width="130" align="right">{{number_format($row->amount,2)}}</td>
        </tr>
        <?php
            $qty+=$row->qty;
            $amount+=$row->amount;
            $grandAmount+=$row->amount;
        ?>
        @endforeach
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td align="center" colspan="3"><strong>Grand Total</strong></td>
            <td align="right"><strong>{{number_format($qty,2)}}</strong></td>
            <td align="center"></td>
            <td align="center"></td>
            <td width="130" align="right"><strong>{{number_format($grandAmount,2)}}</strong></td>
        </tr>
    </tfoot>
</table>
<br/>
<p></p>
<strong>In Words : {{ $purOrder['master']->inword }}.</strong>
<br/>
<br/>
<table>
        <tr>
        <td width="638">
        <strong>Terms & Conditions:</strong>
        </td>
        </tr>
        <?php
        $i=1;
        ?>
    @foreach($purOrder['purchasetermscondition'] as $terms)
        <tr>
            <td width="38" align="center">
                {{$i}}.
            </td>
            <td width="600" align="left">
                <strong>{{$terms->term}}</strong>
            </td>
        </tr>
        <?php
        $i++;
        ?>
    @endforeach
</table>
<br/>
@if (!$purOrder['master']->approved_by)
    <h3 align="right" style="font-stretch: ultra-expanded">UNAPPROVED&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h3>
@endif
<br/>
<table>
    <tr>
        <td width="107"></td>
        <td width="107"></td>
        <td width="100"></td>
        <td width="112"></td>
        <td width="106"></td>
        <td width="106" align="center">@if ($purOrder['master']->approved_user_signature)<img src="{{ $purOrder['master']->approved_user_signature }}" width="100", height="40"/>
            @endif</td>
    </tr>
    <tr>
        <td width="107"  align="center">Prepared By</td>
        <td width="107">Department Manager</td>
        <td width="100">GM Marketing</td>
        <td width="112">GM Finance & Accounts</td>
        <td width="106" align="center">Director/C.O.O</td>
        <td width="106" align="center">Approved By</td>
    </tr>
    <tr>
        <td width="107" align="center">&nbsp;&nbsp;&nbsp;{{ $purOrder['master']->user_name }},&nbsp;&nbsp;{{ $purOrder['master']->contact }}<br/>
            &nbsp;&nbsp;&nbsp;{{ $purOrder['created_at'] }}
        </td>
        <td width="107">
        </td>
        <td width="100">
        </td>
        <td width="112">
        </td>
        <td width="106">
        </td>
        <td width="106" align="center">{{ $purOrder['master']->approval_emp_name }}<br/>
            {{ $purOrder['master']->approval_emp_contact }}<br/>
            {{ $purOrder['master']->approval_emp_designation }}<br/>
            {{ $purOrder['master']->approved_at }}
        </td>
    </tr>
</table>
    