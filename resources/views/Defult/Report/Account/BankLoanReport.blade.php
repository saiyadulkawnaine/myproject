<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Bank Liablity Position'" style="padding:2px" id="bankloanreportcontainer">
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#bankloanreportFrmFt'" style="width:350px; padding:2px">
        <form id="bankloanreportFrm">
            <div id="container">
                <div id="body">
                    <code>     
                        <div class="row middle">
                        <div class="col-sm-4 req-text">As On </div>
                        <div class="col-sm-8">
                        <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" value="<?php echo date('Y-m-d') ?>"  />
                        </div>
                        </div>
                        <div class="row middle">
                        <div class="col-sm-4">Company </div>
                        <div class="col-sm-8">{!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}</div>
                        </div>
                        <div class="row middle">
                        <div class="col-sm-4">Bank </div>
                        <div class="col-sm-8">{!! Form::select('bank_id', $bank,'',array('id'=>'bank_id')) !!}</div>
                        </div>
                    </code>
                </div>
            </div>
            <div id="bankloanreportFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBankLoanReport.get()">Show</a>
            
            </div>
        </form>
    </div>
</div>





<script type="text/javascript" src="<?php echo url('/');?>/js/report/Account/MsBankLoanReportController.js"></script>
<script>
$(".datepicker" ).datepicker({
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
</script>

