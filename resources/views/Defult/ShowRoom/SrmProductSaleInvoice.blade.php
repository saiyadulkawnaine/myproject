<div  style="width: 292px; text-align: center; border: 1px solid #ccc">
<p><strong>Al-Mahee Fashion LLC</strong><br/>
New Industrial Area,<br/>
Fashion Street (Beside Factorymart),<br/>
P.O BOX # 20775<br/>
Ajman, UAE
</p>
<p>
VAT NO:<br/>
Invoice No:{{ $invoice->invoice_no}}<br/>
Date:{{ $invoice->invoice_date}}<br/>
Time:{{ $invoice->invoice_time}}<br/>
Customer:{{ $invoice->customer_name}}
</p>
<hr/>
<table border="0">
  <tr>
    <td>#</td>
    <td>Item</td>
    <td>Qty</td>
    <td>Rate</td>
    <td>Amount</td>
  </tr>
  <?php
  $i=1;
  ?>
  @foreach($product as $row)
  <tr>
    <td>{{$i}}</td>
    <td>{{$row->decs}}</td>
    <td align="right">{{$row->display_qty}}</td>
    <td align="right">{{$row->display_rate}}</td>
    <td align="right">{{$row->display_amount}}</td>
  </tr>
  <?php
  $i++;
  ?>
  
  @endforeach
  <tr>
    <td style="border-top: 1px solid #000000" colspan="2">Sub Total:</td>
    <td style="border-top: 1px solid #000000" align="right">{{$invoice->qty}}</td>
    <td style="border-top: 1px solid #000000" align="right"> </td>
    <td style="border-top: 1px solid #000000" align="right">{{$invoice->amount}}</td>
  </tr>
  <tr>
    <td style="border-top: 1px solid #000000" colspan="4">Vat:</td>
    <td style="border-top: 1px solid #000000" align="right">{{$invoice->vat}}</td>
  </tr>
  <tr>
    <td style="border-top: 1px solid #000000" colspan="4">S.TAX:</td>
    <td style="border-top: 1px solid #000000" align="right">{{$invoice->stax}}</td>
  </tr>
  <tr>
    <td style="border-top: 1px solid #000000" colspan="4">Total:</td>
    <td style="border-top: 1px solid #000000" align="right">{{$invoice->gross_amount}}</td>
  </tr>
  <tr>
    <td style="border-top: 1px solid #000000" colspan="4">Discount:</td>
    <td style="border-top: 1px solid #000000" align="right">{{$invoice->discount_amount}}</td>
  </tr>
  <tr>
    <td style="border-top: 1px solid #000000" colspan="4">Net Pay:</td>
    <td style="border-top: 1px solid #000000" align="right">{{$invoice->net_paid_amount}}</td>
  </tr>
</table>
</div>