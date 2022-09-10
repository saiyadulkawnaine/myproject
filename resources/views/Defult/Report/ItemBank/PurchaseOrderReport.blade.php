<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
     <table id="purchaseorderreportTbl" style="width:100%">
        <thead>
            <tr>
                <th data-options="field:'company_name'" width="80" align="center">Company</th>
                <th data-options="field:'po_no'" width="80" align="center" formatter='MsPurchaseOrderReport.formatpdf'>PO No</th>
                <th data-options="field:'po_date'" width="100" align="center">PO Date</th>
                <th data-options="field:'supplier_name'" width="90" align="left">Supplier<br/>Name</th>
                <th data-options="field:'buyer_name'" width="120">Buyer</th>
                <th data-options="field:'itemcategory',halign:'center'" width="80">Item<br/>Category</th>
                <th data-options="field:'itemclass_name',halign:'center'" width="80">Item<br/>Class</th>
                <th data-options="field:'sub_class_name',halign:'center'" width="80">Sub Group</th>
                <th data-options="field:'item_description',halign:'center'" width="160">Item Description</th>
                <th data-options="field:'specification',halign:'center'" width="80">Specification</th>
                <th data-options="field:'count_name',halign:'center'" width="60">Count</th>
                <th data-options="field:'composition',halign:'center'" width="100">Composition</th>
                <th data-options="field:'yarn_type',halign:'center'" width="80">Yarn<br/>Type</th>
                <th data-options="field:'color_id',halign:'center'" width="60">Color</th>


                <th data-options="field:'requisition_no',halign:'center'" width="60">Requisition</th>
                <th data-options="field:'qty_d',halign:'center'" width="60" align="right">PO.Qty</th>
                <th data-options="field:'rcv_qty_d',halign:'center'" width="60" align="right" formatter="MsPurchaseOrderReport.formatRcvNo">Rcv.Qty</th>
                <th data-options="field:'balance_qty',halign:'center'" width="60" align="right">balance<br/>Qty</th>
                <th data-options="field:'rate_d',halign:'center'" width="60" align="right">PO Rate</th>
                <th data-options="field:'amount_d',halign:'center'" width="90" align="right">Amount</th>
                <th data-options="field:'rcv_amount_d',halign:'center'" width="90" align="right">Rcv <br/>Amount</th> 
                <th data-options="field:'balance_amount_d',halign:'center'" width="90" align="right">Balance<br/>Amount</th>  
                <th data-options="field:'currency_name',halign:'center'" width="70">Currency</th> 
                <th data-options="field:'exch_rate',halign:'center'" width="70">Exch Rate</th> 
                <th data-options="field:'amount_taka',halign:'center'" width="90" align="right">Amount (BDT)</th>
                <th data-options="field:'rcv_amount_taka',halign:'center'" width="90" align="right">Rcv<br/>Amount(BDT)</th> 
                <th data-options="field:'balance_amount_taka',halign:'center'" width="90" align="right">Balance<br/>Amount(BDT)</th>  
                <th data-options="field:'pi_no'" width="130">PI No</th> 
                <th data-options="field:'pi_date',halign:'center'" width="80">PI Date</th> 
                <th data-options="field:'lc_no'" width="100">Lc</th> 
                <th data-options="field:'item_remarks'" width="200">Item<br/>Remarks</th>
                <th data-options="field:'remarks'" width="200">Remarks</th>
            </tr>
        </thead>
     </table>
    </div>
	<div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
		<form id="purchaseorderreportFrm">
		<div id="container">
		<div id="body">
		<code>
		<div class="row middle">
		<div class="col-sm-4 req-text">Menu Name</div>
		<div class="col-sm-8">
		{!! Form::select('menu_id', $menu,'',array('id'=>'menu_id')) !!}
		</div>
		</div>
		<div class="row middle">
		<div class="col-sm-4">PO Date</div>
		<div class="col-sm-4" style="padding-right:0px">
		<input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
		</div>
		<div class="col-sm-4" style="padding-left:0px">
		<input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
		</div>
		</div>
		<div class="row middle">
		<div class="col-sm-4">Company</div>
		<div class="col-sm-8">
		{!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
		</div>
		</div>
		<div class="row middle">
		<div class="col-sm-4">Supplier</div>
		<div class="col-sm-8">
		{!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
		</div>
		</div>
		<div class="row middle">
		<div class="col-sm-4">Item Category</div>
		<div class="col-sm-8">
		{!! Form::select('itemcategory_id', $itemcategory,'',array('id'=>'itemcategory_id','style'=>'width: 100%; border-radius:2px')) !!}
		</div>
		</div>
        <div class="row middle">
        <div class="col-sm-4">Buyer</div>
        <div class="col-sm-8">
        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
        </div>
        </div>
		</code>
		</div>
		</div>
		<div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
		<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPurchaseOrderReport.get()">Show</a>
		<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPurchaseOrderReport.resetForm('purchaseorderreportFrm')" >Reset</a>
		</div>

		</form>
	</div>
    <div data-options="region:'east',border:true,collapsed:true,hideCollapsedContent:false,title:'Summary'" style="width:400px; padding:1px">
    	<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="jobtabs">
            <div title="Item Category" style="padding:1px" data-options="selected:true">
            	<table id="purchaseorderreportcategorywiseTbl" style="width:100%">
                <thead>
                    <th data-options="field:'category_name',halign:'center'" align="left" width="120">Cetegory</th>
                    <th data-options="field:'no_of_po',halign:'center'" align="center" width="60">No of PO</th>
                    <th data-options="field:'no_of_supplier',halign:'center'" align="center" width="60">No of <br/>Supplier</th>
                    <th data-options="field:'qty',halign:'center'" align="right" width="80">Qty</th>
                    <th data-options="field:'amount_taka',halign:'center'" align="right" width="80">Total in Tk</th>
                    <th data-options="field:'po_usd',halign:'center'" align="right" width="80">PO in USD</th>
                    <th data-options="field:'po_taka',halign:'center'" align="right" width="80">PO in Taka</th>
                    <th data-options="field:'po_oth',halign:'center'" align="right" width="80">PO (Other)</th>

                </thead>
                </table>
            </div>
            <div title="Supplier" style="padding:1px">
            	<table id="purchaseorderreportsupplierwiseTbl" style="width:100%">
                <thead>
                	<th data-options="field:'supplier_name',halign:'center'" align="left" width="120">Supplier</th>
                    <th data-options="field:'category_name',halign:'center'" align="left" width="120">Cetegory</th>
                    <th data-options="field:'no_of_po',halign:'center'" align="center" width="60">No of PO</th>
                    <th data-options="field:'qty',halign:'center'" align="right" width="80">Qty</th>
                    <th data-options="field:'amount_taka',halign:'center'" align="right" width="80">Total in Tk</th>
                    <th data-options="field:'po_usd',halign:'center'" align="right" width="80">PO in USD</th>
                    <th data-options="field:'po_taka',halign:'center'" align="right" width="80">PO in Taka</th>
                    <th data-options="field:'po_oth',halign:'center'" align="right" width="80">PO (Other)</th>
                </thead>
                </table>
            </div>
            <div title="Top Sheet" style="padding:1px">
                <table id="purchaseorderreportpowiseTbl" style="width:100%">
                <thead>
                    <th data-options="field:'po_no',halign:'center'" align="center" width="60">Po No</th>
                    <th data-options="field:'company_name',halign:'center'" align="center" width="60">Company</th>
                    <th data-options="field:'supplier_name',halign:'center'" align="left" width="120">Supplier</th>
                    <th data-options="field:'po_date',halign:'center'" align="center" width="80">PO Date</th>
                    <th data-options="field:'remarks',halign:'center'" align="center" width="120">Description</th>
                    <th data-options="field:'qty',halign:'center'" align="right" width="80">Qty</th>
                    <th data-options="field:'amount',halign:'center'" align="right" width="80">Amount</th>
                    <th data-options="field:'currency_name',halign:'center'" align="center" width="60">Currency</th>
                    <th data-options="field:'exch_rate',halign:'center'" align="center" width="60">Exch. Rate</th>
                    <th data-options="field:'amount_taka',halign:'center'" align="right" width="80">Total in Tk</th>
                </thead>
                </table>
            </div>
        </div>
    </div>  
</div>

<div id="rcvdetailWindow" class="easyui-window" title="Receive Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="rcvdetailTbl" style="width:900px">
        <thead>
            <tr>
            <th data-options="field:'po_item_id',halign:'center'" width="100" >Po Item ID</th>
            <th data-options="field:'inv_rcv_item_id',halign:'center'" align="left" width="100">Rcv Item ID</th>
            <th data-options="field:'receive_no'" width="100px">MRR No</th>
            <th data-options="field:'receive_date'" width="100px">Receive Date</th>
            <th data-options="field:'challan_no'" width="100px">Challan No</th>
            <th data-options="field:'qty'" width="100px">Rcv Qty</th>
            <th data-options="field:'rate'" width="100px">Rcv Rate</th>
            <th data-options="field:'amount'" width="100px">Rcv Amount</th>
            <th data-options="field:'store_qty'" width="100px">Store Qty</th>
            <th data-options="field:'store_amount'" width="100px">Store Amount</th>
            <th data-options="field:'remarks'" width="150px">Remarks</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/report/ItemBank/MsPurchaseOrderReportController.js"></script>
<script>
 $(".datepicker" ).datepicker({
     dateFormat: 'yy-mm-dd',
     changeMonth: true,
     changeYear: true
 });
 
 $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
        }
    });
 $('#purchaseorderreportFrm [id="supplier_id"]').combobox();
$('#purchaseorderreportFrm [id="buyer_id"]').combobox();
$('#purchaseorderreportFrm [id="itemcategory_id"]').combobox();
</script>