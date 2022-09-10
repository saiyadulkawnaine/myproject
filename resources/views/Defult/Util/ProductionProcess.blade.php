<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="productionprocessTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="80">ID</th>
        <th data-options="field:'processname'" width="100">Process Name</th>
        <th data-options="field:'productionarea'" width="100">Production Area</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'west',border:true,title:'Add New ProductionProcess',footer:'#ft2'" style="width:380px; padding:2px">
<form id="productionprocessFrm">
    <div id="container">
         <div id="body">
           <code>

                <div class="row">
                    <div class="col-sm-4 req-text">Process Name </div>
                    <div class="col-sm-8">
                    <input type="text" name="process_name" id="process_name" />
                    <input type="hidden" name="id" id="id" />
                    </div>
                  </div>
                  <div class="row middle">
                    <div class="col-sm-4 req-text">Production Area  </div>
                    <div class="col-sm-8">{!! Form::select('production_area_id', $productionarea,'',array('id'=>'production_area_id')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Sequence  </div>
                    <div class="col-sm-8"><input type="text" name="sort_id" id="sort_id" class="number integer" /></div>
                </div>

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProductionProcess.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('productionprocessFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProductionProcess.remove()" >Delete</a>
    </div>

  </form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsProductionProcessController.js"></script>
<script>
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>
