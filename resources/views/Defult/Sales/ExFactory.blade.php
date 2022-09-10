<div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="exfactoryTbl" style="width:100%">
            <thead>
                <tr>
                <th data-options="field:'id'" width="20">ID</th>
                <th data-options="field:'sale_order_id'" width="100">Sale Order No</th>
                <th data-options="field:'ship_date'" width="80">Ship Date</th>
                <th data-options="field:'qty'" width="70" align="right">Qty</th>
                <th data-options="field:'rate'" width="60" align="right">Rate</th>
                <th data-options="field:'amount'" width="80" align="right">Amount</th>
            </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Add New SalesOrder',footer:'#ft3'" style="width:350px; padding:2px">
        <form id="exfactoryFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Sales Order </div>
                            <div class="col-sm-8">
                                <input type="hidden" name="id" id="id" />
                                <input type="text" name="sale_order_no" id="sale_order_no" placeholder="  Double Click" onDblClick="MsExFactory.openSaleOrderSecWindow()" readonly />
                                <input type="hidden" name="sale_order_id" id="sale_order_id" value="" />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Ship Date</div>
                            <div class="col-sm-8"><input type="text" name="ship_date" id="ship_date" class="datepicker" /></div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Price/Unit</div>
                            <div class="col-sm-8"><input type="text" name="rate" id="rate" class="number integer" onchange="MsExFactory.calculate()"/></div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Quantity</div>
                            <div class="col-sm-8"><input type="text" name="qty" id="qty" class="number integer" onchange="MsExFactory.calculate()"/></div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Amount</div>
                            <div class="col-sm-8"><input type="text" name="amount" id="amount" class="number integer" readonly/></div>
                        </div>
                    </code>
                </div>
            </div>
            <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsExFactory.submit()">Save</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('exfactoryFrm')" >Reset</a>
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsExFactory.remove()" >Delete</a>
            </div>
        </form>
    </div>
</div>
<!--========== Sales Order Search Window  ===============--->
<div id="salesSelcWindow" class="easyui-window" title="Sales Order Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:400px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="salesordersearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Style Ref</div>
                                <div class="col-sm-8">
                                    <input type="text" name="style_ref" id="style_ref" value="">
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Job </div>
                                <div class="col-sm-8">
                                    <input type="text" name="job_no" id="job_no" value="">
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsExFactory.showSalesOrderGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="salesordersearchTbl" style="width:610px">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="50">ID</th>
                        <th data-options="field:'sale_order_no'" width="100">Sales Order No.</th>
                        <th data-options="field:'style_ref'" width="100">Style Ref.</th>
                        <th data-options="field:'job_no'" width="100">Job NO.</th>
                        <th data-options="field:'file_no '" width="100">Reorder Level  </th>        
                        
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#salesSelcWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsExFactoryController.js"></script>
<script>
(function(){
    $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
    });
    $('.integer').keyup(function () {
            if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
            }
    });

})(jQuery);
</script>
