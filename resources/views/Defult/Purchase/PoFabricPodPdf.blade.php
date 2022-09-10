<h2 align="center">Fabric Purchase Order</h2>
<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="348">To,
        <br/>
        {{ $purOrder['master']->supplier_name }}<br/>
        {{ $purOrder['master']->supplier_address }}
        </td>
        <td width="200" align="left">
            {{-- <br/><br/>
            Pay Mode:&nbsp;{{ $purOrder['master']->pay_mode }}<br/>Currency:&nbsp;&nbsp;&nbsp;{{ $purOrder['master']->currency_name }} --}}
        </td>
        <td width="220">
            <br/>
            PO No:<br/>
            PO Date:<br/>
            Delivery Start:<br/>
            Delivery End:<br/>
        </td>
        <td width="100">
            <br/>
            {{ $purOrder['master']->po_no }}<br/>
            {{ $purOrder['master']->po_date }}<br/>
            {{ $purOrder['master']->delv_start_date }}<br/>
            {{ $purOrder['master']->delv_end_date }}<br/>
        </td>
    </tr>
    <tr>
        <td width="878">
            {{-- <br/>
            Contact Details: {{ $purOrder['master']->contact_detail}} --}}
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
            <td width="25" align="center">#</td>
            <td width="100" align="center">Style & Buyer</td>
            <td width="80" align="center">Sales Order</td>
            <td width="80" align="center">Fabric Nature</td>
            <td width="50" align="center">Gmt Part</td>
            <td width="100" align="center">Fabrication</td>
            <td width="35" align="center">GSM</td>
            <td width="35" align="center">Dia</td>
            <td width="70" align="center">Fabric Look</td>
            <td width="70" align="center">Fabric Shape</td>
            <td width="70" align="center">Fabric Color</td>
            <td width="40" align="center">UOM</td>
            <td width="70" align="center">PO Qty</td>
            <td width="65" align="center">Rate</td>
            <td width="73" align="center">Amount {{ $purOrder['master']->currency_name}}</td>
        </tr>
    </thead>
    <tbody>
        <?php
            $i=1;
            $grandAmount=0;
            $qty=0;
            $amount=0;
        ?>
        @foreach($purOrder['details'] as $key=>$datas)
        @foreach($datas as $row)
        <tr>
            <td width="25" align="center">{{ $i++ }}</td>
            <td width="100" align="center">{{ $row->style_ref }} & {{ $row->buyer_code }}</td>
            <td width="80" align="center">{{ $row->sale_order_no }}</td>
            <td width="80" align="center">{{ $row->fabricnature }}</td>
            <td width="50" align="center">{{ $row->gmtspart }}</td>
            <td width="100" align="center">{{ $row->fabric_description }}</td>
            <td width="35" align="center">{{ $row->gsm_weight }}</td>
            <td width="35" align="center">{{ $row->dia }}</td>
            <td width="70" align="center">{{ $row->fabriclooks }}</td>
            <td width="70" align="center">{{ $row->fabricshape }}</td>
            <td width="70" align="center">{{ $row->fabric_color_name }}</td>
            <td width="40" align="center">{{ $row->uom_code }}</td>
            <td width="70" align="right">{{ number_format($row->qty,2) }}</td>
            <td width="65" align="right">{{ number_format($row->rate,4) }}</td>
            <td width="73" align="right">{{ number_format($row->amount,2) }}</td>
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
            <td width="755" align="right"><strong>Total</strong></td>
            <td width="70" align="right"><strong>{{ number_format($qty,2) }}</strong></td>
            <td width="65" align="right"></td>
            <td width="73" align="right"><strong>{{ number_format($grandAmount,2) }}</strong></td>
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
        <td width="878">
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
            <td width="800" align="left">
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
<br/>
@if (!$purOrder['master']->approved_by)
    <h3 align="right" style="font-stretch: ultra-expanded">UNAPPROVED&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h3>
@endif
<table>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr align="center">
        <td width="219"></td>
        <td width="220"></td>
        <td width="220"></td>
        <td width="219">@if ($purOrder['master']->approved_user_signature)<img src="{{ $purOrder['master']->approved_user_signature }}" width="100", height="40"/>
            @endif</td>
    </tr>
    <tr align="center">
        <td width="219">
            Prepared By
        </td>
        <td width="220">
            Head of Department
        </td>
        <td width="220">
            Head of Accounts & Finance
        </td>
        <td width="219">
            Authorised By
        </td>
    </tr>
    <tr align="center">
        <td width="220">&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;{{ $purOrder['master']->user_name }},&nbsp;&nbsp;{{ $purOrder['master']->contact }}<br/>
            &nbsp;&nbsp;&nbsp;{{ $purOrder['created_at'] }}
        </td>
        <td width="219">
        </td>
        <td width="220">
        </td>
        <td width="219">{{ $purOrder['master']->approval_emp_name }}<br/>
            {{ $purOrder['master']->approval_emp_contact }}<br/>
            {{ $purOrder['master']->approval_emp_designation }}<br/>
            {{ $purOrder['master']->approved_at }}
        </td>
    </tr>
</table>
    