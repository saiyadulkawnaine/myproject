<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="jhutesaledlvorderapprovaltabs">
    <div title="Waiting For Approval" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="jhutesaledlvorderapprovalTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'app'" width="80" formatter='MsJhuteSaleDlvOrderApproval.approveButton'></th>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'payment_before_dlv'" width="60">Payment Before Delv</th>
                            <th data-options="field:'paid_amount'" width="100">Received Amount</th>
                            <th data-options="field:'do_no'" width="100" formatter="MsJhuteSaleDlvOrderApproval.formatpdf">DO No</th>
                            <th data-options="field:'do_date'" width="80">Do date</th>
                            <th data-options="field:'company_code'" width="60">Company</th>
                            <th data-options="field:'buyer_name'" width="100">Costomer</th>
                            <th data-options="field:'do_for'" width="80">DO For</th>
                            {{-- <th data-options="field:'location_name'" width="100">Location</th> --}}
                            <th data-options="field:'currency_code'" width="60">Currency</th>
                            <th data-options="field:'item_qty'" width="80">Qty</th>
                            <th data-options="field:'item_amount'" width="100">Amount</th>
                            {{-- <th data-options="field:'etd_date'" width="100">ETD Date</th> --}}
                            <th data-options="field:'advised_by'" width="100">Advised By</th>
                            <th data-options="field:'price_verified_by'" width="100">Price Varified By</th>
                            <th data-options="field:'remarks'" width="120">Remarks</th>

                            {{-- <th data-options="field:'pdf'" width="100" formatter='MsJhuteSaleDlvOrderApproval.jhutesaledlvorderButton'></th> --}}
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Sreach',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#jhutesaledlvorderapprovalFrmFt'" style="width:350px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="jhutesaledlvorderapprovalFrm">
                                <div class="row middle">
                                    <div class="col-sm-4">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Customer</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">DO Date</div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="jhutesaledlvorderapprovalFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsJhuteSaleDlvOrderApproval.get()">Search</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('jhutesaledlvorderapprovalFrm')">Reset</a>
                    
                </div>
            </div>
        </div>
    </div>

    <div title="Approved List" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="jhutesaledlvorderapprovedTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'app'" width="80" formatter='MsJhuteSaleDlvOrderApproval.unapproveButton'></th>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'payment_before_dlv'" width="100">Payment Before Delv</th>
                            <th data-options="field:'paid_amount'" width="100">Received Amount</th>
                            <th data-options="field:'do_no'" width="100" formatter="MsJhuteSaleDlvOrderApproval.formatpdf">DO No</th>
                            <th data-options="field:'do_date'" width="100">Do date</th>
                            <th data-options="field:'company_code'" width="100">Company</th>
                            <th data-options="field:'buyer_name'" width="100">Costomer</th>
                            <th data-options="field:'do_for'" width="100">DO For</th>
                            {{-- <th data-options="field:'location_name'" width="100">Location</th> --}}
                            <th data-options="field:'currency_code'" width="100">Currency</th>
                            <th data-options="field:'item_qty'" width="100">Qty</th>
                            <th data-options="field:'item_amount'" width="100">Amount</th>
                            {{-- <th data-options="field:'etd_date'" width="100">ETD Date</th> --}}
                            <th data-options="field:'advised_by'" width="100">Advised By</th>
                            <th data-options="field:'price_verified_by'" width="100">Price Varified By</th>
                            <th data-options="field:'remarks'" width="120">Remarks</th>
                            {{-- <th data-options="field:'pdf'" width="100" formatter='MsJhuteSaleDlvOrderApproval.jhutesaledlvorderButton'></th> --}}
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Sreach',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#jhutesaledlvorderapprovedFrmFt'" style="width:350px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="jhutesaledlvorderapprovedFrm">
                                <div class="row middle">
                                    <div class="col-sm-4">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Customer</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">DO Date</div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="app_date_from" id="app_date_from" class="datepicker" placeholder="From" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="app_date_to" id="app_date_to" class="datepicker"  placeholder="To" />
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="jhutesaledlvorderapprovedFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsJhuteSaleDlvOrderApproval.getApp()">Search</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('jhutesaledlvorderapprovedFrm')">Reset</a>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/approval/MsJhuteSaleDlvOrderApprovalController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });

    $('.integer').keyup(function () {
      if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
      }
  });
  $('#jhutesaledlvorderFrm [id="buyer_id"]').combobox();

</script>
    