<div class="easyui-layout animated rollIn" data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Accessories Progress Report'" style="padding:2px">
        <table id="trimsorderprogressreportTbl" style="width:100%">
            <thead>
                <tr>
                    <th data-options="field:'company_code'" width="60">Bnf <br/>Company</th>
                    <th data-options="field:'produced_company_code'" width="60">Producing<br/>Company</th>
                    <th data-options="field:'buyer_code'" width="70">Buyer</th>
                    <th data-options="field:'style_ref'" width="100">Style Ref</th>
                    <th data-options="field:'team_member_name',halign:'center'" width="100">Dealing <br/>Merchant</th>
                    <th data-options="field:'ship_date'" width="100">Order<br/>Ship Date</th>
                    <th data-options="field:'sale_order_no'" width="100">Sales <br/>Order No</th>
                    <th data-options="field:'itemclass_name'" width="150">Item Class</th>
                    <th data-options="field:'bom_qty'" width="80" align="right">BOM Qty</th>
                    <th data-options="field:'uom_code'" width="70">Uom</th>
                    <th data-options="field:'bom_rate'" width="80" align="right">BOM Rate</th>
                    <th data-options="field:'bom_amount'" width="80" align="right">BOM Amount</th>
                    <th data-options="field:'po_qty'" width="80" align="right" formatter="MsTrimsOrderProgressReport.formatpotrimqty">PO Qty</th>
                    <th data-options="field:'po_rate'" width="80" align="right">PO Rate</th>
                    <th data-options="field:'po_amount'" width="80" align="right">PO Amount</th>
                    <th data-options="field:'bal_po_qty'" width="80" align="right">Balance<br/>PO Qty</th>
                    <th data-options="field:'bal_po_amount'" width="80" align="right">Balance<br/> PO Amount</th>
                    <th data-options="field:'rcv_qty'" width="80" align="right" formatter="MsTrimsOrderProgressReport.formatrcvtrimqty">Rcv Qty</th>
                    <th data-options="field:'rcv_amount'" width="80" align="right">Rcv Amount</th>
                    <th data-options="field:'bal_rcv_qty'" width="80" align="right">Balance<br/> Rcv Qty</th>
                    <th data-options="field:'bal_rcv_amount'" width="80" align="right">Balance<br/> Rcv Amount</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="trimsorderprogressreportFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row">
                            <div class="col-sm-4">Buyer </div>
                            <div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}</div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4"> Company</div>
                            <div class="col-sm-8">
                                {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                            </div>
                        </div> 
                        <div class="row middle">
                            <div class="col-sm-4">Pro. Company</div>
                            <div class="col-sm-8">
                                {!! Form::select('produced_company_id', $company,'',array('id'=>'produced_company_id')) !!}
                            </div>
                        </div>    
                        <div class="row middle">
                            <div class="col-sm-4">Style Ref</div>
                            <div class="col-sm-8">
                                <input type="text" name="style_ref" id="style_ref" onDblClick="MsTrimsOrderProgressReport.openTrimsReportStyleWindow()" placeholder=" Double Click" readonly />
                                <input type="hidden" name="style_id" id="style_id" value=""/>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Dl.Marchant</div>
                            <div class="col-sm-8">
                                <input type="text" name="team_member_name" id="team_member_name" onDblClick="MsTrimsOrderProgressReport.openTrimsReportTeammemberDlmWindow()" placeholder=" Double Click" readonly/>
                                <input type="hidden" name="factory_merchant_id" id="factory_merchant_id" value=""/>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Ship Date </div>
                            <div class="col-sm-4" style="padding-right:0px">
                                <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Receive Date </div>
                            <div class="col-sm-4" style="padding-right:0px">
                                <input type="text" name="receive_date_from" id="receive_date_from" class="datepicker" placeholder="From" />
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="receive_date_to" id="receive_date_to" class="datepicker"  placeholder="To" />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Order By </div>
                            <div class="col-sm-8">{!! Form::select('sort_by', $sortby,'1',array('id'=>'sort_by')) !!}</div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Status </div>
                            <div class="col-sm-8">{!! Form::select('order_status', $status,'1',array('id'=>'order_status')) !!}</div>
                        </div>
                    </code>
                </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsTrimsOrderProgressReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsTrimsOrderProgressReport.resetForm('trimsorderprogressreportFrm')" >Reset</a>
            </div>
        </form>
    </div>
</div>


{{-- Style Filtering Search Window --}}
<div id="trimsreportstyleWindow" class="easyui-window" title="Style Window Acccc" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1200px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'north',split:true, title:'Search'" style="height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="trimsreportstylesearchFrm">
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
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsTrimsOrderProgressReport.searchTrimsReportStyle()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="trimsreportstylesearchTbl" style="width:100%">
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
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#trimsreportstyleWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
{{-- Dealing Merchant / Teammember Search Window --}}
<div id="trimsreportteammemberDlmWindow" class="easyui-window" title="Dealing Merchant Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="trimsreportteammemberdlmFrm">
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Team</div>
                                <div class="col-sm-8">
                                    {!! Form::select('team_id', $team,'',array('id'=>'team_id')) !!}
                                </div>
                            </div> 
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsTrimsOrderProgressReport.searchTrimsTeammemberDlmGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="trimsreportteammemberdlmTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'team_name'" width="120">Team</th>
                        <th data-options="field:'dlm_name'" width="120">Dealing Marchant</th>
                        <th data-options="field:'type_id'" width="130px">Member Type</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#trimsreportteammemberDlmWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>

{{-- PO Trim Qty Pop UP --}}
<div id="openpotrimqtywindow" class="easyui-window" title="Accessories PO Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="potrimqtydtlTbl" style="width:500px">
        <thead>
            <tr>
                <th data-options="field:'id'" width="40">ID</th>
                <th data-options="field:'po_no'" width="80">PO No</th>
                <th data-options="field:'po_date'" width="80">PO Date</th>
                <th data-options="field:'company_code'" width="60"  align="center">Company</th>
                <th data-options="field:'supplier_name'" width="160">Supplier</th>
                <th data-options="field:'pi_no'" width="160">PI No</th>
                <th data-options="field:'pi_date'" width="80">PI Date</th>
                <th data-options="field:'source'" width="70" align="center">Source</th>
                <th data-options="field:'po_qty'" width="100" align="right">Po Qty</th>
                <th data-options="field:'po_amount'" width="100" align="right">Po Amount</th>
                <th data-options="field:'exch_rate'" width="80" align="right">Conv.Rate</th>
                <th data-options="field:'delv_start_date'" width="100" align="center">Delivery Start</th>
                <th data-options="field:'delv_end_date'" width="100" align="center">Delivery End</th>
                <th data-options="field:'paymode'" width="120">Pay Mode</th>
                <th data-options="field:'approved'" width="80">Approval</th>
                <th data-options="field:'remarks'" width="200">Remarks</th>
            </tr>
        </thead>
    </table>
</div>

{{-- Received Trim Qty Pop UP --}}
<div id="openrcvtrimqtywindow" class="easyui-window" title="Accessories MRR Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="rcvtrimqtydtlTbl" style="width:500px">
        <thead>
            <tr>
                <th data-options="field:'inv_trim_rcv_id'" width="40">ID</th>
                <th data-options="field:'company_code'" width="100">Company</th>
                <th data-options="field:'supplier_name'" width="100">Supplier</th>
                <th data-options="field:'receive_no'" width="100">Receive No</th>
                <th data-options="field:'receive_basis_id'" width="100">Receive Basis</th>
                <th data-options="field:'challan_no'" width="80">Challan No</th>
                <th data-options="field:'receive_date'" width="80">Receive Date</th>
                <th data-options="field:'rcv_qty'" width="100" align="right">Rcv Qty</th>
                <th data-options="field:'rcv_rate'" width="100" align="right">Rcv Rate</th>
                <th data-options="field:'rcv_amount'" width="100" align="right">Rcv Amount</th>
                <th data-options="field:'sale_order_no'" width="100">Order No</th>
                <th data-options="field:'class_name'" width="100">Item Class</th>
                <th data-options="field:'description'" width="100">Description</th>
            </tr>
        </thead>
    </table>
</div>
 
<script type="text/javascript" src="<?php echo url('/');?>/js/report/ItemBank/MsTrimsOrderProgressReportController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });

$('#trimsorderprogressreportFrm [id="buyer_id"]').combobox();
$('#trimsreportstylesearchFrm [id="buyer_id"]').combobox();
</script>