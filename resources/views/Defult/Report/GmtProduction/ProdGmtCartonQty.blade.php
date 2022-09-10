<div class="easyui-layout animated rollIn"  data-options="fit:true">
<div data-options="region:'center',border:true,title:'Carton Completed Report'" style="padding:2px">
    <p>From Date:   To Date:</p>
    <table id="prodgmtcartonqtyTbl" border="1" style="width:1890px">
        <thead>
            <tr>
                <th data-options="field:'company_name',halign:'center'" width="60">Beneficiary</th>
                <th data-options="field:'produced_company_name',halign:'center'" width="60">Prod.Company</th>
                <th data-options="field:'team_member_name',halign:'center'" width="80">Dealing Marchent</th>
                <th data-options="field:'buyer_name',halign:'center'" width="70">Buyer</th>
                <th data-options="field:'style_ref',halign:'center'" width="80">Style No</th>
                <th data-options="field:'sale_order_no',halign:'center'" width="80">Sale Order No</th>
                <th data-options="field:'item_description',halign:'center'" width="80">GMT Item</th>
                <th data-options="field:'no_of_carton',halign:'center'" width="80">No.Of Carton</th>
                <th data-options="field:'carton_qty',halign:'right''" width="60">Carton Qty<br/>(Pcs)</th>  
                <th data-options="field:'carton_rate',halign:'right'" width="50">Carton<br/>Rate</th>
                <th data-options="field:'carton_amount',halign:'right'" width="80">Carton<br/>Amount</th>
                <th data-options="field:'shipped_carton_no',halign:'right'" width="80">Shipout<br/>Carton No</th>
                <th data-options="field:'shipped_gmt_qty',halign:'right'" width="60" align="right">Shipped<br/>GMT Pcs</th>
                <th data-options="field:'shipped_gmt_rate',halign:'right'" width="50" align="right">Shipped<br/>Rate</th>
                <th data-options="field:'shipped_gmt_amount',halign:'right'" width="60" align="right">Shipped<br/>Amount</th>
      
                <th data-options="field:'balanced_carton_no',halign:'right'" width="100" >Balanced<br>Carton No</th>
                <th data-options="field:'balanced_carton_qty',halign:'right'" width="70" >Balanced<br>Qty</th>
                <th data-options="field:'balanced_carton_amount',halign:'right'" width="60" align="right">Balanced<br>Amount</th>
                <th data-options="field:'remarks',halign:'center'" width="100" >Remarks</th>
                
            </tr>
        </thead>
    </table>
</div>
<div data-options="region:'west',border:true,title:'Search Carton',footer:'#ft2'" style="width:350px; padding:2px">
    <form id="prodgmtcartonqtyFrm">
    <div id="container">
         <div id="body">
           <code>              
                <div class="row middle">
                    <div class="col-sm-4 req-text">Company </div>
                    <div class="col-sm-8">
                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Buyer </div>
                    <div class="col-sm-8">
                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width:100%;border:2')) !!}
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Carton. Date</div>
                    <div class="col-sm-4" style="padding-right:0px">
                        <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                    </div>
                    <div class="col-sm-4" style="padding-left:0px">
                        <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Order Source</div>
                    <div class="col-sm-8">
                        {!! Form::select('order_source_id', $ordersource,'',array('id'=>'order_source_id')) !!}
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Prod. Sourse </div>
                    <div class="col-sm-8">
                        {!! Form::select('prod_source_id', $productionsource,'',array('id'=>'prod_source_id')) !!}
                    </div>
                </div>      
                <div class="row middle">
                    <div class="col-sm-4">Prod. Company</div>
                    <div class="col-sm-8">
                        {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id')) !!}
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Location </div>
                    <div class="col-sm-8">
                        {!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Shift Name </div>
                    <div class="col-sm-8">
                        {!! Form::select('shiftname_id', $shiftname,'',array('id'=>'shiftname_id')) !!}
                    </div>
                </div>

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtCartonQty.get()">Show</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdGmtCartonQty.resetForm('prodgmtcartonqtyFrm')" >Reset</a>
    </div>

  </form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/report/GmtProduction/MsProdGmtCartonQtyController.js"></script>
<script>
$(".datepicker" ).datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});
</script>

