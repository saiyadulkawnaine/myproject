<h2 align="center">Lithe Group</h2>
<h4 align="center">Order Summary from ({{ $ship_from }} To {{ $ship_to }})</h4>
<?php
    $tQty=0;
    $tAmount=0;
    $ttQty=0;
    $ttAmount=0;
    $tShipQty=0;
    $tShipValue=0;
    $tShipProgress=0;
    $tShipPendingQty=0;
    $tShipPendingValue=0;

    $tYarnReqQty=0;
    $tYarnIsuQty=0;
    $tYarnIsuProgress=0;
    $tYarnPendingQty=0;

    $tKnitReqQty=0;
    $tKnitDoneQty=0;
    $tKnitProgress=0;
    $tKnitPendingQty=0;
    $tKnitWipQty=0;

    $tDyeingReqQty=0;
    $tDyeingDoneQty=0;
    $tDyeingProgress=0;
    $tDyeingPendingQty=0;
    $tDyeingWipQty=0;

    $tFinFabReqQty=0;
    $tFinFabDoneQty=0;
    $tFinFabProgress=0;
    $tFinFabPendingQty=0;
    $tFinFabWipQty=0;
 
    $tCuttingReqQty=0;
    $tFabIsuCutQty=0;
    $tCuttingDoneQty=0;
    $tCuttingProgress=0;
    $tCuttingPendingQty=0;
    $tCuttingWipQty=0;
    $tCuttingWipQtyPcs=0;

    $tScrnPrintReqQty=0;
    $tScrnPrintIsuQty=0;
    $tScrnPrintDoneQty=0;
    $tScrnPrintProgress=0;
    $tScrnPrintPendingQty=0;
    $tScrnPrintWipQty=0;
    
    $tEmbReqQty=0;
    $tEmbIsuQty=0;
    $tEmbDoneQty=0;
    $tEmbProgress=0;
    $tEmbPendingQty=0;
    $tEmbWipQty=0;

    $tSewLineReqQty=0;
    $tSewLineDoneQty=0;
    $tSewLineProgress=0;
    $tSewLinePendingQty=0;
    $tSewLineWipQty=0;

    $tSewReqQty=0;
    $tSewDoneQty=0;
    $tSewProgress=0;
    $tSewPendingQty=0;
    $tSewWipQty=0;

    $tIronReqQty=0;
    $tIronDoneQty=0;
    $tIronProgress=0;
    $tIronPendingQty=0;
    $tIronWipQty=0;

    $tPolyReqQty=0;
    $tPolyDoneQty=0;
    $tPolyProgress=0;
    $tPolyPendingQty=0;
    $tPolyWipQty=0;

    $tCartonReqQty=0;
    $tCartonDoneQty=0;
    $tCartonProgress=0;
    $tCartonPendingQty=0;
    $tCartonWipQty=0;

    $tInspectionReqQty=0;
    $tInspectionDoneQty=0;
    $tInspectionProgress=0;
    $tInspectionPending=0;
    $tInspectionWipQty=0;
    $tInspectionFailQty=0;
    $tInspectionReCheckQty=0;

?>
<table cellpadding="2" cellspacing="0"  style="background-color:LightPink;margin:0 auto">
    <tr>
        <td width="30" align="center"></td>
        <td width="300" rowspan="2" align="left"><strong>AVG FOB</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <?php
        if ($data->qty) {
            $avg_fob=$data->amount/$data->qty;
        }
            $ttQty+=$data->qty;
            $ttAmount+=$data->amount;
        ?>
        <td align="right"  width="100">{{ number_format($avg_fob,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $ttQty+=$data->qty;
            $ttAmount+=$data->amount;
            if ($ttQty) {
                $tAvgFob=$ttAmount/$ttQty;
            }
        ?>
        @endforeach
        @endforeach
        <td align="right" width="100">{{ number_format($tAvgFob,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
</table>
<p></p>
<table border="1" cellpadding="2" cellspacing="0" style="margin:0 auto">
    <tr>
        <td width="30" rowspan="2" align="center"><strong>SL</strong></td>
        <td width="300" rowspan="2" align="center"><strong>Particulars</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
            <td colspan="2" align="center"  width="200"><strong>{{ $prodCompanyArr[$produced_company_id] }}</strong></td>
        @endforeach
        <td colspan="2" align="center"  width="200"><strong>Total</strong></td>
    </tr>
    <tr>
        @foreach ($rows as $produced_company_id=>$row)
        <td align="center" width="100"><strong>Qty</strong></td>
        <td align="center" width="100"><strong>Value</strong></td>
        @endforeach
        <td align="center" width="100"><strong>Qty</strong></td>
        <td align="center" width="100"><strong>Value</strong></td>
    </tr>
    <tr>
        <td width="30" align="center">1</td>
        <td width="300" align="left"><strong>Order Qty & Value</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100"><strong>{{ number_format($data->qty,2) }}</strong></td>
        <td align="right"  width="100"><strong>{{ number_format($data->amount,2) }}</strong></td>
        <?php
            $tQty+=$data->qty;
            $tAmount+=$data->amount;
        ?>
        @endforeach
        @endforeach
        <td align="right" width="100"><strong>{{ number_format($tQty,2) }}</strong></td>
        <td align="right" width="100"><strong>{{ number_format($tAmount,2) }}</strong></td>
    </tr>
    <tr>
        <td width="30" align="center">2</td>
        <td width="300" align="left">Shipped Out</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->ship_qty,2) }}</td>
        <td align="right"  width="100">{{ number_format($data->ship_value,2) }}</td>
        <?php
            $tShipQty+=$data->ship_qty;
            $tShipValue+=$data->ship_value;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tShipQty,2) }}</td>
        <td align="right" width="100">{{ number_format($tShipValue,2) }}</td>
    </tr>
    <tr>
        <td width="30" align="center">3</td>
        <td width="300" align="left"><strong style="color: #087c9f">Shipout Progress % to Value</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <?php
            $ship_progress=0;
            if ($data->amount) {
                $ship_progress=($data->ship_value/$data->amount)*100;
            }
        ?>
        <td align="right"  width="100"></td>
        <td align="right"  width="100"><strong style="color: #087c9f">{{ number_format($ship_progress,2) }}%</strong></td>
        @endforeach 
        @endforeach
        <?php
            $tShipProgress=($tShipValue/$tAmount)*100;
        ?>
        <td align="right" width="100"></td>
        <td align="right" width="100"><strong style="color: #087c9f">{{ number_format($tShipProgress,2) }}%</strong></td>
    </tr>
    <tr>
        <td width="30" align="center">4</td>
        <td width="300" align="left"><strong style="color:#db0a29;">Shipment Pending</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100"><strong style="color:#db0a29;">{{ number_format($data->ship_pending_qty,2) }}</strong></td>
        <td align="right"  width="100"><strong style="color:#db0a29;">{{ number_format($data->ship_pending_value,2) }}</strong></td>
        <?php
            $tShipPendingQty+=$data->ship_pending_qty;
            $tShipPendingValue+=$data->ship_pending_value;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100"><strong style="color:#db0a29;">{{ number_format($tShipPendingQty,2) }}</strong></td>
        <td align="right" width="100"><strong style="color:#db0a29;">{{ number_format($tShipPendingValue,2) }}</strong></td>
    </tr>
    <tr>
        <td width="30" align="center">5</td>
        <td width="300" align="left"><strong>Yarn Required for Knitting<strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100"><strong>{{ number_format($data->yarn_req,2) }}</strong></td>
        <td align="right"  width="100"></td>
        <?php
            $tYarnReqQty+=$data->yarn_req;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100"><strong>{{ number_format($tYarnReqQty,2) }}</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">6</td>
        <td width="300" align="left">Yarn Issued to Knitting</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->yarn_isu_qty,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tYarnIsuQty+=$data->yarn_isu_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tYarnIsuQty,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">7</td>
        <td width="300" align="left"><strong style="color: #087c9f">Yarn Issue Progress % to Required</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <?php
            $yarn_isu_progress=0;
            if ($data->yarn_req) {
                $yarn_isu_progress=($data->yarn_isu_qty/$data->yarn_req)*100;
            }
        ?>
        <td align="right"  width="100"><strong style="color: #087c9f">{{ number_format($yarn_isu_progress,2) }}%</strong></td>
        <td align="right"  width="100"></td>
        @endforeach 
        @endforeach
        <?php
        if ($tYarnReqQty) {
            $tYarnIsuProgress=($tYarnIsuQty/$tYarnReqQty)*100;
        }   
        ?>
        <td align="right" width="100"><strong style="color: #087c9f">{{ number_format($tYarnIsuProgress,2) }}%</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">8</td>
        <td width="300" align="left"><strong style="color:#db0a29;">Yarn Issue Pending</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100"><strong style="color:#db0a29;">{{ number_format($data->yarn_isu_pending,2) }}</strong></td>
        <td align="right"  width="100"></td>
        <?php
            $tYarnPendingQty+=$data->yarn_isu_pending;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100"><strong style="color:#db0a29;">{{ number_format($tYarnPendingQty,2) }}</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">9</td>
        <td width="300" align="left"><strong>Knitting Required</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100"><strong>{{ number_format($data->yarn_req,2) }}</strong></td>
        <td align="right"  width="100"></td>
        <?php
            $tKnitReqQty+=$data->yarn_req;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100"><strong>{{ number_format($tKnitReqQty,2) }}</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">10</td>
        <td width="300" align="left">Knitting Done</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->prod_knit_qty,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tKnitDoneQty+=$data->prod_knit_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tKnitDoneQty,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">11</td>
        <td width="300" align="left"><strong style="color: #087c9f">Knitting Progress % to Required</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <?php
            $knitting_progress=0;
            if ($data->yarn_req) {
                $knitting_progress=($data->prod_knit_qty/$data->yarn_req)*100;
            }
        ?>
        <td align="right"  width="100"><strong style="color: #087c9f">{{ number_format($knitting_progress,2) }}%</strong></td>
        <td align="right"  width="100"></td>
        @endforeach 
        @endforeach
        <?php
            if ($tKnitReqQty) {
                $tKnitProgress=($tKnitDoneQty/$tKnitReqQty)*100;
            }   
        ?>
        <td align="right" width="100"><strong style="color: #087c9f">{{ number_format($tKnitProgress,2) }}%</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">12</td>
        <td width="300" align="left"><strong style="color:#db0a29;">Knitting Pending</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100"><strong style="color:#db0a29;">{{ number_format($data->prod_knit_pending,2) }}</strong></td>
        <td align="right"  width="100"></td>
        <?php
            $tKnitPendingQty+=$data->prod_knit_pending;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100"><strong style="color:#db0a29;">{{ number_format($tKnitPendingQty,2) }}</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">13</td>
        <td width="300" align="left">Knitting WIP</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->prod_knit_wip,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tKnitWipQty+=$data->prod_knit_wip;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tKnitWipQty,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">14</td>
        <td width="300" align="left"><strong>Dyeing Required</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100"><strong>{{ number_format($data->yarn_req,2) }}</strong></td>
        <td align="right"  width="100"></td>
        <?php
            $tDyeingReqQty+=$data->yarn_req;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100"><strong>{{ number_format($tDyeingReqQty,2) }}</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">15</td>
        <td width="300" align="left">Dyeing Done</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->dyeing_qty,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tDyeingDoneQty+=$data->dyeing_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tDyeingDoneQty,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">16</td>
        <td width="300" align="left"><strong style="color: #087c9f">Dyeing Progress % to Required</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <?php
            $dyeing_progress=0;
            if ($data->yarn_req) {
                $dyeing_progress=($data->dyeing_qty/$data->yarn_req)*100;
            }
        ?>
        <td align="right"  width="100"><strong style="color: #087c9f">{{ number_format($dyeing_progress,2) }}%</strong></td>
        <td align="right"  width="100"></td>
        @endforeach 
        @endforeach
        <?php
            if ($tDyeingReqQty) {
                $tDyeingProgress=($tDyeingDoneQty/$tDyeingReqQty)*100;
            }   
        ?>
        <td align="right" width="100"><strong style="color: #087c9f">{{ number_format($tDyeingProgress,2) }}%</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">17</td>
        <td width="300" align="left"><strong style="color:#db0a29;">Dyeing Pending</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100"><strong style="color:#db0a29;">{{ number_format($data->dyeing_pending,2) }}</strong></td>
        <td align="right"  width="100"></td>
        <?php
            $tDyeingPendingQty+=$data->dyeing_pending;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100"><strong style="color:#db0a29;">{{ number_format($tDyeingPendingQty,2) }}</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">18</td>
        <td width="300" align="left">Dyeing WIP</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->dyeing_wip,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tDyeingWipQty+=$data->dyeing_wip;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tDyeingWipQty,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">19</td>
        <td width="300" align="left"><strong>Fabric Finishing Required</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100"><strong>{{ number_format($data->fin_fab_req,2) }}</strong></td>
        <td align="right"  width="100"></td>
        <?php
            $tFinFabReqQty+=$data->fin_fab_req;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100"><strong>{{ number_format($tFinFabReqQty,2) }}</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">20</td>
        <td width="300" align="left">Fabric Finishing Done</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->finish_qty,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tFinFabDoneQty+=$data->finish_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tFinFabDoneQty,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">21</td>
        <td width="300" align="left"><strong style="color: #087c9f">Fabric Finishing Progress % to Required</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <?php
            $finish_progress=0;
            if ($data->fin_fab_req) {
                $finish_progress=($data->finish_qty/$data->fin_fab_req)*100;
            }
        ?>
        <td align="right"  width="100"><strong style="color: #087c9f">{{ number_format($finish_progress,2) }}%</strong></td>
        <td align="right"  width="100"></td>
        @endforeach 
        @endforeach
        <?php
            if ($tFinFabReqQty) {
                $tFinFabProgress=($tFinFabDoneQty/$tFinFabReqQty)*100;
            }   
        ?>
        <td align="right" width="100"><strong style="color: #087c9f">{{ number_format($tFinFabProgress,2) }}%</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">22</td>
        <td width="300" align="left"><strong style="color:#db0a29;">Fabric Finishing Pending</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100"><strong style="color:#db0a29;">{{ number_format($data->fin_fab_pending,2) }}</strong></td>
        <td align="right"  width="100"></td>
        <?php
            $tFinFabPendingQty+=$data->fin_fab_pending;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100"><strong style="color:#db0a29;">{{ number_format($tFinFabPendingQty,2) }}</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">23</td>
        <td width="300" align="left">Fabric Finishing WIP</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->fin_fab_wip,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tFinFabWipQty+=$data->fin_fab_wip;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tFinFabWipQty,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">24</td>
        <td width="300" align="left"><strong>Cutting Required (Pcs)</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100"><strong>{{ number_format($data->plan_cut_qty,2) }}</strong></td>
        <td align="right"  width="100"></td>
        <?php
            $tCuttingReqQty+=$data->plan_cut_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100"><strong>{{ number_format($tCuttingReqQty,2) }}</strong></td>
        <td align="right" width="100"></td>
    </tr>
   <tr>
        <td width="30" align="center">25</td>
        <td width="300" align="left">Fabric Issued to Cutting (Kg)</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->inv_fin_fab_isu_qty,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tFabIsuCutQty+=$data->inv_fin_fab_isu_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tFabIsuCutQty,2) }}</td>
        <td align="right" width="100"></td> 
    </tr>
    <tr>
        <td width="30" align="center">26</td>
        <td width="300" align="left">Cutting Done (Pcs)</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->cut_qty,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tCuttingDoneQty+=$data->cut_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tCuttingDoneQty,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">27</td>
        <td width="300" align="left"><strong style="color: #087c9f">Cutting Progress % to Required</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <?php
            $cutting_progress=0;
            if ($data->fin_fab_req) {
                $cutting_progress=($data->cut_qty/$data->plan_cut_qty)*100;
            }
        ?>
        <td align="right"  width="100"><strong style="color: #087c9f">{{ number_format($cutting_progress,2) }}%</strong></td>
        <td align="right"  width="100"></td>
        @endforeach 
        @endforeach
        <?php
            if ($tCuttingReqQty) {
                $tCuttingProgress=($tCuttingDoneQty/$tCuttingReqQty)*100;
            }
        ?>
        <td align="right" width="100"><strong style="color: #087c9f">{{ number_format($tCuttingProgress,2) }}%</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">28</td>
        <td width="300" align="left"><strong style="color:#db0a29;">Cutting Pending</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100"><strong style="color:#db0a29;">{{ number_format($data->cut_pending,2) }}</strong></td>
        <td align="right"  width="100"></td>
        <?php
            $tCuttingPendingQty+=$data->cut_pending;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100"><strong style="color:#db0a29;">{{ number_format($tCuttingPendingQty,2) }}</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">29</td>
        <td width="300" align="left">Cutting WIP (Kg)</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->cut_wip_qty_kg,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tCuttingWipQty+=$data->cut_wip_qty_kg;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tCuttingWipQty,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">30</td>
        <td width="300" align="left">Cutting WIP (PCS)</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->cut_wip_qty_pcs,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tCuttingWipQtyPcs+=$data->cut_wip_qty_pcs;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tCuttingWipQtyPcs,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">31</td>
        <td width="300" align="left"><strong>Screen Print Required</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100"><strong>{{ number_format($data->req_scr_qty,2) }}</strong></td>
        <td align="right"  width="100"></td>
        <?php
            $tScrnPrintReqQty+=$data->req_scr_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100"><strong>{{ number_format($tScrnPrintReqQty,2) }}</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">32</td>
        <td width="300" align="left">Cut Pcs Issued for Print</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->snd_scr_qty,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tScrnPrintIsuQty+=$data->snd_scr_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tScrnPrintIsuQty,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">33</td>
        <td width="300" align="left">Screen Print Done</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->rcv_scr_qty,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tScrnPrintDoneQty+=$data->rcv_scr_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tScrnPrintDoneQty,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">34</td>
        <td width="300" align="left"><strong style="color: #087c9f">Screen Print Progress % to Required</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <?php
            $scr_progress=0;
            if ($data->req_scr_qty) {
                $scr_progress=($data->rcv_scr_qty/$data->req_scr_qty)*100;
            }
        ?>
        <td align="right"  width="100"><strong style="color: #087c9f">{{ number_format($scr_progress,2) }}%</strong></td>
        <td align="right"  width="100"></td>
        @endforeach 
        @endforeach
        <?php
            if ($tScrnPrintReqQty) {
                $tScrnPrintProgress=($tScrnPrintDoneQty/$tScrnPrintReqQty)*100;
            }
        ?>
        <td align="right" width="100"><strong style="color: #087c9f">{{ number_format($tScrnPrintProgress,2) }}%</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">35</td>
        <td width="300" align="left"><strong style="color:#db0a29;">Screen Print Pending</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100"><strong style="color:#db0a29;">{{ number_format($data->scr_pending_qty,2) }}</strong></td>
        <td align="right"  width="100"></td>
        <?php
            $tScrnPrintPendingQty+=$data->scr_pending_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100"><strong style="color:#db0a29;">{{ number_format($tScrnPrintPendingQty,2) }}</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">36</td>
        <td width="300" align="left">Screen Print WIP</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->scr_wip_qty,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tScrnPrintWipQty+=$data->scr_wip_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tScrnPrintWipQty,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">37</td>
        <td width="300" align="left"><strong>Embroidery Required</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100"><strong>{{ number_format($data->req_emb_qty,2) }}</strong></td>
        <td align="right"  width="100"></td>
        <?php
            $tEmbReqQty+=$data->req_emb_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100"><strong>{{ number_format($tEmbReqQty,2) }}</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">38</td>
        <td width="300" align="left">Cut Pcs Issued for Embroidery</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->snd_emb_qty,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tEmbIsuQty+=$data->snd_emb_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tEmbIsuQty,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">39</td>
        <td width="300" align="left">Embroidery Done</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->rcv_emb_qty,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tEmbDoneQty+=$data->rcv_emb_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tEmbDoneQty,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">40</td>
        <td width="300" align="left"><strong style="color: #087c9f">Embroidery Progress % to Required</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <?php
            $emb_progress=0;
            if ($data->req_emb_qty) {
                $emb_progress=($data->rcv_emb_qty/$data->req_emb_qty)*100;
            }
        ?>
        <td align="right"  width="100"><strong style="color: #087c9f">{{ number_format($emb_progress,2) }}%</strong></td>
        <td align="right"  width="100"></td>
        @endforeach 
        @endforeach
        <?php
            if ($tEmbReqQty) {
                $tEmbProgress=($tEmbDoneQty/$tEmbReqQty)*100;
            }
        ?>
        <td align="right" width="100"><strong style="color: #087c9f">{{ number_format($tEmbProgress,2) }}%</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">41</td>
        <td width="300" align="left"><strong style="color:#db0a29;">Embroidery Pending</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100"><strong style="color:#db0a29;">{{ number_format($data->emb_pending_qty,2) }}</strong></td>
        <td align="right"  width="100"></td>
        <?php
            $tEmbPendingQty+=$data->emb_pending_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100"><strong style="color:#db0a29;">{{ number_format($tEmbPendingQty,2) }}</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">42</td>
        <td width="300" align="left">Embroidery WIP</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->emb_wip_qty,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tEmbWipQty+=$data->emb_wip_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tEmbWipQty,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">43</td>
        <td width="300" align="left"><strong>Line Input Required</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100"><strong>{{ number_format($data->qty,2) }}</strong></td>
        <td align="right"  width="100"></td>
        <?php
            $tSewLineReqQty+=$data->qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100"><strong>{{ number_format($tSewLineReqQty,2) }}</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">44</td>
        <td width="300" align="left">Line Input Done</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->sew_line_qty,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tSewLineDoneQty+=$data->sew_line_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tSewLineDoneQty,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">45</td>
        <td width="300" align="left"><strong style="color: #087c9f">Line Input Progress % to Required</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <?php
            $sewline_progress=0;
            if ($data->qty) {
                $sewline_progress=($data->sew_line_qty/$data->qty)*100;
            }
        ?>
        <td align="right"  width="100"><strong style="color: #087c9f">{{ number_format($sewline_progress,2) }}%</strong></td>
        <td align="right"  width="100"></td>
        @endforeach 
        @endforeach
        <?php
            if ($tSewLineReqQty) {
                $tSewLineProgress=($tSewLineDoneQty/$tSewLineReqQty)*100;
            }
        ?>
        <td align="right" width="100"><strong style="color: #087c9f">{{ number_format($tSewLineProgress,2) }}%</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">46</td>
        <td width="300" align="left"><strong style="color:#db0a29;">Line Input Pending</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100"><strong style="color:#db0a29;">{{ number_format($data->sew_line_pending_qty,2) }}</strong></td>
        <td align="right"  width="100"></td>
        <?php
            $tSewLinePendingQty+=$data->sew_line_pending_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100"><strong style="color:#db0a29;">{{ number_format($tSewLinePendingQty,2) }}</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">47</td>
        <td width="300" align="left">Line Input WIP</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->sew_line_wip_qty,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tSewLineWipQty+=$data->sew_line_wip_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tSewLineWipQty,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">48</td>
        <td width="300" align="left"><strong>Sewing Required</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100"><strong>{{ number_format($data->qty,2) }}</strong></td>
        <td align="right"  width="100"></td>
        <?php
            $tSewReqQty+=$data->qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100"><strong>{{ number_format($tSewReqQty,2) }}</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">49</td>
        <td width="300" align="left">Sewing QC Done</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->sew_qty,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tSewDoneQty+=$data->sew_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tSewDoneQty,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">50</td>
        <td width="300" align="left"><strong style="color: #087c9f">Sewing Progress %  to Required</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <?php
            $sew_progress=0;
            if ($data->qty) {
                $sew_progress=($data->sew_qty/$data->qty)*100;
            }
        ?>
        <td align="right"  width="100"><strong style="color: #087c9f">{{ number_format($sew_progress,2) }}%</strong></td>
        <td align="right"  width="100"></td>
        @endforeach 
        @endforeach
        <?php
            if ($tSewReqQty) {
                $tSewProgress=($tSewDoneQty/$tSewReqQty)*100;
            }
        ?>
        <td align="right" width="100"><strong style="color: #087c9f">{{ number_format($tSewProgress,2) }}%</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">51</td>
        <td width="300" align="left"><strong style="color:#db0a29;">Sewing Pending</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100"><strong style="color:#db0a29;">{{ number_format($data->sew_pending_qty,2) }}</strong></td>
        <td align="right"  width="100"></td>
        <?php
            $tSewPendingQty+=$data->sew_pending_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100"><strong style="color:#db0a29;">{{ number_format($tSewPendingQty,2) }}</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">52</td>
        <td width="300" align="left">Sewing WIP</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->sew_wip_qty,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tSewWipQty+=$data->sew_wip_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tSewWipQty,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">53</td>
        <td width="300" align="left"><strong>Iron Required</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100"><strong>{{ number_format($data->qty,2) }}</strong></td>
        <td align="right"  width="100"></td>
        <?php
            $tIronReqQty+=$data->qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100"><strong>{{ number_format($tIronReqQty,2) }}</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">54</td>
        <td width="300" align="left">Iron Done</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->iron_qty,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tIronDoneQty+=$data->iron_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tIronDoneQty,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">55</td>
        <td width="300" align="left"><strong style="color: #087c9f">Iron Progress % to Required</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <?php
            $iron_progress=0;
            if ($data->qty) {
                $iron_progress=($data->iron_qty/$data->qty)*100;
            }
        ?>
        <td align="right"  width="100"><strong style="color: #087c9f">{{ number_format($iron_progress,2) }}%</strong></td>
        <td align="right"  width="100"></td>
        @endforeach 
        @endforeach
        <?php
            if ($tIronReqQty) {
                $tIronProgress=($tIronDoneQty/$tIronReqQty)*100;
            }
        ?>
        <td align="right" width="100"><strong style="color: #087c9f">{{ number_format($tIronProgress,2) }}%</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">56</td>
        <td width="300" align="left"><strong style="color:#db0a29;">Iron Pending</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100"><strong style="color:#db0a29;">{{ number_format($data->iron_pending_qty,2) }}</strong></td>
        <td align="right"  width="100"></td>
        <?php
            $tIronPendingQty+=$data->iron_pending_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100"><strong style="color:#db0a29;">{{ number_format($tIronPendingQty,2) }}</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">57</td>
        <td width="300" align="left">Iron WIP</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->iron_wip_qty,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tIronWipQty+=$data->iron_wip_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tIronWipQty,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">58</td>
        <td width="300" align="left"><strong>Poly Required</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100"><strong>{{ number_format($data->qty,2) }}</strong></td>
        <td align="right"  width="100"></td>
        <?php
            $tPolyReqQty+=$data->qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100"><strong>{{ number_format($tPolyReqQty,2) }}</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">59</td>
        <td width="300" align="left">Poly Done</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->poly_qty,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tPolyDoneQty+=$data->poly_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tPolyDoneQty,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">60</td>
        <td width="300" align="left"><strong style="color: #087c9f">Poly Progress % to Required</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <?php
            $poly_progress=0;
            if ($data->qty) {
                $poly_progress=($data->poly_qty/$data->qty)*100;
            }
        ?>
        <td align="right"  width="100"><strong style="color: #087c9f">{{ number_format($poly_progress,2) }}%</strong></td>
        <td align="right"  width="100"></td>
        @endforeach 
        @endforeach
        <?php
            if ($tPolyReqQty) {
                $tPolyProgress=($tPolyDoneQty/$tPolyReqQty)*100;
            }
        ?>
        <td align="right" width="100"><strong style="color: #087c9f">{{ number_format($tPolyProgress,2) }}%</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">61</td>
        <td width="300" align="left"><strong style="color:#db0a29;">Poly Pending</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100"><strong style="color:#db0a29;">{{ number_format($data->poly_pending_qty,2) }}</strong></td>
        <td align="right"  width="100"></td>
        <?php
            $tPolyPendingQty+=$data->poly_pending_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100"><strong style="color:#db0a29;">{{ number_format($tPolyPendingQty,2) }}</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">62</td>
        <td width="300" align="left">Poly WIP</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->poly_wip_qty,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tPolyWipQty+=$data->poly_wip_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tPolyWipQty,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">63</td>
        <td width="300" align="left"><strong>Required Gmt in Carton</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100"><strong>{{ number_format($data->qty,2) }}</strong></td>
        <td align="right"  width="100"></td>
        <?php
            $tCartonReqQty+=$data->qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100"><strong>{{ number_format($tCartonReqQty,2) }}</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">64</td>
        <td width="300" align="left">Carton Done</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->car_qty,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tCartonDoneQty+=$data->car_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tCartonDoneQty,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">65</td>
        <td width="300" align="left"><strong style="color: #087c9f">Carton Progress % to Required</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <?php
            $carton_progress=0;
            if ($data->qty) {
                $carton_progress=($data->car_qty/$data->qty)*100;
            }
        ?>
        <td align="right"  width="100"><strong style="color: #087c9f">{{ number_format($carton_progress,2) }}%</strong></td>
        <td align="right"  width="100"></td>
        @endforeach 
        @endforeach
        <?php
            if ($tCartonReqQty) {
                $tCartonProgress=($tCartonDoneQty/$tCartonReqQty)*100;
            }
        ?>
        <td align="right" width="100"><strong style="color: #087c9f">{{ number_format($tCartonProgress,2) }}%</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">66</td>
        <td width="300" align="left"><strong style="color:#db0a29;">Carton Pending</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100"><strong style="color:#db0a29;">{{ number_format($data->car_pending_qty,2) }}</strong></td>
        <td align="right"  width="100"></td>
        <?php
            $tCartonPendingQty+=$data->car_pending_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100"><strong style="color:#db0a29;">{{ number_format($tCartonPendingQty,2) }}</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">67</td>
        <td width="300" align="left">Carton WIP</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->car_wip_qty,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tCartonWipQty+=$data->car_wip_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tCartonWipQty,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">68</td>
        <td width="300" align="left"><strong>Inspection Required</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100"><strong>{{ number_format($data->qty,2) }}</strong></td>
        <td align="right"  width="100"></td>
        <?php
            $tInspectionReqQty+=$data->qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100"><strong>{{ number_format($tInspectionReqQty,2) }}</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">69</td>
        <td width="300" align="left">Inspection Done</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->insp_pass_qty,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tInspectionDoneQty+=$data->insp_pass_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tInspectionDoneQty,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">70</td>
        <td width="300" align="left"><strong style="color: #087c9f">Inspection Progress % to Required</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <?php
            $inspection_progress=0;
            if ($data->qty) {
                $inspection_progress=($data->insp_pass_qty/$data->qty)*100;
            }
        ?>
        <td align="right"  width="100"><strong style="color: #087c9f">{{ number_format($inspection_progress,2) }}%</strong></td>
        <td align="right"  width="100"></td>
        @endforeach 
        @endforeach
        <?php
            if ($tInspectionReqQty) {
                $tInspectionProgress=($tInspectionDoneQty/$tInspectionReqQty)*100;
            }
        ?>
        <td align="right" width="100"><strong style="color: #087c9f">{{ number_format($tInspectionProgress,2) }}%</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">71</td>
        <td width="300" align="left"><strong style="color:#db0a29;">Inspection Pending</strong></td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100"><strong style="color:#db0a29;">{{ number_format($data->insp_pass_pending_qty,2) }}</strong></td>
        <td align="right"  width="100"></td>
        <?php
            $tInspectionPending+=$data->insp_pass_pending_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100"><strong style="color:#db0a29;">{{ number_format($tInspectionPending,2) }}</strong></td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">72</td>
        <td width="300" align="left">Inspection WIP</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->insp_pass_wip_qty,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tInspectionWipQty+=$data->insp_pass_wip_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tInspectionWipQty,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">73</td>
        <td width="300" align="left">Inspection Fail Qty</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->insp_faild_qty,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tInspectionFailQty+=$data->insp_faild_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tInspectionFailQty,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
    <tr>
        <td width="30" align="center">74</td>
        <td width="300" align="left">Inspection Re-Check Qty</td>
        @foreach ($rows as $produced_company_id=>$row)
        @foreach ($row as $data)
        <td align="right"  width="100">{{ number_format($data->insp_re_check_qty,2) }}</td>
        <td align="right"  width="100"></td>
        <?php
            $tInspectionReCheckQty+=$data->insp_re_check_qty;
        ?>
        @endforeach 
        @endforeach
        <td align="right" width="100">{{ number_format($tInspectionReCheckQty,2) }}</td>
        <td align="right" width="100"></td>
    </tr>
</table>