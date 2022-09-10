<table cellspacing="0" cellpadding="2">
<tr>
    <td width="638" align="center" >
        <strong>Program No : {{ $plKnit['master']->pl_no }}</strong>
    </td>
</tr>
<tr>
<td width="400">
To,<br/>
{{ $plKnit['master']->supplier_name }}<br/>
{{ $plKnit['master']->supplier_address }}
</td>
<td width="158">
    <br/>
    Machine No: {{ $plKnit['master']->custom_no }}<br/>
    Machine Dia: {{ $plKnit['master']->dia_width }}<br/>
    Machine Brand:&nbsp;{{ $plKnit['master']->brand }}<br/>
    Program Date:&nbsp;{{ $plKnit['master']->pl_date }}<br/>
</td>
<td width="80">
    <br/>
     
</td>
</tr>
<tr>
	<td width="400">
		Buyer:&nbsp;{{ $plKnit['master']->buyer_name }}
	</td>
	<td width="158">
		Style:&nbsp;{{ $plKnit['master']->style_ref }}
	</td>
</tr>
<tr>
	<td width="400">
		Start Date:&nbsp;&nbsp;{{ $plKnit['master']->pl_start_date }}
	</td>
	<td width="158">
		End Date:&nbsp;&nbsp;{{ $plKnit['master']->pl_end_date }}
	</td>
</tr>
<tr>
	<td width="400">
		Fabrication:&nbsp;{{ $plKnit['master']->fabrication }}, {{ $plKnit['master']->fabriclooks }}
	</td>
	<td width="158">
		Capacity:&nbsp;{{ $plKnit['master']->capacity }}
	</td>
</tr>
<tr>
	<td width="400">
		Program Qty:&nbsp;{{ $plKnit['master']->qty }}&nbsp;Kg
	</td>
	<td width="158">
		Sales Order No:&nbsp;{{ $plKnit['master']->sale_order_no }}
	</td>
</tr>

</table>
<br/>
<table cellspacing="0" cellpadding="2" border="1">
    <thead>
        <tr>
            <td width="70" align="center">Plan Dia</td>
            <td width="70" align="center">Plan Gsm</td>
            <td width="70" align="center">Stitch Length</td>
            <td width="110" align="center">Spandex Stitch Length</td>
            <td width="70" align="center">Draft Ratio</td>
            <td width="70" align="center">No of feeder</td>
            <td width="68" align="center">M/C Gauge</td>
            <td width="110" align="center">Color Range</td>
        </tr>
    </thead>
    <tbody>
    	<tr>
            <td width="70" align="center">{{ $plKnit['master']->dia }},{{ $plKnit['master']->fabricshape }}</td>
            <td width="70" align="center">{{ $plKnit['master']->gsm_weight }}</td>
            <td width="70" align="center">{{ $plKnit['master']->stitch_length }}</td>
            <td width="110" align="center">{{ $plKnit['master']->spandex_stitch_length }}</td>
            <td width="70" align="center">{{ $plKnit['master']->draft_ratio }}</td>
            <td width="70" align="center">{{ $plKnit['master']->no_of_feeder }}</td>
            <td width="68" align="center">{{ $plKnit['master']->machine_gg }}</td>
            <td width="110" align="center">{{ $plKnit['master']->colorrange_name }}</td>
        </tr>
    </tbody>
    </table>

    <br/>
    
<table cellspacing="0" cellpadding="2" border="1">
    <thead>
    	<tr>
            <td width="638" align="left">Stripe Details</td>
            
        </tr>
        <tr>
            <td width="38" align="center">SL</td>
            <td width="150" align="center">GMT Color</td>
            <td width="150" align="center">Stripe Color</td>
            <td width="150" align="center">Measurement</td>
            <td width="150" align="center">No of feeder</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        $i=1;
        ?>
    	@foreach($plKnit['plknititemstripe'] as $stripe)
    	
    	<tr>
            <td width="38" align="center">{{$i}}</td>
            <td width="150" align="center">{{ $stripe->gmt_color_id }}</td>
            <td width="150" align="center">{{ $stripe->stripe_color_id }}</td>
            <td width="150" align="center">{{ $stripe->measurment }}</td>
            <td width="150" align="center">{{ $stripe->no_of_feeder }}</td>
        </tr>
        <?php 
        $i++;
        ?>
        @endforeach
    </tbody>
    </table>

    <br/>
    
<table cellspacing="0" cellpadding="2" border="1">
    <thead>
    	<tr>
            <td width="638" align="left">Narrow Fabric Details</td>
            
        </tr>
        <tr>
            <td width="38" align="center">SL</td>
            <td width="100" align="center">GMT Order No</td>
            <td width="80" align="center">Knitting Sales Order No</td>
            <td width="100" align="center">Gmt Item</td>
            <td width="80" align="center">Gmt Size</td>
            <td width="80" align="center">Measurement</td>
            <td width="80" align="center">Capacity (Pcs)</td>
            <td width="80" align="center">Plan Qty (Pcs)</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        $i=1;
        ?>
    	@foreach($plKnit['narrowfabric'] as $narrowfabric)
    	
    	<tr>
           <td width="38" align="center">{{$i}}</td>
            <td width="100" align="center">{{$narrowfabric->gmt_sale_order_no}}</td>
            <td width="80" align="center"></td>
            <td width="100" align="center"></td>
            <td width="80" align="center">{{$narrowfabric->size_id}}</td>
            <td width="80" align="center">{{$narrowfabric->measurment}}</td>
            <td width="80" align="center">{{$narrowfabric->capacity}}</td>
            <td width="80" align="center">{{$narrowfabric->qty}}</td>
        </tr>
        <?php 
        $i++;
        ?>
        @endforeach
    </tbody>
    </table>
    <br/>


    <table cellspacing="0" cellpadding="2" border="1">
    <thead>
        <tr>
            <td width="638" align="left">Yarn Details</td>
            
        </tr>
        <tr>
            <td width="38" align="center">SL</td>
            <td width="60" align="center">Count</td>
            <td width="80" align="center">Composition</td>
            <td width="80" align="center">Lot</td>
            <td width="60" align="center">Brand</td>
            <td width="60" align="center">Color</td>
            <td width="80" align="center">Supplier</td>
            <td width="80" align="center">Qty</td>
            <td width="40" align="center">UOM</td>
            <td width="60" align="center">Rq. No</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        $tQty=0;
        $i=1;
        ?>
        @foreach($plKnit['yarns'] as $yarn)
        
        <tr>
           <td width="38" align="center">{{$i}}</td>
            <td width="60" align="center">{{$yarn->yarn_count}}</td>
            <td width="80" align="center">{{$yarn->composition}}</td>
            <td width="80" align="center">{{$yarn->lot}}</td>
            <td width="60" align="center">{{$yarn->brand}}</td>
            <td width="60" align="center">{{$yarn->color_name}}</td>
            <td width="80" align="center">{{$yarn->supplier_name}}</td>
            <td width="80" align="right">{{number_format($yarn->qty,2)}}</td>
            <td width="40" align="center">{{$yarn->uom_code}}</td>
            <td width="60" align="center">{{$yarn->rq_no}}</td>
        </tr>
        <?php 
        $tQty+=$yarn->qty;
        $i++;
        ?>
        @endforeach
    </tbody>
    <tfoot>
        
        <tr>
            <td width="458" align="right">Total</td>
            <td width="80" align="right">{{number_format($tQty,2)}}</td>
            <td width="40" align="center"></td>
            <td width="60" align="center"></td>
        </tr>
    </tfoot>
    </table>
    <br/>

    Remarks:&nbsp;{{ $plKnit['master']->dtl_remarks }}
