<div class="easyui-layout animated rollIn"  data-options="fit:true" id="yarnpurchaselcwisePanel">
    <div data-options="region:'center',border:true,title:'LC Wise Yarn Purchase  Report'" style="padding:2px">
        <table id="yarnpurchaselcwiseTbl" style="width:1890px">
            <thead>
                <tr>
                    <th width="60">1</th>
                    <th width="120">2</th>
                    <th width="80">3</th>
                    <th width="160">4</th>
                    <th width="250">5</th>
                    <th width="60">6</th>
                    <th width="100">7</th>
                    <th width="80">8</th>
                    <th width="80">9</th>
                    <th width="80">10</th>
                    <th width="80">11</th>
                    <th width="100">12</th>
                    <th width="100">13</th>
                    <th width="100">14</th>
                    <th width="100">15</th>
                    <th width="100">16</th>
                    <th width="200">17</th>
                </tr>
                <tr>
                    <th data-options="field:'company_code',halign:'center'" width="60">Applicant</th>
                    <th data-options="field:'lc_no',halign:'center'" width="120">LC No</th>
                    <th data-options="field:'lc_date',halign:'center'" width="80">LC Date</th>
                    <th data-options="field:'pi_no',halign:'center'" align="left" width="160">PI No & Date</th>
                    <th data-options="field:'supplier_name',halign:'center'" align="left" width="250">Supplier</th>
                    <th data-options="field:'currency_code',halign:'center'" width="60" align="center">Currency</th>
                    <th data-options="field:'lc_qty',halign:'center'" width="100" align="right" formatter="MsYarnPurchaseLcWise.formatLcQty">LC Qty</th>
                    <th data-options="field:'lc_rate',halign:'center'" width="80" align="right">Avg. Rate</th>
                    <th data-options="field:'lc_amount',halign:'center'" width="80" align="right">LC Value</th>
                    <th data-options="field:'qty',halign:'center'" width="80" align="right" formatter="MsYarnPurchaseLcWise.formatRcvQty">Receive Qty</th>
                    <th data-options="field:'rate',halign:'center'" width="80" align="right">Avg. Rate</th>
                    <th data-options="field:'amount',halign:'center'" width="100" align="right">Amount</th>
                    <th data-options="field:'balance_qty',halign:'center'" width="100" align="right">Balance Qty</th>
                    <th data-options="field:'balance_amount',halign:'center'" width="100" align="right">Balance Amount</th>
                    <th data-options="field:'acceptance_value',halign:'center'" width="100" align="right">Acceptance Given</th>
                    <th data-options="field:'balance_acpt',halign:'center'" width="100" align="right">Yet to Accept</th>
                    <th data-options="field:'buyer',halign:'center'" width="200" align="left">Buyer</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="yarnpurchaselcwiseFrm">
            <div id="container">
                <div id="body">
                <code>
                    
                    <div class="row">
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
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsYarnPurchaseLcWise.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsYarnPurchaseLcWise.resetForm('yarnpurchaselcwiseFrm')" >Reset</a>
            </div>
      </form>
    </div>
</div>

<div id="lcwiseyarnpurlcqtydtlWindow" class="easyui-window" title="Lc Qty Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="lcwiseyarnpurlcqtydtlTbl" style="width:900px">
        <thead>
            <tr>
            <th data-options="field:'yarn_count'" width="70px">Count</th>
            <th data-options="field:'composition'" width="150px">Composition</th>
            <th data-options="field:'yarn_type'" width="80px">Type</th>
            <th data-options="field:'qty'" width="70px" align="right">Qty</th>
            <th data-options="field:'rate'" width="60px" align="right">Rate</th>
            <th data-options="field:'amount'" width="80px" align="right">Amount</th>
            <th data-options="field:'remarks'" width="250px" align="left">Remarks</th>
            </tr>
        </thead>
    </table>
</div>

<div id="lcwiseyarnpurrcvqtydtlWindow" class="easyui-window" title="Receive Qty Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="lcwiseyarnpurrcvqtydtlTbl" style="width:900px">
        <thead>
            <tr>
            <th data-options="field:'receive_no'" width="70px">MRR</th>
            <th data-options="field:'receive_date'" width="80px">Receive Date</th>
            <th data-options="field:'yarn_count'" width="70px">Count</th>
            <th data-options="field:'composition'" width="150px">Composition</th>
            <th data-options="field:'yarn_type'" width="80px">Type</th>
            <th data-options="field:'brand'" width="80px">Brand</th>
            <th data-options="field:'lot'" width="80px">Lot</th>
            <th data-options="field:'qty'" width="70px" align="right">Qty</th>
            <th data-options="field:'rate'" width="60px" align="right">Rate</th>
            <th data-options="field:'amount'" width="80px" align="right">Amount</th>
            <th data-options="field:'no_of_bag'" width="80px" align="right">No Of Bag</th>
            <th data-options="field:'rcv_rtn_qty'" width="80px" align="right">Return Qty</th>
            <th data-options="field:'rcv_rtn_amount'" width="80px" align="right">Return Amount</th>
            <th data-options="field:'net_qty'" width="80px" align="right">Net Rcv. Qty</th>
            <th data-options="field:'net_amount'" width="80px" align="right">Net Rcv. Amount</th>
            <th data-options="field:'remarks'" width="250px" align="left">Remarks</th>
            </tr>
        </thead>
    </table>
</div>
    
<script type="text/javascript" src="<?php echo url('/');?>/js/report/Inventory/MsYarnPurchaseLcWiseController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script>