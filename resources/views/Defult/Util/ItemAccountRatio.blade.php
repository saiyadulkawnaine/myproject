<div class="easyui-layout "  data-options="fit:true">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="itemaccountratioTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="80">ID</th>

        <th data-options="field:'composition'" width="100">Composition</th>
        <th data-options="field:'ratio'" width="100">Ratio</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'north',border:true,title:'Add New ItemAccountRatio',iconCls:'icon-more',hideCollapsedContent:false,footer:'#ft3'" style="height:110px; padding:2px">
<form id="itemaccountratioFrm">
    <div id="container">
         <div id="body">
           <code>
                <div class="row">
                    <div class="col-sm-2">Composition  </div>
                    <div class="col-sm-4">
                    {!! Form::select('composition_id', $composition,'',array('id'=>'composition_id')) !!}
                    <input type="hidden" name="id" id="id" value=""/>
                    <input type="hidden" name="item_account_id" id="item_account_id" value=""/>
                    </div>
                    <div class="col-sm-2 req-text">Ratio </div>
                    <div class="col-sm-4">
                        <input type="text" name="ratio" id="ratio" value=""/>
                    </div>
                </div>
          </code>
       </div>
    </div>
    <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsItemAccountRatio.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('itemaccountratioFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsItemAccountRatio.remove()" >Delete</a>
    </div>

  </form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsItemAccountRatioController.js"></script>
