<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="prodfinishqcbilltabs">
    <div title="Bill Reference" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="prodfinishqcbillTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'dlv_no'" width="100">Bill. No</th>
                            <th data-options="field:'dlv_date'" width="80">Bill Date</th>
                            <th data-options="field:'company_name'" width="80">Company</th>
                            <th data-options="field:'location_name'" width="70">Location</th>
                            <th data-options="field:'store_name'" width="80">To Store</th>
                            <th data-options="field:'buyer_name'" width="70">Customer</th>
                            <th data-options="field:'remarks'" width="200">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add New Entry',footer:'#ft2'" style="width: 400px; padding:2px">
                <form id="prodfinishqcbillFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle">
                                    <div class="col-sm-5">Dlv. No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="dlv_no" id="dlv_no" readonly />
                                        <input type="hidden" name="id" id="id" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Dlv Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="dlv_date" id="dlv_date" class="datepicker" placeholder="yyyy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Company</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('company_id',$company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Location </div>
                                    <div class="col-sm-7">
                                        {!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Store</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('store_id',$store,'',array('id'=>'store_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Customer</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('buyer_id',$buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Remarks</div>
                                    <div class="col-sm-7">
                                        <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdFinishQcBill.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdFinishQcBill.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdFinishQcBill.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdFinishQcBill.pdf()">Bill</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Item Detals" style="padding:2px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Installment Details',footer:'#prodfinishqcbillitemFrmFt'" style="width: 400px; padding:2px">
                <form id="prodfinishqcbillitemFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="prod_finish_dlv_id" id="prod_finish_dlv_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Fabrics</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="fabrication" id="fabrication" value=""  ondblclick="MsProdFinishQcBillItem.prodfinishqcbillitemWindowOpen()"  placeholder="Double Click to Select" />
                                        <input type="hidden" name="so_dyeing_fabric_rcv_item_id" id="so_dyeing_fabric_rcv_item_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Process</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="process_name" id="process_name" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Bill QTY </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="qty" id="qty" value="" class="number integer" onchange="MsProdFinishQcBillItem.calculateAmount()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Rate/Unit</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="rate" id="rate" value="" class="number integer"  onchange="MsProdFinishQcBillItem.calculateAmount()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Amount</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="amount" id="amount" value="" class="number integer"  readonly/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Batch No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="batch_no" id="batch_no" value=""  ondblclick="MsProdFinishQcBillItem.prodbatchfinishqcWindowOpen()"  placeholder="Double Click to Select" readonly/>
                                        <input type="hidden" name="prod_batch_finish_qc_id" id="prod_batch_finish_qc_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Fabric Color</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="fabric_color_name" id="fabric_color_name" value="" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Fin Dia</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="dia" id="dia" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Fin GSM</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="gsm_weight" id="gsm_weight" value="" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">No of Roll</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="no_of_roll" id="no_of_roll" value="" class="number integer"  />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Remarks</div>
                                    <div class="col-sm-7">
                                        <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="prodfinishqcbillitemFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdFinishQcBillItem.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdFinishQcBillItem.resetForm('prodfinishqcbillitemFrm')" >Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdFinishQcBillItem.remove()" >Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="prodfinishqcbillitemTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'fabrication'" width="100">Fabrics</th>
                            <th data-options="field:'fabric_color_name'" width="100">Fabric Color</th>
                            <th data-options="field:'gsm_weight'" width="100">GSM</th>
                            <th data-options="field:'dia'" width="100">Dia</th>
                            <th data-options="field:'batch_no'" width="100">Batch No</th>
                            <th data-options="field:'process_name'" width="100">Process Name</th>
                            <th data-options="field:'amount'" width="80" >Amount</th>
                            <th data-options="field:'qty'" width="80" >Qty</th>
                            <th data-options="field:'rate'" width="80" >Rate</th>
                            <th data-options="field:'remarks'" width="100">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- Item Account Window --}}
<div id="prodfinishqcbillitemWindow" class="easyui-window" title="Item Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
       <div data-options="region:'center',border:true,footer:'#prodfinishqcbillitemsearchTblft'" style="padding:2px">
            <table id="prodfinishqcbillitemsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="30">ID</th>
                        <th data-options="field:'sales_order_no'" width="100">Dyeing Sales<br/> Order No</th>
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
            <div id="prodfinishqcbillitemsearchTblft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#prodfinishqcbillitemWindow').window('close')" style="width:80px">Close</a>
            </div>
        </div>

        <div data-options="region:'west',border:true,footer:'#prodfinishqcbillitemsearchFrmft'" style="padding:2px; width:300px">
            <form id="prodfinishqcbillitemsearchFrm">
                <div id="container">
                    <div id="body">
                        <code>
                            <div class="row middle">
                                <div class="col-sm-4">Sales Order</div>
                                <div class="col-sm-8"> 
                                    <input type="text" name="sale_order_no" id="sale_order_no" /> 
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Receive Date</div>
                                <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="from_date" id="from_date" value="" class="datepicker" />
                                </div>
                                <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="to_date" id="to_date" value="" class="datepicker"/>
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Customer</div>
                                <div class="col-sm-8">
                                    {!! Form::select('buyer_id',$buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                                </div>
                            </div>
                        </code>
                    </div>
                </div>
                <div id="prodfinishqcbillitemsearchFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" \ onClick="MsProdFinishQcBillItem.searchItem()" >Search</a>
                </div>
            </form>
        </div>


    </div>
</div>
{{-- Qc Batch --}}
<div id="openprodbatchfinishqcbatchWindow" class="easyui-window" title="Machine Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
       <div data-options="region:'center',border:true,footer:'#prodfinishqcbatchsearchTblFt'" style="padding:2px">
            <table id="qcbillprodbatchsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'company_code'" width="80">Company</th>
                        <th data-options="field:'batch_no'" width="80">Batch No</th>
                        <th data-options="field:'batch_date'" width="100">Batch Date</th>
                        <th data-options="field:'posting_date'" width="100">Qc Posting Date</th>
                        <th data-options="field:'batchfor'" width="100">Batch For</th>
                        <th data-options="field:'machine_no'" width="80">Machine No</th>
                        <th data-options="field:'batch_color_name'" width="80">Batch Color</th>
                        <th data-options="field:'color_name'" width="80">Roll Color</th>
                        <th data-options="field:'color_range_name'" width="80">Color Range</th>
                        <th data-options="field:'fabric_wgt'" width="80">Fabric Wgt</th>
                        <th data-options="field:'batch_wgt'" width="80">Batch Wgt</th>
                    </tr>
                </thead>
            </table>
            <div id="prodfinishqcbatchsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#prodbatchfinishqcbatchWindow').window('close')" style="width:80px">Close</a>
                </div>
        </div>
        <div data-options="region:'west',border:true,footer:'#prodbatchfinishqcbatchsearchFrmFt'" style="padding:2px; width:350px">
            <form id="qcbillprodbatchsearchFrm">
                <div id="container">
                    <div id="body">
                        <code>
                            <div class="row ">
                            <div class="col-sm-4 req-text">Company</div>
                            <div class="col-sm-8">
                            {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                            </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4">Batch No</div>
                            <div class="col-sm-8">
                            <input type="text" name="batch_no" id="batch_no" />
                            </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4">Batch For</div>
                            <div class="col-sm-8">
                            {!! Form::select('batch_for', $batchfor,'',array('id'=>'batch_for')) !!}
                            </div>
                            </div>
                        </code>
                    </div>
                </div>
                <div id="prodbatchfinishqcbatchsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true"  onClick="MsProdFinishQcBillItem.getBatch()" >Search</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Production/Dyeing/MsAllProdFinishQcBillController.js"></script>
<script>
    (function(){
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

        
        $('#prodfinishqcbillFrm [id="buyer_id"]').combobox();
        $('#prodfinishqcbillitemsearchFrm [id="buyer_id"]').combobox();

        $('.integer').keyup(function () {
            if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
                this.value = this.value.replace(/[^0-9\.]/g, '');
            }
        });

    })(jQuery);
</script>