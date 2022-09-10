let MsAssetServiceRepairPartModel = require('./MsAssetServiceRepairPartModel');
class MsAssetServiceRepairPartController {
	constructor(MsAssetServiceRepairPartModel) {
		this.MsAssetServiceRepairPartModel = MsAssetServiceRepairPartModel;
		this.formId = 'assetservicerepairpartFrm';
		this.dataTable = '#assetservicerepairpartTbl';
		this.route = msApp.baseUrl() + "/assetservicerepairpart"
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
			this.MsAssetServiceRepairPartModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsAssetServiceRepairPartModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}


	resetForm() {
		msApp.resetForm(this.formId);
		$('#assetservicerepairpartFrm [name=asset_service_repair_id]').val($('#assetservicerepairFrm [name=id]').val());

	}

	remove() {
		let formObj = msApp.get(this.formId);
		this.MsAssetServiceRepairPartModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id) {
		event.stopPropagation()
		this.MsAssetServiceRepairPartModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d) {
		$('#assetservicerepairpartTbl').datagrid('reload');
		msApp.resetForm('assetservicerepairpartFrm');
		$('#assetservicerepairpartFrm [name=asset_service_repair_id]').val($('#assetservicerepairFrm [name=id]').val());
	}

	edit(index, row) {
		row.route = this.route;
		row.formId = this.formId;
		this.MsAssetServiceRepairPartModel.get(index, row);
	}

	showGrid(asset_service_repair_id) {
		let self = this;
		var data = {};
		data.asset_service_repair_id = asset_service_repair_id;
		$(this.dataTable).datagrid({
			method: 'get',
			border: false,
			singleSelect: true,
			fit: true,
			fitColumns: true,
			url: this.route,
			queryParams: data,
			onClickRow: function (index, row) {
				self.edit(index, row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value, row) {
		return '<a href="javascript:void(0)"  onClick="MsAssetServiceRepairPart.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openAssetServicePart() {
		$("#openassetservicerepairpartwindow").window('open');
	}
	getParams() {
		let params = {};
		params.itemcategory_id = $('#assetservicerepairpartsearchFrm [name=itemcategory_id]').val();
		params.itemclass_id = $('#assetservicerepairpartsearchFrm [name=itemclass_id]').val();
		return params;
	}
	searchAssetServicePart() {
		let params = this.getParams();
		let data = axios.get(this.route + "/getassetservicerepairpart", {
				params
			})
			.then(function (response) {
				$('#assetservicerepairpartsearchTbl').datagrid('loadData', response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
		return data;
	}

	showAssetServicePartGrid(data) {
		let self = this;
		var pr = $('#assetservicerepairpartsearchTbl').datagrid({
			border: false,
			singleSelect: true,
			fit: true,
			onClickRow: function (index, row) {
				$('#assetservicerepairpartFrm  [name=item_account_id]').val(row.id);
				$('#assetservicerepairpartFrm  [name=itemcategories_name]').val(row.item_description + ',' + row.specification);

				$('#openassetservicerepairpartwindow').window('close');
				$('#assetservicerepairpartsearchTbl').datagrid('loadData', []);
			}
		});
		pr.datagrid('enableFilter').datagrid('loadData', data);
	}

}
window.MsAssetServiceRepairPart = new MsAssetServiceRepairPartController(new MsAssetServiceRepairPartModel());
MsAssetServiceRepairPart.showAssetServicePartGrid([]);