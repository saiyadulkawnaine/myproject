<div class="easyui-accordion" data-options="multiple:false" style="width:99%;" id="poaopserviceAccordion">
    <div title="Master" data-options="iconCls:'icon-ok'" style="padding:1px;height:520px">
      <div class="easyui-layout"  data-options="fit:true" >
        <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
          <table id="poaopserviceTbl" style="width:100%">
            <thead>
              <tr>
                <th data-options="field:'id'" width="40">ID</th>
                <th data-options="field:'po_no'" width="100">WO No</th>
                <th data-options="field:'po_date'" width="100">WO Date</th>
                <th data-options="field:'company_code'" width="60">Company</th>
                <th data-options="field:'supplier_code'" width="120">Supplier</th>
                <th data-options="field:'paymode'" width="100">Pay Mode</th>
                <th data-options="field:'currency_code'" width="60">Currency</th>
                <th data-options="field:'exch_rate'" width="60">Exch.<br/>Rate</th>
                <th data-options="field:'amount'" width="100" align="right">Amount</th>
                <th data-options="field:'pi_no'" width="60">PI No</th>
                <th data-options="field:'pi_date'" width="60">PI Date</th>
                <th data-options="field:'remarks'" width="150">Remarks</th>
                <th data-options="field:'approve_status'" width="80">Approve<br/>Status</th>
              </tr>
            </thead>
          </table>
        </div>
        <div data-options="region:'west',border:true,title:'Aop Service Work Order',iconCls:'icon-more',hideCollapsedContent:false,footer:'#ft2'" style="width:350px; padding:2px">
          <form id="poaopserviceFrm">
            <div id="container">
                <div id="body">
                  <code>
                    <div class="row">
                        <div class="col-sm-4">WO No</div>
                        <div class="col-sm-8">
                        <input type="hidden" name="id" id="id" value=""/>
                        <input type="text" name="po_no" id="po_no" class="integer number" readonly placeholder="display"/>
                        </div>
                      </div>
                      <div class="row middle">
                        <div class="col-sm-4 req-text">Beneficiary</div>
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
                        <div class="col-sm-8">
                          {!! Form::select('basis_id', $basis,'',array('id'=>'basis_id')) !!}
                        </div>
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
                        <div class="col-sm-4">PI No.</div>
                        <div class="col-sm-8">
                          <input type="text" name="pi_no" id="pi_no" value=""/>
                        </div>
                        </div>
                      <div class="row middle">
                        <div class="col-sm-4">PI Date</div>
                        <div class="col-sm-8"><input type="text" name="pi_date" id="pi_date" class="datepicker"/></div>
                      </div>
                      <div class="row middle">   
                        <div class="col-sm-4">Remarks</div>
                        <div class="col-sm-8">
                          <textarea name="remarks" id="remarks"></textarea>
                        </div>
                      </div>
                </code>
              </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
              <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPoAopService.submit()">Save</a>
              <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('poaopserviceFrm')" >Reset</a>
              <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPoAopService.remove()" >Delete</a>
              <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPoAopService.pdf()" >PDF</a>
            </div> 
          </form>
        </div>
      </div>
    </div>
    <div title="Fabric" style="overflow:auto;padding:1px;height:520px">
      <div class="easyui-layout"  data-options="fit:true" >
        <div data-options="region:'center',border:true,title:'List',footer:'#poaopserviceitemtblft'" style="padding:2px; ">
          <table id="poaopserviceitemTbl" style="width:1250px">
            <thead>                 
              <th data-options="field:'id'" width="40px">ID</th>
              <th data-options="field:'buyer_name'" width="60px">Buyer</th>
              <th data-options="field:'style_ref'" width="70px">Style Ref</th>
              <th data-options="field:'style_gmt'" width="100px">GMT Item</th>
              <th data-options="field:'gmtspart'" width="60px">GMT Part</th>
              <th data-options="field:'fabric_description'" width="150px">Fabric <br/>Description</th>
              <th data-options="field:'fabricnature'" width="50px">Fabric<br/> Nature</th>
              <th data-options="field:'fabriclooks'" width="50px">Fabric<br/>Looks</th>
              <th data-options="field:'fabricshape'" width="50px">Fabric<br/> Shape</th>
              <th data-options="field:'dyeing_type_id'" width="60px">Dyeing<br/> Type</th>
              {{-- <th data-options="field:'embelishment_type_id'" width="60px">Aop<br/> Type</th>
              <th data-options="field:'coverage'" width="60px">Coverage</th>
              <th data-options="field:'impression'" width="60px">No of<br/>Color</th> --}}
              <th data-options="field:'uom_name'" width="30px">UOM</th>
              <th data-options="field:'gsm_weight'" width="40px">GSM</th>
              <th data-options="field:'qty'" width="70px" align="right">WO.Qty</th>
              <th data-options="field:'rate'" width="70px" align="right">Rate</th>
              <th data-options="field:'amount'" width="80px" align="right">Amount</th>
              <th data-options="field:'add_con'" formatter="MsPoAopServiceItem.formatQty" width="50" align="center">Qty</th>
              <th data-options="field:'delete_fabric'" formatter="MsPoAopServiceItem.deleteButton" width="60" align="center"></th>
            </thead>
          </table>
          <div id="poaopserviceitemtblft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPoAopServiceItem.openConsWindow()">Import Fabric</a>
          </div>
        </div>
      </div>
    </div>
    <div title="Terms & Conditions" style="overflow:auto;padding:1px;height:520px">
      @includeFirst(['Purchase.PurchaseTermsCondition', 'Defult.Purchase.PurchaseTermsCondition'])
    </div>
  </div>
  <div id="poaopserviceitemimportWindow" class="easyui-window" title="Fabric Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
      <div data-options="region:'center',border:true,footer:'#poaopserviceitemsearchTblft'" style="padding:2px">
        <table id="poaopserviceitemsearchTbl" style="width:1250px">
          <thead>                 
            <th field="ck" checkbox="true"></th>
            <th data-options="field:'id'" width="40px">ID</th>
            <th data-options="field:'buyer_name'" width="200px">Buyer</th>
            <th data-options="field:'style_ref'" width="200px">Style Ref</th>
            <th data-options="field:'item_description'" width="200px">GMT Item</th>
            <th data-options="field:'gmtspart_name'" width="150px">GMT Part</th>
            <th data-options="field:'fabric_description'" width="250px">Fabric Description</th>
            <th data-options="field:'fabricnature'" width="80px">Fabric Nature</th>
            <th data-options="field:'fabriclooks'" width="80px">Fabric Looks</th>
            <th data-options="field:'fabricshape'" width="80px">Fabric Shape</th>
            <th data-options="field:'materialsourcing'" width="80px">Material Source</th>
            <th data-options="field:'embelishment_type_id'" width="60px">Aop<br/> Type</th>
            <th data-options="field:'coverage'" width="60px">Coverage</th>
            <th data-options="field:'impression'" width="60px">No Of<br/>Color</th>
            <th data-options="field:'uom_name'" width="30px">UOM</th>
            <th data-options="field:'gsm_weight'" width="30px">GSM</th>
            <th data-options="field:'supplier_name'" width="80px">Supplier</th>
            <th data-options="field:'fabric_cons'" width="70px" align="right">Budget Qty</th>
            <th data-options="field:'rate'" width="70px" align="right">Rate</th>
            <th data-options="field:'amount'" width="80px" align="right">Amount</th>
            <th data-options="field:'balance'" width="70px" align="right">Balance Qty</th>
          </thead>
        </table>
        <div id="poaopserviceitemsearchTblft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
          <a href="javascript:void(0)" class=" easyui-linkbutton c1" style="height:25px;border-radius:1px"  plain="true" id="pdf" onClick="MsPoAopServiceItem.submit()" >Add More</a>
          <a href="javascript:void(0)" class=" easyui-linkbutton c6" style="height:25px;border-radius:1px"  plain="true" id="pdf" onClick="MsPoAopServiceItem.submitAndClose()" >Next</a>
        </div>
      </div>   
      <div data-options="region:'west',border:true,footer:'#poaopserviceitemsearchFrmft'" style="padding:2px; width:350px">
        <form id="poaopserviceitemsearchFrm">
          <div id="container">
            <div id="body">
              <code>
                <div class="row ">
                  <div class="col-sm-4">Budget ID</div>
                  <div class="col-sm-8">
                    <input type="text" name="budget_id" id="budget_id" />
                  </div>
                </div>
                <div class="row middle">
                  <div class="col-sm-4">Job No</div>
                  <div class="col-sm-8">
                    <input type="text" name="job_no" id="job_no" />
                  </div>
                </div>
                <div class="row middle">
                  <div class="col-sm-4">Style Ref</div>
                  <div class="col-sm-8">
                    <input type="text" name="style_ref" id="style_ref" />
                  </div>
                </div>
                <div class="row middle">
                  <div class="col-sm-4">Company</div>
                  <div class="col-sm-8">
                    {!! Form::select('company_id', $company,'',array('id'=>'company_id','disabled'=>'disabled')) !!}
                  </div>
                </div>
              </code>
            </div>
          </div>
          <div id="poaopserviceitemsearchFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class=" easyui-linkbutton c1" style="height:25px;border-radius:1px"  plain="true" id="pdf" onClick="MsPoAopServiceItem.searchFabric()" >Search</a>
          </div>
        </form>   
      </div>
    </div>
  </div>
  
  <div id="poaopserviceitemqtyWindow" class="easyui-window" title="Fabric Qty Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
      <div class="easyui-layout"  data-options="fit:true">
          <div data-options="region:'center',border:true,footer:'#poaopserviceitemqtyft'" style="padding:2px">
            <form id="poaopserviceitemqtyFrm">
              <code id="poaopserviceitemqtyscs" style="margin:0px">
              </code>
            </form>
          </div>
          <div id="poaopserviceitemqtyft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPoAopServiceItemQty.submit()">Save</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPoAopServiceItemQty.resetForm()" >Reset</a>
            <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPoAopServiceItemQty.remove()" >Delete</a>
          </div>
      </div>
  </div>
  
  <script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsPoAopServiceController.js"></script>
  <script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsPoAopServiceItemController.js"></script>
  <script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsPoAopServiceItemQtyController.js"></script>
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
  $('#poaopserviceFrm [id="supplier_id"]').combobox();
  </script>
  