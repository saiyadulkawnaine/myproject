<table>
  <tr>
    <td align="left" width="150">To<br />{{ $data['master']->buyer_name }}<br />{{ $data['master']->buyer_address }}
    </td>
    <td align="left" width="30"></td>
    <td align="left" width="200">Driver
      Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $data['master']->driver_name }}<br />Driver
      Contact:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $data['master']->driver_contact_no }}<br />Driver
      License
      No:&nbsp;&nbsp;&nbsp;{{ $data['master']->driver_license_no }}
    </td>
    <td align="left" width="130">Truck No: {{ $data['master']->truck_no }}<br />Lock
      No:&nbsp;&nbsp;&nbsp;{{ $data['master']->lock_no }}</td>
    <td align="left" width="50"></td>
    <td align="left" width="120">Bill No: {{ $data['master']->dlv_no }}<br />Bill
      Date:&nbsp;&nbsp;{{ $data['master']->dlv_date }}</td>
  </tr>
  <tr>
    <td colspan="4" align="left" width="650"><strong>Remarks: {{ $data['master']->remarks }}</strong></td>
  </tr>
</table>
<p></p>
<table border="1" cellspacing="0" cellpadding="2">
  <tr>
    <td align="center" width="20"><strong>SL</strong></td>
    <td align="center" width="50"><strong>Sales<br /> Order</strong></td>
    <td align="center" width="50"><strong>Emb.<br /> Name</strong></td>
    <td align="center" width="70"><strong>Emb.<br /> Type</strong></td>
    <td align="center" width="60"><strong>Emb.<br /> Size</strong></td>
    <td align="center" width="60"><strong>GMT <br /> Part</strong></td>
    <td align="center" width="70"><strong>GMT <br />Item</strong></td>
    <td align="center" width="70"><strong>GMT <br />Color</strong></td>
    <td align="center" width="50"><strong>Dlv. Qty</strong></td>
    <td align="center" width="50"><strong>Rate</strong></td>
    <td align="center" width="50"><strong>Amount</strong></td>
    <td align="center" width="70"><strong>Remarks</strong></td>
  </tr>
  <?php
        $i=1;
        // $tdlv_qty=0;
        $tAmount=0;
    ?>
  @foreach ($data['details'] as $rows)
  <tbody>
    <tr>
      <td align="center" width="20">{{ $i++ }}</td>
      <td align="left" width="50">{{ $rows->sale_order_no }}</td>
      <td align="left" width="50">{{ $rows->emb_name }}</td>
      <td align="left" width="70">{{ $rows->emb_type }}</td>
      <td align="left" width="60">{{ $rows->emb_size }}</td>
      <td align="left" width="60">{{ $rows->gmtspart }}</td>
      <td align="left" width="70">{{ $rows->item_desc }}</td>
      <td align="left" width="70">{{ $rows->gmt_color }}</td>
      <td align="right" width="50">{{ $rows->dlv_qty }}</td>
      <td align="right" width="50">{{ $rows->rate }}</td>
      <td align="right" width="50">{{ $rows->amount }}</td>
      <td align="left" width="70">{{ $rows->remarks }}</td>
    </tr>
  </tbody>
  <?php 
 $tAmount+=$rows->amount;
 ?>
  @endforeach
  <tr>
    <td width="450" align="right">Total</td>
    <td width="50" align="right">{{number_format($data['details']->sum('dlv_qty'),2)}}</td>
    <td width="50" align="right">{{number_format($data['details']->avg('rate'),2)}}</td>
    <td width="50" align="right">{{number_format($tAmount,2)}}</td>
    <td width="70" align="center"></td>
  </tr>
</table>
<p></p>
<p></p>
<p><strong>In Words:{{ $data['master']->inword }}</strong></p>
<p></p>
<p></p>
<table>
  <tr align="center">
    <td width="120"></td>
    <td width="120">
      @if ($data['master']->createdby_signature)<img src="{{ $data['master']->createdby_signature }}" width="100" ,
        height="40" />
      @endif
    </td>
    <td width="120"></td>
    <td width="120"></td>
    <td width="120">
      @if ($data['master']->approvedby_signature)<img src="{{ $data['master']->approvedby_signature }}" width="100" ,
        height="40" />
      @endif
    </td>
  </tr>
  <tr align="center">
    <td width="120">
      Received By
    </td>
    <td width="120">
      Issued By
    </td>
    <td width="120">
      Head Of Department
    </td>
    <td width="120">
      Checked By
    </td>
    <td width="120">
      Approved By
    </td>
  </tr>
  <tr align="center">
    <td width="120"></td>
    <td width="120">{{ $data['master']->createdby_user_name }}<br />{{ $data['master']->createdby_contact }}<br />
      &nbsp;&nbsp;&nbsp;{{ date('d-M-Y',strtotime($data['master']->created_at)) }}
    </td>
    <td width="120"></td>
    <td width="120"></td>
    <td width="120"></td>

  </tr>
</table>