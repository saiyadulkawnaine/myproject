<div class="easyui-layout" style="width:100%;height:100%; border:none" id="renewalentrytabs">  
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
            <table id="renewalentryTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'renewal_item_id'" width="100">Renewal Item</th>
                        <th data-options="field:'renewal_no'" width="100">Renewal No</th>
                        <th data-options="field:'company_id'" width="70">Company Name</th>
                        <th data-options="field:'validity_start'" width="100">Validity Start</th>
                        <th data-options="field:'validity_end'" width="100">Validity End</th>
                        <th data-options="field:'document_no'" width="100">Document No</th>
                        <th data-options="field:'no_of_sewing_machine'" width="100">No of Sew M/C</th>
                        <th data-options="field:'fees'" width="100">Fees</th>
                        <th data-options="field:'processing_expense'" width="100">Processing Exp</th>
                        <th data-options="field:'fees_deposit_to'" width="100">Fees Deposit To</th>
                        <th data-options="field:'applied_date'" width="100">Applied Date</th>
                        <th data-options="field:'renewed_date'" width="100">Renewed Date</th>
                        <th data-options="field:'user_id'" width="100">Dealing Person</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'west',border:true,title:'Renewal Entry',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="width:450px; padding:2px">
            <div id="container">
                <div id="body">
                    <code>
                        <form id="renewalentryFrm">
                            <div class="row" style="display:none">
                                <input type="hidden" name="id" id="id">
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Renewal No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="renewal_no" id="renewal_no" disabled />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Company</div>
                                <div class="col-sm-8">
                                    {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Renewal Item </div>
                                <div class="col-sm-8">
                                    {!! Form::select('renewal_item_id', $renewalitem,'',array('id'=>'renewal_item_id')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Validity Start</div>
                                <div class="col-sm-8">
                                    <input type="text" name="validity_start" id="validity_start" class="datepicker" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Validity End</div>
                                <div class="col-sm-8">
                                    <input type="text" name="validity_end" id="validity_end" class="datepicker" />
                                </div>
                            </div>   
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Document No</div>
                                <div class="col-sm-8">
                                    <input  type="text" name="document_no" id="document_no"  /> 
                                </div>
                            </div>          
                            <div class="row middle">
                                <div class="col-sm-4 ">No of Sew.M/C</div>
                                <div class="col-sm-8">
                                    <input type="text" name="no_of_sewing_machine" id="no_of_sewing_machine" class="number integer" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Fees</div>
                                <div class="col-sm-8">
                                    <input type="text" name="fees" id="fees" class="number integer" />
                                </div>
                            </div>  
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Processing Exp</div>
                                <div class="col-sm-8">
                                    <input  type="text" name="processing_expense" id="processing_expense" class="number integer"/>   
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Fees Deposit To </div>
                                <div class="col-sm-8">
                                    <input  type="text" name="fees_deposit_to" id="fees_deposit_to"  /> 
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Applied Date</div>
                                <div class="col-sm-8">
                                    <input type="text" name="applied_date" id="applied_date" class="datepicker" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 ">Renewed Date</div>
                                <div class="col-sm-8">
                                    <input type="text" name="renewed_date" id="renewed_date" class="datepicker" />
                                </div>
                            </div>  
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Dealing Person</div>
                                <div class="col-sm-8">
                                    {!! Form::select('user_id', $user,'',array('id'=>'user_id')) !!}
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
                <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsRenewalEntry.submit()">Save</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('renewalentryFrm')">Reset</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsRenewalEntry.remove()">Delete</a>
                {{-- <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="save"  onClick="MsRenewalEntry.pdf()">PDF</a> 
                <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsRenewalEntry.showData()">Show</a> --}}
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/HRM/MsRenewalEntryController.js"></script>
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
         changeYear: true
      });

      $('.integer').keyup(function () {
         if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
         }
      });

   })(jQuery);

</script>

      
