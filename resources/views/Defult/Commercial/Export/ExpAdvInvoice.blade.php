<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="comexpadvinvoicetabs">
 <div title="Invoice And LC Ref." style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="expadvinvoiceTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="40">ID</th>
       <th data-options="field:'lc_sc_no'" width="100">LC/SC No</th>
       <th data-options="field:'invoice_no'" width="100">Invoice No</th>
       <th data-options="field:'invoice_date'" width="100">Invoice Date</th>
       <th data-options="field:'exp_form_no'" width="100">Exp Form No</th>
       <th data-options="field:'exp_form_date'" width="100">Exp Form Date</th>
       <th data-options="field:'actual_ship_date'" width="100">Actual Ship Date</th>
       <th data-options="field:'net_wgt_exp_qty'" width="70" align="right">Net Wgt</th>
       <th data-options="field:'gross_wgt_exp_qty'" width="70" align="right">Gross Wgt</th>
       <th data-options="field:'shipping_bill_no'" width="100">Shipping Bill</th>
       <th data-options="field:'invoice_value'" width="100">Invoice Value</th>
       <th data-options="field:'remarks'" width="120">Remarks</th>
      </tr>
     </thead>
    </table>
   </div>

   <div data-options="region:'west',border:true,title:'Add Invoice',footer:'#ft2'" style="width: 400px; padding:2px">
    <form id="expadvinvoiceFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row">
                                        <input type="hidden" name="id" id="id" value="" />    
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">LC/SC No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="lc_sc_no" id="lc_sc_no" ondblclick="MsExpAdvInvoice.openExpAdvInvoiceWindow()" placeholder=" Double Click" readonly />
                                        <input type="hidden" name="exp_lc_sc_id" id="exp_lc_sc_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Invoice No </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="invoice_no" id="invoice_no" placeholder="invoice no" />
                                    </div>
                                </div>                              
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Invoice Date </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="invoice_date" id="invoice_date" class="datepicker" placeholder="yy-mm-dd" />
                                    </div>
                                </div>                          
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Invoice Value </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="invoice_value" id="invoice_value" class="number integer" disabled>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Exp Form No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="exp_form_no" id="exp_form_no" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Exp Form Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="exp_form_date" id="exp_form_date" class="datepicker" placeholder=" yy-mm-dd"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Actual ShipDate</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="actual_ship_date" id="actual_ship_date" class="datepicker" placeholder=" yy-mm-dd"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Country </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('country_id', $country,'',array('id'=>'country_id')) !!}
                                    </div>
                                </div>                               
                                <div class="row middle">
                                    <div class="col-sm-4">Category No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="category_no" id="category_no" />
                                    </div>
                                </div>                               
                                <div class="row middle">
                                    <div class="col-sm-4">HS Code</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="hs_code" id="hs_code" placeholder="display" disabled />
                                    </div>
                                </div>                               
                                <div class="row middle">
                                    <div class="col-sm-4">Buyer </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="buyer_id" id="buyer_id" placeholder="display" disabled />
                                    </div>
                                </div>                               
                                <div class="row middle">
                                    <div class="col-sm-4">Applicant </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="buyer_id" id="buyer_id" placeholder="display" disabled />
                                    </div>
                                </div>                               
                                <div class="row middle">
                                    <div class="col-sm-4">Lien Date </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="lien_date" id="lien_date" placeholder="display" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Beneficiary </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="beneficiary_id" id="beneficiary_id" placeholder="display" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Internal File No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="file_no" id="file_no" placeholder="display" disabled />
                                    </div>
                                </div>
								<div class="row middle">
                                    <div class="col-sm-4 req-text">Net Weight</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="net_wgt_exp_qty" id="net_wgt_exp_qty" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Gross Weight</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="gross_wgt_exp_qty" id="gross_wgt_exp_qty" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">CBM</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="cbm" id="cbm" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Total CTN Qty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="total_ctn_qty" id="total_ctn_qty" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Shipping Mark</div>
                                    <div class="col-sm-8">
                                        <textarea name="shipping_mark" id="shipping_mark"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Rex Declaration</div>
                                    <div class="col-sm-8">
                                        <textarea name="rex_declaration" id="rex_declaration"></textarea>
                                    </div>
                                </div>
                            </code>
      </div>
     </div>
     <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsExpAdvInvoice.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('expadvinvoiceFrm')">Reset</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsExpAdvInvoice.remove()">Delete</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px; border-radius:1px" plain="true"
       id="save" onClick="MsExpAdvInvoice.advCI()">CI</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsExpAdvInvoice.boe()">BOE</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsExpAdvInvoice.forward()">Fwd.Letter</a>
     </div>
    </form>
   </div>
  </div>
 </div>
 <!----------------->
 <div title="Corresponding Order Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">

   <div data-options="region:'center',border:true,title:'Lists',footer:'#depft'" style="padding:2px">
    <form id="expadvinvoiceorderFrm">
     <code id="expadvinvoiceordermatrix"></code>
     <div id="depft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsExpAdvInvoiceOrder.submit()">Save</a>
     </div>
    </form>
   </div>
  </div>
 </div>
</div>
<div id="openinvoicesaleorderWindow" class="easyui-window" title="Color&Size Wise Invoice Qty Details"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',border:true,title:'Lists',footer:'#orderdtailft'" style="padding:2px">
   <form id="expadvinvoiceorderdtlFrm">
    <code id="expadvinvoiceorderdtlmatrix">
                       <div class="row">
                           {{-- <input type="hidden" name="exp_adv_invoice_order_id" id="exp_adv_invoice_order_id" /> --}}
                       </div>
                   </code>
    <div id="orderdtailft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
      iconCls="icon-save" plain="true" id="save" onClick="MsExpAdvInvoiceOrderDtl.submit()">Save</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsExpAdvInvoiceOrderDtl.resetForm()">Reset</a>
     <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsExpAdvInvoiceOrderDtl.remove()">Delete</a>
    </div>
   </form>
  </div>
  <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
   <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
    onclick="$('#openinvoicesaleorderWindow').window('close')" style="width:80px">Close</a>
  </div>
 </div>
</div>
<!-------->
<div id="openlcscwindow" class="easyui-window" title="Sales Order Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
   <div class="easyui-layout" data-options="fit:true">
    <div id="body">
     <code>
                     <form id="explcscsearchFrm">
                         <div class="row middle">
                             <div class="col-sm-4">Contract No</div>
                             <div class="col-sm-8">
                                     <input type="text" name="lc_sc_no" id="lc_sc_no" value="">
                             </div>
                         </div>
                         <div class="row middle">
                             <div class="col-sm-4">Contract Date </div>
                             <div class="col-sm-8">
                                     <input type="text" name="lc_sc_date" id="lc_sc_date" value="" class="datepicker" />
                             </div>
                         </div>
                     </form>
                 </code>
    </div>
    <p class="footer">
     <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px"
      onClick="MsExpAdvInvoice.searchExpSalesContractGrid()">Search</a>
    </p>
   </div>
  </div>
  <div data-options="region:'center'" style="padding:10px;">
   <table id="explcscsearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="30">ID</th>
      <th data-options="field:'sc_or_lc'" width="30">SC\LC</th>
      <th data-options="field:'lc_sc_no'" width="130">Contract No</th>
      <th data-options="field:'lc_sc_value'" width="100">Contract Value</th>
      <th data-options="field:'lc_sc_nature_id'" width="100">Contract Nature</th>
      <th data-options="field:'lc_sc_date'" width="100"> Contract Date</th>
      <th data-options="field:'last_delivery_date'" width="100" align="right"> Last Delivery Date</th>
     </tr>
    </thead>
   </table>
  </div>
  <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
   <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
    onclick="$('#openlcscwindow').window('close')" style="width:80px">Close</a>
  </div>
 </div>
</div>
<!-------->
{{-- <div id="invoiceWindow" class="easyui-window" title="Commercial Invoice Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:400px;height:300px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center'" style="padding:10px;">
            <table id="invoiceSearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'pdf'" width="80" formatter="MsExpAdvInvoice.formatOrderCIPdf"></th>
                        <th data-options="field:'pdf1'" width="80" formatter="MsExpAdvInvoice.formatColorSizeCIPdf"></th>
                        <th data-options="field:'pdf2'" width="80" formatter="MsExpAdvInvoice.formatColorWiseCIPdf"></th>
                        <th data-options="field:'pdf3'" width="80" formatter="MsExpAdvInvoice.formatSizeWiseCIPdf"></th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#invoiceWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div> --}}


<script type="text/javascript" src="<?php echo url('/');?>/js/Commercial/Export/MsAllExpAdvInvoiceController.js">
</script>
<script>
 $(".datepicker").datepicker({
        beforeShow:function(input) {
            $(input).css({
                "position": "relative",
                "z-index": 999999
            });
        },
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
    });

    $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
        }
    });

</script>