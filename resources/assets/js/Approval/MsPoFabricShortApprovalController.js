let MsPoFabricShortApprovalModel = require('./MsPoFabricShortApprovalModel');
require('./../datagrid-filter.js');

class MsPoFabricShortApprovalController
{
	constructor(MsPoFabricShortApprovalModel)
	{
		this.MsPoFabricShortApprovalModel = MsPoFabricShortApprovalModel;
		this.formId = 'pofabricshortapprovalFrm';
		this.dataTable = '#pofabricshortapprovalTbl';
		this.route = msApp.baseUrl() + "/pofabricshortapproval"
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
		this.MsPoFabricShortApprovalModel.save(this.route + '/approved', 'POST', msApp.qs.stringify(formObj), this.response);
	}

	getParams()
	{
		let params = {};
		params.date_from = $('#pofabricshortapprovalFrm  [name=date_from]').val();
		params.date_to = $('#pofabricshortapprovalFrm  [name=date_to]').val();
		params.company_id = $('#pofabricshortapprovalFrm  [name=company_id]').val();
		params.supplier_id = $('#pofabricshortapprovalFrm  [name=supplier_id]').val();
		return params;
	}

	get()
	{
		let params = this.getParams();
		let d = axios.get(this.route + '/getdata', { params })
			.then(function (response)
			{
				$('#pofabricshortapprovalTbl').datagrid('loadData', response.data);
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
			fit: true,
			rownumbers: true,
			emptyMsg: 'No Record Found'
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	response(d)
	{
		MsPoFabricShortApproval.get();
	}

	resetForm()
	{
		msApp.resetForm(this.formId);
	}

	approveButton(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoFabricShortApproval.approve(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Approve</span></a>';
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
		this.MsPoFabricShortApprovalModel.save(this.route + '/unapproved', 'POST', msApp.qs.stringify(formObj), this.unresponse);
	}

	getParamsApp()
	{
		let params = {};
		params.date_from = $('#pofabricshortapprovedFrm  [name=app_date_from]').val();
		params.date_to = $('#pofabricshortapprovedFrm  [name=app_date_to]').val();
		params.company_id = $('#pofabricshortapprovedFrm  [name=company_id]').val();
		params.supplier_id = $('#pofabricshortapprovedFrm  [name=supplier_id]').val();
		return params;
	}

	getApp()
	{
		let params = this.getParamsApp();
		let d = axios.get(this.route + '/getdataapp', { params })
			.then(function (response)
			{
				$('#pofabricshortapprovedTbl').datagrid('loadData', response.data);
			})
			.catch(function (error)
			{
				console.log(error);
			});
	}

	showGridApp(data)
	{
		var dg = $("#pofabricshortapprovedTbl");
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
		MsPoFabricShortApproval.getApp();
	}

	unapproveButton(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoFabricShortApproval.unapprove(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Un-Approve</span></a>';
	}

	formatpdf(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoFabricShortApproval.showpofabric(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>PDF</span></a>';
	}


	showpofabric(e, id)
	{
		window.open(msApp.baseUrl() + "/pofabric/getpospdf?id=" + id);
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
		let d = axios.get(msApp.baseUrl() + "/pofabricshortapproval/reportsummeryhtml", { params });
		d.then(function (response)
		{
			$('#pofabricApprovalDetailContainer').html(response.data);
			$('#pofabricApprovalDetailWindow').window('open');
		})
			.catch(function (error)
			{
				console.log(error);
			});
	}

	formatsummery(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoFabricShortApproval.showsummeryHtml(' + row.id + ')">' + row.po_no + '</a>';
	}

	pofabricdetailWindow(company_id, style_fabrication_id, style_id)
	{
		let params = {};
		params.company_id = company_id;
		params.style_fabrication_id = style_fabrication_id;
		params.style_id = style_id;
		let d = axios.get(msApp.baseUrl() + "/pofabricshortapproval/podetails", { params })
			.then(function (response)
			{
				$('#pofabricshortapprovalpodetailWindow').window('open');
				$('#pofabricshortapprovalpofabricdetailTbl').datagrid('loadData', response.data);
			})
			.catch(function (error)
			{
				console.log(error);
			});
	}

	showGridPODetail(data)
	{
		var dgp = $("#pofabricshortapprovalpofabricdetailTbl");
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
				$('#pofabricshortapprovalpofabricdetailTbl').datagrid('reloadFooter', [
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
window.MsPoFabricShortApproval = new MsPoFabricShortApprovalController(new MsPoFabricShortApprovalModel());
MsPoFabricShortApproval.showGrid([]);
MsPoFabricShortApproval.showGridApp([]);
MsPoFabricShortApproval.showGridPODetail([]);