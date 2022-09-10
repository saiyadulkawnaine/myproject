<div class="easyui-accordion" data-options="multiple:false" style="width:99%;" id="budgetAccordion">

	<div title="Cost Master .." data-options="iconCls:'icon-ok'" style="padding:1px;height:550px">
		<div class="easyui-layout animated rollIn" data-options="fit:true">
			<div data-options="region:'west',border:true,title:'Budget ',footer:'#ft2'"
				style="width:400px; padding:2px">
				<form id="budgetFrm">
					<div id="container">
						<div id="body">
							<code>
					<div class="row">
					<div class="col-sm-4 req-text">Job No</div>
					<div class="col-sm-8">
					<input type="text" name="job_no" id="job_no" onDblClick="MsBudget.openJobWindow()" readonly placeholder=" Double Click"/>
					<input type="hidden" name="job_id" id="job_id" value=""/>
					<input type="hidden" name="style_id" id="style_id" value=""/>
					<input type="hidden" name="id" id="id" value=""/>

					</div>
					</div>
					<div class="row middle">
					<div class="col-sm-4 req-text">Costing Unit </div>
					<div class="col-sm-8">{!! Form::select('costing_unit_id', $costingunit,12,array('id'=>'costing_unit_id')) !!}</div>
					</div>
					<div class="row middle">
					<div class="col-sm-4 req-text">Budget Date </div>
					<div class="col-sm-8"><input type="text" name="budget_date" id="budget_date" class="datepicker"/></div>
					</div>

					<div class="row middle">
					<div class="col-sm-4 req-text">Company</div>
					<div class="col-sm-8">{!! Form::select('company_id', $company,'',array('id'=>'company_id','disabled'=>'disabled')) !!}</div>
					</div>
					<div class="row middle">
					<div class="col-sm-4 req-text">Buyer</div>
					<div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','disabled'=>'disabled')) !!}</div>
					</div>
					<div class="row middle">
					<div class="col-sm-4 req-text">Currency</div>
					<div class="col-sm-8">{!! Form::select('currency_id', $currency,'',array('id'=>'currency_id','disabled'=>'disabled')) !!}</div>
					</div>
					<div class="row middle">
					<div class="col-sm-4">Exchange Rate </div>
					<div class="col-sm-8"><input type="text" name="exch_rate" id="exch_rate" class="number integer" disabled/></div>
					</div>
					<div class="row middle">
					<div class="col-sm-4">Order Qty </div>
					<div class="col-sm-8"><input type="text" name="order_qty" id="order_qty"  disabled/></div>
					</div>
					<div class="row middle">
					<div class="col-sm-4">Cut Qty </div>
					<div class="col-sm-8"><input type="text" name="plan_cut_qty" id="plan_cut_qty"  disabled/></div>
					</div>
					<div class="row middle">
					<div class="col-sm-4">Order Amount </div>
					<div class="col-sm-8"><input type="text" name="order_amount" id="order_amount"  disabled/></div>
					</div>
					<div class="row middle">
					<div class="col-sm-4 req-text">UOM</div>
					<div class="col-sm-8">{!! Form::select('uom_id', $uom,'',array('id'=>'uom_id','disabled'=>'disabled')) !!}</div>
					</div>
					<div class="row middle">
					<div class="col-sm-4">Remarks</div>
					<div class="col-sm-8"><input type="text" name="remarks" id="remarks" value=""/></div>
					</div>
					</code>
						</div>
					</div>
					<div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
						<a href="javascript:void(0)" class="easyui-linkbutton  c1"
							style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save"
							onClick="MsBudget.submit()">Save</a>
						<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
							iconCls="icon-remove" plain="true" id="delete"
							onClick="msApp.resetForm('budgetFrm')">Reset</a>
						<a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
							iconCls="icon-remove" plain="true" id="delete" onClick="MsBudget.remove()">Delete</a>
						<a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
							plain="true" id="pdf" onClick="MsBudget.pdf()">Pdf</a>
						<a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
							plain="true" id="pdf" onClick="MsBudget.mos()">MOS</a>
						<a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
							plain="true" id="pdf" onClick="MsBudget.mosbyshipdate()">MOS-2</a>
					</div>
				</form>
			</div>
			<div data-options="region:'center',border:true,title:'List',footer:'#budgersc'" style="padding:2px">
				<table id="budgetTbl" width="930">
					<thead>
						<tr>
							<th data-options="field:'id'" width="80">ID</th>
							<th data-options="field:'job_no'" width="70">Job No</th>
							<th data-options="field:'style_ref'" width="70">Style Ref</th>
							<th data-options="field:'company_name'" width="70">Company</th>
							<th data-options="field:'buyer_name'" width="70">Buyer</th>
							<th data-options="field:'budget_date'" width="70">Budget Date</th>
							<th data-options="field:'currency_code'" width="70">Currency</th>
							<th data-options="field:'exch_rate'" width="70">Exch Rate</th>
							<th data-options="field:'uom_code'" width="70">Uom</th>
						</tr>
					</thead>
				</table>
				<div id="budgersc" style="padding: 0px 0px; text-align: right; background: #F3F3F3;">
					Buyer: {!! Form::select('buyer_search_id',
					$buyer,'',array('id'=>'buyer_search_id','style'=>'width:150px;height: 25px')) !!}
					Style: <input type="text" name="style_ref" id="style_ref" style="width:100px; height: 25px; ">
					Budget Date: <input type="text" name="from_date" id="from_date" class="datepicker"
						style="width: 100px ;height: 23px" />
					<input type="text" name="to_date" id="to_date" class="datepicker"
						style="width: 100px;height: 23px" />
					<a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
						iconCls="icon-search" plain="true" id="save" onClick="MsBudget.searchBudget()">Show</a>
				</div>
			</div>
		</div>
	</div>

	<div title="Fabric Cost" style="overflow:auto;padding:1px;">
		<form id="budgetfabricFrm">
			<code id="fabricdiv">
	    </code>
			<div id="ftfabric" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
				<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
					iconCls="icon-save" plain="true" id="save" onClick="MsBudgetFabric.submit()">Save</a>
				<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
					iconCls="icon-remove" plain="true" id="delete" onClick="MsBudgetFabric.resetForm()">Reset</a>
				<a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
					iconCls="icon-remove" plain="true" id="delete" onClick="MsBudgetFabric.remove()">Delete</a>
			</div>
		</form>
	</div>

	<div title="Narrow Fabric  Cost" style="padding:1px;">
		<form id="budgetnarrowfabricFrm">
			<code id="narrowfabricdiv">
        </code>
			<div id="ftfabric" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
				<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
					iconCls="icon-save" plain="true" id="save" onClick="MsBudgetNarrowFabric.submit()">Save</a>
				<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
					iconCls="icon-remove" plain="true" id="delete"
					onClick="msApp.resetForm('budgetnarrowfabricFrm')">Reset</a>
				<a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
					iconCls="icon-remove" plain="true" id="delete" onClick="MsBudgetNarrowFabric.remove()">Delete</a>
			</div>
		</form>
	</div>

	<div title="Yarn cost Summary" style="padding:1px; height:300px">
		<div class="easyui-layout" data-options="fit:true">
			<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
				<table id="budgetyarnTbl" style="width:100%">
					<thead>
						<tr>
							<th data-options="field:'yarn_des'" width="250px">Yarn</th>
							<th data-options="field:'yarn_ratio'" width="70px" align="right">Ratio</th>
							<th data-options="field:'yarn_cons'" width="70px" align="right">BOM Qty</th>
							<th data-options="field:'yarn_rate'" width="70px" align="right">Rate</th>
							<th data-options="field:'yarn_amount'" width="80px" align="right">Amount</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>

	<div title="Yarn Dyeing Cost" style="padding:1px; height:250px">
		<div class="easyui-layout" data-options="fit:true">
			<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
				<table id="budgetyarndyeingTbl" style="width:100%">
					<thead>
						<tr>
							<th data-options="field:'id'" width="50">ID</th>
							<th data-options="field:'budgetfabric'" width="400">Fabric Description..</th>
							<th data-options="field:'process_id'" width="150">Process</th>
							<th data-options="field:'cons'" width="70" align="right">BOM Qty</th>
							<th data-options="field:'rate'" width="70" align="right">Rate</th>
							<th data-options="field:'amount'" width="70" align="right">Amount</th>
							<th data-options="field:'overhead_rate'" width="70" align="right">Overhead Rate</th>
							<th data-options="field:'overhead_amount'" width="70" align="right">Overhead Amount</th>
							<th data-options="field:'total_amount'" width="70" align="right">Total Amount</th>
							<th data-options="field:'add_con'" formatter="MsBudgetYarnDyeing.formatAddCons" width="70">
								Breakdown</th>
						</tr>
					</thead>
				</table>
			</div>
			<div data-options="region:'west',border:true,title:'Add New',footer:'#budgetyarndyeingFrmFt'"
				style="width:350px; padding:2px">
				<form id="budgetyarndyeingFrm">
					<div id="container">
						<div id="body">
							<code>
	                        <div class="row">
	                            <div class="col-sm-4 req-text">Fabric: </div>
	                            <div class="col-sm-8">
	                                <select id="budget_fabric_id" name="budget_fabric_id" onChange="MsBudgetYarnDyeing.getFabricCons(this.value)"></select>
	                                <input type="hidden" name="id" id="id" value=""/>
	                                <input type="hidden" name="budget_id" id="budget_id" value=""/>
	                            </div>
	                        </div>
	                        <div class="row middle">
	                            <div class="col-sm-4 req-text">Process: </div>
	                            <div class="col-sm-8">
	                                {!! Form::select('production_process_id', $productionprocess_yarn_dyeing,'',array('id'=>'production_process_id','onChange'=>'MsBudgetYarnDyeing.processChange(this.value)')) !!}
	                            </div>
	                        </div>
	                        <div class="row middle">
	                         <div class="col-sm-4">Company</div>
	                         <div class="col-sm-8">{!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
	                         </div>
	                         </div>
	                        <div class="row middle">
	                            <div class="col-sm-4 req-text">Req. Bom Qty: </div>
	                            <div class="col-sm-8">
	                                <input type="text" name="req_cons" id="req_cons" class="number integer"  readonly />
	                            </div>
	                        </div>
	                        <div class="row middle">
	                            <div class="col-sm-4">Bom Qty: </div>
	                            <div class="col-sm-8">
	                                <input type="text" name="cons" id="cons" class="number integer" onChange="MsBudgetYarnDyeing.calculatemount()" readonly/>
	                            </div>
	                        </div>
	                        <div class="row middle">
	                            <div class="col-sm-4">Rate: </div>
	                            <div class="col-sm-8">
	                                <input type="text" name="rate" id="rate" class="number integer" onChange="MsBudgetYarnDyeing.calculatemount()" readonly/>
	                            </div>
	                        </div>
	                        <div class="row middle">
	                            <div class="col-sm-4">Amount </div>
	                            <div class="col-sm-8">
	                                <input type="text" name="amount" id="amount" class="number integer" readonly />
	                            </div>
	                        </div>
	                        
	                        <div class="row middle">
	                            <div class="col-sm-4">Over Head Rate: </div>
	                            <div class="col-sm-8">
	                                <input type="text" name="overhead_rate" id="overhead_rate" class="number integer" readonly/>
	                            </div>
	                        </div>
	                    </code>
						</div>
					</div>
					<div id="budgetyarndyeingFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
						<a href="javascript:void(0)" class="easyui-linkbutton  c1"
							style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save"
							onClick="MsBudgetYarnDyeing.submit()">Save</a>
						<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
							iconCls="icon-remove" plain="true" id="delete"
							onClick="msApp.resetForm('budgetyarndyeingFrm')">Reset</a>
						<a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
							iconCls="icon-remove" plain="true" id="delete"
							onClick="MsBudgetYarnDyeing.remove()">Delete</a>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div title="Fabric Production Cost" style="padding:1px; height:250px">
		<div class="easyui-layout" data-options="fit:true">
			<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
				<table id="budgetfabricprodTbl" style="width:100%">
					<thead>
						<tr>
							<th data-options="field:'id'" width="50">ID</th>
							<th data-options="field:'budgetfabric'" width="400">Fabric Description..</th>
							<th data-options="field:'process_id'" width="150">Process</th>
							<th data-options="field:'cons'" width="70" align="right">BOM Qty</th>
							<th data-options="field:'rate'" width="70" align="right">Rate</th>
							<th data-options="field:'amount'" width="70" align="right">Amount</th>
							<th data-options="field:'overhead_rate'" width="70" align="right">Overhead Rate</th>
							<th data-options="field:'overhead_amount'" width="70" align="right">Overhead Amount</th>
							<th data-options="field:'total_amount'" width="70" align="right">Total Amount</th>
							<th data-options="field:'add_con'" formatter="MsBudgetFabricProd.formatAddCons" width="70">
								Breakdown</th>
						</tr>
					</thead>
				</table>
			</div>
			<div data-options="region:'west',border:true,title:'Add New',footer:'#ftfaprod'"
				style="width:350px; padding:2px">
				<form id="budgetfabricprodFrm">
					<div id="container">
						<div id="body">
							<code>
	                        <div class="row">
	                            <div class="col-sm-4 req-text">Fabric: </div>
	                            <div class="col-sm-8">
	                                <select id="budget_fabric_id" name="budget_fabric_id" onChange="MsBudgetFabricProd.getFabricCons(this.value)"></select>
	                                <input type="hidden" name="id" id="id" value=""/>
	                                <input type="hidden" name="budget_id" id="budget_id" value=""/>
	                            </div>
	                        </div>
	                        <div class="row middle">
	                            <div class="col-sm-4 req-text">Process: </div>
	                            <div class="col-sm-8">
	                                {!! Form::select('production_process_id', $productionprocess,'',array('id'=>'production_process_id','onChange'=>'MsBudgetFabricProd.processChange(this.value)')) !!}
	                            </div>
	                        </div>
	                        <div class="row middle">
	                         <div class="col-sm-4 req-text">Company</div>
	                         <div class="col-sm-8">{!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
	                         </div>
	                         </div>
	                        <div class="row middle">
	                            <div class="col-sm-4 req-text">Req. Bom Qty: </div>
	                            <div class="col-sm-8">
	                                <input type="text" name="req_cons" id="req_cons" class="number integer"  readonly />
	                            </div>
	                        </div>
	                        <div class="row middle">
	                            <div class="col-sm-4">Bom Qty: </div>
	                            <div class="col-sm-8">
	                                <input type="text" name="cons" id="cons" class="number integer" onChange="MsBudgetFabricProd.calculatemount()" readonly/>
	                            </div>
	                        </div>
	                        <div class="row middle">
	                            <div class="col-sm-4">Rate: </div>
	                            <div class="col-sm-8">
	                                <input type="text" name="rate" id="rate" class="number integer" onChange="MsBudgetFabricProd.calculatemount()" readonly/>
	                            </div>
	                        </div>
	                        <div class="row middle">
	                            <div class="col-sm-4">Amount </div>
	                            <div class="col-sm-8">
	                                <input type="text" name="amount" id="amount" class="number integer" readonly />
	                            </div>
	                        </div>
	                        
	                        <div class="row middle">
	                            <div class="col-sm-4">Over Head Rate: </div>
	                            <div class="col-sm-8">
	                                <input type="text" name="overhead_rate" id="overhead_rate" class="number integer" readonly/>
	                            </div>
	                        </div>
	                    </code>
						</div>
					</div>
					<div id="ftfaprod" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
						<a href="javascript:void(0)" class="easyui-linkbutton  c1"
							style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save"
							onClick="MsBudgetFabricProd.submit()">Save</a>
						<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
							iconCls="icon-remove" plain="true" id="delete"
							onClick="msApp.resetForm('budgetfabricprodFrm')">Reset</a>
						<a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
							iconCls="icon-remove" plain="true" id="delete"
							onClick="MsBudgetFabricProd.remove()">Delete</a>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div title="Trim Cost" style="padding:1px; height:350px">
		<div class="easyui-layout" data-options="fit:true">
			<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
				<table id="budgettrimTbl" width="1040">{{-- 1040 --}}
					<thead>
						<tr>
							<th data-options="field:'id',halign:'center'" width="50">ID</th>
							<th data-options="field:'item_account',halign:'center'" width="150">Item Class</th>
							<th data-options="field:'description',halign:'center'" width="200">Description</th>
							<th data-options="field:'sup_ref',halign:'center'" width="100">Sup Ref </th>
							<th data-options="field:'uom',halign:'center'" width="50">Uom</th>
							<th data-options="field:'cons',halign:'center'" width="50" align="right">BOM Qty</th>
							<th data-options="field:'rate',halign:'center'" width="50" align="right">Rate</th>
							<th data-options="field:'amount',halign:'center'" width="50" align="right">Amount</th>
							<th data-options="field:'add_con',halign:'center'" formatter="MsBudgetTrim.formatAddCons"
								width="50">Break Down</th>
							<th data-options="field:'add_dtm',halign:'center'" formatter="MsBudgetTrim.formatAddDtm"
								width="50">DTM</th>
						</tr>
					</thead>
				</table>
			</div>
			<div data-options="region:'west',border:true,title:'Add New',footer:'#fttrim'"
				style="width:350px; padding:2px">
				<form id="budgettrimFrm">
					<div id="container">
						<div id="body">
							<code>
        <div class="row middle">
        <div class="col-sm-4 req-text">Item Class </div>
        <div class="col-sm-8">
        {!! Form::select('itemclass_id', $trimgroup,'',array('id'=>'itemclass_id','onChange'=>'MsBudgetTrim.setUom(this.value)')) !!}
        <input type="hidden" name="id" id="id" value=""/>
        <input type="hidden" name="budget_id" id="budget_id" value=""/>
        </div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Supplier </div>
        <div class="col-sm-8">
        {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id')) !!}
        </div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Description </div>
        <div class="col-sm-8"><input type="text" name="description" id="description" /></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Specification </div>
        <div class="col-sm-8"><input type="text" name="specification" id="specification" /></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Item Size </div>
        <div class="col-sm-8"><input type="text" name="item_size" id="item_size" /></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Sup Ref </div>
        <div class="col-sm-8"><input type="text" name="sup_ref" id="sup_ref" /></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">UOM </div>
        <input type="hidden" name="uom_id" id="uom_id"/>
        <div class="col-sm-8">{!! Form::select('uom_name', $uom,'',array('id'=>'uom_name','disabled'=>'disabled')) !!}</div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Bom Qty </div>
        <div class="col-sm-8"><input type="text" name="cons" id="cons" class="number integer" onChange="MsBudgetTrim.calculatemount()" readonly /></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Rate </div>
        <div class="col-sm-8"><input type="text" name="rate" id="rate" class="number integer" onChange="MsBudgetTrim.calculatemount()" readonly/></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Amount </div>
        <div class="col-sm-8"><input type="text" name="amount" id="amount" class="number integer" readonly /></div>
        </div>

        </code>
						</div>
					</div>
					<div id="fttrim" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
						<a href="javascript:void(0)" class="easyui-linkbutton  c1"
							style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save"
							onClick="MsBudgetTrim.submit()">Save</a>
						<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
							iconCls="icon-remove" plain="true" id="delete"
							onClick="msApp.resetForm('budgettrimFrm')">Reset</a>
						<a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
							iconCls="icon-remove" plain="true" id="delete" onClick="MsBudgetTrim.remove()">Delete</a>
					</div>

				</form>
			</div>
		</div>
	</div>

	<div title="Embelishment & Wash Cost" style="padding:1px;">
		<form id="budgetembFrm">
			<code id="emb">
        </code>
			<div id="ftemb" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
				<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
					iconCls="icon-save" plain="true" id="save" onClick="MsBudgetEmb.submit()">Save</a>
				<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
					iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('budgetembFrm')">Reset</a>
				<a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
					iconCls="icon-remove" plain="true" id="delete" onClick="MsBudgetEmb.remove()">Delete</a>
			</div>
		</form>
	</div>

	<div title="Other Cost" style="padding:1px; height:450px">
		<div class="easyui-layout" data-options="fit:true">
			<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
				<table id="budgetotherTbl" style="width:100%">
					<thead>
						<tr>
							<th data-options="field:'id'" width="80">ID</th>
							<th data-options="field:'cost_head_id'" width="70" align="right">Cost Head</th>
							<th data-options="field:'amount'" width="70" align="right">Cost/Unit </th>
							<th data-options="field:'bom_amount'" width="70" align="right">Amount</th>
						</tr>
					</thead>
				</table>
			</div>
			<div data-options="region:'west',border:true,title:'Add New',footer:'#ftother'"
				style="width:350px; padding:2px">
				<form id="budgetotherFrm">
					<div id="container">
						<div id="body">
							<code>

        <div class="row middle">
									<div class="col-sm-4 req-text">Cost Head: </div>
									<div class="col-sm-8">
									{!! Form::select('cost_head_id', $othercosthead,'',array('id'=>'cost_head_id')) !!}
									<input type="hidden" name="id" id="id" value=""/>
									<input type="hidden" name="budget_id" id="budget_id" value=""/>
									</div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Cost/Unit </div>
        <div class="col-sm-8"><input type="text" name="amount" id="amount" class="number integer" onChange="MsBudgetOther.calculate()" /></div>
        </div>
        <div class="row middle">
         <div class="col-sm-4">Amount </div>
        <div class="col-sm-8"><input type="text" name="bom_amount" id="bom_amount" class="number integer" readonly /></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Order Qty </div>
        <div class="col-sm-5" style="padding-right:0px"><input type="text" name="order_qty" id="order_qty" class="number integer" disabled /></div>
        <div class="col-sm-3" style="padding-left:0px">{!! Form::select('uom_id', $uom,'',array('id'=>'uom_id','disabled'=>'disabled')) !!}</div>
        </div>
        </code>
						</div>
					</div>
					<div id="ftother" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
						<a href="javascript:void(0)" class="easyui-linkbutton  c1"
							style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save"
							onClick="MsBudgetOther.submit()">Save</a>
						<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
							iconCls="icon-remove" plain="true" id="delete"
							onClick="msApp.resetForm('budgetotherFrm')">Reset</a>
						<a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
							iconCls="icon-remove" plain="true" id="delete" onClick="MsBudgetOther.remove()">Delete</a>
					</div>

				</form>
			</div>
		</div>
	</div>

	<div title="CM Cost" style="padding:1px;;height:300px">
		<div class="easyui-layout" data-options="fit:true">
			<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
				<table id="budgetcmTbl" style="width:100%">
					<thead>
						<tr>
							<th data-options="field:'id'" width="80">ID</th>
							<th data-options="field:'name'" width="80">Item</th>
							<th data-options="field:'gmt_qty'" width="80">Item Ratio</th>
							<th data-options="field:'smv'" width="70" align="right">SMV</th>
							<th data-options="field:'sewing_effi_per'" width="70" align="right">Sewing Effi.%</th>
							<th data-options="field:'cpm'" width="70" align="right">CPM</th>
							<th data-options="field:'cm_per_pcs'" width="70" align="right">CM/Pcs</th>
							<th data-options="field:'no_of_man_power'" width="70" align="right">Manpower</th>
							<th data-options="field:'prod_per_hour'" width="70" align="right">Prod/Hour</th>
							<th data-options="field:'amount'" width="70" align="right">Cost/Unit</th>
							<th data-options="field:'bom_amount'" width="70" align="right">Amount</th>
						</tr>
					</thead>
				</table>
			</div>
			<div data-options="region:'west',border:true,title:'Add New',footer:'#ftcm'"
				style="width:350px; padding:2px">
				<form id="budgetcmFrm">
					<div id="container">
						<div id="body">
							<code>


		<div class="row middle">
        <div class="col-sm-4 req-text">Gmts. Item </div>
        <div class="col-sm-8">
        
        <input type="hidden" name="id" id="id" readonly/>
		<input type="hidden" name="budget_id" id="budget_id" readonly/>
        <input type="hidden" name="style_gmt_id" id="style_gmt_id" readonly/>
        <input type="text" name="style_gmt_name" id="style_gmt_name" readonly ondblclick="MsBudgetCm.getGmtItem()" />
        </div>
        </div>
        <div class="row middle">
            <div class="col-sm-4 req-text">SMV </div>
            <div class="col-sm-8">
                <input type="text" name="smv" id="smv" class="number integer" onchange="MsBudgetCm.calCmPerPcs()"/>
            </div>
        </div>
        <div class="row middle">
            <div class="col-sm-4 req-text">Sewing Effi.%</div>
            <div class="col-sm-8">
                <input type="text" name="sewing_effi_per" id="sewing_effi_per" class="number integer" onchange="MsBudgetCm.calCmPerPcs()"/>
            </div>
        </div>
        <div class="row middle">
            <div class="col-sm-4 req-text">CPM</div>
            <div class="col-sm-8">
                <input type="text" name="cpm" id="cpm" class="number integer" disabled />
            </div>
        </div>
        <div class="row middle">
            <div class="col-sm-4 req-text">CM/Pcs</div>
            <div class="col-sm-8">
                <input type="text" name="cm_per_pcs" id="cm_per_pcs" class="number integer" readonly />
            </div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">No of Manpower </div>
        <div class="col-sm-8"><input type="text" name="no_of_man_power" id="no_of_man_power" class="number integer" onchange="MsBudgetCm.calProdHour()" /></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Prod/Hour </div>
        <div class="col-sm-8"><input type="text" name="prod_per_hour" id="prod_per_hour" class="number integer" readonly /></div>
        </div>
		<!-- <div class="row middle">
		<div class="col-sm-4">Cost/Unit </div>
		<div class="col-sm-8"><input type="text" name="amount" id="amount" class="number integer" onChange="MsBudgetCm.calculate()" readonly /></div>
		</div>
		<div class="row middle">
		<div class="col-sm-4">Amount </div>
		<div class="col-sm-8"><input type="text" name="bom_amount" id="bom_amount" class="number integer" readonly /></div>
		</div>
		<div class="row middle">
		<div class="col-sm-4">Order Qty </div>
		<div class="col-sm-5" style="padding-right:0px"><input type="text" name="order_qty" id="order_qty" class="number integer" disabled /></div>
		<div class="col-sm-3" style="padding-left:0px">{!! Form::select('uom_id', $uom,'',array('id'=>'uom_id','disabled'=>'disabled')) !!}</div>
		</div> -->
		</code>
						</div>
					</div>
					<div id="ftcm" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
						<a href="javascript:void(0)" class="easyui-linkbutton  c1"
							style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save"
							onClick="MsBudgetCm.submit()">Save</a>
						<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
							iconCls="icon-remove" plain="true" id="delete"
							onClick="msApp.resetForm('budgetcmFrm')">Reset</a>
						<a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
							iconCls="icon-remove" plain="true" id="delete" onClick="MsBudgetCm.remove()">Delete</a>
					</div>

				</form>
			</div>
		</div>
	</div>

	<div title="Commercial Cost" style="padding:1px;height:165px">
		<div class="easyui-layout" data-options="fit:true">
			<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
				<table id="budgetcommercialTbl" width="170">
					<thead>
						<tr>
							<th data-options="field:'id'" width="30">ID</th>
							<th data-options="field:'rate'" width="70" align="right">Rate</th>
							<th data-options="field:'amount'" width="70" align="right">Amount</th>
						</tr>
					</thead>
				</table>
			</div>
			<div data-options="region:'west',border:true,title:'Add New',footer:'#ftcommercial'"
				style="width:350px; padding:2px">
				<form id="budgetcommercialFrm">
					<div id="container">
						<div id="body">
							<code>
        <div class="row middle">
        <div class="col-sm-4 req-text">Rate: </div>
        <div class="col-sm-8">
        <input type="text" name="rate" id="rate" class="number integer" onChange="MsBudgetCommercial.calculatemount()"/>
        <input type="hidden" name="id" id="id" readonly/>
        <input type="hidden" name="budget_id" id="budget_id" readonly/>
        </div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Amount </div>
        <div class="col-sm-8"><input type="text" name="amount" id="amount" class="number integer" readonly/></div>
        </div>

        </code>
						</div>
					</div>
					<div id="ftcommercial" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
						<a href="javascript:void(0)" class="easyui-linkbutton  c1"
							style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save"
							onClick="MsBudgetCommercial.submit()">Save</a>
						<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
							iconCls="icon-remove" plain="true" id="delete"
							onClick="msApp.resetForm('budgetcommercialFrm')">Reset</a>
						<a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
							iconCls="icon-remove" plain="true" id="delete"
							onClick="MsBudgetCommercial.remove()">Delete</a>
					</div>

				</form>
			</div>
		</div>
	</div>

	<div title="Commission Cost" style="padding:1px;height:200px">
		<div class="easyui-layout" data-options="fit:true">
			<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
				<table id="budgetcommissionTbl" style="width:100%">
					<thead>
						<tr>
							<th data-options="field:'id'" width="80">ID</th>
							<th data-options="field:'for_id'" width="70" align="right">For</th>
							<th data-options="field:'rate'" width="70" align="right" class="dzn-pcs">Rate</th>
							<th data-options="field:'amount'" width="70" align="right" class="dzn-pcs">Amount</th>
						</tr>
					</thead>
				</table>
			</div>
			<div data-options="region:'west',border:true,title:'Add New',footer:'#ftcommission'"
				style="width:350px; padding:2px">
				<form id="budgetcommissionFrm">
					<div id="container">
						<div id="body">
							<code>
						<div class="row">
						<div class="col-sm-4 req-text">For </div>
						<div class="col-sm-8">
						{!! Form::select('for_id', $commissionfor,'',array('id'=>'for_id')) !!}
						<input type="hidden" name="id" id="id" readonly/>
						<input type="hidden" name="budget_id" id="budget_id" readonly/>
						</div>
						</div>
						<div class="row middle">
						<div class="col-sm-4 dzn-pcs req-text">Rate </div>
						<div class="col-sm-8">
						<input type="text" name="rate" id="rate" class="number integer" onChange="MsBudgetCommission.calculatemount()"/>
						</div>
						</div>
						<div class="row middle">
						<div class="col-sm-4 dzn-pcs">Amount </div>
						<div class="col-sm-8"><input type="text" name="amount" id="amount" class="number integer" readonly/></div>
						</div>
						<div class="row middle">
						<div class="col-sm-4">Order Amount </div>
						<div class="col-sm-8"><input type="text" name="order_amount" id="order_amount" class="number integer"  disabled/></div>
						</div>
					</code>
						</div>
					</div>
					<div id="ftcommission" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
						<a href="javascript:void(0)" class="easyui-linkbutton  c1"
							style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save"
							onClick="MsBudgetCommission.submit()">Save</a>
						<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
							iconCls="icon-remove" plain="true" id="delete"
							onClick="msApp.resetForm('budgetcommissionFrm')">Reset</a>
						<a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
							iconCls="icon-remove" plain="true" id="delete"
							onClick="MsBudgetCommission.remove()">Delete</a>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div title="Total Cost" style="padding:1px; height:100px">
		<div class="easyui-layout" data-options="fit:true">
			<div data-options="region:'east',border:true,footer:'#ftmcqtp'" style="width:350px; padding:12px">
				<form id="budgettotalFrm">
					<code>
                            <div class="row">
                                <div class="col-sm-6 dzn-pcs">Total Cost </div>
                                <div class="col-sm-6">
                                    <input type="text" name="total_cost" id="total_cost" class="number integer" readonly/>
                                </div>
                            </div>
                            <!--<div class="row middle">
                                <div class="col-sm-6">Total Cost/Pcs </div>
                                <div class="col-sm-6">
                                    <input type="text" name="total_cost_pcs" id="total_cost_pcs" class="number integer" readonly/>
                                </div>
                            </div>-->
                        </code>
				</form>
			</div>
		</div>
	</div>
</div>

<div id="jobwindow" class="easyui-window" title="Style Window" data-options="modal:true,closed:true,iconCls:'icon-save'"
	style="width:1000px;height:500px;padding:2px;">
	<div class="easyui-layout" data-options="fit:true">
		<div data-options="region:'north',split:true, title:'Search'" style="height:160px">
			<div class="easyui-layout" data-options="fit:true">
				<div id="body">
					<code>
					<form id="jobsearch">
						<div class="row">
							<div class="col-sm-2">Company</div>
							<div class="col-sm-4">
								{!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
							</div>
							<div class="col-sm-2">Buyer</div>
							<div class="col-sm-4">
								{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
							</div>
							</div>
							<div class="row middle">
							<div class="col-sm-2 req-text">Job No. </div>
							<div class="col-sm-4"><input type="text" name="job_no" id="job_no" value=""/></div>
							<div class="col-sm-2 req-text">Style Ref. </div>
							<div class="col-sm-4"><input type="text" name="style_ref" id="style_ref" value=""/></div>
							</div>
							<div class="row middle">
							<div class="col-sm-2">Style Des.  </div>
							<div class="col-sm-4"><input type="text" name="style_description" id="style_description" value=""/></div>
						</div>
					</form>
				</code>
				</div>
				<p class="footer">
					<a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:30px"
						onClick="MsBudget.showJobGrid()">Search</a>
				</p>
			</div>
		</div>
		<div data-options="region:'center'" style="padding:10px;">
			<table id="jobsearchTbl" style="width:610px">
				<thead>
					<tr>
						<th data-options="field:'id'" width="80">ID</th>
						<th data-options="field:'job_no'" width="80">Job No</th>
						<th data-options="field:'company_name'" width="100">Company</th>
						<th data-options="field:'style_ref'" width="100">Style</th>
						<th data-options="field:'buyer_name'" width="100">Buyer</th>
						<th data-options="field:'currency_name'" width="70">Currency</th>
						<th data-options="field:'uom_name'" width="60">UOM</th>
						<th data-options="field:'season_name'" width="100">Season</th>
					</tr>
				</thead>
			</table>
		</div>
		<div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
			<a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
				onclick="$('#w').window('close')" style="width:80px">Close</a>
		</div>
	</div>
</div>

<div id="budgetfabricconsWindow" class="easyui-window" title="Fabric Cons Window"
	data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
	<div class="easyui-layout" data-options="fit:true">
		<div data-options="region:'center',border:true,footer:'#fabricconsfooter'" style="padding:2px">
			<form id="budgetfabricconFrm">
				<div style="text-align:right; background:#f9f9f9; padding-right:100px">
					<input type="checkbox" name="is_copy" id="is_copy" checked /><strong> Copy</strong>
				</div>
				<code id="budgetfabricconscs" style="margin:0px">
                </code>
			</form>
		</div>
		<div id="fabricconsfooter" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
			<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
				iconCls="icon-save" plain="true" id="save" onClick="MsBudgetFabricCon.submit()">Save</a>
			<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
				iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('budgetfabricconFrm')">Reset</a>
			<a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
				iconCls="icon-remove" plain="true" id="delete" onClick="MsBudgetFabricCon.remove()">Delete</a>
		</div>
	</div>
</div>

<div id="yarnWindow" class="easyui-window" title="Add Yarn" data-options="modal:true,closed:true,iconCls:'icon-save'"
	style="width:100%;height:100%;padding:2px;">
	<div class="easyui-layout" data-options="fit:true">
		<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
			<table id="budgetyarnpopupTbl" style="width:100%">
				<thead>
					<tr>
						<th data-options="field:'id',halign:'center'" width="30">ID</th>
						<th data-options="field:'yarn_des',halign:'center'" width="400">Yarn</th>
						<th data-options="field:'yarn_ratio',halign:'center'" width="50" align="right">Ratio</th>
						<th data-options="field:'yarn_cons',halign:'center'" width="70" align="right">BOM Qty</th>
						<th data-options="field:'yarn_rate',halign:'center'" width="30" align="right">Rate</th>
						<th data-options="field:'yarn_amount',halign:'center'" width="70" align="right">Amount</th>
					</tr>
				</thead>
			</table>
		</div>
		<div data-options="region:'west',border:true,title:'Add New BudgetYarn',footer:'#ftyarn'"
			style="width:550px; padding:2px">
			<form id="budgetyarnFrm">
				<div id="container">
					<div id="body">
						<code id="yarndiv">
        </code>
					</div>
				</div>
				<div id="ftyarn" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
					<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
						iconCls="icon-save" plain="true" id="save" onClick="MsBudgetYarn.submit()">Save</a>
					<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
						iconCls="icon-remove" plain="true" id="delete" onClick="MsBudgetYarn.resetForm()">Reset</a>
					<a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
						iconCls="icon-remove" plain="true" id="delete" onClick="MsBudgetYarn.remove()">Delete</a>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="itemWindow" class="easyui-window" title="Add Yarn" data-options="modal:true,closed:true,iconCls:'icon-save'"
	style="width:1000px;height:550px;padding:2px;">
</div>

<div id="TrimconsWindow" class="easyui-window" title="Trim PopUp"
	data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
	<form id="budgettrimconFrm">
		<code id="trimscs">
		</code>
		<div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
			<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
				iconCls="icon-save" plain="true" id="save" onClick="MsBudgetTrimCon.submit()">Save</a>
			<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
				iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('budgettrimconFrm')">Reset</a>
			<a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
				iconCls="icon-remove" plain="true" id="delete" onClick="MsBudgetTrimCon.remove()">Delete</a>
		</div>
	</form>
</div>

<div id="TrimdtmWindow" class="easyui-window" title="Trim DTM" data-options="modal:true,closed:true,iconCls:'icon-save'"
	style="width:1000px;height:500px;padding:2px;">
	<div class="easyui-layout" data-options="fit:true">
		<div data-options="region:'center',border:true,footer:'#trimdtmfooter',title:'List'" style="padding:2px">
			<form id="budgettrimdtmFrm">
				<code id="trimdtmscs">
        </code>
			</form>
			<div id="trimdtmfooter" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
				<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
					iconCls="icon-save" plain="true" id="save" onClick="MsBudgetTrimDtm.submit()">Save</a>
				<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
					iconCls="icon-remove" plain="true" id="delete" onClick="MsBudgetTrimDtm.resetForm()">Reset</a>
				<a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
					iconCls="icon-remove" plain="true" id="delete" onClick="MsBudgetTrimDtm.remove()">Delete</a>
			</div>
		</div>
	</div>
</div>

<div id="EmbconsWindow" class="easyui-window" title="Emb PopUp"
	data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
	<form id="budgetembconFrm">
		<code id="embscs">
		</code>
		<div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
			<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
				iconCls="icon-save" plain="true" id="save" onClick="MsBudgetEmbCon.submit()">Save</a>
			<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
				iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('budgetembconFrm')">Reset</a>
			<a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
				iconCls="icon-remove" plain="true" id="delete" onClick="MsBudgetEmbCon.remove()">Delete</a>
		</div>
	</form>
</div>

<div id="BudgetFabricProdConsWindow" class="easyui-window" title="Pop Up"
	data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:700px;height:600px;padding:2px;">
	<form id="budgetfabricprodconFrm">
		<code id="budgetfabricprodconscs">
		</code>
		<div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
			<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
				iconCls="icon-save" plain="true" id="save" onClick="MsBudgetFabricProdCon.submit()">Save</a>
			<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
				iconCls="icon-remove" plain="true" id="delete"
				onClick="msApp.resetForm('budgetfabricprodconFrm')">Reset</a>
			<a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
				iconCls="icon-remove" plain="true" id="delete" onClick="MsBudgetFabricProdCon.remove()">Delete</a>
		</div>
	</form>
</div>

<div id="budgetyarnsearchWindow" class="easyui-window" title="Search Yarn"
	data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
	<div class="easyui-layout" data-options="fit:true">
		<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
			<table id="budgetyarnsearchTbl" style="width:100%">
				<thead>
					<tr>
						<th data-options="field:'id'" width="80">ID</th>
						<th data-options="field:'itemclass_name'" width="70">Item Class</th>
						<th data-options="field:'count'" width="70">Count</th>
						<th data-options="field:'yarn_type'" width="70">Type</th>
						<th data-options="field:'composition_name'" width="70">Compositions</th>
						<th data-options="field:'smp_ratio'" width="70">Ratio</th>
						<th data-options="field:'rate'" width="70">Rate</th>
					</tr>
				</thead>
			</table>
		</div>
		<div data-options="region:'west',border:true,title:'Search',footer:'#ftyarnSearch'"
			style="width:350px; padding:2px">
			<form id="budgetyarnsearchFrm">
				<div id="container">
					<div id="body">
						<code>
                        <div class="row">
                        <div class="col-sm-4 req-text">Count  </div>
                        <div class="col-sm-8"><input type="text" name="count_name" id="count_name"/></div>
                        </div>
                        <div class="row middle">
                        <div class="col-sm-4 req-text">Type  </div>
                        <div class="col-sm-8"><input type="text" name="type_name" id="type_name" /></div>
                        </div>
                    </code>
					</div>
				</div>
				<div id="ftyarnSearch" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
					<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
						iconCls="icon-save" plain="true" id="save" onClick="MsBudgetYarn.budgetyarnsearch()">Search</a>
					<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
						iconCls="icon-remove" plain="true" id="delete"
						onClick="msApp.resetForm('budgetyarnsearchFrm')">Reset</a>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="BudgetYarnDyeingConsWindow" class="easyui-window" title="Pop Up"
	data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
	<form id="budgetyarndyeingconFrm">
		<code id="budgetyarndyeingconscs">
		</code>
		<div id="budgetyarndyeingconFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
			<a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
				iconCls="icon-save" plain="true" id="save" onClick="MsBudgetYarnDyeingCon.submit()">Save</a>
			<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
				iconCls="icon-remove" plain="true" id="delete"
				onClick="msApp.resetForm('budgetyarndyeingconFrm')">Reset</a>
			<a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
				iconCls="icon-remove" plain="true" id="delete" onClick="MsBudgetYarnDyeingCon.remove()">Delete</a>
		</div>
	</form>
</div>
<div id="budgetCmGmtItemWindow" class="easyui-window" title="Gmts. Item"
	data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
	<table id="budgetcmgmtsTbl" style="width:100%">
		<thead>
			<tr>
				<th data-options="field:'id'" width="80">ID</th>
				<th data-options="field:'itemaccount'" width="100">Item Account</th>
				<th data-options="field:'gmtqty'" width="70" align="right">GMT Qty Ratio</th>
				<th data-options="field:'itemcomplexity'" width="100">Item Complexity</th>
				<th data-options="field:'gmtcategory'" width="100">GMT Category</th>
				<th data-options="field:'created_by_user'" width="100">Entry By</th>
				<th data-options="field:'created_at'" width="100">Entry At</th>
				<th data-options="field:'updated_by_user'" width="100">Updated By</th>
				<th data-options="field:'updated_at'" width="100">Updated At</th>
				<th data-options="field:'article'" width="100">Article</th>
				<th data-options="field:'smv'" width="100">SMV</th>
				<th data-options="field:'sewing_effi_per'" width="100">Sewing Effi. %</th>
				<th data-options="field:'no_of_man_power'" width="100">No of Manpower </th>
				<th data-options="field:'prod_per_hour'" width="100">Prod/Hour</th>
				<th data-options="field:'remarks'" width="100">Remarks</th>
				<th data-options="field:'cpm'" width="100">CPM</th>
			</tr>
		</thead>
	</table>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAllBudgetController.js"></script>
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
		$('#jobsearch [id="buyer_id"]').combobox();
	})(jQuery);
</script>