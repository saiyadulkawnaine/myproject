<table cellspacing="0" cellpadding="2">
<tr>
    <td width="878"></td>
</tr>
<tr>
    <td width="708">To,<br/>
    &nbsp;{{ $purOrder['master']->supplier_name }}<br/>
    &nbsp;{{ $purOrder['master']->supplier_address }}
    </td>
    <td width="90">
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
    <td width="878">
        <br/>
        Contact Details: {{ $purOrder['master']->contact_detail}}
    </td>
</tr>
<tr>
    <td width="878">
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
            <td width="70" align="center">Sales Order</td>
            <td width="70" align="center">Body Part</td>
            <td width="100" align="center">Fabric Description</td>
            <td width="30" align="center">GSM/Wgt</td>
            <td width="50" align="center">Fabric Shape</td>
            <td width="50" align="center">Fabric Looks</td>
            <td width="80" align="center">Fabric Color</td>
            <th width="60" align="center">Dyeing<br/>Type</th>
            <th width="60" align="center">Aop<br/> Type</th>
            <th width="60" align="center">Coverage%</th>
            <th width="60" align="center">No of<br/>Color</th>
            <td width="65" align="center">WO.Qty</td>
            <td width="30" align="center">Rate</td>
            <td width="65" align="center">Amount {{ $purOrder['master']->currency_name}}</td>
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
         <tr>
            <td width="28" align="center">{{ $loop->iteration }}</td>
            <td width="70" align="center">{{$row->sale_order_no}}</td>
            <td width="70" align="center">{{$row->gmtspart_name}}</td>
            <td width="100" align="center">{{$row->fabric_description}}</td>
            <td width="30" align="center">{{$row->gsm_weight}}</td>
            <td width="50" align="center">{{$row->fabricshape}}</td>
            <td width="50" align="center">{{$row->fabriclooks}}</td>
            <td width="80" align="center">{{$row->fabric_color}}</td>
            <td width="60" align="right">{{$row->dyeing_type_id}}</td>
            <td width="60" align="right">{{$row->embelishment_type_id}}</td>
            <td width="60" align="right">{{$row->coverage}}</td>
            <td width="60" align="right">{{$row->impression}}</td>
            <td width="65" align="right">{{$row->qty}}</td>
            <td width="30" align="right">{{$row->rate}}</td>
            <td width="65" align="right">{{$row->amount}}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td width="28" align="center"></td>
            <td colspan="11"></td>
            <td width="65" align="right">{{$totQty}}</td>
            <td width="30" align="right"></td>
            <td width="65" align="right">{{$totAmount}}</td>
        </tr>
        
    </tfoot>
</table>
<br/>
<strong>In Words : {{ $purOrder['master']->inword }}.</strong>
<br/>
<br/>
<table>
    <tr>
    <td width="658">
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
        <td width="860">
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
@if (!$purOrder['master']->approved_by)
    <h3 align="right" style="font-stretch: ultra-expanded">UNAPPROVED&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h3>
@endif
<table>
<tr align="center">
    <td width="147"></td>
    <td width="147"></td>
    <td width="146"></td>
    <td width="146"></td>
    <td width="146"></td>
    <td width="146">@if ($purOrder['master']->approved_user_signature)<img src="{{ $purOrder['master']->approved_user_signature }}" width="100", height="40"/>
            @endif
    </td>
</tr>
<tr align="center">
    <td width="147">
        Prepared By
    </td>
    <td width="147">
        Department Manager
    </td>
    <td width="146">
        GM Marketing
    </td>
    <td width="146">
        GM Finance & Accounts
    </td>
    <td width="146">
        Director/C.O.O
    </td>
    <td width="146">
        Approved By
    </td>
</tr>
<tr align="center">
    <td width="147">
        &nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;{{ $purOrder['master']->user_name }},&nbsp;&nbsp;{{ $purOrder['master']->contact }},&nbsp;{{ $purOrder['created_at'] }}
    </td>
    <td width="147">
    </td>
    <td width="146">
    </td>
    <td width="146">
    </td>
    <td width="146">
    </td>
    <td width="146">{{ $purOrder['master']->approval_emp_name }}<br/>
            {{ $purOrder['master']->approval_emp_contact }}<br/>
            {{ $purOrder['master']->approval_emp_designation }}<br/>
            {{ $purOrder['master']->approved_at }}
    </td>
</tr>
</table>