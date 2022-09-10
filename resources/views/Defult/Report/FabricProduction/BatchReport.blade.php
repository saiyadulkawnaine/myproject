<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Daily Batch Load Target'" style="padding:2px">
        
        <table id="batchreportTbl" style="width:100%">
            <thead>
                <tr>

                    <th data-options="field:'dd'" width="80" formatter="MsBatchReport.batchCardButton"></th>
                   
                    <th data-options="field:'batch_no'" formatter="MsBatchReport.formatbatchno" width="80">Batch No</th>
                    <th data-options="field:'batch_date'"  width="100">Batch Date</th>
                    <th data-options="field:'target_load_date'" width="100">Tgt. Date</th>
                    <th data-options="field:'batch_for'" width="100">Batch For</th>
                    <th data-options="field:'redyeing'" width="100">Re-Dyeing</th>
                    <th data-options="field:'color_name'" width="80">Batch Color</th>
                    <th data-options="field:'batch_wgt'" width="80" align="right">Batch Wgt</th>
                    <th data-options="field:'prod_capacity'" width="80" align="right">Capacity</th>
                    <th data-options="field:'prod_capacity_var'" width="80" align="right">Cap. Varience </th>
                    <th data-options="field:'machine_no'" width="80">M/C No</th>
                    <th data-options="field:'machine_brand'" width="80">M/C Brand</th>
                    <th data-options="field:'lap_dip_no'" width="80">Lab Dip No</th>
                    <th data-options="field:'color_range_name'" width="80">Color Range</th>
                    <th data-options="field:'remarks'" width="80">Remarks</th>
                    <th data-options="field:'approval_status',halign:'center'" width="80" align="center">Approval Status</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="batchreportFrm">
            <div id="container">
                <div id="body">
                   <code>
                        <div class="row middle">
                            <div class="col-sm-4">Batch Date </div>
                            <div class="col-sm-4" style="padding-right:0px">
                                <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                            </div>
                        </div>

                        <div class="row middle">
                            <div class="col-sm-4">Tgt. Load Date </div>
                            <div class="col-sm-4" style="padding-right:0px">
                                <input type="text" name="target_date_from" id="target_date_from" class="datepicker" placeholder="From" />
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="target_date_to" id="target_date_to" class="datepicker"  placeholder="To" />
                            </div>
                        </div>
                       
                  </code>
               </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" plain="true" id="save" onClick="MsBatchReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsBatchReport.resetForm('batchreportFrm')" >Reset</a> 
            </div>
      </form>
    </div>
</div>
{{-- Style Filtering Search Window --}}
<div id="batchreportrollWindow" class="easyui-window" title="Roll Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center'" style="padding:10px;">
            <table id="batchreportrollTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="70">ID</th>
                          <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
                          <th data-options="field:'custom_no'" width="70">Custom No</th>
                          <th data-options="field:'sale_order_no'" width="100">Order No</th>
                          <th data-options="field:'style_ref'" width="100">Style Ref</th>
                          <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
                          
                          <th data-options="field:'batch_qty'" width="80" align="right">Batch Qty</th>
                          
                          <th data-options="field:'colorrange_name'" width="80">Color Range</th>
                          
                          <th data-options="field:'dyeing_color'" width="80">Dyeing Color</th>
                          <th data-options="field:'body_part'" width="100">Body Part</th>
                          <th data-options="field:'fabrication'" width="200">Fabric <br/>Description</th>
                          <th data-options="field:'fabric_shape'" width="70">Fabric <br/>Shape</th>
                          <th data-options="field:'fabric_look'" width="70">Fabric <br/>Look</th>
                          <th data-options="field:'gsm_weight'" width="40">GSM</th>
                          <th data-options="field:'dia_width'" width="40">Dia/<br/>Widht</th>
                          <th data-options="field:'measurement'" width="70">Measurement</th>
                          <th data-options="field:'roll_length'" width="70">Roll Length</th>
                          <th data-options="field:'stitch_length'" width="70">Stitch<br/>Length</th>
                          <th data-options="field:'shrink_per'" width="60">Shrink %</th>
                          
                          <th data-options="field:'dyetype'" width="80">Dyeing Type</th>
                          
                          <th data-options="field:'prod_no'" width="70">Prod. Ref</th>
                          
                          <th data-options="field:'gmt_sample'" width="100">Gmt Sample</th>
                          <th data-options="field:'supplier_name'" width="100">Produced By</th>
                          <th data-options="field:'customer_name'" width="100">Produced For</th>
                          <th data-options="field:'yarndtl'" width="300">Yarn</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#ordstyleWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/report/FabricProduction/MsBatchReportController.js"></script>
<script>
    (function(){
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
        });
        $('.integer').keyup(function () {
            if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
            }
        });
        $('#ordstylesearchFrm [id="buyer_id"]').combobox();
    })(jQuery);
</script>
