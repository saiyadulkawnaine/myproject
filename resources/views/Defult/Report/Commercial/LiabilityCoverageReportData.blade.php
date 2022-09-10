        <table  border="1">
        <thead>
        <tr align="center" style="background-color: #DBDCDB ; color: #000;font-weight: bold">
        <th width="70" colspan="6" class="text-center">Order In hand</th>
        <th width="80" colspan="2" class="text-center">LC / Sales Contract</th>
        <th width="60" colspan="2" class="text-center">Back to Back</th>
        <th width="60" colspan="2" class="text-center">Packing Credit</th>
        <th width="60" colspan="2" class="text-center">Doc Purchase</th>
        <th width="60" class="text-center" rowspan="2" >Total Liability</th>
        <th width="60" colspan="2" class="text-center">Shipment Status</th>
        <th width="60" colspan="5" class="text-center">Security</th>

        </tr>
        <tr style="background-color: #DBDCDB ; color: #000;font-weight: bold">
        <th width="70" class="text-center">SL</th>

        <th width="90" class="text-center">Buyer</th>
        <th width="50" class="text-center">Order Qty</th>
        <th width="80" class="text-center">Rate/ Pcs</th>
        <th width="80" class="text-center">Amount</th>
        <th width="100" class="text-center">Plan Ship Date</th>
        <th width="80" class="text-center">File No</th>
        <th width="80" class="text-center">Amount</th>
        <th width="60" class="text-center">Particulars</th>
        <th width="60" class="text-center">Amount</th>
        <th width="60" class="text-center">Particulars</th>
        <th width="60" class="text-center">Amount</th>
        <th width="60" class="text-center">Particulars</th>
        <th width="60" class="text-center">Amount</th>

        <th width="60" class="text-center">Qty</th>
        <th width="60" class="text-center">Amount</th>
        <th width="60" class="text-center">Doc In Process</th>
        <th width="60" class="text-center">Un- Realized Value</th>
        <th width="60" class="text-center">Yet to Ship Amount</th>
        <th width="60" class="text-center">Total Security</th>
        <th width="60" class="text-center">Comments</th>
        </tr>
        </thead>
        <?php
        $i=1;
        ?>
        @foreach ($datas as $data)
        <tr>
        <td width="70" class="text-center" rowspan="4">{{$i}}</td>
        <td width="50" class="text-center" rowspan="4">{{$data->buyer_name}}</td>

        <td width="50" align="right" rowspan="4"><a href="javascript:void(0)" onclick="MsLiabilityCoverageReport.orderQtyAmtPopUp({{$data->file_no}})">{{number_format($data->so_qty,0,'.',',')}}</a></td>
        <td width="80" align="right" rowspan="4">{{ number_format($data->so_rate,2)}}</td>
        <td width="80" align="right" rowspan="4"><a href="javascript:void(0)" onclick="MsLiabilityCoverageReport.orderQtyAmtPopUp({{$data->file_no}})">{{number_format($data->so_amount,2)}}</a></td>
        <td width="100" class="text-center" rowspan="4">{{date('d-M-y',strtotime($data->last_delivery_date))}}</td>
        <td width="80" class="text-center" rowspan="4">{{$data->file_no}}</td>
        <td width="80" align="right" rowspan="4"><a href="javascript:void(0)" onclick="MsLiabilityCoverageReport.lcscQtyAmtPopUp({{$data->file_no}})">{{number_format($data->lc_amount,2)}}</a></td>

        <td width="60">BTB Limit</td>
        <td width="60" align="right">{{number_format($data->limit_btb_open,2)}}</td>
        <td width="60">PC Limit</td>
        <td width="60" align="right">{{number_format($data->limit_pc_taken,2)}}</td>
        <td width="60">Limit</td>
        <td width="60" align="right">{{number_format($data->limit_doc_pur,2)}}</td>
        <td width="60" align="right" rowspan="4">{{number_format($data->tot_liab_amount,2)}}</td>
        <td width="60" align="right" rowspan="4"><a href="javascript:void(0)" onclick="MsLiabilityCoverageReport.shipQtyAmtPopUp({{$data->file_no}})">{{ number_format($data->sh_qty,2)}}</a></td>
        <td width="60" align="right" rowspan="4"><a href="javascript:void(0)" onclick="MsLiabilityCoverageReport.shipQtyAmtPopUp({{$data->file_no}})">{{ number_format($data->sh_amount,2)}}</a></td>
        <td width="60" align="right" rowspan="4">{{ number_format($data->doc_in_process,2)}}</td>
        <td width="60" align="right" rowspan="4">{{ number_format($data->un_rlz_amount,2)}}</td>
        <td width="60" align="right" rowspan="4"><a href="javascript:void(0)" onclick="MsLiabilityCoverageReport.shipQtyAmtPopUp({{$data->file_no}})">{{ number_format($data->yet_to_ship_amount,2)}}</a></td>
        <td width="60" align="right" rowspan="4">{{ number_format($data->security,2)}}</td>
        <td width="60" class="text-center" rowspan="4">{{ $data->comments }}</td>
        </tr>
        <tr>
        <td width="60">BTB Opened</td>
        <td width="60"  align="right"> <a href="javascript:void(0)" onclick="MsLiabilityCoverageReport.btbopenQtyAmtPopUp({{$data->file_no}})">{{number_format($data->btb_open_amount,2)}}</a></td>
        <td width="60">PC Taken</td>
        <td width="60" align="right"><a href="javascript:void(0)" onclick="MsLiabilityCoverageReport.pctakenQtyAmtPopUp({{$data->file_no}})">{{number_format($data->pc_taken_mount,2)}}</a></td>
        <td width="60">Taken</td>
        <td width="60" align="right"><a href="javascript:void(0)" onclick="MsLiabilityCoverageReport.docpurQtyAmtPopUp({{$data->file_no}})">{{number_format($data->doc_taken_amount,2)}}</a></td>



        </tr>
        <tr>
        <td width="60">Yet to Open</td>
        <td width="60"  align="right">{{number_format($data->yet_btb_open,2)}}</td>
        <td width="60">Yet to Avail</td>
        <td width="60" align="right">{{number_format($data->yet_pc_taken,2)}}</td>
        <td width="60">Yet to Purchase</td>
        <td width="60" align="right">{{number_format($data->yet_doc_pur,2)}}</td>


        </tr>
        <tr>
        <td width="60">Current Liability</td>
        <td width="60" align="right"> <a href="javascript:void(0)" onclick="MsLiabilityCoverageReport.btbadjustQtyAmtPopUp({{$data->file_no}})">{{number_format($data->btb_liab_amount,2)}}</a></td>
        <td width="60">Current Liability</td>
        <td width="60" align="right"><a href="javascript:void(0)" onclick="MsLiabilityCoverageReport.pcadjustQtyAmtPopUp({{$data->file_no}})">{{number_format($data->pc_liab_amount,2)}}</a></td> 
        <td width="60">Current Liability</td>
        <td width="60" align="right"><a href="javascript:void(0)" onclick="MsLiabilityCoverageReport.docadjustQtyAmtPopUp({{$data->file_no}})">{{number_format($data->doc_liab_amount,2)}}</a></td>


        </tr>
        <?php
        $i++;
        ?>
        @endforeach
        <tr style="background-color: #B1F8B7 ; color: #000;font-weight: bold">
        <td width="70" class="text-center" rowspan="4" colspan="2" >Total</td>
        

        <td width="50" align="right" rowspan="4">{{number_format($datas->sum('so_qty'),0,'.',',')}}</td>
        <td width="80" align="right" rowspan="4"></td>
        <td width="80" align="right" rowspan="4">{{number_format($datas->sum('so_amount'),2)}}</td>
        <td width="100" class="text-center" rowspan="4"></td>
        <td width="80" class="text-center" rowspan="4"></td>
        <td width="80" align="right" rowspan="4">{{number_format($datas->sum('lc_amount'),2)}}</td>

        <td width="60">BTB Limit</td>
        <td width="60" align="right">{{number_format($datas->sum('limit_btb_open'),0,'.',',')}}</td>
        <td width="60">PC Limit</td>
        <td width="60" align="right">{{number_format($datas->sum('limit_pc_taken'),0,'.',',')}}</td>
        <td width="60">Limit</td>
        <td width="60" align="right">{{number_format($datas->sum('limit_doc_pur'),0,'.',',')}}</td>
        <td width="60" align="right" rowspan="4">{{number_format($datas->sum('tot_liab_amount'),2)}}</td>
        <td width="60" align="right" rowspan="4">{{ number_format($datas->sum('sh_qty'),2)}}</td>
        <td width="60" align="right" rowspan="4">{{ number_format($datas->sum('sh_amount'),2)}}</td>
        <td width="60" align="right" rowspan="4">{{ number_format($datas->sum('doc_in_process'),2)}}</td>
        <td width="60" align="right" rowspan="4">{{ number_format($datas->sum('un_rlz_amount'),2)}}</td>
        <td width="60" align="right" rowspan="4">{{ number_format($datas->sum('yet_to_ship_amount'),2)}}</td>
        <td width="60" align="right" rowspan="4">{{ number_format($datas->sum('security'),2)}}</td>
        <td width="60" class="text-center" rowspan="4"></td>
        </tr>
        <tr style="background-color: #B1F8B7 ; color: #000;font-weight: bold">
        <td width="60">BTB Opened</td>
        <td width="60"  align="right"> {{number_format($datas->sum('btb_open_amount'),2)}}</td>
        <td width="60">PC Taken</td>
        <td width="60" align="right">{{number_format($datas->sum('pc_taken_mount'),2)}}</td>
        <td width="60">Taken</td>
        <td width="60" align="right">{{number_format($datas->sum('doc_taken_amount'),2)}}</td>



        </tr>
        <tr style="background-color: #B1F8B7 ; color: #000;font-weight: bold">
        <td width="60">Yet to Open</td>
        <td width="60"  align="right">{{number_format($datas->sum('yet_btb_open'),2)}}</td>
        <td width="60">Yet to Avail</td>
        <td width="60" align="right">{{number_format($datas->sum('yet_pc_taken'),2)}}</td>
        <td width="60">Yet to Purchase</td>
        <td width="60" align="right">{{number_format($datas->sum('yet_doc_pur'),2)}}</td>


        </tr>
        <tr style="background-color: #B1F8B7 ; color: #000;font-weight: bold">
        <td width="60">Current Liability</td>
        <td width="60" align="right"> {{number_format($datas->sum('btb_liab_amount'),2)}}</td>
        <td width="60">Current Liability</td>
        <td width="60" align="right">{{number_format($datas->sum('pc_liab_amount'),2)}}</td> 
        <td width="60">Current Liability</td>
        <td width="60" align="right">{{number_format($datas->sum('doc_liab_amount'),2)}}</td>


        </tr>
        </table>





