<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="poembserviceshortapprovaltabs">
 <div title="Waiting For Approval" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="poembserviceshortapprovalTbl">
     <thead>
      <tr>
       <th data-options="field:'app'" width="60" formatter='MsPoEmbServiceShortApproval.approveButton'></th>
       <th data-options="field:'id'" width="50">ID</th>
       <th data-options="field:'po_no'" formatter='MsPoEmbServiceShortApproval.formatsummery' width="100">PO No</th>
       <th data-options="field:'po_date'" width="80">WO Date</th>
       <th data-options="field:'company_code'" align="center" width="60">Producing<br />Company</th>
       <th data-options="field:'supplier_code'" align="center" width="60">Supplier</th>
       <th data-options="field:'paymode'" width="100">Pay Mode</th>
       <th data-options="field:'currency_code'" width="60" align="center">Currency</th>
       <th data-options="field:'exch_rate'" align="right" width="40">Exch<br />Rate</th>
       <th data-options="field:'amount'" width="70">Amount</th>
       <th data-options="field:'remarks'" width="150">Remarks</th>
       <th data-options="field:'pdf'" formatter='MsPoEmbServiceShortApproval.formatPdf' width="60">PDF</th>
      </tr>
     </thead>
    </table>
   </div>
   <div
    data-options="region:'west',border:true,title:'Sreach',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#poembserviceshortapprovalFrmFt'"
    style="width:350px; padding:2px">
    <div id="container">
     <div id="body">
      <code>
        <form id="poembserviceshortapprovalFrm">
           <div class="row middle">
            <div class="col-sm-4">Company</div>
            <div class="col-sm-8">
             {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
            </div>
           </div>
           <div class="row middle">
            <div class="col-sm-4">Supplier</div>
            <div class="col-sm-8">
             {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>"width:100%; border-raduis:2px")) !!}
            </div>
           </div>
           <div class="row middle">
            <div class="col-sm-4">Order Date</div>
            <div class="col-sm-4" style="padding-right:0px">
             <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
            </div>
            <div class="col-sm-4" style="padding-left:0px">
             <input type="text" name="date_to" id="date_to" class="datepicker" placeholder="To" />
            </div>
           </div>
        </form>
      </code>
     </div>
    </div>
    <div id="poembserviceshortapprovalFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
     <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
      iconCls="icon-save" plain="true" id="save" onClick="MsPoEmbServiceShortApproval.get()">Search</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('poembserviceshortapprovalFrm')">Reset</a>

    </div>
   </div>
  </div>
 </div>

 <div title="Approved List" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="poembserviceshortapprovedTbl">
     <thead>
      <tr>
       <th data-options="field:'unapp'" width="80" formatter='MsPoEmbServiceShortApproval.unapproveButton'></th>
       <th data-options="field:'id'" width="50">ID</th>
       <th data-options="field:'po_no'" formatter='MsPoEmbServiceShortApproval.formatsummery' width="100">PO No</th>
       <th data-options="field:'po_date'" width="80">WO Date</th>
       <th data-options="field:'company_code'" align="center" width="60">Producing<br />Company</th>
       <th data-options="field:'supplier_code'" align="center" width="60">Supplier</th>
       <th data-options="field:'paymode'" width="100">Pay Mode</th>
       <th data-options="field:'currency_code'" width="65" align="center">Currency</th>
       <th data-options="field:'exch_rate'" align="right" width="50">Exch<br />Rate</th>
       <th data-options="field:'amount'" width="100">Amount</th>
       <th data-options="field:'remarks'" width="150">Remarks</th>
       <th data-options="field:'lc_no'" width="200">LC</th>
       <th data-options="field:'pdf'" formatter='MsPoEmbServiceShortApproval.formatPdf' width="60">PDF</th>
      </tr>
     </thead>
    </table>
   </div>
   <div
    data-options="region:'west',border:true,title:'Sreach',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#poembserviceshortapprovedFrmFt'"
    style="width:350px; padding:2px">
    <div id="container">
     <div id="body">
      <code>
       <form id="poembserviceshortapprovedFrm">
         <div class="row middle">
          <div class="col-sm-4">Company</div>
          <div class="col-sm-8">
           {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
          </div>
         </div>
         <div class="row middle">
          <div class="col-sm-4">Supplier</div>
          <div class="col-sm-8">
           {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>"width:100%; border-raduis:2px")) !!}
          </div>
         </div>
         <div class="row middle">
          <div class="col-sm-4">Order Date</div>
          <div class="col-sm-4" style="padding-right:0px">
           <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
          </div>
          <div class="col-sm-4" style="padding-left:0px">
           <input type="text" name="date_to" id="date_to" class="datepicker" placeholder="To" />
          </div>
         </div>
      </form>
     </code>
     </div>
    </div>
    <div id="poembserviceshortapprovedFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
     <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
      iconCls="icon-save" plain="true" id="save" onClick="MsPoEmbServiceShortApproval.getApp()">Search</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('poembserviceshortapprovedFrm')">Reset</a>
    </div>
   </div>
  </div>
 </div>
</div>


<div id="poembserviceApprovalDetailWindow" class="easyui-window" title="Fabric Purchase Order Details"
 data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
 <div id="poembserviceApprovalDetailContainer"></div>
</div>

<div id="poembservicedetailWindow" class="easyui-window" title="Events"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <table id="poembservicedetailTbl" style="width:100%">
  <thead>
   <tr>
    <th data-options="field:'po_emb_service_id'" width="40">ID</th>
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

<script type="text/javascript" src="<?php echo url('/');?>/js/approval/MsPoEmbServiceShortApprovalController.js">
</script>
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
  $('#poembserviceshortapprovalFrm [id="supplier_id"]').combobox();
  $('#poembserviceshortapprovedFrm [id="supplier_id"]').combobox();

</script>