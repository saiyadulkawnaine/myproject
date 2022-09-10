
<div class="easyui-accordion" data-options="multiple:false" style="width:100%;" id="purordertrimAccordion">
    <div title="Master" data-options="iconCls:'icon-ok'" style="padding:1px;height:520px">
<div class="easyui-layout"  data-options="fit:true">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="purordertrimTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="80">ID</th>
         <th data-options="field:'pur_order_no'" width="100">Order No</th>
        <th data-options="field:'company'" width="100">Company</th>
        <th data-options="field:'source'" width="100">Source</th>
        <th data-options="field:'delv_start_date'" width="100">Delivery Start</th>
        <th data-options="field:'delv_end_date'" width="100">Delivery End</th>
        <th data-options="field:'paymode'" width="100">Pay Mode</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'west',border:true,title:'Purchase Order',iconCls:'icon-more',hideCollapsedContent:false,footer:'#ft2'" style="width:350px; padding:2px">
<form id="purordertrimFrm">
    <div id="container">
         <div id="body">
           <code>
              <div class="row">
                 <div class="col-sm-4">Order No</div>
                  <div class="col-sm-8">
                 <input type="text" name="pur_order_no" id="pur_order_no" class="integer number" readonly placeholder="display"/>
                  </div>
                </div>
                <div class="row middle">
                  <div class="col-sm-4">Order Date</div>
                  <div class="col-sm-8"><input type="text" name="pur_order_date" id="pur_order_date" class="datepicker"/></div>
                </div>
                <div class="row middle">
                  <div class="col-sm-4 req-text">Company</div>
                  <div class="col-sm-8">
                  {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                  <input type="hidden" name="id" id="id" value=""/>
                  <input type="hidden" name="order_type_id" id="order_type_id" value="{{ $order_type_id }}"/>
                  </div>
                </div>
                <div class="row middle">
                  <div class="col-sm-4 req-text" >Category</div>
                  <div class="col-sm-8">{!! Form::select('itemcategory_id', $itemcategory,'',array('id'=>'itemcategory_id')) !!}</div>
                </div>
                <div class="row middle">   
                  <div class="col-sm-4 req-text">Source</div>
                  <div class="col-sm-8">{!! Form::select('source_id', $source,'',array('id'=>'source_id')) !!}</div>
                </div>
                <div class="row middle">
                  <div class="col-sm-4 req-text">Basis</div>
                  <div class="col-sm-8">{!! Form::select('basis_id', $basis,'',array('id'=>'basis_id')) !!}</div>
                </div>
                 <div class="row middle">
                 
                  <div class="col-sm-4 req-text">Supplier</div>
                  <div class="col-sm-8">{!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id')) !!}</div>
                  </div>
                <div class="row middle">
                  <div class="col-sm-4 req-text">Pay Mode</div>
                  <div class="col-sm-8">{!! Form::select('pay_mode', $paymode,'',array('id'=>'pay_mode')) !!}</div>
                </div>
                <div class="row middle">
                  <div class="col-sm-4 req-text">Currency</div>
                  <div class="col-sm-8">{!! Form::select('currency_id', $currency,'',array('id'=>'currency_id')) !!}</div>
                  </div>
                <div class="row middle">
                  <div class="col-sm-4 req-text">Exchange Rate  </div>
                  <div class="col-sm-8"><input type="text" name="exch_rate" id="exch_rate" class="integer number"/></div>
                </div>
                <div class="row middle">
                  <div class="col-sm-4 req-text">Delivery Start  </div>
                  <div class="col-sm-8"><input type="text" name="delv_start_date" id="delv_start_date" class="datepicker"/></div>
                  </div>
                <div class="row middle">
                  <div class="col-sm-4 req-text">Delivery End  </div>
                  <div class="col-sm-8"><input type="text" name="delv_end_date" id="delv_end_date" class="datepicker"/></div>
                </div>
                <div class="row middle">
                  
                  <div class="col-sm-4">PI No.  </div>
                  <div class="col-sm-8"><input type="text" name="pi_no" id="pi_no" value=""/></div>
                  </div>
                <div class="row middle">
                  <div class="col-sm-4">PI Date  </div>
                  <div class="col-sm-8"><input type="text" name="pi_date" id="pi_date" class="datepicker"/></div>
                </div>
                <div class="row middle">
                  <div class="col-sm-4">Remarks  </div>
                  <div class="col-sm-8"><input type="text" name="remarks" id="remarks" value=""/></div>
                </div>

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPurOrderTrim.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('purordertrimFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPurOrderTrim.remove()" >Delete</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="pdf" onClick="MsPurOrderTrim.pdf()" >PDF</a>
    </div>

  </form>
</div>
</div>
</div>
<div title="Trims" style="overflow:auto;padding:1px;height:520px">
  <div class="easyui-layout"  data-options="fit:true" >
    <div data-options="region:'center',border:true,title:'List',footer:'#purtrimft'" style="padding:2px; ">
      <table id="purtrimTbl" style="width:1250px">
      <thead>
        <th data-options="field:'id',halign:'center'" width="50">ID</th>
        <th data-options="field:'style_ref',halign:'center'" width="100">Style Ref</th>
        <th data-options="field:'job_no',halign:'center'" width="60">Job No</th>
        <th data-options="field:'item_account',halign:'center'" width="150">Item Class</th>
        <th data-options="field:'description',halign:'center'" width="200">Description</th>
        <th data-options="field:'specification',halign:'center'" width="100">Specification </th>
        <th data-options="field:'item_size',halign:'center'" width="80">Item Size </th>
        <th data-options="field:'sup_ref',halign:'center'" width="100">Sup Ref  </th>
        <th data-options="field:'uom',halign:'center'" width="70">Uom</th>
        <th data-options="field:'cons',halign:'center'" width="50" align="right">BOM Qty</th>
        <th data-options="field:'rate',halign:'center'" width="50" align="right">Rate</th>
        <th data-options="field:'amount',halign:'center'" width="50" align="right" >Amount</th>
        <th data-options="field:'add_con',halign:'center'" formatter="MsPurTrim.formatQty" width="70">Click</th>
        <th data-options="field:'add_dtm',halign:'center'" formatter="MsPurTrim.deleteButton" width="70">Delete</th>
      </thead>
      </table>
      <div id="purtrimft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPurTrim.openConsWindow()">Import Trims</a>
      </div>
    </div>
  </div>
</div>
<div title="Terms & Conditions" style="overflow:auto;padding:1px;height:520px">
  @includeFirst(['Purchase.PurchaseTermsCondition', 'Defult.Purchase.PurchaseTermsCondition'])
</div>
</div>


<div id="importtrimWindow" class="easyui-window" title="Fabric Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,footer:'#budgettrimsearchTblft'" style="padding:2px">
            <table id="budgettrimsearchTbl" style="width:1250px">
                <thead>
                <th data-options="field:'id',halign:'center'" width="50">ID</th>
                <th data-options="field:'style_ref',halign:'center'" width="100">Style Ref</th>
                <th data-options="field:'job_no',halign:'center'" width="60">Job No</th>
                <th data-options="field:'item_account',halign:'center'" width="150">Item Class</th>
                <th data-options="field:'description',halign:'center'" width="200">Description</th>
                <th data-options="field:'specification',halign:'center'" width="100">Specification </th>
                <th data-options="field:'item_size',halign:'center'" width="80">Item Size </th>
                <th data-options="field:'sup_ref',halign:'center'" width="100">Sup Ref  </th>
                <th data-options="field:'uom',halign:'center'" width="70">Uom</th>
                <th data-options="field:'cons',halign:'center'" width="50" align="right">BOM Qty</th>
                <th data-options="field:'rate',halign:'center'" width="50" align="right">Rate</th>
                <th data-options="field:'amount',halign:'center'" width="50" align="right" >Amount</th>
                <th data-options="field:'balance',halign:'center'" width="50" align="right" >Balance Qty.</th>
                </thead>
            </table>
            <div id="budgettrimsearchTblft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" id="pdf" onClick="MsPurTrim.submit()" >Add More</a>
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" id="pdf" onClick="MsPurTrim.submitAndClose()" >Next</a>
            </div>
        </div>
        
            
        <div data-options="region:'west',border:true" style="padding:2px; width:350px">
        <form id="budgettrimsearchFrm">
            <div id="container">
                 <div id="body">
                   <code>
                   <div class="row ">
                         <div class="col-sm-4 req-text">Budget ID</div>
                         <div class="col-sm-8"> <input type="text" name="budget_id" id="budget_id" /> </div>
                     </div>

                     <div class="row middle">
                         <div class="col-sm-4 req-text">Job No</div>
                         <div class="col-sm-8">
                         <input type="text" name="job_no" id="job_no"/>
                         </div>
                     </div>
                     <div class="row middle">
                         <div class="col-sm-4 req-text">Company</div>
                         <div class="col-sm-8">{!! Form::select('company_id', $company,'',array('id'=>'company_id','disabled'=>'disabled')) !!}</div>
                     </div>
                  </code>
               </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" id="pdf" onClick="MsPurTrim.searchTrim()" >Search</a>
            </div>

          </form>
               
        </div>
    </div>
</div>


<div id="purtrimqtyWindow" class="easyui-window" title="Trim Qty Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,footer:'#purtrimqtyfooter'" style="padding:2px">
            <form id="purtrimqtyFrm">
              
            <code id="purtrimqtyscs" style="margin:0px">
            </code>
            </form>
        </div>
        <div id="purtrimqtyfooter" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPurTrimQty.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPurTrimQty.resetForm()" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPurTrimQty.remove()" >Delete</a>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsPurOrderTrimController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsPurTrimController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsPurTrimQtyController.js"></script>
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
</script>
