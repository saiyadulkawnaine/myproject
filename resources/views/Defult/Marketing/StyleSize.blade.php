<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="stylesizeTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="80">ID</th>
        <th data-options="field:'style'" width="100">Style</th>
        <th data-options="field:'stylegmts'" width="100">Style GMT</th>
        <th data-options="field:'size'" width="100">Size</th>
        <th data-options="field:'sort'" width="100">Sequence</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'north',border:true,title:'Add New StyleSize',iconCls:'icon-more',hideCollapsedContent:false,collapsed:true,footer:'#ft2'" style="height:160px; padding:2px">
<form id="stylesizeFrm">
    <div id="container">
         <div id="body">
           <code>

             <div class="row">
                 <div class="col-sm-2 req-text">Style</div>
                 <div class="col-sm-4">
                 {!! Form::select('style_id', $style,'',array('id'=>'style_id')) !!}
                 <input type="hidden" name="id" id="id" value=""/>
                 </div>
                 <div class="col-sm-2 req-text">Style GMT </div>
                 <div class="col-sm-4">{!! Form::select('style_gmt_id', $stylegmts,'',array('id'=>'style_gmt_id')) !!}</div>
             </div>
             <div class="row middle">
                 <div class="col-sm-2 req-text">Size</div>
                 <div class="col-sm-4">{!! Form::select('size_id', $size,'',array('id'=>'size_id')) !!}</div>
                 <div class="col-sm-2">Size Code  </div>
                 <div class="col-sm-4">{!! Form::select('size_code', $sizecode,'',array('id'=>'size_code')) !!}</div>
             </div>
             <div class="row middle">
                 <div class="col-sm-2">Sequence  </div>
                 <div class="col-sm-4"><input type="text" name="sort_id" id="sort_id" value=""/></div>
             </div>

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsStyleSize.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('stylesizeFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsStyleSize.remove()" >Delete</a>
    </div>

  </form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsStyleSizeController.js"></script>