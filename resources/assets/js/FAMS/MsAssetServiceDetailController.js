let MsAssetServiceDetailModel = require('./MsAssetServiceDetailModel');
class MsAssetServiceDetailController {
	constructor(MsAssetServiceDetailModel) {
		this.MsAssetServiceDetailModel = MsAssetServiceDetailModel;
		this.formId = 'assetservicedetailFrm';
		this.dataTable = '#assetservicedetailTbl';
		this.route = msApp.baseUrl() + "/assetservicedetail"
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
			this.MsAssetServiceDetailModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsAssetServiceDetailModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}


	resetForm() {
		msApp.resetForm(this.formId);
		$('#assetservicedetailFrm [name=asset_service_id]').val($('#assetserviceFrm [name=id]').val());

	}

	remove() {
		let formObj = msApp.get(this.formId);
		this.MsAssetServiceDetailModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id) {
		event.stopPropagation()
		this.MsAssetServiceDetailModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d) {
		$('#assetservicedetailTbl').datagrid('reload');
		msApp.resetForm('assetservicedetailFrm');
		$('#assetservicedetailFrm [name=asset_service_id]').val($('#assetserviceFrm [name=id]').val());
	}

	edit(index, row) {
		row.route = this.route;
		row.formId = this.formId;
		this.MsAssetServiceDetailModel.get(index, row);
	}

	showGrid(asset_service_id) {
		let self = this;
		var data = {};
		data.asset_service_id = asset_service_id;
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
		return '<a href="javascript:void(0)"  onClick="MsAssetServiceDetail.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openAssetWindow() {
		$("#openassetwindow").window('open');
	}
	getParams() {
		let params = {};
		params.asset_no = $('#assetsearchFrm [name=asset_no]').val();
		params.custom_no = $('#assetsearchFrm [name=custom_no]').val();
		return params;
	}
	searchAsset() {
		let params = this.getParams();
		let rpt = axios.get(this.route + "/getasset", {
				params
			})
			.then(function (response) {
				$('#assetsearchTbl').datagrid('loadData', response.data);
			})
			.catch(function (error) {
				console.log(error);
			});
		return rpt;
	}

	showAssetGrid(data) {
		let self = this;
		var pr = $('#assetsearchTbl').datagrid({
			border: false,
			singleSelect: true,
			fit: true,
			onClickRow: function (index, row) {
				$('#assetservicedetailFrm  [name=asset_quantity_cost_id]').val(row.id);
				$('#assetservicedetailFrm  [name=asset_no]').val(row.asset_no);
				$('#assetservicedetailFrm  [name=asset_name]').val(row.asset_name);
				$('#assetservicedetailFrm  [name=production_area_id]').val(row.production_area_id);
				$('#assetservicedetailFrm  [name=asset_group]').val(row.asset_group);
				$('#assetservicedetailFrm  [name=brand]').val(row.brand);
				$('#assetservicedetailFrm  [name=purchase_date]').val(row.purchase_date);
				$('#assetservicedetailFrm  [name=type_id]').val(row.type_id);
				$("#assetservicedetailFrm [name=action_taken]").val(row.action_taken);

				$('#openassetwindow').window('close');
				$('#assetsearchTbl').datagrid('loadData', []);
			}
		});
		pr.datagrid('enableFilter').datagrid('loadData', data);
	}

}
window.MsAssetServiceDetail = new MsAssetServiceDetailController(new MsAssetServiceDetailModel());
MsAssetServiceDetail.showAssetGrid([]);