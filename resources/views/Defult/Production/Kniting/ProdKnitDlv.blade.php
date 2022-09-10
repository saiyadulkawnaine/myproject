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

 .flex-container>div {
  background-color: #021344;
  margin: 5px;
  padding: 10px;
  font-size: 18px;
  width: 33.33%;
  color: #ffffff;
  font-weight: bold;
 }

</style>
<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="prodknitdlvtabs">
 <div title="Delivery" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List',footer:'#prodknitdlvTblFt'" style="padding:2px">
    <table id="prodknitdlvTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="40">ID</th>
       <th data-options="field:'dlv_no'" width="100">Dlv. No</th>
       <th data-options="field:'dlv_date'" width="80">Dlv Date</th>
       <th data-options="field:'company_name'" width="80">Company</th>
       <th data-options="field:'store_name'" width="80">Store Name</th>
       <th data-options="field:'remarks'" width="200">Remarks</th>
      </tr>
     </thead>
    </table>
    <div id="prodknitdlvTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     Company: {!! Form::select('company_search_id',
     $company,'',array('id'=>'company_search_id','style'=>'width:
     100px;
     border-radius:2px; height:23px')) !!}
     Store: {!! Form::select('store_search_id',
     $store,'',array('id'=>'store_search_id','style'=>'width:
     100px;
     border-radius:2px; height:23px')) !!}
     Dlv Date: <input type="text" name="from_date" id="from_date" class="datepicker"
      style="width: 100px ;height: 23px" />
     <input type="text" name="to_date" id="to_date" class="datepicker" style="width: 100px;height: 23px" />
     <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
      iconCls="icon-search" plain="true" id="save" onClick="MsProdKnitDlv.searchProdKint()">Show</a>
    </div>
   </div>
   <div data-options="region:'west',border:true,title:'Delivery Reference',footer:'#prodknitdlvFrmft'"
    style="width: 350px; padding:2px">
    <form id="prodknitdlvFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row">
                                  <div class="col-sm-4">Dlv. No</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="dlv_no" id="dlv_no" readonly />
                                  <input type="hidden" name="id" id="id" readonly />
                                  </div>
                                </div>
                                <div class="row middle">
                                  <div class="col-sm-4 req-text">Dlv Date</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="dlv_date" id="dlv_date" class="datepicker" placeholder="yyyy-mm-dd" />
                                  </div>
                                </div>
                                
                                <div class="row middle">
                                  <div class="col-sm-4 req-text">Company</div>
                                  <div class="col-sm-8">
                                    {!! Form::select('company_id',$company,'',array('id'=>'company_id')) !!}
                                  </div>
                                </div>

                                <div class="row middle">
                                  <div class="col-sm-4 req-text">Store</div>
                                  <div class="col-sm-8">
                                    {!! Form::select('store_id',$store,'',array('id'=>'store_id')) !!}
                                  </div>
                                </div>

                                <div class="row middle">
                                  <div class="col-sm-4">Buyer</div>
                                  <div class="col-sm-8">
                                    {!! Form::select('buyer_id',$buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                                  </div>
                                </div>

                                
                                
                                <div class="row middle">
                                  <div class="col-sm-4">Remarks</div>
                                  <div class="col-sm-8">
                                  <input type="text" name="remarks" id="remarks"/>
                                  </div>
                                </div>
                            </code>
      </div>
     </div>
     <div id="prodknitdlvFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsProdKnitDlv.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodknitdlvFrm')">Reset</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsProdKnitDlv.remove()">Delete</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf"
       plain="true" id="cfdc" onClick="MsProdKnitDlv.pdf()">GFDC</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf"
       plain="true" id="cfdc" onClick="MsProdKnitDlv.challan()">Challan</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-pdf"
       plain="true" id="cfdc" onClick="MsProdKnitDlv.bill()">Bill</a>
     </div>
    </form>
   </div>
  </div>
 </div>
 <div title="Roll" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List',footer:'#prodknitdlvrollTblft'" style="padding:2px">
    <table id="prodknitdlvrollTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="70">ID</th>
       <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
       <th data-options="field:'custom_no'" width="70">Custom No</th>
       <th data-options="field:'qc_pass_qty'" width="80">Delv. Qty</th>
       <th data-options="field:'body_part'" width="100">Body Part</th>
       <th data-options="field:'fabrication'" width="200">Fabric <br />Description</th>
       <th data-options="field:'fabric_shape'" width="70">Fabric <br />Shape</th>
       <th data-options="field:'fabric_look'" width="70">Fabric <br />Look</th>
       <th data-options="field:'gsm_weight'" width="40">GSM</th>
       <th data-options="field:'dia_width'" width="40">Dia/<br />Widht</th>
       <th data-options="field:'measurement'" width="70">Measurement</th>
       <th data-options="field:'roll_length'" width="70">Roll Length</th>
       <th data-options="field:'stitch_length'" width="70">Stitch<br />Length</th>
       <th data-options="field:'shrink_per'" width="60">Shrink %</th>
       <th data-options="field:'colorrange_name'" width="80">Color Range</th>
       <th data-options="field:'fabric_color_name'" width="80">Fabric Color</th>



       <th data-options="field:'qty_pcs'" width="100">PCS</th>
       <th data-options="field:'prod_no'" width="70">Prod. Ref</th>
       <th data-options="field:'floor_name'" width="100">Prod. <br />Floor</th>
       <th data-options="field:'machine_no'" width="100">M/C No</th>
       <th data-options="field:'machine_dia'" width="60">M/C Dia</th>
       <th data-options="field:'machine_gg'" width="60">M/C Gauge</th>
       <th data-options="field:'shift_name'" width="80">Shift Name</th>
       <th data-options="field:'sale_order_no'" width="100">Order No</th>
       <th data-options="field:'style_ref'" width="100">Style Ref</th>
       <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
       <th data-options="field:'gmt_sample'" width="100">Gmt Sample</th>
       <th data-options="field:'supplier_name'" width="100">Produced By</th>
       <th data-options="field:'customer_name'" width="100">Produced For</th>
       <th data-options="field:''" width="100" formatter="MsProdKnitDlvRoll.formatDetail">Action</th>
      </tr>
     </thead>
    </table>
    <div id="prodknitdlvrollTblft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
      iconCls="icon-save" plain="true" id="save" onClick="MsProdKnitDlvRoll.import()">Import</a>

    </div>
   </div>
  </div>
 </div>
</div>

<div id="prodknitdlvrollWindow" class="easyui-window" title="Production Knitting Roll"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'south',border:true,footer:'#prodknitdlvrollWindowFt'" style="padding:2px; height: 250px">
   <table id="prodknitdlvrollselectedTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="70">ID</th>
      <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
      <th data-options="field:'custom_no'" width="70">Custom No</th>
      <th data-options="field:'qc_pass_qty'" width="80">Delv. Qty</th>
      <th data-options="field:'body_part'" width="100">Body Part</th>
      <th data-options="field:'fabrication'" width="200">Fabric <br />Description</th>
      <th data-options="field:'fabric_shape'" width="70">Fabric <br />Shape</th>
      <th data-options="field:'fabric_look'" width="70">Fabric <br />Look</th>
      <th data-options="field:'gsm_weight'" width="40">GSM</th>
      <th data-options="field:'dia_width'" width="40">Dia/<br />Widht</th>
      <th data-options="field:'measurement'" width="70">Measurement</th>
      <th data-options="field:'roll_length'" width="70">Roll Length</th>
      <th data-options="field:'stitch_length'" width="70">Stitch<br />Length</th>
      <th data-options="field:'shrink_per'" width="60">Shrink %</th>
      <th data-options="field:'colorrange_name'" width="80">Color Range</th>
      <th data-options="field:'fabric_color_name'" width="80">Fabric Color</th>



      <th data-options="field:'qty_pcs'" width="100">PCS</th>
      <th data-options="field:'prod_no'" width="70">Prod. Ref</th>
      <th data-options="field:'floor_name'" width="100">Prod. <br />Floor</th>
      <th data-options="field:'machine_no'" width="100">M/C No</th>
      <th data-options="field:'machine_dia'" width="60">M/C Dia</th>
      <th data-options="field:'machine_gg'" width="60">M/C Gauge</th>
      <th data-options="field:'shift_name'" width="80">Shift Name</th>
      <th data-options="field:'sale_order_no'" width="100">Order No</th>
      <th data-options="field:'style_ref'" width="100">Style Ref</th>
      <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
      <th data-options="field:'gmt_sample'" width="100">Gmt Sample</th>
      <th data-options="field:'supplier_name'" width="100">Produced By</th>
      <th data-options="field:'customer_name'" width="100">Produced For</th>
     </tr>
    </thead>
   </table>
   <div id="prodknitdlvrollWindowFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">


    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
     iconCls="icon-save" plain="true" id="save" onClick="MsProdKnitDlvRoll.submit()">Save</a>
   </div>
  </div>
  <div data-options="region:'center',border:true,footer:'#prodknitdlvrollselectionTblFt'" style="padding:2px">
   <table id="prodknitdlvrollselectionTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="70">ID</th>
      <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
      <th data-options="field:'custom_no'" width="70">Custom No</th>
      <th data-options="field:'qc_pass_qty'" width="80">Delv. Qty</th>
      <th data-options="field:'body_part'" width="100">Body Part</th>
      <th data-options="field:'fabrication'" width="200">Fabric <br />Description</th>
      <th data-options="field:'fabric_shape'" width="70">Fabric <br />Shape</th>
      <th data-options="field:'fabric_look'" width="70">Fabric <br />Look</th>
      <th data-options="field:'gsm_weight'" width="40">GSM</th>
      <th data-options="field:'dia_width'" width="40">Dia/<br />Widht</th>
      <th data-options="field:'measurement'" width="70">Measurement</th>
      <th data-options="field:'roll_length'" width="70">Roll Length</th>
      <th data-options="field:'stitch_length'" width="70">Stitch<br />Length</th>
      <th data-options="field:'shrink_per'" width="60">Shrink %</th>
      <th data-options="field:'colorrange_name'" width="80">Color Range</th>
      <th data-options="field:'fabric_color_name'" width="80">Fabric Color</th>



      <th data-options="field:'qty_pcs'" width="100">PCS</th>
      <th data-options="field:'prod_no'" width="70">Prod. Ref</th>
      <th data-options="field:'floor_name'" width="100">Prod. <br />Floor</th>
      <th data-options="field:'machine_no'" width="100">M/C No</th>
      <th data-options="field:'machine_dia'" width="60">M/C Dia</th>
      <th data-options="field:'machine_gg'" width="60">M/C Gauge</th>
      <th data-options="field:'shift_name'" width="80">Shift Name</th>
      <th data-options="field:'sale_order_no'" width="100">Order No</th>
      <th data-options="field:'style_ref'" width="100">Style Ref</th>
      <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
      <th data-options="field:'gmt_sample'" width="100">Gmt Sample</th>
      <th data-options="field:'supplier_name'" width="100">Produced By</th>
      <th data-options="field:'customer_name'" width="100">Produced For</th>

     </tr>
    </thead>
   </table>
   <div id="prodknitdlvrollselectionTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    Knitted Company:{!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width: 100px
    ;height: 23px')) !!}
    QC Date: <input type="text" name="from_qc_date" id="from_qc_date" class="datepicker"
     style="width: 100px ;height: 23px" />
    <input type="text" name="to_qc_date" id="to_qc_date" class="datepicker" style="width: 100px;height: 23px" />
    Prod. Ref: <input type="text" name="prod_ref_no" id="prod_ref_no" style="width: 100px ;height: 23px" />
    Prod Date: <input type="text" name="pord_date_from" id="pord_date_from" class="datepicker"
     style="width: 100px ;height: 23px" />
    <input type="text" name="prod_date_to" id="prod_date_to" class="datepicker" style="width: 100px;height: 23px" />
    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
     iconCls="icon-save" plain="true" id="save" onClick="MsProdKnitDlvRoll.getRoll()">Show</a>

   </div>

  </div>
 </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Production/Kniting/MsProdKnitDlvController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Production/Kniting/MsProdKnitDlvRollController.js">
</script>
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
      $('#prodknitdlvFrm [id="buyer_id"]').combobox();
   })(jQuery);
</script>