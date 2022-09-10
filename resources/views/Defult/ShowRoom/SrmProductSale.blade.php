 <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center',border:true" style="padding:2px">
      <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,title:'Product List'" style="padding:2px">
          <table id="srmproductscanTbl">
            <thead>
              <tr>
                <th data-options="field:'srm_product_receive_dtl_id'" width="40">ID</th>
                <th data-options="field:'product_desc'" width="130">Product Des.</th>
                <th data-options="field:'qty',editor:{type:'numberbox',options:{precision:0}}" width="60">Qty</th>
                <th data-options="field:'sales_rate'" width="70">Selling<br/>Price</th>
                <th data-options="field:'amount'" width="70">Amount</th>
                <th data-options="field:'vat'" width="50">VAT</th>
                <th data-options="field:'source_tax'" width="50">S.TAX</th>
                <th data-options="field:'gross_amount'" width="80">Gross<br/>Amount</th>
              </tr>
            </thead>
          </table>
        </div>
        <div data-options="region:'north',border:true" style="height:30px; padding:2px">
          <div class="easyui-layout"  data-options="fit:true">
          <form id="srmproductscanFrm" onsubmit="return false;">
            <div class="row middle">
              <div class="col-sm-2"><strong>Barcode</strong></div>
              <div class="col-sm-10">
                <input type="text" name="srm_product_receive_dtl_id" id="srm_product_receive_dtl_id" onchange="MsSrmProductSale.getProduct()" />
              </div>
            </div>
          </form>
        </div>
        </div>
      </div>
    </div>
    <div data-options="region:'east',border:true,title:'Invoice List', footer:'#srmproductsaleFt'" style="width:450px; padding:2px">
      <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,title:'Invoice'" style="padding:2px">
          <form id="srmproductsaleFrm">
            <div id="container">
              <div id="body">
                <code>
                  <div class="row middle">
                    <div class="col-sm-4">Gross Amount</div>
                    <div class="col-sm-8">
                      <input type="text" name="gross_amount" id="gross_amount" disabled/>
                    </div>
                  </div>
                  {{-- <div class="row middle">
                    <div class="col-sm-4">Tax</div>
                    <div class="col-sm-8">
                      <input type="text" name="tax_amount" id="tax_amount"/>
                    </div>
                  </div> --}}
                  <div class="row middle">
                    <div class="col-sm-4">Discount</div>
                    <div class="col-sm-8">
                      <input type="text" name="discount_amount" id="discount_amount" class="number integer" onchange="MsSrmProductSale.afterDiscount()"/>
                    </div>
                  </div>
                  <div class="row middle">
                    <div class="col-sm-4">Net Amount</div>
                    <div class="col-sm-8">
                      <input type="text" name="net_paid_amount" id="net_paid_amount" class="number integer" />
                    </div>
                  </div>
                  <div class="row middle">
                    <div class="col-sm-4">Cash Received</div>
                    <div class="col-sm-8">
                      <input type="text" name="paid_amount" id="paid_amount" class="number integer" onchange="MsSrmProductSale.cashReturn()"/>
                    </div>
                  </div>
                  <div class="row middle">
                    <div class="col-sm-4">Cash Return</div>
                    <div class="col-sm-8">
                      <input type="text" name="return_amount" id="return_amount" class="number integer"/>
                    </div>
                  </div>
                  <div class="row middle">
                    <div class="col-sm-4">Debit Card</div>
                    <div class="col-sm-8">
                      <input type="text" name="debit_card_no" id="debit_card_no"/>
                    </div>
                  </div>
                  <div class="row middle">
                    <div class="col-sm-4">Credit Card</div>
                    <div class="col-sm-8">
                      <input type="text" name="credit_card_no" id="credit_card_no"/>
                    </div>
                  </div>
                  <div class="row middle">
                    <div class="col-sm-4 req-text">Sales Date</div>
                    <div class="col-sm-8">
                      <input type="text" name="scan_date" id="scan_date" class="datepicker" value="{{ date('Y-m-d') }}"/>
                    </div>
                  </div>
                  {{-- <div class="row middle">
                    <div class="col-sm-4 req-text">Payment Type</div>
                    <div class="col-sm-8">
                      {!! Form::select('payment_type_id', $paymentType,1,array('id'=>'payment_type_id')) !!}
                    </div>
                  </div> --}}
                  <div class="row middle">
                    <div class="col-sm-4 req-text">Credit Sale</div>
                    <div class="col-sm-8">
                      {!! Form::select('credit_sale_id', $yesno,0,array('id'=>'credit_sale_id')) !!}
                    </div>
                  </div>
                  <div class="row middle">
                    <div class="col-sm-4">Remarks </div>
                    <div class="col-sm-8">
                      <textarea name="remarks" id="remarks" rows="3"></textarea>
                    </div>
                  </div>
                  <div class="row middle">
                    <div class="col-sm-4">Invoice No</div>
                    <div class="col-sm-8">
                      <input type="text" name="invoice_no" id="invoice_no" readonly>
                    </div>
                  </div>
                  <div class="row middle">
                    <div class="col-sm-4">Custommer</div>
                    <div class="col-sm-8">
                      <input type="text" name="customer_name" id="customer_name"/>
                    </div>
                  </div>
                </code>
              </div>
            </div>
            <div id="srmproductsaleFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
              <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('srmproductsaleFrm')" >Cancel</a>
              <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSrmProductSale.submit()">Pay</a>
            </div>
          </form>
        </div>
        <div data-options="region:'south',border:true" style="height:140px; padding:2px">
          <table id="srmproductsaleTbl">
            <thead>
              <tr>
                <th data-options="field:'id'" width="40">ID</th>
                <th data-options="field:'invoice_no'" width="80">Invoice No</th>
                <th data-options="field:'paid_amount'" width="60">Cash Received</th>
                <th data-options="field:'scan_date'" width="70">Sales Date</th>
                <th data-options="field:'customer_name'" width="100">Customer</th>
                <th data-options="field:'pdf',halign:'center'" width="40" halign:'center' formatter="MsSrmProductSale.formatPdf" align="center">Pdf</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
</div>
<div id="printit">

</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/ShowRoom/MsAllSrmProductSaleController.js"></script>

<script>
$(".datepicker" ).datepicker({
  dateFormat: 'yy-mm-dd',
  changeMonth: true,
  changeYear: true
});
$( "#srm_product_receive_dtl_id" ).focus();

</script>