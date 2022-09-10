<div class="easyui-layout animated rollIn"  data-options="fit:true" id="yystck">
    <div data-options="region:'center',border:true,title:'Dyes & Chemical Stock Report'" style="padding:2px">
        
        
                <table id="dyechemstockTbl" style="width:1890px">
                    <thead>
                        <tr>
                            <th data-options="field:'id',halign:'center'" width="80" >ID</th>
                            <th data-options="field:'itemcategory_name',halign:'center'" width="100" >Category</th>
                            <th data-options="field:'itemclass_name',halign:'center'" align="left" width="100">Item Class</th>
                            <th data-options="field:'item_desc',halign:'center'" width="250">Item Description</th>
                            <th data-options="field:'uom_code',halign:'center'" width="50">UOM</th>
                            <th data-options="field:'opening_qty',halign:'center'" width="80" align="right">Opening Qty</th>
                            <th data-options="field:'pur_qty',halign:'center'" width="80" align="right" >Purchase</th>
                            <th data-options="field:'trans_in_qty',halign:'center'" width="80" align="right" >Trans In Qty</th>
                            <th data-options="field:'isu_rtn_qty',halign:'center'" width="80" align="right" >Issue Rtn</th>
                            <th data-options="field:'receive_qty',halign:'center'" width="80" align="right" >Available</th>

                            <th data-options="field:'regular_issue_qty',halign:'center'" width="80" align="right">Consumed</th>
                            <th data-options="field:'trans_out_issue_qty',halign:'center'" width="80" align="right">Trans Out</th>
                            <th data-options="field:'rcv_rtn_issue_qty',halign:'center'" width="80" align="right">Purchase Rtn.</th>
                            <th data-options="field:'issue_qty',halign:'center'" width="80" align="right">Issue Qty</th>
                            <th data-options="field:'stock_qty',halign:'center'" width="80" align="right">Stock Qty</th>
                            <th data-options="field:'rate',halign:'center'" width="80" align="right">Rate -TK</th>
                            <th data-options="field:'stock_value',halign:'center'" width="120" align="right">Value - TK</th>
                            <th data-options="field:'last_receive',halign:'center'" width="80" align="center">Last Receive  <br/> Date</th>
                            <th data-options="field:'max_receive_qty',halign:'center'" width="80" align="right">Last Receive  <br/>Qty</th>
                            <th data-options="field:'last_issue',halign:'center'" width="80" align="center">Last Issue  <br/> Date </th>
                            <th data-options="field:'max_issue_qty',halign:'center'" width="80" align="right">Last Issue  <br/> Qty</th>
                            <th data-options="field:'diff_days',halign:'center'" width="80" align="center">Iddle Days</th>
                        </tr>
                    </thead>
                </table>
            
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="dyechemstockFrm">
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
                        <div class="col-sm-4">Store</div>
                        <div class="col-sm-8">
                        {!! Form::select('store_id', $store,'',array('id'=>'store_id')) !!}                        
                    </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Item Category</div>
                        <div class="col-sm-8">
                        {!! Form::select('item_category_id', $itemcategory,'',array('id'=>'item_category_id')) !!}                        
                    </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Consumption Level</div>
                        <div class="col-sm-8">
                        {!! Form::select('consumption_level_id', $consumptionlevel,'',array('id'=>'consumption_level_id')) !!}                        
                        </div>
                    </div>
                    <!--<div class="row middle">
                        <div class="col-sm-4">Count</div>
                        <div class="col-sm-8">
                            <input type="text" name="count" id="count" />
                        </div>
                    </div>             
                    <div class="row middle">
                        <div class="col-sm-4">Lot</div>
                        <div class="col-sm-8">
                            <input type="text" name="lot" id="lot" />
                        </div>
                    </div>  
                    <div class="row middle">
                        <div class="col-sm-4">Type</div>
                        <div class="col-sm-8">
                            <input type="text" name="type" id="type" />
                        </div>
                    </div>  --> 
                    
                              
                </code>
            </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsDyeChemStock.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsDyeChemStock.resetForm('dyechemstockFrm')" >Reset</a>
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
    
<script type="text/javascript" src="<?php echo url('/');?>/js/report/Inventory/MsDyeChemStockController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script>