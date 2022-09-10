<table>
    <tr>
        <td align="left" width="200">{{ $data['master']->buyer_name }}<br />{{ $data['master']->buyer_address }}</td>
        <td align="left" width="200">Driver
            Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $data['master']->driver_name }}<br />Driver
            Contact:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $data['master']->driver_contact_no }}<br />Driver License
            No:{{ $data['master']->driver_license_no }}
        </td>
        <td align="left" width="130">Truck No: {{ $data['master']->truck_no }}<br />Lock
            No:&nbsp;&nbsp;{{ $data['master']->lock_no }}</td>
        <td align="left" width="120">Challan No: {{ $data['master']->dlv_no }}<br />Challan
            Date:{{ $data['master']->dlv_date }}</td>
    </tr>
    <tr>
        <td colspan="4" align="left" width="650"><strong>Remarks: {{ $data['master']->remarks }}</strong></td>
    </tr>
</table>
<p></p>
<table border="1" cellspacing="0" cellpadding="2">
    <tr>
        <td align="center" width="30"><strong>SL</strong></td>
        <td align="center" width="200"><strong>Item Descriptions</strong></td>
        <td align="center" width="50"><strong>UOM</strong></td>
        <td align="center" width="80"><strong>Qty</strong></td>
        <td align="center" width="80"><strong>Rate</strong></td>
        <td align="center" width="80"><strong>Amount</strong></td>
        <td align="center" width="130"><strong>Remarks</strong></td>
    </tr>
    <?php
        $i=1;
        $tAmount=0;
    ?>
    @foreach ($data['details'] as $rows)
    <tbody>
        <tr>
            <td align="center" width="30">{{ $i++ }}</td>
            <td align="left" width="200">{{ $rows->acc_chart_ctrl_head_name }}</td>
            <td align="center" width="50">{{ $rows->uom_code }}</td>
            <td align="right" width="80">{{ $rows->qty }}</td>
            <td align="right" width="80">{{ $rows->rate }}</td>
            <td align="right" width="80">{{ $rows->amount }}</td>
            <td align="left" width="130">{{ $rows->order_item_remarks }}</td>
        </tr>
    </tbody>
    <?php
        $tAmount+=$rows->amount;
    ?>
    @endforeach
</table>
<table cellspacing="0" cellpadding="2">
    <tr>
        <td align="center" width="30"></td>
        <td align="center" width="200"><strong>Total</strong></td>
        <td align="center" width="50"></td>
        <td align="center" width="80"></td>
        <td align="center" width="80"></td>
        <td align="right" width="80"><strong>{{ $tAmount }}</strong></td>
        <td align="center" width="130"></td>
    </tr>
    <tr>
        <td colspan="7" width="650"></td>
    </tr>
</table>
<p><strong>In Words: {{ $data['master']->inword }}</strong></p>
<p></p>
<table>
    <tr>
        <td width="200" align="left"><strong>Prepared By</strong></td>
        <td width="100" align="left"></td>
        <td width="100" align="left"></td>
        <td width="100" align="left"></td>
        <td width="180" align="left"><strong>Approved By</strong></td>
    </tr>
    <tr>
        <td width="200" align="left">@if ($data['master']->createdby_signature)<img
                src="{{ $data['master']->createdby_signature }}" width="100" , height="40" />
            @endif
        </td>
        <td width="100" align="left"></td>
        <td width="100" align="left"></td>
        <td width="100" align="left"></td>
        <td width="180" align="left">@if ($data['master']->approvedby_signature)<img
                src="{{ $data['master']->approvedby_signature }}" width="100" , height="40" />
            @endif</td>
    </tr>
    <tr>
        <td width="200" align="left">
            <strong>{{$data['master']->createdby_user_name}}<br />{{$data['master']->createdby_designation}}</strong>
        </td>
        <td width="100" align="left"></td>
        <td width="100" align="left"></td>
        <td width="100" align="left"></td>
        <td width="180" align="left">
            <strong>{{$data['master']->approvedby_user_name}}<br />{{$data['master']->approvedby_designation}}</strong>
        </td>
    </tr>
</table>