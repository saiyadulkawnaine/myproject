<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="incentiveTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="80">ID</th>
        <th data-options="field:'name'" width="100">Name</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'north',border:true,title:'Add New Incentive',iconCls:'icon-more',hideCollapsedContent:false,collapsed:true,footer:'#ft2'" style="height:160px; padding:2px">
<form id="incentiveFrm">
    <div id="container">
         <div id="body">
           <code>

                <div class="row">
                    <div class="col-sm-2 req-text">Company </div>
                    <div class="col-sm-4">
                    {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                    <input type="hidden" name="id" id="id" value=""/>
                    </div>
                    <div class="col-sm-2 req-text">Location </div>
                    <div class="col-sm-4">{!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-2 req-text">Division  </div>
                    <div class="col-sm-4">{!! Form::select('division_id', $division,'',array('id'=>'division_id')) !!}</div>
                    <div class="col-sm-2 req-text">Department  </div>
                    <div class="col-sm-4">{!! Form::select('department_id', $department,'',array('id'=>'department_id')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-2 req-text">Section  </div>
                    <div class="col-sm-4">{!! Form::select('section_id', $section,'',array('id'=>'section_id')) !!}</div>
                    <div class="col-sm-2 req-text">Production Process  </div>
                    <div class="col-sm-4">{!! Form::select('production_process_id', $productionprocess,'',array('id'=>'production_process_id')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-2 req-text">Basis  </div>
                    <div class="col-sm-4">{!! Form::select('basis_id', $incentivebasis,'',array('id'=>'basis_id')) !!}</div>
                    <div class="col-sm-2 req-text">Designation  </div>
                    <div class="col-sm-4">{!! Form::select('designation_id', $designation,'',array('id'=>'designation_id')) !!}</div>
                </div>

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsIncentive.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('incentiveFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsIncentive.remove()" >Delete</a>
    </div>

  </form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsIncentiveController.js"></script>
