<div class="easyui-layout animated rollIn" data-options="fit:true"
 style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
 <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
  <table id="targetprocesssetupTbl" style="width:100%">
   <thead>
    <tr>
     <th data-options="field:'id'" width="80">ID</th>
     <th data-options="field:'process_name'" width="100">Target Proccess</th>
     <th data-options="field:'production_area_name'" width="100">Production Area</th>
     <th data-options="field:'sort_id'" width="100">Sequence</th>
    </tr>
   </thead>
  </table>
 </div>
 <div data-options="region:'west',border:true,title:'Add New Target Process Setup',footer:'#ft2'"
  style="width: 350px; padding:2px">
  <form id="targetprocesssetupFrm">
   <div id="container">
    <div id="body">
     <code>
        <div class="row">
        <div class="col-sm-5 req-text">Target Process </div>
        <div class="col-sm-7">{!! Form::select('process_id', $tergetProcess,'',array('id'=>'process_id')) !!}</div>
        <input type="hidden" name="id" id="id">
        </div>
        <div class="row middle">
        <div class="col-sm-5 req-text">Production Area </div>
        <div class="col-sm-7">{!! Form::select('production_area_id', $productionarea,'',array('id'=>'production_area_id','style'=>'width:100%;border-radius:2px')) !!}</div>
        </div>
        <div class="row middle req-text">
          <div class="col-sm-5">Sequence</div>
          <div class="col-sm-7"><input type="text" name="sort_id" id="sort_id" class="number integer"/></div>
        </div>
    </code>
    </div>
   </div>
   <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
     iconCls="icon-save" plain="true" id="save" onClick="MsTargetProcessSetup.submit()">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
     iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('targetprocesssetupFrm')">Reset</a>
    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
     iconCls="icon-remove" plain="true" id="delete" onClick="MsTargetProcessSetup.remove()">Delete</a>
   </div>

  </form>
 </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsTargetProcessSetupController.js"></script>
<script>
 $('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
$('#targetprocesssetupFrm [name=production_area_id]').combobox();
</script>