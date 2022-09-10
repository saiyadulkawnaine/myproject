<div class="easyui-layout" data-options="fit:true">
 <div data-options="region:'center',border:true,title:'List',footer:'#salesordercloseTblFt'"
  style="padding:2px;width:600px">
  <table id="salesordercloseTbl" style="width:100%">
   <thead>
    <tr>
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
     <th data-options="field:'approved_by'" width="120">Approved By</th>
     <th data-options="field:'approved_at'" width="80">Approved At</th>
    </tr>
   </thead>
  </table>
  <div id="salesordercloseTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
   Ship Date: <input type="text" name="date_from" id="date_from" class="datepicker"
    style="width: 100px ;height: 23px" />
   <input type="text" name="date_to" id="date_to" class="datepicker" style="width: 100px;height: 23px" />
   <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
    iconCls="icon-search" plain="true" id="save" onClick="MsSalesOrderClose.searchAllClose()">Show</a>
  </div>
 </div>
 <div data-options="region:'west',border:true,title:'Sales Order Close',footer:'#salesordercloseFrmFT'"
  style="width: 400px; padding:2px">
  <form id="salesordercloseFrm">
   <div id="container">
    <div id="body">
     <code>
                        <div class="row" style="display:none;">
                            <input type="hidden" name="id" id="id" value=""/>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Sales Order No</div>
                            <div class="col-sm-7">
                                <input type="text" name="sale_order_no" id="sale_order_no" ondblclick="MsSalesOrderClose.openSalesOrderCloseSearchWindow()" placeholder=" Click Here" value="" />
                                <input type="hidden" name="sale_order_id" id="sale_order_id" />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Style Ref</div>
                            <div class="col-sm-7"><input type="text" name="style_ref" id="style_ref" value="" disabled/></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-5">Job</div>
                            <div class="col-sm-7">
                            <input type="text" name="job_no" id="job_no" disabled/>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Produced Company</div>
                            <div class="col-sm-7">{!! Form::select('produced_company_id', $company,'',array('id'=>'produced_company_id','disabled'=>'disabled')) !!}</div>
                         </div>
                         <div class="row middle">
                            <div class="col-sm-5">Place Date</div>
                            <div class="col-sm-7">
                                <input type="text" name="place_date" id="place_date" disabled />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Receive Date</div>
                            <div class="col-sm-7">
                                <input type="text" name="receive_date" id="receive_date" disabled />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Price/Unit</div>
                            <div class="col-sm-7">
                                <input type="text" name="rate" id="rate" class="number integer" disabled/>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">File No</div>
                            <div class="col-sm-7">
                                <input type="text" name="file_no" id="file_no" disabled />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Internal Ref.</div>
                            <div class="col-sm-7">
                                <input type="text" name="internal_ref" id="internal_ref" disabled />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">TNA To </div>
                            <div class="col-sm-7">
                                <input type="text" name="tna_to" id="tna_to" disabled />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">TNA From </div>
                            <div class="col-sm-7">
                                <input type="text" name="tna_from" id="tna_from" disabled />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-5">Status </div>
                            <div class="col-sm-7">
                                <input type="text" name="order_status" id="order_status" disabled />
                            </div>
                        </div>
                    </code>
     {{-- ---------------------------- --}}
     <code>
       <div class="row middle">
           <div class="col-sm-5">Remarks</div>
           <div class="col-sm-7">
               <textarea name="remarks" id="remarks"></textarea>
           </div>
       </div>
      </code>
    </div>
   </div>
   <div id="salesordercloseFrmFT" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
     iconCls="icon-save" plain="true" id="save" onClick="MsSalesOrderClose.submit()">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
     iconCls="icon-remove" plain="true" id="delete" onClick="MsSalesOrderClose.resetForm()">Reset</a>
    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
     iconCls="icon-remove" plain="true" id="delete" onClick="MsSalesOrderClose.remove()">Delete</a>
   </div>
  </form>
 </div>
</div>


<!--========== Sales Order Search Window  ===============--->
<div id="opensalesorderclosesearchwindow" class="easyui-window" title="Sales Order Search Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'west',split:true, title:'Search'" style="width:350px">
   <div class="easyui-layout" data-options="fit:true">
    <div id="body">
     <code>
                        <form id="salesorderclosesearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Style Ref</div>
                                <div class="col-sm-8">
                                    <input type="text" name="style_ref" id="style_ref" value="">
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Job </div>
                                <div class="col-sm-8">
                                    <input type="text" name="job_no" id="job_no" value="">
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Sales Order No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="job_no" id="job_no" value="">
                                </div>
                            </div>
                        </form>
                    </code>
    </div>
    <p class="footer">
     <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px"
      onClick="MsSalesOrderClose.searchSalesOrder()">Search</a>
    </p>
   </div>
  </div>
  <div data-options="region:'center'" style="padding:10px;">
   <table id="salesorderclosesearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="50">ID</th>
      <th data-options="field:'sale_order_no'" width="100">Sales Order No.</th>
      <th data-options="field:'style_ref'" width="100">Style Ref.</th>
      <th data-options="field:'job_no'" width="100">Job NO.</th>
      <th data-options="field:'qty'" width="70" align="right">Qty</th>
      <th data-options="field:'rate'" width="60" align="right">Rate</th>
      <th data-options="field:'amount'" width="80" align="right">Amount</th>
      <th data-options="field:'lead_time'" width="80" align="right">Lead Time</th>
      <th data-options="field:'place_date'" width="80">Place Date</th>
      <th data-options="field:'receive_date'" width="80">Receive Date</th>
      <th data-options="field:'ship_date'" width="80">Ship Date</th>
      <th data-options="field:'produced_company'" width="80">Produced Company</th>
      <th data-options="field:'file_no'" width="70">File No</th>
     </tr>
    </thead>
   </table>
  </div>
  <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
   <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
    onclick="$('#opensalesorderclosesearchwindow').window('close')" style="width:80px">Close</a>
  </div>
 </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsSalesOrderCloseController.js"></script>
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
    changeYear: true,
    });
    $('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
    this.value = this.value.replace(/[^0-9\.]/g, '');
    }
    });

})(jQuery);

</script>