<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Subcontract Dyeing Fabric Report'" style="padding:2px">
        <table id="subcondyeingfabricreportTbl" style="width:1890px">
            <thead>
                <tr>
                    <th data-options="field:'company_name'" width="60" align="center">Company</th>
                    <th data-options="field:'receive_date'" width="80">Order<br/>Rcv Date</th>
                    <th data-options="field:'delivery_date'" width="80">Delivery<br/>Date</th>
                    <th data-options="field:'buyer_name'" width="100">Customer</th>
                    <th data-options="field:'gmt_buyer'" width="100">Customer's<br/>Buyer</th>
                    <th data-options="field:'sales_order_no'" width="100">DSO No</th>
                    <th data-options="field:'gmtspart'" width="70">GMT Part</th>
                    <th data-options="field:'fabrication'" width="250">Item Description</th>
                    <th data-options="field:'fabric_color'" width="80">Fabric Color</th>
                    <th data-options="field:'gsm_weight'" width="50" >GSM<br/>/Weight</th>
                    <th data-options="field:'dia'" width="50" >Dia</th>
                    <th data-options="field:'fabricshape'" width="60">Fabric <br/>Shape</th>
                    <th data-options="field:'fabriclooks'" width="60">Fabric <br/>Looks</th>
                    <th data-options="field:'qty'" width="80" align="right">Order Qty</th>
                    <th data-options="field:'rate'" width="70" align="right">Rate</th>
                    <th data-options="field:'amount'" width="80" align="right">Selling<br/>Value</th>
                    <th data-options="field:'grey_rcv_qty'" width="80" align="right">Grey Rcv Qty</th>
                    <th data-options="field:'batch_qty'" width="80"  align="right">Batch Qty</th>
                    <th data-options="field:'batch_wip'" width="80"  align="right">Batch WIP</th>
                    <th data-options="field:'batch_bal'" width="80"  align="right">Batch Bal</th>
                    <th data-options="field:'dyeing_qty'" width="80"  align="right">Dyeing Qty</th>
                    <th data-options="field:'dyeing_wip'" width="80"  align="right">Dyeing WIP</th>
                    <th data-options="field:'dyeing_bal'" width="80"  align="right">Dyeing Bal</th>
                    <th data-options="field:'process_loss_per'" width="60" align="center">Tgt <br/>Loss %</th>
                    <th data-options="field:'fin_qty'" width="80" align="right">Fin Fab.Qty</th>
                    <th data-options="field:'fin_wip'" width="80" align="right">Fin Fab WIP</th>
                    <th data-options="field:'fin_bal'" width="80" align="right">Fin Fab Bal</th>
                    <th data-options="field:'dlv_qty'" width="70" align="right">Dlv. Qty</th>
                    <th data-options="field:'grey_used'" width="70" align="right">Grey Used</th>
                    <th data-options="field:'dlv_wip'" width="70" align="right">Dlv. WIP</th>
                    <th data-options="field:'dlv_bal'" width="70" align="right">Dlv. Bal</th>
                    <th data-options="field:'bill_value'" width="80" align="right">Bill Value</th>
                    <th data-options="field:'bill_value_bal'" width="80" align="right">Bill Value Bal</th>
                    <th data-options="field:'so_dyeing_id'" width="120" align="center">Order ID</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#subcondyeingfabricreportFrmFt'" style="width:350px; padding:2px">
        <form id="subcondyeingfabricreportFrm">
            <div id="container">
                <div id="body">
                    <code>
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
                        <div class="row middle">
                            <div class="col-sm-4">Sales Order</div>
                            <div class="col-sm-8">
                                <input type="text" name="sales_order_no" id="sales_order_no" placeholder="" value=""/>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Rcv Date</div>
                            <div class="col-sm-4" style="padding-right:0px">
                                <input type="text" name="rcv_date_from" id="rcv_date_from" class="datepicker" placeholder="  From" value="" />
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="rcv_date_to" id="rcv_date_to" class="datepicker"  placeholder="  To" value=""/>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Delv Date</div>
                            <div class="col-sm-4" style="padding-right:0px">
                                <input type="text" name="dlv_date_from" id="dlv_date_from" class="datepicker" placeholder="  From" value="" />
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="dlv_date_to" id="dlv_date_to" class="datepicker"  placeholder="  To" value=""/>
                            </div>
                        </div>
                    </code>
                </div>
            </div>
            <div id="subcondyeingfabricreportFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSubConDyeingFabricReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSubConDyeingFabricReport.resetForm('subcondyeingfabricreportFrm')" >Reset</a>
            </div>
      </form>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/report/Subcontract/Dyeing/MsSubConDyeingFabricReportController.js"></script>
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

      $('#subcondyeingfabricreportFrm [id="buyer_id"]').combobox();
      $('#subcondyeingfabricreportFrm [id="teammember_id"]').combobox();

   })(jQuery);
</script>