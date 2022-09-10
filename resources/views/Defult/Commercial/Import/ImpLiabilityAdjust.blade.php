<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="impliabitytabs">
  <div title="Liability Adjustment Reference" style="padding:2px">
      <div class="easyui-layout" data-options="fit:true">
          <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
            <table id="impliabilityadjustTbl" style="width:100%">
              <thead>
                <tr>
                  <th data-options="field:'id'" width="50">ID</th>
                  <th data-options="field:'imp_liability_adjust_no'" width="80">System No</th>
                  <th data-options="field:'bank_ref'" width="130">Bank Ref</th>
                  <th data-options="field:'payment_date'" width="80">Payment<br> Date</th>
                  <th data-options="field:'issuing_bank_branch_id'" width="100">Issue Bank</th>
                  <th data-options="field:'company_name'" width="100">Company</th>
                  <th data-options="field:'lc_no'" width="120">LC NO.</th>
                  <th data-options="field:'invoice_no'" width="120">Invoice No.</th>
                  <th data-options="field:'supplier_name'" width="100">Supplier</th>
                  <th data-options="field:'acceptance_value'" width="100" align="right">Acceptance<br> Value</th>
                  <th data-options="field:'remarks'" width="120" align="right">Remarks</th>
                </tr>
              </thead>
            </table>
          </div>
          <div data-options="region:'west',border:true,title:'New Adjustments Reference',footer:'#ft2'" style="width: 380px; padding:2px">
              <form id="impliabilityadjustFrm">
                  <div id="container">
                      <div id="body">
                          <code>
                            <div class="row">
                              <input type="hidden" name="id" id="id" value="" />
                            </div>
                            <div class="row middle">
                              <div class="col-sm-4 req-text">System No</div>
                              <div class="col-sm-8">
                                <input type="text" name="imp_liability_adjust_no" id="imp_liability_adjust_no"  readonly />
                              </div>
                            </div>
                            <div class="row middle">
                              <div class="col-sm-4 req-text">Bank Ref</div>
                              <div class="col-sm-8">
                                <input type="text" name="bank_ref" id="bank_ref" ondblclick="MsImpLiabilityAdjust.openBankRefWndow()" placeholder=" Double Click" />
                                <input type="hidden" name="imp_doc_accept_id" id="imp_doc_accept_id" value="" />
                              </div>
                            </div>
                            <div class="row middle">
                              <div class="col-sm-4">Payment Date </div>
                              <div class="col-sm-8">
                                <input type="text" name="payment_date" id="payment_date" class="datepicker" placeholder=" yy-mm-dd">
                              </div>
                            </div>
                            <div class="row middle">
                              <div class="col-sm-4">Remarks</div>
                              <div class="col-sm-8">
                                <textarea name="remarks" id="remarks" ></textarea>
                              </div>
                            </div>
                            <div class="row middle">
                              <div class="col-sm-4">Accepted Value</div>
                              <div class="col-sm-8">
                                <input type="text" name="acceptance_value" id="acceptance_value" value="" disabled />
                              </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">L\C No</div>
                                <div class="col-sm-8">
                                  <input type="text" name="lc_no" id="lc_no" value="" disabled />
                                </div>
                            </div>
                            <div class="row middle">
                              <div class="col-sm-4">Supplier</div>
                              <div class="col-sm-8">
                                <input type="text" name="supplier_name" id="supplier_name" value="" disabled />
                              </div>
                            </div>
                            <div class="row middle">
                              <div class="col-sm-4">PO Type</div>
                              <div class="col-sm-8">
                                <input type="text" name="menu_id" id="menu_id" value="" disabled />
                              </div>
                            </div>
                            <div class="row middle">
                              <div class="col-sm-4">L\C Issuing Bank</div>
                              <div class="col-sm-8">
                                <input type="text" name="issuing_bank_branch_id" id="issuing_bank_branch_id" value="" disabled />
                              </div>
                            </div>
                          </code>
                      </div>
                  </div>
                  <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsImpLiabilityAdjust.submit()" accesskey="s"><u>S</u>ave</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsImpLiabilityAdjust.resetForm()" accesskey="r"><u>R</u>eset</a>
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsImpLiabilityAdjust.remove()" accesskey="d"><u>D</u>elete</a>
                  </div>
              </form>
          </div>
      </div>
  </div>
  <!-----===============Requisition Item=============----------->
  <div title="Liability Adjustment Details" style="padding:2px">
      <div class="easyui-layout" data-options="fit:true">
          <div data-options="region:'west',border:true,title:'New Requisition Item',iconCls:'icon-more',footer:'#ft4'" style="width:450px; padding:2px">
              <form id="impliabilityadjustchldFrm">
                  <div id="container">
                      <div id="body">
                          <code>
                              <div class="row middle">
                                <div class="col-sm-4">Payment Head </div>
                                <div class="col-sm-8">
                                  {!! Form::select('payment_head',$commercialhead,'',array('id'=>'payment_head')) !!}
                                  <input type="hidden" name="id" id="id" value="" />
                                  <input type="hidden" name="imp_liability_adjust_id" id="imp_liability_adjust_id" value="" />
                                </div>
                            </div>
                              <div class="row middle">
                                <div class="col-sm-4">Adj. Source </div>
                                  <div class="col-sm-8">
                                    {!! Form::select('adj_source',$commercialhead,'',array('id'=>'adj_source')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Loan Reference</div>
                                <div class="col-sm-8">
                                    <input type="text" name="loan_ref" id="loan_ref" value="" />
                                </div>                                    
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Bank Limit A/C</div>
                                <div class="col-sm-8">
                                    <input type="text" name="commercial_head_name" id="commercial_head_name" value="" ondblclick="MsImpLiabilityAdjustChld.bankWindowOpen()" placeholder="Double Click to Select"/>
                                    <input type="hidden" name="bank_account_id" id="bank_account_id">
                                </div> 
                            </div>
                            <div class="row middle">
                              <div class="col-sm-4 req-text">Tenure</div>
                              <div class="col-sm-8">
                                  <input type="text" name="tenor" id="tenor" value="" class="number integer"/>
                              </div>                                    
                            </div>
                            <div class="row middle">
                              <div class="col-sm-4 req-text">Maturity Date</div>
                              <div class="col-sm-8">
                                  <input type="text" name="maturity_date" id="maturity_date" value="" readonly />
                              </div>                                    
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Adjusted Amount</div>
                                <div class="col-sm-8">
                                    <input type="text" name="amount" id="amount" class="integer number" onchange="MsImpLiabilityAdjustChld.calculateAmount()"  />
                                </div>                                    
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Ex. Rate </div>
                                <div class="col-sm-8">
                                    <input type="text" name="exch_rate" id="exch_rate" class="integer number" onchange="MsImpLiabilityAdjustChld.calculateAmount()" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Domestic  Currency </div>
                                <div class="col-sm-8">
                                    <input type="text" name="dom_currency" id="dom_currency" class="integer number" readonly />
                                </div>
                            </div>   
                          </code>
                      </div>
                  </div>
                  <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsImpLiabilityAdjustChld.submit()" accesskey="s"><u>S</u>ave</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsImpLiabilityAdjustChld.resetForm()" accesskey="r"><u>R</u>eset</a>
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsImpLiabilityAdjustChld.remove()" accesskey="d"><u>D</u>elete</a>
                  </div>
              </form>
          </div>
          <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
            <table id="impliabilityadjustchldTbl" style="width:100%">
              <thead>
                <tr>
                  <th data-options="field:'id'" width="40">ID</th>
                  <th data-options="field:'payment_head'" width="100">Payment Head</th>
                  <th data-options="field:'adj_source'" width="100">Adj. Source</th>
                  <th data-options="field:'maturity_date'" width="100">Maturity Date</th>
                  <th data-options="field:'commercial_head_name'" width="100">A/C Type</th>
                  <th data-options="field:'amount'" width="130" align="right">Adjustment Amount</th>
                  <th data-options="field:'exch_rate'" width="100" align="right">Conv. Rate</th>
                  <th data-options="field:'dom_currency'" width="130" align="right">Domestic  Currency</th>
                </tr>
              </thead>
            </table>
          </div>
      </div>
  </div>
</div>

<!------------------>
<div id="bankRefWindow" class="easyui-window" title="Import LC Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
  <div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'west',split:true, title:'Search'" style="width:400px">
      <div id="body">
        <code>
          <form id="impdocacceptsearchFrm">
            <div class="row middle">
              <div class="col-sm-4">Invoice NO</div>
              <div class="col-sm-8">
              <input type="text" name="invoice_no" id="invoice_no" />
              </div>
            </div>
            <div class="row middle">
              <div class="col-sm-4">Invoice Date</div>
              <div class="col-sm-8">
              <input type="text" name="invoice_date" id="invoice_date" class="datepicker" />
              </div>
            </div>
            <div class="row middle">
              <div class="col-sm-4">Shipment Date</div>
              <div class="col-sm-8">
              <input type="text" name="shipment_date" id="shipment_date" class="datepicker" />
              </div>
            </div>
          </form>
        </code>
      </div>
      <p class="footer">
        <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsImpLiabilityAdjust.searchLiabilityAdjustGrid()">Search</a>
      </p>
    </div>
    <div data-options="region:'center'" style="padding:10px;">
      <table id="impacceptsearchTbl" style="width:610px">
        <thead>
          <tr>
          <th data-options="field:'imp_doc_accept_id'" width="50">ID</th>
          <th data-options="field:'lc_no'" width="150">Lc No.</th>
          <th data-options="field:'lc_amount'" width="80">Lc Value</th>
          <th data-options="field:'supplier_name'" width="150">Supplier</th>
          <th data-options="field:'invoice_no'" width="150">Invoice No.</th>
          <th data-options="field:'invoice_date'" width="100">Invoice Date</th>
          <th data-options="field:'bank_ref'" width="150">Bank Ref</th>
          <th data-options="field:'acceptance_value'" width="100" align="right">Acceptance Value</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
{{-- openbanckaccount window --}}
<div id="openadjbankaccountWindow" class="easyui-window" title="Bank Account Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
  <div class="easyui-layout" data-options="fit:true">
     <div data-options="region:'center',border:true,footer:'#bankaccountsearchTblFt'" style="padding:2px">
          <table id="adjbankaccountsearchTbl" style="width:100%">
              <thead>
                  <tr>
                      <th data-options="field:'id'" width="80">ID</th>
                      <th data-options="field:'name'" width="100">Name</th>
                      <th data-options="field:'account_no'" width="100">A/C No.</th>      
                      <th data-options="field:'account_type_id'" width="100">A/C Type</th>
                      <th data-options="field:'branch_name'" width="100">Branch Name</th>
                  </tr>
              </thead>
          </table>
          <div id="bankaccountsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
              <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#openadjbankaccountWindow').window('close')" style="width:80px">Close</a>
          </div>
      </div>
      <div data-options="region:'west',border:true,footer:'#bankaccountsearchFrmFt'" style="padding:2px; width:350px">
          <form id="adjbankaccountsearchFrm">
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
              <div id="bankaccountsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                  <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true"  onClick="MsImpLiabilityAdjustChld.searchAdjBankAccount()">Search</a>
              </div>
          </form>
      </div>
  </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Commercial/Import/MsAllImpLiabilityAdjustController.js"></script>
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
      if (this.value != this.value.replace(/[^0-9\.]/g, '')) 
      {
        this.value = this.value.replace(/[^0-9\.]/g, '');
      }
    });
  })(jQuery);

  $('#tenor').change(function(){
      MsImpLiabilityAdjustChld.setMaturityDate();
    });
</script>