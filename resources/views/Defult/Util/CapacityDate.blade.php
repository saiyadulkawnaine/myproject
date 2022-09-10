<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="capacitydateTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="80">ID</th>
        <th data-options="field:'capacity'" width="100">Capacity</th>
        <th data-options="field:'capacitydate'" width="100">Capacity Date</th>
        <th data-options="field:'isoffday'" width="100">Is Off Day</th>
        <th data-options="field:'prodsource'" width="100">Product Source</th>
        <th data-options="field:'machine'" width="100">Machine(Qty)</th>
        <th data-options="field:'mktsmv'" width="100">MKT SMV</th>
        <th data-options="field:'mktpcs'" width="100">MKT Pcs</th>
        <th data-options="field:'prodsmv'" width="100">Product SMV</th>
        <th data-options="field:'prodpcs'" width="100">Product Pcs</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'north',border:true,title:'Add New CapacityDate',iconCls:'icon-more',hideCollapsedContent:false,collapsed:true,footer:'#ft2'" style="height:190px; padding:2px">
<form id="capacitydateFrm">
    <div id="container">
         <div id="body">
           <code>

                <div class="row">
                    <div class="col-sm-2 req-text">Capacity Date  </div>
                    <div class="col-sm-4"><input type="text" name="capacity_date" id="capacity_date" class="datepicker"/></div>
                    <input type="hidden" name="id" id="id" value=""/>
                    <input type="hidden" name="capacity_id" id="capacity_id" value=""/>
                    <div class="col-sm-2 req-text">Is Off Day  </div>
                    <div class="col-sm-4">{!! Form::select('is_off_day', $yesno,'',array('id'=>'is_off_day')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-2 req-text">Product Source  </div>
                    <div class="col-sm-4">{!! Form::select('prod_source_id', $productionsource,'',array('id'=>'prod_source_id')) !!}</div>
                    <div class="col-sm-2 req-text">No Of Machine  </div>
                    <div class="col-sm-4"><input type="text" name="no_of_machine" id="no_of_machine" value=""/></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-2 req-text">MKT SMV  </div>
                    <div class="col-sm-4"><input type="text" name="mkt_smv" id="mkt_smv" value=""/></div>
                    <div class="col-sm-2 req-text">MKT Pcs  </div>
                    <div class="col-sm-4"><input type="text" name="mkt_pcs" id="mkt_pcs" value=""/></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-2 req-text">Product SMV  </div>
                    <div class="col-sm-4"><input type="text" name="prod_smv" id="prod_smv" value=""/></div>
                    <div class="col-sm-2 req-text">Product Pcs  </div>
                    <div class="col-sm-4"><input type="text" name="prod_pcs" id="prod_pcs" value=""/></div>
                </div>

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCapacityDate.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('capacitydateFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsCapacityDate.remove()" >Delete</a>
    </div>

  </form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsCapacityDateController.js"></script>
<script>
$(".datepicker" ).datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});
</script>
