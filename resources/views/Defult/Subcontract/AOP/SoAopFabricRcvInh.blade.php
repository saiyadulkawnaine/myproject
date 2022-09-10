<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="soaopfabricrcvinhtabs">
    <div title="Start Up" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="soaopfabricrcvinhTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'receive_no'" width="30">Receive No</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'buyer_id'" width="100">Customer</th>
                            <th data-options="field:'sales_order_no'" width="100">Sales Order NO</th>
                            <th data-options="field:'receive_date'" width="100">Receive Date</th>
                            <th data-options="field:'challan_no'" width="100">Challan No</th>
                            <th data-options="field:'remarks'" width="100">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Work Order Received',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#soknitFrmft'" style="width:450px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="soaopfabricrcvinhFrm">
                                <div class="row">
                                    <input type="hidden" name="id" id="id" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">MRR No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="receive_no" id="receive_no"  disabled />
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-5">Challan No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="challan_no" id="challan_no" ondblclick="MsSoAopFabricRcvInh.challanWindow()" placeholder="Double Click" readonly />
                                        <input type="hidden" name="prod_finish_dlv_id" id="prod_finish_dlv_id" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Customer</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-5 req-text">Sales Order No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="sales_order_no" id="sales_order_no" ondblclick="MsSoAopFabricRcvInh.soWindow()" placeholder="Double Click"/>
                                        <input type="hidden" name="so_aop_id" id="so_aop_id" />
                                    </div>
                                </div>



                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Company</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                
                               
                                                                  
                               
                                <div class="row middle">
                                    <div class="col-sm-5"> Receive Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="receive_date" id="receive_date" class="datepicker" />
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-5">Remarks </div>
                                    <div class="col-sm-7">
                                        <textarea name="remarks" id="remarks" value=""></textarea>
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="soknitFrmft" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoAopFabricRcvInh.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('soaopfabricrcvinhFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoAopFabricRcvInh.remove()">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Item Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'Lists',footer:'#soaopfabricrcvinhitemTblFt'" style="padding:2px">
                <table id="soaopfabricrcvinhitemTbl" style="width:100%">
                    <thead>
                         <tr>
                            <th data-options="field:'id'" width="100">ID</th>
                            <th data-options="field:'fabrication'" width="300">Fabrication</th>
                            <th data-options="field:'fabriclooks'" width="100">Fabric Looks</th>
                            <th data-options="field:'fabricshape'" width="100">Fabric Shape</th>
                            <th data-options="field:'gsm_weight'" width="70" align="center">GSM/Weight</th>
                            <th data-options="field:'aop_color'" width="80" align="left">AOP Color</th>
                            <th data-options="field:'colorrange_id'" width="70">Color Range</th>
                            <th data-options="field:'coverage'" width="70" align="right">Coverage %</th>
                            <th data-options="field:'impression'" width="70" align="right">Impression</th>
                            <th data-options="field:'qty'" width="70" align="right">Qty</th>
                            <th data-options="field:'rate'" width="70" align="right">Rate</th>
                            <th data-options="field:'amount'" width="100" align="right">Amount</th>
                            <th data-options="field:'style_ref'" width="100">Style Ref</th>
                            <th data-options="field:'buyer_name'" width="100">Buyer</th>
                            <th data-options="field:'sale_order_no'" width="100">Sales Order No</th>
                            <th data-options="field:'remarks'" width="80">Remarks</th>
                        </tr>
                    </thead>
                </table>
                <div id="soaopfabricrcvinhitemTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoAopFabricRcvInhItem.import()">Import</a>
                    </div>
            </div>
        </div>
    </div>
    <div title="Roll Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            
            <div data-options="region:'center',border:true,title:'Lists',footer:'#soaopfabricrcvinhrolTblFt'" style="padding:2px">
                <table id="soaopfabricrcvinhrolTbl" style="width:100%">
                    <thead>
                        <tr>
                          <th data-options="field:'id'" width="70">ID</th>
                          <th data-options="field:'issue_no'" width="70">Issue No</th>
                          <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
                          <th data-options="field:'custom_no'" width="70">Custom No</th>
                          <th data-options="field:'rcv_qty'" width="80">Receive Qty</th>
                          <th data-options="field:'room'" width="80">Room</th>
                          <th data-options="field:'rack'" width="80">Rack</th>
                          <th data-options="field:'shelf'" width="80">Shelf</th>

                          <th data-options="field:'body_part'" width="100">Body Part</th>
                          <th data-options="field:'fabrication'" width="200">Fabric <br/>Description</th>
                          <th data-options="field:'fabric_shape'" width="70">Fabric <br/>Shape</th>
                          <th data-options="field:'fabric_look'" width="70">Fabric <br/>Look</th>
                          <th data-options="field:'gsm_weight'" width="40">GSM</th>
                          <th data-options="field:'dia_width'" width="40">Dia/<br/>Widht</th>
                          <th data-options="field:'measurement'" width="70">Measurement</th>
                          <th data-options="field:'roll_length'" width="70">Roll Length</th>
                          <th data-options="field:'stitch_length'" width="70">Stitch<br/>Length</th>
                          <th data-options="field:'shrink_per'" width="60">Shrink %</th>
                          <th data-options="field:'colorrange_name'" width="80">Color Range</th>
                          <th data-options="field:'fabric_color'" width="80">AOP Color</th>
                          
                          <th data-options="field:'prod_no'" width="70">Prod. Ref</th>
                          <th data-options="field:'sale_order_no'" width="100">Gmt Order No</th>
                          <th data-options="field:'style_ref'" width="100">Style Ref</th>
                          <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
                          <th data-options="field:'gmt_sample'" width="100">Gmt Sample</th>
                          <th data-options="field:'supplier_name'" width="100">Produced By</th>
                          <th data-options="field:'customer_name'" width="100">Produced For</th>
                        </tr>
                    </thead>
                </table>
                    <div id="soaopfabricrcvinhrolTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">

                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoAopFabricRcvInhRol.import()">Import</a>
                    </div>
            </div>
            <div data-options="region:'west',border:true,title:'Roll',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#soaopfabricrcvinhrolFrmFt'" style="width:450px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="soaopfabricrcvinhrolFrm">
                                <div class="row">
                                    <input type="hidden" name="id" id="id" readonly />
                                    <input type="hidden" name="prod_finish_dlv_roll_id" id="prod_finish_dlv_roll_id" readonly/>
                                    <input type="hidden" name="so_aop_fabric_rcv_item_id" id="so_aop_fabric_rcv_item_id" readonly/>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Room</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="room" id="room" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Rack</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="rack" id="rack" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Shelf</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="shelf" id="shelf" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Remarks </div>
                                    <div class="col-sm-7">
                                        <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="soaopfabricrcvinhrolFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoAopFabricRcvInhRol.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('soaopfabricrcvinhrolFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoAopFabricRcvInhRol.remove()">Delete</a>
                </div>
            </div>
        </div>
    </div>
</div>
    


<div id="soaopfabricrcvinhsoWindow" class="easyui-window" title="AOP Order Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search',footer:'#soaopfabricrcvinhsoWindowFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="soaopfabricrcvinhsosearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">AOP Sales Order No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="so_no" id="so_no">
                                </div>
                            </div>
                           
                        </form>
                    </code>
                </div>
                 <div id="soaopfabricrcvinhsoWindowFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="border-radius:1px" onClick="MsSoAopFabricRcvInh.getsubconorder()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#soaopfabricrcvinhsosearchTblFt'" style="padding:10px;">
            <table id="soaopfabricrcvinhsosearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="30">ID</th>
                        <th data-options="field:'sales_order_no'" width="100">AOP Sales Order No</th>
                        <th data-options="field:'company_name'" width="100">Company</th>
                        <th data-options="field:'customer_name'" width="100">Customer</th>
                        <th data-options="field:'style_ref'" width="100">Style Ref</th>
                        <th data-options="field:'sale_order_no'" width="100">GMT Sales Order No</th>

                    </tr>
                </thead>
            </table>
             <div id="soaopfabricrcvinhsosearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#soaopfabricrcvinhsoWindow').window('close')" style="border-radius:1px">Close</a>
        </div>
        </div>
       
    </div>
</div>

<div id="soaopfabricrcvinhchallanWindow" class="easyui-window" title="Challan Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search',footer:'#soaopfabricrcvinhchallanWindowFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="soaopfabricrcvinhchallansearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Challan No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="challan_no" id="challan_no">
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Delivery Date </div>
                                <div class="col-sm-4" style="padding-right:0px">
                                <input type="text" name="from_dlv_date" id="from_dlv_date" class="datepicker" placeholder="From" />
                                </div>
                                <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="to_dlv_date" id="to_dlv_date" class="datepicker"  placeholder="To" />
                                </div>
                            </div>
                           
                        </form>
                    </code>
                </div>
                 <div id="soaopfabricrcvinhchallanWindowFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="border-radius:1px" onClick="MsSoAopFabricRcvInh.getchallan()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#soaopfabricrcvinhchallansearchTblFt'" style="padding:10px;">
            <table id="soaopfabricrcvinhchallansearchTbl" style="width:100%">
                <thead>
                    <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'dlv_no'" width="100">Dlv. No</th>
                            <th data-options="field:'dlv_date'" width="80">Dlv Date</th>
                            <th data-options="field:'company_name'" width="80">Company</th>
                            <th data-options="field:'location_name'" width="70">Location</th>
                            <th data-options="field:'store_name'" width="80">To Store</th>
                            <th data-options="field:'buyer_name'" width="70">Customer</th>
                            <th data-options="field:'remarks'" width="200">Remarks</th>
                            <th data-options="field:'driver_name'" width="100">Driver Name</th>
                            <th data-options="field:'driver_contact_no'" width="120">Driver Contact</th>
                            <th data-options="field:'driver_license_no'" width="120">Driver License No</th>
                            <th data-options="field:'lock_no'" width="80">Lock No</th>
                            <th data-options="field:'truck_no'" width="100">Truck No</th>

                    </tr>
                </thead>
            </table>
             <div id="soaopfabricrcvinhchallansearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#soaopfabricrcvinhchallanWindow').window('close')" style="border-radius:1px">Close</a>
        </div>
        </div>
       
    </div>
</div>



<div id="soaopfabricrcvinhitemWindow" class="easyui-window" title="Fabric Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center',footer:'#soaopfabricrcvinhitemWindowFt'" style="padding:10px;">
        <table id="soaopfabricrcvinhitemsearchTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'so_aop_ref_id'" width="100">ID</th>
                            <th data-options="field:'fabrication'" width="300">Fabrication</th>
                            <th data-options="field:'fabriclooks'" width="100">Fabric Looks</th>
                            <th data-options="field:'fabricshape'" width="100">Fabric Shape</th>
                            <th data-options="field:'gsm_weight'" width="70" align="center">GSM/Weight</th>
                            <th data-options="field:'aop_color'" width="80" align="left">AOP Color</th>
                            <th data-options="field:'colorrange_id'" width="70">Color Range</th>
                            <th data-options="field:'coverage'" width="70" align="right">Coverage %</th>
                            <th data-options="field:'impression'" width="70" align="right">Impression</th>
                            <th data-options="field:'qty'" width="70" align="right">Qty</th>
                            <th data-options="field:'rate'" width="70" align="right">Rate</th>
                            <th data-options="field:'amount'" width="100" align="right">Amount</th>
                            <th data-options="field:'style_ref'" width="100">Style Ref</th>
                            <th data-options="field:'buyer_name'" width="100">Buyer</th>
                            <th data-options="field:'sale_order_no'" width="100">Sales Order No</th>
                            <th data-options="field:'remarks'" width="80">Remarks</th>
                        </tr>
                    </thead>
                </table>
        <div id="soaopfabricrcvinhitemWindowFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoAopFabricRcvInhItem.submitBatch()">Save</a>

        </div>
      </div>  
       
    </div>
</div>

<div id="soaopfabricrcvinhrollWindow" class="easyui-window" title="Roll Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center',footer:'#soaopfabricrcvinhrollWindowFt'" style="padding:10px;">
        <table id="soaopfabricrcvrollsearchTbl" style="width:100%">
                    <thead>
                         <tr>
                          <th data-options="field:'id'" width="70">ID</th>
                          <th data-options="field:'issue_no'" width="100">Issue No</th>
                          <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
                          <th data-options="field:'custom_no'" width="70">Custom No</th>
                          <th data-options="field:'isu_qty'" width="80">Issue Qty</th>
                          <th data-options="field:'body_part'" width="100">Body Part</th>
                          <th data-options="field:'fabrication'" width="200">Fabric <br/>Description</th>
                          <th data-options="field:'fabric_shape'" width="70">Fabric <br/>Shape</th>
                          <th data-options="field:'fabric_look'" width="70">Fabric <br/>Look</th>
                          <th data-options="field:'gsm_weight'" width="40">GSM</th>
                          <th data-options="field:'dia_width'" width="40">Dia/<br/>Widht</th>
                          <th data-options="field:'measurement'" width="70">Measurement</th>
                          <th data-options="field:'roll_length'" width="70">Roll Length</th>
                          <th data-options="field:'stitch_length'" width="70">Stitch<br/>Length</th>
                          <th data-options="field:'shrink_per'" width="60">Shrink %</th>
                          <th data-options="field:'colorrange_name'" width="80">Color Range</th>
                          <th data-options="field:'fabric_color'" width="80">Fabric Color</th>
                          <th data-options="field:'prod_no'" width="70">Prod. Ref</th>
                          <th data-options="field:'sale_order_no'" width="100">Order No</th>
                          <th data-options="field:'style_ref'" width="100">Style Ref</th>
                          <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
                          <th data-options="field:'gmt_sample'" width="100">Gmt Sample</th>
                          <th data-options="field:'supplier_name'" width="100">Produced By</th>
                          <th data-options="field:'customer_name'" width="100">Produced For</th>
                          
                        </tr>
                    </thead>
                </table>
        <div id="soaopfabricrcvinhrollWindowFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
            Total:<span id="soaop_fabric_rcvinh_selected_roll_total" style="padding-right: 150px">0</span>
             <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoAopFabricRcvInhRol.selectAll()">Select All</a>
        <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoAopFabricRcvInhRol.unselectAll()">Unselect All</a>
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoAopFabricRcvInhRol.openMultiForm()">Save</a>
        </div>
      </div>  
    </div>
</div>

<div id="soaopfabricrcvinhrollmultiformWindow" class="easyui-window" title="Roll Form Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center',footer:'#soaopfabricrcvinhrollmultiformWindowFt'" style="padding:10px;">
            <div id ="soaopfabricrcvinhrollmultiformWindowscs">
            </div>
        
        <div id="soaopfabricrcvinhrollmultiformWindowFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoAopFabricRcvInhRol.submitBatch()">Save</a>
        </div>
      </div>  
    </div>
</div>




<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/AOP/MsSoAopFabricRcvInhController.js"></script>
 <script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/AOP/MsSoAopFabricRcvInhItemController.js"></script> 
 <script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/AOP/MsSoAopFabricRcvInhRolController.js"></script> 
<script>
$(document).ready(function() {
    $(".datepicker").datepicker({
        beforeShow:function(input) {
            $(input).css({
                "position": "relative",
                "z-index": 999999
            });
        },
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
    });
    $('#soaopfabricrcvinhitemFrm [id="uom_id"]').combobox();
    $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
        }
    });
});
</script>
    