<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'north',border:true,title:'Garment Stock Report'" style="padding:2px;height:100%">
                <table id="garmentstockreportTbl" style="width:1890px">
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
                            <th width="40">11</th>
                            <th width="80">12</th>
                        </tr>
                        <tr>
                            <th data-options="field:'style_gmt_name',halign:'center'" width="150">Garment Name</th>
                            <th data-options="field:'style_ref',halign:'center'" align="center" width="70">Style</th>
                            <th data-options="field:'color_name',halign:'center'" align="center" width="100">Color</th>
                            <th data-options="field:'size_name',halign:'center'" width="100">Size</th>
                            <th data-options="field:'opening_stock',halign:'center'" width="80" align="right">Opening Stock</th>
                            <th data-options="field:'receive_qty',halign:'center'" width="80" align="right" formatter="MsGarmentStockReport.formatReceiveQty">Receive Qty</th>
                            <th data-options="field:'total_qty',halign:'center'" width="80" align="right">Total Qty</th>
                            <th data-options="field:'sale_qty',halign:'center'" width="80" align="right">Sold Qty</th>
                            <th data-options="field:'closing_stock',halign:'center'" width="80" align="right">Closing Stock</th>
                            <th data-options="field:'rate',halign:'center'" width="80" align="right">Cost/Unit</th>
                            <th data-options="field:'stock_value',halign:'center'" width="80" align="right">Stock Value</th>
                            <th data-options="field:'sale_amount',halign:'center'" width="80" align="right" formatter="MsGarmentStockReport.formatSalesQty">Sold Value</th>
                        </tr>
                    </thead>
                </table>
            </div>    
        </div>
    </div>
    <div data-options="region:'west',border:true,title:'Garment Stock Report',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="garmentstockreportFrm">
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
                        <div class="col-sm-4">Gmt Item</div>
                        <div class="col-sm-8">
                            <input type="text" name="style_gmt_item" id="style_gmt_item" />
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Color</div>
                        <div class="col-sm-8">
                            <input type="text" name="color_name" id="color_name" />
                        </div>
                    </div>             
                    <div class="row middle">
                        <div class="col-sm-4">Size</div>
                        <div class="col-sm-8">
                            <input type="text" name="size_name" id="size_name" />
                        </div>
                    </div>  
                    <div class="row middle">
                        <div class="col-sm-4">Company </div>
                        <div class="col-sm-8">
                            {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Buyer </div>
                        <div class="col-sm-8">
                            {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                        </div>
                    </div>           
                </code>
            </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsGarmentStockReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsGarmentStockReport.resetForm('garmentstockreportFrm')" >Reset</a>
            </div>
      </form>
    </div>
</div>
{{-- pop ups --}}
<div id="receiveqtyStockWindow" class="easyui-window" title="Receive Qty Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div data-options="region:'center'" style="width:100%;height:460px;">
        <table id="receiveqtyTbl">
            <thead>
                <tr>
                    <th data-options="field:'srm_product_receive_dtl_id',halign:'center'" width="150" >Barcode</th>
                    <th data-options="field:'receive_date',halign:'center'" width="150" >Receive Date</th>
                    <th data-options="field:'style_gmt_name',halign:'center'" width="150" >Garment Name</th>
                    <th data-options="field:'style_ref',halign:'center'" align="center" width="70">Style</th>
                    <th data-options="field:'color_name',halign:'center'" align="center" width="100">Color</th>
                    <th data-options="field:'size_name',halign:'center'" width="100">Size</th>
                    <th data-options="field:'receive_qty',halign:'center'" width="80" align="right">Receive Qty</th>
                    <th data-options="field:'receive_rate',halign:'center'" width="80" align="right">Cost/Unit</th>
                    <th data-options="field:'receive_amount',halign:'center'" width="80" align="right">Receive Value</th>
                    <th data-options="field:'invoice_no',halign:'center'" width="80" align="right">Invoice No</th>
                </tr>
            </thead>
        </table>   
    </div>
    <div data-options="region:'south',border:false" style="text-align:right;padding:1px 0 0;">
        <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#receiveqtyStockWindow').window('close')" style="width:80px">Close</a>
    </div>
</div>

<div id="salesQtyWindow" class="easyui-window" title="Sold Qty Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div data-options="region:'center'" style="width:100%;height:460px;">
        <table id="salesqtyTbl">
            <thead>
                <tr>
                    {{-- <th data-options="field:'srm_product_receive_dtl_id',halign:'center'" width="150" >Barcode</th> --}}
                    <th data-options="field:'scan_date',halign:'center'" width="150" >Sales Date</th>
                    <th data-options="field:'style_gmt_name',halign:'center'" width="150" >Garment Name</th>
                    <th data-options="field:'style_ref',halign:'center'" align="center" width="70">Style</th>
                    <th data-options="field:'color_name',halign:'center'" align="center" width="100">Color</th>
                    <th data-options="field:'size_name',halign:'center'" width="100">Size</th>
                    <th data-options="field:'sale_qty',halign:'center'" width="80" align="right">Sales Qty</th>
                    <th data-options="field:'sale_rate',halign:'center'" width="80" align="right">Sales <br/>Price/Unit</th>
                    <th data-options="field:'sale_amount',halign:'center'" width="80" align="right">Sold Value</th>
                </tr>
            </thead>
        </table>   
    </div>
    <div data-options="region:'south',border:false" style="text-align:right;padding:1px 0 0;">
        <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#salesQtyWindow').window('close')" style="width:80px">Close</a>
    </div>
</div>
    
<script type="text/javascript" src="<?php echo url('/');?>/js/report/POS/MsGarmentStockReportController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script>