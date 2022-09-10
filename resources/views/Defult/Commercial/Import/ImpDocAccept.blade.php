<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="impshippindoctabs">
   <div title="Import Shipping Document Acceptance" style="padding:2px">
      <div class="easyui-layout" data-options="fit:true">
         <div data-options="region:'center',border:true,title:'list'" style="padding:2px">
            <table id="impdocacceptTbl" style="width:100%">
               <thead>
                  <tr>
                     <th data-options="field:'id'" width="40">ID</th>
                     <th data-options="field:'lc_no'" width="150">LC NO.</th>
                     <th data-options="field:'invoice_no'" width="110">Invoice No</th>
                     <th data-options="field:'invoice_date'" width="80">Invoice Date</th>
                     <th data-options="field:'shipment_date'" width="100">Shipment Date</th>
                     <th data-options="field:'company_accep_date'" width="100">Company Accept Date</th>
                     <th data-options="field:'bank_ref'" width="130">Bank Ref</th>
                     <th data-options="field:'loan_ref'" width="100">Loan Ref</th>
                     <th data-options="field:'doc_value'" width="100" align="right">Document Value</th>
                     <th data-options="field:'rate'" width="100" align="right">Interest Rate</th>
                     <th data-options="field:'commercial_head_id'" width="100">Means of Retirement</th>
                      <th data-options="field:'commercial_head_name'" width="100">A/C Type</th>
                     <th data-options="field:'lc_type_id'" width="130">LC Type</th>
                     <th data-options="field:'pay_term_id'" width="130">Pay Term</th>
                  </tr>
               </thead>
            </table>
         </div>
         <div data-options="region:'west',border:true,title:'Invoice Details',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'"
            style="width:450px; padding:2px">
            <div id="container">
               <div id="body">
                  <code>
                     <form id="impdocacceptFrm">
                        <div class="row">
                              <input type="hidden" name="id" id="id" value="" />
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5 req-text">LC No</div>
                           <div class="col-sm-7">
                                <input type="text" name="lc_no" id="lc_no" ondblclick="MsImpDocAccept.openImpLcWndow()" placeholder=" Double Click" />
                                <input type="hidden" name="imp_lc_id" id="imp_lc_id" value=""/>
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5 req-text">Invoice No</div>
                           <div class="col-sm-7">
                              <input type="text" name="invoice_no" id="invoice_no"/>
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5 req-text">Invoice Date</div>
                           <div class="col-sm-7">
                              <input type="text" name="invoice_date" id="invoice_date" class="datepicker" placeholder=" yy-mm-dd" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5 req-text">Shipment Date</div>
                           <div class="col-sm-7">
                              <input type="text" name="shipment_date" id="shipment_date" class="datepicker" placeholder="yy-mm-dd" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5 req-text">Company Accep Date</div>
                           <div class="col-sm-7">
                              <input type="text" name="company_accep_date" id="company_accep_date" class="datepicker" placeholder=" yy-mm-dd" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Bank Accep. Date</div>
                           <div class="col-sm-7">
                              <input type="text" name="bank_accep_date" id="bank_accep_date" class="datepicker" placeholder=" yy-mm-dd" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Discharge Date</div>
                           <div class="col-sm-7">
                              <input type="text" name="discharge_date" id="discharge_date" class="datepicker" placeholder=" yy-mm-dd" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Port Clearing Date</div>
                           <div class="col-sm-7">
                              <input type="text" name="port_clearing_date" id="port_clearing_date" class="datepicker" placeholder=" yy-mm-dd" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Bank Ref</div>
                           <div class="col-sm-7">
                              <input type="text" name="bank_ref" id="bank_ref" placeholder="txt">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Means of Retirement</div>
                           <div class="col-sm-7">
                              {!! Form::select('commercial_head_id',
                              $commercialhead,'',array('id'=>'commercial_head_id')) !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Bank Limit A/C</div>
                           <div class="col-sm-7">
                              <input type="text" name="commercial_head_name" id="commercial_head_name" value="" ondblclick="MsImpDocAccept.bankWindowOpen()" placeholder="Double Click To Select">
                              <input type="hidden" name="bank_account_id" id="bank_account_id">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Loan Ref</div>
                           <div class="col-sm-7">
                              <input type="text" name="loan_ref" id="loan_ref" placeholder=" write" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5 req-text">Document Value</div>
                           <div class="col-sm-7">
                              <input type="text" name="doc_value" id="doc_value" class="number integer" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Interest Rate</div>
                           <div class="col-sm-7">
                              <input type="text" name="rate" id="rate" class="number integer"/>
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">BL/Cargo No</div>
                           <div class="col-sm-7">
                              <input type="text" name="bl_cargo_no" id="bl_cargo_no" placeholder=" write">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">BL/Cargo Date</div>
                           <div class="col-sm-7">
                              <input type="text" name="bl_cargo_date" id="bl_cargo_date" class="datepicker" placeholder="yy-mm-dd" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Shipment Mode</div>
                           <div class="col-sm-7">
                              {!! Form::select('shipment_mode',
                              $deliveryMode,'',array('id'=>'shipment_mode')) !!}
                           </div>
                        </div>                      
                        <div class="row middle">
                           <div class="col-sm-5 req-text">Document Status</div>
                           <div class="col-sm-7">
                              {!! Form::select('doc_status',
                              $docStatus,'',array('id'=>'doc_status')) !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5 req-text">Copy Doc Receive Date</div>
                           <div class="col-sm-7">
                              <input type="text" name="copy_doc_rcv_date" id="copy_doc_rcv_date" class="datepicker" placeholder=" yy-mm-dd" value="" />
                           </div>
                        </div>                  
                        <div class="row middle">
                           <div class="col-sm-5">Original Doc Receive Date</div>
                           <div class="col-sm-7">
                              <input type="text" name="original_doc_rcv_date" id="original_doc_rcv_date" placeholder=" yy-mm-dd" class="datepicker" value="" />
                           </div>
                        </div>                       
                        <div class="row middle">
                           <div class="col-sm-5">Document to C&F</div>
                           <div class="col-sm-7">
                              <input type="text" name="doc_to_cf_date" id="doc_to_cf_date" value="" placeholder="yy-mm-dd" class="datepicker" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Feeder Vessel</div>
                           <div class="col-sm-7">
                                 <input type="text" name="feeder_vessel" id="feeder_vessel" value="" placeholder=" txt" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Mother Vessel</div>
                           <div class="col-sm-7">
                                 <input type="text" name="mother_vessel" id="mother_vessel" value="" placeholder=" txt" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">ETA Date </div>
                           <div class="col-sm-7">
                              <input type="text" name="eta_date" id="eta_date" class="datepicker" placeholder="yy-mm-dd">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">IC Received Date </div>
                           <div class="col-sm-7">
                              <input type="text" name="ic_received_date" id="ic_received_date" value="" class="datepicker" placeholder=" yy-mm-dd" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Shipping Bill No</div>
                           <div class="col-sm-7">
                              <input type="text" name="shipping_bill_no" id="shipping_bill_no" value="" placeholder=" write">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5"> Incoterm </div>
                           <div class="col-sm-7">
                              {!! Form::select('incoterm_id', $incoterm,'',array('id'=>'incoterm_id')) !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Inco Term Place </div>
                           <div class="col-sm-7">
                              <input type="text" name="incoterm_place" id="incoterm_place" value=""  />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Port of Loading </div>
                           <div class="col-sm-7">
                              <input type="text" name="port_of_loading" id="port_of_loading" value="" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Port of Discharge </div>
                           <div class="col-sm-7">
                              <input type="text" name="port_of_discharge" id="port_of_discharge" value="" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Internal File No</div>
                           <div class="col-sm-7">
                              <input type="text" name="internal_file_no" id="internal_file_no" value="" placeholder="write" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Bill Of Entry No</div>
                           <div class="col-sm-7">
                              <input type="text" name="bill_of_entry_no" id="bill_of_entry_no" placeholder=" write">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">PSI Reference No</div>
                           <div class="col-sm-7">
                              <input type="text" name="psi_ref_no" id="psi_ref_no" placeholder="write">
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Maturity Date</div>
                           <div class="col-sm-7">
                              <input type="text" name="maturity_date" id="maturity_date" placeholder=" display date" readonly/>
                           </div>
                        </div>  
                        <div class="row middle">
                           <div class="col-sm-5"> Tenor </div>
                           <div class="col-sm-7">
                              <input type="text" name="tenor" id="tenor" class="number integer" disabled/>
                           </div>
                        </div>                      
                        <div class="row middle">
                           <div class="col-sm-5">Container No </div>
                           <div class="col-sm-7">
                              <input type="text" name="container_no" id="container_no" placeholder=" write" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Pakt. Quantity</div>
                           <div class="col-sm-7">
                              <input type="text" name="qty" id="qty" class="number integer" />
                           </div>
                        </div>                       
                        <div class="row middle">
                           <div class="col-sm-5">LC Value & Currency</div>
                           <div class="col-sm-7">                            
                                 <input type="text" name="" id="" value="" placeholder=" display" disabled /> 
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Importer</div>
                           <div class="col-sm-7">
                              <input type="text" name="company_name" id="company_name" value="" placeholder=" display" disabled />
                              <input type="hidden" name="company_id" id="company_id" value=""/>
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Supplier </div>
                           <div class="col-sm-7">
                                 <input type="text" name="supplier_name" id="supplier_name" value="" placeholder=" display" disabled />
                           </div>
                        </div>
                        
                        <div class="row middle">
                           <div class="col-sm-5">IssuingBank Branch</div>
                           <div class="col-sm-7">
                              <input type="text" name="issuing_bank_branch" id="issuing_bank_branch" value="" placeholder=" display" disabled />
                              <input type="hidden" name="issuing_bank_branch_id" id="issuing_bank_branch_id" value="" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Pay Term</div>
                           <div class="col-sm-7">
                                 <input type="text" name="pay_term_id" id="pay_term_id" value="" placeholder="display" disabled />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">L/C Type</div>
                           <div class="col-sm-7">
                                 <input type="text" name="lc_type_id" id="lc_type_id" value="" placeholder=" display" disabled />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-5">Remarks</div>
                           <div class="col-sm-7">
                              <textarea name="remarks" id="remarks" ></textarea>
                           </div>
                        </div>
                     </form>
                  </code>
               </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#CCC;">
               <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsImpDocAccept.submit()">Save</a>
               <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('impdocacceptFrm')">Reset</a>
               <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsImpDocAccept.remove()">Delete</a>
               <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsImpDocAccept.mletter()">M.Letter</a>
            </div>
         </div>
      </div>
   </div>
   <div title="Accepted Commodity Details" style="padding:2px">
      <div class="easyui-layout" data-options="fit:true">
        
         <div data-options="region:'center',border:true,title:'Lists',footer:'#depft'" style="padding:2px">
            <form id="impacccomdetailFrm">
            <code id="impacccomdetailmatrix"></code>
            <div id="depft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
               <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsImpAccComDetail.submit()">Save</a>
               <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('impacccomdetailFrm')">Reset</a>
               <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsImpAccComDetail.remove()">Delete</a>
            </div>
         </form>
         </div>
      </div>                           
   </div>
   <div title="Maturity" style="padding:2px">
      <div class="easyui-layout" data-options="fit:true">
         <div data-options="region:'center',border:true,title:'list'" style="padding:2px">
            <table id="impdocacceptmaturityTbl" style="width:100%">
               <thead>
                  <tr>
                     <th data-options="field:'id'" width="40">ID</th>
                     <th data-options="field:'lc_no'" width="110">LC NO.</th>
                     <th data-options="field:'invoice_no'" width="110">Invoice No</th>
                     <th data-options="field:'invoice_date'" width="80">Invoice Date</th>
                     <th data-options="field:'shipment_date'" width="100">Shipment Date</th>
                     <th data-options="field:'company_accep_date'" width="100">Company Accept Date</th>
                     <th data-options="field:'bank_ref'" width="130">Bank Ref</th>
                     <th data-options="field:'loan_ref'" width="100">Loan Ref</th>
                     <th data-options="field:'doc_value'" width="100" align="right">Document Value</th>
                     <th data-options="field:'rate'" width="100" align="right">Interest Rate</th>
                     <th data-options="field:'commercial_head_id'" width="100">Means of Retirement</th>
                  </tr>
               </thead>
            </table>
         </div>
         <div data-options="region:'west',border:true,title:'Invoice Details',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft4'"
            style="width:300px; padding:2px">
            <div id="container">
               <div id="body">
                  <code>
                     <form id="impdocacceptmaturityFrm">
                        <div class="row">
                           <input type="hidden" name="id" id="id" value="" />
                           <input type="hidden" name="imp_doc_accept_id" id="imp_doc_accept_id" value="" />
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4 req-text">Date</div>
                           <div class="col-sm-8">
                              <input type="text" name="docaccept_maturity_date" id="docaccept_maturity_date" class="datepicker" placeholder=" yy-mm-dd" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4 req-text">Invoice No</div>
                           <div class="col-sm-8">
                              <input type="text" name="invoice_no" id="invoice_no" ondblclick="MsImpDocAcceptMaturity.openMatureImpDocAcceptWindow()" placeholder=" Double Click" />
                              <input type="hidden" name="doc_invoice_id" id="doc_invoice_id" value=""/>
                           </div>
                        </div>
                     </form>
                  </code>
               </div>
            </div>
            <div id="ft4" style="padding:0px 0px; text-align:right; background:#CCC;">
               <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsImpDocAcceptMaturity.submit()">Save</a>
               <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('impdocacceptmaturityFrm')">Reset</a>
               <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsImpDocAcceptMaturity.remove()">Delete</a>
            </div>
         </div>
      </div>
   </div>
</div>
<div id="impLcWindow" class="easyui-window" title="Import LC Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
   <div class="easyui-layout" data-options="fit:true">
       <div data-options="region:'west',split:true, title:'Search'" style="width:400px">
           <div class="easyui-layout" data-options="fit:true">
               <div id="body">
                   <code>
                       <form id="implcsearchFrm">
                           <div class="row middle">
                              <div class="col-sm-4">Company</div>
                              <div class="col-sm-8">
                                 {!! Form::select('company_id',$company,'',array('id'=>'company_id')) !!}
                              </div>
                           </div>
                           <div class="row middle">
                              <div class="col-sm-4">Supplier</div>
                              <div class="col-sm-8">
                                 {!! Form::select('supplier_id',
                                 $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
                              </div>
                           </div>
                           <div class="row middle">
                              <div class="col-sm-4">LC Type</div>
                              <div class="col-sm-8">
                                 {!! Form::select('lc_type_id',$lctype,'',array('id'=>'lc_type_id')) !!}
                              </div>
                           </div>
                           <div class="row middle">
                              <div class="col-sm-4">IssuingBank Branch</div>
                              <div class="col-sm-8">
                                 {!! Form::select('issuing_bank_branch_id',$bankbranch,'',array('id'=>'issuing_bank_branch_id')) !!}
                              </div>
                           </div>
                       </form>
                   </code>
               </div>
               <p class="footer">
                   <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsImpDocAccept.searchDocAcceptImpLc()">Search</a>
               </p>
           </div>
       </div>
       <div data-options="region:'center'" style="padding:10px;">
           <table id="implcsearchTbl" style="width:610px">
               <thead>
                   <tr>
                       <th data-options="field:'id'" width="50">ID</th>
                       <th data-options="field:'lc_no'" width="100">Import LC No</th>
                       <th data-options="field:'supplier_id'" width="100">Supplier</th>
                       <th data-options="field:'pay_term_id'" width="100">Pay Term</th>
                       <th data-options="field:'company_id'" width="100">Importer</th>
                       <th data-options="field:'lc_type_id'" width="100">L/C Type</th>
                   </tr>
               </thead>
           </table>
       </div>
       <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
           <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#impLcWindow').window('close')" style="width:80px">Close</a>
       </div>
   </div>
</div>
{{-- IMPORT Document shipping WINDOW --}}
<div id="impdocacceptWindow" class="easyui-window" title="Import Document Accept Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
   <div class="easyui-layout" data-options="fit:true">
       <div data-options="region:'west',split:true, title:'Search'" style="width:400px">
           <div class="easyui-layout" data-options="fit:true">
               <div id="body">
                   <code>
                       <form id="impdocacceptsearchFrm">
                           <div class="row middle">
                              <div class="col-sm-4">Invoice No</div>
                              <div class="col-sm-8">
                                 <input type="text" name="invoice_no" id="invoice_no" value=""/>
                              </div>
                           </div>
                           <div class="row middle">
                              <div class="col-sm-4">Invoice Date</div>
                              <div class="col-sm-8">
                                 <input type="text" name="invoice_date" id="invoice_date" value=""/>
                              </div>
                           </div>
                           <div class="row middle">
                              <div class="col-sm-4">LC No</div>
                              <div class="col-sm-8">
                                 <input type="text" name="lc_no" id="lc_no" value=""/>
                              </div>
                           </div>
                           <div class="row middle">
                              <div class="col-sm-4">Bank Ref</div>
                              <div class="col-sm-8">
                                 <input type="text" name="bank_ref" id="bank_ref" value=""/>
                              </div>
                           </div>
                       </form>
                   </code>
               </div>
               <p class="footer">
                  <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsImpDocAcceptMaturity.searchDocAcceptImpLc()">Search</a>
               </p>
           </div>
       </div>
       <div data-options="region:'center'" style="padding:10px;">
           <table id="impdocacceptsearchTbl" style="width:610px">
               <thead>
                  <tr>
                     <th data-options="field:'id'" width="40">ID</th>
                     <th data-options="field:'lc_no'" width="110">LC NO.</th>
                     <th data-options="field:'invoice_no'" width="110">Invoice No</th>
                     <th data-options="field:'invoice_date'" width="80">Invoice Date</th>
                     <th data-options="field:'shipment_date'" width="100">Shipment Date</th>
                     <th data-options="field:'company_accep_date'" width="100">Company Accept Date</th>
                     <th data-options="field:'bank_ref'" width="130">Bank Ref</th>
                     <th data-options="field:'loan_ref'" width="100">Loan Ref</th>
                     <th data-options="field:'doc_value'" width="100" align="right">Document Value</th>
                     <th data-options="field:'rate'" width="100" align="right">Interest Rate</th>
                     <th data-options="field:'commercial_head_id'" width="100">Means of Retirement</th>
                  </tr>
               </thead>
           </table>
       </div>
       <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
           <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#impdocacceptWindow').window('close')" style="width:80px">Close</a>
       </div>
   </div>
</div>
{{-- openbanckaccount window  --}}
<div id="openbankaccountWindow" class="easyui-window" title="Bank Account Window"
   data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
   <div class="easyui-layout" data-options="fit:true">
     <div data-options="region:'center',border:true,footer:'#bankaccountsearchTblFt'" style="padding:2px">
      <table id="bankaccountsearchTbl" style="width:100%">
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
      <div id="bankaccountsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
       <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
        onclick="$('#openbankaccountWindow').window('close')" style="width:80px">Close</a>
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
         onClick="MsImpDocAccept.searchbankAccount()">Search</a>
       </div>
      </form>
     </div>
   </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Commercial/Import/MsAllImpShipDocController.js"></script>
<script>
   (function(){

       $(".datepicker").datepicker({
               dateFormat: 'yy-mm-dd',
               changeMonth: true,
               changeYear: true,
       });

       $('.integer').keyup(function () {
            if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
               this.value = this.value.replace(/[^0-9\.]/g, '');
            }
       });

      $('#bank_accep_date').change(function(){
         let tenor=$('#tenor').val()*1;
         if(!tenor){
            tenor=0;
         }
         tenor=tenor-1;
         let bank_accep_date=new Date($('#bank_accep_date').val());
         let maturity_date= msApp.addDays(bank_accep_date,tenor);
         $('#maturity_date').val(maturity_date);
      });

   })(jQuery);
   </script>