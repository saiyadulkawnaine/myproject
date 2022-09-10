let MsAssetReturnDetailModel = require('./MsAssetReturnDetailModel');
require('../datagrid-filter.js');
class MsAssetReturnDetailController {
	constructor(MsAssetReturnDetailModel) {
		this.MsAssetReturnDetailModel = MsAssetReturnDetailModel;
		this.formId = 'assetreturndetailFrm';
		this.dataTable = '#assetreturndetailTbl';
		this.route = msApp.baseUrl() + "/assetreturndetail"
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
			this.MsAssetReturnDetailModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsAssetReturnDetailModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}


	resetForm() {
		msApp.resetForm(this.formId);
		$('#assetreturndetailFrm [name=asset_return_id]').val($('#assetreturnFrm [name=id]').val());
	}

	remove() {
		let formObj = msApp.get(this.formId);
		this.MsAssetReturnDetailModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id) {
		event.stopPropagation()
		this.MsAssetReturnDetailModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d) {
		MsAssetReturnDetail.resetForm();
		$('#assetreturndetailFrm [name=id]').val(d.id);
		$('#assetreturndetailFrm [name=asset_return_id]').val($('#assetreturnFrm [name=id]').val());
		MsAssetReturnDetail.get($('#assetreturnFrm [name=id]').val());
		$('#assetreturndetailcostFrm [name=asset_return_detail_id]').val($('#assetreturndetailFrm [name=id]').val());
	}

	edit(index, row) {
		row.route = this.route;
		row.formId = this.formId;
		this.MsAssetReturnDetailModel.get(index, row);
		$('#assetreturndetailcostFrm [name=asset_return_detail_id]').val(row.id);
		MsAssetReturnDetailCost.get(row.id);
	}

	showGrid(data) {
		let self = this;
		$(this.dataTable).datagrid({
			border: false,
			singleSelect: true,
			fit: true,
			onClickRow: function (index, row) {
				self.edit(index, row);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	formatDetail(value, row) {
		return '<a href="javascript:void(0)"  onClick="MsAssetReturnDetail.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	get(asset_return_id) {
		let data = axios.get(this.route + "?asset_return_id=" + asset_return_id);
		data.then(function (response) {
			$('#assetreturndetailTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		})
	}

	openAssetPart() {
		$('#openservicewindow').window('open');
	}

	getParams() {
		let params = {};
		params.menu_id = $('#assetreturnFrm [name=menu_id]').val();
		return params;
	}

	searchAsset() {
		let params = this.getParams();
		let rpt = axios.get(this.route + "/getassetpart", {
			params
		}).then(function (response) {
			$('#assetservicesearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
		return rpt;
	}

	showAssetGrid(data) {
		let self = this;
		var pr = $('#assetservicesearchTbl').datagrid({
			border: false,
			singleSelect: true,
			fit: true,
			onClickRow: function (index, row) {
				$('#assetreturndetailFrm [name=asset_part_id]').val(row.id);
				$('#assetreturndetailFrm [name=asset_part]').val(row.item_description);
				$('#assetreturndetailFrm [name=out_date]').val(row.out_date);
				$('#assetreturndetailFrm [name=returnable_date]').val(row.returnable_date);
				$('#assetreturndetailFrm [name=asset_name]').val(row.asset_name);
				$('#openservicewindow').window('close');
				$('#assetvendorsearchTbl').datagrid('loadData', []);
			}
		});
		pr.datagrid('enableFilter').datagrid('loadData', data);
	}

	getPdf() {
		let id = $('#assetreturndetailFrm [name=id]').val();
		if (id == '') {
			alert("Select An Asset Repair");
			return;
		}
		window.open(this.route + '/getassetreturndetailpdf?id=' + id);
	}

}
window.MsAssetReturnDetail = new MsAssetReturnDetailController(new MsAssetReturnDetailModel());
MsAssetReturnDetail.showGrid([]);
MsAssetReturnDetail.showAssetGrid([]);