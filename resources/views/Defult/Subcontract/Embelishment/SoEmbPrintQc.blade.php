<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="soembprintqctabs">
 {{-- Reference Details --}}
 <div title="Reference Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="soembprintqcTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="40">ID</th>
       <th data-options="field:'company_name'" width="80">Company Name</th>
       <th data-options="field:'shiftname_id'" width="80">Shift Name</th>
       <th data-options="field:'so_qc_date'" width="100">Prod.Qc Date</th>
       <th data-options="field:'buyer_name'" width="100">Costomer</th>
       <th data-options="field:'remarks'" width="100">Remarks</th>
      </tr>
     </thead>
    </table>
   </div>
   <div data-options="region:'west',border:true,title:'Add Printing References Entry',footer:'#ft2'"
    style="width: 350px; padding:2px">
    <form id="soembprintqcFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Company</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                 <div class="col-sm-5 req-text">Costomer</div>
                                 <div class="col-sm-7">
                                  {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width:100%;border-radius:2px')) !!}
                                 </div>
                                </div>
                                <div class="row middle">
                                 <div class="col-sm-5 req-text">Prod, Qc Date</div>
                                 <div class="col-sm-7">
                                  <input type="text" name="so_qc_date" id="so_qc_date" class="datepicker" placeholder="Date" />
                                 </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Shift Name </div>
                                    <div class="col-sm-7">
                                        {!! Form::select('shiftname_id', $shiftname,'',array('id'=>'shiftname_id')) !!}
                                    </div>
                                </div>  
                                <div class="row middle">
                                    <div class="col-sm-5">Remarks</div>
                                    <div class="col-sm-7">
                                        <textarea name="remarks" id="remarks" ></textarea>
                                    </div>
                                </div>    
                            </code>
      </div>
     </div>
     <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsSoEmbPrintQc.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('soembprintqcFrm')">Reset</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmbPrintQc.remove()">Delete</a>
     </div>
    </form>
   </div>
  </div>
 </div>

 {{-- Order Details --}}
 <div title="Qc Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'Qc List'" style="padding:2px">
    <table id="soembprintqcdtlTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="20">ID</th>
       <th data-options="field:'sales_order_no'" width="100">Emb. Sales Order</th>
       <th data-options="field:'gmtspart'" width="100">Body Part</th>
       <th data-options="field:'item_desc'" width="100">GMT Part</th>
       <th data-options="field:'gmt_color'" width="100">GMT Color</th>
       <th data-options="field:'design_no'" width="100">Design No</th>
       <th data-options="field:'prod_source_id'" width="100">Prod.Source</th>
       <th data-options="field:'prod_hour'" width="100">Prod.Hour</th>
       <th data-options="field:'qc_pass'" width="90">Qc Pass</th>
       <th data-options="field:'reject'" width="90">Reject</th>
       <th data-options="field:'alter'" width="90">Alter</th>
       <th data-options="field:'replace'" width="90">Replace</th>
       <th data-options="field:'remarks'" width="100">Remarks</th>
      </tr>
     </thead>
    </table>
   </div>
   <div data-options="region:'west',border:true,title:'Add Qc Entry',footer:'#ft4'" style="width: 350px; padding:2px">
    <form id="soembprintqcdtlFrm">
     <div id="container">
      <div id="body">
       <code>
                                    <div class="row middle" style="display:none">
                                        <input type="hidden" name="id" id="id" value="" />
                                        <input type="hidden" name="so_emb_print_qc_id" id="so_emb_print_qc_id" value="" />
                                    </div>
                                    <div class="row middle">
                                      <div class="col-sm-4">Prod Source</div>
                                      <div class="col-sm-8">
                                       {!! Form::select('prod_source_id', $productionsource,'',array('id'=>'prod_source_id')) !!}
                                      </div>
                                     </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">Emb. Sales Order</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="sales_order_no" id="sales_order_no" value="" onclick="
                                            MsSoEmbPrintQcDtl.openSoEmbPrintWindow()" placeholder="Click" readonly />
                                            <input type="hidden" name="so_emb_ref_id" id="so_emb_ref_id" value="" />
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">Body Part</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="gmtspart" id="gmtspart" placeholder="dispaly" disabled/>
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">GMT Item</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="item_desc" id="item_desc" placeholder="dispaly" disabled/>
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">GMT Color</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="gmt_color" id="gmt_color" placeholder="dispaly" disabled/>
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">Design No</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="design_no" id="design_no" placeholder="dispaly" disabled/>
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">Prod Hour</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="prod_hour" id="prod_hour" />
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">Qc Pass</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="qc_pass" id="qc_pass" class="number integer"/>
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">Reject</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="reject" id="reject" class="number integer"/>
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">Alter</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="alter" id="alter" class="number integer"/>
                                        </div>
                                    </div>
                                    <div class="row middle">
                                     <div class="col-sm-4">Replace</div>
                                     <div class="col-sm-8">
                                      <input type="text" name="replace" id="replace" class="number integer" />
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
     <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsSoEmbPrintQcDtl.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('soembprintqcdtlFrm')">Reset</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmbPrintQcDtl.remove()">Delete</a>
     </div>
    </form>
   </div>
  </div>
 </div>

 {{-- Defect Details --}}
 <div title="Defect Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">

   <div data-options="region:'center',border:true,title:'Defect Quantity',footer:'#depft'" style="padding:2px">
    <form id="soembprintqcdtldeftFrm">
     <code id="soembprintqcdtldeftmatrix"></code>
     <div id="depft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsSoEmbPrintQcDtlDeft.submit()">Save</a>
     </div>
    </form>
   </div>
  </div>
 </div>
</div>
{{-- Order window --}}
<div id="opensoembentorderwindow" class="easyui-window" title="Production Print Search Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
   <div class="easyui-layout" data-options="fit:true">
    <div id="body">
     <code>
                        <form id="opensoembentordersearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Order No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="sale_order_no" id="sale_order_no" value="" />
                                </div>
                            </div>
                        </form>
                    </code>
    </div>
    <p class="footer">
     <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px"
      onClick="MsSoEmbPrintQcDtl.searchSoEmbEntOrderGrid()">Search</a>
    </p>
   </div>
  </div>
  <div data-options="region:'center'" style="padding:10px;">
   <table id="opensoembentordersearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="40">ID</th>
      <th data-options="field:'sales_order_no'" width="100">Order No</th>
      <th data-options="field:'gmtspart'" width="100">Body Part</th>
      <th data-options="field:'item_desc'" width="100">GMT Part</th>
      <th data-options="field:'gmt_color'" width="100">GMT Color</th>
      <th data-options="field:'design_no'" width="100">Design No</th>
      {{-- <th data-options="field:'prod_source_id'" width="100">Prod.Source</th>
      <th data-options="field:'prod_hour'" width="100">Prod.Hour</th>
      <th data-options="field:'qty'" width="90">Qty</th> --}}
      <th data-options="field:'remarks'" width="100">Remarks</th>
     </tr>
    </thead>
   </table>
  </div>
  <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
   <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
    onclick="$('#opensoembentorderwindow').window('close')" style="width:80px">Close</a>
  </div>
 </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Embelishment/MsAllSoEmbPrintQcController.js">
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
         changeYear: true
      });

      $('.integer').keyup(function () {
         if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
         }
      });

      $('#soembprintqcFrm [id="buyer_id"]').combobox();

   })(jQuery);

$(function() {
$('#prod_hour').timepicker(
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