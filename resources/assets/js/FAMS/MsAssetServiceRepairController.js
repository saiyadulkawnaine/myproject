let MsAssetServiceRepairModel = require('./MsAssetServiceRepairModel');
require('../datagrid-filter.js');
class MsAssetServiceRepairController {
	constructor(MsAssetServiceRepairModel) {
		this.MsAssetServiceRepairModel = MsAssetServiceRepairModel;
		this.formId = 'assetservicerepairFrm';
		this.dataTable = '#assetservicerepairTbl';
		this.route = msApp.baseUrl() + "/assetservicerepair"
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
			this.MsAssetServiceRepairModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsAssetServiceRepairModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}


	resetForm() {
		msApp.resetForm(this.formId);
		$('#assetservicerepairFrm [id="supplier_id"]').combobox('setValue', '');

	}

	remove() {
		let formObj = msApp.get(this.formId);
		this.MsAssetServiceRepairModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id) {
		event.stopPropagation()
		this.MsAssetServiceRepairModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d) {
		$('#assetservicerepairTbl').datagrid('reload');
		msApp.resetForm('assetservicerepairFrm');
		$('#assetservicerepairFrm [id="supplier_id"]').combobox('setValue', '');
	}

	edit(index, row) {
		row.route = this.route;
		row.formId = this.formId;

		let asset = this.MsAssetServiceRepairModel.get(index, row);
		asset.then(function (response) {
				$('#assetservicerepairFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
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
		return '<a href="javascript:void(0)"  onClick="MsAssetServiceRepair.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openAssetBreakDownWindow() {
		$("#openassetbreakdownwindow").window('open');
	}
	getParams() {
		let params = {};
		params.asset_no = $('#assetbreakdownsearchFrm [name=asset_no]').val();
		params.custom_no = $('#assetbreakdownsearchFrm [name=custom_no]').val();
		return params;
	}
	searchAssetBreakdown() {
		let params = this.getParams();
		let rpt = axios.get(this.route + "/getassetbreakdown", {
				params
			})
			.then(function (response) {
				$('#assetbreakdownsearchTbl').datagrid('loadData', response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
		return rpt;
	}

	showAssetBreakdownGrid(data) {
		let self = this;
		var pr = $('#assetbreakdownsearchTbl').datagrid({
			border: false,
			singleSelect: true,
			fit: true,
			onClickRow: function (index, row) {
				$('#assetservicerepairFrm  [name=asset_breakdown_id]').val(row.id);
				$('#assetservicerepairFrm  [name=custom_no]').val(row.custom_no);
				$('#assetservicerepairFrm  [name=asset_no]').val(row.asset_no);
				$('#assetservicerepairFrm  [name=asset_name]').val(row.asset_name);
				$('#assetservicerepairFrm  [name=production_area_id]').val(row.production_area_id);
				$('#assetservicerepairFrm  [name=asset_group]').val(row.asset_group);
				$('#assetservicerepairFrm  [name=brand]').val(row.brand);
				$('#assetservicerepairFrm  [name=purchase_date]').val(row.purchase_date);
				$('#assetservicerepairFrm  [name=type_id]').val(row.type_id);
				$("#assetservicerepairFrm [name=reason_id]").val(row.reason_id);
				$("#assetservicerepairFrm [name=action_taken]").val(row.action_taken);

				$('#openassetbreakdownwindow').window('close');
				$('#assetbreakdownsearchTbl').datagrid('loadData', []);
			}
		});
		pr.datagrid('enableFilter').datagrid('loadData', data);
	}

	getPdf() {
		let id = $('#assetservicerepairFrm [name=id]').val();
		if (id == '') {
			alert("Select An Asset Repair");
			return;
		}
		window.open(this.route + '/getassetrepairpdf?id=' + id);
	}

}
window.MsAssetServiceRepair = new MsAssetServiceRepairController(new MsAssetServiceRepairModel());
MsAssetServiceRepair.showGrid();
MsAssetServiceRepair.showAssetBreakdownGrid([]);
$('#assetservicerepairTabs').tabs({
	onSelect: function (title, index) {
		let asset_service_repair_id = $("#assetservicerepairFrm [name=id]").val();
		var data = {};
		data.asset_service_repair_id = asset_service_repair_id;
		if (index == 1) {
			if (asset_service_repair_id === '') {
				$('#assetservicerepairTabs').tabs("select", 0);
				msApp.showError("Select a Service First");
				return;
			}
			$('#assetservicerepairpartFrm [name=asset_service_repair_id]').val(asset_service_repair_id);
			MsAssetServiceRepairPart.showGrid(asset_service_repair_id);
		}
	}
});