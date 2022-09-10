        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="daytargettransferTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'entry_id'" width="100">Entry ID</th>
                            <th data-options="field:'sale_order_no'" width="80">Order No</th>
                            <th data-options="field:'company_name'" width="150">Prod.Company</th>
                            <th data-options="field:'ship_date'" width="80">Ship Date</th>
                            <th data-options="field:'target_date'" width="80">Target Date</th>
                            <th data-options="field:'process'" width="80" align="right">Process</th>
                            <th data-options="field:'qty'" width="80" align="right">Target Qty</th>
                            <th data-options="field:'prod_qty'" width="80" align="right">Prod.Qty</th>
                            
                            
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Target Transfer',footer:'#ft2'" style="width: 450px; padding:2px">
                <form id="daytargettransferFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 ">Entry ID</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="entry_id" id="entry_id" value="" disabled  />
                                    </div>
                                </div>
                                <div class="row middle">
                                        <div class="col-sm-4 req-text">Process</div>
                                        <div class="col-sm-8">
                                            {!! Form::select('process_id', $tergetProcess,'',array('id'=>'process_id')) !!}
                                        </div>
                                </div>
                                <div class="row middle">
                                        <div class="col-sm-4 req-text">Prod.Company</div>
                                        <div class="col-sm-8">
                                            {!! Form::select('produced_company_id', $company,'',array('id'=>'produced_company_id')) !!}
                                        </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Order No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sale_order_no" id="sale_order_no" value="" ondblclick="
                                        MsDayTargetTransfer.opendaytargetorderWindow()
                                        " placeholder= "Double Click" readonly />
                                        <input type="hidden" name="sales_order_id" id="sales_order_id" value=""   />
                                    </div>
                                </div>
                                
                                
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Target Date Range</div>
                                    <div class="col-sm-8">
                                    <input type="text" name="target_date" id="target_date" class="datepicker"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                        <div class="col-sm-4 req-text">Target Qty</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="qty" id="qty" class="number integer" />
                                        </div>
                                </div>
                                <div class="row middle">
                                        <div class="col-sm-4 req-text">Prod. Qty</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="prod_qty" id="prod_qty" class="number integer" />
                                        </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsDayTargetTransfer.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('daytargettransferFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove"   plain="true" id="delete"  zdy90 onClick="MsDayTargetTransfer.remove()">Delete</a>

                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove"   plain="true" id="delete"  zdy90 onClick="MsDayTargetTransfer.sendSms()">Send SMS</a>
                        
                    </div>
                </form>
            </div>
        </div>
</div>
{{-- Order window --}}
<div id="opendaytargetorderwindow" class="easyui-window" title="Sales Order No Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="opendaytargetorderFrm">
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
                                <div class="col-sm-4">Sale Order No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="sale_order_no" id="sale_order_no" value="" />
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsDayTargetTransfer.searchDayTargetOrderGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="opendaytargetorderTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'sales_order_id'" width="40">ID</th>
                        <th data-options="field:'style_ref'" width="70">Style Ref</th>
                        <th data-options="field:'job_no'" width="90">Job No</th>
                        <th data-options="field:'sale_order_no'" width="100">Sale Order No</th>
                        <th data-options="field:'country_id'" width="100">Country</th>    
                        <th data-options="field:'ship_date'" width="100">Ship Date</th> 
                        <th data-options="field:'buyer_name'" width="100">Buyer</th>
                        <th data-options="field:'company_name'" width="80">Company</th>
                        <th data-options="field:'produced_company_name'" width="140">Produced Company</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#opendaytargetorderwindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Marketing/MsDayTargetTransferController.js"></script>

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

     

   })(jQuery);
   
  

</script>