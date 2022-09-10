<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Daily Dyeing Production'" style="padding:2px">
        <table id="prodknittingdailyloadreportTbl" style="width:100%">
            <thead>
                <tr>
                    <th data-options="field:'machine_no'" width="80" >M/C No</th>
                    <th data-options="field:'brand'" width="100">Brand</th>
                    <th data-options="field:'origin'" width="100">Origin</th>
                    <th data-options="field:'asset_group'" width="100">Group</th>
                    <th data-options="field:'prod_capacity'" width="100" align="right">Capacity</th>
                    <th data-options="field:'prod_knit_qty'" width="100" align="right">Produced Qty</th>
                    <th data-options="field:'unused_prod_capacity'" width="100" align="right">Unused Capacity</th>
                    <th data-options="field:'knit_charge_usd'" width="100" align="right">Knit Charge<br> USD</th>
                    <th data-options="field:'knit_charge_bdt'" width="100" align="right">Knit Charge<br>BDT</th>
                    <th data-options="field:'prod_date'" width="80">Start Date</th>
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
        <form id="prodknittingdailyloadreportFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row middle">
                            <div class="col-sm-5 req-text">As On </div>
                            <div class="col-sm-7">
                                <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="  To" value="{{ $date_to }}" />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Knit.Company</div>
                            <div class="col-sm-7">
                                {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'border:2px;width:100%')) !!}
                            </div>
                        </div>    
                    </code>
                </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdKnittingDailyLoadReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdKnittingDailyLoadReport.resetForm('prodknittingdailyloadreportFrm')" >Reset</a>
            </div>
        </form>
    </div>
</div>
{{-- 
<div id="dyeingisurqWindow" class="easyui-window" title="Inventory Dyeing Issue Requisition Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;"> 
    <table id="dyeingisurqTbl">
        <thead>
            <tr>
                <th data-options="field:'id'" formatter="MsProdKnittingDailyLoadReport.formatPdf"  width="40">ID</th>
                <th data-options="field:'company_id'" width="100">Company</th>
                <th data-options="field:'location_id'" width="100">Location</th>
                <th data-options="field:'rq_no'" width="100">Requisition No</th>
                <th data-options="field:'rq_date'" width="80">Requisition Date</th>
                <th data-options="field:'fabric_color'" width="80">Roll Color</th>
                <th data-options="field:'batch_color'" width="80">Batch Color</th>
                <th data-options="field:'colorrange_name'" width="80">Color Range</th>
                <th data-options="field:'batch_no'" width="80">Batch No</th>
                <th data-options="field:'lap_dip_no'" width="80">Lab Dip No</th>
                <th data-options="field:'batch_wgt'" width="80">Batch Wgt</th>
                <th data-options="field:'liqure_ratio'" width="80">Liqure Ratio</th>
                <th data-options="field:'liqure_wgt'" width="80">Liqure Wgt.</th>
                <th data-options="field:'remarks'" width="80">Remarks</th>
            </tr>
        </thead>
    </table>  
</div> --}}

{{-- <div id="dyeingloadsummeryWindow" class="easyui-window" title="Dyeing Load Summery Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;"> 
    <table id="dyeingloadsummeryTbl">
        <thead>
            <tr>
                <th data-options="field:'machine_no'" width="80" >M/C No</th>
                <th data-options="field:'brand'" width="100">Brand</th>
                <th data-options="field:'batch_no'" width="100">Batch No</th>
                <th data-options="field:'batch_date'" width="80">Batch Date</th>
                <th data-options="field:'batch_color_name'" width="150">Batch Color</th>
                <th data-options="field:'prod_capacity'" width="100" align="right">Capacity</th>
                <th data-options="field:'batch_qty'" width="100" align="right">Batch Qty</th>
                <th data-options="field:'unused_prod_capacity'" width="100" align="right">Unused Capacity</th>
                <th data-options="field:'load_date'" width="80">Load Date</th>
                <th data-options="field:'load_time'" width="70">Load Time</th>
                <th data-options="field:'tgt_hour'" width="70"  align="right">Standard<br/>Hour</th>
                <th data-options="field:'running_hour',styler:MsProdKnittingDailyLoadReport.exceedhour" width="70"  align="right">Running<br/>Hour</th>
                <th data-options="field:'idle_date'" width="80">M/C Idle Date</th>
                <th data-options="field:'idle_time'" width="80">M/C Idle Time</th>
                <th data-options="field:'reason'" width="80">Reason</th>
                <th data-options="field:'remarks'" width="80">Remarks</th>
                <th data-options="field:'idle_hour'" width="80">Idle Hour</th>
            </tr>
        </thead>
    </table>  
</div> --}}
    
<script type="text/javascript" src="<?php echo url('/');?>/js/report/FabricProduction/MsProdKnittingDailyLoadReportController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });

    $('#prodknittingdailyloadreportFrm [id="supplier_id"]').combobox();
</script>