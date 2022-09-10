<div class="easyui-layout animated rollIn"  data-options="fit:true">
<div data-options="region:'center',border:true,title:'Shipment Pending'" style="padding:2px">
<table id="pendingShipmentTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'company_code',halign:'center'" width="80">Beneficiary</th>
        <th data-options="field:'produced_company_code',halign:'center'" width="60">Producing. <br/>Unit</th>
         <th data-options="field:'buyer_name',halign:'center'" width="60">Buyer</th>
         <th data-options="field:'style_ref',halign:'center'" width="80">Style No</th>
        <th data-options="field:'sale_order_receive_date',halign:'center'" width="80">Order <br/>Recv Date</th>
        <th data-options="field:'ship_date',halign:'center'" width="80">Delivery Date</th>
        <th data-options="field:'lead_days',halign:'center'" width="80" align="center">Lead Days</th>
         <th data-options="field:'delay_days',halign:'center'" width="80" align="center">Delay Days</th>
        <th data-options="field:'team_member_name',halign:'center'" width="100">Dealing <br/>Merchant</th>
        <th data-options="field:'season_name',halign:'center'" width="80">Season</th>
        <th data-options="field:'sale_order_no',halign:'center'" width="80">Order No</th>
        <th data-options="field:'department_name',halign:'center'" width="80">Prod. Dept</th>
        
        <th data-options="field:'flie_src',halign:'center',align:'center'" formatter="MsPendingShipment.formatimage" width="30">Image</th>
        <th data-options="field:'qty',halign:'center'" nowrap="false"   width="80" align="right">Order Qty <br/>(Pcs)</th>
        <th data-options="field:'rate',halign:'center'" width="60" align="right">Price <br/>(Pcs)</th>
        <th data-options="field:'amount',halign:'center'" width="80" align="right">Selling Value</th>
        
        <th data-options="field:'carton_qty',halign:'center'"  width="100" align="right">Carton Qty (Pcs)</th>
        <th data-options="field:'ship_qty',halign:'center'"  width="100" align="right">Shipout (Pcs) 
        </th>
        <th data-options="field:'ship_value',halign:'center'"  width="100" align="right">Shipout Value
        </th>
        <th data-options="field:'ship_balance',halign:'center'"  width="100" align="right">Shipout Balance <br/> (Pcs)
        </th>
        <th data-options="field:'ship_balance_value',halign:'center'"  width="100" align="right">Shipout Balance <br/>Value
        </th>
        <th data-options="field:'remarks',halign:'center'" width="750" align="left">Remarks</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'west',border:true,title:'Search',footer:'#ft3'" style="width:350px; padding:2px">
<form id="pendingShipmentFrm">
    <div id="container">
         <div id="body">
           <code>
                <div class="row middle">
                    <div class="col-sm-4">As at </div>
                    <div class="col-sm-4" style="padding-right:0px; display: none ">
                    <input type="text" name="pending_date_from" id="pending_date_from" class="datepicker" placeholder="From" value=""/>
                    </div>
                    <div class="col-sm-4" style="padding-left:0px">
                    <input type="text" name="pending_date_to" id="pending_date_to" class="datepicker"  placeholder="To" value="<?php echo date('Y-m-d'); ?>" />
                    </div>
                </div>
          </code>
       </div>
    </div>
    <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPendingShipment.get()">Show</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPendingShipment.resetForm('pendingShipmentFrm')" >Reset</a>
    </div>

  </form>
</div>
</div>
    <script type="text/javascript" src="<?php echo url('/');?>/js/report/Dashbord/MsPendingShipmentController.js"></script>
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
