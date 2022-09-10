<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="350">To,
        <br/>
        {{ $data['master']->supplier_name }}<br/>
        {{ $data['master']->supplier_address }}
        
        </td>
        <td width="300" align="left">
        </td>
        <td width="120">
            <br/>
            Return No:<br/>
            Return Date:<br/>
        </td>
        <td width="140">
            <br/>
            {{ $data['master']->return_no }}<br/>
            {{ $data['master']->return_date }}<br/>
        </td>
    </tr>
    <tr>
        <td width="910">{{ $data['master']->master_remarks }}</td>
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
            <td width="100" align="center">Item Class</td>
            <td width="60" align="center">Yarn Count</td>
            <td width="140" align="center">Composition</td>
            <td width="45" align="center">Yarn Type</td>
            <td width="50" align="center">Color</td>
            <td width="60" align="center">Lot/Batch</td>
            <td width="60" align="center">Brand</td>
            <td width="60" align="center">Qty</td>
            <td width="60" align="center">Rate</td>
            <td width="60" align="center">Amount</td>
            <td width="60" align="center">Receive Challan</td>
            <td width="90" align="center">Remarks</td>
            <td width="66" align="center">MRR No</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $i=1;
        ?>
        @foreach($data['details'] as $row)
        <tr>
            <td width="30" align="center">{{$i}}</td>
            <td width="100" align="center">{{$row->itemclass_name}}</td>
            <td width="60" align="center">{{$row->yarn_count}}</td>
            <td width="140" align="center">{{$row->composition}}</td>
            <td width="45" align="left">{{$row->yarn_type}}</td>
            <td width="50" align="center">{{$row->color_name}}</td>
            <td width="60" align="center">{{$row->lot}}</td>
            <td width="60" align="center">{{$row->barnd}}</td>
            <td width="60" align="right">{{number_format($row->qty,2)}}</td>
            <td width="60" align="right">{{number_format($row->rate,4)}}</td>
            <td width="60" align="right">{{number_format($row->amount,2)}}</td>
            <td width="60" align="center">{{$row->challan_no}}</td>
            <td width="90" align="center">{{$row->remarks}}</td>
            <td width="66" align="center">{{$row->receive_no}}</td>
        </tr>
        <?php
        $i++;
        ?>
        @endforeach
        <tr>
            <td width="545" align="right">Total</td>
            <td width="60" align="right">{{number_format($data['details']->sum('qty'),2)}}</td>
            <td width="60" align="center"></td>
            <td width="60" align="center">{{number_format($data['details']->sum('amount'),2)}}</td>
            <td width="60" align="center"></td>
            <td width="90" align="center"></td>
            <td width="66" align="center"></td>
        </tr>
    </tbody>
</table>



<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>


<table>
    <tr align="center">
        <td width="182">
            Received By
        </td>
        <td width="182">
            Returned By
        </td>
        <td width="182">
            Head Of Department 
        </td>
        <td width="182">
            Checked By
        </td>
        <td width="182">
            Authorized By
        </td>
    </tr>
    <tr align="center">
        <td width="182"></td>
        <td width="182">&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;{{ $data['master']->user_name }},&nbsp;&nbsp;{{ $data['master']->contact }}<br/>
            &nbsp;&nbsp;&nbsp;{{ date('d-M-Y',strtotime($data['master']->created_at)) }}
        </td>
        <td width="182"></td>
        <td width="182"></td>
        <td width="182"></td>
        
    </tr>
</table>