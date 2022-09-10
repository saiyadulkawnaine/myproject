<table cellspacing="0" cellpadding="2">
<tr>
<td width="107"></td>
<td width="107"></td>
<td width="106" >Buyer:</td>
<td width="212"><strong>{{ $budget['master']->buyer_name}}</strong></td>

<td width="106"></td>
</tr>
<tr>
<td width="107"></td>
<td width="107"></td>
<td width="106">Style Ref :</td>
<td width="212"><strong>{{ $budget['master']->style_ref}}</strong></td>

<td width="106"></td>
</tr>
<tr>
<td width="107"></td>
<td width="107"></td>
<td width="106">Beneficiary Company :</td>
<td width="212"><strong>{{ $budget['master']->full_company_name}}</strong></td>

<td width="106"></td>
</tr>
</table>
<table cellspacing="0" cellpadding="2" border="1">

<tr>
<td width="107">Buying House</td>
<td width="107">{{ $budget['master']->buying_agent_name }}</td>
<td width="106">Dealing Merchant</td>
<td width="106">{{ $budget['master']->dl_marchant}}</td>
<td width="106">ERP No</td>
<td width="106" align="right">{{ $budget['master']->id}}</td>
</tr>
<tr>
<td width="107">Team Leader</td>
<td width="107">{{ $budget['master']->tl_marchant}}</td>
<td width="106">Phone Number</td>
<td width="106">{{ $budget['master']->contactb }}</td>
<td width="106">Budget Unit</td>
<td width="106">{{ $budget['master']->costingunit }}</td>
</tr>
<tr>
<td width="107">Phone Number</td>
<td width="107" >{{ $budget['master']->contact }}</td>
<td width="106">Merchant's Email</td>
<td width="106" >{{ $budget['master']->email }}</td>
<td width="106">Budget Entry Date</td>
<td width="106">{{ $budget['master']->entry_date }}</td>

</tr>
<tr>
<td width="107">Selling Price/Unit</td>
<td width="107">{{ $budget['master']->selling_price }}</td>
<td width="106">Order UOM</td>
<td width="106">{{ $budget['master']->uom_code }}</td>
<td width="106">Entered By</td>
<td width="106">{{ $budget['master']->created_by_name }}</td>

</tr>
<tr>

<td width="107">Order Value</td>
<td width="107">{{ $budget['master']->order_amount }}</td>
</tr>
<tr>
    <td width="107">Remarks</td>
    <td width="531">{{ $budget['master']->remarks }}</td>
</tr>
</table>
<br/>
<caption>Order Details</caption>
<br/>
<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td width="50px">Job No</td>
        <td width="50px">Company</td>
        <td width="88px">Sales Order No</td>
        <td width="100px">Country</td>
        <td width="150px">GMT Item</td>
        <td width="64px">Ship date</td>
        <td width="68px">Order Qty</td>
        <td width="68px">Plan Cut Qty</td>
    </tr>
    </thead>
    <tbody>
    <?php 
    $gmtqty=0;
    $plan_cut_qty=0;
    ?>
    @foreach($budget['orderdetails'] as $row)
    <tr>
        <td width="50px">{{ $row->job_no }}</td>
        <td width="50px">{{ $row->company_name }}</td>
        <td width="88px">{{ $row->sale_order_no }}</td>
        <td width="100px">{{ $row->country_name }}</td>
        <td width="150px">{{ $row->item_description }}</td>
        <td width="64px">{{ $row->country_ship_date }}</td>
        <td width="68px" align="right">{{number_format($row->gmtqty,0)}}</td>
        <td width="68px" align="right">{{number_format($row->plan_cut_qty,0)}}</td>
        
    </tr>
    <?php 
    $gmtqty+= $row->gmtqty;
    $plan_cut_qty+= $row->plan_cut_qty;
    ?>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td width="502px" align="right">Total</td>
        
        <td width="68px" align="right">{{number_format($gmtqty,0)}}</td>
        <td width="68px" align="right">{{number_format($plan_cut_qty,0)}}</td>
    </tr>
    </tfoot>
</table>
<br/>
<caption>Body Fabric Details</caption>
<br/>
<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td width="70px">GMT Item</td>
        <td width="70px">Fab. Construction</td>
        <td width="80px">Composition</td>
        <td width="30px">GSM</td>
        <td width="30px">DIA</td>
        <td width="40px">Looks</td>
        <td width="60px">Fabric Color</td>
        <td width="40px">Knitting Shape</td>
        <td width="60px">Dyeing Type</td>
        <td width="64px">Finished Fab. Qty</td>
        <td width="64px">Grey Fab. Qty</td>
        <td width="30px">UOM</td>
    </tr>
    </thead>
    <tbody>
    <?php 
    $main_fin_fab=0;
    $main_grey_fab=0;
    ?>
    @foreach($budget['fabrics']['main'] as $shipdate => $value)
    <tr><td width="638px">{{ $shipdate }}</td></tr>
    <tr><td width="638px">{{ implode(',',$shipDatePo[$shipdate]) }}</td></tr>
    <?php 
    $main_fin_fab_sd=0;
    $main_grey_fab_sd=0;
    ?>
    @foreach($value as $row)
    @if($row->fin_fab)
    <tr>
        <td width="70px">{{ $row->item_description }}</td>
        <td width="70px"> {{ $row->constructions_name }}</td>
        <td width="80px">{{ $row->fabric_description }}</td>
        <td width="30px">{{ $row->gsm_weight }}</td>
        <td width="30px">{{  $row->dia  }}</td>
        <td width="40px">{{ $row->fabriclooks }}</td>
        <td width="60px">{{ $row->fabric_color }}</td>
        <td width="40px">{{ $row->fabricshape }}</td>
        <td width="60px">{{ $row->dyetype }}</td>
        <td width="64px" align="right">{{number_format($row->fin_fab,2)}}</td>
        <td width="64px" align="right">{{number_format($row->grey_fab,2)}}</td>
        <td width="30px">{{$row->uom_name}}</td>
    </tr>
    <?php 
    $main_fin_fab_sd+= $row->fin_fab;
    $main_grey_fab_sd+= $row->grey_fab;
    $main_fin_fab+= $row->fin_fab;
    $main_grey_fab+= $row->grey_fab;
    ?>
    @endif
    @endforeach
    <tr>
        <td width="480px" align="right">Sub Total </td>
        <td width="64px" align="right">{{number_format($main_fin_fab_sd,2)}}</td>
        <td width="64px" align="right">{{number_format($main_grey_fab_sd,2)}}</td>
        <td width="30px"></td>
    </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td width="480px" align="right">Total </td>
        <td width="64px" align="right">{{number_format($main_fin_fab,2)}}</td>
        <td width="64px" align="right">{{number_format($main_grey_fab,2)}}</td>
        <td width="30px"></td>
    </tr>
    </tfoot>
</table>

<br/>
@if($budget['fabrics']['narrow']->isNotEmpty())
<caption>Trims Fabric Details</caption>
<br/>
<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td width="60px">GMT Item</td>
        <td width="60px">Fab. Construction</td>
        <td width="60px">Composition</td>
        <td width="30px">GSM</td>
        <td width="30px">DIA</td>
        <td width="40px">Measurement</td>
        <td width="40px">Looks</td>
        <td width="40px">GMT Color</td>
        <td width="60px">Fabric Color</td>
        <td width="40px">Knitting Shape</td>
        <td width="50px">Dyeing Type</td>
        <td width="50px">Finished Fab. Qty</td>
        <td width="50px">Grey Fab. Qty</td>
        <td width="28px">UOM</td>
    </tr>
    </thead>
    <tbody>
    <?php 
    $narrow_fin_fab=0;
    $narrow_grey_fab=0;
    ?>
    @foreach($budget['fabrics']['narrow'] as $shipdate=>$value)
    <?php 
    $narrow_fin_fab_sd=0;
    $narrow_grey_fab_sd=0;
    ?>
    <tr><td width="638px">{{ $shipdate }}</td></tr>
    <tr><td width="638px">{{ implode(',',$shipDatePo[$shipdate]) }}</td></tr>
    @foreach($value as $row)
    @if($row->fin_fab)
    <tr>
        <td width="60px">{{ $row->item_description }}</td>
        <td width="60px"> {{ $row->constructions_name }}</td>
        <td width="60px">{{ $row->fabric_description }}</td>
        <td width="30px">{{ $row->gsm_weight }}</td>
        <td width="30px">{{  $row->dia  }}</td>
        <td width="40px">{{  $row->measurment  }}</td>
        <td width="40px">{{ $row->fabriclooks }}</td>
        <td width="40px">{{ $row->gmt_color }}</td>
        <td width="60px">{{ $row->fabric_color }}</td>
        <td width="40px">{{ $row->fabricshape }}</td>
        <td width="50px">{{ $row->dyetype }}</td>
        <td width="50px" align="right">{{number_format($row->fin_fab,2)}}</td>
        <td width="50px" align="right">{{number_format($row->grey_fab,2)}}</td>
        <td width="28px">{{$row->uom_name}}</td>
    </tr>
    <?php 
    $narrow_fin_fab_sd+= $row->fin_fab;
    $narrow_grey_fab_sd+= $row->grey_fab;
    $narrow_fin_fab+= $row->fin_fab;
    $narrow_grey_fab+= $row->grey_fab;
    ?>
    @endif
    @endforeach
    <tr>
        <td width="510" align="right">Sub Total </td>
        <td width="50px" align="right">{{number_format($narrow_fin_fab_sd,2)}}</td>
        <td width="50px" align="right">{{number_format($narrow_grey_fab_sd,2)}}</td>
        <td width="28px"></td>
    </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td width="510" align="right">Total</td>
        <td width="50px" align="right">{{number_format($narrow_fin_fab,2)}}</td>
        <td width="50px" align="right">{{number_format($narrow_grey_fab,2)}}</td>
        <td width="28px"></td>
    </tr>
    <tr>
        <td width="510" align="right">Fabric Total</td>
        <td width="50px" align="right">{{number_format($narrow_fin_fab+$main_fin_fab,2)}}</td>
        <td width="50px" align="right">{{number_format($narrow_grey_fab+$main_grey_fab,2)}}</td>
        <td width="28px"></td>
    </tr>
    </tfoot>
</table>
<br/>
@endif
<caption>Grey Yarn Details</caption>
<br/>
<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td width="162px">Count</td>
        <td width="164px">Composition</td>
        <td width="162px">Type</td>
        <td width="50px">BOM Qty</td>
        <td width="100px">Supplier</td>
    </tr>
    </thead>
    <tbody>
    <?php 
    $i=1;
    $tot=0;
    $totcons=0;
    ?>
    @foreach($budget['yarns'] as $row)
    <?php
    $cons= $row->cons;
    ?>
    <tr>
        <td width="162px"> {{  $row->count }}/{{ $row->symbol }}</td>
        <td width="164px"> {{  $row->composition }}</td>
        <td width="162px"> {{ $row->name  }}</td>
        <td width="50px" align="right">{{  number_format($cons,2) }}</td>
        <td width="100px" align="right">{{  $row->supplier_name }}</td>
    </tr>
    <?php 
    $totcons+= $cons;
    $tot+= $row->amount ;
    $i++;
    ?>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td width="488px" align="right">Total</td>
        <td width="50px" align="right">{{ number_format($totcons,2) }}</td>
        <td width="100px" align="right"></td>
    </tr>
    </tfoot>
</table>
<br/>
@if($budget['fabricProd']->isNotEmpty())
<caption>Fabric Production Details</caption>
<br/>
<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td width="100px">GMT Item</td>
        <td width="70px">Fab. Construction</td>
        <td width="90px">Composition</td>
        <td width="30px">GSM</td>
        <td width="60px">Fabric Color</td>
        <td width="60px">Process</td>
        <td width="100px">Dyeing Type</td>
        <td width="88px">Fab. Qty</td>
       <td width="40px">UOM</td>
    </tr>
    </thead>
    <tbody>
    <?php 
    $aop_fab=0;
    ?>
    @foreach($budget['fabricProd'] as $processArr)
    <?php
    $subaop_fab=0;
    ?>
    @foreach($processArr as $row)
    <tr>
        <td width="100px">{{ $row->item_description }}</td>
        <td width="70px"> {{ $row->constructions_name }}</td>
        <td width="90px">{{ $row->fabric_description }}</td>
        <td width="30px">{{ $row->gsm_weight }}</td>
        <td width="60px">{{ $row->fabric_color }}</td>
        <td width="60px">{{ $row->process_name }}</td>
        <td width="100px" align="center">@if($row->production_process_id==4){{ $row->dyeing_type }}@endif </td>
        <td width="88px" align="right">{{number_format($row->fin_fab,2)}}</td>
        <td width="40px">{{$row->uom_name}}</td>
    </tr>
    <?php 
    $subaop_fab+= $row->fin_fab;
    $aop_fab+= $row->fin_fab;
    ?>
    @endforeach
    <tr>
        <td width="510px" align="right">Sub Total</td>
        

        <td width="88px" align="right">{{number_format($subaop_fab,2)}}</td>
        <td width="40px"></td>
    </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td width="510px" align="right">Total</td>
        

        <td width="88px" align="right">{{number_format($aop_fab,2)}}</td>
        <td width="40px"></td>
    </tr>
    </tfoot>
</table>
@endif
<br/>
@if($budget['aop']->isNotEmpty())
<caption>AOP Details</caption>
<br/>
<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td width="100px">GMT Item</td>
        <td width="70px">Fab. Construction</td>
        <td width="90px">Composition</td>
        <td width="30px">GSM</td>
        <td width="60px">Fabric Color</td>
        <td width="60px">Aop Type</td>
        <td width="50px">Coverage %</td>
        <td width="50px">No of Color</td>
        <td width="88px">Fab. Qty</td>
       <td width="40px">UOM</td>
    </tr>
    </thead>
    <tbody>
    <?php 
    $aop_fab=0;
    ?>
    @foreach($budget['aop'] as $row)
    <tr>
        <td width="100px">{{ $row->item_description }}</td>
        <td width="70px"> {{ $row->constructions_name }}</td>
        <td width="90px">{{ $row->fabric_description }}</td>
        <td width="30px">{{ $row->gsm_weight }}</td>
        <td width="60px">{{ $row->fabric_color }}</td>
        <td width="60px">{{ $row->aoptype }}</td>
        <td width="50px" align="center">{{ $row->coverage }} %</td>
        <td width="50px" align="center">{{ $row->impression }}</td>
        <td width="88px" align="right">{{number_format($row->fin_fab,2)}}</td>
        <td width="40px">{{$row->uom_name}}</td>
    </tr>
    <?php 
    $aop_fab+= $row->fin_fab;
    ?>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td width="510px" align="right">Total</td>
        

        <td width="88px" align="right">{{number_format($aop_fab,2)}}</td>
        <td width="40px"></td>
    </tr>
    </tfoot>
</table>
@endif
<br/>
@if($budget['burnout']->isNotEmpty())
<caption>Burn Out Details</caption>
<br/>
<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td width="160px">GMT Item</td>
        <td width="70px">Fab. Construction</td>
        <td width="90px">Composition</td>
        <td width="30px">GSM</td>
        <td width="160px">Fabric Color</td>
        
        <td width="88px">Fab. Qty</td>
       <td width="40px">UOM</td>
    </tr>
    </thead>
    <tbody>
    <?php 
    $burnout_fin_fab=0;
    ?>
    @foreach($budget['burnout'] as $row)
    <tr>
        <td width="160px">{{ $row->item_description }}</td>
        <td width="70px"> {{ $row->constructions_name }}</td>
        <td width="90px">{{ $row->fabric_description }}</td>
        <td width="30px">{{ $row->gsm_weight }}</td>
        <td width="160px">{{ $row->fabric_color }}</td>
        <td width="88px" align="right">{{number_format($row->fin_fab,2)}}</td>
        <td width="40px">{{$row->uom_name}}</td>
    </tr>
    <?php 
    $burnout_fin_fab+= $row->fin_fab;
    ?>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td width="510px" align="right">Total</td>
        <td width="88px" align="right">{{number_format($burnout_fin_fab,2)}}</td>
        <td width="40px"></td>
    </tr>
    </tfoot>
</table>
@endif
<br/>

@if($budget['yarndyeing']->isNotEmpty())
<caption>Yarn Dyeing Cost</caption>
<br/>
<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td width="240px">Fabric Description</td>
        <td width="88px">Process</td>
        <td width="50px">BOM Qty.</td>
        <td width="40px">Rate</td>
        <td width="60px">Amount</td>
        <td width="40px">Overhead Rate</td>
        <td width="60px">Overhead Amount</td>
        <td width="60px">Total Amount</td>
    </tr>
    </thead>
    <tbody>
    <?php 
    $i=1;
    $gtot=0;
    $gohtot=0;
    $gttot=0;
    ?>
    @foreach($budget['yarndyeing'] as $processArr)
    <?php 
    $tot=0;
    $ohtot=0;
    $ttot=0;
    ?>
    @foreach($processArr as $row)
    <?php
    $cons=$row->cons;
    ?>
    <tr>
        <td width="240px"> {{ $row->budgetfabric }}</td>
        <td width="88px">{{ $row->process_id }}</td>
        <td width="50px" align="right">{{   number_format($cons,2) }}</td>
        <td width="40px" align="right">{{ $row->rate }}</td>
        <td width="60px" align="right">{{ number_format($row->amount,2) }}</td>
        <td width="40px" align="right">{{ $row->overhead_rate }}</td>
        <td width="60px" align="right">{{ number_format($row->overhead_amount,2) }}</td>
        <td width="60px" align="right">{{ number_format($row->total_amount,2) }}</td>
    </tr>
    <?php 
    $tot+= $row->amount;
    $ohtot+= $row->overhead_amount;
    $ttot+= $row->total_amount;
    $gtot+= $row->amount;
    $gohtot+= $row->overhead_amount;
    $gttot+= $row->total_amount;
    $i++;
    ?>
    @endforeach
    <tr>
        <td width="418px" align="right">Sub Total</td>
        <td width="60px" align="right">{{ number_format($tot,2)}}</td>
        <td width="40px" align="right"></td>
        <td width="60px" align="right">{{ number_format($ohtot,2)}}</td>
        <td width="60px" align="right">{{ number_format($ttot,2)}}</td>
    </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td width="418px" align="right">Grand Total</td>
        <td width="60px" align="right">{{ number_format($gtot,2)}}</td>
        <td width="40px" align="right"></td>
        <td width="60px" align="right">{{ number_format($gohtot,2)}}</td>
        <td width="60px" align="right">{{ number_format($gttot,2)}}</td>
    </tr>
    </tfoot>
</table>
@endif
<br/>

<br/>
<caption>Cutting Details</caption>
<br/>
<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td width="70px">GMT Item</td>
        <td width="70px">Fab. Construction</td>
        <td width="80px">Composition</td>
        <td width="30px">GSM</td>
        <td width="30px">DIA</td>
        <td width="40px">Looks</td>
        <td width="60px">Garment Color</td>
        <td width="40px">Garment Size</td>
        <td width="60px">Plan Cut Qty</td>
        <td width="64px">CAD Cons/Dzn</td>
        <td width="64px">Finished Fab. Qty</td>
        <td width="30px">UOM</td>
    </tr>
    </thead>
    <tbody>
    <?php 
    $main_fin_fab=0;
    $main_grey_fab=0;
    $plan_cut_qty=0;
    ?>
    @foreach($budget['cuttings'] as $row)
    @if($row->fin_fab)
    <tr>
        <td width="70px">{{ $row->item_description }}</td>
        <td width="70px"> {{ $row->constructions_name }}</td>
        <td width="80px">{{ $row->fabric_description }}</td>
        <td width="30px">{{ $row->gsm_weight }}</td>
        <td width="30px">{{  $row->dia  }}</td>
        <td width="40px">{{ $row->fabriclooks }}</td>
        <td width="60px">{{ $row->gmt_color }}</td>
        <td width="40px">{{ $row->gmt_size }}</td>
        <td width="60px" align="right">{{ $row->plan_cut_qty }}</td>
        <td width="64px" align="right">{{number_format($row->cons,2)}}</td>
        <td width="64px" align="right">{{number_format($row->fin_fab,2)}}</td>
        <td width="30px">{{$row->uom_name}}</td>
    </tr>
    <?php 
    $main_fin_fab+= $row->fin_fab;
    $main_grey_fab+= $row->cons;
    $plan_cut_qty+= $row->plan_cut_qty;
    ?>
    @endif
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td width="420px" align="right">Total</td>
        <td width="60px" align="right">{{number_format($plan_cut_qty,0)}}</td>
        <td width="64px" align="right"></td>
        <td width="64px" align="right">{{number_format($main_fin_fab,2)}}</td>
        <td width="30px"></td>
    </tr>
    </tfoot>
</table>
<br/>
@if($budget['screenprint']->isNotEmpty())
<caption>Screen Printing Details</caption>
<br/>
<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td width="140px">Gmt. Item</td>
        <td width="148px">Printing Type</td>
        <td width="100px">Print Size</td>
        <td width="100px">Garment Place</td>
        <td width="90px" align="right">Qty</td>
        <td width="60px">UOM</td>
    </tr>
    </thead>
    <tbody>
    <?php 
    $i=1;
    $tot=0;
    ?>
    @foreach($budget['screenprint'] as $row)
    <?php
    $cons= $row->cons;
    ?>
    <tr>
        <td width="140px"> {{ $row->item_description }}</td>
        <td width="148px">{{  $row->embelishment_type }}</td>
        <td width="100px">{{  $row->embelishment_size }}</td>
        <td width="100px">{{  $row->gmt_parts_name }}</td>
        <td width="90px" align="right">{{ number_format($cons,0) }}</td>
        <td width="60px">Pcs</td>
       
    </tr>
    <?php 
    $tot+=$cons;
    $i++;
    ?>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td width="388px"></td>
        <td width="100px">Total</td>
        <td width="90px" align="right">{{ number_format($tot,0)}}</td>
        <td width="60px" align="right"></td>
    </tr>
    </tfoot>
</table>

@endif
@if($budget['embroidary']->isNotEmpty())
<br/>
<caption>Embroidery Details</caption>
<br/>
<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td width="388px">Gmt. Item</td>
        
        <td width="100px">Garment Place</td>
        <td width="90px" align="right">Qty</td>
        <td width="60px">UOM</td>
    </tr>
    </thead>
    <tbody>
    <?php 
    $i=1;
    $tot=0;
    ?>
    @foreach($budget['embroidary'] as $row)
    <?php
    $cons= $row->cons;
    ?>
    <tr>
        <td width="388px"> {{ $row->item_description }}</td>
        <td width="100px">{{  $row->gmt_parts_name }}</td>
        <td width="90px" align="right">{{ number_format($cons,0) }}</td>
        <td width="60px">Pcs</td>
    </tr>
    <?php 
    $tot+=$cons;
    $i++;
    ?>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td width="388px"></td>
        <td width="100px">Total</td>
        <td width="90px" align="right">{{ number_format($tot,0)}}</td>
        <td width="60px" align="right"></td>
    </tr>
    </tfoot>
</table>
@endif
<br/>
@if($budget['sewingtrims']->isNotEmpty())
<caption>Sewing Accessories Details</caption>
<br/>
<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td width="100">Gmt. Item</td>
        <td  width="60">Garment Qty</td>
        <td  width="100">Accessories</td>
        <td  width="208">Description</td>
        <td  width="70">Req. Per Dzn</td>
        <td  width="60" align="right">BOM Qty.</td>
        <td  width="40">UOM</td>
        
    </tr>
    </thead>
    <tbody>
    <?php 
    $i=1;
    ?>
    @foreach($budget['sewingtrims'] as $row)
    
    <tr>
        <td width="100">{{ $row->item_description }}</td>
        <td width="60px" align="right">{{ $row->gmtqty }}</td>
        <td width="100px"> {{ $row->name }}</td>
        <td width="208px">{{ $row->description }}</td>
        <td width="70px" align="right">{{ $row->cons }}</td>
        <td width="60px" align="right">{{ number_format($row->bom_trim ,4) }}</td>
        <td width="40px">{{ $row->code }}</td>
        
    </tr>
    <?php 
    $i++;
    ?>
    @endforeach
    </tbody>
    <tfoot>
    
    </tfoot>
</table>
@endif
<br/>
@if($budget['finishingtrims']->isNotEmpty())
<caption>Finishing Accessories Details</caption>
<br/>
<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td width="100">Gmt. Item</td>
        <td  width="60">Garment Qty</td>
        <td  width="100">Accessories</td>
        <td  width="208">Description</td>
        <td  width="70">Req. Per Dzn</td>
        <td  width="60" align="right">BOM Qty.</td>
        <td  width="40">UOM</td>
        
    </tr>
    </thead>
    <tbody>
    <?php 
    $i=1;
    ?>
    @foreach($budget['finishingtrims'] as $row)
    
    <tr>
        <td width="100">{{ $row->item_description }}</td>
        <td width="60px" align="right">{{ $row->gmtqty }}</td>
        <td width="100px"> {{ $row->name }}</td>
        <td width="208px">{{ $row->description }}</td>
        <td width="70px" align="right">{{ $row->cons }}</td>
        <td width="60px" align="right">{{ number_format($row->bom_trim ,4) }}</td>
        <td width="40px">{{ $row->code }}</td>
        
    </tr>
    <?php 
    $i++;
    ?>
    @endforeach
    </tbody>
    <tfoot>
    
    </tfoot>
</table>
@endif
<br/>
@if($budget['spembroidary']->isNotEmpty())
<caption>Special Embroidery Details</caption>
<br/>
<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td width="388px">Gmt. Item</td>
        
        <td width="100px">Garment Place</td>
        <td width="90px" align="right">Qty</td>
        <td width="60px">UOM</td>
    </tr>
    </thead>
    <tbody>
    <?php 
    $i=1;
    $tot=0;
    ?>
    @foreach($budget['spembroidary'] as $row)
    <?php
    $cons= $row->cons;
    ?>
    <tr>
        <td width="388px"> {{ $row->item_description }}</td>
        <td width="100px">{{  $row->gmt_parts_name }}</td>
        <td width="90px" align="right">{{ number_format($cons,0) }}</td>
        <td width="60px">Pcs</td>
    </tr>
    <?php 
    $tot+=$cons;
    $i++;
    ?>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td width="388px"></td>
        <td width="100px">Total</td>
        <td width="90px" align="right">{{ number_format($tot,0)}}</td>
        <td width="60px" align="right"></td>
    </tr>
    </tfoot>
</table>
@endif

<br/>
@if($budget['gmtdyeing']->isNotEmpty())
<caption>Garment Dyeing Details</caption>
<br/>
<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td width="388px">Gmt. Item</td>
        
        <td width="100px">Garment Place</td>
        <td width="90px" align="right">Qty</td>
        <td width="60px">UOM</td>
    </tr>
    </thead>
    <tbody>
    <?php 
    $i=1;
    $tot=0;
    ?>
    @foreach($budget['gmtdyeing'] as $row)
    <?php
    $cons= $row->cons;
    ?>
    <tr>
        <td width="388px"> {{ $row->item_description }}</td>
        <td width="100px">{{  $row->gmt_parts_name }}</td>
        <td width="90px" align="right">{{ number_format($cons,0) }}</td>
        <td width="60px">Pcs</td>
    </tr>
    <?php 
    $tot+=$cons;
    $i++;
    ?>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td width="388px"></td>
        <td width="100px">Total</td>
        <td width="90px" align="right">{{ number_format($tot,0)}}</td>
        <td width="60px" align="right"></td>
    </tr>
    </tfoot>
</table>

@endif
@if($budget['gmtwashing']->isNotEmpty())
<br/>
<caption>Garment Washing Detail</caption>
<br/>
<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td width="388px">Gmt. Item</td>
        
        <td width="100px">Garment Place</td>
        <td width="90px" align="right">Qty</td>
        <td width="60px">UOM</td>
    </tr>
    </thead>
    <tbody>
    <?php 
    $i=1;
    $tot=0;
    ?>
    @foreach($budget['gmtwashing'] as $row)
    <?php
    $cons= $row->cons;
    ?>
    <tr>
        <td width="388px"> {{ $row->item_description }}</td>
        <td width="100px">{{  $row->gmt_parts_name }}</td>
        <td width="90px" align="right">{{ number_format($cons,0) }}</td>
        <td width="60px">Pcs</td>
    </tr>
    <?php 
    $tot+=$cons;
    $i++;
    ?>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td width="388px"></td>
        <td width="100px">Total</td>
        <td width="90px" align="right">{{ number_format($tot,0)}}</td>
        <td width="60px" align="right"></td>
    </tr>
    </tfoot>
</table>
@endif
<br/>
<caption>Sewing Details</caption>
<br/>
<table cellspacing="0" cellpadding="2" border="1">
    <thead>
        <tr align="center">
            <td width="170px">GMT Item</td>
            <td width="100px">Garment Color</td>
            <td width="100px">Garment Size</td>
            <td width="60px">Plan Cut Qty</td>
            <td width="64px">Order Qty</td>
            <td width="64px">Fabric Looks</td>
            <td width="40px">SMV</td>
            <td width="40px">Efficiency %</td>
        </tr>
    </thead>
    <tbody>
    <?php 
    $gmtqty=0;
    $plan_cut_qty=0;
    ?>
    @foreach($budget['sewings'] as $row)
    <tr>
        <td width="170px">{{ $row->item_description }}</td>
        <td width="100px">{{ $row->gmt_color }}</td>
        <td width="100px">{{ $row->gmt_size }}</td>
        <td width="60px" align="right">{{ number_format($row->plan_cut_qty,0) }}</td>
        <td width="64px" align="right">{{number_format($row->gmtqty,0)}}</td>
        <td width="64px"></td>
        <td width="40px" align="right">{{$row->smv}}</td>
        <td width="40px" align="right">{{$row->sewing_effi_per}}</td>
    </tr>
    <?php 
    $gmtqty+= $row->gmtqty;
    $plan_cut_qty+= $row->plan_cut_qty;
    ?>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td width="370px" align="right">Total</td>    
        <td width="60px" align="right">{{number_format($plan_cut_qty,0)}}</td>
        <td width="64px" align="right">{{number_format($gmtqty,0)}}</td>
        <td width="64px" align="right"></td>
        <td width="40px"></td>
        <td width="40px"></td>
    </tr>
    </tfoot>
</table>
@if($budget['cartondetails']->isNotEmpty())
<br/>
<caption>Carton Details</caption>
<br/>
<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td width="170px">Carton Details</td>  
        <td width="100px">Assortment</td>
        <td width="60px">Packing Type</td>
        <td width="64px">Assortment Name</td>
        <td width="100px">GMT Item</td>
        <td width="64px">Garment Color</td>
        <td width="40px">Garment Size</td>
        <td width="40px">Ratio Per Carton</td>
    </tr>
    </thead>
    <tbody>
    <?php 
    $tgmtqty=0;
    ?>
    @foreach($budget['cartondetails'] as $rows)
    <?php 
    $gmtqty=0;
    $r=1;
    ?>
    @foreach($rows as $row)
    @if($r==1)
    <tr>
        <td width="170px" >{{ $row->spec }}</td>
        <td width="100px">{{ $row->assortment }}</td>
        <td width="60px" align="right">{{ $row->packing_type }}</td>
        <td width="64px" align="right">{{ $row->assortment_name}}</td>
        <td width="100px">{{$row->item_description }}</td>
        <td width="64px">{{$row->color_name}}</td>
        <td width="40px" align="right">{{$row->size_name}}</td>
        <td width="40px" align="right">{{$row->qty}}</td>
    </tr>
    @else
    <tr>
        <td width="170px" ></td>
        <td width="100px"></td>
        <td width="60px" align="right"></td>
        <td width="64px" align="right"></td>
        <td width="100px">{{$row->item_description }}</td>
        <td width="64px">{{$row->color_name}}</td>
        <td width="40px" align="right">{{$row->size_name}}</td>
        <td width="40px" align="right">{{$row->qty}}</td>
    </tr>
    @endif
   
    <?php 
    $r++;
    $gmtqty+= $row->qty;
    $tgmtqty+= $row->qty;
    ?>
    @endforeach
    <tr align="center">
        <td width="598px" align="right">Pcs Per Carton</td>
        <td width="40px" align="right">{{ number_format($gmtqty,0) }}</td>
    </tr>
    @endforeach
    </tbody>
    <tfoot>
    
    </tfoot>
</table>
@endif

<br/>
<br/>
<br/>
<br/>
<table cellspacing="0" cellpadding="2" border="0">
    <tr align="center">
        <td width="159">Dealing Merchant</td>
        <td width="159">Marketng Team Leader</td>
        <td width="160">Marketing Director</td>
        <td width="160">Managing Director</td>
    </tr>
</table>



