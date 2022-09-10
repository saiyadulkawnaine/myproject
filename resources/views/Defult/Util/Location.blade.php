<div class="easyui-layout animated rollIn boxshadow"  data-options="fit:true">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="locationTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="80">ID</th>
        <th data-options="field:'name'" width="100">Name</th>
        <th data-options="field:'code'" width="100">Code</th>
        <th data-options="field:'chief_name'" width="100">Chief Name</th>
   </tr>
</thead>
</table>
</div>

<div data-options="region:'west',border:true,title:'Add New Location',footer:'#ft2'" style="width: 350px; padding:2px">
<form id="locationFrm">
<div id="container">
<div id="body">
<code>

<div class="row">
<div class="col-sm-4 req-text">Name</div>
<div class="col-sm-8">
<input type="text" name="name" id="name" />
<input type="hidden" name="id" id="id" />
</div>
</div>
<div class="row middle">
<div class="col-sm-4 req-text">Code </div>
<div class="col-sm-8"><input type="text" name="code" id="code" /></div>
</div>
<div class="row middle">
<div class="col-sm-4">Chief Name </div>
<div class="col-sm-8"><input type="text" name="chief_name" id="chief_name"  /></div>
</div>
<div class="row middle">
<div class="col-sm-4">address </div>
<div class="col-sm-8"><input type="text" name="address" id="address"  /></div>
</div>
<div class="row middle">
<div class="col-sm-4">Sequence  </div>
<div class="col-sm-8"><input type="text" name="sort_id" id="sort_id" class="number integer"/></div>
</div>

</code>
</div>
</div>
<div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
<a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsLocation.submit()">Save</a>
 <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('locationFrm')" >Reset</a>
<a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsLocation.remove()" >Delete</a>
</div>
</form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsLocationController.js"></script>
<script>
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>
