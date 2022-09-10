<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="implcapprovaltabs">
 <div title="Waiting For Approval" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="implcapprovalTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'app'" width="80" formatter='MsImpLcApproval.approveButton'></th>
       <th data-options="field:'id'" width="80" formatter='MsImpLcApproval.formatpdf'>ID</th>
       <th data-options="field:'company'" width="60" align="center">Company</th>
       <th data-options="field:'file_no'" width="60" align="center">File No</th>
       <th data-options="field:'lc_no'" width="150">LC No</th>
       <th data-options="field:'lc_application_date'" width="90" align="center">Apply Date</th>
       <th data-options="field:'lc_date'" width="90" align="center">LC Date</th>
       <th data-options="field:'lc_amount'" width="100" align="right">Proposed LC Value</th>
       <th data-options="field:'fund_available'" width="100" align="right">Fund Available</th>
       <th data-options="field:'pay_term_id'" width="80">Pay Term</th>
       <th data-options="field:'supplier'" width="100">Supplier</th>
       <th data-options="field:'bankbranch'" width="120">Issuing Bank</th>
       <th data-options="field:'lc_type_id'" width="100">LC Type</th>
       <th data-options="field:'menu_id'" width="100">PO Type</th>
       <th data-options="field:'last_delivery_date'" width="90">Last Delivery Date</th>
       <th data-options="field:'expiry_date'" width="90" align="right">Expiry Date</th>
      </tr>
     </thead>
    </table>
   </div>
   <div
    data-options="region:'west',border:true,title:'Sreach',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#implcapprovalFrmFt'"
    style="width:350px; padding:2px">
    <div id="container">
     <div id="body">
      <code>
        <form id="implcapprovalFrm">
           <div class="row middle">
            <div class="col-sm-5">Company</div>
            <div class="col-sm-7">
             {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
            </div>
           </div>
           <div class="row middle">
            <div class="col-sm-5">Supplier</div>
            <div class="col-sm-7">
             {!! Form::select('supplier_id',
             $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
            </div>
           </div>
           <div class="row middle">
            <div class="col-sm-5">Purchase Order Type</div>
            <div class="col-sm-7">
             {!! Form::select('menu_id',
             $menu,'',array('id'=>'menu_id','style'=>'width: 100%; border-radius:2px')) !!}
            </div>
           </div>
           <div class="row middle">
            <div class="col-sm-5">LC To</div>
            <div class="col-sm-7">
             {!! Form::select('lc_to_id',
             $supplier,'',array('id'=>'lc_to_id','style'=>'width: 100%; border-radius:2px')) !!}
            </div>
           </div>
        </form>
      </code>
     </div>
    </div>
    <div id="implcapprovalFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
     <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
      iconCls="icon-save" plain="true" id="save" onClick="MsImpLcApproval.get()">Search</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('implcapprovalFrm')">Reset</a>
    </div>
   </div>
  </div>
 </div>
 <div title="Approved List" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="implcapprovedTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'unapp'" width="80" formatter='MsImpLcApproval.unapproveButton'></th>
       <th data-options="field:'id'" width="80">ID</th>
       <th data-options="field:'company'" width="60" align="center">Company</th>
       <th data-options="field:'file_no'" width="60" align="center">File No</th>
       <th data-options="field:'lc_no'" width="150">LC No</th>
       <th data-options="field:'lc_application_date'" width="90" align="center">Apply Date</th>
       <th data-options="field:'lc_date'" width="90" align="center">LC Date</th>
       <th data-options="field:'lc_amount'" width="100" align="right">Proposed LC Value</th>
       
       <th data-options="field:'pay_term_id'" width="80">Pay Term</th>
       <th data-options="field:'supplier'" width="100">Supplier</th>
       <th data-options="field:'bankbranch'" width="120">Issuing Bank</th>
       <th data-options="field:'lc_type_id'" width="100">LC Type</th>
       <th data-options="field:'menu_id'" width="100">PO Type</th>
       <th data-options="field:'last_delivery_date'" width="90">Last Delivery Date</th>
       <th data-options="field:'expiry_date'" width="90" align="right">Expiry Date</th>
      </tr>
     </thead>
    </table>
   </div>
   <div
    data-options="region:'west',border:true,title:'Sreach',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#implcapprovedFrmFt'"
    style="width:350px; padding:2px">
    <div id="container">
     <div id="body">
      <code>
       <form id="implcapprovedFrm">
         <div class="row middle">
           <div class="col-sm-5">Company</div>
           <div class="col-sm-7">
            {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
           </div>
          </div>
          <div class="row middle">
           <div class="col-sm-5">Supplier</div>
           <div class="col-sm-7">
            {!! Form::select('supplier_id',
            $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
           </div>
          </div>
          <div class="row middle">
           <div class="col-sm-5">Purchase Order Type</div>
           <div class="col-sm-7">
            {!! Form::select('menu_id',
            $menu,'',array('id'=>'menu_id','style'=>'width: 100%; border-radius:2px')) !!}
           </div>
          </div>
          <div class="row middle">
           <div class="col-sm-5">LC To</div>
           <div class="col-sm-7">
            {!! Form::select('lc_to_id',
            $supplier,'',array('id'=>'lc_to_id','style'=>'width: 100%; border-radius:2px')) !!}
           </div>
          </div>
      </form>
     </code>
     </div>
    </div>
    <div id="implcapprovedFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
     <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
      iconCls="icon-save" plain="true" id="save" onClick="MsImpLcApproval.getApp()">Search</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('implcapprovedFrm')">Reset</a>
    </div>
   </div>
  </div>
 </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/approval/MsImpLcApprovalController.js">
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
  $('#implcapprovalFrm [id="supplier_id"]').combobox();
  $('#implcapprovalFrm [id="menu_id"]').combobox();
  $('#implcapprovalFrm [id="lc_to_id"]').combobox();
  $('#implcapprovedFrm [id="supplier_id"]').combobox();
  $('#implcapprovedFrm [id="menu_id"]').combobox();
  $('#implcapprovedFrm [id="lc_to_id"]').combobox();

</script>