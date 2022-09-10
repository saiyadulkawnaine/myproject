<div class="easyui-tabs" style="width:100%;height:100%; border:none">
  <div title="Supplier Setting" style="padding:1px" data-options="selected:true">
    <div class="easyui-layout" data-options="fit:true">
      <div
        data-options="region:'west',split:true, title:'Supplier Setting',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'"
        style="width:400px;padding:3px">

        <div id="container">
          <div id="body">
            <code>
          <form id="suppliersettingFrm">
            <div class="row middle">
            <div class="col-sm-4 req-text">Supplier</div>
            <div class="col-sm-8">
              {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
              <input type="hidden" name="id" id="id" value="" />
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-4 req-text">Payment Blocked</div>
            <div class="col-sm-8">
              {!! Form::select('payment_blocked_id', $yesno,'',array('id'=>'payment_blocked_id')) !!}
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-4 req-text">Remarks</div>
            <div class="col-sm-8">
              <textarea name="remarks" id="remarks"></textarea>
            </div>
            </div>

              <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
              <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSupplierSetting.submit()">Save</a>
              <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('suppliersettingFrm')" >Reset</a>
              <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSupplierSetting.remove()" >Delete</a>
             </div>
            </form>
            </code>
          </div>
        </div>
      </div>
      <div data-options="region:'center',split:true, title:'List'">
        <table id="suppliersettingTbl" style="width:790px">
          <thead>
            <tr>
              <th data-options="field:'id'" width="50">ID</th>
              <th data-options="field:'supplier_name'" width="80">Supplier</th>
              <th data-options="field:'payment_blocked_id'" width="150">Payment Blocked</th>
              <th data-options="field:'remarks'" width="150">Remarks</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsSupplierSettingController.js"></script>
<script>
  $(".datepicker" ).datepicker({
  	dateFormat: 'yy-mm-dd',
  	changeMonth: true,
  	changeYear: true
  });
   $('#suppliersettingFrm [id="supplier_id"]').combobox();
</script>