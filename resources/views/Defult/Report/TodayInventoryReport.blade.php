<div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Inventory'" style="padding:2px">
        <div id="todayinventoryreportdatamatrix">
        </div>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#todayinventoryreportFrmft'" style="width:350px; padding:2px">
        <form id="todayinventoryreportFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row middle">
                            <div class="col-sm-4">Date </div>
                            
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="trans_date" id="trans_date" class="datepicker"  placeholder="To" value="<?php echo $date_to; ?>" />
                            </div>
                        </div>
                    </code>
                </div>
            </div>
            <div id="todayinventoryreportFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsTodayInventoryReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsTodayInventoryReport.resetForm('todayinventoryreportFrm')" >Reset</a>
            </div>
        </form>
    </div> 
</div>

<div id="todayinventorygeneralreportWindow" class="easyui-window" title="General Item Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true" >
        <table id="todayinventorygeneralreportTbl" style="width:610px">
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
</div>


<div id="todayinventorygeneralreportRcvWindow" class="easyui-window" title="General Item Receive Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true" >
        <table id="todayinventorygeneralreportRcvTbl" style="width:610px">
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
                <th data-options="field:'receive_qty',halign:'center'" width="80" align="right" >Tot. Rcv. Qty</th>
                <th data-options="field:'receive_amount',halign:'center'" width="80" align="right" >Tot. Rcv. Amount</th>

                <th data-options="field:'regular_issue_qty',halign:'center'" width="80" align="right">Consumed</th>
                <th data-options="field:'trans_out_issue_qty',halign:'center'" width="80" align="right">Trans Out</th>
                <th data-options="field:'rcv_rtn_issue_qty',halign:'center'" width="80" align="right">Purchase Rtn.</th>
                <th data-options="field:'issue_qty',halign:'center'" width="80" align="right">Issue Qty</th>
                <th data-options="field:'issue_amount',halign:'center'" width="80" align="right">Issue Amount</th>
                <th data-options="field:'stock_qty',halign:'center'" width="80" align="right">Stock Qty</th>
                <th data-options="field:'rate',halign:'center'" width="80" align="right">Rate -TK</th>
                <th data-options="field:'stock_value',halign:'center'" width="120" align="right">Value - TK</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div id="todayinventorygeneralreportIsuWindow" class="easyui-window" title="General Item Issue Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true" >
        <table id="todayinventorygeneralreportIsuTbl" style="width:610px">
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
                <th data-options="field:'receive_qty',halign:'center'" width="80" align="right" >Tot. Rcv. Qty</th>
                <th data-options="field:'receive_amount',halign:'center'" width="80" align="right" >Tot. Rcv. Amount</th>

                <th data-options="field:'regular_issue_qty',halign:'center'" width="80" align="right">Consumed</th>
                <th data-options="field:'trans_out_issue_qty',halign:'center'" width="80" align="right">Trans Out</th>
                <th data-options="field:'rcv_rtn_issue_qty',halign:'center'" width="80" align="right">Purchase Rtn.</th>
                <th data-options="field:'issue_qty',halign:'center'" width="80" align="right">Issue Qty</th>
                <th data-options="field:'issue_amount',halign:'center'" width="80" align="right">Issue Amount</th>
                <th data-options="field:'stock_qty',halign:'center'" width="80" align="right">Stock Qty</th>
                <th data-options="field:'rate',halign:'center'" width="80" align="right">Rate -TK</th>
                <th data-options="field:'stock_value',halign:'center'" width="120" align="right">Value - TK</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div id="todayinventoryyarnreportWindow" class="easyui-window" title="Yarn Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true" >
        <table id="todayinventoryyarnreportTbl" style="width:610px">
        <thead>
            <tr>
            <th data-options="field:'id',halign:'center'" width="80" >ID</th>
            <th data-options="field:'lot',halign:'center'" width="80" >Lot</th>
            <th data-options="field:'count_name',halign:'center'" align="center" width="70">Count</th>
            <th data-options="field:'composition',halign:'center'" align="left" width="150">Composition</th>
            <th data-options="field:'type_name',halign:'center'" width="100">Type</th>
            <th data-options="field:'brand',halign:'center'" width="100">Brand</th>
            <th data-options="field:'color_name',halign:'center'" width="100">Color</th>
            <th data-options="field:'supplier_name',halign:'center'" width="120">Supplier</th>
            <th data-options="field:'opening_qty',halign:'center'" width="80" align="right">Opening Qty</th>
            <th data-options="field:'receive_qty',halign:'center'" width="80" align="right" >Receive Qty</th>
            <th data-options="field:'issue_qty',halign:'center'" width="80" align="right">Issue Qty</th>
            <th data-options="field:'stock_qty',halign:'center'" width="80" align="right">Stock Qty</th>
            <th data-options="field:'rate',halign:'center'" width="80" align="right">Rate -TK</th>
            <th data-options="field:'stock_value',halign:'center'" width="120" align="right">Value - TK</th>
            </tr>
        </thead>
        </table>
    </div>
</div>

<div id="todayinventoryyarnreportRcvWindow" class="easyui-window" title="Yarn Receive Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true" >
        <table id="todayinventoryyarnreportRcvTbl" style="width:610px">
        <thead>
            <tr>
            <th data-options="field:'id',halign:'center'" width="80" >ID</th>
            <th data-options="field:'lot',halign:'center'" width="80" >Lot</th>
            <th data-options="field:'count_name',halign:'center'" align="center" width="70">Count</th>
            <th data-options="field:'composition',halign:'center'" align="left" width="150">Composition</th>
            <th data-options="field:'type_name',halign:'center'" width="100">Type</th>
            <th data-options="field:'brand',halign:'center'" width="100">Brand</th>
            <th data-options="field:'color_name',halign:'center'" width="100">Color</th>
            <th data-options="field:'supplier_name',halign:'center'" width="120">Supplier</th>
            <th data-options="field:'opening_qty',halign:'center'" width="80" align="right">Opening Qty</th>
            <th data-options="field:'receive_qty',halign:'center'" width="80" align="right" >Receive Qty</th>
            <th data-options="field:'receive_amount',halign:'center'" width="80" align="right" >Receive Amount</th>
            <th data-options="field:'issue_qty',halign:'center'" width="80" align="right">Issue Qty</th>
            <th data-options="field:'issue_amount',halign:'center'" width="80" align="right">Issue Amount</th>
            <th data-options="field:'stock_qty',halign:'center'" width="80" align="right">Stock Qty</th>
            <th data-options="field:'rate',halign:'center'" width="80" align="right">Rate -TK</th>
            <th data-options="field:'stock_value',halign:'center'" width="120" align="right">Value - TK</th>
            </tr>
        </thead>
        </table>
    </div>
</div>

<div id="todayinventoryyarnreportIsuWindow" class="easyui-window" title="Yarn Issue Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true" >
        <table id="todayinventoryyarnreportIsuTbl" style="width:610px">
        <thead>
            <tr>
            <th data-options="field:'id',halign:'center'" width="80" >ID</th>
            <th data-options="field:'lot',halign:'center'" width="80" >Lot</th>
            <th data-options="field:'count_name',halign:'center'" align="center" width="70">Count</th>
            <th data-options="field:'composition',halign:'center'" align="left" width="150">Composition</th>
            <th data-options="field:'type_name',halign:'center'" width="100">Type</th>
            <th data-options="field:'brand',halign:'center'" width="100">Brand</th>
            <th data-options="field:'color_name',halign:'center'" width="100">Color</th>
            <th data-options="field:'supplier_name',halign:'center'" width="120">Supplier</th>
            <th data-options="field:'opening_qty',halign:'center'" width="80" align="right">Opening Qty</th>
            <th data-options="field:'receive_qty',halign:'center'" width="80" align="right" >Receive Qty</th>
            <th data-options="field:'receive_amount',halign:'center'" width="80" align="right" >Receive Amount</th>
            <th data-options="field:'issue_qty',halign:'center'" width="80" align="right">Issue Qty</th>
            <th data-options="field:'issue_amount',halign:'center'" width="80" align="right">Issue Amount</th>
            <th data-options="field:'stock_qty',halign:'center'" width="80" align="right">Stock Qty</th>
            <th data-options="field:'rate',halign:'center'" width="80" align="right">Rate -TK</th>
            <th data-options="field:'stock_value',halign:'center'" width="120" align="right">Value - TK</th>
            </tr>
        </thead>
        </table>
    </div>
</div>

<div id="todayinventorydyechemreportWindow" class="easyui-window" title="Dyes & Chemical Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true" >
        <table id="todayinventorydyechemreportTbl" style="width:610px">
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
</div>

<div id="todayinventorydyechemreportRcvWindow" class="easyui-window" title="Dyes & Chemical Receive Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true" >
        <table id="todayinventorydyechemreportRcvTbl" style="width:610px">
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
                <th data-options="field:'receive_qty',halign:'center'" width="80" align="right" >Receive Qty</th>
                <th data-options="field:'receive_amount',halign:'center'" width="80" align="right" >Receive Amount</th>

                <th data-options="field:'regular_issue_qty',halign:'center'" width="80" align="right">Consumed</th>
                <th data-options="field:'trans_out_issue_qty',halign:'center'" width="80" align="right">Trans Out</th>
                <th data-options="field:'rcv_rtn_issue_qty',halign:'center'" width="80" align="right">Purchase Rtn.</th>
                <th data-options="field:'issue_qty',halign:'center'" width="80" align="right">Issue Qty</th>
                <th data-options="field:'issue_amount',halign:'center'" width="80" align="right">Issue Amount</th>
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
</div>

<div id="todayinventorydyechemreportIsuWindow" class="easyui-window" title="Dyes & Chemical Receive Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true" >
        <table id="todayinventorydyechemreportIsuTbl" style="width:610px">
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
                <th data-options="field:'receive_qty',halign:'center'" width="80" align="right" >Receive Qty</th>
                <th data-options="field:'receive_amount',halign:'center'" width="80" align="right" >Receive Amount</th>

                <th data-options="field:'regular_issue_qty',halign:'center'" width="80" align="right">Consumed</th>
                <th data-options="field:'trans_out_issue_qty',halign:'center'" width="80" align="right">Trans Out</th>
                <th data-options="field:'rcv_rtn_issue_qty',halign:'center'" width="80" align="right">Purchase Rtn.</th>
                <th data-options="field:'issue_qty',halign:'center'" width="80" align="right">Issue Qty</th>
                <th data-options="field:'issue_amount',halign:'center'" width="80" align="right">Issue Amount</th>
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
</div>

<div id="todayinventorygreyfabreportWindow" class="easyui-window" title="Grey Fabric Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true" >
        <table id="todayinventorygreyfabreportTbl" style="width:610px">
            <thead>
                <tr>
                <th data-options="field:'id',halign:'center'" width="80" >ID</th>
                <th data-options="field:'gmtspart_name',halign:'center'" width="100" >GMTS Part</th>
                <th data-options="field:'fabrication',halign:'center'" width="250">Fab. Description</th>
                <th data-options="field:'fabric_look',halign:'center'" width="50">Fabric Look</th>
                <th data-options="field:'fabric_shape',halign:'center'" width="50">Fabric Shape</th>
                <th data-options="field:'gsm_weight',halign:'center'" width="50">GSM /Weight</th>
                <th data-options="field:'dia',halign:'center'" width="80" align="right">Dia</th>
                <th data-options="field:'measurment',halign:'center'" width="80" align="right">Measurment</th>
                <th data-options="field:'roll_length',halign:'center'" width="80" align="right">Roll Length</th>
                <th data-options="field:'stitch_length',halign:'center'" width="80" align="right">Stitch Length</th>
                <th data-options="field:'shrink_per',halign:'center'" width="80" align="right">Shrink %</th>
                <th data-options="field:'colorrange_name',halign:'center'" width="80" align="right">Colorrange</th>
                <th data-options="field:'opening_qty',halign:'center'" width="80" align="right">Opening Qty</th>
                <th data-options="field:'pur_qty',halign:'center'" width="80" align="right" >Received</th>
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
</div>

<div id="todayinventorygreyfabreportRcvWindow" class="easyui-window" title="Grey Fabric Receive Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true" >
        <table id="todayinventorygreyfabreportRcvTbl" style="width:610px">
            <thead>
                <tr>
                <th data-options="field:'id',halign:'center'" width="80" >ID</th>
                <th data-options="field:'gmtspart_name',halign:'center'" width="100" >GMTS Part</th>
                <th data-options="field:'fabrication',halign:'center'" width="250">Fab. Description</th>
                <th data-options="field:'fabric_look',halign:'center'" width="50">Fabric Look</th>
                <th data-options="field:'fabric_shape',halign:'center'" width="50">Fabric Shape</th>
                <th data-options="field:'gsm_weight',halign:'center'" width="50">GSM /Weight</th>
                <th data-options="field:'dia',halign:'center'" width="80" align="right">Dia</th>
                <th data-options="field:'measurment',halign:'center'" width="80" align="right">Measurment</th>
                <th data-options="field:'roll_length',halign:'center'" width="80" align="right">Roll Length</th>
                <th data-options="field:'stitch_length',halign:'center'" width="80" align="right">Stitch Length</th>
                <th data-options="field:'shrink_per',halign:'center'" width="80" align="right">Shrink %</th>
                <th data-options="field:'colorrange_name',halign:'center'" width="80" align="right">Colorrange</th>
                <th data-options="field:'opening_qty',halign:'center'" width="80" align="right">Opening Qty</th>
                <th data-options="field:'pur_qty',halign:'center'" width="80" align="right" >Received</th>
                <th data-options="field:'trans_in_qty',halign:'center'" width="80" align="right" >Trans In Qty</th>
                <th data-options="field:'isu_rtn_qty',halign:'center'" width="80" align="right" >Issue Rtn</th>
                <th data-options="field:'receive_qty',halign:'center'" width="80" align="right" >Receive Qty</th>
                <th data-options="field:'receive_amount',halign:'center'" width="80" align="right" >Receive Amount</th>

                <th data-options="field:'regular_issue_qty',halign:'center'" width="80" align="right">Consumed</th>
                <th data-options="field:'trans_out_issue_qty',halign:'center'" width="80" align="right">Trans Out</th>
                <th data-options="field:'rcv_rtn_issue_qty',halign:'center'" width="80" align="right">Purchase Rtn.</th>
                <th data-options="field:'issue_qty',halign:'center'" width="80" align="right">Issue Qty</th>
                <th data-options="field:'issue_amount',halign:'center'" width="80" align="right">Issue Amount</th>
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
</div>
<div id="todayinventorygreyfabreportIsuWindow" class="easyui-window" title="Grey Fabric Issue Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true" >
        <table id="todayinventorygreyfabreportIsuTbl" style="width:610px">
            <thead>
                <tr>
                <th data-options="field:'id',halign:'center'" width="80" >ID</th>
                <th data-options="field:'gmtspart_name',halign:'center'" width="100" >GMTS Part</th>
                <th data-options="field:'fabrication',halign:'center'" width="250">Fab. Description</th>
                <th data-options="field:'fabric_look',halign:'center'" width="50">Fabric Look</th>
                <th data-options="field:'fabric_shape',halign:'center'" width="50">Fabric Shape</th>
                <th data-options="field:'gsm_weight',halign:'center'" width="50">GSM /Weight</th>
                <th data-options="field:'dia',halign:'center'" width="80" align="right">Dia</th>
                <th data-options="field:'measurment',halign:'center'" width="80" align="right">Measurment</th>
                <th data-options="field:'roll_length',halign:'center'" width="80" align="right">Roll Length</th>
                <th data-options="field:'stitch_length',halign:'center'" width="80" align="right">Stitch Length</th>
                <th data-options="field:'shrink_per',halign:'center'" width="80" align="right">Shrink %</th>
                <th data-options="field:'colorrange_name',halign:'center'" width="80" align="right">Colorrange</th>
                <th data-options="field:'opening_qty',halign:'center'" width="80" align="right">Opening Qty</th>
                <th data-options="field:'pur_qty',halign:'center'" width="80" align="right" >Received</th>
                <th data-options="field:'trans_in_qty',halign:'center'" width="80" align="right" >Trans In Qty</th>
                <th data-options="field:'isu_rtn_qty',halign:'center'" width="80" align="right" >Issue Rtn</th>
                <th data-options="field:'receive_qty',halign:'center'" width="80" align="right" >Receive Qty</th>
                <th data-options="field:'receive_amount',halign:'center'" width="80" align="right" >Receive Amount</th>

                <th data-options="field:'regular_issue_qty',halign:'center'" width="80" align="right">Consumed</th>
                <th data-options="field:'trans_out_issue_qty',halign:'center'" width="80" align="right">Trans Out</th>
                <th data-options="field:'rcv_rtn_issue_qty',halign:'center'" width="80" align="right">Purchase Rtn.</th>
                <th data-options="field:'issue_qty',halign:'center'" width="80" align="right">Issue Qty</th>
                <th data-options="field:'issue_amount',halign:'center'" width="80" align="right">Issue Amount</th>
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
</div>

<div id="todayinventorydyeingsubconreportWindow" class="easyui-window" title="Yarn Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="todayinventorydyeingsubconreportWindowContainer" style="width:100%;height:100%;padding:0px;">
        
    </div>
    <!-- <div class="easyui-layout"  data-options="fit:true" >
        <table id="todayinventorydyeingsubconreportTbl" style="width:610px">
            <thead>
            <tr>
            <th data-options="field:'buyer_name',halign:'center'" width="150" >Dyeing Party</th>
            <th data-options="field:'opening_qty',halign:'center'" width="80" align="right">Opening Stock</th>
            <th data-options="field:'rcv_qty',halign:'center'" width="80" align="right" >Grey Received</th>
            <th data-options="field:'total_rcv_qty',halign:'center'" width="80" align="right">Total Received</th>
            <th data-options="field:'dlv_fin_qty',halign:'center'" width="80" align="right">Fin. Delv.</th>
            <th data-options="field:'dlv_grey_used_qty',halign:'center'" width="80" align="right">Grey Used</th>
            <th data-options="field:'rtn_qty',halign:'center'" width="80" align="right" >Grey Returned</th>
            <th data-options="field:'total_adjusted',halign:'center'" width="80" align="right">Total Adusted</th>
            <th data-options="field:'stock_qty',halign:'center'" width="80" align="right">Closing Stock</th>
            <th data-options="field:'rate',halign:'center'" width="80" align="right" align="right">Avg.Rate (BDT)</th>
            <th data-options="field:'stock_value',halign:'center'" width="100" align="right" align="right">Value (BDT)</th>
            </tr>
            </thead>
        </table>
    </div> -->
</div>

<div id="todayinventoryaopsubconreportWindow" class="easyui-window" title="Yarn Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="todayinventoryaopsubconreportWindowContainer" style="width:100%;height:100%;padding:0px;">
        
    </div>
    <!-- <div class="easyui-layout"  data-options="fit:true" >
        <table id="todayinventoryaopsubconreportTbl" style="width:610px">
            <thead>
            <tr>
            <th data-options="field:'buyer_name',halign:'center'" width="150" >AOP Party</th>
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
    </div> -->
</div>

<div id="todayinventoryknitingsubconreportWindow" class="easyui-window" title="Yarn Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="todayinventoryknitingsubconreportWindowContainer" style="width:100%;height:100%;padding:0px;">
        
    </div>
    <!-- <div class="easyui-layout"  data-options="fit:true" >
        <table id="todayinventoryknitingsubconreportTbl" style="width:610px">
            <thead>
            <tr>
            <th data-options="field:'buyer_name',halign:'center'" width="150" >Kniting Party</th>
            <th data-options="field:'opening_qty',halign:'center'" width="80" align="right">Opening Stock</th>
            <th data-options="field:'rcv_qty',halign:'center'" width="80" align="right" >Grey Received</th>
            <th data-options="field:'total_rcv_qty',halign:'center'" width="80" align="right">Total Received</th>
            <th data-options="field:'dlv_fin_qty',halign:'center'" width="80" align="right">Fin. Delv.</th>
            <th data-options="field:'dlv_grey_used_qty',halign:'center'" width="80" align="right">Grey Used</th>
            <th data-options="field:'rtn_qty',halign:'center'" width="80" align="right" >Grey Returned</th>
            <th data-options="field:'total_adjusted',halign:'center'" width="80" align="right">Total Adusted</th>
            <th data-options="field:'stock_qty',halign:'center'" width="80" align="right" >Closing Stock</th>
            <th data-options="field:'rate',halign:'center'" width="80" align="right" align="right">Avg.Rate (BDT)</th>
            <th data-options="field:'stock_value',halign:'center'" width="100" align="right" align="right">Value (BDT)</th>
            </tr>
            </thead>
        </table>
    </div> -->
</div>









<script type="text/javascript" src="<?php echo url('/');?>/js/report/Dashbord/MsTodayInventoryReportController.js"></script>
<script>
$(".datepicker" ).datepicker({
    beforeShow:function(input) {
        $(input).css({
            "position": "relative",
            "z-index": 999999
        });
    },
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
    changeYear: true
});
</script>
