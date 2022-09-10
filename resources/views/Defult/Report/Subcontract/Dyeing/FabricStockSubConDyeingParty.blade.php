<div class="easyui-layout animated rollIn"  data-options="fit:true" id="fabricstocksubcondyeingpartypanel">
    <div data-options="region:'center',border:true,title:'Dyeing Party Wise Fabric Stock Report'" style="padding:2px">
        
        
                <table id="fabricstocksubcondyeingpartyTbl" style="width:1890px">
                    <thead>
                        <tr>
                            <th data-options="field:'buyer_name',halign:'center'" width="150" >Dyeing Party</th>
                            <th data-options="field:'opening_qty',halign:'center'" width="80" align="right">Opening Stock</th>
                            <th data-options="field:'rcv_qty',halign:'center'" width="80" align="right" formatter="MsFabricStockSubconDyeingParty.formatReceived">Grey Received</th>
                            <th data-options="field:'total_rcv_qty',halign:'center'" width="80" align="right">Total Received</th>
                            <th data-options="field:'dlv_fin_qty',halign:'center'" width="80" align="right">Fin. Delv.</th>
                            <th data-options="field:'dlv_grey_used_qty',halign:'center'" width="80" align="right" formatter="MsFabricStockSubconDyeingParty.formatUsed">Grey Used</th>
                            <th data-options="field:'rtn_qty',halign:'center'" width="80" align="right" formatter="MsFabricStockSubconDyeingParty.formatReturn">Grey Returned</th>
                            <th data-options="field:'total_adjusted',halign:'center'" width="80" align="right">Total Adusted</th>
                            <th data-options="field:'stock_qty',halign:'center'" width="80" align="right" formatter="MsFabricStockSubconDyeingParty.formatClosing">Closing Stock</th>
                            <th data-options="field:'rate',halign:'center'" width="80" align="right" align="right">Avg.Rate (BDT)</th>
                            <th data-options="field:'stock_value',halign:'center'" width="100" align="right" align="right">Value (BDT)</th>
                        </tr>
                    </thead>
                </table>
            
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#fabricstocksubcondyeingpartyFrmFt'" style="width:350px; padding:2px">
        <form id="fabricstocksubcondyeingpartyFrm">
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
            <div id="fabricstocksubcondyeingpartyFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsFabricStockSubconDyeingParty.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsFabricStockSubconDyeingParty.resetForm('fabricstocksubcondyeingpartyFrm')" >Reset</a>
            </div>
      </form>
    </div>
</div>



<div id="fabricstocksubcondyeingpartyreceivedWindow" class="easyui-window" title="Received" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center'">
        <table id="fabricstocksubcondyeingpartyreceivedTbl">
            <thead>
                <tr>
                    <th data-options="field:'gmtsparts_name',halign:'center'" width="80" >Gmt Parts</th>
                    <th data-options="field:'fabric_desc',halign:'center'" width="250" >Fabric Description</th>
                    <th data-options="field:'gsm_weight',halign:'center'" width="80" >GSM/Weight</th>
                    <th data-options="field:'fabric_shape_name',halign:'center'" width="100" >Fabric Shape</th>
                    <th data-options="field:'fabric_look_name',halign:'center'" width="100" >Fabric Looks</th>
                    <th data-options="field:'fabric_color_name',halign:'center'" width="130" >Fabric Color</th>
                    <th data-options="field:'color_range_name',halign:'center'" width="100" >Color Range</th>
                    <th data-options="field:'dyeing_type_name',halign:'center'" width="80" >Dyeing Type</th>
                    <th data-options="field:'qty',halign:'center'" width="80" align="right" >Qty</th>
                    <th data-options="field:'rate',halign:'center'" width="70" align="right">Rate</th>
                    <th data-options="field:'amount',halign:'center'"  width="80" align="right">Value</th>
                </tr>
            </thead>
        </table>   
    </div>
</div>
</div>

<div id="fabricstocksubcondyeingpartyreturnWindow" class="easyui-window" title="Return" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center'">
        <table id="fabricstocksubcondyeingpartyreturnTbl">
            <thead>
                <tr>
                    <th data-options="field:'gmtsparts_name',halign:'center'" width="80" >Gmt Parts</th>
                    <th data-options="field:'fabric_desc',halign:'center'" width="250" >Fabric Description</th>
                    <th data-options="field:'gsm_weight',halign:'center'" width="80" >GSM/Weight</th>
                    <th data-options="field:'fabric_shape_name',halign:'center'" width="100" >Fabric Shape</th>
                    <th data-options="field:'fabric_look_name',halign:'center'" width="100" >Fabric Looks</th>
                    <th data-options="field:'fabric_color_name',halign:'center'" width="130" >Fabric Color</th>
                    <th data-options="field:'color_range_name',halign:'center'" width="100" >Color Range</th>
                    <th data-options="field:'dyeing_type_name',halign:'center'" width="80" >Dyeing Type</th>
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

<div id="fabricstocksubcondyeingpartyusedWindow" class="easyui-window" title="Used" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center'">
        <table id="fabricstocksubcondyeingpartyusedTbl">
            <thead>
                <tr>
                    <th data-options="field:'gmtsparts_name',halign:'center'" width="80" >Gmt Parts</th>
                    <th data-options="field:'fabric_desc',halign:'center'" width="250" >Fabric Description</th>
                    <th data-options="field:'gsm_weight',halign:'center'" width="80" >GSM/Weight</th>
                    <th data-options="field:'fabric_shape_name',halign:'center'" width="100" >Fabric Shape</th>
                    <th data-options="field:'fabric_look_name',halign:'center'" width="100" >Fabric Looks</th>
                    <th data-options="field:'fabric_color_name',halign:'center'" width="130" >Fabric Color</th>
                    <th data-options="field:'color_range_name',halign:'center'" width="100" >Color Range</th>
                    <th data-options="field:'dyeing_type_name',halign:'center'" width="80" >Dyeing Type</th>
                    <th data-options="field:'fin_qty',halign:'center'" width="80" align="right" >Fin. Dlv</th>
                    <th data-options="field:'qty',halign:'center'" width="80" align="right" >Grey Used</th>
                    <th data-options="field:'rate',halign:'center'" width="70" align="right">Rate</th>
                    <th data-options="field:'amount',halign:'center'"  width="80" align="right">Value</th>
                </tr>
            </thead>
        </table>   
    </div>
</div>
</div>

<div id="fabricstocksubcondyeingpartyclosingWindow" class="easyui-window" title="Closing Stock" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center'">
        <table id="fabricstocksubcondyeingpartyclosingTbl">
            <thead>
                <tr>
                    <th data-options="field:'gmtsparts_name',halign:'center'" width="80" >Gmt Parts</th>
                    <th data-options="field:'fabric_desc',halign:'center'" width="250" >Fabric Description</th>
                    <th data-options="field:'gsm_weight',halign:'center'" width="80" >GSM/Weight</th>
                    <th data-options="field:'fabric_shape_name',halign:'center'" width="100" >Fabric Shape</th>
                    <th data-options="field:'fabric_look_name',halign:'center'" width="100" >Fabric Looks</th>
                    <th data-options="field:'fabric_color_name',halign:'center'" width="130" >Fabric Color</th>
                    <th data-options="field:'color_range_name',halign:'center'" width="100" >Color Range</th>
                    <th data-options="field:'dyeing_type_name',halign:'center'" width="80" >Dyeing Type</th>
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

    
<script type="text/javascript" src="<?php echo url('/');?>/js/report/Subcontract/Dyeing/MsFabricStockSubconDyeingPartyController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script>