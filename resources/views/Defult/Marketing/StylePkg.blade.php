<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="stylepkgTbl" style="width:100%">
<thead>
    <tr>
      <th data-options="field:'id'" width="80">ID</th>
      <th data-options="field:'style'" width="100">Style</th>
      <th data-options="field:'itemclass'" width="100">Item Class</th>
      <th data-options="field:'spec'" width="100">Spec</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'north',border:true,title:'Add New StylePkg',iconCls:'icon-more',hideCollapsedContent:false,collapsed:true,footer:'#ft2'" style="height:160px; padding:2px">
<form id="stylepkgFrm">
    <div id="container">
         <div id="body">
           <code>

             <div class="row">
                 <div class="col-sm-2 req-text">Style</div>
                 <div class="col-sm-4">
                 {!! Form::select('style_id', $style,'',array('id'=>'style_id')) !!}
                 <input type="hidden" name="id" id="id" value=""/>
                 </div>
                 <div class="col-sm-2 req-text">Item Class </div>
                 <div class="col-sm-4">{!! Form::select('itemclass_id', $itemclass,'',array('id'=>'itemclass_id')) !!}</div>
             </div>
             <div class="row middle">
                 <div class="col-sm-2 req-text">Spec </div>
                 <div class="col-sm-4"><input type="text" name="spec" id="spec" value=""/></div>
                 <div class="col-sm-2">Quantity  </div>
                 <div class="col-sm-4"><input type="text" name="qty" id="qty" value=""/></div>
             </div>

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsStylePkg.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('stylepkgFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsStylePkg.remove()" >Delete</a>
    </div>

  </form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsStylePkgController.js"></script>
