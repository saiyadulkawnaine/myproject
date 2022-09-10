<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="250">To,<br/>
        {{ $data['master']->buyer_name }}<br/>
        </td>
        <td width="400" align="left">
            
        </td>
        <td width="120">
            <br/>
            Challan No:<br/>
            Challan Date:<br/>
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
            Remarks: {{ $data['master']->master_remarks}}
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
            <td width="60" align="center">Work Order No</td>
            <td width="160" align="center">Fabric Description</td>
            <td width="60" align="center">Fabric Looks</td>
            <td width="70" align="center">Fabric Shape</td>
            <td width="45" align="center">GSM</td>
            <td width="45" align="center">Dia</td>
            <td width="70" align="center">Fabric Color</td>
            <td width="50" align="center">UOM *</td>
            <td width="50" align="center">Dlv. Qty</td>
            <td width="60" align="center">No of Roll</td>
            <td width="160" align="center">Yarn Used</td>
            <td width="86" align="left">Remarks</td>
        </tr>
    </thead>
    <tbody>
    <?php
    $qty=0;
    $no_of_roll=0;
    ?>
    @foreach($data['details'] as $row)
    <tr>
    <td width="30" align="center">{{ $i }}</td>
    <td width="60" align="center">{{$row->sales_order_no}}</td>
    <td width="160" align="left">{{$row->fabrication}}</td>
    <td width="60" align="center">{{$row->fabriclooks}}</td>
    <td width="70" align="right">{{$row->fabricshape}}</td>
    <td width="45" align="center">{{$row->gsm_weight}}</td>
    <td width="45" align="right">{{$row->dia}}</td>
    <td width="70" align="right">{{$row->fabric_color}}</td>
    <td width="50" align="right">{{$row->uom_name}}</td>
    <td width="50" align="right">{{number_format($row->qty,2)}}</td>
    <td width="60" align="right">{{$row->no_of_roll}}</td>
    <td width="160" align="left">{{$row->yarn_used}}</td>
    <td width="86" align="center"> {{$row->remarks}}</td>
    </tr>
    <?php
    $qty+=$row->qty;
    $no_of_roll+=$row->no_of_roll;
    $i++;
    ?>
    @endforeach
        
    <tr>
        <td width="310" align="right">Total</td>
        <td width="70" align="right"></td>
        <td width="45" align="center"></td>
        <td width="45" align="center"></td>
        <td width="70" align="right"></td>
        <td width="50" align="right"></td>
        <td width="50" align="right">{{number_format($qty,2)}}</td>
        <td width="60" align="right">{{number_format($no_of_roll,0)}}</td>
        <td width="160" align="right"></td>
        <td width="86" align="center"></td>
    </tr>
</tbody>
</table>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<table>
    
    <tr align="center">
        <td width="151">
            Prepared By
        </td>
        <td width="151">
          Received By  
        </td>
        <td width="151">
            Checked/ Audited By
        </td>
        <td width="151">
            Head Of Dept.
        </td>
        <td width="151">
           Head Of Store 
        </td>
        <td width="151">
           Approved By
        </td>
        
    </tr>
    <tr align="center">
        <td width="151">&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;{{ $data['master']->user_name }},&nbsp;&nbsp;{{ $data['master']->contact }}<br/>
            &nbsp;&nbsp;&nbsp;{{ $data['master']->created_at }}
        </td>
        <td width="151">
        </td>
        <td width="151">
            &nbsp;&nbsp;{{ $data['master']->approved_by_name }},<br/>
            &nbsp;&nbsp;&nbsp;{{ $data['master']->approved_at }}
        </td>
        <td width="151">
        </td>
        <td width="151">
        </td>
        <td width="151">
            
        </td>
        
    </tr>
</table>