<div class="easyui-layout animated rollIn" data-options="fit:true">
 <div data-options="region:'center',border:true" style="padding:2px">
  <table id="orderpendingTbl" style="width:100%" title="Order Not Received">
   <thead>
    <tr>
     <th data-options="field:'company_code'" width="60" align="center">Company</th>
     <th data-options="field:'teamleader_name'" width="100" formatter="MsOrderPending.formatteamleader" align="center">
      Team <br />Leader</th>
     <th data-options="field:'team_member_name'" width="100" formatter="MsOrderPending.formatdlmerchant" align="center">
      Dealing <br />Merchant</th>
     <th data-options="field:'buyer_name'" width="180">Buyer</th>
     <th data-options="field:'buying_agent_name'" width="150" formatter="MsOrderPending.formatbuyingAgent">Buying House
     </th>
     <th data-options="field:'style_ref'" width="100" formatter="MsOrderPending.formatopfiles">Style No</th>
     <th data-options="field:'style_description'" width="150">Style Description</th>
     <th data-options="field:'receive_date'" width="80">Receive Date</th>
     <th data-options="field:'ship_date'" width="90">Est.Ship Date</th>
     <th data-options="field:'department_name'" width="100" align="center">Prod. Dept</th>
     <th data-options="field:'season_name'" width="100">Season</th>
     <th data-options="field:'flie_src'" formatter="MsOrderPending.formatimage" width="50" align="center">Image</th>
     <th data-options="field:'qty'" width="80" align="right">Offer Qty</th>
     <th data-options="field:'uom_code'" width="50">UOM</th>
     <th data-options="field:'remarks'" width="150">Remarks</th>
    </tr>
   </thead>
  </table>

 </div>
 <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
  <form id="orderpendingFrm">
   <div id="container">
    <div id="body">
     <code>
                    <div class="row middle">
                        <div class="col-sm-4">Create Date</div>
                        <div class="col-sm-4" style="padding-right:0px">
                            <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                        </div>
                        <div class="col-sm-4" style="padding-left:0px">
                            <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Receive Date</div>
                        <div class="col-sm-4" style="padding-right:0px">
                            <input type="text" name="receive_date_from" id="receive_date_from" class="datepicker" placeholder="From" />
                        </div>
                        <div class="col-sm-4" style="padding-left:0px">
                            <input type="text" name="receive_date_to" id="receive_date_to" class="datepicker"  placeholder="To" />
                        </div>
                    </div>
                   	<div class="row middle">
                        <div class="col-sm-4">Buyer</div>
                        <div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}</div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Company</div>
                        <div class="col-sm-8">
                            {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                        </div>
                    </div>  
                    <div class="row middle">
                        <div class="col-sm-4">Style Ref</div>
                        <div class="col-sm-8">
                            <input type="text" name="style_ref" id="style_ref" onDblClick="MsOrderPending.openOrdStyleWindow()" placeholder=" Double Click" readonly />
                            <input type="hidden" name="style_id" id="style_id" value=""/>
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Dl.Marchant</div>
                        <div class="col-sm-8">
                            <input type="text" name="team_member_name" id="team_member_name" onDblClick="MsOrderPending.openTeammemberDlmWindow()" placeholder=" Double Click" readonly/>
                            <input type="hidden" name="factory_merchant_id" id="factory_merchant_id" value=""/>
                        </div>
                    </div>
              </code>
    </div>
   </div>
   <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" plain="true"
     id="save" onClick="MsOrderPending.get()">Show</a>
    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
     iconCls="icon-remove" plain="true" id="delete" onClick="MsOrderPending.resetForm('orderpendingFrm')">Reset</a>
    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" plain="true"
     id="save" onClick="MsOrderPending.showExcel('orderpendingTbl','orderpendingTbl')">XLS</a>
   </div>
  </form>
 </div>
 <div data-options="region:'east',border:true,collapsed:true,hideCollapsedContent:false,title:'Monthly Summary'"
  style="width:400px; padding:2px">
  <table id="monthlystyleTbl" style="width:100%">
   <thead>
    <th data-options="field:'month',halign:'center'" align="center" width="100">Month</th>
    <th data-options="field:'qty',halign:'center'" align="right" width="100">Offer Qty</th>
   </thead>
  </table>
 </div>
</div>

{{-- Dealing Merchant --}}
<div id="dlmerchantWindow" class="easyui-window" title="Merchandiser Details"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
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
<div id="buyagentwindow" class="easyui-window" title="Buying Agents Details"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
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
{{-- Style Filtering Search Window --}}
<div id="styleWindow" class="easyui-window" title="Style Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1200px;height:500px;padding:2px;">
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
     <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px"
      onClick="MsOrderPending.searchStyleGrid()">Search</a>
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
   <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
    onclick="$('#styleWindow').window('close')" style="width:80px">Close</a>
  </div>
 </div>
</div>
{{-- File Src --}}
<div id="opfilesrcwindow" class="easyui-window" title="Style Uploaded Files"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <table id="opfilesrcTbl" style="width:500px">
  <thead>
   <tr>
    <th data-options="field:'id'" width="30">ID</th>
    <th data-options="field:'file_src'" width="180px">File Source </th>
    <th data-options="field:'original_name'" formatter="MsOrderPending.formatShowOpFile" width="250px">Original Name
    </th>
   </tr>
  </thead>
 </table>
</div>

<div id="orderPendingImageWindow" class="easyui-window" title="Image"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:500px;height:500px;padding:2px;">
 <img id="orderPendingImageWindowoutput" src="" />
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/report/MsOrderPendingController.js"></script>

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
        $('#orderpendingFrm [id="buyer_id"]').combobox();
        $('#ordstylesearchFrm [id="buyer_id"]').combobox();
    })(jQuery);
</script>