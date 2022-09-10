<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'MKT Costing vs Actual Budget'" style="padding:2px">
        <div id="budgetandcostingcomparisonmatrix">
    
        </div>
    {{-- <table id="budgetandcostingcomparisonTbl">
    <thead>
        <tr>
            <th data-options="field:'company_code',halign:'center'" width="60" rowspan="2">Company</th>
            <th data-options="field:'team_member_name',halign:'center'" width="100" rowspan="2"> Dealing <br/>Merchant</th>
            <th data-options="field:'delivery_date',halign:'center'" width="80" rowspan="2">Delivery Date</th>
            <th data-options="field:'buyer_name',halign:'center'" width="60" rowspan="2">Buyer</th>
            <th data-options="field:'style_ref',halign:'center'" width="80" rowspan="2" formatter="MsBudgetAndCostingComparison.formatfiles" >Style No</th>
            <th data-options="field:'season_name',halign:'center'" width="80" rowspan="2">Season</th>
    
            <th data-options="field:'sale_order_no',halign:'center'" width="80" rowspan="2">Order No</th>
            <th data-options="field:'sale_order_receive_date',halign:'center'" width="80" rowspan="2">Order <br/>Recv Date</th>
            <th data-options="field:'department_name',halign:'center'" width="80" rowspan="2">Prod. Dept</th>
            
            <th data-options="field:'flie_src',halign:'center',align:'center'" formatter="MsBudgetAndCostingComparison.formatimage" width="30" rowspan="2">Image</th>
            <th data-options="field:'qty',halign:'center'" nowrap="false" formatter="MsBudgetAndCostingComparison.formatSaleOrder"   width="80" align="right" rowspan="2">Order Qty <br/>(Pcs)</th>
            <th data-options="field:'rate',halign:'center'" width="80" align="right" rowspan="2">Price (Pcs)</th>
            <th data-options="field:'amount',halign:'center'" width="80" align="right" rowspan="2">Selling Value</th>
            <th width="80" align="right" colspan="3">Yarn</th>
            <th width="80" align="right" colspan="3">Trim</th>
            <th width="80" align="right" colspan="3">Yarn Dyeing</th>
            <th width="80" align="right" colspan="3">Knitting</th>
            <!-- 
            <th width="80" align="right" colspan="3">Weaving </th>
            -->
            <th width="80" align="right" colspan="3">Dyeing </th>
            
            <th width="80" align="right" colspan="3">AOP</th>
            <th width="80" align="right" colspan="3">Burn Out</th>
            <!-- 
            <th width="100" align="right" colspan="3">Finishing</th>
            -->
            <th width="80" align="right" colspan="3">Washing</th>
            <th width="80" align="right" colspan="3">Printing</th>
            <th width="80" align="right" colspan="3">Embelishment</th>
            <th width="80" align="right" colspan="3">Sp. Embelishment</th>
            <th width="80" align="right" colspan="3">GMT Dying</th>
            <th width="80" align="right" colspan="3">GMT Washing</th>
            <th width="80" align="right" colspan="3">Others </th>
            <th width="80" align="right" colspan="3">Freight</th>
            <th width="80" align="right" colspan="3">CM</th>
            <th width="80" align="right" colspan="3">Commission</th>
            <th width="80" align="right" colspan="3">Commercial</th>
            <th width="80" align="right" colspan="3">Total Cost</th>
            <th width="80" align="right" colspan="3">Profit</th>
            <th width="80" align="right" colspan="3">Profit %</th>
    
       </tr>
    
       <tr>
            <th data-options="field:'mkt_yarn_amount',halign:'center'"  width="80" align="right">Marketing</th>
            <th data-options="field:'yarn_amount',halign:'center'" formatter="MsBudgetAndCostingComparison.formatYarn" width="80" align="right">Budget</th>
            <th data-options="field:'yarn_vari',halign:'center'"  width="80" align="right">Variance</th>
    
            <th data-options="field:'mkt_trim_amount',halign:'center'"  width="80" align="right">Marketing</th>
            <th data-options="field:'trim_amount',halign:'center'" formatter="MsBudgetAndCostingComparison.formatTrim" width="80" align="right">Budget</th>
            <th data-options="field:'trim_vari',halign:'center'"  width="80" align="right">Variance</th>
    
           <th data-options="field:'mkt_yd_amount',halign:'center'"  width="80" align="right">Marketing</th>
            <th data-options="field:'yarn_dying_amount',halign:'center'" formatter="MsBudgetAndCostingComparison.formatYarnDying" width="80" align="right">Budget</th>
            <th data-options="field:'yd_vari',halign:'center'"  width="80" align="right">Variance</th>
    
            <th data-options="field:'mkt_knit_amount',halign:'center'"  width="80" align="right">Marketing</th>
            <th data-options="field:'kniting_amount',halign:'center'" formatter="MsBudgetAndCostingComparison.formatKnit" width="80" align="right">Budget</th>
            <th data-options="field:'kniting_vari',halign:'center'"  width="80" align="right">Variance</th>
            
            <!-- 
                <th data-options="field:'mkt_weav_amount',halign:'center'" width="80" align="right">Marketing</th>
                <th data-options="field:'weaving_amount',halign:'center'" formatter="MsBudgetAndCostingComparison.formatWeaving" width="80" align="right">Budget</th>
                <th data-options="field:'weav_vari',halign:'center'" width="80" align="right">Variance</th>
                 -->
            <th data-options="field:'mkt_dye_amount',halign:'center'"  width="80" align="right">Marketing</th>
            <th data-options="field:'dying_amount',halign:'center'" formatter="MsBudgetAndCostingComparison.formatDyeing"  width="80" align="right">Budget</th>
            <th data-options="field:'dye_vari',halign:'center'"  width="80" align="right">Variance</th>
    
            <th data-options="field:'mkt_aop_amount',halign:'center'"  width="80" align="right">Marketing</th>
            <th data-options="field:'aop_amount',halign:'center'" formatter="MsBudgetAndCostingComparison.formatAop" width="80" align="right">Budget</th>
            <th data-options="field:'aop_vari',halign:'center'"  width="80" align="right">Variance</th>
    
            <th data-options="field:'mkt_burn_amount',halign:'center'"  width="80" align="right">Marketing</th>
            <th data-options="field:'burn_out_amount',halign:'center'" formatter="MsBudgetAndCostingComparison.formatBurnOut" width="80" align="right">Budget</th>
            <th data-options="field:'burn_out_vari',halign:'center'"  width="80" align="right">Variance</th>
            <!-- 
                <th data-options="field:'mkt_fab_finsh_amount',halign:'center'"  width="80" align="right">Marketing</th>
    
                <th data-options="field:'finishing_amount',halign:'center'" formatter="MsBudgetAndCostingComparison.formatFinishing" width="80" align="right">Budget</th>
                <th data-options="field:'finish_vari',halign:'center'"  width="80" align="right"> Variance</th>
    
                 -->
            <th data-options="field:'mkt_fab_wash_amount',halign:'center'"  width="80" align="right">Marketing</th>
            <th data-options="field:'washing_amount',halign:'center'" formatter="MsBudgetAndCostingComparison.formatWashing" width="80" align="right">Budget</th>
            <th data-options="field:'wash_vari',halign:'center'"  width="80" align="right">Variance</th>
    
            <th data-options="field:'mkt_print_amount',halign:'center'"  width="80" align="right">Marketing</th>
            <th data-options="field:'printing_amount',halign:'center'" formatter="MsBudgetAndCostingComparison.formatPrinting" width="80" align="right">Budget</th>
            <th data-options="field:'print_vari',halign:'center'"  width="80" align="right">Variance</th>
    
            <th data-options="field:'mkt_embel_amount',halign:'center'"  width="80" align="right">Marketing</th>
            <th data-options="field:'emb_amount',halign:'center'" formatter="MsBudgetAndCostingComparison.formatEmb" width="80" align="right">Budget</th>
            <th data-options="field:'emb_vari',halign:'center'"  width="80" align="right">Variance</th>
    
            <th data-options="field:'mkt_spembel_amount',halign:'center'"  width="80" align="right">Marketing</th>
            <th data-options="field:'spemb_amount',halign:'center'" formatter="MsBudgetAndCostingComparison.formatSpEmb" width="80" align="right">Budget</th>
            <th data-options="field:'spemb_vari',halign:'center'"  width="80" align="right">Variance</th>
    
            <th data-options="field:'mkt_gmt_dye_amount',halign:'center'"  width="80" align="right">Marketing</th>
            <th data-options="field:'gmt_dyeing_amount',halign:'center'" formatter="MsBudgetAndCostingComparison.formatGmtDying" width="80" align="right">Budget</th>
            <th data-options="field:'gmt_dye_vari',halign:'center'"  width="80" align="right">Variance</th>
    
            <th data-options="field:'mkt_gmt_wash_amount',halign:'center'" width="80" align="right">Marketing</th>
            <th data-options="field:'gmt_washing_amount',halign:'center'" formatter="MsBudgetAndCostingComparison.formatGmtWashing" width="80" align="right">Budget</th>
             <th data-options="field:'gmt_wash_vari',halign:'center'"  width="80" align="right">Variance</th>
    
            <th data-options="field:'mkt_other_amount',halign:'center'"  width="80" align="right">Marketing</th>
            <th data-options="field:'courier_amount',halign:'center'" formatter="MsBudgetAndCostingComparison.formatCourier" width="80" align="right">Budget</th>
            <th data-options="field:'gmt_other_vari',halign:'center'"  width="80" align="right">Variance</th>
    
            <th data-options="field:'mkt_frei_amount',halign:'center'"  width="80" align="right">Marketing</th>
            <th data-options="field:'freight_amount',halign:'center'" formatter="MsBudgetAndCostingComparison.formatFreight" width="80" align="right">Budget</th>
            <th data-options="field:'gmt_frei_vari',halign:'center'"  width="80" align="right">Variance</th>
    
            <th data-options="field:'mkt_cm_amount',halign:'center'"  width="80" align="right">Marketing</th>
            <th data-options="field:'cm_amount',halign:'center'" formatter="MsBudgetAndCostingComparison.formatCm" width="80" align="right">Budget</th>
            <th data-options="field:'gmt_cm_vari',halign:'center'"  width="80" align="right">Variance</th>
    
            <th data-options="field:'mkt_commi_amount',halign:'center'"  width="80" align="right">Marketing</th>
            <th data-options="field:'commi_amount',halign:'center'" formatter="MsBudgetAndCostingComparison.formatCommi" width="80" align="right">Budget</th>
            <th data-options="field:'gmt_commi_vari',halign:'center'"  width="80" align="right">Variance</th>
    
            <th data-options="field:'mkt_commer_amount',halign:'center'"  width="80" align="right">Marketing</th>
            <th data-options="field:'commer_amount',halign:'center'" formatter="MsBudgetAndCostingComparison.formatCommer" width="80" align="right">Budget</th>
            <th data-options="field:'gmt_commer_vari',halign:'center'"  width="80" align="right">Variance</th>
    
            <th data-options="field:'mkt_total_amount',halign:'center'"  width="80" align="right">Marketing</th>
            <th data-options="field:'total_amount',halign:'center'"  width="80" align="right">Budget</th>
            <th data-options="field:'total_amount_vari',halign:'center'"  width="80" align="right">Variance</th>
    
            <th data-options="field:'mkt_total_profit',halign:'center'" width="80" align="right">Marketing</th>
            <th data-options="field:'total_profit',halign:'center'" width="80" align="right">Budget</th>
            <th data-options="field:'total_profit_vari',halign:'center'" width="80" align="right">Variance</th>
    
            <th data-options="field:'mkt_total_profit_per',halign:'center'" width="80" align="right">Marketing %</th>
            <th data-options="field:'total_profit_per',halign:'center'" width="80" align="right">Budget %</th>
            <th data-options="field:'total_profit_per_vari',halign:'center'" width="80" align="right">Variance %</th>
    <!--         <th data-options="field:'id',halign:'center'" width="80" align="right">ID</th>
     -->
       </tr>
    </thead>
    </table> --}}
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
    <form id="budgetandcostingcomparisonFrm">
        <div id="container">
             <div id="body">
               <code>
    
                <div class="row">
                        <div class="col-sm-4">Buyer </div>
                        <div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}</div>
                    </div>
    
                    <div class="row middle">
                        <div class="col-sm-4">Company</div>
                        <div class="col-sm-8">
                       {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
    
                        </div>
                    </div>
                    
                    <div class="row middle">
                        <div class="col-sm-4">Style Ref</div>
                        <div class="col-sm-8">
                       <input type="text" name="style_ref" id="style_ref" />
    
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Job No </div>
                        <div class="col-sm-8"><input type="text" name="job_no" id="job_no" /></div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Ship Date </div>
                        <div class="col-sm-4" style="padding-right:0px">
                        <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" value="{{$from}}"/>
                        </div>
                        <div class="col-sm-4" style="padding-left:0px">
                        <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" value="{{$to}}"/>
                        </div>
                    </div>
                     <div class="row middle">
                        <div class="col-sm-4">Status </div>
                        <div class="col-sm-8">{!! Form::select('order_status', $status,1,array('id'=>'order_status')) !!}</div>
                    </div>
              </code>
           </div>
        </div>
        <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <!-- <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetAndCostingComparison.get()">Show-2</a> -->
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetAndCostingComparison.formatTow()">Show</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsBudgetAndCostingComparison.resetForm('budgetandcostingcomparisonFrm')" >Reset</a>
        </div>
      </form>
    </div>
    </div>
    <div id="orderWiseBudgetImageWindow" class="easyui-window" title="Image" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:500px;height:500px;padding:2px;">
        <img id="orderWiseBudgetImageWindowoutput" src=""/>
    </div>
    
    <div id="bacyarnWindow" class="easyui-window" title="Yarn Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="bncReportyarnTbl" style="width:900px">
            <thead>
                <tr>
                    <th data-options="field:'gmt_item_description'" width="150">GMT Item</th>
                    <th data-options="field:'gmt_part_name'" width="150">GMT Parts</th> 
                    <th data-options="field:'fab_des'" width="250px">Fabric</th>
                    <th data-options="field:'yarn_des'" width="250px">Yarn</th>
                    <th data-options="field:'req_cons'" width="70px" align="right">Fabric<br/>  Cons</th>
                    <th data-options="field:'ratio'" width="70px" align="right">Ratio</th>
                    <th data-options="field:'cons'" width="70px" align="right">Qty</th>
                    <th data-options="field:'rate'" width="70px" align="right">Rate/kg</th>
                    <th data-options="field:'yarn_amount'" width="80px" align="right">Amount</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div id="owbyarnWindow2" class="easyui-window" title="Yarn Total Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="OrdbudgetReportyarnTbl2" style="width:900px">
            <thead>
                <tr>
                    <th data-options="field:'yarn_des'" width="350px">Yarn</th>
                    <th data-options="field:'yarn_qty'" width="100px" align="right">Qty</th>
                    <th data-options="field:'rate'" width="70px" align="right">Rate/kg</th>
                    <th data-options="field:'yarn_amount'" width="100px" align="right">Amount</th>
                </tr>
            </thead>
        </table>   
    </div>
    
    <div id="bactrimWindow" class="easyui-window" title="Trims Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="bacReporttrimTbl" style="width:900px">
            <thead>
                <tr>
                <th data-options="field:'name'" width="250px">Name</th>
                <th data-options="field:'description'" width="250px">Description</th>
                <th data-options="field:'code'" width="50px">UOM</th>
                <th data-options="field:'bom_trim'" width="70px" align="right">BOM<br/> Qty</th>
                <th data-options="field:'rate'" width="70px" align="right">Rate</th>
                <th data-options="field:'trim_amount'" width="80px" align="right">Amount</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div id="owbtrimWindow2" class="easyui-window" title="Trims Total Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="OrdbudgetReporttrimTbl2" style="width:900px">
            <thead>
                <tr>
                <th data-options="field:'name'" width="250px">Name</th>
                <th data-options="field:'description'" width="250px">Description</th>
                <th data-options="field:'code'" width="50px">UOM</th>
                <th data-options="field:'bom_trim'" width="70px" align="right">BOM<br/> Qty</th>
                <th data-options="field:'rate'" width="70px" align="right">Rate</th>
                <th data-options="field:'trim_amount'" width="80px" align="right">Amount</th>
                </tr>
            </thead>
        </table>
    </div>

     <div id="bacfabpurWindow" class="easyui-window" title="Fabric Purchase Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="bacReportfabpurTbl" style="width:900px">
            <thead>
                <tr>
                    <th data-options="field:'gmt_item_description'" width="150">GMT Item</th>
                    <th data-options="field:'gmt_part_name'" width="150">GMT Parts</th>
                    <th data-options="field:'fab_des'" width="250px">Fabric</th>
                    <th data-options="field:'grey_fab_qty'" width="70px" align="right">Fab.Qty</th>
                    <th data-options="field:'rate'" width="70px" align="right">Rate/kg</th>
                    <th data-options="field:'fab_pur_amount'" width="80px" align="right">Amount</th>
                </tr>
            </thead>
        </table>
    </div>
    <div id="owbfabpurWindow2" class="easyui-window" title="Fabric Purchase Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="OrdbudgetReportfabpurTbl2" style="width:900px">
            <thead>
                <tr>
                    <th data-options="field:'fab_des'" width="250px">Fabric</th>
                    <th data-options="field:'grey_fab_qty'" width="70px" align="right">Fab.Qty</th>
                    <th data-options="field:'rate'" width="70px" align="right">Rate/kg</th>
                    <th data-options="field:'fab_pur_amount'" width="80px" align="right">Amount</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div id="backnitWindow" class="easyui-window" title="Knitting Cost Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="bacReportknitTbl" style="width:900px">
            <thead>
                <tr>
                    <th data-options="field:'gmt_item_description'" width="150">GMT Item</th>
                    <th data-options="field:'gmt_part_name'" width="150">GMT Parts</th>
                    <th data-options="field:'fab_des'" width="250px">Fabric</th>
                    <th data-options="field:'kniting_qty'" width="70px" align="right">Gray<br/>Fab.Qty</th>
                    <th data-options="field:'rate'" width="70px" align="right">Knitting<br/>Charge/kg</th>
                    <th data-options="field:'kniting_amount'" width="80px" align="right">Amount</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div id="owbknitWindow2" class="easyui-window" title="Knitting Cost Total Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="OrdbudgetReportknitTbl2" style="width:900px">
            <thead>
                <tr>
                    <th data-options="field:'fab_des'" width="250px">Fabric</th>
                    <th data-options="field:'kniting_qty'" width="70px" align="right">Gray <br/>Fab.Qty</th>
                    <th data-options="field:'rate'" width="70px" align="right">Knitting<br/>Charge/kg</th>
                    <th data-options="field:'kniting_amount'" width="80px" align="right">Amount</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div id="bacyarndyeingWindow" class="easyui-window" title="Yarn Dyeing Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="bacReportyarndyeingTbl" style="width:900px">
            <thead>
                <tr>
                    <th data-options="field:'gmt_item_description'" width="150">GMT Item</th>
                    <th data-options="field:'gmt_part_name'" width="150">GMT Parts</th>
                    <th data-options="field:'fab_des'" width="250px">Fabric</th>
                    <th data-options="field:'fabric_color'" width="250px">Color </th>
                    <th data-options="field:'yarn_dyeing_qty'" width="70px" align="right">Qty</th>
                    <th data-options="field:'rate'" width="70px" align="right">Rate/kg</th>
                    <th data-options="field:'yarn_dyeing_amount'" width="80px" align="right">Amount</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div id="owbyarndyeingWindow2" class="easyui-window" title="Yarn Dyeing Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="OrdbudgetReportyarndyeingTbl2" style="width:900px">
            <thead>
                <tr>
                    <th data-options="field:'fab_des'" width="250px">Fabric</th>
                    <th data-options="field:'fabric_color'" width="250px">Color </th>
                    <th data-options="field:'yarn_dyeing_qty'" width="70px" align="right">Qty</th>
                    <th data-options="field:'rate'" width="70px" align="right">Rate/kg</th>
                    <th data-options="field:'yarn_dyeing_amount'" width="80px" align="right">Amount</th>
                </tr>
            </thead>
        </table>
    </div>
    
    
    <div id="bacdyeingWindow" class="easyui-window" title="Dyeing Charge Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="bacReportdyeingTbl" style="width:900px">
            <thead>
                <tr>
                    <th data-options="field:'gmt_item_description'" width="90">GMT Item</th>
                    <th data-options="field:'gmt_part_name'" width="90">GMT Parts</th>
                    <th data-options="field:'fab_des'" width="200px">Fabric</th>
                    <th data-options="field:'process_name'" width="70px">Process</th>
                    <th data-options="field:'looks'" width="70px" align="left">Look</th>
                    <th data-options="field:'shape'" width="70px" align="left">Shape</th>
                    <th data-options="field:'dyetype'" width="70px" align="left">Dye Type</th>
                    <th data-options="field:'fabric_color'" width="80px">Color </th>
                    <th data-options="field:'qty'" width="70px" align="right">Gray<br/>Fab.Qty</th>
                    <th data-options="field:'rate'" width="50px" align="right">Rate/kg</th>
                    <th data-options="field:'amount'" width="80px" align="right">Amount</th>
                    <th data-options="field:'overhead_rate'" width="60px" align="right" formatter="MsBudgetAndCostingComparison.formatOverheadRate">Overhead<br/>Rate/kg</th>
                    <th data-options="field:'overhead_amount'" width="80px" align="right">Overhead<br/> Amount</th>
                    <th data-options="field:'total_amount'" width="80px" align="right">Total Amount</th>
                    <th data-options="field:'dye_charge_per_kg'" width="70px" align="right">Dye/Fin<br/> Charge/kg</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div id="owbdyeingWindow2" class="easyui-window" title="Dyeing Charge Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="bacReportdyeingTbl2" style="width:900px">
            <thead>
                <tr>
                    <th data-options="field:'fab_des'" width="250px">Fabric</th>
                    <th data-options="field:'process_name'" width="70px">Process</th>
                    <th data-options="field:'looks'" width="70px" align="right">Look</th>
                    <th data-options="field:'shape'" width="70px" align="right">Shape</th>
                    <th data-options="field:'dyetype'" width="70px" align="right">Dye Type</th>
                    <th data-options="field:'fabric_color'" width="250px">Color </th>
                    <th data-options="field:'qty'" width="70px" align="right">Gray<br/> Fab.Qty</th>
                    <th data-options="field:'rate'" width="70px" align="right">Rate/kg</th>
                    <th data-options="field:'amount'" width="80px" align="right">Amount</th>
                    <th data-options="field:'overhead_rate'" width="80px" align="right">Overhead Rate/kg</th>
                    <th data-options="field:'overhead_amount'" width="80px" align="right">Overhead Amount</th>
                    <th data-options="field:'total_amount'" width="80px" align="right">Total Amount</th>
                    <th data-options="field:'dye_charge_per_kg'" width="80px" align="right">Dye/Fin<br/> Charge/kg</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div id="bacaopWindow" class="easyui-window" title="AOP Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="bacReportaopTbl" style="width:900px">
            <thead>
                <tr>
                    <th data-options="field:'gmt_item_description'" width="100">GMT Item</th>
                    <th data-options="field:'gmt_part_name'" width="90">GMT Parts</th>
                    <th data-options="field:'fab_des'" width="190px">Fabric</th>
                    <th data-options="field:'looks'" width="70px" align="left">Look</th>
                    <th data-options="field:'shape'" width="70px" align="left">Shape</th>
                    <th data-options="field:'aoptype'" width="70px" align="left">Aop Type</th>
                    <th data-options="field:'coverage'" width="70px">Coverage </th>
                    <th data-options="field:'impression'" width="70px">Impression </th>
                    <th data-options="field:'qty'" width="70px" align="right">Gray<br/>Fab.Qty</th>
                    <th data-options="field:'rate'" width="60px" align="right"  formatter="MsBudgetAndCostingComparison.formatOverheadRateAop">Rate/kg</th>
                    <th data-options="field:'amount'" width="80px" align="right">Amount</th>
                    <th data-options="field:'overhead_rate'" width="70px" align="right">Overhead<br/> Rate/kg</th>
                    <th data-options="field:'overhead_amount'" width="80px" align="right">Overhead<br/> Amount</th>
                    <th data-options="field:'total_amount'" width="80px" align="right">Total Amount</th>
                    <th data-options="field:'aop_charge_per_kg'" width="65px" align="right">AOP<br/> Charge/kg</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div id="owbaopWindow2" class="easyui-window" title="AOP Total Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="bacReportaopTbl2" style="width:900px">
            <thead>
                <tr>
                    <th data-options="field:'fab_des'" width="250px">Fabric</th>
                    <th data-options="field:'looks'" width="70px" align="right">Look</th>
                    <th data-options="field:'shape'" width="70px" align="right">Shape</th>
                    <th data-options="field:'aoptype'" width="70px" align="right">Aop Type</th>
                    <th data-options="field:'coverage'" width="70px">Coverage </th>
                    <th data-options="field:'impression'" width="70px">Impression </th>
                    <th data-options="field:'qty'" width="70px" align="right">Gray <br/>Fab.Qty</th>
                    <th data-options="field:'rate'" width="70px" align="right">Rate/kg</th>
                    <th data-options="field:'amount'" width="80px" align="right">Amount</th>
                    <th data-options="field:'overhead_rate'" width="80px" align="right">Overhead<br/> Rate/kg</th>
                    <th data-options="field:'overhead_amount'" width="80px" align="right">Overhead<br/> Amount</th>
                    <th data-options="field:'total_amount'" width="80px" align="right">Total Amount</th>
                    <th data-options="field:'aop_charge_per_kg'" width="80px" align="right">AOP <br/> Charge/kg</th>
                </tr>
            </thead>
        </table>
    </div>
    
    
    <div id="bacbocWindow" class="easyui-window" title="Burn Out Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="bacReportbocTbl" style="width:900px">
            <thead>
                <tr>
                    <th data-options="field:'gmt_item_description'" width="150">GMT Item</th>
                    <th data-options="field:'gmt_part_name'" width="150">GMT Parts</th>
                    <th data-options="field:'fab_des'" width="250px">Fabric</th>
                    <th data-options="field:'looks'" width="70px" align="right">Look</th>
                    <th data-options="field:'shape'" width="70px" align="right">Shape</th>
                    <th data-options="field:'qty'" width="70px" align="right">Qty</th>
                    <th data-options="field:'rate'" width="70px" align="right">Rate</th>
                    <th data-options="field:'amount'" width="80px" align="right">Amount</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div id="owbbocWindow2" class="easyui-window" title="Burn Out Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="OrdbudgetReportbocTbl2" style="width:900px">
            <thead>
                <tr>
                    <th data-options="field:'fab_des'" width="250px">Fabric</th>
                    <th data-options="field:'looks'" width="70px" align="right">Look</th>
                    <th data-options="field:'shape'" width="70px" align="right">Shape</th>
                    <th data-options="field:'qty'" width="70px" align="right">Qty</th>
                    <th data-options="field:'rate'" width="70px" align="right">Rate</th>
                    <th data-options="field:'amount'" width="80px" align="right">Amount</th>
                </tr>
            </thead>
        </table>
    </div>
    
    
    <div id="bacfwcWindow" class="easyui-window" title="Washing Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="bacReportfwcTbl" style="width:900px">
            <thead>
                <tr>
                    <th data-options="field:'gmt_item_description'" width="150">GMT Item</th>
                    <th data-options="field:'gmt_part_name'" width="150">GMT Parts</th>
                    <th data-options="field:'fab_des'" width="250px">Fabric</th>
                    <th data-options="field:'looks'" width="70px" align="right">Look</th>
                    <th data-options="field:'shape'" width="70px" align="right">Shape</th>
                    <th data-options="field:'fabric_color'" width="250px">Color </th>
                    <th data-options="field:'qty'" width="70px" align="right">Qty</th>
                    <th data-options="field:'rate'" width="70px" align="right">Rate</th>
                    <th data-options="field:'amount'" width="80px" align="right">Amount</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div id="owbfwcWindow2" class="easyui-window" title="Washing Total Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="OrdbudgetReportfwcTbl2" style="width:900px">
            <thead>
                <tr>
                    <th data-options="field:'fab_des'" width="250px">Fabric</th>
                    <th data-options="field:'looks'" width="70px" align="right">Look</th>
                    <th data-options="field:'shape'" width="70px" align="right">Shape</th>
                    <th data-options="field:'fabric_color'" width="250px">Color </th>
                    <th data-options="field:'qty'" width="70px" align="right">Qty</th>
                    <th data-options="field:'rate'" width="70px" align="right">Rate</th>
                    <th data-options="field:'amount'" width="80px" align="right">Amount</th>
                </tr>
            </thead>
        </table>
    </div>
    
    
    <div id="bacgpcWindow" class="easyui-window" title="Screen Printing Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="bacReportgpcTbl" style="width:900px">
            <thead>
                <tr>
                    <th data-options="field:'gmt_item'" width="150">GMT Item</th>
                    <th data-options="field:'emb_type'" width="150">Print Type </th>
                    <th data-options="field:'gmt_part_name'" width="250px">GMT Part</th>
                    <th data-options="field:'embelishment_size'" width="70px" align="right">Print Size</th>
                    <th data-options="field:'qty'" width="70px" align="right">Garment<br/> Qty</th>
                    <th data-options="field:'rate'" width="70px" align="right">Rate/Dzn</th>
                    <th data-options="field:'amount'" width="80px" align="right">Amount</th>
                    <th data-options="field:'overhead_rate'" width="80px" align="right"  formatter="MsBudgetAndCostingComparison.formatOverheadRateGpc">Overhead<br/> Rate/Dzn</th>
                    <th data-options="field:'overhead_amount'" width="80px" align="right">Overhead<br/> Amount</th>
                    <th data-options="field:'total_amount'" width="80px" align="right">Total Amount</th>
                    <th data-options="field:'screen_print_charge'" width="80px" align="right">Screen<br/>Printing<br/>Charge/dzn</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div id="owbgpcWindow2" class="easyui-window" title="Screen Printing Total Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="bacReportgpcTbl2" style="width:900px">
            <thead>
                <tr>
                    <th data-options="field:'gmt_item'" width="150">GMT Item</th>
                    <th data-options="field:'emb_type'" width="150">Print Type </th>
                    <th data-options="field:'gmt_part_name'" width="250px">GMT Part</th>
                    <th data-options="field:'embelishment_size'" width="70px" align="right">Print Size</th>
                    <th data-options="field:'qty'" width="70px" align="right">Qty</th>
                    <th data-options="field:'rate'" width="70px" align="right">Rate/Dzn</th>
                    <th data-options="field:'amount'" width="80px" align="right">Amount</th>
                    <th data-options="field:'overhead_rate'" width="80px" align="right">Overhead<br/> Rate/Dzn</th>
                    <th data-options="field:'overhead_amount'" width="80px" align="right">Overhead Amount</th>
                    <th data-options="field:'total_amount'" width="80px" align="right">Total Amount</th>
                    <th data-options="field:'screen_print_charge'" width="80px" align="right">Screen<br/>Printing<br/>Charge/dzn</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div id="bacgecWindow" class="easyui-window" title="Embroidery Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="bacReportgecTbl" style="width:900px">
            <thead>
                <tr>
                    <th data-options="field:'gmt_item'" width="150">GMT Item</th>
                    <th data-options="field:'emb_type'" width="150">Emb Type </th>
                    <th data-options="field:'gmt_part_name'" width="250px">GMT Part</th>
                    <th data-options="field:'embelishment_size'" width="70px" align="right">Emb Size</th>
                    <th data-options="field:'qty'" width="70px" align="right">Qty</th>
                    <th data-options="field:'rate'" width="70px" align="right">Rate</th>
                    <th data-options="field:'amount'" width="80px" align="right">Amount</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div id="owbgecWindow2" class="easyui-window" title="Embroidery Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="OrdbudgetReportgecTbl2" style="width:900px">
            <thead>
                <tr>
                    <th data-options="field:'gmt_item'" width="150">GMT Item</th>
                    <th data-options="field:'emb_type'" width="150">Emb Type </th>
                    <th data-options="field:'gmt_part_name'" width="250px">GMT Part</th>
                    <th data-options="field:'embelishment_size'" width="70px" align="right">Emb Size</th>
                    <th data-options="field:'qty'" width="70px" align="right">Qty</th>
                    <th data-options="field:'rate'" width="70px" align="right">Rate</th>
                    <th data-options="field:'amount'" width="80px" align="right">Amount</th>
                </tr>
            </thead>
        </table>
    </div>
    
    
    <div id="bacgsecWindow" class="easyui-window" title="Sp.Embelishment Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="bacReportgsecTbl" style="width:900px">
            <thead>
                <tr>
                    <th data-options="field:'gmt_item'" width="150">GMT Item</th>
                    <th data-options="field:'emb_type'" width="150">Emb Type </th>
                    <th data-options="field:'gmt_part_name'" width="250px">GMT Part</th>
                    <th data-options="field:'embelishment_size'" width="70px" align="right">Emb Size</th>
                    <th data-options="field:'qty'" width="70px" align="right">Qty</th>
                    <th data-options="field:'rate'" width="70px" align="right">Rate</th>
                    <th data-options="field:'amount'" width="80px" align="right">Amount</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div id="owbgsecWindow2" class="easyui-window" title="Sp.Embelishment Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="OrdbudgetReportgsecTbl2" style="width:900px">
            <thead>
                <tr>
                    <th data-options="field:'gmt_item'" width="150">GMT Item</th>
                    <th data-options="field:'emb_type'" width="150">Emb Type </th>
                    <th data-options="field:'gmt_part_name'" width="250px">GMT Part</th>
                    <th data-options="field:'embelishment_size'" width="70px" align="right">Emb Size</th>
                    <th data-options="field:'qty'" width="70px" align="right">Qty</th>
                    <th data-options="field:'rate'" width="70px" align="right">Rate</th>
                    <th data-options="field:'amount'" width="80px" align="right">Amount</th>
                </tr>
            </thead>
        </table>
    </div>
    
    
    <div id="bacgdcWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="bacReportgdcTbl" style="width:900px">
            <thead>
                <tr>
                    <th data-options="field:'gmt_item'" width="150">GMT Item</th>
                    <th data-options="field:'emb_type'" width="150">Emb Type </th>
                    <th data-options="field:'gmt_part_name'" width="250px">GMT Part</th>
                    <th data-options="field:'embelishment_size'" width="70px" align="right">Emb Size</th>
                    <th data-options="field:'qty'" width="70px" align="right">Qty</th>
                    <th data-options="field:'rate'" width="70px" align="right">Rate</th>
                    <th data-options="field:'amount'" width="80px" align="right">Amount</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div id="owbgdcWindow2" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="OrdbudgetReportgdcTbl2" style="width:900px">
            <thead>
                <tr>
                    <th data-options="field:'gmt_item'" width="150">GMT Item</th>
                    <th data-options="field:'emb_type'" width="150">Emb Type </th>
                    <th data-options="field:'gmt_part_name'" width="250px">GMT Part</th>
                    <th data-options="field:'embelishment_size'" width="70px" align="right">Emb Size</th>
                    <th data-options="field:'qty'" width="70px" align="right">Qty</th>
                    <th data-options="field:'rate'" width="70px" align="right">Rate</th>
                    <th data-options="field:'amount'" width="80px" align="right">Amount</th>
                </tr>
            </thead>
        </table>
    </div>
    
    
    <div id="bacgwcWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="bacReportgwcTbl" style="width:900px">
            <thead>
                <tr>
                    <th data-options="field:'gmt_item'" width="150">GMT Item</th>
                    <th data-options="field:'emb_type'" width="150">Emb Type </th>
                    <th data-options="field:'gmt_part_name'" width="250px">GMT Part</th>
                    <th data-options="field:'embelishment_size'" width="70px" align="right">Emb Size</th>
                    <th data-options="field:'qty'" width="70px" align="right">Qty</th>
                    <th data-options="field:'rate'" width="70px" align="right">Rate</th>
                    <th data-options="field:'amount'" width="80px" align="right">Amount</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div id="owbgwcWindow2" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="OrdbudgetReportgwcTbl2" style="width:900px">
            <thead>
                <tr>
                    <th data-options="field:'gmt_item'" width="150">GMT Item</th>
                    <th data-options="field:'emb_type'" width="150">Emb Type </th>
                    <th data-options="field:'gmt_part_name'" width="250px">GMT Part</th>
                    <th data-options="field:'embelishment_size'" width="70px" align="right">Emb Size</th>
                    <th data-options="field:'qty'" width="70px" align="right">Qty</th>
                    <th data-options="field:'rate'" width="70px" align="right">Rate</th>
                    <th data-options="field:'amount'" width="80px" align="right">Amount</th>
                </tr>
            </thead>
        </table>
    </div>
    
    
    <div id="bacothWindow" class="easyui-window" title="Other Cost Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="bacReportothTbl" style="width:900px">
            <thead>
                <tr>
                    <th data-options="field:'name'" width="150">Name</th>
                    <th data-options="field:'amount'" width="80px" align="right">Amount</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div id="owbothWindow2" class="easyui-window" title="Other Cost Total Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="OrdbudgetReportothTbl2" style="width:900px">
            <thead>
              
                  <tr>
                    <th data-options="field:'name'" width="150">Name</th>
                    <th data-options="field:'amount'" width="80px" align="right">Amount</th>
                </tr>
                
            </thead>
        </table>
    </div>
    
    <div id="owbsalesorderWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <div id="owbscs"></div>
    </div>
    
    {{-- Dealing Merchant --}}
    <div id="dlmerchantWindow" class="easyui-window" title="Team Leader & Merchandiser Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="dealmctinfoTbl" style="width:100%;height:250px">
            <thead>
                <tr>
                    <th data-options="field:'name'" width="120">Name</th>
                    <th data-options="field:'date_of_join'" width="80px">Date Of Join </th>
                    <th data-options="field:'last_education'" width="100px">Last Education</th>
                    <th data-options="field:'contact'" width="100px">Contact</th>
                    <th data-options="field:'experience'" width="100px">Experience</th>
                    <th data-options="field:'address'" width="350px">Address</th>
                    <th data-options="field:'email'" width="150px">Email</th>
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
    {{-- File Src --}}
    <div id="bacfilesrcwindow" class="easyui-window" title="Style Uploaded Files" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="bacfilesrcTbl" style="width:500px">
            <thead>
                <tr>
                    <th data-options="field:'id'" width="30">ID</th>
                    <th data-options="field:'file_src'" width="180px">File Source </th>
                    <th data-options="field:'original_name'" formatter="MsBudgetAndCostingComparison.formatShowBacFile" width="250px">Original Name</th>
                </tr>
            </thead>
        </table>
    </div>

    <div id="mnccconsdznWindow" class="easyui-window" title="Yarn Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="mnccReportconsdznTbl" style="width:900px">
            <thead>
                <tr>
                    <th data-options="field:'gmt_item_description'" width="150">GMT Item</th>
                    <th data-options="field:'gmt_part_name'" width="150">GMT Parts</th>
                    <th data-options="field:'fab_des'" width="250px">Fabric</th>
                    <th data-options="field:'qty',halign:'center'" width="70px" align="right">Order <br/> Qty</th>
                    <th data-options="field:'extra_percent',halign:'center'" width="70px" align="right">Excess <br/> Cut %</th>
                    <th data-options="field:'plan_cut_qty',halign:'center'" width="70px" align="right">Plan Cut <br/> Qty</th>

                    <th data-options="field:'fin_fab',halign:'center'" width="70px" align="right">Finish <br/>Fab Qty</th>
                    <th data-options="field:'process_loss',halign:'center'" width="70px" align="right">Process <br/>Loss</th>
                    <th data-options="field:'grey_fab',halign:'center'" width="70px" align="right">Grey <br/> Fab Qty</th>
                    <th data-options="field:'req_cons',halign:'center'" width="70px" align="right">Grey Fab <br/> Cons/Dzn</th>
                    <th data-options="field:'cons',halign:'center'" width="70px" align="right">Fin Fab <br/> Cons/Dzn</th>
                </tr>
            </thead>
        </table>
    </div>

    <div id="mnccconsdznWindow2" class="easyui-window" title="Yarn Subtotal Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="mnccReportconsdznTbl2" style="width:900px">
            <thead>
                <tr>
                    <th data-options="field:'yarn_des'" width="350px">Yarn</th>
                    <th data-options="field:'yarn_qty'" width="100px" align="right">Yarn<br/> Qty</th>
                    <th data-options="field:'rate'" width="70px" align="right">Rate/kg</th>
                    <th data-options="field:'yarn_amount'" width="100px" align="right">Amount</th>
                </tr>
            </thead>
        </table>
    </div>

    
    <div id="bacbepWindow" class="easyui-window" title="BEP Details " data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <div id="bacbepmatrix">
        </div>
    </div>

    <div id="bacbepgpcWindow" class="easyui-window" title="BEP Details " data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <div id="bacbepgpcmatrix">
        </div>
    </div>
    
    <script type="text/javascript" src="<?php echo url('/');?>/js/report/MsBudgetAndCostingComparisonController.js"></script>
    <script>
        $(".datepicker" ).datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true
        });
    </script>