<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="350">To,
        <br/>
        {{ $data['master']->supplier_name }}<br/>
        {{ $data['master']->supplier_address }}
        
        </td>
        <td width="300" align="left">Driver:&nbsp;{{ $data['master']->driver_name }},&nbsp;{{ $data['master']->driver_contact_no }}<br/>
            License No:&nbsp;{{ $data['master']->driver_license_no }}<br/>
            Truck No:&nbsp;{{ $data['master']->truck_no }}<br/>
            Lock No:&nbsp;{{ $data['master']->lock_no }}<br/>
            Recipient:&nbsp;{{ $data['master']->recipient }}
        </td>
        <td width="120">
            <br/>
            Issue No:<br/>
            Issue Date:<br/>
            Issue Basis:<br/>
            Issue Against:<br/>
        </td>
        <td width="140">
            <br/>
            {{ $data['master']->issue_no }}<br/>
            {{ $data['master']->issue_date }}<br/>
            {{ $data['master']->isu_basis_id }}<br/>
            {{ $data['master']->isu_against_id }}<br/>
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
            <td width="115" align="center">GMT Part</td>
            <td width="220" align="center">Fabric Description</td>
            
            <td width="65" align="center">GSM/Weight</td>
            <td width="50" align="center">Dia/Width</td>
            <td width="65" align="center">Measurement</td>
            <td width="60" align="center">Roll Length</td>
            <td width="60" align="center">Stitch Length</td>
            <td width="60" align="center">Shrink Per</td>
            <td width="60" align="center">Qty</td>
            <td width="30" align="center">UOM</td>
            <td width="60" align="center">Number Of Roll</td>
            <td width="66" align="center">Pcs</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $i=1;
        ?>
        @foreach($data['details'] as $row)
        <tr>
            <td width="30" align="center">{{$i}}</td>
            <td width="115" align="left">{{$row->body_part}}</td>
            <td width="220" align="left">{{$row->fabrication}},{{$row->fabric_shape}},{{$row->fabric_look}}</td>
            
            <td width="65" align="center">{{$row->gsm_weight}}</td>
            <td width="50" align="center">{{$row->dia_width}}</td>
            <td width="65" align="center">{{$row->measurement}}</td>
            <td width="60" align="center">{{$row->roll_length}}</td>
            <td width="60" align="center">{{$row->stitch_length}}</td>
            <td width="60" align="center">{{$row->shrink_per}}</td>
            <td width="60" align="right">{{number_format($row->qty,2)}}</td>
            <td width="30" align="center">KG</td>
            <td width="60" align="right">{{$row->number_of_roll}}</td>
            <td width="66" align="right">{{number_format($row->qty_pcs,2)}}</td>
        </tr>
        <?php
        $i++;
        ?>
        @endforeach
        <tr>
            <td width="725" align="right">Total</td>
            <td width="60" align="right">{{number_format($data['details']->sum('qty'),2)}}</td>
            <td width="30" align="center"></td>
            <td width="60" align="right">{{$data['details']->sum('number_of_roll')}}</td>
            <td width="66" align="right">{{number_format($data['details']->sum('qty_pcs'),2)}}</td>
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