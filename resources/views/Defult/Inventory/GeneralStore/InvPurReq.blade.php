<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="invreqtabs">
 <div title="Purchase Requisition Entry" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List',footer:'#invpurreqTblFt'" style="padding:2px">
    <table id="invpurreqTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id',styler:MsInvPurReq.approvedmsg" width="40">ID</th>
       <th data-options="field:'requisition_no'" width="100">Requisition NO</th>
       <th data-options="field:'company_id'" width="100">Company</th>
       <th data-options="field:'req_date'" width="100">Requisition Date</th>
       <th data-options="field:'pay_mode'" width="120">Pay Mode</th>
       <th data-options="field:'currency_id'" width="100">Currency</th>
       <th data-options="field:'location_id'" width="100">Location</th>
       <th data-options="field:'remarks'" width="120">Remarks</th>
       <th data-options="field:'ready_to_approve_id'" width="100">Ready to Approve</th>
      </tr>
     </thead>
    </table>
    <div id="invpurreqTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     Company: {!! Form::select('company_id', $company,'',array('id'=>'company_id','style'=>'width:
     100px;height: 23px'))
     !!}
     Requisition Date: <input type="text" name="date_from" id="date_from" class="datepicker"
      style="width: 100px ;height: 23px" />
     <input type="text" name="date_to" id="date_to" class="datepicker" style="width: 100px;height: 23px" />
     <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
      iconCls="icon-search" plain="true" id="save" onClick="MsInvPurReq.searchInvPurReq()">Show</a>
    </div>
   </div>
   <div data-options="region:'west',border:true,title:'Requisition Reference',footer:'#ft2'"
    style="width: 380px; padding:2px">
    <form id="invpurreqFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Requisition No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="requisition_no" id="requisition_no" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Location </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Req. Date </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="req_date" id="req_date" class="datepicker" placeholder="  yy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Pay Mode </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('pay_mode', $paymode,'',array('id'=>'pay_mode')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Currency </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('currency_id', $currency,'',array('id'=>'currency_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Delivery By </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="delivery_by" id="delivery_by" class="datepicker" placeholder="  yy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Demand By</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('demand_by_id', $user,'',array('id'=>'demand_by_id','style'=>'width:100%;border:2px;')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Price Varified By</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('price_verified_by_id', $user,'',array('id'=>'price_verified_by_id','style'=>'width:100%;border:2px;')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Job Done</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('job_done_id', $yesno,'',array('id'=>'job_done_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Completion Date </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="job_completion_date" id="job_completion_date" class="datepicker" placeholder="  yy-mm-dd" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks </div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks" cols="30" rows="5"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Ready To Approve</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('ready_to_approve_id', $yesno,0,array('id'=>'ready_to_approve_id')) !!}
                                    </div>
                                </div>
                            </code>
      </div>
     </div>
     <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsInvPurReq.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invpurreqFrm')">Reset</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsInvPurReq.remove()">Delete</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true"
       id="pdf" onClick="MsInvPurReq.pdf()">PR</a>
     </div>
    </form>
   </div>
  </div>
 </div>
 <!-----===============Requisition Item=============----------->
 <div title="Requisition Item Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div
    data-options="region:'west',border:true,title:'New Requisition Item',iconCls:'icon-more',footer:'#invPurReqItemft'"
    style="width:450px; padding:2px">
    <form id="invpurreqitemFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="inv_pur_req_id" id="inv_pur_req_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Item Description </div>
                                    <div class="col-sm-8">  
                                        <input type="text" name="item_description" id="item_description" placeholder="  Double Click" onDblClick="MsInvPurReqItem.openPurchaseItemWindow()" readonly />
                                        <input type="hidden" name="item_account_id" id="item_account_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Consuming Dept</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('department_id', $department,'',array('id'=>'department_id','style'=>'width:100%;border:2px;')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Req.Qty </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" onchange="MsInvPurReqItem.calculateAmount()" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">UOM </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="uom_code" id="uom_code" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Rate </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rate" id="rate" onchange="MsInvPurReqItem.calculateAmount()" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Amount </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount" id="amount" class="number integer" readonly/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Item Category </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('itemcategory_id', $itemcategory,'',array('id'=>'itemcategory_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Item Class </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('itemclass_id', $itemclass,'',array('id'=>'itemclass_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks </div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Stocks </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="" id="" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Re-Order Lavel </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="reorder_level" id="reorder_level" disabled />
                                    </div>
                                </div>
                            </code>
      </div>
     </div>
     <div id="invPurReqItemft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsInvPurReqItem.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsInvPurReqItem.resetForm()">Reset</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsInvPurReqItem.remove()">Delete</a>
     </div>
    </form>
   </div>
   <div data-options="region:'center',border:true,title:'Requisition Item Details'" style="padding:2px">
    <table id="invpurreqitemTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="40">ID</th>
       <th data-options="field:'item_description'" width="100">Item Description</th>
       <th data-options="field:'uom_code'" width="60">UOM</th>
       <th data-options="field:'qty',align:'right'" width="100"> Reqn. Qty</th>
       <th data-options="field:'rate',align:'right'" width="80">Rate</th>
       <th data-options="field:'amount',align:'right'" width="100">Amount</th>
       <th data-options="field:'remarks'" width="120">Remarks</th>
      </tr>
     </thead>
    </table>
   </div>
  </div>
 </div>
 <!-----=============== Asset Breakdown =============----------->
 <div title="Asset Breakdown ID" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div
    data-options="region:'west',border:true,title:'New Asset Breakdown ID',iconCls:'icon-more',footer:'#invpurreqbreakdownft'"
    style="width:450px; padding:2px">
    <form id="invpurreqassetbreakdownFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="inv_pur_req_id" id="inv_pur_req_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Asset Breakdown ID</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="asset_breakdown_id" id="asset_breakdown_id" ondblclick="MsInvPurReqAssetBreakdown.openAssetBreakdownWindow()"  placeholder=" Double Click"/> 
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Asset No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="custom_no" id="custom_no" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Breakdown Date</div>
                                    <div class="col-sm-3" style="padding-right:0px">
                                        <input type="text" name="breakdown_date" id="breakdown_date" value="" placeholder="date" class="datepicker" disabled />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                        <input type="text" name="breakdown_time" id="breakdown_time" value="" placeholder="hh:mm:ss am/pm" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Reason</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="reason_id" id="reason_id" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Remarks</div>
                                    <div class="col-sm-7">
                                       <input type="text" name="remarks" id="remarks" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Decision</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="decision_id" id="decision_id" disabled />
                                    </div>
                                </div>
                            </code>
      </div>
     </div>
     <div id="invpurreqbreakdownft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsInvPurReqAssetBreakdown.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invpurreqassetbreakdownFrm')">Reset</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsInvPurReqAssetBreakdown.remove()">Delete</a>
     </div>
    </form>
   </div>
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="invpurreqassetbreakdownTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="80">ID</th>
       <th data-options="field:'asset_breakdown_id'" width="100">AssetBreakdown ID</th>
       <th data-options="field:'custom_no'" width="80">Custom <br />Asset No</th>
       <th data-options="field:'asset_name'" width="250">Asset Name</th>
       <th data-options="field:'breakdown_date'" width="80">Breakdown<br /> Date</th>
       <th data-options="field:'breakdown_time'" width="80">Breakdown<br /> Time</th>
       <th data-options="field:'reason_id'" width="100">Reason</th>
       <th data-options="field:'decision_id'" width="130">Decision</th>
       <th data-options="field:'remarks'" width="150">Remarks</th>
       <th data-options="field:'function_date'" width="80">Recovery Date</th>
       <th data-options="field:'function_time'" width="80">Recovery Time</th>
       <th data-options="field:'action_taken'" width="150">Action Taken</th>
      </tr>
     </thead>
    </table>
   </div>
  </div>
 </div>
 <!-----=============== Paid To =============----------->
 <div title="Paid" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'west',border:true,title:'New Amount Paid',iconCls:'icon-more',footer:'#invpurreqpaidft'"
    style="width:450px; padding:2px">
    <form id="invpurreqpaidFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="inv_pur_req_id" id="inv_pur_req_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Date </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="paid_date" id="paid_date" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Amount </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount" id="amount" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Paid To </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('user_id', $user,'',array('id'=>'user_id','style'=>'width:100%;border-radius:2px;')) !!}
                                    </div>
                                </div>
                            </code>
      </div>
     </div>
     <div id="invpurreqpaidft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsInvPurReqPaid.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('invpurreqpaidFrm')">Reset</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsInvPurReqPaid.remove()">Delete</a>
     </div>
    </form>
   </div>
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="invpurreqpaidTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="40">ID</th>
       <th data-options="field:'paid_date'" width="100">Item Description</th>
       <th data-options="field:'amount',align:'right'" width="100">Amount</th>
       <th data-options="field:'user_name'" width="100">Paid To</th>
      </tr>
     </thead>
    </table>
   </div>
  </div>
 </div>
</div>
<!--------------------Item Search------------------->
<div id="newWindow" class="easyui-window" title="Item Window" data-options="modal:true,closed:true,iconCls:'icon-save'"
 style="width:100%;height:500px;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'west',split:true, title:'Search'" style="width:350px">
   <div class="easyui-layout" data-options="fit:true">
    <div id="body">
     <code>
                        <form id="itemsearchFrm">
                            <div class="row">
                                <div class="col-sm-4">Item Category</div>
                                <div class="col-sm-8">
                                    {!! Form::select('itemcategory_id', $itemcategory,'',array('id'=>'itemcategory_id')) !!}
                                    <input type="hidden" name="item_account_id" id="item_account_id" value="" />
                                </div>
                            </div>

                            <div class="row middle">
                                <div class="col-sm-4">Item Class </div>
                                <div class="col-sm-8">
                                    {!! Form::select('itemclass_id', $itemclass,'',array('id'=>'itemclass_id')) !!}
                                </div>
                            </div>
                        </form>
                    </code>
    </div>
    <p class="footer">
     <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px"
      onClick="MsInvPurReqItem.searchPurchaseItemGrid()">Search</a>
    </p>
   </div>
  </div>
  <div data-options="region:'center'" style="padding:10px;">
   <table id="itemsearchTbl" style="width:700px">
    <thead>
     <tr>
      <th data-options="field:'id'" width="50">ID</th>
      <th data-options="field:'sub_class_name'" width="100">Sub Class Name</th>
      <th data-options="field:'sub_class_code'" width="100">Sub Class Code</th>
      <th data-options="field:'name'" width="100">Category</th>
      <th data-options="field:'class_name'" width="100">Item Class</th>
      <th data-options="field:'item_description'" width="150">Item Description</th>
      <th data-options="field:'specification'" width="100">Specification</th>
      <th data-options="field:'uom_code'" width="60">UOM</th>
      <th data-options="field:'reorder_level '" width="100">Reorder Level </th>

     </tr>
    </thead>
   </table>
  </div>
  <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
   <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
    onclick="$('#newWindow').window('close')" style="width:80px">Close</a>
  </div>
 </div>
</div>
<!----Asset Breakdown Search Window------>
{{-- Fixed Asset Search Window --}}
<div id="openassetbreakdownwindow" class="easyui-window" title="Asset Details Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',border:true,footer:'#assetdtlft'" style="padding:2px">
   <table id="assetbreakdownsearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="40">ID</th>
      <th data-options="field:'custom_no'" width="80">Custom <br />Asset No</th>
      <th data-options="field:'asset_name'" width="250">Asset Name</th>
      <th data-options="field:'prod_capacity'" width="70">Capacity</th>
      <th data-options="field:'breakdown_date'" width="80">Breakdown<br /> Date</th>
      <th data-options="field:'breakdown_time'" width="80">Breakdown<br /> Time</th>
      <th data-options="field:'reason_id'" width="100">Reason</th>
      <th data-options="field:'decision_id'" width="130">Decision</th>
      <th data-options="field:'employee_name'" width="100">Custody Of</th>
      <th data-options="field:'remarks'" width="150">Remarks</th>
      <th data-options="field:'function_date'" width="80">Recovery Date</th>
      <th data-options="field:'function_time'" width="80">Recovery Time</th>
      <th data-options="field:'action_taken'" width="150">Action Taken</th>
     </tr>
    </thead>
   </table>
   <div id="assetdtlft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
     onclick="$('#openassetbreakdownwindow').window('close')" style="width:80px">Close</a>
   </div>
  </div>
  <div data-options="region:'west',border:true,footer:'#assetdtlFrmft'" style="padding:2px; width:350px">
   <form id="assetbreakdownsearchFrm">
    <div id="container">
     <div id="body">
      <code>
                            <div class="row middle">
                                <div class="col-sm-4">Custom No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="custom_no" id="custom_no" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Asset Name</div>
                                <div class="col-sm-8">
                                    <input type="text" name="name" id="name" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Breakdown Date</div>
                                <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="breakdown_date_from" id="breakdown_date_from" value="" class="datepicker"/>
                                </div>
                                <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="breakdown_date_to" id="breakdown_date_to" value="" class="datepicker"/>
                                </div>
                            </div>
                        </code>
     </div>
    </div>
    <div id="assetdtlFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true"
      onClick="MsInvPurReqAssetBreakdown.searchAssetBreakdown()">Search</a>
    </div>
   </form>
  </div>
 </div>
</div>
<!--------------------------------------->
<script type="text/javascript" src="<?php echo url('/');?>/js/Inventory/GeneralStore/MsAllInventoryController.js">
</script>

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
    $('#invpurreqFrm [id="demand_by_id"]').combobox();
    $('#invpurreqFrm [id="price_verified_by_id"]').combobox();
    $('#invpurreqitemFrm [id="department_id"]').combobox();
    $('#invpurreqpaidFrm [id="user_id"]').combobox();

})(jQuery);
</script>

</script>