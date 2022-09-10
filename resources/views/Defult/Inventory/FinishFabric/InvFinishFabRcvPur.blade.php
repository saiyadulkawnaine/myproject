<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="invfinishfabrcvpurtabs">
    <div title="Finish Fabric Purchase Receive Entry" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="invfinishfabrcvpurTbl" style="width:500px">
                    <thead>
                        <tr>
                            <th data-options="field:'inv_finish_fab_rcv_id'" width="40">ID</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'receive_no'" width="100">Receive No</th>
                            <th data-options="field:'challan_no'" width="80">Challan No</th>
                            <th data-options="field:'po_no'" width="80">Po No</th>
                            <th data-options="field:'receive_date'" width="80">Receive Date</th>
                            <th data-options="field:'remarks'" width="200">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Receive Reference',footer:'#ft2'" style="width: 400px; padding:2px">
                <form id="invfinishfabrcvpurFrm">
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
                                    <div class="col-sm-4 req-text">Po No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="po_no" id="po_no" placeholder="Double Click" ondblclick="MsInvFinishFabRcvPur.poWindow()" readonly />
                                        <input type="hidden" name="po_fabric_id" id="po_fabric_id" value="" readonly />
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Dlv Challan</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="challan_no" id="challan_no"/>
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-4 req-text" >Company</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="company_name" id="company_name" value="" disabled />
                                        <input type="hidden" name="company_id" id="company_id" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text" >Supplier</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="supplier_name" id="supplier_name" value="" disabled />
                                        <input type="hidden" name="supplier_id" id="supplier_id" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Receive Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="receive_date" id="receive_date" class="datepicker" placeholder=" yyyy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Remarks</div>
                                    <div class="col-sm-8">
                                    	<textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvFinishFabRcvPur.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invfinishfabrcvpurFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvFinishFabRcvPur.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="delete" onClick="MsInvFinishFabRcvPur.showPdf()">MRR</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="delete" onClick="MsInvFinishFabRcvPur.showPdfTwo()">Challan</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Fabrication" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="invfinishfabrcvpurfabricTbl" style="width:500px">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'buyer_name'" width="100">Buyer</th>
                            <th data-options="field:'style_ref'" width="100">Style</th>
                            <th data-options="field:'sale_order_no'" width="100">Sales Order No</th>
                            <th data-options="field:'gmts_part_name'" width="100">Gmt Part</th>
                            <th data-options="field:'fabrication'" width="100">Fabrication</th>
                            <th data-options="field:'req_gsm_weight'" width="100">Req. GSM</th>
                            <th data-options="field:'req_dia'" width="80">Req. Dia</th>
                            <th data-options="field:'fabric_color'" width="80">Fabric Color</th>
                            <th data-options="field:'colorrange_name'" width="80"> Color Range</th>
                            <th data-options="field:'gsm_weight'" width="80"> GSM</th>
                            <th data-options="field:'dia'" width="80"> Dia</th>
                            <th data-options="field:'stitch_length'" width="80"> Stitch Length</th>
                            <th data-options="field:'shrink_per'" width="80"> Shrink %</th>
                            <th data-options="field:'qty'" width="80">Qty</th>
                            <th data-options="field:'rate'" width="80">Rate</th>
                            <th data-options="field:'amount'" width="80">Amount</th>
                            <th data-options="field:'remarks'" width="200">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Fabrication',footer:'#invfinishfabrcvpurfabricFrmFt'" style="width: 400px; padding:2px">
                <form id="invfinishfabrcvpurfabricFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Fabrication</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fabrication" id="fabrication" ondblclick="MsInvFinishFabRcvPurFabric.getFabric()" />
                                        <input type="hidden" name="po_fabric_item_id" id="po_fabric_item_id" readonly />
                                        <input type="hidden" name="id" id="id" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text"> Req. Dia</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="req_dia" id="req_dia"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Fabric Color</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fabric_color" id="fabric_color"   disabled />
                                        <input type="hidden" name="fabric_color_id" id="fabric_color_id"   readonly />
                                        
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Sales Order No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sales_order_no" id="sales_order_no"/>
                                        <input type="hidden" name="sales_order_id" id="sales_order_id"/>
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Color Range</div>
                                    <div class="col-sm-8">
                                         {!! Form::select("colorrange_id", $colorrange,'',array('id'=>'colorrange_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4 req-text">GSM</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="gsm_weight" id="gsm_weight"/>
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4 req-text">Dia</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="dia" id="dia"/>
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4 req-text">Stitch Length</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="stitch_length" id="stitch_length"/>
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4 req-text">Shrink %</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="shrink_per" id="shrink_per"/>
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4 req-text">Rate</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="rate" id="rate" class="number integer" readonly />
                                  </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Remarks</div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="invfinishfabrcvpurfabricFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvFinishFabRcvPurFabric.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invfinishfabrcvpurfabricFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvFinishFabRcvPurFabric.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
        
    <div title="Rolls" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="invfinishfabrcvpuritemTbl" style="width:500px">
                    <thead>
                        <tr>
                          <th data-options="field:'id'" width="70">ID</th>
                          <th data-options="field:'roll_no'" width="70">Roll No</th>
                          <th data-options="field:'qty'" width="80">Rcv. Qty</th>
                          <th data-options="field:'body_part'" width="100">Body Part</th>
                          <th data-options="field:'fabrication'" width="200">Fabric <br/>Description</th>
                          <th data-options="field:'fabric_shape'" width="70">Fabric <br/>Shape</th>
                          <th data-options="field:'fabric_look'" width="70">Fabric <br/>Look</th>
                          <th data-options="field:'gsm_weight'" width="40">GSM</th>
                          <th data-options="field:'dia'" width="40">Dia/<br/>Widht</th>
                          <th data-options="field:'stitch_length'" width="70">Stitch<br/>Length</th>
                          <th data-options="field:'shrink_per'" width="60">Shrink %</th>
                          
                          
                        </tr>
                    </thead>
                </table>

                
            </div>
            <div data-options="region:'west',border:true,title:'Receive Reference',footer:'#invfinishfabrcvpuritemFrmFt'" style="width: 400px; padding:2px">
                
                <form id="invfinishfabrcvpuritemFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="inv_finish_fab_rcv_id" id="inv_finish_fab_rcv_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Store</div>
                                    <div class="col-sm-8">
                                         {!! Form::select("store_id", $store,'',array('id'=>'store_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4 req-text">Roll Wgt</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="roll_weight" id="roll_weight" class="integer number"/>
                                  
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4">Roll Length</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="roll_length" id="roll_length" class="integer number" />
                                  </div>
                                </div>
                               
                                
                                
                                

                                <div class="row middle">
                                  <div class="col-sm-4">Roll No</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="roll_no" id="roll_no" />
                                  
                                  </div>
                                </div>
                                
                                
                               
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Room</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="room" id="room"/>
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4 req-text">Rack</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rack" id="rack"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Shelf</div>
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
                    <div id="invfinishfabrcvpuritemFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvFinishFabRcvPurItem.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invfinishfabrcvpuritemFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvFinishFabRcvPurItem.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</div>


<div id="invfinishfabrcvpurpoWindow" class="easyui-window" title=" FinishFab Item" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
      <div data-options="region:'west',border:true,title:'Po Search',footer:'#invfinishfabrcvpurpoFrmFt'" style="width: 400px; padding:2px">
        <form id="invfinishfabrcvpurpoFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Po No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="po_no" id="po_no"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company</div>
                                    <div class="col-sm-8">
                                         {!! Form::select("company_id", $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Supplier</div>
                                    <div class="col-sm-8">
                                         {!! Form::select("supplier_id", $supplier,'',array('id'=>'supplier_id')) !!}
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="invfinishfabrcvpurpoFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvFinishFabRcvPur.getPo()">Search</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invfinishfabrcvpurpoFrm')">Reset</a>
                        
                    </div>
                </form>
      </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="invfinishfabrcvpurpoTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'po_no'" width="100">PO No</th>
                            <th data-options="field:'po_date'" width="100">PO Date</th>
                            <th data-options="field:'company_name'" width="60">Company</th>
                            <th data-options="field:'supplier_name'" width="230">Supplier</th>
                            <th data-options="field:'paymode'" width="140">Pay Mode</th>
                            <th data-options="field:'currency_code'" width="60">Currency</th>
                            <th data-options="field:'exch_rate'" width="50">Exch.<br>Rate</th>
                            <th data-options="field:'pi_date'" width="80">PI Date</th>
                            <th data-options="field:'pi_no'" width="150">PI No</th>
                            <th data-options="field:'remarks'" width="180">Remarks</th>
                        </tr>
                    </thead>
                </table>
        </div>
    </div>
</div>

<div id="invfinishfabrcvpurfabricWindow" class="easyui-window" title="Fabrication" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center'" style="padding:10px;">
            <table id="invfinishfabrcvpurfabricsearchTbl" style="width:100%">
                    <thead>
                        <tr>
                        <th data-options="field:'po_fabric_item_id'" width="40px">ID</th>
                        <th data-options="field:'style_gmt'" width="200px">GMT Item</th>
                        <th data-options="field:'style_ref'" width="200px">Style Ref</th>
                        <th data-options="field:'buyer_code'" width="200px">Buyer</th>
                        <th data-options="field:'sale_order_no'" width="200px">Sale Order No</th>
                        <th data-options="field:'gmtspart'" width="150px">GMT Part</th>
                        <th data-options="field:'fabric_description'" width="250px">Fabric Description</th>
                        <th data-options="field:'fabricnature'" width="80px">Fabric Nature</th>
                        <th data-options="field:'fabriclooks'" width="80px">Fabric Looks</th>
                        <th data-options="field:'fabricshape'" width="80px">Fabric Shape</th>
                        <th data-options="field:'fabric_color_name'" width="80px">Fabric Color</th>
                        <th data-options="field:'gsm_weight'" width="30px">GSM/WGT</th>
                        <th data-options="field:'dia'" width="30px">Dia</th>
                        <th data-options="field:'qty'" width="70px" align="right">Qty</th>
                        <th data-options="field:'rate'" width="70px" align="right">Rate</th>
                        <th data-options="field:'amount'" width="80px" align="right">Amount</th>
                        
                        </tr>
                    </thead>
                </table>
        </div>
    </div>
</div>












<script type="text/javascript" src="<?php echo url('/');?>/js/Inventory/FinishFabric/MsAllInvFinishFabRcvPurController.js"></script>
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
