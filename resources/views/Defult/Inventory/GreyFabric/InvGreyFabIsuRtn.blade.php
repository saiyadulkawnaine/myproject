<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="invgreyfabisurtntabs">
    <div title="Grey Fabric Issue Return Entry" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="invgreyfabisurtnTbl" style="width:500px">
                    <thead>
                        <tr>
                            <th data-options="field:'inv_grey_fab_rcv_id'" width="40">ID</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'supplier_id'" width="100">Supplier</th>
                            <th data-options="field:'receive_no'" width="100">Receive No</th>
                            <th data-options="field:'receive_basis_id'" width="100">Receive Basis</th>
                            <th data-options="field:'receive_date'" width="80">Receive Date</th>
                            <th data-options="field:'remarks'" width="150">Remarks</th>

                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Receive Reference',footer:'#invgreyfabisurtnFrmFt'" style="width: 400px; padding:2px">
                <form id="invgreyfabisurtnFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="inv_grey_fab_rcv_id" id="inv_grey_fab_rcv_id" value="" />
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
                                    <div class="col-sm-4 req-text" >Return From </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id')) !!}
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
                                        <input type="text" name="remarks" id="remarks" />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="invgreyfabisurtnFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvGreyFabIsuRtn.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invgreyfabisurtnFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvGreyFabIsuRtn.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="delete" onClick="MsInvGreyFabIsuRtn.showPdf()">MRR</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="delete" onClick="MsInvGreyFabIsuRtn.showPdfTwo()">Challan</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
        
    <div title="Item" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            
            <div data-options="region:'center',border:true,title:'List',footer:'#invgreyfabisurtnitemTblFt'" style="padding:2px">
                <table id="invgreyfabisurtnitemTbl" style="width:100%">
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
                          <th data-options="field:'gsm_weight'" width="40">GSM</th>
                          <th data-options="field:'dia_width'" width="40">Dia/<br/>Widht</th>
                          <th data-options="field:'measurement'" width="70">Measurement</th>
                          <th data-options="field:'roll_length'" width="70">Roll Length</th>
                          <th data-options="field:'stitch_length'" width="70">Stitch<br/>Length</th>
                          <th data-options="field:'shrink_per'" width="60">Shrink %</th>
                          <th data-options="field:'colorrange_name'" width="80">Color Range</th>
                          <th data-options="field:'fabric_color'" width="80">Dyeing Color</th>

                          
                          
                          <th data-options="field:'qty_pcs'" width="100">PCS</th>
                          <th data-options="field:'prod_no'" width="70">Prod. Ref</th>
                          <th data-options="field:'floor_name'" width="100">Prod. <br/>Floor</th>
                          <th data-options="field:'machine_no'" width="100">M/C No</th>
                          <th data-options="field:'machine_dia'" width="60">M/C Dia</th>
                          <th data-options="field:'machine_gg'" width="60">M/C Gauge</th>
                          <th data-options="field:'shift_name'" width="80">Shift Name</th>
                          <th data-options="field:'sale_order_no'" width="100">Order No</th>
                          <th data-options="field:'style_ref'" width="100">Style Ref</th>
                          <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
                          <th data-options="field:'gmt_sample'" width="100">Gmt Sample</th>
                          <th data-options="field:'supplier_name'" width="100">Produced By</th>
                          <th data-options="field:'customer_name'" width="100">Produced For</th>
                            
                        </tr>
                    </thead>
                </table>
                 <div id="invgreyfabisurtnitemTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                
                <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvGreyFabIsuRtnItem.import()">Import</a>
                </div>
            </div>
        </div>
    </div>
    
</div>
<div id="invgreyfabisurtnitemsearchwindow" class="easyui-window" title="Roll" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#invgreyfabisurtnitemsearchFrmFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invgreyfabisurtnitemsearchFrm">
                            <div class="row middle">
                            <div class="col-sm-4" >Challan No</div>
                            <div class="col-sm-8">
                            <input type="text" name="challan_no" id="challan_no" />
                            </div>
                            </div>
                            
                        </form>
                    </code>
                </div>
                <div id="invgreyfabisurtnitemsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvGreyFabIsuRtnItem.serachItem()">Show</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="invgreyfabisurtnitemsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'cdf'" width="100" formatter="MsInvGreyFabIsuRtnItem.formatsv">Action</th>
                          <th data-options="field:'id'" width="70">ID</th>
                          <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
                          <th data-options="field:'custom_no'" width="70">Custom No</th>
                          <th data-options="field:'rcv_qty'" width="80">Issue Qty</th>
                          <th data-options="field:'body_part'" width="100">Body Part</th>
                          <th data-options="field:'fabrication'" width="200">Fabric <br/>Description</th>
                          <th data-options="field:'fabric_shape'" width="70">Fabric <br/>Shape</th>
                          <th data-options="field:'fabric_look'" width="70">Fabric <br/>Look</th>
                          <th data-options="field:'gsm_weight'" width="40">GSM</th>
                          <th data-options="field:'dia_width'" width="40">Dia/<br/>Widht</th>
                          <th data-options="field:'measurement'" width="70">Measurement</th>
                          <th data-options="field:'roll_length'" width="70">Roll Length</th>
                          <th data-options="field:'stitch_length'" width="70">Stitch<br/>Length</th>
                          <th data-options="field:'shrink_per'" width="60">Shrink %</th>
                          <th data-options="field:'colorrange_name'" width="80">Color Range</th>
                          <th data-options="field:'fabric_color'" width="80">Dyeing Color</th>

                          
                          
                          <th data-options="field:'qty_pcs'" width="100">PCS</th>
                          <th data-options="field:'prod_no'" width="70">Prod. Ref</th>
                          <th data-options="field:'floor_name'" width="100">Prod. <br/>Floor</th>
                          <th data-options="field:'machine_no'" width="100">M/C No</th>
                          <th data-options="field:'machine_dia'" width="60">M/C Dia</th>
                          <th data-options="field:'machine_gg'" width="60">M/C Gauge</th>
                          <th data-options="field:'shift_name'" width="80">Shift Name</th>
                          <th data-options="field:'sale_order_no'" width="100">Order No</th>
                          <th data-options="field:'style_ref'" width="100">Style Ref</th>
                          <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
                          <th data-options="field:'gmt_sample'" width="100">Gmt Sample</th>
                          <th data-options="field:'supplier_name'" width="100">Produced By</th>
                          <th data-options="field:'customer_name'" width="100">Produced For</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div id="invgreyfabisurtnitemwindow" class="easyui-window" title="Save/Edit" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:450px;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center',footer:'#invgreyfabisurtnitemFrmFt'" style="padding:10px;">
            <form id="invgreyfabisurtnitemFrm" >
                    <div id="container">
                        <div id="body">
                            <code>                               
                            <div class="row">
                            <div class="col-sm-4 req-text">Store Name</div>
                            <div class="col-sm-8">
                            {!! Form::select("store_id", $store,'',array('id'=>'store_id')) !!}
                            <input type="hidden" name="inv_grey_fab_rcv_id" id="inv_grey_fab_rcv_id" value="" />
                            <input type="hidden" name="id" id="id" value="" />

                            <input type="hidden" name="inv_grey_fab_item_id" id="inv_grey_fab_item_id" readonly />
                            <input type="hidden" name="inv_grey_fab_isu_item_id" id="inv_grey_fab_isu_item_id" readonly />
                            <input type="hidden" name="prod_knit_dlv_roll_id" id="prod_knit_dlv_roll_id" readonly />
                            <input type="hidden" name="row_index" id="row_index" readonly />
                            </div>
                            </div>

                           

                            <div class="row middle">
                            <div class="col-sm-4">Roll No</div>
                            <div class="col-sm-8">
                            <input type="text" name="roll_no" id="roll_no" readonly/>
                            </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4">Custom No</div>
                            <div class="col-sm-8">
                            <input type="text" name="custom_no" id="custom_no" readonly/>
                            </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4">Qty</div>
                            <div class="col-sm-8">
                            <input type="text" name="qty" id="qty" class="number integer"/>
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
                    <div id="invgreyfabisurtnitemFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvGreyFabIsuRtnItem.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invgreyfabisurtnitemFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvGreyFabIsuRtnItem.remove()">Delete</a>
                    </div>
                </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Inventory/GreyFabric/MsAllInvGreyFabIsuRtnController.js"></script>
<script>
    $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script>
