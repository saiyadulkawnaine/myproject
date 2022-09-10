<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="acccostdistributiondtltabs">
 <div title="Cost Distribution Entry Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'Cost Distribution Details List'" style="padding:2px">
    <table id="acccostdistributiondtlTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="80">ID</th>
       <th data-options="field:'cost_type_id'" width="100">Cost Type</th>
       <th data-options="field:'sale_order_no'" width="100">Sales Order</th>
       <th data-options="field:'amount'" width="100">Amount</th>
       <th data-options="field:'remarks'" width="100">Remarks</th>
      </tr>
     </thead>
    </table>
   </div>
   <div data-options="region:'west',border:true,title:'Add New Entry',footer:'#ft2'" style="width: 350px; padding:2px">
    <form id="acccostdistributiondtlFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row middle">
                                 <div class="col-sm-4 req-text">Cost Type</div>
                                 <div class="col-sm-8">
                                  {!! Form::select('cost_type_id', $othercosthead,'',array('id'=>'cost_type_id')) !!}
                                  <input type="hidden" name="id" id="id" value="" />
                                 </div>
                                </div>
                                <div class="row middle">
                                 <div class="col-sm-4">Order No</div>
                                 <div class="col-sm-8">
                                  <input type="text" name="sale_order_no" id="sale_order_no" value="" onclick="MsAccCostDistributionDtl.openSalesOrderWindow()"
                                   placeholder="Click" readonly />
                                  <input type="hidden" name="sale_order_id" id="sale_order_id" value="" />
                                 </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Amount</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount" id="amount" value="" class="number integer" />
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
     <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsAccCostDistributionDtl.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsAccCostDistributionDtl.resetForm()">Reset</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsAccCostDistributionDtl.remove()">Delete</a>
     </div>

    </form>
   </div>
  </div>
 </div>
</div>

{{-- Order window --}}
<div id="opensalesorderwindow" class="easyui-window" title="Sales Order No Search Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
   <div class="easyui-layout" data-options="fit:true">
    <div id="body">
     <code>
                        <form id="salesordersearchFrm">
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
      onClick="MsAccCostDistributionDtl.searchSalesOrderGrid()">Search</a>
    </p>
   </div>
  </div>
  <div data-options="region:'center'" style="padding:10px;">
   <table id="salesordersearchTbl" style="width:100%">
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
      <th data-options="field:'produced_company_name'" width="80">Produced Company</th>
     </tr>
    </thead>
   </table>
  </div>
  <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
   <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
    onclick="$('#opensalesorderwindow').window('close')" style="width:80px">Close</a>
  </div>
 </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Account/MsAccCostDistributionDtlController.js"></script>
<script>
 (function(){    
    $(".form_date").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        beforeShowDay: function(date) {
            if (date.getDate() == 1) {
                return [true, ''];
            }
            return [false, ''];
        }
    });

    function LastDayOfMonth(Year, Month) {
        return (new Date((new Date(Year, Month + 1, 1)) - 1)).getDate();
    }

    $('.to_date').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        beforeShowDay: function(date) {
            //getDate() returns the day (0-31)
            if (date.getDate() == LastDayOfMonth(date.getFullYear(), date.getMonth())) {
                return [true, ''];
            }
            return [false, ''];
        }
    });
    })(jQuery);
</script>