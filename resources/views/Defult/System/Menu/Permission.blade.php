<div class="easyui-tabs" style="width:100%;height: 100%;border:none" id="permissionTabs">
 <div title="Permission" style="padding: 2px;">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="permissionTbl">
     <thead>
      <tr>
       <th data-options="field:'id'" width="80">ID</th>
       <th data-options="field:'name'" width="100">Name</th>
       <th data-options="field:'slug'" width="100">Slug</th>
       <th data-options="field:'description'" width="150">Desctiption</th>

      </tr>
     </thead>
    </table>
   </div>
   <div data-options="region:'west',border:true,title:'Add New Permission',footer:'#ft2'"
    style="width:400px; padding:2px">
    <form id="premissionFrm">
     <div id="container">
      <div id="body">
       <code>
        <div class="row middle">
           <div class="col-sm-5 req-text">Name</div>
           <div class="col-sm-7">
            <input type="text" name="name" id="name" value="" />
            <input type="hidden" name="id" id="id" value="" />
            <input type="hidden" name="model" id="model" value="Permission" />
           </div>
          </div>
          <div class="row middle">
           <div class="col-sm-5 req-text">Slug </div>
           <div class="col-sm-7">
            <input type="text" name="slug" id="slug" value="" />
           </div>
          </div>
          <div class="row middle">
           <div class="col-sm-5">Description</div>
           <div class="col-sm-7">
            <input type="text" name="description" id="description" value="" />
           </div>
          </div>
       </code>
      </div>
     </div>
     <div id="ft2" style="padding:0px 0px; text-align:right; background:#CCC;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsPermission.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('premissionFrm')">Reset</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsPermission.remove()">Delete</a>
     </div>
    </form>
   </div>
  </div>
 </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/menu/MsPermissionController.js"></script>