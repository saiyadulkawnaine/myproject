<div class="easyui-tabs" style="width:100%;height:100%; border:none">
    <div title="Daily" style="padding:1px" data-options="selected:true">
        <div class="easyui-layout animated rollIn"  data-options="fit:true">
            <div data-options="region:'center',border:true,title:'Daily Textile Efficiency'" style="padding:2px">
                <div id="txtdailyefficiencyreportTblContainer">
                </div>
            </div>
            <div data-options="region:'west',border:true,title:'Search',footer:'#txtdailyefficiencyreportFrmFt'" style="width:350px; padding:2px">
                <form id="txtdailyefficiencyreportFrm">
                <div id="container">
                <div id="body">
                <code>  

                <div class="row middle">
                <div class="col-sm-4 req-text">Prod. Date</div>
                <div class="col-sm-8">
                <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" value="<?php echo date('Y-m-d',strtotime("-1 days")); ?>" />

                </div>
                </div>
                </code>
                </div>
                </div>
                <div id="txtdailyefficiencyreportFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsTxtDailyEfficiencyReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsTxtDailyEfficiencyReport.resetForm('txtdailyefficiencyreportFrm')" >Reset</a>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Monthly" style="padding:1px" data-options="selected:true">
        <div class="easyui-layout animated rollIn"  data-options="fit:true">
            <div data-options="region:'center',border:true,title:'Monthly Textile Efficiency'" style="padding:2px">
                <div id="txtmonthlyefficiencyreportTblContainer">
                </div>
            </div>
            <div data-options="region:'west',border:true,title:'Search',footer:'#txtmonthlyefficiencyreportFrmFt'" style="width:350px; padding:2px">
                <form id="txtmonthlyefficiencyreportFrm">
                <div id="container">
                <div id="body">
                <code>  
                <div class="row middle">
                <div class="col-sm-4 req-text">Prod. Date From</div>
                <div class="col-sm-8">
                <input type="text" name="txtmonthly_date_from" id="txtmonthly_date_from" class="datepicker"  placeholder="To" value="<?php echo date('Y-m-d',strtotime("-31 days")); ?>" />

                </div>
                </div>
                <div class="row middle">
                <div class="col-sm-4 req-text">Prod. Date To</div>
                <div class="col-sm-8">
                <input type="text" name="txtmonthly_date_to" id="txtmonthly_date_to" class="datepicker"  placeholder="To" value="<?php echo date('Y-m-d',strtotime("-1 days")); ?>" />

                </div>
                </div>
                </code>
                </div>
                </div>
                <div id="txtmonthlyefficiencyreportFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsTxtDailyEfficiencyReport.getMonthly()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsTxtDailyEfficiencyReport.resetForm('txtmonthlyefficiencyreportFrm')" >Reset</a>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>




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
    changeYear: true
});
</script>
<script type="text/javascript" src="<?php echo url('/');?>/js/report/FabricProduction/MsTxtDailyEfficiencyReportController.js"></script>


