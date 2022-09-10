<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="invfinishfabtransintabs">
    <div title="Finish Fabric Item Transfer In Entry" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="invfinishfabtransinTbl" style="width:500px">
                    <thead>
                        <tr>
                            <th data-options="field:'inv_finish_fab_rcv_id'" width="40">ID</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'from_company'" width="100">From Company</th>
                            <th data-options="field:'receive_no'" width="100">Receive No</th>
                            <th data-options="field:'receive_basis_id'" width="100">Receive Basis</th>
                            <th data-options="field:'challan_no'" width="80">Challan No</th>
                            <th data-options="field:'receive_date'" width="80">Receive Date</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Receive Reference',footer:'#invfinishfabtransinFrmFt'" style="width: 400px; padding:2px">
                <form id="invfinishfabtransinFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="inv_finish_fab_rcv_id" id="inv_finish_fab_rcv_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Receive No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="receive_no" id="receive_no" placeholder=" Receive No" disabled />
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4 req-text">Challan No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="challan_no" id="challan_no" placeholder="Double Click" ondblclick="MsInvFinishFabTransIn.challanWindow()" readonly />
                                        <input type="hidden" name="inv_isu_id" id="inv_isu_id" readonly/>
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
                                    <div class="col-sm-4 req-text">Receive Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="receive_date" id="receive_date" class="datepicker" placeholder=" yyyy-mm-dd" />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="invfinishfabtransinFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvFinishFabTransIn.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invfinishfabtransinFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvFinishFabTransIn.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="delete" onClick="MsInvFinishFabTransIn.showPdf()">MRR</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="delete" onClick="MsInvFinishFabTransIn.showPdfTwo()">Challan</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
        
    <div title="Item" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
          <div data-options="region:'west',border:true,title:'Receive Reference',footer:'#invfinishfabtransinitemFrmFt'" style="width: 400px; padding:2px">
          <form id="invfinishfabtransinitemFrm" >
          <div id="container">
          <div id="body">
          <code>                               
          <div class="row">
          <div class="col-sm-4 req-text">Store Name</div>
          <div class="col-sm-8">
          {!! Form::select("store_id", $store,'',array('id'=>'store_id')) !!}
          <input type="hidden" name="inv_finish_fab_rcv_id" id="inv_finish_fab_rcv_id" value="" />
          <input type="hidden" name="id" id="id" value="" />

          <input type="hidden" name="inv_finish_fab_item_id" id="inv_finish_fab_item_id" readonly />
          <input type="hidden" name="inv_finish_fab_isu_item_id" id="inv_finish_fab_isu_item_id" readonly />
          <input type="hidden" name="prod_knit_dlv_roll_id" id="prod_knit_dlv_roll_id" readonly />
          </div>
          </div>

          
          
          <div class="row middle">
          <div class="col-sm-4 req-text">Qty</div>
          <div class="col-sm-8">
          <input type="text" name="qty" id="qty" class="number integer"/>
          </div>
          </div>
          <div class="row middle">
          <div class="col-sm-4">Room</div>
          <div class="col-sm-8">
          <input type="text" name="room" id="room"/>
          </div>
          </div>
          <div class="row middle">
          <div class="col-sm-4">Rack</div>
          <div class="col-sm-8">
          <input type="text" name="rack" id="rack"/>
          </div>
          </div>
          <div class="row middle">
          <div class="col-sm-4">Shelf</div>
          <div class="col-sm-8">
          <input type="text" name="shelf" id="shelf"/>
          </div>
          </div>

          <div class="row middle">
          <div class="col-sm-4 req-text">Remarks</div>
          <div class="col-sm-8">
          <input type="text" name="remarks" id="remarks"/>
          </div>
          </div>




          </code>
          </div>
          </div>
          <div id="invfinishfabtransinitemFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
          <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvFinishFabTransInItem.submit()">Save</a>
          <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invfinishfabtransinitemFrm')">Reset</a>
          <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvFinishFabTransInItem.remove()">Delete</a>
          </div>
          </form>
          </div>
            
            <div data-options="region:'center',border:true,title:'List',footer:'#invfinishfabtransinitemTblFt'" style="padding:2px">
                <table id="invfinishfabtransinitemTbl" style="width:100%">
                    <thead>
                        <tr>
                            
                        <th data-options="field:'id'" width="70">ID</th>
                          <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
                          <th data-options="field:'custom_no'" width="70">Custom No</th>
                          <th data-options="field:'qty'" width="80">Rcv. Qty</th>
                          <th data-options="field:'body_part'" width="100">Body Part</th>
                          <th data-options="field:'fabrication'" width="200">Fabric <br/>Description</th>
                          <th data-options="field:'fabric_shape'" width="70">Fabric <br/>Shape</th>
                          <th data-options="field:'fabric_look'" width="70">Fabric <br/>Look</th>
                          <th data-options="field:'batch_color_name'" width="80">Fabric Color</th>
                          <th data-options="field:'gsm_weight'" width="40">GSM</th>
                          <th data-options="field:'dia_width'" width="40">Dia/<br/>Widht</th>
                          <th data-options="field:'measurement'" width="70">Measurement</th>
                          <th data-options="field:'roll_length'" width="70">Roll Length</th>
                          <th data-options="field:'stitch_length'" width="70">Stitch<br/>Length</th>
                          <th data-options="field:'shrink_per'" width="60">Shrink %</th>
                          
                          <th data-options="field:'store_name'" width="80">Store</th>

                          
                          
                          <th data-options="field:'qty_pcs'" width="100">PCS</th>
                          <th data-options="field:'prod_no'" width="70">Knit. Ref</th>
                          <th data-options="field:'dyeing_batch_no'" width="70">Dyeing Batch</th>
                          <th data-options="field:'aop_batch_no'" width="70">AOP Batch</th>
                          <th data-options="field:'sale_order_no'" width="100">Order No</th>
                          <th data-options="field:'style_ref'" width="100">Style Ref</th>
                          <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
                          
                          <th data-options="field:'customer_name'" width="100">Produced For</th>
                            
                        </tr>
                    </thead>
                </table>
                 <div id="invfinishfabtransinitemTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                
                <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvFinishFabTransInItem.import()">Import</a>
                </div>
            </div>
        </div>
    </div>
    
</div>


<div id="invfinishfabtransinchallansearchwindow" class="easyui-window" title=" Yarn Item" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#invfinishfabtransinchallansearchFrmFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invfinishfabtransinchallansearchFrm">
                            <div class="row middle">
                            <div class="col-sm-4" >Issue No</div>
                            <div class="col-sm-8">
                            <input type="text" name="issue_no" id="issue_no" />
                            </div>
                            </div>
                            
                        </form>
                    </code>
                </div>
                <div id="invfinishfabtransinchallansearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvFinishFabTransIn.serachChallan()">Show</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="invfinishfabtransinchallansearchTbl" style="width:100%">
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
    </div>
</div>

<div id="invfinishfabtransinitemsearchwindow" class="easyui-window" title=" Yarn Item" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#invfinishfabtransinitemsearchFrmFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invfinishfabtransinitemsearchFrm">
                            <div class="row middle">
                            <div class="col-sm-4" >Challan No</div>
                            <div class="col-sm-8">
                            <input type="text" name="challan_no" id="challan_no" />
                            </div>
                            </div>
                            
                        </form>
                    </code>
                </div>
                <div id="invfinishfabtransinitemsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvFinishFabTransInItem.serachItem()">Show</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#invfinishfabtransinitemsearchTblFT'" style="padding:10px;">
            <table id="invfinishfabtransinitemsearchTbl" style="width:100%">
                <thead>
                    <tr>
                          <th data-options="field:'id'" width="70">ID</th>
                          <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
                          <th data-options="field:'custom_no'" width="70">Custom No</th>
                          <th data-options="field:'rcv_qty'" width="80">Issue Qty</th>
                          <th data-options="field:'body_part'" width="100">Body Part</th>
                          <th data-options="field:'fabrication'" width="200">Fabric <br/>Description</th>
                          <th data-options="field:'fabric_shape'" width="70">Fabric <br/>Shape</th>
                          <th data-options="field:'fabric_look'" width="70">Fabric <br/>Look</th>
                          <th data-options="field:'batch_color_name'" width="80">Fabric Color</th>
                          <th data-options="field:'gsm_weight'" width="40">GSM</th>
                          <th data-options="field:'dia_width'" width="40">Dia/<br/>Widht</th>
                          <th data-options="field:'measurement'" width="70">Measurement</th>
                          <th data-options="field:'roll_length'" width="70">Roll Length</th>
                          <th data-options="field:'stitch_length'" width="70">Stitch<br/>Length</th>
                          <th data-options="field:'shrink_per'" width="60">Shrink %</th>
                          <th data-options="field:'qty_pcs'" width="100">PCS</th>
                          <th data-options="field:'prod_no'" width="70">Knit. Ref</th>
                          <th data-options="field:'dyeing_batch_no'" width="70">Dyeing Batch</th>
                          <th data-options="field:'aop_batch_no'" width="70">AOP Batch</th>
                         
                          <th data-options="field:'sale_order_no'" width="100">Order No</th>
                          <th data-options="field:'style_ref'" width="100">Style Ref</th>
                          <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
                          
                          <th data-options="field:'customer_name'" width="100">Produced For</th>
                    </tr>
                </thead>
            </table>
            <div id="invfinishfabtransinitemsearchTblFT" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvFinishFabTransInItem.closeinvfinishfabtransinitemsearchwindow()">NEXT</a>
                </div>
        </div>
    </div>
</div>
<div id="invfinishfabtransinitemwindow" class="easyui-window" title="Save/Edit" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center',footer:'#invfinishfabtransinitemmultiFrmFt'" style="padding:10px;">
            <form id="invfinishfabtransinitemmultiFrm" >
                    <div id="invfinishfabtransinitemscs">
                    </div>
                    <div id="invfinishfabtransinitemmultiFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvFinishFabTransInItem.submitBatch()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invfinishfabtransinitemFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvFinishFabTransInItem.remove()">Delete</a>
                    </div>
                </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Inventory/FinishFabric/MsAllInvFinishFabTransInController.js"></script>
<script>
    $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script>
