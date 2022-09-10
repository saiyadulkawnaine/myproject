<br>
<table><tr><td>
<h3 style="text-align:center">THE CONTRACT HAS BEEN MADE UNDER THE FOLLOWING TERMS & CONDITIONS:</h3>
</td></tr></table>
<br>

<table><tr><td>
<ol>
    <li>SALES CONTRACT NO & DATE : {{$explcsc['local_lc_no']}}</li>
    <li>NAME AND ADDRESS OF THE APPLICANT : {{$explcsc['buyer']}} , {{ $buyerbranch['address'] }}</li>
    <li>APPLICANT'S BANK : {{$explcsc['buyers_bank']}}</li>
    <li>TERMS OF PAYMENT : {{ $explcsc['pay_term_id'] }}</li>
</ol>
</td></tr></table>

<table>
    <tr><td><h3>DESCRIPTION OF GOODS AND VALUE AS BELOW :</h3></td></tr>
</table>
<table border="1" cellspacing="0" cellpadding="2">
        <tr align="center">
            <td width="80px">PO #</td>
            <td width="80px">STYLE</td>
            <td width="100px">ITEM DESCRIPTION</td>
            <td width="80px">FABRICATION</td>
            <td width="80px">QTY</td>
            <td width="70px">FOB UNIT PRICE</td>
            <td width="80px">AMOUNT</td>
            <td width="80px">SHIPMENT DATE</td>
        </tr>
     <tbody>
      <?php 
      $i=1;
      ?>
        @foreach($orders as $order)
            <tr align="center">
                <td width="80px">{{ $order->sale_order_no }}</td>
                <td width="80px">{{ $order->style_ref }}
                {{-- <input type="hidden" name="sales_order_id[{{ $i }}]" id="sales_order_id{{ $i }}" value="{{ $order->id}}"/> --}}
                </td>
                <td width="100px">{{ $order->item_description }}</td>
                <td width="80px"></td>
                <td width="80px" align="right">{{ $order->qty }} </td>
                <td width="70px" align="right">{{ $order->rate }}</td>
                <td width="80px" align="right">{{ $order->amount }}</td>
                <td width="80px">{{ date('d-M-Y',strtotime($order->ship_date))  }}</td>
            </tr>
        <?php $i++; ?>
        @endforeach
     </tbody>
     <tfoot>
        <tr align="center">
            <td width="80px"></td>
            <td width="80px"></td>
            <td width="100px"></td>
            <td width="80px">Total</td>
            <td width="80px" align="right">{{ $orders->sum('qty') }} </td>
            <td width="70px"></td>
            <td width="80px" align="right">{{ $orders->sum('amount') }}</td>
            <td width="80px"></td>
        </tr>
    </tfoot>
</table>
