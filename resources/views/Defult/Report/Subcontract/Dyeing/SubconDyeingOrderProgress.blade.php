<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Subcontract Dyeing Order Progress'" style="padding:2px">
        <table id="subcondyeingorderprogressTbl" style="width:1890px">
            <thead>
                <tr>
                    <th width="60" align="center">1</th>
                    <th width="80" align="center">2</th>
                    <th width="80" align="center">3</th>
                    <th width="60" align="center">4</th>
                    <th width="100" align="center">5</th>
                    <th width="100" align="center">6</th>
                    <th width="100" align="center">7</th>
                    <th width="100" align="center">8</th>
                    <th width="250" align="center">9</th>
                    <th width="80" align="center">10</th>
                    <th width="80" align="center">11</th>
                    <th width="70" align="center">12</th>
                    <th width="80" align="center">13</th>
                    <th width="200" align="center">14</th>
                    <th width="80" align="center">15</th>
                    <th width="70" align="center">16</th>
                    <th width="80" align="center">17</th>
                    <th width="80" align="center">18</th>
                    <th width="80" align="center">19</th>
                    <th width="80" align="center">20</th>
                    <th width="80" align="center">21</th>
                    <th width="80" align="center">22</th>
                    <th width="80" align="center">23</th>
                    <th width="80" align="center">24</th>
                    <th width="80" align="center">25</th>
                    <th width="80" align="center">26</th>
                    <th width="80" align="center">27</th>
                    <th width="70" align="center">28</th>
                    <th width="70" align="center">29</th>
                    <th width="70" align="center">30</th>
                    <th width="70" align="center">31</th>
                    <th width="80" align="center">32</th>
                    <th width="80" align="center">33</th>
                    <th width="70" align="center">34</th>
                    <th width="70" align="center">35</th>
                    <th width="70" align="center">36</th>
                    <th width="70" align="center">37</th>
                    <th width="70" align="center">38</th>
                    <th width="70" align="center">39</th>
                    <th width="120" align="center">40</th>
                </tr>
                <tr>
                    <th data-options="field:'company_name'" width="60" align="center">Company</th>
                    <th data-options="field:'receive_date'" width="80">Order<br/>Rcv Date</th>
                    <th data-options="field:'delivery_date'" width="80">Delivery<br/>Date</th>
                    <th data-options="field:'lead_time'" width="60">Lead<br/>Time</th>
                    <th data-options="field:'teamleader_name'" width="100">Marketer</th> 
                    <th data-options="field:'buyer_name'" width="100">Customer</th>
                    <th data-options="field:'gmt_buyer'" width="100">Customer's<br/>Buyer</th>
                    <th data-options="field:'sales_order_no'" width="100" align="center">DSO No</th>
                    <th data-options="field:'fabrication'" width="250">Item Description</th>
                    <th data-options="field:'fabric_color'" width="80">Fabric Color</th>
                    <th data-options="field:'qty'" width="80" align="right">Order Qty</th>
                    <th data-options="field:'rate'" width="70" align="right">Rate</th>
                    <th data-options="field:'amount'" width="80" align="right">Selling<br/>Value</th>
                    <th data-options="field:'local_pi_no_date'" width="200" >PI No & Date</th>
                    <th data-options="field:'pi_amount'" width="80" align="right">PI Value</th>
                    <th data-options="field:'local_lc_no'" width="70" align="right">LC/SC Rcv</th>
                    <th data-options="field:'grey_rcv_qty'" width="80" align="right">Grey Rcv Qty</th>
                    <th data-options="field:'batch_qty'" width="80"  align="right">Batch Qty</th>
                    <th data-options="field:'batch_wip'" width="80"  align="right">Batch WIP</th>
                    <th data-options="field:'batch_bal'" width="80"  align="right">Batch Bal</th>
                    <th data-options="field:'dyeing_qty'" width="80"  align="right">Dyeing Qty</th>
                    <th data-options="field:'dyeing_wip'" width="80"  align="right">Dyeing WIP</th>
                    <th data-options="field:'dyeing_bal'" width="80"  align="right">Dyeing Bal</th>
                    <th data-options="field:'process_loss_per'" width="80" align="right">Process Loss%</th>
                    <th data-options="field:'fin_qty'" width="80" align="right">Fin Fab.Qty</th>
                    <th data-options="field:'fin_wip'" width="80" align="right">Fin Fab WIP</th>
                    <th data-options="field:'fin_bal'" width="80" align="right">Fin Fab Bal</th>
                    <th data-options="field:'dlv_qty'" width="70" align="right">Dlv. Qty</th>
                    <th data-options="field:'grey_used'" width="70" align="right">Grey Used</th>
                    <th data-options="field:'dlv_wip'" width="70" align="right">Dlv. WIP</th>
                    <th data-options="field:'dlv_bal'" width="70" align="right">Dlv. Bal</th>
                    <th data-options="field:'bill_value'" width="80" align="right">Bill Value</th>
                    <th data-options="field:'bill_value_bal'" width="80" align="right">Bill Value Bal</th>
                    <th data-options="field:'ci_qty'" width="70" align="right">CI Qty</th>
                    <th data-options="field:'ci_qty_wip'" width="70" align="right">CI WIP</th>
                    <th data-options="field:'ci_qty_bal'" width="70" align="right">CI Bal Qty</th>
                    <th data-options="field:'ci_amount'" width="70" align="right">CI Value</th>
                    <th data-options="field:'ci_amount_wip'" width="70" align="right">CI Value  WIP</th>
                    <th data-options="field:'ci_amount_bal'" width="70" align="right">CI Value  Bal</th>
                    <th data-options="field:'so_dyeing_id'" width="120" align="center">Order ID</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#subcondyeingorderprogressFrmFt'" style="width:350px; padding:2px">
        <form id="subcondyeingorderprogressFrm">
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
                        <div class="row middle">
                            <div class="col-sm-4">Marketer</div>
                            <div class="col-sm-8">
                                {!! Form::select('teammember_id', $teammember,'',array('id'=>'teammember_id','style'=>'border:2px;width:100%')) !!}
                            </div>
                        </div>
                    </code>
                </div>
            </div>
            <div id="subcondyeingorderprogressFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSubconDyeingOrderProgress.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSubconDyeingOrderProgress.resetForm('subcondyeingorderprogressFrm')" >Reset</a>
            </div>
      </form>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/report/Subcontract/Dyeing/MsSubconDyeingOrderProgressController.js"></script>
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

      $('#subcondyeingorderprogressFrm [id="buyer_id"]').combobox();
      $('#subcondyeingorderprogressFrm [id="teammember_id"]').combobox();

   })(jQuery);
</script>