<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="invyarntransintabs">
    <div title="Yarn Transfer In" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="invyarntransinTbl" style="width:500px">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'receive_no'" width="40">Receive No</th>
                            <th data-options="field:'company_name'" width="100">Company</th>
                            <th data-options="field:'from_company_name'" width="100">Transfer From</th>
                            <th data-options="field:'receive_date'" width="80">Receive Date</th>
                            <th data-options="field:'remarks'" width="80">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Yarn Transfer Out Reference',footer:'#invyarntransinFrmft'" style="width: 400px; padding:2px">
                <form id="invyarntransinFrm">
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
                                    <div class="col-sm-4 req-text" >Supplier</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id')) !!}
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Challan No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="challan_no" id="challan_no"/>
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Receive Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="receive_date" id="receive_date" class="datepicker" placeholder=" yyyy-mm-dd" />
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
                    <div id="invyarntransinFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvYarnTransIn.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvYarnTransIn.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvYarnTransIn.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="delete" onClick="MsInvYarnTransIn.showPdf()">PDF</a>
                    </div>
                </form>
            </div>
        </div>
    </div> 
    <div title="Item" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Item',iconCls:'icon-more',footer:'#invyarntransinitemFrmFt'" style="width:400px; padding:2px">
                <form id="invyarntransinitemFrm" >
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
                                    <div class="col-sm-4 req-text">Transfer No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="transfer_no" id="transfer_no"   ondblclick="MsInvYarnTransInItem.openInvYarnWindow()"  placeholder=" Double Click" readonly/>
                                        <input type="hidden" name="inv_yarn_isu_item_id" id="inv_yarn_isu_item_id" />
                                        <input type="hidden" name="inv_yarn_item_id" id="inv_yarn_item_id"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Yarn Lot/ Batch</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="lot" id="lot" disabled>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Count</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="yarn_count" id="yarn_count" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Description</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="yarn_des" id="yarn_des" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Type</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="yarn_type" id="yarn_type" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Yarn Color</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="yarn_color_name" id="yarn_color_name" disabled/>
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4">Brand</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="brand" id="brand" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Yarn Supplier</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="supplier_name" id="supplier_name" disabled/>
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4">Store Rate</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rate" id="rate"  class="number integer" readonly/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Receive Qty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" value="" class="number integer" onchange="MsInvYarnTransInItem.calculate_qty_form()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Receive Amount</div>
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
                                    <div class="col-sm-4">Room</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="room" id="room"  placeholder=" write txt"/>
                                    </div>
                                </div>
                                
                            </code>
                        </div>
                    </div>
                    <div id="invyarntransinitemFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvYarnTransInItem.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invyarntransinitemFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvYarnTransInItem.remove()">Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'List',footer:'#invyarntransinitemTblFt'" style="padding:2px">
                <table id="invyarntransinitemTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'transfer_no'" width="100">Transfer No</th>
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
                        </tr>
                    </thead>
                </table>
                <div id="invyarntransinitemTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="invyarnisuitemTbimport" name="import" onClick="MsInvYarnTransInItem.import()">Import</a>
                    </div>
            </div>
        </div>
    </div>
</div>
    

<!--------------------Item Search-Window Start------------------>
<div id="invyarntransinitemWindow" class="easyui-window" title="Yarn Dyeing Service Order /Inventory Yarn Item Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:450px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invyarntransinitemsearchFrm">
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
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsInvYarnTransInItem.searchYarnItem()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="invyarntransinitemsearchTbl" style="width:610px">
                <thead>
                    <tr>
                        <th data-options="field:'inv_yarn_isu_item_id'" width="40">ID</th>
                        <th data-options="field:'transfer_no'" width="40">Transfer No</th>
                        <th data-options="field:'inv_yarn_item_id'" width="40">Yarn ID</th>
                        <th data-options="field:'itemclass_name'" width="100">Item Class</th>
                        <th data-options="field:'yarn_count'" width="70">Count</th>
                        <th data-options="field:'yarn_des'" width="100">Yarn Description</th>
                           
                        <th data-options="field:'yarn_type'" width="80">Yarn Type</th>
                        <th data-options="field:'yarn_color_name'" width="100">Color</th>
                        <th data-options="field:'lot'" width="70">Lot</th> 
                        <th data-options="field:'brand'" width="100">Brand</th>
                        <th data-options="field:'supplier_name'" width="100">Supplier</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#invyarntransinitemWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
  
<script type="text/javascript" src="<?php echo url('/');?>/js/Inventory/Yarn/MsAllInvYarnTransInController.js"></script>
<script>
    $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script>
    