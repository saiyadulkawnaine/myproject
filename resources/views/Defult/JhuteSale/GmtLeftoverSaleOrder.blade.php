<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="gmtleftoversaleordertabs">
 <div title="Garments Leftover DO Referance" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List view'" style="padding:2px">
    <table id="gmtleftoversaleorderTbl" style="width:100%">
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
   </div>
   <div data-options="region:'west',border:true,title:'Leftover DO Referance',footer:'#ft2'"
    style="width: 400px; padding:2px">
    <div id="container">
     <div id="body">
      <form id="gmtleftoversaleorderFrm">
       <code>
                    <div class="row middle" style="display:none">
                        <input type="hidden" name="id" id="id" value="" />
                    </div>
                    <div class="row middle">
                        <div class="col-sm-5">DO No</div>
                        <div class="col-sm-7">
                            <input type="text" name="do_no" id="do_no" readonly />
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-5 req-text">Company </div>
                        <div class="col-sm-7">
                            {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-5 req-text">Location </div>
                        <div class="col-sm-7">
                            {!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-5 req-text">Do Date </div>
                        <div class="col-sm-7">
                            <input type="text" name="do_date" id="do_date" class="datepicker" placeholder="yyyy-mm-dd"/>
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-5 req-text">Currency </div>
                        <div class="col-sm-7">
                            {!! Form::select('currency_id', $currency,'',array('id'=>'currency_id')) !!}
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-5">ETD</div>
                        <div class="col-sm-7">
                            <input type="text" name="etd_date" id="etd_date" class="datepicker" placeholder="yy-mm-dd" />
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-5">Customer</div>
                        <div class="col-sm-7">
                            {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-5 req-text">Advised By</div>
                        <div class="col-sm-7">
                            {!! Form::select('advised_by_id', $user, '', array('id' => 'advised_by_id','style'=>'width: 100%; border-radius:2px')) !!}
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-5 req-text">Price Varified By</div>
                        <div class="col-sm-7">
                            {!! Form::select('price_verified_by_id', $user,'',array('id' =>'price_verified_by_id','style'=>'width: 100%; border-radius:2px')) !!}
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-5 req-text">Payment Before Delv</div>
                        <div class="col-sm-7">
                            {!! Form::select('payment_before_dlv_id', $yesno,'',array('id'=>'payment_before_dlv_id')) !!}
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-5">Remarks </div>
                        <div class="col-sm-7">
                            <textarea name="remarks" id="remarks"></textarea>
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-5">Status</div>
                        <div class="col-sm-7">
                            {!! Form::select('status_id', $status,'1',array('id'=>'status_id')) !!}
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-5">Ready to Approve</div>
                        <div class="col-sm-7">
                            {!! Form::select('ready_to_approve_id', $yesno,'0',array('id'=>'ready_to_approve_id')) !!}
                        </div>
                    </div>
                </code>
      </form>
     </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
      iconCls="icon-save" plain="true" id="save" onClick="MsGmtLeftoverSaleOrder.submit()">Save</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('jhutesaledlvorderFrm')">Reset</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsGmtLeftoverSaleOrder.remove()">Delete</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsGmtLeftoverSaleOrder.pdf()">DO</a>
    </div>
   </div>
  </div>
 </div>
 <div title="Style Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'west',border:true" style="width:400px; padding:2px">
    <div class="easyui-layout" data-options="fit:true">
     <div data-options="region:'north',border:true,title:'Add Details',iconCls:'icon-more',footer:'#ft4'"
      style="height:300px; padding:2px">
      <form id="gmtleftoversaleorderstyledtlFrm">
       <div id="container">
        <div id="body">
         <code>
             <div class="row middle" style="display:none">
                 <input type="hidden" name="id" id="id" value="" />
                 <input type="hidden" name="jhute_sale_dlv_order_id" id="jhute_sale_dlv_order_id" value="" />
             </div>
             <div class="row middle">
                 <div class="col-sm-5 req-text">Style Ref</div>
                 <div class="col-sm-7">
                    <input type="text" name="style_ref" id="style_ref" onDblClick="MsGmtLeftoverSaleOrderStyleDtl.openLeftOverStyleWindow()"
                      placeholder="Double Click" readonly />
                     <input type="hidden" name="style_id" id="style_id" value="" />
                 </div>
             </div>
             <div class="row middle">
                 <div class="col-sm-5 req-text">Leftover Garment Sales</div>
                 <div class="col-sm-7">
                     {!! Form::select('acc_chart_ctrl_head_id', $ctrlHead,'',array('id'=>'acc_chart_ctrl_head_id','style'=>'width:
                     100%; border-radius:2px')) !!}
                 </div>
             </div>
             <div class="row middle">
                 <div class="col-sm-5 req-text">UOM </div>
                 <div class="col-sm-7">
                     {!! Form::select('uom_id', $uom,'',array('id'=>'uom_id')) !!}
                 </div>
             </div>
             <div class="row middle">
                 <div class="col-sm-5 req-text">Qty </div>
                 <div class="col-sm-7">
                     <input type="text" name="qty" id="qty"  class="number integer" readonly/>
                 </div>
             </div>
             <div class="row middle">
                 <div class="col-sm-5">Rate </div>
                 <div class="col-sm-7">
                     <input type="text" name="rate" id="rate" readonly class="number integer" />
                 </div>
             </div>
             <div class="row middle">
                 <div class="col-sm-5">Amount </div>
                 <div class="col-sm-7">
                     <input type="text" name="amount" id="amount" class="number integer" readonly />
                 </div>
             </div>
             <div class="row middle">
                 <div class="col-sm-5">Bnf. Company</div>
                 <div class="col-sm-7">
                     <input type="text" name="company_name" id="company_name" class="" readonly />
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
       <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
         iconCls="icon-save" plain="true" id="save" onClick="MsGmtLeftoverSaleOrderStyleDtl.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
         iconCls="icon-remove" plain="true" id="delete"
         onClick="msApp.resetForm('GmtLeftoverSaleOrderStyleDtlFrm')">Reset</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
         iconCls="icon-remove" plain="true" id="delete" onClick="MsGmtLeftoverSaleOrderStyleDtl.remove()">Delete</a>
       </div>
      </form>
     </div>
     <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
      <table id="gmtleftoversaleorderstyledtlTbl" style="width:100%">
       <thead>
        <tr>
         <th data-options="field:'id'" width="80">ID</th>
         <th data-options="field:'acc_chart_ctrl_head_id'" width="100">Item Description</th>
         <th data-options="field:'uom_id'" width="60">UOM</th>
         <th data-options="field:'qty',align:'right'" width="100">Req. Qty</th>
         <th data-options="field:'rate',align:'right'" width="80">Rate</th>
         <th data-options="field:'amount',align:'right'" width="120">Amount</th>
         <th data-options="field:'remarks'" width="120">Remarks</th>
        </tr>
       </thead>
      </table>
     </div>
    </div>
   </div>
   <div data-options="region:'center',border:true,title:'Gmt Qty',footer:'#ftgmtleftoversaleoverqty'"
    style="padding:2px">
    <form id="gmtleftoversaleorderqtyFrm">
     <input type="hidden" name="id" id="id" value="" />
     <code id="gmtleftoversalecosi">
        </code>
     <div id="ftgmtleftoversaleoverqty" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsGmtLeftoverSaleOrderQty.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('gmtleftoversaleorderqtyFrm')">Reset</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsGmtLeftoverSaleOrderQty.remove()">Delete</a>
     </div>
    </form>
   </div>
  </div>
 </div>

 <div title="Payment Received" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div
    data-options="region:'west',border:true,title:'New Payment',iconCls:'icon-more',footer:'#jhutesaledlvorderpaymentft'"
    style="width:450px; padding:2px">
    <form id="gmtleftoversaleorderpaymentFrm">
     <div id="container">
      <div id="body">
       <code>
            <div class="row">
                <input type="hidden" name="id" id="id" value="" />
                <input type="hidden" name="jhute_sale_dlv_order_id" id="jhute_sale_dlv_order_id" value="" />
            </div>
            <div class="row middle">
                <div class="col-sm-5 req-text">Payment Date </div>
                <div class="col-sm-7">
                    <input type="text" name="payment_date" id="payment_date" class="datepicker" />
                </div>
            </div>
            <div class="row middle">
                <div class="col-sm-5 req-text">Amount </div>
                <div class="col-sm-7">
                    <input type="text" name="amount" id="amount" class="number integer" />
                </div>
            </div>
            <div class="row middle">
                <div class="col-sm-5">Received By</div>
                <div class="col-sm-7">
                    {!! Form::select('receive_by_id', $user,'',array('id'=>'receive_by_id','style'=>'width: 100%; border-radius:2px')) !!}
                </div>
            </div>
        </code>
      </div>
     </div>
     <div id="gmtleftoversaleorderpaymentft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsGmtLeftoverSaleOrderPayment.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete"
       onClick="msApp.resetForm('gmtleftoversaleorderpaymentFrm')">Reset</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsGmtLeftoverSaleOrderPayment.remove()">Delete</a>
     </div>
    </form>
   </div>
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="gmtleftoversaleorderpaymentTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="80">ID</th>
       <th data-options="field:'payment_date'" width="100">Payment Date</th>
       <th data-options="field:'amount'" width="100">Amount</th>
       <th data-options="field:'receive_by_id'" width="100">Received By</th>
      </tr>
     </thead>
    </table>
   </div>
  </div>
 </div>
</div>

{{-- Style Filtering Search Window --}}
<div id="LeftOverStyleWindow" class="easyui-window" title="Style Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1200px;height:500px;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'north',split:true, title:'Search'" style="height:130px">
   <div class="easyui-layout" data-options="fit:true">
    <div id="body">
     <code>
                        <form id="LeftOverStyleSearchFrm">
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
      onClick="MsGmtLeftoverSaleOrderStyleDtl.searchLeftOverStyleGrid()">Search</a>
    </p>
   </div>
  </div>
  <div data-options="region:'center'" style="padding:10px;">
   <table id="leftoverstylesearchTbl" style="width:100%">
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
      <th data-options="field:'company_name'" width="80">Company ame</th>
     </tr>
    </thead>
   </table>
  </div>
  <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
   <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
    onclick="$('#LeftOverStyleWindow').window('close')" style="width:80px">Close</a>
  </div>
 </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/JhuteSale/MsAllGmtLeftoverSaleOrderController.js">
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

        $('#gmtleftoversaleorderFrm [id="buyer_id"]').combobox();
        $('#gmtleftoversaleorderFrm [id="advised_by_id"]').combobox();
        $('#gmtleftoversaleorderFrm [id="price_verified_by_id"]').combobox();
        $('#gmtleftoversaleorderstyledtlFrm [id="acc_chart_ctrl_head_id"]').combobox();
        $('#gmtleftoversaleorderpaymentFrm [id="receive_by_id"]').combobox();
    })(jQuery);
</script>