<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Daily Dyeing Production'" style="padding:2px">
        <table id="proddyeingdailyreportTbl" style="width:100%">
            <thead>
                <tr>
                    <th data-options="field:'bcompany_code'" width="70">Bnf.<br/>Company</th>
                    <th data-options="field:'pcompany_code'" width="70">Sew.<br/>Company</th>
                    <th data-options="field:'batch_date'" width="80">Batch Date</th>
                    <th data-options="field:'gmt_buyer_name'" width="180">GMT.Buyer</th>
                    <th data-options="field:'gmt_style_ref'" width="150">GMT.<br/>Style</th>
                    <th data-options="field:'gmt_sale_order_no'" width="150">GMT.<br/>Order</th>
                    <th data-options="field:'customer_name'" width="180">Customer</th>
                    <th data-options="field:'gmtspart'" width="100">GMT <br/>Part</th>
                    <th data-options="field:'fabrication'" width="160">Fabrication</th>
                    <th data-options="field:'batch_color_name'" width="60">Fabric<br/>Color</th>
                    <th data-options="field:'fabricshape'" width="60">Shape</th>
                    <th data-options="field:'fabriclooks'" width="60">Looks</th>
                    <th data-options="field:'gsm_weight'" width="70">Req<br/> GSM</th>
                    <th data-options="field:'dia_width'" width="70">Req<br/> Dia</th>
                    <th data-options="field:'machine_no'" width="70" >M/C</th>
                    <th data-options="field:'prod_capacity'" width="70">Capacity</th>
                    <th data-options="field:'batch_no'" width="100">Batch No</th>
                    <th data-options="field:'batch_qty'" width="80" align="right">Batch Qty</th>
                    <th data-options="field:'load_date'" width="70">Load Date</th>
                    <th data-options="field:'load_time'" width="70">Load Time</th>
                    <th data-options="field:'unload_date'" width="80">Unload Date</th>
                    <th data-options="field:'unload_time'" width="70">Unload Time</th>
                    <th data-options="field:'unload_shift'" width="70">Unload Shift</th>
                    <th data-options="field:'time_taken'" width="70"  align="right">Time Taken</th>
                    <th data-options="field:'tgt_hour'" width="70"  align="right">Standard<br/>Time</th>
                    <th data-options="field:'deviation'" width="70" align="right">Deviation</th>
                    <th data-options="field:'prod_batch_id'" formatter="MsProdDyeingDailyReport.formatdyeingIsuRq" width="100">Requisition</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:320px; padding:2px">
        <form id="proddyeingdailyreportFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Prod.Date</div>
                            <div class="col-sm-4" style="padding-right:0px">
                                <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="  From" />
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="  To" />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Shift</div>
                            <div class="col-sm-8">
                                {!! Form::select('unload_shift', $shiftname,'',array('id'=>'unload_shift')) !!}
                            </div>
                        </div>
                        {{-- <div class="row middle">
                            <div class="col-sm-4">Time Range</div>
                            <div class="col-sm-4" style="padding-right:0px">
                                <input type="text" name="time_from" id="time_from" class="timepicker" placeholder="  From" />
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="time_to" id="time_to" class="timepicker"  placeholder="  To" />
                            </div>
                        </div> --}}
                    </code>
                </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdDyeingDailyReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdDyeingDailyReport.resetForm('proddyeingdailyreportFrm')" >Reset</a>
            </div>
        </form>
    </div>
</div>

<div id="dyeingisurqWindow" class="easyui-window" title="Inventory Dyeing Issue Requisition Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;"> 
    <table id="dyeingisurqTbl">
        <thead>
            <tr>
                <th data-options="field:'id'" formatter="MsProdDyeingDailyReport.formatPdf"  width="40">ID</th>
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
</div>
    
<script type="text/javascript" src="<?php echo url('/');?>/js/report/FabricProduction/MsProdDyeingDailyReportController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });

    $('.timepicker').timepicker(
        {
            'timeFormat': 'h:i:s A',
            'interval': 60,
            'minTime': '12:00pm',
            'maxTime': '11:59am',
            'showDuration': false,
            'step':1,
            'listWidth': 1,
            'width': '200px !important',
            'scrollDefault': 'now',
            'change': function(){
                alert('m')
            }
        }
    );
</script>