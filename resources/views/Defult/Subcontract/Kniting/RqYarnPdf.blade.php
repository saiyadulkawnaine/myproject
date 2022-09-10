<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="250">To,
        <br/>
        {{ $data['master']->supplier_name }}<br/>
        {{ $data['master']->supplier_address }}
        
        </td>
        <td width="400" align="left">
            
        </td>
        <td width="120">
            <br/>
            Req. No:<br/>
            Req. Date:<br/>
            Req. Against:<br/>
        </td>
        <td width="140">
            <br/>
            {{ $data['master']->rq_no }}<br/>
            {{ $data['master']->rq_date }}<br/>
            {{ $data['master']->rq_against_name }}<br/>
        </td>
    </tr>
    <tr>
        <td width="910">
            <br/>
           
        </td>
    </tr>
</table>
<br/>

<table cellspacing="0" cellpadding="2" border="1">
    <thead>
        <tr>
            <td width="30" align="center">#</td>
            <td width="170" align="center">Fabric Description</td>
            <td width="45" align="center">Count</td>
            <td width="170" align="center">Yarn Description</td>
            <td width="60" align="center">Lot</td>
            <td width="60" align="center">Supplier</td>
            <td width="60" align="center">Style & Buyer </td>
            <td width="60" align="center">Sales Order</td>
            <td width="50" align="center">Qty</td>
            <td width="30" align="center">UOM</td>
            
            <td width="120" align="center">Remarks</td>
            <td width="86" align="center">{{$data['master']->po_pl_no}}</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $i=1;
        ?>
        @foreach($data['details'] as $row)
        
        <tr nobr="true">
            <td width="30" align="center">{{$i}}</td>
            <td width="170" align="center">{{$row->fabrication}},{{$row->gsm_weight}}</td>
            <td width="45" align="center">{{$row->yarn_count}}</td>
            <td width="170" align="left">{{$row->composition}}, {{$row->yarn_type}},  {{$row->brand}}, {{$row->color_name}}</td>
            <td width="60" align="center">{{$row->lot}}</td>
            <td width="60" align="center">{{$row->supplier_name}}</td>
            <td width="60" align="center">{{$row->style_ref}} {{$row->buyer_name}}</td>
            <td width="60" align="center">{{$row->sale_order_no}}</td>
            <td width="50" align="right">{{$row->store_qty}}</td>
            <td width="30" align="center">{{$row->uom_code}}</td>
            
            
            <td width="120" align="center">{{$row->remarks}}</td>
            <td width="86" align="center">{{$row->pl_po_no}}</td>
        </tr>
        <?php
        $i++;
        ?>
        @endforeach
        <tr>
            <td width="655" align="right">Total</td>
            <td width="50" align="right">{{$data['details']->sum('qty')}}</td>
            <td width="30" align="center"></td>
            <td width="120" align="center"></td>
            <td width="86" align="center"></td>
        </tr>
    </tbody>
</table>
<br/>
<br/>
@if (!$data['master']->approved_by)
    <h3 align="right" style="font-stretch: ultra-expanded">UNAPPROVED&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h3>
@endif
<br/>
<br/>
<br/>
<br/>
<table>
    <tr align="center">
        <td width="228">Required By</td>
        <td width="227">Checked By</td>
        <td width="227">Planning Head</td>
        <td width="227">Head Of Department</td>
    </tr>
    <tr align="center">
        <td width="228">&nbsp;{{ $data['master']->user_name }},&nbsp;&nbsp;{{ $data['master']->contact }}<br/>
            &nbsp;&nbsp;&nbsp;{{ $data['master']->created_at }}
        </td>
        <td width="227"></td>
        <td width="227"></td>
        <td width="227"></td>
    </tr>
</table>