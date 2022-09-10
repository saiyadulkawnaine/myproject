let MsSoEmbPrintDlvModel = require('./MsSoEmbPrintDlvModel');
require('../../datagrid-filter.js');
class MsSoEmbPrintDlvController
{
	constructor(MsSoEmbPrintDlvModel)
	{
		this.MsSoEmbPrintDlvModel = MsSoEmbPrintDlvModel;
		this.formId = 'soembprintdlvFrm';
		this.dataTable = '#soembprintdlvTbl';
		this.route = msApp.baseUrl() + "/soembprintdlv"
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
			this.MsSoEmbPrintDlvModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsSoEmbPrintDlvModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}


	resetForm()
	{
		msApp.resetForm(this.formId);
		$('#soembprintdlvFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj = msApp.get(this.formId);
		this.MsSoEmbPrintDlvModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id)
	{
		event.stopPropagation()
		this.MsSoEmbPrintDlvModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d)
	{
		$('#soembprintdlvTbl').datagrid('reload');
		msApp.resetForm('soembprintdlvFrm');
		$('#soembprintdlvFrm [id="buyer_id"]').combobox('setValue', '');
	}

	edit(index, row)
	{
		row.route = this.route;
		row.formId = this.formId;
		let carton = this.MsSoEmbPrintDlvModel.get(index, row);
		carton.then(function (response)
		{
			$('#soembprintdlvFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
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
		return '<a href="javascript:void(0)"  onClick="MsSoEmbPrintDlv.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Del</span></a>';
	}

	pdf()
	{
		var id = $('#soembprintdlvFrm  [name=id]').val();
		if (id == "") {
			alert("Select a Delivery References");
			return;
		}
		window.open(this.route + "/report?id=" + id);
	}

	challan()
	{
		var id = $('#soembprintdlvFrm  [name=id]').val();
		if (id == "") {
			alert("Select a Delivery References");
			return;
		}
		window.open(this.route + "/getchallan?id=" + id);
	}
}
window.MsSoEmbPrintDlv = new MsSoEmbPrintDlvController(new MsSoEmbPrintDlvModel());
MsSoEmbPrintDlv.showGrid();
$('#soembprintdlvtabs').tabs({
	onSelect: function (title, index)
	{
		let so_emb_print_dlv_id = $('#soembprintdlvFrm [name=id]').val();
		var data = {};
		data.so_emb_print_dlv_id = so_emb_print_dlv_id;
		if (index == 1) {
			if (so_emb_print_dlv_id === '') {
				$('#soembprintdlvtabs').tabs('select', 0);
				msApp.showError('Select a Delivery References First', 0);
				return;
			}
			MsSoEmbPrintDlvItem.resetForm();
			$('#soembprintdlvitemFrm [name=so_emb_print_dlv_id]').val();
			MsSoEmbPrintDlvItem.showGrid(so_emb_print_dlv_id);
		}
	}
});

