<div class="easyui-layout animated rollIn"  data-options="fit:true" id="yarnprocurementPanel">
    <div data-options="region:'center',border:true,title:'Yarn Procurement Report'" style="padding:2px">
        <table id="yarnprocurementreportTbl" style="width:1890px">
            <thead>
                <tr>
                    <th data-options="field:'company_code',halign:'center'" width="60">1<br/>Bnf<br/>Company</th>
                    <th data-options="field:'produced_company_code',halign:'center'" width="80" align="center">2<br/>Prod <br/>Company</th>
                    <th data-options="field:'item_account_id',halign:'center'" width="80">3<br/>Yarn ID</th>
                    <th data-options="field:'yarn_des',halign:'center'" width="200">4<br/>Yarn Description</th>
                    <th data-options="field:'req_qty',halign:'center'" width="70"  align="right">5<br/>Req Qty</th>
                    <th data-options="field:'po_qty',halign:'center'" align="right" width="70" formatter="MsYarnProcurementReport.formatPoQty">6<br/>PO BD<br/> Qty</th>
                    <th data-options="field:'po_bal',halign:'center'" align="right" width="70">7<br/>Po Balance</th>
                    <th data-options="field:'issue_qty',halign:'center'" width="60">8<br/>Issue <br/>To Knit</th>
                    <th data-options="field:'issue_bal',halign:'center'" width="70">9<br/>Issue <br/>Balance</th>
                    <th data-options="field:'tna_start_date',halign:'center'" width="80">10<br/>Plan<br/>Issue start</th>
                    <th data-options="field:'tna_end_date',halign:'center'" width="80">11<br/>Plan<br/>Issue End</th>
                    <th data-options="field:'acl_start_date',halign:'center'" width="80" align="center">12<br/>Issue Started</th>
                    <th data-options="field:'acl_end_date',halign:'center'" width="80" align="center">13<br/>Issue Ended</th>
                    <th data-options="field:'team_member_name',halign:'center'" width="100" align="center"formatter="MsYarnProcurementReport.formatdlmerchant">14<br/>Dealing Marchant</th>
                    <th data-options="field:'buyer_name',halign:'center'" width="70" align="center">15<br/>Buyer</th>
                    <th data-options="field:'style_ref',halign:'center'" width="80" formatter="MsYarnProcurementReport.formatopfiles">16<br/>Style Ref</th>
                    <th data-options="field:'sale_order_no',halign:'center'" width="80">17<br/>Order No</th>
                    <th data-options="field:'sale_order_id',halign:'center'" width="80">18<br/>Order ID</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="yarnprocurementreportFrm">
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
                        <div class="row middle">
                            <div class="col-sm-4">Pro. Company</div>
                            <div class="col-sm-8">
                                {!! Form::select('produced_company_id', $company,'',array('id'=>'produced_company_id')) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">Buyer </div>
                            <div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}</div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Style Ref</div>
                            <div class="col-sm-8">
                                <input type="text" name="style_ref" id="style_ref" onDblClick="MsYarnProcurementReport.openStyleWindow()" placeholder=" Double Click" readonly />
                                <input type="hidden" name="style_id" id="style_id" value=""/>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Order</div>
                            <div class="col-sm-8">
                                <input type="text" name="sale_order_no" id="sale_order_no" onDblClick="MsYarnProcurementReport.openOrderWindow()" placeholder=" Double Click" readonly />
                                <input type="hidden" name="sales_order_id" id="sales_order_id" value=""/>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Status </div>
                            <div class="col-sm-8">{!! Form::select('order_status', $status,'1',array('id'=>'order_status')) !!}</div>
                        </div>
                    </code>
                </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsYarnProcurementReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsYarnProcurementReport.resetForm('yarnprocurementreportFrm')" >Reset</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onclick="MsYarnProcurementReport.showExcel()">Excel</a>
            </div>
      </form>
    </div>
    <div data-options="region:'east',border:true,collapsed:true,hideCollapsedContent:false,title:'Summary'" style="width:500px; padding:2px">
        <table id="yarnprocurementsummeryTbl" style="width:100%" toolbar="#summeryTblFt">
            <thead>
                <th data-options="field:'yarn_des',halign:'center'" width="200">1<br/>Yarn Description</th>
                <th data-options="field:'req_qty',halign:'center'" align="right" width="80">2<br/>Req Qty</th>
                <th data-options="field:'po_item_qty',halign:'center'" align="right" width="80">3<br/>PO Qty</th>
                <th data-options="field:'po_qty',halign:'center'" align="right" width="80">4<br/>PO BD Qty</th>
                <th data-options="field:'po_bal',halign:'center'" align="right" width="80">5<br/>PO <br/>Balance</th>
                <th data-options="field:'issue_qty',halign:'center'" align="right" width="80">6<br/>Issue<br/>to Knit <br/>Qty</th>
                <th data-options="field:'issue_bal',halign:'center'" align="right" width="80">7<br/>Issue Bal</th>
                <th data-options="field:'issue_per',halign:'center'" align="right" width="80">8<br/>Issue %</th>
            </thead>
        </table>
        <div id="summeryTblFt" >
            <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" onclick="MsYarnProcurementReport.getsummery()">Show</a>
        </div>
    </div>
</div>
{{-- Style Filtering Search Window --}}
<div id="openstyleWindow" class="easyui-window" title="Style Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1200px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'north',split:true, title:'Search'" style="height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="stylesearchFrm">
                            <div class="row">
                                <div class="col-sm-2">Buyer :</div>
                                <div class="col-sm-4">
                                {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                                </div>
                                    <div class="col-sm-2 req-text">Style Ref. </div>
                                <div class="col-sm-4"><input type="text" name="style_ref" id="style_ref" value=""/></div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-2">Style Des.  </div>
                                <div class="col-sm-4"><input type="text" name="style_description" id="style_description" value=""/></div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsYarnProcurementReport.searchStyle()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="stylesearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="50">ID</th>
                        <th data-options="field:'buyer'" width="70">Buyer</th>
                        <th data-options="field:'receivedate'" width="80">Receive Date</th>
                        <th data-options="field:'style_ref'" width="120">Style Refference</th>
                        <th data-options="field:'style_description'" width="150">Style Description</th>
                        <th data-options="field:'deptcategory'" width="80">Dept. Category</th>
                        <th data-options="field:'productdepartment'" width="80">Product Department</th>
                        <th data-options="field:'season'" width="80">Season</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#openstyleWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
{{-- Order window --}}
<div id="salesorderWindow" class="easyui-window" title="Sales Order No Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="yarnordersearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Style Ref</div>
                                <div class="col-sm-8">
                                    <input type="text" name="style_ref" id="style_ref" value="">
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Job No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="job_no" id="job_no" value="" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Sale Order No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="sale_order_no" id="sale_order_no" value="" />
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsYarnProcurementReport.searchOrder()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="yarnordersearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'sales_order_id'" width="40">ID</th>
                        <th data-options="field:'sale_order_no'" width="100">Sale Order No</th>
                        <th data-options="field:'company_id'" width="120">Beneficiary</th>
                        <th data-options="field:'buyer_name'" width="100">Buyer</th>
                        <th data-options="field:'job_no'" width="90">Job No</th>
                        <th data-options="field:'style_ref'" width="80">Style Ref</th>
                        <th data-options="field:'ship_date'" width="100">Ship Date</th> 
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#salesorderWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
{{-- Dealing Merchant --}}
<div id="dlmerchantWindow" class="easyui-window" title="Merchandiser Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="dealmctinfoTbl" style="width:100%;height:250px">
        <thead>
            <tr>
                <th data-options="field:'name'" width="120">Name</th>
                <th data-options="field:'date_of_join'" width="80px">Date Of Join </th>
                <th data-options="field:'last_education'" width="100px">Last Education</th>
                <th data-options="field:'contact'" width="100px">Contact</th>
                <th data-options="field:'experience'" width="100px">Experience</th>
                <th data-options="field:'address'" width="350px">Address</th>
            </tr>
        </thead>
    </table>
</div>
{{-- PO QTY --}}
<div id="poqtydtlWindow" class="easyui-window" title="YARN PO Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="poqtydtlTbl" style="width:100%;height:250px">
        <thead>
            <tr>
                <th data-options="field:'po_yarn_id'" width="50">ID</th>
                <th data-options="field:'po_no'" width="80px">PO NO</th>
                <th data-options="field:'po_date'" width="80px">PO Date</th>
                <th data-options="field:'supplier_name'" width="200px">Supplier</th>
                <th data-options="field:'pi_no'" width="150px">PI NO</th>
                <th data-options="field:'pi_date'" width="80px">PI Date</th>
                <th data-options="field:'po_qty'" width="80px">PO BD Qty</th>
                <th data-options="field:'po_amount'" width="80px">Amount</th>
                <th data-options="field:'remarks'" width="150px">Remarks</th>
                <th data-options="field:'lc_no'" width="150px">LC No</th>
            </tr>
        </thead>
    </table>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/report/Inventory/MsYarnProcurementReportController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
    $('#yarnprocurementreportFrm [id="buyer_id"]').combobox();
    $('#stylesearchFrm [id="buyer_id"]').combobox();
</script>