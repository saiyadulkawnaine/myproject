
<div class="easyui-layout easyui-tabs animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;" id="utildepartmenttabs">
  <div title="Department" style="padding:2px">
    <div class="easyui-layout"  data-options="fit:true">
      <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="departmentTbl" style="width:100%;">
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
      {{-- <div data-options="region:'north',border:true,title:'Add New Department',iconCls:'icon-more',hideCollapsedContent:false,collapsed:true,footer:'#ft2'" style="height:350px; padding:2px">
        <form id="departmentFrm">
          <div id="container">
            <div id="body">
              <code>
                <div class="row">
                    <div class="col-sm-2 req-text">Name</div>
                    <div class="col-sm-3">
                    <input type="text" name="name" id="name" />
                    <input type="hidden" name="id" id="id" />
          
                    </div>
                    <div class="col-sm-2 req-text">Code </div>
                    <div class="col-sm-5"><input type="text" name="code" id="code" /></div>
                </div>
                <div class="row middle">
                  <div class="col-sm-2">Chief Name </div>
                  <div class="col-sm-3"><input type="text" name="chief_name" id="chief_name"  /></div>
                  <div class="col-sm-2">address </div>
                  <div class="col-sm-5"><input type="text" name="address" id="address"  /></div>
                </div>
                <div class="row middle">
                  <div class="col-sm-2 req-text">Sequence </div>
                  <div class="col-sm-3"><input type="text" name="sort_id" id="sort_id" class="number integer"/></div>
                </div>
              </code>
            </div>
          </div>
          <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsDepartment.submit()">Save</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('departmentFrm')" >Reset</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsDepartment.remove()" >Delete</a>
          </div>
        </form>
      </div> --}}
      <div data-options="region:'west',border:true,title:'Add New Department',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="width:450px; padding:2px">
        <form id="departmentFrm">
          <div id="container">
            <div id="body">
              <code>
                <div class="row">
                  <div class="col-sm-4 req-text">Name</div>
                  <div class="col-sm-8">
                  <input type="text" name="name" id="name" value=""/>
                  <input type="hidden" name="id" id="id" value=""/>
                  </div>
                </div>
                <div class="row middle">
                  <div class="col-sm-4 req-text">Code </div>
                  <div class="col-sm-8"><input type="text" name="code" id="code" value=""/></div>
                </div>
                <div class="row middle">
                  <div class="col-sm-4">Chief Name</div>
                  <div class="col-sm-8">
                    <input type="text" name="chief_name" id="chief_name" value=""/>
                  </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Address</div>
                    <div class="col-sm-8">
                      <input type="text" name="address" id="address" />
                    </div>
                </div>
                <div class="row middle">
                  <div class="col-sm-4">Sequence</div>
                  <div class="col-sm-8">
                    <input type="text" name="sort_id" id="sort_id" />
                  </div>
                </div>
              </code>
            </div>
          </div>
          <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsDepartment.submit()">Save</a>
              <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('departmentFrm')" >Reset</a>
            <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsDepartment.remove()" >Delete</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div title="Floor" style="padding:2px">
    <div class="easyui-layout"  data-options="fit:true">
      <div data-options="region:'west',border:true,title:'Add Department Floor',footer:'#utildepartmentfloorft'" style="width:450px; padding:2px">
        <form id="departmentfloorFrm">
          <input type="hidden" name="department_id" id="department_id" value=""/>
        </form>
        <table id="departmentfloorTbl">
        </table>
        <div id="utildepartmentfloorft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
          <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsDepartmentFloor.submit()">Save</a>
        </div>
      </div>
      <div data-options="region:'center',border:true,title:'Taged Floor'" style="padding:2px">
        <table id="departmentfloorsavedTbl">
        </table>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAllDepartmentController.js"></script>
<script>
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>
  