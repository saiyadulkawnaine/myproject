<h2 align="center">Dyes/Chemical Purchase Order</h2>
<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="250">To,
        <br/>
        {{ $purOrder['master']->supplier_name }}<br/>
        {{ $purOrder['master']->supplier_address }}
        </td>
        <td width="188" align="left">
            <br/><br/>
            Pay Mode:&nbsp;{{ $purOrder['master']->pay_mode }}<br/>
            Currency:&nbsp;&nbsp;&nbsp;{{ $purOrder['master']->currency_name }}<br/>
            Exch. Rate:&nbsp;&nbsp;&nbsp;{{ $purOrder['master']->exch_rate }}
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
            <td width="80" align="center">Requisition No</td>
            <td width="80" align="center">Item <br/>Category</td>
            <td width="130" align="center">Item description</td>
            <td width="100" align="center">Remarks</td>
            <td width="60" align="center">Qty</td>
            <td width="30" align="center">UOM</td>
            <td width="65" align="center">Rate</td>
            <td width="65" align="center">Amount {{ $purOrder['master']->currency_name}}</td>
        </tr>
    </thead>
    <tbody>
        <?php
            $grandAmount=0;
        ?>
        {{-- @foreach($purOrder['details'] as $key=>$datas) --}}
        <?php
            $qty=0;
            $amount=0;
            $tRate=0;
        ?>
        @foreach ($data as $row)
        <tr>
            <td width="28" align="center">{{ $loop->iteration }}</td>
            <td width="80" align="center">{{$row->requisition_no}}</td>
            <td width="80" align="center">{{ $row->category_name }}</td>
            <td width="130" align="left">{{ $row->sub_class_name }}, {{ $row->item_description }} , {{ $row->specification }}</td>
            {{-- <td width="210" align="left">{{$row->custom_name}}</td> --}}
            <td width="100" align="center">{{ $row->item_remarks }}</td>
            <td width="60" align="right">{{number_format($row->qty,2)}}</td>
            <td width="30" align="center">{{$row->uom_code}}</td>
            <td width="65" align="right">{{number_format($row->rate,4)}}</td>
            <td width="65" align="right">{{number_format($row->amount,2)}}</td>
        </tr>
        <?php
            $qty+=$row->qty;
            $amount+=$row->amount;
            $grandAmount+=$row->amount;
            //$tRate=0;
            if($qty){
                $tRate=$amount/$qty;
            }
        ?>
        @endforeach
        <tr>
            <td width="418" align="right"><strong>Item Total</strong></td>
            <td width="60" align="right">{{number_format($qty,2)}}</td>
            <td width="30" align="right"></td>
            <td width="65" align="right"></td>
            <td width="65" align="right">{{number_format($amount,2)}}</td>
        </tr>
       {{--  @endforeach --}}
    </tbody>
    <tfoot>
        <tr>
            <td width="573" align="right"><strong>Grand Total</strong></td>
            <td width="65" align="right">{{number_format($grandAmount,2)}}</td>
        </tr>
    </tfoot>
</table>
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
            <td width="38">
                {{$i}}.
            </td>
            <td width="600">
                <strong>{{$terms->term}}</strong>
            </td>
        </tr>
        <?php
        $i++;
        ?>
    @endforeach
</table>
<br/>
<br/>
<br/>
@if (!$purOrder['master']->approved_by)
    <h3 align="right" style="font-stretch: ultra-expanded">UNAPPROVED&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h3>
@endif
<br/>
<table>
    <tr align="center">
        <td width="107"></td>
        <td width="107"></td>
        <td width="106"></td>
        <td width="106"></td>
        <td width="106"></td>
        <td width="106">@if ($purOrder['master']->approved_user_signature)<img src="{{ $purOrder['master']->approved_user_signature }}" width="100", height="40"/>
            @endif</td>
    </tr>
    <tr align="center">
        <td width="107">
            Prepared By
        </td>
        <td width="107">
            Department Manager
        </td>
        <td width="106">
            GM Marketing
        </td>
        <td width="106">
            GM Finance & Accounts
        </td>
        <td width="106">
            Director/C.O.O
        </td>
        <td width="106">
            Approved By
        </td>
    </tr>
    <tr align="center">
        <td width="107">&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;{{ $purOrder['master']->user_name }},&nbsp;&nbsp;{{ $purOrder['master']->contact }}<br/>
            &nbsp;&nbsp;&nbsp;{{ $purOrder['created_at'] }}
        </td>
        <td width="107">
        </td>
        <td width="106">
        </td>
        <td width="106">
        </td>
        <td width="106">
        </td>
        <td width="106">{{ $purOrder['master']->approval_emp_name }}<br/>
            {{ $purOrder['master']->approval_emp_contact }}<br/>
            {{ $purOrder['master']->approval_emp_designation }}<br/>
            {{ $purOrder['master']->approved_at }}
        </td>
    </tr>
</table>
    