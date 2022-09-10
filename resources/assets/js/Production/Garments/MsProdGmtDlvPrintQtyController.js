let MsProdGmtDlvPrintQtyModel = require('./MsProdGmtDlvPrintQtyModel');

class MsProdGmtDlvPrintQtyController
{
	constructor(MsProdGmtDlvPrintQtyModel)
	{
		this.MsProdGmtDlvPrintQtyModel = MsProdGmtDlvPrintQtyModel;
		this.formId = 'prodgmtdlvprintqtyFrm';
		this.dataTable = '#prodgmtdlvprintqtyTbl';
		this.route = msApp.baseUrl() + "/prodgmtdlvprintqty"
	}

	submit()
	{
		let prod_gmt_dlv_print_order_id = $('#prodgmtdlvprintorderFrm [name=id]').val()
		let formObj = msApp.get(this.formId);
		formObj.prod_gmt_dlv_print_order_id = prod_gmt_dlv_print_order_id;
		if (formObj.id) {
			this.MsProdGmtDlvPrintQtyModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsProdGmtDlvPrintQtyModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}


	resetForm()
	{
		msApp.resetForm(this.formId);
		MsProdGmtDlvPrintOrder.resetForm();
		$('#dlvprintgmtcosi').html('');
	}

	remove()
	{
		let formObj = msApp.get(this.formId);
		this.MsProdGmtDlvPrintQtyModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id)
	{
		event.stopPropagation()
		this.MsProdGmtDlvPrintQtyModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d)
	{
		//$('#prodgmtdlvprintqtyTbl').datagrid('reload');
		//msApp.resetForm('prodgmtdlvprintqtyFrm');
		MsProdGmtDlvPrintQty.resetForm()
		$('#dlvprintgmtcosi').html('');
		//$('#prodgmtdlvprintqtyFrm [name=prod_gmt_dlvprint_order_id]').val($('#prodgmtdlvprintorderFrm [name=id]').val());
	}

	edit(index, row)
	{
		row.route = this.route;
		row.formId = this.formId;
		this.MsProdGmtDlvPrintQtyModel.get(index, row);

	}

	showGrid(prod_gmt_dlv_print_order_id)
	{
		let self = this;
		let data = {};
		data.prod_gmt_dlv_print_order_id = prod_gmt_dlv_print_order_id;
		$(this.dataTable).datagrid({
			method: 'get',
			border: false,
			singleSelect: true,
			fit: true,
			queryParams: data,
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
		return '<a href="javascript:void(0)"  onClick="MsProdGmtDlvPrintQty.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsProdGmtDlvPrintQty = new MsProdGmtDlvPrintQtyController(new MsProdGmtDlvPrintQtyModel());