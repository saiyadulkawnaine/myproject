<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Purchase Requisition Report'" style="padding:2px">
         <table id="purchaserequisitionreportTbl" style="width:100%">
            <thead>
                <tr>
                    <th data-options="field:'id',styler:MsPurchaseRequisitionReport.jobdonemsg" width="100">ID</th>
                    <th data-options="field:'requisition_no'" width="100" formatter="MsPurchaseRequisitionReport.formatprintpdf">Requisition NO</th>
                    <th data-options="field:'approve_status',halign:'center'" width="100">Approval</th>
                    <th data-options="field:'company_id'" width="100">Company</th>
                    <th data-options="field:'req_date'" width="100">Requisition Date</th>
                    <th data-options="field:'pay_mode'" width="120">Pay Mode</th>
                    <th data-options="field:'qty'" width="80" align="right">Qty</th>
                    <th data-options="field:'rate'" width="80" align="right">Rate</th>
                    <th data-options="field:'currency_name'" width="70"  align="right">Currency</th>
                    <th data-options="field:'amount'" width="100" align="right">Amount</th>
                    <th data-options="field:'paid_amount'" width="100" align="right">Paid Amount</th>
                    <th data-options="field:'balance_amount'" width="100" align="right">Balance Amount</th>
                    <th data-options="field:'location_id'" width="100">Location</th>
                    <th data-options="field:'delivery_by'" width="100">Delivery By</th>
                    <th data-options="field:'demand_user_name'" width="130">Demand By</th>
                    <th data-options="field:'price_varify_user_name'" width="130">Price Varified By</th>
                    <th data-options="field:'job_done'" width="100">Job Done</th>
                    <th data-options="field:'job_completion_date'" width="80">Job Completion Date</th>
                    <th data-options="field:'remarks'" width="200">Remarks</th>
                </tr>
            </thead>
         </table>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="purchaserequisitionreportFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row middle">
                            <div class="col-sm-4">Date Range</div>
                            <div class="col-sm-4" style="padding-right:0px">
                                <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Company</div>
                            <div class="col-sm-8">
                                {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Requisition No</div>
                            <div class="col-sm-8">
                                <input type="text" name="requisition_no" id="requisition_no" ondblclick="MsPurchaseRequisitionReport.openInvPurReqWindow()" placeholder=" Double Click">
                            </div>
                        </div>
                    </code>
                </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPurchaseRequisitionReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPurchaseRequisitionReport.resetForm('purchaserequisitionreportFrm')" >Reset</a>
            </div>
        </form>
    </div>
 </div>

 <div id="invpurreqWindow" class="easyui-window" title="PO/PR Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="invpurreqsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">PR No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="requisition_no" id="requisition_no" value="">
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Company</div>
                                <div class="col-sm-8">
                                    {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsPurchaseRequisitionReport.searchRequisition()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="invpurreqsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'requisition_no'" width="100">Requisition NO</th>
                        <th data-options="field:'company'" width="100">Company</th>
                        <th data-options="field:'req_date'" width="100">Requisition Date</th>
                        <th data-options="field:'pay_mode'" width="120">Pay Mode</th>
                        <th data-options="field:'currency_id'" width="100">Currency</th>
                        <th data-options="field:'location_id'" width="100">Location</th>
                        <th data-options="field:'remarks'" width="120">Remarks</th> 
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#invpurreqWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
 
 <script type="text/javascript" src="<?php echo url('/');?>/js/report/ItemBank/MsPurchaseRequisitionReportController.js"></script>
 <script>
     $(".datepicker" ).datepicker({
         dateFormat: 'yy-mm-dd',
         changeMonth: true,
         changeYear: true
     });
 </script>