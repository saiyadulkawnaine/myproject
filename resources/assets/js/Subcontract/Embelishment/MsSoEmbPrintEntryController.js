let MsSoEmbPrintEntryModel = require('./MsSoEmbPrintEntryModel');
require('../../datagrid-filter.js');
class MsSoEmbPrintEntryController
{
	constructor(MsSoEmbPrintEntryModel)
	{
		this.MsSoEmbPrintEntryModel = MsSoEmbPrintEntryModel;
		this.formId = 'soembprintentryFrm';
		this.dataTable = '#soembprintentryTbl';
		this.route = msApp.baseUrl() + "/soembprintentry"
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
			this.MsSoEmbPrintEntryModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsSoEmbPrintEntryModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}


	resetForm()
	{
		msApp.resetForm(this.formId);
		// $('soembprintentryFrm [id=buyer_id]').combobox('setValue', '');
	}

	remove()
	{
		let formObj = msApp.get(this.formId);
		this.MsSoEmbPrintEntryModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id)
	{
		event.stopPropagation()
		this.MsSoEmbPrintEntryModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d)
	{
		$('#soembprintentryTbl').datagrid('reload');
		msApp.resetForm('soembprintentryFrm');
		// $('#soembprintentryFrm [id=buyer_id]').combobox('setValue', '');
	}

	edit(index, row)
	{
		row.route = this.route;
		row.formId = this.formId;
		soembprintentry = this.MsSoEmbPrintEntryModel.get(index, row);
		// soembprintentry.then(function (response)
		// {
		// 	$('#soembprintentryFrm [id=buyer_id]').combobox('setValue', response.data.fromData.buyer_id);
		// })
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
		return '<a href="javascript:void(0)"  onClick="MsSoEmbPrintEntry.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}



}
window.MsSoEmbPrintEntry = new MsSoEmbPrintEntryController(new MsSoEmbPrintEntryModel());
MsSoEmbPrintEntry.showGrid();

$('#soembprintentrytabs').tabs({
	onSelect: function (title, index)
	{
		let so_emb_print_entry_id = $('#soembprintentryFrm [name=id]').val();
		var data = {};
		data.so_emb_print_entry_id = so_emb_print_entry_id;
		if (index == 1) {
			if (so_emb_print_entry_id === '') {
				$('#soembprintentrytabs').tabs('select', 0);
				msApp.showError('Select a Start Up First', 0);
				return;
			}
			MsSoEmbPrintEntOrder.resetForm();
			$('#soembprintentorderFrm [name=so_emb_print_entry_id]').val();
			MsSoEmbPrintEntOrder.showGrid(so_emb_print_entry_id);
		}
	}
});
