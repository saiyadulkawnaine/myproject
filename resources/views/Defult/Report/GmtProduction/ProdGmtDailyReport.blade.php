<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="prodgmtdailyreportTbl" style="width:1890px">
            <thead>
                <tr>
                    <th width="70">1</th>
                    <th width="70">2</th>
                    <th width="70">3</th>
                    <th width="100">4</th>
                    <th width="100">5</th>
                    <th width="80">6</th>
                    <th width="100">7</th>
                    <th width="80">8</th>
                    <th width="30">9</th>
                    <th width="80">10</th>
                    <th width="70">11</th>
                    <th width="70">12</th>
                    <th width="70">13</th>
                    <th width="70">14</th>
                    <th width="70">15</th>
                    <th width="70">16</th>
                    <th width="80">17</th>
                    <th width="70">18</th>
                    <th width="70">19</th>
                    <th width="70">20</th>
                    <th width="70">21</th>
                    <th width="70">22</th>
                    <th width="70">23</th>
                </tr>
                <tr>
                    <th data-options="field:'company_code'" width="70">Beneficiary</th>
                    <th data-options="field:'pcompany_code'" width="70">Producing Company</th>
                    <th data-options="field:'supplier_id'" width="70">Service<br/>Company</th>
                    <th data-options="field:'dl_marchent'" width="100" formatter="MsProdGmtDailyReport.formatprodgmtdlmerchant">Dealing Merchant</th>
                    <th data-options="field:'buyer_name'" width="100">   Buyer</th>
                    <th data-options="field:'style_ref'" width="80" formatter="MsProdGmtDailyReport.formatprodgmtfile">  Style No</th>
                    <th data-options="field:'sale_order_no'" width="100">Order No</th>
                    <th data-options="field:'ship_date'" width="80" align="center">Ship Date</th>
                    <th data-options="field:'flie_src'" formatter="MsProdGmtDailyReport.formatimage" width="30">Image</th>
                    <th data-options="field:'production_date'" width="80">Production Date</th>
                    <th data-options="field:'cut_qty'" width="70" align="right">Cutting  Qty</th>
                    <th data-options="field:'print_qty'" width="70" align="right">Screen<br/>Printing Qty</th>
                    <th data-options="field:'emb_qty'" width="70" align="right"> EMB Qty</th>
                    <th data-options="field:'sew_qty'" width="70" align="right">Sewing Qty</th>
                    <th data-options="field:'iron_qty'" width="70" align="right">Iron Qty</th>
                    <th data-options="field:'poly_qty'" width="70" align="right">Poly Qty</th>
                    <th data-options="field:'finishing_qty'" width="80" align="right">Finish Qty</th>
                    <th data-options="field:'finishing_amount'" width="70" align="right">Finish Value</th>
                    <th data-options="field:'cm_rate'" width="70" align="right"> CM Cost</th>
                    <th data-options="field:'cm_amount'" width="70" align="right">CM <br/>Total Cost</th>
                    <th data-options="field:'mkt_cm_rate'" width="70" align="right">Mkt. CM Cost</th>
                    <th data-options="field:'mkt_cm_amount'" width="70" align="right">Mkt. CM <br/>Total Cost</th>
                    <th data-options="field:'prod_hour'" width="70" align="right">Prod Hour</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Daily Production Report',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="prodgmtdailyreportFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Prod. Area</div>
                            <div class="col-sm-8">
                                {!! Form::select('production_area_id',$productionarea,'',array('id'=>'production_area_id')) !!}
                            </div>
                        </div>
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
                        {{-- <div class="row">
                            <div class="col-sm-4">Sew. Company </div>
                            <div class="col-sm-8">{!! Form::select('produced_company_id', $company,'',array('id'=>'produced_company_id')) !!}</div>
                        </div> --}}
                        <div class="row middle">
                            <div class="col-sm-4">Beneficiary </div>
                            <div class="col-sm-8">{!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">Buyer </div>
                            <div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}</div>
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
                        <div class="row middle">
                            <div class="col-sm-4">Prod.Source</div>
                            <div class="col-sm-8">
                                {!! Form::select('prod_source_id', $productionsource,'',array('id'=>'prod_source_id')) !!}
                            </div>
                        </div>      
                    </code>
                </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtDailyReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdGmtDailyReport.resetForm('prodgmtdailyreportFrm')" >Reset</a>
            </div>
        </form>
    </div>
</div>
<div id="dailyreportImageWindow" class="easyui-window" title="Image" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:500px;height:500px;padding:2px;">
    <img id="dailyreportImageWindowoutput" src=""/>
</div>
{{-- POP UP WINDOW --}}
<div id="prodgmtdlmerchantWindow" class="easyui-window" title="Merchandiser Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="prodgmtdealmctinfoTbl" style="width:100%;height:250px">
        <thead>
            <tr>
                <th data-options="field:'name'" width="120">Name</th>
                <th data-options="field:'date_of_join'" width="80px">Date Of Join </th>
                <th data-options="field:'last_education'" width="100px">Last Education</th>
                <th data-options="field:'contact'" width="100px">Contact</th>
                <th data-options="field:'experience'" width="100px">Experience</th>
                <th data-options="field:'email'" width="100px">Email</th>
                <th data-options="field:'address'" width="350px">Address</th>
            </tr>
        </thead>
    </table>
</div>
<div id="prodgmtfilesrcwindow" class="easyui-window" title="Style Uploaded Files" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="prodgmtfilesrcTbl" style="width:500px">
        <thead>
            <tr>
                <th data-options="field:'id'" width="30">ID</th>
                <th data-options="field:'file_src'" width="180px">File Source </th>
                <th data-options="field:'original_name'" formatter="MsProdGmtDailyReport.formatProdGmtShowFile" width="250px">Original Name</th>
            </tr>
        </thead>
    </table>
</div>    
<script type="text/javascript" src="<?php echo url('/');?>/js/report/GmtProduction/MsProdGmtDailyReportController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script>