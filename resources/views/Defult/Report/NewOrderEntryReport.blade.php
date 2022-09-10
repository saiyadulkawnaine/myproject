<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true" style="padding:2px">
        <table id="neworderentryreportTbl" title="New Order Entry" style="width:100%">
            <thead>
                <tr>
                    <th data-options="field:'company_code',halign:'center'" width="60">Company</th>
                    <th data-options="field:'produced_company_code',halign:'center'" width="60">Producing <br/> Unit</th>
                    <th data-options="field:'team_name',halign:'center'" width="80" formatter="MsNewOrderEntryReport.formatteamleader">Team <br/>Leader</th>
                     
                    <th data-options="field:'sale_order_entry_date',halign:'center'" width="80">Order <br/>Entry Date</th> 
                    <th data-options="field:'sale_order_entry_time',halign:'center'" width="80">Order <br/>Entry Time</th> 
                    <th data-options="field:'sale_order_receive_date',halign:'center'" width="80">Order <br/>Recv Date</th>
                    <th data-options="field:'delivery_date',halign:'center'" width="80">Delivery Date</th>
                    <th data-options="field:'lead_time',halign:'center'" width="60">Lead Time</th>
                    <th data-options="field:'team_member_name',halign:'center'" width="100" formatter="MsNewOrderEntryReport.formatdlmerchant">Dealing <br/>Merchant</th>
                    <th data-options="field:'delivery_month',halign:'center'" width="60">Delv <br/>Month</th>
                    <th data-options="field:'buyer_name',halign:'center'" width="60">Buyer</th>
                    <th data-options="field:'buying_agent_name',halign:'center'" width="70" formatter="MsNewOrderEntryReport.formatbuyingAgent">Buying House</th>
                    <th data-options="field:'style_ref',halign:'center'" width="80" formatter="MsNewOrderEntryReport.formatopfiles">Style No</th>

                    <th data-options="field:'sale_order_no',halign:'center'" width="80">Order No</th>
                    <th data-options="field:'lc_sc_no',halign:'center'" width="80" formatter="MsNewOrderEntryReport.formatlcsc">LC/SC Receive</th>
                    <th data-options="field:'department_name',halign:'center'" width="80">Prod. Dept</th>
                
                    <th data-options="field:'flie_src',halign:'center',align:'center'" formatter="MsNewOrderEntryReport.formatimage" width="30">Image</th>
                    <th data-options="field:'qty',halign:'center'" nowrap="false" width="80" align="right" formatter="MsNewOrderEntryReport.formatorderqty">Order Qty <br/>(Pcs)</th>
                    <th data-options="field:'rate',halign:'center'" width="100" align="right">Price (Pcs)</th>
                    <th data-options="field:'amount',halign:'center'" width="100" align="right" formatter="MsNewOrderEntryReport.formatorderqty">Selling Value</th>
                    <th data-options="field:'order_created_by',halign:'center'" align="right" width="100">Entered By</th>
                    <th data-options="field:'sale_order_id',halign:'center'" align="right" width="100">ID</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="neworderentryreportFrm">
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
                                <input type="text" name="style_ref" id="style_ref" onDblClick="MsNewOrderEntryReport.openOrdStyleWindow()" placeholder=" Double Click" readonly />
                                <input type="hidden" name="style_id" id="style_id" value=""/>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Dl.Marchant</div>
                            <div class="col-sm-8">
                                <input type="text" name="team_member_name" id="team_member_name" onDblClick="MsNewOrderEntryReport.openTeammemberDlmWindow()" placeholder=" Double Click" readonly/>
                                <input type="hidden" name="factory_merchant_id" id="factory_merchant_id" value=""/>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Entry Date</div>
                            <div class="col-sm-4" style="padding-right:0px">
                                <input type="text" name="entry_date_from" id="entry_date_from" class="datepicker" placeholder="From" />
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="entry_date_to" id="entry_date_to" class="datepicker"  placeholder="To" />
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
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" plain="true" id="save" onClick="MsNewOrderEntryReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsNewOrderEntryReport.resetForm('neworderentryreportFrm')" >Reset</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" plain="true" id="save" onClick="MsNewOrderEntryReport.showExcel('neworderentryreportTbl','neworderentryreportTbl')">XLS</a>
            </div>
        </form>
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
{{-- Buying Agent --}}
<div id="buyagentwindow" class="easyui-window" title="Buying Agents Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="buyagentTbl" style="width:500px">
        <thead>
            <tr>
                <th data-options="field:'id'" width="40">ID</th>
                <th data-options="field:'buyer_name'" width="180px">Buyer Name </th>
                <th data-options="field:'branch_name'" width="180px">Branch Name</th>
                <th data-options="field:'contact_person'" width="180px">Contact Person</th>
                <th data-options="field:'email'" width="180px">Email</th>
                <th data-options="field:'designation'" width="180px">Designation</th>
                <th data-options="field:'address'" width="250px">Address</th>
            </tr>
        </thead>
    </table>
</div>

<div id="oplcscwindow" class="easyui-window" title="Dyed Yarn Received" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="oplcscTbl" style="width:500px">
        <thead>
            <tr>
                <th data-options="field:'buyer_name'" width="100px">Buyer </th>
                <th data-options="field:'lc_sc_no'" width="100px">LC/SC No </th>
                <th data-options="field:'lc_sc_date'" width="100px">LC/SC Date </th>
                <th data-options="field:'lc_sc_value'" width="100px" align="right">LC Value </th>
                <th data-options="field:'currency_code'" width="100px">Currency </th>
                <th data-options="field:'file_no'" width="100px">File Number </th>
                <th data-options="field:'lc_nature'" width="100px">LC Nature </th>
                <th data-options="field:'pay_term'" width="100px">Pay Term </th>
                <th data-options="field:'inco_term'" width="130px">Inco Term </th>
                <th data-options="field:'remarks'" width="100px">Remarks</th>
            </tr>
        </thead>
    </table>
</div>

{{-- File Src --}}
<div id="opfilesrcwindow" class="easyui-window" title="Style Uploaded Files" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="opfilesrcTbl" style="width:500px">
        <thead>
            <tr>
                <th data-options="field:'id'" width="30">ID</th>
                <th data-options="field:'file_src'" width="180px">File Source </th>
                <th data-options="field:'original_name'" formatter="MsNewOrderEntryReport.formatShowOpFile" width="250px">Original Name</th>
            </tr>
        </thead>
    </table>
</div>

<div id="neworderImageWindow" class="easyui-window" title="Image" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:500px;height:500px;padding:2px;">
    <img id="neworderImageWindowoutput" src=""/>
</div>

<div id="oporderqtywindow" class="easyui-window" title="Order Qty" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="oporderqtyTbl" style="width:500px">
        <thead>
            <tr>
            <th data-options="field:'buyer_name',halign:'center'" width="150">Buyer</th>
            <th data-options="field:'style_ref',halign:'center'" width="100">Style No</th>
            <th data-options="field:'sale_order_no',halign:'center'" width="100">Order No</th>
            <th data-options="field:'department_name',halign:'center'" width="80">Prod. Dept</th>
            <th data-options="field:'item_description',halign:'center'" width="80">GMT. Item</th>
            <th data-options="field:'item_complexity',halign:'center'" width="80">Item Complexity</th>
            <th data-options="field:'country_name',halign:'center'" width="80">Country</th>
            <th data-options="field:'color_name',halign:'center'" width="80">GMT. Color</th>
            <th data-options="field:'size_name',halign:'center'" width="60">GMT. Size</th>

            <th data-options="field:'qty',halign:'center'" nowrap="false" width="80" align="right">Order Qty <br/>(Pcs)</th>
            <th data-options="field:'rate',halign:'center'" width="100" align="right">Price (Pcs)</th>
            <th data-options="field:'amount',halign:'center'" width="100" align="right">Selling Value</th>
            <th data-options="field:'smv',halign:'center'" width="60" align="right">SMV</th>
            <th data-options="field:'booked_minute',halign:'center'" width="80" align="right">Minute Booked</th>
            </tr>
        </thead>
    </table>
</div>

{{-- Style Filtering Search Window --}}
<div id="ordstyleWindow" class="easyui-window" title="Style Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1200px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'north',split:true, title:'Search'" style="height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="ordstylesearchFrm">
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
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsNewOrderEntryReport.searchOrdStyleGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="ordstylesearchTbl" style="width:100%">
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
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#ordstyleWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
{{-- Dealing Merchant / Teammember Search Window --}}
<div id="teammemberDlmWindow" class="easyui-window" title="Dealing Merchant Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="teammemberdlmFrm">
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
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsNewOrderEntryReport.searchTeammemberDlmGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="teammemberdlmTbl" style="width:100%">
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
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#teammemberDlmWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/report/MsNewOrderEntryReportController.js"></script>
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
        $('#neworderentryreportFrm [id="buyer_id"]').combobox();
        $('#ordstylesearchFrm [id="buyer_id"]').combobox();
    })(jQuery);
</script>