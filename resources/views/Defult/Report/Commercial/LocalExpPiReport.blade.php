<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="localexppireportTbl" style="width:100%">
            <thead>
                <tr>
                    <th  width="70" >1</th>
                    <th width="120">2</th>
                    <th width="80">3</th>
                    <th width="80">4</th>
                    <th width="80">5</th>
                    <th width="100">6</th>
                    <th width="70">7</th>
                    <th width="100">8</th>
                    <th width="100">9</th>
                    <th width="100">10</th>
                    <th width="80">11</th>
                    <th width="60">12</th>
                    <th width="150">13</th>
                    
                </tr>
                <tr>
                    <th data-options="field:'company_code',halign:'center'" align="center" width="70">Company</th>
                    <th data-options="field:'buyer_name',halign:'center'" width="120">Customer</th>
                    <th data-options="field:'pi_no',halign:'center'" width="80" formatter="MsLocalExpPiReport.formatpipdf" >PI No</th>
                    <th data-options="field:'pi_date',halign:'center'" align="center" width="80">PI Date</th>
                    <th data-options="field:'total_qty',halign:'center'" width="80" align="right">Total Qty</th>
                    <th data-options="field:'total_value',halign:'center'" width="100" formatter="MsLocalExpPiReport.formatitemdtl" align="right">Gross Value</th>
                    <th data-options="field:'total_discount'" width="70" align="right"> UP/Down<br/>Charge%</th> 
                    <th data-options="field:'net_value',halign:'center'" width="100"  align="right">Net Value</th>
                    <th data-options="field:'lc_no',halign:'center'" width="100">LC No</th>
                    <th data-options="field:'lc_date',halign:'center'" width="80">LC Date</th>
                    <th data-options="field:'lc_value',halign:'center'" width="80" align="right">LC Value</th>
                    <th data-options="field:'local_exp_pi_id',halign:'center'" width="60">PI ID</th>
                    <th data-options="field:'remarks'" width="150" align="left">Remarks</th>

                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Local Export PI Progress',footer:'#ft2'" style="width:300px; padding:2px">
        <form id="localexppireportFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row middle">
                            <div class="col-sm-5">Company</div>
                            <div class="col-sm-7">
                                {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5 req-text">Production Area</div>
                            <div class="col-sm-7">
                                {!! Form::select('production_area_id', $productionarea,'',array('id'=>'production_area_id')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5 req-text">Date Range</div>
                            <div class="col-sm-3" style="padding-right:0px">
                                <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="  From" />
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="  To" />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Customer</div>
                            <div class="col-sm-7">
                                {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                            </div>
                        </div>         
                    </code>
                </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsLocalExpPiReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsLocalExpPiReport.resetForm('localexppireportFrm')">Reset</a>
            </div>
        </form>
    </div>
</div>

    
<div id="exppiItemWindow" class="easyui-window" title="Export PI Item Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:90%;height:500px;padding:2px;margin:2px;"> 
    <table id="piItemReportTbl">
        <thead>
            <tr>
                <th data-options="field:'id'" width="40">ID</th>
                <th data-options="field:'style_ref'" width="70">Style Ref</th>
                <th data-options="field:'sale_order_no'" width="90">Sale Order No</th>
                <th data-options="field:'item_description'" width="300">Item</th>    
                <th data-options="field:'uom_code'" width="40"> UOM</th>
                <th data-options="field:'qty'" width="80" align="right">Qty</th>
                <th data-options="field:'order_rate'" width="60" align="right"> Rate</th> 
                <th data-options="field:'amount'" width="80" align="right"> Amount</th>
                <th data-options="field:'discount_per'" width="70" align="right"> UP/Down<br/>Charge%</th> 
                <th data-options="field:'net_amount'" width="100" align="right"> Net <br/>Amount</th>
            </tr>
        </thead>
    </table>  
</div>
    
<script type="text/javascript" src="<?php echo url('/');?>/js/report/Commercial/MsLocalExpPiReportController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });

    $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
        }
      });
    $('#localexppireportFrm [id="buyer_id"]').combobox();
</script>