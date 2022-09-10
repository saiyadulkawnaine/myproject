<div class="easyui-accordion" data-options="multiple:false" style="width:100%;" id="pogeneralAccordion">
 <div title="Master" data-options="iconCls:'icon-ok'" style="padding:1px;height:520px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List',footer:'#pogeneralTblFt'" style="padding:2px">
    <table id="pogeneralTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="40">ID</th>
       <th data-options="field:'po_no'" width="80">PO No</th>
       <th data-options="field:'po_date'" width="80">PO Date</th>
       <th data-options="field:'company_code'" width="60" align="center">Company</th>
       <th data-options="field:'supplier_code'" width="160">Supplier</th>
       <th data-options="field:'pi_no'" width="160">PI No</th>
       <th data-options="field:'source'" width="70" align="center">Source</th>
       <th data-options="field:'item_qty'" width="80" align="right">Qty</th>
       <th data-options="field:'amount'" width="100" align="right">Amount</th>
       <th data-options="field:'exch_rate'" width="70" align="right">Conv.Rate</th>
       <th data-options="field:'currency_code'" width="60">Currency</th>
       <th data-options="field:'delv_start_date'" width="80" align="center">Delivery Start</th>
       <th data-options="field:'delv_end_date'" width="80" align="center">Delivery End</th>
       <th data-options="field:'paymode'" width="120">Pay Mode</th>
       <th data-options="field:'remarks'" width="150">Remarks</th>
       <th data-options="field:'approve_status'" width="80">Approve<br />Status</th>
      </tr>
     </thead>
    </table>
    <div id="pogeneralTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     Po No: <input type="text" name="po_no" id="po_no" style="width: 80px;height:23px">
     Supplier: {!! Form::select('supplier_search_id',
     $supplier,'',array('id'=>'supplier_search_id','style'=>'width:
     80px;
     border-radius:2px; height:23px')) !!}
     Company: {!! Form::select('company_search_id', $company,'',array('id'=>'company_search_id','style'=>'width:
     80px;
     border-radius:2px; height:23px')) !!}
     Po Date: <input type="text" name="from_date" id="from_date" class="datepicker" style="width: 80px ;height: 23px" />
     <input type="text" name="to_date" id="to_date" class="datepicker" style="width: 80px;height: 23px" />
     <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
      iconCls="icon-search" plain="true" id="save" onClick="MsPoGeneral.searchPoGeneral()">Show</a>
    </div>
   </div>
   <div
    data-options="region:'west',border:true,title:'Purchase Order',iconCls:'icon-more',hideCollapsedContent:false,footer:'#ft2'"
    style="width:350px; padding:2px">
    <form id="pogeneralFrm">
     <div id="container">
      <div id="body">
       <code>
                    <div class="row">
                      <div class="col-sm-4">PO No</div>
                        <div class="col-sm-8">
                      		<input type="text" name="po_no" id="po_no" class="integer number" readonly placeholder="display"/>
                      		<input type="hidden" name="id" id="id" value=""/>
                        </div>
                      </div>
                      <div class="row middle">
                        <div class="col-sm-4">PO Date</div>
                        <div class="col-sm-8"><input type="text" name="po_date" id="po_date" class="datepicker"/></div>
                      </div>
                      <div class="row middle">
                        <div class="col-sm-4 req-text">Company</div>
                        <div class="col-sm-8">
                        	{!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
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
                        <div class="col-sm-4 req-text">Pay Mode</div>
                        <div class="col-sm-8">{!! Form::select('pay_mode', $paymode,'',array('id'=>'pay_mode')) !!}</div>
                      </div>
                      <div class="row middle">
                        <div class="col-sm-4 req-text">Currency</div>
                        <div class="col-sm-8">{!! Form::select('currency_id', $currency,'',array('id'=>'currency_id')) !!}</div>
                        </div>
                      <div class="row middle">
                        <div class="col-sm-4 req-text">Exch.Rate</div>
                        <div class="col-sm-8"><input type="text" name="exch_rate" id="exch_rate" class="integer number"/></div>
                      </div>
                      <div class="row middle">
                        <div class="col-sm-4 req-text">Delivery Start</div>
                        <div class="col-sm-8"><input type="text" name="delv_start_date" id="delv_start_date" class="datepicker"/></div>
                      </div>
                      <div class="row middle">
                        <div class="col-sm-4 req-text">Delivery End</div>
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
                        <div class="col-sm-8"><input type="text" name="pi_no" id="pi_no" value=""/></div>
                      </div>
                      <div class="row middle">
                        <div class="col-sm-4">PI Date  </div>
                        <div class="col-sm-8"><input type="text" name="pi_date" id="pi_date" class="datepicker"/></div>
                      </div>
                      <div class="row middle">
                        <div class="col-sm-4">Remarks</div>
                        <div class="col-sm-8">
                        	<textarea  name="remarks" id="remarks" value=""></textarea>
						</div>
                      </div>

                </code>
      </div>
     </div>
     <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsPoGeneral.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('pogeneralFrm')">Reset</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsPoGeneral.remove()">Delete</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="pdf" onClick="MsPoGeneral.pdf()">PDF</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="pdf" onClick="MsPoGeneral.openTopSheetWindow()">Top Sheet</a>
     </div>

    </form>
   </div>
  </div>
 </div>
 <div title="Items" style="overflow:auto;padding:1px;height:520px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'west',border:true,title:'From',footer:'#pogeneralitemfrmft'"
    style=" width:350px;padding:2px; ">
    <form id="pogeneralitemFrm">
     <div id="container">
      <div id="body">
       <code>
                <div class="row">
                  <div class="col-sm-4">Item Desc.</div>
                  <div class="col-sm-8">
                    <input type="text" name="item_description" id="item_description" readonly placeholder="display"/>
                    <input type="hidden" name="id" id="id"/>  
                    <input type="hidden" name="po_general_id" id="po_general_id"/>
                  </div>
                </div>  
                <div class="row middle">
                  <div class="col-sm-4 req-text" >PO. Qty </div>
                  <div class="col-sm-8"><input type="text" name="qty" id="qty" class="integer number" onchange="MsPoGeneralItem.calculate()" /></div>
                </div>
                <div class="row middle">
                  <div class="col-sm-4 req-text" >Rate</div>
                  <div class="col-sm-8"><input type="text" name="rate" id="rate" class="integer number" onchange="MsPoGeneralItem.calculate()"/></div>
                </div>
                <div class="row middle">
                  <div class="col-sm-4 req-text" >Amount</div>
                  <div class="col-sm-8"><input type="text" name="amount" id="amount" class="integer number" readonly/></div>
                </div>
                <div class="row middle">
                  <div class="col-sm-4" >Remarks</div>
                  <div class="col-sm-8"> <input type="text" name="remarks" id="remarks"/> </div>
                </div>
                <div class="row middle">
                  <div class="col-sm-4" >Balance</div>
                  <div class="col-sm-8"> <input type="text" name="balance_qty" id="balance_qty" class="integer number" disabled /> </div>
                </div>
              </code>
      </div>
     </div>
     <div id="pogeneralitemfrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsPoGeneralItem.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('pogeneralitemFrm')">Reset</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsPoGeneralItem.remove()">Delete</a>
     </div>
    </form>
   </div>
   <div data-options="region:'center',border:true,title:'List',footer:'#pogeneralitemtblft'" style="padding:2px; ">
    <div class="easyui-layout" data-options="fit:true">
     <table id="pogeneralitemTbl" style="width:1250px">
      <thead>
       <tr>
        <th data-options="field:'id'" width="40">ID</th>
        <th data-options="field:'requisition_no'" width="90">Requisition No</th>
        <th data-options="field:'category_name'" width="80">Category</th>
        <th data-options="field:'class_name'" width="80">Item Class</th>
        <th data-options="field:'sub_class_name'" width="100">Sub Class</th>
        <th data-options="field:'item_description'" width="100">Item Description</th>
        <th data-options="field:'specification'" width="100">Specification</th>
        <th data-options="field:'uom_name'" width="50">UOM</th>
        <th data-options="field:'prev_po_qty'" width="80" align="right">Prev. Qty</th>
        <th data-options="field:'balance_qty'" width="80" align="right">Balance Qty</th>
        <th data-options="field:'qty'" width="80" align="right">Qty</th>
        <th data-options="field:'rate'" width="80" align="right">Rate</th>
        <th data-options="field:'amount'" width="100" align="right">Amount</th>
       </tr>
      </thead>
     </table>
     <div id="pogeneralitemtblft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsPoGeneralItem.openItemWindow()">Get Requisition</a>
     </div>
    </div>
   </div>
  </div>
 </div>
 <div title="Terms & Conditions" style="overflow:auto;padding:1px;height:520px">
  @includeFirst(['Purchase.PurchaseTermsCondition', 'Defult.Purchase.PurchaseTermsCondition'])
 </div>
</div>


<div id="importdyechemWindow" class="easyui-window" title="General Item Requisition"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',border:true,footer:'#pogeneralsearchtblft'" style="padding:2px">
   <div class="easyui-layout" data-options="fit:true">
    <table id="pogeneralsearchTbl" style="width:610px">
     <thead>
      <tr>
       <th data-options="field:'id'" width="50">ID</th>
       <th data-options="field:'requisition_no'" width="100">Requisition No</th>
       <th data-options="field:'category_name'" width="100">Category</th>
       <th data-options="field:'class_name'" width="100">Item Class</th>
       <th data-options="field:'sub_class_name'" width="100">Sub Class</th>
       <th data-options="field:'item_description'" width="100">Item Description</th>
       <th data-options="field:'specification'" width="100">Specification</th>
       <th data-options="field:'uom_name'" width="100">UOM</th>
       <th data-options="field:'req_qty'" width="100" align="right">Qty</th>
       <th data-options="field:'req_rate'" width="100" align="right">Rate</th>
       <th data-options="field:'req_amount'" width="100" align="right">Amount</th>
       <th data-options="field:'prev_po_qty'" width="100" align="right">Prev. Qty</th>
       <th data-options="field:'balance_qty'" width="100" align="right">Balance Qty</th>
      </tr>
     </thead>
    </table>
    <div id="pogeneralsearchtblft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
      iconCls="icon-save" plain="true" id="save" onClick="MsPoGeneralItem.closeItemWindow()">Close</a>
    </div>
   </div>
  </div>
  <div data-options="region:'west',border:true,footer:'#pogeneralsearchfrmft'" style="padding:2px; width: 350px">
   <div class="easyui-layout" data-options="fit:true">
    <div id="body">
     <code>
                <form id="pogeneralsearchFrm">
                    <div class="row ">
                      <div class="col-sm-4">Requisition No</div>
                      <div class="col-sm-8">
                        <input type="text" name="requisition_no" id="requisition_no" />
                      </div>
                    </div>
                    <div class="row middle">
                      <div class="col-sm-4">Item Des.</div>
                      <div class="col-sm-8">
                        <input type="text" name="item_description" id="item_description" />
                      </div>
                    </div>
                </form>
            </code>
    </div>
    <div id="pogeneralsearchfrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
      iconCls="icon-save" plain="true" id="save" onClick="MsPoGeneralItem.searchDyeChem()">Search</a>
    </div>
   </div>
  </div>
 </div>
</div>


<div id="pogeneralitemWindow" class="easyui-window" title="Item"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',border:true,footer:'#pogeneralitemmultifrmft'" style="padding:2px">
   <form id="pogeneralitemmultiFrm">
    <code id="importdyechemscs" style="margin:0px">
        </code>
   </form>
   <div id="pogeneralitemmultifrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
     iconCls="icon-save" plain="true" id="save" onClick="MsPoGeneralItem.submitBatch()">save</a>
   </div>
  </div>
 </div>
</div>

<div id="pogeneraltopsheetWindow" class="easyui-window" title="Top Sheet"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',border:true,footer:'#pogeneraltopsheetWindowFt'" style="padding:2px">
   <table id="pogeneraltopsheetTbl" style="width:100%">
    <thead>
     <th data-options="field:'id',halign:'center'" align="left" width="120">ID</th>
     <th data-options="field:'po_no',halign:'center'" align="left" width="120">Po No</th>
     <th data-options="field:'po_date',halign:'center'" align="center" width="60">Po Date</th>
     <th data-options="field:'supplier_code',halign:'center'" align="center" width="60">Supplier</th>
     <th data-options="field:'amount',halign:'center'" align="right" width="80">Amount</th>
     <th data-options="field:'delv_end_date',halign:'center'" align="right" width="80">Dlv. Date</th>
     <th data-options="field:'',halign:'center'" align="right" width="80" formatter="MsPoGeneral.topsheetporemovebtn">
     </th>
    </thead>
   </table>
  </div>
  <div id="pogeneraltopsheetWindowFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
   <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save"
    plain="true" id="save" onClick="MsPoGeneral.printTopSheet()">PDF</a>
  </div>
 </div>
</div>



<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsPoGeneralController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsPoGeneralItemController.js"></script>

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
$('#pogeneralFrm [id="supplier_id"]').combobox();
$('#pogeneralFrm [id="indentor_id"]').combobox();
$('#pogeneralTblFt [id="supplier_search_id"]').combobox();
$('#pogeneralTblFt [id="company_search_id"]').combobox();
</script>