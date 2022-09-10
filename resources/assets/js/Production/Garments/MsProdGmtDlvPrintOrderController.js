let MsProdGmtDlvPrintOrderModel = require('./MsProdGmtDlvPrintOrderModel');

class MsProdGmtDlvPrintOrderController
{
	constructor(MsProdGmtDlvPrintOrderModel)
	{
		this.MsProdGmtDlvPrintOrderModel = MsProdGmtDlvPrintOrderModel;
		this.formId = 'prodgmtdlvprintorderFrm';
		this.dataTable = '#prodgmtdlvprintorderTbl';
		this.route = msApp.baseUrl() + "/prodgmtdlvprintorder"
	}

	submit()
	{
		let formObj = msApp.get(this.formId);
		if (formObj.id) {
			this.MsProdGmtDlvPrintOrderModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsProdGmtDlvPrintOrderModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}


	resetForm()
	{
		msApp.resetForm(this.formId);
		$('#dlvprintgmtcosi').html('');
		let prod_gmt_dlv_print_id = $('#prodgmtdlvprintFrm  [name=id]').val();
		$('#prodgmtdlvprintorderFrm  [name=prod_gmt_dlv_print_id]').val(prod_gmt_dlv_print_id);
	}

	remove()
	{
		let formObj = msApp.get(this.formId);
		this.MsProdGmtDlvPrintOrderModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id)
	{
		event.stopPropagation()
		this.MsProdGmtDlvPrintOrderModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d)
	{
		$('#prodgmtdlvprintorderTbl').datagrid('reload');
		msApp.resetForm('prodgmtdlvprintorderFrm');
		MsProdGmtDlvPrintQty.resetForm();
		$('#prodgmtdlvprintorderFrm [name=prod_gmt_dlv_print_po_id]').val($('#prodgmtdlvprintFrm [name=id]').val());
	}

	edit(index, row)
	{
		row.route = this.route;
		row.formId = this.formId;
		this.MsProdGmtDlvPrintOrderModel.get(index, row);

	}

	showGrid(prod_gmt_dlv_print_id)
	{
		let self = this;
		let data = {};
		data.prod_gmt_dlv_print_id = prod_gmt_dlv_print_id;
		$(this.dataTable).datagrid({
			method: 'get',
			border: false,
			singleSelect: true,
			fit: true,
			queryParams: data,
			fitColumns: true,
			showFooter: true,
			url: this.route,
			onClickRow: function (index, row)
			{
				self.edit(index, row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsProdGmtDlvPrintOrder.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openOrderDlvPrintWindow()
	{
		$('#openorderdlvprintwindow').window('open');
	}

	getParams()
	{
		let params = {};
		params.style_ref = $('#orderdlvprintsearchFrm [name=style_ref]').val();
		params.job_no = $('#orderdlvprintsearchFrm [name=job_no]').val();
		params.sale_order_no = $('#orderdlvprintsearchFrm [name=sale_order_no]').val();
		params.prodgmtdlvprintid = $('#prodgmtdlvprintFrm [name=id]').val();
		params.po_emb_service_id = $('#prodgmtdlvprintpoFrm [name=po_emb_service_id]').val();
		return params;
	}
	searchDlvPrintOrderGrid()
	{
		let params = this.getParams();
		let d = axios.get(this.route + '/getdlvprintorder', { params })
			.then(function (response)
			{
				$('#orderdlvprintsearchTbl').datagrid('loadData', response.data);
			}).catch(function (error)
			{
				console.log(error);
			});
	}
	showDlvPrintOrderGrid(data)
	{
		let self = this;
		$('#orderdlvprintsearchTbl').datagrid({
			border: false,
			singleSelect: true,
			fit: true,
			onClickRow: function (index, row)
			{
				$('#prodgmtdlvprintorderFrm [name=sales_order_country_id]').val(row.sales_order_country_id);
				$('#prodgmtdlvprintorderFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#prodgmtdlvprintorderFrm [name=country_id]').val(row.country_id);
				$('#prodgmtdlvprintorderFrm [name=company_id]').val(row.company_id);
				$('#prodgmtdlvprintorderFrm [name=buyer_name]').val(row.buyer_name);
				$('#prodgmtdlvprintorderFrm [name=job_no]').val(row.job_no);
				$('#prodgmtdlvprintorderFrm [name=style_ref]').val(row.style_ref);
				$('#prodgmtdlvprintorderFrm [name=ship_date]').val(row.ship_date);
				// $('#prodgmtdlvprintorderFrm [name=produced_company_id]').val(row.produced_company_id);
				// $('#prodgmtdlvprintorderFrm [name=produced_company_name]').val(row.produced_company_name);
				$('#openorderdlvprintwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

}
window.MsProdGmtDlvPrintOrder = new MsProdGmtDlvPrintOrderController(new MsProdGmtDlvPrintOrderModel());
MsProdGmtDlvPrintOrder.showDlvPrintOrderGrid([]);