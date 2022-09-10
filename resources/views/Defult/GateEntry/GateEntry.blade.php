<div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center',border:true" style="padding:2px">
      <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,title:'Item List'" style="padding:2px">
          <table id="gateentryitemTbl">
            <thead>
              <tr>
                <th data-options="field:'item_id'" width="80">ID</th>
                <th data-options="field:'item_description'" width="130">Product Des.</th>
                <th data-options="field:'po_qty'" width="80">  PO/RQ Qty</th>
                <th data-options="field:'qty',editor:{type:'numberbox',options:{precision:2}}" width="136">  Rcv Qty</th>
                {{-- <th data-options="field:'remarks',editor:{type:'textbox'}" width="136">Remarks</th> --}}
                <th data-options="field:'uom_code'" width="50">UOM</th>
                <th data-options="field:'remarks'" width="100">Remarks</th>
                <th data-options="field:'style_ref'" width="100">Style Ref</th>
                <th data-options="field:'buyer_name'" width="100">Buyer</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
    <div data-options="region:'west',border:true,title:'Gate In Entry - Goods Only', footer:'#gateentryFt'" style="width:450px; padding:2px">
      <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'north',border:true,title:'Entry Detail'" style="padding:2px;height:300px">
          <form id="gateentryFrm">
            <div id="container">
              <div id="body">
                <code>
                    <div class="row middle" style="display:none">
                      <input type="hidden" name="id" id="id" value="" />
                    </div>
                    <div class="row middle">
                      <div class="col-sm-4 req-text">Purchase Through</div>
                      <div class="col-sm-8">
                        {!! Form::select('menu_id',
                          $menu,'',array('id'=>'menu_id','onchange'=>'MsGateEntry.purchaseOnchage(this.value)')) !!}
                      </div>
                    </div>
                    <div class="row middle">
                      <div class="col-sm-4 req-text"><strong>Barcode</strong></div>
                      <div class="col-sm-8">
                        <input type="text" name="barcode_no_id" id="barcode_no_id" onchange="MsGateEntry.getPurchaseNo()" />
                      </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4 req-text">Challan No</div>
                        <div class="col-sm-8">
                          <input type="text" name="challan_no" id="challan_no" value="" />
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Supplier</div>
                        <div class="col-sm-8">
                            <input type="text" name="supplier_name" id="supplier_name" value="" disabled />
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Supplier Contact</div>
                        <div class="col-sm-8">
                            <input type="text" name="supplier_contact" id="supplier_contact" value="" disabled />
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">PO Number</div>
                        <div class="col-sm-8">
                            <input type="text" name="po_no" id="po_no" value="" disabled />
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Requisition No</div>
                        <div class="col-sm-8">
                            <input type="text" name="requisition_no" id="requisition_no" value="" disabled />
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">LC Number</div>
                        <div class="col-sm-8">
                            <input type="text" name="lc_sc_no" id="lc_sc_no" value="" disabled />
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Company</div>
                        <div class="col-sm-8">
                            <input type="text" name="company_name" id="company_name" value="" disabled />
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4">Remarks</div>
                        <div class="col-sm-8">
                          <textarea name="comments" id="comments"></textarea>
                        </div>
                    </div>
                </code>
              </div>
            </div>
            <div id="gateentryFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsGateEntry.resetForm()">Reset</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsGateEntry.submit()">Save</a>
            </div>
          </form>
        </div>
        <div data-options="region:'center',border:true" style="height:80px; padding:2px">
          <table id="gateentryTbl">
            <thead>
              <tr>
                  <th data-options="field:'id'" width="40">Id</th>
                  <th data-options="field:'po_pr_no'" width="80">PO/PR No</th>
                  <th data-options="field:'barcode_no_id'" width="80">Barcode No</th>
                  <th data-options="field:'challan_no'" width="80">Challan No</th> 
                  <th data-options="field:'company_name'" width="60">Company</th> 
                  <th data-options="field:'supplier_name'" width="100">Supplier</th> 
                  <th data-options="field:'master_remarks'" width="100">Remarks</th> 
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/GateEntry/MsGateEntryController.js"></script>

<script>
$(".datepicker" ).datepicker({
  dateFormat: 'yy-mm-dd',
  changeMonth: true,
  changeYear: true
});
$('.integer').keyup(function () {
            if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
            }
        });

$( "#barcode_no_id" ).focus();

</script>