let MsPoYarnDyeingShortApprovalModel = require('./MsPoYarnDyeingShortApprovalModel');
require('./../datagrid-filter.js');

class MsPoYarnDyeingShortApprovalController
{
	constructor(MsPoYarnDyeingShortApprovalModel)
	{
		this.MsPoYarnDyeingShortApprovalModel = MsPoYarnDyeingShortApprovalModel;
		this.formId = 'poyarndyeingshortapprovalFrm';
		this.dataTable = '#poyarndyeingshortapprovalTbl';
		this.route = msApp.baseUrl() + "/poyarndyeingshortapproval"
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
		this.MsPoYarnDyeingShortApprovalModel.save(this.route + '/approved', 'POST', msApp.qs.stringify(formObj), this.response);

	}

	getParams()
	{
		let params = {};
		params.company_id = $('#poyarndyeingshortapprovalFrm [name=company_id]').val();
		params.supplier_id = $('#poyarndyeingshortapprovalFrm [name=supplier_id]').val();
		params.date_from = $('#poyarndyeingshortapprovalFrm [name=date_from]').val();
		params.date_to = $('#poyarndyeingshortapprovalFrm [name=date_to]').val();
		return params;
	}

	get()
	{
		let params = this.getParams();
		let d = axios.get(this.route + '/getdata', { params }).then(function (response)
		{
			$('#poyarndyeingshortapprovalTbl').datagrid('loadData', response.data);
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
		MsPoYarnDyeingShortApproval.get();
	}

	resetForm()
	{
		msApp.resetForm(this.formId);
	}


	approveButton(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoYarnDyeingShortApproval.approve(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Approve</span></a>';
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
		this.MsPoYarnDyeingShortApprovalModel.save(this.route + '/unapproved', 'POST', msApp.qs.stringify(formObj), this.unresponse);
	}

	getParamsApp()
	{
		let params = {};
		params.company_id = $('#poyarndyeingshortapprovedFrm  [name=company_id]').val();
		params.supplier_id = $('#poyarndyeingshortapprovedFrm [name=supplier_id]').val();
		params.date_from = $('#poyarndyeingshortapprovedFrm [name=date_from]').val();
		params.date_to = $('#poyarndyeingshortapprovedFrm [name=date_to]').val();
		return params;
	}

	getApp()
	{
		let params = this.getParamsApp();
		let d = axios.get(this.route + '/getdataapp', { params })
			.then(function (response)
			{
				$('#poyarndyeingshortapprovedTbl').datagrid('loadData', response.data);
			})
			.catch(function (error)
			{
				console.log(error);
			});
	}

	showGridApp(data)
	{
		var dg = $("#poyarndyeingshortapprovedTbl");
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
		MsPoYarnDyeingShortApproval.getApp();
	}

	unapproveButton(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoYarnDyeingShortApproval.unapprove(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Un-Approve</span></a>';
	}

	formatpoyarndyeingpdf(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoYarnDyeingShortApproval.pdf(event,' + row.id + ')">' + row.po_no + '</a>';
	}
	pdf(e, id)
	{
		window.open(msApp.baseUrl() + "/poyarndyeing/report?id=" + id);
	}

}
window.MsPoYarnDyeingShortApproval = new MsPoYarnDyeingShortApprovalController(new MsPoYarnDyeingShortApprovalModel());
MsPoYarnDyeingShortApproval.showGrid([]);
MsPoYarnDyeingShortApproval.showGridApp([]);