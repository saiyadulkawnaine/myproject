let MsSoEmbPrintMcModel = require('./MsSoEmbPrintMcModel');
require('../../datagrid-filter.js');
class MsSoEmbPrintMcController
{
	constructor(MsSoEmbPrintMcModel)
	{
		this.MsSoEmbPrintMcModel = MsSoEmbPrintMcModel;
		this.formId = 'soembprintmcFrm';
		this.dataTable = '#soembprintmcTbl';
		this.route = msApp.baseUrl() + "/soembprintmc"
	}

	submit()
	{
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
			this.MsSoEmbPrintMcModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsSoEmbPrintMcModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}

	resetForm()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj = msApp.get(this.formId);
		this.MsSoEmbPrintMcModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id)
	{
		event.stopPropagation()
		this.MsSoEmbPrintMcModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d)
	{
		$('#soembprintmcTbl').datagrid('reload');
		msApp.resetForm('soembprintmcFrm');
	}

	edit(index, row)
	{
		row.route = this.route;
		row.formId = this.formId;
		this.MsSoEmbPrintMcModel.get(index, row);
	}

	showGrid()
	{
		let self = this;
		$(this.dataTable).datagrid({
			method: 'get',
			border: false,
			singleSelect: true,
			fit: true,
			fitColumns: true,
			url: this.route,
			onClickRow: function (index, row)
			{
				self.edit(index, row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value, row)
	{
		return '<a href="javascript:void(0)" onClick="MsSoEmbPrintMc.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openstudyprintmcsetupWindow()
	{
		$("#opensoembprintmcwindow").window('open');
	}

	searchPrintMcsetup()
	{
		let params = {};
		params.asset_no = $('#opensoembprintmcFrm [name=asset_no]').val();
		params.custom_no = $("#opensoembprintmcFrm [name=custom_no]").val();
		params.asset_name = $("#opensoembprintmcFrm [name=asset_name]").val();
		let data = axios.get(this.route + "/getprintmcsetup", { params });
		data.then(function (response)
		{
			$("#opensoembprintmcTbl").datagrid('loadData', response.data);
		}).catch(function (error)
		{
			console.log(error);
		})
	}

	showPrintMcsetupGrid(data)
	{
		let self = this;
		var pr = $("#opensoembprintmcTbl").datagrid({
			border: false,
			singleSelect: true,
			fit: true,
			onClickRow: function (index, row)
			{
				$("#soembprintmcFrm [name=asset_quantity_cost_id]").val(row.id);
				$("#soembprintmcFrm [name=asset_no]").val(row.asset_no);
				$("#opensoembprintmcwindow").window('close');
				$("#opensoembprintmcTbl").datagrid("loadData", []);
			}
		});
		pr.datagrid('enableFilter').datagrid('loadData', data);
	}

}
window.MsSoEmbPrintMc = new MsSoEmbPrintMcController(new MsSoEmbPrintMcModel());
MsSoEmbPrintMc.showGrid();
MsSoEmbPrintMc.showPrintMcsetupGrid([])

$('#soembprintmctabs').tabs({
	onSelect: function (title, index)
	{
		let so_emb_print_mc_id = $('#soembprintmcFrm [name=id]').val();
		let so_emb_print_mc_dtl_id = $('#soembprintmcdtlFrm [name=id]').val();

		var data = {};
		data.so_emb_print_mc_id = so_emb_print_mc_id;
		if (index == 1) {
			if (so_emb_print_mc_id === '') {
				$('#soembprintmctabs').tabs('select', 0);
				msApp.showError('Select a Machine First', 0);
				return;
			}
			MsSoEmbPrintMcDtl.resetForm();
			$('#soembprintmcdtlFrm [name=so_emb_print_mc_id]').val(so_emb_print_mc_id);
			$('#soembprintmcdtlFrm [name=printing_start_at]').val('08:00:00 AM');
			$('#soembprintmcdtlFrm [name=printing_end_at]').val('05:00:00 PM');
			$('#soembprintmcdtlFrm [name=lunch_start_at]').val('01:00:00 PM');
			$('#soembprintmcdtlFrm [name=lunch_end_at]').val('02:00:00 PM');
			MsSoEmbPrintMcDtl.showGrid(so_emb_print_mc_id);
		}
		if (index == 2) {
			if (so_emb_print_mc_dtl_id === '') {
				$('#soembprintmctabs').tabs('select', 1);
				msApp.showError('Select a Reference Details First', 1);
				return;
			}
			MsSoEmbPrintMcDtlOrd.resetForm();
			$('#soembprintmcdtlordFrm [name=so_emb_print_mc_dtl_id]').val(so_emb_print_mc_dtl_id);
			MsSoEmbPrintMcDtlOrd.get(so_emb_print_mc_dtl_id);
		}
		if (index == 3) {
			if (so_emb_print_mc_dtl_id === '') {
				$('#soembprintmctabs').tabs('select', 2);
				msApp.showError('Select an Engaged Resource Details First', 2);
				return;
			}
			MsSoEmbPrintMcDtlMinaj.resetForm();
			$('#soembprintmcdtlminajFrm [name=so_emb_print_mc_dtl_id]').val(so_emb_print_mc_dtl_id);
			MsSoEmbPrintMcDtlMinaj.get(so_emb_print_mc_dtl_id);
		}
	}
});