<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="250">From,<br/>
        {{ $data['master']->location_name }}<br/>
        {{ $data['master']->location_address }}
        </td>
        <td width="400" align="left">
            
        </td>
        <td width="120">
            <br/>
            MRR No:<br/>
            Receive Date:<br/>
            Challan No:<br/>
            Receive Basis:<br/>
            Receive Against:<br/>
        </td>
        <td width="140">
            <br/>
            {{ $data['master']->receive_no }}<br/>
            {{ $data['master']->receive_date }}<br/>
            {{ $data['master']->challan_no }}<br/>
            {{ $data['master']->receive_basis_id }}<br/>
            {{ $data['master']->receive_against_id }}<br/>
        </td>
    </tr>
    <tr>
        <td width="910">
            <br/>
            Remarks: {{ $data['master']->remarks}}
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
            <td width="60" align="center">Item Category</td>
            <td width="60" align="center">Item Class</td>
            <td width="260" align="center">Item Des.</td>
            <td width="70" align="center">Recv. Qty</td>
            <td width="45" align="center">UOM</td>
            <td width="45" align="center">Rate</td>
            <td width="70" align="center">Amount</td>
            <td width="70" align="center">Store Amount</td>
            <td width="60" align="center">Sale Order No</td>
            <td width="76" align="center">Store</td>
            <td width="100" align="center">Remarks</td>
        </tr>
    </thead>
    <tbody>
        @foreach($data['details'] as $row)
        <tr>
            <td width="30" align="center">{{ $i }}</td>
            <td width="60" align="center">{{$row->category_name}}</td>
            <td width="60" align="center">{{$row->class_name}}</td>
            <td width="260" align="left">{{$row->item_desc}}, {{$row->specification}}</td>
            <td width="70" align="right">{{$row->qty}}</td>
            <td width="45" align="center">{{$row->uom_code}}</td>
            <td width="45" align="right">{{$row->rate}}</td>
            <td width="70" align="right">{{$row->amount}}</td>
            <td width="70" align="right">{{$row->store_amount}}</td>
            <td width="60" align="right">{{$row->sale_order_no}}</td>
            <td width="76" align="center">{{$row->store_name}}</td>
            <td width="100" align="center">{{$row->remarks}}</td>

        </tr>
        <?php
        $i++;
    ?>
        @endforeach
        <tr>
            <td width="410" align="right">Total</td>
            <td width="70" align="right">{{$data['details']->sum('qty')}}</td>
            <td width="45" align="center"></td>
            <td width="45" align="center"></td>
            <td width="70" align="right">{{$data['details']->sum('amount')}}</td>
            <td width="70" align="right">{{$data['details']->sum('store_amount')}}</td>
            <td width="60" align="center"></td>
            <td width="76" align="center"></td>
            <td width="100" align="center"></td>

        </tr>
    </tbody>
</table>


<br/>
<br/>
<br/>
<br/>
<table>
    
    <tr align="center">
        <td width="303">
            Received By
        </td>
        <td width="303">
            Checked By
        </td>
        <td width="304">
            Head of Department
        </td>
        
    </tr>
    <tr align="center">
        <td width="303">&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;{{ $data['master']->user_name }},&nbsp;&nbsp;{{ $data['master']->contact }}<br/>
            &nbsp;&nbsp;&nbsp;{{ $data['master']->created_at }}
        </td>
        <td width="303">
        </td>
        <td width="304">
        </td>
        
    </tr>
</table>