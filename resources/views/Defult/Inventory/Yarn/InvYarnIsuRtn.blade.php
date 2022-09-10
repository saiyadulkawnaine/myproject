<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="invyarnisurtntabs">
    <div title="Yarn Issue Return" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="invyarnisurtnTbl" style="width:500px">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'receive_no'" width="40">Receive No</th>
                            <th data-options="field:'company_name'" width="100">Company</th>
                            <th data-options="field:'challan_no'" width="100">Challan No</th>
                            <th data-options="field:'supplier_name'" width="100">Yarn Supplier</th>
                            <th data-options="field:'return_from_name'" width="100">Return From</th>
                            <th data-options="field:'receive_date'" width="80">Receive Date</th>
                            <th data-options="field:'remarks'" width="120">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Yarn Issue Return Reference',footer:'#ft2'" style="width: 400px; padding:2px">
                <form id="invyarnisurtnFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="inv_yarn_rcv_id" id="inv_yarn_rcv_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Receive No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="receive_no" id="receive_no" placeholder="Receive No" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text" >Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Receive Against </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('receive_against_id', $menu,'',array('id'=>'receive_against_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text" >Location</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Return From</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('return_from_id', $supplier,'',array('id'=>'return_from_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Yarn Supplier</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Receive Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="receive_date" id="receive_date" class="datepicker" placeholder=" yyyy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Challan No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="challan_no" id="challan_no" placeholder=" Challan No" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvYarnIsuRtn.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvYarnIsuRtn.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvYarnIsuRtn.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="delete" onClick="MsInvYarnIsuRtn.showPdf()">PDF</a>
                    </div>
                </form>
            </div>
        </div>
    </div> 
    <div title="Item" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Item',iconCls:'icon-more',footer:'#invyarnisurtnitemFrmFt'" style="width:400px; padding:2px">
                <form id="invyarnisurtnitemFrm" >
                    <div id="container">
                        <div id="body">
                            <code>                               
                                <div class="row" style="display: none;">
                                    <div class="col-sm-4 req-text"></div>
                                    <div class="col-sm-8">
                                        <input type="hidden" name="id" id="id" value="" />
                                        <input type="hidden" name="inv_yarn_rcv_id" id="inv_yarn_rcv_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Store Name</div>
                                    <div class="col-sm-8">
                                        {!! Form::select("store_id", $store,'',array('id'=>'store_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Yarn ID</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="inv_yarn_item_id" id="inv_yarn_item_id"   ondblclick="MsInvYarnIsuRtnItem.openReturnInvYarnWindow()"  placeholder=" Double Click" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Yarn Lot/ Batch</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="lot" id="lot" disabled>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Description</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="yarn_des" id="yarn_des" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Yarn Supplier</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="supplier_name" id="supplier_name" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Sales Order</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sale_order_no" id="sale_order_no" ondblclick="MsInvYarnIsuRtnItem.openReturnSaleOrderWindow()" placeholder=" Double Click"  readonly/>
                                        <input type="hidden" name="sales_order_id" id="sales_order_id"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Cone/Bag</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="cone_per_bag" id="cone_per_bag"  class="number integer" value="" onchange="MsInvYarnIsuRtnItem.calculate_qty_form()"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Wgt/Cone</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="wgt_per_cone" id="wgt_per_cone"  class="number integer" value="" onchange="MsInvYarnIsuRtnItem.calculate_qty_form()"/>
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">No of Bag</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="no_of_bag" id="no_of_bag"  class="number integer" value="" onchange="MsInvYarnIsuRtnItem.calculate_qty_form()"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Store Rate</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rate" id="rate"  class="number integer" readonly/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Return Qty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" value="" class="number integer" value=""/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Returned Amount</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount" id="amount" value="" class="number integer" readonly/>
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="remarks" id="remarks"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Shelf</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="shelf" id="shelf" placeholder=" write txt"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Rack</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rack" id="rack" placeholder=" write txt"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Room</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="room" id="room"  placeholder=" write txt"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Buyer</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="buyer_name" id="buyer_name" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Style Ref</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="style_ref" id="style_ref" disabled/>
                                    </div>
                                </div>
                                
                            </code>
                        </div>
                    </div>
                    <div id="invyarnisurtnitemFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvYarnIsuRtnItem.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invyarnisurtnitemFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvYarnIsuRtnItem.remove()">Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="invyarnisurtnitemTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'itemclass_name'" width="100">Item Class</th>
                            <th data-options="field:'yarn_count'" width="100">Yarn Count</th>
                            <th data-options="field:'composition'" width="100">Composition</th>
                            <th data-options="field:'yarn_type'" width="100">Yarn Type</th>
                            <th data-options="field:'color_name'" width="100">Color</th>
                            <th data-options="field:'lot'" width="100">Lot/Batch</th>
                            <th data-options="field:'brand'" width="100">Brand</th>

                            <th data-options="field:'qty'" width="100" align="right">Issu. Qty</th>
                            <th data-options="field:'rate'" width="100" align="right">Rate</th>
                            <th data-options="field:'amount'" width="100" align="right">Issu. Amount</th>
                            <th data-options="field:'sale_order_no'" width="100" align="right">Sales Order No</th>
                            <th data-options="field:'style_ref'" width="100" align="right">Style</th>
                            <th data-options="field:'buyer_name'" width="100" align="right">Buyer</th>
                            
                        </tr>
                    </thead>
                </table>
               
            </div>
        </div>
    </div>
</div>
    
<div id="openinvyarnisuitemwindow" class="easyui-window" title="Yarn Item" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#invyarnisuitemsearchFrmFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invyarnisuitemsearchFrm">
                            <div class="row middle">
                            <div class="col-sm-4" >PO No</div>
                            <div class="col-sm-8">
                            <input type="text" name="po_no" id="po_no" />
                            </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4">Req NO</div>
                            <div class="col-sm-8">
                            <input type="text" name="rq_no" id="rq_no" />
                            </div>
                            </div>
                        </form>
                    </code>
                </div>
                <div id="invyarnisuitemsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvYarnIsuRtnItem.serachItem()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="invyarnisuitemsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="30">ID</th>
                        <th data-options="field:'rq_no'" width="30">Req/Po No</th>
                        <th data-options="field:'inv_yarn_item_id'" width="90">Yarn Item</th>
                        <th data-options="field:'count'" width="50">Count</th>
                        <th data-options="field:'composition'" width="100">Composition</th>
                        <th data-options="field:'yarn_type'" width="70">Type</th>
                        <th data-options="field:'lot'" width="70">Lot</th>
                        <th data-options="field:'brand'" width="90">Brand</th>     
                        <th data-options="field:'color_name'" width="80">Color</th>
                        <th data-options="field:'qty'" width="80" align="right">Qty</th>
                        <th data-options="field:'qty'" width="80" align="right">Qty</th>
                        <th data-options="field:'qty'" width="80" align="right">Qty</th>
                        <th data-options="field:'sale_order_no'" width="100" align="right">Sales Order No</th>
                        <th data-options="field:'style_ref'" width="100" align="right">Style</th>
                        <th data-options="field:'buyer_name'" width="100" align="right">Buyer</th>
                        <th data-options="field:'dyed_yarn_color_name'" width="100">Dyed Yarn Color</th>
                        <th data-options="field:'gmt_color_name'" width="100">Gmt Color</th>
                        <th data-options="field:'remarks'" width="100">Remarks</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<!--------------------Item Search-Window Start------------------>
<div id="rtninvyarnitemWindow" class="easyui-window" title="Yarn Dyeing Service Order /Inventory Yarn Item Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:450px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invyarnitemsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">LOT</div>
                                <div class="col-sm-8">
                                    <input type="text" name="lot" id="lot" value="" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Brand</div>
                                <div class="col-sm-8">
                                    <input type="text" name="brand" id="brand" value="" />
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsInvYarnIsuRtnItem.searchReturnedYarnItem()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="rtnyarnitemsearchTbl" style="width:610px">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'yarn_des'" width="100">Yarn Description</th>
                        <th data-options="field:'itemcategory_name'" width="100">Yarn Category</th>
                        <th data-options="field:'itemclass_name'" width="100">Item Class</th>
                        <th data-options="field:'lot'" width="70">Lot</th>    
                        <th data-options="field:'yarn_count'" width="70">Count</th>
                        <th data-options="field:'item_description'" width="100">Item Description</th>
                        <th data-options="field:'yarn_type'" width="80">Yarn Type</th>
                        <th data-options="field:'color_id'" width="100">Color</th>
                        <th data-options="field:'brand'" width="100">Brand</th>
                        <th data-options="field:'supplier_name'" width="100">Supplier</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#rtninvyarnitemWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
<!--------------------Sales Order Search-Window Start------------------>
<div id="rtnSaleOrdersearchWindow" class="easyui-window" title="Return Issue Yarn Item / Sales Order Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search',footer:'#rtnsaleordercolorsearchFrmFt'" style="width:400px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="rtnsaleordersearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Style Ref</div>
                                <div class="col-sm-8">
                                    <input type="text" name="style_ref" id="style_ref" value="">
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Job No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="job_no" id="job_no" value="" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Sale Order No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="sale_order_no" id="sale_order_no" value="" />
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                 
                <div id="rtnsaleordercolorsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvYarnIsuRtnItem.searchReturnedSaleOrder()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#poyarndyesaleordercolorsearchTblFt'" style="padding:10px;">
            <table id="rtnsaleordersearchTbl" style="width:800px">
                <thead>
                   <tr>
                    <th data-options="field:'sales_order_id'" width="40">ID</th>
                    <th data-options="field:'style_ref'" width="70">Style Ref</th>
                    <th data-options="field:'job_no'" width="90">Job No</th>
                    <th data-options="field:'sale_order_no'" width="100">Sale Order No</th>
                    <th data-options="field:'ship_date'" width="100">Ship Date</th> 
                    <th data-options="field:'buyer_name'" width="100">Buyer</th>
                    <th data-options="field:'company_name'" width="80">Company</th>
                    <th data-options="field:'produced_company_name'" width="140">Produced Company</th> 
                    </tr>
                </thead>
            </table>
            <div id="poyarndyesaleordercolorsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="$('#rtnSaleOrdersearchWindow').window('close')">Close</a>
            </div>
        </div>
    </div>
</div>
{{--  --}}   
<script type="text/javascript" src="<?php echo url('/');?>/js/Inventory/Yarn/MsAllInvYarnIsuRtnController.js"></script>
<script>
    $('#invyarnisurtnFrm [id="supplier_id"]').combobox();
    $('#invyarnisurtnFrm [id="return_from_id"]').combobox();
    $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script>
    