let MsProdGmtIronOrderModel = require('./MsProdGmtIronOrderModel');

class MsProdGmtIronOrderController
{
	constructor(MsProdGmtIronOrderModel)
	{
		this.MsProdGmtIronOrderModel = MsProdGmtIronOrderModel;
		this.formId = 'prodgmtironorderFrm';
		this.dataTable = '#prodgmtironorderTbl';
		this.route = msApp.baseUrl() + "/prodgmtironorder"
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
			this.MsProdGmtIronOrderModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsProdGmtIronOrderModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}


	resetForm()
	{
		msApp.resetForm(this.formId);
		$('#irongmtcosi').html('');
		let prod_gmt_iron_id = $('#prodgmtironFrm  [name=id]').val();
		$('#prodgmtironorderFrm  [name=prod_gmt_iron_id]').val(prod_gmt_iron_id);
		$('#prodgmtironorderFrm [id="supplier_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj = msApp.get(this.formId);
		this.MsProdGmtIronOrderModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id)
	{
		event.stopPropagation()
		this.MsProdGmtIronOrderModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d)
	{
		$('#prodgmtironorderTbl').datagrid('reload');
		msApp.resetForm('prodgmtironorderFrm');
		MsProdGmtIronOrder.resetForm();
		$('#prodgmtironorderFrm [name=prod_gmt_iron_id]').val($('#prodgmtironFrm [name=id]').val());
		$('#prodgmtironorderFrm [id="supplier_id"]').combobox('setValue', '');
	}

	edit(index, row)
	{
		row.route = this.route;
		row.formId = this.formId;
		let prod = this.MsProdGmtIronOrderModel.get(index, row);
		prod.then(function (response)
		{
			$('#prodgmtironorderFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
		})
			.catch(function (error)
			{
				console.log(error);
			});

	}

	showGrid(prod_gmt_iron_id)
	{
		let self = this;
		let data = {};
		data.prod_gmt_iron_id = prod_gmt_iron_id;
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
		return '<a href="javascript:void(0)"  onClick="MsProdGmtIronOrder.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openOrderIronWindow()
	{
		$('#openorderironwindow').window('open');
	}

	getParams()
	{
		let params = {};
		params.style_ref = $('#orderironsearchFrm [name=style_ref]').val();
		params.job_no = $('#orderironsearchFrm [name=job_no]').val();
		params.sale_order_no = $('#orderironsearchFrm [name=sale_order_no]').val();
		return params;
	}
	searchIronOrderGrid()
	{
		let params = this.getParams();
		let d = axios.get(this.route + '/getironorder', { params })
			.then(function (response)
			{
				$('#orderironsearchTbl').datagrid('loadData', response.data);
			}).catch(function (error)
			{
				console.log(error);
			});
	}
	showIronOrderGrid(data)
	{
		let self = this;
		$('#orderironsearchTbl').datagrid({
			border: false,
			singleSelect: true,
			fit: true,
			onClickRow: function (index, row)
			{
				$('#prodgmtironorderFrm [name=sales_order_country_id]').val(row.sales_order_country_id);
				$('#prodgmtironorderFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#prodgmtironorderFrm [name=order_qty]').val(row.order_qty);
				$('#prodgmtironorderFrm [name=country_id]').val(row.country_id);
				$('#prodgmtironorderFrm [name=job_no]').val(row.job_no);
				$('#prodgmtironorderFrm [name=company_id]').val(row.company_id);
				$('#prodgmtironorderFrm [name=buyer_name]').val(row.buyer_name);
				$('#prodgmtironorderFrm [name=produced_company_id]').val(row.produced_company_id);
				$('#prodgmtironorderFrm [name=produced_company_name]').val(row.produced_company_name);
				$('#prodgmtironorderFrm [name=ship_date]').val(row.ship_date);
				$('#openorderironwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	openTableNoWindow()
	{
		$('#opentablenowindow').window('open');
	}

	searchTableNo()
	{
		let params = {};
		params.brand = $('#tablenosearchFrm  [name=brand]').val();
		params.custom_no = $('#tablenosearchFrm  [name=custom_no]').val();
		let d = axios.get(this.route + '/gettable', { params })
			.then(function (response)
			{
				$('#tablenosearchTbl').datagrid('loadData', response.data);
			}).catch(function (error)
			{
				console.log(error);
			});
	}

	showTableGrid(data)
	{
		let self = this;
		$('#tablenosearchTbl').datagrid({
			border: false,
			singleSelect: true,
			fit: true,
			onClickRow: function (index, row)
			{
				$('#prodgmtironorderFrm [name=asset_quantity_cost_id]').val(row.id);
				$('#prodgmtironorderFrm [name=table_no]').val(row.custom_no);
				$('#prodgmtironorderFrm [name=location_name]').val(row.location_name);
				$('#prodgmtironorderFrm [name=location_id]').val(row.location_id);
				$('#opentablenowindow').window('close');
				$('#tablenosearchTbl').datagrid('loadData', []);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

}
window.MsProdGmtIronOrder = new MsProdGmtIronOrderController(new MsProdGmtIronOrderModel());
MsProdGmtIronOrder.showIronOrderGrid([]);
// MsProdGmtIronOrder.showLineGrid([]);
MsProdGmtIronOrder.showTableGrid([]);