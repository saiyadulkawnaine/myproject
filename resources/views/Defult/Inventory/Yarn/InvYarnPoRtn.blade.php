<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="invyarnportntabs">
    <div title="Yarn Purchse Return" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="invyarnportnTbl" style="width:500px">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'company_name'" width="100">Company</th>
                            <th data-options="field:'issue_no'" width="100">Return No</th>
                            <th data-options="field:'supplier_name'" width="100">Supplier</th>
                            <th data-options="field:'issue_date'" width="80">Return Date</th>
                            <th data-options="field:'remarks'" width="120">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Yarn Purchase Return Reference',footer:'#ft2'" style="width: 400px; padding:2px">
                <form id="invyarnportnFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Return No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="issue_no" id="issue_no" placeholder=" Return No" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text" >Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                {{-- <div class="row middle">
                                    <div class="col-sm-4 req-text" >Location</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}
                                    </div>
                                </div> --}}
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Return To</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Return Date</div>
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
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvYarnPoRtn.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvYarnPoRtn.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvYarnPoRtn.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="delete" onClick="MsInvYarnPoRtn.showPdf()">PDF</a>
                    </div>
                </form>
            </div>
        </div>
    </div> 
    <div title="Item" style="padding:2px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'center',border:true" style="padding:2px">
              <div class="easyui-layout"  data-options="fit:true">
                <div data-options="region:'center',border:true,title:'Item List'" style="padding:2px">
                    <table id="invyarnportnitemTbl" style="width:100%">
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
                                <th data-options="field:'qty'" width="100" align="right">Qty</th>
                                <th data-options="field:'rate'" width="100" align="right">Rate</th>
                                <th data-options="field:'amount'" width="100" align="right">Amount</th>
                                  
                            </tr>
                        </thead>
                    </table>
                </div>
              </div>
            </div>
            <div data-options="region:'west',border:true,title:'', footer:'#gateentryFt'" style="width:450px; padding:2px">
              <div class="easyui-layout"  data-options="fit:true">
                <div data-options="region:'north',border:true,title:'PO Return Detail'" style="padding:2px;height:340px">
                  <form id="invyarnportnitemFrm">
                    <div id="container">
                      <div id="body">
                        <code>
                            <div class="row" style="display: none;">
                                <div class="col-sm-4 req-text"></div>
                                <div class="col-sm-8">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="inv_isu_id" id="inv_isu_id" value="" />
                                    <input type="hidden" name="receive_qty" id="receive_qty" value="" />
                                    <input type="hidden" name="store_rate" id="store_rate" value="" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Store Name</div>
                                <div class="col-sm-8">
                                    {!! Form::select("store_id", $store,'',array('id'=>'store_id')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Barcode</div>
                                <div class="col-sm-8">
                                    <input type="text" name="inv_rcv_id" id="inv_rcv_id" onchange="MsInvYarnPoRtnItem.getMrrItem()" />
                                    <input type="hidden" name="inv_yarn_rcv_item_id" id="inv_yarn_rcv_item_id" value="" />
                                    <input type="hidden" name="inv_yarn_item_id" id="inv_yarn_item_id" value="" />
                                </div>
                            </div>
                           
                            <div class="row middle">
                                <div class="col-sm-4">Qty</div>
                                <div class="col-sm-8">
                                    <input type="text" name="qty" id="qty" value="" class="number integer" onchange="MsInvYarnPoRtnItem.calculate_qty()" />
                                </div>
                            </div>
                             <div class="row middle">
                                <div class="col-sm-4">Rate</div>
                                <div class="col-sm-8">
                                    <input type="text" name="rate" id="rate" value="" class="number integer" readonly />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Amount</div>
                                <div class="col-sm-8">
                                    <input type="text" name="amount" id="amount" value="" class="number integer" readonly />
                                </div>
                            </div>
                            
                            <div class="row middle">
                                <div class="col-sm-4">Remarks</div>
                                <div class="col-sm-8">
                                    <input type="text" name="remarks" id="remarks" value="" />
                                </div>
                            </div>
                            
                            <div class="row middle">
                                <div class="col-sm-4">Receive Challan</div>
                                <div class="col-sm-8">
                                    <input type="text" name="challan_no" id="challan_no" value="" disabled />
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
                                    <input type="text" name="yarn_desc" id="yarn_desc" disabled/>
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Yarn Supplier</div>
                                <div class="col-sm-8">
                                    <input type="text" name="yarn_supplier" id="yarn_supplier" disabled/>
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">PO No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="po_no" id="po_no" value="" class="number integer" disabled />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">PI NO</div>
                                <div class="col-sm-8">
                                    <input type="text" name="pi_no" id="pi_no" value="" disabled/>
                                </div>
                            </div>
                            
                        </code>
                      </div>
                    </div>
                    <div id="gateentryFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvYarnPoRtnItem.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invyarnportnitemFrm')">Reset</a>
                    </div>
                  </form>
                </div>
                <div data-options="region:'center',border:true,title:'MRR Item'" style="height:80px; padding:2px">
                  <table id="invyarnportnmrritemTbl">
                    <thead>
                      <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'yarn_count'" width="100">Yarn Count</th>
                            <th data-options="field:'composition'" width="100">Composition</th>
                            <th data-options="field:'yarn_type'" width="100">Yarn Type</th>
                            <th data-options="field:'color_name'" width="100">Color</th>
                            <th data-options="field:'lot'" width="100">Lot/Batch</th>
                            <th data-options="field:'brand'" width="100">Brand</th>
                            <th data-options="field:'qty'" width="100" align="right">Rcv. Qty</th>
                            <th data-options="field:'uom_code'" width="100">Rcv. UOM</th>
                            <th data-options="field:'rate'" width="100" align="right">Rcv.Rate</th>
                            <th data-options="field:'amount'" width="100" align="right">Rcv. Amount</th>
                            <th data-options="field:'currency_code'" width="100">Rcv. Currency</th>
                            <th data-options="field:'exch_rate'" width="100" align="right">Exch. Rate</th>
                            <th data-options="field:'store_qty'" width="100" align="right">Store Qty</th>
                            <th data-options="field:'uom'" width="100" align="right">Store. UOM</th>
                            <th data-options="field:'store_rate'" width="100" align="right">Store Rate</th>
                            <th data-options="field:'store_amount'" width="100" align="right">Store Amount</th>
                            <th data-options="field:'po_no'" width="100">PO NO</th>
                            <th data-options="field:'pi_no'" width="100">PI NO</th>
                            <th data-options="field:'itemcategory_name'" width="100">Item Category</th>
                            <th data-options="field:'itemclass_name'" width="100">Item Class</th>
                            
                        </tr>
                    </thead>
                  </table>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>
    
<div id="openinvyarnportnitemwindow" class="easyui-window" title="Yarn Item" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#invyarnportnitemsearchFrmFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invyarnportnitemsearchFrm">
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
                <div id="invyarnportnitemsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvYarnPoRtnItem.serachItem()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="invyarnportnitemsearchTbl" style="width:100%">
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
<div id="invyarnitemwindow" class="easyui-window" title="Returned Yarn Purchase /Inventory Yarn Item Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:450px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invyarnitemsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Item Category</div>
                                <div class="col-sm-8">
                                    {{-- {!! Form::select('itemcategory_id', $itemcategory,'',array('id'=>'itemcategory_id')) !!} --}}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Item Class </div>
                                <div class="col-sm-8">
                                    {{-- {!! Form::select('itemclass_id', $itemclass,'',array('id'=>'itemclass_id')) !!} --}}
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsInvYarnPoRtnItem.searchYarnRcvItem()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="invyarnitemsearchTbl" style="width:610px">
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
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#dyeinvyarnitemwindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>    
<script type="text/javascript" src="<?php echo url('/');?>/js/Inventory/Yarn/MsAllInvYarnPoRtnController.js"></script>
<script>
    $('#invyarnportnFrm [id="supplier_id"]').combobox();
    $('#inv_isu_id').focus();

    $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script>