<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="250">From,<br/>
        {{ $data['master']->supplier_name }}<br/>
        {{ $data['master']->supplier_address }}
        </td>
        <td width="400" align="left">
            
        </td>
        <td width="120">
            <br/>
            YRR No:<br/>
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
            Contact Details: {{ $data['master']->contact_detail}}
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
            <td width="60" align="center">Count</td>
            <td width="150" align="center">Yarn Des.</td>
            <td width="60" align="center">Lot</td>
            <td width="70" align="center">Recv. Qty</td>
            <td width="45" align="center">UOM</td>
            <td width="45" align="center">Rate</td>
            <td width="70" align="center">Amount</td>
            <td width="80" align="center">Store Amount</td>
            <td width="50" align="center">No Of Bag</td>
            <td width="50" align="center">Wgt/Bag</td>
            <td width="120" align="center">Remarks</td>
            <td width="86" align="left">Po No/ PI No/ LC No</td>
        </tr>
    </thead>
    <tbody>
        @foreach($data['details'] as $row)
        <tr>
            <td width="30" align="center">{{ $i }}</td>
            <td width="60" align="center">{{$row->yarn_count}}</td>
            <td width="150" align="left">{{$row->composition}}, {{$row->yarn_type}},  {{$row->brand}}, {{$row->color_name}}</td>
            <td width="60" align="center">{{$row->lot}}</td>
            <td width="70" align="right">{{$row->qty}}</td>
            <td width="45" align="center">{{$row->uom_code}}</td>
            <td width="45" align="right">{{$row->rate}}</td>
            <td width="70" align="right">{{$row->amount}}</td>
            <td width="80" align="right">{{$row->store_amount}}</td>
            <td width="50" align="right">{{$row->no_of_bag}}</td>
            <td width="50" align="right">{{$row->wgt_per_bag}}</td>
            <td width="120" align="center">{{$row->remarks}}</td>
            <td width="86" align="center">PO: {{$row->po_no}}<br/>PI: {{$row->pi_no}}<br/>LC: {{$row->lc_no}}</td>
        </tr>
        <?php
        $i++;
    ?>
        @endforeach
        <tr>
            <td width="300" align="right">Total</td>
            <td width="70" align="right">{{$data['details']->sum('qty')}}</td>
            <td width="45" align="center"></td>
            <td width="45" align="center"></td>
            <td width="70" align="right">{{$data['details']->sum('amount')}}</td>
            <td width="80" align="right">{{$data['details']->sum('store_amount')}}</td>
            <td width="50" align="right">{{$data['details']->sum('no_of_bag')}}</td>
            <td width="50" align="center"></td>
            <td width="120" align="center"></td>
            <td width="86" align="center"></td>
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