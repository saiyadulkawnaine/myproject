<p align="center"><strong style="font-size: 40px">Buyer: {{ $data['master']->buyer_name }}</strong></p>
<p align="center"><strong style="font-size: 40px">Batch No: {{ $data['master']->batch_no }}</strong></p>
<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="140" align="left"><br/>
            Requisition No:<br/>
            Requisition Date:<br/>
            Paste Wgt:<br/>
            Fabric Wgt:    
        </td>
        <td width="140" align="left">
            {{ $data['master']->rq_no }}<br/>
            {{ $data['master']->rq_date }}<br/>
            {{ $data['master']->paste_wgt }}<br/>
            {{ $data['master']->fabric_wgt }}
        </td>
        <td width="140"></td>
        <td width="150"></td>
        <td width="140">
            <br/>
            Req. For:<br/>
            Design No:<br/>
            Fabric Color:<br/>
            Color Range:
        </td>
        <td width="140">
            <br/>
            {{ $data['master']->rq_for }}<br/>
            {{ $data['master']->design_no }}<br/>
            {{ $data['master']->fabric_color }}<br/>
            {{ $data['master']->colorrange_name }}
        </td>
    </tr>
    <tr>
        <td width="910"><br/>Remarks:  {{ $data['master']->remarks }}
        </td>
    </tr>
</table>
<br/>
<?php
    $i=1;
?>
<table cellspacing="0" cellpadding="2" border="1">
    <thead>
        <tr>
            <td width="30" align="center">#</td>
            <td width="30" align="center">Seq.</td>
            <td width="50" align="center">Print Type</td>
            <td width="60" align="center">Item Category</td>
            <td width="60" align="center">Item Class</td>
            <td width="110" align="center">Item Des.</td>
            <td width="55" align="center">Sales Order</td>
            <td width="50" align="center">Paste Wgt</td>
            <td width="60" align="center">Ratio on Paste Wgt</td>
            <td width="60" align="center">Qty</td>
            <td width="45" align="center">UOM</td>

            <td width="60" align="center">Stock</td>
            <td width="50" align="center">Avg. Rate</td>
            <td width="60" align="center">Amount</td>

            <td width="60" align="center">Item ID</td>
            <td width="106" align="center">Remarks</td>
        </tr>
    </thead>
    <tbody>
        
        <?php
        $qty=0;
        $amount=0;
        ?>
        @foreach($data['details'] as $row)
        <?php
        $qty+=$row->qty;
        $amount=$row->amount;
        ?>
        <tr nobr="true">
            <td width="30" align="center">{{ $i }}</td>
            <td width="30" align="center">{{$row->sort_id}}</td>
            <td width="50" align="center">{{$row->print_type}}</td>
            <td width="60" align="center">{{$row->category_name}}</td>
            <td width="60" align="center">{{$row->class_name}}</td>
            <td width="110" align="left">{{$row->item_description}}, {{$row->specification}}</td>
            <td width="55" align="left">{{$row->sale_order_no}}</td>
            <td width="50" align="right">{{$row->paste_wgt}}</td>
            <td width="60" align="right">{{$row->rto_on_paste_wgt}}</td>
            <td width="60" align="right">{{$row->qty}}</td>
            <td width="45" align="center">{{$row->uom_name}}</td>
            <td width="60" align="center">{{$row->stock_qty}}</td>
            <td width="50" align="center">{{$row->stock_rate}}</td>
            <td width="60" align="center">{{$row->stock_amount}}</td>
            <td width="60" align="center">{{$row->item_account_id}}</td>
            <td width="106" align="left">{{$row->remarks}}</td>

        </tr>
        <?php
        $i++;
    ?>
        @endforeach
         <tr>
            <td width="505" align="right">Total</td>
            <td width="60" align="right">{{$qty}}</td>
            <td width="45" align="center"></td>
            <td width="60" align="center"></td>
            <td width="50" align="center"></td>
            <td width="60" align="center"></td>
            <td width="60" align="center"></td>
            <td width="106" align="center"></td>
        </tr>
       
    </tbody>
</table>
<p></p>
<p></p>
<p></p>
<p></p>
<table>
    <tr align="center">
        <td width="227">
            Prepared By
        </td>
        <td width="227">
            Checked By
        </td>
        <td width="227">
            Production Manager
        </td>
        <td width="229">
            Head of Department
        </td>
        
    </tr>
    <tr align="center">
        <td width="227">&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;{{ $data['master']->user_name }},&nbsp;&nbsp;{{ $data['master']->contact }}<br/>
            &nbsp;&nbsp;&nbsp;{{ $data['master']->created_at }}
        </td>
        <td width="227">
        </td>
        <td width="227">
        </td>
        <td width="229">
        </td>
        
    </tr>
</table>