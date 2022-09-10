<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="stylefabricationTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="80">ID</th>
        <th data-options="field:'style'" width="100">Style</th>
        <th data-options="field:'stylegmt'" width="100">Style GMT</th>
        <th data-options="field:'fabricnature'" width="100">Fabric Nature</th>
        <th data-options="field:'gmtspart'" width="100">Gmtspart</th>
        <th data-options="field:'autoyarn'" width="100">Autoyarn</th>
        <th data-options="field:'fabriclook'" width="100">Fabric Look</th>
        <th data-options="field:'materialsource'" width="100">Material Source</th>
        <th data-options="field:'yarncount'" width="100">Yarncount</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'north',border:true,title:'Add New StyleFabrication',iconCls:'icon-more',hideCollapsedContent:false,collapsed:true,footer:'#ft2'" style="height:160px; padding:2px">
<form id="stylefabricationFrm">
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
                 <div class="col-sm-2 req-text">Fabric Nature</div>
                 <div class="col-sm-4">{!! Form::select('fabric_nature_id', $fabricnature,'',array('id'=>'fabric_nature_id')) !!}</div>
                 <div class="col-sm-2 req-text">Gmtspart </div>
                 <div class="col-sm-4">{!! Form::select('gmtspart_id', $gmtspart,'',array('id'=>'gmtspart_id')) !!}</div>
             </div>
             <div class="row middle">
                 <div class="col-sm-2 req-text">Autoyarn</div>
                 <div class="col-sm-4">{!! Form::select('autoyarn_id', $autoyarn,'',array('id'=>'autoyarn_id')) !!}</div>
                 <div class="col-sm-2 req-text">Fabric Look </div>
                 <div class="col-sm-4">{!! Form::select('fabric_look_id', $fabriclooks,'',array('id'=>'fabric_look_id')) !!}</div>
             </div>
             <div class="row middle">
                 <div class="col-sm-2 req-text">Material Source</div>
                 <div class="col-sm-4">{!! Form::select('material_source_id', $materialsourcing,'',array('id'=>'material_source_id')) !!}</div>
                 <div class="col-sm-2 req-text">Yarncount </div>
                 <div class="col-sm-4">{!! Form::select('yarncount_id', $yarncount,'',array('id'=>'yarncount_id')) !!}</div>
             </div>
             <div class="row middle">
                 <div class="col-sm-2">Is Stripe </div>
                 <div class="col-sm-4"><input type="text" name="is_stripe" id="is_stripe" value=""/></div>
                 <div class="col-sm-2">Image Src  </div>
                 <div class="col-sm-4"><input type="text" name="image_src" id="image_src" value=""/></div>
             </div>

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsStyleFabrication.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('stylefabricationFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsStyleFabrication.remove()" >Delete</a>
    </div>

  </form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsStyleFabricationController.js"></script>
