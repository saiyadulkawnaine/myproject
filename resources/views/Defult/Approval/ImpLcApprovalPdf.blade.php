<p align="right">{{ $rows->id }}</p>
<h1 align="center">{{ $rows->company_name }}</h1>
<h2 align="center">Import LC Proposal</h2>
<h3 align="center">{{ $rows->commodity }} {{ $rows->lc_type }} @if ($rows->lc_type_id==2)
   {{ $rows->margin_deposit }} %
@endif</h3>
<p></p>
@if ($rows->lc_type_id==1)
    <table cellpadding="2" cellspacing="0" style="margin: 0 auto">
        <tr>
            <th width="200"><strong>{{ $rows->bank_name }}<br/>{{ $rows->branch_name }}<br/>{{ $rows->branch_address }}</strong>
            </th>
            <th width="250"><strong>LC/SC Value:</strong>&nbsp;{{ $rows->currency_symbol }} {{ number_format($rows->lc_sc_value,2) }}<br/><strong>BTB Limit:</strong>&nbsp;{{ $rows->currency_symbol }}  {{ number_format($rows->limit_btb_open,2) }}<br/><strong>BTB Opened:</strong>&nbsp;{{ $rows->currency_symbol }}  {{ number_format($rows->btb_opened_amount,2) }}<br/><strong>Limit Balance:</strong>&nbsp;{{ $rows->currency_symbol }} {{ number_format($rows->yet_btb_open,2) }}
            </th>
            <th width="250"><strong>Proposed LC Value: </strong>&nbsp;{{ $rows->currency_symbol }} {{ number_format($rows->lc_amount,2) }}<br/><strong>Fund Available: </strong>&nbsp;{{ $rows->currency_symbol }} {{ number_format($rows->fund_available,2) }}<br/><strong>Limit Booked:</strong>&nbsp;{{ $rows->currency_symbol }}  {{ number_format($rows->limit_btb_booked,2) }}<br/><strong>Supplier: </strong> {{ $rows->supplier_name }}
            </th>
        </tr>
        <tr><td colspan="3"></td></tr>
    </table>
    <p><strong>File No:</strong>{{ $rows->file_no }}&nbsp;&nbsp;&nbsp;<strong>Buyer Name:</strong>{{ $rows->buyer_name }}&nbsp;&nbsp;&nbsp;<strong>Order Value:</strong>&nbsp;&nbsp;{{ number_format($rows->so_amount,2) }}&nbsp;&nbsp;&nbsp;<strong>Plan Ship Date:</strong>&nbsp;&nbsp;{{$rows->last_delivery_date }}</p>
    <p><strong>Remarks: {{ $rows->remarks }}</strong></p>
    <p><strong>Budget & BTB Status</strong></p>
    <?php
        $totalBugetAmount=$rows->fin_fab_req_amount+$rows->yarn_req_amount+$rows->trim_req_amount+$rows->dying_req_amount+$rows->aop_req_amount+$rows->dyed_yarn_rq_amount+$rows->kniting_amount;
        $totalBTBAmount=$rows->fabric_btb_amount+$rows->yarn_btb_amount+$rows->trims_btb_amount+$rows->dye_chem_btb_amount+$rows->dyeing_service_btb_amount+$rows->aop_btb_amount+$rows->yarn_dyeing_btb_amount+$rows->knit_btb_amount+$rows->others_btb_amount;
        $finfabPer=0;
        if ($rows->so_amount) {
            $finfabPer=($rows->fin_fab_req_amount/$rows->so_amount)*100;
        }
        $yarnPer=0;
        if ($rows->so_amount) {
            $yarnPer=($rows->yarn_req_amount/$rows->so_amount)*100;
        }
        $trimsPer=0;
        if ($rows->so_amount) {
            $trimsPer=($rows->trim_req_amount/$rows->so_amount)*100;
        }
        $dyeingPer=0;
        if ($rows->so_amount) {
            $dyeingPer=($rows->dying_req_amount/$rows->so_amount)*100;
        }
        $aopPer=0;
        if ($rows->so_amount) {
            $aopPer=($rows->aop_req_amount/$rows->so_amount)*100;
        }
        $yarnDyeingPer=0;
        if ($rows->so_amount) {
            $yarnDyeingPer=($rows->dyed_yarn_rq_amount/$rows->so_amount)*100;
        }
        $knittingPer=0;
        if ($rows->so_amount) {
            $knittingPer=($rows->kniting_amount/$rows->so_amount)*100;
        }
    ?>
    <table cellpadding="2" cellspacing="0" border="1">
        <tr>
            <td align="center"><strong>Particulars</strong></td>
            <td align="center"><strong>Fabric</strong></td>
            <td align="center"><strong>Yarn</strong></td>
            <td align="center"><strong>Trims</strong></td>
            <td align="center"><strong>Dyes & Chem</strong></td>
            <td align="center"><strong>Dyeing</strong></td>
            <td align="center"><strong>AOP</strong></td>
            <td align="center"><strong>Yarn Dyeing</strong></td>
            <td align="center"><strong>Knitting</strong></td>
            <td align="center"><strong>Others</strong></td>
            <td align="center"><strong>Total</strong></td>
        </tr>
        <tr>
            <td align="left"><strong>Budget</strong><br>%</td>
            <td align="right">{{ number_format($rows->fin_fab_req_amount,2) }}<br><strong>{{ number_format($finfabPer,2) }}%</strong></td>
            <td align="right">{{ number_format($rows->yarn_req_amount,2) }}<br><strong>{{ number_format($yarnPer,2) }}%</strong></td>
            <td align="right">{{ number_format($rows->trim_req_amount,2) }}<br><strong>{{ number_format($trimsPer,2) }}%</strong></td>
            <td align="center">N/A</td>
            <td align="right">{{ number_format($rows->dying_req_amount,2) }}<br><strong>{{ number_format($dyeingPer,2) }}%</strong></td>
            <td align="right">{{ number_format($rows->aop_req_amount,2) }}<br><strong>{{ number_format($aopPer,2) }}%</strong></td>
            <td align="right">{{ number_format($rows->dyed_yarn_rq_amount,2) }}<br><strong>{{ number_format($yarnDyeingPer,2) }}%</strong></td>
            <td align="right">{{ number_format($rows->kniting_amount,2) }}<br><strong>{{ number_format($knittingPer,2) }}%</strong></td>
            <td align="center">N/A</td>
            <td align="right">{{ number_format($totalBugetAmount,2) }}</td>
        </tr>
        <tr>
            <td align="left"><strong>BTB Open & Proposed</strong></td>
            <td align="right">{{ number_format($rows->fabric_btb_amount,2) }}</td>
            <td align="right">{{ number_format($rows->yarn_btb_amount,2) }}</td>
            <td align="right">{{ number_format($rows->trims_btb_amount,2) }}</td>
            <td align="right">{{ number_format($rows->dye_chem_btb_amount,2) }}</td>
            <td align="right">{{ number_format($rows->dyeing_service_btb_amount,2) }}</td>
            <td align="right">{{ number_format($rows->aop_btb_amount,2) }}</td>
            <td align="right">{{ number_format($rows->yarn_dyeing_btb_amount,2) }}</td>
            <td align="right">{{ number_format($rows->knit_btb_amount,2) }}</td>
            <td align="right">{{ number_format($rows->others_btb_amount,2) }}</td>
            <td align="right">{{ number_format($totalBTBAmount,2) }}</td>
        </tr>
        <tr>
            <td align="left"><strong>Balance</strong></td>
            <td align="right">{{ number_format($rows->fin_fab_req_amount-$rows->fabric_btb_amount,2) }}</td>
            <td align="right">{{ number_format($rows->yarn_req_amount-$rows->yarn_btb_amount,2) }}</td>
            <td align="right">{{ number_format($rows->trim_req_amount-$rows->trims_btb_amount,2) }}</td>
            <td align="right"></td>
            <td align="right">{{ number_format($rows->dying_req_amount-$rows->dyeing_service_btb_amount,2) }}</td>
            <td align="right">{{ number_format($rows->aop_req_amount-$rows->aop_btb_amount,2) }}</td>
            <td align="right">{{ number_format($rows->dyed_yarn_rq_amount-$rows->yarn_dyeing_btb_amount,2) }}</td>
            <td align="right">{{ number_format($rows->kniting_amount-$rows->knit_btb_amount,2) }}</td>
            <td align="right"></td>
            <td align="right">{{ number_format($totalBugetAmount-$totalBTBAmount,2) }}</td>
        </tr>
    </table>
@elseif ($rows->lc_type_id==3 || $rows->lc_type_id==5)
    <p><strong>File No:</strong>{{ $rows->file_no }}&nbsp;&nbsp;&nbsp;<strong>Buyer Name:</strong>{{ $rows->buyer_name }}&nbsp;&nbsp;&nbsp;<strong>Order Value:</strong>&nbsp;&nbsp;{{ number_format($rows->so_amount,2) }}&nbsp;&nbsp;&nbsp;<strong>Plan Ship Date:</strong>&nbsp;&nbsp;{{$rows->last_delivery_date }}</p>
    <p><strong>Remarks: {{ $rows->remarks }}</strong></p>
@else
    <table cellpadding="2" cellspacing="0" style="margin: 0 auto">
        <tr>
            <th width="250"><strong>{{ $rows->bank_name }}<br/>{{ $rows->branch_name }}<br/>{{ $rows->branch_address }}</strong></th>
            <th width="100"></th>
            <th width="250"><strong>LC Value:{{ $rows->currency_symbol }} {{ $rows->lc_amount }}<br/>Supplier: {{ $rows->supplier_name }}</strong></th>
        </tr>
    </table>
    <p><strong>Remarks: {{ $rows->remarks }}</strong></p>
@endif
<?php
    $grandTotalQty=0;
    $grandTotalAmount=0;
?>
@foreach ($datas as $purchase_order_id=>$row)
<p></p>
<p><strong>PO NO: {{ $poNoArr[$purchase_order_id]['po_no'] }} &nbsp;&nbsp;&nbsp;Remarks: {{ $poNoArr[$purchase_order_id]['remarks'] }}</strong></p>

<?php
    $i=1;
    $totalQty=0;
    $totalAmount=0;
?>
    <table border="1" cellpadding="2" cellspacing="0" style="margin: 0 auto">
        <tr>
            <td align="center" width="30">SL</td>
            {{-- <td align="center" width="100">PO NO</td> --}}
            <td align="center" width="250">Item Desctription</td>
            <td align="center" width="50">UOM</td>
            <td align="center" width="80">PO Qty</td>
            <td align="center" width="80">PO Rate</td>
            <td align="center" width="80">PO Amount</td>
        </tr>
        @foreach ($row as $data)
        <tbody>
            <tr>
                <td align="center" width="30">{{ $i++ }}</td>
                {{-- <td width="100" align="left">{{ $data->po_no }}</td> --}}
                <td width="250" align="left">{{ $data->item_description }}</td>
                <td align="center" width="50">{{ $data->uom_code }}</td>
                <td width="80" align="right">{{ number_format($data->po_qty,2) }}</td>
                <td width="80" align="right">{{ number_format($data->po_rate,4) }}</td>
                <td width="80" align="right">{{ number_format($data->po_amount,2) }}</td>
            </tr>
        </tbody> 
        <?php
            $totalQty+=$data->po_qty;
            $totalAmount+=$data->po_amount;
        ?>
        @endforeach
    </table>
    <table cellpadding="2" cellspacing="0" style="margin: 0 auto">
        <tr>
            <td align="center" width="30"></td>
            {{-- <td align="center" width="100"></td> --}}
            <td align="center" width="250"><strong>Total</strong></td>
            <td align="center" width="50"></td>
            <td align="right" width="80" align="right"><strong>{{ number_format($totalQty,2) }}</strong></td>
            <td align="center" width="80"></td>
            <td align="right" width="80" align="right"><strong>{{ number_format($totalAmount,2) }}</strong></td>
        </tr>
        <tr>
            <td align="center" colspan="6"></td>
        </tr>
    </table>
    <?php
        $grandTotalQty+=$totalQty;
        $grandTotalAmount+=$totalAmount;
    ?>
    <table cellpadding="2" cellspacing="0" style="margin: 0 auto">
        <tr>
            <td align="left" width="200"></td>
            <td align="right" width="250">@if ($poNoArr[$purchase_order_id]['approved_by_signature'])<img src="{{ $poNoArr[$purchase_order_id]['approved_by_signature'] }}" width="80", height="20"/>
                @endif</td>
            <td align="left" width="200"></td>
        </tr>
        <tr>
            <td align="left" width="200"></td>
            <td align="right" width="250">@if ($poNoArr[$purchase_order_id]['approved_by'])<strong>{{ $poNoArr[$purchase_order_id]['approved_by'] }}</strong>@endif</td>
            <td align="left" width="200">@if ($poNoArr[$purchase_order_id]['approved_by_designation'])<strong>{{ $poNoArr[$purchase_order_id]['approved_by_designation'] }}</strong>
            @endif</td>
        </tr>
    </table>
@endforeach
<p></p>
<table cellpadding="2" cellspacing="0" style="margin: 0 auto">
    <tr>
        <td align="center" width="30"></td>
        <td align="center" width="250"><strong>Grand Total</strong></td>
        <td align="center" width="50"></td>
        <td align="right" width="80" align="right"><strong>{{ number_format($grandTotalQty,2) }}</strong></td>
        <td align="center" width="80"></td>
        <td align="right" width="80" align="right"><strong>{{ number_format($grandTotalAmount,2) }}</strong></td>
    </tr>
</table>
<p></p>
<p></p>
<p><strong>In Words: {{ $rows->inword }}</strong></p>