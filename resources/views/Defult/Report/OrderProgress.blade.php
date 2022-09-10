<style>
  #opordersumcolcom{
    font-weight: bold;
    /*background:#021344;
    color: #FFFFFF;*/
  }
  #opyarnsumcolcom{
    background:#2cf4f1;
    font-weight: bold;
    color: #FFFFFF;
  }
  #opknitsumcolcom{
    background:#021344;
    font-weight: bold;
    color: #FFFFFF;
  }
  #opdyeingsumcolcom{
    background:#2cf4f1;
    font-weight: bold;
    color: #FFFFFF;
  }
  #opcutsumcolcom{
    background:#021344;
    font-weight: bold;
    color: #FFFFFF;
  }
  #opsewsumcolcom{
    background:#2cf4f1;
    font-weight: bold;
    color: #FFFFFF;
  }
   #opironsumcolcom{
    background:#021344;
    font-weight: bold;
    color: #FFFFFF; 
  }
  #oppolysumcolcom{
    background:#2cf4f1;
    font-weight: bold;
    color: #FFFFFF;
  }
  #opfinsumcolcom{
    background:#021344;
    font-weight: bold;
    color: #FFFFFF;
  }
  #opshipsumcolcom{
    background:#2cf4f1;
    font-weight: bold;
    color: #FFFFFF;
  }
  #opinvsumcolcom{
    background:#021344;
    font-weight: bold;
    color: #FFFFFF;
  }


  #opordersumcolbuy{
    font-weight: bold;
    /*background:#021344;
    color: #FFFFFF;*/
  }
  #opyarnsumcolbuy{
    background:#2cf4f1;
    font-weight: bold;
    color: #FFFFFF;
  }
  #opknitsumcolbuy{
    background:#021344;
    font-weight: bold;
    color: #FFFFFF;
  }
  #opdyeingsumcolbuy{
    background:#2cf4f1;
    font-weight: bold;
    color: #FFFFFF;
  }
  #opcutsumcolbuy{
    background:#021344;
    font-weight: bold;
    color: #FFFFFF;
  }
  #opsewsumcolbuy{
    background:#2cf4f1;
    font-weight: bold;
    color: #FFFFFF;
  }
  #opironsumcolbuy{
    background:#021344;
    font-weight: bold;
    color: #FFFFFF;
  }
  #oppolysumcolbuy{
    background:#2cf4f1;
    font-weight: bold;
    color: #FFFFFF;
  }
  #opfinsumcolbuy{
    background:#021344;
    font-weight: bold;
    color: #FFFFFF;
  }
  #opshipsumcolbuy{
    background:#2cf4f1;
    font-weight: bold;
    color: #FFFFFF;
  }
  #opinvsumcolbuy{
    background:#021344;
    font-weight: bold;
    color: #FFFFFF;
  }
</style>
<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true" style="padding:2px">
        <div class="easyui-tabs" style="width:100%;height:100%; border:none" id="orderprogressTab">
            <div title="Order Wise" style="padding:1px" data-options="selected:true">
                <table id="orderprogressTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'company_code',halign:'center'" width="60">1<br/>Company</th>
                            <th data-options="field:'produced_company_code',halign:'center'" width="60">2<br/>Producing <br/> Unit</th>
                            <th data-options="field:'team_name',halign:'center'" width="80" formatter="MsOrderProgress.formatteamleader">3<br/>Team <br/>Leader</th>
                            <th data-options="field:'sale_order_receive_date',halign:'center'" width="80">4<br/>Order <br/>Recv Date</th>
                            <th data-options="field:'delivery_date',halign:'center'" width="80">5<br/>Delivery Date</th>
                            <th data-options="field:'lead_time',halign:'center'" width="60">6<br/>Lead Time</th>
                            <th data-options="field:'team_member_name',halign:'center'" width="100" formatter="MsOrderProgress.formatdlmerchant">7<br/>Dealing <br/>Merchant</th>
                            <th data-options="field:'delivery_month',halign:'center'" width="60">8<br/>Delv <br/>Month</th>
                            <th data-options="field:'buyer_name',halign:'center'" width="60">9<br/>Buyer</th>
                            <th data-options="field:'buying_agent_name',halign:'center'"  width="70" formatter="MsOrderProgress.formatbuyingAgent">10<br/>Buying House</th>
                            <th data-options="field:'style_ref',halign:'center'" width="80" formatter="MsOrderProgress.formatopfiles">11<br/>Style No</th>

                            <th data-options="field:'sale_order_no',halign:'center'" width="80">12<br/>Order No</th>
                            <th data-options="field:'lc_sc_no',halign:'center'" width="80" formatter="MsOrderProgress.formatlcsc">13<br/>LC/SC Receive</th>
                            <th data-options="field:'department_name',halign:'center'" width="80">14<br/>Prod. Dept</th>
                           
                            <th data-options="field:'flie_src',halign:'center',align:'center'" formatter="MsOrderProgress.formatimage" width="30">15<br/>Image</th>
                            <th data-options="field:'qty',halign:'center'" nowrap="false" width="80" align="right" formatter="MsOrderProgress.formatorderqty">16<br/>Order Qty <br/>(Pcs)</th>
                            <th data-options="field:'rate',halign:'center'" width="100" align="right">17<br/>Price (Pcs)</th>
                            <th data-options="field:'amount',halign:'center'" width="100" align="right" formatter="MsOrderProgress.formatorderqty">18<br/>Selling Value</th>
                            <th data-options="field:'target_qty',halign:'center'" width="100" align="right">19<br/>Target Qty</th> 
                            <th data-options="field:'smv',halign:'center'" width="100" align="right">20<br/>SMV</th>
                            <th data-options="field:'sewing_effi_per',halign:'center'" width="100" align="right">21<br/>Efficiency%</th>
                            <th data-options="field:'booked_minute',halign:'center'" width="100" align="right" formatter="MsOrderProgress.formatorderqty">22<br/>Minute Booked</th>
                            <th data-options="field:'cut_to_ship_days',halign:'center'" width="100" align="right">23<br/>Cut To Ship Days</th>
                            
                            <th data-options="field:'dyed_yarn_rq',halign:'center'" align="right" width="100" formatter="MsOrderProgress.formadyedyarnrq">24<br/>Y/D Req</th>
                            <th data-options="field:'grey_yarn_issue_qty_for_dye',halign:'center'"  align ="right" width="100" formatter="MsOrderProgress.formatgreyyarntodye">25<br/>G. Yrn. Delv</th>

                             <th data-options="field:'grey_yarn_issue_for_dye_bal',halign:'center',styler:MsOrderProgress.greyyarnissuefordyebal"  align ="right" width="100">26<br/>G. Yrn. Delv.<br/> Pending </th>

                            <th data-options="field:'dyed_yarn_rcv_qty',halign:'center'" align="right" width="80" formatter="MsOrderProgress.formatdyedyarnrcv">27<br/>D/Y Recv</th>
                            <th data-options="field:'dyed_yarn_bal_qty',halign:'center'" align="right" width="100">28<br/>D/Y Bal</th>
                            <th data-options="field:'yarn_req',halign:'center'" align="right" width="100" formatter="MsOrderProgress.formatyarnrq">29<br/>Yarn Required</th>
                            <th data-options="field:'poyarnlc_qty',halign:'center'" align="right" width="100">30<br/>Yarn LC<br/> Qty</th>
                            <th data-options="field:'poyarnlc_qty_bal',halign:'center'" align="right" width="100">31<br/>Yarn LC<br/> Balance</th>
                            <th data-options="field:'yarn_rcv',halign:'center'" align="right" width="100" formatter="MsOrderProgress.formatyarnrq">32<br/>Yarn Receive</th>
                            <th data-options="field:'yarn_rcv_bal',halign:'center',styler:MsOrderProgress.yarnrcvbal" align="right" width="100">33<br/>Yarn Receive <br/> Balance</th>

                            <th data-options="field:'inh_yarn_isu_qty',halign:'center'" align="right" width="100" formatter="MsOrderProgress.formatyarnisuinh">34<br/>Issued - Inhouse</th>
                            <th data-options="field:'out_yarn_isu_qty',halign:'center'" align="right" width="80" formatter="MsOrderProgress.formatyarnisuout">35<br/>Issued - Subcon</th>
                            <th data-options="field:'yarn_req_bal',halign:'center'" align="right" width="100">36<br/>Balance</th>
                            <th data-options="field:'knit_qty',halign:'center'" align="right" width="100" formatter="MsOrderProgress.formatknit">37<br/>Knitted</th>

                            <th data-options="field:'knit_bal',halign:'center',styler:MsOrderProgress.knitbal" align="right" width="100">38<br/>Knit Bal</th>

                            <th data-options="field:'batch_qty',halign:'center'" align="right" width="100">39<br/>Batch Qty</th>
                            <th data-options="field:'batch_bal',halign:'center' ,styler:MsOrderProgress.batchbal" align="right" width="100">40<br/>Batch Bal</th>
                            <th data-options="field:'dyeing_qty',halign:'center'" align="right" width="100">41<br/>Dyeing Qty</th>
                            <th data-options="field:'dyeing_bal',halign:'center',styler:MsOrderProgress.dyeingbal" align="right" width="100">42<br/>Dyeing Bal</th>
                            <th data-options="field:'fin_fab_req',halign:'center'" align="right" width="100">43<br/>Fin Fab. Req</th>
                            <th data-options="field:'finish_qty',halign:'center'" align="right" width="100">44<br/>Fin Fab. Qty</th>
                            <th data-options="field:'finish_bal',halign:'center',styler:MsOrderProgress.finishbal" align="right" width="100">45<br/>Fin Fab. Bal</th>
                            <th data-options="field:'plan_cut_qty',halign:'center'" align="right" width="80">46<br/>Plan Cut Qty</th>
                            <th data-options="field:'cut_qty',halign:'center'" align="right" width="100" formatter="MsOrderProgress.formatcutqty">47<br/>Cut Qty</th>
                            <th data-options="field:'cut_bal',halign:'center',styler:MsOrderProgress.cutbal" align="right" width="100">48<br/>Cut Bal</th>
                            <th data-options="field:'req_scr_qty',halign:'center'" align="right" width="80"> 49<br/>Screen Print<br/> Required </th>
                            <th data-options="field:'snd_scr_qty',halign:'center'" align="right" width="80" formatter="MsOrderProgress.formatscrqty">50<br/>Sent To <br/> Screen Print </th>
                            <th data-options="field:'snd_scr_qty_bal',halign:'center',styler:MsOrderProgress.sndscrqtybal" align="right" width="80" >51<br/>Sending<br/> Balance</th>
                            <th data-options="field:'rcv_scr_qty',halign:'center'" align="right" width="100" formatter="MsOrderProgress.formatscrqty">52<br/>Recv from <br/> Screen Print </th>
                            <th data-options="field:'bal_scr_qty',halign:'center',styler:MsOrderProgress.balscrqty" align="right" width="100">53<br/>Screen <br/>Print WIP </th>

                           
                            <th data-options="field:'sew_line_qty',halign:'center'" align="right" width="100">54<br/>Line Input</th>
                            <th data-options="field:'sew_line_bal',halign:'center',styler:MsOrderProgress.sewlinebal" align="right" width="100">55<br/>Line Input WIP</th>
                            <th data-options="field:'sew_qty',halign:'center'" align="right" width="100" formatter="MsOrderProgress.formatsewqty">56<br/>Sew Qty</th>
                            <th data-options="field:'sew_bal',halign:'center',styler:MsOrderProgress.sewbal" align="right" width="80">57<br/>Sew Qty WIP</th>
                            <th data-options="field:'iron_qty',halign:'center'" align="right" width="80">58<br/>Iron <br/>Done Qty</th>
                            <th data-options="field:'iron_bal_qty',halign:'center'" align="right" width="80">59<br/>Iron <br/>Balance</th>
                            <th data-options="field:'iron_bal',halign:'center',styler:MsOrderProgress.ironbal" align="right" width="80">60<br/>Iron Qty WIP</th>
                            <th data-options="field:'poly_qty',halign:'center'" align="right" width="80">61<br/>Poly <br/>Done Qty</th>
                            <th data-options="field:'poly_bal_qty',halign:'center'" align="right" width="80">62<br/>Poly <br/>Balance</th>
                            <th data-options="field:'poly_bal',halign:'center',styler:MsOrderProgress.polybal" align="right" width="80">63<br/>Poly Qty WIP</th>
                            <th data-options="field:'car_qty',halign:'center'" align="right" width="100" formatter="MsOrderProgress.formatcarqty">64<br/>Carton Qty</th>
                            <th data-options="field:'car_bal',halign:'center',styler:MsOrderProgress.carbal" align="right" width="80">65<br/>Carton WIP</th>

                            <th data-options="field:'insp_pass_qty',halign:'center'" align="right" width="80" formatter="MsOrderProgress.formatinspqty">66<br/>Inspection <br/>Pass Qty</th>
                            <th data-options="field:'insp_re_check_qty',halign:'center'" align="right" width="80" formatter="MsOrderProgress.formatinspqty">67<br/>Inspection <br/>Re-check Qty</th>
                            <th data-options="field:'insp_faild_qty',halign:'center'" align="right" width="80" formatter="MsOrderProgress.formatinspqty">68<br/>Inspection <br/>Failed Qty</th>

                            <th data-options="field:'ship_qty',halign:'center'" width="100" align="right" formatter="MsOrderProgress.formatexfqty">69<br/>Ship <br/>Out Qty</th>
                            <th data-options="field:'ship_value',halign:'center'" width="100" align="right">70<br/>Ship <br/>Out Value</th>

                            <th data-options="field:'yet_to_ship_qty',halign:'center',styler:MsOrderProgress.yettoshipqty" width="100" align="right">71<br/>Yet to <br/>Ship Qty</th>
                            <th data-options="field:'yet_to_ship_value',halign:'center'" width="100" align="right">72<br/>Yet to <br/>Ship Value</th>

                            <th data-options="field:'ci_qty',halign:'center'" align="right"width="80">73<br/>CI Qty</th>
                            <th data-options="field:'ci_qty_bal',halign:'center',styler:MsOrderProgress.ciqtybal" align="right" width="100">74<br/>CI Bal Qty</th>
                            <th data-options="field:'ci_amount',halign:'center'" align="right" width="100">75<br/>CI Value</th>
                            <th data-options="field:'ci_amount_bal',halign:'center'" align="right" width="100">76<br/>CI Value Bal</th>
                            <th data-options="field:'sale_order_id',halign:'center'" align="right" width="100">77<br/>ID</th>

                        </tr>
                    </thead>
                </table>
            </div>
            <div title="Company Wise" style="padding:1px">
                <table id="orderprogresscomTbl" style="width:100%">
                    
                </table>
            </div>
            <div title="Buyer Wise" style="padding:1px">
                <table id="orderprogressbuyTbl" style="width:100%">
                </table>
            </div>
        </div>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="orderprogressFrm">
        <div id="container">
             <div id="body">
               <code>
                   	<div class="row">
                        <div class="col-sm-4">Buyer </div>
                        <div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}</div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4"> Company</div>
                        <div class="col-sm-8">
                            {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                        </div>
                    </div> 
                    <div class="row middle">
                        <div class="col-sm-4">Pro. Company</div>
                        <div class="col-sm-8">
                            {!! Form::select('produced_company_id', $company,'',array('id'=>'produced_company_id')) !!}
                        </div>
                    </div>    
                    <div class="row middle">
                        <div class="col-sm-4">Style Ref</div>
                        <div class="col-sm-8">
                            <input type="text" name="style_ref" id="style_ref" onDblClick="MsOrderProgress.openOrdStyleWindow()" placeholder=" Double Click" readonly />
                            <input type="hidden" name="style_id" id="style_id" value=""/>
                        </div>
                    </div>
                    <!-- <div class="row middle">
                        <div class="col-sm-4">Job No </div>
                        <div class="col-sm-8"><input type="text" name="job_no" id="job_no" /></div>
                    </div> -->
                    <div class="row middle">
                        <div class="col-sm-4">Dl.Marchant</div>
                        <div class="col-sm-8">
                            <input type="text" name="team_member_name" id="team_member_name" onDblClick="MsOrderProgress.openTeammemberDlmWindow()" placeholder=" Double Click" readonly/>
                            <input type="hidden" name="factory_merchant_id" id="factory_merchant_id" value=""/>
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Ship Date </div>
                        <div class="col-sm-4" style="padding-right:0px">
                            <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                        </div>
                        <div class="col-sm-4" style="padding-left:0px">
                            <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Receive Date </div>
                        <div class="col-sm-4" style="padding-right:0px">
                            <input type="text" name="receive_date_from" id="receive_date_from" class="datepicker" placeholder="From" />
                        </div>
                        <div class="col-sm-4" style="padding-left:0px">
                            <input type="text" name="receive_date_to" id="receive_date_to" class="datepicker"  placeholder="To" />
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Order By </div>
                        <div class="col-sm-8">{!! Form::select('sort_by', $sortby,'1',array('id'=>'sort_by')) !!}</div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Status </div>
                        <div class="col-sm-8">{!! Form::select('order_status', $status,'1',array('id'=>'order_status')) !!}</div>
                    </div>
              </code>
           </div>
        </div>
        <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" plain="true" id="save" onClick="MsOrderProgress.get()">Show</a>
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" plain="true" id="save" onClick="MsOrderProgress.getCom()">Company</a>
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" plain="true" id="save" onClick="MsOrderProgress.getBuy()">Buyer</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsOrderProgress.resetForm('orderprogressFrm')" >Reset</a>
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" plain="true" id="save" onClick="MsOrderProgress.showExcel('orderprogressTbl','orderprogressTbl')">XLS</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" onclick="MsOrderProgress.getsummery()">Summery</a>
        </div>

      </form>
    </div>
{{--     <div data-options="region:'east',border:true,collapsed:true,hideCollapsedContent:false,title:'Summary'" style="width:70%; padding:2px">
        <div id="summeryTblFt" >
            <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" onclick="MsOrderProgress.getsummery()">Show</a>
        </div>
        <div id="orderprogressSummeryContainer"></div>
    </div> --}}
</div>
{{-- Summery --}}
<div id="orderprogressSummeryWindow" class="easyui-window" title="Order Progress Summery Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="orderprogressSummeryContainer" style="padding:2px;">

    </div>
</div>


{{-- Dealing Merchant --}}
<div id="dlmerchantWindow" class="easyui-window" title="Merchandiser Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="dealmctinfoTbl" style="width:100%;height:250px">
        <thead>
            <tr>
                <th data-options="field:'name'" width="120">Name</th>
                <th data-options="field:'date_of_join'" width="80px">Date Of Join </th>
                <th data-options="field:'last_education'" width="100px">Last Education</th>
                <th data-options="field:'contact'" width="100px">Contact</th>
                <th data-options="field:'experience'" width="100px">Experience</th>
                <th data-options="field:'address'" width="350px">Address</th>
            </tr>
        </thead>
    </table>
</div>
{{-- Buying Agent --}}
<div id="buyagentwindow" class="easyui-window" title="Buying Agents Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="buyagentTbl" style="width:500px">
        <thead>
            <tr>
                <th data-options="field:'id'" width="40">ID</th>
                <th data-options="field:'buyer_name'" width="180px">Buyer Name </th>
                <th data-options="field:'branch_name'" width="180px">Branch Name</th>
                <th data-options="field:'contact_person'" width="180px">Contact Person</th>
                <th data-options="field:'email'" width="180px">Email</th>
                <th data-options="field:'designation'" width="180px">Designation</th>
                <th data-options="field:'address'" width="250px">Address</th>
            </tr>
        </thead>
    </table>
</div>

<div id="oplcscwindow" class="easyui-window" title="Dyed Yarn Received" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="oplcscTbl" style="width:500px">
        <thead>
            <tr>
                <th data-options="field:'buyer_name'" width="100px">Buyer </th>
                <th data-options="field:'lc_sc_no'" width="100px">LC/SC No </th>
                <th data-options="field:'lc_sc_date'" width="100px">LC/SC Date </th>
                <th data-options="field:'lc_sc_value'" width="100px" align="right">LC Value </th>
                <th data-options="field:'currency_code'" width="100px">Currency </th>
                <th data-options="field:'file_no'" width="100px">File Number </th>
                <th data-options="field:'lc_nature'" width="100px">LC Nature </th>
                <th data-options="field:'pay_term'" width="100px">Pay Term </th>
                <th data-options="field:'inco_term'" width="130px">Inco Term </th>
                <th data-options="field:'remarks'" width="100px">Remarks</th>
            </tr>
        </thead>
    </table>
</div>

{{-- File Src --}}
<div id="opfilesrcwindow" class="easyui-window" title="Style Uploaded Files" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="opfilesrcTbl" style="width:500px">
        <thead>
            <tr>
                <th data-options="field:'id'" width="30">ID</th>
                <th data-options="field:'file_src'" width="180px">File Source </th>
                <th data-options="field:'original_name'" formatter="MsOrderProgress.formatShowOpFile" width="250px">Original Name</th>
            </tr>
        </thead>
    </table>
</div>

<div id="orderProgressImageWindow" class="easyui-window" title="Image" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:500px;height:500px;padding:2px;">
    <img id="orderProgressImageWindowoutput" src=""/>
</div>

<div id="oporderqtywindow" class="easyui-window" title="Order Qty" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="oporderqtyTbl" style="width:500px">
        <thead>
            <tr>
            <th data-options="field:'buyer_name',halign:'center'" width="150">Buyer</th>
            <th data-options="field:'style_ref',halign:'center'" width="100">Style No</th>
            <th data-options="field:'sale_order_no',halign:'center'" width="100">Order No</th>
            <th data-options="field:'department_name',halign:'center'" width="80">Prod. Dept</th>
            <th data-options="field:'item_description',halign:'center'" width="80">GMT. Item</th>
            <th data-options="field:'item_complexity',halign:'center'" width="80">Item Complexity</th>
            <th data-options="field:'country_name',halign:'center'" width="80">Country</th>
            <th data-options="field:'color_name',halign:'center'" width="80">GMT. Color</th>
            <th data-options="field:'size_name',halign:'center'" width="60">GMT. Size</th>

            <th data-options="field:'qty',halign:'center'" nowrap="false" width="80" align="right">Order Qty <br/>(Pcs)</th>
            <th data-options="field:'rate',halign:'center'" width="100" align="right">Price (Pcs)</th>
            <th data-options="field:'amount',halign:'center'" width="100" align="right">Selling Value</th>
            <th data-options="field:'smv',halign:'center'" width="60" align="right">SMV</th>
            <th data-options="field:'booked_minute',halign:'center'" width="80" align="right">Minute Booked</th>
            </tr>
        </thead>
    </table>
</div>



<div id="opdyedyarnrqwindow" class="easyui-window" title="Dyed Yarn Required" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="opdyedyarnrqTbl" style="width:500px">
        <thead>
            <tr>
                <th data-options="field:'buyer_name'" width="100px">Buyer</th>
                <th data-options="field:'style_ref'" width="100px">Style</th>
                <th data-options="field:'sale_order_no'" width="100px">Sales Order No </th>
                <th data-options="field:'fabric_des'" width="100px">Fabrication </th>
                <th data-options="field:'fabriclooks'" width="100px">Fabric Looks </th>
                <th data-options="field:'item_description'" width="100px">GMT Item </th>
                <th data-options="field:'gmt_color_name'" width="100px">GMT Color </th>
                <th data-options="field:'yarn_color_name'" width="100px">Yarn Color </th>
                <th data-options="field:'dyed_yarn_rq'" width="100px" align="right">Qty</th>
                <th data-options="field:'measurment'" width="100px">Measurment </th>
                <th data-options="field:'feeder'" width="100px">Feeder </th>
                
            </tr>
        </thead>
    </table>
</div>

<div id="opgreyyarntodyewindow" class="easyui-window" title="Grey Yarn Issue To Dye" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="opgreyyarntodyewindowcontainer">
    </div>
    {{--<table id="opgreyyarntodyeTbl" style="width:500px">
        <thead>
            <tr>
                <th data-options="field:'issue_date'" width="100px">Issue Date </th>
                <th data-options="field:'issue_no'" width="100px">Issue No </th>
                <th data-options="field:'count_name'" width="100px">Count </th>
                <th data-options="field:'composition'" width="100px">Yarn Desc. </th>
                <th data-options="field:'yarn_type'" width="100px">Type </th>
                
                <th data-options="field:'issue_to_name'" width="130px">Issue To </th>
                <th data-options="field:'grey_yarn_issue_qty_for_dye'" width="100px" align="right">Qty</th>
                <th data-options="field:'grey_yarn_issue_rate_for_dye'" width="100px" align="right">Rate</th>
                <th data-options="field:'grey_yarn_issue_amount_for_dye'" width="100px" align="right">Amount</th>
               <th data-options="field:'yarn_color_name'" width="100px">Yarn Color </th>
                <th data-options="field:'sale_order_no'" width="100px">Sales Order No </th>
                <th data-options="field:'lot'" width="100px">Lot </th>
                <th data-options="field:'brand'" width="100px">Brand </th>
                
                <th data-options="field:'supplier_name'" width="100px">Yarn Supplier </th> 
                
                
                
            </tr>
        </thead>
    </table>
    <table id="opdyedyarnrcvTbl" style="width:500px">
        <thead>
            <tr>
                <th data-options="field:'receive_date'" width="100px">MRR Date </th>
                <th data-options="field:'receive_no'" width="100px">MRR </th>
                <th data-options="field:'count_name'" width="100px">Count </th>
                
                <th data-options="field:'composition'" width="100px">Yarn Desc. </th>
                
                <th data-options="field:'yarn_type'" width="100px">Type </th>
                <th data-options="field:'yarn_color_name'" width="100px">Yarn Color </th>
                <th data-options="field:'dyed_yarn_rcv_qty'" width="100px" align="right">Qty</th>
                <th data-options="field:'dyed_yarn_rcv_rate'" width="100px" align="right">Rate</th>
                <th data-options="field:'dyed_yarn_rcv_amount'" width="100px" align="right">Amount</th>
                <th data-options="field:'sale_order_no'" width="100px">Sales Order No </th>
                <th data-options="field:'lot'" width="100px">Lot </th>
                <th data-options="field:'brand'" width="100px">Brand </th>
                
                <th data-options="field:'supplier_name'" width="100px">Yarn Supplier </th>
                
                <th data-options="field:'issue_to_name'" width="130px">Receive From </th>
               
            </tr>
        </thead>
    </table>--}}
</div>

<div id="opdyedyarnrcvwindow" class="easyui-window" title="Dyed Yarn Received" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    
</div>

<div id="opyarnrqwindow" class="easyui-window" title="Dyed Yarn Received" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="opyarnrqTbl" style="width:500px">
        <thead>
            <tr>
               
                <th data-options="field:'composition'" width="100px">Yarn Desc. </th>
                <th data-options="field:'count_name'" width="100px">Count </th>
                <th data-options="field:'yarn_type'" width="100px">Type </th>
                <th data-options="field:'yarn_req'" width="100px" align="right">Req.Qty</th>
                <th data-options="field:'rate'" width="100px" align="right">Rate</th>
                <th data-options="field:'req_amount'" width="100px" align="right">Amount</th>
                <th data-options="field:'rcv_qty'" width="100px" align="right">Rcv.Qty</th>
                <th data-options="field:'rcv_rate'" width="100px" align="right">Rate</th>
                <th data-options="field:'rcv_amount'" width="100px" align="right">Amount</th>
                <th data-options="field:'pending_qty'" width="100px" align="right">Pending Qty</th>
                <th data-options="field:'pending_amount'" width="100px" align="right">Pending Amount</th>
                
            </tr>
        </thead>
    </table>
</div>

<div id="opyarnisuinhwindow" class="easyui-window" title="Yarn Issue To Knit (Inhouse)" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="opyarnisuinhwindowcontainer">
    </div>
    {{--<table id="opyarnisuinhTbl" style="width:500px">
        <thead>
            <tr>
                <th data-options="field:'sale_order_no'" width="100px">Sales Order No </th>
                <th data-options="field:'composition'" width="100px">Yarn Desc. </th>
                <th data-options="field:'count_name'" width="100px">Count </th>
                <th data-options="field:'yarn_type'" width="100px">Type </th>
                <th data-options="field:'lot'" width="100px">Lot </th>
                <th data-options="field:'brand'" width="100px">Brand </th>
                <th data-options="field:'yarn_color_name'" width="100px">Yarn Color </th>
                <th data-options="field:'supplier_name'" width="100px">Yarn Supplier </th>
                <th data-options="field:'receive_no'" width="100px">MRR </th>
                <th data-options="field:'issue_to_name'" width="130px">Receive From </th>
                <th data-options="field:'inh_yarn_isu_qty'" width="100px" align="right">Qty</th>
            </tr>
        </thead>
    </table>--}}
</div>

<div id="opyarnisuoutwindow" class="easyui-window" title=" Yarn Issue to Knit Subcontact" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="opyarnisuoutwindowcontainer">
    </div>
    {{--<table id="opyarnisuoutTbl" style="width:500px">
        <thead>
            <tr>
                <th data-options="field:'sale_order_no'" width="100px">Sales Order No </th>
                <th data-options="field:'composition'" width="100px">Yarn Desc. </th>
                <th data-options="field:'count_name'" width="100px">Count </th>
                <th data-options="field:'yarn_type'" width="100px">Type </th>
                <th data-options="field:'lot'" width="100px">Lot </th>
                <th data-options="field:'brand'" width="100px">Brand </th>
                <th data-options="field:'yarn_color_name'" width="100px">Yarn Color </th>
                <th data-options="field:'supplier_name'" width="100px">Yarn Supplier </th>
                <th data-options="field:'receive_no'" width="100px">MRR </th>
                <th data-options="field:'issue_to_name'" width="130px">Receive From </th>
                <th data-options="field:'out_yarn_isu_qty'" width="100px" align="right">Qty</th>
            </tr>
        </thead>
    </table>--}}
</div>

<div id="opknitwindow" class="easyui-window" title="Knit Production" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="opknitTbl" style="width:500px">
        <thead>
            <tr>
                <th data-options="field:'gmts_part_name'" width="100px">GMT Part </th>
                <th data-options="field:'construction_name'" width="100px">Constructions </th>
                <th data-options="field:'composition_name'" width="150px">Composition </th>
                <th data-options="field:'gsm_weight'" width="100px">GSM </th>
                <th data-options="field:'dia'" width="100px">Dia </th>
                <th data-options="field:'fabric_color_name'" width="100px">Fabric Color </th>
                <th data-options="field:'fabriclooks'" width="100px">Fabric Looks </th>
                <th data-options="field:'fabricshape'" width="100px">Fabric Shape </th>
                <th data-options="field:'req_qty'" width="100px" align="right">Req. Qty </th>
                <th data-options="field:'qc_pass_qty'" width="100px" align="right">Knitted Qty </th>
                <th data-options="field:'qc_pass_qty_pcs'" width="130px" align="right">Eqv. Pcs </th>
                <th data-options="field:'pending_knit'" width="130px" align="right">Kinting Pending </th>
            </tr>
        </thead>
    </table>
</div>

<div id="opcutqtywindow" class="easyui-window" title="Cut Qty" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="opcutqtyTbl" style="width:500px">
        <thead>
            <tr>
            <th data-options="field:'buyer_name',halign:'center'" width="150">Buyer</th>
            <th data-options="field:'style_ref',halign:'center'" width="100">Style No</th>
            <th data-options="field:'sale_order_no',halign:'center'" width="100">Order No</th>
            <th data-options="field:'department_name',halign:'center'" width="80">Prod. Dept</th>
            <th data-options="field:'item_description',halign:'center'" width="80">GMT. Item</th>
            <th data-options="field:'item_complexity',halign:'center'" width="80">Item Complexity</th>
            <th data-options="field:'country_name',halign:'center'" width="80">Country</th>
            <th data-options="field:'color_name',halign:'center'" width="80">GMT. Color</th>
            <th data-options="field:'size_name',halign:'center'" width="60">GMT. Size</th>

            <th data-options="field:'qty',halign:'center'" nowrap="false" width="80" align="right">Order Qty <br/>(Pcs)</th>
            <th data-options="field:'plan_cut_qty',halign:'center'" width="80" align="right">Plan Cut Qty</th>
            <th data-options="field:'cut_qty',halign:'center'" width="80" align="right">Actual Cut Qty</th>
            <th data-options="field:'cut_pending',halign:'center'" width="80" align="right">Cut Pending</th>
            </tr>
        </thead>
    </table>
</div>

<div id="opscrqtywindow" class="easyui-window" title="Screen Print Qty" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="opscrqtyTbl" style="width:500px">
        <thead>
            <tr>
            <th data-options="field:'buyer_name',halign:'center'" width="150">Buyer</th>
            <th data-options="field:'style_ref',halign:'center'" width="100">Style No</th>
            <th data-options="field:'sale_order_no',halign:'center'" width="100">Order No</th>
            <th data-options="field:'department_name',halign:'center'" width="80">Prod. Dept</th>
            <th data-options="field:'item_description',halign:'center'" width="80">GMT. Item</th>
            <th data-options="field:'item_complexity',halign:'center'" width="80">Item Complexity</th>
            <th data-options="field:'country_name',halign:'center'" width="80">Country</th>
            <th data-options="field:'color_name',halign:'center'" width="80">GMT. Color</th>
            <th data-options="field:'size_name',halign:'center'" width="60">GMT. Size</th>

            <th data-options="field:'qty',halign:'center'" nowrap="false" width="80" align="right">Order Qty <br/>(Pcs)</th>
            
            <th data-options="field:'cut_qty',halign:'center'" width="80" align="right">Actual Cut Qty</th>
            <th data-options="field:'snd_scr_qty',halign:'center'" width="80" align="right">Send To Print</th>
            <th data-options="field:'rcv_scr_qty',halign:'center'" width="80" align="right">Rcv. From Print</th>
            <th data-options="field:'scr_pending',halign:'center'" width="80" align="right">Print Pending</th>

            </tr>
        </thead>
    </table>
</div>

<div id="opsewqtywindow" class="easyui-window" title="Sewing Qty" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="opsewqtyTbl" style="width:500px">
        <thead>
            <tr>
            <th data-options="field:'buyer_name',halign:'center'" width="150">Buyer</th>
            <th data-options="field:'style_ref',halign:'center'" width="100">Style No</th>
            <th data-options="field:'sale_order_no',halign:'center'" width="100">Order No</th>
            <th data-options="field:'department_name',halign:'center'" width="80">Prod. Dept</th>
            <th data-options="field:'item_description',halign:'center'" width="80">GMT. Item</th>
            <th data-options="field:'item_complexity',halign:'center'" width="80">Item Complexity</th>
            <th data-options="field:'country_name',halign:'center'" width="80">Country</th>
            <th data-options="field:'color_name',halign:'center'" width="80">GMT. Color</th>
            <th data-options="field:'size_name',halign:'center'" width="60">GMT. Size</th>

            <th data-options="field:'qty',halign:'center'" nowrap="false" width="80" align="right">Order Qty <br/>(Pcs)</th>
            
            <th data-options="field:'sew_line_qty',halign:'center'" width="80" align="right">Line Input Qty</th>
            <th data-options="field:'sew_qty',halign:'center'" width="80" align="right">Sewing Qty</th>
            <th data-options="field:'sewwip',halign:'center'" width="80" align="right">Sewing WIP</th>
            <th data-options="field:'sew_pending',halign:'center'" width="80" align="right">Sewing Pending</th>

            </tr>
        </thead>
    </table>
</div>

<div id="opcarqtywindow" class="easyui-window" title="Carton Qty" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="opcarqtyTbl" style="width:500px">
        <thead>
            <tr>
            <th data-options="field:'buyer_name',halign:'center'" width="150">Buyer</th>
            <th data-options="field:'style_ref',halign:'center'" width="100">Style No</th>
            <th data-options="field:'sale_order_no',halign:'center'" width="100">Order No</th>
            <th data-options="field:'department_name',halign:'center'" width="80">Prod. Dept</th>
            <th data-options="field:'item_description',halign:'center'" width="80">GMT. Item</th>
            <th data-options="field:'item_complexity',halign:'center'" width="80">Item Complexity</th>
            <th data-options="field:'country_name',halign:'center'" width="80">Country</th>
            <th data-options="field:'color_name',halign:'center'" width="80">GMT. Color</th>
            <th data-options="field:'size_name',halign:'center'" width="60">GMT. Size</th>

            <th data-options="field:'qty',halign:'center'" nowrap="false" width="80" align="right">Order Qty <br/>(Pcs)</th>
            
            
            <th data-options="field:'sew_qty',halign:'center'" width="80" align="right">Sewing Qty</th>
            <th data-options="field:'car_qty',halign:'center'" width="80" align="right">Carton Qty</th>
            <th data-options="field:'carwip',halign:'center'" width="80" align="right">Carton WIP</th>
            <th data-options="field:'car_pending',halign:'center'" width="80" align="right">Carton Pending</th>

            </tr>
        </thead>
    </table>
</div>

<div id="opinspqtywindow" class="easyui-window" title="Inspection Qty" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="opinspqtyTbl" style="width:500px">
        <thead>
            <tr>
            <th data-options="field:'buyer_name',halign:'center'" width="150">Buyer</th>
            <th data-options="field:'style_ref',halign:'center'" width="100">Style No</th>
            <th data-options="field:'sale_order_no',halign:'center'" width="100">Order No</th>
            <th data-options="field:'department_name',halign:'center'" width="80">Prod. Dept</th>
            <th data-options="field:'item_description',halign:'center'" width="80">GMT. Item</th>
            <th data-options="field:'item_complexity',halign:'center'" width="80">Item Complexity</th>
            <th data-options="field:'country_name',halign:'center'" width="80">Country</th>
            <th data-options="field:'color_name',halign:'center'" width="80">GMT. Color</th>
            <th data-options="field:'size_name',halign:'center'" width="60">GMT. Size</th>

            <th data-options="field:'qty',halign:'center'" nowrap="false" width="80" align="right">Order Qty <br/>(Pcs)</th>
            
            
            <th data-options="field:'car_qty',halign:'center'" width="80" align="right">Carton Qty</th>
            <th data-options="field:'insp_pass_qty',halign:'center'" width="80" align="right">Insp. Pass Qty</th>

            <th data-options="field:'insp_re_check_qty',halign:'center'" width="80" align="right">Re-check Qty</th>
            <th data-options="field:'insp_faild_qty',halign:'center'" width="80" align="right">Failed Qty</th>
            <th data-options="field:'insp_pending',halign:'center'" width="80" align="right">Insp. Pending</th>
            <th data-options="field:'re_check_remarks',halign:'center'" width="80" align="right">Re-check Cause</th>
            <th data-options="field:'failed_remarks',halign:'center'" width="80" align="right">Failed Cause</th>

            </tr>
        </thead>
    </table>
</div>

<div id="opexfqtywindow" class="easyui-window" title="Ship Out Qty" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="opexfqtyTbl" style="width:500px">
        <thead>
            <tr>
            <th data-options="field:'buyer_name',halign:'center'" width="150">Buyer</th>
            <th data-options="field:'style_ref',halign:'center'" width="100">Style No</th>
            <th data-options="field:'sale_order_no',halign:'center'" width="100">Order No</th>
            <th data-options="field:'department_name',halign:'center'" width="80">Prod. Dept</th>
            <th data-options="field:'item_description',halign:'center'" width="80">GMT. Item</th>
            <th data-options="field:'item_complexity',halign:'center'" width="80">Item Complexity</th>
            <th data-options="field:'country_name',halign:'center'" width="80">Country</th>
            <th data-options="field:'color_name',halign:'center'" width="80">GMT. Color</th>
            <th data-options="field:'size_name',halign:'center'" width="60">GMT. Size</th>

            <th data-options="field:'qty',halign:'center'" nowrap="false" width="80" align="right">Order Qty <br/>(Pcs)</th>
            
            
            <th data-options="field:'car_qty',halign:'center'" width="80" align="right">Carton Qty</th>
            <th data-options="field:'exf_qty',halign:'center'" width="80" align="right">Ship Out Qty</th>
            <th data-options="field:'exf_pending',halign:'center'" width="80" align="right">Ship Out Pending</th>
            

            </tr>
        </thead>
    </table>
</div>



{{--
<div id="summarywindow" class="easyui-window" title="Buyer & Company Wise Summary" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="orderprogresssummaryTbl" style="width:500px">
    </table>
</div>

<div id="summarybuyerbuyinghousewindow" class="easyui-window" title="Buyer & Buying House Wise Summary" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="summarybuyerBuyingHouseTbl" style="width:500px">
    </table>
</div>

<div id="summarybuyerwindow" class="easyui-window" title="Buyer Wise Summary" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="summarybuyerTbl" style="width:500px">
    </table>
</div>

<div id="summarycompanywindow" class="easyui-window" title="Company Wise Summary" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="summarycompanyTbl" style="width:500px">
    </table>
</div>

<div id="detailwindow" class="easyui-window" title="Summary" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="orderprogresdetailTbl" style="width:500px">
        <thead>
            <tr>
               <th data-options="field:'company_code',halign:'center'" width="60">Company</th>
                <th data-options="field:'produced_company_code',halign:'center'" width="60">Producing Compamy Unit</th>
                <th data-options="field:'team_name',halign:'center'" width="80" formatter="MsOrderProgress.formatteamleader">Team <br/>Leader</th>
                <th data-options="field:'team_member_name',halign:'center'" width="100" formatter="MsOrderProgress.formatdlmerchant">Dealing <br/>Merchant</th>
                <th data-options="field:'delivery_date',halign:'center'" width="80">Delivery Date</th>
                <th data-options="field:'delivery_month',halign:'center'" width="60">Delv <br/>Month</th>
                <th data-options="field:'buyer_name',halign:'center'" width="60">Buyer</th>
                <th data-options="field:'buying_agent_name',halign:'center'"  width="70">Buying House</th>
                <th data-options="field:'style_ref',halign:'center'" width="80" formatter="MsOrderProgress.formatopfiles">Style No</th>

                <th data-options="field:'sale_order_no',halign:'center'" width="80">Order No</th>
                <th data-options="field:'sale_order_receive_date',halign:'center'" width="80">Order <br/>Recv Date</th>
                <th data-options="field:'department_name',halign:'center'" width="80">Prod. Dept</th>
                <th data-options="field:'item_description',halign:'center'" width="100">GMT Item</th>
                <th data-options="field:'item_complexity',halign:'center'" width="80">Complexity</th>
                <th data-options="field:'flie_src',halign:'center',align:'center'" formatter="MsOrderProgress.formatimage" width="30">Image</th>
                <th data-options="field:'qty',halign:'center'" nowrap="false" width="80" align="right">Order Qty <br/>(Pcs)</th>
                <th data-options="field:'rate',halign:'center'" width="100" align="right">Price (Pcs)</th>
                <th data-options="field:'amount',halign:'center'" width="100" align="right">Selling <br/>Value</th>
                <th data-options="field:'ship_qty',halign:'center'" width="100" align="right">Ship <br/>Out Qty</th>
                <th data-options="field:'ship_value',halign:'center'" width="100" align="right">Ship <br/>Out Value</th>

                <th data-options="field:'yet_to_ship_qty',halign:'center'" width="100" align="right">Yet to <br/>Ship Qty</th>
                <th data-options="field:'yet_to_ship_value',halign:'center'" width="100" align="right">Yet to <br/>Ship Value</th>

                <th data-options="field:'pending_ship_qty',halign:'center'" width="100" align="right">Pending <br/>Shipment Qty</th>
                <th data-options="field:'pending_ship_value',halign:'center'" width="100" align="right">Pending <br/>Shipment Value</th>
            </tr>
        </thead>
    </table>
</div>
--}}

{{-- Style Filtering Search Window --}}
<div id="ordstyleWindow" class="easyui-window" title="Style Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1200px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'north',split:true, title:'Search'" style="height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="ordstylesearchFrm">
                            <div class="row">
                                <div class="col-sm-2">Buyer :</div>
                                <div class="col-sm-4">
                                {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                                </div>
                                    <div class="col-sm-2 req-text">Style Ref. </div>
                                <div class="col-sm-4"><input type="text" name="style_ref" id="style_ref" value=""/></div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-2">Style Des.  </div>
                                <div class="col-sm-4"><input type="text" name="style_description" id="style_description" value=""/></div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsOrderProgress.searchOrdStyleGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="ordstylesearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="50">ID</th>
                        <th data-options="field:'buyer'" width="70">Buyer</th>
                        <th data-options="field:'receivedate'" width="80">Receive Date</th>
                        <th data-options="field:'style_ref'" width="120">Style Refference</th>
                        <th data-options="field:'style_description'" width="150">Style Description</th>
                        <th data-options="field:'deptcategory'" width="80">Dept. Category</th>
                        <th data-options="field:'productdepartment'" width="80">Product Department</th>
                        <th data-options="field:'season'" width="80">Season</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#ordstyleWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
{{-- Dealing Merchant / Teammember Search Window --}}
<div id="teammemberDlmWindow" class="easyui-window" title="Dealing Merchant Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="teammemberdlmFrm">
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Team</div>
                                <div class="col-sm-8">
                                    {!! Form::select('team_id', $team,'',array('id'=>'team_id')) !!}
                                </div>
                            </div> 
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsOrderProgress.searchTeammemberDlmGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="teammemberdlmTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'team_name'" width="120">Team</th>
                        <th data-options="field:'dlm_name'" width="120">Dealing Marchant</th>
                        <th data-options="field:'type_id'" width="130px">Member Type</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#teammemberDlmWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/report/MsOrderProgressController.js"></script>
<script>
    (function(){
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
        });
        $('.integer').keyup(function () {
            if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
            }
        });
        $('#orderprogressFrm [id="buyer_id"]').combobox();
        $('#ordstylesearchFrm [id="buyer_id"]').combobox();
    })(jQuery);
</script>
