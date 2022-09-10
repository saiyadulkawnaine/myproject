<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="subinbworkrcvtabs">
 <div title="Start Up" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="soembTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="30">ID</th>
       <th data-options="field:'company_id'" width="100">Company</th>
       <th data-options="field:'buyer_id'" width="100">Customer</th>
       <th data-options="field:'sales_order_no'" width="100">Sales Order NO</th>
       <th data-options="field:'receive_date'" width="100">Rceive Date</th>
       <th data-options="field:'remarks'" width="100">Remarks</th>
       <th data-options="field:'production_area'" width="100">Production Area</th>
      </tr>
     </thead>
    </table>
   </div>
   <div
    data-options="region:'west',border:true,title:'Work Order Received',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#soembFrmft'"
    style="width:450px; padding:2px">
    <div id="container">
     <div id="body">
      <code>
                            <form id="soembFrm">
                                <div class="row">
                                    <input type="hidden" name="id" id="id" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Company</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Customer</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                               
                                <div class="row middle">
                                    <div class="col-sm-5">MKT Ref.</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="sub_inb_marketing_id" id="sub_inb_marketing_id" ondblclick="MsSoEmb.openSubInbMktWindow()" placeholder=" Double click" />
                                    </div>
                                </div>
                                <div class="row middle">
                                     <div class="col-sm-5 req-text areaCon">Production Area</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('production_area_id',$productionarea,'',array('id'=>'production_area_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Currency </div>
                                    <div class="col-sm-7">
                                        {!! Form::select('currency_id', $currency,'',array('id'=>'currency_id')) !!}
                                    </div>
                                </div>  
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Exch. Rate </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="exch_rate" id="exch_rate" value="" class="number integer"/>
                                    </div>
                                </div>                                
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Work Order/SalesOrder No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="sales_order_no" id="sales_order_no" ondblclick="MsSoEmb.soembpoWindowOpen()" placeholder=" Write/Double Click"/>
                                        <input type="hidden" name="po_emb_service_id" id="po_emb_service_id" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text"> Rceive Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="receive_date" id="receive_date" class="datepicker" />
                                    </div>
                                </div>         
                                <div class="row middle">
                                    <div class="col-sm-5">Remarks </div>
                                    <div class="col-sm-7">
                                        <textarea name="remarks" id="remarks" value=""></textarea>
                                    </div>
                                </div>
                            </form>
                        </code>
     </div>
    </div>
    <div id="soembFrmft" style="padding:0px 0px; text-align:right; background:#CCC;">
     <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
      iconCls="icon-save" plain="true" id="save" onClick="MsSoEmb.submit()">Save</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('soembFrm')">Reset</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmb.remove()">Delete</a>
    </div>
   </div>
  </div>
 </div>
 <div title="Item Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'west',border:true,title:'Add Information',iconCls:'icon-more',footer:'#soembitemFrmft'"
    style="width:450px; padding:2px">
    <form id="soembitemFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row">
                                    <input type="hidden" name="so_emb_id" id="so_emb_id" value="" />
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="po_emb_service_item_id" id="po_emb_service_item_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Emb. Name </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('embelishment_id', $embelishment,'',array('id'=>'embelishment_id','style'=>'width: 100%; border-radius:2px')) !!} {{-- ,'onchange'=>'MsSoEmbItem.embnameChange(this.value)' --}}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Emb. Type </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('embelishment_type_id', $embelishmenttype,'',array('id'=>'embelishment_type_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4 req-text">Emb. Size </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('embelishment_size_id', $embelishmentsize,'',array('id'=>'embelishment_size_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Gmt Parts </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('gmtspart_id', $gmtspart,'',array('id'=>'gmtspart_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">GMT. Item </div>
                                    <div class="col-sm-8">
                                         {!! Form::select('item_account_id', $itemaccount,'',array('id'=>'item_account_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">GMT. Color</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="gmt_color" id="gmt_color" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">GMT. Size </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="gmt_size" id="gmt_size" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">UOM </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('uom_id', $uom,'',array('id'=>'uom_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Order Qty  </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" value="" class="number integer" onchange="MsSoEmbItem.calculate()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Rate/Unit </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rate" id="rate" value="" class="number integer" onchange="MsSoEmbItem.calculate()" onpaste="return false" placeholder="write number (not more than 4 digit)" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Order Value </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount" id="amount" value="" class="number integer" readonly/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Delivery Date </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="delivery_date" id="delivery_date" value="" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Delivery Point </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="delivery_point" id="delivery_point" value="" />
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-4">Buyer </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('gmt_buyer', $buyer,'',array('id'=>'gmt_buyer','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-4">Style Ref </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="gmt_style_ref" id="gmt_style_ref" value="" />
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-4">Sales Order No </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="gmt_sale_order_no" id="gmt_sale_order_no" value="" />
                                    </div>
                                </div>   
       </code>
      </div>
     </div>
     <div id="soembitemFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsSoEmbItem.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmbItem.resetForm()">Reset</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmbItem.remove()">Delete</a>
     </div>
    </form>
   </div>
   <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
    <table id="soembitemTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="30">ID</th>
       <th data-options="field:'buyer_name'" width="100">Buyer</th>
       <th data-options="field:'style_ref'" width="100">Style Ref</th>
       <th data-options="field:'sale_order_no'" width="100">Sales Order No</th>
       <th data-options="field:'emb_name'" width="100">Emb. Name</th>
       <th data-options="field:'emb_type'" width="100">Emb. Type</th>
       <th data-options="field:'emb_size'" width="80">Emb. Size</th>
       <th data-options="field:'item_desc'" width="100">GMT Item</th>
       <th data-options="field:'gmtspart'" width="100">GMT Part</th>
       <th data-options="field:'gmt_color'" width="100">GMT Color</th>
       <th data-options="field:'gmt_size'" width="80">GMT Size</th>
       <th data-options="field:'qty'" width="80" align="right">Qty</th>
       <th data-options="field:'uom_name'" width="40">Uom</th>

       <th data-options="field:'rate'" width="60" align="right">Rate</th>
       <th data-options="field:'amount'" width="80" align="right">Amount</th>
       <th data-options="field:'delivery_date'" width="80">Delivery Date</th>
      </tr>
     </thead>
    </table>
   </div>
  </div>
 </div>
 <div title="Upload File" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'west',border:true,title:'Add File',iconCls:'icon-more',footer:'#assetimageft'"
    style="width:450px; padding:2px">
    <form id="soembfileFrm" enctype="multipart/form-data">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="so_emb_id" id="so_emb_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">File Upload</div>
                                    <div class="col-sm-8">
                                        <input type="file" id="file_src" name="file_src" />
                                    </div>
                                </div>                               
                            </code>
      </div>
     </div>
     <div id="assetimageft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsSubInbOrderFile.submit()">Upload</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('subinborderfileFrm')">Reset</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsSubInbOrderFile.remove()">Delete</a>
     </div>
    </form>
   </div>
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="soembfileTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'file_src',halign:'center',align:'center'" width="100"
        formatter="MsSoEmbFile.formatimage">Uploaded File</th>
       <th data-options="field:'id'" formatter="MsSoEmbFile.formatFile" width="100">Download</th>

      </tr>
     </thead>
    </table>
   </div>
  </div>
 </div>
</div>
{{-- Subcontract Inbound Marketing Window --}}
<div id="subinbmktwindow" class="easyui-window" title="Subcontract Inbound Marketing"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'west',split:true, title:'Search',footer:'#marketingwindowft'"
   style="width:350px;height:130px">
   <div class="easyui-layout" data-options="fit:true">
    <div id="body">
     <code>
                        <form id="subinbmktsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Company</div>
                                <div class="col-sm-8">
                                    {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                </div>
                            </div>
                            
                            <div class="row middle">
                                <div class="col-sm-4">Customer</div>
                                <div class="col-sm-8">
                                    {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4"> Date </div>
                                <div class="col-sm-8">
                                    <input type="text" name="mkt_date" id="mkt_date" class="datepicker" placeholder="YY-MM-DD" />
                                </div>
                            </div>
                        </form>
                    </code>
    </div>
    <p class="footer">
     <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; border-radius:1px"
      onClick="MsSoEmb.showMktGrid()">Search</a>
    </p>
   </div>
  </div>
  <div data-options="region:'center'" style="padding:10px;">
   <table id="subinbmktsearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="30">ID</th>
      <th data-options="field:'company_id'" width="100">Company</th>
      <th data-options="field:'production_area_id'" width="100">Prod. Capacity</th>
      <th data-options="field:'team_name'" width="100">Team</th>
      <th data-options="field:'teammember'" width="100">Marketing Member</th>
      <th data-options="field:'buyer_id'" width="100">Customer</th>
      <th data-options="field:'contact'" width="100">Contact</th>
      <th data-options="field:'contact_no'" width="100">Contact No</th>
     </tr>
    </thead>
   </table>
  </div>
  <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
   <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
    onclick="$('#subinbmktwindow').window('close')" style="width:80px">Close</a>
  </div>
 </div>
</div>

{{-- Emb service order Window --}}
<div id="soembpoWindow" class="easyui-window" title="Subcontract Inbound Marketing"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'west',split:true, title:'Search',footer:'#soembpowindowft'"
   style="width:300px;height:130px">
   <div class="easyui-layout" data-options="fit:true">
    <div id="body">
     <code>
                        <form id="soembposearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Work Order No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="po_no" id="po_no">
                                </div>
                            </div>
                           
                        </form>
                    </code>
    </div>
    <div id="soembpowindowft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class="easyui-linkbutton c2" style="border-radius:1px"
      onClick="MsSoEmb.getsoembpo()">Search</a>
    </div>
   </div>
  </div>
  <div data-options="region:'center',footer:'#soembposearchTblft'" style="padding:10px;">
   <table id="soembposearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="30">ID</th>
      <th data-options="field:'po_no'" width="100">WO No</th>
      <th data-options="field:'po_date'" width="80">WO Date</th>
      <th data-options="field:'company_name'" width="100">Company</th>
      <th data-options="field:'currency_code'" width="60">Currency</th>
      <th data-options="field:'exch_rate'" width="60">Exch.<br />Rate</th>
      <th data-options="field:'amount'" width="100" align="right">Wo.Amount</th>
      <th data-options="field:'pi_no'" width="60">PI No</th>
      <th data-options="field:'pi_date'" width="60">PI Date</th>
      <th data-options="field:'remarks'" width="200">Remarks</th>
     </tr>
    </thead>
   </table>
   <div id="soembposearchTblft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
     onclick="$('#soembpoWindow').window('close')" style="border-radius:1px">Close</a>
   </div>
  </div>

 </div>
</div>


<!-------==============Work Order File Pop Up===================-------------->
<div id="assetImageWindow" class="easyui-window" title="Image" data-options="modal:true,closed:true,iconCls:'icon-save'"
 style="width:500px;height:500px;padding:2px;">
 <div id="assetImageWindowoutput"></div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Embelishment/MsSoEmbController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Embelishment/MsSoEmbItemController.js">
</script>
<script>
 $(document).ready(function() {
    $(".datepicker").datepicker({
        beforeShow:function(input) {
            $(input).css({
                "position": "relative",
                "z-index": 999999
            });
        },
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
    });
    $('#soembFrm [id="buyer_id"]').combobox();
    $('#soembitemFrm [id="uom_id"]').combobox();
    $('#soembitemFrm [id="gmt_buyer"]').combobox();
    $('#soembitemFrm [id="gmtspart_id"]').combobox();
    $('#soembitemFrm [id="item_account_id"]').combobox();
    $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
        }
    });
    $('#embelishment_id').css('pointer-events','none').attr('tabindex', '-1');
});
</script>