<div class="easyui-layout animated rollIn"  data-options="fit:true">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="orderwisebudgetTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'company_code',halign:'center'" width="60">Company</th>
        <th data-options="field:'internal_ref',halign:'center'" width="80">Inter.Ref. No</th>
        <th data-options="field:'teammember',halign:'center'" width="100">Dealing <br/>Merchant</th>
        <th data-options="field:'delivery_date',halign:'center'" width="80">Delivery Date</th>
        <th data-options="field:'delivery_month',halign:'center'" width="60">Delv <br/>Month</th>
        <th data-options="field:'buyer',halign:'center'" width="60">Buyer</th>
        <th data-options="field:'style_ref',halign:'center'" width="80">Style No</th>
        <th data-options="field:'season',halign:'center'" width="80">Season</th>

        <th data-options="field:'sale_order_no',halign:'center'" width="80">Order No</th>
        <th data-options="field:'sale_order_receive_date',halign:'center'" width="80">Order <br/>Recv Date</th>
        <th data-options="field:'productdepartment',halign:'center'" width="80">Prod. Dept</th>
        
        <th data-options="field:'flie_src',halign:'center',align:'center'" formatter="MsOrderWiseBudget.formatimage" width="30">Image</th>
        <th data-options="field:'qty',halign:'center'" nowrap="false" formatter="MsOrderWiseBudget.formatSaleOrder"   width="80" align="right">Order Qty <br/>(Pcs)</th>
        <th data-options="field:'rate',halign:'center'" width="100" align="right">Price (Pcs)</th>
        <th data-options="field:'amount',halign:'center'" width="100" align="right">Selling Value</th>
        
        <th data-options="field:'yarn_amount',halign:'center'" formatter="MsOrderWiseBudget.formatYarn" width="100" align="right">Yarn</th>
        <th data-options="field:'trim_amount',halign:'center'" formatter="MsOrderWiseBudget.formatTrim" width="100" align="right">Trim</th>
        <th data-options="field:'yarn_dying_amount',halign:'center'" formatter="MsOrderWiseBudget.formatYarnDying" width="80" align="right">Yarn Dyeing</th>
        <th data-options="field:'kniting_amount',halign:'center'" formatter="MsOrderWiseBudget.formatKnit" width="100" align="right">Knitting</th>
        
        <!-- <th data-options="field:'weaving_amount',halign:'center'" formatter="MsOrderWiseBudget.formatWeaving" width="100" align="right">Weaving</th> -->
        <th data-options="field:'dying_amount',halign:'center'" formatter="MsOrderWiseBudget.formatDyeing"  width="100" align="right">Dyeing</th>
        <th data-options="field:'aop_amount',halign:'center'" formatter="MsOrderWiseBudget.formatAop" width="100" align="right">AOP</th>

        <th data-options="field:'burn_out_amount',halign:'center'" formatter="MsOrderWiseBudget.formatBurnOut" width="80" align="right">Burn Out</th>
        <!-- <th data-options="field:'finishing_amount',halign:'center'" formatter="MsOrderWiseBudget.formatFinishing" width="100" align="right">Finishing</th> -->
        <th data-options="field:'washing_amount',halign:'center'" formatter="MsOrderWiseBudget.formatWashing" width="100" align="right">Washing</th>
        
        <th data-options="field:'printing_amount',halign:'center'" formatter="MsOrderWiseBudget.formatPrinting" width="80" align="right">Printing</th>
        <th data-options="field:'emb_amount',halign:'center'" formatter="MsOrderWiseBudget.formatEmb" width="100" align="right">Embelishment</th>
        <th data-options="field:'spemb_amount',halign:'center'" formatter="MsOrderWiseBudget.formatSpEmb" width="100" align="right">Sp. Embelishment</th>
        <th data-options="field:'gmt_dyeing_amount',halign:'center'" formatter="MsOrderWiseBudget.formatGmtDying" width="100" align="right">GMT Dying</th>
        <th data-options="field:'gmt_washing_amount',halign:'center'" formatter="MsOrderWiseBudget.formatGmtWashing" width="100" align="right">GMT Washing</th>

        <th data-options="field:'courier_amount',halign:'center'" formatter="MsOrderWiseBudget.formatCourier" width="80" align="right">Others</th>
        <th data-options="field:'freight_amount',halign:'center'" formatter="MsOrderWiseBudget.formatFreight" width="100" align="right">Freight</th>
        <th data-options="field:'cm_amount',halign:'center'" formatter="MsOrderWiseBudget.formatCm" width="100" align="right">CM</th>
        <th data-options="field:'commi_amount',halign:'center'" formatter="MsOrderWiseBudget.formatCommi" width="100" align="right">Commission</th>
        <th data-options="field:'commer_amount',halign:'center'" formatter="MsOrderWiseBudget.formatCommer" width="80" align="right">Commercial</th>
        <th data-options="field:'total_amount',halign:'center'"  width="100" align="right">Total Cost</th>
        <th data-options="field:'total_profit',halign:'center'" width="100" align="right">Profit</th>
        <th data-options="field:'total_profit_per',halign:'center'" width="100" align="right">Profit %</th>
        <th data-options="field:'id',halign:'center'" width="100" align="right">ID</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
<form id="orderwisebudgetFrm">
    <div id="container">
         <div id="body">
           <code>

                <div class="row">
                    <div class="col-sm-4">Company</div>
                    <div class="col-sm-8">
                   {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}

                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Buyer </div>
                    <div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}</div>
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
                    <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                    </div>
                    <div class="col-sm-4" style="padding-left:0px">
                    <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                    </div>
                </div>
                 <div class="row middle">
                    <div class="col-sm-4">Status </div>
                    <div class="col-sm-8">{!! Form::select('order_status', $status,'',array('id'=>'order_status')) !!}</div>
                </div>
          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsOrderWiseBudget.get()">Show</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsOrderWiseBudget.resetForm('orderwisebudgetFrm')" >Reset</a>
    </div>

  </form>
</div>
</div>
<div id="orderWiseBudgetImageWindow" class="easyui-window" title="Image" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:500px;height:500px;padding:2px;">
    <img id="orderWiseBudgetImageWindowoutput" src=""/>
    </div>

    <div id="owbyarnWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="OrdbudgetReportyarnTbl" style="width:900px">
                    <thead>
                        <tr>
                            <th data-options="field:'gmt_item_description'" width="150">GMT Item</th>
                            <th data-options="field:'gmt_part_name'" width="150">GMT Parts</th>
                            
                            <th data-options="field:'fab_des'" width="250px">Fabric</th>
                            <th data-options="field:'yarn_des'" width="250px">Yarn</th>
                            <th data-options="field:'req_cons'" width="70px" align="right">Cons</th>
                            <th data-options="field:'ratio'" width="70px" align="right">Ratio</th>
                            <th data-options="field:'cons'" width="70px" align="right">Qty</th>
                            <th data-options="field:'rate'" width="70px" align="right">Rate</th>
                            <th data-options="field:'yarn_amount'" width="80px" align="right">Amount</th>
                        </tr>
                    </thead>
                </table>
    
    </div>

     <div id="owbyarnWindow2" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="OrdbudgetReportyarnTbl2" style="width:900px">
                    <thead>
                        <tr>
                            <th data-options="field:'yarn_des'" width="350px">Yarn</th>
                            <th data-options="field:'yarn_qty'" width="100px" align="right">Qty</th>
                            <th data-options="field:'rate'" width="70px" align="right">Rate</th>
                            <th data-options="field:'yarn_amount'" width="100px" align="right">Amount</th>
                        </tr>
                    </thead>
                </table>
               
    
    </div>

<div id="owbtrimWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="OrdbudgetReporttrimTbl" style="width:900px">
        <thead>
            <tr>
            <th data-options="field:'name'" width="250px">Name</th>
            <th data-options="field:'description'" width="250px">Description</th>
            <th data-options="field:'code'" width="50px">UOM</th>
            <th data-options="field:'bom_trim'" width="70px" align="right">Qty</th>
            <th data-options="field:'rate'" width="70px" align="right">Rate</th>
            <th data-options="field:'trim_amount'" width="80px" align="right">Amount</th>
            </tr>
        </thead>
    </table>
</div>

<div id="owbtrimWindow2" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="OrdbudgetReporttrimTbl2" style="width:900px">
        <thead>
            <tr>
            <th data-options="field:'name'" width="250px">Name</th>
            <th data-options="field:'description'" width="250px">Description</th>
            <th data-options="field:'code'" width="50px">UOM</th>
            <th data-options="field:'bom_trim'" width="70px" align="right">Qty</th>
            <th data-options="field:'rate'" width="70px" align="right">Rate</th>
            <th data-options="field:'trim_amount'" width="80px" align="right">Amount</th>
            </tr>
        </thead>
    </table>
</div>

<div id="owbknitWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="OrdbudgetReportknitTbl" style="width:900px">
        <thead>
            <tr>
                <th data-options="field:'gmt_item_description'" width="150">GMT Item</th>
                <th data-options="field:'gmt_part_name'" width="150">GMT Parts</th>
                <th data-options="field:'fab_des'" width="250px">Fabric</th>
                <th data-options="field:'kniting_qty'" width="70px" align="right">Qty</th>
                <th data-options="field:'rate'" width="70px" align="right">Rate</th>
                <th data-options="field:'kniting_amount'" width="80px" align="right">Amount</th>
            </tr>
        </thead>
    </table>
</div>

<div id="owbknitWindow2" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="OrdbudgetReportknitTbl2" style="width:900px">
        <thead>
            <tr>
                <th data-options="field:'fab_des'" width="250px">Fabric</th>
                <th data-options="field:'kniting_qty'" width="70px" align="right">Qty</th>
                <th data-options="field:'rate'" width="70px" align="right">Rate</th>
                <th data-options="field:'kniting_amount'" width="80px" align="right">Amount</th>
            </tr>
        </thead>
    </table>
</div>

<div id="owbyarndyeingWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="OrdbudgetReportyarndyeingTbl" style="width:900px">
        <thead>
            <tr>
                <th data-options="field:'gmt_item_description'" width="150">GMT Item</th>
                <th data-options="field:'gmt_part_name'" width="150">GMT Parts</th>
                <th data-options="field:'fab_des'" width="250px">Fabric</th>
                <th data-options="field:'fabric_color'" width="250px">Color </th>
                <th data-options="field:'yarn_dyeing_qty'" width="70px" align="right">Qty</th>
                <th data-options="field:'rate'" width="70px" align="right">Rate</th>
                <th data-options="field:'yarn_dyeing_amount'" width="80px" align="right">Amount</th>
            </tr>
        </thead>
    </table>
</div>

<div id="owbyarndyeingWindow2" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="OrdbudgetReportyarndyeingTbl2" style="width:900px">
        <thead>
            <tr>
                <th data-options="field:'fab_des'" width="250px">Fabric</th>
                <th data-options="field:'fabric_color'" width="250px">Color </th>
                <th data-options="field:'yarn_dyeing_qty'" width="70px" align="right">Qty</th>
                <th data-options="field:'rate'" width="70px" align="right">Rate</th>
                <th data-options="field:'yarn_dyeing_amount'" width="80px" align="right">Amount</th>
            </tr>
        </thead>
    </table>
</div>


<div id="owbdyeingWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="OrdbudgetReportdyeingTbl" style="width:900px">
        <thead>
            <tr>
                <th data-options="field:'gmt_item_description'" width="150">GMT Item</th>
                <th data-options="field:'gmt_part_name'" width="150">GMT Parts</th>
                <th data-options="field:'fab_des'" width="250px">Fabric</th>
                <th data-options="field:'process_name'" width="70px">Process</th>
                <th data-options="field:'looks'" width="70px" align="right">Look</th>
                <th data-options="field:'shape'" width="70px" align="right">Shape</th>
                <th data-options="field:'dyetype'" width="70px" align="right">Dye Type</th>
                <th data-options="field:'fabric_color'" width="250px">Color </th>
                <th data-options="field:'qty'" width="70px" align="right">Qty</th>
                <th data-options="field:'rate'" width="70px" align="right">Rate</th>
                <th data-options="field:'amount'" width="80px" align="right">Amount</th>
            </tr>
        </thead>
    </table>
</div>

<div id="owbdyeingWindow2" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="OrdbudgetReportdyeingTbl2" style="width:900px">
        <thead>
            <tr>
                <th data-options="field:'fab_des'" width="250px">Fabric</th>
                <th data-options="field:'process_name'" width="70px">Process</th>
                <th data-options="field:'looks'" width="70px" align="right">Look</th>
                <th data-options="field:'shape'" width="70px" align="right">Shape</th>
                <th data-options="field:'dyetype'" width="70px" align="right">Dye Type</th>
                <th data-options="field:'fabric_color'" width="250px">Color </th>
                <th data-options="field:'qty'" width="70px" align="right">Qty</th>
                <th data-options="field:'rate'" width="70px" align="right">Rate</th>
                <th data-options="field:'amount'" width="80px" align="right">Amount</th>
            </tr>
        </thead>
    </table>
</div>

<div id="owbaopWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="OrdbudgetReportaopTbl" style="width:900px">
        <thead>
            <tr>
                <th data-options="field:'gmt_item_description'" width="150">GMT Item</th>
                <th data-options="field:'gmt_part_name'" width="150">GMT Parts</th>
                <th data-options="field:'fab_des'" width="250px">Fabric</th>
                <th data-options="field:'looks'" width="70px" align="right">Look</th>
                <th data-options="field:'shape'" width="70px" align="right">Shape</th>
                <th data-options="field:'aoptype'" width="70px" align="right">Aop Type</th>
                <th data-options="field:'coverage'" width="70px">Coverage </th>
                <th data-options="field:'impression'" width="70px">Impression </th>
                <th data-options="field:'qty'" width="70px" align="right">Qty</th>
                <th data-options="field:'rate'" width="70px" align="right">Rate</th>
                <th data-options="field:'amount'" width="80px" align="right">Amount</th>
            </tr>
        </thead>
    </table>
</div>

<div id="owbaopWindow2" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="OrdbudgetReportaopTbl2" style="width:900px">
        <thead>
            <tr>
                <th data-options="field:'fab_des'" width="250px">Fabric</th>
                <th data-options="field:'looks'" width="70px" align="right">Look</th>
                <th data-options="field:'shape'" width="70px" align="right">Shape</th>
                <th data-options="field:'aoptype'" width="70px" align="right">Aop Type</th>
                <th data-options="field:'coverage'" width="70px">Coverage </th>
                <th data-options="field:'impression'" width="70px">Impression </th>
                <th data-options="field:'qty'" width="70px" align="right">Qty</th>
                <th data-options="field:'rate'" width="70px" align="right">Rate</th>
                <th data-options="field:'amount'" width="80px" align="right">Amount</th>
            </tr>
        </thead>
    </table>
</div>


<div id="owbbocWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="OrdbudgetReportbocTbl" style="width:900px">
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

<div id="owbbocWindow2" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
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


<div id="owbfwcWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="OrdbudgetReportfwcTbl" style="width:900px">
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

<div id="owbfwcWindow2" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
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


<div id="owbgpcWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="OrdbudgetReportgpcTbl" style="width:900px">
        <thead>
            <tr>
                <th data-options="field:'gmt_item'" width="150">GMT Item</th>
                <th data-options="field:'emb_type'" width="150">Print Type </th>
                <th data-options="field:'gmt_part_name'" width="250px">GMT Part</th>
                <th data-options="field:'embelishment_size'" width="70px" align="right">Print Size</th>
                <th data-options="field:'qty'" width="70px" align="right">Qty</th>
                <th data-options="field:'rate'" width="70px" align="right">Rate</th>
                <th data-options="field:'amount'" width="80px" align="right">Amount</th>
            </tr>
        </thead>
    </table>
</div>

<div id="owbgpcWindow2" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="OrdbudgetReportgpcTbl2" style="width:900px">
        <thead>
            <tr>
                <th data-options="field:'gmt_item'" width="150">GMT Item</th>
                <th data-options="field:'emb_type'" width="150">Print Type </th>
                <th data-options="field:'gmt_part_name'" width="250px">GMT Part</th>
                <th data-options="field:'embelishment_size'" width="70px" align="right">Print Size</th>
                <th data-options="field:'qty'" width="70px" align="right">Qty</th>
                <th data-options="field:'rate'" width="70px" align="right">Rate</th>
                <th data-options="field:'amount'" width="80px" align="right">Amount</th>
            </tr>
        </thead>
    </table>
</div>

<div id="owbgecWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="OrdbudgetReportgecTbl" style="width:900px">
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

<div id="owbgecWindow2" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
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


<div id="owbgsecWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="OrdbudgetReportgsecTbl" style="width:900px">
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

<div id="owbgsecWindow2" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
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


<div id="owbgdcWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="OrdbudgetReportgdcTbl" style="width:900px">
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


<div id="owbgwcWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="OrdbudgetReportgwcTbl" style="width:900px">
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


<div id="owbothWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="OrdbudgetReportothTbl" style="width:900px">
        <thead>
            <tr>
                <th data-options="field:'name'" width="150">Name</th>
                <th data-options="field:'amount'" width="80px" align="right">Amount</th>
            </tr>
        </thead>
    </table>
</div>

<div id="owbothWindow2" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
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

<script type="text/javascript" src="<?php echo url('/');?>/js/report/MsOrderWiseBudgetController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script>