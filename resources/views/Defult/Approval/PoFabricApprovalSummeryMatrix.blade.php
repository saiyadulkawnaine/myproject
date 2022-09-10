<h3 align="center">{{ $data['master']->company_name }}</h3>

<table align="center" cellpadding="2" cellspacing="2">
    <tr>
        <td width="655px" style="font-size: 15px"><strong>Purchase Order No : </strong>{{ $data['master']->po_no }}</td>
        <td width="655px"></td>
    </tr>
    <tr>
        <td width="655px" style="font-size: 15px"><strong>Supplier : </strong>{{ $data['master']->supplier_name }}</td>
        <td width="655px"></td>
    </tr>
</table>
<br><br>
<table align="center" border="1" cellpadding="2" cellspacing="2">
    <tr>
        <td width="30px" align="center">SL</td>
        <td width="50px" align="center">Bnf Company</td>
        <td width="100px" align="center">Style</td>
        <td width="150px" align="center">Fabrication</td>
        <td width="40px" align="center">Fabric Look</td>
        <td width="80px" align="center">Gmt Part</td>
        <td width="40px" align="center">UOM</td>
        <td width="70px" align="center">Fabric Color</td>
        <td width="70px" align="center">Current PO Qty</td>
        <td width="40px" align="center">Current PO Rate</td>
        <td width="70px" align="center">Current PO Amount</td>
        <td width="70px" align="center">Budget Qty</td>
        <td width="70px" align="center">Total PO Qty</td>
        <td width="70px" align="center" style="background-color:#a2e2e6;margin:0 auto">Qty Variance</td>
        <td width="40px" align="center">Budget Rate</td>
        <td width="70px" align="center">Budget Amount</td>
        <td width="40px" align="center">Avg PO Rate</td>
        <td width="70px" align="center">Total PO Amount</td>
        <td width="40px" align="center" style="background-color:#a2e2e6;margin:0 auto">Rate Variance</td>
        <td width="70px" align="center" style="background-color:#a2e2e6;margin:0 auto">Amount Variance</td>
    </tr>
    <tbody>
        <?php
            $i=1;
            $j=1;
            $tBomQty=0;
            $tBomRate=0;
            $tBomAmount=0;
            $tPoQty=0;
            $tTotalPoQty=0;
            $tPoRate=0;
            $tPoAmount=0;
            $tTotalPoAmount=0;
            $tBalQty=0;
            $tBalAmount=0;
            $qty_color="color:black;background-color: #a2e2e6;margin:0 auto;";
            $rate_color="color:black;background-color:#a2e2e6;margin:0 auto;";
            $amount_bal="color:black;background-color:#a2e2e6;margin:0 auto;";
            $total_amount_bal="color:black;background-color:#a2e2e6;margin:0 auto";
        ?>
        @foreach ($data['details'] as $row)
        <?php
        if($row->qty_bal < 0){
            $qty_color="color:red;background-color:#a2e2e6;margin:0 auto";
        }
        if($row->rate_bal < 0){
            $rate_color="color:red;background-color:#a2e2e6;margin:0 auto";
        }
        if($row->amount_bal < 0){
            $amount_bal="color:red;background-color:#a2e2e6;margin:0 auto";
        }
            
        ?>
        <tr>
            <td width="30px" align="center">{{ $i++ }}</td>
            <td width="50px" align="left">{{ $row->bnf_company }}</td>
            <td width="10px" align="left">{{ $row->style_ref }}</td>
            <td width="150px" align="left">{{ $row->fabric_description }}</td>
            <td width="40px" align="center">{{ $row->fabriclooks }}</td>
            <td width="80px" align="left">{{ $row->gmtspart_name }}</td>
            <td width="40px" align="center">{{ $row->uom_code }}</td>
            <td width="70px" align="center">{{ $row->fabric_color_name }}</td>
            <td width="70px" align="right">{{ number_format($row->po_qty,2) }}</td>
            <td width="40px" align="right">{{ number_format($row->po_rate,4) }}</td>
            <td width="70px" align="right">{{ number_format($row->po_amount,2) }}</td>
            <td width="70px" align="right">{{ number_format($row->bom_qty,2) }}</td>
            <td width="70px" align="right"><a href="javascript:void(0)" onClick="MsPoFabricApproval.pofabricdetailWindow('{{$row->company_id}}','{{$row->style_fabrication_id}}','{{$row->style_id}}')">{{ number_format($row->total_po_qty,2) }}</a></td>
            <td width="70px" align="right" style="{{$qty_color}}"> {{ number_format($row->qty_bal,2) }} </td>
            <td width="40px" align="right">{{ number_format($row->bom_rate,4) }}</td>
            <td width="70px" align="right">{{ number_format($row->bom_amount,2) }}</td>
            <td width="40px" align="right">{{ number_format($row->total_po_rate,4) }}</td>
            <td width="70px" align="right">{{ number_format($row->total_po_amount,2) }}</td>
            <td width="40px" align="right" style="{{$rate_color}}"> {{ number_format($row->rate_bal,4) }} </td>
            <td width="70px" align="right" style="{{$amount_bal}}"> {{ number_format($row->amount_bal,4) }} </td>
        </tr>
        <?php
            $tBomQty+=$row->bom_qty;
            //$tBomRate+=0;
            $tBomAmount+=$row->bom_amount;
            $tPoQty+=$row->po_qty;
            $tTotalPoQty+=$row->total_po_qty;
            //$tPoRate+=0;
            $tPoAmount+=$row->po_amount;
            $tTotalPoAmount+=$row->total_po_amount;
            $tBalQty+=$row->qty_bal;
            $tBalAmount+=$row->amount_bal;
            if($row->qty_bal < 0){
                $total_qty_color="color:red;background-color:#a2e2e6;margin:0 auto";
            }
            if($row->amount_bal < 0){
                $total_amount_bal="color:red;background-color:#a2e2e6;margin:0 auto";
            }
        ?>
        @endforeach
    </tbody>
    <tr>
        <td width="30px" align="center"></td>
        <td align="center" colspan="5"><strong>Total</strong></td>
        <td width="30px" align="right"></td>
        <td width="70px" align="center"></td>
        <td width="70px" align="right"><strong>{{ number_format($tPoQty,2) }}</strong></td>
        <td width="40px" align="right"></td>
        <td width="70px" align="right"><strong>{{ number_format($tPoAmount,2) }}</strong></td>
        <td width="70px" align="right"><strong>{{ number_format($tBomQty,2) }}</strong></td>
        <td width="70px" align="right"><strong>{{ number_format($tTotalPoQty,2) }}</strong></td>
        <td width="70px" align="right" style="color:black;background-color:#a2e2e6;margin:0 auto"><strong>{{ number_format($tBalQty,2) }}</strong></td>
        <td width="40px" align="right"></td>
        <td width="70px" align="right"><strong>{{ number_format($tBomAmount,2) }}</strong></td>
        <td width="40px" align="right"></td>
        <td width="70px" align="right"><strong>{{ number_format($tTotalPoAmount,2) }}</strong></td>
        <td width="40px" align="right" style="color:black;background-color:#a2e2e6;margin:0 auto"></td>
        <td width="70px" align="right" style="{{$total_amount_bal}}"><strong>{{ number_format($tBalAmount,4) }}</strong></td>
    </tr> 
</table>
<br><br><br>
@if ($data['master']->po_type_id==2)
    <table align="center" border="1" cellpadding="2" cellspacing="2">
        <tr>
            <td width="30px" align="center">SL</td>
            <td width="150px" align="center">Section</td>
            <td width="150px" align="center">Person</td>
            <td width="120px" align="center">Company</td>
            <td width="200px" align="center">Reason</td>
            <td width="100px" align="center">Short Type</td>
            <td width="80px" align="center">Cost Sharing</td>
        </tr>
        <tbody>
            @foreach ($data['responsible'] as $row)
            <tr>
                <td width="30px" align="center">{{ $j++ }}</td>
                <td width="150px" align="left">{{ $row->section_name }}</td>
                <td width="150px" align="left">{{ $row->employee_name }}</td>
                <td width="120px" align="left">{{ $row->company_name }}</td>
                <td width="200px" align="left">{{ $row->reason }}</td>
                <td width="100px" align="left">{{ $row->short_type }}</td>
                <td width="80px" align="right">{{ $row->cost_share_per }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif