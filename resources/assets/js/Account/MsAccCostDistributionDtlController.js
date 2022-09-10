let MsAccCostDistributionDtlModel = require('./MsAccCostDistributionDtlModel');
require('./../datagrid-filter.js');
class MsAccCostDistributionDtlController
{
	constructor(MsAccCostDistributionDtlModel)
	{
		this.MsAccCostDistributionDtlModel = MsAccCostDistributionDtlModel;
		this.formId = 'acccostdistributiondtlFrm';
		this.dataTable = '#acccostdistributiondtlTbl';
		this.route = msApp.baseUrl() + "/acccostdistributiondtl"
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
			this.MsAccCostDistributionDtlModel.save(this.route + "/" + formObj.id, 'PUT', msApp.qs.stringify(formObj), this.response);
		} else {
			this.MsAccCostDistributionDtlModel.save(this.route, 'POST', msApp.qs.stringify(formObj), this.response);
		}
	}

	resetForm()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj = msApp.get(this.formId);
		this.MsAccCostDistributionDtlModel.save(this.route + "/" + formObj.id, 'DELETE', null, this.response);
	}

	delete(event, id)
	{
		event.stopPropagation()
		this.MsAccCostDistributionDtlModel.save(this.route + "/" + id, 'DELETE', null, this.response);
	}

	response(d)
	{
		$('#acccostdistributiondtlTbl').datagrid('reload');
		MsAccCostDistributionDtl.resetForm();
	}

	edit(index, row)
	{
		row.route = this.route;
		row.formId = this.formId;
		this.MsAccCostDistributionDtlModel.get(index, row);
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
		return '<a href="javascript:void(0)"  onClick="MsAccCostDistributionDtl.delete(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openSalesOrderWindow()
	{
		$('#opensalesorderwindow').window('open');
	}

	getParams()
	{
		let params = {};
		params.style_ref = $('#salesordersearchFrm [name=style_ref]').val();
		params.job_no = $('#salesordersearchFrm [name=job_no]').val();
		params.sale_order_no = $('#salesordersearchFrm [name=sale_order_no]').val();
		return params;
	}

	searchSalesOrderGrid()
	{
		let params = this.getParams();
		let d = axios.get(this.route + '/getsalesorder', { params })
			.then(function (response)
			{
				$('#salesordersearchTbl').datagrid('loadData', response.data);
			}).catch(function (error)
			{
				console.log(error);
			});
	}

	showSalesOrderGrid(data)
	{
		let self = this;
		$('#salesordersearchTbl').datagrid({
			border: false,
			singleSelect: true,
			fit: true,
			onClickRow: function (index, row)
			{
				$('#acccostdistributiondtlFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#acccostdistributiondtlFrm [name=sale_order_id]').val(row.sale_order_id);
				$('#opensalesorderwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

}
window.MsAccCostDistributionDtl = new MsAccCostDistributionDtlController(new MsAccCostDistributionDtlModel());
MsAccCostDistributionDtl.showGrid();
MsAccCostDistributionDtl.showSalesOrderGrid([]);
