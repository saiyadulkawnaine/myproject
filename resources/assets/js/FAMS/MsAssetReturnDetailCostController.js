let MsAssetReturnDetailCostModel = require('./MsAssetReturnDetailCostModel');
require('../datagrid-filter.js');
class MsAssetReturnDetailCostController {
	constructor(MsAssetReturnDetailCostModel) {
		this.MsAssetReturnDetailCostModel = MsAssetReturnDetailCostModel;
		this.formId = 'assetreturndetailcostFrm';
		this.dataTable = '#assetreturndetailcostTbl';
		this.route = msApp.baseUrl() + "/assetreturndetailcost"
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
		let formObj = msApp.get(this.formId);
		if (formObj.id) {
			this.MsAssetReturnDetailCostModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsAssetReturnDetailCostModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}


	resetForm() {
		msApp.resetForm(this.formId);
		$('#assetreturndetailcostFrm [name=asset_return_detail_id]').val($('#assetreturndetailFrm [name=id]').val());
	}

	remove() {
		let formObj = msApp.get(this.formId);
		this.MsAssetReturnDetailCostModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id) {
		event.stopPropagation()
		this.MsAssetReturnDetailCostModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d) {
		MsAssetReturnDetailCost.resetForm();
		$('#assetreturndetailcostFrm [name=asset_return_detail_id]').val($('#assetreturndetailFrm [name=id]').val());
		MsAssetReturnDetailCost.get($('#assetreturndetailFrm [name=id]').val());
	}

	edit(index, row) {
		row.route = this.route;
		row.formId = this.formId;
		let asset = this.MsAssetReturnDetailCostModel.get(index, row);
		asset.then(function (response) {})
			.catch(function (error) {
				console.log(error);
			});
	}

	showGrid(data) {
		let self = this;
		$(this.dataTable).datagrid({
			border: false,
			singleSelect: true,
			fit: true,
			showFooter: true,
			fitColumns: true,
			queryParams: data,
			onClickRow: function (index, row) {
				self.edit(index, row);
			},
			onLoadSuccess: function (data) {
				var tQty = 0;
				var tAmount = 0;
				var tDiscount = 0;
				var tNetCost = 0;
				for (var i = 0; i < data.rows.length; i++) {
					tQty += data.rows[i]['qty'].replace(/,/g, '') * 1;
					tAmount += data.rows[i]['amount'].replace(/,/g, '') * 1;
					tDiscount += data.rows[i]['discount'].replace(/,/g, '') * 1;
					tNetCost += data.rows[i]['net_cost'].replace(/,/g, '') * 1;
				}
				$(this).datagrid('reloadFooter', [{
					qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					discount: tDiscount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					net_cost: tNetCost.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
				}]);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	formatDetail(value, row) {
		return '<a href="javascript:void(0)"  onClick="MsAssetReturnDetailCost.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	calculateAmount() {
		let qty = $('#assetreturndetailcostFrm [name=qty]').val();
		let ret = $('#assetreturndetailcostFrm [name=rate]').val();
		let amount = (qty * ret);
		$('#assetreturndetailcostFrm [name=amount]').val(amount);
		return amount;
	}

	calculateNetCost() {
		let amount = this.calculateAmount();
		let discount = $('#assetreturndetailcostFrm [name=discount]').val();
		if (discount === '') {
			$('#assetreturndetailcostFrm [name=discount]').val(0);
		}
		let net_cost = amount - (amount * discount) / 100;
		$('#assetreturndetailcostFrm [name=net_cost]').val(net_cost);
	}

	get(asset_return_detail_id) {
		let data = axios.get(this.route + "?asset_return_detail_id=" + asset_return_detail_id);
		data.then(function (response) {
			$('#assetreturndetailcostTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		})
	}
}
window.MsAssetReturnDetailCost = new MsAssetReturnDetailCostController(new MsAssetReturnDetailCostModel());