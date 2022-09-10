<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="comlocalexppiTabs">
	<div title="Basic Information" style="padding:2px">
		<div class="easyui-layout" data-options="fit:true">
			<div data-options="region:'center',border:true,title:'List',footer:'#localexppiTblFt'" style="padding:2px">
				<table id="localexppiTbl" style="width:100%">
					<thead>
						<tr>
							<th data-options="field:'id'" width="40">ID</th>
							<th data-options="field:'pi_no'" width="100">PI No</th>
							<th data-options="field:'company_id'" width="100">Company</th>
							<th data-options="field:'buyer_id'" width="100">Buyer</th>
							<th data-options="field:'pi_date'" width="100">PI Date</th>
							<th data-options="field:'delivery_date'" width="100">Delivery Date</th>
							<th data-options="field:'pi_validity_days'" width="100">Validity Days</th>
							<th data-options="field:'pay_term_id'" width="100">Pay Term</th>
							<th data-options="field:'production_area_id'" width="100">Production Area</th>
						</tr>
					</thead>
				</table>
				<div id="localexppiTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
					Company: {!! Form::select('company_search_id',
					$company,'',array('id'=>'company_search_id','style'=>'width:
					100px;
					border-radius:2px; height:23px')) !!}
					Buyer: {!! Form::select('buyer_search_id',
					$buyer,'',array('id'=>'buyer_search_id','style'=>'width:
					100px;
					border-radius:2px; height:23px')) !!}
					PI Date: <input type="text" name="from_date" id="from_date" class="datepicker"
						style="width: 100px ;height: 23px" />
					<input type="text" name="to_date" id="to_date" class="datepicker"
						style="width: 100px;height: 23px" />
					<a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
						iconCls="icon-search" plain="true" id="save" onClick="MsLocalExpPi.searchExpPi()">Show</a>
				</div>
			</div>
			<div data-options="region:'west',border:true,title:'Add Basic Information',footer:'#ft2'"
				style="width: 400px; padding:2px">
				<form id="localexppiFrm">
					<div id="container">
						<div id="body">
							<code>
                                    <div class="row middle">
                                        <div class="col-sm-4 req-text">Company </div>
                                        <div class="col-sm-8">
                                            {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                            <input type="hidden" name="id" id="id" value="" />
                                        </div>
                                    </div>
                                    <div class="row middle" style="display: none">
                                        <div class="col-sm-4">Sys PI No </div>
                                        <div class="col-sm-8">
                                            <input type="text" name="sys_pi_no" id="sys_pi_no" />
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4 req-text">PI No </div>
                                        <div class="col-sm-8">
                                            <input type="text" name="pi_no" id="pi_no" />
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4 req-text">Production Area</div>
                                        <div class="col-sm-8">
                                            {!! Form::select('production_area_id', $productionarea,'',array('id'=>'production_area_id')) !!}
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4 req-text">Customer </div>
                                        <div class="col-sm-8">
                                            {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'border-radius:2px;width:100%;')) !!}
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">PI Validity Days </div>
                                        <div class="col-sm-8">
                                            <input type="text" name="pi_validity_days" id="pi_validity_days" value="" />
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4 req-text">PI Date </div>
                                        <div class="col-sm-8">
                                           <input type="text" name="pi_date" id="pi_date" class="datepicker" placeholder=" yy-mm-dd"/>
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4 req-text">Pay Term </div>
                                        <div class="col-sm-8">
                                            {!! Form::select('pay_term_id', $payterm,'',array('id'=>'pay_term_id')) !!}
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">Tenor </div>
                                        <div class="col-sm-8">
                                            <input type="text" name="tenor" id="tenor" class="number integer">
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4 req-text">Incoterm </div>
                                        <div class="col-sm-8">
                                            {!! Form::select('incoterm_id', $incoterm,'',array('id'=>'incoterm_id')) !!}
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4 req-text">Currency </div>
                                        <div class="col-sm-8">
                                            {!! Form::select('currency_id', $currency,'',array('id'=>'currency_id')) !!}
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4 req-text">Exch Rate </div>
                                        <div class="col-sm-8">
                                            <input type="text" name="exch_rate" id="exch_rate" class="number integer" />
                                        </div>
                                    </div>
                                    <div class="row middle" style="display:none;">
                                        <div class="col-sm-4">Inco Term Place </div>
                                        <div class="col-sm-8">
                                            <input type="hidden" name="incoterm_place" id="incoterm_place">
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4 req-text">Delivery Date </div>
                                        <div class="col-sm-8">
                                            <input type="text" name="delivery_date" id="delivery_date" class="datepicker" placeholder="yy-mm-dd" />
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">Delivery Place</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="delivery_place" id="delivery_place">
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">HS Code </div>
                                        <div class="col-sm-8">
                                            <input type="text" name="hs_code" id="hs_code">
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">Advising Bank</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="advise_bank" id="advise_bank" placeholder="txt" />
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">Account No</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="account_no" id="account_no" placeholder="txt" />
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">Swift Code</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="swift_code" id="swift_code" placeholder="txt" />
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">LC Negotiable</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="lc_negotiable" id="lc_negotiable" placeholder="txt" />
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">Overdue</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="overdue" id="overdue" placeholder="txt" />
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">Maturity Date</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="maturity_date" id="maturity_date" placeholder="txt" />
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">Partial Delivery</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="partial_delivery" id="partial_delivery" placeholder="txt" />
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">Tolerance</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="tolerance" id="tolerance" class="number integer" value="" />
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">Remarks </div>
                                        <div class="col-sm-8">
                                            <textarea name="remarks" id="remarks"></textarea>
                                        </div>
                                    </div>
                                </code>
						</div>
					</div>
					<div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
						<a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px; border-radius:1px"
							plain="true" id="save" iconCls="icon-remove" onClick="MsLocalExpPi.pdf()">PI</a>
						<a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px; border-radius:1px"
							plain="true" id="save" iconCls="icon-remove" onClick="MsLocalExpPi.pdfshort()">PI-Short</a>
						<a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
							iconCls="icon-save" plain="true" id="save" onClick="MsLocalExpPi.submit()">Save</a>
						<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
							iconCls="icon-remove" plain="true" id="delete"
							onClick="msApp.resetForm('localexppiFrm')">Reset</a>
						<a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
							iconCls="icon-remove" plain="true" id="delete" onClick="MsLocalExpPi.remove()">Delete</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div title="Sales Order" style="padding:2px">
		<div class="easyui-layout" data-options="fit:true">
			<div data-options="region:'center',border:true,title:'List'" style="width: 1500px; padding:2px">
				<table id="localexppiorderTbl" style="width:100%">
					<thead>
						<tr>
							<th data-options="field:'id'" width="40">ID</th>
							<th data-options="field:'style_ref'" width="70">Style Ref</th>
							<th data-options="field:'sale_order_no'" width="90">Sale Order No</th>
							<th data-options="field:'item_description'" width="300">Item</th>
							<th data-options="field:'uom_code'" width="40"> UOM</th>
							<th data-options="field:'qty'" width="80" align="right">Qty</th>
							<th data-options="field:'order_rate'" width="60" align="right"> Rate</th>
							<th data-options="field:'amount'" width="80" align="right"> Amount</th>
							<th data-options="field:'discount_per'" width="70" align="right"> UP/Down<br />Charge%</th>
							<th data-options="field:'net_amount'" width="100" align="right"> Net <br />Amount</th>
						</tr>
					</thead>
				</table>
			</div>
			<div data-options="region:'west',border:true,title:'Sales Order Details',footer:'#ft3'"
				style="width: 350px; padding:2px">
				<form id="localexppiorderFrm">
					<div id="container">
						<div id="body">
							<code>
                                    <div class="row middle" style="display:none">
                                        <input type="hidden" name="id" id="id" value="" />
                                        <input type="hidden" name="local_exp_pi_id" id="local_exp_pi_id" value="" />    
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">WO/SO</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="sales_order_no" id="sales_order_no" ondblclick="MsLocalExpPiOrder.openLocalsaleorder()" placeholder=" Double Click" readonly/>
                                            <input type="hidden" name="sales_order_ref_id" id="sales_order_ref_id" value="" />
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">Sales Order</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="sale_order_no" id="sale_order_no" value="" disabled/>
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">Style Ref </div>
                                        <div class="col-sm-8">
                                            <input type="text" name="style_ref" id="style_ref" disabled>
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">Order UOM </div>
                                        <div class="col-sm-8">
                                            <input type="text" name="uom_code" id="uom_code"disabled />
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">Item </div>
                                        <div class="col-sm-8">   
                                            <input type="text" name="item_description" id="item_description" value="" disabled>
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4"> Order Qty </div>
                                        <div class="col-sm-8">
                                            <input type="text" name="order_qty" id="order_qty" value="" class="number integer" readonly>
                                        </div>
                                    </div>  
                                    <div class="row middle">
                                        <div class="col-sm-4 req-text"> Tagged Qty </div>
                                        <div class="col-sm-8">
                                            <input type="text" name="qty" id="qty" onchange="MsLocalExpPiOrder.calculateAmount()" placeholder="write" class="number integer"/>
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4"> Balance Qty </div>
                                        <div class="col-sm-8">
                                            <input type="text" name="balance_qty" id="balance_qty"  class="number integer"  />
                                        </div>
                                    </div>                          
                                    <div class="row middle">
                                        <div class="col-sm-4"> Price/Unit </div>
                                        <div class="col-sm-8">
                                            <input type="text" name="rate" id="rate" onchange="MsLocalExpPiOrder.calculateAmount()" class="number integer" disabled />
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4"> Amount </div>
                                        <div class="col-sm-8">
                                            <input type="text" name="amount" id="amount" readonly class="number integer" value="" onchange="MsLocalExpPiOrder.netDiscountCalc()"/>
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">UP/Down<br/>Charge</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="discount_per" id="discount_per" class="number " value=""  onchange="MsLocalExpPiOrder.netDiscountCalc()"/>
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">Net Amount</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="net_amount" id="net_amount" readonly class="number integer" value="" />
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4"> Remarks </div>
                                        <div class="col-sm-8">
                                            <textarea name="remarks" id="remarks"></textarea>
                                        </div>
                                    </div>
                               </code>
						</div>
					</div>
					<div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
						<a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
							iconCls="icon-save" plain="true" id="save" onClick="MsLocalExpPiOrder.submit()">Save</a>
						<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
							iconCls="icon-remove" plain="true" id="delete"
							onClick="msApp.resetForm('localexppiorderFrm')">Reset</a>
						<a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
							iconCls="icon-remove" plain="true" id="delete"
							onClick="MsLocalExpPiOrder.remove()">Delete</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div title="Terms & Conditions" style="overflow:auto;padding:1px;height:520px">
		@includeFirst(['Purchase.PurchaseTermsCondition', 'Defult.Purchase.PurchaseTermsCondition'])
	</div>
</div>
<div id="subinbSaleOrderWindow" class="easyui-window" title="Sales Order Window"
	data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
	<div class="easyui-layout" data-options="fit:true">
		<div data-options="region:'west',split:true, title:'Search'" style="width:300px;">
			<div class="easyui-layout" data-options="fit:true">
				<div id="body">
					<code>
                            <form id="localexppiordersearchFrm">
                                <div class="row middle">
                                    <div class="col-sm-4">WorkOrder/SaleOrder No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sales_order_no" id="sales_order_no" value="" />
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
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'border-radius:2px;width:100%;')) !!}
                                    </div>
                                </div>
                            </form>
                        </code>
				</div>
				<p class="footer">
					<a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px"
						onClick="MsLocalExpPiOrder.searchLocalExpPiSaleOrderGrid()">Search</a>
				</p>
			</div>
		</div>
		<div data-options="region:'center'" style="padding:10px;">
			<table id="localexppiordersearchTbl" style="width:100%">
				<thead>
					<tr>
						<th data-options="field:'sales_order_ref_id'" width="40">Item<br /> ID</th>
						<th data-options="field:'sales_company_code'" width="60">Company</th>
						<th data-options="field:'customer_code'" width="80">Customer</th>
						<th data-options="field:'customer_sales_order'" width="100">WO/SO NO</th>
						<th data-options="field:'receive_date'" width="80">Rceive Date</th>
						<th data-options="field:'buyer_name'" width="80">Customer<br />Buyer</th>
						<th data-options="field:'style_ref'" width="80">Customer<br />Style</th>
						<th data-options="field:'sale_order_no'" width="80">Customer<br />Sales Order</th>
						<th data-options="field:'gmtspart'" width="80">GMT Part</th>
						<th data-options="field:'construction_name'" width="80">Construction</th>
						<th data-options="field:'fabrication'" width="80">Fabrication</th>
						<th data-options="field:'fabriclooks'" width="80">Fabric Looks</th>
						<th data-options="field:'fabricshape'" width="80">Fabric Shape</th>
						<th data-options="field:'gsm_weight'" width="80" align="right">GSM/Weight</th>
						<th data-options="field:'dia'" width="80" align="right">Dia</th>
						<th data-options="field:'measurment'" width="80">Measurment</th>
						<th data-options="field:'fabric_color'" width="80">Fabric Color</th>
						<th data-options="field:'uom_code'" width="50">Uom</th>
						<th data-options="field:'qty'" width="80" align="right">WO. Qty</th>
						<th data-options="field:'balance_qty'" width="80" align="right">Balance. Qty</th>
						<th data-options="field:'rate'" width="80" align="right">Rate</th>
						<th data-options="field:'amount'" width="80" align="right">Amount</th>
						<th data-options="field:'delivery_date'" width="80">Dlv Date</th>
					</tr>
				</thead>
			</table>
		</div>
		<div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
			<a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
				onclick="MsLocalExpPiOrder.closeLocalSaleOrderWindow()" style="width:80px">Close</a>
		</div>
	</div>
</div>
<!--------------------Sales Order Search-Window Ends----------------->

<div id="localexppiqtymultiWindow" class="easyui-window" title="Local Export tagged Qty"
	data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
	<div class="easyui-layout" data-options="fit:true">
		<div data-options="region:'center',footer:'#localexppiqtymultiFrmFt'" style="padding:10px;">
			<form id="localexppiqtymultiFrm">
				<code id="localexppiqtymultiscs">
                </code>
			</form>
			<div id="localexppiqtymultiFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
				<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
					iconCls="icon-save" plain="true" id="save" onClick="MsLocalExpPiOrder.submitBatch()">Save</a>
			</div>
		</div>
	</div>

	<script type="text/javascript" src="<?php echo url('/');?>/js/Commercial/LocalExport/MsAllLocalExpController.js">
	</script>
	<script>
		$(document).ready(function() {
        $('.integer').keyup(function () {
            if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
            }
        });
        $('#localexppiFrm [id="buyer_id"]').combobox();
        $('#localexppiTblFt [id="buyer_search_id"]').combobox();
    
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true
        });

        var bloodhound = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
        url: msApp.baseUrl()+'/localexppi/getbank?q=%QUERY%',
        wildcard: '%QUERY%'
        },
    });

    $('#advise_bank').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
      },
        {
            name: 'advise_bank',
            limit:1000,
            source: bloodhound,
            display: function(data) {
                return data.name  //Input value to be set when you select a suggestion. 
            },
            templates: {
                empty: [
                    '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
                ],
                header: [
                    '<div class="list-group search-results-dropdown">'
                ],
                suggestion: function(data) {
                    return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item">' + data.name + '</div></div>'
                }
            }
        });

    });
	</script>