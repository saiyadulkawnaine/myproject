<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="invtrimrcvtabs">
    <div title="Trim Receive Entry" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="invtrimrcvTbl" style="width:500px">
                    <thead>
                        <tr>
                            <th data-options="field:'inv_trim_rcv_id'" width="40">ID</th>
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
            <div data-options="region:'west',border:true,title:'Receive Reference',footer:'#invtrimrcvFrmFt'" style="width: 400px; padding:2px">
                <form id="invtrimrcvFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="inv_trim_rcv_id" id="inv_trim_rcv_id" value="" />
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
                                    <div class="col-sm-4 req-text">Receive Basis </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('receive_basis_id', $invreceivebasis,'',array('id'=>'receive_basis_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Receive Against </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('receive_against_id', $menu,'',array('id'=>'receive_against_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text" >Supplier</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
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
                    <div id="invtrimrcvFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvTrimRcv.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invtrimrcvFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvTrimRcv.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="delete" onClick="MsInvTrimRcv.showPdf()">MRR</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
        
    <div title="Item" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Opening Balance',iconCls:'icon-more',footer:'#invtrimrcvitemFrmFt'" style="width:400px; padding:2px">
                <form id="invtrimrcvitemFrm" >
                    <div id="container">
                        <div id="body">
                            <code>                               
                                <div class="row">
                                    <div class="col-sm-4 req-text">Store Name</div>
                                    <div class="col-sm-8">
                                        {!! Form::select("store_id", $store,'',array('id'=>'store_id')) !!}
                                        <input type="hidden" name="inv_trim_rcv_id" id="inv_trim_rcv_id" value="" />
                                        <input type="hidden" name="po_trim_item_report_id" id="po_trim_item_report_id" value="" />
                                        <input type="hidden" name="id" id="id" value="" />
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4">Item Class</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="class_name" id="class_name" disabled />
                                        <input type="hidden" name="itemclass_id" id="itemclass_id" readonly/>
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4">Description</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="description" id="description" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Item Color</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="item_color_name" id="item_color_name" disabled/>
                                        <input type="hidden" name="trim_color_id" id="trim_color_id" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Item Size</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="measurment" id="measurment" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">GMT Color</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="style_color_name" id="style_color_name" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">GMT Size</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="style_size_name" id="style_size_name" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Sales Order No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sale_order_no" id="sale_order_no" disabled/>
                                    </div>
                                </div>

                                
                                
                                
                               
                                
                                <div class="row middle">
                                    <div class="col-sm-4">Rcv. Qnty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" value="" class="number integer" onchange="MsInvTrimRcvItem.calculate_qty_form()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">UOM</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="uom_code" id="uom_code" value="" class="number integer" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Rcv.Rate</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rate" id="rate" value="" class="number integer" onchange="MsInvTrimRcvItem.calculate_qty_form()" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Rcv.Amount</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount" id="amount" value="" class="number integer" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Rcv. Currency</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="currency_code" id="currency_code" value="" class="number integer" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Exch. Rate</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="exch_rate" id="exch_rate" value="" class="number integer" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Store. Qty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="store_qty" id="store_qty" value="" class="number integer" disabled />
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4">Store. Rate</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="store_rate" id="store_rate" value="" class="number integer" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Store Amount</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="store_amount" id="store_amount" value="" class="number integer" disabled />
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
                                <div class="row middle">
                                    <div class="col-sm-4">Po No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="po_no" id="po_no" value="" class="number integer" disabled />
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4">PI No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="pi_no" id="pi_no" value="" class="number integer" disabled />
                                    </div>
                                </div>
                                
                               
                            </code>
                        </div>
                    </div>
                    <div id="invtrimrcvitemFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvTrimRcvItem.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invtrimrcvitemFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvTrimRcvItem.remove()">Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'List',footer:'#invtrimrcvitemTblFt'" style="padding:2px">
                <table id="invtrimrcvitemTbl" style="width:100%">
                    <thead>
                        <tr>
                            
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'sale_order_no'" width="100">Sales Order No</th>
                        <th data-options="field:'style_color_name'" width="100">GMT Color</th>
                        <th data-options="field:'style_size_name'" width="100">GMT Size</th>
                        <th data-options="field:'class_name'" width="100">Item Class</th>
                        <th data-options="field:'description'" width="100">Description</th>
                        <th data-options="field:'item_color_name'" width="100">Item Color</th>
                        <th data-options="field:'measurment'" width="100">Item Size</th>
                        
                        <th data-options="field:'po_no'" width="100">PO NO</th>
                        <th data-options="field:'pi_no'" width="100">PI NO</th>

                        <th data-options="field:'qty'" width="100" align="right">Qty</th>
                        <th data-options="field:'uom_name'" width="100">UOM</th>
                        <th data-options="field:'rate'" width="100" align="right">Rate</th>
                        <th data-options="field:'currency_code'" width="100">Rcv. Currency</th>
                        <th data-options="field:'amount'" width="100" align="right">Amount</th>
                        <th data-options="field:'exch_rate'" width="100" align="right">Exch. Rate</th>
                        <th data-options="field:'store_qty'" width="100" align="right">Store Qty</th>
                        <th data-options="field:'store_rate'" width="100" align="right">Store Rate</th>
                        <th data-options="field:'store_amount'" width="100" align="right">Store Amount</th>
                            
                        </tr>
                    </thead>
                </table>
                <div id="invtrimrcvitemTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="invyarnrcvitemTbimport" name="import" onClick="MsInvTrimRcvItem.openitemWindow()">Import</a>
                </div> 
            </div>
        </div>
    </div>
</div>






<div id="invtrimrcvitemsearchwindow" class="easyui-window" title="Item" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#invtrimrcvitemsearchFrmFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invtrimrcvitemsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4 req-text" >PO No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="po_no" id="po_no" placeholder="write"/>
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">PI NO</div>
                                <div class="col-sm-8">
                                    <input type="text" name="pi_no" id="pi_no"  placeholder="write"/>
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">PO Barcode No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="po_trim_id" id="po_trim_id"  placeholder="write"/>
                                </div>
                            </div>
                            
                           
                        </form>
                    </code>
                </div>
                <div id="invtrimrcvitemsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvTrimRcvItem.serachItem()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#invtrimrcvitemsearchTblFt'" style="padding:10px;">
            <table id="invtrimrcvitemsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="100">ID</th>
                        <th data-options="field:'sale_order_no'" width="100">Sales Order No</th>
                        <th data-options="field:'style_ref'" width="100">Style Ref</th>
                        <th data-options="field:'style_color_name'" width="100">GMT Color</th>
                        <th data-options="field:'style_size_name'" width="100">GMT Size</th>
                        <th data-options="field:'class_name'" width="100">Item Class</th>
                        <th data-options="field:'description'" width="100">Description</th>
                        <th data-options="field:'item_color_name'" width="100">Item Color</th>
                        <th data-options="field:'measurment'" width="100">Item Size</th>
                        
                        <th data-options="field:'po_no'" width="100">PO NO</th>
                        <th data-options="field:'pi_no'" width="100">PI NO</th>
                        <th data-options="field:'cu_qty'" width="100" align="right">Tot.Rcv. Qty</th>
                        <th data-options="field:'bal_qty'" width="100" align="right">Bal. Qty</th>
                        <th data-options="field:'qty'" width="100" align="right">Qty</th>
                        <th data-options="field:'uom_name'" width="100">UOM</th>
                        <th data-options="field:'rate'" width="100" align="right">Rate</th>
                        <th data-options="field:'amount'" width="100" align="right">Amount</th>
                        <th data-options="field:'currency_code'" width="100">Currency</th>
                        <th data-options="field:'exch_rate'" width="100" align="right">Exch. Rate</th>
                        
                    </tr>
                </thead>
            </table>
            <div id="invtrimrcvitemsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvTrimRcvItem.closeinvtrimrcvitemWindow()">Next</a>
                </div>
        </div>
    </div>
</div>


<div id="invtrimrcvitemWindow" class="easyui-window" title="Item" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center',footer:'#invtrimrcvitemmatrixFrmFt'" style="padding:10px;">
            <form id="invtrimrcvitemmatrixFrm">
                <div id="invtrimrcvitemscs"></div>
            </form>
        </div>
        <div id="invtrimrcvitemmatrixFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvTrimRcvItem.submitBatch()">Save</a>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Inventory/Trim/MsAllInvTrimRcvController.js"></script>
<script>
    $('#invtrimrcvFrm [id="supplier_id"]').combobox();
    $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script>
