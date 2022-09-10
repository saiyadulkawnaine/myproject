<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="prodgmtscreenprintrcvtabs">
 <div title="Reference Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="prodgmtprintrcvTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="40">ID</th>
       <th data-options="field:'shiftname_id'" width="80">Shift Name</th>
       <th data-options="field:'receive_no'" width="80">Receive No</th>
       <th data-options="field:'party_challan_no'" width="80">Challan</th>
       <th data-options="field:'receive_date'" width="100">Receive Date</th>
       <th data-options="field:'remarks'" width="100">Remarks</th>
      </tr>
     </thead>
    </table>
   </div>
   <div data-options="region:'west',border:true,title:'Add Screen Print Receive Department',footer:'#ft2'"
    style="width: 350px; padding:2px">
    <form id="prodgmtprintrcvFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Receive No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="receive_no" id="receive_no" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Party Challan</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="party_challan_no" id="party_challan_no" placeholder=" write " />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Issue Challan</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="challan_no" id="challan_no" placeholder=" double click " ondblclick="MsProdGmtPrintRcv.openDlvPrintWindow()" />
                                        <input type="hidden" name="prod_gmt_dlv_print_id" id="prod_gmt_dlv_print_id">
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Shift Name </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('shiftname_id', $shiftname,'',array('id'=>'shiftname_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Receive Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="receive_date" id="receive_date" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks" ></textarea>
                                    </div>
                                </div>  
                            </code>
      </div>
     </div>
     <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtPrintRcv.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodgmtprintrcvFrm')">Reset</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsProdGmtPrintRcv.remove()">Delete</a>
     </div>
    </form>
   </div>
  </div>
 </div>
 <!-----===============  =============----------->
 <div title="Order Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'west',border:true" style="width:400px; padding:2px">
    <div class="easyui-layout" data-options="fit:true">
     <div data-options="region:'north',border:true,title:'Add Details',iconCls:'icon-more',footer:'#ft4'"
      style="height:300px; padding:2px">
      <form id="prodgmtprintrcvorderFrm">
       <div id="container">
        <div id="body">
         <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="prod_gmt_print_rcv_id" id="prod_gmt_print_rcv_id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Order No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sale_order_no" id="sale_order_no" value="" onclick="
                                        MsProdGmtPrintRcvOrder.openOrderPrintRcvWindow()" placeholder="  Click" readonly />
                                        <input type="hidden" name="sales_order_country_id" id="sales_order_country_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Fabric Looks </div>
                                    <div class="col-sm-8">{!! Form::select('fabric_look_id', $fabriclooks,'',array('id'=>'fabric_look_id')) !!}</div>  {{-- ,'onchange'=>'MsStyleFabrication.fabricLookChange(this.value)', 'disabled' --}}
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Prod.Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Prod Source</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('prod_source_id', $productionsource,'',array('id'=>'prod_source_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Location</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Machine No</div>
                                    <div class="col-sm-8">
                                       {{--  <input type="text" name="machine_no" id="machine_no" /> --}}
                                        {!! Form::select('asset_quantity_cost_id', $assetquantitycost,'',array('id'=>'asset_quantity_cost_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Receive Hour</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="receive_hour" id="receive_hour" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Country</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="country_id" id="country_id" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Beneficiary</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="company_id" id="company_id" value="" disabled />
                                    </div>
                               </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Buyer</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="buyer_name" id="buyer_name" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">Job No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="job_no" id="job_no" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Style Reference</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="style_ref" id="style_ref" value="" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Ship Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="ship_date" id="ship_date" value="" class="datepicker" disabled/>
                                    </div>
                                </div>
                            </code>
        </div>
       </div>
       <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
         iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtPrintRcvOrder.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
         iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodgmtprintrcvorderFrm')">Reset</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
         iconCls="icon-remove" plain="true" id="delete" onClick="MsProdGmtPrintRcvOrder.remove()">Delete</a>
       </div>
      </form>
     </div>
     <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
      <table id="prodgmtprintrcvorderTbl" style="width:100%">
       <thead>
        <tr>
         <th data-options="field:'id'" width="40">Id</th>
         <th data-options="field:'sale_order_no'" width="100">Order No</th>
         <th data-options="field:'country_id'" width="100">Country</th>
         <th data-options="field:'fabric_look_id'" width="100">Fabric Looks</th>
         <th data-options="field:'supplier_id'" width="100">Prod.Company</th>
         <th data-options="field:'location_id'" width="100">Location</th>
         <th data-options="field:'machine_no'" width="100">Machine No</th>
         <th data-options="field:'receive_hour'" width="100">Receive Hour</th>
        </tr>
       </thead>
      </table>
     </div>
    </div>
   </div>
   <div data-options="region:'center',border:true,title:'Gmt Qty',footer:'#ftscreenprintrcvqty'" style="padding:2px">
    <form id="prodgmtprintrcvqtyFrm">
     <input type="hidden" name="id" id="id" value="" />
     <code id="printreceivegmtcosi">
                    </code>
     <div id="ftscreenprintrcvqty" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtPrintRcvQty.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodgmtprintrcvqtyFrm')">Reset</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsProdGmtPrintRcvQty.remove()">Delete</a>
     </div>
    </form>
   </div>
  </div>
 </div>
</div>
{{-- Receive NO Window --}}
<div id="opendlvprintwindow" class="easyui-window" title="Challan Search Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
   <div class="easyui-layout" data-options="fit:true">
    <div id="body">
     <code>
                        <form id="dlvprintsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Pro.Company</div>
                                <div class="col-sm-8">
                                    {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Delivery Date</div>
                                <div class="col-sm-8">
                                    <input type="text" name="delivery_date" id="delivery_date" class="datepicker" />
                                </div>
                            </div> 
                        </form>
                    </code>
    </div>
    <p class="footer">
     <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px"
      onClick="MsProdGmtPrintRcv.searchDlvPrintGrid()">Search</a>
    </p>
   </div>
  </div>
  <div data-options="region:'center'" style="padding:10px;">
   <table id="dlvprintsearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="40">ID</th>
      <th data-options="field:'challan_no'" width="80">Challan No</th>
      <th data-options="field:'supplier_name'" width="80">Prod. Company</th>
      <th data-options="field:'location_id'" width="80">Prod. Location</th>
      <th data-options="field:'shiftname_id'" width="80">Shift Name</th>
      <th data-options="field:'delivery_date'" width="100">Delivery Date</th>
     </tr>
    </thead>
   </table>
  </div>
  <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
   <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
    onclick="$('#opendlvprintwindow').window('close')" style="width:80px">Close</a>
  </div>
 </div>
</div>
{{-- Order window --}}
<div id="openprintorderwindow" class="easyui-window" title="Sales Order Search Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
   <div class="easyui-layout" data-options="fit:true">
    <div id="body">
     <code>
                        <form id="printordersearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Style Ref</div>
                                <div class="col-sm-8">
                                    <input type="text" name="style_ref" id="style_ref" value="">
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Job No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="job_no" id="job_no" value="" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Sale Order No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="sale_order_no" id="sale_order_no" value="" />
                                </div>
                            </div>
                        </form>
                    </code>
    </div>
    <p class="footer">
     <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px"
      onClick="MsProdGmtPrintRcvOrder.searchPrintReceiveOrderGrid()">Search</a>
    </p>
   </div>
  </div>
  <div data-options="region:'center'" style="padding:10px;">
   <table id="printordersearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="40">ID</th>
      <th data-options="field:'style_ref'" width="70">Style Ref</th>
      <th data-options="field:'job_no'" width="90">Job No</th>
      <th data-options="field:'sale_order_no'" width="100">Sale Order No</th>
      <th data-options="field:'country_id'" width="100">Country</th>
      <th data-options="field:'ship_date'" width="100">Ship Date</th>
      <th data-options="field:'buyer_name'" width="100">Buyer</th>
      <th data-options="field:'company_id'" width="80">Company</th>
      <th data-options="field:'fabric_looks'" width="80">Fabric Looks</th>
      <th data-options="field:'supplier_id'" width="80">Produced Company</th>
     </tr>
    </thead>
   </table>
  </div>
  <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
   <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
    onclick="$('#openprintorderwindow').window('close')" style="width:80px">Close</a>
  </div>
 </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Production/Garments/MsAllPrintRcvController.js"></script>

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

     // $('#prodgmtprintrcvFrm [id="supplier_id"]').combobox();

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