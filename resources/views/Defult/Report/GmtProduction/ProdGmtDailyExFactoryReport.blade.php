<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Order Wise Detail'" style="padding:2px;height:100%">
        <table id="prodgmtexfactorydailyreportTbl" style="width:100%">
            <thead>
                <tr>
                    <th width="100">1</th>
                    <th width="70">2</th>
                    <th width="100">3</th>
                    <th width="100">4</th>
                    <th width="100">5</th>
                    <th width="80">6</th>
                    <th width="100">7</th>
                    <th width="80">8</th>
                    <th width="30">9</th>
                    <th width="80">10</th>
                    <th width="60">11</th>
                    <th width="80">12</th>
                    <th width="100">13</th>
                    <th width="100">14</th>
                </tr>
                <tr>
                    <th data-options="field:'challan_no'" align="center" width="100" formatter="MsProdGmtDailyExFactoryReport.formatPdf">Challan</th>
                    <th data-options="field:'company_code'" align="center" width="70">Beneficiary</th>
                    <th data-options="field:'pcompany_code',halign:'center'" width="100">Prod.Company</th>
                    <th data-options="field:'dl_marchent'" width="150">Dealing Merchant</th>
                    <th data-options="field:'buyer_name',halign:'center'" width="200">Buyer</th>
                    <th data-options="field:'style_ref',halign:'center'" width="150">Style No</th>
                    <th data-options="field:'sale_order_no'" align="center" width="100">Order No</th>
                    <th data-options="field:'ship_date'" align="center" width="80">Ship Date</th>
                    <th data-options="field:'flie_src'" formatter="MsProdGmtDailyExFactoryReport.formatimage" width="40">Image</th>
                    <th data-options="field:'exfactory_date'" width="80">Exfactory<br/> Date</th>
                    <th data-options="field:'delayDays',styler:MsProdGmtDailyExFactoryReport.formatDelays"  width="60">Delay<br/>Days</th>
                    <th data-options="field:'exfactory_qty',halign:'center'" width="80" align="right">Ex-Factory<br/> Qty</th>
                    <th data-options="field:'exfactory_amount',halign:'center'" align="right" width="100" >Ex-Factory<br/>Value</th>
                    <th data-options="field:'invoice_no'" width="100">Invoice No</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Daily Ex-Factory Report',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="prodgmtexfactorydailyreportFrm">
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
                        <div class="col-sm-4">Prod.Company</div>
                        <div class="col-sm-8">
                            {!! Form::select('produced_company_id', $company,'',array('id'=>'produced_company_id')) !!}
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Beneficiary </div>
                        <div class="col-sm-8">{!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}</div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Buyer </div>
                        <div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}</div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Style Ref</div>
                        <div class="col-sm-8">
                            <input type="text" name="style_ref" id="style_ref" />
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Order No</div>
                        <div class="col-sm-8">
                            <input type="text" name="sale_order_no" id="sale_order_no" />
                        </div>
                    </div>             
                </code>
            </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtDailyExFactoryReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdGmtDailyExFactoryReport.resetForm('prodgmtdailyexfactoryreportFrm')" >Reset</a>
            </div>
        </form>
    </div>
</div>
<div id="dailyreportImageWindow" class="easyui-window" title="Image" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:500px;height:500px;padding:2px;">
    <img id="dailyreportImageWindowoutput" src=""/>
</div>
    
<script type="text/javascript" src="<?php echo url('/');?>/js/report/GmtProduction/MsProdGmtDailyExFactoryReportController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });

    $('#prodgmtexfactorydailyreportFrm [id="buyer_id"]').combobox();
</script>