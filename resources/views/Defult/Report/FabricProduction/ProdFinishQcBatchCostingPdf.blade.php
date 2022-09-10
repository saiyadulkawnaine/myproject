<h2 align="center">Batch Costing</h2>
<table cellpadding="2" cellspacing="0">
    <tr>
        <td align="center" colspan="8" width="680">
            Buyer:<strong>&nbsp;{{ $rows->buyer_name }}</strong>&nbsp;&nbsp;Style Ref:<strong>&nbsp;{{ $rows->style_ref }}</strong>&nbsp;&nbsp;Plan Ship Date:<strong>&nbsp;{{ $rows->org_ship_date }}</strong>
        </td>
    </tr>
    <tr>
        <td align="center" colspan="8" width="680">
            Batch Color:<strong>&nbsp;{{ $rows->batch_color_name }}</strong>&nbsp;&nbsp;Batch Weight:<strong>&nbsp;{{ $rows->batch_qty }}&nbsp;KG</strong>
        </td>
    </tr>
    <tr><td align="center" colspan="8" width="680"></td></tr>
    <tr>
        <td align="left" width="60"><strong>Batch No:</strong><br/>Customer:
            Batch For:
        </td>
        <td align="left" width="100"><strong>{{ $rows->batch_no }}</strong><br/>{{ $rows->customer_name }}<br/>
            {{ $rows->batch_for }}
            
        </td>
        <td align="left" width="80">Color Range:<br/>
            Dyeing Machine:<br/>
        </td>
        <td align="left" width="80">{{ $rows->colorrange }}<br/>
            {{ $rows->custom_no }}<br/>
            
        </td>
        <td align="left" width="60">Color %:<br/>
            Lab Dip No:<br/>
            Batch Date:
        </td>
        <td align="left" width="70">{{ $rows->ratio }}<br/>
            {{ $rows->lap_dip_no }}<br/>
            {{ $rows->batch_date }}
        </td>
        <td align="left" width="120">Loading Date & Time:<br/>
            Unloading Date & Time:<br/>
            Used Hour:
        </td>
        <td align="left" width="110">{{ $rows->loaded_at }}<br/>
            {{ $rows->unloaded_at }}<br/>
            {{ $rows->hour_used }}
        </td>
    </tr>
    <tr>
        <td align="left" width="60">Fabrics:</td>
        <td align="left" colspan="7" width="620">{{ $rows->fabrication }}</td>
    </tr>
    <tr>
        <td align="left" width="60">Order No:</td>
        <td align="left" colspan="7" width="620">{{ $rows->sale_order_no }}</td>
    </tr>
</table>
<hr>
<p align="right"><strong>Amount in TK</strong></p>
<?php
$avgRevenue=$rows->revenue/$rows->batch_qty;
$totalDyeChemCost=$rows->dyes_cost_amount+$rows->chem_cost_amount;
$avgDyecost=$rows->dyes_cost_amount/$rows->batch_qty;
$avgChemCost=$rows->chem_cost_amount/$rows->batch_qty;
$avgTotalDyeChemCost=$totalDyeChemCost/$rows->batch_qty;
$dyeChemCostPer=($totalDyeChemCost/$rows->revenue)*100;
?>
<table>
    <tr>
        <td width="400"><table cellpadding="2" cellspacing="0">
                <tr>
                    <td width="80" align="left">
                        <strong>&nbsp;&nbsp;Particulars</strong>
                    </td>
                    <td width="50" border="1" align="center"><strong>Grey Qty</strong></td>
                    <td width="50" border="1" align="center"><strong>Process Loss Qty</strong></td>
                    <td width="50" border="1" align="center"><strong>Process Loss %</strong></td>
                    <td width="50" border="1" align="center"><strong>QC Pass Qty</strong></td>
                    <td width="50" border="1" align="center"><strong>Avg/Kg</strong><br>(Revenue /Cost)</td>
                    <td width="60" border="1" align="center"><strong>Amount</strong></td>
                </tr>
                <tr>
                    <td width="80" align="left"><strong>A. Revenue</strong></td>
                    <td width="50" border="1" align="right">{{ number_format($rows->batch_qty,2) }}</td>
                    <td width="50" border="1" align="right">{{ number_format($rows->process_loss,2) }}</td>
                    <td width="50" border="1" align="right">{{ number_format($rows->process_loss_per,2) }} %</td>
                    <td width="50" border="1" align="right">{{ number_format($rows->qc_pass_qty,2) }}</td>
                    <td width="50" border="1" align="right">{{ number_format($avgRevenue,2) }}</td>
                    <td width="60" border="1" align="right">{{ number_format($rows->revenue,2) }}</td>
                </tr>
                <tr>
                    <td width="390" align="left" colspan="7"><strong>B. Material Cost</strong></td>
                </tr>
                <tr>
                    <td width="120" align="left">
                        <strong>&nbsp;&nbsp;Dyes Consumed</strong>
                    </td>
                    <td width="160" align="right" colspan="4"></td>
                    <td width="50" border="1" align="right">{{ number_format($avgDyecost,2) }}</td>
                    <td width="60" border="1" align="right">{{ number_format($rows->dyes_cost_amount,2) }}</td>
                </tr>
                <tr>
                    <td width="120" align="left">
                        <strong>&nbsp;&nbsp;Chemicals Consumed</strong>
                    </td>
                    <td width="160" align="right" colspan="4"></td>
                    <td width="50" border="1" align="right">{{ number_format($avgChemCost,2) }}</td>
                    <td width="60" border="1" align="right">{{ number_format($rows->chem_cost_amount,2) }}</td>
                </tr>
                <tr>
                    <td width="120" align="left">
                        <strong>&nbsp;&nbsp;Total</strong>
                    </td>
                    <td width="160" align="right" colspan="4"></td>
                    <td width="50" align="right"><strong>{{ number_format($avgTotalDyeChemCost,2) }} </strong></td>
                    <td width="60" align="right"><strong>{{number_format($totalDyeChemCost,2) }}</strong></td>
                </tr>
                <tr>
                    <td width="160" align="left">
                        <strong>&nbsp;&nbsp;Dyes & Chemical Cost %</strong>
                    </td>
                    <td width="120" align="right" colspan="4"></td>
                    <td width="50" align="right"></td>
                    <td width="60" align="right"><strong>{{ number_format($dyeChemCostPer,2) }} %</strong></td>
                </tr>
            </table>
            <br><br><table cellpadding="2" cellspacing="0">
                <tr>
                    <td width="270" align="left"><strong>C. Overheads</strong>
                    </td>
                    <td width="60" border="1" align="center"><strong>Amount</strong></td>
                    <td width="60" border="1" align="center"><strong>%</strong></td>
                </tr>
                <?php
                    $tOverheadAmount=0;
                    $tCostPer=0;
                ?>
                @foreach ($datas['overheads'] as $data)
                <tr>
                    <td width="270" align="left">&nbsp;&nbsp;&nbsp;{{ $data->acc_chart_ctrl_head_name }}</td>
                    <td width="60" border="1" align="right">{{ number_format(($data->cost_per*$rows->revenue)/100,2) }}</td>
                    <td width="60" border="1" align="right">{{ number_format($data->cost_per,2) }}</td>
                </tr>
                <?php
                    $tAmount=($data->cost_per*$rows->revenue)/100;
                    $tOverheadAmount+=$tAmount;
                    $tCostPer+=$data->cost_per;
                ?>
                @endforeach
                <tr>
                    <td width="270" align="left">&nbsp;&nbsp;<strong>Total Overheads</strong></td>
                    <td width="60" align="right"><strong>{{ number_format($tOverheadAmount,2) }}</strong></td>
                    <td width="60" align="right"><strong>{{ number_format($tCostPer,2) }}</strong></td>
                </tr>
                <?php
                    $tNetProfitAmount=$rows->revenue-$totalDyeChemCost-$tOverheadAmount;
                    $tNetProfitPer=($tNetProfitAmount/$rows->revenue)*100;
                ?>
                <tr>
                    <td width="270" align="left"><strong>D. Net Profit/ (Loss)</strong></td>
                    <td width="60" align="right"><strong>{{ number_format($tNetProfitAmount,2) }}</strong></td>
                    <td width="60" align="right"><strong>{{ number_format($tNetProfitPer,2) }}</strong></td>
                </tr>
            </table>
        </td>
        <td width="380">
            <strong>Used Dyes & Chemicals</strong><br>
            <strong>Dyes</strong><br>
            <table border="1" cellpadding="2" cellspacing="0">
                <tr>
                    <td align="center" width="20"><strong>SL</strong></td>
                    <td align="center" width="60"><strong>Item Desc</strong></td>
                    <td align="center" width="50"><strong>Used Qty</strong></td>
                    <td align="center" width="60"><strong>Rate</strong></td>
                    <td align="center" width="60"><strong>Amount</strong></td>
                    <td align="center" width="40"><strong>%</strong></td>
                </tr>
                <?php
                    $i=1;
                    $totalDyeCostQty=0;
                    $totalDyeCost=0;
                    $dyecostper=0;
                ?>
                @foreach ($datas['dyeingcost'] as $data)
                
                <tr>
                    <td align="center" width="20">{{ $i }}</td>
                    <td align="left" width="60">{{ $data->itemclass_name }}</td>
                    <td align="right" width="50">{{ number_format($data->qty,2) }}</td>
                    <td align="right" width="60">{{ number_format($data->rate,2) }}</td>
                    <td align="right" width="60">{{ number_format($data->amount,2) }}</td>
                    <td align="right" width="40">{{ number_format(($data->amount/$rows->revenue)*100,2) }}</td>
                </tr> 
                <?php
                    $i++;
                    $totalDyeCost+=$data->amount;
                    $totalDyeCostQty+=$data->qty;
                    $dyecostper=($totalDyeCost/$rows->revenue)*100;
                ?> 
                @endforeach
            </table>
            <table border="0" cellpadding="2" cellspacing="0">
                <tr>
                    <td align="center" width="20"></td>
                    <td align="left" width="60"><strong>Total</strong></td>
                    <td align="right" width="50"><strong>{{ number_format($totalDyeCostQty,2) }}</strong></td>
                    <td align="right" width="60"></td>
                    <td align="right" width="60"><strong>{{ number_format($totalDyeCost,2) }}</strong></td>
                    <td align="right" width="40"><strong>{{ number_format($dyecostper,2) }}</strong></td>
                </tr>
            </table>
            <br><br>
            <strong>Chemical</strong><br>
            <table border="1" cellpadding="2" cellspacing="0">
                <tr>
                    <td align="center" width="20"><strong>SL</strong></td>
                    <td align="center" width="60"><strong>Item Desc</strong></td>
                    <td align="center" width="50"><strong>Used Qty</strong></td>
                    <td align="center" width="60"><strong>Rate</strong></td>
                    <td align="center" width="60"><strong>Amount</strong></td>
                    <td align="center" width="40"><strong>%</strong></td>
                </tr>
                <?php
                    $i=1;
                    $totalChemCostQty=0;
                    $totalChemCost=0;
                    $chemcostper=0;
                ?>
                @foreach ($datas['chemicalcost'] as $data)
                
                <tr>
                    <td align="center" width="20">{{ $i }}</td>
                    <td align="left" width="60">{{ $data->itemclass_name }}</td>
                    <td align="right" width="50">{{ number_format($data->qty,2) }}</td>
                    <td align="right" width="60">{{ number_format($data->rate,2) }}</td>
                    <td align="right" width="60">{{ number_format($data->amount,2) }}</td>
                    <td align="right" width="40">{{ number_format(($data->amount/$rows->revenue)*100,2) }}</td>
                </tr> 
                <?php
                    $i++;
                    $totalChemCost+=$data->amount;
                    $totalChemCostQty+=$data->qty;
                    $chemcostper=($totalChemCost/$rows->revenue)*100;
                ?> 
                @endforeach
            </table>
            <table border="0" cellpadding="2" cellspacing="0">
                <tr>
                    <td align="center" width="20"></td>
                    <td align="left" width="60"><strong>Total</strong></td>
                    <td align="right" width="50"><strong>{{ number_format($totalChemCostQty,2) }}</strong></td>
                    <td align="right" width="60"></td>
                    <td align="right" width="60"><strong>{{ number_format($totalChemCost,2) }}</strong></td>
                    <td align="right" width="40"><strong>{{ number_format($chemcostper,2) }}</strong></td>
                </tr>
                <tr>
                    <td align="center" width="20"></td>
                    <td align="left" width="170" colspan="3"><strong>Grand Total</strong></td>
                    <td align="right" width="60"><strong>{{ number_format($totalDyeCost+$totalChemCost,2) }}</strong></td>
                    <td align="right" width="40"><strong>{{ number_format($dyecostper+$chemcostper,2) }}</strong></td>
                </tr>
                <tr><td align="left" width="290" colspan="6"></td></tr>
                <tr><td align="left" width="290" colspan="6"><strong>Requisition ID: </strong>{{ $datas['inv_dye_chem_rq_id'] }}</td></tr>
                <tr><td align="left" width="290" colspan="6"><strong>Issue No: </strong>{{ $datas['issue_no'] }}</td></tr>
                @if ($rows->flie_src)
                <tr>
                    <td align="top"  colspan="6">
                        <?php
                        $impath=url('/')."/images/".$rows->flie_src;
                        ?>
                        <img src="<?php echo $impath;?>" height="150" widht="100">
                    </td>
                </tr>
                @endif
            </table>
        </td>
    </tr>
</table>