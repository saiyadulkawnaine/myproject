
<div class="easyui-layout"  data-options="fit:true" id="receivedeliverypanel">
    <div data-options="region:'center',border:true,title:'Date Wise Dyeing Delivery'" style="padding:2px">
        
                <table id="receivedeliveryTbl" style="width:1890px">
                    <thead>
                       
                        <tr>
                            <th data-options="field:'company_name'" width="60" align="center">Company</th>
                            <th data-options="field:'buyer_name'" width="120">Customer</th>
                            <th data-options="field:'ref_type'" width="80">Ref. Type</th>
                            <th data-options="field:'ref_no'" width="80" formatter="MsReceiveDelivery.formatrefno">Ref. No</th>
                            <th data-options="field:'ref_date'" width="80" align="center">Ref. Date</th>
                            <th data-options="field:'fabrication'" width="250">Fabrication</th>
                            <th data-options="field:'fabriclooks'" width="80">Fabric Looks</th>
                            <th data-options="field:'fabricshape'" width="80">Fabric Shape</th>
                            <th data-options="field:'gsm_weight'" width="70" align="center">GSM/Weight</th>
                            <th data-options="field:'dia'" width="70" align="center">Dia</th>
                            <th data-options="field:'dyeing_color'" width="80" align="center">Dyeing Color</th>
                            <th data-options="field:'colorrange_id'" width="70">Color Range</th>
                            <th data-options="field:'dyetype'" width="70">Dye Type</th>
                            <th data-options="field:'uom_code'" width="60">Uom</th>
                            <th data-options="field:'rcv_qty'" width="70" align="right">Receive Qty</th>
                            <th data-options="field:'dlv_qty'" width="70" align="right">Delivery Qty</th>
                            <th data-options="field:'grey_used_qty'" width="80" align="right">Grey Used</th>
                            <th data-options="field:'rtn_qty'" width="80" align="right">Return Qty</th>
                            <th data-options="field:'no_of_roll'" width="60" align="center">No of Roll</th>
                        </tr>
                    </thead>
                </table>
                   
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#receivedeliveryFrmFt'" style="width:350px; padding:2px">
        <form id="receivedeliveryFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Date Range</div>
                            <div class="col-sm-4" style="padding-right:0px">
                                <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="  From" value="{{$from}}" />
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="  To" value="{{$to}}"/>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Company</div>
                            <div class="col-sm-8">
                                {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Customer</div>
                            <div class="col-sm-8">
                                {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'border:2px;width:100%')) !!}
                            </div>
                        </div>
                    </code>
                </div>
            </div>
            <div id="receivedeliveryFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsReceiveDelivery.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsReceiveDelivery.resetForm('subcondyeingdeliveryFrm')" >Reset</a>
            </div>
      </form>
    </div>
</div>

<div id="subcondyeingdeliveryWindow" class="easyui-window" title="Subcontract/Dyeing/Subcon" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:500px;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center'">
            <table id="subcondyeingdlvitemTbl">
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
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#subcondyeingdeliveryWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/report/Inventory/MsReceiveDeliveryController.js"></script>
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

      $('#subcondyeingdeliveryFrm [id="buyer_id"]').combobox();

   })(jQuery);
</script>