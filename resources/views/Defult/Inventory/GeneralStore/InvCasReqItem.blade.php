<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="cashtabs">
    <div title="Cash Requisition Entry" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="invcasreqTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'requisition_no'" width="100">Requisition NO</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'req_date'" width="100">Requisition Date</th>
                            <th data-options="field:'currency_id'" width="100">Currency</th>
                            <th data-options="field:'location_id'" width="100">Location</th>
                            <th data-options="field:'remarks'" width="120">Remarks</th>

                        </tr>
                    </thead>
                </table>
            </div>

            <div data-options="region:'west',border:true,title:'Requisition Reference',footer:'#ft2'" style="width: 380px; padding:2px">
                <form id="invcasreqFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Requisition No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="requisition_no" id="requisition_no" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Location </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Req. Date </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="req_date" id="req_date" class="datepicker" placeholder=" yy-mm-dd"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Currency </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('currency_id', $currency,'',array('id'=>'currency_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Cash Need By</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="disburse_by" id="disburse_by" class="datepicker" placeholder="yy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Demand By</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('demand_by_id', $user,'',array('id'=>'demand_by_id','style'=>'width:100%;border:2px;')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Price Varified By</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('price_verified_by_id', $user,'',array('id'=>'price_verified_by_id','style'=>'width:100%;border:2px;')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks </div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks" cols="30" rows="5"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Ready To Approve</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('ready_to_approve_id', $yesno,0,array('id'=>'ready_to_approve_id')) !!}
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvCasReq.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invcasreqFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvCasReq.remove()">Delete</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" id="pdf" onClick="MsInvCasReq.pdf()">CR</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Requisition Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="invcasreqitemTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'item_description'" width="100">Item Description</th>
                            <th data-options="field:'uom_code'" width="60">UOM</th>
                            <th data-options="field:'qty',align:'right'" width="100">Req. Qty</th>
                            <th data-options="field:'rate',align:'right'" width="80">Rate</th>
                            <th data-options="field:'amount',align:'right'" width="120">Amount</th>
                            <th data-options="field:'remarks'" width="120">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Requisition Details',footer:'#ft3'" style="width: 350px; padding:2px">
                <form id="invcasreqitemFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />                
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Item Description </div>
                                    <div class="col-sm-8">
                                        <input type="hidden" name="inv_pur_req_id" id="inv_pur_req_id" value="" />
                                        <textarea name="item_description" id="item_description"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">UOM </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('uom_id', $uom,'',array('id'=>'uom_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Req. Qty </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" onchange="MsInvCasReqItem.calculateAmount()" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Rate </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rate" id="rate" onchange="MsInvCasReqItem.calculateAmount()" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Amount </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount" id="amount" class="number integer" readonly/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks </div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks" cols="30" rows="5"></textarea>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvCasReqItem.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invcasreqitemFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvCasReqItem.remove()">Delete</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <!-----=============== Paid To =============----------->
    <div title="Paid" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'New Amount Paid',iconCls:'icon-more',footer:'#invpurreqpaidft'" style="width:450px; padding:2px">
                <form id="invcasreqpaidFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="inv_pur_req_id" id="inv_pur_req_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Date </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="paid_date" id="paid_date" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Amount </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount" id="amount" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Paid To </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('user_id', $user,'',array('id'=>'user_id','style'=>'width:100%;border-radius:2px;')) !!}
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="invpurreqpaidft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvCasReqPaid.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invcasreqpaidFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsInvCasReqPaid.remove()" >Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="invcasreqpaidTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'paid_date'" width="100">Item Description</th>
                            <th data-options="field:'amount',align:'right'" width="100">Amount</th>   
                            <th data-options="field:'user_name'" width="100">Paid To</th>   
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Inventory/GeneralStore/MsAllCashController.js"></script>
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

    $('#invcasreqFrm [id="demand_by_id"]').combobox();
    $('#invcasreqFrm [id="price_verified_by_id"]').combobox();
    $('#invcasreqpaidFrm [id="user_id"]').combobox();

    })(jQuery);
</script>