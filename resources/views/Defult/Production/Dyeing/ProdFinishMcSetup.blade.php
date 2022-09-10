<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="prodfinishmcsetuptabs">
 <div title="Fabric Finishing Machine Setup" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="prodfinishmcsetupTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="80">ID</th>
       <th data-options="field:'custom_no'" width="100">Machine No</th>
       <th data-options="field:'asset_name'" width="150">Asset Name</th>
       <th data-options="field:'company_name'" width="150">Company</th>
       <th data-options="field:'remarks'" width="200">Remarks</th>
      </tr>
     </thead>
    </table>
   </div>
   <div data-options="region:'west',border:true,title:'Add New Entry',footer:'#ft2'" style="width: 400px; padding:2px">
    <form id="prodfinishmcsetupFrm">
     <div id="container">
        <div id="body">
          <code>
            <div class="row middle">
              <div class="col-sm-4 req-text">Machine No</div>
                <div class="col-sm-8">
                    <input type="text" name="machine_no" id="machine_no" ondblclick="MsProdFinishMcSetup.machineWindow()" placeholder=" Double Click" readonly/>
                    <input type="hidden" name="machine_id" id="machine_id" value="" />
                    <input type="hidden" name="id" id="id" value="" />
                </div>
              </div>
              <div class="row middle">
               <div class="col-sm-4">Company name</div>
               <div class="col-sm-8">
                <input type="text" name="company_name" id="company_name" disabled />
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
       iconCls="icon-save" plain="true" id="save" onClick="MsProdFinishMcSetup.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsProdFinishMcSetup.resetForm()">Reset</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsProdFinishMcSetup.remove()">Delete</a>
     </div>

    </form>
   </div>
  </div>
 </div>
 <div title="Date" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'west',border:true,title:'Fabric Finishing Machine Date',footer:'#prodfinishmcdateFrmFt'"
    style="width: 400px; padding:2px">
    <form id="prodfinishmcdateFrm">
     <div id="container">
      <div id="body">
        <code>
              <div class="row middle">
                <div class="col-sm-4 req-text">Targer Date</div>
                <div class="col-sm-8">
                   <input type="hidden" name="id" id="id"/>
                   <input type="hidden" name="prod_finish_mc_setup_id" id="prod_finish_mc_setup_id">
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
     <div id="prodfinishmcdateFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsProdFinishMcDate.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsProdFinishMcDate.resetForm()">Reset</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsProdFinishMcDate.remove()">Delete</a>
     </div>
    </form>
   </div>
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="prodfinishmcdateTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="80">ID</th>
       <th data-options="field:'target_date'" width="80" align="center">Target date</th>
       <th data-options="field:'adjusted_minute'" width="80" align="center">Adjusted Minute</th>
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
    <table id="prodfinishmcparameterTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="80">ID</th>
       <th data-options="field:'batch_no'" width="80">Batch No</th>
       <th data-options="field:'rmp'" width="80">RPM</th>
       <th data-options="field:'gsm_weight'" width="80">GSM Weight</th>
       <th data-options="field:'batch_wgt'" width="80">Batch Weight</th>
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
            <form id="prodfinishmcparameterFrm">
                <div class="row middle" style="display:none">
                    <input type="hidden" name="id" id="id" value="" />
                    <input type="hidden" name="prod_finish_mc_date_id" id="prod_finish_mc_date_id" value="" />
                </div>
                <div class="row middle">
                    <div class="col-sm-5 req-text">Batch No</div>
                    <div class="col-sm-7">
                        <input type="hidden" name="prod_batch_id" id="prod_batch_id">
                        <input type="text" name="batch_no" id="batch_no" value="" placeholder="Dobule Click" ondblclick="MsProdFinishMcParameter.prodFinishParameterWindow()" readonly/>
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5 req-text">RPM</div>
                    <div class="col-sm-7">
                        <input type="text" name="rmp" id="rmp" value="" onchange="MsProdFinishMcParameter.prodFinishMcParameterCalculateWorkingMinute()" class="integer number"/>
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5 req-text">GSM Weight</div>
                    <div class="col-sm-7">
                        <input type="text" name="gsm_weight" id="gsm_weight" value="" onchange="MsProdFinishMcParameter.prodFinishMcParameterCalculateWorkingMinute()" class="integer number"/>
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5 req-text">Dia/Width</div>
                    <div class="col-sm-7">
                        <input type="text" name="dia" id="dia" value="" onchange="MsProdFinishMcParameter.prodFinishMcParameterCalculateWorkingMinute()" class="integer number"/>
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5 req-text">Working Minute</div>
                    <div class="col-sm-7">
                        <input type="text" name="working_minute" id="working_minute" value=""  readonly/>
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
                   <input type="hidden" name="employee_h_r_id" id="employee_h_r_id" value="">
                   <input type="text" name="employee_name" id="employee_name" placeholder="Dobule Click" ondblclick="MsProdFinishMcParameter.openManpowerEmployee()" readonly />
                  </div>
                 </div>
                 <div class="row middle">
                  <div class="col-sm-5 req-text">Batch Wgt</div>
                  <div class="col-sm-7">
                   <input type="text" name="batch_wgt" id="batch_wgt" value="" disabled />
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
      iconCls="icon-save" plain="true" id="save" onClick="MsProdFinishMcParameter.submit()">Save</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsProdFinishMcParameter.resetForm()">Reset</a>
     <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsProdFinishMcParameter.remove()">Delete</a>
    </div>
   </div>
  </div>
 </div>
</div>

<div id="prodfinishmachineWindow" class="easyui-window" title="Machine Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',border:true,footer:'#prodfinishmachinesearchTblFt'" style="padding:2px">
   <table id="prodfinishmachinesearchTbl" style="width:100%">
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
   <div id="prodfinishmachinesearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
     onclick="$('#prodfinishmachineWindow').window('close')" style="width:80px">Close</a>
   </div>
  </div>
  <div data-options="region:'west',border:true,footer:'#prodfinishmachinesearchFrmFt'" style="padding:2px; width:350px">
   <form id="prodfinishmachinesearchFrm">
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
    <div id="prodfinishmachinesearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true" \
      onClick="MsProdFinishMcSetup.searchMachine()">Search</a>
    </div>
   </form>
  </div>
 </div>
</div>

<div id="prodfinishmcparameterWindow" class="easyui-window" title="Machine Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',border:true,footer:'#prodfinishmcparametersearchTblFt'" style="padding:2px">
   <table id="prodfinishmcparametersearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="40">ID</th>
      <th data-options="field:'company_code'" width="80">Company</th>
      <th data-options="field:'batch_no'" width="80">Batch No</th>
      <th data-options="field:'batch_date'" width="100">Batch Date</th>
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
   <div id="prodfinishmcparametersearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
     onclick="$('#prodfinishmcparameterWindow').window('close')" style="width:80px">Close</a>
   </div>
  </div>
  <div data-options="region:'west',border:true,footer:'#prodfinishmcparametersearchFrmFt'"
   style="padding:2px; width:350px">
   <form id="prodfinishmcparametersearchFrm">
    <div id="container">
     <div id="body">
      <code>
                            <div class="row ">
                            <div class="col-sm-4 req-text">Company</div>
                            <div class="col-sm-8">
                           <input type="text" name="company_id" id="company_id">
                            </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4">Batch No</div>
                            <div class="col-sm-8">
                            <input type="text" name="batch_no" id="batch_no" />
                            </div>
                            </div>
                        </code>
     </div>
    </div>
    <div id="prodfinishmcparametersearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true"
      onClick="MsProdFinishMcParameter.getBatch()">Search</a>
    </div>
   </form>
  </div>
 </div>
</div>

<!--------------------Employee Search-Window Start------------------>
<div id="prodFinishMcParameterEmployeeWindow" class="easyui-window" title="Employee Search Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
   <div class="easyui-layout" data-options="fit:true">
    <div id="body">
     <code>
                        <form id="prodFinishMcParametersearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Company</div>
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
      onClick="MsProdFinishMcParameter.searchEmployeeGrid()">Search</a>
    </p>
   </div>
  </div>
  <div data-options="region:'center'" style="padding:10px;">
   <table id="prodFinishMcParameterSearchTbl" style="width:700px">
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
    onclick="$('#prodFinishMcParameterEmployeeWindow').window('close')" style="width:80px">Close</a>
  </div>
 </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/Production/Dyeing/MsAllProdFinishMcController.js">
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