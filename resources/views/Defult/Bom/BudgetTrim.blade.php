<div class="easyui-layout"  data-options="fit:true">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="budgettrimTbl" style="width:100%">
<thead>
    <tr>
      <th data-options="field:'id'" width="80">ID</th>
      <th data-options="field:'budget'" width="70">MKT Cost</th>
      <th data-options="field:'item_group_id'" width="70">Item Group</th>
      <th data-options="field:'description'" width="70">Description</th>
      <th data-options="field:'specification'" width="70">Specification</th>
      <th data-options="field:'item_size'" width="70">Item Size</th>
      <th data-options="field:'sup_ref'" width="70">Sup Ref</th>
      <th data-options="field:'uom'" width="70">Uom</th>
      <th data-options="field:'cons'" width="70">Cons</th>
      <th data-options="field:'rate'" width="70">Rate</th>
      <th data-options="field:'amount'" width="70">Amount</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'west',border:true,title:'Add New BudgetTrim',footer:'#ft2'" style="width:350px; padding:2px">
<form id="budgettrimFrm">
    <div id="container">
         <div id="body">
           <code>

             <div class="row">
                 <div class="col-sm-4 req-text">Mkt Cost</div>
                 <div class="col-sm-8">
                 {!! Form::select('budget_id', $budget,'',array('id'=>'budget_id')) !!}
                 <input type="hidden" name="id" id="id" value=""/>
                 </div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4 req-text">Item Group </div>
                 <div class="col-sm-8"><input type="text" name="item_group_id" id="item_group_id" /></div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4 req-text">Description </div>
                 <div class="col-sm-8"><input type="text" name="description" id="description" /></div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4 req-text">Specification </div>
                 <div class="col-sm-8"><input type="text" name="specification" id="specification" /></div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4 req-text">Item Size </div>
                 <div class="col-sm-8"><input type="text" name="item_size" id="item_size" /></div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4 req-text">Sup Ref </div>
                 <div class="col-sm-8"><input type="text" name="sup_ref" id="sup_ref" /></div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4 req-text">UOM </div>
                 <div class="col-sm-8">{!! Form::select('uom_id', $uom,'',array('id'=>'uom_id')) !!}</div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4">Cons </div>
                 <div class="col-sm-8"><input type="text" name="cons" id="cons" /></div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4">Rate </div>
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
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetTrim.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('budgettrimFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsBudgetTrim.remove()" >Delete</a>
    </div>

  </form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsBudgetTrimController.js"></script>
