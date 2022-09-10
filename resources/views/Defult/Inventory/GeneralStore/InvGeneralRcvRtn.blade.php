<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="invgeneralrcvrtntabs">
    <div title="General Item Receive Return" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="invgeneralrcvrtnTbl" style="width:500px">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'supplier_id'" width="100">Supplier</th>
                            <th data-options="field:'location_id'" width="100">Location</th>
                            <th data-options="field:'issue_no'" width="100">Issue No</th>
                            <th data-options="field:'issue_date'" width="80">Issue Date</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Issue Reference',footer:'#invgeneralrcvrtnFrmFt'" style="width: 400px; padding:2px">
                <form id="invgeneralrcvrtnFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Issue No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="issue_no" id="issue_no" placeholder=" Issue No" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text" >Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text" >Supplier</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Location </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Issue Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="issue_date" id="issue_date" class="datepicker" placeholder=" yyyy-mm-dd" />
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4 req-text">Remarks</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="remarks" id="remarks" />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="invgeneralrcvrtnFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvGeneralRcvRtn.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invgeneralrcvrtnFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvGeneralRcvRtn.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="delete" onClick="MsInvGeneralRcvRtn.showPdf()">MRR</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
        
    <div title="Item" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Opening Balance',iconCls:'icon-more',footer:'#invgeneralrcvrtnitemFrmFt'" style="width:400px; padding:2px">
                <form id="invgeneralrcvrtnitemFrm" >
                    <div id="container">
                        <div id="body">
                            <code>                               
                                <div class="row">
                                    <div class="col-sm-4 req-text">Store Name</div>
                                    <div class="col-sm-8">
                                        {!! Form::select("store_id", $store,'',array('id'=>'store_id')) !!}
                                        <input type="hidden" name="inv_isu_id" id="inv_isu_id" value="" />
                                        <input type="hidden" name="id" id="id" value="" />
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Barcode</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="inv_rcv_id" id="inv_rcv_id" readonly   ondblclick="MsInvGeneralRcvRtnItem.openitemWindow()" />
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Item Description</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="item_desc" id="item_desc" disabled   />
                                        <input type="hidden" name="item_account_id" id="item_account_id" readonly/>
                                        <input type="hidden" name="inv_general_rcv_item_id" id="inv_general_rcv_item_id" readonly/>
                                    </div>
                                </div>

                                

                                <div class="row middle">
                                    <div class="col-sm-4">Specification</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="specification" id="specification" disabled/>
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4">UOM</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="uom_code" id="uom_code" value=""  disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Item Catg.</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="item_category" id="item_category" disabled>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Item Class</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="item_class" id="item_class" disabled/>
                                    </div>
                                </div>

                                
                                
                                
                               
                                
                                <div class="row middle">
                                    <div class="col-sm-4">Qnty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" value="" class="number integer" onchange="MsInvGeneralRcvRtnItem.calculate_qty_form()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Rate</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rate" id="rate"  class="number integer" readonly/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Amount</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount" id="amount"  class="number integer" readonly />
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4">Receive Rate</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="receive_rate" id="receive_rate"  class="number integer" disabled />
                                    </div>
                                </div>
                                 
                                
                               
                                
                               
                                 
                               
                                
                                <div class="row middle">
                                    <div class="col-sm-4">Room</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="room" id="room" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Rack</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rack" id="rack" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Shelf</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="shelf" id="shelf" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Box</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="box" id="box" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="remarks" id="remarks" value="" />
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4">Item ID</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="item_id" id="item_id" value="" class="number integer" disabled />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="invgeneralrcvrtnitemFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvGeneralRcvRtnItem.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invgeneralrcvrtnitemFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvGeneralRcvRtnItem.remove()">Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'List',footer:'#invgeneralrcvrtnitemTblFt'" style="padding:2px">
                <table id="invgeneralrcvrtnitemTbl" style="width:100%">
                    <thead>
                        <tr>
                            
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'category_name'" width="100">Item Category</th>
                        <th data-options="field:'class_name'" width="100">Item Class</th>
                        <th data-options="field:'sub_class_name'" width="100">Sub Class</th>
                        <th data-options="field:'item_description'" width="100">Description</th>
                        <th data-options="field:'specification'" width="100">Specification</th>
                        <th data-options="field:'qty'" width="100" align="right">Qty</th>
                        <th data-options="field:'uom_name'" width="100">UOM</th>
                        <th data-options="field:'rate'" width="100">Rate</th>
                        <th data-options="field:'amount'" width="100">Amount</th>
                        <th data-options="field:'receive_no'" width="100">Receive No</th>
                        <th data-options="field:'challan_no'" width="100">Challan No</th>
                        <th data-options="field:'receive_rate'" width="100" align="right">Receive Rate</th>
                        <th data-options="field:'receive_amount'" width="100" align="right">Receive Amount</th>
                        <th data-options="field:'remarks'" width="100">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>






<div id="invgeneralrcvrtnitemsearchwindow" class="easyui-window" title=" Yarn Item" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#invgeneralrcvrtnitemsearchFrmFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invgeneralrcvrtnitemsearchFrm">
                            <div class="row middle">
                            <div class="col-sm-4" >Receive No</div>
                            <div class="col-sm-8">
                            <input type="text" name="receive_no" id="receive_no" />
                            </div>
                            </div>
                             <div class="row middle">
                            <div class="col-sm-4" >Challan No</div>
                            <div class="col-sm-8">
                            <input type="text" name="challan_no" id="challan_no" />
                            </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4" >Item Class</div>
                            <div class="col-sm-8">
                            <input type="text" name="item_class" id="item_class" />
                            </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4">Item Desc</div>
                            <div class="col-sm-8">
                            <input type="text" name="item_desc" id="item_desc" />
                            </div>
                            </div>
                        </form>
                    </code>
                </div>
                <div id="invgeneralrcvrtnitemsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvGeneralRcvRtnItem.serachItem()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#invgeneralrcvrtnitemsearchTblFt'" style="padding:10px;">
            <table id="invgeneralrcvrtnitemsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="100">ID</th>
                        <th data-options="field:'category_name'" width="100">Item Category</th>
                        <th data-options="field:'class_name'" width="100">Item Class</th>
                        <th data-options="field:'sub_class_name'" width="100">Sub Class</th>
                        <th data-options="field:'item_description'" width="100">Description</th>
                        <th data-options="field:'specification'" width="100">Specification</th>
                        <th data-options="field:'uom_name'" width="100">UOM</th>
                        <th data-options="field:'challan_no'" width="100">Challan No</th>
                        <th data-options="field:'receive_no'" width="100">Receive No</th>
                        <th data-options="field:'qty'" width="100">Store Qty</th>
                        <th data-options="field:'rate'" width="100">Store Rate</th>
                        <th data-options="field:'amount'" width="100">Store Amount</th>

                        <th data-options="field:'receive_qty'" width="100">Receive Qty</th>
                        <th data-options="field:'receive_rate'" width="100">Receive Rate</th>
                        <th data-options="field:'receive_amount'" width="100">Receive Amount</th>
                    </tr>
                </thead>
            </table>
            <div id="invgeneralrcvrtnitemsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvGeneralRcvRtnItem.closeinvyarnrcvrtnitemWindow()">Next</a>
                </div>
        </div>
    </div>
</div>


<div id="invgeneralrcvrtnordersearchwindow" class="easyui-window" title=" Sales Order" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#invgeneralrcvrtnordersearchFrmFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invgeneralrcvrtnordersearchFrm">
                            <div class="row middle">
                            <div class="col-sm-4" >Order No</div>
                            <div class="col-sm-8">
                            <input type="text" name="order_no" id="order_no" />
                            </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4">Style Ref</div>
                            <div class="col-sm-8">
                            <input type="text" name="style_ref" id="style_ref" />
                            </div>
                            </div>
                        </form>
                    </code>
                </div>
                <div id="invgeneralrcvrtnordersearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvGeneralRcvRtnItem.serachOrder()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="invgeneralrcvrtnordersearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="100">ID</th>
                        <th data-options="field:'style_ref'" width="100">Style Ref</th>
                        <th data-options="field:'buyer_name'" width="100">Buyer</th>
                        <th data-options="field:'company_name'" width="100">Company</th>
                        <th data-options="field:'sale_order_no'" width="100">Sales Order No</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>



<script type="text/javascript" src="<?php echo url('/');?>/js/Inventory/GeneralStore/MsAllInvGeneralRcvRtnController.js"></script>
<script>
    $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script>
