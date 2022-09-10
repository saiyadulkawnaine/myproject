<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Batch Costing'" style="padding:2px">
        
        <table id="prodfinishqcbatchcostingTbl" style="width:1890px">
            <thead>
                <tr>
                    <th data-options="field:'customer_name'" width="70">Customer</th>
                    <th data-options="field:'buyer_name'" width="70">Buyer</th>
                    <th data-options="field:'style_ref'" width="100">Style Ref</th>
                    <th data-options="field:'batch_no'" formatter="MsProdFinishQcBatchCosting.formatpdf" width="80">Batch No</th>
                    <th data-options="field:'lap_dip_no'" width="80">Lab Dip No</th>
                    <th data-options="field:'fabric_color_name'" width="60">Color</th>
                    <th data-options="field:'colorrange'" width="80">Color Range</th>
                    <th data-options="field:'ratio'" width="80" align="right">Color %</th>
                    <th data-options="field:'custom_no'" width="60">Dyeing M/C</th>
                    <th data-options="field:'revenue',halign:'center'" width="60" align="right" formatter="MsProdFinishQcBatchCosting.formatsodyeingdtl">Revenue</th>
                   
                    <th data-options="field:'dyes_cost_amount',halign:'center'" width="70" align="right" formatter="MsProdFinishQcBatchCosting.formatcostsheetpdf">Dyes Cost</th>
                    <th data-options="field:'chem_cost_amount'" width="70" align="right" formatter="MsProdFinishQcBatchCosting.formatcostsheetpdf">Chem Cost</th>
                    <th data-options="field:'revenue_per'" width="70" align="right">%</th>
                    <th data-options="field:'overhead'" width="80" align="right">Overhead</th>
                    <th data-options="field:'profit'" width="70" align="right">Profit</th>
                    <th data-options="field:'profit_per'" width="70" align="right">%</th>
                    <th data-options="field:'prod_capacity'" width="70" align="right">Capacity</th>
                    <th data-options="field:'batch_qty'" width="70" align="right">Batch Wgt</th>
                    <th data-options="field:'utilize_per'" width="70" align="right">Utilize %</th>
                    <th data-options="field:'loaded_at',halign:'center'" width="130">Load</th>
                    <th data-options="field:'unloaded_at',halign:'center'" width="130">Unload</th>
                    <th data-options="field:'hour_used',halign:'center'" width="70" align="right">Hour Used</th>
                    <th data-options="field:'tgt_hour',halign:'center'" width="70" align="right">Standard<br/>Hour</th>
                    <th data-options="field:'additional_hour',halign:'center',styler:MsProdFinishQcBatchCosting.formatAdditionalHr" width="70" align="right">Additional<br/>Hour</th>
                    <th data-options="field:'qc_pass_qty',halign:'center'" width="70" align="right">Fin Qty</th>
                    <th data-options="field:'process_loss',halign:'center'" width="70"  align="right">Process Loss</th>
                    <th data-options="field:'process_loss_per',halign:'center'" width="70"  align="right">%</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:320px; padding:2px">
        <form id="prodfinishqcbatchcostingFrm">
            <div id="container">
                <div id="body">
                <code>
                    <div class="row middle">
                        <div class="col-sm-4 req-text">Date Range</div>
                        <div class="col-sm-4" style="padding-right:0px">
                            <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="  From" />
                        </div>
                        <div class="col-sm-4" style="padding-left:0px">
                            <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="  To" />
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Company</div>
                        <div class="col-sm-8">
                            {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Batch For</div>
                        <div class="col-sm-8">
                        {!! Form::select('basis_id',$batchfor,'',array('id'=>'basis_id')) !!}
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Batch No</div>
                        <div class="col-sm-8">
                            <input type="text" name="batch_no" id="batch_no" ondblclick="MsProdFinishQcBatchCosting.openqcbatchcostingbatchWindow()" placeholder="Double Click" readonly/>
                             <input type="hidden" name="prod_batch_id" id="prod_batch_id"  class="number integer" />
                        </div>
                    </div>      
                </code>
            </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdFinishQcBatchCosting.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdFinishQcBatchCosting.resetForm('prodfinishqcbatchcostingFrm')" >Reset</a>
            </div>
        </form>
    </div>
</div>

<div id="qcbatchcostingbatchsearchwindow" class="easyui-window" title="Batch" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west', split:true, title:'Search',footer:'#qcbatchcostingbatchsearchFrmft'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="prodbatchsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Batch Date</div>
                                <div class="col-sm-4">
                                    <input type="text" name="batch_date_from" id="batch_date_from" class="datepicker" style="margin-right: 0px" />
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" name="batch_date_to" id="batch_date_to" class="datepicker" style="margin-left: 0px" />
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <div id="qcbatchcostingbatchsearchFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdFinishQcBatchCosting.getBatch()">Search</a>
                </div>
            </div>
        </div>
        <div data-options="region:'center',footer:'#qcbatchcostingbatchsearchTblFt'" style="padding:10px;">
            <table id="prodbatchsearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'company_code'" width="80">Company</th>
                        <th data-options="field:'batch_no'" width="80">Batch No</th>
                        <th data-options="field:'batch_date'" width="100">Batch Date</th>
                        <th data-options="field:'batch_for'" width="100">Batch For</th>
                        <th data-options="field:'machine_no'" width="80">Machine No</th>
                        <th data-options="field:'color_name'" width="80">Roll Color</th>
                        <th data-options="field:'batch_color_name'" width="80">Batch Color</th>
                        <th data-options="field:'color_range_name'" width="80">Color Range</th>
                        <th data-options="field:'fabric_wgt'" width="80">Fabric Wgt</th>
                        <th data-options="field:'batch_wgt'" width="80">Batch Wgt</th>
                        <th data-options="field:'remarks'" width="80">Remarks</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div id="sodyeingdtlWindow" class="easyui-window" title="Dyeing Sales Order Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;"> 
    <table id="sodyeingdtlTbl">
        <thead>
            <tr>
                <th data-options="field:'so_dyeing_id'"  width="40">DSO ID</th>
                <th data-options="field:'dye_sales_order_no'" width="100">DSO No</th>
                <th data-options="field:'buyer_name'" width="80">Buyer</th>
                <th data-options="field:'style_ref'" width="100">Style Ref</th>
                <th data-options="field:'sale_order_no'" width="100">GMT <br>Sales Order No</th>
                <th data-options="field:'gmtspart'" width="70">GMT Part</th>
                <th data-options="field:'construction_name'" width="100">Constructions</th>
                <th data-options="field:'fabrication'" width="100">Fabrication</th>
                <th data-options="field:'fabriclooks'" width="100">Fabric Looks</th>
                <th data-options="field:'fabricshape'" width="100">Fabric Shape</th>
                <th data-options="field:'gsm_weight'" width="70" align="right">GSM/Weight</th>
                <th data-options="field:'dia'" width="70" align="right">Dia</th>
                <th data-options="field:'measurment'" width="70" align="right">Measurment</th>
                <th data-options="field:'fabric_color'" width="80" align="right">Fabric Color</th>
                <th data-options="field:'colorrange_id'" width="70">Color Range</th>
                <th data-options="field:'dyeingtype'" width="80">Dye Type</th>
                <th data-options="field:'uom_name'" width="80">Uom</th>
                <th data-options="field:'qty'" width="70" align="right">Qty</th>
                <th data-options="field:'pcs_qty'" width="70" align="right">EQV Qty</th>
                <th data-options="field:'rate'" width="70" align="right">Rate</th>
                <th data-options="field:'exch_rate'" width="70" align="right">Exch Rate</th>
                <th data-options="field:'amount'" width="100" align="right">Amount</th>
                <th data-options="field:'delivery_date'" width="80">Delivery Date</th>
            </tr>
        </thead>
    </table>  
</div>
    
<script type="text/javascript" src="<?php echo url('/');?>/js/report/FabricProduction/MsProdFinishQcBatchCostingController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });

</script>