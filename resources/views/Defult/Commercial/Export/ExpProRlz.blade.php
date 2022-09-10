<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="comExpRlzTabs">
   <div title="New Entry" style="padding:2px">
      <div class="easyui-layout" data-options="fit:true">
         <div data-options="region:'center',border:true" style="padding:2px">
          <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true" style="padding:2px">
            <div class="easyui-layout" data-options="fit:true">
              <table id="framTbl" style="width:100%">
             <!--  <thead>
              <tr>
              <th data-options="field:'id'" width="80">ID</th>
              <th data-options="field:'commercial_head_id'" width="100">Deduction Head</th>
              <th data-options="field:'doc_value'" width="100">Document Currency</th>
              <th data-options="field:'exch_rate'" width="100">Conversion Rate</th>
              <th data-options="field:'dom_value'" width="100">Domestic Currency</th>
              </tr>
              </thead> -->
              </table>
              <div id="expprorlzTb2">
              <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save"
              plain="true" id="save" onClick="MsExpProRlzAmount.insert()">Add</a>
              <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px; border-radius:1px" iconCls="icon-save"
              plain="true" id="save" onClick="MsExpProRlzAmount.deleterow()">Remove</a>
              
              </div>
            </div>
          </div>
          <div data-options="region:'north',border:true" style="padding:2px; height:250px">
            <table id="frdeTbl" style="width:100%">
             <!--  <thead>
              <tr>
              <th data-options="field:'id'" width="80">ID</th>
              <th data-options="field:'commercial_head_id'" width="100">Deduction Head</th>
              <th data-options="field:'doc_value'" width="100">Document Currency</th>
              <th data-options="field:'exch_rate'" width="100">Conversion Rate</th>
              <th data-options="field:'dom_value'" width="100">Domestic Currency</th>
              </tr>
              </thead> -->
              </table>
              <div id="expprorlzTb">
              <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save"
              plain="true" id="save" onClick="MsExpProRlzDeduct.insert()">Add</a>
              <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px; border-radius:1px" iconCls="icon-save"
              plain="true" id="save" onClick="MsExpProRlzDeduct.deleterow()">Remove</a>
              <input type="radio" name="aa" value="1"> Document Currency
              <input type="radio" name="aa" value="2"> Conversion Rate
              <input type="radio" name="aa" value="3" checked="checked"> Domestic Currency
              </div>
          </div>
          <div data-options="region:'south',border:true" style="padding:2px; height:30px">
            <div class="easyui-layout" data-options="fit:true">
              <div class="row middle">        
                <div class="col-sm-3 req-text">Document Currency </div>
                <div class="col-sm-3">
                  <input type="text" name="total_doc_value" id="total_doc_value" class="number" disabled />
                  <input type="hidden" name="d_total_doc_value" id="d_total_doc_value"/>
                  <input type="hidden" name="a_total_doc_value" id="a_total_doc_value"/>
                </div>
                <div class="col-sm-3 req-text">Domestic Currency </div>
                <div class="col-sm-3">
                  <input type="text" name="total_dom_value" id="total_dom_value" class="number" disabled />
                  <input type="hidden" name="d_total_dom_value" id="d_total_dom_value"/>
                  <input type="hidden" name="a_total_dom_value" id="a_total_dom_value"/>
                </div>
              </div>
            </div>
          </div>
          </div>
         </div>
         <div data-options="region:'west',border:true,title:'Add New Bank',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'"
            style="width:450px; padding:2px">
            <div id="container">
               <div id="body">
                  <code>
                     <form id="expprorlzFrm">
                        <div class="row middle">
                           <div class="col-sm-4 req-text">Bank Ref/ Bill No </div>
                           <div class="col-sm-8">
                              <input type="text" name="bank_ref_bill_no" id="bank_ref_bill_no" value="" ondblclick="MsExpProRlz.openDocSubmissionWindow()"  placeholder="  Double Click" readonly/>

                              <input type="hidden" name="exp_doc_submission_id" id="exp_doc_submission_id" value="" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4 req-text">Beneficiary </div>
                           <div class="col-sm-8">
                                 <input type="hidden" name="id" id="id" value="" />
                                 {!! Form::select('beneficiary_id', $company,'',array('id'=>'beneficiary_id','disabled'=>'disabled'))
                              !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4 req-text">Buyer</div>
                           <div class="col-sm-8">
                              {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','disabled'=>'disabled')) !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4 req-text">Realized Date</div>
                           <div class="col-sm-8">
                              <input type="text" name="realization_date" id="realization_date" class="datepicker" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">LC\SC NO</div>
                           <div class="col-sm-8">
                              <input type="text" name="lc_sc_no" id="lc_sc_no" disabled />                         
                           </div>
                        </div> 
                        <div class="row middle">
                           <div class="col-sm-4 req-text">Currency</div>
                           <div class="col-sm-8">
                              <input type="text" name="currency_id" id="currency_id" placeholder="display" disabled/>
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Bank Ref/ Bill Date </div>
                           <div class="col-sm-8">
                              <input type="text" name="bank_ref_date" id="bank_ref_date" class="datepicker" placeholder="display" disabled/>
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4 req-text">Bank Ref/ Bill Amount </div>
                           <div class="col-sm-8">
                                 <input type="text" name="bank_ref_amount" id="bank_ref_amount" placeholder="display" disabled/>
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Negotiated Amount</div>
                           <div class="col-sm-8">
                              <input type="text" name="negotiated_amount" id="negotiated_amount" placeholder="display" disabled/>
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Remarks</div>
                           <div class="col-sm-8">
                              <textarea name="remarks" id="remarks"></textarea>
                           </div>
                        </div>
                     </form>
                  </code>
               </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#CCC;">
               <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save"
               plain="true" id="save" onClick="MsExpProRlz.submit()">Save</a>
               <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('expprorlzFrm')">Reset</a>
               <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove"
               plain="true" id="delete" onClick="MsExpProRlz.remove()">Delete</a>
            </div>
         </div>
      </div>
   </div>
</div>
<div id="expdocsubwindow" class="easyui-window" title="LC/SC Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
   <div class="easyui-layout" data-options="fit:true">
       <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
           <div class="easyui-layout" data-options="fit:true">
               <div id="body">
                   <code>
                       <form id="expdocsubsearchFrm">
                           <div class="row middle">
                              <div class="col-sm-4">Bank Ref Bill Date </div>
                                  <div class="col-sm-4" style="padding-right:0px">
                                  <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                              </div>
                              <div class="col-sm-4" style="padding-left:0px">
                                  <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                              </div>
                           </div>
                           <div class="row middle">
                               <div class="col-sm-4">Bank Ref/ Bill No</div>
                               <div class="col-sm-8">
                                   <input type="text" name="bank_ref_bill_no" id="bank_ref_bill_no" value="" />
                               </div>
                           </div>
                       </form>
                   </code>
               </div>
               <p class="footer">
                   <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsExpProRlz.showDocSubmissionGrid()">Search</a>
               </p>
           </div>
       </div>
       <div data-options="region:'center'" style="padding:10px;">
           <table id="expdocsubsearchTbl" style="width:100%">
               <thead>
                   <tr>
                       <th data-options="field:'id'" width="30">ID</th>
                       <th data-options="field:'bank_ref_bill_no'" width="120">Bank Ref/Bill No</th>
                       <th data-options="field:'lc_sc_no'" width="100">Contract No</th>
                       <th data-options="field:'bank_ref_date'" width="100">Bank Ref Bill Date</th>
                       <th data-options="field:'bank_ref_amount'" width="100" align="right">Bank Ref/Bill Amount</th>
                       <th data-options="field:'currency_id'" width="100"> Currency</th> 
                       <th data-options="field:'courier_recpt_no'" width="100" align="right">Currier recv No</th>  
                   </tr>
               </thead>
           </table>
       </div>
       <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
           <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#expdocsubwindow').window('close')" style="width:80px">Close</a>
       </div>
   </div>
</div>

<div id="expprorlzloanwindow" class="easyui-window" title="Loan Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
   <div class="easyui-layout" data-options="fit:true">
       <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
           <div class="easyui-layout" data-options="fit:true">
               <div id="body">
                   <code>
                       <form id="expprorlzloansearchFrm">
                           <div class="row middle">
                              <div class="col-sm-4">Loan Date </div>
                                  <div class="col-sm-4" style="padding-right:0px">
                                  <input type="text" name="loan_date_from" id="loan_date_from" class="datepicker" placeholder="From" />
                              </div>
                              <div class="col-sm-4" style="padding-left:0px">
                                  <input type="text" name="loan_date_to" id="loan_date_to" class="datepicker"  placeholder="To" />
                              </div>
                           </div>
                           <div class="row middle">
                               <div class="col-sm-4">Loan No</div>
                               <div class="col-sm-8">
                                   <input type="text" name="bank_loan_no" id="bank_loan_no" value="" />
                                   <input type="hidden" name="grid_index" id="grid_index" readonly/>
                               </div>
                           </div>
                       </form>
                   </code>
               </div>
               <p class="footer">
                   <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsExpProRlzAmount.getLoanRef()">Search</a>
               </p>
           </div>
       </div>
       <div data-options="region:'center'" style="padding:10px;">
           <table id="expprorlzloansearchTbl" style="width:100%">
               <thead>
                   <tr>
                       <th data-options="field:'id'" width="30">ID</th>
                       <th data-options="field:'loan_no'" width="120">Loan No</th>
                       <th data-options="field:'loan_date'" width="100">Loan Date</th>
                       <th data-options="field:'amount'" width="100" align="right">Amount</th>
                       <th data-options="field:'loan_type'" width="100">Loan Type</th> 
                   </tr>
               </thead>
           </table>
       </div>
       <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
           <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#expprorlzloanwindow').window('close')" style="width:80px">Close</a>
       </div>
   </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Commercial/Export/MsAllExpProRlzController.js"></script>
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
</script>
