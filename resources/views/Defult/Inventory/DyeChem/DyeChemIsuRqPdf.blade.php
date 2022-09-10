
<table cellspacing="0" cellpadding="2">
    
    <tr>
        <td width="140">
            <br/>
            Batch No:<br/>
            Requisition No:<br/>
            Requisition Date:<br/>
            Batch Color:<br/>
            Roll Color:<br/>
            Color Range:<br/>
        </td>
        <td width="140">
            <br/>
            {{ $data['master']->batch_no }}<br/>
            {{ $data['master']->rq_no }}<br/>
            {{ $data['master']->rq_date }}<br/>
            {{ $data['master']->batch_color }}<br/>
            {{ $data['master']->fabric_color }}<br/>
            {{ $data['master']->colorrange_name }}<br/>
        </td>
        
        <td width="140" align="left">
         <br/>
           
            Batch Wgt:<br/>  
            Liqure Ratio:<br/>
            Liqure Wgt:<br/>
            Lab Dip No:<br/> 
            Machine No:
        </td>
         <td width="140" align="left"><br/>
            {{ $data['master']->batch_wgt }}<br/>
            {{ $data['master']->liqure_ratio }}<br/>
            {{ $data['master']->liqure_wgt }}<br/>
            {{ $data['master']->lap_dip_no }}<br/>
            {{ $data['master']->machine_no }}
        </td>

        <td width="290"><br/>
            Customer: @if (isset($batch['ordDtl']['customer_name']))
            {{implode(',',$batch['ordDtl']['customer_name'])}}
            @endif
            <br/>
            Order No: @if (isset($batch['ordDtl']['sale_order_no']))
            {{implode(',',$batch['ordDtl']['sale_order_no'])}}
            @endif
            <br/>
            Buyer:  @if (isset($batch['ordDtl']['buyer_name']))
            {{implode(',',$batch['ordDtl']['buyer_name'])}}
            @endif
        </td>
        
    </tr>
    <tr>
        <td width="910">
            <br/>
             Remarks:{{ $data['master']->remarks }}
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
            <td width="60" align="center">Sequence</td>
            <td width="60" align="center">Item Category</td>
            <td width="60" align="center">Item Class</td>
            <td width="140" align="center">Item Des.</td>
            <td width="150" align="center">Ratio</td>
            <td width="70" align="center">Qty</td>
            <td width="45" align="center">UOM</td>
            <td width="60" align="center">Stock</td>
            <td width="50" align="center">Avg. Rate</td>
            <td width="60" align="center">Amount</td>
            <td width="60" align="center">Item ID</td>
            <td width="101" align="center">Remarks</td>
        </tr>
    </thead>
    <tbody>
        @foreach($data['details'] as $key=>$value)
        <tr>
            <td width="946" align="left">{{ $key }}</td>
        </tr>
        <?php
        $qty=0;
        $amount=0;
        ?>
        @foreach($value as $row)
        <?php
        $qty+=$row->qty;
        $amount=$row->amount;
        ?>
        <tr>
            <td width="30" align="center">{{ $i }}</td>
            <td width="60" align="center">{{$row->sort_id}}</td>
            <td width="60" align="center">{{$row->category_name}}</td>
            <td width="60" align="center">{{$row->class_name}}</td>
            <td width="140" align="left">{{$row->item_description}}, {{$row->specification}}</td>
            <td width="150" align="left">{{$row->ratio}}</td>
            <td width="70" align="right">{{$row->qty}}</td>
            <td width="45" align="center">{{$row->uom_name}}</td>
            <td width="60" align="center">{{$row->stock_qty}}</td>
            <td width="50" align="center">{{$row->stock_rate}}</td>
            <td width="60" align="center">{{$row->stock_amount}}</td>
            <td width="60" align="center">{{$row->item_account_id}}</td>
            <td width="101" align="left">{{$row->remarks}}</td>

        </tr>
        <?php
        $i++;
    ?>
        @endforeach
         <tr>
            <td width="500" align="right">Total</td>
            <td width="70" align="right">{{$qty}}</td>
            <td width="45" align="center"></td>
            <td width="60" align="center"></td>
            <td width="50" align="center"></td>
            <td width="60" align="center"></td>
            <td width="60" align="center"></td>
            <td width="101" align="center"></td>

        </tr>
        @endforeach
       
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