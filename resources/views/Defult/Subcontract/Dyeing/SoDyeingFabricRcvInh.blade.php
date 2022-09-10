<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="sodyeingfabricrcvinhtabs">
    <div title="Start Up" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="sodyeingfabricrcvinhTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'receive_no'" width="30">Receive No</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'buyer_id'" width="100">Customer</th>
                            <th data-options="field:'sales_order_no'" width="100">Sales Order NO</th>
                            <th data-options="field:'receive_date'" width="100">Rceive Date</th>
                            <th data-options="field:'remarks'" width="200">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Work Order Received',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#sodyeingfabricrcvinhFrmft'" style="width:450px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="sodyeingfabricrcvinhFrm">
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
                                    <div class="col-sm-5 req-text">Sales Order No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="sales_order_no" id="sales_order_no" ondblclick="MsSoDyeingFabricRcvInh.soWindow()" placeholder="Double Click"/>
                                        <input type="hidden" name="so_dyeing_id" id="so_dyeing_id" />
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
                                    <div class="col-sm-5"> Rceive Date</div>
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
                <div id="sodyeingfabricrcvinhFrmft" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingFabricRcvInh.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('sodyeingfabricrcvinhFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingFabricRcvInh.remove()">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Item Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            
            <div data-options="region:'center',border:true,title:'Lists',footer:'#sodyeingfabricrcvinhitemTblFt'" style="padding:2px">
                <form id="sodyeingfabricrcvinhitemFrm">
                    <input type="hidden" name="so_dyeing_fabric_rcv_id" id="so_dyeing_fabric_rcv_id" value="" />
                    <input type="hidden" name="id" id="id" value="" />
                </form>
                <table id="sodyeingfabricrcvinhitemTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'fabrication'" width="250">Fabrication</th>
                            <th data-options="field:'fabriclooks'" width="100">Fabric Looks</th>
                            <th data-options="field:'fabricshape'" width="100">Fabric Shape</th>
                            <th data-options="field:'gsm_weight'" width="70" align="center">GSM/Weight</th>
                            <th data-options="field:'dia'" width="70" align="center">Dia</th>
                            <th data-options="field:'dyeing_color'" width="80" align="center">Dyeing Color</th>
                            <th data-options="field:'colorrange_id'" width="70">Color Range</th>
                            <th data-options="field:'dyetype'" width="80">Dyeing Type</th>
                            <th data-options="field:'qty'" width="70" align="right">Qty</th>
                            <th data-options="field:'rate'" width="70" align="right">Rate</th>
                            <th data-options="field:'amount'" width="100" align="right">Amount</th>
                            <th data-options="field:'style_ref'" width="100">Style Ref</th>
                            <th data-options="field:'buyer_name'" width="100">Buyer</th>
                            <th data-options="field:'sale_order_no'" width="100">Sales Order No</th>
                            <th data-options="field:'remarks'" width="80">Remarks</th>
                        </tr>
                    </thead>
                </table>
                    <div id="sodyeingfabricrcvinhitemTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">

                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingFabricRcvInhItem.import()">Import</a>
                    </div>
            </div>
        </div>
    </div>
    <div title="Roll Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            
            <div data-options="region:'center',border:true,title:'Lists',footer:'#sodyeingfabricrcvinhrolTblFt'" style="padding:2px">
                <table id="sodyeingfabricrcvinhrolTbl" style="width:100%">
                    <thead>
                        <tr>
                          <th data-options="field:'id'" width="70">ID</th>
                          <th data-options="field:'issue_no'" width="70">Issue No</th>
                          <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
                          <th data-options="field:'custom_no'" width="70">Custom No</th>
                          <th data-options="field:'rcv_qty'" width="80">Receive Qty</th>
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
                          
                          <th data-options="field:'prod_no'" width="70">Prod. Ref</th>
                          <th data-options="field:'sale_order_no'" width="100">Order No</th>
                          <th data-options="field:'style_ref'" width="100">Style Ref</th>
                          <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
                          <th data-options="field:'gmt_sample'" width="100">Gmt Sample</th>
                          <th data-options="field:'supplier_name'" width="100">Produced By</th>
                          <th data-options="field:'customer_name'" width="100">Produced For</th>
                        </tr>
                    </thead>
                </table>
                    <div id="sodyeingfabricrcvinhrolTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">

                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingFabricRcvInhRol.import()">Import</a>
                    </div>
            </div>
        </div>
    </div>
</div>

<div id="sodyeingfabricrcvsoinhWindow" class="easyui-window" title="Subcontract Order Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search',footer:'#sodyeingfabricrcvsoinhWindowFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="sodyeingfabricrcvsosearchinhFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Sales Order No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="so_no" id="so_no">
                                </div>
                            </div>
                           
                        </form>
                    </code>
                </div>
                 <div id="sodyeingfabricrcvsoinhWindowFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="border-radius:1px" onClick="MsSoDyeingFabricRcvInh.getsubconorder()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#sodyeingfabricrcvsosearchinhTblFt'" style="padding:10px;">
            <table id="sodyeingfabricrcvsosearchinhTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="30">ID</th>
                        <th data-options="field:'sales_order_no'" width="100">Sales Order No</th>
                        <th data-options="field:'company_name'" width="100">Company</th>
                        <th data-options="field:'buyer_name'" width="100">Buyer</th>
						<th data-options="field:'remarks'" width="200">Remarks</th>
                    </tr>
                </thead>
            </table>
             <div id="sodyeingfabricrcvsosearchinhTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#sodyeingfabricrcvsoinhWindow').window('close')" style="border-radius:1px">Close</a>
        </div>
        </div>
       
    </div>
</div>

<div id="sodyeingfabricrcviteminhWindow" class="easyui-window" title="Fabric Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center',footer:'#sodyeingfabricrcviteminhWindowFt'" style="padding:10px;">
        <table id="sodyeingfabricrcvinhitemsearchTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'so_dyeing_ref_id'" width="100">ID</th>
                            <th data-options="field:'fabrication'" width="300">Fabrication</th>
                            <th data-options="field:'fabriclooks'" width="100">Fabric Looks</th>
                            <th data-options="field:'fabricshape'" width="100">Fabric Shape</th>
                            <th data-options="field:'gsm_weight'" width="70" align="center">GSM/Weight</th>
                            <th data-options="field:'dia'" width="70" align="center">Dia</th>
                            <th data-options="field:'dyeing_color'" width="80" align="left">Dyeing Color</th>
                            <th data-options="field:'colorrange_id'" width="70">Color Range</th>
                            <th data-options="field:'dyetype'" width="70">Dyeing Type</th>
                            <th data-options="field:'remarks'" width="80">Remarks</th>
                        </tr>
                    </thead>
                </table>
        <div id="sodyeingfabricrcviteminhWindowFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingFabricRcvInhItem.submitBatch()">Save</a>
        </div>
      </div>  
    </div>
</div>

<div id="sodyeingfabricrcvrollinhWindow" class="easyui-window" title="Roll Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center',footer:'#sodyeingfabricrcvrollinhWindowFT'" style="padding:10px;">
        <table id="sodyeingfabricrcvrollsearchTbl" style="width:100%">
                    <thead>
                         <tr>
                          <th data-options="field:'id'" width="70">ID</th>
                          <th data-options="field:'issue_no'" width="100">Issue No</th>
                          <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
                          <th data-options="field:'custom_no'" width="70">Custom No</th>
                          <th data-options="field:'isu_qty'" width="80">Issue Qty</th>
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
                          <th data-options="field:'fabric_color'" width="80">Fabric Color</th>
                          <th data-options="field:'prod_no'" width="70">Prod. Ref</th>
                          <th data-options="field:'sale_order_no'" width="100">Order No</th>
                          <th data-options="field:'style_ref'" width="100">Style Ref</th>
                          <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
                          <th data-options="field:'gmt_sample'" width="100">Gmt Sample</th>
                          <th data-options="field:'supplier_name'" width="100">Produced By</th>
                          <th data-options="field:'customer_name'" width="100">Produced For</th>
                          
                        </tr>
                    </thead>
                </table>
        <div id="sodyeingfabricrcvrollinhWindowFT" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
            Total:<span id="sodyeing_fabric_rcvinh_selected_roll_total" style="padding-right: 150px">0</span>
             <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingFabricRcvInhRol.selectAll()">Select All</a>
        <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingFabricRcvInhRol.unselectAll()">Unselect All</a>
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingFabricRcvInhRol.submitBatch()">Save</a>
        </div>
      </div>  
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Dyeing/MsSoDyeingFabricRcvInhController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Dyeing/MsSoDyeingFabricRcvInhItemController.js"></script> 
<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Dyeing/MsSoDyeingFabricRcvInhRolController.js"></script> 
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
    $('#sodyeingfabricrcvitemFrm [id="uom_id"]').combobox();
    $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
        }
    });
});
</script>