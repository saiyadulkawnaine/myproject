<h3 align="center">Subcontract Garments Production Report</h3>
<p align="center">{{ $txt }}</p>
<table border="1" cellspacing="0" cellpadding="1">
    <tr>
        <th width="40" class="text-center" align="center">SL</th>
        <th width="50" class="text-center" align="center">1<br/>Bnf<br/>Company</th>
        <th width="50" class="text-center" align="center">2<br/>Resp<br/>company</th>
        <th width="90" class="text-center" align="center">3<br/>ServiceProvider<br/>Company</th>
        <th width="80" class="text-center" align="center">4<br/>Buyer</th>
        <th width="80" class="text-center" align="center">5<br/>Style No</th>
        <th width="80" class="text-center" align="center">6<br/>Order No</th>
        <th width="70" class="text-center" align="center">7<br/>Ship Date</th>
        <th width="50" class="text-center" align="center">8<br/> Qty</th>
        <th width="70" class="text-center" align="center">9<br/>Recv<br/>Date</th>
        <th width="40" class="text-center" align="center">10<br/>yesterday</th>
    </tr>
    <?php
        $i=1;
       $totalQty=0;
    ?>
    @foreach ($gmtproduction as $key=>$orders)
    <tr>
        <th width="40" class="text-center" align="center">{{ $i++ }}</th>
        <th width="50" class="text-center" align="center">{{ $orders['company_code'] }}</th>
        <th width="50" class="text-center" align="center">{{ $orders['pcompany_code'] }}</th>
        <th width="90" class="text-center" align="center">{{ $orders['supplier_name'] }}</th>
        <th width="80" class="text-center" align="center">{{ $orders['buyer_name'] }}</th>
        <th width="80" class="text-center" align="center">{{ $orders['style_ref'] }}</th>
        <th width="80" class="text-center" align="center">{{ $orders['sale_order_no'] }}</th>
        <th width="70" class="text-center" align="center">{{ $orders['ship_date'] }}</th>
        <th width="50" class="text-center" align="right">{{ number_format($orders['qty'],0) }}</th>
        <th width="70" class="text-center" align="center">{{ $orders['qc_date'] }}</th>
        <th width="40" class="text-center" align="center">{{ $orders['sup_qty'] }}</th>
        <?php
        
       $totalQty+=$orders['qty'];
    ?>
</tr>
@endforeach
<tr>
    <th class="text-center" align="center" colspan="8"><strong>Total</strong></th>
    <th width="50" class="text-center" align="right">{{  number_format($totalQty,0) }}</th>
    <th width="70" class="text-center" align="center"></th>
    <th width="40" class="text-center" align="center"></th>
</tr>
</table>