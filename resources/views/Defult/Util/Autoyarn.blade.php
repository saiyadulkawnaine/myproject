<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'west'" style="width:350px;padding:3px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'north',split:true, title:'Fabric',footer:'#ft2'" style="height:300px;padding:3px">
                <form id="autoyarnFrm">
                <div id="container">
                <div id="body">
                <code>
                <div class="row">
                <div class="col-sm-4 req-text">Fabric Nature</div>
                <div class="col-sm-8">
                {!! Form::select('fabric_nature_id', $fabricnature,'',array('id'=>'fabric_nature_id')) !!}
                <input type="hidden" name="id" id="id" value=""/>
                </div>
                </div>
                <div class="row middle">
                <div class="col-sm-4">Fabric Type </div>
                <div class="col-sm-8">
                <input type="text" name="fabric_type" id="fabric_type" value=""/>
                </div>
                </div>
                <div class="row middle" style="display:none">
                <div class="col-sm-4">Item Class </div>
                <div class="col-sm-8">{!! Form::select('itemclass_id', $itemclass,'',array('id'=>'itemclass_id')) !!}</div>
                </div>
                <div class="row middle">
                <div class="col-sm-4">Construction </div>
                <div class="col-sm-8">{!! Form::select('construction_id', $construction,'',array('id'=>'construction_id')) !!}</div>
                </div>
                <div class="row middle">
                <div class="col-sm-4">Machine Dia  </div>
                <div class="col-sm-8"><input type="text" name="machine_dia" id="machine_dia" class="number integer"/></div>
                </div>
                <div class="row middle">
                <div class="col-sm-4">Fabric Dia  </div>
                <div class="col-sm-8"><input type="text" name="fabric_dia" id="fabric_dia" class="number integer"/></div>
                </div>
                <div class="row middle">
                <div class="col-sm-4">Machine GG  </div>
                <div class="col-sm-8"><input type="text" name="machine_gg" id="machine_gg" class="number integer"/></div>
                </div>
                <div class="row middle">
                <div class="col-sm-4">Stitch Length  </div>
                <div class="col-sm-8"><input type="text" name="stitch_length" id="stitch_length"/></div>
                </div>
                <div class="row middle">
                <div class="col-sm-4">GSM  </div>
                <div class="col-sm-8"><input type="text" name="gsm" id="gsm" class="number integer"/></div>
                </div>
                <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAutoyarn.submit()">Save</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('autoyarnFrm')" >Reset</a>
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAutoyarn.remove()" >Delete</a>
                </div>
                </code>
                </div>
                </div>
                </form>
            </div>
            <div data-options="region:'center',split:true, title:'Composition',footer:'#ft3'" style="padding:3px">
                <form id="autoyarnratioFrm">
                <div id="container">
                <div id="body">
                <code>
                <div class="row">

                <div class="col-sm-4">Composition: </div>
                <div class="col-sm-8">
                {!! Form::select('composition_id', $composition,'',array('id'=>'composition_id')) !!}
                <input type="hidden" name="autoyarn_id" id="autoyarn_id" value=""/>
                <input type="hidden" name="id" id="id" value=""/>
                </div>
                </div>
                <div class="row middle">
                <div class="col-sm-4">Yarn Count: </div>
                <div class="col-sm-8">{!! Form::select('yarncount_id', $yarncount,'',array('id'=>'yarncount_id')) !!}</div>
                </div>
                <div class="row middle">
                <div class="col-sm-4">Ratio</div>
                <div class="col-sm-8"><input type="text" name="ratio" id="ratio" class="number integer"/></div>

                </div>
                </code>
                </div>
                </div>
                <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAutoyarnratio.submit()">Save</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('autoyarnratioFrm')" >Reset</a>
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAutoyarnratio.remove()" >Delete</a>
                </div>

                </form>
            </div>
        </div>
    </div>
    <div data-options="region:'center'" style="padding:3px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center', title:'Fabrication List'" style="padding:3px">
                <table id="autoyarnTbl" style="width:100%">
                <thead>
                <tr>
                <th data-options="field:'id'" width="80">ID</th>
                <th data-options="field:'fabricnature'" width="100">Fabric Nature</th>
                <th data-options="field:'fabrictype'" width="100">Fabric Type</th>
                <th data-options="field:'construction'" width="100">Construction</th>
                <th data-options="field:'composition'" width="100">Composition</th>
                </tr>
                </thead>
                </table>
            </div>
            <div data-options="region:'south', title:'Composition List'" style="height:200px;padding:3px">
                <table id="autoyarnratioTbl" style="width:100%">
                <thead>
                <tr>
                <th data-options="field:'id'" width="80">ID</th>
                <th data-options="field:'composition'" width="100">Composition</th>
                <th data-options="field:'ratio',align:'right'" width="100">Ratio</th>
                </tr>
                </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAutoyarnratioController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAutoyarnController.js"></script>
<script>
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>
