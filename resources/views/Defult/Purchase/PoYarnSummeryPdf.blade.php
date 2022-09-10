<h3 align="center">Yarn Purchase Order Stylewise Summery</h3>
<table border="1" cellpadding="2" cellspacing="0" style="margin: 2% auto">
    <tr>
        <td align="center" width="30"><strong>SL</strong></td>
        <td align="center" width="170"><strong>Style</strong></td>
        <td align="center" width="100"><strong>Buyer</strong></td>
        <td align="center" width="90"><strong>Req Yarn Qty</strong></td>
        <td align="center" width="90"><strong>Yarn Cost</strong></td>
        <td align="center" width="80"><strong>Yarn Budget / Kg</strong></td>
        <td align="center" width="90"><strong>GMT Selling Value</strong></td>
        <td align="center" width="80"><strong>GMT Price</strong></td>
        <td align="center" width="90"><strong>Yarn PO Qty-Kg</strong></td>
        <td align="center" width="90"><strong>Total PO Qty-Kg</strong></td>
    </tr>
    <?php
        $i=1;
        $tSellingValue=0;
        $tReqQty=0;
        $tYarnCost=0;
        $tCurrentPoQty=0;
        $tSellingValue=0;
        $tTotalPoQty=0;
        $tYarnBudgetRate=0;
    ?>
    @foreach ($data as $rows)
    <tr nobr="true">
        <td align="center" width="30">{{ $i++ }}</td>
        <td align="left" width="170">{{ $rows->style_ref }}</td>
        <td align="left" width="100">{{ $rows->buyer_name }}</td>
        <td align="right" width="90">{{ number_format($rows->req_qty,2) }}</td>
        <td align="right" width="90">{{ number_format($rows->yarn_cost,2) }}</td>
        <td align="right" width="80">{{ number_format($rows->yarn_budget_per_kg,2) }}</td>
        <td align="right" width="90">{{ number_format($rows->selling_value,2) }}</td>
        <td align="right" width="80">{{ number_format($rows->unit_price,2) }}</td>     
        <td align="right" width="90">{{ number_format($rows->current_po_qty,2) }}</td>
        <td align="right" width="90">{{ number_format($rows->total_po_qty,2) }}</td>
    </tr>
    <?php
        $tSellingValue+=$rows->selling_value;
        $tReqQty+=$rows->req_qty;
        $tYarnCost+=$rows->yarn_cost;
        $tCurrentPoQty+=$rows->current_po_qty;
        $tTotalPoQty+=$rows->total_po_qty;
        if ($tReqQty) {
            $tYarnBudgetRate=$tYarnCost/$tReqQty;
        }
    ?>
    @endforeach
    <tr>
        <td align="center" width="30"></td>
        <td align="left" width="170"><strong>Total</strong></td>
        <td align="right" width="100"></td>
        <td align="right" width="90"><strong>{{ number_format($tReqQty,2) }}</strong></td>
        <td align="right" width="90"><strong>{{ number_format($tYarnCost,2) }}</strong></td>
        <td align="right" width="80"><strong>{{ number_format($tYarnBudgetRate,2) }}</strong></td>
        <td align="right" width="90"><strong>{{ number_format($tSellingValue,2) }}</strong></td>
        <td align="right" width="80"></td>
        <td align="right" width="90"><strong>{{ number_format($tCurrentPoQty,2) }}</strong></td>
        <td align="right" width="90"><strong>{{ number_format($tTotalPoQty,2) }}</strong></td>
    </tr>
</table>