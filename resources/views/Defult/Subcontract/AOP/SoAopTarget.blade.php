<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="soaoptargetTbl" style="width:100%">
  <thead>
      <tr>
          <th data-options="field:'id'" width="50">ID</th>
          <th data-options="field:'buyer_name'" width="100">Customer</th>
          <th data-options="field:'company_name'" width="100">Company</th>
          <th data-options="field:'target_date'" width="100">Entry Date</th>
          <th data-options="field:'qty'" width="80">Aop Qty</th>
          <th data-options="field:'rate'" width="70">Rate/Unit</th>
          <th data-options="field:'execute_month'" width="100">Prod.month</th>
          <th data-options="field:'teammember_id'" width="100">Teammember</th>
          <th data-options="field:'remarks'" width="100">Remarks</th>
     </tr>
  </thead>
</table>

</div>
<div data-options="region:'west',border:true,title:'Add New Aop Subcon Sales Target',footer:'#ft2'" style="width:350px; padding:2px">
<form id="soaoptargetFrm">
    <div id="container">
         <div id="body">
           <code>
                        <div class="row middle">
                        <div class="col-sm-4 ">Customer</div>
                        <div class="col-sm-8">
                          {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                        <input type="hidden" name="id" id="id" />
                        </div>
                        </div>                               
                        <div class="row middle">
                        <div class="col-sm-4 req-text">Company</div>
                        <div class="col-sm-8">
                          {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                        </div>
                        </div>
                        <div class="row middle">
                        <div class="col-sm-4 req-text">Team Member</div>
                        <div class="col-sm-8">
                        {!! Form::select('teammember_id', $teammember,'',array('id'=>'teammember_id')) !!}
                        </div>
                        </div>
                        <div class="row middle">
                        <div class="col-sm-4">Entry Date</div>
                        <div class="col-sm-8"><input type="text" name="target_date" id="target_date" class="datepicker" placeholder="yy-mm-dd"/>
                        </div>
                        </div>
                        <div class="row middle">
                        <div class="col-sm-4 req-text">Aop Qty</div>
                        <div class="col-sm-8"><input type="text" name="qty" id="qty" class="number integer" onchange="MsSoAopTarget.calculate()" />
                        </div>
                        </div>
                        <div class="row middle">
                        <div class="col-sm-4 req-text">Rate/Unit</div>
                        <div class="col-sm-8"><input type="text" name="rate" id="rate" class="number integer" onchange="MsSoAopTarget.calculate()" />
                        </div>
                        </div>  
                        <div class="row middle">
                        <div class="col-sm-4">Amount</div>
                        <div class="col-sm-8"><input type="text" name="amount" id="amount" class="number integer" />
                        </div>
                        </div>  
                        <div class="row middle">
                        <div class="col-sm-4 req-text">Prod.Month</div>
                      <div class="col-sm-8"><input type="text" name="execute_month" id="execute_month" class="datepicker" placeholder="yy-mm-dd"/>
                       </div>
                       </div>                                                                                                  
                       <div class="row middle">
                       <div class="col-sm-4 ">Remarks</div>
                       <div class="col-sm-8"><textarea name="remarks" id="remarks" rows="3"></textarea></div>
                       </div>            
          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSoAopTarget.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('soaoptargetFrm')" >Reset</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSoAopTarget.remove()" >Delete</a>
    </div>
  </form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/AOP/MsSoAopTargetController.js"></script>
<script>
  $(".datepicker" ).datepicker({
dateFormat: 'yy-mm-dd',
changeMonth: true,
changeYear: true
});
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
 $('#soaoptargetFrm [id="buyer_id"]').combobox();

</script>
