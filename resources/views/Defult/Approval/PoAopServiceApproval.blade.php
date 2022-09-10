<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="poaopserviceapprovaltabs">
 <div title="Waiting For Approval" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="poaopserviceapprovalTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'app'" width="60" formatter='MsPoAopServiceApproval.approveButton'>
       </th>
       <th data-options="field:'id'" width="80">ID</th>
       <th data-options="field:'po_no'" formatter='MsPoAopServiceApproval.formatsummery' width="80">WO No</th>
       <th data-options="field:'po_date'" width="80">WO Date</th>
       <th data-options="field:'po_type',styler:MsPoAopServiceApproval.formatpotype" width="60">PO
        Type</th>
       <th data-options="field:'company_code'" width="60" align="center">Company</th>
       <th data-options="field:'supplier_code'" width="150">Supplier</th>
       <th data-options="field:'pi_no'" width="160">PI No</th>
       <th data-options="field:'source'" width="50" align="center">Source</th>
       <th data-options="field:'amount'" width="80" align="right">Amount</th>
       <th data-options="field:'delv_start_date'" width="80" align="center">Delivery Start</th>
       <th data-options="field:'delv_end_date'" width="80" align="center">Delivery End</th>
       <th data-options="field:'paymode'" width="120">Pay Mode</th>
       <th data-options="field:'pdf'" width="60" formatter='MsPoAopServiceApproval.formatPdf'></th>
      </tr>
     </thead>
    </table>
   </div>
   <div
    data-options="region:'west',border:true,title:'Sreach',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#poaopserviceapprovalFrmFt'"
    style="width:350px; padding:2px">
    <div id="container">
     <div id="body">
      <code>
                            <form id="poaopserviceapprovalFrm">
                                <div class="row middle">
                                    <div class="col-sm-4">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Supplier</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Order Date</div>
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
    <div id="poaopserviceapprovalFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
     <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
      iconCls="icon-save" plain="true" id="save" onClick="MsPoAopServiceApproval.get()">Search</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('poaopserviceapprovalFrm')">Reset</a>

    </div>
   </div>
  </div>
 </div>

 <div title="Approved List" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="poaopserviceapprovedTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'app'" width="80" formatter='MsPoAopServiceApproval.unapproveButton'></th>
       <th data-options="field:'id'" width="80">ID</th>
       <th data-options="field:'po_no'" formatter='MsPoAopServiceApproval.formatsummery' width="80">WO No</th>
       <th data-options="field:'po_date'" width="80">WO Date</th>
       <th data-options="field:'company_code'" width="60" align="center">Company</th>
       <th data-options="field:'supplier_code'" width="150">Supplier</th>
       <th data-options="field:'pi_no'" width="160">PI No</th>
       <th data-options="field:'source'" width="50" align="center">Source</th>
       <th data-options="field:'amount'" width="80" align="right">Amount</th>
       <th data-options="field:'delv_start_date'" width="80" align="center">Delivery Start</th>
       <th data-options="field:'delv_end_date'" width="80" align="center">Delivery End</th>
       <th data-options="field:'paymode'" width="120">Pay Mode</th>
       <th data-options="field:'lc_no'" width="200">LC</th>
       <th data-options="field:'pdf'" width="60" formatter='MsPoAopServiceApproval.formatPdf'></th>
      </tr>
     </thead>
    </table>
   </div>
   <div
    data-options="region:'west',border:true,title:'Sreach',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#poaopserviceapprovedFrmFt'"
    style="width:350px; padding:2px">
    <div id="container">
     <div id="body">
      <code>
                            <form id="poaopserviceapprovedFrm">
                                <div class="row middle">
                                    <div class="col-sm-4">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Supplier</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Order Date</div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="app_date_from" id="app_date_from" class="datepicker" placeholder="From" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="app_date_to" id="app_date_to" class="datepicker"  placeholder="To" />
                                    </div>
                                </div>
                            </form>
                        </code>
     </div>
    </div>
    <div id="poaopserviceapprovedFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
     <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
      iconCls="icon-save" plain="true" id="save" onClick="MsPoAopServiceApproval.getApp()">Search</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('poaopserviceapprovedFrm')">Reset</a>
    </div>
   </div>
  </div>
 </div>
</div>

<div id="poaopserviceApprovalDetailWindow" class="easyui-window" title="Fabric Purchase Order Details"
 data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
 <div id="poaopserviceApprovalDetailContainer"></div>
</div>

<div id="poaopservicedetailWindow" class="easyui-window" title="Events"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <table id="poaopservicedetailTbl" style="width:100%">
  <thead>
   <tr>
    <th data-options="field:'po_aop_service_id'" width="40">ID</th>
    <th data-options="field:'po_no'" width="80">PO No</th>
    <th data-options="field:'po_date'" width="80">PO Date</th>
    <th data-options="field:'company_code'" width="60" align="center">Company</th>
    <th data-options="field:'supplier_name'" width="160">Supplier</th>
    <th data-options="field:'pi_no'" width="160">PI No</th>
    <th data-options="field:'pi_date'" width="80">PI Date</th>
    <th data-options="field:'source'" width="70" align="center">Source</th>
    <th data-options="field:'paymode'" width="100" align="center">Paymode</th>
    <th data-options="field:'po_qty',halign:'center'" width="50" align="right">PO Qty</th>
    <th data-options="field:'po_rate',halign:'center'" width="50" align="right">PO Rate</th>
    <th data-options="field:'po_amount',halign:'center'" width="50" align="right">PO Amount</th>
    <th data-options="field:'exch_rate'" width="80" align="right">Conv.Rate</th>
    <th data-options="field:'delv_start_date'" width="100" align="center">Delivery Start</th>
    <th data-options="field:'delv_end_date'" width="100" align="center">Delivery End</th>
   </tr>
  </thead>
 </table>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/approval/MsPoAopServiceApprovalController.js"></script>
<script>
 $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });

    $('.integer').keyup(function () {
      if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
      }
  });
  $('#poaopserviceFrm [id="supplier_id"]').combobox();

</script>