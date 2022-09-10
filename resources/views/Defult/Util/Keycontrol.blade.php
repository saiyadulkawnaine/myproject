<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="UtilKeyControls">
  <div title="Key Control" style="padding:1px" data-options="selected:true">
    <div class="easyui-layout" data-options="fit:true">
     <div data-options="region:'north',split:true, title:'Key Control',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="height:130px;padding:0px 12px">
       <code>
            <form id="keycontrolFrm">
               <div class="row">
                   <div class="col-sm-2 req-text">Company</div>
                   <div class="col-sm-4">
                   {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}

                   <input type="hidden" name="id" id="id" value=""/>
                   </div>
                   <div class="col-sm-2 req-text">Working Hour </div>
                   <div class="col-sm-4"><input type="text" name="working_hour" id="working_hour" value=""/></div>
               </div>
               <div class="row middle">
                   <div class="col-sm-2">Location  </div>
                   <div class="col-sm-4">
                   {!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}
                   </div>
                   <div class="col-sm-2">Currency  </div>
                   <div class="col-sm-4">
                       {!! Form::select('currency_id', $currency,'',array('id'=>'currency_id')) !!}
                   </div>
               </div>
                <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsKeycontrol.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('keycontrolFrm')" >Reset</a>
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsKeycontrol.remove()" >Delete</a>
                </div>
              </form>
         </code>
     </div>
      <div data-options="region:'center',split:true, title:'List'">
        <table id="keycontrolTbl" style="width:100%">
          <thead>
            <tr>
              <th data-options="field:'id'" width="80">ID</th>
              <th data-options="field:'workinghour'" width="100">Working Hour</th>
              <th data-options="field:'company'" width="100">Company</th>
              <th data-options="field:'location'" width="100">Location</th>
              <th data-options="field:'currency'" width="100">Currency</th>
            </tr>
          </thead>
        </table>
      </div>
   </div>
  </div>
  <div title="Key Control Parameter" style="padding:1px" data-options="selected:true">
      <div class="easyui-layout" data-options="fit:true">
          <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
              <table id="keycontrolparameterTbl" style="width:100%">
                  <thead>
                      <tr>
                          <th data-options="field:'id'" width="80">ID</th>
                          <th data-options="field:'parameter'" align="center" width="100">Parameter</th>
                          <th data-options="field:'from_date'" align="center" width="100">From Date</th>
                          <th data-options="field:'to_date'" align="center" width="100">To Date</th>
                          <th data-options="field:'value'" align="right" width="100">Value</th>
                          <th data-options="field:'working_hour'" align="right" width="100">Working hour</th>
                      </tr>
                  </thead>
              </table>
          </div>
          <div data-options="region:'north',border:true,title:'Add New Keycontrol Parameter',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft3'" style="height:125px; padding:0px 12px">
              <code>
                  <form id="keycontrolparameterFrm">
                      <div class="row">
                          <div class="col-sm-2 req-text">Parameter </div>
                          <div class="col-sm-4">
                            {!! Form::select('parameter_id', $keycontrolparameter,'',array('id'=>'parameter_id')) !!}
                            <input type="hidden" name="keycontrol_id" id="keycontrol_id" value=""/>
                            <input type="hidden" name="id" id="id" value=""/>
                          </div>
                          <div class="col-sm-2 req-text">Value </div>
                          <div class="col-sm-4"><input type="text" name="value" id="value" value=""/></div>
                      </div>
                      <div class="row middle">
                          <div class="col-sm-2 req-text">From Date </div>
                          <div class="col-sm-4"><input type="text" name="from_date" id="from_date" value="" class="datepicker"/></div>
                          <div class="col-sm-2 req-text">To Date </div>
                          <div class="col-sm-4"><input type="text" name="to_date" id="to_date" value="" class="datepicker"/></div>
                      </div>
                      <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                          <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsKeycontrolParameter.submit()">Save</a>
                          <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('keycontrolparameterFrm')" >Reset</a>
                          <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsKeycontrolParameter.remove()" >Delete</a>
                      </div>
                  </form>
              </code>
          </div>
      </div>
  </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAllKeycontrolController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script>