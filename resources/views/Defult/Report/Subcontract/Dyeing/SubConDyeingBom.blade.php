
<div class="easyui-layout"  data-options="fit:true" id="subcondyeingbompanel">
    <div data-options="region:'center',border:true,title:'Dyeing Party Wise Fabric Stock Report'" style="padding:2px">
                <div class="easyui-tabs" style="width:100%;height:100%; border:none" id="subcondyeingbomtabs">
                <div title="Details" style="padding:2px">
                <table id="subcondyeingbomTbl" style="width:1890px">
                    <thead>
                        <tr>
                            
                            <th data-options="field:'company_name'" width="60">Company</th>
                            <th data-options="field:'teamleader_name'" width="120">Marketeer</th>
                            <th data-options="field:'buyer_name'" width="180" formatter="MsSubconDyeingBom.formatContact">Customer</th>
                            <th data-options="field:'marketing_ref'" width="100">Mkt. Ref</th>
                            <th data-options="field:'sales_order_no'" width="180">Sale Order No</th>
                            
                            <th data-options="field:'order_qty'" width="80" align="right" formatter="MsSubconDyeingBom.formatorderqty">Qty</th>
                            <th data-options="field:'order_rate'" width="70" align="right">Avg.Rate</th>
                            <th data-options="field:'order_val'" width="80" align="right" formatter="MsSubconDyeingBom.formatorderqty">Order Value</th>
                            <th data-options="field:'delivery_date'" width="80" align="right">Dlv Date</th>
                            <th data-options="field:'rcv_qty'" width="80" align="right" >Rcv. Qty</th>

                            <th data-options="field:'fin_qty'" width="80" align="right" formatter="MsSubconDyeingBom.formatdlvqty">Dlv. Qty</th>
                            <th data-options="field:'grey_used_qty'" width="80" align="right">Grey Used Qty</th>
                            <th data-options="field:'bal_qty'" width="80" align="right">Balance Qty</th>
                            <th data-options="field:'fin_amount'" width="80" align="right" formatter="MsSubconDyeingBom.formatdlvqty">Bill Amount</th>                            
                            <th data-options="field:'dye_cost'" width="80" align="right" formatter="MsSubconDyeingBom.formatdyeqty">Dyes Cost</th>                            
                            <th data-options="field:'chem_cost'" width="80" align="right" formatter="MsSubconDyeingBom.formatchemqty">Chemical Cost</th>                            
                            <th data-options="field:'dye_chem_cost'" width="80" align="right">Dyes + Chem Cost</th> 
                            <th data-options="field:'overhead_cost'" width="80" align="right" formatter="MsSubconDyeingBom.formatohqty">Overhead Cost</th>                            
                            <th data-options="field:'total_cost'" width="80" align="right">Total Cost</th>                            
                            <th data-options="field:'profit_loss'" width="80" align="right">Profit/Loss</th>                            

                            <th data-options="field:'profit_loss_per'" width="80" align="right">Profit/Loss %</th>

                            <th data-options="field:'id'" width="100">Order ID</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div title="Summary" style="padding:2px">
                <table id="subcondyeingbomsummaryTbl" style="width:1890px">
                    <thead>
                        <tr>
                            
                            
                            
                            <th data-options="field:'buyer_name'" width="180" formatter="MsSubconDyeingBom.formatContact">Customer</th>
                            
                            <th data-options="field:'order_qty'" width="80" align="right">Qty</th>
                            <th data-options="field:'order_rate'" width="70" align="right">Avg.Rate</th>
                            <th data-options="field:'order_val'" width="80" align="right">Order Value</th>
                            <th data-options="field:'fin_qty'" width="80" align="right">Dlv. Qty</th>
                            <th data-options="field:'grey_used_qty'" width="80" align="right">Grey Used Qty</th>
                            <th data-options="field:'bal_qty'" width="80" align="right">Balance Qty</th>
                            <th data-options="field:'fin_amount'" width="80" align="right">Bill Amount</th>                            
                            <th data-options="field:'dye_cost'" width="80" align="right">Dyes Cost</th>                            
                            <th data-options="field:'chem_cost'" width="80" align="right">Chemical Cost</th>                            
                            <th data-options="field:'dye_chem_cost'" width="80" align="right">Dyes + Chem Cost</th> 
                            <th data-options="field:'overhead_cost'" width="80" align="right">Overhead Cost</th>                            
                            <th data-options="field:'total_cost'" width="80" align="right">Total Cost</th>                            
                            <th data-options="field:'profit_loss'" width="80" align="right">Profit/Loss</th>                            

                            <th data-options="field:'profit_loss_per'" width="80" align="right">Profit/Loss %</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div title="Chart" style="padding:2px">
                <div id="subcondyeingbomchartcontainer" style="height: 600px">
                <canvas id="subcondyeingbomchartcontainercanvas"></canvas>
                </div>
            </div>
        </div>
            
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#subcondyeingbomFrmFt'" style="width:350px; padding:2px">
        <form id="subcondyeingbomFrm">
            <div id="container">
                <div id="body">
                <code>
                    <div class="row middle">
                    <div class="col-sm-4 req-text">Dlv. Date</div>
                    <div class="col-sm-4" style="padding-right:0px">
                    <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="  From" value="{{$from}}" />
                    </div>
                    <div class="col-sm-4" style="padding-left:0px">
                    <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="  To" value="{{$to}}"/>
                    </div>
                    </div>
                    <div class="row middle">
                    <div class="col-sm-4">Company</div>
                    <div class="col-sm-8">
                    {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                    </div>
                    </div>
                    <div class="row middle">
                    <div class="col-sm-4">Customer</div>
                    <div class="col-sm-8">
                    {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                    </div>
                    </div>

                    <div class="row middle">
                    <div class="col-sm-4">Currency</div>
                    <div class="col-sm-8">
                    <input type="text" name="currency_id" id="currency_id" value="BDT" disabled/>
                    </div>
                    </div>
                    <div class="row middle">
                    <div class="col-sm-4">Exch. Rate</div>
                    <div class="col-sm-8">
                    <input type="text" name="exch_rate" id="exch_rate" value="83" class="number integer" />
                    </div>
                    </div>

                </code>
            </div>
            </div>
            <div id="subcondyeingbomFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSubconDyeingBom.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSubconDyeingBom.resetForm('subcondyeingbomFrm')" >Reset</a>
            </div>
      </form>
    </div>
</div>

<div id="subcondyeingbomWindow" class="easyui-window" title="Buyer Contact" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center'">
        <table id="subcondyeingbomcontactTbl">
            <thead>
                <tr>
                    <th data-options="field:'buyer_name',halign:'center'" width="150" >Buyer</th>
                    <th data-options="field:'contact_person',halign:'center'" width="150" >Contact Person</th>
                    <th data-options="field:'designation',halign:'center'" width="120" >Designation</th>
                    <th data-options="field:'email',halign:'center'" width="150" >Email</th>
                    <th data-options="field:'cell_no',halign:'center'" width="150" >Cell No</th>
                    <th data-options="field:'address',halign:'center'" width="200" >Address</th>
                </tr>
            </thead>
        </table>   
    </div>
</div>
</div>

<div id="subcondyeingbomorderqtyWindow" class="easyui-window" title="Orders" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center'">
        <table id="subcondyeingbomorderqtyTbl">
            <thead>
                <tr>
                    <th data-options="field:'fabrication',halign:'center'" width="450" >Fabric Description</th>
                    <th data-options="field:'buyer_name',halign:'center'" width="120" align="left" >Buyer</th>

                    <th data-options="field:'qty',halign:'right'" width="120" align="right" >Qty</th>
                    <th data-options="field:'rate',halign:'right'" width="150" align="right">Rate</th>
                    <th data-options="field:'order_val',halign:'right'" width="150" align="right">Amount</th>
                    <th data-options="field:'delivery_date',halign:'center'" width="80" align="center" >Dlv. Date</th>
                </tr>
            </thead>
        </table>   
    </div>
</div>
</div>

<div id="subcondyeingbomdlvqtyWindow" class="easyui-window" title="Delivery" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center'">
        <table id="subcondyeingbomdlvqtyTbl">
            <thead>
                <tr>
                    <th data-options="field:'issue_no',halign:'right'" width="120" align="right" >Bill No</th>
                    <th data-options="field:'fabrication',halign:'center'" width="450" >Fabric Description</th>
                    <th data-options="field:'qty',halign:'right'" width="120" align="right" >Dlv. Qty</th>
                    <th data-options="field:'rate',halign:'right'" width="150" align="right">Rate</th>
                    <th data-options="field:'amount',halign:'right'" width="150" align="right" >Amount</th>
                    <th data-options="field:'delivery_date',halign:'center'" width="80" align="center" >Actual Dlv. Date</th>
                </tr>
            </thead>
        </table>   
    </div>
</div>
</div>

<div id="subcondyeingbomdyeqtyWindow" class="easyui-window" title="Dyes" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center'">
        <table id="subcondyeingbomdyeqtyTbl">
            <thead>
                <tr>
                    <th data-options="field:'item_account_id',halign:'right'" width="120" align="right" >Item ID</th>
                    <th data-options="field:'category_name',halign:'center'" width="120" >Item Category</th>
                    <th data-options="field:'class_name',halign:'center'" width="120" >Item Class</th>
                    <th data-options="field:'sub_class_name',halign:'center'" width="120" >Item Sub Class</th>
                    <th data-options="field:'item_description',halign:'center'" width="120" >Item Description</th>
                    <th data-options="field:'specification',halign:'center'" width="120" >Specification</th>
                    <th data-options="field:'uom_name',halign:'center'" width="120" >UOM</th>
                    <th data-options="field:'qty',halign:'right'" width="120" align="right" >Qty</th>
                    <th data-options="field:'rate',halign:'right'" width="150" align="right">Rate</th>
                    <th data-options="field:'amount',halign:'right'" width="150" align="right" >Amount</th>
                </tr>
            </thead>
        </table>   
    </div>
</div>
</div>

<div id="subcondyeingbomchemqtyWindow" class="easyui-window" title="Chemical" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center'">
        <table id="subcondyeingbomchemqtyTbl">
            <thead>
                <tr>
                    <th data-options="field:'item_account_id',halign:'right'" width="120" align="right" >Item ID</th>
                    <th data-options="field:'category_name',halign:'center'" width="120" >Item Category</th>
                    <th data-options="field:'class_name',halign:'center'" width="120" >Item Class</th>
                    <th data-options="field:'sub_class_name',halign:'center'" width="120" >Item Sub Class</th>
                    <th data-options="field:'item_description',halign:'center'" width="120" >Item Description</th>
                    <th data-options="field:'specification',halign:'center'" width="120" >Specification</th>
                    <th data-options="field:'uom_name',halign:'center'" width="120" >UOM</th>
                    <th data-options="field:'qty',halign:'right'" width="120" align="right" >Qty</th>
                    <th data-options="field:'rate',halign:'right'" width="150" align="right">Rate</th>
                    <th data-options="field:'amount',halign:'right'" width="150" align="right" >Amount</th>
                </tr>
            </thead>
        </table>   
    </div>
</div>
</div>
<div id="subcondyeingbomohqtyWindow" class="easyui-window" title="Overhead" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center'">
        <table id="subcondyeingbomohqtyTbl">
            <thead>
                <tr>
                    
                    <th data-options="field:'acc_head',halign:'center'" width="120" >Item Category</th>
                    <th data-options="field:'cost_per',halign:'center'" width="120" align="right">Standard %</th>
                    <th data-options="field:'order_val',halign:'center'" width="120" align="right">Order Value</th>
                    <th data-options="field:'amount',halign:'right'" width="150" align="right" >Amount</th>
                </tr>
            </thead>
        </table>   
    </div>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Chart.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/report/Subcontract/Dyeing/MsSubconDyeingBomController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script>