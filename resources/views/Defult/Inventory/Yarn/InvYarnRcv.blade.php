<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="invyarnrcvtabs">
    <div title="Yarn Receive Entry" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="invyarnrcvTbl" style="width:500px">
                    <thead>
                        <tr>
                            <th data-options="field:'inv_yarn_rcv_id'" width="40">ID</th>
                            <th data-options="field:'id'" width="40">Barcode NO</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'receive_no'" width="100">Receive No</th>
                            <th data-options="field:'receive_basis_id'" width="100">Receive Basis</th>
                            <th data-options="field:'challan_no'" width="80">Challan No</th>
                            <th data-options="field:'receive_date'" width="80">Receive Date</th>
                            <th data-options="field:'supplier_id'" width="120">Supplier</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Receive Reference',footer:'#ft2'" style="width: 400px; padding:2px">
                <form id="invyarnrcvFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="inv_yarn_rcv_id" id="inv_yarn_rcv_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">YRR No</div>
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
                                        {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id')) !!}
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
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvYarnRcv.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invyarnrcvFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvYarnRcv.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="delete" onClick="MsInvYarnRcv.showPdf()">MRR</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="delete" onClick="MsInvYarnRcv.showStorePdf()">MRR2</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
        
    <div title="Item" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Opening Balance',iconCls:'icon-more',footer:'#invyarnrcvitemFrmFt'" style="width:400px; padding:2px">
                <form id="invyarnrcvitemFrm" >
                    <div id="container">
                        <div id="body">
                            <code>                               
                                <div class="row">
                                    <div class="col-sm-4 req-text">Store Name</div>
                                    <div class="col-sm-8">
                                        {!! Form::select("store_id", $store,'',array('id'=>'store_id')) !!}
                                        <input type="hidden" name="inv_yarn_rcv_id" id="inv_yarn_rcv_id" value="" />
                                        <input type="hidden" name="po_yarn_item_id" id="po_yarn_item_id" value="" />
                                        <input type="hidden" name="id" id="id" value="" />
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
                                    <div class="col-sm-4">UOM</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="uom" id="uom" disabled/>
                                    </div>
                                </div>
                                

                                <div class="row middle">
                                    <div class="col-sm-4">Yarn Color</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="color_id" id="color_id"> 
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Yarn Lot/ Batch</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="lot" id="lot">
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Brand</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="brand" id="brand" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Cone/Bag</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="cone_per_bag" id="cone_per_bag" value="" class="number integer" onchange="MsInvYarnRcvItem.calculate_qty_form()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Wgt. / Cone</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="wgt_per_cone" id="wgt_per_cone" value="" class="number integer" onchange="MsInvYarnRcvItem.calculate_qty_form()"/>
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4">No. of Bag</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="no_of_bag" id="no_of_bag" value="" class="number integer" onchange="MsInvYarnRcvItem.calculate_qty_form()"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Rcv. Qnty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" value="" class="number integer" readonly/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Rcv.Rate</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rate" id="rate" value="" class="number integer" onchange="MsInvYarnRcvItem.calculate_qty_form()" readonly/>
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
                                    <div class="col-sm-4">Used Yarn</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="used_yarn" id="used_yarn" value="" class="number integer"  />
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
                                    <div class="col-sm-4">ILE</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="percent" id="percent" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">ILE Cost POC</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="ile_cost_poc" id="ile_cost_poc" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle" style="display:none">
                                    <div class="col-sm-4">ILE Cost POC</div>
                                    <div class="col-sm-8">
                                        <input type="hidden" name="stc_currency_rate" id="stc_currency_rate" value="" class="number integer" />
                                        <input type="hidden" name="rate_stc" id="rate_stc" value="" class="number integer" />
                                        <input type="hidden" name="ile_cost_stc" id="ile_cost_stc" value="" class="number integer" />
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
                    <div id="invyarnrcvitemFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvYarnRcvItem.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invyarnrcvitemFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvYarnRcvItem.remove()">Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'List',footer:'#invyarnrcvitemTblFt'" style="padding:2px">
                <table id="invyarnrcvitemTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'Click'" width="50" formatter="MsInvYarnRcvItem.addSoDetails"></th>
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
                <div id="invyarnrcvitemTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="invyarnrcvitemTbimport" name="import" onClick="MsInvYarnRcvItem.import()">Import</a>
                        
                    </div>
            </div>
        </div>
    </div>
</div>






<div id="openinvyarnrcvitemwindow" class="easyui-window" title=" Yarn Item" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#invyarnrcvitemsearchFrmFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invyarnrcvitemsearchFrm">
                            <div class="row middle">
                            <div class="col-sm-4" >PO No</div>
                            <div class="col-sm-8">
                            <input type="text" name="po_no" id="po_no" />
                            </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4">PI NO</div>
                            <div class="col-sm-8">
                            <input type="text" name="pi_no" id="pi_no" />
                            </div>
                            </div>
                        </form>
                    </code>
                </div>
                <div id="invyarnrcvitemsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvYarnRcvItem.serachItem()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#invyarnrcvitemsearchTblFt'" style="padding:10px;">
            <table id="invyarnrcvitemsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'po_no'" width="100">PO NO</th>
                        <th data-options="field:'pi_no'" width="100">PI NO</th>
                        <th data-options="field:'itemcategory_name'" width="100">Item Category</th>
                        <th data-options="field:'itemclass_name'" width="100">Item Class</th>
                        <th data-options="field:'yarn_count'" width="100">Yarn Count</th>
                        <th data-options="field:'composition'" width="100">Composition</th>
                        <th data-options="field:'yarn_type'" width="100">Yarn Type</th>
                        <th data-options="field:'uom_code'" width="100">UOM</th>
                        <th data-options="field:'qty'" width="100" align="right">Po Qty</th>
                        <th data-options="field:'rate'" width="100" align="right">Po Rate</th>
                        <th data-options="field:'amount'" width="100" align="right">Po Amount</th>
                        <th data-options="field:'currency_code'" width="100">Currency</th>
                        <th data-options="field:'yarn_color'" width="100">Yarn Color</th>
                    </tr>
                </thead>
            </table>
            <div id="invyarnrcvitemsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvYarnRcvItem.closeinvyarnrcvitemWindow()">Next</a>
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

<div id="invyarnrcvlibraryitemserchWindow" class="easyui-window" title="Yarn Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true" style="padding:2px">
            <table id="invyarnrcvlibraryitemserchTbl" style="width:1250px">
                <thead>               
                    
                    <th data-options="field:'id'" width="40px">ID</th>
                    <th field="itemcategory_name">Item category</th>
                    <th field="itemclass_name">Itemclass</th>
                    <th field="yarn_count">Count</th>
                    <th data-options="field:'composition',halign:'center'" width="400">Composition</th>
                    <th data-options="field:'yarn_type',halign:'center'" width="400">Type</th>
                </thead>
            </table>
        </div>
        
            
        <div data-options="region:'west',border:true,footer:'#invyarnrcvlibraryitemserchFrmFt'" style="padding:2px; width:350px">
        <form id="invyarnrcvlibraryitemserchFrm">
            <div id="container">
                 <div id="body">
                   <code>
                   <div class="row ">
                         <div class="col-sm-4">Yarn Count</div>
                         <div class="col-sm-8"> <input type="text" name="yarn_count" id="yarn_count" /> </div>
                     </div>

                     <div class="row middle">
                         <div class="col-sm-4">Yarn Type</div>
                         <div class="col-sm-8">
                         <input type="text" name="yarn_type" id="yarn_type" />
                         </div>
                     </div>
                  </code>
               </div>
            </div>
            <div id="invyarnrcvlibraryitemserchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" id="pdf" onClick="MsInvYarnRcvItem.searchYarn()" >Search</a>
            </div>

          </form>
               
        </div>
    </div>
</div>

<div id="invyarnrcvitemsoWindow" class="easyui-window" title="Sales Order" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',title:'New Entry',footer:'#invyarnrcvitemsosFrmFt'" style="padding:2px; width: 400px">
            <form id="invyarnrcvitemsosFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="inv_yarn_rcv_item_id" id="inv_yarn_rcv_item_id" value="" />
                                    <input type="hidden" name="po_yarn_item_bom_qty_id" id="po_yarn_item_bom_qty_id" value="" />


                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Sales Order No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sales_order_no" id="sales_order_no" placeholder=" Sales Order No" readonly ondblclick="MsInvYarnRcvItemSos.openSalesOrderWindow()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4" >Buyer</div>
                                    <div class="col-sm-8">
                                         <input type="text" name="buyer_name" id="buyer_name" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Style Ref </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="style_ref" id="style_ref" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Qty </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4" >Rate</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rate" id="rate" disabled />
                                    </div>
                                </div>
                               
                                <div class="row middle">
                                    <div class="col-sm-4">Amount</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount" id="amount" disabled />
                                    </div>
                                </div>                                
                            </code>
                        </div>
                    </div>
                    <div id="invyarnrcvitemsosFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvYarnRcvItemSos.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invyarnrcvitemsosFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvYarnRcvItemSos.remove()">Delete</a>
                    </div>
                </form>
        </div>
        <div data-options="region:'center',title:'List'" style="padding:10px;">
            <table id="invyarnrcvitemsosTbl" style="width:500px">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'company_name'" width="100">Company</th>
                        <th data-options="field:'buyer_name'" width="100">Buyer</th>
                        <th data-options="field:'style_ref'" width="100">Style Ref</th>
                        <th data-options="field:'sale_order_no'" width="80">Sales Order No</th>
                        <th data-options="field:'ship_date'" width="80">Ship Date</th>
                        <th data-options="field:'qty'" width="80">Rcv.Qty</th>
                        <th data-options="field:'rate'" width="80">Rate</th>
                        <th data-options="field:'amount'" width="80">Amount</th>
                        <th data-options="field:'po_qty'" width="80" align="right">Po Qty</th>
                        <th data-options="field:'cumulative_qty'" width="80" align="right">Prev Rcv. Qty</th>
                        <th data-options="field:'balance_qty'" width="80" align="right">Balance Qty</th>
                    </tr>
                </thead>
            </table>
        </div>
        
    </div>
</div>



<div id="invyarnrcvitemsosserchWindow" class="easyui-window" title="Sales Order Search" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',title:'Search',footer:'#invyarnrcvitemsossearchFrmFt'" style="padding:2px; width: 400px">
            <form id="invyarnrcvitemsossearchFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Sales Order No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sales_order_no" id="sales_order_no" placeholder=" Sales Order No"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4" >Buyer</div>
                                    <div class="col-sm-8">
                                         <input type="text" name="buyer_name" id="buyer_name" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Style Ref </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="style_ref" id="style_ref" />
                                    </div>
                                </div>
                                                                
                            </code>
                        </div>
                    </div>
                    <div id="invyarnrcvitemsossearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvYarnRcvItemSos.salesorderSearch()">Search</a>
                        
                    </div>
                </form>
        </div>
        <div data-options="region:'center',title:'List'" style="padding:10px;">
            <table id="invyarnrcvitemsosserchTbl" style="width:500px">
                <thead>
                    <tr>
                        <th data-options="field:'po_yarn_item_bom_qty_id'" width="40">ID</th>
                        <th data-options="field:'id'" width="100">Sale Order ID</th>
                        <th data-options="field:'company_name'" width="100">Company</th>
                        <th data-options="field:'buyer_name'" width="100">Buyer</th>
                        <th data-options="field:'style_ref'" width="100">Style Ref</th>
                        <th data-options="field:'sale_order_no'" width="80">Sales Order No</th>
                        <th data-options="field:'ship_date'" width="80">Ship Date</th>
                        <th data-options="field:'po_qty'" width="80" align="right">Po Qty</th>
                        <th data-options="field:'cumulative_qty'" width="80" align="right">Rec. Qty</th>
                        <th data-options="field:'balance_qty'" width="80" align="right">Balance Qty</th>
                    </tr>
                </thead>
            </table>
        </div>
        
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Inventory/Yarn/MsAllInvYarnRcvController.js"></script>
<script>
    $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
    
    $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
        }
    });
</script>
