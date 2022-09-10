<h2 align="center">{{ $data['master']->buyer_name }}</h2>
<table cellspacing="0" cellpadding="2" border="1">
 <tr align="center">
  <th width="30px" class="text-center"><strong>SL</strong></th>
  <th width="90px" class="text-center"><strong>Style</strong></th>
  <th width="100px" class="text-center"><strong>Buyer</strong></th>
  <th width="80px" class="text-center"><strong>Order No</strong></th>
  <th width="70px" class="text-center"><strong>GMT Item</strong></th>
  <th width="60px" class="text-center"><strong>GMT Color</strong></th>
  <th width="60px" class="text-center"><strong>Size</strong></th>
  <th width="50px" class="text-center"><strong>DO Qty</strong></th>
  <th width="50px" class="text-center"><strong>Rate</strong></th>
  <th width="50px" class="text-center"><strong>Amount</strong></th>
 </tr>
 <?php
   $i=1;
   $tqty=0;
   $tammount=0;
  ?>
 @foreach ($data['details'] as $rows)
 <tbody>
  <tr align="center">
   <td width="30px" class="text-center">{{ $i++ }}</td>
   <td width="90px" class="text-center">{{ $rows->style_ref }}</td>
   <td width="100px" class="text-center">{{ $rows->buyer_name }}</td>
   <td width="80px" class="text-center">{{ $rows->sale_order_no }}</td>
   <td width="70px">{{ $rows->item_description }}</td>
   <td width="60px">{{ $rows->color_name }}</td>
   <td width="60px">{{ $rows->size_name }}</td>
   <td width="50px" align="right">{{ number_format($rows->item_qty,2)}}</td>
   <td width="50px" align="right">{{ number_format($rows->item_rate,4) }}</td>
   <td width="50px" align="right">{{ number_format($rows->item_amount,2) }}</td>
  </tr>
 </tbody>
 <?php
     $tqty+=$rows->item_qty;
     $tammount+=$rows->item_amount;
    ?>
 @endforeach
</table>
<table cellspacing="0" cellpadding="2">
 <tr>
  <td width="150" align="left"></td>
  <td width="170" align="left"></td>
  <td width="125" align="left"><strong>Total</strong></td>
  <td width="100" align="right"><strong>{{ number_format($tqty,2) }}</strong></td>
  <td width="100" align="right"><strong>{{ number_format($tammount,2) }}</strong></td>
  <td width="200"></td>
 </tr>
</table>