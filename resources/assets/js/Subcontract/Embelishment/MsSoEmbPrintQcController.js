let MsSoEmbPrintQcModel = require('./MsSoEmbPrintQcModel');
require('../../datagrid-filter.js');
class MsSoEmbPrintQcController
{
	constructor(MsSoEmbPrintQcModel)
	{
		this.MsSoEmbPrintQcModel = MsSoEmbPrintQcModel;
		this.formId = 'soembprintqcFrm';
		this.dataTable = '#soembprintqcTbl';
		this.route = msApp.baseUrl() + "/soembprintqc"
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
			this.MsSoEmbPrintQcModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsSoEmbPrintQcModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}


	resetForm()
	{
		msApp.resetForm(this.formId);
		$('soembprintqcFrm [id=buyer_id]').combobox('setValue', '');
	}

	remove()
	{
		let formObj = msApp.get(this.formId);
		this.MsSoEmbPrintQcModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id)
	{
		event.stopPropagation()
		this.MsSoEmbPrintQcModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d)
	{
		$('#soembprintqcTbl').datagrid('reload');
		msApp.resetForm('soembprintqcFrm');
		$('#soembprintqcFrm [id=buyer_id]').combobox('setValue', '');
	}

	edit(index, row)
	{
		row.route = this.route;
		row.formId = this.formId;
		soembprintqc = this.MsSoEmbPrintQcModel.get(index, row);
		soembprintqc.then(function (response)
		{
			$('#soembprintqcFrm [id=buyer_id]').combobox('setValue', response.data.fromData.buyer_id);
		})
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
		return '<a href="javascript:void(0)"  onClick="MsSoEmbPrintQc.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsSoEmbPrintQc = new MsSoEmbPrintQcController(new MsSoEmbPrintQcModel());
MsSoEmbPrintQc.showGrid();

$('#soembprintqctabs').tabs({
	onSelect: function (title, index)
	{
		let so_emb_print_qc_id = $('#soembprintqcFrm [name=id]').val();
		let so_emb_print_qc_dtl_id = $('#soembprintqcdtlFrm [name=id]').val();
		var data = {};
		data.so_emb_print_qc_id = so_emb_print_qc_id;
		data.so_emb_print_qc_dtl_id = so_emb_print_qc_dtl_id;
		if (index == 1) {
			if (so_emb_print_qc_id === '') {
				$('#soembprintqctabs').tabs('select', 0);
				msApp.showError('Select a References First', 0);
				return;
			}
			MsSoEmbPrintQcDtl.resetForm();
			$('#soembprintqcdtlFrm [name=so_emb_print_qc_id]').val();
			MsSoEmbPrintQcDtl.showGrid(so_emb_print_qc_id);
		}
		if (index == 2) {
			if (so_emb_print_qc_dtl_id === '') {
				$('#soembprintqctabs').tabs('select', 0);
				msApp.showError("Select an Order Details");
				return;
			}
			$('#soembprintqcdtldeftFrm  [name=so_emb_print_qc_dtl_id]').val(so_emb_print_qc_dtl_id);
			MsSoEmbPrintQcDtlDeft.create(so_emb_print_qc_dtl_id);
		}

	}
});
