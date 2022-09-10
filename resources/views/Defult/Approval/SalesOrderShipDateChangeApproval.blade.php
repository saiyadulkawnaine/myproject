<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="salesordershipdatechangeapprovaltabs">
    <div title="Waiting For Approval" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'Shipdate Change Approval List'" style="padding:2px">
                <table id="salesordershipdatechangeapprovalTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'app'" width="60" formatter='MsSalesOrderShipDateChangeApproval.approveButton'>Approval</th>
                            <th data-options="field:'saleorderprogress'" width="100" formatter='MsSalesOrderShipDateChangeApproval.orderProgressButton'>Order Progress<br/>Details</th>
                            <th data-options="field:'id'" width="60">ID</th>
                            <th data-options="field:'job_no'" width="60">Job No</th>
                            <th data-options="field:'buyer_code'" width="100">Buyer</th>
                            <th data-options="field:'style_ref'" width="120">Style</th>
                            <th data-options="field:'sale_order_no'" width="120">Sales Order No</th>
                            <th data-options="field:'ship_date'" width="80">New <br/>Ship Date</th>
                            <th data-options="field:'old_ship_date'" width="80">Previous <br/>Ship Date</th>
                            <th data-options="field:'org_ship_date'" width="80">Original <br/>Ship Date</th>
                            <th data-options="field:'remarks',halign:'center'" width="200">Remarks</th>
                            <th data-options="field:'qty'" width="70" align="right">Qty</th>
                            <th data-options="field:'rate'" width="60" align="right">Rate</th>
                            <th data-options="field:'amount'" width="80" align="right">Amount</th>
                            <th data-options="field:'lead_time'" width="80" align="right">Lead Time</th>
                            <th data-options="field:'place_date'" width="80">Place Date</th>
                            <th data-options="field:'receive_date'" width="80">Receive Date</th>
                            <th data-options="field:'produced_company'" width="120">Produced Company</th>
                            <th data-options="field:'sale_order_id'" width="100">Sales Order ID</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Search',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#salesordershipdatechangeapprovalFrmFt'" style="width:300px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="salesordershipdatechangeapprovalFrm">
                                <div class="row middle">
                                    <div class="col-sm-4">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Ship Date</div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="salesordershipdatechangeapprovalFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSalesOrderShipDateChangeApproval.get()">Search</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('salesordershipdatechangeapprovalFrm')">Reset</a>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/approval/MsSalesOrderShipDateChangeApprovalController.js"></script>
<script>
$(".datepicker" ).datepicker({
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
    changeYear: true
});

</script>  