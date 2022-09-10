 <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center',border:true" style="padding:2px">
      <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,title:'Product List'" style="padding:2px">
          <table id="showroomproductscanTbl">
            <thead>
            <tr>
              <th data-options="field:'id'" width="80">ID</th>
              <th data-options="field:'product_desc'" width="250">Product Des.</th>
              <th data-options="field:'qty'" width="60">Qty</th>
              <th data-options="field:'rate'" width="100">Rate</th>
              <th data-options="field:'amount'" width="100">Amount</th>
           </tr>
        </thead>
          </table>
        </div>
        <div data-options="region:'north',border:true" style="height:30px; padding:2px">
          <div class="easyui-layout"  data-options="fit:true">
          <form id="showroomproductscanFrm">
            <div class="row middle">
            <div class="col-sm-2"><strong>Barcode</strong></div>
            <div class="col-sm-10">
            <input type="text" name="bar_code_no" id="bar_code_no" onchange="MsSrmProductSale.getProduct()" />
            </div>
            </div>
          </form>
        </div>
        </div>
      </div>
    </div>
    <div data-options="region:'east',border:true,title:'Invoice', footer:'#showroomsalesFrmFt'" style="width:450px; padding:2px">
      <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,title:'Cart'" style="padding:2px">
          <table id="srmproductsaleTbl">
            <thead>
            <tr>
              <th data-options="field:'id'" width="80">ID</th>
              <th data-options="field:'product_desc'" width="250">Product Des.</th>
              <th data-options="field:'qty'" width="60">Qty</th>
              <th data-options="field:'rate'" width="100">Rate</th>
              <th data-options="field:'amount'" width="100">Amount</th>
           </tr>
        </thead>
          </table>
        </div>
        <div data-options="region:'south',border:true" style="height:300px; padding:2px">
        <form id="showroomsalesFrm">
        <div id="container">
        <div id="body">
        <code>
        <div class="row middle">
        <div class="col-sm-4">Invoice No</div>
        <div class="col-sm-8">
        <input type="text" name="invoice_no" id="invoice_no" disabled />
        </div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Tax</div>
        <div class="col-sm-8">
        <input type="text" name="tax_amount" id="tax_amount"/>
        </div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Discount</div>
        <div class="col-sm-8">
        <input type="text" name="discount_amount" id="discount_amount"/>
        </div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Paid</div>
        <div class="col-sm-8">
        <input type="text" name="paid_amount" id="paid_amount"/>
        </div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Returnable</div>
        <div class="col-sm-8">
        <input type="text" name="return_amount" id="return_amount"/>
        </div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Payment Type</div>
        <div class="col-sm-8">
        <select id="payment_type" name="payment_type">
        <option value="1">Cash</option>
        <option value="2">Credit Card</option>
        <option value="3">Debit Card</option>
        <option value="4">Cheque</option>
        </select>
        </div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Custommer</div>
        <div class="col-sm-8">
        <input type="text" name="customer_name" id="customer_name"/>
        </div>
        </div>
        
        <div class="row middle">
        <div class="col-sm-4">Remarks </div>
        <div class="col-sm-8"><textarea name="remarks" id="remarks" rows="3"></textarea></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Net Paid</div>
        <div class="col-sm-8">
        <input type="text" name="net_paid_amount" id="net_paid_amount"/>
        </div>
        </div>
        </code>
        </div>
        </div>
        <div id="showroomsalesFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('jobFrm')" >Cancel</a>
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsJob.submit()">Pay</a>
        </div>
        </form>
      </div>
      </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/ShowRoom/MsAllSrmProductSaleController.js"></script>

<script>
$(".datepicker" ).datepicker({
  dateFormat: 'yy-mm-dd',
  changeMonth: true,
  changeYear: true
});
</script>