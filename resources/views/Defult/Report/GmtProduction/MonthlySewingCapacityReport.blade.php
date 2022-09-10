<div class="easyui-layout animated rollIn" data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Line Wise Hourly Production'" style="padding:2px">
        <div id="monthlysewingcapacityreportTblContainer">
        </div>

    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="monthlysewingcapacityreportFrm">
            <div id="container">
                <div id="body">
                    <code>
                    <div class="row middle">
                        <div class="col-sm-4">Company </div>
                        <div class="col-sm-8">
                            {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4 req-text">Year </div>
                        <div class="col-sm-8">{!! Form::select('year', $years,$selected_year,array('id'=>'year')) !!}</div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4 req-text">Month Range </div>
                        <div class="col-sm-4" style="padding-right:0px">
                            {!! Form::select('month_from', $months,'',array('id'=>'month_from','placeholder'=>'Month From')) !!}
                        </div>
                        <div class="col-sm-4" style="padding-left:0px">
                            {!! Form::select('month_to', $months,'',array('id'=>'month_to','placeholder'=>'Month To')) !!}
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Location </div>
                        <div class="col-sm-8">
                            {!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Prod. Sourse </div>
                        <div class="col-sm-8">
                            {!! Form::select('prod_source_id', $productionsource,'',array('id'=>'prod_source_id')) !!}
                        </div>
                    </div> 
                </code>
                </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
                    iconCls="icon-save" plain="true" id="save" onClick="MsMonthlySewingCapacityReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
                    iconCls="icon-remove" plain="true" id="delete"
                    onClick="MsMonthlySewingCapacityReport.resetForm('monthlysewingcapacityreportFrm')">Reset</a>
            </div>

        </form>
    </div>
</div>


<script type="text/javascript"
    src="<?php echo url('/');?>/js/report/GmtProduction/MsMonthlySewingCapacityReportController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script>