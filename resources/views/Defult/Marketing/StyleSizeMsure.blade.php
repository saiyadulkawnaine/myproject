<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="stylesizemsureTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="80">ID</th>
        <th data-options="field:'style'" width="100">Style</th>
        <th data-options="field:'stylesize'" width="100">Style Size</th>
        <th data-options="field:'stylegmts'" width="100">Style GMT</th>
        <th data-options="field:'msurepoint'" width="100">Measure Point</th>
        <th data-options="field:'uom'" width="100">Uom</th>
        <th data-options="field:'size'" width="100">Size</th>
        <th data-options="field:'tollerance'" width="100">Tollerance</th>
        <th data-options="field:'msurevalue'" width="100">Measure Value</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'north',border:true,title:'Add New StyleSizeMsure',iconCls:'icon-more',hideCollapsedContent:false,collapsed:true,footer:'#ft2'" style="height:160px; padding:2px">
<form id="stylesizemsureFrm">
    <div id="container">
         <div id="body">
           <code>

                <div class="row">
                    <div class="col-sm-2 req-text">Style Size</div>
                    <div class="col-sm-4">
                    {!! Form::select('style_size_id', $stylesize,'',array('id'=>'style_size_id')) !!}
                    <input type="hidden" name="id" id="id" value=""/>
                    </div>
                    <div class="col-sm-2 req-text">Style </div>
                    <div class="col-sm-4">{!! Form::select('style_id', $style,'',array('id'=>'style_id')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-2 req-text">Style GMT </div>
                    <div class="col-sm-4">{!! Form::select('style_gmt_id', $stylegmt,'',array('id'=>'style_gmt_id')) !!}</div>
                    <div class="col-sm-2 req-text">Measure Point </div>
                    <div class="col-sm-4"><input type="text" name="msure_point" id="msure_point" value=""/></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-2 req-text">Uom </div>
                    <div class="col-sm-4">{!! Form::select('uom_id', $uom,'',array('id'=>'uom_id')) !!}</div>
                    <div class="col-sm-2 req-text">Size </div>
                    <div class="col-sm-4">{!! Form::select('size_id', $size,'',array('id'=>'size_id')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-2 req-text">Tollerance </div>
                    <div class="col-sm-4"><input type="text" name="tollerance" id="tollerance" value=""/></div>
                    <div class="col-sm-2 req-text">Measure Value </div>
                    <div class="col-sm-4"><input type="text" name="msure_value" id="msure_value" value=""/></div>
                </div>

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsStyleSizeMsure.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('stylesizemsureFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsStyleSizeMsure.remove()" >Delete</a>
    </div>

  </form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsStyleSizeMsureController.js"></script>
