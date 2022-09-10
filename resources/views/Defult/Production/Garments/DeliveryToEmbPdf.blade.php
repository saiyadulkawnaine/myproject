<table  cellspacing="0" cellpadding="2">
    <tr>
        <th colspan="3" align="center"><h4>Cut Panel Delivery To Embroidery</h4></th>
    </tr>
    <tr>
        <td width="160">&nbsp;&nbsp;&nbsp;&nbsp;To </td>
        <td width="160"></td>
        <td width="160"></td>
        <td width="160">Challan No: {{$prodgmtdlvtoemb['challan_no']}}</td>
    </tr>
    <tr>
        <td width="160">&nbsp;&nbsp;&nbsp;&nbsp;{{$prodgmtdlvtoemb['supplier_name']}}</td>
        <td width="160"></td>
        <td width="160"></td>
        <td width="160">Date: {{$prodgmtdlvtoemb['delivery_date']}}</td>
    </tr>
    <tr>
        <td width="320" colspan="1">&nbsp;&nbsp;&nbsp;&nbsp;{{$prodgmtdlvtoemb['supplier_address']}}</td>
        {{-- <td width="160"></td> --}}
        <td width="160"></td>
        <td width="160"></td>
    </tr>
    <tr>
        <td width="160"></td>
        <td width="160"></td>
        <td width="160"></td>
        <td width="160"></td>
    </tr>
</table>
<?php 
    $totalqty=0;
?>
<table cellspacing="0" cellpadding="2">
    @foreach($saved as $sale_order_id=>$row)
        <tr>
            <td width="160" align="left"><strong>&nbsp;&nbsp;&nbsp;&nbsp;Order No: {{ $podata[$sale_order_id]['sale_order_no'] }}</strong>
            </td>
            <td width="160" align="left"><strong>&nbsp;&nbsp;Style: {{$podata[$sale_order_id]['style_ref']}}</strong></td>
            <td width="160" align="left"><strong>Buyer: {{$podata[$sale_order_id]['buyer_name']}}</strong></td>
            <td width="160" align="left"><strong>Ship Date: {{$podata[$sale_order_id]['ship_date']}}</strong></td>
        </tr>
        @foreach($row as $color_id=>$datas)
        <tr>
            <td width="160" align="left"></td>
            <td width="320" align="left" colspan="3">&nbsp;&nbsp;<strong>Garment Color :{{$colordata[$color_id]}}</strong></td>
        </tr>
        <tr>
            <td width="160" align="left"></td>
            <td  width="160" align="left" colspan="3">
                <table cellspacing="0" cellpadding="2" border="1">
                    <thead> 
                        <tr>
                            <th width="50px"  class="text-center" align="center">#SL</th>
                            <th width="100px"  class="text-center" align="center">GMT Item</th>
                            <th width="80px"  class="text-center" align="center">Size</th>
                            <th width="100px"  class="text-center" align="center">Quantity</th>
                            <th width="80px"  class="text-center" align="center">Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i=1;
                        $gmtqty=0;
                        ?>
                        @foreach($datas as $data)
                        <tr>
                            <td width="50px"  class="text-center">{{ $i }}</td>
                            <td width="100px">{{ $data->item_description }}</td>
                            <td width="80px">{{ $data->size_name }}</td>
                            <td width="100px" align="right">{{number_format($data->qty,0)}}</td>
                            <td width="80px">{{ $data->uom_name }}</td>
                        </tr>
                        
                        <?php
                        $i++;
                        $gmtqty += $data->qty;
                        ?>
                        @endforeach
                    </tbody>
                    <tfoot>
                        {{-- <tr>
                            <td width="50px"  class="text-center"></td>
                            <td  colspan="2"><strong>Color Total  :</strong></td>
                            <td width="100px" align="right"><strong>{{ number_format($gmtqty,0) }}</strong></td>
                            <td width="80px"><strong>{{ $data->uom_name }}</strong></td>
                        </tr> --}}
                    </tfoot>
                </table>
            </td>
        </tr>
        <tr>
            <td width="160" align="left"></td>
            <td width="160" align="left" colspan="3">
                <table cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="50px" class="text-center"></td>
                        <td width="180px" colspan="2"align="right"><strong>Color Total:</strong></td>
                        <td width="100px" align="right"><strong>{{ number_format($gmtqty,0) }}</strong></td>
                        <td width="80px" align="left"><strong>{{ $data->uom_name }}</strong></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="160" align="left"></td>
            <td width="160" align="left"></td>
            <td width="160" align="left"></td>
            <td width="160" align="left"></td>
        </tr>
        <?php
            $totalqty+=$gmtqty;
        ?>
        @endforeach
        
    @endforeach
</table>
<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="160" align="left"></td>
        {{-- <td width="160" align="right"><strong>Grand Total&nbsp;:</strong></td>
        <td width="160" align="right" colspan="2"><strong>&nbsp;&nbsp;&nbsp; {{ number_format($totalqty,0) }}&nbsp;{{ $data->uom_name }}</strong></td> --}}
        <td width="160" align="left" colspan="3">
            <table cellspacing="0" cellpadding="2">
                <tr>
                    <td width="50px" class="text-center"></td>
                    <td width="180px" colspan="2"align="right"><strong>Grand Total:</strong></td>
                    <td width="100px" align="right"><strong>{{ number_format($totalqty,0) }}</strong></td>
                    <td width="80px" align="left"><strong>{{ $data->uom_name }}</strong></td>
                </tr>
            </table>
        </td>

    </tr>
</table>
<table cellspacing="0" cellpadding="2" >
    <tr>
        <td width="250" align="left"></td>
        <td width="130" align="left"></td>
        <td width="130" align="left"></td>
        <td width="130" align="left"></td>
    </tr>
    <tr>
        <td width="250" align="left"></td>
        <td width="130" align="left"></td>
        <td width="130" align="left"></td>
        <td width="130" align="left"></td>
    </tr>
    <tr>
        <td width="250" align="left"></td>
        <td width="130" align="left"></td>
        <td width="130" align="left"></td>
        <td width="130" align="left"></td>
    </tr>
    <tr>
        <td width="250" align="left"></td>
        <td width="130" align="left"></td>
        <td width="130" align="left"></td>
        <td width="130" align="left"></td>
    </tr>
    <tr>
        <td width="250" align="left"></td>
        <td width="130" align="left"></td>
        <td width="130" align="left"></td>
        <td width="130" align="left"></td>
    </tr>
    <tr>
        <td width="250" align="left">&nbsp;&nbsp;Received Cut Panel In Good, No Descrepancy<br/>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Receiver's Signature With Data & Seal</td>
        <td width="130" align="left">Cutting Input S .V </td>
        <td width="130" align="left">Cutting In-Charge</td>
        <td width="130" align="left">Authorised Signature</td>
    </tr>
</table>