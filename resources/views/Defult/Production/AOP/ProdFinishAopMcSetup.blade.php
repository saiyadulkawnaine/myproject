<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="prodfinishaopmcsetuptabs">
 <div title="Aop Machine Setup" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="prodfinishaopmcsetupTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="80">ID</th>
       <th data-options="field:'custom_no'" width="100">Machine No</th>
       <th data-options="field:'asset_name'" width="100">Asset Name</th>
       <th data-options="field:'company_name'" width="100">Company</th>
       <th data-options="field:'remarks'" width="200">Remarks</th>
      </tr>
     </thead>
    </table>
   </div>
   <div data-options="region:'west',border:true,title:'Add New Entry',footer:'#ft2'" style="width: 400px; padding:2px">
    <form id="prodfinishaopmcsetupFrm">
        <div id="container">
            <div id="body">
                <code>
                    <div class="row middle">
                        <div class="col-sm-4 req-text">Machine No</div>
                        <div class="col-sm-8">
                        <input type="hidden" name="machine_id" id="machine_id" value="" />
                          <input type="text" name="custom_no" id="custom_no" ondblclick="MsProdFinishAopMcSetup.openFinishMachineWindow()"
                           placeholder=" Double Click"  readonly/>
                         </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4 req-text">Company</div>
                        <div class="col-sm-8">
                        <input type="text" name="company_name" id="company_name" value="" disabled />
                        <input type="hidden" name="id" id="id" value="" />
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Remarks</div>
                        <div class="col-sm-8">
                            <textarea name="remarks" id="remarks"></textarea>
                        </div>
                    </div>
                </code>
            </div>
        </div>
        <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
          <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
           iconCls="icon-save" plain="true" id="save" onClick="MsProdFinishAopMcSetup.submit()">Save</a>
          <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
           iconCls="icon-remove" plain="true" id="delete" onClick="MsProdFinishAopMcSetup.resetForm()">Reset</a>
          <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
           iconCls="icon-remove" plain="true" id="delete" onClick="MsProdFinishAopMcSetup.remove()">Delete</a>
        </div>
    </form>
   </div>
  </div>
 </div>
 <div title="Date" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'west',border:true,title:'Aop Machine Date',footer:'#prodfinishaopmcdateFrmFt'"
    style="width: 400px; padding:2px">
    <form id="prodfinishaopmcdateFrm">
     <div id="container">
      <div id="body">
       <code>
            <div class="row middle">
                <div class="col-sm-4 req-text">Targer Date</div>
                <div class="col-sm-8">
                 <input type="hidden" name="id" id="id"/>
                 <input type="hidden" name="prod_finish_aop_mc_setup_id" id="prod_finish_aop_mc_setup_id">
                    <input type="text" name="target_date" id="target_date" value="" class="datepicker"/>
                </div>
             </div>
             <div class="row middle">
                <div class="col-sm-4">Adjusted Minute</div>
                <div class="col-sm-8">
                    <input type="text" name="adjusted_minute" id="adjusted_minute" value="" class="number integer"/>
                </div>
              </div>
             <div class="row middle">
              <div class="col-sm-4">Remarks</div>
              <div class="col-sm-8">
               <textarea name="remarks" id="remarks"></textarea>
              </div>
             </div>
        </code>
      </div>
     </div>
     <div id="prodfinishaopmcdateFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsProdFinishAopMcDate.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsProdFinishAopMcDate.resetForm()">Reset</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsProdFinishAopMcDate.remove()">Delete</a>
     </div>
    </form>
   </div>
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="prodfinishaopmcdateTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="80">ID</th>
       <th data-options="field:'target_date'" width="80" align="center">Target date</th>
       <th data-options="field:'adjusted_minute'" width="100" align="center">Adjusted Minute</th>
       <th data-options="field:'remarks'" width="80" align="right">Remarks</th>
      </tr>
     </thead>
    </table>
   </div>
  </div>
 </div>
 <div title="Parameter" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="prodfinishaopmcparameterTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="80">ID</th>
       <th data-options="field:'batch_no'" width="80">Batch No</th>
       <th data-options="field:'rmp'" width="80">RPM</th>
       <th data-options="field:'gsm_weight'" width="80">GSM Weight</th>
       <th data-options="field:'fabric_wgt'" width="80">Fabric Weight</th>
       <th data-options="field:'dia'" width="80">Dia</th>
       <th data-options="field:'working_minute'" width="80">Working Minute</th>
       <th data-options="field:'shift_id'" width="80">Shift Name</th>
       <th data-options="field:'employee_name'" width="80">Supervisor</th>
       <th data-options="field:'remarks'" width="80">Remarks</th>
      </tr>
     </thead>
    </table>
   </div>
   <div
    data-options="region:'west',border:true,title:'Payment Details',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft4'"
    style="width:400px; padding:2px">
    <div id="container">
     <div id="body">
      <code>
            <form id="prodfinishaopmcparameterFrm">
                <div class="row middle" style="display:none">
                    <input type="hidden" name="id" id="id" value="" />
                    <input type="hidden" name="prod_finish_aop_mc_date_id" id="prod_finish_aop_mc_date_id" value="" />
                </div>
                <div class="row middle">
                    <div class="col-sm-5 req-text">Batch No</div>
                    <div class="col-sm-7">
                        <input type="hidden" name="prod_aop_batch_id" id="prod_aop_batch_id">
                        <input type="text" name="batch_no" id="batch_no" value="" placeholder="Dobule Click" ondblclick="MsProdFinishAopMcParameter.prodFinishAopParameterBatchWindow()" readonly/>
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5 req-text">RPM</div>
                    <div class="col-sm-7">
                        <input type="text" name="rmp" id="rmp" value="" onchange="MsProdFinishAopMcParameter.CalculateWorkingMinute()" class="integer number"/>
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5 req-text">GSM Weight</div>
                    <div class="col-sm-7">
                        <input type="text" name="gsm_weight" id="gsm_weight" value="" onchange="MsProdFinishAopMcParameter.CalculateWorkingMinute()" class="integer number"/>
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5 req-text">Dia/Width</div>
                    <div class="col-sm-7">
                        <input type="text" name="dia" id="dia" value="" onchange="MsProdFinishAopMcParameter.CalculateWorkingMinute()" class="integer number"/>
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5 req-text">Fabric Wgt.</div>
                    <div class="col-sm-7">
                        <input type="text" name="fabric_wgt" id="fabric_wgt"  class="number integer" onchange="MsProdFinishAopMcParameter.CalculateWorkingMinute()" readonly />
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5 req-text">Working Minute</div>
                    <div class="col-sm-7">
                        <input type="text" name="working_minute" id="working_minute" value="" class="number integer"  readonly/>
                    </div>
                </div>
                <div class="row middle">
                  <div class="col-sm-5">Shift Name </div>
                  <div class="col-sm-7">
                  {!! Form::select('shift_id', $shiftname,'',array('id'=>'shift_id')) !!}
                  </div>
                 </div>
                <div class="row middle">
                  <div class="col-sm-5">Supervisor</div>
                  <div class="col-sm-7">
                   <input type="hidden" name="employee_h_r_id" id="employee_h_r_id">
                   <input type="text" name="name" id="name" placeholder="Dobule Click" ondblclick="MsProdFinishAopMcParameter.openProdFinishEmployee()" readonly>
                  </div>
                 </div>
                <div class="row middle">
                    <div class="col-sm-5">Remarks</div>
                    <div class="col-sm-7"> 
                        <textarea name="remarks" id="remarks" ></textarea>
                    </div>
                </div>
            </form>
        </code>
     </div>
    </div>
    <div id="ft4" style="padding:0px 0px; text-align:right; background:#CCC;">
     <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
      iconCls="icon-save" plain="true" id="save" onClick="MsProdFinishAopMcParameter.submit()">Save</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsProdFinishAopMcParameter.resetForm()">Reset</a>
     <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsProdFinishAopMcParameter.remove()">Delete</a>
    </div>
   </div>
  </div>
 </div>
</div>

<div id="prodfinishaopmachineWindow" class="easyui-window" title="Machine Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',border:true,footer:'#prodfinishaopmachinesearchTblFt'" style="padding:2px">
   <table id="prodfinishaopmachinesearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="80">ID</th>
      <th data-options="field:'custom_no'" width="100">Machine No</th>
      <th data-options="field:'asset_name'" width="100">Asset Name</th>
      <th data-options="field:'origin'" width="100">Origin</th>
      <th data-options="field:'company_name'" width="100">Company Name</th>
      <th data-options="field:'brand'" width="100">Brand</th>
      <th data-options="field:'prod_capacity'" width="100">Prod. Capacity</th>
      <th data-options="field:'dia_width'" width="60">Dia/Width</th>
      <th data-options="field:'gauge'" width="60">Gauge</th>
      <th data-options="field:'extra_cylinder'" width="60">Extra Cylinder</th>
      <th data-options="field:'no_of_feeder'" width="60">Feeder</th>
     </tr>
    </thead>
   </table>
   <div id="prodfinishaopmachinesearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
     onclick="$('#prodfinishaopmachineWindow').window('close')" style="width:80px">Close</a>
   </div>
  </div>
  <div data-options="region:'west',border:true,footer:'#prodfinishaopmachinesearchFrmFt'" style="padding:2px; width:350px">
   <form id="prodfinishaopmachinesearchFrm">
    <div id="container">
     <div id="body">
        <code>
            <div class="row ">
            <div class="col-sm-4">Machine No</div>
            <div class="col-sm-8"> <input type="text" name="machine_no" id="machine_no" /> </div>
            </div>
          <div class="row middle ">
           <div class="col-sm-4">Company Name</div>
           <div class="col-sm-8"> <input type="text" name="company_name" id="company_name" /> </div>
          </div>
        </code>
     </div>
    </div>
    <div id="prodfinishaopmachinesearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true" \
      onClick="MsProdFinishAopMcSetup.searchFinishMachine()">Search</a>
    </div>
   </form>
  </div>
 </div>
</div>

{{-- search batch --}}
<div id="prodfinishaopmcbatchWindow" class="easyui-window" title="Machine Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
       <div data-options="region:'center',border:true,footer:'#prodfinishaopmcbatchsearchTblFt'" style="padding:2px">
            <table id="prodfinishaopmcbatchsearchTbl" style="width:100%">
                <thead>
                    <tr>
                    <th data-options="field:'id'" width="40">ID</th>
                    <th data-options="field:'company_code'" width="80">Company</th>
                    <th data-options="field:'batch_no'" width="80">Batch No</th>
                    <th data-options="field:'batch_date'" width="100">Batch Date</th>
                    <th data-options="field:'batchfor'" width="100">Batch For</th>
                    <th data-options="field:'machine_no'" width="80">Machine No</th>
                    <th data-options="field:'batch_color_name'" width="80">Batch Color</th>
                    <th data-options="field:'fabric_wgt'" width="80">Fabric Wgt</th>
                    <th data-options="field:'paste_wgt'" width="80">Paste Wgt</th>
                    </tr>
                </thead>
            </table>
            <div id="prodfinishaopmcbatchsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#prodfinishaopmcWindow').window('close')" style="width:80px">Close</a>
                </div>
        </div>
        <div data-options="region:'west',border:true,footer:'#prodfinishaopmcbatchparametersearchFrmFt'" style="padding:2px; width:350px">
            <form id="prodfinishaopmcbatchparametersearchFrm">
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
                <div id="prodfinishaopmcbatchparametersearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true"  onClick="MsProdFinishAopMcParameter.getAopBatch()" >Search</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!--------------------Employee Search-Window Start------------------>
<div id="prodfinishaopEmployeeSearchWindow" class="easyui-window" title="Employee Search Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
    <div class="easyui-layout" data-options="fit:true">
        <div id="body">
            <code>
                <form id="prodfinishaopemployeeSearchFrm">
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
            <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px"
          onClick="MsProdFinishAopMcParameter.searchProdFinishEmployeeGrid()">Search</a>
        </p>
    </div>
  </div>
  <div data-options="region:'center'" style="padding:10px;">
   <table id="prodfinishaopemployeeSearchTbl" style="width:700px">
    <thead>
     <tr>
      <th data-options="field:'id'" width="40">ID</th>
      <th data-options="field:'name'" width="100">Name</th>
      <th data-options="field:'code'" width="100">User Given Code</th>
      <th data-options="field:'designation_id'" width="100">Designation</th>
      <th data-options="field:'department_id'" width="100">Department</th>
      <th data-options="field:'company_id'" width="100">Company</th>
      <th data-options="field:'grade'" width="100">Grade</th>
      <th data-options="field:'date_of_join'" width="120">Date of Join</th>
      <th data-options="field:'contact'" width="100">Phone No</th>
      <th data-options="field:'email'" width="120">Email Address</th>
      <th data-options="field:'national_id'" width="100">National ID</th>
      <th data-options="field:'address'" width="100">Address</th>
      <th data-options="field:'is_advanced_applicable'" width="100">Advance Applicable</th>
      <th data-options="field:'last_education'" width="100">Last Education</th>
      <th data-options="field:'experience'" width="100">Experience</th>
     </tr>
    </thead>
   </table>
  </div>
  <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
   <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
    onclick="$('#prodfinishaopEmployeeSearchWindow').window('close')" style="width:80px">Close</a>
  </div>
 </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/Production/AOP/MsAllProdFinishAopMcSetupController.js">
</script>
<script>
 (function(){
    $(".datepicker").datepicker({
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
    changeYear: true
    });
    })(jQuery);
    $('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.-]/g, '')) {
    this.value = this.value.replace(/[^0-9\.-]/g, '');
    }
    });
</script>