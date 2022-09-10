<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="cadconTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="80">ID</th>
        <th data-options="field:'name'" width="100">Name</th>
        <th data-options="field:'code'" width="100">Code</th>
        <th data-options="field:'resource'" width="100">Resource</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'west',border:true,title:'Add New CadCon',footer:'#ft2'" style="width:350px; padding:2px">
<form id="cadconFrm">
    <div id="container">
         <div id="body">
           <code>

                <div class="row">
                    <div class="col-sm-4 req-text">Cad</div>
                    <div class="col-sm-8">
                    {!! Form::select('cad_id', $cad,'',array('id'=>'cad_id')) !!}
                    <input type="hidden" name="id" id="id" />
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Style Fabrication </div>
                    <div class="col-sm-8">{!! Form::select('style_fabriction_id', $stylefabrication,'',array('id'=>'style_fabriction_id')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Style Size</div>
                    <div class="col-sm-8">{!! Form::select('style_size_id', $stylesize,'',array('id'=>'style_size_id')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Cons </div>
                    <div class="col-sm-8"><input type="text" name="cons" id="cons" /></div>
                </div>

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCadCon.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('cadconFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsCadCon.remove()" >Delete</a>
    </div>

  </form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsCadConController.js"></script>
