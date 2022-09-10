<style>
    table {
        margin: 0 auto;
    }
    thead,  th {
        background-color: #ccc;
    }
</style>
<table>
@if($is_html)
<tr>
<td width="638" align="center"><img width="300" height="100" src="images/logo/{{ $company->logo }}"/></td>
</tr>
<tr>
<td width="638" align="center">{{$company->address}}</td>
</tr>
<tr>
<td width="638" align="center"><strong><u>Marketing Cost </u></strong></td>
</tr>
@endif
<tr>
<td width="638" align="center"><strong>Status:{{ $mktcost['status'] }}</strong></td>
</tr>
</table>
<table cellspacing="0" cellpadding="2" border="1">
<tr>
<td width="107">Costing ID</td>
<td width="107">{{ $mktcost['id'] }}</td>
<td width="106">Buyer</td>
<td width="106">{{ $mktcost['buyer'] }}</td>
<td width="106">Team</td>
<td width="106">{{ $mktcost['team'] }}</td>
</tr>
<tr>
<td width="107">Style Ref. No</td>
<td width="107">{{ $mktcost['style'] }}</td>
<td width="106">Order UOM</td>
<td width="106">{{ $mktcost['uom'] }}</td>
<td width="106">Offer Qty</td>
<td width="106" align="right">{{ number_format($mktcost['offerqty'],0) }}</td>
</tr>
<tr>


<td width="107">Season</td>
<td width="107">{{ $mktcost['season_name'] }}</td>
<td width="106">OP. Date</td>
<td width="106">{{ $mktcost['opdate'] }}</td>
<td width="106">FOB</td>
<td width="106" align="right">{{ number_format($mktcost['grossFobvalue'],0) }}</td>


</tr>
<tr>
<td width="107">Cost/{{$mktcost['uom']}}</td>
<td width="107">{{ $mktcost['price_aft_commission_pcs'] }}</td>
<td width="106">Target Price/{{$mktcost['uom']}}</td>
<td width="106" align="right">{{ $mktcost['grossTargetPricePcs'] }}</td>

<td width="106">Price/{{$mktcost['uom']}}</td>
<td width="106" align="right">{{ $mktcost['grossFobPrice_pcs'] }}</td>


</tr>
<tr>

<td width="107">Ship Date</td>
<td width="107">{{ $mktcost['estshipdate'] }}</td>
</tr>
</table>
<br/>
<table cellspacing="0" cellpadding="2" border="1">
<thead>
<tr align="center">
<td colspan="9">Garments Production Data</td>
</tr>
<tr align="center">
<td  width="200">Item</td>
<td  width="70">Item Ratio</td>
<td  width="50">SMV/Pcs</td>
<td  width="50">Effi. %</td>
<td  width="50">CM/Pcs</td>
<td  width="50">CM/{{ $mktcost['costingunit'] }}</td>
<td  width="70">Req. Prod / Hr</td>
<td  width="70">Manpower</td>
<td  width="">CPM</td>
</tr>
</thead>
<tbody>
<?php
$i=0;
$smv=0;
$sewing_effi_per=0;
$cm_per_pcs=0;
$amount=0;
$prod_per_hour=0;
$no_of_man_power=0;
$cpm=0;
?>
@foreach($mktcost['cm'] as $row=>$value)

<tr>
<td width="200">{{$value->name}}</td>
<td width="70">{{$value->gmt_qty}}</td>
<td width="50" align="right">{{$value->smv}}</td>
<td width="50" align="right">{{$value->sewing_effi_per}} %</td>
<td width="50" align="right">{{$value->cm_per_pcs}} </td>
<td width="50" align="right">{{ number_format($value->amount,2) }}</td>
<td width="70" align="right">{{ number_format($value->prod_per_hour,0) }}</td>
<td width="70" align="right">{{ number_format($value->no_of_man_power,0) }}</td>
<td width="" align="right">{{ number_format($value->cpm,2) }}</td>
</tr>

<?php
$i++;
$smv+=$value->smv;
$sewing_effi_per+=$value->sewing_effi_per;
$cm_per_pcs+=$value->cm_per_pcs;
$amount+=$value->amount;
$prod_per_hour+=$value->prod_per_hour;
$no_of_man_power+=$value->no_of_man_power;
$cpm+=$value->cpm;

?>
@endforeach
<?php
$mktcost['sewing_smv']=$smv/$i;
$mktcost['sewing_effi_per']=$sewing_effi_per/$i;
$mktcost['production_per_hr']=$prod_per_hour/$i;
?>
</tbody>
</table>
<br/>
<table cellspacing="0" cellpadding="2" border="1">
<tr align="center" style="font-weight: bold">
<td width="57">Yarn Cons. / {{ $mktcost['costingunit'] }}</td>
<td width="57">Fabric Cons. / {{ $mktcost['costingunit'] }}</td>
<td width="72">Avg. Process Loss. %</td>
<td width="50">Trim Cost</td>
<td width="50">Embel. Cost </td>
<td width="50">CM / {{ $mktcost['costingunit'] }}</td>
<td width="57">Avg. SMV / PCS</td>
<td width="106">Avg. Efficiency %</td>
<td width="70">Avg. Req. Prod / Hr</td>
<td width="70">Profit % / {{ $mktcost['costingunit'] }}</td>
</tr>
<tr align="center">
<td width="57">{{ number_format($mktcost['totYarnCons'],2) }}</td>
<td width="57">{{ number_format($mktcost['totalFabricCons'],2) }}</td>
<td width="72">{{ number_format($mktcost['avgFabricProcessLoss'],0) }} %</td>
<td width="50">{{ number_format($mktcost['totalTrimCost'],2) }}</td>
<td width="50">{{ number_format($mktcost['totalEmbCost'],2) }}</td>
<td width="50">{{ number_format($mktcost['totalCmCost'],2) }}</td>
<td width="57">{{ number_format($mktcost['sewing_smv'],2) }}</td>
<td width="106">{{ number_format($mktcost['sewing_effi_per'],0) }} %</td>
<td width="70">{{ number_format($mktcost['production_per_hr'],0) }}</td>
<td width="70">{{ number_format($mktcost['netProfitPercent'],2) }} %</td>
</tr>
</table>
<br/>







<table cellspacing="0" cellpadding="2" border="1">
<tr align="center">
<td width="40">#SL</td>
<td width="358">Particulars</td>
<td width="80">Amount USD/{{ $mktcost['costingunit'] }}</td>
<td width="80">Total Value (Offer Qty)</td>
<td width="80">%</td>
</tr>

<tr align="left">
<td width="40" align="right">1</td>
<td width="358"><b>Gross FOB Value/{{ $mktcost['costingunit'] }}</b></td>
<td width="80" align="right">{{ $mktcost['grossFobPrice'] }}</td>
<td width="80" align="right">{{ $mktcost['grossFobvalue'] }}</td>
<td width="80" align="right">100.00 %</td>
</tr>

<tr align="left">
<td width="40" align="right">2</td>
<td width="358">&nbsp;&nbsp;&nbsp;Less Commision</td>
<td width="80" align="right">{{ $mktcost['totalCommission'] }}</td>
<td width="80" align="right">{{ $mktcost['totalCommissionvalue'] }}</td>
<td width="80" align="right">{{ $mktcost['totalCommissionPercent'] }} %</td>
</tr>

<tr align="left">
<td width="40" align="right">3</td>
<td width="358"><b>Net FOB Value (1-2)</b></td>
<td width="80" align="right">{{ $mktcost['netFobValue'] }}</td>
<td width="80" align="right">{{ $mktcost['netFobValuevalue'] }}</td>
<td width="80" align="right">{{ $mktcost['netFobValuePercent'] }}%</td>
</tr>

<tr align="right">
<td width="40">4</td>
<td width="358" align="left">&nbsp;&nbsp;&nbsp;Less: Cost of Material & Service (4.1+4.2+4.3+4.4+4.5+4.6)</td>
<td width="80">{{ $mktcost['costOfMaterial'] }}</td>
<td width="80">{{ $mktcost['costOfMaterialvalue'] }}</td>
<td width="80">{{ $mktcost['costOfMaterialPercent'] }} %</td>
</tr>

<tr align="right">
<td width="40"></td>
<!-- <td width="40" align="left">4.1</td> -->
<td width="358" align="left"><b>4.1| Yarn Cost</b></td>
<td width="80" >{{ $mktcost['totalYarnCost'] }}</td>
<td width="80">{{ $mktcost['totalYarnCostvalue'] }}</td>
<td width="80">{{ $mktcost['totalYarnCostPercent'] }} %</td>
</tr>

<tr align="right">
<td width="40"></td>
<!-- <td width="40" align="left">4.2</td> -->
<td width="358" align="left"><b>4.2| Fabric Production Cost</b></td>
<td width="80" >{{ $mktcost['totalFabricProdCost'] }}</td>
<td width="80">{{ $mktcost['totalFabricProdCostvalue'] }}</td>
<td width="80">{{ $mktcost['totalFabricProdCostPercent'] }} %</td>
</tr>

<tr align="right">
<td width="40"></td>
<!-- <td width="40" align="left">4.3</td> -->
<td width="358" align="left"><b>4.3| Fabric Purchase Cost</b></td>
<td width="80" >{{ $mktcost['totalFabricCost'] }}</td>
<td width="80">{{ $mktcost['totalFabricCostvalue'] }}</td>
<td width="80">{{ $mktcost['totalFabricCostPercent'] }} %</td>
</tr>

<tr align="right">
<td width="40"></td>
<!-- <td width="40" align="left">4.4</td> -->
<td width="358" align="left"><b>4.4| Trims Cost</b></td>
<td width="80" >{{ $mktcost['totalTrimCost'] }}</td>
<td width="80">{{ $mktcost['totalTrimCostvalue'] }}</td>
<td width="80">{{ $mktcost['totalTrimCostPercent'] }} %</td>
</tr>

<tr align="right">
<td width="40"></td>
<!-- <td width="40" align="left">4.5</td> -->
<td width="358" align="left"><b>4.5| Embelishment Cost</b></td>
<td width="80">{{ $mktcost['totalEmbCost'] }}</td>
<td width="80">{{ $mktcost['totalEmbCostvalue'] }}</td>
<td width="80">{{ $mktcost['totalEmbCostPercent'] }} %</td>
</tr>

<tr align="right">
<td width="40"></td>
<!-- <td width="40" align="left">4.6</td> -->
<td width="358" align="left"><b>4.6| Other Direct Expense</b></td>
<td width="80" >{{ $mktcost['totalOtherCost'] }}</td>
<td width="80">{{ $mktcost['totalOtherCostvalue'] }}</td>
<td width="80">{{ $mktcost['totalOtherCostPercent'] }} %</td>
</tr>

<tr align="right">
<td width="40">5</td>
<td width="358" align="left"><b>Contributions/Value Additions (3-4)</b></td>
<td width="80" >{{ $mktcost['contribution'] }}</td>
<td width="80">{{ $mktcost['contributionvalue'] }}</td>
<td width="80">{{ $mktcost['contributionPercent'] }} %</td>
</tr>

<tr align="right">
<td width="40">6</td>
<td width="358" align="left">&nbsp;&nbsp;&nbsp;Less CM Cost</td>
<td width="80">{{ $mktcost['totalCmCost'] }}</td>
<td width="80">{{ $mktcost['totalCmCostvalue'] }}</td>
<td width="80">{{ $mktcost['totalCmCostPercent'] }} %</td>
</tr>

<tr align="right">
<td width="40">7</td>
<td width="358" align="left"><b>Gross Profit (5-6)</b></td>
<td width="80">{{ $mktcost['grossProfit'] }}</td>
<td width="80">{{ $mktcost['grossProfitvalue'] }}</td>
<td width="80">{{ $mktcost['grossProfitPercent'] }} %</td>
</tr>

<tr align="right">
<td width="40">8</td>
<td width="358" align="left">&nbsp;&nbsp;&nbsp;Less Commercial Cost</td>
<td width="80" >{{ $mktcost['totalCommercialCost'] }}</td>
<td width="80">{{ $mktcost['totalCommercialCostvalue'] }}</td>
<td width="80">{{ $mktcost['totalCommercialCostPercent'] }} %</td>
</tr>

<tr align="right">
<td width="40">9</td>
<td width="358" align="left">&nbsp;&nbsp;&nbsp;Less Operating Expense</td>
<td width="80">{{ $mktcost['totalOperatingCost'] }}</td>
<td width="80">{{ $mktcost['totalOperatingCostvalue'] }}</td>
<td width="80">{{ $mktcost['totalOperatingCostPercent'] }} %</td>
</tr>

<tr align="right">
<td width="40">10</td>
<td width="358" align="left"><b>Operating Profit/Loss (7-(8+9))</b></td>
<td width="80">{{ $mktcost['operatingProfitLoss'] }}</td>
<td width="80">{{ $mktcost['operatingProfitLossvalue'] }}</td>
<td width="80">{{ $mktcost['operatingProfitLossPercent'] }} %</td>
</tr>

<tr align="right">
<td width="40">11</td>
<td width="358" align="left">&nbsp;&nbsp;&nbsp;Less Depreciation & Amortization</td>
<td width="80">{{ $mktcost['totalDepreciationCost'] }}</td>
<td width="80">{{ $mktcost['totalDepreciationCostvalue'] }}</td>
<td width="80">{{ $mktcost['totalDepreciationCostPercent'] }} %</td>
</tr>

<tr align="right">
<td width="40">12</td>
<td width="358" align="left">&nbsp;&nbsp;&nbsp;Less Interest</td>
<td width="80">{{ $mktcost['totalInterestCost'] }}</td>
<td width="80">{{ $mktcost['totalInterestCostvalue'] }}</td>
<td width="80">{{ $mktcost['totalInterestCostPercent'] }} %</td>
</tr>

<tr align="right">
<td width="40">13</td>
<td width="358" align="left">&nbsp;&nbsp;&nbsp;Less Income Tax</td>
<td width="80">{{ $mktcost['totalIcomeTaxCost'] }}</td>
<td width="80">{{ $mktcost['totalIcomeTaxCostvalue'] }}</td>
<td width="80">{{ $mktcost['totalIcomeTaxCostPercent'] }} %</td>
</tr>

<tr align="right">
<td width="40">14</td>
<td width="358" align="left"><b>Net Profit(10-(11+12+13))</b></td>
<td width="80">{{ $mktcost['netProfit'] }}</td>
<td width="80">{{ $mktcost['netProfitvalue'] }}</td>
<td width="80">{{ $mktcost['netProfitPercent'] }} %</td>
</tr>
</table>

<br/>

<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td colspan="7">Fabric Cost</td>
    </tr>
    <tr align="center">
        <td width="70px">Fabric Nature</td>
        <td width="308px">Fabric Description</td>
        <td width="80px">Source</td>
        <td width="30px">UOM</td>
        <td width="50px">Cons</td>
        <td width="40px">Rate</td>
        <td width="60px">Amount</td>
    </tr>
    </thead>
    <tbody>
    <?php 
    $i=1;
    $tot=0;
    ?>
    @foreach($mktcost['fabrics']['main'] as $row=>$value)
    <?php
    $cons=$value['req_cons'];
    ?>
    <tr>
        <td width="70px">{{ $value['fabricnature'] }}</td>
        <td width="308px"> {{ $value['style_gmt'] }} {{ $value['gmtspart'] }} {{ $value['fabric_description'] }}  {{ $value['fabriclooks'] }} {{ $value['fabricshape'] }}
,{{ $value['gsm_weight'] }}
        </td>
        <td width="80px" align="center">{{ $value['materialsourcing'] }}</td>
        <td width="30px" align="center">{{ $value['uom_name'] }}</td>
        <td width="50px" align="right">{{   $cons }}</td>
        <td width="40px" align="right">{{ $value['rate'] }}</td>
        <td width="60px" align="right">{{ $value['amount'] }}</td>
    </tr>
    <?php 
    $tot+= $value['amount'];
    $i++;
    ?>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td width="70px"></td>
        <td width="308px"></td>
        <td width="80px"></td>
        <td width="30px"></td>
        <td width="50px"></td>
        <td width="40px">Total</td>
        <td width="60px" align="right">{{ $tot}}</td>
    </tr>
    </tfoot>
</table>

<br/>

<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td colspan="7">Narrow Cost</td>
    </tr>
    <tr align="center">
        <td width="70px">Fabric Nature</td>
        <td width="308px">Fabric Description</td>
        <td width="80px">Source</td>
        <td width="30px">UOM</td>
        <td width="50px">Cons</td>
        <td width="40px">Rate</td>
        <td width="60px">Amount</td>
    </tr>
    </thead>
    <tbody>
    <?php 
    $i=1;
    $tot=0;
    ?>
    @foreach($mktcost['fabrics']['narrow'] as $row=>$value)
    <?php
    $cons=$value['req_cons'];
    ?>
    <tr>
        <td width="70px">{{ $value['fabricnature'] }}</td>
        <td width="308px"> {{ $value['style_gmt'] }} {{ $value['gmtspart'] }} {{ $value['fabric_description'] }}  {{ $value['fabriclooks'] }} {{ $value['fabricshape'] }},{{ $value['gsm_weight'] }}</td>
        <td width="80px" align="center">{{ $value['materialsourcing'] }}</td>
        <td width="30px" align="center">{{ $value['uom_name'] }}</td>
        <td width="50px" align="right">{{   $cons }}</td>
        <td width="40px" align="right">{{ $value['rate'] }}</td>
        <td width="60px" align="right">{{ $value['amount'] }}</td>
    </tr>
    <?php 
    $tot+= $value['amount'];
    $i++;
    ?>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td width="70px"></td>
        <td width="308px"></td>
        <td width="80px"></td>
        <td width="30px"></td>
        <td width="50px"></td>
        <td width="40px">Total</td>
        <td width="60px" align="right">{{ $tot}}</td>
    </tr>
    </tfoot>
</table>

<br/>

<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td colspan="4">Yarn Cost</td>
    </tr>
    <tr align="center">
        <td width="488px">Yarn Description</td>
        <td width="50px">Cons</td>
        <td width="40px">Rate</td>
        <td width="60px">Amount</td>
    </tr>
    </thead>
    <tbody>
    <?php 
    $i=1;
    $tot=0;
    $totYarn=0;
    $totYarnAmount=0;
    ?>
    @foreach($mktcost['yarns'] as $row=>$value)
    <?php
    $cons=$value['yarn_cons'];
    ?>
    <tr>
        <td width="488px"> {{ $value['yarn_des'] }}</td>
        <td width="50px" align="right">{{   $cons }}</td>
        <td width="40px" align="right">{{ $value['yarn_rate'] }}</td>
        <td width="60px" align="right">{{ $value['yarn_amount'] }}</td>
    </tr>
    <?php 
    $tot+= $value['yarn_amount'];
    $totYarn+=$cons;
    $totYarnAmount+=$value['yarn_amount'];
    $i++;
    ?>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td width="488px"></td>
        <td width="50px"></td>
        <td width="40px">Total</td>
        <td width="60px" align="right">{{ $tot}}</td>
    </tr>
    </tfoot>
</table>

<br/>

<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td colspan="5">Fabric Production Cost</td>
    </tr>
    <tr align="center">
        <td width="400px">Fabric Description</td>
        <td width="88px">Process</td>
        <td width="50px">Cons</td>
        <td width="40px">Rate</td>
        <td width="60px">Amount</td>
    </tr>
    </thead>
    <tbody>
    <?php 
    $i=1;
    $tot=0;
    $totArr=array();
    ?>
    @foreach($mktcost['fabricProd'] as $row=>$value)
    <?php
    $cons=$value['cons'];
    if(isset($totArr[$value['process_id']]['qty'])){
       $totArr[$value['process_id']]['qty']+= $cons; 
    }
    else
    {
       $totArr[$value['process_id']]['qty']= $cons; 
    }
    if(isset($totArr[$value['process_id']]['amount'])){
       $totArr[$value['process_id']]['amount']+=  $value['amount']; 
    }
    else
    {
       $totArr[$value['process_id']]['amount']=  $value['amount']; 
    }
    
    ?>
    <tr>
        <td width="400px"> {{ $value['mktcostfabric'] }}</td>
        <td width="88px">{{ $value['process_id'] }}</td>
        <td width="50px" align="right">{{   $cons }}</td>
        <td width="40px" align="right">{{ $value['rate'] }}</td>
        <td width="60px" align="right">{{ $value['amount'] }}</td>
    </tr>
    <?php 
    $tot+= $value['amount'];
    $i++;
    ?>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td width="400px"></td>
        <td width="88px"></td>
        <td width="50px"></td>
        <td width="40px">Total</td>
        <td width="60px" align="right">{{ $tot}}</td>
    </tr>
    </tfoot>
</table>

<br/>

<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td colspan="6">Trim Cost</td>
    </tr>
    <tr align="center">
        <td  width="170px">Item Class</td>
        <td  width="238px">Description</td>
        <td  width="70px">Uom</td>
        <td  width="50px" align="right">Cons</td>
        <td  width="50px" align="right">Rate</td>
        <td  width="60px" align="right">Amount</td>
    </tr>
    </thead>
    <tbody>
    <?php 
    $i=1;
    $tot=0;
    ?>
    @foreach($mktcost['trims'] as $row=>$value)
    <?php
    $cons=$value['cons'];
    ?>
    <tr>
        <td width="170px"> {{ $value['name'] }}</td>
        <td width="238px">{{ $value['description'] }}</td>
        <td width="70px">{{ $value['code'] }}</td>
        <td width="50px" align="right">{{   number_format($cons,4) }}</td>
        <td width="50px" align="right">{{ number_format($value['rate'],4) }}</td>
        <td width="60px" align="right">{{ number_format($value['amount'],4) }}</td>
    </tr>
    <?php 
    $tot+= $value['amount'];
    $i++;
    ?>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td width="170px"></td>
        <td width="238px"></td>
        <td width="70px"></td>
        <td width="50px"></td>
        <td width="50px">Total</td>
        <td width="60px" align="right">{{ number_format($tot,4)}}</td>
    </tr>
    </tfoot>
</table>


<br/>

<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td colspan="6">Embelishment & Wash Cost</td>
    </tr>
    <tr align="center">
        <td  width="170px">Gmt. Item</td>
        <td  width="238px">Name</td>
        <td  width="70px">Type</td>
        <td  width="50px" align="right">Gmt. Qty</td>
        <td  width="50px" align="right">Rate</td>
        <td  width="60px" align="right">Amount</td>
    </tr>
    </thead>
    <tbody>
    <?php 
    $i=1;
    $tot=0;
    ?>
    @foreach($mktcost['embs'] as $row=>$value)
    <?php
    $cons=$value['cons'];
    ?>
    <tr>
        <td width="170px"> {{ $value['item_description'] }}</td>
        <td width="238px">{{ $value['embelishment_name'] }}</td>
        <td width="70px">{{ $value['embelishment_type'] }}</td>
        <td width="50px" align="right">{{   number_format($cons,4) }}</td>
        <td width="50px" align="right">{{ number_format($value['rate'],4) }}</td>
        <td width="60px" align="right">{{ number_format($value['amount'],4) }}</td>
    </tr>
    <?php 
    $tot+= $value['amount'];
    $i++;
    ?>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td width="170px"></td>
        <td width="238px"></td>
        <td width="70px"></td>
        <td width="50px"></td>
        <td width="50px">Total</td>
        <td width="60px" align="right">{{ number_format($tot,4)}}</td>
    </tr>
    </tfoot>
</table>
<br/>


<table cellspacing="0" cellpadding="2" border="0">
<tr>
<td width="319">
<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td colspan="2">Other Cost</td>
    </tr>
    <tr align="center">
        <td  width="260">Head</td>
        <td  width="" align="right">Amount</td>
    </tr>
    </thead>
    <tbody>
    <?php 
    $i=1;
    $tot=0;
    $totOther=0;
    ?>
    @foreach($mktcost['other'] as $row=>$value)
    <tr>
        <td> {{ $value['cost_head'] }}</td>
        <td width="" align="right">{{ number_format($value['amount'],4) }}</td>
    </tr>
    <?php 
    $tot+= $value['amount'];
    $totOther+=$value['amount'];
    $i++;
    ?>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td>Total</td>
        <td width="" align="right">{{ number_format($tot,4)}}</td>
    </tr>
    </tfoot>
</table>

<br/>

<table cellspacing="0" cellpadding="2" border="1">
<thead>
     <tr align="center">
        <td colspan="2">CM Cost</td>
    </tr>
    <tr align="center">
        <td  width="260"></td>
        <td  width="" align="right">Amount</td>
    </tr>
    </thead>
    <tbody>
        @foreach($mktcost['cm'] as $row=>$value)
    <tr>
        <td>{{$value->name}}</td>
        <td width="" align="right">{{ number_format($value->amount,4) }}</td>
    </tr>
    @endforeach
    </tbody>
</table>


<br/>

<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td colspan="2">Commercial Cost</td>
    </tr>
    <tr align="center">
        <td  width="260" align="right">Rate</td>
        <td  width="" align="right">Amount</td>
    </tr>
    </thead>
    <tbody>
    <?php 
    $i=1;
    $tot=0;
    ?>
    @foreach($mktcost['commercial'] as $row=>$value)
    <tr>
        <td align="right"> {{ $value['rate'] }}</td>
        <td width="" align="right">{{ number_format($value['amount'],4) }}</td>
    </tr>
    <?php 
    $tot+= $value['amount'];
    $i++;
    ?>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td>Total</td>
        <td width="" align="right">{{ number_format($tot,4)}}</td>
    </tr>
    </tfoot>
</table>

<br/>

<table cellspacing="0" cellpadding="2" border="1">
<thead>
     <tr align="center">
        <td colspan="2">Total Cost</td>
    </tr>
    <tr align="center">
        <td  width="260"></td>
        <td  width="" align="right">Amount</td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td></td>
        <td width="" align="right">{{ number_format($mktcost['total_cost'],4) }}</td>
    </tr>
    </tbody>
</table>


<br/>

<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td colspan="2">Profit</td>
    </tr>
    <tr align="center">
        <td  width="260"></td>
        <td  width="" align="right">Amount</td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td></td>
        <td width="" align="right">{{ number_format($mktcost['profit'],4) }}</td>
    </tr>
    </tbody>
</table>

<br/>

<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td colspan="2">Price Before Commission</td>
    </tr>
    <tr align="center">
        <td  width="260"></td>
        <td  width="" align="right">Amount</td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td></td>
        <td width="" align="right">{{ number_format($mktcost['price_bfr_commission'],4) }}</td>
    </tr>
    </tbody>
</table>
<br/>

<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td colspan="2">Commission Cost</td>
    </tr>
    <tr align="center">
        <td  width="260" align="right">Rate</td>
        <td  width="" align="right">Amount</td>
    </tr>
    </thead>
    <tbody>
    <?php 
    $i=1;
    $tot=0;
    ?>
    @foreach($mktcost['commission'] as $row=>$value)
    <tr>
        <td align="right"> {{ $value['rate'] }}</td>
        <td width="" align="right">{{ number_format($mktcost['grossFobPrice']*$value['rate']/100,4) }}</td>
    </tr>
    <?php 
    $tot+= $mktcost['grossFobPrice']*$value['rate']/100;
    $i++;
    ?>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td>Total</td>
        
        <td width="" align="right">{{ number_format($tot,4)}}</td>
    </tr>
    </tfoot>
</table>

<br/>

<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td colspan="2">Price After Commission</td>
    </tr>
    <tr align="center">
        <td  width="260"></td>
        <td  width="" align="right">Amount</td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td></td>
        <td width="" align="right">{{ number_format($mktcost['price_aft_commission'],4) }}</td>
    </tr>
    </tbody>
</table>

<br/>

<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td colspan="2">Quoted Price</td>
    </tr>
    <tr align="center">
        <td  width="260">Date</td>
        <td  width="" align="right">Price/{{$mktcost['uom']}}</td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>{{ $mktcost['QuotedPrice']->qprice_date }}</td>
        <td width="" align="right">{{ number_format($mktcost['QuotedPrice']->quote_price,4) }}</td>
    </tr>
    </tbody>
</table>

<br/>

<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td colspan="2">Target Price</td>
    </tr>
    <tr align="center">
        <td  width="260">Date</td>
        <td  width="" align="right">Price/{{$mktcost['uom']}}</td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>{{ $mktcost['TargetPrice']->price_date }}</td>
        <td width="" align="right">{{ number_format($mktcost['TargetPrice']->target_price,4) }}</td>
    </tr>
    </tbody>
</table>
</td>

<td width="319" valign="top">
@if($mktcost['flie_src'])

<!-- <img src="{{url('/').'/images/'.$mktcost['flie_src']}}" /> -->
<img src="images/{{ $mktcost['flie_src'] }}"/>
<br/>
@endif
    


    <table cellspacing="0" cellpadding="2" border="1">
    <thead>
    <tr align="center">
    <td colspan="3">Summary</td>
    </tr>
    </thead>
    <tbody>

    <tr>
    <td width="199">SMV/Pcs</td>
    <td width="65" align="right">{{ number_format($mktcost['sewing_smv'],2) }}</td>
    <td  width="" align="right"></td>
    </tr>
    <tr>
    <td>C.P.M</td>
    <td  align="right">{{ number_format($mktcost['cpm'],2) }}</td>
    <td  width="" align="right"></td>
    </tr>
    <tr>
    <td>Efficiency%</td>
    
    <td align="right">{{ number_format($mktcost['sewing_effi_per'],2) }}</td>
    <td width="" align="right"></td>
    </tr>

    </tbody>
    </table>
    <br/>
    <?php 
    $totFabricCost=0;
    $totFabricCostKg=0;
    ?>
<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td  width="100">Cost Head</td>
        <td  width="60" align="right">Cons</td>
        <td  width="60" align="right">Cost</td>
        <td  width="" align="right">Cost/Kg</td>
    </tr>
    </thead>
    <tbody>
     @if($totYarn) 
     <?php 
     $totYarnAmountKg=number_format($totYarnAmount/$totYarn,4,'.','');
     $totFabricCost+=$totYarnAmount;
     $totFabricCostKg+=$totYarnAmountKg;
     ?> 
    <tr>
        <td width="100px">Yarn</td>
        <td width="60px" align="right">{{ number_format($totYarn,4) }}</td>
        <td  width="60" align="right">{{ number_format($totYarnAmount,4) }}</td>
        <td  width="" align="right">{{ number_format($totYarnAmount/$totYarn,4) }}</td>
    </tr>
    @endif
    @foreach($totArr as $process=>$processValue)
     @if($process)
     <?php 
     $totProcessAmountKg=number_format($processValue['amount']/$totYarn,4,'.','');
     $totFabricCost+=$processValue['amount'];
     $totFabricCostKg+=$totProcessAmountKg;
     ?>  
    <tr>
        <td width="100px">{{$process}}</td>
        <td width="60px" align="right"></td>
        <td  width="60" align="right">{{ number_format($processValue['amount'],4) }}</td>
        <td  width="" align="right">{{ number_format($processValue['amount']/$totYarn,4) }}</td>
    </tr>
    @endif
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td width="100px">Total</td>
        <td width="60px" align="right">{{ number_format($totYarn,4) }}</td>
        <td  width="60" align="right">{{ number_format($totFabricCost,4) }}</td>
        <td  width="" align="right">{{ number_format($totFabricCostKg,4) }}</td>
    </tr>
    </tfoot>
</table>
<br/>
    <table cellspacing="0" cellpadding="2" border="1">
    <tbody>
    @if($mktcost['totalTrimCost']>0)
    <tr>
    <td width="199px">Trims</td>
  <td width="60px" align="right">{{ number_format($mktcost['totalTrimCost'],4) }}</td>
    <td  width="" align="right"></td>
    </tr>
    @endif
    @if($mktcost['totalEmbCost']>0)
    <tr>
    <td width="199px">Embelishment</td>
    
    <td width="60px" align="right">{{ number_format($mktcost['totalEmbCost'],4) }}</td>
    <td  width="" align="right"></td>
    </tr>
    @endif
    @if($mktcost['totalTrimCost']>0)
    <tr>
    <td width="199px">CM</td>
    <td width="60px" align="right">{{ number_format($mktcost['totalCmCost'],4) }}</td>
    <td  width="" align="right"></td>
    </tr>
    @endif
    @if($mktcost['totalCommercialCost']>0)
    <tr>
    <td width="199px">Commercial</td>
    <td width="60px" align="right">{{ number_format($mktcost['totalCommercialCost'],4) }}</td>
    <td  width="" align="right"></td>
    </tr>
    @endif
    @if($totOther)
    <tr>
    <td width="199px">Other</td>
    <td width="60px" align="right">{{ number_format($totOther,4) }}</td>
    <td  width="" align="right"></td>
    </tr>
    @endif
    </tbody>
    <tfoot>
    <tr>
        <td width="199px">Total Cost</td>
        <td  width="60" align="right">{{ number_format($totFabricCost+$mktcost['totalTrimCost']+$mktcost['totalEmbCost']+$mktcost['totalCmCost']+$mktcost['totalCommercialCost']+$totOther,4) }}</td>
        <td  width="" align="right"></td>
    </tr>
    </tfoot>
    </table>
    


</td>
</tr>

</table>
<br/>
<table cellspacing="0" cellpadding="2" border="1">
    <thead>
      <tr>
        <th width="30">#</th>
        <th width="300">
            Comments
        </th>
        <th width="150">
            Comments By
        </th>
        <th>
            Comments At
        </th>
    </tr>  
    </thead>
    <tbody>
        <?php
        $i=1;
        ?>
        @foreach($mktcost['comment_histories'] as $comments)
        <tr>
            <td width="30">{{$i}}</td>
            <td width="300">
                {{$comments->comments}}
            </td>
            <td width="150">
                {{$comments->user_name}}
            </td>
            <td>
                {{$comments->comments_at}}
            </td>
        </tr>
        <?php
        $i++;
        ?>
        @endforeach
    </tbody>
    
</table>
<br/>
@if($is_html)
<table>
<tr>
<td width="638" align="center">
<form id="mktcostaprovalreturncommentFrm">
<textarea cols="3" rows="5" id="mkt_cost_aproval_return_comments" name="mkt_cost_aproval_return_comments"></textarea>
</form>
</td>
</tr>

</table>
<br/>
<br/>
<br/>
<table>
<tr>
<td width="638" align="center">
@permission('approvefirst.mktcosts')
<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; font-size: 24px; border-radius:1px; padding: 5px 10px; margin-right: 30px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCostApproval.approveSingle('{{$approval_type}}',{{$mktcost['id']}})">Approve
</a>
<a href="javascript:void(0)" class="easyui-linkbutton  c2" style="height:25px; font-size: 24px; border-radius:1px; padding: 5px 10px; margin-right: 30px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCostApproval.appReturn('{{$approval_type}}',{{$mktcost['id']}})">Return
@endpermission
@permission('approveconfirm.mktcosts')
<a href="javascript:void(0)" class="easyui-linkbutton  c4" style="height:25px; font-size: 24px; border-radius:1px; padding: 5px 10px; margin-right: 30px" iconCls="icon-save" plain="true" id="save" onClick="MsMktCostConfirmation.confirmed(event,{{$mktcost['id']}})">Confirm
</a>

@endpermission
<a href="javascript:void(0)" class="easyui-linkbutton  c4" style="height:25px; font-size: 24px; border-radius:1px; padding: 5px 10px" iconCls="icon-save" plain="true" id="save" onClick="closeWindow()">Close
</a>
</td>
</tr>
<tr><td width="638" align="center"></td></tr>
</table>
@endif
@if( !$is_html)
<table border="0" cellspacing="0" cellpadding="2">
    <tr><td width="638" colspan="4"></td></tr>
    <tr><td width="638" colspan="4"></td></tr>
    <tr><td width="638" colspan="4"></td></tr>
    <tr>
        <td width="213">@if ($mktcost['first_approval_signature'])<img src="{{ $mktcost['first_approval_signature'] }}" width="100", height="40"/>
            @endif</td>
        <td width="213">@if ($mktcost['second_approval_signature'])<img src="{{ $mktcost['second_approval_signature'] }}" width="100", height="40"/>
            @endif</td>
        
        <td width="212" align="center">@if ($mktcost['final_approval_signature'])<img src="{{ $mktcost['final_approval_signature'] }}" width="100", height="40"/>@endif
            @if(!$mktcost['final_approval_name'])<strong style="font-stretch: ultra-expanded" >UNAPPROVED</strong>@endif</td>
    </tr>
    <tr>
        <td width="213"><strong>Director</strong></td>
        <td width="213"><strong>Deputy Managing Director</strong></td>
        <td width="212" align="center"><strong>Managing Director</strong></td>
    </tr>
    <tr>
        <td width="213"><strong>{{ $mktcost['first_approval_emp_name'] }}<br/>
            {{ $mktcost['first_approval_emp_contact'] }}<br/>
            {{ $mktcost['first_approval_emp_designation'] }}<br/>
            {{ $mktcost['first_approved_at'] }}
            </strong>
        </td>
        
        <td width="213"><strong>{{ $mktcost['second_approval_name'] }}<br/>
            {{ $mktcost['second_approved_at'] }}
            </strong>
        </td>
        <td width="212" align="center"><strong>{{ $mktcost['final_approval_name'] }}<br/>
            {{ $mktcost['final_approved_at'] }}
            </strong>
        </td>
    </tr>
</table>
@endif
@if($is_html)
<script>
    function closeWindow(){
        $('#mktcostConfirmationDetailContainer').html('');
        $('#mktcostConfirmationDetailWindow').window('close');
        $('#mktcostApprovalDetailContainer').html('');
        $('#mktcostApprovalDetailWindow').window('close');
    }
</script>
@endif