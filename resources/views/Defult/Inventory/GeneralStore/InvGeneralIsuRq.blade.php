<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="invgeneralisurqtabs">
    <div title="General Item Requisition Entry" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="invgeneralisurqTbl" style="width:500px">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'location_id'" width="100">Location</th>
                            <th data-options="field:'rq_no'" width="100">Requisition No</th>
                            <th data-options="field:'rq_date'" width="80">Requisition Date</th>
                            <th data-options="field:'remarks'" width="80">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Receive Reference',footer:'#invgeneralisurqFrmFt'" style="width: 400px; padding:2px">
                <form id="invgeneralisurqFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Requisition No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rq_no" id="rq_no" placeholder=" Receive No" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text" >Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Location </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}
                                    </div>
                                </div>
                                
                                
                               
                                                               
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Requisition Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rq_date" id="rq_date" class="datepicker" placeholder=" yyyy-mm-dd" />
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
                    <div id="invgeneralisurqFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvGeneralIsuRq.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invgeneralisurqFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvGeneralIsuRq.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="delete" onClick="MsInvGeneralIsuRq.showPdf()">MRR</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
        
    <div title="Item" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Opening Balance',iconCls:'icon-more',footer:'#invgeneralisurqitemFrmFt'" style="width:400px; padding:2px">
                <form id="invgeneralisurqitemFrm" >
                    <div id="container">
                        <div id="body">
                            <code>                               
                                
                                
                                <div class="row">
                                    <div class="col-sm-4 req-text">Item Description</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="item_desc" id="item_desc" readonly   ondblclick="MsInvGeneralIsuRqItem.openitemWindow()" placeholder="Double Click" />
                                        <input type="hidden" name="item_account_id" id="item_account_id" readonly/>
                                        <input type="hidden" name="id" id="id" readonly/>
                                    </div>
                                </div>

                                

                                <div class="row middle">
                                    <div class="col-sm-4">UOM</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="uom_code" id="uom_code" value="" class="number integer" disabled />
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4">Sales Order</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sale_order_no" id="sale_order_no" readonly   ondblclick="MsInvGeneralIsuRqItem.openorderWindow()" placeholder="Double Click" />
                                        <input type="hidden" name="sale_order_id" id="sale_order_id" readonly/>
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4">Department</div>
                                    <div class="col-sm-8">
                                        {!! Form::select("department_id", $department,'',array('id'=>'department_id','style'=>'width:100%;border:2px;')) !!}
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4">Purpose</div>
                                    <div class="col-sm-8">
                                        {!! Form::select("purpose_id", $generalisurqpurpose,'',array('id'=>'purpose_id')) !!}
                                    </div>
                                </div>
                                
                                
                               
                                
                                <div class="row middle">
                                    <div class="col-sm-4">Qnty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" value="" class="number integer" />
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4">M/C No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="custom_no" id="custom_no" readonly  ondblclick="MsInvGeneralIsuRqItem.openmachineWindow()" placeholder="Double Click" />
                                        <input type="hidden" name="asset_quantity_cost_id" id="asset_quantity_cost_id" readonly/>
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
                    <div id="invgeneralisurqitemFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvGeneralIsuRqItem.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invgeneralisurqitemFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvGeneralIsuRqItem.remove()">Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'List',footer:'#invgeneralisurqitemTblFt'" style="padding:2px">
                <table id="invgeneralisurqitemTbl" style="width:100%">
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
                        <th data-options="field:'style_ref'" width="100">Style</th>
                        <th data-options="field:'sale_order_no'" width="100">Sale Order No</th>
                        <th data-options="field:'department_name'" width="100">Department</th>
                        <th data-options="field:'custom_no'" width="100">Machine</th>
                        <th data-options="field:'remarks'" width="100">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>






<div id="invgeneralisurqitemsearchwindow" class="easyui-window" title=" Yarn Item" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#invgeneralisurqitemsearchFrmFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invgeneralisurqitemsearchFrm">
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
                <div id="invgeneralisurqitemsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvGeneralIsuRqItem.serachItem()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#invgeneralisurqitemsearchTblFt'" style="padding:10px;">
            <table id="invgeneralisurqitemsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="100">ID</th>
                        <th data-options="field:'category_name'" width="100">Item Category</th>
                        <th data-options="field:'class_name'" width="100">Item Class</th>
                        <th data-options="field:'sub_class_name'" width="100">Sub Class</th>
                        <th data-options="field:'item_description'" width="100">Description</th>
                        <th data-options="field:'specification'" width="100">Specification</th>
                        <th data-options="field:'uom_name'" width="100">UOM</th>
                        <th data-options="field:'stock_qty'" width="100" align="right">Stock Qty</th>

                    </tr>
                </thead>
            </table>
            <div id="invgeneralisurqitemsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvGeneralIsuRqItem.closeinvyarnisurqitemWindow()">Next</a>
                </div>
        </div>
    </div>
</div>


<div id="invgeneralisurqordersearchwindow" class="easyui-window" title=" Sales Order" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#invgeneralisurqordersearchFrmFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invgeneralisurqordersearchFrm">
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
                <div id="invgeneralisurqordersearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvGeneralIsuRqItem.serachOrder()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="invgeneralisurqordersearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="100">ID</th>
                        <th data-options="field:'company_name'" width="100">Bnf. Company</th>
                        <th data-options="field:'pcompany_name'" width="100">Prod.Company</th>
                        <th data-options="field:'buyer_name'" width="100">Buyer</th>
                        <th data-options="field:'style_ref'" width="100">Style Ref</th>
                        <th data-options="field:'sale_order_no'" width="100">Sales Order No</th>
                        <th data-options="field:'team_member_name'" width="100">Dealing Merchanet</th>
                        <th data-options="field:'ship_date'" width="100">Ship Date</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>


<div id="invgeneralisurqmachinesearchwindow" class="easyui-window" title=" Sales Order" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#invgeneralisurqmachinesearchFrmFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invgeneralisurqmachinesearchFrm">
                            <div class="row ">
                            <div class="col-sm-4 req-text">Dia/Width</div>
                            <div class="col-sm-8"> <input type="text" name="dia_width" id="construction_name" /> </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4 req-text">Feeder</div>
                            <div class="col-sm-8">
                            <input type="text" name="composition_name" id="no_of_feeder" />
                            </div>
                            </div>
                        </form>
                    </code>
                </div>
                <div id="invgeneralisurqmachinesearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvGeneralIsuRqItem.serachMachine()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="invgeneralisurqmachinesearchTbl" style="width:100%">
                <thead>
                    <tr>
                    <th data-options="field:'id'" width="80">ID</th>
                    <th data-options="field:'custom_no'" width="100">Machine No</th>
                    <th data-options="field:'asset_name'" width="100">Asset Name</th>
                    <th data-options="field:'origin'" width="100">Origin</th>
                    <th data-options="field:'brand'" width="100">Brand</th>
                    <th data-options="field:'prod_capacity'" width="100">Prod. Capacity</th>
                    <th data-options="field:'dia_width'" width="60">Dia/Width</th>
                    <th data-options="field:'gauge'" width="60">Gauge</th>
                    <th data-options="field:'extra_cylinder'" width="60">Extra Cylinder</th>
                    <th data-options="field:'no_of_feeder'" width="60">Feeder</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>



<script type="text/javascript" src="<?php echo url('/');?>/js/Inventory/GeneralStore/MsAllInvGeneralIsuRqController.js"></script>
<script>
    $('#invgeneralisurqitemFrm [id="department_id"]').combobox();
    $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script>
