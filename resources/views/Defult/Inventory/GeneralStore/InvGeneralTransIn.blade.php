<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="invgeneraltransintabs">
    <div title="General Item Transfer In Entry" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="invgeneraltransinTbl" style="width:500px">
                    <thead>
                        <tr>
                            <th data-options="field:'inv_general_rcv_id'" width="40">ID</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'supplier_id'" width="100">Supplier</th>
                            <th data-options="field:'receive_no'" width="100">Receive No</th>
                            <th data-options="field:'receive_basis_id'" width="100">Receive Basis</th>
                            <th data-options="field:'challan_no'" width="80">Challan No</th>
                            <th data-options="field:'receive_date'" width="80">Receive Date</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Receive Reference',footer:'#invgeneraltransinFrmFt'" style="width: 400px; padding:2px">
                <form id="invgeneraltransinFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="inv_general_rcv_id" id="inv_general_rcv_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Receive No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="receive_no" id="receive_no" placeholder=" Receive No" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text" >Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                              
                                <div class="row middle">
                                    <div class="col-sm-4 req-text" >From Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('from_company_id', $company,'',array('id'=>'from_company_id')) !!}
                                    </div>
                                </div>
                               
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Challan No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="challan_no" id="challan_no" placeholder="Write Challan No" />
                                    </div>
                                </div>                                
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Receive Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="receive_date" id="receive_date" class="datepicker" placeholder=" yyyy-mm-dd" />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="invgeneraltransinFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvGeneralTransIn.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invgeneraltransinFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvGeneralTransIn.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="delete" onClick="MsInvGeneralTransIn.showPdf()">MRR</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
        
    <div title="Item" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Opening Balance',iconCls:'icon-more',footer:'#invgeneraltransinitemFrmFt'" style="width:400px; padding:2px">
                <form id="invgeneraltransinitemFrm" >
                    <div id="container">
                        <div id="body">
                            <code>                               
                                <div class="row">
                                    <div class="col-sm-4 req-text">Store Name</div>
                                    <div class="col-sm-8">
                                        {!! Form::select("store_id", $store,'',array('id'=>'store_id')) !!}
                                        <input type="hidden" name="inv_general_rcv_id" id="inv_general_rcv_id" value="" />
                                        <input type="hidden" name="id" id="id" value="" />
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4">Transfer No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="transfer_no" id="transfer_no" value="" class="number integer"  ondblclick="MsInvGeneralTransInItem.openitemWindow()" readonly placeholder="Double Click" />
                                        <input type="hidden" name="item_account_id" id="item_account_id" readonly    />
                                        <input type="hidden" name="inv_general_isu_item_id" id="inv_general_isu_item_id" readonly/>
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Item Description</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="item_desc" id="item_desc" disabled/>
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
                                    <div class="col-sm-4">UOM</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="uom_code" id="uom_code" value="" class="number integer" disabled />
                                    </div>
                                </div>

                                
                                
                                
                               
                                
                                <div class="row middle">
                                    <div class="col-sm-4">Qnty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" value="" class="number integer" onchange="MsInvGeneralTransInItem.calculate_qty_form()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Rate</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rate" id="rate" value="" class="number integer" onchange="MsInvGeneralTransInItem.calculate_qty_form()"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Amount</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount" id="amount" value="" class="number integer" readonly />
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
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="remarks" id="remarks" value="" />
                                    </div>
                                </div>
                                

                               
                                
                            </code>
                        </div>
                    </div>
                    <div id="invgeneraltransinitemFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvGeneralTransInItem.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invgeneraltransinitemFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvGeneralTransInItem.remove()">Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'List',footer:'#invgeneraltransinitemTblFt'" style="padding:2px">
                <table id="invgeneraltransinitemTbl" style="width:100%">
                    <thead>
                        <tr>
                            
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'transfer_no'" width="100">Transfer NO</th>

                        <th data-options="field:'category_name'" width="100">Item Category</th>
                        <th data-options="field:'class_name'" width="100">Item Class</th>
                        <th data-options="field:'sub_class_name'" width="100">Sub Class</th>
                        <th data-options="field:'item_description'" width="100">Description</th>
                        <th data-options="field:'specification'" width="100">Specification</th>
                        <th data-options="field:'qty'" width="100" align="right">Qty</th>
                        <th data-options="field:'uom_name'" width="100">UOM</th>
                        <th data-options="field:'rate'" width="100" align="right">Rate</th>
                        <th data-options="field:'amount'" width="100" align="right">Amount</th>
                            
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    
</div>
<div id="invgeneraltransinitemsearchwindow" class="easyui-window" title=" Yarn Item" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#invgeneraltransinitemsearchFrmFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invgeneraltransinitemsearchFrm">
                            <div class="row middle">
                            <div class="col-sm-4" >Challan No</div>
                            <div class="col-sm-8">
                            <input type="text" name="challan_no" id="challan_no" />
                            </div>
                            </div>
                        </form>
                    </code>
                </div>
                <div id="invgeneraltransinitemsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvGeneralTransInItem.serachItem()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#invgeneraltransinitemsearchTblFt'" style="padding:10px;">
            <table id="invgeneraltransinitemsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="100">ID</th>
                        <th data-options="field:'category_name'" width="100">Item Category</th>
                        <th data-options="field:'class_name'" width="100">Item Class</th>
                        <th data-options="field:'sub_class_name'" width="100">Sub Class</th>
                        <th data-options="field:'item_description'" width="100">Description</th>
                        <th data-options="field:'specification'" width="100">Specification</th>
                        <th data-options="field:'uom_name'" width="100">UOM</th>
                        <th data-options="field:'transfer_no'" width="100">Transfer No</th>
                        <th data-options="field:'qty'" width="100" align="right">Qty</th>
                        <th data-options="field:'rate'" width="100" align="right">Rate</th>
                        <th data-options="field:'amount'" width="100" align="right">Amount</th>
                        <th data-options="field:'currency_code'" width="100">Currency</th>
                    </tr>
                </thead>
            </table>
            <div id="invgeneraltransinitemsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvGeneralTransInItem.closeinvyarntransinitemWindow()">Next</a>
                </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Inventory/GeneralStore/MsAllInvGeneralTransInController.js"></script>
<script>
    $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script>
