<div class="easyui-layout animated rollIn"  data-options="fit:true" id="yarnpurchaseratetrendPanel">
    <div data-options="region:'center',border:true,title:'Yarn Purchase Rate Trend Report'" style="padding:2px">
        
        
                <table id="yarnpurchaseratetrendTbl" style="width:1890px">
                    <thead>
                        <tr>
                            <th data-options="field:'item_account_id',halign:'center'" width="80">Item ID</th>
                            <th data-options="field:'supplier_name',halign:'center'" width="180">Supplier</th>
                            <th data-options="field:'count_name',halign:'center'" align="center" width="70">Count</th>
                            <th data-options="field:'composition',halign:'center'" align="left" width="250">Yarn Description.</th>
                            <th data-options="field:'yarn_type',halign:'center'" width="100">Type</th>
                            
                            <th data-options="field:'month',halign:'center'" width="80" align="center">Month</th>
                            <th data-options="field:'store_rate',halign:'center'" width="80" align="right">Rate (BDT)</th>
                            <th data-options="field:'rate',halign:'center'" width="80" align="right">Po Rate</th>
                            <th data-options="field:'exch_rate',halign:'center'" width="80" align="right">Exch. Rate</th>
                        </tr>
                    </thead>
                </table>
            
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="yarnpurchaseratetrendFrm">
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
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsYarnPurchaseRateTrend.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsYarnPurchaseRateTrend.resetForm('yarnpurchaseratetrendFrm')" >Reset</a>
            </div>
      </form>
    </div>
</div>
    
<script type="text/javascript" src="<?php echo url('/');?>/js/report/Inventory/MsYarnPurchaseRateTrendController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script>