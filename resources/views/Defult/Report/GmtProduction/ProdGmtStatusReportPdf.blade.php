<h2 align="center">Beneficiary :{{ $bnfcompany }}</h2>
<h3 align="center">Garments Status Report</h3>
<h3>STYLE LEVEL</h3>
<table border="1" cellpadding="2">
    <tr>
        <th width="120" align="center"><strong>Buyer</strong></th>
        <th width="100" align="center"><strong>Style</strong></th>
        <th width="100" align="center"><strong>Dealing Merchant</strong></th>
        <th width="100" align="center"><strong>Gmt Item</strong></th>
        <th width="60" align="center"><strong>Order Qty</strong></th>
        <th width="60" align="center"><strong>Extra Cut Qty</strong></th>
        <th width="60" align="center"><strong>Plan Cut Qty</strong></th>
        <th width="60" align="center"><strong>Extra Cut%</strong></th>
    </tr>
    <?php
        $totalQty=0;
        $totalPlanCutQty=0;
        $totalExtraQty=0;
        $totalExtraPer=0;
    ?>
    @foreach ($rows as $row)
    <tr>
        <td width="120" align="left">{{ $row->buyer_name }}</td>
        <td width="100" align="left">{{ $row->style_ref }}</td>
        <td width="100" align="left">{{ $row->team_member_name }}</td>
        <td width="100" align="left">{{ $row->item_description }}</td>
        <td width="60" align="right">{{ number_format($row->qty,0) }}</td>
        <td width="60" align="right">{{ number_format($row->extra_qty,0) }}</td>
        <td width="60" align="right">{{ number_format($row->plan_cut_qty,0) }}</td>
        <td width="60" align="right">{{ number_format($row->extra_percent,0) }}</td>
    </tr>
    <?php
        $totalQty+=$row->qty;
        $totalPlanCutQty+=$row->plan_cut_qty;
        $totalExtraQty+=$row->extra_qty;
        $totalExtraPer=($totalExtraQty/$totalQty)*100;
    ?>
    @endforeach

    <tr>
        <td width="120" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"></td>
        <td width="100" align="center"><strong>Total</strong></td>
        <td width="60" align="right"><strong>{{ number_format($totalQty,0) }}</strong></td>
        <td width="60" align="right"><strong>{{ number_format($totalExtraQty,0) }}</strong></td>
        <td width="60" align="right"><strong>{{ number_format($totalPlanCutQty,0) }}</strong></td>
        <td width="60" align="right"><strong>{{ number_format($totalExtraPer,0) }}</strong></td>
    </tr>
</table>
<p></p>
<p></p>

@foreach ($datas as $item_account_id => $itemdata)
<p align="right"><strong>Garment Item: {{ $itemArr[$item_account_id]['item_description'] }}</strong><br>
@foreach ($itemdata as $color_id => $data)
<table cellpadding="1">
    <tr>
        <td align="left">&nbsp;&nbsp;<strong>Color:&nbsp;&nbsp; {{ $colorArr[$color_id] }}</strong></td>
        <td align="right">{{-- <strong>Garment Item:{{ $itemArr[$color_id]['item_description'] }}</strong> --}}</td>
    </tr>
    <tr>
        <td colspan="2">
            <table border="1" cellpadding="2">
            <?php
                $tStQty=0;
                $tStCutQty=0;
                $tStScrnReqQty=0;
                $tStScrnRcvQty=0;
                $tStEmbReqQty=0;
                $tStEmbRcvQty=0;
                $tStSewLineQty=0;
                $tStSewQty=0;
                $tStIronQty=0;
                $tStPolyQty=0;
                $tStCartonQty=0;
                $tStShipOutQty=0;
                $tStYetToShipQty=0;
            ?>
                <tr>
                    <td  align="left"><strong>Process</strong></td>
                    @foreach ($data as $sizewise)
                        <td align="center"><strong>{{ $sizewise->size_name }}</strong></td>
                    @endforeach
                    <td align="center"><strong>TOTAL</strong></td>
                </tr>
                <tr>
                    <td align="left">Order Qty</td>
                    @foreach ($data as $sizewise)
                    <td align="right">{{ $sizewise->qty }}</td>
                        <?php
                            $tStQty+=$sizewise->qty;
                        ?>
                    @endforeach
                    <td align="right">{{ $tStQty }}</td>
                </tr>
                <tr>
                    <td align="left">Cutting Qty</td>
                    @foreach ($data as $sizewise)
                        <?php
                            $cutting_color='';
                            if($sizewise->cut_qty < $sizewise->qty){
                                $cutting_color='LightPink';
                            }
                            $tStCutQty+=$sizewise->cut_qty;
                        ?>
                        <td align="right" style="background-color:{{$cutting_color}}">{{ $sizewise->cut_qty }}</td>
                    @endforeach
                    <td align="right">{{ $tStCutQty }}</td>
                </tr>
                <tr>
                    <td align="left">Screen Print Req</td>
                    @foreach ($data as $sizewise)
                        <td align="right">{{ $sizewise->req_scr_qty }}</td>
                        <?php
                            $tStScrnReqQty+=$sizewise->req_scr_qty;
                        ?>
                    @endforeach
                    <td align="right">{{ $tStScrnReqQty }}</td>
                </tr>
                <tr>
                    <td align="left">Screen Print Done</td>
                    @foreach ($data as $sizewise)
                    <?php
                        $scr_print_color='';
                        if($sizewise->rcv_scr_qty >=1 && $sizewise->rcv_scr_qty < $sizewise->req_scr_qty){
                            $scr_print_color='LightPink';
                        }
                        $tStScrnRcvQty+=$sizewise->rcv_scr_qty;
                    ?>
                        <td align="right" style="background-color:{{$scr_print_color}}">{{ $sizewise->rcv_scr_qty }}</td>
                    @endforeach
                    <td align="right">{{ $tStScrnRcvQty }}</td>
                </tr>
                <tr>
                    <td align="left">Embroidery Req</td>
                    @foreach ($data as $sizewise)
                        <td align="right">{{ $sizewise->req_emb_qty }}</td>
                        <?php
                            $tStEmbReqQty+=$sizewise->req_emb_qty;
                        ?>
                    @endforeach
                    <td  align="right">{{ $tStEmbReqQty }}</td>
                </tr>
                <tr>
                    <td align="left">Embroidery Done</td>
                    @foreach ($data as $sizewise)
                        <?php
                            $emb_color='';
                            if($sizewise->emb_rcv_qty >=1 && $sizewise->emb_rcv_qty < $sizewise->req_emb_qty){
                                $emb_color='LightPink';
                            }
                            $tStEmbRcvQty+=$sizewise->emb_rcv_qty;
                        ?>
                        <td align="right" style="background-color:{{$emb_color}}">{{ $sizewise->emb_rcv_qty }}</td>
                    @endforeach
                    <td  align="right">{{ $tStEmbRcvQty }}</td>
                </tr>
                <tr>
                    <td align="left">Line Input</td>
                    @foreach ($data as $sizewise)
                        <?php
                            $sewline_color='';
                            if($sizewise->sew_line_qty < $sizewise->qty){
                                $sewline_color='LightPink';
                            }
                            $tStSewLineQty+=$sizewise->sew_line_qty;
                        ?>
                        <td align="right" style="background-color:{{$sewline_color}}">{{ $sizewise->sew_line_qty }}</td>
                        
                    @endforeach
                    <td align="right">{{ $tStSewLineQty }}</td>
                </tr>
                <tr>
                    <td align="left">Sewing </td>
                    @foreach ($data as $sizewise)
                    <?php
                        $sew_color='';
                        if($sizewise->sew_qty < $sizewise->qty){
                            $sew_color='LightPink';
                        }
                        $tStSewQty+=$sizewise->sew_qty;
                    ?>
                    <td align="right" style="background-color:{{$sew_color}}">{{ $sizewise->sew_qty }}</td>
                    @endforeach
                    <td align="right">{{ $tStSewQty }}</td>
                </tr>
                <tr>
                    <td align="left">Iron</td>
                    @foreach ($data as $sizewise)
                    <?php
                        $iron_color='';
                        if($sizewise->iron_qty < $sizewise->qty){
                            $iron_color='LightPink';
                        }
                        $tStIronQty+=$sizewise->iron_qty;
                    ?>
                    <td align="right" style="background-color:{{$iron_color}}">{{ $sizewise->iron_qty }}</td>
                    @endforeach
                    <td align="right">{{ $tStIronQty }}</td>
                </tr>
                <tr>
                    <td align="left">Poly</td>
                    @foreach ($data as $sizewise)
                    <?php
                        $poly_color='';
                        if($sizewise->poly_qty < $sizewise->qty){
                            $poly_color='LightPink';
                        }
                        $tStPolyQty+=$sizewise->poly_qty;
                    ?>
                    <td align="right" style="background-color:{{$poly_color}}">{{ $sizewise->poly_qty }}</td>
                    @endforeach
                    <td align="right">{{ $tStPolyQty }}</td>
                </tr>
                <tr>
                    <td align="left">Carton</td>
                    @foreach ($data as $sizewise)
                    <?php
                        $carton_color='';
                        if($sizewise->carton_qty < $sizewise->qty){
                            $carton_color='LightPink';
                        }
                        $tStCartonQty+=$sizewise->carton_qty;
                    ?>
                    <td align="right" style="background-color:{{$carton_color}}">{{ $sizewise->carton_qty }}</td>
                    @endforeach
                    <td align="right">{{ $tStCartonQty }}</td>
                </tr>
                <tr>
                    <td align="left">Shipout</td>
                    @foreach ($data as $sizewise)
                    <?php
                        $shipout_color='';
                        if($sizewise->ship_out_qty < $sizewise->qty){
                            $shipout_color='LightPink';
                        }
                        $tStShipOutQty+=$sizewise->ship_out_qty;
                    ?>
                    <td align="right" style="background-color:{{$shipout_color}}">{{ $sizewise->ship_out_qty }}</td>
                    @endforeach
                    <td align="right">{{ $tStShipOutQty }}</td>
                </tr>
                <tr>
                    <td align="left">Yet to Ship</td>
                    @foreach ($data as $sizewise)
                        <td align="right">{{ $sizewise->ship_out_qty-$sizewise->qty }}</td>
                        
                    @endforeach
                    <td align="right">{{ $tStShipOutQty-$tStQty }}</td>
                </tr>
            </table> 
        </td>
    </tr>
</table>
@endforeach
</p>
@endforeach

<p></p>
<p></p>
<p></p>
<h3>ORDER LEVEL</h3>
@foreach ($orderdtl as $sales_order_id=>$order)
<?php
    $tOrderQty=0;
    $tOrderPlanCutQty=0;
    $tOrderExtraQty=0;
    $tOrderExtraPer=0;
?>
<h3 align="center">Producing Company: {{ $salesorderArr[$sales_order_id]['produced_company_name'] }}</h3>
    <table border="1" cellpadding="2" nobr="true">
        <tr>
            <th width="60" align="center"><strong>Buyer</strong></th>
            <th width="100" align="center"><strong>Order No</strong></th>
            <th width="80" align="center"><strong>Dealing Merchant</strong></th>
            {{-- <th width="80" align="center"><strong>Gmt Item</strong></th> --}}
            <th width="50" align="center"><strong>Order Qty</strong></th>
            <th width="50" align="center"><strong>Allowed Extra Cut</strong></th>
            <th width="50" align="center"><strong>Plan Cut Qty</strong></th>
            <th width="50" align="center"><strong>Extra Cut%</strong></th>
            <th width="70" align="center"><strong>Ship Date</strong></th>
            <th width="70" align="center"><strong>Original Ship Date</strong></th>
        </tr>
        <tbody>
        <tr nobr="true">
            <td width="60" align="left">{{ $salesorderArr[$sales_order_id]['buyer_code'] }}</td>
            <td width="100" align="left">{{ $salesorderArr[$sales_order_id]['sale_order_no'] }}</td>
            <td width="80" align="left">{{ $salesorderArr[$sales_order_id]['team_member_name'] }}</td>
            {{-- <td width="80" align="left">{{ $salesorderArr[$sales_order_id]['item_description'] }}</td> --}}
            <td width="50" align="right">{{ $salesorderArr[$sales_order_id]['qty'] }}</td>
            <td width="50" align="right">{{ $salesorderArr[$sales_order_id]['extra_qty'] }}</td>
            <td width="50" align="right">{{ $salesorderArr[$sales_order_id]['plan_cut_qty']}}</td>
            <td width="50" align="right">{{ $salesorderArr[$sales_order_id]['extra_percent'] }}</td>
            <th width="70" align="center">{{ $salesorderArr[$sales_order_id]['ship_date'] }}</th>
            <th width="70" align="center">{{ $salesorderArr[$sales_order_id]['org_ship_date'] }}</th>
        </tr>
        </tbody>
    </table> 
    <p></p>
    @foreach ($order as $item_account_id => $orderitem)
        <p align="right"><strong>Garment Item: {{ $salesordergmtItemArr[$item_account_id]['item_description'] }}</strong><br>
        @foreach ($orderitem as $color_id => $orddata)
            <table cellpadding="1">
                <tr>
                    <td align="left">&nbsp;&nbsp;<strong>Color:&nbsp;&nbsp; {{ $salesordercolorArr[$color_id] }}</strong></td>
                    <td align="right">{{-- <strong>Garment Item:{{ $itemArr[$color_id]['item_description'] }}</strong> --}}</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table border="1" cellpadding="2">
                        <?php
                            $tOrderQty=0;
                            $tOrderCutQty=0;
                            $tOrderScrnReqQty=0;
                            $tOrderScrnRcvQty=0;
                            $tOrderEmbReqQty=0;
                            $tOrderEmbRcvQty=0;
                            $tOrderSewLineQty=0;
                            $tOrderSewQty=0;
                            $tOrderIronQty=0;
                            $tOrderPolyQty=0;
                            $tOrderCartonQty=0;
                            $tOrderShipOutQty=0;
                            $tOrderYetToShipQty=0;
                        ?>
                            <tr>
                                <td  align="left"><strong>Process</strong></td>
                                @foreach ($orddata as $sizewiseorder)
                                    <td align="center"><strong>{{ $sizewiseorder->size_name }}</strong></td>
                                @endforeach
                                <td align="center"><strong>TOTAL</strong></td>
                            </tr>
                            <tr>
                                <td align="left">Order Qty</td>
                                @foreach ($orddata as $sizewiseorder)
                                <td align="right">{{ $sizewiseorder->qty }}</td>
                                    <?php
                                        $tOrderQty+=$sizewiseorder->qty;
                                    ?>
                                @endforeach
                                <td align="right">{{ $tOrderQty }}</td>
                            </tr>
                            <tr>
                                <td align="left">Cutting Qty</td>
                                @foreach ($orddata as $sizewiseorder)
                                    <?php
                                        $order_cut_color='';
                                        if($sizewiseorder->cut_qty < $sizewiseorder->qty){
                                            $order_cut_color='LightPink';
                                        }
                                        $tOrderCutQty+=$sizewiseorder->cut_qty;
                                    ?>
                                    <td align="right" style="background-color:{{$order_cut_color}}">{{ $sizewiseorder->cut_qty }}</td>
                                @endforeach
                                <td align="right">{{ $tOrderCutQty }}</td>
                            </tr>
                            <tr>
                                <td align="left">Screen Print Req</td>
                                @foreach ($orddata as $sizewiseorder)
                                    <?php
                                        $tOrderScrnReqQty+=$sizewiseorder->req_scr_qty;
                                    ?>
                                    <td align="right">{{ $sizewiseorder->req_scr_qty }}</td>
                                @endforeach
                                <td align="right">{{ $tOrderScrnReqQty }}</td>
                            </tr>
                            <tr>
                                <td align="left">Screen Print Done</td>
                                @foreach ($orddata as $sizewiseorder)
                                    <?php
                                        $order_scrrcv_color='';
                                        if($sizewiseorder->rcv_scr_qty >=1 && $sizewiseorder->rcv_scr_qty < $sizewiseorder->req_scr_qty){
                                            $order_scrrcv_color='LightPink';
                                        }
                                        $tOrderScrnRcvQty+=$sizewiseorder->rcv_scr_qty;
                                    ?>
                                    <td align="right" style="background-color:{{$order_scrrcv_color}}">{{ $sizewiseorder->rcv_scr_qty }}</td>
                                @endforeach
                                <td align="right">{{ $tOrderScrnRcvQty }}</td>
                            </tr>
                            <tr>
                                <td align="left">Embroidery Req</td>
                                @foreach ($orddata as $sizewiseorder)
                                    <?php
                                        $tOrderEmbReqQty+=$sizewise->req_emb_qty;
                                    ?>
                                    <td align="right">{{ $sizewise->req_emb_qty }}</td>
                                @endforeach
                                <td  align="right">{{ $tOrderEmbReqQty }}</td>
                            </tr>
                            <tr>
                                <td align="left">Embroidery Done</td>
                                @foreach ($orddata as $sizewiseorder)
                                    <?php
                                        $order_embrcv_color='';
                                        if($sizewiseorder->emb_rcv_qty >=1 && $sizewiseorder->emb_rcv_qty < $sizewiseorder->req_emb_qty){
                                            $order_embrcv_color='LightPink';
                                        }
                                        $tOrderEmbRcvQty +=$sizewiseorder->emb_rcv_qty;
                                    ?>
                                    <td align="right" style="background-color:{{$order_embrcv_color}}">{{ $sizewiseorder->emb_rcv_qty }}</td>
                                @endforeach
                                <td  align="right">{{ $tOrderEmbRcvQty }}</td>
                            </tr>
                            <tr>
                                <td align="left">Line Input</td>
                                @foreach ($orddata as $sizewiseorder)
                                    <?php
                                        $order_sewline_color='';
                                        if($sizewiseorder->sew_line_qty < $sizewiseorder->qty){
                                            $order_sewline_color='LightPink';
                                        }
                                        $tOrderSewLineQty+=$sizewiseorder->sew_line_qty;
                                    ?>
                                    <td align="right" style="background-color:{{$order_sewline_color}}">{{ $sizewiseorder->sew_line_qty }}</td>
                                @endforeach
                                <td align="right">{{ $tOrderSewLineQty }}</td>
                            </tr>
                            <tr>
                                <td align="left">Sewing </td>
                                @foreach ($orddata as $sizewiseorder)
                                    <?php
                                        $order_sew_color='';
                                        if($sizewiseorder->sew_qty < $sizewiseorder->qty){
                                            $order_sew_color='LightPink';
                                        }
                                        $tOrderSewQty+=$sizewiseorder->sew_qty;
                                    ?>
                                    <td align="right" style="background-color:{{$order_sew_color}}">{{ $sizewiseorder->sew_qty }}</td>
                                @endforeach
                                <td align="right">{{ $tOrderSewQty }}</td>
                            </tr>
                            <tr>
                                <td align="left">Iron</td>
                                @foreach ($orddata as $sizewiseorder)
                                <?php
                                    $order_iron_color='';
                                    if($sizewiseorder->iron_qty < $sizewiseorder->qty){
                                        $order_iron_color='LightPink';
                                    }
                                    $tOrderIronQty+=$sizewiseorder->iron_qty;
                                ?>
                                <td align="right" style="background-color:{{$order_iron_color}}">{{ $sizewiseorder->iron_qty }}</td>
                                @endforeach
                                <td align="right">{{ $tOrderIronQty }}</td>
                            </tr>
                            <tr>
                                <td align="left">Poly</td>
                                @foreach ($orddata as $sizewiseorder)
                                <?php
                                    $order_poly_color='';
                                    if($sizewiseorder->poly_qty < $sizewiseorder->qty){
                                        $order_poly_color='LightPink';
                                    }
                                    $tOrderPolyQty+=$sizewiseorder->poly_qty;
                                ?>
                                <td align="right" style="background-color:{{$order_poly_color}}">{{ $sizewiseorder->poly_qty }}</td>
                                @endforeach
                                <td align="right">{{ $tOrderPolyQty }}</td>
                            </tr>
                            <tr>
                                <td align="left">Carton</td>
                                @foreach ($orddata as $sizewiseorder)
                                <?php
                                    $order_carton_color='';
                                    if($sizewiseorder->carton_qty < $sizewiseorder->qty){
                                        $order_carton_color='LightPink';
                                    }
                                    $tOrderCartonQty+=$sizewiseorder->carton_qty;
                                ?>
                                <td align="right" style="background-color:{{$order_carton_color}}">{{ $sizewiseorder->carton_qty }}</td>
                                @endforeach
                                <td align="right">{{ $tOrderCartonQty }}</td>
                            </tr>
                            <tr>
                                <td align="left">Shipout</td>
                                @foreach ($orddata as $sizewiseorder)
                                <?php
                                    $order_shipout_color='';
                                    if($sizewiseorder->ship_out_qty < $sizewiseorder->qty){
                                        $order_shipout_color='LightPink';
                                    }
                                    $tOrderShipOutQty+=$sizewiseorder->ship_out_qty;
                                ?>
                                <td align="right" style="background-color:{{$order_shipout_color}}">{{ $sizewiseorder->ship_out_qty }}</td>
                                @endforeach
                                <td align="right">{{ $tOrderShipOutQty }}</td>
                            </tr>
                            <tr>
                                <td align="left">Yet to Ship</td>
                                @foreach ($orddata as $sizewiseorder)
                                    <td align="right">{{ $sizewiseorder->ship_out_qty-$sizewiseorder->qty }}</td>
                                    
                                @endforeach
                                <td align="right">{{ $tOrderShipOutQty-$tOrderQty }}</td>
                            </tr>
                        </table> 
                    </td>
                </tr>
            </table>
        @endforeach
        </p>
    @endforeach
@endforeach
<p></p>
<p></p>
<?php
    $i=1;
    //$sizewiseTotalArr=[];
?>
<h3 align="center">Packing Ratio</h3>
@foreach ($pkgratiodtl as $style_pkg_id=>$pkgdtl)
<?php
    $sizewiseTotalArr=[];
?>
    <p><strong>{{$i++}}.Assortment Name:{{ $pkgratioAssortnameArr[$style_pkg_id]['assortment_name'] }}</strong></p>
    <table border="1" nobr="true">
        <tr>
            <th width="100" align="center"><strong>Gmt Item</strong></th>
            <th width="70" align="center"><strong>Color</strong></th>
            @foreach ($pkgdtl as $key=>$size_name)
            <th width="40" align="center"><strong>{{ $pkgratiosizeArr[$style_pkg_id][$key] }}</strong></th>
            @endforeach
                
            <th width="50" align="center"><strong>Total</strong></th>
        </tr>
        @foreach ($pkgratioGmtItemArr as $pkg_item_id=>$pkgitem)
        <tbody>
            <tr>
                <td width="100" align="left">{{ $pkgratioGmtItemArr[$pkg_item_id]['item_description'] }}</td>
                <td   style="padding-left: 0px margin-left:0px"><table border="1" style="padding-left: 0px margin-left:0px">@foreach ($pkgratiocolorArr as $pkg_color_id=>$pkgcolor)
                        <tr>
                            <td width="70" style="padding-left: 0px margin-left:0px">{{ $pkgcolor }}</td>
                            <?php 
                                $sizewiseTotalPkgQty=0;
                            ?>
                            @foreach ($pkgdtl as $size_id=>$pkgsize)
                            <?php 
                                $pkg_qty=isset($pkgcolorsizeArr[$style_pkg_id][$pkg_item_id][$pkg_color_id][$size_id])?$pkgcolorsizeArr[$style_pkg_id][$pkg_item_id][$pkg_color_id][$size_id]:0;
                            ?>
                                <td style="padding-left: 0px margin-left:0px" width="40" align="center">{{ $pkg_qty }}</td>
                                <?php 
                                $sizewiseTotalPkgQty+=$pkg_qty;
                                $sizewiseTotalArr[$size_id]=isset($sizewiseTotalArr[$size_id])?$sizewiseTotalArr[$size_id]+=$pkg_qty:$pkg_qty;
                            ?>
                            @endforeach
                            <td width="50" align="center">{{ $sizewiseTotalPkgQty }}</td>
                        </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        </tbody>
        @endforeach
        <tfoot>
            <tr>
                <th width="100" align="center"><strong>Total</strong></th>
                <th width="70" align="center"></th>
                @foreach ($pkgdtl as $key=>$size_name)
                <th width="40" align="center"><strong>{{ $sizewiseTotalArr[$key] }}</strong></th>
                @endforeach 
                <th width="50" align="center"><strong>{{ array_sum( $sizewiseTotalArr) }}</strong></th>
            </tr>
        </tfoot>
    </table>
    <p></p>
@endforeach