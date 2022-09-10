<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="uomconversionTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="80">ID</th>
         <th data-options="field:'uom_name'" width="100">UOM</th>
         <th data-options="field:'uom_to_name'" width="100">UOM To</th>
        <th data-options="field:'coversion_factor'" width="100">Coversion Factor</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'west',border:true,title:'Add New Uomconversion',footer:'#ft2'" style="width: 350px; padding:2px">
<form id="uomconversionFrm">
    <div id="container">
         <div id="body">
           <code>

                <div class="row">
                    <div class="col-sm-4 req-text">Uom</div>
                    <div class="col-sm-8">
                    {!! Form::select('uom_id', $uom,'',array('id'=>'uom_id')) !!}
                    <input type="hidden" name="id" id="id" value=""/>
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Uom To </div>
                    <div class="col-sm-8">{!! Form::select('uom_to', $uom,'',array('id'=>'uom_to')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Coversion Factor </div>
                    <div class="col-sm-8"><input type="text" name="coversion_factor" id="coversion_factor" value="" /></div>
                </div>

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsUomconversion.submit()">Save</a>
         <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('roleFrm')" >Reset</a>

        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsUomconversion.remove()" >Delete</a>
    </div>

  </form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsUomconversionController.js"></script>
<script>
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>
