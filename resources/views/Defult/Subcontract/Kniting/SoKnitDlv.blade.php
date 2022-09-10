<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="soknitdlvtabs">
    <div title="Start Up" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="soknitdlvTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'buyer_id'" width="100">Customer</th>
                            <th data-options="field:'issue_no'" width="100">GIN</th>
                            <th data-options="field:'issue_date'" width="100">Issue Date</th>
                            <th data-options="field:'remarks'" width="100">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Work Order Received',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#soknitFrmft'" style="width:450px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="soknitdlvFrm">
                                <div class="row">
                                    <input type="hidden" name="id" id="id" />
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-5 req-text">GIN</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="issue_no" id="issue_no" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Company</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Customer</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Currency</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('currency_id', $currency,'',array('id'=>'currency_id')) !!}
                                    </div>
                                </div>
                               
                                                                  
                               
                                <div class="row middle">
                                    <div class="col-sm-5"> Dlv. Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="issue_date" id="issue_date" class="datepicker" />
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
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoKnitDlv.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('soknitFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoKnitDlv.remove()">Delete</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoKnitDlv.showBill()">Bill</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoKnitDlv.showDc()">DC</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Item Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Information',iconCls:'icon-more',footer:'#soknititemFrmft'" style="width:450px; padding:2px">
                <form id="soknitdlvitemFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <input type="hidden" name="so_knit_dlv_id" id="so_knit_dlv_id" value="" />
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Qty  </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" value="" class="number integer" onchange="MsSoKnitDlvItem.calculate_form()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Rate/Unit </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rate" id="rate" value="" class="number integer" onchange="MsSoKnitDlvItem.calculate_form()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Amount </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount" id="amount" value="" class="number integer" readonly/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">No Of Roll </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="no_of_roll" id="no_of_roll" value="" class="number integer"/>
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
                    <div id="soknititemFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoKnitDlvItem.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('subinborderproductFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoKnitDlvItem.remove()">Delete</a>
                    </div>
                </form>  
            </div>
            <div data-options="region:'center',border:true,title:'Lists',footer:'#soknitdlvitemTblFt'" style="padding:2px">
                <table id="soknitdlvitemTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'buyer_name'" width="100">Buyer</th>
                            <th data-options="field:'gmt_style_ref'" width="100">Style Ref</th>
                            <th data-options="field:'gmt_sale_order_no'" width="100">Sales Order No</th>
                            <th data-options="field:'gmtspart'" width="100">GMT Part</th>
                            <th data-options="field:'constructions_name'" width="100">Constructions</th>
                            <th data-options="field:'fabrication'" width="100">Fabrication</th>
                            <th data-options="field:'fabriclooks'" width="100">Fabric Looks</th>
                            <th data-options="field:'fabricshape'" width="100">Fabric Shape</th>
                            <th data-options="field:'gsm_weight'" width="80" align="right">GSM/Weight</th>
                            <th data-options="field:'dia'" width="80" align="right">Dia</th>
                            <th data-options="field:'measurment'" width="100">Measurment</th>
                            <th data-options="field:'fabric_color'" width="100">Fabric Color</th>
                            <th data-options="field:'uom_name'" width="80">Uom</th>
                            <th data-options="field:'qty'" width="100" align="right">Qty</th>
                            <th data-options="field:'rate'" width="80" align="right">Rate</th>
                            <th data-options="field:'amount'" width="100" align="right">Amount</th>
                            <th data-options="field:'no_of_roll'" width="100" align="right">No Of Roll</th>
                            <th data-options="field:'remarks'" width="100" align="right">Remarks</th>
                        </tr>
                    </thead>
                </table>
                <div id="soknitdlvitemTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">

                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoKnitDlvItem.itemWindow()">Import</a>
                    </div>
            </div>
        </div>
    </div>
    <div title="Yarn" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="soknitdlvitemyarnTbl" style="width:100%">
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
                            <th data-options="field:'qty'" width="100" align="right">Qty</th>
                            <th data-options="field:'uom_name'" width="80">Uom</th>
                            <th data-options="field:'remarks'" width="80">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Work Order Received',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#soknitdlvitemyarnFrmFt'" style="width:450px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="soknitdlvitemyarnFrm">
                                <div class="row">
                                    <input type="hidden" name="id" id="id" />
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-5 req-text">Yarn description</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="item_desc" id="item_desc" ondblclick="MsSoKnitDlvItemYarn.getitem()" readonly placeholder="Double Click" />
                                        <input type="hidden" name="so_knit_yarn_rcv_item_id" id="so_knit_yarn_rcv_item_id" />

                                    </div>
                                </div>
                                
                               
                                                                  
                               
                                <div class="row middle">
                                    <div class="col-sm-5">Yarn Count</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="yarn_count" id="yarn_count"  disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Lot</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="lot" id="lot" disabled />
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-5">Supplier</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="supplier" id="supplier" disabled  />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Qty  </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="qty" id="qty" value="" class="number integer"/>
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
                <div id="soknitdlvitemyarnFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoKnitDlvItemYarn.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('soknitFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoKnitDlvItemYarn.remove()">Delete</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="soknitdlvitemWindow" class="easyui-window" title="Subcontract Order Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search',footer:'#soknitdlvitemWindowFt'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="soknitdlvitemsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Order No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="sales_order_no" id="sales_order_no">
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Style</div>
                                <div class="col-sm-8">
                                    <input type="text" name="style_ref" id="style_ref">
                                </div>
                            </div>
                           
                        </form>
                    </code>
                </div>
                 <div id="soknitdlvitemWindowFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="border-radius:1px" onClick="MsSoKnitDlvItem.getitem()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#soknitdlvitemsearchTblFt'" style="padding:10px;">
            <div id="soknitdlvitemWindowscs">
            </div>
            
            <div id="soknitdlvitemsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="MsSoKnitDlvItem.submitBatch()" style="border-radius:1px">Save</a>
            </div>
        </div>
    </div>
</div>

<div id="soknitdlvitemyarnWindow" class="easyui-window" title="Yarn Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center'" style="padding:10px;">
            <table id="soknitdlvitemyarnsearchTbl" style="width:100%">
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




<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Kniting/MsSoKnitDlvController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Kniting/MsSoKnitDlvItemController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Kniting/MsSoKnitDlvItemYarnController.js"></script>
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
    $('#soknitdlvitemFrm [id="uom_id"]').combobox();
    $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
        }
    });
});
</script>
    