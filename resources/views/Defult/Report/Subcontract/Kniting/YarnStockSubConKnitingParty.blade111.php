<div class="easyui-layout animated rollIn"  data-options="fit:true" id="kpwystck">
    <div data-options="region:'center',border:true,title:'Yarn Dyeing Party Wise Yarn Stock Report'" style="padding:2px">
        
        
                <table id="yarnstockyarndyeingpartyTbl" style="width:1890px">
                    <thead>
                        <tr>
                            <th data-options="field:'supplier_name',halign:'center'" width="150" >Knitting Party</th>
                            <th data-options="field:'opening_qty',halign:'center'" width="80" align="right">Opening Stock</th>
                            <th data-options="field:'issue_qty',halign:'center'" width="80" align="right" formatter="MsYarnStockYarnDyeingParty.formatIssued">Issued</th>
                            <th data-options="field:'total_issue_qty',halign:'center'" width="80" align="right">Total issued</th>
                            <th data-options="field:'used_qty',halign:'center'" width="80" align="right" formatter="MsYarnStockYarnDyeingParty.formatUsed">Yarn Used</th>
                            <th data-options="field:'return_qty',halign:'center'" width="80" align="right" formatter="MsYarnStockYarnDyeingParty.formatReturn">Yarn Returned</th>
                            <th data-options="field:'total_adjusted',halign:'center'" width="80" align="right">Total Adusted</th>
                            <th data-options="field:'stock_qty',halign:'center'" width="80" align="right">Closing Stock</th>
                            <th data-options="field:'rate',halign:'center'" width="80" align="right" align="right">Avg.Rate (BDT)</th>
                            <th data-options="field:'stock_value',halign:'center'" width="80" align="right" align="right">Value (BDT)</th>
                        </tr>
                    </thead>
                </table>
            
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#yarnstockyarndyeingpartyFrmFt'" style="width:350px; padding:2px">
        <form id="yarnstockyarndyeingpartyFrm">
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
            <div id="yarnstockyarndyeingpartyFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsYarnStockYarnDyeingParty.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsYarnStockYarnDyeingParty.resetForm('yarnstockyarndyeingpartyFrm')" >Reset</a>
            </div>
      </form>
    </div>
</div>



<div id="yarnstockyarndyeingpartyissuedWindow" class="easyui-window" title="Issued" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center'">
        <table id="yarnstockyarndyeingpartyissuedTbl">
            <thead>
                <tr>
                    <th data-options="field:'issue_no',halign:'center'" width="80" >Issue No</th>
                    <th data-options="field:'issue_date',halign:'center'" width="80" >Issue Date</th>
                    <th data-options="field:'sale_order_no',halign:'center'" width="80" >Sale Order No</th>
                    <th data-options="field:'yarn_count',halign:'center'" width="50" >Count</th>
                    <th data-options="field:'yarn_desc',halign:'center'" width="150" >Yarn Description</th>
                    <th data-options="field:'yarn_type',halign:'center'" width="70" >Type</th>
                    <th data-options="field:'lot',halign:'center'" width="80" >Lot</th>
                    <th data-options="field:'brand',halign:'center'" width="80" >Brand</th>
                    <th data-options="field:'qty',halign:'center'" width="80" align="right" >Qty</th>
                    <th data-options="field:'rate',halign:'center'" width="70" align="right">Rate</th>
                    <th data-options="field:'amount',halign:'center'"  width="80" align="right">Value</th>
                    <th data-options="field:'remarks',halign:'center'"  width="150">Remarks</th>
                    <th data-options="field:'id',halign:'center'" width="100">Yarn ID</th>
                </tr>
            </thead>
        </table>   
    </div>
</div>
</div>

<div id="yarnstockyarndyeingpartyreturnWindow" class="easyui-window" title="Return" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center'">
        <table id="yarnstockyarndyeingpartyreturnTbl">
            <thead>
                <tr>
                    <th data-options="field:'receive_no',halign:'center'" width="80" >Return No</th>
                    <th data-options="field:'receive_date',halign:'center'" width="80" >Return Date</th>
                    <th data-options="field:'sale_order_no',halign:'center'" width="80" >Sale Order No</th>
                    <th data-options="field:'yarn_count',halign:'center'" width="50" >Count</th>
                    <th data-options="field:'yarn_desc',halign:'center'" width="150" >Yarn Description</th>
                    <th data-options="field:'yarn_type',halign:'center'" width="70" >Type</th>
                    <th data-options="field:'lot',halign:'center'" width="80" >Lot</th>
                    <th data-options="field:'brand',halign:'center'" width="80" >Brand</th>
                    <th data-options="field:'qty',halign:'center'" width="80" align="right" >Qty</th>
                    <th data-options="field:'rate',halign:'center'" width="70" align="right">Rate</th>
                    <th data-options="field:'amount',halign:'center'"  width="80" align="right">Value</th>
                    <th data-options="field:'remarks',halign:'center'"  width="150">Remarks</th>
                    <th data-options="field:'id',halign:'center'" width="100">Yarn ID</th>
                </tr>
            </thead>
        </table>   
    </div>
</div>
</div>

<div id="yarnstockyarndyeingpartyusedWindow" class="easyui-window" title="Used" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center'">
        <table id="yarnstockyarndyeingpartyusedTbl">
            <thead>
                <tr>
                    <th data-options="field:'prod_no',halign:'center'" width="80" >Prod. No</th>
                    <th data-options="field:'prod_date',halign:'center'" width="80" >Prod. Date</th>
                    <th data-options="field:'sale_order_no',halign:'center'" width="80" >Sale Order No</th>
                    <th data-options="field:'yarn_count',halign:'center'" width="50" >Count</th>
                    <th data-options="field:'yarn_desc',halign:'center'" width="150" >Yarn Description</th>
                    <th data-options="field:'yarn_type',halign:'center'" width="70" >Type</th>
                    <th data-options="field:'lot',halign:'center'" width="80" >Lot</th>
                    <th data-options="field:'brand',halign:'center'" width="80" >Brand</th>
                    <th data-options="field:'qty',halign:'center'" width="80" align="right" >Qty</th>
                    <th data-options="field:'rate',halign:'center'" width="70" align="right">Rate</th>
                    <th data-options="field:'amount',halign:'center'"  width="80" align="right">Value</th>
                    <th data-options="field:'remarks',halign:'center'"  width="150">Remarks</th>
                    <th data-options="field:'id',halign:'center'" width="100">Yarn ID</th>
                </tr>
            </thead>
        </table>   
    </div>
</div>
</div>

    
<script type="text/javascript" src="<?php echo url('/');?>/js/report/Inventory/MsYarnStockYarnDyeingPartyController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script>