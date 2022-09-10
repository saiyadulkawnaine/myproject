<p align="center" style="font-size:2.1em"><strong><u>Dyeing/Finishing Work Order</u></strong></p>
<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="708">To,<br/>
        {{ $purOrder['master']->supplier_name }}<br/>
        {{ $purOrder['master']->supplier_address }}
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
        <td width="878" colspan="3">
            <br/>
            Remarks: {{ $purOrder['master']->remarks}}
        </td>
    </tr>
</table>
<p></p>
<table cellspacing="0" cellpadding="2" border="1">
    <thead>
        <tr>
            <td width="28" align="center">#</td>
            <td width="80" align="center">Style & Buyer</td>
            <td width="70" align="center">Sales Order</td>
            <td width="70" align="center">Body Part</td>
            <td width="100" align="center">Fabric Description</td>
            <td width="30" align="center">GSM/Wgt</td>
            <td width="50" align="center">Fabric Shape</td>
            <td width="50" align="center">Fabric Looks</td>
            <td width="80" align="center">Fabric Color</td>
            <td width="70" align="center">Color Range</td>
            <td width="50" align="center">Dye Type</td>
            <td width="60" align="center">Qty</td>
            <td width="55" align="center">Fin. Qty</td>
            <td width="30" align="center">EQV Pcs</td>
            <td width="45" align="center">Rate</td>
            <td width="" align="center">Amount {{ $purOrder['master']->currency_name}}</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $totQty=0;
        $totFinQty=0;
        $totPcsQty=0;
        $totAmount=0;
        $i=1;
        ?>

        @foreach($purOrder['details'] as $row)
        <?php
        $totQty+=$row->qty;
        $totFinQty+=$row->fin_qty;
        $totPcsQty+=$row->pcs_qty;
        $totAmount+=$row->amount;
        ?>
         <tr>
            <td width="28" align="center">{{ $i++ }}</td>
            <td width="80" align="center">{{ $row->style_ref }}<br/>{{ $row->buyer_name }}</td>
            <td width="70" align="center">{{$row->sale_order_no}}</td>
            <td width="70" align="center">{{$row->gmtspart_name}}</td>
            <td width="100" align="center">{{$row->fabric_description}}</td>
            <td width="30" align="center">{{$row->gsm_weight}}</td>
            <td width="50" align="center">{{$row->fabricshape}}</td>
            <td width="50" align="center">{{$row->fabriclooks}}</td>
            <td width="80" align="center">{{$row->fabric_color}}</td>
            <td width="70" align="center">{{ $row->colorrange }}</td>
            <td width="50" align="center">{{ $row->dyeingtype }}</td>
            <td width="60" align="right">{{ number_format($row->qty,2,'.',',') }}</td>
            <td width="55" align="right">{{ number_format($row->fin_qty,2,'.',',') }}<br/>({{number_format($row->process_loss,2,'.',',')}} %)</td>
            <td width="30" align="right">{{ number_format($row->pcs_qty,2,'.',',') }}</td>
            <td width="45" align="right">{{ number_format($row->rate,4,'.',',') }}</td>
            <td width="" align="right">{{ number_format($row->amount,2,'.',',') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td align="right" colspan="11">Total</td>
            <td width="60" align="right">{{ number_format($totQty ,2,'.',',')}}</td>
            <td width="55" align="right">{{ number_format($totFinQty ,2,'.',',')}}</td>
            <td width="30" align="right">{{ number_format($totPcsQty ,2,'.',',')}}</td>
            <td width="45" align="right"></td>
            <td width="" align="right">{{ number_format($totAmount ,2,'.',',')}}</td>
        </tr>
    </tfoot>
</table>
<p></p>
<p></p>
@if($purOrder['yarncolors']->isNotEmpty())
<p></p>
<p>Fabric Color Yarn Color Mapping</p>
<p></p>
<table cellspacing="0" cellpadding="2" border="1">
    <thead>
        <tr>
            <td width="28" align="center">#</td>
            <td width="80" align="center">Style & Buyer</td>
            <td width="70" align="center">Sales Order</td>
            <td width="70" align="center">Body Part</td>
            <td width="100" align="center">Fabric Description</td>
            <td width="30" align="center">GSM/Wgt</td>
            <td width="50" align="center">Fabric Shape</td>
            <td width="50" align="center">Fabric Looks</td>
            <!-- <td width="80" align="center">Fabric Color</td> -->
            <td width="70" align="center">GMT Color</td>
            <td width="70" align="center">Yarn Color</td>
            <td width="" align="center"></td>
        </tr>
    </thead>
    <?php
    $i=1;
    ?>
    <tbody>
        @foreach($purOrder['yarncolors'] as $row)
         <tr>
            <td width="28" align="center">{{ $i++ }}</td>
            <td width="80" align="center">{{ $row->style_ref }}<br/>{{ $row->buyer_name }}</td>
            <td width="70" align="center">{{$row->sale_order_no}}</td>
            <td width="70" align="center">{{$row->gmt_part_name}}</td>
            <td width="100" align="center">{{$row->fabric_description}}</td>
            <td width="30" align="center">{{$row->gsm_weight}}</td>
            <td width="50" align="center">{{$row->fabricshape}}</td>
            <td width="50" align="center">{{$row->fabriclooks}}</td>
            {{-- <td width="80" align="center">{{$row->fabric_color_name}}</td> --}}
            <td width="70" align="center">{{ $row->gmt_color_name }}</td>
            <td width="70" align="center">{{ $row->yarn_color_name }}</td>
            <td width="" align="center"></td>
        </tr>
        @endforeach
    </tbody>
</table>
<p></p>
@endif
<p><strong>In Words : {{ $purOrder['master']->inword }}.</strong></p>
<p></p>
<table>
    <tr>
        <td width="878"><strong>Terms & Conditions:</strong></td>
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
<p></p>
<p></p>
<p></p>
<p></p>
<table>
<tr align="center">
    <td width="219"></td>
    <td width="219"></td>
    <td width="219"></td>
    <td width="221"></td>
</tr>
<tr align="center">
    <td width="219"></td>
    <td width="219"></td>
    <td width="219"></td>
    <td width="221"></td>
</tr>
<tr>
    <td colspan="4" width="878">@if (!$purOrder['master']->approved_by)
    <h3 align="right" style="font-stretch: ultra-expanded">UNAPPROVED&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h3>
@endif
    </td>
</tr>
<tr align="center">
    <td width="219"></td>
    <td width="219"></td>
    <td width="219"></td>
    <td width="221">@if ($purOrder['master']->approved_user_signature)<img src="{{ $purOrder['master']->approved_user_signature }}" width="100", height="40"/>
            @endif</td>
</tr>
<tr align="center">
    <td width="219">
        Prepared By
    </td>
    <td width="219">
        Head Of Department
    </td>
    <td width="219">
        Director/C.O.O
    </td>
    <td width="221">
        Approved By
    </td>
</tr>
<tr align="center">
    <td width="219">
        {{$purOrder['master']->user_name}}
    </td>
    <td width="219">
    </td>
    <td width="219">
    </td>
    <td width="221">{{ $purOrder['master']->approval_emp_name }}<br/>
            {{ $purOrder['master']->approval_emp_contact }}<br/>
            {{ $purOrder['master']->approval_emp_designation }}<br/>
            {{ $purOrder['master']->approved_at }}
    </td>
</tr>
</table>