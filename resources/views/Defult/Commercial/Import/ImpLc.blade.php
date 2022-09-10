<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="comimportlctabs">
   <div title="Import Lc" style="padding:2px">
      <div class="easyui-layout" data-options="fit:true">
         <div data-options="region:'center',border:true,title:'Import LC list'" style="padding:2px">
            <table id="implcTbl" style="width:100%">
               <thead>
                  <tr>
                     <th data-options="field:'id'" width="80">ID</th>
                     <th data-options="field:'company'" width="60" align="center">Company</th>
                     <th data-options="field:'lc_no'" width="150">LC No</th>
                     <th data-options="field:'lc_application_date'" width="90" align="center">Apply Date</th>
                     <th data-options="field:'lc_date'" width="90" align="center">LC Date</th>
                     <th data-options="field:'lc_amount'" width="100" align="right">LC Value</th>
                     <th data-options="field:'supplier'" width="100">Supplier</th>
                     <th data-options="field:'lc_to'" width="100">LC To</th>
                     <th data-options="field:'bankbranch'" width="120">Issuing Bank</th>
                     <th data-options="field:'commercial_head_name'" width="100">A/C Type</th>
                     <th data-options="field:'lc_type_id'" width="100">LC Type</th>
                     <th data-options="field:'menu_id'" width="100">PO Type</th>
                     <th data-options="field:'last_delivery_date'" width="90">Last Delivery Date</th>
                     <th data-options="field:'expiry_date'" width="90" align="right">Expiry Date</th>   
                     <th data-options="field:'pay_term_id'" width="80">Pay Term</th>
                     <th data-options="field:'exch_rate'" width="60">Exch Rate</th>
                     <th data-options="field:'ud_no'" width="80">UD No</th>
                     <th data-options="field:'ud_date'" width="90" align="center">UD Date</th>
                     <th data-options="field:'days_taken_ud'" width="100">Days Taken UD<br>/Overdue</th>
                     <th data-options="field:'approval'" width="100">Approval</th>
                  </tr>
               </thead>
            </table>
         </div>
         <div data-options="region:'west',border:true,title:'Add Import LC',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'"
            style="width:450px; padding:2px">
            <div id="container">
               <div id="body">
                  <code>
                     <form id="implcFrm">
                        <div class="row">
                              <input type="hidden" name="id" id="id" value="" />
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4 req-text">Company</div>
                           <div class="col-sm-8">
                              {!! Form::select('company_id',
                              $company,'',array('id'=>'company_id','onchange'=>'MsImpLc.setBankAccount(this.value)')) !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4 req-text">Supplier</div>
                           <div class="col-sm-8">
                              {!! Form::select('supplier_id',
                              $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">LC To</div>
                           <div class="col-sm-8">
                              {!! Form::select('lc_to_id',
                              $supplier,'',array('id'=>'lc_to_id','style'=>'width: 100%; border-radius:2px')) !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4 req-text">LC Type</div>
                           <div class="col-sm-8">
                              {!! Form::select('lc_type_id',
                              $lctype,'',array('id'=>'lc_type_id')) !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4 req-text">PO Type</div>
                           <div class="col-sm-8">
                              {!! Form::select('menu_id',
                              $menu,'',array('id'=>'menu_id')) !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4 req-text">Issuing Bank</div>
                           <div class="col-sm-8">
                              {!! Form::select('issuing_bank_branch_id',
                              $bankbranch,'',array('id'=>'issuing_bank_branch_id','onchange'=>'MsImpLc.setBankAccount(this.value)')) !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4 req-text">Bank Limit A/C</div>
                           <div class="col-sm-8">
                             <input type="text" name="commercial_head_name" id="commercial_head_name" ondblclick="MsImpLc.implcBankWindowOpen()" placeholder="Double Click To Select">
                             <input type="hidden" name="bank_account_id" id="bank_account_id" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4 req-text">Last Delivery Date </div>
                           <div class="col-sm-8">
                              <input type="text" name="last_delivery_date" id="last_delivery_date" class="datepicker"
                                 placeholder="yy-mm-dd" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4 req-text">Expiry Date </div>
                           <div class="col-sm-8">
                              <input type="text" name="expiry_date" id="expiry_date" class="datepicker"
                                 placeholder="yy-mm-dd" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Expiry Place</div>
                           <div class="col-sm-8">
                              <input type="text" name="expiry_place" id="expiry_place" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">LC No</div>
                           <div class="col-sm-8">
                              <input type="text" name="lc_no_i" id="lc_no_i" style="width: 50px" />
                              <input type="text" name="lc_no_ii" id="lc_no_ii" style="width: 30px" />
                              <input type="text" name="lc_no_iii" id="lc_no_iii" style="width: 30px" />
                              <input type="text" name="lc_no_iv" id="lc_no_iv" style="width: 138px" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">LC Application Date</div>
                           <div class="col-sm-8">
                              <input type="text" name="lc_application_date" id="lc_application_date" class="datepicker" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">LC Date</div>
                           <div class="col-sm-8">
                              <input type="text" name="lc_date" id="lc_date" class="datepicker" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">LCA NO</div>
                           <div class="col-sm-8">
                              <input type="text" name="lca_no" id="lca_no">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">LCAF NO</div>
                           <div class="col-sm-8">
                              <input type="text" name="lcaf_no" id="lcaf_no" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">IMP FORM NO</div>
                           <div class="col-sm-8">
                              <input type="text" name="imp_form_no" id="imp_form_no" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Debit A/C</div>
                           <div class="col-sm-8">
                              {!! Form::select('debit_ac_id',
                                 $bankaccount,'',array('id'=>'debit_ac_id')) !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Doc Release</div>
                           <div class="col-sm-8">
                              <textarea name="doc_release" id="doc_release"></textarea>
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Commodity</div>
                           <div class="col-sm-8">
                              <input type="text" name="commodity" id="commodity" />
                           </div>
                        </div>
                       <!--  <div class="row middle">
                           <div class="col-sm-4 req-text">LC Value </div>
                           <div class="col-sm-8">
                              <input type="text" name="" id="" value="" class="number integer" placeholder="display sum of PO" />
                           </div>
                        </div> -->
                        <div class="row middle">
                           <div class="col-sm-4 req-text">Currency</div>
                           <div class="col-sm-8">
                                 {!! Form::select('currency_id',
                              $currency,'',array('id'=>'currency_id',)) !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4 req-text">Ex. Rate </div>
                           <div class="col-sm-8">
                              <input type="text" name="exch_rate" id="exch_rate" class="integer number" onchange="MsImpLc.changeExchRate()" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Supplier's Bank</div>
                           <div class="col-sm-8">
                              <input type="text" name="suppliers_bank" id="suppliers_bank" placeholder="write">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Re-imbursing Bank</div>
                           <div class="col-sm-8">
                              <input type="text" name="re_imbursing_bank" id="re_imbursing_bank" placeholder="write">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4 req-text">Delivery Mode </div>
                           <div class="col-sm-8">
                              {!! Form::select('delivery_mode_id', $deliveryMode,'',array('id'=>'delivery_mode_id'))
                              !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Port of Entry </div>
                           <div class="col-sm-8">
                              <input type="text" name="port_of_entry" id="port_of_entry" value="">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Port of Loading </div>
                           <div class="col-sm-8">
                              <input type="text" name="port_of_loading" id="port_of_loading" value="">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Port of Discharge </div>
                           <div class="col-sm-8">
                              <input type="text" name="port_of_discharge" id="port_of_discharge" value="">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Final Destination </div>
                           <div class="col-sm-8">
                              <input type="text" name="final_destination" id="final_destination" value="">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4 req-text">Pay Term </div>
                           <div class="col-sm-8">
                              {!! Form::select('pay_term_id', $payterm,'',array('id'=>'pay_term_id')) !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4 req-text">Tenor </div>
                           <div class="col-sm-8">
                              <input type="text" name="tenor" id="tenor" class="number integer">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Incoterm </div>
                           <div class="col-sm-8">
                              {!! Form::select('incoterm_id', $incoterm,'',array('id'=>'incoterm_id')) !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Inco Term Place </div>
                           <div class="col-sm-8">
                              <input type="text" name="incoterm_place" id="incoterm_place">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Partial Shipment </div>
                           <div class="col-sm-8">
                              {!! Form::select('partial_shipment_id', $yesno,'',array('id'=>'partial_shipment_id'))
                              !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Transhipment </div>
                           <div class="col-sm-8">
                              {!! Form::select('transhipment_id', $yesno,'',array('id'=>'transhipment_id'))
                              !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Add Conf. Req.</div>
                           <div class="col-sm-8">
                              {!! Form::select('add_conf_ref_id', $yesno,'',array('id'=>'add_conf_ref_id')) !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Add Conf. Bank</div>
                           <div class="col-sm-8">
                              <input type="text" name="add_conf_bank" id="add_conf_bank" placeholder="write">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Add Conf. Charge</div>
                           <div class="col-sm-8">
                              {!! Form::select('add_conf_charge_id', $inoutcharges,'',array('id'=>'add_conf_charge_id')) !!}     
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">PSI Company</div>
                           <div class="col-sm-8">
                              <input type="text" name="psi_company" id="psi_company" placeholder="write">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Maturity From</div>
                           <div class="col-sm-8">                            
                               {!! Form::select('maturity_form_id', $maturityform,'',array('id'=>'maturity_form_id')) !!} 
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Credit To Be Advised</div>
                           <div class="col-sm-8">
                              <input type="text" name="credit_to_be_advised" id="credit_to_be_advised" placeholder="write">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">ETD Port </div>
                           <div class="col-sm-8">
                              <input type="text" name="etd_port" id="etd_port" class="datepicker" placeholder="yy-mm-dd">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">ETA Port </div>
                           <div class="col-sm-8">
                              <input type="text" name="eta_port" id="eta_port" class="datepicker" placeholder="yy-mm-dd">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">HS Code </div>
                           <div class="col-sm-8">
                              <input type="text" name="hs_code" id="hs_code" value="" placeholder="write" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Clearing Agent</div>
                           <div class="col-sm-8">
                              {!! Form::select('clearing_agent_id', $yesno,'',array('id'=>'clearing_agent_id'))
                              !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Shipping Line</div>
                           <div class="col-sm-8">
                              {!! Form::select('shipping_line_id', $shippingLines,'',array('id'=>'shipping_line_id'))
                              !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Insurance Company</div>
                           <div class="col-sm-8">
                              
                              {!! Form::select('insurance_company_id',
                              $insuranceCompany,'',array('id'=>'insurance_company_id')) !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Cover Note NO</div>
                           <div class="col-sm-8">
                              <input type="text" name="cover_note_no" id="cover_note_no" placeholder="Write">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Cover Note Date</div>
                           <div class="col-sm-8">
                              <input type="text" name="cover_note_date" id="cover_note_date" class="datepicker" placeholder="yy-mm-dd" />
                           </div>
                        </div>                                            
                        <div class="row middle">
                           <div class="col-sm-4">Origin</div>
                           <div class="col-sm-8">
                              {!! Form::select('origin_id', $country,'',array('id'=>'origin_id')) !!}            
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">UD No</div>
                           <div class="col-sm-8">
                              <input type="text" name="ud_no" id="ud_no">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">UD Date</div>
                           <div class="col-sm-8">
                              <input type="text" name="ud_date" id="ud_date" class="datepicker" placeholder="yy-mm-dd" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Margin Deposit %</div>
                           <div class="col-sm-8">
                              <input type="text" name="margin_deposit" id="margin_deposit" class="number integer">
                           </div>
                        </div>                    
                        <div class="row middle">
                           <div class="col-sm-4">Tolerance %</div>
                           <div class="col-sm-8">
                              <input type="text" name="tolerance" id="tolerance" class="number integer">
                           </div>
                        </div>                    
                        <div class="row middle">
                           <div class="col-sm-4">Shipping Mark</div>
                           <div class="col-sm-8">
                              <input type="text" name="" id="" placeholder="from many marks">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Doc Present Days</div>
                           <div class="col-sm-8">
                                 <input type="text" name="doc_present_days" id="doc_present_days" class="number integer">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Bonded Warehouse</div>
                           <div class="col-sm-8">
                              {!! Form::select('bonded_warehouse_id', $yesno,'',array('id'=>'bonded_warehouse_id')) !!}     
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Advising Bank</div>
                           <div class="col-sm-8">
                              <input type="text" name="advise_bank" id="advise_bank" value="" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Outside Charge BD</div>
                           <div class="col-sm-8">
                              {!! Form::select('outside_charge_id', $inoutcharges,'',array('id'=>'outside_charge_id')) !!}     
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Inside Charge BD</div>
                           <div class="col-sm-8">
                              {!! Form::select('inside_charge_id', $inoutcharges,'',array('id'=>'inside_charge_id')) !!}     
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Gmts. Qnty</div>
                           <div class="col-sm-8">
                                 <input type="text" name="gmts_qty" id="gmts_qty" class="number integer">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Other Terms&Conditions</div>
                           <div class="col-sm-8">
                              <textarea name="other_terms_condition" id="other_terms_condition"></textarea>
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Ready to Approve</div>
                           <div class="col-sm-8">
                               {!! Form::select('ready_to_approve_id', $yesno,'0',array('id'=>'ready_to_approve_id')) !!}
                           </div>
                       </div>
                     </form>
                  </code>
               </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#CCC;">
               <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsImpLc.submit()">Save</a>
               <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('implcFrm')">Reset</a>
               <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsImpLc.remove()">Delete</a>
               <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsImpLc.latter()">Latter</a>
               <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="delete" onClick="MsImpLc.creditletter()">App.Form</a>
            </div>
         </div>
      </div>
   </div>
   <!------------===========LC SALES ORDER=================-------------->
   <div title="Tag PO" style="padding:2px">
      <div class="easyui-layout" data-options="fit:true">
          <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
              <table id="implcpoTbl" style="width:100%">
                  <thead>
                   <tr>
                       <th data-options="field:'id'" width="40">ID</th>
                       <th data-options="field:'po_no'" width="70" formatter="MsImpLcPo.formatpdf">PO No</th>
                       <th data-options="field:'company_name'" width="60">Company</th>
                       <th data-options="field:'supplier_name'" width="60">Supplier</th> 
                       <th data-options="field:'po_qty'" width="60" align="right"> Po Qty</th>
                       <th data-options="field:'amount'" width="60" align="right"> Amount</th>   
                       <th data-options="field:'currency_name'" width="60"> Currency</th>
                       <th data-options="field:'delete'" width="100" formatter="MsImpLcPo.formatDetail">Delete</th>  
                   </tr>
               </thead>
              </table>
          </div>
          <div data-options="region:'west',border:true,title:'PO',footer:'#ft3'" style="width: 350px; padding:2px">     
            <div class="easyui-layout" data-options="fit:true">
               <div data-options="region:'north',border:true" style="height: 100px; padding:2px">
                  <form id="implcpoFrm">
                        <div id="container">
                           <div id="body">
                              <code>
                                    <div class="row middle" style="display:none">
                                       <input type="hidden" name="imp_lc_id" id="imp_lc_id" value="" />
                                       <input type="hidden" name="id" id="id"  />
                                    </div>
                                    <div class="row middle">
                                       <div class="col-sm-4">PO ID</div>
                                       <div class="col-sm-8">
                                          <input type="text" name="purchase_order_id" id="purchase_order_id" value="" />
                                       </div>
                                    </div>
                                    {{-- <div class="row middle">
                                       <div class="col-sm-4">PO No</div>
                                       <div class="col-sm-8">
                                          <input type="text" name="po_no" id="po_no">
                                       </div>
                                    </div> --}}
                              </code>
                           </div>
                        </div>
                        <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                           <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsImpLcPo.importPo()">Search</a>
                           <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsImpLcPo.submit()">Tag</a>
                        </div>
                  </form>
               </div>
               <div data-options="region:'center',border:true,title:'List'">
                  <table id="implcposearchTbl" style="width:100%">
                        <thead>
                        <tr>
                           <th data-options="field:'id'" width="40">ID</th>
                           <th data-options="field:'po_no'" width="70">PO No</th>
                           <th data-options="field:'company_name'" width="60">Company</th>
                           <th data-options="field:'supplier_name'" width="60">Supplier</th>    
                           <th data-options="field:'currency_name'" width="60"> Currency</th> 
                           <th data-options="field:'amount'" width="60">Amount</th> 
                        </tr>
                     </thead>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!----============  Backed Exp SC/LC  =============------>
   <div title="Backed By Export LC/SC" style="padding:2px">
      <div class="easyui-layout" data-options="fit:true">
         <div data-options="region:'west',border:true,title:'Export LC / SC',iconCls:'icon-more',footer:'#impbackedexplcscft'" style="width:450px; padding:2px">
             <div class="easyui-layout" data-options="fit:true">
               <div data-options="region:'north',border:true" style="height:125px; padding:2px">
               <form id="impbackedexplcscFrm">
                  <div id="container">
                     <div id="body">
                        <code>                            
                           <div class="row">
                              <div class="col-sm-4">LC/SC Number </div>
                              <div class="col-sm-8">
                                 <input type="text" name="lc_sc_no" id="lc_sc_no"/>
                                 <input type="hidden" name="imp_lc_id" id="imp_lc_id" value=""/>
                                 <input type="hidden" name="id" id="id" value="" />
                              </div>
                           </div>
                           <div class="row middle">
                              <div class="col-sm-4 req-text">LC/SC Date </div>
                              <div class="col-sm-8">
                                 <input type="text" name="lc_sc_date" id="lc_sc_date" />
                              </div>
                           </div>
                        </code>
                     </div>
                  </div>
                  <div id="impbackedexplcscft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                     <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsImpBackedExpLcSc.importlcsc()">Search</a>
                     <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsImpBackedExpLcSc.submit()">Tag</a>
                  </div>
               </form>
         </div>
         <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
             <table id="impbackedexplcscsearchTbl" style="width:100%">
               <thead>
                  <tr>
                     <th data-options="field:'id'" width="40">ID</th>
                     <th data-options="field:'lc_sc_no'" width="80">LC/SC Number</th>
                     <th data-options="field:'lc_sc_value'" width="100">Value</th>
                     <th data-options="field:'file_no'" width="100">File No</th>
                     <th data-options="field:'buyer'" width="100">Buyer</th>
                     
                  </tr>
               </thead>
            </table>
         </div>
         </div>
         </div>
         <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
            <table id="impbackedexplcscTbl" style="width:100%">
               <thead>
                 <tr>
                     <th data-options="field:'id'" width="40">ID</th>
                     <th data-options="field:'lc_sc_no'" width="80">LC/SC Number</th>
                     <th data-options="field:'lc_sc_value'" width="100">Value</th>
                     <th data-options="field:'buyer'" width="80">Buyer</th>
                     <th data-options="field:'file_no'" width="80">File No</th>
                     <th data-options="field:'amount'" width="100">Amount</th>
                     <th data-options="field:'delete'" width="100" formatter="MsImpBackedExpLcSc.formatDetail">Delete</th>
                  </tr>
               </thead>
            </table>
         </div>
      </div>
   </div>
   <!------============     Shipping Mark    ================--------->
   <div title="Shipping Mark" style="padding:2px">
      <div class="easyui-layout" data-options="fit:true">
         <div data-options="region:'west',border:true,title:'',iconCls:'icon-more',footer:'#comshipmarkft'" style="width:450px; padding:2px">
            <form id="impshippingmarkFrm">
               <div id="container">
                  <div id="body">
                     <code>                            
                           <div class="row">
                              <div class="col-sm-4">Shipping Mark </div>
                              <div class="col-sm-8">
                                 <textarea name="shipping_mark" id="shipping_mark"></textarea>
                                 <input type="hidden" name="imp_lc_id" id="imp_lc_id" value=""/>
                                 <input type="hidden" name="id" id="id" value="" />
                              </div>
                           </div>
                     </code>
                  </div>
               </div>
               <div id="comshipmarkft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                  <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsImpShippingMark.submit()">Save</a>
                  <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('impshippingmarkFrm')">Reset</a>
                  <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsImpShippingMark.remove()">Delete</a>
               </div>
            </form>
         </div>
         <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
            <table id="impshippingmarkTbl" style="width:100%">
               <thead>
                  <tr>
                     <th data-options="field:'id'" width="40">ID</th>
                     <th data-options="field:'shipping_mark'" width="80">Shipping Mark </th>
                  </tr>
               </thead>
            </table>
         </div>
      </div>
   </div> 
   <!------============     Bank Charges/ Margin    ================--------->
   <div title="Bank Charges/ Margin" style="padding:2px">
      <div class="easyui-layout" data-options="fit:true">
         <div data-options="region:'west',border:true,title:'',iconCls:'icon-more',footer:'#combankchargeft'" style="width:450px; padding:2px">
            <form id="impbankchargeFrm">
               <div id="container">
                  <div id="body">
                     <code>                            
                           <div class="row">
                              <div class="col-sm-4">Account Head </div>
                              <div class="col-sm-8">
                                 <input type="text" name="amount" id="amount" placeholder=" Display" readonly />
                                 <input type="hidden" name="imp_lc_id" id="imp_lc_id" value=""/>
                                 <input type="hidden" name="id" id="id" value="" />
                              </div>
                           </div>
                     </code>
                  </div>
               </div>
               <div id="combankchargeft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                  <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsImpBankCharge.submit()">Save</a>
                  <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('impbankchargeFrm')">Reset</a>
                  <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsImpBankCharge.remove()">Delete</a>
               </div>
            </form>
         </div>
         <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
            <table id="impbankchargeTbl" style="width:100%">
               <thead>
                  <tr>
                     <th data-options="field:'id'" width="40">ID</th>
                     <th data-options="field:'Amount'" width="80">LC/SC Number</th>
                     <th data-options="field:'Account Head'" width="100">Value</th>
                  </tr>
               </thead>
            </table>
         </div>
      </div>
   </div>
   <!-------- File Upload---------------->
   <div title="Files" style="padding:2px">
      <div class="easyui-layout" data-options="fit:true">
          <div data-options="region:'west',border:true,title:'Upload File',iconCls:'icon-more',footer:'#implcfileft'" style="width:450px; padding:2px">
              <form id="implcfileFrm" enctype="multipart/form-data">
                  <div id="container">
                      <div id="body">
                          <code>
                              <div class="row">
                                  <input type="hidden" name="id" id="id" value="" />
                                  <input type="hidden" name="imp_lc_id" id="imp_lc_id" value=""/>
                              </div>
                              <div class="row middle">
                                 <div class="col-sm-4 req-text">File Name</div>
                                 <div class="col-sm-8">
                                     <input type="text" id="original_name" name="original_name" value="" />
                                 </div>
                             </div>
                              <div class="row middle">
                                 <div class="col-sm-4 req-text">File Upload</div>
                                 <div class="col-sm-8">
                                     <input type="file" id="file_src" name="file_src" value="" />
                                 </div>
                              </div>
                           </code>
                        </div>
                  </div>
                  <div id="implcfileft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsImpLcFile.submit()">Upload</a>
                      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('implcfileFrm')">Reset</a>
                      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsImpLcFile.remove()">Delete</a>
                  </div>
              </form>
          </div>
          <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
              <table id="implcfileTbl" style="width:100%">
                  <thead>
                      <tr>
                          <th data-options="field:'id'" width="80">ID</th>
                          <th data-options="field:'original_name'" width="120">Original Name</th>
                          <th data-options="field:'file_src'" width="80" formatter="MsImpLcFile.formatFile" >Download Files</th>
                      </tr>
                  </thead>
              </table>
          </div>
      </div>
  </div>
</div>  
<!--------------=======  Imp LC Order Search Window  =================------------>
<div id="implsorderwindow" class="easyui-window" title="Sales Order Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
   <div class="easyui-layout" data-options="fit:true">
      <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
         <div class="easyui-layout" data-options="fit:true">
            <div id="body">
               <code>
                  <form id="implcposearchFrm">
                      <div class="row middle">
                        <div class="col-sm-4">Style Ref</div>
                        <div class="col-sm-8">
                           <input type="text" name="style_ref" id="style_ref" value="">
                        </div>
                      </div>
                      <div class="row middle">
                        <div class="col-sm-4">Job </div>
                        <div class="col-sm-8">
                           <input type="text" name="job_no" id="job_no" value="">
                        </div>
                      </div>
                  </form>
               </code>
            </div>
            <p class="footer">
               <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsImpLcPo.searchOrder()">Search</a>
            </p>
         </div>
     </div>
     <div data-options="region:'center'" style="padding:10px;">
         <table id="implcposearchTbl" style="width:100%">
            <thead>
               <tr>
                     <th data-options="field:'id'" width="40">ID</th>
                     <th data-options="field:'style_ref'" width="70">Style Ref</th>
                     <th data-options="field:'job_no'" width="100">Job No</th>
                     <th data-options="field:'sale_order_no'" width="100">Sale Order No</th>
                     <th data-options="field:'item_description'" width="100">Item</th>    
                     <th data-options="field:'uom_name'" width="100"> UOM</th> 
                     <th data-options="field:'qty'" width="100" align="right"> Order Qty</th> 
                     <th data-options="field:'rate'" width="100" align="right"> Rate</th> 
                     <th data-options="field:'amount'" width="100" align="right"> Amount</th>
                     <th data-options="field:'cumulative_qty'" width="100" align="right"> Cumulative Qty</th>
                     <th data-options="field:'balance_qty'" width="100" align="right">Balance Qty</th>                              
                 </tr>
             </thead>
         </table>
     </div>
     <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
         <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="MsImpLcPo.closeImpLcPorWindow()" style="width:80px">Close</a>
     </div>
   </div>
</div>
   
<div id="implctagorderwindow" class="easyui-window" title="Purchase Order Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:100%;padding:2px;">
   <div class="easyui-layout" data-options="fit:true">  
      <div data-options="region:'center',footer:'#implctagorderScsft'" style="padding:10px;">
         <form id="implctagorderFrm">
            <code id="implctagorderScs">
            </code>

            <div id="implctagorderScsft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
               <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsImpLcPo.submitBatch()">Save</a>
               <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('implctagorderFrm')" >Reset</a>
            </div>
         </form>     
      </div>
   </div>
</div>

{{-- openbanckaccount window open --}}
<div id="implcopenimplcbankaccountWindow" class="easyui-window" title="Bank Account Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',border:true,footer:'#implcbankaccountsearchTblFt'" style="padding:2px">
   <table id="implcbankaccountsearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="80">ID</th>
      <th data-options="field:'name'" width="100">Name</th>
      <th data-options="field:'account_no'" width="100">A/C No.</th>
      <th data-options="field:'commercial_head_name'" width="100">A/C Type</th>
      <th data-options="field:'branch_name'" width="100">Branch Name</th>
     </tr>
    </thead>
   </table>
   <div id="implcbankaccountsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
     onclick="$('#implcopenimplcbankaccountWindow').window('close')" style="width:80px">Close</a>
   </div>
  </div>
  <div data-options="region:'west',border:true,footer:'#bankaccountsearchFrmFt'" style="padding:2px; width:350px">
   <form id="bankaccountsearchFrm">
    <div id="container">
      <div id="body">
         <code>
          <div class="row">
              <div class="col-sm-4 req-text">Name</div>
              <div class="col-sm-8">
                  <input type="text" name="branch_name" id="name" />
              </div>
          </div>
          <div class="row middle">
              <div class="col-sm-4 req-text">Account No. </div>
              <div class="col-sm-8">
                  <input type="text" name="account_no" id="account_no" value="" />
              </div>
          </div>
      </code>
     </div>
    </div>
    <div id="bankaccountsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true"
      onClick="MsImpLc.implcSearchBankAccount()">Search</a>
    </div>
   </form>
  </div>
 </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Commercial/Import/MsAllImpLcController.js"></script>
<script>
   (function(){
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
      
      $('#implcFrm [id="supplier_id"]').combobox();
      $('#implcFrm [id="lc_to_id"]').combobox();
      
      $('#last_delivery_date').change(function(){
        MsImpLc.setExpiryDate();
      });

   })(jQuery);

</script>
