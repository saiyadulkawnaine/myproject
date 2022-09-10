<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="tnataskTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="80">ID</th>
        <th data-options="field:'tna_task_id'" width="100">Task ID</th>
        <th data-options="field:'task_name'" width="100">Task Name</th>
        <th data-options="field:'autocompletion'" width="100">Auto Completion</th>
        <th data-options="field:'completion_treated_percent'" width="100"> Completion %</th>
        <th data-options="field:'sort_id'" width="100"> Sequence</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'west',border:true,title:'Add New Tnatask',iconCls:'icon-more',footer:'#tnataskFrmFt'" style="width:350px; padding:2px">
    <form id="tnataskFrm">
      <div id="container">
         <div id="body">
           <code>
                <div class="row">
                    <div class="col-sm-5 req-text">Task ID</div>
                    <div class="col-sm-7">
                        {!! Form::select('tna_task_id', $tnatask,'',array('id'=>'tna_task_id')) !!}
                    <input type="hidden" name="id" id="id" value=""/>
                    </div>
                    
                </div>
                <div class="row middle">
                    <div class="col-sm-5 req-text">Task Name </div>
                    <div class="col-sm-7">
                        <input type="text" name="task_name" id="task_name" value=""/>
                    </div>
                    
                </div>

                 <div class="row middle">
                    
                    <div class="col-sm-5 req-text">Is Auto Completion </div>
                    <div class="col-sm-7">
                        {!! Form::select('is_auto_completion', $yesno,'',array('id'=>'is_auto_completion')) !!}
                    </div>
                </div>

                <div class="row middle">
                    <div class="col-sm-5">Completion  % </div>
                    <div class="col-sm-7">
                        <input type="text" name="completion_treated_percent" id="completion_treated_percent" value=""/>
                    </div>
                    
                </div>

                 <div class="row middle">
                   
                    <div class="col-sm-5">Penalty Applicable </div>
                    <div class="col-sm-7">
                        {!! Form::select('penalty_applicable_id', $yesno,'',array('id'=>'penalty_applicable_id')) !!}
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5">Panalty Amount </div>
                    <div class="col-sm-7"><input type="text" name="panalty_amount" id="panalty_amount" value=""/></div>
                    
                </div>
                <div class="row middle">
                    
                    <div class="col-sm-5">Sequence  </div>
                    <div class="col-sm-7">
                        <input type="text" name="sort_id" id="sort_id" value=""/>
                    </div>
                </div>
            </code>
       </div>
      </div>
        <div id="tnataskFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsTnatask.submit()">Save</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('tnataskFrm')" >Reset</a>
            <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsTnatask.remove()" >Delete</a>
        </div>
    </form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsTnataskController.js"></script>
<script>
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>