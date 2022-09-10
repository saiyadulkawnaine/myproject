<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="realizetabs">
 <div title="Referrence Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="cashincentiverealizeTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="40">ID</th>
       <th data-options="field:'incentive_no'" width="80">ERP ID</th>
       <th data-options="field:'buyer_id'" width="80">Buyer</th>
       <th data-options="field:'lc_sc_no'" width="140">LC/SC No</th>
       <th data-options="field:'bank_file_no'" width="120" align="center">Bank File No</th>
       <th data-options="field:'claim_amount'" width="100" align="right">Claim Amount</th>
       <th data-options="field:'sanctioned_amount'" width="100" align="right">Sanctioned Amount</th>
       <th data-options="field:'advance_amount_tk'" width="100" align="right">Loan Amount</th>
       <th data-options="field:'loan_ref_no'" width="100">Loan Ref.</th>

      </tr>
     </thead>
    </table>
   </div>
   <div data-options="region:'west',border:true,title:'Add Information',footer:'#ft2'"
    style="width: 350px; padding:2px">
    <form id="cashincentiverealizeFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row" class="display:none;">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Bank File No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="bank_file_no" id="bank_file_no" ondblclick="MsCashIncentiveRealize.openIncentiveRealizWindow()" placeholder=" Double Click" readonly />
                                        <input type="hidden" name="cash_incentive_ref_id" id="cash_incentive_ref_id" value="" />
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">ERP ID </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="incentive_no" id="incentive_no" value="" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Claim Amount Tk</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="claim_amount" id="claim_amount" class="number integer"  disabled />
                                    </div>
                                </div>     
                                <div class="row middle">
                                    <div class="col-sm-4">Sanctioned Amount Tk</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sanctioned_amount" id="sanctioned_amount" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Loan Amount Tk</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="advance_amount_tk" id="advance_amount_tk" value="" class="number integer" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Loan Ref</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="loan_ref_no" id="loan_ref_no" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Buyer </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="buyer_id" id="buyer_id" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">LC/SC No </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="lc_sc_no" id="lc_sc_no" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Lien Bank </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="exporter_branch_name" id="exporter_branch_name" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Currency </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="currency_id" id="currency_id" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Company </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="company_name" id="company_name" value="" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks </div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>
                            </code>
      </div>
     </div>
     <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsCashIncentiveRealize.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsCashIncentiveRealize.resetForm()">Reset</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsCashIncentiveRealize.remove()">Delete</a>
     </div>
    </form>
   </div>
  </div>
 </div>
 <div title="Received Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px;width:600px">
    <table id="cashincentiverealizercvTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="40px">ID</th>
       <th data-options="field:'receive_date'" width="100px">Received Date</th>
       <th data-options="field:'commercial_head_id'" width="100px" align="right">A/C Head</th>
       <th data-options="field:'sanctioned_amount'" width="60px" align="right">Sanctioned Amount Tk</th>
       <th data-options="field:'amount'" width="60px" align="right">Amount</th>
       <th data-options="field:'tax_percent'" width="60px" align="right">Tax %</th>
       <th data-options="field:'remarks'" width="80px" align="right">Remarks</th>
      </tr>
     </thead>
    </table>
   </div>
   <div data-options="region:'west',border:true,title:'Received Details',footer:'#ft5'"
    style="width: 400px; padding:2px">
    <form id="cashincentiverealizercvFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="cash_incentive_realize_id" id="cash_incentive_realize_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Realized Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="receive_date" id="receive_date" value="" placeholder="  YYYY-mm-dd" class="datepicker" />
                                    </div>
                                </div> 
                                <div class="row middle">
                                 <div class="col-sm-4 req-text">A/C Head</div>
                                 <div class="col-sm-8">
                                  {!! Form::select('commercial_head_id',
                                  $commercialhead,'',array('id'=>'commercial_head_id','style'=>'width:100%;border-radius:2px')) !!}
                                 </div>
                                </div>
                                <div class="row middle">
                                 <div class="col-sm-4">Sanctioned Amount Tk</div>
                                 <div class="col-sm-8">
                                  <input type="text" name="sanctioned_amount" id="sanctioned_amount" value="" class="number integer" disabled/>
                                 </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4 req-text">Text %</div>
                                  <div class="col-sm-8">
                                   <input type="text" name="tax_percent" id="tax_percent"
                                    onchange="MsCashIncentiveRealizeRcv.calculateReceiveAmount()">
                                  </div>
                                 </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Amount </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount" id="amount" value="" placeholder="  number" class="number integer" />
                                    </div>
                                </div>    
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>   
                            </code>
      </div>
     </div>
     <div id="ft5" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsCashIncentiveRealizeRcv.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsCashIncentiveRealizeRcv.resetForm()">Reset</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsCashIncentiveRealizeRcv.remove()">Delete</a>
     </div>
    </form>
   </div>
  </div>
 </div>
</div>
{{-- Cash Incentive Reference Window --}}
<div id="openRefWindow" class="easyui-window"
 title="Cash Incentive Realization /Incentive Reference Bank File No Search Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
   <div class="easyui-layout" data-options="fit:true">
    <div id="body">
     <code>
                    <form id="cashrefsearchFrm">
                        <div class="row middle">
                            <div class="col-sm-4">LC No</div>
                            <div class="col-sm-8">
                                <input type="text" name="lc_sc_no" id="lc_sc_no" value="">
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Claim Sub.Date</div>
                            <div class="col-sm-8">
                                <input type="text" name="claim_sub_date" id="claim_sub_date" value="" class="datepicker" />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">ERP ID</div>
                            <div class="col-sm-8">
                                <input type="text" name="incentive_no" id="incentive_no" value="" />
                            </div>
                        </div>
                    </form>
                </code>
    </div>
    <p class="footer">
     <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px"
      onClick="MsCashIncentiveRealize.searchIncentiveRefGrid()">Search</a>
    </p>
   </div>
  </div>
  <div data-options="region:'center'" style="padding:10px;">
   <table id="cashrefsearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="40">ID</th>
      <th data-options="field:'incentive_no'" width="70">Incentive ID</th>
      <th data-options="field:'company_name'" width="100">Exporter</th>
      <th data-options="field:'buyer_id'" width="90">Buyer</th>
      <th data-options="field:'lc_sc_no'" width="150">LC/SC No</th>
      <th data-options="field:'replaced_lc_sc_no'" width="150">Replaces LC/SC</th>
      <th data-options="field:'bank_file_no'" width="100">Bank File No</th>
      <th data-options="field:'region_id'" width="80">Region</th>
      <th data-options="field:'claim_sub_date'" width="80">Claim Sub Date</th>
      <th data-options="field:'remarks'" width="100">Remarks</th>
     </tr>
    </thead>
   </table>
  </div>
  <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
   <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
    onclick="$('#openRefWindow').window('close')" style="width:80px">Close</a>
  </div>
 </div>
</div>

<script type="text/javascript"
 src="<?php echo url('/');?>/js/Commercial/CashIncentive/MsAllCashIncentiveRealizeController.js"></script>
<script>
 (function(){
  $('#cashincentiverealizercvFrm [id=commercial_head_id]').combobox();
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
})(jQuery);

</script>