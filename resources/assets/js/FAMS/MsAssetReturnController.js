let MsAssetReturnModel = require('./MsAssetReturnModel');
require('../datagrid-filter.js');
class MsAssetReturnController {
	constructor(MsAssetReturnModel) {
		this.MsAssetReturnModel = MsAssetReturnModel;
		this.formId = 'assetreturnFrm';
		this.dataTable = '#assetreturnTbl';
		this.route = msApp.baseUrl() + "/assetreturn"
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
			this.MsAssetReturnModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsAssetReturnModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}


	resetForm() {
		msApp.resetForm(this.formId);
	}

	remove() {
		let formObj = msApp.get(this.formId);
		this.MsAssetReturnModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id) {
		event.stopPropagation()
		this.MsAssetReturnModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d) {
		$('#assetreturnTbl').datagrid('reload');
		msApp.resetForm('assetreturnFrm');
	}

	edit(index, row) {
		row.route = this.route;
		row.formId = this.formId;

		let asset = this.MsAssetReturnModel.get(index, row);
		asset.then(function (response) {
				$('#assetreturnFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
			})
			.catch(function (error) {
				console.log(error);
			});

	}

	showGrid() {
		let self = this;
		$(this.dataTable).datagrid({
			method: 'get',
			border: false,
			singleSelect: true,
			fit: true,
			fitColumns: true,
			url: this.route,
			onClickRow: function (index, row) {
				self.edit(index, row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value, row) {
		return '<a href="javascript:void(0)"  onClick="MsAssetReturn.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openVendor() {
		$('#openvendorwindow').window('open');
	}

	getParams() {
		let params = {};
		params.menu_id = $('#assetreturnFrm [name=menu_id]').val();
		return params;
	}

	searchVendor() {
		let params = this.getParams();
		let rpt = axios.get(this.route + "/getvendor", {
			params
		}).then(function (response) {
			$('#assetvendorsearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
		return rpt;
	}

	showVendorGrid(data) {
		let self = this;
		var pr = $('#assetvendorsearchTbl').datagrid({
			border: false,
			singleSelect: true,
			fit: true,
			onClickRow: function (index, row) {
				$('#assetreturnFrm [name=supplier_id]').val(row.id);
				$('#assetreturnFrm [name=supplier_name]').val(row.supplier_name);
				$('#openvendorwindow').window('close');
				$('#assetvendorsearchTbl').datagrid('loadData', []);
			}
		});
		pr.datagrid('enableFilter').datagrid('loadData', data);
	}

	getPdf() {
		let id = $('#assetreturnFrm [name=id]').val();
		if (id == '') {
			alert("Select An Asset Repair");
			return;
		}
		window.open(this.route + '/getassetreturnpdf?id=' + id);
	}

}

window.MsAssetReturn = new MsAssetReturnController(new MsAssetReturnModel());
MsAssetReturn.showGrid();
MsAssetReturn.showVendorGrid([]);
$('#assetreturnTabs').tabs({
	onSelect: function (title, index) {
		let asset_return_id = $('#assetreturnFrm [name=id]').val();
		var data = {};
		data.asset_return_id = asset_return_id;
		if (index == 1) {
			if (asset_return_id === '') {
				$('#assetreturnTabs').tabs("select", 0);
				msApp.showError('Select a return First');
				return;
			}
			MsAssetReturnDetail.resetForm();
			MsAssetReturnDetailCost.resetForm();
			MsAssetReturnDetailCost.showGrid([]);
			$('#assetreturndetailFrm [name=asset_return_id]').val(asset_return_id);
			MsAssetReturnDetail.get(asset_return_id);
		}
	}
});