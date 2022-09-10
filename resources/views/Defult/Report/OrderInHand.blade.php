<div class="easyui-layout animated rollIn"  data-options="fit:true">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="orderinhandTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="title:'company_code',halign:'center'" width="80">1</th>
        <th data-options="title:'produced_company_id',halign:'center'" width="60">2</th>
        <th data-options="title:'buyer',halign:'center'" width="60">3</th>
        <th data-options="title:'style_ref',halign:'center'" width="80" >4</th>
        <th data-options="title:'sale_order_receive_date',halign:'center'" width="80">5</th>
        <th data-options="title:'delivery_date',halign:'center'" width="80">6</th>
        <th data-options="title:'lead_time',halign:'center'" width="80">7</th>
        <th data-options="title:'teammember',halign:'center'" width="100">8</th>
        <th data-options="title:'season',halign:'center'" width="80">9</th>
        <th data-options="title:'sale_order_no',halign:'center'" width="80">10</th>
        <th data-options="title:'lc_sc_no',halign:'center'" width="80">11</th>
        <th data-options="title:'productdepartment',halign:'center'" width="80">12</th>      
        <th data-options="title:'flie_src',halign:'center',align:'center'" width="30">13</th>
        <th data-options="title:'qty',halign:'center'" nowrap="false" width="80" align="right">14</th>
        <th data-options="title:'rate',halign:'center'" width="60" align="right">15</th>
        <th data-options="title:'amount',halign:'center'" width="80" align="right">16</th>
        <th data-options="title:'ship_qty',halign:'center'" width="80" align="right">17</th>
        <th data-options="title:'ship_balance',halign:'center'" width="80" align="right">18</th> 
        <th data-options="title:'fin_fab_cons',halign:'center'" width="100" align="right">19</th> 
        <th data-options="title:'fab_pur_amount',halign:'center'" width="100" align="right">20</th>
        <th data-options="title:'yarn_amount',halign:'center'" width="100" align="right">21</th>
        <th data-options="title:'trim_amount',halign:'center'" width="100" align="right">22</th>
        <th data-options="title:'yarn_dying_amount',halign:'center'" width="80" align="right">23</th>
        <th data-options="title:'kniting_amount',halign:'center'" width="100" align="right">24</th>
        <th data-options="title:'dying_amount',halign:'center'"  width="100" align="right">25</th>
        <th data-options="title:'aop_amount',halign:'center'" width="100" align="right">26</th>
        <th data-options="title:'burn_out_amount',halign:'center'" width="80" align="right">27</th>
        <th data-options="title:'washing_amount',halign:'center'" width="100" align="right">28</th>
        
        <th data-options="title:'printing_amount',halign:'center'" width="80" align="right">29</th>
        <th data-options="title:'emb_amount',halign:'center'" width="100" align="right">30</th>
        <th data-options="title:'spemb_amount',halign:'center'" width="100" align="right">31</th>
        <th data-options="title:'gmt_dyeing_amount',halign:'center'" width="100" align="right">32</th>
        <th data-options="title:'gmt_washing_amount',halign:'center'" width="100" align="right">33</th>

        <th data-options="title:'courier_amount',halign:'center'" width="80" align="right">34</th>
        <th data-options="title:'freight_amount',halign:'center'" width="100" align="right">35</th>
        <th data-options="title:'cm_amount',halign:'center'" width="100" align="right">36</th>
        <th data-options="title:'commi_amount',halign:'center'" width="100" align="right">37</th>
        <th data-options="title:'commer_amount',halign:'center'" width="80" align="right">38</th>
        <th data-options="title:'total_amount',halign:'center'"  width="100" align="right">39</th>
        <th data-options="title:'total_profit',halign:'center'" width="100" align="right">40</th>
        <th data-options="title:'total_profit_per',halign:'center'" width="100" align="right">41</th>
        
    </tr>
    <tr>
        <th data-options="field:'company_code',halign:'center'" width="80">Beneficiary</th>
        <th data-options="field:'produced_company_id',halign:'center'" width="60">Producing. <br/>Unit</th>
         <th data-options="field:'buyer',halign:'center'" width="60">Buyer</th>
         <th data-options="field:'style_ref',halign:'center'" width="80"  formatter="MsOrderInHand.formatfiles" >Style No</th>
<!--         <th data-options="field:'internal_ref',halign:'center'" width="80">Inter.Ref. No</th>
 -->        <th data-options="field:'sale_order_receive_date',halign:'center'" width="80">Order <br/>Recv Date</th>
        <th data-options="field:'delivery_date',halign:'center'" width="80">Delivery Date</th>
        <th data-options="field:'lead_time',halign:'center'" width="60">Lead Time</th>
        <th data-options="field:'teammember',halign:'center'" width="100">Dealing <br/>Merchant</th>
        <!-- <th data-options="field:'delivery_month',halign:'center'" width="60">Delv <br/>Month</th> -->
        <th data-options="field:'season',halign:'center'" width="80">Season</th>
        <th data-options="field:'sale_order_no',halign:'center'" width="80">Order No</th>
        <th data-options="field:'lc_sc_no',halign:'center'" width="80" formatter="MsOrderInHand.formatlcsc">LC/SC No</th>
        <th data-options="field:'productdepartment',halign:'center'" width="80">Prod. Dept</th>
        
        <th data-options="field:'flie_src',halign:'center',align:'center'" formatter="MsOrderInHand.formatimage" width="30">Image</th>
        <th data-options="field:'qty',halign:'center'" nowrap="false" formatter="MsOrderInHand.formatSaleOrder"   width="80" align="right">Order Qty <br/>(Pcs)</th>
        <th data-options="field:'rate',halign:'center'" width="60" align="right">Price <br/>(Pcs)</th>
        <th data-options="field:'amount',halign:'center'" width="80" align="right">Selling Value</th>
        <th data-options="field:'ship_qty',halign:'center'" width="80" align="right">Ship Qty</th>
        <th data-options="field:'ship_balance',halign:'center'" width="80" align="right">Yet to Ship</th>
        <th data-options="field:'fin_fab_cons',halign:'center'" formatter="MsOrderInHand.formatconsdzn" width="100" align="right">Fin. Cons/Dzn</th>
        <th data-options="field:'fab_pur_amount',halign:'center'" formatter="MsOrderInHand.formatFabPur" width="100" align="right">Fab.Purchase</th> 

        <th data-options="field:'yarn_amount',halign:'center'" formatter="MsOrderInHand.formatYarn" width="100" align="right">Yarn</th>
        <th data-options="field:'trim_amount',halign:'center'" formatter="MsOrderInHand.formatTrim" width="100" align="right">Trim</th>
        <th data-options="field:'yarn_dying_amount',halign:'center'" formatter="MsOrderInHand.formatYarnDying" width="80" align="right">Yarn Dyeing</th>
        <th data-options="field:'kniting_amount',halign:'center'" formatter="MsOrderInHand.formatKnit" width="100" align="right">Knitting</th>
        
        <!-- <th data-options="field:'weaving_amount',halign:'center'" formatter="MsOrderInHand.formatWeaving" width="100" align="right">Weaving</th> -->
        <th data-options="field:'dying_amount',halign:'center'" formatter="MsOrderInHand.formatDyeing"  width="100" align="right">Dyeing</th>
        <th data-options="field:'aop_amount',halign:'center'" formatter="MsOrderInHand.formatAop" width="100" align="right">AOP</th>

        <th data-options="field:'burn_out_amount',halign:'center'" formatter="MsOrderInHand.formatBurnOut" width="80" align="right">Burn Out</th>
        <!-- <th data-options="field:'finishing_amount',halign:'center'" formatter="MsOrderInHand.formatFinishing" width="100" align="right">Finishing</th> -->
        <th data-options="field:'washing_amount',halign:'center'" formatter="MsOrderInHand.formatWashing" width="100" align="right">Washing</th>
        
        <th data-options="field:'printing_amount',halign:'center'" formatter="MsOrderInHand.formatPrinting" width="80" align="right">Screen <br/>Printing</th>
        <th data-options="field:'emb_amount',halign:'center'" formatter="MsOrderInHand.formatEmb" width="100" align="right">Embroidery</th>
        <th data-options="field:'spemb_amount',halign:'center'" formatter="MsOrderInHand.formatSpEmb" width="100" align="right">Sp. Embelishment</th>
        <th data-options="field:'gmt_dyeing_amount',halign:'center'" formatter="MsOrderInHand.formatGmtDying" width="100" align="right">GMT Dyeing</th>
        <th data-options="field:'gmt_washing_amount',halign:'center'" formatter="MsOrderInHand.formatGmtWashing" width="100" align="right">GMT Washing</th>

        <th data-options="field:'courier_amount',halign:'center'" formatter="MsOrderInHand.formatCourier" width="80" align="right">Others</th>
        <th data-options="field:'freight_amount',halign:'center'" formatter="MsOrderInHand.formatFreight" width="100" align="right">Freight</th>
        <th data-options="field:'cm_amount',halign:'center'" formatter="MsOrderInHand.formatCm" width="100" align="right">CM</th>
        <th data-options="field:'commi_amount',halign:'center'" formatter="MsOrderInHand.formatCommi" width="100" align="right">Commission</th>
        <th data-options="field:'commer_amount',halign:'center'" formatter="MsOrderInHand.formatCommer" width="80" align="right">Commercial</th>
        <th data-options="field:'total_amount',halign:'center'"  width="100" align="right">Total Cost</th>
        <th data-options="field:'total_profit',halign:'center'" width="100" align="right">Profit</th>
        <th data-options="field:'total_profit_per',halign:'center'" width="100" align="right">Profit %</th>
        
   </tr>
</thead>
</table>
</div>
<div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
<form id="orderinhandFrm">
    <div id="container">
         <div id="body">
           <code>
                <div class="row middle">
                    <div class="col-sm-4">Buyer </div>
                    <div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}</div>
                </div>
                <div class="row">
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
                    <div class="col-sm-4 req-text">Year </div>
                    <div class="col-sm-8">{!! Form::select('year', $years,$selected_year,array('id'=>'year')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Month Range </div>
                    <div class="col-sm-4" style="padding-right:0px">
                        {!! Form::select('month_from', $months,1,array('id'=>'month_from','placeholder'=>'Month From')) !!}
                    </div>
                    <div class="col-sm-4" style="padding-left:0px">
                        {!! Form::select('month_to', $months,'12',array('id'=>'month_to','placeholder'=>'Month To')) !!}
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Status </div>
                    <div class="col-sm-8">{!! Form::select('order_status', $status,'1',array('id'=>'order_status')) !!}</div>
                </div>
          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsOrderInHand.show()">Show</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsOrderInHand.resetForm('orderinhandFrm')" >Reset</a>
        
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsOrderInHand.showExcel('orderinhandTbl','orderinhandTbl')">XLS</a>
    </div>

  </form>
</div>
</div>
<div id="orderWiseBudgetImageWindow" class="easyui-window" title="Image" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:500px;height:500px;padding:2px;">
    <img id="orderWiseBudgetImageWindowoutput" src=""/>
</div>
<div id="owbyarnWindow" class="easyui-window" title="Yarn Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="OrdbudgetReportyarnTbl" style="width:900px">
        <thead>
            <tr>
                <th data-options="field:'gmt_item_description'" width="150">GMT Item</th>
                <th data-options="field:'gmt_part_name'" width="150">GMT Parts</th>
                <th data-options="field:'fab_des'" width="250px">Fabric</th>
                <th data-options="field:'yarn_des'" width="250px">Yarn</th>
                <th data-options="field:'req_cons'" width="70px" align="right">Fabric<br/> Cons</th>
                <th data-options="field:'ratio'" width="70px" align="right">Ratio</th>
                <th data-options="field:'cons'" width="70px" align="right">Yarn<br/> Qty</th>
                <th data-options="field:'rate'" width="70px" align="right">Rate/kg</th>
                <th data-options="field:'yarn_amount'" width="80px" align="right">Amount</th>
            </tr>
        </thead>
    </table>
</div>

<div id="owbyarnWindow2" class="easyui-window" title="Yarn Subtotal Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="OrdbudgetReportyarnTbl2" style="width:900px">
        <thead>
            <tr>
                <th data-options="field:'yarn_des'" width="350px">Yarn</th>
                <th data-options="field:'yarn_qty'" width="100px" align="right">Yarn<br/> Qty</th>
                <th data-options="field:'rate'" width="70px" align="right">Rate/kg</th>
                <th data-options="field:'yarn_amount'" width="100px" align="right">Amount</th>
                <th data-options="field:'po_qty'" width="100px" align="right">Po Qty</th>
                <th data-options="field:'po_rate'" width="100px" align="right">Po Rate</th>
                <th data-options="field:'po_amount'" width="100px" align="right">Po Amount</th>
                <th data-options="field:'bal_po_qty'" width="100px" align="right">Balance Qty</th>
                <th data-options="field:'bal_po_amount'" width="100px" align="right">Balance Amount</th>
            </tr>
        </thead>
    </table>
</div>

<div id="owbtrimWindow" class="easyui-window" title="Trims Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="OrdbudgetReporttrimTbl" style="width:900px">
        <thead>
            <tr>
            <th data-options="field:'name'" width="250px">Name</th>
            <th data-options="field:'description'" width="250px">Description</th>
            <th data-options="field:'code'" width="50px">UOM</th>
            <th data-options="field:'bom_trim'" width="70px" align="right">BOM Qty</th>
            <th data-options="field:'rate'" width="70px" align="right">Rate</th>
            <th data-options="field:'trim_amount'" width="80px" align="right">Amount</th>
            <th data-options="field:'po_qty'" width="80px" align="right">Po Qty</th>
            <th data-options="field:'po_rate'" width="80px" align="right">Po Rate</th>
            <th data-options="field:'po_amount'" width="80px" align="right">Po Amount</th>
            <th data-options="field:'rate_var',styler:MsOrderInHand.trimratevar" width="80px" align="right">Rate Variance</th>
            <th data-options="field:'amount_var',styler:MsOrderInHand.trimamountvar" width="80px" align="right">Amount Variance</th>
            <th data-options="field:'lc_amount'" width="80px" align="right">LC Amount</th>

            </tr>
        </thead>
    </table>
</div>

<div id="owbtrimWindow2" class="easyui-window" title="Trims subtotal Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="OrdbudgetReporttrimTbl2" style="width:900px">
        <thead>
            <tr>
            <th data-options="field:'name'" width="250px">Name</th>
            <th data-options="field:'description'" width="250px">Description</th>
            <th data-options="field:'code'" width="50px">UOM</th>
            <th data-options="field:'bom_trim'" width="70px" align="right">Qty</th>
            <th data-options="field:'rate'" width="70px" align="right">Rate</th>
            <th data-options="field:'trim_amount'" width="80px" align="right">Amount</th>
            <th data-options="field:'po_qty'" width="80px" align="right">Po Qty</th>
            <th data-options="field:'po_rate'" width="80px" align="right">Po Rate</th>
            <th data-options="field:'po_amount'" width="80px" align="right">Po Amount</th>
            <th data-options="field:'rate_var'" width="80px" align="right">Rate Variance</th>
            <th data-options="field:'amount_var'" width="80px" align="right">Amount Variance</th>
            <th data-options="field:'lc_amount'" width="80px" align="right">LC Amount</th>

            </tr>
        </thead>
    </table>
</div>

<div id="owbfabpurWindow" class="easyui-window" title="Fabric Purchase Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="OrdbudgetReportfabpurTbl" style="width:900px">
        <thead>
            <tr>
                <th data-options="field:'gmt_item_description'" width="150">GMT Item</th>
                <th data-options="field:'gmt_part_name'" width="150">GMT Parts</th>
                <th data-options="field:'fab_des'" width="250px">Fabric</th>
                <th data-options="field:'grey_qty'" width="70px" align="right">Fab.Qty</th>
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
                <th data-options="field:'gmt_item_description'" width="150">GMT Item</th>
                <th data-options="field:'gmt_part_name'" width="150">GMT Parts</th>
                <th data-options="field:'fab_des'" width="250px">Fabric</th>
                <th data-options="field:'grey_qty'" width="70px" align="right">Fab.Qty</th>
                <th data-options="field:'rate'" width="70px" align="right">Rate/kg</th>
                <th data-options="field:'fab_pur_amount'" width="80px" align="right">Amount</th>
            </tr>
        </thead>
    </table>
</div>

<div id="owbknitWindow" class="easyui-window" title="Knitting Cost Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="OrdbudgetReportknitTbl" style="width:900px">
        <thead>
            <tr>
                <th data-options="field:'gmt_item_description'" width="150">GMT Item</th>
                <th data-options="field:'gmt_part_name'" width="150">GMT Parts</th>
                <th data-options="field:'fab_des'" width="250px">Fabric</th>
                <th data-options="field:'kniting_qty'" width="70px" align="right">Gray <br/> Fab.Qty</th>
                <th data-options="field:'rate'" width="70px" align="right">Knitting<br/>Charge/kg</th>
                <th data-options="field:'kniting_amount'" width="80px" align="right">Amount</th>
            </tr>
        </thead>
    </table>
</div>

<div id="owbknitWindow2" class="easyui-window" title="Knitting Subtotal Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="OrdbudgetReportknitTbl2" style="width:900px">
        <thead>
            <tr>
                <th data-options="field:'fab_des'" width="250px">Fabric</th>
                <th data-options="field:'kniting_qty'" width="70px" align="right">Gray <br/> Fab.Qty</th>
                <th data-options="field:'rate'" width="70px" align="right">Knitting<br/>Charge/kg</th>
                <th data-options="field:'kniting_amount'" width="80px" align="right">Amount</th>
            </tr>
        </thead>
    </table>
</div>

<div id="owbyarndyeingWindow" class="easyui-window" title="Yarn Dyeing Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
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

<div id="owbyarndyeingWindow2" class="easyui-window" title="Yarn Dyeing Total Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
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


<div id="owbdyeingWindow" class="easyui-window" title="Dyeing Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="OrdbudgetReportdyeingTbl" style="width:900px">
        <thead>
            <tr>
                <th data-options="field:'gmt_item_description'" width="100">GMT Item</th>
                <th data-options="field:'gmt_part_name'" width="100">GMT Parts</th>
                <th data-options="field:'fab_des'" width="200px">Fabric</th>
                <th data-options="field:'process_name'" width="70px">Process</th>
                <th data-options="field:'looks'" width="70px" align="right">Look</th>
                <th data-options="field:'shape'" width="70px" align="right">Shape</th>
                <th data-options="field:'dyetype'" width="70px" align="right">Dye Type</th>
                <th data-options="field:'fabric_color'" width="150px">Color </th>
                <th data-options="field:'qty'" width="70px" align="right">Qty</th>
                <th data-options="field:'rate'" width="70px" align="right">Rate/kg</th>
                <th data-options="field:'amount'" width="80px" align="right">Amount</th>
                <th data-options="field:'overhead_rate'" width="80px" align="right">Overhead<br/> Rate</th>
                <th data-options="field:'overhead_amount'" width="80px" align="right">Overhead<br/> Amount</th>
                <th data-options="field:'total_amount'" width="80px" align="right">Total Amount</th>
                <th data-options="field:'dye_charge_per_kg'" width="80px" align="right">Dye/Fin<br/> Charge/kg</th>

                
            </tr>
        </thead>
    </table>
</div>

<div id="owbdyeingWindow2" class="easyui-window" title="Dyeing Total Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
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
                <th data-options="field:'rate'" width="70px" align="right">Rate/kg</th>
                <th data-options="field:'amount'" width="80px" align="right">Amount</th>
                <th data-options="field:'overhead_rate'" width="80px" align="right">Overhead<br/> Rate</th>
                <th data-options="field:'overhead_amount'" width="80px" align="right">Overhead<br/> Amount</th>
                <th data-options="field:'total_amount'" width="80px" align="right">Total Amount</th>
                <th data-options="field:'dye_charge_per_kg'" width="80px" align="right">Dye/Fin<br/> Charge/kg</th>
            </tr>
        </thead>
    </table>
</div>

<div id="owbaopWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="OrdbudgetReportaopTbl" style="width:900px">
        <thead>
            <tr>
                <th data-options="field:'gmt_item_description'" width="100">GMT Item</th>
                <th data-options="field:'gmt_part_name'" width="120">GMT Parts</th>
                <th data-options="field:'fab_des'" width="130px">Fabric</th>
                <th data-options="field:'looks'" width="70px" align="right">Look</th>
                <th data-options="field:'shape'" width="70px" align="right">Shape</th>
                <th data-options="field:'aoptype'" width="70px" align="right">Aop Type</th>
                <th data-options="field:'coverage'" width="70px">Coverage </th>
                <th data-options="field:'impression'" width="50px">No Of<br/> Color </th>
                <th data-options="field:'qty'" width="70px" align="right">Qty</th>
                <th data-options="field:'rate'" width="60px" align="right">Rate</th>
                <th data-options="field:'amount'" width="80px" align="right">Amount</th>
                <th data-options="field:'overhead_rate'" width="80px" align="right">Overhead<br/> Rate</th>
                <th data-options="field:'overhead_amount'" width="80px" align="right">Overhead<br/> Amount</th>
                <th data-options="field:'total_amount'" width="80px" align="right">Total Amount</th>
                <th data-options="field:'aop_charge_per_kg'" width="80px" align="right">AOP <br/> Charge/kg</th>
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
                <th data-options="field:'overhead_rate'" width="80px" align="right">Overhead<br/> Rate</th>
                <th data-options="field:'overhead_amount'" width="80px" align="right">Overhead<br/> Amount</th>
                <th data-options="field:'total_amount'" width="80px" align="right">Total Amount</th>
                <th data-options="field:'aop_charge_per_kg'" width="80px" align="right">AOP <br/> Charge/kg</th>
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
                <th data-options="field:'overhead_rate'" width="80px" align="right">Overhead<br/> Rate</th>
                <th data-options="field:'overhead_amount'" width="80px" align="right">Overhead<br/> Amount</th>
                <th data-options="field:'total_amount'" width="80px" align="right">Total Amount</th>
                <th data-options="field:'screen_print_charge'" width="80px" align="right">Screen<br/>Printing<br/>Charge/dzn</th>
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
                <th data-options="field:'overhead_rate'" width="80px" align="right">Overhead<br/> Rate</th>
                <th data-options="field:'overhead_amount'" width="80px" align="right">Overhead<br/> Amount</th>
                <th data-options="field:'total_amount'" width="80px" align="right">Total Amount</th>
                <th data-options="field:'screen_print_charge'" width="80px" align="right">Screen<br/>Printing<br/>Charge/dzn</th>
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
 {{-- File Src --}}
 <div id="orderinhandfilesrcwindow" class="easyui-window" title="Style Uploaded Files " data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="orderinhandfilesrcTbl" style="width:500px">
        <thead>
            <tr>
                <th data-options="field:'id'" width="30">ID</th>
                <th data-options="field:'file_src'" width="180px">File</th>
                <th data-options="field:'original_name'" formatter="MsOrderInHand.formatShowFile" width="250px">Original Name</th>
            </tr>
        </thead>
    </table>
</div>

<div id="owbconsdznWindow" class="easyui-window" title="Cons Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="OrdbudgetReportconsdznTbl" style="width:900px">
        <thead>
            <tr>
                <th data-options="field:'gmt_item_description'" width="150">GMT Item</th>
                <th data-options="field:'gmt_part_name'" width="150">GMT Parts</th>
                <th data-options="field:'fab_des'" width="250px">Fabric</th>
                <th data-options="field:'qty',halign:'center'" width="70px" align="right">Order <br/> Qty</th>
                <th data-options="field:'extra_percent',halign:'center'" width="70px" align="right">Excess <br/> Cut %</th>
                <th data-options="field:'plan_cut_qty',halign:'center'" width="70px" align="right">Plan Cut <br/> Qty</th>

                <th data-options="field:'cad_req',halign:'center'" width="70px" align="right">Cad <br/>Cons</th>
                <th data-options="field:'unlayable_per',halign:'center'" width="70px" align="right">Unlayable %</th>
                <th data-options="field:'fin_fab',halign:'center'" width="70px" align="right">Finish <br/>Fab Qty</th>
                <th data-options="field:'process_loss',halign:'center'" width="70px" align="right">Process <br/>Loss</th>
                <th data-options="field:'grey_fab',halign:'center'" width="70px" align="right">Grey <br/> Fab Qty</th>
                <th data-options="field:'req_cons',halign:'center'" width="70px" align="right">Grey Fab <br/> Cons/Dzn</th>
                <th data-options="field:'cons',halign:'center'" width="70px" align="right">Fin Fab <br/> Cons/Dzn</th>
            </tr>
        </thead>
    </table>
</div>

<div id="owbconsdznWindow2" class="easyui-window" title="Yarn Subtotal Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="OrdbudgetReportconsdznTbl2" style="width:900px">
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

<div id="owblcscwindow" class="easyui-window" title="Dyed Yarn Received" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="owblcscTbl" style="width:500px">
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
<script type="text/javascript" src="<?php echo url('/');?>/js/report/MsOrderInHandController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script>