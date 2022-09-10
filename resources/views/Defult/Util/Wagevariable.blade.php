<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="wagevariableTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="80">ID</th>
        <th data-options="field:'name'" width="100">Name</th>
        <th data-options="field:'productionprocess'" width="100">Production Process</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'north',border:true,title:'Add New Wagevariable',iconCls:'icon-more',hideCollapsedContent:false,collapsed:true,footer:'#ft2'" style="height:160px; padding:2px">
<form id="wagevariableFrm">
    <div id="container">
         <div id="body">
           <code>

                <div class="row">
                    <div class="col-sm-2 req-text">Name</div>
                    <div class="col-sm-4">
                    <input type="text" name="name" id="name" value=""/>
                    <input type="hidden" name="id" id="id" value=""/>
                    </div>
                    <div class="col-sm-2 req-text">Production Process </div>
                    <div class="col-sm-4">{!! Form::select('production_process_id', $productionprocess,'',array('id'=>'production_process_id')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-2">Sequence  </div>
                    <div class="col-sm-4"><input type="text" name="sort_id" id="sort_id" value=""/></div>
                </div>

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsWagevariable.submit()">Save</a>
         <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('wagevariableFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsWagevariable.remove()" >Delete</a>
    </div>

  </form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsWagevariableController.js"></script>
