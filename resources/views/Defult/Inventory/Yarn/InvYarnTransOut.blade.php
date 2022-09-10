<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="invyarntransouttabs">
    <div title="Yarn Transfer Out" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="invyarntransoutTbl" style="width:500px">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'issue_no'" width="40">Issue No</th>
                            <th data-options="field:'company_name'" width="100">Company</th>
                            <th data-options="field:'to_company_name'" width="100">Transfer To</th>
                            <th data-options="field:'issue_date'" width="80">Issue Date</th>
                            <th data-options="field:'remarks'" width="80">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Yarn Transfer Out Reference',footer:'#invyarntransoutFrmft'" style="width: 400px; padding:2px">
                <form id="invyarntransoutFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Transfer No</div>
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
                                    <div class="col-sm-4 req-text" >To Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('to_company_id', $company,'',array('id'=>'to_company_id')) !!}
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Issue Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="issue_date" id="issue_date" class="datepicker" placeholder=" yyyy-mm-dd" />
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
                    <div id="invyarntransoutFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvYarnTransOut.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvYarnTransOut.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvYarnTransOut.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="delete" onClick="MsInvYarnTransOut.showPdf()">PDF</a>
                    </div>
                </form>
            </div>
        </div>
    </div> 
    <div title="Item" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Item',iconCls:'icon-more',footer:'#invyarntransoutitemFrmFt'" style="width:400px; padding:2px">
                <form id="invyarntransoutitemFrm" >
                    <div id="container">
                        <div id="body">
                            <code>                               
                                <div class="row" style="display: none;">
                                    <div class="col-sm-4 req-text"></div>
                                    <div class="col-sm-8">
                                        <input type="hidden" name="id" id="id" value="" />
                                        <input type="hidden" name="inv_isu_id" id="inv_isu_id" value="" />
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
                                        <input type="text" name="inv_yarn_item_id" id="inv_yarn_item_id"   ondblclick="MsInvYarnTransOutItem.openInvYarnWindow()"  placeholder=" Double Click" />
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
                                    <div class="col-sm-4">Transfer Qty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" value="" class="number integer" onchange="MsInvYarnTransOutItem.calculate_qty_form()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Transfer Amount</div>
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
                    <div id="invyarntransoutitemFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvYarnTransOutItem.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invyarntransoutitemFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvYarnTransOutItem.remove()">Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'List',footer:'#invyarntransoutitemTblFt'" style="padding:2px">
                <table id="invyarntransoutitemTbl" style="width:100%">
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
                        </tr>
                    </thead>
                </table>
                <!-- <div id="invyarntransoutitemTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="invyarnisuitemTbimport" name="import" onClick="MsInvYarnTransOutItem.import()">Import</a>
                </div> -->
            </div>
        </div>
    </div>
</div>
    

<!--------------------Item Search-Window Start------------------>
<div id="invyarntransoutitemWindow" class="easyui-window" title="Yarn Dyeing Service Order /Inventory Yarn Item Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:450px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invyarntransoutitemsearchFrm">
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
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsInvYarnTransOutItem.searchYarnItem()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="invyarntransoutitemsearchTbl" style="width:610px">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
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
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#invyarntransoutitemWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
  
<script type="text/javascript" src="<?php echo url('/');?>/js/Inventory/Yarn/MsAllInvYarnTransOutController.js"></script>
<script>
    $('#invyarnisurtnFrm [id="supplier_id"]').combobox();
    $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script>
    