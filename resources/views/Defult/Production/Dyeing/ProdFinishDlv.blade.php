<style>
.flex-form-container {
  display: flex;
  background-color: #f1f1f1;
  /*height: 100%;*/
  justify-content: center;
  text-transform: uppercase;
}
.flex-container {
  display: flex;
  background-color: #f1f1f1;
  /*height: 100%;*/
  justify-content: center;
  text-transform: uppercase;
}

.flex-container > div {
  background-color: #021344;
  margin: 5px;
  padding: 10px;
  font-size: 18px;
  width:33.33%;
  color:#ffffff;
  font-weight: bold;
}
</style>
<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="prodfinishdlvtabs">
    <div title="Delivery" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="prodfinishdlvTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'dlv_no'" width="100">Dlv. No</th>
                            <th data-options="field:'dlv_date'" width="80">Dlv Date</th>
                            <th data-options="field:'company_name'" width="80">Company</th>
                            <th data-options="field:'location_name'" width="70">Location</th>
                            <th data-options="field:'store_name'" width="80">To Store</th>
                            <th data-options="field:'buyer_name'" width="70">Customer</th>
                            <th data-options="field:'remarks'" width="200">Remarks</th>
                            <th data-options="field:'driver_name'" width="100">Driver Name</th>
                            <th data-options="field:'driver_contact_no'" width="120">Driver Contact</th>
                            <th data-options="field:'driver_license_no'" width="120">Driver License No</th>
                            <th data-options="field:'lock_no'" width="80">Lock No</th>
                            <th data-options="field:'truck_no'" width="100">Truck No</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Delivery Reference',footer:'#prodfinishdlvFrmft'" style="width: 350px; padding:2px">
                <form id="prodfinishdlvFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                  <div class="col-sm-5">Dlv. No</div>
                                  <div class="col-sm-7">
                                  <input type="text" name="dlv_no" id="dlv_no" readonly />
                                  <input type="hidden" name="id" id="id" readonly />
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-5 req-text">Dlv Date</div>
                                  <div class="col-sm-7">
                                  <input type="text" name="dlv_date" id="dlv_date" class="datepicker" placeholder="yyyy-mm-dd" />
                                  </div>
                                </div>
                                
                                <div class="row middle">
                                  <div class="col-sm-5 req-text">Company</div>
                                  <div class="col-sm-7">
                                    {!! Form::select('company_id',$company,'',array('id'=>'company_id')) !!}
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-5">Location </div>
                                  <div class="col-sm-7">
                                    {!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-5 req-text">Store</div>
                                  <div class="col-sm-7">
                                    {!! Form::select('store_id',$store,'',array('id'=>'store_id')) !!}
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-5 req-text">Customer</div>
                                  <div class="col-sm-7">
                                    {!! Form::select('buyer_id',$buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-5">Remarks</div>
                                  <div class="col-sm-7">
                                  <input type="text" name="remarks" id="remarks"/>
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-5">Driver Name </div>
                                  <div class="col-sm-7">
                                  <input type="text" name="driver_name" id="driver_name" value="" />
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-5">Driver Contact</div>
                                  <div class="col-sm-7">
                                  <input type="text" name="driver_contact_no" id="driver_contact_no" placeholder="(+88) 01234567890" />
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-5">Driver License No</div>
                                  <div class="col-sm-7">
                                    <input type="text" name="driver_license_no" id="driver_license_no" value="" />
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-5">Truck No</div>
                                  <div class="col-sm-7">
                                      <input type="text" name="truck_no" id="truck_no" value="" placeholder=" write" />
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-5">Lock No</div>
                                  <div class="col-sm-7">
                                    <input type="text" name="lock_no" id="lock_no" value="" />
                                  </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="prodfinishdlvFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdFinishDlv.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodfinishdlvFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdFinishDlv.remove()">Delete</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="cfdc" onClick="MsProdFinishDlv.pdf()">BILL</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="cfdc" onClick="MsProdFinishDlv.pdfshort()">BILL-S</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf" plain="true" id="cfdc" onClick="MsProdFinishDlv.challan()">Challan</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Roll" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List',footer:'#prodfinishdlvrollTblft'" style="padding:2px" >
                <table id="prodfinishdlvrollTbl" style="width:100%">
                    <thead>
                        <tr>
                          <th data-options="field:'del'" width="60" formatter="MsProdFinishDlvRoll.formatDetail">Action</th>
                          <th data-options="field:'id'" width="70">ID</th>
                          <th data-options="field:'batch_no'" width="70">Batch No</th>
                          <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
                          <th data-options="field:'custom_no'" width="70">Custom No</th>
                          <th data-options="field:'batch_qty'" width="80">Batch Qty</th>
                          <th data-options="field:'roll_weight'" width="80">Delv. Qty</th>
                          <th data-options="field:'body_part'" width="100">Body Part</th>
                          <th data-options="field:'fabrication'" width="200">Fabric <br/>Description</th>
                          <th data-options="field:'fabric_shape'" width="70">Fabric <br/>Shape</th>
                          <th data-options="field:'fabric_look'" width="70">Fabric <br/>Look</th>
                          <th data-options="field:'gsm_weight'" width="40">GSM</th>
                          <th data-options="field:'dia_width'" width="40">Dia/<br/>Widht</th>
                          <th data-options="field:'measurement'" width="70">Measurement</th>
                          <th data-options="field:'roll_length'" width="70">Roll Length</th>
                          <th data-options="field:'stitch_length'" width="70">Stitch<br/>Length</th>
                          <th data-options="field:'shrink_per'" width="60">Shrink %</th>
                          <th data-options="field:'colorrange_name'" width="80">Color Range</th>
                          <th data-options="field:'fabric_color_name'" width="80">Roll Color</th>
                          <th data-options="field:'batch_color_name'" width="80">Batch Color</th>
                          <th data-options="field:'qty_pcs'" width="100">PCS</th>
                          <th data-options="field:'prod_no'" width="70">Prod. Ref</th>
                          <th data-options="field:'floor_name'" width="100">Prod. <br/>Floor</th>
                          <th data-options="field:'machine_no'" width="100">M/C No</th>
                          <th data-options="field:'machine_dia'" width="60">M/C Dia</th>
                          <th data-options="field:'machine_gg'" width="60">M/C Gauge</th>
                          <th data-options="field:'shift_name'" width="80">Shift Name</th>
                          <th data-options="field:'sale_order_no'" width="100">Order No</th>
                          <th data-options="field:'style_ref'" width="100">Style Ref</th>
                          <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
                          <th data-options="field:'gmt_sample'" width="100">Gmt Sample</th>
                          <th data-options="field:'dyeing_sales_order_no'" width="100">Dyeing Sales Order</th>
                          <th data-options="field:'dyeing_company'" width="100">Dyeing By</th>
                          <th data-options="field:'dyeing_customer'" width="100">Dyeing For</th>
                          <th data-options="field:'supplier_name'" width="100">Knitted By</th>
                          <th data-options="field:'customer_name'" width="100">Knitted For</th>
                          <th data-options="field:'bnf_company_name'" width="100">Bnf. Comapny</th>
                          <th data-options="field:'prod_company_name'" width="100">Prod Comapny</th>
                          
                        </tr>
                    </thead>
                </table>
                 <div id="prodfinishdlvrollTblft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdFinishDlvRoll.import()">Import</a>
                       
                    </div>
            </div>
        </div>
    </div>
</div>

<div id="prodfinishdlvrollWindow" class="easyui-window" title="Production Knitting Roll" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'south',border:true,footer:'#prodfinishdlvrollWindowFt'" style="padding:2px; height: 250px">
            <table id="prodfinishdlvrollselectedTbl" style="width:100%">
            <thead>
            <tr>
              <th data-options="field:'id'" width="70">ID</th>
              <th data-options="field:'batch_no'" width="70">Batch No</th>
              <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
              <th data-options="field:'custom_no'" width="70">Custom No</th>
              <th data-options="field:'sale_order_no'" width="100">Order No</th>
              <th data-options="field:'style_ref'" width="100">Style Ref</th>
              <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
              <!-- <th data-options="field:'root_roll_qty',halign:'center'" width="80" align="right">Org. <br/>Batch Qty</th> -->
              <th data-options="field:'batch_qty',halign:'center'" width="80" align="right">Batch Qty</th>
              <th data-options="field:'qc_pass_qty',halign:'center'" width="80" align="right">QC Pass Qty</th>
              <th data-options="field:'reject_qty',halign:'center'" width="80" align="right">Reject Qty</th>
              <th data-options="field:'qc_gsm_weight'" width="40">GSM</th>
              <th data-options="field:'qc_dia_width'" width="40">Dia/<br/>Widht</th>
              <th data-options="field:'grade'" width="40">Grade</th>

              <th data-options="field:'colorrange_name'" width="80">Color Range</th>
              <th data-options="field:'dyeing_color'" width="80">Roll Color</th>
              <th data-options="field:'batch_color'" width="80">Batch Color</th>
              <th data-options="field:'body_part'" width="100">Body Part</th>
              <th data-options="field:'fabrication'" width="200">Fabric <br/>Description</th>
              <th data-options="field:'fabric_shape'" width="70">Fabric <br/>Shape</th>
              <th data-options="field:'fabric_look'" width="70">Fabric <br/>Look</th>
              <th data-options="field:'gsm_weight'" width="40">GSM</th>
              <th data-options="field:'dia_width'" width="40">Dia/<br/>Widht</th>
              <th data-options="field:'measurement'" width="70">Measurement</th>
              <th data-options="field:'roll_length'" width="70">Roll Length</th>
              <th data-options="field:'stitch_length'" width="70">Stitch<br/>Length</th>
              <th data-options="field:'shrink_per'" width="60">Shrink %</th>

              <th data-options="field:'dyetype'" width="80">Dyeing Type</th>

              <th data-options="field:'prod_no'" width="70">Prod. Ref</th>

              <th data-options="field:'gmt_sample'" width="100">Gmt Sample</th>
              <th data-options="field:'dyeing_sales_order_no'" width="100">Dyeing Sales Order</th>
              <th data-options="field:'dyeing_company'" width="100">Dyeing By</th>
              <th data-options="field:'dyeing_customer'" width="100">Dyeing For</th>
              {{-- <th data-options="field:'supplier_name'" width="100">knitted By</th>
              <th data-options="field:'customer_name'" width="100">knitted For</th> --}}
              <th data-options="field:'bnf_company_name'" width="100">Bnf. Comapny</th>
              <th data-options="field:'prod_company_name'" width="100">Prod Comapny</th>
            </tr>
            </thead>
            </table>
            <div id="prodfinishdlvrollWindowFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
              <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdFinishDlvRoll.submit()">Save</a>
            </div>
        </div>
        <div data-options="region:'center',border:true,footer:'#prodfinishdlvrollselectionTblFt'" style="padding:2px">
            <table id="prodfinishdlvrollselectionTbl" style="width:100%">
            <thead>
              <tr>
                <th data-options="field:'id'" width="70">ID</th>
                <th data-options="field:'batch_no'" width="70">Batch No</th>
                <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
                <th data-options="field:'custom_no'" width="70">Custom No</th>
                <th data-options="field:'sale_order_no'" width="100">Order No</th>
                <th data-options="field:'style_ref'" width="100">Style Ref</th>
                <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
                <!-- <th data-options="field:'root_roll_qty',halign:'center'" width="80" align="right">Org. <br/>Batch Qty</th> -->
                <th data-options="field:'batch_qty',halign:'center'" width="80" align="right">Batch Qty</th>
                <th data-options="field:'qc_pass_qty',halign:'center'" width="80" align="right">QC Pass Qty</th>
                <th data-options="field:'reject_qty',halign:'center'" width="80" align="right">Reject Qty</th>
                <th data-options="field:'qc_gsm_weight'" width="40">GSM</th>
                <th data-options="field:'qc_dia_width'" width="40">Dia/<br/>Widht</th>
                <th data-options="field:'grade'" width="40">Grade</th>

                <th data-options="field:'colorrange_name'" width="80">Color Range</th>
                <th data-options="field:'dyeing_color'" width="80">Roll Color</th>
                <th data-options="field:'batch_color'" width="80">Batch Color</th>
                <th data-options="field:'body_part'" width="100">Body Part</th>
                <th data-options="field:'fabrication'" width="200">Fabric <br/>Description</th>
                <th data-options="field:'fabric_shape'" width="70">Fabric <br/>Shape</th>
                <th data-options="field:'fabric_look'" width="70">Fabric <br/>Look</th>
                <th data-options="field:'gsm_weight'" width="40">GSM</th>
                <th data-options="field:'dia_width'" width="40">Dia/<br/>Widht</th>
                <th data-options="field:'measurement'" width="70">Measurement</th>
                <th data-options="field:'roll_length'" width="70">Roll Length</th>
                <th data-options="field:'stitch_length'" width="70">Stitch<br/>Length</th>
                <th data-options="field:'shrink_per'" width="60">Shrink %</th>

                <th data-options="field:'dyetype'" width="80">Dyeing Type</th>

                <th data-options="field:'prod_no'" width="70">Prod. Ref</th>

                <th data-options="field:'gmt_sample'" width="100">Gmt Sample</th>
                <th data-options="field:'dyeing_sales_order_no'" width="100">Dyeing Sales Order</th>
                <th data-options="field:'dyeing_company'" width="100">Dyeing By</th>
                <th data-options="field:'dyeing_customer'" width="100">Dyeing For</th>
                {{-- <th data-options="field:'supplier_name'" width="100">knitted By</th>
                <th data-options="field:'customer_name'" width="100">knitted For</th> --}}
                <th data-options="field:'bnf_company_name'" width="100">Bnf. Comapny</th>
                <th data-options="field:'prod_company_name'" width="100">Prod Comapny</th>
              </tr>
            </thead>
            </table>
            <div id="prodfinishdlvrollselectionTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
              Finish QC Date: <input type="text" name="from_qc_date" id="from_qc_date" class="datepicker" style="width: 100px ;height: 23px" />
              <input type="text" name="to_qc_date" id="to_qc_date" class="datepicker" style="width: 100px;height: 23px" />
              Batch No: <input type="text" name="batch_no" id="batch_no" style="width: 100px ;height: 23px" />
              Batch Date: <input type="text" name="batch_date_from" id="batch_date_from" class="datepicker" style="width: 100px ;height: 23px" />
              <input type="text" name="batch_date_to" id="batch_date_to" class="datepicker" style="width: 100px;height: 23px" />
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdFinishDlvRoll.getRoll()">Show</a>

            </div>
            
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Production/Dyeing/MsProdFinishDlvController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Production/Dyeing/MsProdFinishDlvRollController.js"></script>
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
         if (this.value != this.value.replace(/[^0-9\.]/g, '')) 
         {
            this.value = this.value.replace(/[^0-9\.]/g, '');
         }
      });
      $('#prodfinishdlvFrm [id="buyer_id"]').combobox();
   })(jQuery);
</script>

