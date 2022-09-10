<div class="easyui-accordion" data-options="multiple:false" style="width:100%;" id="potrimAccordion">
  <div title="Master" data-options="iconCls:'icon-ok'" style="padding:1px;height:520px">
    <div class="easyui-layout" data-options="fit:true">
      <div data-options="region:'center',border:true,title:'List',footer:'#potrimTblFt'" style="padding:2px">
        <table id="potrimTbl" style="width:100%">
          <thead>
            <tr>
              <th data-options="field:'id'" width="40">ID</th>
              <th data-options="field:'po_no'" width="80">PO No</th>
              <th data-options="field:'po_date'" width="80">PO Date</th>
              <th data-options="field:'company_code'" width="60" align="center">Company</th>
              <th data-options="field:'supplier_code'" width="160">Supplier</th>
              <th data-options="field:'pi_no'" width="160">PI No</th>
              <th data-options="field:'pi_date'" width="80">PI Date</th>
              <th data-options="field:'source'" width="70" align="center">Source</th>
              {{-- <th data-options="field:'item_qty'" width="100" align="right">Qty</th> --}}
              <th data-options="field:'amount'" width="100" align="right">Amount</th>
              <th data-options="field:'exch_rate'" width="80" align="right">Conv.Rate</th>
              <th data-options="field:'delv_start_date'" width="100" align="center">Delivery Start</th>
              <th data-options="field:'delv_end_date'" width="100" align="center">Delivery End</th>
              <th data-options="field:'paymode'" width="120">Pay Mode</th>
              <th data-options="field:'approved'" width="80">Approval</th>
              <th data-options="field:'remarks'" width="200">Remarks</th>
            </tr>
          </thead>
        </table>
        <div id="potrimTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
          Po No: <input type="text" name="po_no" id="po_no" style="width: 100px;height:23px">
          Supplier: {!! Form::select('supplier_search_id',
          $supplier,'',array('id'=>'supplier_search_id','style'=>'width:
          150px;
          border-radius:2px; height:23px')) !!}
          Buyer: {!! Form::select('buyer_search_id', $buyer,'',array('id'=>'buyer_search_id','style'=>'width:
          150px;
          border-radius:2px; height:23px')) !!}
          {{-- Po Date: <input type="text" name="from_date" id="from_date" class="datepicker"
      style="width: 100px ;height: 23px" />
     <input type="text" name="to_date" id="to_date" class="datepicker" style="width: 100px;height: 23px" /> --}}
          <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
            iconCls="icon-search" plain="true" id="save" onClick="MsPoTrim.searchPoTrim()">Show</a>
        </div>
      </div>
      <div
        data-options="region:'west',border:true,title:'Purchase Order',iconCls:'icon-more',hideCollapsedContent:false,footer:'#ft2'"
        style="width:350px; padding:2px">
        <form id="potrimFrm">
          <div id="container">
            <div id="body">
              <code>
                  <div class="row">
                    <div class="col-sm-4">Order No</div>
                    <div class="col-sm-8">
                    <input type="text" name="po_no" id="po_no" class="integer number" readonly placeholder="display"/>
                    </div>
                  </div>
                  <div class="row middle">
                    <div class="col-sm-4">Order Date</div>
                    <div class="col-sm-8"><input type="text" name="po_date" id="po_date" class="datepicker"/></div>
                  </div>
                  <div class="row middle">
                    <div class="col-sm-4 req-text">Company</div>
                    <div class="col-sm-8">
                    {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                    <input type="hidden" name="id" id="id" value=""/>
                    </div>
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
                  <div class="col-sm-8">{!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}</div>
                  </div>
                  <div class="row middle">
                  <div class="col-sm-4">Buyer</div>
                  <div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}</div>
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
                    <div class="col-sm-4">Indentor</div>
                    <div class="col-sm-8">
                      {!! Form::select('indentor_id', $indentor,'',array('id'=>'indentor_id','style'=>'width: 100%; border-radius:2px')) !!}
                    </div>
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
              </code>
            </div>
          </div>
          <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
              iconCls="icon-save" plain="true" id="save" onClick="MsPoTrim.submit()">Save</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
              iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('potrimFrm')">Reset</a>
            <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
              iconCls="icon-remove" plain="true" id="delete">Delete</a>
            <!-- <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-print" plain="true" id="pdf" onClick="MsPoTrim.pdf()" >PDF</a> -->
            <a href="javascript:void(0)" class=" easyui-linkbutton c8" style="height:25px;border-radius:1px"
              iconCls="icon-pdf" plain="true" id="pdf" onClick="MsPoTrim.pdf2('A4')">PO-A4</a>
            <a href="javascript:void(0)" class=" easyui-linkbutton c6" style="height:25px;border-radius:1px"
              iconCls="icon-pdf" plain="true" id="pdf" onClick="MsPoTrim.pdf2('LEGAL')">PO-Legal</a>


          </div>
        </form>
      </div>
    </div>
  </div>
  <div title="Trims" style="overflow:auto;padding:1px;height:520px">
    <div class="easyui-layout" data-options="fit:true">
      <div data-options="region:'center',border:true,title:'List',footer:'#purtrimft'" style="padding:2px; ">
        <table id="potrimitemTbl" style="width:1250px">
          <thead>
            <tr>
              <th data-options="field:'job_no',halign:'center'" width="60">Job No</th>
              <th data-options="field:'buyer_name',halign:'center'" width="100">Buyer</th>
              <th data-options="field:'style_ref',halign:'center'" width="100">Style Ref</th>
              <th data-options="field:'item_account',halign:'center'" width="150">Item Class</th>
              <th data-options="field:'description',halign:'center'" width="200">Description</th>
              <th data-options="field:'sup_ref',halign:'center'" width="100">Sup Ref </th>
              <th data-options="field:'uom',halign:'center'" width="70">Uom</th>
              <th data-options="field:'cons',halign:'center'" width="50" align="right">BOM Qty</th>
              <th data-options="field:'rate',halign:'center'" width="50" align="right">Rate</th>
              <th data-options="field:'amount',halign:'center'" width="50" align="right">Amount</th>
              <th data-options="field:'add_con',halign:'center'" formatter="MsPoTrimItem.formatQty" width="70">Click
              </th>
              <th data-options="field:'add_dtm',halign:'center'" formatter="MsPoTrimItem.deleteButton" width="70">Delete
              </th>
              <th data-options="field:'id',halign:'center'" width="50">ID</th>
            </tr>
          </thead>
        </table>
        <div id="purtrimft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
          <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
            iconCls="icon-save" plain="true" id="save" onClick="MsPoTrimItem.openConsWindow()">Import Trims</a>
        </div>
      </div>
    </div>
  </div>
  <div title="Terms & Conditions" style="overflow:auto;padding:1px;height:520px">
    @includeFirst(['Purchase.PurchaseTermsCondition', 'Defult.Purchase.PurchaseTermsCondition'])
  </div>
</div>


<div id="potrimitemimportWindow" class="easyui-window" title="Trim Window"
  data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
  <div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center',border:true,footer:'#budgettrimsearchTblft'" style="padding:2px">
      <table id="potrimitemsearchTbl" style="width:1250px">
        <thead>
          <th data-options="field:'job_no',halign:'center'" width="60">Job No</th>
          <th data-options="field:'buyer_name',halign:'center'" width="100">Buyer</th>
          <th data-options="field:'style_ref',halign:'center'" width="100">Style Ref</th>
          <th data-options="field:'item_account',halign:'center'" width="150">Item Class</th>
          <th data-options="field:'description',halign:'center'" width="200">Description</th>
          <th data-options="field:'sup_ref',halign:'center'" width="100">Sup Ref </th>
          <th data-options="field:'uom',halign:'center'" width="70">Uom</th>
          <th data-options="field:'cons',halign:'center'" width="50" align="right">BOM Qty</th>
          <th data-options="field:'rate',halign:'center'" width="50" align="right">Rate</th>
          <th data-options="field:'amount',halign:'center'" width="50" align="right">Amount</th>
          <th data-options="field:'balance',halign:'center'" width="50" align="right">Balance Qty.</th>
          <th data-options="field:'id',halign:'center'" width="50">ID</th>
        </thead>
      </table>
      <div id="budgettrimsearchTblft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true"
          id="pdf" onClick="MsPoTrimItem.submit()">Add More</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true"
          id="pdf" onClick="MsPoTrimItem.submitAndClose()">Next</a>
      </div>
    </div>


    <div data-options="region:'west',border:true,footer:'#potrimitemsearchFrmft'" style="padding:2px; width:350px">
      <form id="potrimitemsearchFrm">
        <div id="container">
          <div id="body">
            <code>
                     <!-- <div class="row">
                         <div class="col-sm-4">Buyer</div>
                         <div class="col-sm-8">
                          {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                         </div>
                     </div> -->
                      <div class="row">
                         <div class="col-sm-4">Style Ref</div>
                         <div class="col-sm-8">
                         <input type="text" name="style_ref" id="style_ref"/>
                         </div>
                     </div>
                      <div class="row middle">
                         <div class="col-sm-4">Job No</div>
                         <div class="col-sm-8">
                         <input type="text" name="job_no" id="job_no"/>
                         </div>
                     </div>
                      <div class="row middle">
                         <div class="col-sm-4">Budget ID</div>
                         <div class="col-sm-8"> <input type="text" name="budget_id" id="budget_id" /> </div>
                     </div>
                     <div class="row middle">
                         <div class="col-sm-4">Prod. Company</div>
                         <div class="col-sm-8">{!! Form::select('company_id', $company,'',array('id'=>'company_id','disabled'=>'disabled')) !!}</div>
                     </div>
                  </code>
          </div>
        </div>
        <div id="potrimitemsearchFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
          <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true"
            id="pdf" onClick="MsPoTrimItem.searchTrim()">Search</a>
        </div>

      </form>

    </div>
  </div>
</div>


<div id="potrimitemqtyWindow" class="easyui-window" title="Trim"
  data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
  <div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center',border:true,footer:'#potrimitemqtyfooter'" style="padding:2px">
      <form id="potrimitemqtyFrm">

        <code id="purtrimqtyscs" style="margin:0px">
            </code>
      </form>
    </div>
    <div id="potrimitemqtyfooter" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
        iconCls="icon-save" plain="true" id="save" onClick="MsPoTrimItemQty.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
        iconCls="icon-remove" plain="true" id="delete" onClick="MsPoTrimItemQty.resetForm()">Reset</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
        iconCls="icon-remove" plain="true" id="delete" onClick="MsPoTrimItemQty.remove()">Delete</a>
    </div>
  </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsPoTrimController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsPoTrimItemController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsPoTrimItemQtyController.js"></script>
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

  $('#potrimFrm [id="supplier_id"]').combobox();
  $('#potrimFrm [id="indentor_id"]').combobox();
  $('#potrimFrm [id="buyer_id"]').combobox();
  $('#potrimTblFt [id="supplier_search_id"]').combobox();
  $('#potrimTblFt [id="buyer_search_id"]').combobox();
</script>