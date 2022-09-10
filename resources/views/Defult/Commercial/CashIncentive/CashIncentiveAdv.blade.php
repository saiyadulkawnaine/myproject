<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="advancetabs">
    <div title="Referrence Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="cashincentiveadvTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'company_id'" width="80">Company</th>
                            <th data-options="field:'exporter_bank_branch_id'" width="150">Applied To</th>
                            <th data-options="field:'applied_date'" width="80">Applied Date</th>
                            <th data-options="field:'advance_per'" width="100">Advance%</th>
                            <th data-options="field:'remarks'" width="100">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add Information',footer:'#ft2'" style="width: 350px; padding:2px">
                <form id="cashincentiveadvFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row" class="display:none;">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>                              
                                <div class="row middle">
                                    <div class="col-sm-4">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                   <div class="col-sm-4">Applied To</div>
                                   <div class="col-sm-8">
                                       {!! Form::select('exporter_bank_branch_id', $bankbranch,'',array('id'=>'exporter_bank_branch_id')) !!} 
                                   </div>
                                </div>
                                <div class="row middle">
                                   <div class="col-sm-4 req-text">Applied Date </div>
                                   <div class="col-sm-8">
                                      <input type="text" name="applied_date" id="applied_date" class="datepicker" placeholder="yy-mm-dd" />
                                   </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Advance %</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="advance_per" id="advance_per" value="" class="number integer" />
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
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCashIncentiveAdv.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('cashincentiveadvFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsCashIncentiveAdv.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true" id="delete" onClick="MsCashIncentiveAdv.getAdvLetter()">Adv.Letter</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Claim Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px;width:600px">
                <table id="cashincentiveadvclaimTbl" style="width:600px">
                    <thead>
                      <tr>
                        <th data-options="field:'id'" width="40px">ID</th>
                        <th data-options="field:'cash_incentive_ref_id'" width="40px">RefID</th>
                        <th data-options="field:'lc_sc_no'" width="150">LC/SC No</th>
                        <th data-options="field:'lc_sc_date'" width="70px">LC/SC Date</th>
                        <th data-options="field:'bank_file_no'" width="100px">Bank File No</th>
                        <th data-options="field:'local_cur_amount'" width="80px" align="right">Claim <br/>Amount-Tk</th>
                        <th data-options="field:'advance_per'" width="80px" align="right">Advance %</th>
                        <th data-options="field:'amount'" width="100px" align="right">Advance-Tk</th>
                        <th data-options="field:'rate'" width="70px" align="right">Interest <br/>Rate</th>
                        <th data-options="field:'claim_amount'" width="80px" align="right">Claim <br/>Amount-USD</th>
                        <th data-options="field:'remarks'" width="60px" align="right">Remarks</th>           
                      </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Cash Incentive Claims',footer:'#ft6'" style="width: 400px; padding:2px">
                <form id="cashincentiveadvclaimFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="cash_incentive_adv_id" id="cash_incentive_adv_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Ref ID</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="cash_incentive_ref_id" id="cash_incentive_ref_id" value="" ondblclick="MsCashIncentiveAdvClaim.openCashIncentiveRefWindow()" placeholder=" Double Click" readonly/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Claim-Tk</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="local_cur_amount" id="local_cur_amount" value="" class="number integer"  disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Claim-USD</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="claim_amount" id="claim_amount" value=""  class="number integer" disabled />
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-4">Advance %</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="advance_per" id="advance_per" value="" placeholder="  write" class="number integer" disabled/>
                                    </div>
                                </div>   
                                <div class="row middle">
                                    <div class="col-sm-4">Adv. Amount</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount" id="amount" value="" placeholder="  number" class="number integer"  readonly/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Interest Rate %</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rate" id="rate" value="" placeholder="  write" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">LC/SC</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="lc_sc_no" id="lc_sc_no" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Buyer</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="buyer_name" id="buyer_name" value="" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Bank File No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="bank_file_no" id="bank_file_no" value="" placeholder=" write" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Internal File No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="file_no" id="file_no" value="" placeholder=" write" disabled/>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft6" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCashIncentiveAdvClaim.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsCashIncentiveAdvClaim.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsCashIncentiveAdvClaim.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- Export Lc Window --}}
 <div id="opencashincentiverefWindow" class="easyui-window" title="Cash Incentive Reference /Export Lc Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
     <div class="easyui-layout" data-options="fit:true">
         <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="cashincentiverefsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">LC No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="lc_sc_no" id="lc_sc_no" value="">
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Bank File No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="bank_file_no" id="bank_file_no" value="" class="datepicker" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">LC Date </div>
                                <div class="col-sm-8">
                                    <input type="text" name="lc_sc_date" id="lc_sc_date" value="" class="datepicker" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Beneficiary</div>
                                <div class="col-sm-8">
                                    {!! Form::select('beneficiary_id',$company,'',array('id'=>'beneficiary_id')) !!}
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsCashIncentiveAdvClaim.searchIncentiveRef()">Search</a>
                </p>
            </div>
         </div>
         <div data-options="region:'center'" style="padding:10px;">
            <table id="cashincentiverefsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="30">ID</th>
                        <th data-options="field:'incentive_no'" width="70">Incentive ID</th>
                        <th data-options="field:'company_name'" width="100">Exporter</th>
                        <th data-options="field:'buyer_name'" width="90">Buyer</th>
                        <th data-options="field:'lc_sc_no'" width="150">LC/SC No</th>
                        <th data-options="field:'replaced_lc_sc_no'" width="150">Replaces LC/SC</th>
                        <th data-options="field:'bank_file_no'" width="100">Bank File No</th>
                        <th data-options="field:'file_no'" width="100">Internal File No</th>
                        <th data-options="field:'region_id'" width="80">Region</th>
                        <th data-options="field:'claim_sub_date'" width="80">Claim Sub Date</th>
                        <th data-options="field:'remarks'" width="100">Remarks</th>
                        <th data-options="field:'lc_sc_date'" width="90">LC/Contract Date</th> 
                        <th data-options="field:'lc_sc_value'" width="100" align="right">Contract Value</th>
                        <th data-options="field:'advance_amount'" width="100" align="right">Advance Amount</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#opencashincentiverefWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Commercial/CashIncentive/MsAllCashIncentiveAdvanceController.js"></script>
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
})(jQuery);

</script>
 