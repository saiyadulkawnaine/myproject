<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="250">From,<br/>
        {{ $data['master']->from_company_name }}<br/>
        {{ $data['master']->from_company_address }}
        </td>
        <td width="400" align="left">
            
        </td>
        <td width="120">
            <br/>
            Receive No:<br/>
            Receive Date:<br/>
            
        </td>
        <td width="140">
            <br/>
            {{ $data['master']->receive_no }}<br/>
            {{ $data['master']->receive_date }}<br/>
        </td>
    </tr>
    <tr>
        <td width="910">
            <br/>
            
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
            <td width="80" align="center">Item Class</td>
            <td width="60" align="center">Count</td>
            <td width="120" align="center">Composition</td>
            <td width="60" align="center">Type</td>
            <td width="60" align="center">Yarn Color</td>
            <td width="60" align="center">Lot</td>
            <td width="60" align="center">Brand</td>
            <td width="100" align="center">Supplier</td>

            <td width="70" align="center">Qty</td>
            <td width="50" align="center">Rate</td>
            <td width="70" align="center">Amount</td>
            <td width="70" align="center">Remarks</td>
            <td width="50" align="center">Transfer No</td>

        </tr>
    </thead>
    <tbody>
        @foreach($data['details'] as $row)
        <tr>
            <td width="30" align="center">{{ $i }}</td>
            <td width="80" align="center">{{$row->itemclass_name}}</td>
            <td width="60" align="left">{{$row->yarn_count}} </td>
            <td width="120" align="center">{{$row->composition}}</td>
            <td width="60" align="right">{{$row->yarn_type}}</td>
            <td width="60" align="center">{{$row->color_name}}</td>
            <td width="60" align="right">{{$row->lot}}</td>
            <td width="60" align="right">{{$row->brand}}</td>
            <td width="100" align="right">{{$row->supplier_name}}</td>
            <td width="70" align="right">{{$row->qty}}</td>
            <td width="50" align="right">{{$row->rate}}</td>
            <td width="70" align="center">{{$row->amount}}</td>
            <td width="70" align="center">{{$row->remarks}}</td>
            <td width="50" align="center">{{$row->transfer_no}}</td>

        </tr>
        <?php
        $i++;
    ?>
        @endforeach
        <tr>
            <td width="630" align="right">Total</td>
            <td width="70" align="right">{{$data['details']->sum('qty')}}</td>
            <td width="50" align="center"></td>
            <td width="70" align="center">{{$data['details']->sum('amount')}}</td>
            <td width="70" align="center"></td>
            <td width="50" align="center"></td>
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
            Receive By
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