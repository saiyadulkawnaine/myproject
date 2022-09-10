<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="comexplctabs">
   <div title="Export LC" style="padding:2px">
      <div class="easyui-layout" data-options="fit:true">
         <div data-options="region:'center',border:true,title:' LC list'" style="padding:2px">
            <table id="explcTbl" style="width:100%">
               <thead>
                  <tr>
                     <th data-options="field:'id'" width="60">ID</th>
                     <th data-options="field:'lc_sc_no'" width="110">LC No</th>
                     <th data-options="field:'exporter_bank_branch'" width="180">Exporters Bank</th>
                     <th data-options="field:'beneficiary'" width="60">Beneficiary</th>
                     <th data-options="field:'buyer'" width="60">Buyer</th>
                     <th data-options="field:'lc_sc_date'" width="80">LC Date</th>
                     <th data-options="field:'last_delivery_date'" width="80">Last Delivery Date</th>
                     <th data-options="field:'lc_sc_nature_id'" width="80">LC Nature</th>
                     <th data-options="field:'lc_sc_value'" width="100" align="right">LC value</th>
                     <th data-options="field:'currency'" width="60">Currency</th>
                     <th data-options="field:'exch_rate'" width="70">Exch Rate</th>
                     <th data-options="field:'file_no'" width="80">File No</th>
                     <th data-options="field:'pay_term'" width="70">Pay Term</th>
                     <th data-options="field:'tenor'" width="60">Tenor</th>
                     <th data-options="field:'incoterm'" width="60">Incoterm</th>
                     <th data-options="field:'exporting_item'" width="60">Exporting Item</th>
                     <th data-options="field:'buyers_bank'" width="100">Buyers Bank</th>
                     <th data-options="field:'transfer_bank'" width="100">Transferring Bank</th>
                     <th data-options="field:'consignee'" width="100">Consignee</th>
                     <th data-options="field:'notifying_party'" width="100">Notifying Party</th>
                     <th data-options="field:'last_delivery_date'" width="80">Last Delivery</th>
                     <th data-options="field:'eta_port'" width="80">ETA Port</th>
                     <th data-options="field:'hs_code'" width="80">HS Code</th>
                     <th data-options="field:'local_commission_per'" width="80">Local Commission%</th>
                     <th data-options="field:'foreign_commission_per'" width="80">Foreign Commission%</th>
                     <th data-options="field:'remarks'" width="120">Remarks</th>
                  </tr>
               </thead>
            </table>
         </div>
         <div data-options="region:'west',border:true,title:'Add Export LC',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'"
            style="width:450px; padding:2px">
            <div id="container">
               <div id="body">
                  <code>
                     <form id="explcFrm">
                        <div class="row middle">
                           <div class="col-sm-5 req-text">LC No</div>
                           <div class="col-sm-7">
                              <input type="text" name="lc_sc_no" id="lc_sc_no" />
                              <input type="hidden" name="id" id="id" value="" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5 req-text">LC Date</div>
                           <div class="col-sm-7">
                              <input type="text" name="lc_sc_date" id="lc_sc_date" class="datepicker" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5 req-text">LC Nature</div>
                           <div class="col-sm-7">
                              {!! Form::select('lc_sc_nature_id',
                              $contractNature,'',array('id'=>'lc_sc_nature_id')) !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5 req-text">LC Value </div>
                           <div class="col-sm-7">
                              <input type="text" name="lc_sc_value" id="lc_sc_value" value="" class="number integer" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">File No </div>
                           <div class="col-sm-7">
                               <input type="text" name="file_no" id="file_no">
                           </div>
                       </div>
                        <div class="row middle">
                           <div class="col-sm-5 req-text">Currency</div>
                           <div class="col-sm-7">
                              {!! Form::select('currency_id', $currency,'',array('id'=>'currency_id')) !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5 req-text">Ex. Rate </div>
                           <div class="col-sm-7">
                              <input type="text" name="exch_rate" id="exch_rate" class="integer number" onchange="MsExpLcSc.changeExchRate()" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5 req-text">Pay Term </div>
                           <div class="col-sm-7">
                              {!! Form::select('pay_term_id', $payterm,'',array('id'=>'pay_term_id')) !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Tenor </div>
                           <div class="col-sm-7">
                              <input type="text" name="tenor" id="tenor" class="number integer">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5 req-text">Incoterm </div>
                           <div class="col-sm-7">
                              {!! Form::select('incoterm_id', $incoterm,'',array('id'=>'incoterm_id')) !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Inco Term Place </div>
                           <div class="col-sm-7">
                              <input type="text" name="incoterm_place" id="incoterm_place">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5 req-text">Exporting Item </div>
                           <div class="col-sm-7">
                              {!! Form::select('exporting_item_id', $exportingItem,'',array('id'=>'exporting_item_id'))
                              !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5 req-text">Beneficiary </div>
                           <div class="col-sm-7">
                              {!! Form::select('beneficiary_id', $company,'',array('id'=>'beneficiary_id'))
                              !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5 req-text">Buyer</div>
                           <div class="col-sm-7">
                              {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Buyer's Bank</div>
                           <div class="col-sm-7">
                              <input type="text" name="buyers_bank" id="buyers_bank">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Transferring Bank</div>
                           <div class="col-sm-7">
                              <textarea name="transfer_bank" id="transfer_bank"></textarea>
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Advising Bank</div>
                           <div class="col-sm-7">
                              <textarea name="advise_bank" id="advise_bank"></textarea>
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Exporters bank Branch</div>
                           <div class="col-sm-7">
                              
                               {!! Form::select('exporter_bank_branch_id', $bankbranch,'',array('id'=>'exporter_bank_branch_id')) !!} 
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5 req-text">Lien Date </div>
                           <div class="col-sm-7">
                              <input type="text" name="lien_date" id="lien_date" class="datepicker" placeholder="yy-mm-dd" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Re-imbursing Bank</div>
                           <div class="col-sm-7">
                              <input type="text" name="re_imbursing_bank" id="re_imbursing_bank" placeholder="write">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5 req-text">Consignee</div>
                           <div class="col-sm-7">
                              {!! Form::select('consignee_id', $consignee,'',array('id'=>'consignee_id','style'=>'width: 100%; border-radius:2px')) !!} 
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Notifying Party</div>
                           <div class="col-sm-7">
                              
                              {!! Form::select('notifying_party_id', $notifyingParties,'',array('id'=>'notifying_party_id','style'=>'width: 100%; border-radius:2px')) !!} 
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">2nd Notifying Party</div>
                           <div class="col-sm-7">
                              {!! Form::select('second_notifying_party_id', $notifyingParties,'',array('id'=>'second_notifying_party_id','style'=>'width: 100%; border-radius:2px')) !!} 
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5 req-text">Last Delivery Date </div>
                           <div class="col-sm-7">
                              <input type="text" name="last_delivery_date" id="last_delivery_date" class="datepicker"
                                 placeholder="yy-mm-dd" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5 req-text">Delivery Mode </div>
                           <div class="col-sm-7">
                              {!! Form::select('delivery_mode_id', $deliveryMode,'',array('id'=>'delivery_mode_id'))
                              !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Port of Entry </div>
                           <div class="col-sm-7">
                              <input type="text" name="port_of_entry" id="port_of_entry" value="">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Port of Loading </div>
                           <div class="col-sm-7">
                              <input type="text" name="port_of_loading" id="port_of_loading" value="">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Port of Discharge </div>
                           <div class="col-sm-7">
                              <input type="text" name="port_of_discharge" id="port_of_discharge" value="">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Final Destination </div>
                           <div class="col-sm-7">
                              <input type="text" name="final_destination" id="final_destination" value="">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">ETD Port </div>
                           <div class="col-sm-7">
                              <input type="text" name="etd_port" id="etd_port" class="datepicker" placeholder="yy-mm-dd">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">ETA Port </div>
                           <div class="col-sm-7">
                              <input type="text" name="eta_port" id="eta_port" class="datepicker" placeholder="yy-mm-dd">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">HS Code </div>
                           <div class="col-sm-7">
                              <input type="text" name="hs_code" id="hs_code" value="">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Local Commission%</div>
                           <div class="col-sm-7">
                              <input type="text" name="local_commission_per" id="local_commission_per" value="" class="number integer" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Foreign Commission%</div>
                           <div class="col-sm-7">
                              <input type="text" name="foreign_commission_per" id="foreign_commission_per" value="" class="number integer" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Forwarding Agent</div>
                           <div class="col-sm-7">
                              
                              {!! Form::select('forwarding_agent_id', $forwardingAgents,'',array('id'=>'forwarding_agent_id','style'=>'width: 100%; border-radius:2px'))
                              !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Shipping Line</div>
                           <div class="col-sm-7">
                              
                              {!! Form::select('shipping_line_id', $shippingLines,'',array('id'=>'shipping_line_id','style'=>'width: 100%; border-radius:2px'))
                              !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Remarks</div>
                           <div class="col-sm-7">
                              <input type="text" name="remarks" id="remarks" />
                           </div>
                        </div>
                     </form>
                  </code>
               </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#CCC;">
               <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsExpLc.submit()">Save</a>
               <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('explcFrm')">Reset</a>
               <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsExpLc.remove()">Delete</a>
               <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true" id="pdf" onClick="MsExpLc.lcLienLetter()">LN Letter</a>
               <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true" id="pdf" onClick="MsExpLc.lcAmendmentLetter()">Amnd Letter</a>
            </div>
         </div>
      </div>
   </div>
   <!--==========Lc Tag PI Tab=================-->
   <div title="Tag PI" style="padding:2px">
         <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Search'"
               style="width:450px; padding:2px">
               <div class="easyui-layout" data-options="fit:true">
               <div data-options="region:'north',border:true,title:'Add PI',iconCls:'icon-more',footer:'#lctagpift'"
               style="height:180px; padding:2px">
               <form id="lctagpisearchFrm">
                  <div id="container">
                     <div id="body">
                        <code>
                           <div class="row middle">
                              <div class="col-sm-4">PI No </div>
                              <div class="col-sm-8">
                                 <input type="text" name="pi_no" id="pi_no" />
                              </div>
                           </div>
                           <div class="row middle">
                              <div class="col-sm-4">Style Ref </div>
                              <div class="col-sm-8">
                                 <input type="text" name="style_ref" id="style_ref" />
                              </div>
                           </div>
                            <div class="row middle">
                              <div class="col-sm-4">Job No </div>
                              <div class="col-sm-8">
                                 <input type="text" name="job_no" id="job_no" />
                              </div>
                           </div>
                           <div class="row middle">
                              <div class="col-sm-4">Order No </div>
                              <div class="col-sm-8">
                                 <input type="text" name="order_no" id="order_no" value="" class="number integer"
                                    onclick="" />
                              </div>
                           </div>                     
                           
                        </code>
                     </div>
                  </div>
                  <div id="lctagpift" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                     <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsExpLcTagPi.importPi()">Search</a>
                     <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('explctagpiFrm')">Reset</a>
                     <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsExpLcTagPi.submit()">Tag</a>
                     
                  </div>  
               </form>
               </div> 
               <div data-options="region:'center',border:true,title:'List'">
                   <table id="explctagpisearchTbl" style="width:100%">
                  <thead>
                     <tr>
                        <th data-options="field:'id'" width="80">ID</th>
                        <th data-options="field:'pi_no'" width="80">PI NO</th>
                        <th data-options="field:'qty'" width="100" align="right">PI Qty</th>
                        <th data-options="field:'rate'" width="100" align="right">Price/Unit</th>
                        <th data-options="field:'amount'" width="100" align="right">PI Value</th>
                     </tr>
                  </thead>
               </table>
               </div>
               </div> 
               
            </div>
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
               <table id="explctagpiTbl" style="width:100%">
                  <thead>
                     <tr>
                        <th data-options="field:'id'" width="80">ID</th>
                        <th data-options="field:'exp_pi_id'" width="80">PI ID</th>
                        <th data-options="field:'pi_no'" width="80">PI NO</th>
                        
                        <th data-options="field:'qty'" width="100" align="right">PI Qty</th>
                        <th data-options="field:'rate'" width="100" align="right">Price/Unit</th>
                        <th data-options="field:'amount'" width="100" align="right">PI Value</th>
                     </tr>
                  </thead>
               </table>
            </div>
         </div>
   </div>
   <!------------===========LC SALES ORDER=================-------------->
   <div title="Tag Order" style="padding:2px">
      <div class="easyui-layout" data-options="fit:true">
          <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
              <table id="explcorderTbl" style="width:100%">
                  <thead>
                   <tr>
                       <th data-options="field:'id'" width="40">ID</th>
                       <th data-options="field:'style_ref'" width="70">Style Ref</th>
                       <th data-options="field:'job_no'" width="100">Job No</th>
                       <th data-options="field:'sale_order_no'" width="100">Sale Order No</th>
                       <th data-options="field:'item_description'" width="100">Item</th>    
                       <th data-options="field:'uom_name'" width="100"> UOM</th> 
                       <th data-options="field:'qty'" width="100" align="right">Qty</th> 
                       <th data-options="field:'rate'" width="100" align="right"> Rate</th> 
                       <th data-options="field:'amount'" width="100" align="right"> Amount</th>         
                       
                   </tr>
               </thead>
              </table>
          </div>
          <div data-options="region:'west',border:true,title:'Sales Order',footer:'#ft3'" style="width: 350px; padding:2px">
              <form id="explcorderFrm">
                  <div id="container">
                      <div id="body">
                          <code>
                               <div class="row middle" style="display:none">
                                 <input type="hidden" name="exp_lc_sc_id" id="exp_lc_sc_id" value="" />
                                 <input type="hidden" name="id" id="id"  />               
                               </div>
                              <div class="row middle">
                                   <div class="col-sm-4">Sales Order</div>
                                   <div class="col-sm-8">
                                       <input type="text" name="sale_order_no" id="sales_order_no" ondblclick="MsExpLcOrder.openExpLcOrderWindow()" placeholder=" Double Click">
                                       <input type="hidden" name="sales_order_id" id="sales_order_id" value="" />
                                   </div>
                               </div>
                               <div class="row middle">
                                   <div class="col-sm-4">Style Ref </div>
                                   <div class="col-sm-8">
                                       <input type="text" name="style_ref" id="style_ref" disabled>
                                   </div>
                               </div>
                               <div class="row middle">
                                   <div class="col-sm-4">Job 
                                       No </div>
                                   <div class="col-sm-8">
                                       <input type="text" name="job_no" id="job_no" disabled>
                                   </div>
                               </div>
                               <div class="row middle">
                                   <div class="col-sm-4">Order UOM </div>
                                   <div class="col-sm-8">
                                       <input type="text" name="uom_name" id="uom_name"disabled />
                                   </div>
                               </div>
                                 <div class="row middle">
                                    <div class="col-sm-4">Item </div>
                                    <div class="col-sm-8">   
                                       <input type="text" name="item_description" id="item_description" value="" disabled>
                                       </div>
                                 </div>
                               <div class="row middle">
                                   <div class="col-sm-4"> Order Qty </div>
                                   <div class="col-sm-8">
                                       <input type="text" name="order_qty" id="order_qty" value="" class="number integer" readonly/>
                                   </div>
                               </div>  
                               <div class="row middle">
                                 <div class="col-sm-4 req-text"> Tagged Qty </div>
                                 <div class="col-sm-8">
                                       <input type="text" name="qty" id="qty" onchange="MsExpLcOrder.calculateLcAmount()" placeholder="write" class="number integer"/>
                                 </div>
                              </div>
                              <div class="row middle">
                                 <div class="col-sm-4"> Balance Qty </div>
                                 <div class="col-sm-8">
                                       <input type="text" name="balance_qty" id="balance_qty"  class="number integer" />
                                 </div>
                              </div>                          
                               <div class="row middle">
                                   <div class="col-sm-4"> Price/Unit </div>
                                   <div class="col-sm-8">
                                       <input type="text" name="rate" id="rate" onchange="MsExpLcOrder.calculateLcAmount()" class="number integer" />
                                   </div>
                               </div>
                               <div class="row middle">
                                   <div class="col-sm-4"> Amount </div>
                                   <div class="col-sm-8">
                                       <input type="text" name="amount" id="amount" readonly class="number integer" value=""/>
                                   </div>
                               </div>
                               <div class="row middle">
                                   <div class="col-sm-4"> Remarks </div>
                                   <div class="col-sm-8">
                                       <textarea name="remarks" id="remarks"></textarea>
                                   </div>
                               </div>
                          </code>
                      </div>
                  </div>
                  <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsExpLcOrder.submit()">Save</a>
                      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('explcorderFrm')">Reset</a>
                      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsExpLcOrder.remove()">Delete</a>
                  </div>
              </form>
          </div>
      </div>
  </div>
   <!--==========Replace LC Tab=================-->
   <div title="Replacing SC" style="padding:2px">
   <div class="easyui-layout" data-options="fit:true">
      <div data-options="region:'west',border:true,title:'Add Replacing Lc',iconCls:'icon-more',footer:'#exreplcft'"
         style="width:450px; padding:2px">
         <form id="expreplcFrm">
            <div id="container">
               <div id="body">
                  <code>
                     <div class="row">
                        <div class="col-sm-8">
                           <input type="hidden" name="exp_lc_sc_id" id="exp_lc_sc_id" value="" />
                           <input type="hidden" name="id" id="id"  />
                           <input type="hidden" name="replaced_lc_sc_id" id="exp_lc_sc_id" value="" />
                        </div>
                     </div>
                     <div class="row middle">
                        <div class="col-sm-4">SC No </div>
                        <div class="col-sm-8">
                           <input type="text" name="lc_sc_no" id="lc_sc_no" ondblclick="MsExpRepLc.openRepLcWindow()" />
                        </div>
                     </div>
                     <div class="row middle">
                        <div class="col-sm-4">SC Value </div>
                        <div class="col-sm-8">
                           <input type="text" name="lc_sc_value" id="lc_sc_value" value="" class="number integer"
                              onclick="" />
                        </div>
                     </div>
                     <div class="row middle">
                        <div class="col-sm-4">Replaced Amount</div>
                        <div class="col-sm-8">
                           <input type="text" name="replaced_amount" id="replaced_amount" value="" class="number integer" onclick="" /></div>
                     </div>
                     <div class="row middle">
                        <div class="col-sm-4">Total Replaced </div>
                        <div class="col-sm-8">
                           <input type="text" name="total_replaced" id="total_replaced" class="number integer" readonly />
                        </div>
                     </div>
                     <div class="row middle">
                        <div class="col-sm-4">Balance</div>
                        <div class="col-sm-8">
                           <input type="text" name="balance" id="balance"  class="number integer" readonly />
                        </div>
                     </div>
                     <div class="row middle">
                        <div class="col-sm-4">SC Date</div>
                        <div class="col-sm-8">
                           <input type="text" name="lc_sc_date" id="lc_sc_date" value="" class="datepicker" placeholder="yy-mm-dd" readonly />
                        </div>
                     </div>
                     <div class="row middle">
                        <div class="col-sm-4">Buyer</div>
                        <div class="col-sm-8">
                           <input type="text" name="buyer_id" id="buyer_id" value="" placeholder="display" readonly />
                        </div>
                     </div>
                  </code>
               </div>
            </div>
            <div id="exreplcft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
               <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
                  iconCls="icon-save" plain="true" id="save" onClick="MsExpRepLc.submit()">Save</a>
               <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
                  iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('expreplcFrm')">Reset</a>
               <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
                  iconCls="icon-remove" plain="true" id="delete" onClick="MsExpRepLc.remove()">Delete</a>
            </div>
         </form>
      </div>
      <div data-options="region:'center',border:true,title:'Account Details'" style="padding:2px">
         <table id="expreplcTbl" style="width:100%">
            <thead>
               <tr>
                  <th data-options="field:'id'" width="80">ID</th>
                  <th data-options="field:'lc_sc_no'" width="100">SC No</th>
                  <th data-options="field:'lc_sc_date'" width="100">SC Date</th>
                  <th data-options="field:'buyer'" width="100">Buyer</th>
                  <th data-options="field:'lc_sc_value'" width="80" align="right">SC Value</th>
                  <th data-options="field:'replaced_amount'" width="80" align="right">Replaced Amount</th>
                  <th data-options="field:'total_replaced'" width="100" align="right">Total Replaced</th>
                  <th data-options="field:'balance'" width="100" align="right">Balance</th>                 
               </tr>
            </thead>
         </table>
      </div>
   </div>
   </div>
   <div title="Revise Details" style="padding:2px">
      <div class="easyui-layout" data-options="fit:true">
          <div data-options="region:'west',border:true,title:'Add Amendment Details',iconCls:'icon-more',footer:'#utiltechfeatureft'" style="width:450px; padding:2px">
              <form id="explcreviseFrm">
                  <div id="container">
                      <div id="body">
                          <code>
                              <div class="row" style="display: none;">
                                 <input type="hidden" name="exp_lc_sc_id" id="exp_lc_sc_id" value=""/>
                                 <input type="hidden" name="id" id="id" value="" />
                              </div>
                              <div class="row middle">
                                 <div class="col-sm-5 req-text">Last Amandment Date </div>
                                 <div class="col-sm-7">
                                    <input type="text" name="last_amendment_date" id="last_amendment_date" class="datepicker" placeholder="yy-mm-dd" />
                                 </div>
                              </div>
                              <div class="row middle">
                                 <div class="col-sm-5">Value Changed By</div>
                                 <div class="col-sm-7">
                                    <input type="text" name="amount" id="amount" class="number integer" placeholder="number" />
                                 </div>
                              </div>
                              <div class="row middle">
                                 <div class="col-sm-5">Remarks</div>
                                 <div class="col-sm-7">
                                    <textarea name="remarks" id="remarks"></textarea>
                                 </div>
                              </div>
                          </code>
                      </div>
                  </div>
                  <div id="utiltechfeatureft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsExpLcRevise.submit()">Save</a>
                      {{-- <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('explcreviseFrm')">Reset</a> --}}
                      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsExpLcRevise.remove()">Delete</a>
                  </div>
              </form>
          </div>
          <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
              <table id="explcreviseTbl" style="width:100%">
                  <thead>
                      <tr>
                          <th data-options="field:'id'" width="40">ID</th>
                          <th data-options="field:'last_amendment_date'" width="80">Last Amandment Date</th>
                          <th data-options="field:'amount'" width="100">Value Changed By</th>
                          <th data-options="field:'remarks'" width="100">Remarks</th>
                      </tr>
                  </thead>
              </table>
          </div>
      </div>
   </div>
</div>
<!----======== New Replacing Sales Search Window  ===========-------->
<div id="expreplcwindow" class="easyui-window" title="Sales Order Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
   <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
         <div class="easyui-layout" data-options="fit:true">
            <div id="body">
               <code>
                     <form id="expreplcsearchFrm">
                        <div class="row middle">
                           <div class="col-sm-4">LC No</div>
                           <div class="col-sm-8">
                                    <input type="text" name="lc_sc_no" id="lc_sc_no" value="">
                           </div>
                        </div>
                        <div class="row middle">
                        <div class="col-sm-4 req-text">Beneficiary </div>
                        <div class="col-sm-8">
                           {!! Form::select('beneficiary_id', $company,'',array('id'=>'beneficiary_id'))
                           !!}
                        </div>
                     </div>
                     <div class="row middle">
                        <div class="col-sm-4 req-text">Buyer</div>
                        <div class="col-sm-8">
                           {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                        </div>
                     </div>
                     </form>
               </code>
            </div>
            <p class="footer">
               <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsExpRepLc.importRepLc()">Search</a>
            </p>
         </div>
   </div>
   <div data-options="region:'center'" style="padding:10px;">
         <table id="expreplcsearchTbl" style="width:100%">
            <thead>
               <tr>
                     <th data-options="field:'id'" width="40">ID</th>
                     <th data-options="field:'lc_sc_no'" width="70">LC No</th>
                     <th data-options="field:'lc_sc_date'" width="100">LC Date</th>
                     <th data-options="field:'buyer'" width="100">Buyer</th>
                     <th data-options="field:'company'" width="100">Company</th>
                     <th data-options="field:'lc_sc_value'" width="100">LC Value</th>             
                     <th data-options="field:'total_replaced'" width="100"> Total Replaced</th> 
                     <th data-options="field:'balance'" width="100" align="right"> Balance</th>                  
               </tr>
            </thead>
         </table>
   </div>
   
   </div>
</div>
<!--------------=======  Exp SC Order Search Window  =================------------>
<div id="explsorderwindow" class="easyui-window" title="Sales Order Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
<div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
      <div class="easyui-layout" data-options="fit:true">
          <div id="body">
              <code>
                  <form id="explcordersearchFrm">
                      <div class="row middle">
                          <div class="col-sm-4">Style Ref</div>
                          <div class="col-sm-8">
                              <input type="text" name="style_ref" id="style_ref" value="">
                          </div>
                      </div>
                      <div class="row middle">
                          <div class="col-sm-4">Job </div>
                          <div class="col-sm-8">
                              <input type="text" name="job_no" id="job_no" value="" />
                          </div>
                      </div>
                      <div class="row middle">
                        <div class="col-sm-4">Sale Order No</div>
                        <div class="col-sm-8">
                           <input type="text" name="sale_order_no" id="sale_order_no" value="" />
                        </div>
                    </div>
                  </form>
              </code>
          </div>
          <p class="footer">
            <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsExpLcOrder.searchOrder()">Search</a>
          </p>
      </div>
  </div>
  <div data-options="region:'center'" style="padding:10px;">
      <table id="explcordersearchTbl" style="width:100%">
          <thead>
              <tr>
                  <th data-options="field:'id'" width="40">ID</th>
                  <th data-options="field:'style_ref'" width="70">Style Ref</th>
                  <th data-options="field:'job_no'" width="100">Job No</th>
                  <th data-options="field:'sale_order_no'" width="100">Sale Order No</th>
                  <th data-options="field:'item_description'" width="100">Item</th>    
                  <th data-options="field:'uom_name'" width="100"> UOM</th> 
                  <th data-options="field:'qty'" width="100" align="right">Order Qty</th> 
                  <th data-options="field:'rate'" width="100" align="right"> Rate</th> 
                  <th data-options="field:'amount'" width="100" align="right"> Amount</th>
                  <th data-options="field:'cumulative_qty'" width="100" align="right"> Cumulative Qty</th>
                  <th data-options="field:'balance_qty'" width="100" align="right"> Balance Qty</th>    
              </tr>
          </thead>
      </table>
  </div>
  <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
     <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="MsExpLcOrder.closeExpLcOrderWindow()" style="width:80px">Close</a>
  </div>
</div>
</div>

<div id="explctagorderwindow" class="easyui-window" title="Sales Order Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:100%;padding:2px;">
   <div class="easyui-layout" data-options="fit:true">
     
     <div data-options="region:'center',footer:'#explctagorderScsft'" style="padding:10px;">
       <form id="explctagorderFrm">
       <code id="explctagorderScs">
       </code>
       <div id="explctagorderScsft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
       <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsExpLcOrder.submitBatch()">Save</a>
       <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('explctagorderFrm')" >Reset</a>
       </div>

       </form>
        
     </div>
   </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Commercial/Export/MsAllLcController.js"></script>
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
      
      $('#explcFrm [id="buyer_id"]').combobox();
      $('#explcFrm [id="consignee_id"]').combobox();
      $('#explcFrm [id="notifying_party_id"]').combobox();
      $('#explcFrm [id="forwarding_agent_id"]').combobox();
      $('#explcFrm [id="shipping_line_id"]').combobox();
      $('#explcFrm [id="second_notifying_party_id"]').combobox();
   })(jQuery);

</script>
