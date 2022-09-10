<div class="easyui-layout animated rollIn"  data-options="fit:true">
        <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="todayShipmentTbl" style="width:100%">
            <thead>
                <tr>
                    <th data-options="field:'company_code',halign:'center'" width="80">Beneficiary</th>
                     <th data-options="field:'produced_company_code',halign:'center'" width="60">Producing. <br/>Unit</th>
                    <th data-options="field:'buyer',halign:'center'" width="60">Buyer</th>
                    <th data-options="field:'style_ref',halign:'center'" width="80">Style No</th>
                    <th data-options="field:'sale_order_receive_date',halign:'center'" width="80">Order <br/>Recv Date</th>
                    <th data-options="field:'delivery_date',halign:'center'" width="80">Delivery Date</th>
                    <th data-options="field:'teammember',halign:'center'" width="100">Dealing <br/>Merchant</th>
                    <th data-options="field:'season',halign:'center'" width="80">Season</th>
                    <th data-options="field:'sale_order_no',halign:'center'" width="80">Order No</th>
                    <th data-options="field:'productdepartment',halign:'center'" width="80">Prod. Dept</th>                  
                    <th data-options="field:'flie_src',halign:'center',align:'center'" formatter="MsTodayShipment.formatimage" width="30">Image</th>
                    <th data-options="field:'qty',halign:'center'" nowrap="false" formatter="MsTodayShipment.formatSaleOrder"   width="80" align="right">Order Qty <br/>(Pcs)</th>
                    <th data-options="field:'rate',halign:'center'" width="60" align="right">Price <br/>(Pcs)</th>
                    <th data-options="field:'amount',halign:'center'" width="80" align="right">Selling Value</th>
                    <th data-options="field:'remarks',halign:'center'" width="750" align="left">Remarks</th>
                </tr>
            </thead>
        </table>
        </div>
        <div data-options="region:'west',border:true,title:'Search',footer:'#todayShipmentFrmft'" style="width:350px; padding:2px">
            <form id="todayShipmentFrm">
                <div id="container">
                    <div id="body">
                    <code>
                            <div class="row middle">
                                <div class="col-sm-4">Ship Date </div>
                                <div class="col-sm-4" style="padding-right:0px">
                                <input type="text" name="today_date_from" id="today_date_from" class="datepicker" placeholder="From" value="<?php echo date('Y-m-d'); ?>"/>
                                </div>
                                <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="today_date_to" id="today_date_to" class="datepicker"  placeholder="To" value="<?php echo date('Y-m-d'); ?>" />
                                </div>
                            </div>
                    </code>
                </div>
                </div>
                <div id="todayShipmentFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsTodayShipment.get()">Show</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsTodayShipment.resetForm('todayShipmentFrm')" >Reset</a>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="<?php echo url('/');?>/js/report/Dashbord/MsTodayShipmentController.js"></script>
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
