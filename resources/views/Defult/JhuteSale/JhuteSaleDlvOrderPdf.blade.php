<table>
    <tr>
        <td width="300">{{ $data['master']->buyer_name }},<br/>
        {{ $data['master']->buyer_cell_no }}<br/>
        Payment Before Delv: {{ $data['master']->payment_before_dlv }}</td>
        <td width="250"></td>
        <td width="200">DO No: {{ $data['master']->do_no }}<br/>
            DO Date: {{ $data['master']->do_date }}<br/>
            ETD: {{ $data['master']->etd_date }}<br/>
        </td>
    </tr>
    <tr>
        <td colspan="3">Remarks: {{ $data['master']->remarks }}<br/></td>
    </tr>
</table>
<table cellspacing="0" cellpadding="2" border="1">
    <tr>
        <td width="30" align="center"><strong>SL</strong></td>
        <td width="150" align="center"><strong>Item Description</strong></td>
        <td width="60" align="center"><strong>UOM</strong></td>
        <td width="100" align="center"><strong>Qty</strong></td>
        <td width="100" align="center"><strong>Rate</strong></td>
        <td width="100" align="center"><strong>Amount</strong></td>
        <td width="200" align="center"><strong>Remarks</strong></td>
    </tr>
    <?php
        $i=1;
        $tAmount=0;
    ?>
    @foreach ($data['details']  as $rows)
    <tbody>
        <tr>
            <td width="30" align="center">{{ $i++ }}</td>
            <td width="150" align="left">{{ $rows->ctrl_head_name }}</td>
            <td width="60" align="center">{{ $rows->uom_code }}</td>
            <td width="100" align="right">{{ $rows->qty }}</td>
            <td width="100" align="right">{{ $rows->rate }}</td>
            <td width="100" align="right">{{ $rows->amount }}</td>
            <td width="200" align="left">{{ $rows->item_remarks }}</td>
        </tr>
    </tbody>
    <?php
        $tAmount+=$rows->amount;
    ?>
    @endforeach
</table>
<?php
    $yetToReceive=$tAmount-$data['master']->payment_received;
    $totalOutstanding=$data['master']->receivable+$yetToReceive;
?>
<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="240" align="left"></td>
        <td width="200" align="left"><strong>Total</strong></td>
        <td width="100" align="right"><strong>{{ number_format($tAmount,2) }}</strong></td>
        <td width="200"></td>
    </tr>
    <tr>
        <td width="240" align="left"></td>
        <td width="200" align="left"><strong>Amount Received</strong></td>
        <td width="100" align="right"><strong>{{ number_format($data['master']->payment_received,2) }}</strong></td>
        <td width="200"></td>
    </tr>
    <tr>
        <td width="240" align="left"></td>
        <td width="200" align="left"><strong>Yet to Receive</strong></td>
        <td width="100" align="right"><strong>{{ number_format($yetToReceive,2) }}</strong></td>
        <td width="200"></td>
    </tr>
    <tr>
        <td width="240" align="left"></td>
        <td width="200" align="left"><strong>Previous Outstandings</strong></td>
        <td width="100" align="right"><strong>{{ number_format($data['master']->receivable,2) }}</strong></td>
        <td width="200"></td>
    </tr>
    <tr>
        <td width="240" align="left"></td>
        <td width="200" align="left"><strong>Total Outstandings</strong></td>
        <td width="100" align="right"><strong>{{ number_format($totalOutstanding,2) }}</strong></td>
        <td width="200"></td>
    </tr>
</table>
<p></p>
<table>
    <tr>
        <td width="200" align="left"><strong>Prepared By</strong></td>
        <td width="200" align="left"><strong>Advised By</strong></td>
        <td width="200" align="left"><strong>Price Verified By</strong></td>
        <td width="200" align="left"><strong>Approved By</strong></td>
    </tr>
    <tr>
        <td width="200" align="left">@if ($data['master']->createdby_signature)<img src="{{ $data['master']->createdby_signature }}" width="100", height="40" />
            @endif
        </td>
        <td width="200" align="left">@if ($data['master']->advisedby_signature)<img src="{{ $data['master']->advisedby_signature }}" width="100", height="40" />
            @endif</td>
        <td width="200" align="left">@if ($data['master']->price_varify_signature)<img src="{{ $data['master']->price_varify_signature }}" width="100", height="40" />
            @endif</td>
        <td width="200" align="left">@if ($data['master']->approvedby_signature)<img src="{{ $data['master']->approvedby_signature }}" width="100", height="40" />
            @endif</td>
    </tr>
    <tr>
        <td width="200" align="left"><strong>{{$data['master']->createdby_user_name}}<br/>{{$data['master']->createdby_designation}}</strong></td>
        <td width="200" align="left"><strong>{{$data['master']->advisedby_user_name}}<br/>{{$data['master']->advisedby_designation}}</strong></td>
        <td width="200" align="left"><strong>{{$data['master']->price_verify_user_name}}<br/>{{$data['master']->price_verify_designation}}</strong></td>
        <td width="200" align="left"><strong>{{$data['master']->approvedby_user_name}}<br/>{{$data['master']->approvedby_designation}}</strong></td>
    </tr>
</table>