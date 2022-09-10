<div class="easyui-tabs" style="width:100%;height:100%; border:none">
    <div title="Monthly" style="padding:1px" data-options="selected:true">
        <div class="easyui-layout animated rollIn"  data-options="fit:true">
            <div data-options="region:'center',border:true,title:'Monthly Target Achievement'" style="padding:2px">
                <div id="targetachievementreportTblContainer">
                </div>
            </div>
            <div data-options="region:'west',border:true,title:'Search',footer:'#targetachievementreportFrmFt'" style="width:350px; padding:2px">
                <form id="targetachievementreportFrm">
                <div id="container">
                <div id="body">
                <code>  
                <div class="row middle">
                <div class="col-sm-4 req-text">Date From</div>
                <div class="col-sm-8">
                <input type="text" name="tgtach_date_from" id="tgtach_date_from" class="datepicker"  placeholder="To" value="<?php echo date('Y-m')."-01"; ?>" />

                </div>
                </div>
                <div class="row middle">
                <div class="col-sm-4 req-text">Date To</div>
                <div class="col-sm-8">
                <input type="text" name="tgtach_date_to" id="tgtach_date_to" class="datepicker"  placeholder="To" value="<?php echo date('Y-m-t'); ?>" />

                </div>
                </div>
                </code>
                </div>
                </div>
                <div id="targetachievementreportFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsTargetAchievementReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsTargetAchievementReport.resetForm('targetachievementreportFrm')" >Reset</a>
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
<script type="text/javascript" src="<?php echo url('/');?>/js/report/Dashbord/MsTargetAchievementReportController.js"></script>


