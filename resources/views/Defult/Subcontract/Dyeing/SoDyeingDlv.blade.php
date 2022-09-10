<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="sodyeingdlvtabs">
 <div title="Start Up" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List',footer:'#sodyeingdlvTblFt'" style="padding:2px">
    <table id="sodyeingdlvTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="30">ID</th>
       <th data-options="field:'company_name'" width="100">Company</th>
       <th data-options="field:'buyer_name'" width="100">Customer</th>
       <th data-options="field:'issue_no'" width="100">GIN</th>
       <th data-options="field:'issue_date'" width="100">Issue Date</th>
       <th data-options="field:'remarks'" width="100">Remarks</th>
      </tr>
     </thead>
    </table>
    <div id="sodyeingdlvTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     Customer: {!! Form::select('customer_id',
     $buyer,'',array('id'=>'customer_id','style'=>'width:200px;height: 25px')) !!} Issue Date: <input type="text"
      name="from_date" id="from_date" class="datepicker" style="width: 100px ;height: 23px" />
     <input type="text" name="to_date" id="to_date" class="datepicker" style="width: 100px;height: 23px" />
     <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
      iconCls="icon-search" plain="true" id="save" onClick="MsSoDyeingDlv.searchSoDyeingDlvList()">Show</a>
    </div>
   </div>
   <div
    data-options="region:'west',border:true,title:'Work Order Received',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#soknitFrmft'"
    style="width:450px; padding:2px">
    <div id="container">
     <div id="body">
      <code>
                            <form id="sodyeingdlvFrm">
                                <div class="row">
                                    <input type="hidden" name="id" id="id" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">GIN No</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="issue_no" id="issue_no"  disabled />
                                    </div>
                                </div>
                                 
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Company</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Customer</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Currency</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('currency_id', $currency,'',array('id'=>'currency_id')) !!}
                                    </div>
                                </div>
                               
                                                                  
                               
                                <div class="row middle">
                                    <div class="col-sm-5"> Dlv. Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="issue_date" id="issue_date" class="datepicker" />
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-5">Remarks </div>
                                    <div class="col-sm-7">
                                        <textarea name="remarks" id="remarks" value=""></textarea>
                                    </div>
                                </div>
                            </form>
                        </code>
     </div>
    </div>
    <div id="soknitFrmft" style="padding:0px 0px; text-align:right; background:#CCC;">
     <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
      iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingDlv.submit()">Save</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('sodyeingdlvFrm')">Reset</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingDlv.remove()">Delete</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingDlv.showBill()">Bill</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingDlv.showBillTk()">Bill Tk.</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingDlv.showDc()">DC</a>
    </div>
   </div>
  </div>
 </div>
 <div title="Item Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div
    data-options="region:'west',border:true,title:'Add Information',iconCls:'icon-more',footer:'#sodyeingdlvitemFrmFt'"
    style="width:450px; padding:2px">
    <form id="sodyeingdlvitemFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row">
                                    <input type="hidden" name="so_dyeing_dlv_id" id="so_dyeing_dlv_id" value="" />
                                    <input type="hidden" name="so_dyeing_ref_id" id="so_dyeing_ref_id" value="" />
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                
                                
                                
                              <div class="row middle">
                                    <div class="col-sm-4 req-text">Fin. Dia  </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fin_dia" id="fin_dia" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Fin. GSM  </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fin_gsm" id="fin_gsm" class="number integer" />
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Dlv. Qty</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="qty" id="qty" value="" class="number integer"  />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Grey Used  </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="grey_used" id="grey_used" class="number integer" onchange="MsSoDyeingDlvItem.calculate_form()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Rate/Unit </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rate" id="rate" value="" class="number integer" onchange="MsSoDyeingDlvItem.calculate_form()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Amount </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount" id="amount" value="" class="number integer" readonly/>
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4 req-text">Process Used</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="process_name" id="process_name" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Batch No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="batch_no" id="batch_no" value="" />
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4 req-text">No Of Roll </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="no_of_roll" id="no_of_roll" value="" class="number integer"/>
                                    </div>
                                </div>
                                
                                 <div class="row middle">
                                    <div class="col-sm-4">Remarks </div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks" value=""></textarea>
                                    </div>
                                </div>
                                

                                
                                  
                            </code>
      </div>
     </div>
     <div id="sodyeingdlvitemFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingDlvItem.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('sodyeingdlvitemFrm')">Reset</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingDlvItem.remove()">Delete</a>
     </div>
    </form>
   </div>
   <div data-options="region:'center',border:true,title:'Lists',footer:'#sodyeingdlvitemTblFt'" style="padding:2px">
    <table id="sodyeingdlvitemTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="30">ID</th>
       <th data-options="field:'fabrication'" width="100">Fabrication</th>
       <th data-options="field:'fabriclooks'" width="100">Fabric Looks</th>
       <th data-options="field:'fabricshape'" width="100">Fabric Shape</th>
       <th data-options="field:'gsm_weight'" width="70" align="right">GSM/Weight</th>
       <th data-options="field:'dyeing_color'" width="80" align="right">Dyeing Color</th>
       <th data-options="field:'colorrange_id'" width="70">Color Range</th>
       <th data-options="field:'dyetype'" width="70">Dye Type</th>
       <th data-options="field:'uom_code'" width="80">Uom</th>
       <th data-options="field:'qty'" width="70" align="right">Dlv. Qty</th>
       <th data-options="field:'grey_used'" width="80">Grey Used</th>

       <th data-options="field:'rate'" width="70" align="right">Rate</th>
       <th data-options="field:'amount'" width="100" align="right">Amount</th>
       <th data-options="field:'process_name'" width="100" align="right">Process Name</th>
       <th data-options="field:'batch_no'" width="80">Batch No</th>
       <th data-options="field:'fin_dia'" width="80">Fin. Dia</th>
       <th data-options="field:'fin_gsm'" width="80">Fin. GSM</th>
       <th data-options="field:'no_of_roll'" width="80">No Of Roll</th>
       <th data-options="field:'remarks'" width="80">Remarks</th>
      </tr>
     </thead>
    </table>
    <div id="sodyeingdlvitemTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">

     <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsSoDyeingDlvItem.import()">Import</a>
    </div>
   </div>
  </div>
 </div>
</div>





<div id="sodyeingdlvitemWindow" class="easyui-window" title="Fabric Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'west',split:true, title:'Search',footer:'#sodyeingdlvitemsearchFrmFt'"
   style="width:350px;height:130px">
   <div class="easyui-layout" data-options="fit:true">
    <div id="body">
     <code>
                        <form id="sodyeingdlvitemsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Order No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="sales_order_no" id="sales_order_no">
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Style</div>
                                <div class="col-sm-8">
                                    <input type="text" name="style_ref" id="style_ref">
                                </div>
                            </div>
                           
                        </form>
                    </code>
    </div>
    <div id="sodyeingdlvitemsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class="easyui-linkbutton c2" style="border-radius:1px"
      onClick="MsSoDyeingDlvItem.getitem()">Search</a>
    </div>
   </div>
  </div>
  <div data-options="region:'center',footer:'#sodyeingdlvitemWindowFt'" style="padding:10px;">
   <div id="sodyeingdlvitemWindowscs">
   </div>
   <div id="sodyeingdlvitemWindowFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
     iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingDlvItem.submitBatch()">Save</a>

   </div>
  </div>

 </div>
</div>




<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Dyeing/MsSoDyeingDlvController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Dyeing/MsSoDyeingDlvItemController.js">
</script>
<script>
 $(document).ready(function() {
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
    $('#sodyeingdlvitemFrm [id="uom_id"]').combobox();
    $('#sodyeingdlvFrm [id="buyer_id"]').combobox();

    $('#sodyeingdlvTblFt [id="customer_id"]').combobox();

    $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
        }
    });
});
</script>