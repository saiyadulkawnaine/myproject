<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="205">
            
        </td>
        <td width="500" style="font-size:40px;">
        Buyer: {{ $data['master']->buyer_name }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Team Leader: {{ $data['master']->team_leader_name }}<br/>
        </td>
        <td width="205" align="left">
            
        </td>
        
    </tr>
    <tr>
        <td width="120">
            <br/>
            Sales Order:<br/>
            Sales Value:<br/>
            Costing Date:<br/>
            Currency:<br/>
        </td>
        <td width="140">
            <br/>
            {{ $data['master']->sales_order_no }}<br/>
            {{ $data['master']->order_val }}<br/>
            {{ $data['master']->costing_date }}<br/>
            {{ $data['master']->currency_name }}<br/>
        </td>
        
        <td width="650" align="left"><br/>
        Revenue Item:<br/>
        <?php
        $i=1;
        ?>
        @foreach($data['fabric'] as $row)
        {{$i}} .{{ $row->gmtspart}},{{ $row->fabrication}},{{ $row->fabriclooks}},{{ $row->fabricshape}},{{ $row->gsm_weight}}<br/>
        <?php
        $i++;
        ?>
        @endforeach
            <?php
        $i=1;
        ?>
        </td>
        
    </tr>
    <tr>
        <td width="910">
            <br/>
            Remarks: {{ $data['master']->master_remarks}}
        </td>
    </tr>
</table>
<br/>
<?php
    $i=1;
?>
<p>1.Revenue Item</p>
<table cellspacing="0" cellpadding="2" border="1">
    <thead>
        <tr>
        <td width="30" align="center">#</td>
        <td width="450" align="center">Fabric Description</td>
        <td width="100" align="center">Color Range</td>
        <td width="100" align="center">Qty</td>
        <td width="80" align="center">Price</td>
        <td width="100" align="center">Amount</td>
        <td width="86" align="left">Remarks</td>
        </tr>
    </thead>
    <tbody>
    <?php
    $j=1;
    $tot_fabric_qty=0;
    $tot_fabric_amount=0;
    ?>
    @foreach($data['fabric'] as $fabric)
     <tr>
        <td width="30" align="center">{{$j}}</td>
        <td width="450" align="left">{{ $fabric->gmtspart}},{{ $fabric->fabrication}},{{ $fabric->fabriclooks}},{{ $fabric->fabricshape}},{{ $fabric->gsm_weight}}</td>
        <td width="100" align="center">{{ $fabric->colorrange_id}}</td>
        <td width="100" align="right">{{ number_format($fabric->qty,2)}}</td>
        <td width="80" align="right">{{ number_format($fabric->rate,2)}}</td>
        <td width="100" align="right">{{ number_format($fabric->order_val,2)}}</td>
        <td width="86" align="left">{{ $fabric->remarks}}</td>
    </tr>
    <?php
    $tot_fabric_qty+=$fabric->qty;
    $tot_fabric_amount+=$fabric->order_val;
    $j++;
    ?>
    @endforeach
    <?php
    $tot_fabric_price=0;
    if($tot_fabric_qty){
        $tot_fabric_price=$tot_fabric_amount/$tot_fabric_qty;
    }
    ?>

    </tbody>
     <tr>
        <td width="580" align="right">Total</td>
        <td width="100" align="right">{{ number_format($tot_fabric_qty,2)}}</td>
        <td width="80" align="right">{{ number_format($tot_fabric_price,2)}}</td>
        <td width="100" align="right">{{ number_format($tot_fabric_amount,2)}}</td>
        <td width="86" align="left"></td>
    </tr>
</table>
<p></p>
<p>2. Dyes & Chemicals Cost</p>
<table cellspacing="0" cellpadding="2" border="1">
    <thead>
        <tr>
        <td width="30" align="center">#</td>
        <td width="450" align="center">Item Category</td>
        <td width="100" align="center">Qty</td>
        <td width="100" align="center">Avg. Cost/Kg</td>
        <td width="80" align="center">Amount</td>
        <td width="100" align="center">% On Order Value</td>
        <td width="86" align="left">Cons/Kg Fab</td>
        </tr>
    </thead>
    <tbody>
    <?php
    $j=1;
    $tot_dye_chem_qty=0;
    $tot_dye_chem_amount=0;
    ?>
    @foreach($data['dyechem'] as $dyechem)
        <?php
        $dye_chem_avg_cost_kg=$dyechem->amount/$dyechem->qty;
        $dye_chem_per_on_order_val=($dyechem->amount/$tot_fabric_amount)*100;
        $dye_chem_cons_per_kg_fab=$dyechem->amount/$tot_fabric_qty;

        ?>
     <tr>
        <td width="30" align="center">{{$j}}</td>
        <td width="450" align="left">{{ $dyechem->item_cat}}</td>
        <td width="100" align="right">{{ $dyechem->qty}}</td>
        <td width="100" align="right">{{ number_format($dye_chem_avg_cost_kg,2)}}</td>
        <td width="80" align="right">{{ number_format($dyechem->amount,2)}}</td>
        <td width="100" align="right">{{ number_format($dye_chem_per_on_order_val,2)}}</td>
        <td width="86" align="right">{{ number_format($dye_chem_cons_per_kg_fab,2)}}</td>
    </tr>
    <?php
    $tot_dye_chem_qty+=$dyechem->qty;
    $tot_dye_chem_amount+=$dyechem->amount;
    $j++;
    ?>
    @endforeach
    <?php
    $tot_dye_chem_avg_cost_kg=0;
    if($tot_dye_chem_qty){
        $tot_dye_chem_avg_cost_kg=$tot_dye_chem_amount/$tot_dye_chem_qty;
    }
    $tot_dye_chem_per_on_order_val=0;
    if($tot_fabric_amount){
        $tot_dye_chem_per_on_order_val=($tot_dye_chem_amount/$tot_fabric_amount)*100;
    }
    $tot_dye_chem_cons_per_kg_fab=0;
    if($tot_fabric_qty){
        $tot_dye_chem_cons_per_kg_fab=($tot_dye_chem_amount/$tot_fabric_qty);
    }
    ?>

    </tbody>
     <tr>
        <td width="480" align="right">Total</td>
        <td width="100" align="right">{{ number_format($tot_dye_chem_qty,2)}}</td>
        <td width="100" align="right">{{ number_format($tot_dye_chem_avg_cost_kg,2)}}</td>
        <td width="80" align="right">{{ number_format($tot_dye_chem_amount,2)}}</td>
        <td width="100" align="right">{{ number_format($tot_dye_chem_per_on_order_val,2)}}</td>
        <td width="86" align="right">{{ number_format($tot_dye_chem_cons_per_kg_fab,2)}}</td>
    </tr>
</table>

<p></p>
<p>3. Overheads Cost</p>
<table cellspacing="0" cellpadding="2" border="1">
    <thead>
        <tr>
        <td width="30" align="center">#</td>
        <td width="450" align="center">Account Head</td>
        <td width="100" align="center">Standard %</td>
        <td width="100" align="center"></td>
        <td width="80" align="center">Amount</td>
        <td width="100" align="center">% On Order Value</td>
        <td width="86" align="left">OH/Kg Fab</td>
        </tr>
    </thead>
    <tbody>
    <?php
    $j=1;
    $tot_head_amount=0;
    ?>
    @foreach($data['head'] as $head)
        <?php
        $head_per_on_order_val=($head->amount/$tot_fabric_amount)*100;
        $head_cons_per_kg_fab=$head->amount/$tot_fabric_qty;

        ?>
     <tr>
        <td width="30" align="center">{{$j}}</td>
        <td width="450" align="left">{{ $head->acc_head}}</td>
        <td width="100" align="right">{{ $head->cost_per}}</td>
        <td width="100" align="right"></td>
        <td width="80" align="right">{{ number_format($head->amount,2)}}</td>
        <td width="100" align="right">{{ number_format($head_per_on_order_val,2)}}</td>
        <td width="86" align="right">{{ number_format($head_cons_per_kg_fab,2)}}</td>
    </tr>
    <?php
    $tot_head_amount+=$head->amount;
    $j++;
    ?>
    @endforeach
    <?php
    $tot_head_per_on_order_val=0;
    if($tot_fabric_amount){
        $tot_head_per_on_order_val=($tot_head_amount/$tot_fabric_amount)*100;
    }
    $tot_head_cons_per_kg_fab=0;
    if($tot_fabric_qty){
        $tot_head_cons_per_kg_fab=$tot_head_amount/$tot_fabric_qty;
    }
    ?>

    </tbody>
     <tr>
        <td width="480" align="right">Total</td>
        <td width="100" align="right"></td>
        <td width="100" align="right"></td>
        <td width="80" align="right">{{ number_format($tot_head_amount,2)}}</td>
        <td width="100" align="right">{{ number_format($tot_head_per_on_order_val,2)}}</td>
        <td width="86" align="right">{{ number_format($tot_head_cons_per_kg_fab,2)}}</td>
    </tr>
    
</table>
<p></p>
<p></p>
<table cellspacing="0" cellpadding="2" border="0">
<tbody>
    <?php
    $tot_exp_amount=$tot_dye_chem_amount+$tot_head_amount;
    $tot_exp_on_per_order_val=$tot_dye_chem_per_on_order_val+$tot_head_per_on_order_val;;
    $tot_exp_cons_per_kg_fab=$tot_dye_chem_cons_per_kg_fab+$tot_head_cons_per_kg_fab;;
    ?>
     <tr style="font-weight: bold; font-size: 35px">
        <td width="480" align="right">Total Expenses</td>
        <td width="100" align="right"></td>
        <td width="100" align="right"></td>
        <td width="80" align="right">{{ number_format($tot_exp_amount,2)}}</td>
        <td width="100" align="right">{{ number_format($tot_exp_on_per_order_val,2)}}</td>
        <td width="86" align="right">{{ number_format($tot_exp_cons_per_kg_fab,2)}}</td>
    </tr>
    <?php
    $tot_pro_amount=$tot_fabric_amount-$tot_exp_amount;
    $tot_pro_on_per_order_val=($tot_pro_amount/$tot_fabric_amount)*100;
    $tot_pro_cons_per_kg_fab=$tot_pro_amount/$tot_fabric_qty;
    ?>
    <tr style="font-weight: bold; font-size: 35px">
        <td width="480" align="right">Net Profit / (Loss)</td>
        <td width="100" align="right"></td>
        <td width="100" align="right"></td>
        <td width="80" align="right">{{ number_format($tot_pro_amount,2)}}</td>
        <td width="100" align="right">{{ number_format($tot_pro_on_per_order_val,2)}}</td>
        <td width="86" align="right">{{ number_format($tot_pro_cons_per_kg_fab,2)}}</td>
    </tr>
</tbody>
</table>

<p></p>
<p>4.  Consumed Dyes</p>
<table cellspacing="0" cellpadding="2" border="1">
    <thead>
        <tr>
        <td width="30" align="center">#</td>
        <td width="450" align="center">Item Details</td>
        <td width="100" align="center">Qty</td>
        <td width="100" align="center">Rate/Kg</td>
        <td width="80" align="center">Amount</td>
        <td width="100" align="center">% On Order Value</td>
        <td width="86" align="left"></td>
        </tr>
    </thead>
    <tbody>
    <?php
    $j=1;
    $tot_dye_qty=0;
    $tot_dye_amount=0;
    ?>
    @foreach($data['dyes'] as $dye)
        <?php
        $dye_per_on_order_val=($dye->amount/$tot_fabric_amount)*100;

        ?>
     <tr>
        <td width="30" align="center">{{$j}}</td>
        <td width="450" align="left">{{ $dye->item_description}}{{ $dye->specification}}</td>
        <td width="100" align="right">{{ $dye->qty}}</td>
        <td width="100" align="right">{{ $dye->rate}}</td>
        <td width="80" align="right">{{ number_format($dye->amount,2)}}</td>
        <td width="100" align="right">{{ number_format($dye_per_on_order_val,2)}}</td>
        <td width="86" align="right"></td>
    </tr>
    <?php
    $tot_dye_qty+=$dye->qty;
    $tot_dye_amount+=$dye->amount;
    $j++;
    ?>
    @endforeach
    <?php
    
    $tot_dye_per_on_order_val=0;
    if($tot_fabric_amount){
        $tot_dye_per_on_order_val=($tot_dye_amount/$tot_fabric_amount)*100;
    }
    ?>

    </tbody>
     <tr>
        <td width="480" align="right">Total</td>
        <td width="100" align="right">{{ number_format($tot_dye_qty,2)}}</td>
        <td width="100" align="right"></td>
        <td width="80" align="right">{{ number_format($tot_dye_amount,2)}}</td>
        <td width="100" align="right">{{ number_format($tot_dye_per_on_order_val,2)}}</td>
        <td width="86" align="right"></td>
    </tr>
</table>

<p></p>
<p>5.  Consumed Chemicals </p>
<table cellspacing="0" cellpadding="2" border="1">
    <thead>
        <tr>
        <td width="30" align="center">#</td>
        <td width="450" align="center">Item Details</td>
        <td width="100" align="center">Qty</td>
        <td width="100" align="center">Rate/Kg</td>
        <td width="80" align="center">Amount</td>
        <td width="100" align="center">% On Order Value</td>
        <td width="86" align="left"></td>
        </tr>
    </thead>
    <tbody>
    <?php
    $j=1;
    $tot_chem_qty=0;
    $tot_chem_amount=0;
    ?>
    @foreach($data['chems'] as $chem)
        <?php
        $chem_per_on_order_val=($chem->amount/$tot_fabric_amount)*100;

        ?>
     <tr>
        <td width="30" align="center">{{$j}}</td>
        <td width="450" align="left">{{ $chem->item_description}}{{ $chem->specification}}</td>
        <td width="100" align="right">{{ $chem->qty}}</td>
        <td width="100" align="right">{{ $chem->rate}}</td>
        <td width="80" align="right">{{ number_format($chem->amount,2)}}</td>
        <td width="100" align="right">{{ number_format($chem_per_on_order_val,2)}}</td>
        <td width="86" align="right"></td>
    </tr>
    <?php
    $tot_chem_qty+=$chem->qty;
    $tot_chem_amount+=$chem->amount;
    $j++;
    ?>
    @endforeach
    <?php
    
    $tot_chem_per_on_order_val=0;
    if($tot_fabric_amount){
        $tot_chem_per_on_order_val=($tot_chem_amount/$tot_fabric_amount)*100;
    }
    ?>

    </tbody>
     <tr>
        <td width="480" align="right">Total</td>
        <td width="100" align="right">{{ number_format($tot_chem_qty,2)}}</td>
        <td width="100" align="right"></td>
        <td width="80" align="right">{{ number_format($tot_chem_amount,2)}}</td>
        <td width="100" align="right">{{ number_format($tot_chem_per_on_order_val,2)}}</td>
        <td width="86" align="right"></td>
    </tr>
</table>

<table cellspacing="0" cellpadding="2" border="0">
<tbody>
    <?php
    $tot_grd_qty=$tot_chem_qty+$tot_dye_qty;
    $tot_grd_amount=$tot_chem_amount+$tot_dye_amount;
    $tot_grd_per_order_val=$tot_chem_per_on_order_val+$tot_dye_per_on_order_val;
    ?>
     <tr style="font-weight: bold; font-size: 35px">
        <td width="480" align="right">Grand Total</td>
        <td width="100" align="right">{{ number_format($tot_grd_qty,2)}}</td>
        <td width="100" align="right"></td>
        <td width="80" align="right">{{ number_format($tot_grd_amount,2)}}</td>
        <td width="100" align="right">{{ number_format($tot_grd_per_order_val,2)}}</td>
        <td width="86" align="right"></td>
    </tr>
</tbody>
</table>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<table>
    
    <tr align="center">
        <td width="151">
            Prepared By
        </td>
        <td width="151">
          
        </td>
        <td width="151">
            Checked/ Audited By
        </td>
        <td width="151">
            Head Of Dept.
        </td>
        <td width="151">
          
        </td>
        <td width="151">
           Approved By
        </td>
        
    </tr>
    <tr align="center">
        <td width="151">&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;{{ $data['master']->user_name }},&nbsp;&nbsp;{{ $data['master']->contact }}<br/>
            &nbsp;&nbsp;&nbsp;{{ $data['master']->created_at }}
        </td>
        <td width="151">
        </td>
        <td width="151">
            
        </td>
        <td width="151">
        </td>
        <td width="151">
        </td>
        <td width="151">
            
        </td>
        
    </tr>
</table>