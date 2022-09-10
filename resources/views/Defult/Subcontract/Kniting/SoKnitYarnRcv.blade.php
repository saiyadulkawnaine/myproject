<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="soknityarnrcvtabs">
    <div title="Start Up" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="soknityarnrcvTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'buyer_id'" width="100">Customer</th>
                            <th data-options="field:'sales_order_no'" width="100">Sales Order NO</th>
                            <th data-options="field:'receive_date'" width="100">Receive Date</th>
                            <th data-options="field:'remarks'" width="100">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Work Order Received',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#soknitFrmft'" style="width:450px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="soknityarnrcvFrm">
                                <div class="row">
                                    <input type="hidden" name="id" id="id" />
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-5 req-text">Sales Order No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="sales_order_no" id="sales_order_no" ondblclick="MsSoKnitYarnRcv.soWindow()" placeholder="Double Click"/>
                                        <input type="hidden" name="so_knit_id" id="so_knit_id" />
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
                <div id="soknitFrmft" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoKnitYarnRcv.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('soknitFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoKnitYarnRcv.remove()">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Item Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Information',iconCls:'icon-more',footer:'#soknititemFrmft'" style="width:450px; padding:2px">
                <form id="soknityarnrcvitemFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <input type="hidden" name="so_knit_yarn_rcv_id" id="so_knit_yarn_rcv_id" value="" />
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Yarn ID </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="item_account_id" id="item_account_id" value=""  ondblclick="MsSoKnitYarnRcvItem.itemWindow()"  placeholder="Double Click to Select" />
                                    </div>
                                </div>
                                
                                 
                                
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Count </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="count" id="count" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Item Description</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="item_description" id="item_description" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Lot</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="lot" id="lot" />
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4 req-text">Supplier</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="supplier_name" id="supplier_name" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Color</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="yarn_color" id="yarn_color" />
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">UOM </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('uom_id', $uom,'',array('id'=>'uom_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Qty  </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" value="" class="number integer" onchange="MsSoKnitYarnRcvItem.calculate()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Recovery Rate/Unit </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rate" id="rate" value="" class="number integer" onchange="MsSoKnitYarnRcvItem.calculate()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Amount </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount" id="amount" value="" class="number integer" readonly/>
                                    </div>
                                </div>
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
                                  
                            </code>
                        </div>
                    </div>
                    <div id="soknititemFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoKnitYarnRcvItem.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('subinborderproductFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoKnitYarnRcvItem.remove()">Delete</a>
                    </div>
                </form>  
            </div>
            <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                <table id="soknityarnrcvitemTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'itemclass_name'" width="100">Item Class</th>
                            <th data-options="field:'count'" width="100">Count</th>
                            <th data-options="field:'yarn_type'" width="100">Type</th>
                            <th data-options="field:'composition'" width="100">Composition</th>
                            <th data-options="field:'lot'" width="100">Lot</th>
                            <th data-options="field:'supplier_name'" width="100">Supplier</th>
                            <th data-options="field:'color_name'" width="100">Yarn Color</th>
                            <th data-options="field:'uom_name'" width="80">Uom</th>
                            <th data-options="field:'qty'" width="100" align="right">Qty</th>
                            <th data-options="field:'rate'" width="80" align="right">Rate</th>
                            <th data-options="field:'amount'" width="100" align="right">Amount</th>
                            <th data-options="field:'process_loss_per'" width="100" align="right">Process Loss %</th>
                            <th data-options="field:'real_rate'" width="80">Real Rate</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
    


<div id="soknityarnrcvsoWindow" class="easyui-window" title="Subcontract Order Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search',footer:'#soknityarnrcvsoWindowFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="soknityarnrcvsosearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Sales Order No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="so_no" id="so_no">
                                </div>
                            </div>
                           
                        </form>
                    </code>
                </div>
                 <div id="soknityarnrcvsoWindowFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="border-radius:1px" onClick="MsSoKnitYarnRcv.getsubconorder()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#soknityarnrcvsosearchTblFt'" style="padding:10px;">
            <table id="soknityarnrcvsosearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="30">ID</th>
                        <th data-options="field:'sales_order_no'" width="100">Sales Order No</th>
                        <th data-options="field:'company_name'" width="100">Company</th>
                        <th data-options="field:'buyer_name'" width="100">Buyer</th>
                        <th data-options="field:'receive_date'" width="100">Receive Date</th>

                    </tr>
                </thead>
            </table>
             <div id="soknityarnrcvsosearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#soknityarnrcvsoWindow').window('close')" style="border-radius:1px">Close</a>
        </div>
        </div>
       
    </div>
</div>

<div id="soknityarnrcvitemWindow" class="easyui-window" title="Subcontract Order Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search',footer:'#soknityarnrcvitemWindowFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="soknityarnrcvitemsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Count</div>
                                <div class="col-sm-8">
                                    <input type="text" name="count_name" id="count_name">
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Type</div>
                                <div class="col-sm-8">
                                    <input type="text" name="type_name" id="type_name">
                                </div>
                            </div>
                           
                        </form>
                    </code>
                </div>
                 <div id="soknityarnrcvitemWindowFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="border-radius:1px" onClick="MsSoKnitYarnRcvItem.getitem()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#soknityarnrcvitemsearchTblFt'" style="padding:10px;">
            <table id="soknityarnrcvitemsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="30">ID</th>
                        <th data-options="field:'itemclass_name'" width="100">Item Class</th>
                        <th data-options="field:'count'" width="100">Count</th>
                        <th data-options="field:'yarn_type'" width="100">Type</th>
                        <th data-options="field:'composition_name'" width="200">Composition</th>

                    </tr>
                </thead>
            </table>
             <div id="soknityarnrcvitemsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#soknityarnrcvitemWindow').window('close')" style="border-radius:1px">Close</a>
        </div>
        </div>
       
    </div>
</div>




<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Kniting/MsSoKnitYarnRcvController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Kniting/MsSoKnitYarnRcvItemController.js"></script>
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
    $('#soknityarnrcvitemFrm [id="uom_id"]').combobox();
    $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
        }
    });
});
</script>
    