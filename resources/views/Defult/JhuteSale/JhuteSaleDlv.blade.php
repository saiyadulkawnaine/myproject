<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="jhutesaledlvtabs">
 <div title="Jhute Sale Delivery" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="jhutesaledlvTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="40">ID</th>
       <th data-options="field:'dlv_no'" width="100">Dlv No</th>
       <th data-options="field:'do_no'" width="100">DO no</th>
       <th data-options="field:'dlv_date'" width="100">Dlv Date</th>
       <th data-options="field:'company_name'" width="100">Company</th>
       <th data-options="field:'location_name'" width="100">Location</th>
       <th data-options="field:'store_name'" width="100">Store</th>
       <th data-options="field:'buyer_name'" width="100">Costomer</th>
       <th data-options="field:'driver_name'" width="100">Driver Name</th>
       <th data-options="field:'driver_contact_no'" width="100">Driver Contact No</th>
       <th data-options="field:'driver_license_no'" width="100">Driver License No</th>
       <th data-options="field:'lock_no'" width="100">Lock No</th>
       <th data-options="field:'truck_no'" width="100">Truck No</th>
       <th data-options="field:'remarks'" width="120">Remarks</th>
      </tr>
     </thead>
    </table>
   </div>
   <div data-options="region:'west',border:true,title:'Jhute Sale Delivery',footer:'#ft2'"
    style="width: 400px; padding:2px">
    <div id="container">
     <div id="body">
        <form id="jhutesaledlvFrm">
            <code>
                <div class="row middle" style="display:none">
                    <input type="hidden" name="id" id="id" value="" />
                </div>
                <div class="row middle">
                    <div class="col-sm-5 req-text">Dlv No</div>
                    <div class="col-sm-7">
                        <input type="text" name="dlv_no" id="dlv_no" readonly/>
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5 req-text">Do No</div>
                    <div class="col-sm-7">
                        <input type="text" name="do_no" id="do_no" ondblclick="MsJhuteSaleDlv.openWindow()" placeholder="Double Click" value="" readonly/>
                        <input type="hidden" name="jhute_sale_dlv_order_id" id="jhute_sale_dlv_order_id" value=""/>
                    </div>
                </div>
                <div class="row middle">
                 <div class="col-sm-5 req-text">Dlv Date</div>
                 <div class="col-sm-7">
                  <input type="text" name="dlv_date" id="dlv_date" class="datepicker" placeholder="yyyy-mm-dd"  value=""/>
                 </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5">Company </div>
                    <div class="col-sm-7">
                        <input type="hidden" name="company_id" id="company_id" value="" />
                        <input type="text" name="company_name" id="company_name" value="" disabled />
                        
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5">Location </div>
                    <div class="col-sm-7">
                        <input type="text" name="location_name" id="location_name" value="" disabled/>
                    </div>
                </div>
                <div class="row middle">
                  <div class="col-sm-5">Store</div>
                  <div class="col-sm-7">
                   {!! Form::select('store_id',$store,'',array('id'=>'store_id','style'=>'width:100%;border:2px;')) !!}
                  </div>
                 </div>
                <div class="row middle">
                    <div class="col-sm-5">Customer</div>
                    <div class="col-sm-7">
                        <input type="text" name="buyer_name" id="buyer_name" value="" disabled />
                    </div>
                </div>
                <div class="row middle">
                  <div class="col-sm-5">Driver Name</div>
                  <div class="col-sm-7">
                   <input type="text" name="driver_name" id="driver_name" value="" />
                  </div>
                 </div>
                <div class="row middle">
                  <div class="col-sm-5">Driver Contact No</div>
                  <div class="col-sm-7">
                   <input type="text" name="driver_contact_no" id="driver_contact_no" value="" />
                  </div>
                 </div>
                <div class="row middle">
                  <div class="col-sm-5">Driver License No</div>
                  <div class="col-sm-7">
                   <input type="text" name="driver_license_no" id="driver_license_no" value="" />
                  </div>
                 </div>
                <div class="row middle">
                  <div class="col-sm-5">Lock No</div>
                  <div class="col-sm-7">
                   <input type="text" name="lock_no" id="lock_no" value="" //>
                  </div>
                 </div>
                <div class="row middle">
                  <div class="col-sm-5">Truck No</div>
                  <div class="col-sm-7">
                   <input type="text" name="truck_no" id="truck_no" value="" />
                  </div>
                 </div>
                <div class="row middle">
                    <div class="col-sm-5">Remarks </div>
                    <div class="col-sm-7">
                        <textarea name="remarks" id="remarks"></textarea>
                    </div>
                </div>
            </code>
        </form>
     </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
      iconCls="icon-save" plain="true" id="save" onClick="MsJhuteSaleDlv.submit()">Save</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('jhutesaledlvFrm')">Reset</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsJhuteSaleDlv.remove()">Delete</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsJhuteSaleDlv.billpdf()">BILL</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsJhuteSaleDlv.challanpdf()">Challan</a>
    </div>
   </div>
  </div>
 </div>
 <div title="Item Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="jhutesaledlvitemTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="80">ID</th>
       <th data-options="field:'acc_chart_ctrl_head_name'" width="100">Item Description</th>
       <th data-options="field:'uom_code'" width="60">UOM</th>
       <th data-options="field:'qty',align:'right'" width="100">Req. Qty</th>
       <th data-options="field:'rate',align:'right'" width="80">Rate</th>
       <th data-options="field:'amount',align:'right'" width="120">Amount</th>
       <th data-options="field:'remarks'" width="120">Remarks</th>
      </tr>
     </thead>
    </table>
   </div>
   <div data-options="region:'west',border:true,title:'Items',footer:'#ft3'" style="width: 350px; padding:2px">
    <form id="jhutesaledlvitemFrm">
     <div id="container">
      <div id="body">
       <code>
            <div class="row middle" style="display:none">
                <input type="hidden" name="id" id="id" value="" />
                <input type="hidden" name="jhute_sale_dlv_id" id="jhute_sale_dlv_id">
            </div>
            <div class="row middle">
                <div class="col-sm-5 req-text">Item description</div>
                <div class="col-sm-7">
                 <input type="hidden" name="jhute_sale_dlv_order_item_id" id="jhute_sale_dlv_order_item_id" value="" />
                 <input type="text" name="acc_chart_ctrl_head_name" id="acc_chart_ctrl_head_name" ondblclick="MsJhuteSaleDlvItem.openWindow()"
                    placeholder="Double Click" value="" />
                 </div>
            </div>
            <div class="row middle">
                <div class="col-sm-5 req-text">UOM </div>
                <div class="col-sm-7">
                    <input type="text" name="uom_name" id="uom_name" readonly value="" />
                </div>
            </div>
            <div class="row middle">
                <div class="col-sm-5 req-text">Qty </div>
                <div class="col-sm-7">
                    <input type="text" name="qty" id="qty" value="" onchange="MsJhuteSaleDlvItem.calculateAmount()" class="number integer" />
                </div>
            </div>
            <div class="row middle">
                <div class="col-sm-5">Rate </div>
                <div class="col-sm-7">
                    <input type="text" name="rate" id="rate" class="number integer" onchange="MsJhuteSaleDlvItem.calculateAmount()" readonly/>
                </div>
            </div>
            <div class="row middle">
                <div class="col-sm-5">Amount </div>
                <div class="col-sm-7">
                    <input type="text" name="amount" id="amount" class="number integer" readonly />
                </div>
            </div>
            <div class="row middle">
                <div class="col-sm-5">Remarks </div>
                <div class="col-sm-7">
                    <textarea name="remarks" id="remarks" cols="30" rows="5"></textarea>
                </div>
            </div>
        </code>
      </div>
     </div>
     <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsJhuteSaleDlvItem.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('jhutesaledlvitemFrm')">Reset</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsJhuteSaleDlvItem.remove()">Delete</a>
     </div>
    </form>
   </div>
  </div>
 </div>
</div>

{{-- jhute sale dlv window  --}}
<div id="jhutesaledlvwindow" class="easyui-window" title="Jhute Order Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',border:true,footer:'#jhutesaledlvsearchTblFt'" style="padding:2px">
   <table id="jhutesaledlvsearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="40">ID</th>
      <th data-options="field:'do_no'" width="100">DO No</th>
      <th data-options="field:'company_name'" width="100">Company</th>
      <th data-options="field:'location_name'" width="100">Location</th>
      <th data-options="field:'do_date'" width="100">Do date</th>
      <th data-options="field:'currency_code'" width="100">Currency</th>
      <th data-options="field:'etd_date'" width="100">ETD Date</th>
      <th data-options="field:'buyer_name'" width="100">Costomer</th>
      <th data-options="field:'advised_by'" width="100">Advised By</th>
      <th data-options="field:'price_verified_by'" width="100">Price Varified By</th>
      <th data-options="field:'payment_before_dlv'" width="100">Payment Before Delv</th>
      <th data-options="field:'remarks'" width="120">Remarks</th>
     </tr>
    </thead>
   </table>
   <div id="jhutesaledlvsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
     onclick="$('#jhutesaledlvwindow').window('close')" style="width:80px">Close</a>
   </div>
  </div>
  <div data-options="region:'west',border:true,footer:'#jhutesaledlvsearchFrmFt'" style="padding:2px; width:350px">
   <form id="jhutesaledlvsearchFrm">
    <div id="container">
     <div id="body">
        <code>
            <div class="row middle">
                <div class="col-sm-4">Do no</div>
                <div class="col-sm-8">
                    <input type="text" name="do_no" id="do_no" />
                </div>
            </div>
            <div class="row ">
                <div class="col-sm-4 req-text">Company</div>
                <div class="col-sm-8">
                    <input type="text" name="company_id" id="company_id" />
                </div>
            </div>
        </code>
     </div>
    </div>
    <div id="jhutesaledlvsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true"
      onClick="MsJhuteSaleDlv.searchJhuteSaleDlv()">Search</a>
    </div>
   </form>
  </div>
 </div>
</div>

{{-- jhute sale item window --}}
<div id="jhutesaledlvitemwindow" class="easyui-window" title="Jhute item Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',border:true,footer:'#jhutesaledlvitemsearchTblFt'" style="padding:2px">
   <table id="jhutesaledlvitemsearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="80">ID</th>
      <th data-options="field:'acc_chart_ctrl_head_name'" width="100">Item Description</th>
      <th data-options="field:'uom_name'" width="60">UOM</th>
      <th data-options="field:'qty',align:'right'" width="100">Req. Qty</th>
      <th data-options="field:'rate',align:'right'" width="80">Rate</th>
      <th data-options="field:'amount',align:'right'" width="120">Amount</th>
      <th data-options="field:'remarks'" width="120">Remarks</th>
      <th data-options="field:'balance_qty'" width="100">Balance Qty</th>
     </tr>
    </thead>
   </table>
   <div id="jhutesaledlvitemsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
     onclick="$('#jhutesaledlvitemwindow').window('close')" style="width:80px">Close</a>
   </div>
  </div>
  <div data-options="region:'west',border:true,footer:'#jhutesaledlvitemsearchFrmFt'" style="padding:2px; width:350px">
   <form id="jhutesaledlvitemsearchFrm">
    <div id="container">
     <div id="body">
      <code>
       <div class="row middle">
                            <div class="col-sm-4">item Description</div>
                            <div class="col-sm-8">
                            <input type="text" name="item_description" id="item_description" />
                            </div>
                            </div>
                            <div class="row ">
                            <div class="col-sm-4 req-text">uom id</div>
                            <div class="col-sm-8">
                           <input type="text" name="uom_id" id="uom_id">
                            </div>
                            </div>
                        </code>
     </div>
    </div>
    <div id="jhutesaledlvitemsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true"
      onClick="MsJhuteSaleDlvItem.searchJhuteSaleDlvItem()">Search</a>
    </div>
   </form>
  </div>
 </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/JhuteSale/MsAllJhuteSaleDlvController.js"></script>

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

        $('#jhutesaledlvFrm [id="store_id"]').combobox();
    })(jQuery);
</script>