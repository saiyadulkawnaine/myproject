<div class="easyui-accordion" data-options="multiple:false" style="width:99%;" id="purorderfabricAccordion">
    <div title="Master" data-options="iconCls:'icon-ok'" style="padding:1px;height:520px">
    <div class="easyui-layout"  data-options="fit:true" >
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="purorderfabricTbl" style="width:100%">
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
    <form id="purorderfabricFrm">
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
                      <div class="col-sm-4 req-text" >Supplier</div>
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
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPurOrderFabric.submit()">Save</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('purorderfabricFrm')" >Reset</a>
            <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPurOrderFabric.remove()" >Delete</a>
        </div> 
      </form>
    </div>
    </div>
    </div>
    <div title="Fabric" style="overflow:auto;padding:1px;height:520px">
        <div class="easyui-layout"  data-options="fit:true" >
            <div data-options="region:'center',border:true,title:'List',footer:'#purfabricft'" style="padding:2px; ">
                <table id="purfabricTbl" style="width:1250px">
                    <thead>                 
                        <th data-options="field:'id'" width="40px">ID</th>
                        <th data-options="field:'style_gmt'" width="200px">GMT Item</th>
                        <th data-options="field:'gmtspart'" width="150px">GMT Part</th>
                        <th data-options="field:'fabric_description'" width="250px">Fabric Description</th>
                        <th data-options="field:'fabricnature'" width="80px">Fabric Nature</th>
                        <th data-options="field:'fabriclooks'" width="80px">Fabric Looks</th>
                        <th data-options="field:'fabricshape'" width="80px">Fabric Shape</th>
                        <th data-options="field:'materialsourcing'" width="80px">Material Source</th>
                        <th data-options="field:'uom_name'" width="30px">UOM</th>
                        <th data-options="field:'gsm_weight'" width="30px">GSM/WGT</th>
                        <th data-options="field:'supplier_id'" width="80px">Supplier</th>
                        <th data-options="field:'qty'" width="70px" align="right">Qty</th>
                        <th data-options="field:'rate'" width="70px" align="right">Rate</th>
                        <th data-options="field:'amount'" width="80px" align="right">Amount</th>
                        <th data-options="field:'add_con'" formatter="MsPurFabric.formatQty" width="50" align="center">Qty</th>
                        <th data-options="field:'delete_fabric'" formatter="MsPurFabric.deleteButton" width="60" align="center"></th>
                    
                    </thead>
                </table>
                <div id="purfabricft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPurFabric.openConsWindow()">Import Fabric</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Terms & Conditions" style="overflow:auto;padding:1px;height:520px">
      @includeFirst(['Purchase.PurchaseTermsCondition', 'Defult.Purchase.PurchaseTermsCondition'])
    </div>
</div>
<div id="importfabricWindow" class="easyui-window" title="Fabric Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,footer:'#budgetfabricsearchTblft'" style="padding:2px">
            <table id="budgetfabricsearchTbl" style="width:1250px">
                <thead>                  
                    <th field="ck" checkbox="true"></th>
                    <th data-options="field:'id'" width="40px">ID</th>
                    <th data-options="field:'item_description'" width="200px">GMT Item</th>
                    <th data-options="field:'gmtspart_name'" width="150px">GMT Part</th>
                    <th data-options="field:'fabric_description'" width="250px">Fabric Description</th>
                    <th data-options="field:'fabricnature'" width="80px">Fabric Nature</th>
                    <th data-options="field:'fabriclooks'" width="80px">Fabric Looks</th>
                    <th data-options="field:'fabricshape'" width="80px">Fabric Shape</th>
                    <th data-options="field:'materialsourcing'" width="80px">Material Source</th>
                    <th data-options="field:'uom_name'" width="30px">UOM</th>
                    <th data-options="field:'gsm_weight'" width="30px">GSM/WGT</th>
                    <th data-options="field:'supplier_name'" width="80px">Supplier</th>
                    <th data-options="field:'fabric_cons'" width="70px" align="right">Bom Qty</th>
                    <th data-options="field:'rate'" width="70px" align="right">Rate</th>
                    <th data-options="field:'amount'" width="80px" align="right">Amount</th>
                    <th data-options="field:'balance'" width="70px" align="right">Balance Qty</th>                   
                </thead>
            </table>
            <div id="budgetfabricsearchTblft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" id="pdf" onClick="MsPurFabric.submit()" >Add More</a>
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" id="pdf" onClick="MsPurFabric.submitAndClose()" >Next</a>
            </div>
        </div>
        
            
        <div data-options="region:'west',border:true" style="padding:2px; width:350px">
        <form id="budgetfabricsearchFrm">
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
                         <input type="text" name="job_no" id="job_no" />
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
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" id="pdf" onClick="MsPurFabric.searchFabric()" >Search</a>
            </div>

          </form>
               
        </div>
    </div>
</div>

<div id="purfabricqtyWindow" class="easyui-window" title="Fabric Qty Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,footer:'#purfabricqtyfooter'" style="padding:2px">
            <form id="purfabricqtyFrm">
            <code id="purfabricqtyscs" style="margin:0px">
            </code>
            </form>
        </div>
        <div id="purfabricqtyfooter" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPurFabricQty.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPurFabricQty.resetForm()" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPurFabricQty.remove()" >Delete</a>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsPurOrderFabricController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsPurFabricController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsPurFabricQtyController.js"></script>
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
