<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="workinghoursetupTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="40">ID</th>
        <th data-options="field:'company'" width="100">Company</th>
        <th data-options="field:'location'" width="100">Location</th>
        <th data-options="field:'floor_id'" width="100">Floor</th>
        <th data-options="field:'shiftname_id'" width="80">Shift</th>
        <th data-options="field:'shift_start'" width="80">Shift Starts</th>
        <th data-options="field:'shift_end'" width="80">Shift Ends</th>
        <th data-options="field:'lunch_start'" width="100">Lunch Starts</th>
        <th data-options="field:'lunch_duration'" width="100">Lunch Duration</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'west',border:true,title:'New Working Hour Setup',footer:'#ft2'" style="width:400px; padding:2px">
<form id="workinghoursetupFrm">
    <div id="container">
         <div id="body">
           <code>
              <div class="row middle">
                <div class="col-sm-4 req-text">Company</div>
                <div class="col-sm-8">
                  {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                  <input type="hidden" name="id" id="id" value=""/>
                </div>
              </div>
              <div class="row middle">
                <div class="col-sm-4 req-text">Location  </div>
                <div class="col-sm-8">
                  {!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}
                </div>
              </div>
              <div class="row middle">
                <div class="col-sm-4 req-text">Floor</div>
                <div class="col-sm-8">
                  {!! Form::select('floor_id', $floor,'',array('id'=>'floor_id')) !!}
               </div>
              </div>
              <div class="row middle">
                <div class="col-sm-4 req-text">Shift</div>
                <div class="col-sm-8">
                  {!! Form::select('shiftname_id', $shiftname,'',array('id'=>'shiftname_id')) !!}
                </div>
              </div>
              <div class="row middle">
                <div class="col-sm-4 req-text">Shift Starts</div>
                <div class="col-sm-8">
                  <input type="text" name="shift_start" id="shift_start"/>
                </div>
              </div>
              <div class="row middle">
                <div class="col-sm-4 req-text">Shift Ends</div>
                <div class="col-sm-8">
                  <input type="text" name="shift_end" id="shift_end" />
                </div>
              </div>
              <div class="row middle">
                <div class="col-sm-4 req-text">Lunch Starts</div>
                <div class="col-sm-8">
                  <input type="text" name="lunch_start" id="lunch_start" />
                </div>
              </div>
              <div class="row middle">
                <div class="col-sm-4 req-text">Lunch Duration</div>
                <div class="col-sm-8">
                  <input type="text" name="lunch_duration" id="lunch_duration" />
                </div>
              </div>
              
          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsWorkingHourSetup.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('workinghoursetupFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsWorkingHourSetup.remove()" >Delete</a>
    </div>

  </form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsWorkingHourSetupController.js"></script>

<script>
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>
