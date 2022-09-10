<div class="easyui-accordion" data-options="multiple:false" style="width:100%;" id="purorderchemAccordion">
    <div title="Master" data-options="iconCls:'icon-ok'" style="padding:1px;height:520px">
        <div class="easyui-layout"  data-options="fit:true" >
        <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="purorderchemTbl" style="width:100%">
        <thead>
            <tr>
                <th data-options="field:'id'" width="80">ID</th>
                <th data-options="field:'pur_order_no'" width="100">PO No</th>
                <th data-options="field:'company_code'" width="100">Company</th>
                <th data-options="field:'supplier_code'" width="100">Supplier</th>
                <th data-options="field:'paymode'" width="100">Pay Mode</th>
                <th data-options="field:'currency_code'" width="100">Currency</th>
                <th data-options="field:'exch_rate'" width="100">Exch. Rate</th>
            </tr>
        </thead>
        </table>
        </div>
        <div data-options="region:'west',border:true,title:'Purchase Order',iconCls:'icon-more',hideCollapsedContent:false,footer:'#ft2'" style="width:350px; padding:2px">
        <form id="purorderchemFrm">
            <div id="container">
                <div id="body">
                <code>
                    <div class="row">
                        <div class="col-sm-4">PO No</div>
                            <div class="col-sm-8">
                                <input type="text" name="pur_order_no" id="pur_order_no" class="integer number" readonly placeholder="display"/>
                            </div>
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
                        <div class="col-sm-4 req-text">Category</div>
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
                        <div class="col-sm-4 req-text" >Currency</div>
                        <div class="col-sm-8">{!! Form::select('currency_id', $currency,'',array('id'=>'currency_id')) !!}</div>
                        </div>
                        <div class="row middle">
                        <div class="col-sm-4 req-text" >Exch. Rate  </div>
                        <div class="col-sm-8"><input type="text" name="exch_rate" id="exch_rate" class="integer number"/></div>
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
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPurOrderChem.submit()">Save</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('purorderchemFrm')" >Reset</a>
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPurOrderChem.remove()" >Delete</a>
            </div>
        
        </form>
        </div>
        </div>
    </div>

    <div title="Chemical" style="overflow:auto;padding:1px;height:520px">
        <div class="easyui-layout"  data-options="fit:true" >
          <div data-options="region:'west',border:true,title:'From',footer:'#ft3'" style=" width:350px;padding:2px; ">
            <form id="purchemFrm">
        <div id="container">
             <div id="body">
               <code>
                 <div class="row">
                     <div class="col-sm-4">Chemical</div>
                      <div class="col-sm-8">
                     <input type="text" name="item_description" id="item_description" readonly placeholder="display"/>
                     <input type="hidden" name="item_account_id" id="item_account_id" class="integer number" readonly placeholder="display"/>
                      <input type="hidden" name="purchase_order_id" id="purchase_order_id" class="integer number" readonly placeholder="display"/>
                       <input type="hidden" name="id" id="id" class="integer number" readonly placeholder="display"/>   
                      </div>
                      </div>
                    <div class="row middle">
                      <div class="col-sm-4 req-text">UOM</div>
                      <div class="col-sm-8">
                      {!! Form::select('uom_id', $uom,'',array('id'=>'uom_id')) !!}
                      <input type="hidden" name="id" id="id" value=""/>
                      <input type="hidden" name="order_type_id" id="order_type_id" value="{{ $order_type_id }}"/>
                      </div>
                    </div>
                    
                    
                    <div class="row middle">
                      <div class="col-sm-4 req-text" >PO. Qty </div>
                      <div class="col-sm-8"><input type="text" name="qty" id="qty" class="integer number" onchange="MsPurChem.calculate()" /></div>
                    </div>
                     <div class="row middle">
                      <div class="col-sm-4 req-text" >Rate</div>
                      <div class="col-sm-8"><input type="text" name="rate" id="rate" class="integer number" onchange="MsPurChem.calculate()"/></div>
                    </div>
                    <div class="row middle">
                      <div class="col-sm-4 req-text" >Amount</div>
                      <div class="col-sm-8"><input type="text" name="amount" id="amount" class="integer number" readonly/></div>
                    </div>
                    <div class="row middle">
                      <div class="col-sm-4" >No. Of Bag </div>
                      <div class="col-sm-8"><input type="text" name="no_of_bag" id="no_of_bag" class="integer number"/></div>
                    </div>
                    <div class="row middle">
                      <div class="col-sm-4" >Remarks</div>
                      <div class="col-sm-8"> <input type="text" name="remarks" id="remarks"/> </div>
                    </div>
              </code>
           </div>
        </div>
        <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPurChem.submit()">Save</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('purchemFrm')" >Reset</a>
            <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPurChem.remove()" >Delete</a>
        </div>
    
      </form>


          </div>
            <div data-options="region:'center',border:true,title:'List',footer:'#purchemft'" style="padding:2px; ">
            <table id="purchemTbl" style="width:1250px">
            <thead>
                  <th data-options="field:'id'" width="40px">ID</th>
                  <th data-options="field:'style_ref',halign:'center'" width="80">Style Ref.</th>
                  <th data-options="field:'buyer_code',halign:'center'" width="80">Buyer</th>
                  <th data-options="field:'chem_des',halign:'center'" width="400">Chem</th>
                  <th data-options="field:'qty',halign:'center'" width="70" align="right">Qty</th>
                  <th data-options="field:'uom_code',halign:'center'" width="80">UOM</th>
                  <th data-options="field:'rate',halign:'center'" width="30" align="right">Rate</th>
                  <th data-options="field:'amount',halign:'center'" width="70" align="right">Amount</th>
                  <th data-options="field:'add_con',halign:'center'" formatter="MsPurChem.formatQty" width="70">Click</th>
                  
                   
                </thead>
                </table>
                <div id="purchemft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPurChem.openConsWindow()">Import Chemical</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Terms & Conditions" style="overflow:auto;padding:1px;height:520px">
      @includeFirst(['Purchase.PurchaseTermsCondition', 'Defult.Purchase.PurchaseTermsCondition'])
    </div>
</div>
<div id="importchemWindow" class="easyui-window" title="Chemical Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,footer:'#budgetchemsearchTblft'" style="padding:2px">
            <table id="budgetchemsearchTbl" style="width:1250px">
                <thead>                    
                    <th data-options="field:'id'" width="50">ID</th>
                    <th data-options="field:'name'" width="100">Category</th>
                    <th data-options="field:'class_name'" width="100">Item Class</th>
                    <th data-options="field:'item_description'" width="100">Item Description</th>
                    <th data-options="field:'reorder_level '" width="100">Reorder Level  </th>
                    <th data-options="field:'qty',halign:'center'" width="70" align="right">Req Qty</th>
                    <th data-options="field:'rate',halign:'center'" width="30" align="right">Rate</th>
                    <th data-options="field:'amount',halign:'center'" width="70" align="right">Amount</th>
                    <th data-options="field:'balance'" width="70px" align="right">Balance Qty</th>                   
                </thead>
            </table>
            <div id="budgetchemsearchTblft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <!-- <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" id="pdf" onClick="MsPurChem.submit()" >Add More</a>
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" id="pdf" onClick="MsPurChem.submitAndClose()" >Next</a> -->
                <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="MsPurChem.closeConsWindow()" style="width:80px">Close</a>
            </div>
        </div>
        
        <div data-options="region:'west',border:true" style="padding:2px; width:350px">
        <form id="budgetchemsearchFrm">
            <div id="container">
                 <div id="body">
                   <code>
                    <div class="row">
                    <div class="col-sm-4">Item Class </div>
                    <div class="col-sm-8">
                    {!! Form::select('itemclass_id', $itemclass,'',array('id'=>'itemclass_id')) !!}
                    </div>
                    </div>
                  </code>
               </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" id="pdf" onClick="MsPurChem.searchChem()" >Search</a>
            </div>
          </form>              
        </div>
    </div>
</div>

<div id="purchemitemWindow" class="easyui-window" title="Chemical Qty Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,footer:'#purchemitemfooter'" style="padding:2px">
            <form id="purchemitemFrm">
            <code id="purchemitemscs" style="margin:0px">
            </code>
            </form>
        </div>
        <div id="purchemitemfooter" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPurChem.submitBatch()">Save</a>
        
        </div>
    </div>
</div>


<div id="purchemqtyWindow" class="easyui-window" title="Chemical Qty Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,footer:'#purchemqtyfooter'" style="padding:2px">
            <form id="purchemqtyFrm">
            <code id="purchemqtyscs" style="margin:0px">
            </code>
            </form>
        </div>
        <div id="purchemqtyfooter" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPurChemQty.submit()">Save</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPurChemQty.resetForm()" >Reset</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPurChemQty.remove()" >Delete</a>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/'); ?>/js/Util/MsPurOrderChemController.js"></script>
<script type="text/javascript" src="<?php echo url('/'); ?>/js/Util/MsPurChemController.js"></script>
<script type="text/javascript" src="<?php echo url('/'); ?>/js/Util/MsPurChemQtyController.js"></script>
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
