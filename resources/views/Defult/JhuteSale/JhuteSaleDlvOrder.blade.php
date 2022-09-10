<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="jhutesaledlvordertabs">
    <div title="DO Referance" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="jhutesaledlvorderTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'do_no'" width="100">DO No</th>
                            <th data-options="field:'company_name'" width="100">Company</th>
                            <th data-options="field:'location_name'" width="100">Location</th>
                            <th data-options="field:'do_date'" width="100">Do date</th>
                            <th data-options="field:'currency_code'" width="100">Currency</th>
                            <th data-options="field:'etd_date'" width="100">ETD Date</th>
                            <th data-options="field:'buyer_name'" width="100">Costomer</th>
                            <th data-options="field:'advised_by'" width="100">Advised By</th>
                            <th data-options="field:'price_verified_by'" width="100">Price Varified By</th>
                            <th data-options="field:'payment_before_dlv'" width="100">Payment Before Delv</th>
                            <th data-options="field:'remarks'" width="120">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Jhute DO Referance',footer:'#ft2'"
                style="width: 400px; padding:2px">
                <div id="container">
                    <div id="body">
                        <form id="jhutesaledlvorderFrm">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">DO No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="do_no" id="do_no" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Company </div>
                                    <div class="col-sm-7">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Location </div>
                                    <div class="col-sm-7">
                                        {!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Do Date </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="do_date" id="do_date" class="datepicker" placeholder="yyyy-mm-dd"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Currency </div>
                                    <div class="col-sm-7">
                                        {!! Form::select('currency_id', $currency,'',array('id'=>'currency_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">ETD</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="etd_date" id="etd_date" class="datepicker" placeholder="yy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Customer</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Advised By</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('advised_by_id', $user, '', array('id' => 'advised_by_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Price Varified By</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('price_verified_by_id', $user,'',array('id' =>'price_verified_by_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Payment Before Delv</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('payment_before_dlv_id', $yesno,'',array('id'=>'payment_before_dlv_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Remarks </div>
                                    <div class="col-sm-7">
                                        <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Status</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('status_id', $status,'1',array('id'=>'status_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Ready to Approve</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('ready_to_approve_id', $yesno,'0',array('id'=>'ready_to_approve_id')) !!}
                                    </div>
                                </div>
                            </code>
                        </form>
                    </div>
                </div>
                <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
                    iconCls="icon-save" plain="true" id="save" onClick="MsJhuteSaleDlvOrder.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
                    iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('jhutesaledlvorderFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
                    iconCls="icon-remove" plain="true" id="delete" onClick="MsJhuteSaleDlvOrder.remove()">Delete</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
                    iconCls="icon-remove" plain="true" id="delete" onClick="MsJhuteSaleDlvOrder.pdf()">DO</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Item Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="jhutesaledlvorderitemTbl" style="width:100%">
                    <thead>
                        <tr>
                        <th data-options="field:'id'" width="80">ID</th>
                        <th data-options="field:'acc_chart_ctrl_head_id'" width="100">Item Description</th>
                        <th data-options="field:'uom_id'" width="60">UOM</th>
                        <th data-options="field:'qty',align:'right'" width="100">Req. Qty</th>
                        <th data-options="field:'rate',align:'right'" width="80">Rate</th>
                        <th data-options="field:'amount',align:'right'" width="120">Amount</th>
                        <th data-options="field:'remarks'" width="120">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Items',footer:'#ft3'" style="width: 350px; padding:2px">
                <form id="jhutesaledlvorderitemFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="jhute_sale_dlv_order_id" id="jhute_sale_dlv_order_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Item description</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('acc_chart_ctrl_head_id', $ctrlHead,'',array('id'=>'acc_chart_ctrl_head_id','style'=>'width:
                                        100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">UOM </div>
                                    <div class="col-sm-7">
                                        {!! Form::select('uom_id', $uom,'',array('id'=>'uom_id','style'=>'width:100%;border:2px;')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Req. Qty </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="qty" id="qty" onchange="MsJhuteSaleDlvOrderItem.calculateAmount()" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Rate </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="rate" id="rate" onchange="MsJhuteSaleDlvOrderItem.calculateAmount()" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Amount </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="amount" id="amount" class="number integer" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Remarks </div>
                                    <div class="col-sm-7">
                                        <textarea name="remarks" id="remarks" cols="30" rows="5"></textarea>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
                        iconCls="icon-save" plain="true" id="save" onClick="MsJhuteSaleDlvOrderItem.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
                        iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('jhutesaledlvorderitemFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
                        iconCls="icon-remove" plain="true" id="delete" onClick="MsJhuteSaleDlvOrderItem.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Payment Received" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'New Payment',iconCls:'icon-more',footer:'#jhutesaledlvorderpaymentft'"
            style="width:450px; padding:2px">
                <form id="jhutesaledlvorderpaymentFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="jhute_sale_dlv_order_id" id="jhute_sale_dlv_order_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Payment Date </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="payment_date" id="payment_date" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Amount </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="amount" id="amount" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Received By</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('receive_by_id', $user,'',array('id'=>'receive_by_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="jhutesaledlvorderpaymentft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
                    iconCls="icon-save" plain="true" id="save" onClick="MsJhuteSaleDlvOrderPayment.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
                    iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('jhutesaledlvpaymentFrm')">Reset</a>
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
                    iconCls="icon-remove" plain="true" id="delete" onClick="MsJhuteSaleDlvOrderPayment.remove()">Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="jhutesaledlvorderpaymentTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'payment_date'" width="100">Payment Date</th>
                            <th data-options="field:'amount'" width="100">Amount</th>
                            <th data-options="field:'receive_by_id'" width="100">Received By</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/JhuteSale/MsAllJhuteSaleDlvOrderController.js"></script>

<script>
    (function(){
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
        });

        $('.integer').keyup(function () {
                if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
                this.value = this.value.replace(/[^0-9\.]/g, '');
            }
        });

        $('#jhutesaledlvorderFrm [id="buyer_id"]').combobox();
        $('#jhutesaledlvorderFrm [id="advised_by_id"]').combobox();
        $('#jhutesaledlvorderFrm [id="price_verified_by_id"]').combobox();
        $('#jhutesaledlvorderitemFrm [id="uom_id"]').combobox();
        $('#jhutesaledlvorderitemFrm [id="acc_chart_ctrl_head_id"]').combobox();
        $('#jhutesaledlvorderpaymentFrm [id="receive_by_id"]').combobox();
    })(jQuery);
</script>