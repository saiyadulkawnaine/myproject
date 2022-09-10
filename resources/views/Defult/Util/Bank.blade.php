<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="utilbanktabs">
    <div title="Banks" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'Banks'" style="padding:2px">
                <table id="bankTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'name'" width="100">Name</th>
                            <th data-options="field:'code'" width="100">Code</th>
                            <th data-options="field:'swift_code'" width="100">Swift Code</th>
                            <th data-options="field:'bank_type_id'" width="100">Bank Type</th>
                            <th data-options="field:'address'" width="100">Address</th>
                        </tr>
                    </thead>
                </table>

            </div>
            <div data-options="region:'west',border:true,title:'Add New Bank',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="width:450px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="bankFrm">
                                <div class="row">
                                    <div class="col-sm-4 req-text">Name</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="name" id="name" />
                                        <input type="hidden" name="id" id="id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Code</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="code" id="code" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Swift Code</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="swift_code" id="swift_code" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Address</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="address" id="address" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Bank Type</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('bank_type_id', $bankType,'',array('id'=>'bank_type_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Is Islamic Bank</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('is_islamic_bank_id', $yesno,'',array('id'=>'is_islamic_bank_id')) !!}
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="ft2" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBank.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('bankFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsBank.remove()">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Bank Branch" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'New Branch',iconCls:'icon-more',footer:'#utilbankbranchft'" style="width:450px; padding:2px">
                <form id="bankbranchFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <div class="col-sm-4 req-text">Branch Name </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="branch_name" id="branch_name"/>
                                        <input type="hidden" name="bank_id" id="Bank_id" value="" />
                                        <input type="hidden" name="id" id="id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Address :</div>
                                    <div class="col-sm-8">
                                        <textarea name="address" id="address"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Contact Person:</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="contact" id="contact" value="" />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="utilbankbranchft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBankBranch.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('bankbranchFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsBankBranch.remove()">Delete</a>
                    </div>

                </form>


            </div>
            <div data-options="region:'center',border:true,title:'Branch Details'" style="padding:2px">
                <table id="bankbranchTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'branch_name'" width="100">Branch</th>
                            <th data-options="field:'address'" width="150">Address</th>
                            <th data-options="field:'contact'" width="120">Contact Person</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div title="Bank Account" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'New Account',iconCls:'icon-more',footer:'#utilbankaccountft'" style="width:450px; padding:2px">
                <form id="bankaccountFrm">
                    <div id="container">
                        <div id="body">
                            <code> 
                                <div class="row">
                                    <input type="hidden" name="bank_branch_id" id="bank_branch_id" value="" />
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Account No. </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="account_no" id="account_no" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Account Type</div>
                                    <div class="col-sm-8">
                                    {!! Form::select('account_type_id', $commercialhead,'',array('id'=>'account_type_id','style'=>'width: 100%; border-radius:2px')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Currency </div>
                                    <div class="col-sm-8">
                                    {!! Form::select('currency_id', $currency,'',array('id'=>'currency_id')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Company</div>
                                    <div class="col-sm-8">
                                    {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Limit </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="limit" id="limit" value="" class="number integer" />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="utilbankaccountft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBankAccount.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsBankAccount.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsBankAccount.remove()">Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'Account Details'" style="padding:2px">
                <table id="bankaccountTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'account_no'" width="100">A/C No.</th>
                            <th data-options="field:'account_type_id'" width="100">A/C Type</th>
                            <th data-options="field:'currency_id'" width="100">Currency</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAllBankController.js"></script>
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
    $('#bankaccountFrm [id="account_type_id"]').combobox();
</script>