
    
    <div class="row middle" style="margin-left:0px; margin-right: 0px">
        <div class="col-sm-3">
            <table  border="1" style="margin: 0 auto;">
            <thead>
            <tr style="background-color: #EAE9E9">
            <th width="80" class="text-center" colspan="3"><a href="javascript:void(0)" onclick="MsTodayInventoryReport.yarn()">Yarn Store</a></th>
            </tr>
            <tr style="background-color: #EAE9E9">
            <th width="80" class="text-left">Particulars</th>
            <th width="112" class="text-center">Qty</th>
            <th width="130" class="text-center">Value</th>
            </tr>
            </thead>
            <tr>
            <td width="80" > Opening </td>
            <td width="112" align="right"  > {{$yarn_arr['yarn_opening_qty']}} </td>
            <td width="130" align="right" > {{$yarn_arr['yarn_opening_amount']}} </td>
            </tr>
            <tr>
            <td width="80" > Receive </td>
            <td width="112" align="right"  > <a href="javascript:void(0)" onclick="MsTodayInventoryReport.yarnRcv()">{{$yarn_arr['yarn_rcv_qty']}} </a></td>
            <td width="130" align="right" > <a href="javascript:void(0)" onclick="MsTodayInventoryReport.yarnRcv()">{{$yarn_arr['yarn_rcv_amount']}}</a> </td>
            </tr>
            <tr>
            <td width="80" > Issue </td>
            <td width="112" align="right"> <a href="javascript:void(0)" onclick="MsTodayInventoryReport.yarnIsu()">{{$yarn_arr['yarn_isu_qty']}}</a> </td>
            <td width="130" align="right" > <a href="javascript:void(0)" onclick="MsTodayInventoryReport.yarnIsu()">{{$yarn_arr['yarn_isu_amount']}} </a></td>
            </tr>
            <tr>
            <td width="80" > Stock </td>
            <td width="112" align="right"  > {{$yarn_arr['yarn_stock_qty']}} </td>
            <td width="130" align="right" > {{$yarn_arr['yarn_stock_amount']}} </td>
            </tr>
            </table>
            <br/>
            @foreach ($dyechems as $row)

            <table  border="1" style="margin: 0 auto;">
            <thead>
            <tr style="background-color: #EAE9E9">
            <th width="80" class="text-center" colspan="3"><a href="javascript:void(0)" onclick="MsTodayInventoryReport.dyechem({{$row->id}})">Dyes & Chemical Store ({{$row->company_code}})</a></th>
            </tr>
            <tr style="background-color: #EAE9E9">
            <th width="80" class="text-left">Particulars</th>
            <th width="112" align="right" class="text-center">Qty</th>
            <th width="130" class="text-center">Value</th>
            </tr>
            </thead>
            <tr>
            <td width="80" > Opening </td>
            <td width="112" align="right" > {{ $row->dyechem_opening_qty}} </td>
            <td width="130" align="right" > {{$row->dyechem_opening_amount}} </td>
            </tr>
            <tr>
            <td width="80" > Receive </td>
            <td width="112" align="right" > <a href="javascript:void(0)" onclick="MsTodayInventoryReport.dyechemRcv({{$row->id}})">{{$row->dyechem_rcv_qty}}</a> </td>
            <td width="130" align="right" > <a href="javascript:void(0)" onclick="MsTodayInventoryReport.dyechemRcv({{$row->id}})">{{$row->dyechem_rcv_amount}}</a> </td>
            </tr>
            <tr>
            <td width="80" > Issue </td>
            <td width="112" align="right" > <a href="javascript:void(0)" onclick="MsTodayInventoryReport.dyechemIsu({{$row->id}})">{{$row->dyechem_isu_qty}}</a> </td>
            <td width="130" align="right" > <a href="javascript:void(0)" onclick="MsTodayInventoryReport.dyechemIsu({{$row->id}})">{{$row->dyechem_isu_amount}}</a> </td>
            </tr>
            <tr>
            <td width="80" > Stock </td>
            <td width="112" align="right" > {{$row->dyechem_stock_qty}} </td>
            <td width="130" align="right" > {{$row->dyechem_stock_amount}} </td>
            </tr>
            </table>
            <br/>
            @endforeach 




            @foreach ($subcondyeings as $row)

            <table  border="1" style="margin: 0 auto;">
            <thead>
            <tr style="background-color: #EAE9E9">
            <th width="80" class="text-center" colspan="3"><a href="javascript:void(0)" onclick="MsTodayInventoryReport.dyeingsubcon()">Dyeing Subcontract Store</a> </th>
            </tr>
            <tr style="background-color: #EAE9E9">
            <th width="80" class="text-left">Particulars</th>
            <th width="112" class="text-center">Qty</th>
            <th width="130" class="text-center">Value</th>
            </tr>
            </thead>
            <tr>
            <td width="80" > Opening </td>
            <td width="112" align="right" > {{ $row->opening_qty}} </td>
            <td width="130" align="right" > {{$row->opening_amount}} </td>
            </tr>
            <tr>
            <td width="80" > Receive </td>
            <td width="112" align="right" > {{$row->rcv_qty}} </td>
            <td width="130" align="right" > {{$row->rcv_amount}} </td>
            </tr>
            <tr>
            <td width="80" > Issue </td>
            <td width="112" align="right" > {{$row->total_adjusted}} </td>
            <td width="130" align="right" > {{$row->total_adjusted_amount}} </td>
            </tr>
            <tr>
            <td width="80" > Stock </td>
            <td width="112" align="right" > {{$row->stock_qty}} </td>
            <td width="130" align="right" > {{$row->stock_value}} </td>
            </tr>
            </table>
            <br/>
            @endforeach 

            @foreach ($subconaops as $row)

            <table  border="1" style="margin: 0 auto;">
            <thead>
            <tr style="background-color: #EAE9E9">
            <th width="80" class="text-center" colspan="3"><a href="javascript:void(0)" onclick="MsTodayInventoryReport.aopsubcon()">AOP Subcontract Store</a> </th>
            </tr>
            <tr style="background-color: #EAE9E9">
            <th width="80" class="text-left">Particulars</th>
            <th width="112" class="text-center">Qty</th>
            <th width="130" class="text-center">Value</th>
            </tr>
            </thead>
            <tr>
            <td width="80" > Opening </td>
            <td width="112" align="right" > {{ $row->opening_qty}} </td>
            <td width="130" align="right" > {{$row->opening_amount}} </td>
            </tr>
            <tr>
            <td width="80" > Receive </td>
            <td width="112" align="right" > {{$row->rcv_qty}} </td>
            <td width="130" align="right" > {{$row->rcv_amount}} </td>
            </tr>
            <tr>
            <td width="80" > Issue </td>
            <td width="112" align="right" > {{$row->total_adjusted}} </td>
            <td width="130" align="right" > {{$row->total_adjusted_amount}} </td>
            </tr>
            <tr>
            <td width="80" > Stock </td>
            <td width="112" align="right" > {{$row->stock_qty}} </td>
            <td width="130" align="right" > {{$row->stock_value}} </td>
            </tr>
            </table>
            <br/>
            @endforeach 

            @foreach ($subconknitings as $row)

            <table  border="1" style="margin: 0 auto;">
            <thead>
            <tr style="background-color: #EAE9E9">
            <th width="80" class="text-center" colspan="3"><a href="javascript:void(0)" onclick="MsTodayInventoryReport.knitingsubcon()">Kniting Subcontract Store</a> </th>
            </tr>
            <tr style="background-color: #EAE9E9">
            <th width="80" class="text-left">Particulars</th>
            <th width="112" class="text-center">Qty</th>
            <th width="130" class="text-center">Value</th>
            </tr>
            </thead>
            <tr>
            <td width="80" > Opening </td>
            <td width="112" align="right" > {{ $row->opening_qty}} </td>
            <td width="130" align="right" > {{$row->opening_amount}} </td>
            </tr>
            <tr>
            <td width="80" > Receive </td>
            <td width="112" align="right" > {{$row->rcv_qty}} </td>
            <td width="130" align="right" > {{$row->rcv_amount}} </td>
            </tr>
            <tr>
            <td width="80" > Issue </td>
            <td width="112" align="right" > {{$row->total_adjusted}} </td>
            <td width="130" align="right" > {{$row->total_adjusted_amount}} </td>
            </tr>
            <tr>
            <td width="80" > Stock </td>
            <td width="112" align="right" > {{$row->stock_qty}} </td>
            <td width="130" align="right" > {{$row->stock_value}} </td>
            </tr>
            </table>
            <br/>
            @endforeach 
        </div>
        <div class="col-sm-3">
            @foreach ($greyfabs as $row)
            <table  border="1" style="margin: 0 auto;">
            <thead>
            <tr style="background-color: #EAE9E9">
            <th width="80" class="text-center" colspan="3"><a href="javascript:void(0)" onclick="MsTodayInventoryReport.greyfab({{$row->id}})">Grey Fabric Store ({{$row->company_code}})</a></th>
            </tr>
            <tr style="background-color: #EAE9E9">
            <th width="80" class="text-left">Particulars</th>
            <th width="112" class="text-center">Qty</th>
            <th width="130" class="text-center">Value</th>
            </tr>
            </thead>
            <tr>
            <td width="80" > Opening </td>
            <td width="112" align="right" > {{ $row->greyfab_opening_qty}} </td>
            <td width="130" align="right" > {{$row->greyfab_opening_amount}} </td>
            </tr>
            <tr>
            <td width="80" > Receive </td>
            <td width="112" align="right" > <a href="javascript:void(0)" onclick="MsTodayInventoryReport.greyfabRcv({{$row->id}})">{{$row->greyfab_rcv_qty}}</a> </td>
            <td width="130" align="right" > <a href="javascript:void(0)" onclick="MsTodayInventoryReport.greyfabRcv({{$row->id}})">{{$row->greyfab_rcv_amount}}</a> </td>
            </tr>
            <tr>
            <td width="80" > Issue </td>
            <td width="112" align="right" > <a href="javascript:void(0)" onclick="MsTodayInventoryReport.greyfabIsu({{$row->id}})">{{$row->greyfab_isu_qty}}</a> </td>
            <td width="130" align="right" > <a href="javascript:void(0)" onclick="MsTodayInventoryReport.greyfabIsu({{$row->id}})">{{$row->greyfab_isu_amount}} </a></td>
            </tr>
            <tr>
            <td width="80" > Stock </td>
            <td width="112" align="right" > {{$row->greyfab_stock_qty}} </td>
            <td width="130" align="right" > {{$row->greyfab_stock_amount}} </td>
            </tr>
            </table>
            <br/>
            @endforeach 


            @foreach ($gmts as $row)

            <table  border="1" style="margin: 0 auto;">
            <thead>
            <tr style="background-color: #EAE9E9">
            <th width="80" class="text-center" colspan="3">Finished Garment Store ({{$row->company_code}}) </th>
            </tr>
            <tr style="background-color: #EAE9E9">
            <th width="80" class="text-left">Particulars</th>
            <th width="112" class="text-center">Qty</th>
            <th width="130" class="text-center">Value</th>
            </tr>
            </thead>
            <tr>
            <td width="80" > Opening </td>
            <td width="112" align="right" > {{ $row->opening_qty}} </td>
            <td width="130" align="right" >  </td>
            </tr>
            <tr>
            <td width="80" > Finish </td>
            <td width="112" align="right" > {{$row->car_qty}} </td>
            <td width="130" align="right" >  </td>
            </tr>
            <tr>
            <td width="80" > Delivery </td>
            <td width="112" align="right" > {{$row->exf_qty}} </td>
            <td width="130" align="right" >  </td>
            </tr>
            <tr>
            <td width="80" > Stock </td>
            <td width="112" align="right"> {{$row->stock_qty}} </td>
            <td width="130" align="right" > </td>
            </tr>
            </table>
            <br/>
            @endforeach 
        </div>
        <div class="col-sm-6">
            <table  border="1" style="margin: 0 auto;">
            <thead>
            <tr style="background-color: #EAE9E9">
            <th width="80" class="text-center" colspan="5">General Store </th>
            </tr>
            <tr style="background-color: #EAE9E9">
            <th width="130" class="text-left">Item Category</th>
            <th width="110" class="text-center">Opening (Taka)</th>
            <th width="110" class="text-center">Receive (Taka)</th>
            <th width="110" class="text-center">Issue (Taka)</th>
            <th width="110" class="text-center">Stock (Taka)</th>
            </tr>
            </thead>
            <?php
            $tot_gen_open_amount=0;
            $tot_gen_receive_amount=0;
            $tot_gen_issue_amount=0;
            $tot_gen_stock_amount=0;
            ?>
            @foreach ($generals as $row)
            <tr>
            <td width="130" align="left">
            <a href="javascript:void(0)" onclick="MsTodayInventoryReport.general({{$row->itemcategory_id}})">{{$row->itemcategory_name}}
            </a>
            </td>
            <td width="110" align="right">{{number_format($row->opening_amount,0)}}</td>
            <td width="110" align="right"><a href="javascript:void(0)" onclick="MsTodayInventoryReport.generalRcv({{$row->itemcategory_id}})">{{number_format($row->receive_amount,0)}}</a></td>
            <td width="110" align="right"><a href="javascript:void(0)" onclick="MsTodayInventoryReport.generalIsu({{$row->itemcategory_id}})">{{number_format($row->issue_amount,0)}}</a></td>
            <td width="110" align="right">{{number_format($row->stock_amount,0)}}</td>
            </tr>
            <?php
            $tot_gen_open_amount+=$row->opening_amount;
            $tot_gen_receive_amount+=$row->receive_amount;
            $tot_gen_issue_amount+=$row->issue_amount;
            $tot_gen_stock_amount+=$row->stock_amount;
            ?>
            @endforeach 
            <tr style="background-color: #EAE9E9; font-weight: bold;">
            <td width="130" align="right">Total</td>
            <td width="110" align="right">{{number_format($tot_gen_open_amount,0)}}</td>
            <td width="110" align="right"><a href="javascript:void(0)" onclick="MsTodayInventoryReport.generalRcv('')">{{number_format($tot_gen_receive_amount,0)}}</a></td>
            <td width="110" align="right"><a href="javascript:void(0)" onclick="MsTodayInventoryReport.generalIsu('')">{{number_format($tot_gen_issue_amount,0)}}</a></td>
            <td width="110" align="right">{{number_format($tot_gen_stock_amount,0)}}</td>
            </tr>
            </table>
        </div>
    </div>
    
    

