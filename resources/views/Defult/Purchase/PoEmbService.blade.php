<div class="easyui-accordion" data-options="multiple:false" style="width:99%;" id="poembserviceAccordion">
    <div title="Master" data-options="iconCls:'icon-ok'" style="padding:1px;height:520px">
      <div class="easyui-layout"  data-options="fit:true" >
        <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
          <table id="poembserviceTbl" style="width:100%">
            <thead>
              <tr>
                <th data-options="field:'id'" width="50">ID</th>
                <th data-options="field:'po_no'" width="100">PO No</th>
                <th data-options="field:'po_date'" width="90">WO Date</th>
                <th data-options="field:'company_code'" align="center" width="60">Producing<br/>Company</th>
                <th data-options="field:'supplier_code'" align="center" width="60">Supplier</th>
                <th data-options="field:'paymode'" width="100">Pay Mode</th>
                <th data-options="field:'currency_code'" width="65" align="center">Currency</th>
                <th data-options="field:'exch_rate'" align="right" width="50">Exch<br/>Rate</th>
                <th data-options="field:'amount'" width="100">Amount</th>
                <th data-options="field:'remarks'" width="150">Remarks</th>
                <th data-options="field:'approve_status'" width="80">Approve<br />Status</th>
              </tr>
            </thead>
          </table>
        </div>
        <div data-options="region:'west',border:true,title:'Embelishment Service Order',iconCls:'icon-more',hideCollapsedContent:false,footer:'#ft2'" style="width:350px; padding:2px">
          <form id="poembserviceFrm">
            <div id="container">
              <div id="body">
                  <code>
                    <div class="row">
                      <div class="col-sm-4">PO No</div>
                      <div class="col-sm-8">
                      <input type="hidden" name="id" id="id" value=""/>
                        <input type="text" name="po_no" id="po_no" class="integer number" readonly placeholder="display"/>
                      </div>
                    </div>
                    <div class="row middle">
                      <div class="col-sm-4 req-text">Company</div>
                      <div class="col-sm-8">
                      {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                      </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">PO Date</div>
                        <div class="col-sm-8"><input type="text" name="po_date" id="po_date" class="datepicker"/></div>
                    </div>
                    
                      <div class="row middle">
                      <div class="col-sm-4 req-text" >Supplier</div>
                      <div class="col-sm-8">{!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}</div>
                    </div>             
                    <div class="row middle">
                      <div class="col-sm-4 req-text" >Basis</div>
                      <div class="col-sm-8">{!! Form::select('basis_id', $basis,'',array('id'=>'basis_id')) !!}</div>
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
                        <div class="col-sm-4">Dlv. Start Date</div>
                        <div class="col-sm-8"><input type="text" name="delv_start_date" id="delv_start_date" class="datepicker"/></div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Dlv. End Date</div>
                        <div class="col-sm-8"><input type="text" name="delv_end_date" id="delv_end_date" class="datepicker"/></div>
                    </div> 
                    <div class="row middle">
                      <div class="col-sm-4">PI No.  </div>
                      <div class="col-sm-8">
                        <input type="text" name="pi_no" id="pi_no" value=""/>
                      </div>
                    </div>
                    <div class="row middle">
                      <div class="col-sm-4">PI Date  </div>
                      <div class="col-sm-8"><input type="text" name="pi_date" id="pi_date" class="datepicker"/></div>
                    </div>
                    <div class="row middle">   
                      <div class="col-sm-4">Remarks  </div>
                      <div class="col-sm-8">
                        <textarea name="remarks" id="remarks"></textarea>
                      </div>
                    </div>
                    <div class="row middle">
                      <div class="col-sm-4 req-text">Production Area</div>
                      <div class="col-sm-8">
                          {!! Form::select('production_area_id',$productionarea,'',array('id'=>'production_area_id')) !!}
                      </div>
                    </div>
                </code>
              </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
              <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPoEmbService.submit()">Save</a>
              <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('poembserviceFrm')" >Reset</a>
              <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPoEmbService.remove()" >Delete</a>
              <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPoEmbService.pdf()" >PDF</a>
            </div> 
          </form>
        </div>
      </div>
    </div>
    <div title="Item" style="overflow:auto;padding:1px;height:520px">
      <div class="easyui-layout"  data-options="fit:true" >
        <div data-options="region:'center',border:true,title:'List',footer:'#poembserviceitemtblft'" style="padding:2px; ">
          <table id="poembserviceitemTbl" style="width:1250px">
            <thead>                 
                <th data-options="field:'id'" width="40px">ID</th>
                <th data-options="field:'buyer_name'" width="200px">Buyer</th>
                <th data-options="field:'style_ref'" width="200px">Style Ref</th>
                <th data-options="field:'item_description'" width="200px">GMT Item</th>
                <th data-options="field:'gmtspart_name'" width="150px">GMT Part</th>
                <th data-options="field:'embelishment_name'" width="80px">Emb. Name</th>
                <th data-options="field:'embelishment_type'" width="120px">Emb. Type</th>
                <th data-options="field:'embelishment_size'" width="80px">Emb. Size</th>
                <th data-options="field:'qty'" width="80px" align="right">Qty</th>
                <th data-options="field:'rate'" width="80px" align="right">Rate</th>
                <th data-options="field:'amount'" width="80px" align="right">Amount</th>
                <th data-options="field:'add_con'" formatter="MsPoEmbServiceItem.formatQty" width="50" align="center"></th>
                <th data-options="field:'delete_fabric'" formatter="MsPoEmbServiceItem.deleteButton" width="60" align="center"></th>
            </thead>
          </table>
          <div id="poembserviceitemtblft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPoEmbServiceItem.openConsWindow()">Import Fabric</a>
          </div>
        </div>
      </div>
    </div>
    <div title="Terms & Conditions" style="overflow:auto;padding:1px;height:520px">
      @includeFirst(['Purchase.PurchaseTermsCondition', 'Defult.Purchase.PurchaseTermsCondition'])
    </div>
</div>
<div id="poembserviceitemimportWindow" class="easyui-window" title="Fabric Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,footer:'#poembserviceitemsearchTblft'" style="padding:2px">
            <table id="poembserviceitemsearchTbl" style="width:1250px">
                <thead>                  
                    <th field="ck" checkbox="true"></th>
                    <th data-options="field:'id'" width="40px">ID</th>
                    <th data-options="field:'buyer_name'" width="200px">Buyer</th>
                    <th data-options="field:'style_ref'" width="200px">Style Ref</th>
                    <th data-options="field:'item_description'" width="200px">GMT Item</th>
                    <th data-options="field:'gmtspart_name'" width="150px">GMT Part</th>
                    <th data-options="field:'embelishment_name'" width="80px">Emb. Name</th>
                    <th data-options="field:'embelishment_type'" width="120px">Emb. Type</th>
                    <th data-options="field:'embelishment_size'" width="80px">Emb. Size</th>
                    <th data-options="field:'qty'" width="70px" align="right">Bom Qty</th>
                    <th data-options="field:'rate'" width="70px" align="right">Rate</th>
                    <th data-options="field:'amount'" width="80px" align="right">Amount</th>
                    <th data-options="field:'balance'" width="70px" align="right">Balance Qty</th>
                  </thead>
            </table>
            <div id="poembserviceitemsearchTblft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class=" easyui-linkbutton c1" style="height:25px;border-radius:1px"  plain="true" id="pdf" onClick="MsPoEmbServiceItem.submit()" >Add More</a>
                <a href="javascript:void(0)" class=" easyui-linkbutton c6" style="height:25px;border-radius:1px"  plain="true" id="pdf" onClick="MsPoEmbServiceItem.submitAndClose()" >Next</a>
            </div>
        </div>
        
            
        <div data-options="region:'west',border:true,footer:'#poembserviceitemsearchFrmft'" style="padding:2px; width:350px">
        <form id="poembserviceitemsearchFrm">
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
                         <div class="col-sm-4 req-text">Style Ref</div>
                         <div class="col-sm-8">
                         <input type="text" name="style_ref" id="style_ref" />
                         </div>
                     </div>
                     <div class="row middle">
                         <div class="col-sm-4 req-text">Company</div>
                         <div class="col-sm-8">{!! Form::select('company_id', $company,'',array('id'=>'company_id','disabled'=>'disabled')) !!}</div>
                     </div>
                  </code>
               </div>
            </div>
            <div id="poembserviceitemsearchFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class=" easyui-linkbutton c1" style="height:25px;border-radius:1px"  plain="true" id="pdf" onClick="MsPoEmbServiceItem.searchFabric()" >Search</a>
            </div>

          </form>
               
        </div>
    </div>
</div>

<div id="poembserviceitemqtyWindow" class="easyui-window" title="Fabric Qty Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,footer:'#poembserviceitemqtyft'" style="padding:2px">
            <form id="poembserviceitemqtyFrm">
            <code id="poembserviceitemqtyscs" style="margin:0px">
            </code>
            </form>
        </div>
        <div id="poembserviceitemqtyft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPoEmbServiceItemQty.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPoEmbServiceItemQty.resetForm()" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPoEmbServiceItemQty.remove()" >Delete</a>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsPoEmbServiceController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsPoEmbServiceItemController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsPoEmbServiceItemQtyController.js"></script>
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

$('#poembserviceFrm [id="supplier_id"]').combobox();
</script>
