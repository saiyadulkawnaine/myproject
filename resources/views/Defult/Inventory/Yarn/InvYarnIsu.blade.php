<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="invyarnisutabs">
    <div title="Yarn Issue Entry" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="invyarnisuTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'company_name'" width="100">Company</th>
                            <th data-options="field:'issue_no'" width="100">Challan No</th>
                            <th data-options="field:'supplier_name'" width="100">Issue To</th>
                            <th data-options="field:'isu_basis_id'" width="100">Issue Basis</th>
                            <th data-options="field:'issue_date'" width="80">Issue Date</th>
                            <th data-options="field:'remarks'" width="150">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Receive Reference',footer:'#ft2'" style="width: 400px; padding:2px">
                <form id="invyarnisuFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Challan No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="issue_no" id="issue_no" placeholder=" Challan No" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text" >Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Issue Basis </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('isu_basis_id', $invissuebasis,'',array('id'=>'isu_basis_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Issue Against </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('isu_against_id', $menu,'',array('id'=>'isu_against_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Issue To </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Issue Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="issue_date" id="issue_date" class="datepicker" placeholder=" yyyy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Driver Name </div>
                                    <div class="col-sm-8">
                                    <input type="text" name="driver_name" id="driver_name" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Driver Contact</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="driver_contact_no" id="driver_contact_no" placeholder="(+88) 01234567890" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Driver License No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="driver_license_no" id="driver_license_no" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Lock
                                     No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="lock_no" id="lock_no" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Truck
                                     No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="truck_no" id="truck_no" value="" placeholder=" write" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Recipient</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="recipient" id="recipient" value="" placeholder=" Write" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks"></textarea>
                                       {{--  <input type="text" name="remarks" id="remarks"/> --}}
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvYarnIsu.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvYarnIsu.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvYarnIsu.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="delete" onClick="MsInvYarnIsu.showPdf()">GIN-PDF</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="delete" onClick="MsInvYarnIsu.showPdf2()">PDF</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
        
    <div title="Item" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Opening Balance',iconCls:'icon-more',footer:'#invyarnisuitemFrmFt'" style="width:400px; padding:2px">
                <form id="invyarnisuitemFrm" >
                    <div id="container">
                        <div id="body">
                            <code>                               
                                <div class="row" style="display: none;">
                                    <div class="col-sm-4 req-text"></div>
                                    <div class="col-sm-8">
                                        <input type="hidden" name="id" id="id" value="" />
                                        <input type="hidden" name="inv_yarn_item_id" id="inv_yarn_item_id" value="" />
                                        <input type="hidden" name="rq_yarn_item_id" id="rq_yarn_item_id" value="" />
                                        <input type="hidden" name="po_yarn_dyeing_item_bom_qty_id" id="po_yarn_dyeing_item_bom_qty_id" value="" />
                                        <input type="hidden" name="inv_isu_id" id="inv_isu_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Yarn Count</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="yarn_count" id="yarn_count" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Yarn Decs</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="composition" id="composition"  disabled ondblclick="MsInvYarnRcvItem.openyarnWindow()" />
                                        <input type="hidden" name="item_account_id" id="item_account_id" readonly/>
                                    </div>
                                </div>
                                
                                
                                
                                
                                <div class="row middle">
                                    <div class="col-sm-4">Yarn Type</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="yarn_type" id="yarn_type" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Yarn Category</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="itemcategory_name" id="itemcategory_name"  disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Yarn Class</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="itemclass_name" id="itemclass_name"  disabled/>
                                    </div>
                                </div>
                                
                                

                                <div class="row middle">
                                    <div class="col-sm-4">Yarn Color</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="color_id" id="color_id" disabled> 
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Yarn Lot/ Batch</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="lot" id="lot" disabled>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Brand</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="brand" id="brand" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Sales Order No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sale_order_no" id="sale_order_no" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Style Ref</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="style_ref" id="style_ref" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Buyer</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="buyer_name" id="buyer_name" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Store Name</div>
                                    <div class="col-sm-8">
                                        {!! Form::select("store_id", $store,'',array('id'=>'store_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Issu. Qnty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" value="" class="number integer"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Returnable Qty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="returnable_qty" id="returnable_qty" value="" class="number integer" value=""/>
                                    </div>
                                </div>
                                <div class="row middle" style="display: none;">
                                    <div class="col-sm-4">Returned Qty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="returned_qty" id="returned_qty" value="" class="number integer" value=""/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="remarks" id="remarks"/>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="invyarnisuitemFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvYarnIsuItem.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invyarnisuitemFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvYarnIsuItem.remove()">Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'List',footer:'#invyarnisuitemTblFt'" style="padding:2px">
                <table id="invyarnisuitemTbl" style="width:100%">
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
                            <th data-options="field:'amount'" width="100" align="right">Issu. Amount</th>
                            <th data-options="field:'returnable_qty'" width="80" align="right">Returnable Qty</th>
                            <th data-options="field:'sale_order_no'" width="100" align="right">Sales Order No</th>
                            <th data-options="field:'style_ref'" width="100" align="right">Style</th>
                            <th data-options="field:'buyer_name'" width="100" align="right">Buyer</th>
                            
                        </tr>
                    </thead>
                </table>
                <div id="invyarnisuitemTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="invyarnisuitemTbimport" name="import" onClick="MsInvYarnIsuItem.import()">Import</a>
                    </div>
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
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvYarnIsuItem.serachItem()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="invyarnisuitemsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="30">ID</th>
                        <th data-options="field:'rq_no'" width="30">Req/Po No</th>
                        <th data-options="field:'inv_yarn_item_id'" width="100">Yarn Item</th>
                        <th data-options="field:'count'" width="100">Count</th>
                        <th data-options="field:'composition'" width="100">Composition</th>
                        <th data-options="field:'yarn_type'" width="100">Type</th>
                        <th data-options="field:'lot'" width="100">Lot</th>
                        <th data-options="field:'brand'" width="100">Brand</th>                 
                        <th data-options="field:'color_name'" width="100">Color</th>
                        <th data-options="field:'qty'" width="100" align="right">Qty</th>
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





<script type="text/javascript" src="<?php echo url('/');?>/js/Inventory/Yarn/MsAllInvYarnIsuController.js"></script>
<script>
    $('#invyarnisuFrm [id="supplier_id"]').combobox();
    $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script>
