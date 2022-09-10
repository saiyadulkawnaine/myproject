<h3 align="center"><u>Asset Sending Out Report For Repair/Service</u></h3>
<table>
  <tr>
    <td width="120" align="left"></td>
    <td width="400" align="center"><strong>{{ $data['master']->remarks }}</strong></td>
    <td width="150" align="left"></td>
  </tr>
  <tr>
    <td></td>
  </tr>
  <tr>
    <td>{{ $data['master']->supplier_name }}<br />
      {{ $data['master']->supplier_address }}</td>
  </tr>
  <tr>
    <td></td>
  </tr>
  <tr>
    <td width="250" align="left">Asset No:
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      {{ $data['master']->custom_no }}<br /> Asset Name:&nbsp;&nbsp;&nbsp;&nbsp;
      {{ $data['master']->asset_name }}<br />
      Asset Type:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $data['master']->type_id }}
    </td>
    <td width="200"></td>
    <td width="250" align="left">Out
      Date:&nbsp;{{ $data['master']->out_date }}<br />
      Returnable Date:&nbsp;{{ $data['master']->returnable_date }}<br />
      Reason:&nbsp;{{ $data['master']->reason_id}}
    </td>
  </tr>
  <tr>
    <td></td>
  </tr>
  <tr>
    <td></td>
  </tr>
</table>
<table border="1" cellspacing='0' cellpadding='2' align="center" style="padding: 3px">
  <thead>
    <tr>
      <td align="center" width="50">SL</td>
      <td align="center" width="200">Parts/Asset</td>
      <td align="center" width="80">UOM</td>
      <td align="center" width="80">Qty</td>
      <td align="center" width="240">Remark</td>
    </tr>
  </thead>
  <?php
  $i=1;
  $tqty=0;
  ?>
  @foreach ($data['details'] as $rows)
  <tbody>
    <tr>
      <td align="left" width="50">{{ $i++ }}</td>
      <td align="left" width="200">{{ $rows->itemcategory }}</td>
      <td align="left" width="80">{{ $rows->uom_code }}</td>
      <td align="right" width="80">{{ $rows->qty }}</td>
      <td align="left" width="240">{{ $rows->remarks }}</td>
    </tr>
  </tbody>
  <?php 
  $tqty+=$rows->qty
 ?>
  @endforeach
</table>
<table>
  <tr>
    <td align="center" width='30'></td>
    <td align="left" width='80'>Total</td>
    <td align="right" width="80">{{ $tqty }}</td>
    <td align="center" width="150"></td>
  </tr>
</table>
<table>
  <tr>
    <td></td>
  </tr>
</table>
<table>
  <tr>
    <td align="left" width="150"></td>
    <td align="left" width="150"></td>
    <td align="left" width="150"></td>
    <td align="left" width="100"></td>
    <td align="left" width="150"></td>
  </tr>
  <tr>
    <td align="left" width="150"></td>
    <td align="left" width="150"></td>
    <td align="left" width="150"></td>
    <td align="left" width="100"></td>
    <td align="left" width="150"></td>
  </tr>
  <tr>
    <td align="left" width="150"></td>
    <td align="left" width="150"></td>
    <td align="left" width="150"></td>
    <td align="left" width="100"></td>
    <td align="left" width="150"></td>
  </tr>
  <tr>
    <td align="left" width="150"></td>
    <td align="left" width="150"></td>
    <td align="left" width="150"></td>
    <td align="left" width="100"></td>
    <td align="left" width="150"></td>
  </tr>
  <tr>
    <td align="left" width="150"></td>
    <td align="left" width="150"></td>
    <td align="left" width="150"></td>
    <td align="left" width="100"></td>
    <td align="left" width="150"></td>
  </tr>
  <tr>
    <td align="left" width="150"><strong>Prepared By</strong></td>
    <td align="left" width="150"><strong>Mantenance Dept</strong></td>
    <td align="left" width="150"><strong>Functional Dept</strong></td>
    <td align="left" width="100"><strong>DMD</strong></td>
    <td align="left" width="150"><strong>Approved By</strong></td>
  </tr>
  <tr>
    <td align="left" width="150">{{ $data['master']->createdby_user_name}}
      <br /> {{ $data['master']->createdby_designation }}
    </td>
    <td align="left" width="150"></td>
    <td align="left" width="150"></td>
    <td align="left" width="100"></td>
    <td align="left" width="150"></td>
  </tr>
</table>