let MsPoKnitServiceApprovalModel = require('./MsPoKnitServiceApprovalModel');
require('./../datagrid-filter.js');

class MsPoKnitServiceApprovalController
{
	constructor(MsPoKnitServiceApprovalModel)
	{
		this.MsPoKnitServiceApprovalModel = MsPoKnitServiceApprovalModel;
		this.formId = 'poknitserviceapprovalFrm';
		this.dataTable = '#poknitserviceapprovalTbl';
		this.route = msApp.baseUrl() + "/poknitserviceapproval"
	}

	approve(e, id)
	{
		$.blockUI({
			message: '<i class="icon-spinner4 spinner">Just a moment...</i>',
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
		let formObj = {}
		formObj.id = id;
		this.MsPoKnitServiceApprovalModel.save(this.route + '/approved', 'POST', msApp.qs.stringify(formObj), this.response);
	}

	getParams()
	{
		let params = {};
		params.date_from = $('#poknitserviceapprovalFrm  [name=date_from]').val();
		params.date_to = $('#poknitserviceapprovalFrm  [name=date_to]').val();
		params.company_id = $('#poknitserviceapprovalFrm  [name=company_id]').val();
		params.supplier_id = $('#poknitserviceapprovalFrm  [name=supplier_id]').val();
		return params;
	}

	get()
	{
		let params = this.getParams();
		let d = axios.get(this.route + '/getdata', { params })
			.then(function (response)
			{
				$('#poknitserviceapprovalTbl').datagrid('loadData', response.data);
			})
			.catch(function (error)
			{
				console.log(error);
			});
	}

	showGrid(data)
	{
		var dg = $(this.dataTable);
		dg.datagrid({
			border: false,
			singleSelect: true,
			showFooter: true,
			fit: true,
			rownumbers: true,
			emptyMsg: 'No Record Found'
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	response(d)
	{
		MsPoKnitServiceApproval.get();
	}

	resetForm()
	{
		msApp.resetForm(this.formId);
	}

	approveButton(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoKnitServiceApproval.approve(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Approve</span></a>';
	}


	unapprove(e, id)
	{
		$.blockUI({
			message: '<i class="icon-spinner4 spinner">Just a moment...</i>',
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
		let formObj = {}
		formObj.id = id;
		this.MsPoKnitServiceApprovalModel.save(this.route + '/unapproved', 'POST', msApp.qs.stringify(formObj), this.unresponse);
	}

	getParamsApp()
	{
		let params = {};
		params.date_from = $('#poknitserviceapprovedFrm  [name=app_date_from]').val();
		params.date_to = $('#poknitserviceapprovedFrm  [name=app_date_to]').val();
		params.company_id = $('#poknitserviceapprovedFrm  [name=company_id]').val();
		params.supplier_id = $('#poknitserviceapprovedFrm  [name=supplier_id]').val();
		return params;
	}

	getApp()
	{
		let params = this.getParamsApp();
		let d = axios.get(this.route + '/getdataapp', { params })
			.then(function (response)
			{
				$('#poknitserviceapprovedTbl').datagrid('loadData', response.data);
			})
			.catch(function (error)
			{
				console.log(error);
			});
	}

	showGridApp(data)
	{
		var dg = $("#poknitserviceapprovedTbl");
		dg.datagrid({
			border: false,
			singleSelect: true,
			showFooter: true,
			fit: true,
			rownumbers: true,
			emptyMsg: 'No Record Found'
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	unresponse(d)
	{
		MsPoKnitServiceApproval.getApp();
	}

	unapproveButton(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoKnitServiceApproval.unapprove(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Un-Approve</span></a>';
	}

	formatPdf(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoKnitServiceApproval.pdf(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>PDF</span></a>';
	}

	pdf(e, id)
	{
		window.open(msApp.baseUrl() + "/poknitservice/report?id=" + id);
	}

	formatpotype(value, row, index)
	{
		if (row.po_type == 'Short') {
			return 'color:red;font:bold;font-size: 15px';
		}
	}

	showsummeryHtml(id)
	{
		let params = {};
		params.id = id;
		let d = axios.get(msApp.baseUrl() + "/poknitserviceapproval/reportsummeryhtml", { params });
		d.then(function (response)
		{
			$('#poknitserviceApprovalDetailContainer').html(response.data);
			$('#poknitserviceApprovalDetailWindow').window('open');
		})
			.catch(function (error)
			{
				console.log(error);
			});
	}

	formatsummery(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoKnitServiceApproval.showsummeryHtml(' + row.id + ')">' + row.po_no + '</a>';
	}

	poknitservicedetailWindow(style_id, company_id, style_fabrication_id, budget_fabric_prod_id)
	{
		let params = {};
		params.style_id = style_id;
		params.company_id = company_id;
		params.style_fabrication_id = style_fabrication_id;
		params.budget_fabric_prod_id = budget_fabric_prod_id;
		let d = axios.get(msApp.baseUrl() + "/poknitserviceapproval/podetails", { params })
			.then(function (response)
			{
				$('#poknitservicedetailWindow').window('open');
				$('#poknitservicedetailTbl').datagrid('loadData', response.data);
			})
			.catch(function (error)
			{
				console.log(error);
			});
	}

	showGridPODetail(data)
	{
		var dgp = $("#poknitservicedetailTbl");
		dgp.datagrid({
			border: false,
			singleSelect: true,
			showFooter: true,
			fit: true,
			rownumbers: true,
			showFooter: true,
			emptyMsg: 'No Record Found',
			onLoadSuccess: function (data)
			{
				var po_qty = 0;
				var po_rate = 0;
				var po_amount = 0;

				for (var i = 0; i < data.rows.length; i++) {
					po_qty += data.rows[i]['po_qty'].replace(/,/g, '') * 1;
					po_amount += data.rows[i]['po_amount'].replace(/,/g, '') * 1;
				}
				if (po_qty) {
					po_rate = po_amount / po_qty;
				}
				$('#poknitservicedetailTbl').datagrid('reloadFooter', [
					{
						po_qty: po_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						po_rate: po_rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						po_amount: po_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		});
		dgp.datagrid('enableFilter').datagrid('loadData', data);
	}

}
window.MsPoKnitServiceApproval = new MsPoKnitServiceApprovalController(new MsPoKnitServiceApprovalModel());
MsPoKnitServiceApproval.showGrid([]);
MsPoKnitServiceApproval.showGridApp([]);
MsPoKnitServiceApproval.showGridPODetail([]);