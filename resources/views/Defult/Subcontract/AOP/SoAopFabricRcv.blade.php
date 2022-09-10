<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="soaopfabricrcvtabs">
    <div title="Start Up" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="soaopfabricrcvTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'receive_no'" width="30">Receive No</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'buyer_id'" width="100">Customer</th>
                            <th data-options="field:'sales_order_no'" width="100">Sales Order NO</th>
                            <th data-options="field:'receive_date'" width="100">Receive Date</th>
                            <th data-options="field:'challan_no'" width="100">Challan No</th>
                            <th data-options="field:'remarks'" width="100">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Work Order Received',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#soknitFrmft'" style="width:450px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="soaopfabricrcvFrm">
                                <div class="row">
                                    <input type="hidden" name="id" id="id" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">MRR No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="receive_no" id="receive_no"  disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Challan No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="challan_no" id="challan_no"/>
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-5 req-text">Sales Order No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="sales_order_no" id="sales_order_no" ondblclick="MsSoAopFabricRcv.soWindow()" placeholder="Double Click"/>
                                        <input type="hidden" name="so_aop_id" id="so_aop_id" />
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Company</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Customer</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                 
                               
                                                                  
                               
                                <div class="row middle">
                                    <div class="col-sm-5"> Receive Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="receive_date" id="receive_date" class="datepicker" />
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-5">Remarks </div>
                                    <div class="col-sm-7">
                                        <textarea name="remarks" id="remarks" value=""></textarea>
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="soknitFrmft" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoAopFabricRcv.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('soaopfabricrcvFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoAopFabricRcv.remove()">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Item Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Information',iconCls:'icon-more',footer:'#soaopfabricrcvitemFrmFt'" style="width:450px; padding:2px">
                <form id="soaopfabricrcvitemFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <input type="hidden" name="so_aop_fabric_rcv_id" id="so_aop_fabric_rcv_id" value="" />
                                    <input type="hidden" name="so_aop_ref_id" id="so_aop_ref_id" value="" />
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                
                                
                                
                              
                                <!-- <div class="row middle">
                                    <div class="col-sm-4 req-text">Qty  </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" value="" class="number integer" onchange="MsSoAopFabricRcvItem.calculate_form()" />
                                    </div>
                                </div> -->
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Rate/Unit </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rate" id="rate" value="" class="number integer" onchange="MsSoAopFabricRcvItem.calculate_form()" />
                                    </div>
                                </div>
                                <!-- <div class="row middle">
                                    <div class="col-sm-4 req-text">Amount </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount" id="amount" value="" class="number integer" readonly/>
                                    </div>
                                </div> -->
                                 <div class="row middle">
                                    <div class="col-sm-4 req-text">Process Loss %</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="process_loss_per" id="process_loss_per" value="" class="number integer"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Real Rate/Unit</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="real_rate" id="real_rate" value="" class="number integer"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Yarn Desc. </div>
                                    <div class="col-sm-8">
                                        <textarea name="yarn_des" id="yarn_des" value=""></textarea>
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4">Remarks </div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks" value=""></textarea>
                                    </div>
                                </div>
                                

                                
                                  
                            </code>
                        </div>
                    </div>
                    <div id="soaopfabricrcvitemFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoAopFabricRcvItem.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('subinborderproductFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoAopFabricRcvItem.remove()">Delete</a>
                    </div>
                </form>  
            </div>
            <div data-options="region:'center',border:true,title:'Lists',footer:'#soaopfabricrcvitemTblFt'" style="padding:2px">
                <table id="soaopfabricrcvitemTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'fabrication'" width="100">Fabrication</th>
                            <th data-options="field:'fabriclooks'" width="100">Fabric Looks</th>
                            <th data-options="field:'fabricshape'" width="100">Fabric Shape</th>
                            <th data-options="field:'gsm_weight'" width="70" align="right">GSM/Weight</th>
                            <th data-options="field:'aop_color'" width="80" align="right">Aop Color</th>
                            <th data-options="field:'colorrange_id'" width="70">Color Range</th>
                            <th data-options="field:'uom_code'" width="80">Uom</th>
                            <th data-options="field:'qty'" width="70" align="right">Qty</th>
                            <th data-options="field:'rate'" width="70" align="right">Rate</th>
                            <th data-options="field:'amount'" width="100" align="right">Amount</th>
                            <th data-options="field:'process_loss_per'" width="100" align="right">Process Loss %</th>
                            <th data-options="field:'real_rate'" width="80">Real Rate</th>
                            <th data-options="field:'yarn_des'" width="80">Yarn Desc.</th>
                            <th data-options="field:'remarks'" width="80">Remarks</th>
                        </tr>
                    </thead>
                </table>
                <div id="soaopfabricrcvitemTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoAopFabricRcvItem.import()">Import</a>
                    </div>
            </div>
        </div>
    </div>
    <div title="Roll Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Information',iconCls:'icon-more',footer:'#soaopfabricrcvrolFrmFt'" style="width:450px; padding:2px">
                <form id="soaopfabricrcvrolFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <input type="hidden" name="so_aop_fabric_rcv_item_id" id="so_aop_fabric_rcv_item_id" value="" />
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                
                                
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Roll No  </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="custom_no" id="custom_no"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Qty  </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" value="" class="number integer" onchange="MsSoAopFabricRcvItem.calculate_form()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Room  </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="room" id="room"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Rack  </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rack" id="rack"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Shelf  </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="shelf" id="shelf"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks  </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="remarks" id="remarks"/>
                                    </div>
                                </div>
                                
                            </code>
                        </div>
                    </div>
                    <div id="soaopfabricrcvrolFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoAopFabricRcvRol.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('soaopfabricrcvrolFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoAopFabricRcvRol.remove()">Delete</a>
                    </div>
                </form>  
            </div>
            <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                <table id="soaopfabricrcvrolTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'custom_no'" width="100">Roll No</th>
                            <th data-options="field:'qty'" width="70" align="right">Qty</th>
                            <th data-options="field:'rate'" width="70" align="right">Rate</th>
                            <th data-options="field:'amount'" width="100" align="right">Amount</th>
                            <th data-options="field:'room'" width="100" align="right">Room</th>
                            <th data-options="field:'rack'" width="100" align="right">Rack</th>
                            <th data-options="field:'shelf'" width="100" align="right">Shelf</th>
                            <th data-options="field:'remarks'" width="80">Remarks</th>
                        </tr>
                    </thead>
                </table>
                
            </div>
        </div>
    </div>
</div>
    


<div id="soaopfabricrcvsoWindow" class="easyui-window" title="Subcontract Order Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search',footer:'#soaopfabricrcvsoWindowFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="soaopfabricrcvsosearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Sales Order No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="so_no" id="so_no">
                                </div>
                            </div>
                           
                        </form>
                    </code>
                </div>
                 <div id="soaopfabricrcvsoWindowFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="border-radius:1px" onClick="MsSoAopFabricRcv.getsubconorder()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#soaopfabricrcvsosearchTblFt'" style="padding:10px;">
            <table id="soaopfabricrcvsosearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="30">ID</th>
                        <th data-options="field:'sales_order_no'" width="100">Sales Order No</th>
                        <th data-options="field:'company_name'" width="100">Company</th>
                        <th data-options="field:'buyer_name'" width="100">Buyer</th>

                    </tr>
                </thead>
            </table>
             <div id="soaopfabricrcvsosearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#soaopfabricrcvsoWindow').window('close')" style="border-radius:1px">Close</a>
        </div>
        </div>
       
    </div>
</div>

<div id="soaopfabricrcvitemWindow" class="easyui-window" title="Fabric Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center',footer:'#soaopfabricrcvitemWindowFt'" style="padding:10px;">
        <div id="soaopfabricrcvitemWindowscs">
        </div>
        <div id="soaopfabricrcvitemWindowFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoAopFabricRcvItem.submitBatch()">Save</a>

        </div>
      </div>  
       
    </div>
</div>




<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/AOP/MsSoAopFabricRcvController.js"></script>
 <script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/AOP/MsSoAopFabricRcvItemController.js"></script> 
 <script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/AOP/MsSoAopFabricRcvRolController.js"></script> 
<script>
$(document).ready(function() {
    $(".datepicker").datepicker({
        beforeShow:function(input) {
            $(input).css({
                "position": "relative",
                "z-index": 999999
            });
        },
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
    });
    $('#soaopfabricrcvitemFrm [id="uom_id"]').combobox();
    $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
        }
    });
});
</script>
    