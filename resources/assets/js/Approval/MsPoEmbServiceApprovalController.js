let MsPoEmbServiceApprovalModel = require('./MsPoEmbServiceApprovalModel');
require('./../datagrid-filter.js');

class MsPoEmbServiceApprovalController
{
	constructor(MsPoEmbServiceApprovalModel)
	{
		this.MsPoEmbServiceApprovalModel = MsPoEmbServiceApprovalModel;
		this.formId = 'poembserviceapprovalFrm';
		this.dataTable = '#poembserviceapprovalTbl';
		this.route = msApp.baseUrl() + "/poembserviceapproval"
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
		this.MsPoEmbServiceApprovalModel.save(this.route + '/approved', 'POST', msApp.qs.stringify(formObj), this.response);

	}

	getParams()
	{
		let params = {};
		params.company_id = $('#poembserviceapprovalFrm [name=company_id]').val();
		params.supplier_id = $('#poembserviceapprovalFrm [name=supplier_id]').val();
		params.date_from = $('#poembserviceapprovalFrm [name=date_from]').val();
		params.date_to = $('#poembserviceapprovalFrm [name=date_to]').val();
		return params;
	}

	get()
	{
		let params = this.getParams();
		let d = axios.get(this.route + '/getdata', { params }).then(function (response)
		{
			$('#poembserviceapprovalTbl').datagrid('loadData', response.data);
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
		MsPoEmbServiceApproval.get();
	}

	resetForm()
	{
		msApp.resetForm(this.formId);
	}


	approveButton(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoEmbServiceApproval.approve(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Approve</span></a>';
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
		this.MsPoEmbServiceApprovalModel.save(this.route + '/unapproved', 'POST', msApp.qs.stringify(formObj), this.unresponse);
	}

	getParamsApp()
	{
		let params = {};
		params.company_id = $('#poembserviceapprovedFrm  [name=company_id]').val();
		params.supplier_id = $('#poembserviceapprovedFrm [name=supplier_id]').val();
		params.date_from = $('#poembserviceapprovedFrm [name=date_from]').val();
		params.date_to = $('#poembserviceapprovedFrm [name=date_to]').val();
		return params;
	}

	getApp()
	{
		let params = this.getParamsApp();
		let d = axios.get(this.route + '/getdataapp', { params })
			.then(function (response)
			{
				$('#poembserviceapprovedTbl').datagrid('loadData', response.data);
			})
			.catch(function (error)
			{
				console.log(error);
			});
	}

	showGridApp(data)
	{
		var dga = $("#poembserviceapprovedTbl");
		dga.datagrid({
			border: false,
			singleSelect: true,
			showFooter: true,
			fit: true,
			rownumbers: true,
			emptyMsg: 'No Record Found'
		});
		dga.datagrid('enableFilter').datagrid('loadData', data);
	}

	unresponse(d)
	{
		MsPoEmbServiceApproval.getApp();
	}

	unapproveButton(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoEmbServiceApproval.unapprove(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Un-Approve</span></a>';
	}

	formatPdf(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoEmbServiceApproval.pdf(' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>PDF</span></a>';
	}

	pdf(id)
	{
		window.open(msApp.baseUrl() + "/poembservice/report?id=" + id);
	}

	showsummeryHtml(id)
	{
		let params = {};
		params.id = id;
		let d = axios.get(msApp.baseUrl() + "/poembserviceapproval/reportsummeryhtml", { params });
		d.then(function (response)
		{
			$('#poembserviceApprovalDetailContainer').html(response.data);
			$('#poembserviceApprovalDetailWindow').window('open');
		})
			.catch(function (error)
			{
				console.log(error);
			});
	}

	formatsummery(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoEmbServiceApproval.showsummeryHtml(' + row.id + ')">' + row.po_no + '</a>';
	}

	poembservicedetailWindow(style_id, company_id, style_embelishment_id, budget_emb_id)
	{
		let params = {};
		params.style_id = style_id;
		params.company_id = company_id;
		params.style_embelishment_id = style_embelishment_id;
		params.budget_emb_id = budget_emb_id;
		let d = axios.get(msApp.baseUrl() + "/poembserviceapproval/podetails", { params })
			.then(function (response)
			{
				$('#poembservicedetailWindow').window('open');
				$('#poembservicedetailTbl').datagrid('loadData', response.data);
			})
			.catch(function (error)
			{
				console.log(error);
			});
	}

	showGridPODetail(data)
	{
		var dgp = $("#poembservicedetailTbl");
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
				$('#poembservicedetailTbl').datagrid('reloadFooter', [
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
window.MsPoEmbServiceApproval = new MsPoEmbServiceApprovalController(new MsPoEmbServiceApprovalModel());
MsPoEmbServiceApproval.showGrid([]);
MsPoEmbServiceApproval.showGridApp([]);
MsPoEmbServiceApproval.showGridPODetail([]);