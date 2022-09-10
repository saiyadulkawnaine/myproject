<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="140" align="left"></td>
        <td width="140" align="left"></td>
        <td width="140"><br/></td>
        <td width="150"><br/></td>
        <td width="140">
            <br/>
            Requisition For:<br/>
            Requisition No:<br/>
            Requisition Date:<br/>
            Supplier:<br/>
        </td>
        <td width="190">
            <br/>
            {{ $data['master']->rq_for }}<br/>
            {{ $data['master']->rq_no }}<br/>
             {{ $data['master']->rq_date }}<br/>
            {{ $data['master']->supplier_name }}<br/>{{ $data['master']->supplier_address }}
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
            <td width="200" align="center">Item Des.</td>
            <td width="70" align="center">Qty</td>
            <td width="45" align="center">UOM</td>
            <td width="60" align="center">Stock</td>
            <td width="50" align="center">Avg. Rate</td>
            <td width="60" align="center">Amount</td>
            <td width="60" align="center">Item ID</td>
            <td width="61" align="center">M/C No</td>
            <td width="130" align="center">Remarks</td>
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
        <tr>
            <td width="30" align="center">{{ $i }}</td>
            <td width="60" align="center">{{$row->sort_id}}</td>
            <td width="60" align="center">{{$row->category_name}}</td>
            <td width="60" align="center">{{$row->class_name}}</td>
            <td width="200" align="left">{{$row->item_description}}, {{$row->specification}}</td>
            <td width="70" align="right">{{$row->qty}}</td>
            <td width="45" align="center">{{$row->uom_name}}</td>
            <td width="60" align="center">{{$row->stock_qty}}</td>
            <td width="50" align="center">{{$row->stock_rate}}</td>
            <td width="60" align="center">{{$row->stock_amount}}</td>
            <td width="60" align="center">{{$row->item_account_id}}</td>
            <td width="61" align="center">{{$row->custom_no}}</td>
            <td width="130" align="left">{{$row->remarks}}</td>

        </tr>
        <?php
        $i++;
    ?>
        @endforeach
         <tr>
            <td width="410" align="right">Total</td>
            <td width="70" align="right">{{$qty}}</td>
            <td width="45" align="center"></td>
            <td width="60" align="center"></td>
            <td width="50" align="center"></td>
            <td width="60" align="center"></td>
            <td width="60" align="center"></td>
            <td width="61" align="center"></td>
            <td width="130" align="center"></td>
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