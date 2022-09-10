<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Finish Fabric Roll Dumping'" style="padding:2px">
        
        <table id="finishfabricrolldumpingTbl" style="width:100%">
            <thead>
                <tr>
                    <th data-options="field:'posting_date'" width="80">Qc Date</th>
                    <th data-options="field:'batch_for'" width="100">Order For</th>
                    <th data-options="field:'body_part'" width="100">Body Part</th>
                    <th data-options="field:'fabrication'" width="200">Fabric Description</th>
                    <th data-options="field:'fabric_shape'" width="70">Fabric Shape</th>
                    <th data-options="field:'fabric_look'" width="70">Fabric Look</th>
                    <th data-options="field:'measurement'" width="70">Measurement</th>
                    <th data-options="field:'roll_length'" width="70">Roll Length</th>
                    <th data-options="field:'stitch_length'" width="70">Stitch Length</th>
                    <th data-options="field:'shrink_per'" width="60">Shrink %</th>
                    <th data-options="field:'dyetype'" width="80">Dyeing Type</th>
                    <th data-options="field:'gsm_weight'" width="40">Req GSM</th>
                    <th data-options="field:'qc_gsm_weight'" width="40">Actual GSM</th>
                    <th data-options="field:'qc_dia_width'" width="40">Req Width</th>
                    <th data-options="field:'dia_width'" width="40">Actual Width</th>
                    <th data-options="field:'grade'" width="40">Grade</th>
                    <th data-options="field:'colorrange_name'" width="80">Color Range</th>
                    <th data-options="field:'batch_color_name'" width="80">Fabric Color</th>
                    <th data-options="field:'batch_qty'" width="80">Batch Qty</th>
                    <th data-options="field:'qc_pass_qty'" width="80" align="right">QC Pass Qty</th>
                    <th data-options="field:'reject_qty'" width="80" align="right">Reject Qty</th>
                    <th data-options="field:'batch_no'" width="80">Batch No</th>
                    <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
                    <th data-options="field:'custom_no'" width="70">Custom No</th>
                    <th data-options="field:'so_customer_name'" width="70">Customer</th>
                    <th data-options="field:'buyer_name'" width="100">Customer Buyer</th>
                    <th data-options="field:'sale_order_no'" width="100">Order No</th>
                    <th data-options="field:'style_ref'" width="100">Style Ref</th>
                    <th data-options="field:'customer_name'" width="100">Produced For</th>
                    <th data-options="field:'supplier_name'" width="100">Knitted By</th>
                    <th data-options="field:'prod_no'" width="70">Prod Knit.Ref</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="finishfabricrolldumpingFrm">
            <div id="container">
                <div id="body">
                   <code>
                        <div class="row middle">
                            <div class="col-sm-4">QC Date </div>
                            <div class="col-sm-4" style="padding-right:0px">
                                <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">Customer </div>
                            <div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}</div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Sew. Company</div>
                            <div class="col-sm-8">
                                {!! Form::select('produced_company_id', $company,'',array('id'=>'produced_company_id')) !!}
                            </div>
                        </div>
                  </code>
               </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" plain="true" id="save" onClick="MsFinishFabricRollDumping.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsFinishFabricRollDumping.resetForm('finishfabricrolldumpingFrm')" >Reset</a> 
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" plain="true" id="save" onClick="MsFinishFabricRollDumping.showExcel()">XL</a>
            </div>
      </form>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/report/FabricProduction/MsFinishFabricRollDumpingController.js"></script>
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
        $('#finishfabricrolldumpingFrm [id="buyer_id"]').combobox();
    })(jQuery);
</script>
