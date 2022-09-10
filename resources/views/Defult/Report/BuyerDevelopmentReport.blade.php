<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="buyerdevelopmentrptTbl" style="width:100%">
            <thead>    
                <tr>
                    <th data-options="field:'status_id',halign:'center'"  width="70">Status</th>
                    <th data-options="field:'team_name',halign:'center'" width="80">Team</th>
                    <th data-options="field:'buyer_name',halign:'center'" width="200" formatter="MsBuyerDevelopmentReport.formatbuy">Buyer</th>
                    <th data-options="field:'buyer_house_id',halign:'center'"  width="200" formatter="MsBuyerDevelopmentReport.formatintm">Buying House</th>
                    <th data-options="field:'product_type_id',halign:'center'"  width="70">Product Type</th>
                    <th data-options="field:'end_user_market',halign:'center'"  width="70">End User Market</th>
                    <th data-options="field:'existing_supplier',halign:'center'"  width="70">Existing Supplier</th>
                    <th data-options="field:'credit_rating',halign:'center'"  width="70">Credit Rating</th>
                    <th data-options="field:'credit_type_id',halign:'center'"  width="70">Credit Type</th>
                    <th data-options="field:'pay_term_id',halign:'center'"  width="70">Pay Term</th>
                    <th data-options="field:'penalty_clause',halign:'center'"  width="70">Penalty Clause</th>
                    <th data-options="field:'compliance_req',halign:'center'"  width="70">Compliance Req.</th>
                    <th data-options="field:'meeting_summary',halign:'center'"  width="250" formatter="MsBuyerDevelopmentReport.formatevent">Last Meeting Summary</th>
                    <th data-options="field:'next_action_plan',halign:'center'"  width="250" formatter="MsBuyerDevelopmentReport.formatevent">Next Action Plan</th>
                    <th data-options="field:'doc_upload',halign:'center'"  width="120" formatter="MsBuyerDevelopmentReport.formatdoc">Doc Uploaded</th>
                    <th data-options="field:'remarks',halign:'center'"  width="120">Remarks</th>
               </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#buyerdevelopmentrptFrmFt'" style="width:350px; padding:2px">
        <form id="buyerdevelopmentrptFrm">
            <div id="container">
                 <div id="body">
                   <code>
                        <div class="row middle">
                            <div class="col-sm-4">Ext.ShipDate </div>
                            <div class="col-sm-4" style="padding-right:0px">
                                <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                            </div>
                        </div>
                       	<div class="row">
                            <div class="col-sm-4">Buyer </div>
                            <div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}</div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Team </div>
                            <div class="col-sm-8">{!! Form::select('team_id', $team,'',array('id'=>'team_id')) !!}</div>
                        </div>
                        
                        <div class="row middle">
                            <div class="col-sm-4">Status </div>
                            <div class="col-sm-8">{!! Form::select('status_id', $buyerdlvstatus,'',array('id'=>'status_id')) !!}</div>
                        </div>
                  </code>
               </div>
            </div>
            <div id="buyerdevelopmentrptFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" plain="true" id="save" onClick="MsBuyerDevelopmentReport.getOrderForcasting()">Forcasting</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" plain="true" id="save" onClick="MsBuyerDevelopmentReport.get()">B.DEV</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsBuyerDevelopmentReport.resetForm('buyerdevelopmentrptFrm')" >Reset</a>
            </div>
      </form>
    </div>
</div>

<div id="buyerdevelopmentrpteventwindow" class="easyui-window" title="Events" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="buyerdevelopmentrpteventTbl" style="width:100%">
        <thead>
        <tr>
        <th data-options="field:'id'" width="40">ID</th>
        <th data-options="field:'meeting_type_id'" width="80">Meeting Type</th>
        <th data-options="field:'meeting_date'" width="80">Meeting Date</th>
        <th data-options="field:'meeting_summary'" width="250">Meeting Summary</th>
        <th data-options="field:'next_action_plan'" width="250">Next Action Plan</th>
        <th data-options="field:'remarks'" width="150">Remarks</th>
        </tr>
        </thead>
    </table>
</div>

<div id="buyerdevelopmentrptintmwindow" class="easyui-window" title="Buying House" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="buyerdevelopmentrptintmTbl" style="width:100%">
        <thead>
        <tr>
        <th data-options="field:'id'" width="40">ID</th>
        <th data-options="field:'buyer_name'" width="200" formatter="MsBuyerDevelopmentReport.formatcont">Buyer</th>
        
        <th data-options="field:'team_name'" width="100">Team</th>
        <th data-options="field:'contact_person'" width="150">Contact Person</th>
        <th data-options="field:'designation'" width="220">Designation</th>
        <th data-options="field:'email'" width="200">Email</th>
        <th data-options="field:'cell_no'" width="120">Cell No</th>
        <th data-options="field:'address'" width="450">Address</th>
        <th data-options="field:'code'" width="60">Code</th>
        <th data-options="field:'vendor_code'" width="60">Vendor Code</th>
        <th data-options="field:'company_name'" width="100">Company</th>
        <th data-options="field:'supplier_name'" width="100">Supplier</th>
        <th data-options="field:'buyinghouse_name'" width="100">Buying Agent</th>
        <th data-options="field:'teammember_name'" width="100">Factory Merchant</th>
        <th data-options="field:'sew_effin_percent'" width="100">Sew Effin Percent</th>
        
        </tr>
        </thead>
    </table>
</div>

<div id="buyerdevelopmentrptbuywindow" class="easyui-window" title="Buyer Profile" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="buyerdevelopmentrptbuyTbl" style="width:100%">
        <thead>
        <tr>
        <th data-options="field:'id'" width="40">ID</th>
        <th data-options="field:'buyer_name'" width="200" formatter="MsBuyerDevelopmentReport.formatcont">Buyer</th>
        
        <th data-options="field:'team_name'" width="100">Team</th>
        <th data-options="field:'contact_person'" width="150">Contact Person</th>
        <th data-options="field:'designation'" width="220">Designation</th>
        <th data-options="field:'email'" width="200">Email</th>
        <th data-options="field:'cell_no'" width="120">Cell No</th>
        <th data-options="field:'address'" width="450">Address</th>
        <th data-options="field:'code'" width="60">Code</th>
        <th data-options="field:'vendor_code'" width="60">Vendor Code</th>
        <th data-options="field:'company_name'" width="100">Company</th>
        <th data-options="field:'supplier_name'" width="100">Supplier</th>
        <th data-options="field:'buyinghouse_name'" width="100">Buying Agent</th>
        <th data-options="field:'teammember_name'" width="100">Factory Merchant</th>
        <th data-options="field:'sew_effin_percent'" width="100">Sew Effin Percent</th>
        
        </tr>
        </thead>
    </table>
</div>

<div id="buyerdevelopmentrptbuycontwindow" class="easyui-window" title="Contacts" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="buyerdevelopmentrptbuycontTbl" style="width:100%">
        <thead>
        <tr>
        <th data-options="field:'id'" width="40">ID</th>
        <th data-options="field:'buyer_name'" width="200">Buyer</th>
        <th data-options="field:'contact_person'" width="150">Contact Person</th>
        <th data-options="field:'country_name'" width="100">Country</th>
        <th data-options="field:'designation'" width="220">Designation</th>
        <th data-options="field:'email'" width="200">Email</th>
        <th data-options="field:'shipment_day'" width="100">Shipment Day</th>
        <th data-options="field:'address'" width="450">Address</th>
        </tr>
        </thead>
        </table>
</div>

<div id="buyerdevelopmentrptdocwindow" class="easyui-window" title="Documents" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="buyerdevelopmentrptdocTbl" style="width:100%">
        <thead>
            <tr>
                <th data-options="field:'id'" width="80">ID</th>
                <th data-options="field:'original_name'" width="80">Original Name</th>
                <th data-options="field:'file_src'" width="80" formatter="MsBuyerDevelopmentReport.formatfile">Upload Files</th>
            </tr>
        </thead>
    </table>
</div>

<div id="orderforcastingWindow" class="easyui-window" title="Order Forcasting Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="orderforcastingrptContainer" style="width:100%;height:100%;padding:0px;">

    </div>
</div>

<div id="buyerdevelopmentrptmktcostWindow" class="easyui-window" title="Events" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="buyerdevelopmentrptmktcostTbl" style="width:100%">
        <thead>
            <tr>
                <th data-options="field:'app'" width="60" formatter='MsBuyerDevelopmentReport.approveButton'></th>
                <th data-options="field:'pdf',halign:'center'" width="40" formatter="MsBuyerDevelopmentReport.formatpdf" align="center">Details</th>
                <th data-options="field:'id'" width="50">1<br>ID</th>
                <th data-options="field:'team_member',halign:'center'" width="70">2<br>Team <br/> Member</th>
                <th data-options="field:'buyer_name',halign:'center'" width="70">3<br>Buyer</th>
                <th data-options="field:'style_ref',halign:'center',styler:MsBuyerDevelopmentReport.styleformat" width="80">4<br>Style No</th>
                <th data-options="field:'style_description',halign:'center'" width="100">5<br>Style Desc.</th>
                <th data-options="field:'season_name',halign:'center'" width="80">6<br>Season</th>
                <th data-options="field:'department_name',halign:'center'" width="80">7<br>Prod. Dept</th>
                <th data-options="field:'flie_src',halign:'center',align:'center'" formatter="MsBuyerDevelopmentReport.formatimage" width="30">8<br>Image</th>
                <th data-options="field:'offer_qty',halign:'center'" width="80" align="right">9<br>Offered Qty</th>
                <th data-options="field:'uom_code',halign:'center'" width="50">10<br>UOM</th>
                <th data-options="field:'est_ship_date',halign:'center'" width="80">11<br>Est. Ship Date</th>
                <th data-options="field:'quot_date',halign:'center'" width="80">12<br> Costing date</th>
                <th data-options="field:'cost_per_pcs',halign:'center'" width="60" align="right">13<br>Cost/Pcs </th>
                <th data-options="field:'price',halign:'center',styler:MsBuyerDevelopmentReport.quotedprice" width="60" align="right">14<br>Quote <br/> Price /Pcs</th>
                <th data-options="field:'comments',halign:'center'" width="100">15<br>Comments</th>
                <th data-options="field:'status',halign:'center'" width="70">16<br>Status</th>


                <th data-options="field:'cm',halign:'center'" width="60" align="right">17<br>CM/Dzn</th>
                <th data-options="field:'fab_amount',halign:'center'" width="60" align="right">18<br>Fabric <br/>Cost /Dzn</th>
                <th data-options="field:'yarn_amount',halign:'center'" width="60" align="right">19<br>Yarn Cost/ <br/> Dzn</th>
                <th data-options="field:'prod_amount',halign:'center'" width="60" align="right">20<br>Fabric Prod. <br/> Cost /Dzn</th>
                <th data-options="field:'trim_amount',halign:'center'" width="40" align="right">21<br>Trims <br/> Cost /Dzn</th>
                <th data-options="field:'emb_amount',halign:'center'" width="60" align="right">22<br>Embel. <br/>Cost /Dzn</th>
                <th data-options="field:'cm_amount',halign:'center'" width="60" align="right">23<br>CM Cost / <br/>Dzn</th>
                <th data-options="field:'other_amount',halign:'center'" width="60" align="right">24<br>Other Cost / <br/>Dzn</th>
                <th data-options="field:'commercial_amount',halign:'center'" width="60" align="right">25<br>Commercial <br/> Cost /Dzn</th>
                <th data-options="field:'commission_on_quoted_price_dzn',halign:'center'" width="80" align="right">26<br>Comm. On <br/> Quoted  Price /Dzn </th>
                <th data-options="field:'total_cost',halign:'center'" width="60" align="right">27<br>Total Cost <br/> /Dzn</th>
                <th data-options="field:'remarks',halign:'center'" width="100">28<br>Remarks</th> 
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/report/MsBuyerDevelopmentReportController.js"></script>
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
    $('#buyerdevelopmentrptFrm [id="buyer_id"]').combobox();
</script>