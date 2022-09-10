<div class="easyui-layout"  data-options="fit:true">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="budgetfabricprodTbl" style="width:100%">
<thead>
    <tr>
      <th data-options="field:'id'" width="80">ID</th>
      <th data-options="field:'budget'" width="70">MKT Cost</th>
      <th data-options="field:'budgetfabric'" width="70">Mkt Cost Fabric</th>
      <th data-options="field:'process'" width="70">Process</th>
      <th data-options="field:'cons'" width="70">Cons</th>
      <th data-options="field:'rate'" width="70">Rate</th>
      <th data-options="field:'amount'" width="70">Amount</th>
      <th data-options="field:'amount',formatter='formatDetail'" width="70">Add Cons</th>

   </tr>
</thead>
</table>
</div>
<div data-options="region:'west',border:true,title:'Add New BudgetFabricProd',footer:'#ft2'" style="width:350px; padding:2px">
<form id="budgetfabricprodFrm">
    <div id="container">
         <div id="body">
           <code>

             <div class="row">
                 <div class="col-sm-4 req-text">Mkt Cost </div>
                 <div class="col-sm-8">
                 {!! Form::select('budget_id', $budget,'',array('id'=>'budget_id')) !!}
                 <input type="hidden" name="id" id="id" value=""/>
                 </div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4 req-text">Mkt Cost Fabric  </div>
                 <div class="col-sm-8">{!! Form::select('budget_fabric_id', $budgetfabric,'',array('id'=>'budget_fabric_id')) !!}</div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4 req-text">Process  </div>
                 <div class="col-sm-8"><input type="text" name="process_id" id="process_id" /></div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4 req-text">Cons  </div>
                 <div class="col-sm-8"><input type="text" name="cons" id="cons" /></div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4 req-text">Rate  </div>
                 <div class="col-sm-8"><input type="text" name="rate" id="rate" /></div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4">Amount </div>
                 <div class="col-sm-8"><input type="text" name="amount" id="amount" /></div>
             </div>

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetFabricProd.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('budgetfabricprodFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsBudgetFabricProd.remove()" >Delete</a>
    </div>

  </form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsBudgetFabricProdController.js"></script>
