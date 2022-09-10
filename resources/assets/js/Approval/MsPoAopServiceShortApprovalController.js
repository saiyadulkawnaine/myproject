let MsPoAopServiceShortApprovalModel = require('./MsPoAopServiceShortApprovalModel');
require('./../datagrid-filter.js');

class MsPoAopServiceShortApprovalController
{
	constructor(MsPoAopServiceShortApprovalModel)
	{
		this.MsPoAopServiceShortApprovalModel = MsPoAopServiceShortApprovalModel;
		this.formId = 'poaopserviceshortapprovalFrm';
		this.dataTable = '#poaopserviceshortapprovalTbl';
		this.route = msApp.baseUrl() + "/poaopserviceshortapproval"
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
		this.MsPoAopServiceShortApprovalModel.save(this.route + '/approved', 'POST', msApp.qs.stringify(formObj), this.response);
	}

	getParams()
	{
		let params = {};
		params.date_from = $('#poaopserviceshortapprovalFrm  [name=date_from]').val();
		params.date_to = $('#poaopserviceshortapprovalFrm  [name=date_to]').val();
		params.company_id = $('#poaopserviceshortapprovalFrm  [name=company_id]').val();
		params.supplier_id = $('#poaopserviceshortapprovalFrm  [name=supplier_id]').val();
		return params;
	}

	get()
	{
		let params = this.getParams();
		let d = axios.get(this.route + '/getdata', { params })
			.then(function (response)
			{
				$('#poaopserviceshortapprovalTbl').datagrid('loadData', response.data);
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
		MsPoAopServiceShortApproval.get();
	}

	resetForm()
	{
		msApp.resetForm(this.formId);
	}

	approveButton(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoAopServiceShortApproval.approve(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Approve</span></a>';
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
		this.MsPoAopServiceShortApprovalModel.save(this.route + '/unapproved', 'POST', msApp.qs.stringify(formObj), this.unresponse);
	}

	getParamsApp()
	{
		let params = {};
		params.date_from = $('#poaopserviceshortapprovedFrm  [name=app_date_from]').val();
		params.date_to = $('#poaopserviceshortapprovedFrm  [name=app_date_to]').val();
		params.company_id = $('#poaopserviceshortapprovedFrm  [name=company_id]').val();
		params.supplier_id = $('#poaopserviceshortapprovedFrm  [name=supplier_id]').val();
		return params;
	}

	getApp()
	{
		let params = this.getParamsApp();
		let d = axios.get(this.route + '/getdataapp', { params })
			.then(function (response)
			{
				$('#poaopserviceshortapprovedTbl').datagrid('loadData', response.data);
			})
			.catch(function (error)
			{
				console.log(error);
			});
	}

	showGridApp(data)
	{
		var dg = $("#poaopserviceshortapprovedTbl");
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
		MsPoAopServiceShortApproval.getApp();
	}

	unapproveButton(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoAopServiceShortApproval.unapprove(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Un-Approve</span></a>';
	}

	formatPdf(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoAopServiceShortApproval.pdf(' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>PDF</span></a>';
	}


	pdf(id)
	{
		window.open(msApp.baseUrl() + "/poaopservice/report?id=" + id);
	}

	showsummeryHtml(id)
	{
		let params = {};
		params.id = id;
		let d = axios.get(msApp.baseUrl() + "/poaopserviceshortapproval/reportsummeryhtml", { params });
		d.then(function (response)
		{
			$('#poaopserviceApprovalDetailContainer').html(response.data);
			$('#poaopserviceApprovalDetailWindow').window('open');
		})
			.catch(function (error)
			{
				console.log(error);
			});
	}

	formatsummery(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoAopServiceShortApproval.showsummeryHtml(' + row.id + ')">' + row.po_no + '</a>';
	}

	poaopservicedetailWindow(style_id, company_id, style_fabrication_id, budget_fabric_prod_id)
	{
		let params = {};
		params.style_id = style_id;
		params.company_id = company_id;
		params.style_fabrication_id = style_fabrication_id;
		params.budget_fabric_prod_id = budget_fabric_prod_id;
		let d = axios.get(msApp.baseUrl() + "/poaopserviceshortapproval/podetails", { params })
			.then(function (response)
			{
				$('#poaopservicedetailWindow').window('open');
				$('#poaopservicedetailTbl').datagrid('loadData', response.data);
			})
			.catch(function (error)
			{
				console.log(error);
			});
	}

	showGridPODetail(data)
	{
		var dgp = $("#poaopservicedetailTbl");
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
				$('#poaopservicedetailTbl').datagrid('reloadFooter', [
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
window.MsPoAopServiceShortApproval = new MsPoAopServiceShortApprovalController(new MsPoAopServiceShortApprovalModel());
MsPoAopServiceShortApproval.showGrid([]);
MsPoAopServiceShortApproval.showGridApp([]);
MsPoAopServiceShortApproval.showGridPODetail([]);