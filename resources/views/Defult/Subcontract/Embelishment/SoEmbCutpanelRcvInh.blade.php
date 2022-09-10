<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="soembcutpanelrcvinhtabs">
 <div title="Reference Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="soembcutpanelrcvinhTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="40">ID</th>
       <th data-options="field:'shift_id'" width="80">Shift Name</th>
       <th data-options="field:'buyer_name'" width="80">Customer</th>
       <th data-options="field:'party_challan_no'" width="80">Challan</th>
       <th data-options="field:'receive_date'" width="100">Receive Date</th>
       <th data-options="field:'remarks'" width="100">Remarks</th>
      </tr>
     </thead>
    </table>
   </div>
   <div data-options="region:'west',border:true,title:'Add Screen Print Receive Department',footer:'#ft2'"
    style="width: 350px; padding:2px">
    <form id="soembcutpanelrcvinhFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Production Area</div>
                                   <div class="col-sm-7">
                                       {!! Form::select('production_area_id',$productionarea,'',array('id'=>'production_area_id')) !!}
                                   </div>
                               </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Party Challan</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="party_challan_no" id="party_challan_no" placeholder=" double click " ondblclick="MsSoEmbCutpanelRcvInh.openPartyChallanWindow()" />
                                        <input type="hidden" name="prod_gmt_party_challan_id" id="prod_gmt_party_challan_id">
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Shift Name </div>
                                    <div class="col-sm-7">
                                        {!! Form::select('shift_id', $shiftname,'',array('id'=>'shift_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Receive Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="receive_date" id="receive_date" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Remarks</div>
                                    <div class="col-sm-7">
                                        <textarea name="remarks" id="remarks" ></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Receiving Company</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="supplier_name" id="supplier_name" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Customer</div>
                                    <div class="col-sm-7">
                                      {!! Form::select('buyer_id',$buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                            </code>
      </div>
     </div>
     <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsSoEmbCutpanelRcvInh.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('soembcutpanelrcvinhFrm')">Reset</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmbCutpanelRcvInh.remove()">Delete</a>
     </div>
    </form>
   </div>
  </div>
 </div>
 <!-----===============  =============----------->
 <!-----===============  =============----------->
 <div title="Order Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'west',border:true" style="width:400px; padding:2px">
    <div class="easyui-layout" data-options="fit:true">
     <div data-options="region:'north',border:true,title:'Add Details',iconCls:'icon-more',footer:'#ft4'"
      style="height:180px; padding:2px">
      <form id="soembcutpanelrcvinhorderFrm">
       <div id="container">
        <div id="body">
         <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="so_emb_cutpanel_rcv_id" id="so_emb_cutpanel_rcv_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Order No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sales_order_no" id="sales_order_no" value="" ondblclick="MsSoEmbCutpanelRcvInhOrder.openOrderCutpanelRcvWindow()" placeholder=" double click browse"  readonly />
                                        <input type="hidden" name="so_emb_id" id="so_emb_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>
                            </code>
        </div>
       </div>
       <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
         iconCls="icon-save" plain="true" id="save" onClick="MsSoEmbCutpanelRcvInhOrder.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
         iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmbCutpanelRcvInhOrder.resetForm()">Reset</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
         iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmbCutpanelRcvInhOrder.remove()">Delete</a>
       </div>
      </form>
     </div>
     <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
      <table id="soembcutpanelrcvinhorderTbl" style="width:100%">
       <thead>
        <tr>
         <th data-options="field:'id'" width="40">Id</th>
         <th data-options="field:'sale_order_no'" width="100">WO/SO No</th>
         <th data-options="field:'customer_name'" width="100">Customer</th>
         <th data-options="field:'remarks'" width="150">Remarks</th>
        </tr>
       </thead>
      </table>
     </div>
    </div>
   </div>
   <div data-options="region:'center',border:true,title:'Order Details'" style="padding:2px">
    <div class="easyui-layout" data-options="fit:true">
     <div data-options="region:'north',border:true,title:'Add Details',iconCls:'icon-more',footer:'#ft5'"
      style="height:200px; padding:2px">
      <form id="soembcutpanelrcvinhqtyFrm">
       <div id="container">
        <div id="body">
         <code>
                                    <table style="width:100%" border="1">
                                        <thead>
                                        <tr>
                                            <th width="100" class="text-center">Order No</th>
                                            <th width="100" class="text-center">GMT Item</th>
                                            <th width="100" class="text-center">GMT Color</th>
                                            <th width="100" class="text-center">Body Part</th>
                                            <th width="100" class="text-center">Design No</th>
                                            <th width="100" class="text-center">Current <br/> Rcv Qty</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td width="100">
                                                <input type="hidden" name="id" id="id" value="" />
                                                <input type="hidden" name="so_emb_cutpanel_rcv_order_id" id="so_emb_cutpanel_rcv_order_id" value="" />
                                                <input type="hidden" name="so_emb_ref_id" id="so_emb_ref_id">
                                                <input type="text" name="sale_order_no" id="sale_order_no" placeholder=" double click browse"   onclick="MsSoEmbCutpanelRcvInhQty.OpenProdGmtEmbItemWindow()">
                                                </td>
                                                <td width="100"><input type="text" name="item_desc" id="item_desc" readonly/></td>
                                                <td width="100">
                                                <input type="text" name="gmt_color" id="gmt_color" readonly/></td>
                                                <td width="100">
                                                <input type="text" name="gmtspart" id="gmtspart" readonly/></td>
                                                <td width="100"><input type="text" name="design_no" id="design_no"/></td>
                                                <td width="100"><input type="text" name="qty" id="qty" class="number integer"/></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </code>
        </div>
       </div>
       <div id="ft5" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
         iconCls="icon-save" plain="true" id="save" onClick="MsSoEmbCutpanelRcvInhQty.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
         iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmbCutpanelRcvInhQty.resetForm()">Reset</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
         iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmbCutpanelRcvInhQty.remove()">Delete</a>
       </div>
      </form>
     </div>
     <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
      <table id="soembcutpanelrcvinhqtyTbl" style="width:100%">
       <thead>
        <tr>
         <th data-options="field:'id'" width="20">ID</th>
         <th data-options="field:'sale_order_no'" width="200">Order No</th>
         <th data-options="field:'item_desc'" width="80">Gmt Item</th>
         <th data-options="field:'gmt_color'" width="100">Gmt Color</th>
         <th data-options="field:'gmtspart'" width="80">Body Part</th>
         <th data-options="field:'design_no'" width="120">Design No</th>
         <th data-options="field:'qty'" width="65">Qty</th>
        </tr>
       </thead>
      </table>
     </div>
    </div>
   </div>
  </div>
 </div>
</div>
{{-- Receive NO Window from ProdGmtDlvToEmb / ProdGmtDlvPrint--}}
<div id="opendlvpartychallanwindow" class="easyui-window" title="Challan Search Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
   <div class="easyui-layout" data-options="fit:true">
    <div id="body">
     <code>
                        <form id="dlvpartychallansearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Pro.Company</div>
                                <div class="col-sm-8">
                                    {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Delivery Date</div>
                                <div class="col-sm-8">
                                    <input type="text" name="delivery_date" id="delivery_date" class="datepicker" />
                                </div>
                            </div> 
                        </form>
                    </code>
    </div>
    <p class="footer">
     <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px"
      onClick="MsSoEmbCutpanelRcvInh.searchDlvPartyChallanGrid()">Search</a>
    </p>
   </div>
  </div>
  <div data-options="region:'center'" style="padding:10px;">
   <table id="dlvpartychallansearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="80">ID</th>
      <th data-options="field:'challan_no'" width="80">Challan No</th>
      <th data-options="field:'company_name'" width="100">Prod. Company</th>
      <th data-options="field:'supplier_name'" width="150">Customer</th>
      <th data-options="field:'location_name'" width="100">Prod. Location</th>
      <th data-options="field:'shift_name'" width="80">Shift Name</th>
      <th data-options="field:'delivery_date'" width="100">Delivery Date</th>
     </tr>
    </thead>
   </table>
  </div>
  <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
   <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
    onclick="$('#opendlvpartychallanwindow').window('close')" style="width:80px">Close</a>
  </div>
 </div>
</div>
{{-- Order window --}}
<div id="opencutpanelorderwindow" class="easyui-window" title="Sales Order / Work Order Search Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
   <div class="easyui-layout" data-options="fit:true">
    <div id="body">
     <code>
                        <form id="cutpanelordersearchFrm">
                            <div class="row middle">
                                <div class="col-sm-5">Company</div>
                                <div class="col-sm-7">
                                  {!! Form::select('company_id',$company,array('id'=>'company_id')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-5 req-text">Sale Order/Work Order No</div>
                                <div class="col-sm-7">
                                    <input type="text" name="sale_order_no" id="sale_order_no" value="" />
                                </div>
                            </div>
                        </form>
                    </code>
    </div>
    <p class="footer">
     <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px"
      onClick="MsSoEmbCutpanelRcvInhOrder.searchCutpanelReceiveOrder()">Search</a>
    </p>
   </div>
  </div>
  <div data-options="region:'center'" style="padding:10px;">
   <table id="cutpanelordersearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="30">ID</th>
      <th data-options="field:'sales_order_no'" width="130">Sales Order No</th>
      <th data-options="field:'company_name'" width="100">Company</th>
      <th data-options="field:'buyer_name'" width="160">Buyer</th>
      <th data-options="field:'receive_date'" width="80">Rcv Date</th>
      <th data-options="field:'remarks'" width="160">Remarks</th>
      <th data-options="field:'currency'" width="60">Currency</th>
     </tr>
    </thead>
   </table>
  </div>
  <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
   <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
    onclick="$('#opencutpanelorderwindow').window('close')" style="width:80px">Close</a>
  </div>
 </div>
</div>
{{-- So emb item ref window --}}
<div id="prodgmtsoembitemWindow" class="easyui-window" title="Emblishment Item Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',footer:'#prodgmtsoembitemsearchTblFt'" style="padding:10px;">
   <table id="prodgmtsoembitemsearchTbl" style="width:100%">
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
   <div id="prodgmtsoembitemsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
     onclick="$('#prodgmtsoembitemWindow').window('close')" style="border-radius:1px">Close</a>
   </div>
  </div>

 </div>
</div>
<script type="text/javascript"
 src="<?php echo url('/');?>/js/Subcontract/Embelishment/MsAllSoEmbCutpanelRcvInhController.js"></script>

<script>
 (function(){
      $(".datepicker").datepicker({
         beforeShow:function(input) {
               $(input).css({
                  "position": "relative",
                  "z-index": 999999
               });
         },
         dateFormat: 'yy-mm-dd',
         changeMonth: true,
         changeYear: true
      });

      $('.integer').keyup(function () {
         if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
         }
      });

     $('#soembcutpanelrcvinhFrm [id="buyer_id"]').combobox();

   })(jQuery);

$(function() {
$('#receive_hour').timepicker(
    {
    'minTime': '12:00pm',
    'maxTime': '11:00am',
    'showDuration': false,
    'step':60,
    'scrollDefault': 'now',
    'change': function(){
        alert('m')
    }
    }
);
});

</script>