<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="250">To,<br/>
        {{ $data['master']->supplier_name }}<br/>
        {{ $data['master']->supplier_address }}
       
        </td>
        <td width="400" align="left">
            
        </td>
        <td width="120">
            <br/>
            Issue No:<br/>
            Issue Date:<br/>
        </td>
        <td width="140">
            <br/>
            {{ $data['master']->issue_no }}<br/>
            {{ $data['master']->issue_date }}<br/>
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
            <td width="60" align="center">Item Category</td>
            <td width="60" align="center">Item Class</td>
            <td width="260" align="center">Item Des.</td>
            <td width="70" align="center">Qty</td>
            <td width="45" align="center">UOM</td>
            <td width="115" align="center">Rate</td>
            <td width="70" align="center">Amount</td>
           <td width="76" align="center">Challan No  </td>
            <td width="160" align="center">Remarks</td>
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
            <td width="115" align="center">{{$row->rate}}</td>
            <td width="70" align="right">{{$row->amount}}</td>
            <td width="76" align="center">{{$row->challan_no }}</td>
            <td width="160" align="center">{{$row->remarks}}</td>

        </tr>
        <?php
        $i++;
    ?>
        @endforeach
        <tr>
            <td width="410" align="right">Total</td>
            <td width="70" align="right">{{$data['details']->sum('qty')}}</td>
            <td width="45" align="center"></td>
            <td width="115" align="center"></td>
            <td width="70" align="right">{{$data['details']->sum('amount')}}</td>
            <td width="76" align="center"></td>
            <td width="160" align="center"></td>

        </tr>
    </tbody>
</table>


<br/>
<br/>
<br/>
<br/>
<table>
    <tr align="center">
        <td width="182">
            Received By
        </td>
        <td width="182">
            Issued By
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