<table>
 <tr>
  <td colspan="3"></td>
 </tr>
 <tr>
  <td width="250" align="left">To<br />{{ $data['master']->supplier_name }}<br />
   {{ $data['master']->supplier_address }}</td>
  <td width="250" align="left"></td>
  <td width="250" align="left">Challan
   Date:&nbsp;{{ $data['master']->out_date }}<br />
   Returnable Date:&nbsp;{{ $data['master']->returnable_date }}<br />
  </td>
 </tr>
 <tr>
  <td colspan="3"></td>
 </tr>
 <tr>
  <td colspan="3">Remark:- {{ $data['master']->remarks }}</td>
 </tr>
 <tr>
  <td colspan="3"></td>
 </tr>
 <tr>
  <td colspan="3"></td>
 </tr>
</table>
<table border="1" cellspacing='0' cellpadding='2' align="center" style="padding: 3px">
 <thead>
  <tr>
   <td align="center" width="20">SL</td>
   <td align="center" width="120">Asset Name</td>
   <td align="center" width="120">Asset Group</td>
   <td align="center" width="120">Brand</td>
   <td align="center" width="30">Qty</td>
   <td align="center" width="40">UOM</td>
   <td align="center" width="200">Remark</td>
  </tr>
 </thead>
 <?php
  $i=1;
  $tqty=0;
  ?>
 @foreach ($data['details'] as $rows)
 <tbody>
  <tr>
   <td align="left" width="20">{{ $i++ }}</td>
   <td align="left" width="120">{{ $rows->asset_name }}</td>
   <td align="left" width="120">{{ $rows->asset_group }}</td>
   <td align="left" width="120">{{ $rows->brand }}</td>
   <td align="right" width="30">{{ $rows->qty }}</td>
   <td align="left" width="40">{{ $rows->uom_code }}</td>
   <td align="left" width="200">{{ $rows->remarks }}</td>
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
<p></p>
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