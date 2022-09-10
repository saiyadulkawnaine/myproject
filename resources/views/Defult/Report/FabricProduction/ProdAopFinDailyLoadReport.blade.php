<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Daily Dyeing/Finishing M/C Wise Production'" style="padding:2px">
        <table id="prodaopfindailyloadreportTbl" style="width:100%">
            <thead>
                <tr>
                    <th data-options="field:'machine_no'" width="100" >M/C No</th>
                    <th data-options="field:'brand'" width="100">Brand</th>
                    <th data-options="field:'origin'" width="100">Origin</th>
                    <th data-options="field:'asset_group'" width="100">Group</th>
                    <th data-options="field:'prod_capacity'" width="100" align="right">Capacity</th>
                    <th data-options="field:'production_process_id'" width="150">Production Process</th>
                    <th data-options="field:'roll_qty'" width="100" align="right">Produced Qty</th>
                    <th data-options="field:'unused_prod_capacity'" width="100" align="right">Unused Capacity</th>
                    <th data-options="field:'posting_date'" width="80">Prod Date</th>
                    <th data-options="field:'idle_date'" width="80">M/C Idle Date</th>
                    <th data-options="field:'idle_time'" width="80">M/C Idle Time</th>
                    <th data-options="field:'reason'" width="80">Reason</th>
                    <th data-options="field:'idle_hour'" width="80">Idle Hour</th>
                    <th data-options="field:'remarks'" width="300">Remarks</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:320px; padding:2px">
        <form id="prodaopfindailyloadreportFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Prod.Date</div>
                            <div class="col-sm-4" style="padding-right:0px">
                                <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="  From" value="{{ $date_from }}" />
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="  To" value="{{ $date_to }}" />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Process</div>
                            <div class="col-sm-8">
                                {!! Form::select('production_process_id', $process_name,'',array('id'=>'production_process_id','style'=>'border:2px;width:100%')) !!}
                            </div>
                        </div>     
                    </code>
                </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdAopFinDailyLoadReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdAopFinDailyLoadReport.resetForm('prodaopfindailyloadreportFrm')" >Reset</a>
            </div>
        </form>
    </div>
</div>
    
<script type="text/javascript" src="<?php echo url('/');?>/js/report/FabricProduction/MsProdAopFinDailyLoadReportController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });

    $('#prodaopfindailyloadreportFrm [id="production_process_id"]').combobox();
</script>