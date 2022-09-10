
<br/>
<?php 
$k=0; 
$red='#ff6666';
?>
@foreach($rows as $row)
<br/>
<strong>{{$row->company_code}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  {{$row->ceo}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $row->date_to }}</strong>
<table border="1" style="border-style:dotted">
    <tr align="center">
        <td width="120px"align="center" colspan="25">-</td>
    </tr>
    <tr align="center">
        <td width="20px"rowspan="2">#</td>
        <td width="120px">1</td>
        <td width="70px">2</td>
        <td width="70px">3</td>
        <td width="70px">4</td>
        <td width="70px">5</td>
        <td width="70px">6</td>
        <td width="70px">7</td>
        <td width="70px">8</td>
        <td width="70px">9</td>
        <td width="70px">10</td>
        <td width="70px">11</td>
        <td width="70px">12</td>
        <td width="70px">13</td>
        <td width="70px">14</td>
        <td width="70px">15</td>
        <td width="70px">16</td>
        <td width="70px">17</td>
        <td width="70px">18</td>
        <td width="70px">19</td>
        <td width="70px">20</td>
        <td width="70px">21</td>
        <td width="70px">22</td>
        <td width="70px">23</td>
        <td width="70px">24</td>
    </tr>
    <tr align="center">
        <td width="120px"align="left">Particulars</td>
        <td width="70px" >TTL</td>
        <td width="70px" >Line to be Set</td>
        <td width="70px" >In-active Line</td>
        <td width="70px">Active Line</td>
        <td width="70px">AOP in Kg</td>
        <td width="70px">Cutting Qty</td>
        <td width="70px">Screen Print Qty</td>
        <td width="70px">Embroidery Qty</td>
        <td width="70px">Sewing Qty</td>
        <td width="70px">Finshing Qty</td>
        <td width="70px">Ex-Factory Qty</td>
        <td width="70px">Sewing FOB Value</td>
        <td width="70px">Finshing FOB Value</td>
        <td width="70px">Exfactory FOB Value</td>
        <td width="70px">CM - Mkt</td>
        <td width="70px">CM - Mnfg</td>
        <td width="70px">BEP in Qty</td>
        <td width="70px">BEP: CM Earnings - Tk.</td>
        <td width="70px" title="Total SMV">Est. SMV</td>
        <td width="70px">Used SMV</td>
        <td width="70px">SMV Variance</td>
        <td width="70px">Avg SMV/Pcs</td>
        <td width="70px">CM Cost/Pcs</td>
    </tr>
    <tr align="right">
        <td width="20px"align="center">1</td>
        <td width="120px" align="left">Man Power</td>
        <td width="70px"  align="left"></td>
        <td width="70px"  align="left"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px">{{number_format($row->emp_att_cutting_staff,0)}}</td>
        <td width="70px">{{number_format($row->emp_att_printing_staff,0)}}</td>
        <td width="70px">{{number_format($row->emp_att_embroidery_staff,0)}}</td>
        <td width="70px">{{number_format($row->attendence,0)}}</td>
        <td width="70px">{{number_format($row->emp_att_finishing_staff,0)}}</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
    </tr>
    <tr align="right">
         <td width="20px"align="center">2</td>
        <td width="120px" align="left">Man Machine Ratio</td>
        <td width="70px"  align="left"></td>
        <td width="70px"  align="left"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"> 1 : {{number_format($row->man_machine,2)}}</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
    </tr>
    <tr align="center">
        <td width="120px"align="center" colspan="25">-</td>
    </tr>
    
    <tr align="right">
        <td width="20px"align="center">3</td>
        <td width="120px" align="left">Capacity</td>
        <td width="70px">{{$row->ttl_qty}}</td>
        <td width="70px">{{$row->pjd_qty}}</td>
        <td width="70px">{{$row->iact_qty}}</td>
        <td width="70px">{{$row->act_qty}}</td>
        <td width="70px"></td>
        <td width="70px">{{number_format($row->cutting_capacity_qty,0)}}</td>
        <td width="70px">{{number_format($row->screen_print_capacity_qty,0)}}</td>
        <td width="70px">{{number_format($row->embroidery_capacity_qty,0)}}</td>
        <td width="70px">{{number_format($row->qty,0)}}</td>
        <td width="70px">{{number_format($row->cartoning_capacity_qty,0)}}</td>
        <td width="70px">{{number_format($row->cartoning_capacity_qty,0)}}</td>
        <td width="70px">{{number_format($row->amount,2)}}</td>
        <td width="70px">{{number_format($row->cartoning_capacity_amount,0)}}</td>
        <td width="70px">{{number_format($row->cartoning_capacity_amount,0)}}</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px">{{number_format($row->bepinqty,0)}}</td>
        <td width="70px"><a href="javascript:void(0)" onClick="MsProdGmtCapacityAchievement.bepWindow({{$row->company_id}})">{{number_format($row->bepinsale,0)}}</a></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px">{{number_format($row->avg_sew_smv,2)}}</td>
        <td width="70px">{{number_format($row->cm_pcs,2)}}</td>
    </tr>
     <tr align="right">
        <td width="20px"align="center">4</td>
        <td width="120px" align="left"><a href="javascript:void(0)" onClick="MsProdGmtCapacityAchievement.commonWindow({{$row->company_id}})">Today Achivement</a></td>
        <td width="70px">{{$row->ttl_qty}}</td>
        <td width="70px">{{$row->pjd_qty}}</td>
        <td width="70px">{{$row->pdc_iact_qty}}</td>
        <td width="70px">{{$row->pdc_qty}}</td>
        <td width="70px"></td>
        <td width="70px"><a href="javascript:void(0)" onClick="MsProdGmtCapacityAchievement.cuttingWindow({{$row->company_id}})">{{number_format($row->cut_qty,0)}}</a></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"><a href="javascript:void(0)" onClick="MsProdGmtCapacityAchievement.detailWindowTwo({{$row->company_id}})">{{number_format($row->sew_qty,0)}}</a></td>
        <td width="70px"><a href="javascript:void(0)" onClick="MsProdGmtCapacityAchievement.cartonWindow({{$row->company_id}})">{{number_format($row->cty,0)}}</a></td>
        <td width="70px"><a href="javascript:void(0)" onClick="MsProdGmtCapacityAchievement.exfactoryWindow({{$row->company_id}})">{{number_format($row->exfactory_qty,0)}}</a></td>
        <td width="70px">{{number_format($row->sew_amount,2)}}</td>
        <td width="70px">{{number_format($row->cmo,2)}}</td>
        <td width="70px">{{number_format($row->exfactory_amount,2)}}</td>
        <td width="70px"></td>
        <td width="70px"><a href="javascript:void(0)" onClick="MsProdGmtCapacityAchievement.cartonWindow({{$row->company_id}})">{{number_format($row->bepincmproduced,2)}}</a></td>
        <td width="70px">{{number_format($row->cty,0)}}</td>
        <td width="70px"><a href="javascript:void(0)" onClick="MsProdGmtCapacityAchievement.cmWindow({{$row->company_id}})">{{--number_format($row->budgeted_produced_cm,2)--}}</a></td>
        <td width="70px">{{number_format($row->sew_smv,2)}}</td>
        <td width="70px">{{number_format($row->used_smv,2)}}</td>
        <td width="70px">{{number_format($row->used_smv-$row->sew_smv,2)}}</td>
        <td width="70px">{{number_format($row->avg_used_smv,2)}}</td>
        <td width="70px">{{number_format($row->cm_used_pcs,2)}}</td>
    </tr>
    
     <tr align="right">
        <td width="20px"align="center">5</td>
        <td width="120px" align="left">Capacity Variance</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px" style=" background-color:<?php if($row->dev_cut_qty <0) {echo $red; }else{ echo ''; }?>">{{number_format($row->dev_cut_qty,0)}}</td>
        <td width="70px" style=" background-color:<?php if($row->dev_screen_print_qty <0) {echo $red; }else{ echo ''; }?>">{{number_format($row->dev_screen_print_qty,0)}}</td>
        <td width="70px" style=" background-color:<?php if($row->dev_embroidery_qty <0) {echo $red; }else{ echo ''; }?>">{{number_format($row->dev_embroidery_qty,0)}}</td>
        <td width="70px" style=" background-color:<?php if($row->dev_sew_qty <0) {echo $red; }else{ echo ''; }?>">{{number_format($row->dev_sew_qty,0)}}</td>
        <td width="70px" style=" background-color:<?php if($row->dev_fin_qty <0) {echo $red; }else{ echo ''; }?>">{{number_format($row->dev_fin_qty,0)}}</td>

        <td width="70px" style=" background-color:<?php if($row->dev_exfactory_qty <0) {echo $red; }else{ echo ''; }?>">{{number_format($row->dev_exfactory_qty,0)}}</td>

        <td width="70px" style=" background-color:<?php if($row->dev_sew_amount <0) {echo $red; }else{ echo ''; }?>">{{number_format($row->dev_sew_amount,2)}}</td>
        <td width="70px" style=" background-color:<?php if($row->dev_fin_amount <0) {echo $red; }else{ echo ''; }?>">{{number_format($row->dev_fin_amount,2)}}</td>
        <td width="70px" style=" background-color:<?php if($row->dev_exfactory_amount <0) {echo $red; }else{ echo ''; }?>">{{number_format($row->dev_exfactory_amount,0)}}</td>
        <td width="70px"></td>

        <td width="70px">{{--number_format($row->dev_cm_amount,2)--}}</td>

        <td width="70px" style=" background-color:<?php if($row->dev_bep_qty <0) {echo $red; }else{ echo ''; }?>">{{number_format($row->dev_bep_qty,0)}}</td>

        <td width="70px"></td>
        <td width="70px">{{--number_format($row->dev_sew_smv,2)--}}</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px" style=" background-color:<?php if($row->dev_avg_sew_smv <0) {echo $red; }else{ echo ''; }?>">{{number_format($row->dev_avg_sew_smv,2)}}</td>
         <td width="70px" style=" background-color:<?php if($row->dev_cm_pcs <0) {echo $red; }else{ echo ''; }?>">{{number_format($row->dev_cm_pcs,2)}}</td>
    </tr>
     
    <tr align="right">
        <td width="20px"align="center">6</td>

        <td width="120px" align="left">Capacity Achieved %</td>
        <td width="70px"  align="left"></td>
        <td width="70px"  align="left"></td>
        <td width="70px"  align="left"></td>
        <td width="70px"  align="left"></td>
        <td width="70px"></td>
        <td width="70px" style=" background-color:<?php if($row->cut_ach_qty_per <0) {echo $red; }else{ echo ''; }?>">{{number_format($row->cut_ach_qty_per,2)}} %</td>
        <td width="70px" style=" background-color:<?php if($row->screen_print_ach_qty_per <0) {echo $red; }else{ echo ''; }?>">{{number_format($row->screen_print_ach_qty_per,2)}} %</td>
        <td width="70px" style=" background-color:<?php if($row->embroidery_ach_qty_per <0) {echo $red; }else{ echo ''; }?>">{{number_format($row->embroidery_ach_qty_per,2)}} %</td>
        <td width="70px" style=" background-color:<?php if($row->ach_qty_per <0) {echo $red; }else{ echo ''; }?>">{{number_format($row->ach_qty_per,2)}} %</td>
        <td width="70px" style=" background-color:<?php if($row->fin_ach_qty_per <0) {echo $red; }else{ echo ''; }?>">{{number_format($row->fin_ach_qty_per,2)}} %</td>

        <td width="70px" style=" background-color:<?php if($row->exfactory_ach_qty_per <0) {echo $red; }else{ echo ''; }?>">{{number_format($row->exfactory_ach_qty_per,2)}} %</td>
        <td width="70px" style=" background-color:<?php if($row->ach_amount_per <0) {echo $red; }else{ echo ''; }?>">{{number_format($row->ach_amount_per,2)}} %</td>
        <td width="70px" style=" background-color:<?php if($row->fin_ach_amount_per <0) {echo $red; }else{ echo ''; }?>">{{number_format($row->fin_ach_amount_per,2)}} %</td>
        <td width="70px" style=" background-color:<?php if($row->exfactory_ach_amount_per <0) {echo $red; }else{ echo ''; }?>">{{number_format($row->exfactory_ach_amount_per,2)}} %</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px" style=" background-color:<?php if($row->bep_ach_qty_per <0) {echo $red; }else{ echo ''; }?>">{{number_format($row->bep_ach_qty_per,2)}} %</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
         <td width="70px"></td>
    </tr>
    <tr align="right">
        <td width="20px"align="center">7</td>
        <td width="120px" align="left">Sewing Effeciency %</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px" style=" background-color:<?php if($row->sew_eff_per <0) {echo $red; }else{ echo ''; }?>">{{number_format($row->sew_eff_per,2)}} %</td>
        
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
    </tr>
    <tr align="center">
        <td width="120px"align="center" colspan="25">-</td>
    </tr>
     <tr align="right">
        <td width="20px"align="center">8</td>
        <td width="120px" align="left">Tgt CM & Prod for Month </td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px">{{number_format($row->aoptgt_qty,0)}}</td>
        <td width="70px">{{number_format($row->cuttgt_plan_cut_qty,0)}}</td>
        <td width="70px">{{number_format($row->screenprinttgt_qty,0)}}</td>
        <td width="70px">{{number_format($row->embrotgt_qty,0)}}</td>
        <td width="70px">{{number_format($row->cuttgt_qty,0)}}</td>
        <td width="70px">{{number_format($row->cuttgt_qty,0)}}</td>
        <td width="70px">{{number_format($row->cuttgt_qty,0)}}</td>
        <td width="70px">{{number_format($row->cuttgt_amount,2)}}</td>
        <td width="70px">{{number_format($row->cuttgt_amount,2)}}</td>
        <td width="70px">{{number_format($row->cuttgt_amount,2)}}</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
    </tr>
    
    <tr align="right">
         <td width="20px"align="center">9</td>
        <td width="120px" align="left"><a href="javascript:void(0)" onClick="MsProdGmtCapacityAchievement.commonWindowForMonth({{$row->company_id}})">Month Achivement</a></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"><a href="javascript:void(0)" onClick="MsProdGmtCapacityAchievement.cuttingMonthWindow({{$row->company_id}})">{{number_format($row->cutasof_qty,0)}}</a></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"><a href="javascript:void(0)" onClick="MsProdGmtCapacityAchievement.sewingMonthWindow({{$row->company_id}})">{{number_format($row->sewasof_qty,0)}}</a></td>
        <td width="70px"><a href="javascript:void(0)" onClick="MsProdGmtCapacityAchievement.cartonMonthWindow({{$row->company_id}})">{{number_format($row->cartoonasof_qty,0)}}</a></td>
        <td width="70px"><a href="javascript:void(0)" onClick="MsProdGmtCapacityAchievement.exfactoryMonthWindow({{$row->company_id}})">{{number_format($row->exfactoryasof_qty,0)}}</a></td>
        <td width="70px">{{number_format($row->sewasof_amount,2)}}</td>
        <td width="70px">{{number_format($row->cartoonasof_amount,2)}}</td>
        <td width="70px">{{number_format($row->exfactoryasof_amount,2)}}</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
    </tr>
    <tr align="right">
        <td width="20px"align="center">10</td>
        <td width="120px" align="left">Month Variance </td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px">{{number_format($row->dev_cut_qty_month,0)}}</td>
        <td width="70px">{{number_format($row->dev_screen_print_qty_month,0)}}</td>
        <td width="70px">{{number_format($row->dev_embroidery_qty_month,0)}}</td>
        <td width="70px">{{number_format($row->dev_sew_qty_month,0)}}</td>
        <td width="70px">{{number_format($row->dev_fin_qty_month,0)}}</td>
        <td width="70px">{{number_format($row->dev_exfactory_qty_month,0)}}</td>
        <td width="70px">{{number_format($row->dev_sew_amount_month,0)}}</td>
        <td width="70px">{{number_format($row->dev_fin_amount_month,0)}}</td>
        <td width="70px">{{number_format($row->dev_exfactory_amount_month,0)}}</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
    </tr>
    <tr align="center">
        <td width="120px"align="center" colspan="25">-</td>
    </tr>
    <tr align="right">
        <td width="20px"align="center">11</td>
        <td width="120px" align="left">Month Comm. Invoice </td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"><a href="javascript:void(0)" onClick="MsProdGmtCapacityAchievement.invoiceMonthWindow({{$row->company_id}})">{{number_format($row->expinvoice_qty,0)}}</a></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
    </tr>
    <tr align="right">
        <td width="20px"align="center">12</td>
        <td width="120px" align="left">Yet to Make Comm. Invoice </td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"> {{number_format($row->yet_to_expinvoice_qty,0)}}</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
    </tr>
</table>
<br/>
<?php
$sewqtyperhead=0;
$sewamountperhead=0;
if($row->attendence){
   $sewqtyperhead=$row->sew_qty/ $row->attendence;
   $sewamountperhead=$row->sew_amount/ $row->attendence;
}
?>
 <strong>Sewing Qty/Head : {{ number_format( $sewqtyperhead ,2)}} Pcs &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sewing Amount/Head : {{ number_format( $sewamountperhead ,2)}} Taka</strong>
 <br/>
<?php 
    $k++; 
?>
@endforeach

<?php 
//=========Row-1


$ttl_qty=$rows->sum('ttl_qty'); 
$pjd_qty=$rows->sum('pjd_qty');
$iact_qty=$rows->sum('iact_qty');
$act_qty=$rows->sum('act_qty');

$qty=$rows->sum('qty');
$amount=$rows->sum('amount');
$cartoning_capacity_qty=$rows->sum('cartoning_capacity_qty');
$cartoning_capacity_amount=$rows->sum('cartoning_capacity_amount');

$bepinqty=$rows->sum('bepinqty');
$bepinsale=$rows->sum('bepinsale');

$pdc_qty=$rows->sum('pdc_qty');
$pdc_iact_qty=$rows->sum('pdc_iact_qty');
$sew_qty=$rows->sum('sew_qty');
$sew_amount=$rows->sum('sew_amount');
$sewasof_qty=$rows->sum('sewasof_qty');
$sewasof_amount=$rows->sum('sewasof_amount');

$cty=$rows->sum('cty');
$cmo=$rows->sum('cmo');
$bepincmproduced=$rows->sum('bepincmproduced');



$dev_sew_qty=$rows->sum('dev_sew_qty');
$dev_sew_amount=$rows->sum('dev_sew_amount');

$dev_fin_qty=$rows->sum('dev_fin_qty');
$dev_fin_amount=$rows->sum('dev_fin_amount');
$dev_bep_qty=$rows->sum('dev_bep_qty');
$dev_bep_amount=$rows->sum('dev_bep_amount');

$operator=$rows->sum('operator');
$helper=$rows->sum('helper');
$ach_qty_per=0;
if($qty){
   $ach_qty_per=($sew_qty/$qty)*100; 
}
$ach_amount_per=0;
if($amount){
$ach_amount_per=($sew_amount/$amount)*100;
}
$fin_ach_qty_per=0;
if($cartoning_capacity_qty){
   $fin_ach_qty_per=($cty/$cartoning_capacity_qty)*100; 
}
$fin_ach_amount_per=0;
if($cartoning_capacity_amount){
   $fin_ach_amount_per=($cmo/$cartoning_capacity_amount)*100; 
}

 $bep_ach_qty_per=0;
if($bepinqty){
    $bep_ach_qty_per=($cty/$bepinqty)*100;
}
$bep_ach_amount_per=0;
if($bepinsale){
   $bep_ach_amount_per=($cmo/$bepinsale)*100;
 
}

$emp_att_operator=$rows->sum('emp_att_operator');
$emp_att_helper=$rows->sum('emp_att_helper');

$emp_att_prod_staff=$rows->sum('emp_att_prod_staff');
$emp_att_supporting_staff=$rows->sum('emp_att_supporting_staff');
$manpower=$operator;
$attendence=$emp_att_operator+$emp_att_helper+$emp_att_prod_staff+$emp_att_supporting_staff;
$man_machine=0;
if($manpower){
   $man_machine= $attendence/$manpower;
}

$sew_smv=$rows->sum('sew_smv');
$used_smv=$rows->sum('used_smv');
$dev_sew_smv=$rows->sum('dev_sew_smv');
$sew_eff_per=0;
if($used_smv){
   $sew_eff_per=$sew_smv/$used_smv*100;//$rows->sum('sew_eff_per');
 
}
//$attendenceto=$rows->sum('attendence');
$cm_amount=$rows->sum('cm_amount');
$budgeted_produced_cm=$rows->sum('budgeted_produced_cm');

$dev_cm_amount=$rows->sum('dev_cm_amount');
$avg_sew_smv=0;
$avg_used_smv=0;
if($sew_qty){
  $avg_sew_smv=$sew_smv/$sew_qty;
  $avg_used_smv=$used_smv/$sew_qty;  
}
$dev_avg_sew_smv=$avg_sew_smv-$avg_used_smv;
$cpm_amount=$rows->sum('cpm_amount');
//$cm_pcs=$avg_sew_smv*$cpm_amount;
//$cm_pcs=$avg_sew_smv*$cpm_amount;
$cm_pcs=$avg_sew_smv*.045;
$cm_used_pcs=$avg_used_smv*.045;
$dev_cm_pcs=$cm_pcs-$cm_used_pcs;



?>
<br/>
@if($k>1)
<strong>Lithe Group</strong>
<table border="1" style="border-style:dotted">
    <tr align="center">
        <td width="20px"rowspan="2">#</td>
        <td width="120px">1</td>
        <td width="70px">2</td>
        <td width="70px">3</td>
        <td width="70px">4</td>
        <td width="70px">5</td>
        <td width="70px">6</td>
        <td width="70px">7</td>
        <td width="70px">8</td>
        <td width="70px">9</td>
        <td width="70px">10</td>
        <td width="70px">11</td>
        <td width="70px">12</td>
        <td width="70px">13</td>
        <td width="70px">14</td>
        <td width="70px">15</td>
        <td width="70px">16</td>
        <td width="70px">17</td>
        <td width="70px">18</td>
        <td width="70px">19</td>
        <td width="70px">20</td>
        <td width="70px">21</td>
        <td width="70px">22</td>
        <td width="70px">23</td>
        <td width="70px">24</td>
    </tr>
    <tr align="center">
        <td width="120px"align="left">Particulars</td>
        <td width="70px" >TTL</td>
        <td width="70px" >Line to be Set</td>
        <td width="70px" >In-active Line</td>
        <td width="70px">Active Line</td>
        <td width="70px">AOP in Kg</td>
        <td width="70px">Cutting Qty</td>
        <td width="70px">Screen Print Qty</td>
        <td width="70px">Embroidery Qty</td>
        <td width="70px">Sewing Qty</td>
        <td width="70px">Finshing Qty</td>
        <td width="70px">Ex-Factory Qty</td>
        <td width="70px">Sewing FOB Value</td>
        
        <td width="70px">Finshing FOB Value</td>
        <td width="70px">Exfactory FOB Value</td>
        <td width="70px">CM - Mkt</td>
        <td width="70px">CM - Mnfg</td>
        <td width="70px">BEP in Qty</td>
        <td width="70px">BEP: CM Earnings - Tk.</td>
        <td width="70px" title="Total SMV">Est. SMV</td>
        <td width="70px">Used SMV</td>
        <td width="70px">SMV Variance</td>
        <td width="70px">Avg SMV/Pcs</td>
        <td width="70px">CM Cost/Pcs</td>
    </tr>
    <tr align="right">
        <td width="20px"align="center">1</td>
        <td width="120px" align="left">Man Power</td>
        <td width="70px"  align="left">Opt: {{$operator}}<br/>Hep: {{$helper}}</td>
        <td width="70px"  align="left"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
    </tr>
    <tr align="right">
        <td width="20px"align="center">2</td>
        <td width="120px" align="left">Man Machine Ratio</td>
        <td width="70px"  align="left"></td>
        <td width="70px"  align="left"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"> </td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px">{{number_format($attendence)}}</td>
        <td width="70px"> 1:{{number_format($man_machine,2)}}</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
    </tr>
    <tr align="center">
        <td width="120px"align="center" colspan="25">-</td>
    </tr>
    
    <tr align="right">
        <td width="20px"align="center">3</td>
        <td width="120px" align="left">Capacity</td>
        <td width="70px">{{$ttl_qty}}</td>
        <td width="70px">{{$pjd_qty}}</td>
        <td width="70px">{{$iact_qty}}</td>
        <td width="70px">{{$act_qty}}</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px">{{number_format($qty,0)}}</td>
        <td width="70px">{{number_format($cartoning_capacity_qty,0)}}</td>
        <td width="70px"></td>
        <td width="70px">{{number_format($amount,2)}}</td>
        
        <td width="70px">{{number_format($cartoning_capacity_amount,0)}}</td>
        <td width="70px"></td>
        
        <td width="70px"></td>
        <td width="70px">{{number_format($bepincmproduced,2)}}</td>

        <td width="70px">{{number_format($bepinqty,0)}}</td>
        <td width="70px">{{number_format($bepinsale,0)}}</td>

        <td width="70px">{{number_format($sew_smv,2)}}</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px">{{number_format($avg_sew_smv,2)}}</td>
        <td width="70px">{{number_format($cm_pcs,2)}}</td>
    </tr>
    <tr align="right">
        <td width="20px"align="center">4</td>
        <td width="120px" align="left">Today Job Done</td>
        <td width="70px">{{$ttl_qty}}</td>
        <td width="70px">{{$pjd_qty}}</td>
        <td width="70px">{{$pdc_iact_qty}}</td>
        <td width="70px">{{$pdc_qty}}</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px">{{number_format($sew_qty,0)}}</td>
        <td width="70px">{{number_format($cty,0)}}</td>
        <td width="70px"></td>
        <td width="70px">{{number_format($sew_amount,2)}}</td>
        
        <td width="70px">{{number_format($cmo,2)}}</td>
        <td width="70px"></td>
        
        <td width="70px"></td>
        <td width="70px">{{number_format($budgeted_produced_cm,2)}}</td>
        <td width="70px">{{number_format($cty,0)}}</td>
        <td width="70px"></td>
        <td width="70px">{{number_format($used_smv,2)}}</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px">{{number_format($avg_used_smv,2)}}</td>
        <td width="70px">{{number_format($cm_used_pcs,2)}}</td>
    </tr>
    
     <tr align="right">
        <td width="20px"align="center">5</td>
        <td width="120px" align="left">Capacity Variance</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px" style=" background-color:<?php if($dev_sew_qty <0) {echo $red; }else{ echo ''; }?>">{{number_format($dev_sew_qty,0)}}</td>
        <td width="70px" style=" background-color:<?php if($dev_fin_qty <0) {echo $red; }else{ echo ''; }?>">{{number_format($dev_fin_qty,0)}}</td>
        <td width="70px"></td>
        <td width="70px" style=" background-color:<?php if($dev_sew_amount <0) {echo $red; }else{ echo ''; }?>">{{number_format($dev_sew_amount,0)}}</td>
        
        <td width="70px" style=" background-color:<?php if($dev_fin_amount <0) {echo $red; }else{ echo ''; }?>">{{number_format($dev_fin_amount,0)}}</td>
        <td width="70px"></td>
        
        <td width="70px"></td>
        <td width="70px" style=" background-color:<?php if($dev_cm_amount <0) {echo $red; }else{ echo ''; }?>">{{number_format($dev_cm_amount,2)}}</td>

        <td width="70px" style=" background-color:<?php if($dev_bep_qty <0) {echo $red; }else{ echo ''; }?>">{{number_format($dev_bep_qty,0)}}</td>
        <td width="70px" style=" background-color:<?php if($dev_bep_amount <0) {echo $red; }else{ echo ''; }?>"></td>
        <td width="70px" style=" background-color:<?php if($dev_sew_smv <0) {echo $red; }else{ echo ''; }?>">{{number_format($dev_sew_smv,2)}}</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px" style=" background-color:<?php if($dev_avg_sew_smv <0) {echo $red; }else{ echo ''; }?>">{{number_format($dev_avg_sew_smv,2)}}</td>
         <td width="70px">{{number_format($dev_cm_pcs,2)}}</td>
    </tr>
     
     <tr align="right">
        <td width="20px"align="center">6</td>
        <td width="120px" align="left">Capacity Achieved %</td>
        <td width="70px"  align="left"></td>
        <td width="70px"  align="left"></td>
        <td width="70px"  align="left"></td>
        <td width="70px"  align="left"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px" style=" background-color:<?php if($ach_qty_per <0) {echo $red; }else{ echo ''; }?>">{{number_format($ach_qty_per,2)}} %</td>
        <td width="70px" style=" background-color:<?php if($fin_ach_qty_per <0) {echo $red; }else{ echo ''; }?>">{{number_format($fin_ach_qty_per,2)}} %</td>
        <td width="70px"></td>
        <td width="70px" style=" background-color:<?php if($ach_amount_per <0) {echo $red; }else{ echo ''; }?>">{{number_format($ach_amount_per,2)}} %</td>
        
        <td width="70px" style=" background-color:<?php if($fin_ach_amount_per <0) {echo $red; }else{ echo ''; }?>">{{number_format($fin_ach_amount_per,2)}} %</td>
        <td width="70px"></td>
        
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px" style=" background-color:<?php if($bep_ach_qty_per <0) {echo $red; }else{ echo ''; }?>">{{number_format($bep_ach_qty_per,2)}} %</td>
        <td width="70px" style=" background-color:<?php if($bep_ach_amount_per <0) {echo $red; }else{ echo ''; }?>"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
    </tr>
    <tr align="right">
        <td width="20px"align="center">7</td>
        <td width="120px" align="left">Sewing Effeciency %</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
       
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px" style=" background-color:<?php if($sew_eff_per <0) {echo $red; }else{ echo ''; }?>">{{number_format($sew_eff_per,2)}}</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>

    </tr>
    <tr align="center">
        <td width="120px"align="center" colspan="25">-</td>
    </tr>
    <tr align="right">
        <td width="20px"align="center">8</td>
        <td width="120px" align="left">Tgt CM & Prod for Month </td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        
        <td width="70px"></td>
        <td width="70px"></td>
        
        <td width="70px"></td>
        <td width="70px"><a href="javascript:void(0)" onClick="MsProdGmtCapacityAchievement.cmWindow({{$row->company_id}})"></a></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
    </tr>
    
     
    <tr align="right">
        <td width="20px"align="center">9</td>
        <td width="120px" align="left">Month Job Done</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px">{{number_format($sewasof_qty,0)}}</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px">{{number_format($sewasof_amount,2)}}</td>
        
        <td width="70px"></td>
        <td width="70px"></td>
        
        <td width="70px"></td>
        <td width="70px"><a href="javascript:void(0)" onClick="MsProdGmtCapacityAchievement.cmWindow({{$row->company_id}})"></a></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
    </tr>
    <tr align="right">
        <td width="20px"align="center">10</td>
        <td width="120px" align="left">Month Variance </td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
    </tr>
    <tr align="center">
        <td width="120px"align="center" colspan="25">-</td>
    </tr>
    <tr align="right">
        <td width="20px"align="center">11</td>
        <td width="120px" align="left">Month Comm. Invoice </td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
    </tr>
    <tr align="right">
        <td width="20px"align="center">12</td>
        <td width="120px" align="left">Yet to Make Comm. Invoice </td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
    </tr>
</table>
<br/>
<?php
$sewqtyperheadtot=0;
$sewamountperheadtot=0;
if($attendence){
   $sewqtyperheadtot=$sew_qty / $attendence;
   $sewamountperheadtot=$sew_amount / $attendence;
}
?>
<strong>Sewing Qty/Head : {{ number_format( $sewqtyperheadtot,2)}} Pcs&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sewing Amount/Head : {{ number_format( $sewamountperheadtot,2)}} Taka </strong>
@endif


 