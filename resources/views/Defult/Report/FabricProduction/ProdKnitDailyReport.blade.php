<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Daily Knitting Production'" style="padding:2px">
        
        <table id="prodknitdailyreportTbl" style="width:1890px">
            <thead>
                <tr>
                    <th data-options="field:'bcompany_name'" width="70">Bnf. <br/>Company</th>
                    <th data-options="field:'pcompany_name'" width="70">Sew. <br/>Company</th>
                    <th data-options="field:'gmt_buyer_name'" width="100">GMT. Buyer</th>
                    <th data-options="field:'gmt_style_ref'" width="80">GMT. Style</th>
                    <th data-options="field:'gmt_sale_order_no'" width="80">GMT. Order</th>
                    <th data-options="field:'customer_name'" width="80">Customer</th>
                    <th data-options="field:'gmtspart'" width="100">GMT Part</th>
                    <th data-options="field:'fabrication'" width="80">Fabrication</th>
                    <th data-options="field:'fabric_color_name',halign:'center'" width="60">Fabric<br/>Color</th>
                    <th data-options="field:'fabricshape',halign:'center'" width="60">Shape</th>
                    <th data-options="field:'fabriclooks',halign:'center'" width="60">Looks</th>
                   
                    <th data-options="field:'gsm_weight',halign:'center'" width="70">Req GSM</th>
                    <th data-options="field:'dia'" width="70">Req Dia</th>
                    <th data-options="field:'knited_dia'" width="70">Actual Dia</th>
                    <th data-options="field:'prod_date'" width="80" >Knit Date</th>
                    <th data-options="field:'supplier_name'" width="70" >Knitted By</th>
                    <th data-options="field:'machine_no',halign:'center'" width="70" >M/C</th>
                    <th data-options="field:'prod_knit_qty',halign:'center'" width="70" align="right">Knit Qty</th>
                    <th data-options="field:'yarn_used_qty',halign:'center'" width="70" align="right">Yarn Used</th>
                    <th data-options="field:'prod_knit_qc_qty',halign:'center'" width="70" align="right" >QC Done</th>
                    <th data-options="field:'prod_knit_qc_wip',halign:'center'" width="70" align="right" >QC WIP</th>
                    <th data-options="field:'prod_knit_dlv_qty',halign:'center'" width="70" align="right">Dlv. Done</th>
                    <th data-options="field:'prod_knit_dlv_wip',halign:'center'" width="70" align="right" >Dlv. WIP</th>
                    <th data-options="field:'rate',halign:'center'" width="70" align="right">Charge<br/>Per Unit</th>
                    <th data-options="field:'knit_charge'" width="70" align="right">Knit Charge</th>
                    <th data-options="field:'knit_sales_order_no'" width="100" >Knit Sales Order</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="prodknitdailyreportFrm">
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
                        <div class="col-sm-4">Knit.Company</div>
                        <div class="col-sm-8">
                            {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'border:2px;width:100%')) !!}
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Prod. Basis</div>
                        <div class="col-sm-8">
                        {!! Form::select('basis_id',$productionsource,'',array('id'=>'basis_id')) !!}
                        </div>
                    </div>
                    <div class="row middle">
                      <div class="col-sm-4">Location</div>
                      <div class="col-sm-8">
                        {!! Form::select('location_id',$location,'',array('id'=>'location_id')) !!}
                      </div>
                    </div>      
                </code>
            </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdKnitDailyReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdKnitDailyReport.resetForm('prodknitdailyreportFrm')" >Reset</a>
            </div>
        </form>
    </div>
</div>

    
<script type="text/javascript" src="<?php echo url('/');?>/js/report/FabricProduction/MsProdKnitDailyReportController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });

    $('#prodknitdailyreportFrm [id="supplier_id"]').combobox();
</script>