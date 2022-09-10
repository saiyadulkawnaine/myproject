let MsSoEmbPrintEntOrderModel = require('./MsSoEmbPrintEntOrderModel');

class MsSoEmbPrintEntOrderController
{
	constructor(MsSoEmbPrintEntOrderModel)
	{
		this.MsSoEmbPrintEntOrderModel = MsSoEmbPrintEntOrderModel;
		this.formId = 'soembprintentorderFrm';
		this.dataTable = '#soembprintentorderTbl';
		this.route = msApp.baseUrl() + "/soembprintentorder"
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
			this.MsSoEmbPrintEntOrderModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsSoEmbPrintEntOrderModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}


	resetForm()
	{
		msApp.resetForm(this.formId);
		$('#sewinggmtcosi').html('');
		let so_emb_print_entry_id = $('#soembprintentryFrm  [name=id]').val();
		$('#soembprintentorderFrm  [name=so_emb_print_entry_id]').val(so_emb_print_entry_id);
		$('#soembprintentorderFrm [id="supplier_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj = msApp.get(this.formId);
		this.MsSoEmbPrintEntOrderModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id)
	{
		event.stopPropagation()
		this.MsSoEmbPrintEntOrderModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d)
	{
		$('#soembprintentorderTbl').datagrid('reload');
		msApp.resetForm('soembprintentorderFrm');
		MsSoEmbPrintEntOrder.resetForm();
		$('#soembprintentorderFrm [name=so_emb_print_entry_id]').val($('#soemdprintentryFrm [name=id]').val());

	}

	edit(index, row)
	{
		row.route = this.route;
		row.formId = this.formId;
		let so = this.MsSoEmbPrintEntOrderModel.get(index, row);
		so.then(function (response)
		{
			$('#soembprintentorderFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
		})
			.catch(function (error)
			{
				console.log(error);
			});

	}

	showGrid(so_emb_print_entry_id)
	{
		let self = this;
		let data = {};
		data.so_emb_print_entry_id = so_emb_print_entry_id;
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
		return '<a href="javascript:void(0)"  onClick="MsSoEmbPrintEntOrder.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openCutPanelOrderWindow()
	{
		$('#opensoembprintwindow').window('open');
	}

	getParams()
	{
		let params = {};
		params.sale_order_no = $('#opencutpanelordersearchFrm [name=sale_order_no]').val();
		params.prod_source_id = $('#soembprintentorderFrm [name=prod_source_id]').val();
		if (params.prod_source_id === '') {
			alert("Select Production Source");
		}
		return params;
	}

	searchCutpanelOrderGrid()
	{
		let params = this.getParams();
		let d = axios.get(this.route + '/getcutpanelorder', { params })
			.then(function (response)
			{
				$('#opencutpanelordersearchTbl').datagrid('loadData', response.data);
			}).catch(function (error)
			{
				console.log(error);
			});
	}

	showCutpanelOrderGrid(data)
	{
		let self = this;
		$('#opencutpanelordersearchTbl').datagrid({
			border: false,
			singleSelect: true,
			fit: true,
			onClickRow: function (index, row)
			{
				$('#soembprintentorderFrm [name=so_emb_cutpanel_rcv_qty_id]').val(row.id);
				$('#soembprintentorderFrm [name=sales_order_no]').val(row.sales_order_no);
				$('#soembprintentorderFrm [name=gmtspart]').val(row.gmtspart);
				$('#soembprintentorderFrm [name=item_desc]').val(row.item_desc);
				$('#soembprintentorderFrm [name=gmt_color]').val(row.gmt_color);
				$('#soembprintentorderFrm [name=design_no]').val(row.design_no);
				// $('#soembprintentorderFrm [name=asset_no]').val(row.asset_no);
				$('#opensoembprintwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}


	openmachinWindow()
	{
		$('#openmachinWindow').window('open');
	}

	getMachineParams()
	{
		let params = {};
		params.asset_no = $('#machinnosearchFrm [name=asset_no]').val();
		params.custom_no = $('#machinnosearchFrm [name=custom_no]').val();
		params.asset_name = $('#machinnosearchFrm [name=asset_name]').val();
		params.prod_date = $('#soembprintentryFrm [name=prod_date]').val();
		params.so_emb_cutpanel_rcv_qty_id = $('#soembprintentorderFrm [name=so_emb_cutpanel_rcv_qty_id]').val();
		return params;
	}
	searchMachineGrid()
	{
		let params = this.getMachineParams();
		let d = axios.get(this.route + '/getmachine', { params })
			.then(function (response)
			{
				$('#machinnosearchTbl').datagrid('loadData', response.data);
			}).catch(function (error)
			{
				console.log(error);
			});
	}

	showMachineGrid(data)
	{
		let self = this;
		$('#machinnosearchTbl').datagrid({
			border: false,
			singleSelect: true,
			fit: true,
			onClickRow: function (index, row)
			{
				$('#soembprintentorderFrm [name=asset_quantity_cost_id]').val(row.id);
				$('#soembprintentorderFrm [name=asset_no]').val(row.asset_no);
				$('#openmachinWindow').window('close');
				$('#machinnosearchTbl').datagrid('loadData', []);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}


}
window.MsSoEmbPrintEntOrder = new MsSoEmbPrintEntOrderController(new MsSoEmbPrintEntOrderModel());
MsSoEmbPrintEntOrder.showCutpanelOrderGrid([]);
MsSoEmbPrintEntOrder.showMachineGrid([]);