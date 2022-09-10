<div class="easyui-layout animated rollIn"  data-options="fit:true">
   <div data-options="region:'center',border:true,title:'Asset Breakdown List'" style="padding:2px">
    <table id="assetbreakdownreportTbl" style="width:100%">
        <thead>
            <tr>
                <th data-options="field:'custom_no',halign:'center'" width="70">1<br/>Asset No</th>
                <th data-options="field:'asset_name',halign:'center'" width="80">2<br/>Asset Name</th>
                <th data-options="field:'asset_type_name',halign:'center'" width="80">3<br/>Asset Type</th>
                <th data-options="field:'asset_group',halign:'center'" width="70">4<br/>Group</th>
                <th data-options="field:'breakdown_date',halign:'center'" width="80">5<br/>Breakdown<br/> Date</th>
                <th data-options="field:'breakdown_time',halign:'center'" width="60">6<br/>Breakdown<br/> Time</th>
                <th data-options="field:'reason_id',halign:'center'" width="80" formatter="MsAssetBreakdownReport.formatpurchaserequisition">7<br/>Reason</th>
                <th data-options="field:'remarks',halign:'center'" width="90">8<br/>Remarks</th>
                <th data-options="field:'decision_id',halign:'center'" width="90">9<br/>Decision</th>
                <th data-options="field:'maintenance_name',halign:'center'" width="80">10<br/>Maintenance<br/> By</th>
                <th data-options="field:'estimated_recovery_date',halign:'center'" width="80">11<br/>Estimated<br/> Recovery<br/> Date</th>
                <th data-options="field:'estimated_recovery_time',halign:'center'" width="60">12<br/>Estimated<br/> Recovery<br/> Time</th>
                <th data-options="field:'function_date',halign:'center'" width="80">13<br/>Actual<br/>Recovery<br/> Date</th>
                <th data-options="field:'function_time',halign:'center'" width="60">14<br/>Actual<br/>Recovery<br/> Time</th>
                <th data-options="field:'action_taken',halign:'center'" width="100">15<br/>Action<br/>Taken</th>
                <th data-options="field:'total_breakdown_hour',halign:'center'" width="60">16<br/>Breakdown<br/>Hour</th>
                <th data-options="field:'pending_breakdown_hour',halign:'center'" width="60">17<br/>Recovery<br/>Hour</th>
                <th data-options="field:'employee_name',halign:'center'" width="80">18<br/>Custody<br/> Of</th>
                <th data-options="field:'purchase_date',halign:'center'" width="80">19<br/>Purchase <br/>Date</th>
                <th data-options="field:'serial_no',halign:'center'" width="80">20<br/>Serial No</th>
                <th data-options="field:'brand',halign:'center'" width="80">21<br/>Brand</th>
                <th data-options="field:'origin',halign:'center'" width="80">22<br/>Origin</th>
                <th data-options="field:'prod_capacity',halign:'center'" width="80">23<br/>Prod <br/>Capacity</th>
            </tr>
        </thead>
    </table>
   </div>
   <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
   <form id="assetbreakdownreportFrm">
       <div id="container">
            <div id="body">
                <code>
                    <div class="row middle">
                        <div class="col-sm-4">Breakdown. Date</div>
                        <div class="col-sm-4" style="padding-right:0px">
                            <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                        </div>
                        <div class="col-sm-4" style="padding-left:0px">
                            <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">Company</div>
                        <div class="col-sm-8">
                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}  
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Reason</div>
                        <div class="col-sm-8">
                            {!! Form::select('reason_id', $reason,'',array('id'=>'reason_id','style'=>'width: 100%; border-radius:2px')) !!}
                        </div>
                    </div>
                </code>
            </div>
       </div>
       <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
           <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAssetBreakdownReport.get()">Show</a>
           <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAssetBreakdownReport.resetForm('breakdownreportFrm')" >Reset</a>
       </div>
     </form>
   </div>
</div>
<div id="openpurchaserequisitionwindow" class="easyui-window" title="Asset Idle Report PopUp Preparation Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="containerRqWindow" style="width:100%;height:100%;padding:0px;">

    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/report/FAM/MsAssetBreakdownReportController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
    $('#assetbreakdownreportFrm [id="reason_id"]').combobox();
</script>