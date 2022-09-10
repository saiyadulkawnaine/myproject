<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="invdyechemrcvtabs">
    <div title="Dyes & Chem Receive Entry" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="invdyechemrcvTbl" style="width:500px">
                    <thead>
                        <tr>
                            <th data-options="field:'inv_dye_chem_rcv_id'" width="40">ID</th>
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
            <div data-options="region:'west',border:true,title:'Receive Reference',footer:'#invdyechemrcvFrmFt'" style="width: 400px; padding:2px">
                <form id="invdyechemrcvFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="inv_dye_chem_rcv_id" id="inv_dye_chem_rcv_id" value="" />
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
                    <div id="invdyechemrcvFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvDyeChemRcv.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invdyechemrcvFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvDyeChemRcv.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="delete" onClick="MsInvDyeChemRcv.showPdf()">MRR</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
        
    <div title="Item" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Opening Balance',iconCls:'icon-more',footer:'#invdyechemrcvitemFrmFt'" style="width:400px; padding:2px">
                <form id="invdyechemrcvitemFrm" >
                    <div id="container">
                        <div id="body">
                            <code>                               
                                <div class="row">
                                    <div class="col-sm-4 req-text">Store Name</div>
                                    <div class="col-sm-8">
                                        {!! Form::select("store_id", $store,'',array('id'=>'store_id')) !!}
                                        <input type="hidden" name="inv_dye_chem_rcv_id" id="inv_dye_chem_rcv_id" value="" />
                                        <input type="hidden" name="po_dye_chem_item_id" id="po_dye_chem_item_id" value="" />
                                        <input type="hidden" name="inv_pur_req_item_id" id="inv_pur_req_item_id" value="" />
                                        <input type="hidden" name="id" id="id" value="" />
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4">Item Description</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="item_desc" id="item_desc" readonly   ondblclick="MsInvDyeChemRcvItem.openitemWindow()" placeholder=" Double Click Search Item"/>
                                        <input type="hidden" name="item_account_id" id="item_account_id" readonly/>
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4">UOM</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="uom_code" id="uom_code" value="" class="number integer" disabled />
                                    </div>
                                </div>
                                
                                
                                <div class="row middle">
                                    <div class="col-sm-4">Batch</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="batch" id="batch">
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Expiry Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="expiry_date" id="expiry_date" class="datepicker" placeholder=" yyyy-mm-dd" />
                                    </div>
                                </div>
                               
                                
                                <div class="row middle">
                                    <div class="col-sm-4">Rcv. Qnty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" value="" class="number integer" onchange="MsInvDyeChemRcvItem.calculate_qty_form()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Rcv.Rate</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rate" id="rate" value="" class="number integer" onchange="MsInvDyeChemRcvItem.calculate_qty_form()"/>
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
                                <div class="row middle">
                                    <div class="col-sm-4">Requisition No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rq_no" id="rq_no" value="" class="number integer" disabled />
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
                    <div id="invdyechemrcvitemFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvDyeChemRcvItem.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invdyechemrcvitemFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvDyeChemRcvItem.remove()">Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'List',footer:'#invdyechemrcvitemTblFt'" style="padding:2px">
                <table id="invdyechemrcvitemTbl" style="width:100%">
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
                        <th data-options="field:'rate'" width="100" align="right">Rate</th>
                        <th data-options="field:'currency_code'" width="100">Rcv. Currency</th>
                        <th data-options="field:'amount'" width="100" align="right">Amount</th>
                        <th data-options="field:'exch_rate'" width="100" align="right">Exch. Rate</th>
                        <th data-options="field:'store_qty'" width="100" align="right">Store Qty</th>
                        <th data-options="field:'store_uom'" width="100" align="right">Store. UOM</th>
                        <th data-options="field:'store_rate'" width="100" align="right">Store Rate</th>
                        <th data-options="field:'store_amount'" width="100" align="right">Store Amount</th>
                        <th data-options="field:'po_no'" width="100">PO NO</th>
                        <th data-options="field:'pi_no'" width="100">PI NO</th>
                        <th data-options="field:'rq_no'" width="100">RQ NO</th>
                            
                        </tr>
                    </thead>
                </table>
               {{--  <div id="invdyechemrcvitemTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="invyarnrcvitemTbimport" name="import" onClick="MsInvDyeChemRcvItem.import()">Import</a>
                        
                    </div> --}}
            </div>
        </div>
    </div>
</div>






<div id="invdyechemrcvitemsearchwindow" class="easyui-window" title=" Yarn Item" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#invdyechemrcvitemsearchFrmFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invdyechemrcvitemsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4" >PO No</div>
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
                                    <input type="text" name="po_dye_chem_id" id="po_dye_chem_id"  placeholder="write"/>
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4" >PR/RQ No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="requisition_no" id="requisition_no"  placeholder="write"/>
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">PR/RQ Barcode No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="inv_pur_req_id" id="inv_pur_req_id"  placeholder="write"/>
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <div id="invdyechemrcvitemsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvDyeChemRcvItem.serachItem()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#invdyechemrcvitemsearchTblFt'" style="padding:10px;">
            <table id="invdyechemrcvitemsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="100">ID</th>
                        <th data-options="field:'category_name'" width="100">Item Category</th>
                        <th data-options="field:'class_name'" width="100">Item Class</th>
                        <th data-options="field:'sub_class_name'" width="100">Sub Class</th>
                        <th data-options="field:'item_description'" width="100">Description</th>
                        <th data-options="field:'specification'" width="100">Specification</th>
                        <th data-options="field:'uom_name'" width="100">UOM</th>
                        <th data-options="field:'po_no'" width="100">PO NO</th>
                        <th data-options="field:'pi_no'" width="100">PI NO</th>
                        <th data-options="field:'rq_no'" width="100">RQ NO</th>
                        <th data-options="field:'qty'" width="100" align="right">Qty</th>
                        <th data-options="field:'rate'" width="100" align="right">Rate</th>
                        <th data-options="field:'amount'" width="100" align="right">Amount</th>
                        <th data-options="field:'currency_code'" width="100">Currency</th>
                    </tr>
                </thead>
            </table>
            <div id="invdyechemrcvitemsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvDyeChemRcvItem.closeinvyarnrcvitemWindow()">Next</a>
                </div>
        </div>
    </div>
</div>


<div id="invyarnrcvitemWindow" class="easyui-window" title=" Yarn Item" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center',footer:'#invyarnrcvitemmatrixFrmFt'" style="padding:10px;">
            <form id="invyarnrcvitemmatrixFrm">
                <div id="invyarnrcvitemscs"></div>
            </form>
        </div>
        <div id="invyarnrcvitemmatrixFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvYarnRcvItem.submitBatch()">Save</a>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Inventory/DyeChem/MsAllInvDyeChemRcvController.js"></script>
<script>
    $('#invdyechemrcvFrm [id="supplier_id"]').combobox();
    $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script>
