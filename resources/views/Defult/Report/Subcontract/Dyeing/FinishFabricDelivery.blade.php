<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true" style="padding:2px;width:100%;height:100%;">
        <table id="finishfabricdeliveryTbl" style="width:100%">
            <thead>
                <tr>
                    <th align="center" width="60">1</th>
                    <th align="center" width="65">2</th>
                    <th align="center" width="70">3</th>
                    <th align="center" width="70">4</th>
                    <th align="center" width="70">5</th>
                    <th align="center" width="80">6</th>
                    <th align="center" width="70">7</th>
                    <th align="center" width="150">8</th>
                    <th align="center" width="80">9</th>
                    <th align="center" width="100">10</th>
                    <th align="center" width="100">11</th>
                    <th align="center" width="60">12</th>
                    <th align="center" width="60">13</th>
                    <th align="center" width="60">14</th>
                    <th align="center" width="50">15</th>
                    <th align="center" width="80">16</th>
                    <th align="center" width="50">17</th>
                    <th align="center" width="70">18</th>
                    <th align="center" width="70">19</th>
                    <th align="center" width="40">20</th>
                    <th align="center"  width="60">21</th>
                    <th align="center" width="70">22</th>
                    <th align="center" width="80">23</th>
                    <th align="center" width="80">24</th>
                    <th align="center" width="40">25</th>
                    <th align="center" width="100">26</th>
                    <th align="center" width="100">27</th>
                </tr>
                <tr>
                    <th data-options="field:'company_code'" width="60">Company</th>
                    <th data-options="field:'customer_code'" width="65">Customer</th>
                    <th data-options="field:'barcode_no'" width="70" formatter="MsFinishFabricDelivery.formatInhDc">Challan</th>
                    <th data-options="field:'bill_no'" width="70" formatter="MsFinishFabricDelivery.formatInhBill">Invoice No</th>
                    <th data-options="field:'bill_date'" width="80">Invoice Date</th>
                    <th data-options="field:'dyeing_sales_order_no'" width="80">Dyeing <br>Sales Order</th>
                    <th data-options="field:'gmtspart'" width="70">Gmt Part</th>
                    <th data-options="field:'fabric_desc'" width="150">Fabric Description</th>
                    <th data-options="field:'fabric_look'" width="80">Fabric Look</th>
                    <th data-options="field:'batch_no'" width="100">Batch No</th>
                    <th data-options="field:'batch_color'" width="100">Batch Color</th>
                    <th data-options="field:'gsm_weight'" width="60">GSM <br>Weight</th>
                    <th data-options="field:'dia_width'" width="60">Dia /<br>Width</th>
                    <th data-options="field:'measurement'" width="60">Measure<br>ment</th>
                    <th data-options="field:'roll_length'" width="50">Roll <br>Length</th>
                    <th data-options="field:'stitch_length'" width="80"> Stitch Length</th>
                    <th data-options="field:'shrink_per'" width="50" align="right">Shrink%</th>
                    <th data-options="field:'qty'" width="70" align="right">Fin Qty</th>
                    <th data-options="field:'grey_wgt'" width="70" align="right">Grey Wgt</th>
                    <th data-options="field:'uom_code'" width="40">UOM</th>
                    <th data-options="field:'currency_code'" width="60">Currency</th>
                    <th data-options="field:'rate'" width="70" align="right">Rate</th>
                    <th data-options="field:'amount'" width="80" align="right">Amount</th>
                    <th data-options="field:'amount_bdt'" width="80" align="right">Amount-BDT</th>
                    <th data-options="field:'no_of_roll'" width="40" align="right">No of<br> Roll</th>
                    <th data-options="field:'gmt_style_ref'" width="100">GMT Style</th>
                    <th data-options="field:'gmt_sale_order_no'" width="100">GMT Order</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Finish Fabric Delivery and Invoice',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="finishfabricdeliveryFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Bill Date </div>
                            <div class="col-sm-4" style="padding-right:0px">
                                <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4"> Company</div>
                            <div class="col-sm-8">
                                {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Buyer </div>
                            <div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'border-radious:2px;width:100%')) !!}</div>
                        </div>
                    </code>
                </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" plain="true" id="save" onClick="MsFinishFabricDelivery.getSelf()">Self Order</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" plain="true" id="save" onClick="MsFinishFabricDelivery.getSubcontract()">Subcontract</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsFinishFabricDelivery.resetForm('finishfabricdeliveryFrm')">Reset</a> 
            </div>
        </form>
    </div>
</div>

<div id="subcontractWindow" class="easyui-window" title=" Date Wise Issue Report" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:600px;">
    <div id="containerWindow" style="width:100%;height:100%;padding:0px;">
        <table id="subcontractfinishfabricdeliveryTbl" style="width:100%;height:100%">
            <thead>
                <tr>
                    <th align="center" width="60">1</th>
                    <th align="center" width="120">2</th>
                    <th align="center" width="80">3</th>
                    <th align="center" width="80">4</th>
                    <th align="center" width="80">5</th>
                    <th align="center" width="100">6</th>
                    <th align="center" width="70">7</th>
                    <th align="center" width="150">8</th>
                    <th align="center" width="80">9</th>
                    <th align="center" width="100">10</th>
                    <th align="center" width="100">11</th>
                    <th align="center" width="80">12</th>
                    <th align="center" width="80">13</th>
                    <th align="center" width="60">14</th>
                    <th align="center" width="70">15</th>
                    <th align="center" width="70">16</th>
                    <th align="center" width="40">17</th>
                    <th align="center" width="60">18</th>
                    <th align="center" width="70">19</th>
                    <th align="center" width="80">20</th>
                    <th align="center" width="80">21</th>
                    <th align="center" width="40">22</th>
                    <th align="center" width="150">23</th>
                    <th align="center" width="150">24</th>
                </tr>
                <tr>
                    <th data-options="field:'company_code'" width="60">Company</th>
                    <th data-options="field:'customer_name'" width="120">Customer</th>
                    <th data-options="field:'barcode_no'" width="80" formatter="MsFinishFabricDelivery.formatSubDc">Challan</th>
                    <th data-options="field:'bill_no'" width="80" formatter="MsFinishFabricDelivery.formatSubBill">Invoice No</th>
                    <th data-options="field:'bill_date'" width="80">Invoice Date</th>
                    <th data-options="field:'dyeing_sales_order_no'" width="100">Dyeing Sales Order</th>
                    <th data-options="field:'gmtspart'" width="70">Gmt Part</th>
                    <th data-options="field:'fabric_desc'" width="150">Fabric Description</th>
                    <th data-options="field:'fabric_look'" width="80">Fabric Look</th>
                    <th data-options="field:'batch_no'" width="100">Batch No</th>
                    <th data-options="field:'batch_color'" width="100">Batch Color</th>
                    <th data-options="field:'gsm_wgt'" width="60">GSM <br/>Weight</th>
                    <th data-options="field:'dia_width'" width="60">Dia /<br/>Width</th>
                    <th data-options="field:'measurment'" width="60">Measure<br/>ment</th>
                    <th data-options="field:'qty'" width="70" align="right">Delivery Qty</th>
                    <th data-options="field:'grey_wgt'" width="70" align="right">Grey Wgt</th>
                    <th data-options="field:'uom_code'" width="40">UOM</th>
                    <th data-options="field:'currency_code'" width="60">Currency</th>
                    <th data-options="field:'rate'" width="70" align="right">Rate  </th>
                    <th data-options="field:'amount'" width="80" align="right"> Amount</th>
                    <th data-options="field:'amount_bdt'" width="80" align="right"> Amount-BDT</th>
                    <th data-options="field:'no_of_roll'" width="40" align="right">No of<br/>Roll</th>
                    <th data-options="field:'gmt_style_ref'" width="150">GMT Style</th>
                    <th data-options="field:'gmt_sale_order_no'" width="150">GMT Order</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/report/Subcontract/Dyeing/MsFinishFabricDeliveryController.js"></script>
<script>
    (function(){
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
        });
        $('.integer').keyup(function () {
            if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
            }
        });
       $('#finishfabricdeliveryFrm [id="buyer_id"]').combobox();
    })(jQuery);
</script>
