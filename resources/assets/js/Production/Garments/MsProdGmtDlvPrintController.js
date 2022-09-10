require('./../../datagrid-filter.js');
let MsProdGmtDlvPrintModel = require('./MsProdGmtDlvPrintModel');
class MsProdGmtDlvPrintController
{
	constructor(MsProdGmtDlvPrintModel)
	{
		this.MsProdGmtDlvPrintModel = MsProdGmtDlvPrintModel;
		this.formId = 'prodgmtdlvprintFrm';
		this.dataTable = '#prodgmtdlvprintTbl';
		this.route = msApp.baseUrl() + "/prodgmtdlvprint"
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
		let formData = $("#" + this.formId).serialize();
		let formObj = msApp.get(this.formId);
		if (formObj.id) {
			this.MsProdGmtDlvPrintModel.save(this.route + "/" + formObj.id, 'PUT', formData, this.response);
		} else {
			this.MsProdGmtDlvPrintModel.save(this.route, 'POST', formData, this.response);
		}
	}


	resetForm()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj = msApp.get(this.formId);
		this.MsProdGmtDlvPrintModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id)
	{
		event.stopPropagation()
		this.MsProdGmtDlvPrintModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d)
	{
		$('#prodgmtdlvprintTbl').datagrid('reload');
		$('#prodgmtdlvprintFrm [name=id]').val(d.id);
		$('#prodgmtdlvprintFrm [name=challan_no]').val(d.challan_no);
		msApp.resetForm('prodgmtdlvprintFrm');
	}

	edit(index, row)
	{
		row.route = this.route;
		row.formId = this.formId;
		this.MsProdGmtDlvPrintModel.get(index, row);

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
		return '<a href="javascript:void(0)"  onClick="MsProdGmtDlvPrint.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openOrderDlvWindow()
	{
		$('#openorderdlvwindow').window('open');
	}

	searchOrderDlvGrid()
	{
		let data = {};
		data.company_id = $('#dlvinputsearchFrm  [name=company_id]').val();
		data.dlv_qc_date = $('#orderdlvsearchFrm  [name=dlv_qc_date]').val();
		let self = this;
		var ex = $('#orderdlvsearchTbl').datagrid({
			method: 'get',
			border: false,
			singleSelect: true,
			fit: true,
			queryParams: data,
			url: msApp.baseUrl() + "/prodgmtdlvprint/getorderdlv",
			onClickRow: function (index, row)
			{
				$('#prodgmtdlvprintFrm  [name=prod_gmt_dlv_input_id]').val(row.id);
				//$('#prodgmtrcvinputFrm  [name=company_id]').val(row.company_id);
				$('#prodgmtdlvprintFrm  [name=location_id]').val(row.location_id);
				$('#openorderdlvwindow').window('close')
			}
		});
		ex.datagrid('enableFilter');
	}

	pdf()
	{
		var id = $('#prodgmtdlvprintFrm [name=id]').val();
		if (id == '') {
			alert("Select A Challan First");
			return;
		}
		window.open(this.route + "/printpdf?id=" + id);
	}

}
window.MsProdGmtDlvPrint = new MsProdGmtDlvPrintController(new MsProdGmtDlvPrintModel());
MsProdGmtDlvPrint.showGrid();

$('#prodgmtdlvprinttabs').tabs({
	onSelect: function (title, index)
	{
		let prod_gmt_dlv_print_id = $('#prodgmtdlvprintFrm  [name=id]').val();
		var data = {};
		data.prod_gmt_dlv_print_id = prod_gmt_dlv_print_id;

		if (index == 1) {
			if (prod_gmt_dlv_print_id === '') {
				$('#prodgmtdlvprinttabs').tabs('select', 0);
				msApp.showError('Select a Start Up First', 0);
				return;
			}
			msApp.resetForm('prodgmtdlvprintorderFrm');
			$('#prodgmtdlvprintorderFrm  [name=prod_gmt_dlv_print_id]').val(prod_gmt_dlv_print_id);
			MsProdGmtDlvPrintOrder.showGrid(prod_gmt_dlv_print_id);
		}
	}
}); 
