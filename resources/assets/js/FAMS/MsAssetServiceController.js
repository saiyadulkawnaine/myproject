let MsAssetServiceModel = require('./MsAssetServiceModel');
require('../datagrid-filter.js');
class MsAssetServiceController {
	constructor(MsAssetServiceModel) {
		this.MsAssetServiceModel = MsAssetServiceModel;
		this.formId = 'assetserviceFrm';
		this.dataTable = '#assetserviceTbl';
		this.route = msApp.baseUrl() + "/assetservice"
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
			this.MsAssetServiceModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsAssetServiceModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}


	resetForm() {
		msApp.resetForm(this.formId);
		$('#assetserviceFrm [id="supplier_id"]').combobox('setValue', '');

	}

	remove() {
		let formObj = msApp.get(this.formId);
		this.MsAssetServiceModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id) {
		event.stopPropagation()
		this.MsAssetServiceModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d) {
		$('#assetserviceTbl').datagrid('reload');
		msApp.resetForm('assetserviceFrm');
		$('#assetserviceFrm [id="supplier_id"]').combobox('setValue', '');
	}

	edit(index, row) {
		row.route = this.route;
		row.formId = this.formId;

		let asset = this.MsAssetServiceModel.get(index, row);
		asset.then(function (response) {
				$('#assetserviceFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
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
		return '<a href="javascript:void(0)"  onClick="MsAssetService.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	getPdf() {
		let id = $('#assetserviceFrm [name=id]').val();
		if (id == '') {
			alert("Select An Asset Repair");
			return;
		}
		window.open(this.route + '/getassetservicepdf?id=' + id);
	}

}
window.MsAssetService = new MsAssetServiceController(new MsAssetServiceModel());
MsAssetService.showGrid();
$('#assetserviceTabs').tabs({
	onSelect: function (title, index) {
		let asset_service_id = $('#assetserviceFrm [name=id]').val();
		var data = {};
		data.asset_service_id = asset_service_id;
		if (index == 1) {
			if (asset_service_id === '') {
				$('#assetserviceTabs').tabs("select", 0);
				msApp.showError('Select a Service First');
				return;
			}
			MsAssetServiceDetail.resetForm();
			$('#assetservicedetailFrm [name=asset_service_id]').val(asset_service_id);
			MsAssetServiceDetail.showGrid(asset_service_id);
		}
	}
});