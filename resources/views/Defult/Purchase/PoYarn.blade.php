<div class="easyui-accordion" data-options="multiple:false" style="width:100%;" id="poyarnAccordion">
  <div title="Master" data-options="iconCls:'icon-ok'" style="padding:1px;height:520px">
    <div class="easyui-layout" data-options="fit:true">
      <div data-options="region:'center',border:true,title:'List',footer:'#poyarnTblFt'" style="padding:2px">
        <table id="poyarnTbl" style="width:100%">
          <thead>
            <tr>
              <th data-options="field:'id'" width="60">ID</th>
              <th data-options="field:'po_no'" width="80">PO No</th>
              <th data-options="field:'po_date'" width="80">PO Date</th>
              <th data-options="field:'company_code'" width="50" align="center">Company</th>
              <th data-options="field:'supplier_code'" width="160">Supplier</th>
              <th data-options="field:'pi_no'" width="160">PI No</th>
              <th data-options="field:'source'" width="70" align="center">Source</th>
              <th data-options="field:'item_qty'" width="100" align="right">Qty</th>
              <th data-options="field:'amount'" width="100" align="right">Amount</th>
              <th data-options="field:'delv_start_date'" width="90" align="center">Delivery Start</th>
              <th data-options="field:'delv_end_date'" width="90" align="center">Delivery End</th>
              <th data-options="field:'paymode'" width="120">Pay Mode</th>
              <th data-options="field:'exch_rate'" width="60">Conv.<br />Rate</th>
              <th data-options="field:'currency_code'" width="70">Currency<br />Code</th>
              <th data-options="field:'remarks'" width="200">Remarks</th>
              <th data-options="field:'approve_status'" width="80">Approve<br />Status</th>
            </tr>
          </thead>
        </table>
        <div id="poyarnTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
          Po No: <input type="text" name="po_no" id="po_no" style="width: 100px;height:23px">
          Supplier: {!! Form::select('supplier_search_id',
          $supplier,'',array('id'=>'supplier_search_id','style'=>'width:
          100px;
          border-radius:2px; height:23px')) !!}
          Po Date: <input type="text" name="from_date" id="from_date" class="datepicker"
            style="width: 100px ;height: 23px" />
          <input type="text" name="to_date" id="to_date" class="datepicker" style="width: 100px;height: 23px" />
          <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
            iconCls="icon-search" plain="true" id="save" onClick="MsPoYarn.searchPoYarn()">Show</a>
        </div>
      </div>
      <div
        data-options="region:'west',border:true,title:'Purchase Order',iconCls:'icon-more',hideCollapsedContent:false,footer:'#ft2'"
        style="width:350px; padding:2px">
        <form id="poyarnFrm">
          <div id="container">
            <div id="body">
              <code>
                  <div class="row">
                    <div class="col-sm-4">Po No</div>
                      <div class="col-sm-8">
                        <input type="text" name="po_no" id="po_no" class="integer number" readonly placeholder="display"/>
                        <input type="hidden" name="id" id="id" value=""/>
                      </div>
                    </div>
                    <div class="row middle">
                      <div class="col-sm-4 req-text">Po Date</div>
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
                      <div class="col-sm-8">
                        {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
                      </div>
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
                      <div class="col-sm-8"><input type="text" name="pi_no" id="pi_no" value=""/></div>
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
              iconCls="icon-save" plain="true" id="save" onClick="MsPoYarn.submit()">Save</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
              iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('poyarnFrm')">Reset</a>
            <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
              iconCls="icon-remove" plain="true" id="delete" onClick="MsPoYarn.remove()">Delete</a>
            <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
              iconCls="icon-remove" plain="true" id="pdf" onClick="MsPoYarn.pdf()">PDF</a>
            <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
              iconCls="icon-remove" plain="true" id="pdf" onClick="MsPoYarn.summeryPdf()">Summery PDF</a>
          </div>

        </form>
      </div>
    </div>
  </div>
  <div title="Yarns" style="overflow:auto;padding:1px;height:520px">
    <div class="easyui-layout" data-options="fit:true">
      <div data-options="region:'west',border:true,title:'From',footer:'#ft3'" style=" width:350px;padding:2px; ">
        <form id="poyarnitemFrm">
          <div id="container">
            <div id="body">
              <code>
              <div class="row">
              <div class="col-sm-4">Yarn</div>
              <div class="col-sm-8">
              <input type="text" name="item_description" id="item_description" readonly placeholder="display"/>
              <input type="hidden" name="item_account_id" id="item_account_id" class="integer number" readonly placeholder="display"/>
              <input type="hidden" name="po_yarn_id" id="po_yarn_id" class="integer number" readonly placeholder="display"/>
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
              <div class="col-sm-8"><input type="text" name="qty" id="qty" class="integer number" onchange="MsPoYarnItem.calculate()" /></div>
              </div>
              <div class="row middle">
              <div class="col-sm-4 req-text" >Rate</div>
              <div class="col-sm-8"><input type="text" name="rate" id="rate" class="integer number" onchange="MsPoYarnItem.calculate()"/></div>
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
          <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
              iconCls="icon-save" plain="true" id="save" onClick="MsPoYarnItem.submit()">Save</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
              iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('poyarnitemFrm')">Reset</a>
            <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
              iconCls="icon-remove" plain="true" id="delete" onClick="MsPoYarnItem.remove()">Delete</a>
          </div>
        </form>
      </div>
      <div data-options="region:'center',border:true,title:'List',footer:'#puryarnft'" style="padding:2px; ">
        <table id="poyarnitemTbl" style="width:1320px">
          <thead>
            <th data-options="field:'id'" width="40px">ID</th>
            <th data-options="field:'yarn_des',halign:'center'" width="400">Yarn</th>
            <th data-options="field:'qty',halign:'center'" width="70" align="right">Qty</th>
            <th data-options="field:'uom_code',halign:'center'" width="80">UOM</th>
            <th data-options="field:'rate',halign:'center'" width="30" align="right">Rate</th>
            <th data-options="field:'amount',halign:'center'" width="70" align="right">Amount</th>
            <th data-options="field:'add_con',halign:'center'" formatter="MsPoYarnItem.formatQty" width="70">Click</th>
            <th data-options="field:'add_del',halign:'center'" formatter="MsPoYarnItem.deleteButton" width="70"></th>
          </thead>
        </table>
        <div id="puryarnft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
          <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
            iconCls="icon-save" plain="true" id="save" onClick="MsPoYarnItem.openConsWindow()">Import Yarn</a>
        </div>
      </div>
    </div>
  </div>
  <div title="Terms & Conditions" style="overflow:auto;padding:1px;height:520px">
    @includeFirst(['Purchase.PurchaseTermsCondition', 'Defult.Purchase.PurchaseTermsCondition'])
  </div>
</div>


<div id="poyarnitemimportWindow" class="easyui-window" title="Yarn Window"
  data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
  <div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center',border:true,footer:'#poyarnitemsearchTblft'" style="padding:2px">
      <table id="poyarnitemsearchTbl" style="width:1250px">
        <thead>
          <th field="ck" checkbox="true"></th>
          <th data-options="field:'id'" width="40px">ID</th>
          <th data-options="field:'yarn_des',halign:'center'" width="400">Yarn</th>
        </thead>
      </table>
      <div id="poyarnitemsearchTblft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
        <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
          onclick="MsPoYarnItem.closeConsWindow()" style="width:80px">Close</a>
      </div>
    </div>


    <div data-options="region:'west',border:true" style="padding:2px; width:350px">
      <form id="poyarnitemsearchFrm">
        <div id="container">
          <div id="body">
            <code>
                   <div class="row ">
                         <div class="col-sm-4">Yarn Count</div>
                         <div class="col-sm-8"> <input type="text" name="yarn_count" id="yarn_count" /> </div>
                     </div>

                     <div class="row middle">
                         <div class="col-sm-4">Yarn Type</div>
                         <div class="col-sm-8">
                         <input type="text" name="yarn_type" id="yarn_type" />
                         </div>
                     </div>
                  </code>
          </div>
        </div>
        <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
          <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true"
            id="pdf" onClick="MsPoYarnItem.searchYarn()">Search</a>
        </div>

      </form>

    </div>
  </div>
</div>

<div id="poyarnitemWindow" class="easyui-window" title="Yarn Qty Window"
  data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
  <div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center',border:true,footer:'#poyarnitemfooter'" style="padding:2px">
      <form id="poyarnitemgridFrm">
        <code id="poyarnitemscs" style="margin:0px">
            </code>
      </form>
    </div>
    <div id="poyarnitemfooter" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
        iconCls="icon-save" plain="true" id="save" onClick="MsPoYarnItem.submitBatch()">Save</a>

    </div>
  </div>
</div>


<div id="poyarnitembomqtymultiWindow" class="easyui-window" title="Yarn Qty Window"
  data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
  <div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center',border:true,footer:'#poyarnitembomqtymultifooter'" style="padding:2px">
      <form id="poyarnitembomqtymultiFrm">
        <code id="poyarnitembomqty" style="margin:0px">
            </code>
      </form>
    </div>

    <div id="poyarnitembomqtymultifooter" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
        iconCls="icon-save" plain="true" id="save" onClick="MsPoYarnItemBomQty.submitMalti()">Save</a>
    </div>
  </div>
</div>

<div id="poyarnitembomqtyWindow" class="easyui-window" title="Yarn Order Window"
  data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
  <div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center',border:true,footer:'#poyarnitembomqtyfooter'" style="padding:2px">
      <table border="1" id="poyarnitembomqtyTbl">
        <thead>
          <tr>
            <th data-options="field:'yarn_des'" width="250">Yarn</th>
            <th data-options="field:'style_ref'" width="100">Style Ref</th>
            <th data-options="field:'buyer_name'" width="100">Buyer</th>
            <th data-options="field:'sale_order_no'" width="100">Sales Order</th>
            <th data-options="field:'ship_date'" width="100">Ship Date</th>
            <th data-options="field:'company_name'" width="100">Company</th>
            <th data-options="field:'p_company_name'" width="100">Prod. Company</th>
            <th data-options="field:'plan_cut_qty'" width="100" align="right">Cut Qty (Psc)</th>
            <th data-options="field:'bom_qty'" width="100" align="right">BOM Qty</th>
            <th data-options="field:'bom_rate'" width="100" align="right">BOM Rate</th>
            <th data-options="field:'bom_amount'" width="100" align="right">BOM Amount</th>
            <th data-options="field:'prev_po_qty'" width="100" align="right">Prev. PO. Qty</th>
            <th data-options="field:'balance_qty'" width="70px" align="right">Bal. PO. Qty</th>
            <th data-options="field:'qty'" width="70px" align="right">PO. Qty</th>
            <th data-options="field:'rate'" width="70px" align="right">Rate</th>
            <th data-options="field:'amount'" width="70px" align="right">Amount</th>
          </tr>
        </thead>
      </table>
      <div id="poyarnitembomqtyfooter" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
          iconCls="icon-remove" plain="true" id="delete" onClick="MsPoYarnItemBomQty.openOrderSearchWindow()">Import
          Order</a>
      </div>
    </div>
    <div data-options="region:'west',border:true,title:'Form',footer:'#poyarnitembomqtyfrmfooter'"
      style="padding:2px; width: 350px">
      <form id="poyarnitembomqtyFrm">
        <div id="container">
          <div id="body">
            <code>
            <div class="row">
            <div class="col-sm-4">Yarn</div>
            <div class="col-sm-8">
            <input type="text" name="item_description" id="item_description" readonly />
            <input type="hidden" name="id" id="id" class="integer number" readonly/>
            <input type="hidden" name="budget_yarn_id" id="budget_yarn_id" class="integer number" readonly/>
            <input type="hidden" name="po_yarn_item_id" id="po_yarn_item_id" class="integer number" readonly />
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-4 req-text">Sale Order No</div>
            <div class="col-sm-8">
            <input type="text" name="sale_order_no" id="sale_order_no"/>
            <input type="hidden" name="sales_order_id" id="sales_order_id"/>
            </div>
            </div>     
            <div class="row middle">
            <div class="col-sm-4 req-text" >PO. Qty </div>
            <div class="col-sm-8"><input type="text" name="qty" id="qty" class="integer number" onchange="MsPoYarnItemBomQty.calculateAmountfrom()" /></div>
            </div>
            <div class="row middle">
            <div class="col-sm-4 req-text" >Rate</div>
            <div class="col-sm-8"><input type="text" name="rate" id="rate" class="integer number" onchange="MsPoYarnItemBomQty.calculateAmountfrom()" /></div>
            </div>
            <div class="row middle">
            <div class="col-sm-4 req-text" >Amount</div>
            <div class="col-sm-8"><input type="text" name="amount" id="amount" class="integer number" readonly/></div>
            </div>
            
            
            </code>
          </div>
        </div>
        <div id="poyarnitembomqtyfrmfooter" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
          <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
            iconCls="icon-save" plain="true" id="save" onClick="MsPoYarnItemBomQty.submit()">Save</a>
          <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
            iconCls="icon-remove" plain="true" id="delete"
            onClick="MsPoYarnItemBomQty.resetForm('poyarnitemFrm')">Reset</a>
          <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
            iconCls="icon-remove" plain="true" id="delete" onClick="MsPoYarnItemBomQty.remove()">Delete</a>
        </div>
      </form>
    </div>
  </div>
</div>

<div id="poyarnordersearchWindow" class="easyui-window" title="Yarn Order Window"
  data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
  <div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'east',border:true,title:'Selected Order',footer:'#poyarnordersearchfooter'"
      style="padding:1px; width: 350px">
      <table border="1" id="poyarnorderselectedTbl">
        <thead>
          <tr>
            <th data-options="field:'yarn_des'" width="250">Yarn</th>
            <th data-options="field:'sale_order_no'" width="100">Sales Order</th>
            <th data-options="field:'style_ref'" width="100">Style Ref</th>
            <th data-options="field:'buyer_name'" width="100">Buyer</th>

          </tr>
        </thead>
      </table>
    </div>
    <div data-options="region:'center',border:true,title:'Order'," style="padding:2px">
      <table border="1" id="poyarnordersearchTbl">
        <thead>
          <tr>
            <th data-options="field:'yarn_des'" width="250">Yarn</th>
            <th data-options="field:'sale_order_no'" width="100">Sales Order</th>
            <th data-options="field:'ship_date'" width="100">Ship Date</th>
            <th data-options="field:'company_name'" width="100">Company</th>
            <th data-options="field:'p_company_name'" width="100">Prod. Company</th>
            <th data-options="field:'style_ref'" width="100">Style Ref</th>
            <th data-options="field:'buyer_name'" width="100">Buyer</th>
            <th data-options="field:'plan_cut_qty'" width="100" align="right">Cut Qty (Psc)</th>
            <th data-options="field:'bom_qty'" width="100" align="right">BOM Qty</th>
            <th data-options="field:'bom_rate'" width="100" align="right">BOM Rate</th>
            <th data-options="field:'bom_amount'" width="100" align="right">BOM Amount</th>
            <th data-options="field:'prev_po_qty'" width="100" align="right">Prev. PO. Qty</th>
            <th data-options="field:'balance_qty'" width="70px" align="right">Bal. PO. Qty</th>
          </tr>
        </thead>
      </table>
    </div>
    <div id="poyarnordersearchfooter" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
        iconCls="icon-save" plain="true" id="save" onClick="MsPoYarnItem.closeOrderWindow()">Close</a>
    </div>
  </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsPoYarnController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsPoYarnItemController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsPoYarnItemBomQtyController.js"></script>

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
$('#poyarnFrm [id="supplier_id"]').combobox();
$('#poyarnFrm [id="indentor_id"]').combobox();
$('#poyarnTblFt [id="supplier_search_id"]').combobox();
</script>