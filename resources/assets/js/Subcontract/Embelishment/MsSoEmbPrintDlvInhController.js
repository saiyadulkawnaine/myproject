let MsSoEmbPrintDlvInhModel = require('./MsSoEmbPrintDlvInhModel');
require('../../datagrid-filter.js');
class MsSoEmbPrintDlvInhController
{
	constructor(MsSoEmbPrintDlvInhModel)
	{
		this.MsSoEmbPrintDlvInhModel = MsSoEmbPrintDlvInhModel;
		this.formId = 'soembprintdlvinhFrm';
		this.dataTable = '#soembprintdlvinhTbl';
		this.route = msApp.baseUrl() + "/soembprintdlvinh"
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
			this.MsSoEmbPrintDlvInhModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsSoEmbPrintDlvInhModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}


	resetForm()
	{
		msApp.resetForm(this.formId);
		$('#soembprintdlvinhFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj = msApp.get(this.formId);
		this.MsSoEmbPrintDlvInhModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id)
	{
		event.stopPropagation()
		this.MsSoEmbPrintDlvInhModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d)
	{
		$('#soembprintdlvinhTbl').datagrid('reload');
		msApp.resetForm('soembprintdlvinhFrm');
		$('#soembprintdlvinhFrm [id="buyer_id"]').combobox('setValue', '');
	}

	edit(index, row)
	{
		row.route = this.route;
		row.formId = this.formId;
		let carton = this.MsSoEmbPrintDlvInhModel.get(index, row);
		carton.then(function (response)
		{
			$('#soembprintdlvinhFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
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
		return '<a href="javascript:void(0)"  onClick="MsSoEmbPrintDlvInh.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Del</span></a>';
	}

	pdf()
	{
		var id = $('#soembprintdlvinhFrm  [name=id]').val();
		if (id == "") {
			alert("Select a Delivery References");
			return;
		}
		window.open(this.route + "/report?id=" + id);
	}

	challan()
	{
		var id = $('#soembprintdlvinhFrm  [name=id]').val();
		if (id == "") {
			alert("Select a Delivery References");
			return;
		}
		window.open(this.route + "/getchallan?id=" + id);
	}
}
window.MsSoEmbPrintDlvInh = new MsSoEmbPrintDlvInhController(new MsSoEmbPrintDlvInhModel());
MsSoEmbPrintDlvInh.showGrid();
$('#soembprintdlvinhtabs').tabs({
	onSelect: function (title, index)
	{
		let so_emb_print_dlv_id = $('#soembprintdlvinhFrm [name=id]').val();
		var data = {};
		data.so_emb_print_dlv_id = so_emb_print_dlv_id;
		if (index == 1) {
			if (so_emb_print_dlv_id === '') {
				$('#soembprintdlvinhtabs').tabs('select', 0);
				msApp.showError('Select a Delivery References First', 0);
				return;
			}
			MsSoEmbPrintDlvInhItem.resetForm();
			$('#soembprintdlvinhitemFrm [name=so_emb_print_dlv_id]').val();
			MsSoEmbPrintDlvInhItem.showGrid(so_emb_print_dlv_id);
		}
	}
});

