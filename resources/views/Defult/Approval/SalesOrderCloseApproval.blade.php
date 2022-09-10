<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="salesordercloseapprovaltabs">
 <div title="Waiting For Approval" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'Sales Order Close Approval List'" style="padding:2px">
    <table id="salesordercloseapprovalTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'app'" width="60" formatter='MsSalesOrderCloseApproval.approveButton'>Approval
       </th>
       {{-- <th data-options="field:'saleorderprogress'" width="100"
        formatter='MsSalesOrderCloseApproval.orderProgressButton'>Order
        Progress<br />Details</th> --}}
       <th data-options="field:'id'" width="40">ID</th>
       <th data-options="field:'job_no'" width="70">Job</th>
       <th data-options="field:'style_ref'" width="120">Style</th>
       <th data-options="field:'buyer_name'" width="180">Buyer</th>
       <th data-options="field:'sale_order_id'" width="80">SalesOrder ID</th>
       <th data-options="field:'sale_order_no'" width="100">SalesOrder No</th>
       <th data-options="field:'ship_date'" width="80">Ship Date</th>
       <th data-options="field:'qty'" width="70" align="right">Qty</th>
       <th data-options="field:'rate'" width="60" align="right">Rate</th>
       <th data-options="field:'amount'" width="80" align="right">Amount</th>
       <th data-options="field:'lead_time'" width="80" align="right">Lead Time</th>
       <th data-options="field:'place_date'" width="80">Place Date</th>
       <th data-options="field:'receive_date'" width="80">Receive Date</th>
       <th data-options="field:'produced_company_id'" width="80">Produced Company</th>
       <th data-options="field:'file_no'" width="70">File No</th>
       <th data-options="field:'remarks'" width="120">Remarks</th>
       <th data-options="field:'sale_order_id'" width="100">Sales Order ID</th>
      </tr>
     </thead>
    </table>
   </div>
   <div
    data-options="region:'west',border:true,title:'Search',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#salesordercloseapprovalFrmFt'"
    style="width:300px; padding:2px">
    <div id="container">
     <div id="body">
      <code>
                            <form id="salesordercloseapprovalFrm">
                                <div class="row middle">
                                    <div class="col-sm-4">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Ship Date</div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                                    </div>
                                </div>
                            </form>
                        </code>
     </div>
    </div>
    <div id="salesordercloseapprovalFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
     <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
      iconCls="icon-save" plain="true" id="save" onClick="MsSalesOrderCloseApproval.get()">Search</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('salesordercloseapprovalFrm')">Reset</a>
    </div>
   </div>
  </div>
 </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/approval/MsSalesOrderCloseApprovalController.js">
</script>
<script>
 $(".datepicker" ).datepicker({
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
    changeYear: true
});

</script>