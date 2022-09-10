<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="comexpinvoicetabs">
 <div title="Invoice And LC Ref." style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="expinvoiceTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="80">ID</th>
       <th data-options="field:'lc_sc_no'" width="180">LC/SC No</th>
       <th data-options="field:'buyer_code'" width="100">Buyer</th>
       <th data-options="field:'invoice_no'" width="100">Invoice No</th>
       <th data-options="field:'invoice_date'" width="80">Invoice Date</th>
       <th data-options="field:'advance_invoice_no'" width="90">Adv.CI</th>
       <th data-options="field:'exp_form_no'" width="130">Exp Form No</th>
       <th data-options="field:'exp_form_date'" width="75">Exp Form<br> Date</th>
       <th data-options="field:'actual_ship_date'" width="75">Actual Ship<br> Date</th>
       <th data-options="field:'net_wgt_exp_qty'" width="70" align="right">Net Wgt</th>
       <th data-options="field:'gross_wgt_exp_qty'" width="70" align="right">Gross Wgt</th>
       <th data-options="field:'shipping_bill_no'" width="100">Shipping Bill</th>
       <th data-options="field:'invoice_value'" width="80" align="right">Invoice Value</th>
       <th data-options="field:'net_inv_value'" width="80" align="right">Net Invoice<br> Value</th>
       <th data-options="field:'invoice_status_id'" width="50">Invoice<br>Status</th>
       <th data-options="field:'remarks'" width="120">Remarks</th>
       <th data-options="field:'updated_by_name'" width="120">Edited By</th>
       <th data-options="field:'updated_at'" width="120">Edited At</th>
      </tr>
     </thead>
    </table>
   </div>
   <div data-options="region:'west',border:true,title:'Add Invoice',footer:'#ft2'" style="width: 400px; padding:2px">
    <form id="expinvoiceFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row">
                                    <input type="hidden" name="id" id="id" value="" />    
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">LC/SC No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="lc_sc_no" id="lc_sc_no" ondblclick="MsExpInvoice.openExpInvoiceWindow()" placeholder=" Double Click" readonly />
                                        <input type="hidden" name="exp_lc_sc_id" id="exp_lc_sc_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Adv.CI Ref.</div>
                                    <div class="col-sm-4"  style="padding-right:0px">
                                        <input type="text" name="adv_invoice_no" id="adv_invoice_no" ondblclick="MsExpInvoice.openAdvInvoiceWindow()" placeholder="Double Click"  readonly class="col-sm-4"/>
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                        <input type="text" name="exp_adv_invoice_id" id="exp_adv_invoice_id" value=""  readonly class="col-sm-4"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Invoice No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="invoice_no" id="invoice_no" placeholder="invoice no" />
                                    </div>
                                </div>                            
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Invoice Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="invoice_date" id="invoice_date" class="datepicker" placeholder="yy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Invoice Status</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('invoice_status_id', $invoicestatus,'',array('id'=>'invoice_status_id')) !!}
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
                                    <div class="col-sm-4">Consignee</div>
                                    <div class="col-sm-8">
                                       {!! Form::select('consignee_id', $consignee,'',array('id'=>'consignee_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Notifying Party</div>
                                    <div class="col-sm-8">
                                       {!! Form::select('notifying_party_id', $notifyingParties,'',array('id'=>'notifying_party_id')) !!} 
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">2nd Notifying Party</div>
                                    <div class="col-sm-8">
                                       {!! Form::select('second_notifying_party_id', $notifyingParties,'',array('id'=>'second_notifying_party_id')) !!} 
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
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks"></textarea>
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
       iconCls="icon-save" plain="true" id="save" onClick="MsExpInvoice.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('expinvoiceFrm')">Reset</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsExpInvoice.remove()">Delete</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px; border-radius:1px" plain="true"
       id="save" onClick="MsExpInvoice.openCI()">CI</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px; border-radius:1px" plain="true"
       id="save" onClick="MsExpInvoice.bnfdeclaration()">BWD</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px; border-radius:1px" plain="true"
       id="save" onClick="MsExpInvoice.confirmletter()">CL</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px; border-radius:1px" plain="true"
       id="save" onClick="MsExpInvoice.shipperconfirm()">SC</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px; border-radius:1px" plain="true"
       id="save" onClick="MsExpInvoice.shipcertifydeclare()">SCD</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px; border-radius:1px" plain="true"
       id="save" onClick="MsExpInvoice.bnfconfirmazo()">AZO</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px; border-radius:1px" plain="true"
       id="save" onClick="MsExpInvoice.certifybanazo()">CCB_AZO</a>
     </div>
    </form>
   </div>
  </div>
 </div>
 <!----------------->
 <div title="Corresponding Order Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">

   <div data-options="region:'center',border:true,title:'Lists',footer:'#depft'" style="padding:2px">
    <form id="expinvoiceorderFrm">
     <code id="expinvoiceordermatrix"></code>
     <div id="depft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsExpInvoiceOrder.submit()">Save</a>
     </div>
    </form>
   </div>
  </div>
 </div>
 <!----------------->
 <div title="Value Adjustment Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'Add Information',iconCls:'icon-more',footer:'#valueadjustft'"
    style="width:450px; padding:2px">
    <form id="expinvoiceadjdetailFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row middle">
                                    <div class="col-sm-2">Invoice Value </div>
                                    <div class="col-sm-2">
                                        <input type="text" name="invoice_amount" id="invoice_amount" class="number integer" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-2">Discount % </div>
                                    <div class="col-sm-2">
                                        <input type="text" name="discount_per" id="discount_per" value="0" class="number integer" onchange="MsExpInvoice.calDiscount()" placeholder="number"/>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="discount_remarks" id="discount_remarks" placeholder="write remarks for discount %" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-2">Discount Amount</div>
                                    <div class="col-sm-2">
                                        <input type="text" name="discount_amount" id="discount_amount" value="0" class="number integer" onchange="MsExpInvoice.calNetValue()" placeholder="number"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-2">Annual Bonus %</div>
                                    <div class="col-sm-2">
                                        <input type="text" name="annual_bonus_per" id="annual_bonus_per" value="0" class="number integer" onchange="MsExpInvoice.calAnualBonus()" placeholder="number"/>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="bonus_remarks" id="bonus_remarks" placeholder="write remarks for bonus amount %" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-2">Bonus Amount</div>
                                    <div class="col-sm-2">
                                        <input type="text" name="bonus_amount" id="bonus_amount" value="0" class="number integer" onchange="MsExpInvoice.calNetValue()" placeholder="number"/>
                                    </div>
                                </div>            
                                <div class="row middle">
                                    <div class="col-sm-2">Claim %  </div>
                                    <div class="col-sm-2">
                                        <input type="text" name="claim_per" id="claim_per" value="0" class="number integer" onchange="MsExpInvoice.calClaim()" placeholder="number"/>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="claim_remarks" id="claim_remarks" placeholder="write remarks for claim details" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-2">Claim Amount  </div>
                                    <div class="col-sm-2">
                                        <input type="text" name="claim_amount" id="claim_amount" value="0" class="number integer" onchange="MsExpInvoice.calNetValue()" placeholder="number"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-2">Commission </div>
                                    <div class="col-sm-2">
                                        <input type="text" name="commission" id="commission" value="0" class="number integer" placeholder=" write" onchange="MsExpInvoice.calNetValue()" placeholder="number"/>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="commision_remarks" id="commision_remarks" placeholder="write remarks for commission %" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-2">Up Charges</div>
                                    <div class="col-sm-2">
                                        <input type="text" name="up_charge_amount" id="up_charge_amount" value="0" class="number integer" onchange="MsExpInvoice.calNetValue()" placeholder="number"/>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="up_charge_remarks" id="up_charge_remarks" placeholder="write remarks for up Charges details" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-2">Net Inv. Value </div>
                                    <div class="col-sm-2">
                                        <input type="text" name="net_inv_value" id="net_inv_value" value="" class="number integer" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-2">Invoice Qty. </div>
                                    <div class="col-sm-2">
                                        <input type="text" name="invoice_qty" id="invoice_qty" value="" class="number integer" placeholder=" display" disabled />
                                    </div>
                                </div>
                            </code>
      </div>
     </div>
     <div id="valueadjustft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsExpInvoice.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('expinvoiceadjdetailFrm')">Reset</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsExpInvoice.remove()">Delete</a>
     </div>
    </form>
   </div>
  </div>
 </div>
 <!----------------->
 <div title="Other Shipping Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'west',border:true,title:'Add Information',iconCls:'icon-more',footer:'#othershipft'"
    style="width:450px; padding:2px">
    <form id="expinvoiceshipdetailFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row middle">
                                    <div class="col-sm-4">BL/Cargo No </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="bl_cargo_no" id="bl_cargo_no" value="" placeholder="write number" />
                                    </div>                                   
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">BL/Cargo Date </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="bl_cargo_date" id="bl_cargo_date" value="" class="datepicker" placeholder=" YY-MM-DD" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Original BL Rev. Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="origin_bl_rev_date" id="origin_bl_rev_date" value="" class="datepicker" placeholder=" YY-MM-DD" />
                                    </div>
                                </div>                                   
                                <div class="row middle">
                                    <div class="col-sm-4">ETD Port </div>
                                    <div class="col-sm-8">
                                       <input type="text" name="etd_port" id="etd_port" class="datepicker" placeholder="yy-mm-dd">
                                    </div>
                                 </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Feeder Vessel </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="feeder_vessel" id="feeder_vessel" value="" placeholder="write" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Mother Vessel </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="mother_vessel" id="mother_vessel" value="" placeholder="write" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">ETA Port </div>
                                    <div class="col-sm-8">
                                       <input type="text" name="eta_port" id="eta_port" class="datepicker" placeholder="yy-mm-dd">
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">IC Recv. Date </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="ic_recv_date" id="ic_recv_date" value="" class="datepicker" placeholder=" yyyy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Shipping Mode </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('ship_mode_id', $deliveryMode,'',array('id'=>'ship_mode_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Shipping Mark</div>
                                    <div class="col-sm-8">
                                        <textarea name="shipping_mark" id="shipping_mark"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Incoterm </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('incoterm_id', $incoterm,'',array('id'=>'incoterm_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Inco Term Place </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="incoterm_place" id="incoterm_place" placeholder="write" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Port of Entry </div>
                                    <div class="col-sm-8">
                                       <input type="text" name="port_of_entry" id="port_of_entry" value="" placeholder="write/select" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Port of Loading </div>
                                    <div class="col-sm-8">
                                       <input type="text" name="port_of_loading" id="port_of_loading" value="" placeholder="write/select" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Port of Discharge </div>
                                    <div class="col-sm-8">
                                       <input type="text" name="port_of_discharge" id="port_of_discharge" value="" placeholder="write/select" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Shipping Bill No </div>
                                    <div class="col-sm-8">
                                       <input type="text" name="shipping_bill_no" id="shipping_bill_no" value="" placeholder="write" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Shipping Bill Date </div>
                                    <div class="col-sm-8">
                                       <input type="text" name="shipping_bill_date" id="shipping_bill_date" class="datepicker" value="" placeholder="yyyy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Ex-factory Date </div>
                                    <div class="col-sm-8">
                                       <input type="text" name="ex_factory_date" id="ex_factory_date" class="datepicker" value="" placeholder="yyyy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Freight By Supplier </div>
                                    <div class="col-sm-8">
                                       <input type="text" name="freight_by_supplier" id="freight_by_supplier" class="number integer" value="" placeholder="write" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Freight By Buyer</div>
                                    <div class="col-sm-8">
                                       <input type="text" name="freight_by_buyer" id="freight_by_buyer" value="" class="number integer" placeholder="write" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Paid Amount </div>
                                    <div class="col-sm-8">
                                       <input type="text" name="paid_amount" id="paid_amount" value="" class="number integer" placeholder="number" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Total Ctn Qty </div>
                                    <div class="col-sm-8">
                                       <input type="text" name="total_ctn_qty" id="total_ctn_qty" value="" class="number integer"  placeholder="number" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Advice Date </div>
                                    <div class="col-sm-8">
                                       <input type="text" name="advice_date" id="advice_date" value="" class="datepicker" placeholder="YY-MM-DD" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Advice Amount </div>
                                    <div class="col-sm-8">
                                       <input type="text" name="advice_amount" id="advice_amount" value="" class="number integer" placeholder="number" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Submit To </div>
                                    <div class="col-sm-8">
                                       <input type="text" name="submit_to_id" id="submit_to_id" value="" placeholder="from bank/buyer combo" />
                                    </div>
                                </div>
                            </code>
      </div>
     </div>
     <div id="othershipft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsExpInvoice.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('expinvoiceshipdetailFrm')">Reset</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsExpInvoice.remove()">Delete</a>
     </div>
    </form>
   </div>
   <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
    {{-- <table id="expinvoiceshipdetailFrm" style="width:100%">
                    <thead>
                        <tr>
                           <th data-options="field:'bl_cargo_no'" width="30">BL/Cargo No</th>
                            <th data-options="field:'bl_cargo_date'" width="80">BL/Cargo Date</th>
                            <th data-options="field:'origin_bl_rev_date'" width="80">Original BL Rev. Date</th>
                            <th data-options="field:'ic_recv_date',halign:'center'" width="80" align="right">IC Recv. Date</th>
                            <th data-options="field:'advice_date',halign:'center'" width="80" align="right">Advice Date</th>
                            <th data-options="field:'advice_amount',halign:'center'" width="80" align="right">Advice Amount</th>
                            <th data-options="field:'Submit To',halign:'center'" width="80" align="right">Submit To</th> 
                        </tr>
                    </thead>
                </table> --}}
   </div>
  </div>
 </div>
 <!--------------------->
</div>
<div id="openinvoicesaleorderWindow" class="easyui-window" title="Color&Size Wise Invoice Qty Details"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',border:true,title:'Lists',footer:'#orderdtailft'" style="padding:2px">
   <form id="expinvoiceorderdtlFrm">
    <code id="expinvoiceorderdtlmatrix">
                       <div class="row">
                           {{-- <input type="hidden" name="exp_invoice_order_id" id="exp_invoice_order_id" /> --}}
                       </div>
                   </code>
    <div id="orderdtailft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
      iconCls="icon-save" plain="true" id="save" onClick="MsExpInvoiceOrderDtl.submit()">Save</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsExpInvoiceOrderDtl.resetForm()">Reset</a>
     <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsExpInvoiceOrderDtl.remove()">Delete</a>
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
      onClick="MsExpInvoice.searchExpSalesContractGrid()">Search</a>
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
      <th data-options="field:'buyer_id'" width="180">Buyer</th>
      <th data-options="field:'beneficiary_id'" width="100">Beneficiary</th>
      <th data-options="field:'lc_sc_nature'" width="100">Contract Nature</th>
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
<div id="invoiceWindow" class="easyui-window" title="Commercial Invoice Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:400px;height:300px;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center'" style="padding:10px;">
   <table id="invoiceSearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'pdf'" width="80" formatter="MsExpInvoice.formatOrderCIPdf"></th>
      <th data-options="field:'pdf1'" width="80" formatter="MsExpInvoice.formatColorSizeCIPdf"></th>
      <th data-options="field:'pdf2'" width="80" formatter="MsExpInvoice.formatColorWiseCIPdf"></th>
      <th data-options="field:'pdf3'" width="80" formatter="MsExpInvoice.formatSizeWiseCIPdf"></th>
     </tr>
    </thead>
   </table>
  </div>
  <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
   <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
    onclick="$('#invoiceWindow').window('close')" style="width:80px">Close</a>
  </div>
 </div>
</div>
<div id="openadvinvoicewindow" class="easyui-window" title="Advance Export Commercial Invoice Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
   <div class="easyui-layout" data-options="fit:true">
    <div id="body">
     <code>
                        <form id="expadvinvoicesearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Invoice No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="invoice_no" id="invoice_no" value="">
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Invoice Date </div>
                                <div class="col-sm-8">
                                    <input type="text" name="invoice_date" id="invoice_date" value="" class="datepicker" />
                                </div>
                            </div>
                        </form>
                    </code>
    </div>
    <p class="footer">
     <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px"
      onClick="MsExpInvoice.searchAdvInvoiceGrid()">Search</a>
    </p>
   </div>
  </div>
  <div data-options="region:'center'" style="padding:10px;">
   <table id="exadvinvoicesearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="30">ID</th>
      <th data-options="field:'lc_sc_no'" width="100">LC/SC No</th>
      <th data-options="field:'invoice_no'" width="100">Invoice No</th>
      <th data-options="field:'invoice_date'" width="100">Invoice Date</th>
      <th data-options="field:'exp_form_no'" width="100">Exp Form No</th>
      <th data-options="field:'exp_form_date'" width="100">Exp Form Date</th>
      <th data-options="field:'actual_ship_date'" width="100">Actual Ship Date</th>
      <th data-options="field:'net_wgt_exp_qty'" width="70" align="right">Net Wgt</th>
      <th data-options="field:'gross_wgt_exp_qty'" width="70" align="right">Gross Wgt</th>
      <th data-options="field:'remarks'" width="120">Remarks</th>
     </tr>
    </thead>
   </table>
  </div>
  <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
   <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
    onclick="$('#openadvinvoicewindow').window('close')" style="width:80px">Close</a>
  </div>
 </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/Commercial/Export/MsAllExpInvoiceController.js"></script>
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


    var bloodhound = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
        url: msApp.baseUrl()+'/expinvoice/portofentry?q=%QUERY%',
        wildcard: '%QUERY%'
        },
    });

    $('#port_of_entry').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
      },
        {
            name: 'port_of_entry',
            limit:1000,
            source: bloodhound,
            display: function(data) {
                return data.name  //Input value to be set when you select a suggestion. 
            },
            templates: {
                empty: [
                    '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
                ],
                header: [
                    '<div class="list-group search-results-dropdown">'
                ],
                suggestion: function(data) {
                    return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item">' + data.name + '</div></div>'
                }
            }
    });

    var bloodhound = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
        url: msApp.baseUrl()+'/expinvoice/portofloading?q=%QUERY%',
        wildcard: '%QUERY%'
        },
    });

    $('#port_of_loading').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
      },
        {
            name: 'port_of_loading',
            limit:1000,
            source: bloodhound,
            display: function(data) {
                return data.name  //Input value to be set when you select a suggestion. 
            },
            templates: {
                empty: [
                    '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
                ],
                header: [
                    '<div class="list-group search-results-dropdown">'
                ],
                suggestion: function(data) {
                    return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item">' + data.name + '</div></div>'
                }
            }
    });

    var bloodhound = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
        url: msApp.baseUrl()+'/expinvoice/portofdischarge?q=%QUERY%',
        wildcard: '%QUERY%'
        },
    });

    $('#port_of_discharge').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
      },
        {
            name: 'port_of_discharge',
            limit:1000,
            source: bloodhound,
            display: function(data) {
                return data.name  //Input value to be set when you select a suggestion. 
            },
            templates: {
                empty: [
                    '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
                ],
                header: [
                    '<div class="list-group search-results-dropdown">'
                ],
                suggestion: function(data) {
                    return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item">' + data.name + '</div></div>'
                }
            }
    });
 
</script>