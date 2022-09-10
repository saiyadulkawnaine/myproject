<p align="center" style="font: 20px 'Arial Narrow', Arial, sans-serif;font-stretch: condensed;"><strong>Factory Level Summery</strong></p>
<table border="1" cellpadding="2" style="font: 14px 'Arial Narrow', Arial, sans-serif;font-stretch: condensed;">
    <tr>
        <td width="30" align="center"><strong>SL</strong></td>
        <td width="150" align="center"><strong>Factory</strong></td>
        <td width="100" align="center"><strong>Order Qty (Pcs)</strong></td>
        <td width="100" align="center"><strong>Grey Req</strong></td>
        <td width="100" align="center"><strong>Yarn Issue</strong></td>
        <td width="100" align="center"><strong>Yarn Balance</strong></td>
        <td width="100" align="center"><strong>Knitted</strong></td>
        <td width="100" align="center"><strong>Knit Balance</strong></td>
        <td width="100" align="center"><strong>Knit QC Done</strong></td>
        <td width="100" align="center"><strong>Grey To Dye</strong></td>
        <td width="100" align="center"><strong>Batch Qty</strong></td>
        <td width="100" align="center"><strong>Batch Balance</strong></td>
        <td width="100" align="center"><strong>Dyeing Qty</strong></td>
        <td width="100" align="center"><strong>Dyeing Balance</strong></td>
        <td width="100" align="center"><strong>Finish Fabric Req</strong></td>
        <td width="100" align="center"><strong>Finish Fabric Available</strong></td>
        <td width="100" align="center"><strong>Finish Fabric Balance</strong></td>
        <td width="100" align="center"><strong>Issue To Cut</strong></td>
        <td width="100" align="center"><strong>Yet To Issue To Cut</strong></td>
    </tr> 
    <tbody>
        <?php
            $i=1;
            $tQty=0;
            $tGreyFab=0;
            $tYisuQty=0;
            $tYisuBal=0;
            $tProdKnitQty=0;
            $tKnitBal=0;
            $tKnitQty=0;
            $tRcvByBatchQty=0;
            $tBatchQty=0;
            $tBatchBal=0;
            $tDyeingQty=0;
            $tDyeingBal=0;
            $tFinFab=0;
            $tFinQty=0;
            $tFinBal=0;
            $tFinIsuToCutQty=0;
            $tFinIsuToCutBal=0;
        ?>
        @foreach ($prodCompanyArr as $produced_company_id=>$data)
        <?php
            $yisu_bal='';
            if($data['yisu_bal'] < 0){
                $yisu_bal='red';
            }
            $knit_bal='';
            if($data['knit_bal'] < 0){
                $knit_bal='red';
            }
            $batch_bal='';
            if($data['batch_bal'] < 0){
                $batch_bal='red';
            }
            $dyeing_bal='';
            if($data['dyeing_bal'] < 0){
                $dyeing_bal='red';
            }
            $finish_bal='';
            if($data['finish_bal'] < 0){
                $finish_bal='red';
            }
            $finish_isu_to_cut_bal='';
            if($data['finish_isu_to_cut_bal'] < 0){
                $finish_isu_to_cut_bal='red';
            }
        ?>
        <tr>
            <td width="30" align="center">{{ $i++ }}</td>
            <td width="150" align="left">{{ $data['produced_company_name'] }}</td>
            <td width="100" align="right">{{ number_format($data['qty']) }}</td>
            <td width="100" align="right">{{ number_format($data['grey_fab']) }}</td>
            <td width="100" align="right">{{ number_format($data['yisu_qty']) }}</td>
            <td width="100" align="right" style="color:{{$yisu_bal}};">{{ number_format($data['yisu_bal']) }}</td>
            <td width="100" align="right">{{ number_format($data['prod_knit_qty']) }}</td>
            <td width="100" align="right" style="color:{{$knit_bal}};">{{ number_format($data['knit_bal']) }}</td>
            <td width="100" align="right">{{ number_format($data['knit_qty']) }}</td>
            <td width="100" align="right">{{ number_format($data['rcv_by_batch_qty']) }}</td>
            <td width="100" align="right">{{ number_format($data['batch_qty']) }}</td>
            <td width="100" align="right" style="color:{{$batch_bal}};">{{ number_format($data['batch_bal']) }}</td>
            <td width="100" align="right">{{ number_format($data['dyeing_qty']) }}</td>
            <td width="100" align="right" style="color:{{$dyeing_bal}};">{{ number_format($data['dyeing_bal']) }}</td>
            <td width="100" align="right">{{ number_format($data['fin_fab']) }}</td>
            <td width="100" align="right">{{ number_format($data['finish_qty']) }}</td>
            <td width="100" align="right" style="color:{{$finish_bal}};">{{ number_format($data['finish_bal']) }}</td>
            <td width="100" align="right">{{ number_format($data['finish_isu_to_cut_qty']) }}</td>
            <td width="100" align="right" style="color:{{$finish_isu_to_cut_bal}};">{{ number_format($data['finish_isu_to_cut_bal']) }}</td>
        </tr> 
        <?php
            $tQty+=$data['qty'];
            $tGreyFab+=$data['grey_fab'];
            $tYisuQty+=$data['yisu_qty'];
            $tYisuBal+=$data['yisu_bal'];
            $tProdKnitQty+=$data['prod_knit_qty'];
            $tKnitBal+=$data['knit_bal'];
            $tKnitQty+=$data['knit_qty'];
            $tRcvByBatchQty+=$data['rcv_by_batch_qty'];
            $tBatchQty+=$data['batch_qty'];
            $tBatchBal+=$data['batch_bal'];
            $tDyeingQty+=$data['dyeing_qty'];
            $tDyeingBal+=$data['dyeing_bal'];
            $tFinFab+=$data['fin_fab'];
            $tFinQty+=$data['finish_qty'];
            $tFinBal+=$data['finish_bal'];
            $tFinIsuToCutQty+=$data['finish_isu_to_cut_qty'];
            $tFinIsuToCutBal+=$data['finish_isu_to_cut_bal'];
        ?>
        @endforeach
    </tbody>
    <tfoot>
        <?php
            $tcQty='';
            if($tYisuBal < 0){
                $tcQty='red';
            }
            $tcKnitBal='';
            if($tKnitBal < 0){
                $tcKnitBal='red';
            }
            $tcBatchBal='';
            if($tBatchBal < 0){
                $tcBatchBal='red';
            }
            $tcDyeingBal='';
            if($tDyeingBal < 0){
                $tcDyeingBal='red';
            }
            $tcFinBal='';
            if($tFinBal < 0){
                $tcFinBal='red';
            }
            $tcFinIsuToCutBal='';
            if($tFinIsuToCutBal < 0){
                $tcFinIsuToCutBal='red';
            }
 ?>
        <tr>
            <td width="30" align="center"></td>
            <td width="150" align="right"><strong>Total</strong></td>
            <td width="100" align="right"><strong>{{ number_format($tQty) }}</strong></td>
            <td width="100" align="right"><strong>{{ number_format($tGreyFab) }}</strong></td>
            <td width="100" align="right"><strong>{{ number_format($tYisuQty) }}</strong></td>
            <td width="100" align="right" style="color:{{$tcQty}};"><strong>{{ number_format($tYisuBal) }}</strong></td>
            <td width="100" align="right"><strong>{{ number_format($tProdKnitQty) }}</strong></td>
            <td width="100" align="right" style="color:{{$tcKnitBal}};"><strong>{{ number_format($tKnitBal) }}</strong></td>
            <td width="100" align="right"><strong>{{ number_format($tKnitQty) }}</strong></td>
            <td width="100" align="right"><strong>{{ number_format($tRcvByBatchQty) }}</strong></td>
            <td width="100" align="right"><strong>{{ number_format($tBatchQty) }}</strong></td>
            <td width="100" align="right" style="color:{{$tcBatchBal}};"><strong>{{ number_format($tBatchBal) }}</strong></td>
            <td width="100" align="right"><strong>{{ number_format($tDyeingQty) }}</strong></td>
            <td width="100" align="right" style="color:{{$tcDyeingBal}};"><strong>{{ number_format($tDyeingBal) }}</strong></td>
            <td width="100" align="right"><strong>{{ number_format($tFinFab) }}</strong></td>
            <td width="100" align="right"><strong>{{ number_format($tFinQty) }}</strong></td>
            <td width="100" align="right" style="color:{{$tcFinBal}};"><strong>{{ number_format($tFinBal) }}</strong></td>
            <td width="100" align="right"><strong>{{ number_format($tFinIsuToCutQty) }}</strong></td>
            <td width="100" align="right" style="color:{{$tcFinIsuToCutBal}};"><strong>{{ number_format($tFinIsuToCutBal) }}</strong></td>
        </tr> 
    </tfoot>
</table>
<p></p>
@foreach ($buyerdata as $produced_company_id=>$rows)
<p align="center" style="font: 18px 'Arial Narrow', Arial, sans-serif;font-stretch: condensed;"><strong>Buyer Level Summery - {{ $prodCompanyArr[$produced_company_id]['produced_company_name'] }}</strong></p>
<table border="1" cellpadding="2" style="font: 14px 'Arial Narrow', Arial, sans-serif;font-stretch: condensed;">
    <tr>
        <td width="30" align="center"><strong>SL</strong></td>
        <td width="180" align="center"><strong>Buyer Name</strong></td>
        <td width="100" align="center"><strong>Order Qty (Pcs)</strong></td>
        <td width="100" align="center"><strong>Grey Req</strong></td>
        <td width="100" align="center"><strong>Yarn Issue</strong></td>
        <td width="100" align="center"><strong>Yarn Balance</strong></td>
        <td width="100" align="center"><strong>Knitted</strong></td>
        <td width="100" align="center"><strong>Knit Balance</strong></td>
        <td width="100" align="center"><strong>Knit QC Done</strong></td>
        <td width="100" align="center"><strong>Grey To Dye</strong></td>
        <td width="100" align="center"><strong>Batch Qty</strong></td>
        <td width="100" align="center"><strong>Batch Balance</strong></td>
        <td width="100" align="center"><strong>Dyeing Qty</strong></td>
        <td width="100" align="center"><strong>Dyeing Balance</strong></td>
        <td width="100" align="center"><strong>Finish Fabric Req</strong></td>
        <td width="100" align="center"><strong>Finish Fabric Available</strong></td>
        <td width="100" align="center"><strong>Finish Fabric Balance</strong></td>
        <td width="100" align="center"><strong>Issue To Cut</strong></td>
        <td width="100" align="center"><strong>Yet To Issue To Cut</strong></td>
    </tr>
    <?php
        $i=1;
        $tBuyerQty=0;
        $tBuyerGreyFab=0;
        $tBuyerYisuQty=0;
        $tBuyerYisuBal=0;
        $tBuyerProdKnitQty=0;
        $tBuyerKnitBal=0;
        $tBuyerKnitQty=0;
        $tBuyerRcvByBatchQty=0;
        $tBuyerBatchQty=0;
        $tBuyerBatchBal=0;
        $tBuyerDyeingQty=0;
        $tBuyerDyeingBal=0;
        $tBuyerFinFab=0;
        $tBuyerFinQty=0;
        $tBuyerFinBal=0;
        $tBuyerFinIsuToCutQty=0;
        $tBuyerFinIsuToCutBal=0;
    ?>
    @foreach ($rows as $bdata)
    <?php
            $yisu_bal='';
            if($bdata->yisu_bal < 0){
                $yisu_bal='red';
            }
            $knit_bal='';
            if($bdata->knit_bal < 0){
                $knit_bal='red';
            }
            $batch_bal='';
            if($bdata->batch_bal < 0){
                $batch_bal='red';
            }
            $dyeing_bal='';
            if($bdata->dyeing_bal < 0){
                $dyeing_bal='red';
            }
            $finish_bal='';
            if($bdata->finish_bal < 0){
                $finish_bal='red';
            }
            $finish_isu_to_cut_bal='';
            if($bdata->finish_isu_to_cut_bal < 0){
                $finish_isu_to_cut_bal='red';
            }
        ?>
    <tbody> 
        <tr>
            <td width="30" align="center">{{ $i++ }}</td>
            <td width="180" align="left">{{ $bdata->buyer_name }}</td>
            <td width="100" align="right">{{ number_format($bdata->qty) }}</td>
            <td width="100" align="right">{{ number_format($bdata->grey_fab) }}</td>
            <td width="100" align="right">{{ number_format($bdata->yisu_qty) }}</td>
            <td width="100" align="right" style="color:{{$yisu_bal}};">{{ number_format($bdata->yisu_bal) }}</td>
            <td width="100" align="right">{{ number_format($bdata->prod_knit_qty) }}</td>
            <td width="100" align="right" style="color:{{$knit_bal}};">{{ number_format($bdata->knit_bal) }}</td>
            <td width="100" align="right">{{ number_format($bdata->knit_qty) }}</td>
            <td width="100" align="right">{{ number_format($bdata->rcv_by_batch_qty) }}</td>
            <td width="100" align="right">{{ number_format($bdata->batch_qty) }}</td>
            <td width="100" align="right" style="color:{{$batch_bal}};">{{ number_format($bdata->batch_bal) }}</td>
            <td width="100" align="right">{{ number_format($bdata->dyeing_qty) }}</td>
            <td width="100" align="right" style="color:{{$dyeing_bal}};">{{ number_format($bdata->dyeing_bal) }}</td>
            <td width="100" align="right">{{ number_format($bdata->fin_fab) }}</td>
            <td width="100" align="right">{{ number_format($bdata->finish_qty) }}</td>
            <td width="100" align="right" style="color:{{$finish_bal}};">{{ number_format($bdata->finish_bal) }}</td>
            <td width="100" align="right">{{ number_format($bdata->finish_isu_to_cut_qty) }}</td>
            <td width="100" align="right" style="color:{{$finish_isu_to_cut_bal}};">{{ number_format($bdata->finish_isu_to_cut_bal) }}</td>
        </tr>
        <?php
            $tBuyerQty+=$bdata->qty;
            $tBuyerGreyFab+=$bdata->grey_fab;
            $tBuyerYisuQty+=$bdata->yisu_qty;
            $tBuyerYisuBal+=$bdata->yisu_bal;
            $tBuyerProdKnitQty+=$bdata->prod_knit_qty;
            $tBuyerKnitBal+=$bdata->knit_bal;
            $tBuyerKnitQty+=$bdata->knit_qty;
            $tBuyerRcvByBatchQty+=$bdata->rcv_by_batch_qty;
            $tBuyerBatchQty+=$bdata->batch_qty;
            $tBuyerBatchBal+=$bdata->batch_bal;
            $tBuyerDyeingQty+=$bdata->dyeing_qty;
            $tBuyerDyeingBal+=$bdata->dyeing_bal;
            $tBuyerFinFab+=$bdata->fin_fab;
            $tBuyerFinQty+=$bdata->finish_qty;
            $tBuyerFinBal+=$bdata->finish_bal;
            $tBuyerFinIsuToCutQty+=$bdata->finish_isu_to_cut_qty;
            $tBuyerFinIsuToCutBal+=$bdata->finish_isu_to_cut_bal;
        ?>
        
    </tbody>
    @endforeach
    <?php
            $tcBuyerYisuBal='';
            if($tBuyerYisuBal < 0){
                $tcBuyerYisuBal='red';
            }
            $tcBuyerKnitBal='';
            if($tBuyerKnitBal < 0){
                $tcBuyerKnitBal='red';
            }
            $tcBuyerBatchBal='';
            if($tBuyerBatchBal < 0){
                $tcBuyerBatchBal='red';
            }
            $tcBuyerDyeingBal='';
            if($tBuyerDyeingBal < 0){
                $tcBuyerDyeingBal='red';
            }
            $tcBuyerFinBal='';
            if($tBuyerFinBal < 0){
                $tcBuyerFinBal='red';
            }
            $tcBuyerFinIsuToCutBal='';
            if($tBuyerFinIsuToCutBal < 0){
                $tcBuyerFinIsuToCutBal='red';
            }
 ?>
    <tfoot>
        <tr>
            <td width="30" align="center"></td>
            <td width="180" align="right"><strong>Total</strong></td>
            <td width="100" align="right"><strong>{{ number_format($tBuyerQty) }}</strong></td>
            <td width="100" align="right"><strong>{{ number_format($tBuyerGreyFab) }}</strong></td>
            <td width="100" align="right"><strong>{{ number_format($tBuyerYisuQty) }}</strong></td>
            <td width="100" align="right" style="color:{{$tcBuyerYisuBal}};"><strong>{{ number_format($tBuyerYisuBal) }}</strong></td>
            <td width="100" align="right"><strong>{{ number_format($tBuyerProdKnitQty) }}</strong></td>
            <td width="100" align="right" style="color:{{$tcBuyerKnitBal}};"><strong>{{ number_format($tBuyerKnitBal) }}</strong></td>
            <td width="100" align="right"><strong>{{ number_format($tBuyerKnitQty) }}</strong></td>
            <td width="100" align="right"><strong>{{ number_format($tBuyerRcvByBatchQty) }}</strong></td>
            <td width="100" align="right"><strong>{{ number_format($tBuyerBatchQty) }}</strong></td>
            <td width="100" align="right" style="color:{{$tcBuyerBatchBal}};"><strong>{{ number_format($tBuyerBatchBal) }}</strong></td>
            <td width="100" align="right"><strong>{{ number_format($tBuyerDyeingQty) }}y</strong></td>
            <td width="100" align="right" style="color:{{$tcBuyerDyeingBal}};"><strong>{{ number_format($tBuyerDyeingBal) }}</strong></td>
            <td width="100" align="right"><strong>{{ number_format($tBuyerFinFab) }}</strong></td>
            <td width="100" align="right"><strong>{{ number_format($tBuyerFinQty) }}</strong></td>
            <td width="100" align="right" style="color:{{$tcBuyerFinBal}};"><strong>{{ number_format($tBuyerFinBal) }}</strong></td>
            <td width="100" align="right"><strong>{{ number_format($tBuyerFinIsuToCutQty) }}</strong></td>
            <td width="100" align="right" style="color:{{$tcBuyerFinIsuToCutBal}};"><strong>{{ number_format($tBuyerFinIsuToCutBal) }}</strong></td>
        </tr> 
    </tfoot>
</table>
<p></p>
@endforeach