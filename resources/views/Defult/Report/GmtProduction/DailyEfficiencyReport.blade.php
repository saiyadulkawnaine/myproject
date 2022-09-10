<div class="easyui-tabs" style="width:100%;height:100%; border:none">
    <div title="Daily" style="padding:1px" data-options="selected:true">
        <div class="easyui-layout animated rollIn"  data-options="fit:true">
            <div data-options="region:'center',border:true,title:'Monthly RMG Efficiency'" style="padding:2px">
                <div id="dailyefficiencyreportTblContainer">
                </div>
            </div>
            <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
                <form id="dailyefficiencyreportFrm">
                <div id="container">
                <div id="body">
                <code>  

                <div class="row middle">
                <div class="col-sm-4 req-text">Sewing. Date</div>
                <div class="col-sm-8">
                <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" value="<?php echo date('Y-m-d') ?>" />

                </div>
                </div>
                </code>
                </div>
                </div>
                <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsDailyEfficiencyReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsDailyEfficiencyReport.resetForm('dailyefficiencyreportFrm')" >Reset</a>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Monthly" style="padding:1px" data-options="selected:true">
        <div class="easyui-layout animated rollIn"  data-options="fit:true">
            <div data-options="region:'center',border:true,title:'Monthly RMG Efficiency'" style="padding:2px">
                <div id="monthlyefficiencyreportTblContainer">
                </div>
            </div>
            <div data-options="region:'west',border:true,title:'Search',footer:'#monthlyefficiencyreportFrmFt'" style="width:350px; padding:2px">
                <form id="monthlyefficiencyreportFrm">
                <div id="container">
                <div id="body">
                <code>  
                <div class="row middle">
                <div class="col-sm-4 req-text">Sew. Date From</div>
                <div class="col-sm-8">
                <input type="text" name="monthly_date_from" id="monthly_date_from" class="datepicker"  placeholder="To" value="<?php echo date('Y-m-d',strtotime("-31 days")); ?>" />

                </div>
                </div>
                <div class="row middle">
                <div class="col-sm-4 req-text">Sew. Date To</div>
                <div class="col-sm-8">
                <input type="text" name="monthly_date_to" id="monthly_date_to" class="datepicker"  placeholder="To" value="<?php echo date('Y-m-d',strtotime("-1 days")); ?>" />

                </div>
                </div>
                </code>
                </div>
                </div>
                <div id="monthlyefficiencyreportFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsDailyEfficiencyReport.getMonthly()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsDailyEfficiencyReport.resetForm('monthlyefficiencyreportFrm')" >Reset</a>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div id="dailyefficiencyreportImageWindow" class="easyui-window" title="Image" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:500px;height:500px;padding:2px;">
    <div id="dailyefficiencyreportimagegrid" style="width: 100%; height: 100%">
    </div>
</div>

<div id="dailyefficiencyreportDetailWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="dailyefficiencyreportdetailsTbl" border="1" style="width:1890px">
        <thead>
            <tr>
            <th data-options="field:'style_ref',halign:'center'" width="80">Style Ref</th>
            <th data-options="field:'sale_order_no',halign:'center'" width="80">Sales Order</th>
            <th data-options="field:'item_description',halign:'center'" width="80">Gmt Item</th>
            <th data-options="field:'color_name',halign:'center'" width="80">Color</th>
            <th data-options="field:'size_name',halign:'center'" width="80">Size</th>
            <th data-options="field:'smv',halign:'center'" width="80">SMV</th>
            <th data-options="field:'sewing_effi_per',halign:'center'" width="80">Eff. %</th>
            <th data-options="field:'sew_qty',halign:'center'" align="right" width="80">Qty</th>
        </tr>
    </thead>
    
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
<script type="text/javascript" src="<?php echo url('/');?>/js/report/GmtProduction/MsDailyEfficiencyReportController.js"></script>


