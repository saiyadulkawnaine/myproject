<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="productdepartmentTbl" style="width:100%">
<thead>
    <tr>
      <th data-options="field:'id'" width="80">ID</th>
      <th data-options="field:'deptcategory'" width="100">Department Category</th>
      <th data-options="field:'departmentname'" width="100">Department Name</th>
      <th data-options="field:'from_age'" width="80">From Age</th>
      <th data-options="field:'to_age'" width="80">To Age</th>
      <th data-options="field:'buyer'" width="100">Buyer</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'north',border:true,title:'Add New Productdepartment',iconCls:'icon-more',hideCollapsedContent:false,collapsed:true,footer:'#ft2'" style="height:160px; padding:2px">
<form id="productdepartmentFrm">
    <div id="container">
         <div id="body">
           <code>

                <div class="row">
                    <div class="col-sm-2 req-text">Dept. Category :</div>
                    <div class="col-sm-4">
                    {!! Form::select('dept_category_id', $deptcategory,'',array('id'=>'dept_category_id')) !!}
                    <input type="hidden" name="id" id="id" value=""/>
                    </div>
                    <div class="col-sm-2 req-text">Department Name</div>
                    <div class="col-sm-4"><input type="text" name="department_name" id="department_name" value=""/></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-2 req-text">Form Age</div>
                    <div class="col-sm-4"><input type="text" name="from_age" id="from_age" value=""/></div>
                    <div class="col-sm-2">To Age :</div>
                    <div class="col-sm-4"><input type="text" name="to_age" id="to_age" value="" /></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-2 req-text">Buyer</div>
                    <div class="col-sm-4">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}</div>
                    <div class="col-sm-2">Sequence</div>
                    <div class="col-sm-4"><input type="text" name="sort_id" id="sort_id" value=""/></div>
                </div>

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProductdepartment.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('productdepartmentFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProductdepartment.remove()" >Delete</a>
    </div>

  </form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsProductdepartmentController.js"></script>
