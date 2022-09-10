<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="prodaopbatchfinishqctabs">
    <div title="Batch" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List',footer:'#prodaopbatchfinishqcTblFt'" style="padding:2px">
                <table id="prodaopbatchfinishqcTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'company_code'" width="80">Company</th>
                            <th data-options="field:'batch_no'" width="80">Batch No</th>
                            <th data-options="field:'shiftname'" width="80">Shift No</th>
                            <th data-options="field:'qc_by_name'" width="80">Incharge</th>
                            <th data-options="field:'posting_date'" width="80">Prod.Date</th>
                            <th data-options="field:'remarks'" width="80">Remarks</th>
                            <th data-options="field:'batch_date'" width="100">Batch Date</th>
                            <th data-options="field:'batch_for'" width="100">Batch For</th>
                            <th data-options="field:'batch_color_name'" width="80">Batch Color</th>
                            <th data-options="field:'fabric_wgt'" width="80">Fabric Wgt</th>
                            <th data-options="field:'paste_wgt'" width="80">Paste Wgt</th>
                        </tr>
                    </thead>
                    <div id="prodaopbatchfinishqcTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        Batch Date: <input type="text" name="from_batch_date" id="from_batch_date" class="datepicker" style="width: 100px ;height: 23px" />
                        <input type="text" name="to_batch_date" id="to_batch_date" class="datepicker" style="width: 100px;height: 23px" />
                         Posting Date: <input type="text" name="from_load_posting_date" id="from_load_posting_date" class="datepicker" style="width: 100px ;height: 23px" />
                        <input type="text" name="to_load_posting_date" id="to_load_posting_date" class="datepicker" style="width: 100px;height: 23px" />
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-search" plain="true" id="save" onClick="MsProdAopBatchFinishQc.searchList()">Show</a>
                    </div>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add Details',footer:'#prodaopbatchfinishqcFrmft'" style="width: 400px; padding:2px">
                <form id="prodaopbatchfinishqcFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Shift</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('shift_id', $shiftname,'',array('id'=>'shift_id')) !!}
                                        <input type="hidden" name="id" id="id"  readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Batch No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="batch_no" id="batch_no" ondblclick="MsProdAopBatchFinishQc.batchWindow()" placeholder=" Double Click"readonly="" />
                                        <input type="hidden" name="prod_aop_batch_id" id="prod_aop_batch_id"  readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Prod.Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="posting_date" id="posting_date" value="{{-- {{ date('Y-m-d') }} --}}" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Customer</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('customer_id', $buyer,'',array('id'=>'customer_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">QC By</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qc_by_name" id="qc_by_name" ondblclick="MsProdAopBatchFinishQc.qcByWindow()" placeholder=" Double Click"/>
                                        <input type="hidden" name="qc_by_id" id="qc_by_id">
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="remarks" id="remarks" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Batch Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="batch_date" id="batch_date" class="datepicker" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Batch For</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('batch_for', $batchfor,'',array('id'=>'batch_for','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Batch Color</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('batch_color_id', $color,'',array('id'=>'batch_color_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Design No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="design_no" id="design_no" disabled />
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-4">Fabric Wgt.</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fabric_wgt" id="fabric_wgt" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Paste Wgt.</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="paste_wgt" id="paste_wgt" disabled />
                                    </div>
                                </div> 
                            </code>
                        </div>
                    </div>
                    <div id="prodaopbatchfinishqcFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdAopBatchFinishQc.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdAopBatchFinishQc.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdAopBatchFinishQc.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdAopBatchFinishQc.exportcsv()">Export</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Roll" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List',footer:'#prodaopbatchfinishqcrollTblFt'" style="padding:2px">
                <table id="prodaopbatchfinishqcrollTbl" style="width:100%">
                    <thead>
                        <tr>
                          <th data-options="field:'id'" width="70">ID</th>
                          <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
                          <th data-options="field:'custom_no'" width="70">Custom No</th>
                          <th data-options="field:'sale_order_no'" width="100">Order No</th>
                          <th data-options="field:'style_ref'" width="100">Style Ref</th>
                          <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
                          <th data-options="field:'rcv_qty',halign:'center'" width="80" align="right">Batch Qty</th>
                          <th data-options="field:'qc_pass_qty',halign:'center'" width="80" align="right">QC Pass Qty</th>
                          <th data-options="field:'reject_qty',halign:'center'" width="80" align="right">Reject Qty</th>
                          <th data-options="field:'qc_gsm_weight'" width="40">GSM</th>
                          <th data-options="field:'qc_dia_width'" width="40">Dia/<br/>Widht</th>
                          <th data-options="field:'grade'" width="40">Grade</th>

                          <th data-options="field:'fabric_color'" width="80">Batch Color</th>
                          <th data-options="field:'body_part'" width="100">Body Part</th>
                          <th data-options="field:'fabrication'" width="200">Fabric <br/>Description</th>
                          <th data-options="field:'fabric_shape'" width="70">Fabric <br/>Shape</th>
                          <th data-options="field:'fabric_look'" width="70">Fabric <br/>Look</th>
                          <th data-options="field:'prod_no'" width="70">Knit Prod. Ref</th>
                        </tr>
                    </thead>
                </table>
                <div id="prodaopbatchfinishqcrollTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdAopBatchFinishQcRoll.openrollWindow()">Import</a>
                
                </div>
            </div>
            <div data-options="region:'west',border:true,title:'Add Details',footer:'#prodaopbatchfinishqcrollFrmft'" style="width: 400px; padding:2px">
                <form id="prodaopbatchfinishqcrollFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Batch Qty</div>
                                    <div class="col-sm-8">
                                       
                                        <input type="hidden" name="id" id="id" class="number integer" readonly />
                                        <input type="hidden" name="prod_aop_batch_roll_id" id="prod_aop_batch_roll_id" class="number integer" readonly />
                                        <input type="text" name="batch_qty" id="batch_qty" class="number integer" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Qc Pass Qty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" class="number integer" onchange="MsProdAopBatchFinishQcRoll.calculate_reject()"/>
                                        
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Reject Qty</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="reject_qty" id="reject_qty" class="number integer" readonly />                                    
                                </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Dia/Width</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="dia_width" id="dia_width" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">GSM/Weight</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="gsm_weight" id="gsm_weight" class="number integer"/>
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4 req-text">Grade</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('grade_id', $rollqcresult,'',array('id'=>'grade_id')) !!}
                                    </div>
                                </div>

                                
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="remarks" id="remarks" />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle">
                                <div class="col-sm-4 req-text">File Upload</div>
                                <div class="col-sm-4">
                                <input type="file" id="roll_file" name="roll_file" value="" />
                                </div>
                                <div class="col-sm-4 req-text"><a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdAopBatchFinishQcRoll.import()">Upload</a></div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="prodaopbatchfinishqcrollFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdAopBatchFinishQcRoll.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdAopBatchFinishQcRoll.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdAopBatchFinishQcRoll.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</div>
<div id="prodaopbatchfinishqcbatchWindow" class="easyui-window" title="Machine Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
       <div data-options="region:'center',border:true,footer:'#prodaopbatchfinishqcbatchsearchTblFt'" style="padding:2px">
            <table id="prodaopbatchfinishqcbatchsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'company_code'" width="80">Company</th>
                        <th data-options="field:'customer_name'" width="100">Customer</th>
                        <th data-options="field:'batch_no'" width="80">Batch No</th>
                        <th data-options="field:'batch_date'" width="100">Batch Date</th>
                        <th data-options="field:'batch_for_name'" width="100">Batch For</th>
                        <th data-options="field:'batch_color_name'" width="80">Batch Color</th>
                        <th data-options="field:'design_no'" width="80">Design No</th>
                        <th data-options="field:'fabric_wgt'" width="80">Fabric Wgt</th>
                        <th data-options="field:'paste_wgt'" width="80">Paste Wgt</th>
                        <th data-options="field:'remarks'" width="80">Remarks</th>
                    </tr>
                </thead>
            </table>
            <div id="prodaopbatchfinishqcbatchsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#prodaopbatchfinishqcbatchWindow').window('close')" style="width:80px">Close</a>
                </div>
        </div>
        <div data-options="region:'west',border:true,footer:'#prodaopbatchfinishqcbatchsearchFrmFt'" style="padding:2px; width:350px">
            <form id="prodaopbatchfinishqcbatchsearchFrm">
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
                <div id="prodaopbatchfinishqcbatchsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true"  onClick="MsProdAopBatchFinishQc.getBatch()" >Search</a>
                </div>
            </form>
        </div>
    </div>
</div>




<div id="prodaopbatchfinishqcinchargewindow" class="easyui-window" title="Operator Employee Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="prodaopbatchfinishqcinchargeFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Company</div>
                                <div class="col-sm-8">
                                    {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Designation</div>
                                <div class="col-sm-8">
                                    {!! Form::select('designation_id', $designation,'',array('id'=>'designation_id','style'=>'width: 100%; border-radius:2px')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Department </div>
                                <div class="col-sm-8">
                                    {!! Form::select('department_id', $department,'',array('id'=>'department_id','style'=>'width: 100%; border-radius:2px')) !!}
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsProdAopBatchFinishQc.searchEmpIncharge()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="prodaopbatchfinishqcinchargeTbl" style="width:700px">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'employee_name'" width="100">Name</th>
                        <th data-options="field:'code'" width="100">User Given Code</th>
                        <th data-options="field:'designation_id'" width="100">Designation</th>
                        <th data-options="field:'department_id'" width="100">Department</th>
                        <th data-options="field:'company_id'" width="100">Company</th>
                        <th data-options="field:'contact'" width="100">Phone No</th>
                        <th data-options="field:'email'" width="120">Email Address</th>
                        <th data-options="field:'last_education'" width="100">Last Education</th>
                        <th data-options="field:'experience'" width="100">Experience</th>
                        <th data-options="field:'address'" width="100">Address</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#prodaopbatchfinishqcinchargewindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>



<div id="prodaopbatchfinishqcrollsearchwindow" class="easyui-window" title="Roll" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center',footer:'#prodaopbatchfinishqcrollsearchTblFt'" style="padding:10px;">
            <table id="prodaopbatchfinishqcrollsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="70">ID</th>
                          <th data-options="field:'issue_no'" width="70">Issue No</th>
                          <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
                          <th data-options="field:'custom_no'" width="70">Custom No</th>
                          <th data-options="field:'sale_order_no'" width="100">Order No</th>
                          <th data-options="field:'style_ref'" width="100">Style Ref</th>
                          <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
                          <th data-options="field:'rcv_qty'" width="80" align="right">Batch Qty</th>
                          <th data-options="field:'fabric_color'" width="80">Batch Color</th>
                          <th data-options="field:'body_part'" width="100">Body Part</th>
                          <th data-options="field:'fabrication'" width="200">Fabric <br/>Description</th>
                          <th data-options="field:'fabric_shape'" width="70">Fabric <br/>Shape</th>
                          <th data-options="field:'fabric_look'" width="70">Fabric <br/>Look</th>
                          <th data-options="field:'dyeing_gsm_weight'" width="40">GSM</th>
                          <th data-options="field:'dyeing_dia_width'" width="40">Dia</th>
                          <th data-options="field:'prod_no'" width="70">Knit Prod. Ref</th>
                    </tr>
                </thead>
            </table>
            <div id="prodaopbatchfinishqcrollsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdAopBatchFinishQcRoll.selectAll('#prodaopbatchfinishqcrollsearchTbl')">Select All</a>
                 <a href="javascript:void(0)" class="easyui-linkbutton  c6" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdAopBatchFinishQcRoll.unselectAll('#prodaopbatchfinishqcrollsearchTbl')">Unselect All</a>

                <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdAopBatchFinishQcRoll.openForm()">Next</a>
                
                </div>
        </div>
    </div>
</div>

<div id="prodaopbatchfinishqcrollmultiwindow" class="easyui-window" title="Roll" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center',footer:'#prodaopbatchfinishqcrollmultiFrmFt'" style="padding:10px;">
            <form id="prodaopbatchfinishqcrollmultiFrm">
                    <div id="prodaopbatchfinishqcrollmultiFrmContainer">
                        
                    </div>
                </form>
            
            <div id="prodaopbatchfinishqcrollmultiFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
            <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdAopBatchFinishQcRoll.submitBatch()">Save</a>

            </div>
        </div>
    </div>
</div>









<script type="text/javascript" src="<?php echo url('/');?>/js/Production/AOP/MsAllProdAopBatchFinishQcController.js"></script>

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
         changeYear: true
      });

      $('#load_time').timepicker(
        {
            'timeFormat': 'h:i:s A',
            'interval': 60,
            'minTime': '12:00pm',
            'maxTime': '11:59am',
            'showDuration': false,
            'step':1,
            'listWidth': 1,
            'width': '200px !important',
            'scrollDefault': 'now',
            'change': function(){
                alert('m')
            }
        }
    );

    $('#unload_time').timepicker(
        {
            'timeFormat': 'h:i:s A',
            'interval': 60,
            'minTime': '12:00pm',
            'maxTime': '11:59am',
            'showDuration': false,
            'step':1,
            'listWidth': 1,
            'width': '200px !important',
            'scrollDefault': 'now',
            'change': function(){
                alert('m')
            }
        }
    );

      $('.integer').keyup(function () {
         if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
         }
      });


   })(jQuery);

</script>