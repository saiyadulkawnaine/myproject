<div class="easyui-tabs animated rollIn" data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;" id="acctabs">
    <div title="Accounting Year" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="accyearTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'start_date'" width="100">Start Date</th>
                            <th data-options="field:'end_date'" width="100">End Date</th>
                            <th data-options="field:'name'" width="100">Name</th>
                            <th data-options="field:'is_current'" width="100">Current Year</th>

                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add New Year',footer:'#ft2'" style="width: 350px; padding:2px">
                <form id="accyearFrm">
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
                                    <div class="col-sm-4 req-text">Current Year</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('is_current', $yesno,'1',array('id'=>'is_current')) !!}
                                    </div>
                                </div>

                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAccYear.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAccYear.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAccYear.remove()">Delete</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <!-----===============Accperiod=============----------->
    <div title="Accounting Period" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'New Account Period',iconCls:'icon-more',footer:'#utilaccperiodft'" style="width:450px; padding:2px">
                <form id="accperiodFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="acc_year_id" id="acc_year_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text"> Name </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="name" id="name" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Period </div>
                                    <div class="col-sm-8">
                                    <input type="text" name="period" id="period" class="intiger number" />
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
                                    <div class="col-sm-4">Is Open </div>
                                    <div class="col-sm-8">{!! Form::select('is_open', $yesno,'1',array('id'=>'is_open')) !!}</div>
                                </div>
                                

                            </code>
                        </div>
                    </div>
                    <div id="utilaccperiodft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAccPeriod.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('accperiodFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAccPeriod.remove()">Delete</a>
                    </div>

                </form>


            </div>
            <div data-options="region:'center',border:true,title:'Account Details'" style="padding:2px">
                <table id="accperiodTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'name'" width="80">Name</th>
                            <th data-options="field:'period'" width="100">Period</th>
                            <th data-options="field:'start_date'" width="100">Start Date</th>
                            <th data-options="field:'end_date'" width="100">End Date</th>
                            <th data-options="field:'is_open'" width="100">Is Open</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Account/MsAllAccYearController.js"></script>
<script>
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

</script>
