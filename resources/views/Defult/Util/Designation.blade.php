<div class="easyui-layout animated rollIn" data-options="fit:true"
 style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
 <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
  <table id="designationTbl" style="width:100%">
   <thead>
    <tr>
     <th data-options="field:'id'" width="80">ID</th>
     <th data-options="field:'name'" width="180">Functionanl Designation</th>
     <th data-options="field:'designation_level_id'" width="180">Structural Designation</th>
     <th data-options="field:'employee_category_id'" width="140">Employee Category</th>
     <th data-options="field:'grade'" width="60">Grade</th>
    </tr>
   </thead>
  </table>
 </div>
 <div
  data-options="region:'west',border:true,title:'Add New Designation',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'"
  style="width:420px;height:100%; padding:2px">
  <form id="designationFrm">
   <div id="container">
    <div id="body">
     <code>
              <div class="row middle">
                <div class="col-sm-5 req-text">Functionanl Designation</div>
                <div class="col-sm-7">
                <input type="text" name="name" id="name" value=""/>
                <input type="hidden" name="id" id="id" value=""/>
                </div>
              </div>
              <div class="row middle">
                <div class="col-sm-5">Structural Designation</div>
                <div class="col-sm-7">{!! Form::select('designation_level_id', $designationlevel,'',array('id'=>'designation_level_id','style'=>'width: 100%; border-radius:2px')) !!}</div>
              </div>
              <div class="row middle">
                <div class="col-sm-5">Employee Category</div>
                <div class="col-sm-7">{!! Form::select('employee_category_id', $employeecategory,'',array('id'=>'employee_category_id')) !!}</div>
              </div>
              <div class="row middle">
                <div class="col-sm-5">Grade </div>
                <div class="col-sm-7"><input type="text" name="grade" id="grade" /></div>
            </div>
            </code>
    </div>
   </div>
   <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save"
     plain="true" id="save" onClick="MsDesignation.submit()">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
     iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('designationFrm')">Reset</a>
    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
     iconCls="icon-remove" plain="true" id="delete" onClick="MsDesignation.remove()">Delete</a>
   </div>
  </form>
 </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsDesignationController.js"></script>
<script>
 (function(){

    $('#designationFrm [id="designation_level_id"]').combobox();
  })(jQuery);
  
</script>