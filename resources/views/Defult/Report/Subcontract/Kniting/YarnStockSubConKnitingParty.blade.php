<div class="easyui-layout animated rollIn"  data-options="fit:true" id="yarnstocksubconknitingpartypanel">
    <div data-options="region:'center',border:true,title:'Kniting Party Wise Yarn Stock Report'" style="padding:2px">
        
        
                <table id="yarnstocksubconknitingpartyTbl" style="width:1890px">
                    <thead>
                        <tr>
                            <th data-options="field:'buyer_name',halign:'center'" width="150" >Kniting Party</th>
                            <th data-options="field:'opening_qty',halign:'center'" width="80" align="right">Opening Stock</th>
                            <th data-options="field:'rcv_qty',halign:'center'" width="80" align="right" formatter="MsYarnStockSubconKnitingParty.formatReceived">Grey Received</th>
                            <th data-options="field:'total_rcv_qty',halign:'center'" width="80" align="right">Total Received</th>
                            <th data-options="field:'dlv_fin_qty',halign:'center'" width="80" align="right">Fin. Delv.</th>
                            <th data-options="field:'dlv_grey_used_qty',halign:'center'" width="80" align="right" formatter="MsYarnStockSubconKnitingParty.formatUsed">Grey Used</th>
                            <th data-options="field:'rtn_qty',halign:'center'" width="80" align="right" formatter="MsYarnStockSubconKnitingParty.formatReturn">Grey Returned</th>
                            <th data-options="field:'total_adjusted',halign:'center'" width="80" align="right">Total Adusted</th>
                            <th data-options="field:'stock_qty',halign:'center'" width="80" align="right" formatter="MsYarnStockSubconKnitingParty.formatClosing">Closing Stock</th>
                            <th data-options="field:'rate',halign:'center'" width="80" align="right" align="right">Avg.Rate (BDT)</th>
                            <th data-options="field:'stock_value',halign:'center'" width="100" align="right" align="right">Value (BDT)</th>
                        </tr>
                    </thead>
                </table>
            
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#yarnstocksubconknitingpartyFrmFt'" style="width:350px; padding:2px">
        <form id="yarnstocksubconknitingpartyFrm">
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
                      
                    
                              
                </code>
            </div>
            </div>
            <div id="yarnstocksubconknitingpartyFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsYarnStockSubconKnitingParty.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsYarnStockSubconKnitingParty.resetForm('yarnstocksubconknitingpartyFrm')" >Reset</a>
            </div>
      </form>
    </div>
</div>



<div id="yarnstocksubconknitingpartyreceivedWindow" class="easyui-window" title="Received" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center'">
        <table id="yarnstocksubconknitingpartyreceivedTbl">
            <thead>
                <tr>
                    <th data-options="field:'yarn_count',halign:'center'" width="80" >Count</th>
                    <th data-options="field:'yarn_desc',halign:'center'" width="250" >Yarn Description</th>
                    <th data-options="field:'yarn_type',halign:'center'" width="80" >Type</th>
                    <th data-options="field:'lot',halign:'center'" width="100" >Lot</th>
                    <th data-options="field:'supplier_name',halign:'center'" width="100" >Supplier</th>
                    <th data-options="field:'yarn_color',halign:'center'" width="130" >Yarn Color</th>
                    <th data-options="field:'qty',halign:'center'" width="80" align="right" >Qty</th>
                    <th data-options="field:'rate',halign:'center'" width="70" align="right">Rate</th>
                    <th data-options="field:'amount',halign:'center'"  width="80" align="right">Value</th>
                </tr>
            </thead>
        </table>   
    </div>
</div>
</div>

<div id="yarnstocksubconknitingpartyreturnWindow" class="easyui-window" title="Return" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center'">
        <table id="yarnstocksubconknitingpartyreturnTbl">
            <thead>
                <tr>
                    <th data-options="field:'yarn_count',halign:'center'" width="80" >Count</th>
                    <th data-options="field:'yarn_desc',halign:'center'" width="250" >Yarn Description</th>
                    <th data-options="field:'yarn_type',halign:'center'" width="80" >Type</th>
                    <th data-options="field:'lot',halign:'center'" width="100" >Lot</th>
                    <th data-options="field:'supplier_name',halign:'center'" width="100" >Supplier</th>
                    <th data-options="field:'yarn_color',halign:'center'" width="130" >Yarn Color</th>
                    <th data-options="field:'qty',halign:'center'" width="80" align="right" >Qty</th>
                    <th data-options="field:'rate',halign:'center'" width="70" align="right">Rate</th>
                    <th data-options="field:'amount',halign:'center'"  width="80" align="right">Value</th>
                </tr>
                </tr>
            </thead>
        </table>   
    </div>
</div>
</div>

<div id="yarnstocksubconknitingpartyusedWindow" class="easyui-window" title="Used" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center'">
        <table id="yarnstocksubconknitingpartyusedTbl">
            <thead>
                <tr>
                    <th data-options="field:'yarn_count',halign:'center'" width="80" >Count</th>
                    <th data-options="field:'yarn_desc',halign:'center'" width="250" >Yarn Description</th>
                    <th data-options="field:'yarn_type',halign:'center'" width="80" >Type</th>
                    <th data-options="field:'lot',halign:'center'" width="100" >Lot</th>
                    <th data-options="field:'supplier_name',halign:'center'" width="100" >Supplier</th>
                    <th data-options="field:'yarn_color',halign:'center'" width="130" >Yarn Color</th>
                    <th data-options="field:'qty',halign:'center'" width="80" align="right" >Qty</th>
                    <th data-options="field:'rate',halign:'center'" width="70" align="right">Rate</th>
                    <th data-options="field:'amount',halign:'center'"  width="80" align="right">Value</th>
                </tr>
            </thead>
        </table>   
    </div>
</div>
</div>

<div id="yarnstocksubconknitingpartyclosingWindow" class="easyui-window" title="Closing Stock" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center'">
        <table id="yarnstocksubconknitingpartyclosingTbl">
            <thead>
                <tr>
                    <th data-options="field:'yarn_count',halign:'center'" width="80" >Count</th>
                    <th data-options="field:'yarn_desc',halign:'center'" width="250" >Yarn Description</th>
                    <th data-options="field:'yarn_type',halign:'center'" width="80" >Type</th>
                    <th data-options="field:'lot',halign:'center'" width="100" >Lot</th>
                    <th data-options="field:'supplier_name',halign:'center'" width="100" >Supplier</th>
                    <th data-options="field:'yarn_color',halign:'center'" width="130" >Yarn Color</th>
                    <th data-options="field:'opening_qty',halign:'center'" width="80" align="right">Opening Stock</th>
                    <th data-options="field:'rcv_qty',halign:'center'" width="80" align="right" >Grey Received</th>
                    <th data-options="field:'total_rcv_qty',halign:'center'" width="80" align="right">Total Received</th>
                    <th data-options="field:'dlv_fin_qty',halign:'center'" width="80" align="right">Fin. Delv.</th>
                    <th data-options="field:'dlv_grey_used_qty',halign:'center'" width="80" align="right" >Grey Used</th>
                    <th data-options="field:'rtn_qty',halign:'center'" width="80" align="right" >Grey Returned</th>
                    <th data-options="field:'total_adjusted',halign:'center'" width="80" align="right">Total Adusted</th>
                    <th data-options="field:'stock_qty',halign:'center'" width="80" align="right" >Closing Stock</th>
                    <th data-options="field:'rate',halign:'center'" width="80" align="right" align="right">Avg.Rate (BDT)</th>
                    <th data-options="field:'stock_value',halign:'center'" width="100" align="right" align="right">Value (BDT)</th>
                </tr>
            </thead>
        </table>   
    </div>
</div>
</div>

    
<script type="text/javascript" src="<?php echo url('/');?>/js/report/Subcontract/Kniting/MsYarnStockSubconKnitingPartyController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script>