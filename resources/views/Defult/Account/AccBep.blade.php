<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="accbeptabs">
    <div title="Break Even Point Entry Page" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="accbepTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'start_date'" width="100">Start Date</th>
                            <th data-options="field:'end_date'" width="100">End Date</th>
                            <th data-options="field:'profitcenter_id'" width="100">Profit Center</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add New Entry',footer:'#ft2'" style="width: 350px; padding:2px">
                <form id="accbepFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <div class="col-sm-4 req-text">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Profit Center </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('profitcenter_id', $profitcenter,'',array('id'=>'profitcenter_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Start Date </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="start_date" id="start_date" class="firstdate" />
                                        <input type="hidden" name="id" id="id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">End Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="end_date" id="end_date" class="lastdate" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Unit Price </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="unit_price" id="unit_price" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Exch. Rate </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="exch_rate" id="exch_rate" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Profit Percent </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="profit_per" id="profit_per" value="" class="number integer" />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAccBep.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAccBep.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAccBep.remove()">Delete</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <div title="BEP Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="accbepentryTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'acc_chart_ctrl_head_id'" width="120">Account Head</th>
                            <th data-options="field:'expense_type_id'" width="80">Expense Type</th>
                            <th data-options="field:'amount'" width="100">Amount</th>
                            <th data-options="field:'salary_prod_bill_id'" width="100">Salary&Prod Bill</th>
                            <th data-options="field:'remarks'" width="100">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add BEP Details',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ftbepdetail'" style="width:450px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="accbepentryFrm">
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="acc_bep_id" id="acc_bep_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Account Head</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('acc_chart_ctrl_head_id', $ctrlHead,'',array('id'=>'acc_chart_ctrl_head_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Expense Type</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('expense_type_id', $expenseType,'',array('id'=>'expense_type_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Salary&Production Bill</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('salary_prod_bill_id', $salaryProdBill,'',array('id'=>'salary_prod_bill_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Amount </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount" id="amount" value="" class="number integer" />
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
                <div id="ftbepdetail" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAccBepEntry.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAccBepEntry.resetForm()">Reset</a>
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAccBepEntry.remove()">Delete</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Account/MsAllAccBepEntryController.js"></script>
<script>
    (function(){    
    $('#accbepentryFrm [id="acc_chart_ctrl_head_id"]').combobox();


    $(".firstdate").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        beforeShowDay: function(date) {

            if (date.getDate() == 1) {
                return [true, ''];
            }
            return [false, ''];
        }
    });

    function LastDayOfMonth(Year, Month) {
        return (new Date((new Date(Year, Month + 1, 1)) - 1)).getDate();
    }

    $('.lastdate').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        beforeShowDay: function(date) {
            //getDate() returns the day (0-31)
            if (date.getDate() == LastDayOfMonth(date.getFullYear(), date.getMonth())) {
                return [true, ''];
            }
            return [false, ''];
        }
    });
    })(jQuery);
</script>