let MsAssetQuantityCostModel = require('./MsAssetQuantityCostModel');
class MsAssetQuantityCostController {
	constructor(MsAssetQuantityCostModel) {
		this.MsAssetQuantityCostModel = MsAssetQuantityCostModel;
		this.formId = 'assetquantitycostFrm';
		this.dataTable = '#assetquantitycostTbl';
		this.route = msApp.baseUrl() + "/assetquantitycost"
	}

	submit() {
		$.blockUI({
			message: '<i class="icon-spinner4 spinner">Saving...</i>',
			overlayCSS: {
				backgroundColor: '#1b2024',
				opacity: 0.8,
				zIndex: 999999,
				cursor: 'wait'
			},
			css: {
				border: 0,
				color: '#fff',
				padding: 0,
				zIndex: 9999999,
				backgroundColor: 'transparent'
			}
		});	

		$('#OpenQuantityWindow').window('close');

		let formObj = msApp.get(this.formId);
		if (formObj.id) {
			this.MsAssetQuantityCostModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsAssetQuantityCostModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}


	resetForm() {
		msApp.resetForm(this.formId);
		$('#assetquantitycostFrm [id="division_id"]').combobox('setValue', '');
		$('#assetquantitycostFrm [id="department_id"]').combobox('setValue', '');
		$('#assetquantitycostFrm [id="section_id"]').combobox('setValue', '');
		$('#assetquantitycostFrm [id="subsection_id"]').combobox('setValue', '');
		$('#assetquantitycostFrm [id="floor_id"]').combobox('setValue', '');
	}

	remove() {
		let formObj = msApp.get(this.formId);
		this.MsAssetQuantityCostModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id) {
		event.stopPropagation()
		this.MsAssetQuantityCostModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d) {
		$('#assetquantitycostTbl').datagrid('reload');
		//msApp.resetForm('assetquantitycostFrm');
		$('#assetquantitycostFrm [name=asset_acquisition_id]').val($('#assetacquisitionFrm [name=id]').val());
		$('#assetquantitycostFrm [id="division_id"]').combobox('setValue', '');
		$('#assetquantitycostFrm [id="department_id"]').combobox('setValue', '');
		$('#assetquantitycostFrm [id="section_id"]').combobox('setValue', '');
		$('#assetquantitycostFrm [id="subsection_id"]').combobox('setValue', '');
		$('#assetquantitycostFrm [id="floor_id"]').combobox('setValue', '');
	}

	edit(index, row) {
		row.route = this.route;
		row.formId = this.formId;
		let assetqty=this.MsAssetQuantityCostModel.get(index, row);
		assetqty.then(function (response) {	
			$('#assetquantitycostFrm [id="division_id"]').combobox('setValue', response.data.fromData.division_id);
			$('#assetquantitycostFrm [id="department_id"]').combobox('setValue', response.data.fromData.department_id);
			$('#assetquantitycostFrm [id="section_id"]').combobox('setValue', response.data.fromData.section_id);
			$('#assetquantitycostFrm [id="subsection_id"]').combobox('setValue', response.data.fromData.subsection_id);
			$('#assetquantitycostFrm [id="floor_id"]').combobox('setValue', response.data.fromData.floor_id);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(asset_acquisition_id) {
		let self = this;
		var data = {};
		data.asset_acquisition_id = asset_acquisition_id;
		$(this.dataTable).datagrid({
			method: 'get',
			border: false,
			singleSelect: true,
			fit: true,
			queryParams: data,
			fitColumns: true,
			url: this.route,
			onClickRow: function (index, row) {
				self.edit(index, row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value, row) {
		return '<a href="javascript:void(0)" onClick="MsAssetQuantityCost.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	copyRate(rate, iteration, count) {
		for (var i = iteration; i <= count; i++) {
			let vendor_price = rate;
			$('input[name="vendor_price[' + i + ']"]').val(vendor_price);
			let landed_price = +($('#assetquantitycostFrm [name="landed_price[' + i + ']"]').val());
			let machanical_cost = +($('#assetquantitycostFrm [name="machanical_cost[' + i + ']"]').val());
			let civil_cost = +($('#assetquantitycostFrm [name="civil_cost[' + i + ']"]').val());
			let electrical_cost = +($('#assetquantitycostFrm [name="electrical_cost[' + i + ']"]').val());
			let total_cost = 1 * (rate + landed_price + machanical_cost + civil_cost + electrical_cost);
			$('input[name="rate[' + i + ']"]').val(rate);
			$('input[name="total_cost[' + i + ']"]').val(total_cost);
		}
	}

	getTotalCostCol(iteration, count, field) {
		//alert(parseFloat(1,2));
		//for(var i=iteration;i<=count;i++){
		let rate = +($('#assetquantitycostFrm [name="rate[' + iteration + ']"]').val());
		let vendor_price = rate;
		$('#assetquantitycostFrm [name="vendor_price[' + iteration + ']"]').val(vendor_price);
		let landed_price = +($('#assetquantitycostFrm [name="landed_price[' + iteration + ']"]').val());
		let machanical_cost = +($('#assetquantitycostFrm [name="machanical_cost[' + iteration + ']"]').val());
		let civil_cost = +($('#assetquantitycostFrm [name="civil_cost[' + iteration + ']"]').val());
		let electrical_cost = +($('#assetquantitycostFrm [name="electrical_cost[' + iteration + ']"]').val());
		let total_cost = 1 * (rate + landed_price + machanical_cost + civil_cost + electrical_cost);
		$('#assetquantitycostFrm [name="total_cost[' + iteration + ']"]').val(total_cost);
		//$('input[name="vendor_price['+iteration+']"]').val(vendor_price);
		if (field === 'rate') {
			this.copyRate(rate, iteration, count);
		} else if (field === 'landed_price') {
			this.copyLandPrice(landed_price, iteration, count);
		} else if (field === 'machanical_cost') {
			this.copyMachanicCost(machanical_cost, iteration, count);
		} else if (field === 'civil_cost') {
			this.copyCivilCost(civil_cost, iteration, count);
		} else if (field === 'electrical_cost') {
			this.copyElectricalCost(electrical_cost, iteration, count);
		}

		//}

	}



	copyLandPrice(landed_price, iteration, count) {
		for (var i = iteration; i <= count; i++) {
			let rate = +(($('#assetquantitycostFrm [name="rate[' + i + ']"]').val()));
			let machanical_cost = +($('#assetquantitycostFrm [name="machanical_cost[' + i + ']"]').val());
			let civil_cost = +($('#assetquantitycostFrm [name="civil_cost[' + i + ']"]').val());
			let electrical_cost = +($('#assetquantitycostFrm [name="electrical_cost[' + i + ']"]').val());
			let total_cost = 1 * (rate + landed_price + machanical_cost + civil_cost + electrical_cost);
			$('input[name="landed_price[' + i + ']"]').val(landed_price)
			$('input[name="total_cost[' + i + ']"]').val(total_cost)
		}
	}

	copyMachanicCost(machanical_cost, iteration, count) {
		for (var i = iteration; i <= count; i++) {
			let landed_price = +($('#assetquantitycostFrm [name="landed_price[' + i + ']"]').val());
			let rate = +(($('#assetquantitycostFrm [name="rate[' + i + ']"]').val()));
			let civil_cost = +($('#assetquantitycostFrm [name="civil_cost[' + i + ']"]').val());
			let electrical_cost = +($('#assetquantitycostFrm [name="electrical_cost[' + i + ']"]').val());
			let total_cost = 1 * (rate + landed_price + machanical_cost + civil_cost + electrical_cost);
			$('input[name="machanical_cost[' + i + ']"]').val(machanical_cost)
			$('input[name="total_cost[' + i + ']"]').val(total_cost)
		}
	}
	copyCivilCost(civil_cost, iteration, count) {
		for (var i = iteration; i <= count; i++) {
			let rate = +(($('#assetquantitycostFrm [name="rate[' + i + ']"]').val()));
			let landed_price = +($('#assetquantitycostFrm [name="landed_price[' + i + ']"]').val());
			let machanical_cost = +($('#assetquantitycostFrm [name="machanical_cost[' + i + ']"]').val());
			let electrical_cost = +($('#assetquantitycostFrm [name="electrical_cost[' + i + ']"]').val());
			let total_cost = 1 * (rate + landed_price + machanical_cost + civil_cost + electrical_cost);
			$('input[name="civil_cost[' + i + ']"]').val(civil_cost)
			$('input[name="total_cost[' + i + ']"]').val(total_cost)
		}
	}
	copyElectricalCost(electrical_cost, iteration, count) {
		for (var i = iteration; i <= count; i++) {
			let rate = +(($('#assetquantitycostFrm [name="rate[' + i + ']"]').val()));
			let landed_price = +($('#assetquantitycostFrm [name="landed_price[' + i + ']"]').val());
			let civil_cost = +($('#assetquantitycostFrm [name="civil_cost[' + i + ']"]').val());
			let machanical_cost = +($('#assetquantitycostFrm [name="machanical_cost[' + i + ']"]').val());
			let total_cost = 1 * (rate + landed_price + machanical_cost + civil_cost + electrical_cost);
			$('input[name="electrical_cost[' + i + ']"]').val(electrical_cost)
			$('input[name="total_cost[' + i + ']"]').val(total_cost)
		}
	}
	copyWarenteeClose(warrantee_close, iteration, count) {
		for (var i = iteration; i <= count; i++) {
			$('input[name="warrantee_close[' + i + ']"]').val(warrantee_close)
		}
	}
	getWClose(iteration, count, field) {
		let warrantee_close = $('#assetquantitycostFrm [name="warrantee_close[' + iteration + ']"]').val()
		$('#assetquantitycostFrm [name="warrantee_close[' + iteration + ']"]').val(warrantee_close);
		if (field === 'warrantee_close') {
			this.copyWarenteeClose(warrantee_close, iteration, count);
		}
	}

	copySalvageValue(salvage_value, iteration, count) {
		for (var i = iteration; i <= count; i++) {
			$('input[name="salvage_value[' + i + ']"]').val(salvage_value)
		}
	}
	


}
window.MsAssetQuantityCost = new MsAssetQuantityCostController(new MsAssetQuantityCostModel());
//MsAssetQuantityCost.showGrid();