let MsPoTrimShortApprovalModel = require('./MsPoTrimShortApprovalModel');
require('./../datagrid-filter.js');

class MsPoTrimShortApprovalController
{
	constructor(MsPoTrimShortApprovalModel)
	{
		this.MsPoTrimShortApprovalModel = MsPoTrimShortApprovalModel;
		this.formId = 'potrimshortapprovalFrm';
		this.dataTable = '#potrimshortapprovalTbl';
		this.route = msApp.baseUrl() + "/potrimshortapproval"
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
		this.MsPoTrimShortApprovalModel.save(this.route + '/approved', 'POST', msApp.qs.stringify(formObj), this.response);
	}

	getParams()
	{
		let params = {};
		params.date_from = $('#potrimshortapprovalFrm  [name=date_from]').val();
		params.date_to = $('#potrimshortapprovalFrm  [name=date_to]').val();
		params.company_id = $('#potrimshortapprovalFrm  [name=company_id]').val();
		params.supplier_id = $('#potrimshortapprovalFrm  [name=supplier_id]').val();
		return params;
	}

	get()
	{
		let params = this.getParams();
		let d = axios.get(this.route + '/getdata', { params })
			.then(function (response)
			{
				$('#potrimshortapprovalTbl').datagrid('loadData', response.data);
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
		MsPoTrimShortApproval.get();
	}

	resetForm()
	{
		msApp.resetForm(this.formId);
	}

	approveButton(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoTrimShortApproval.approve(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Approve</span></a>';
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
		this.MsPoTrimShortApprovalModel.save(this.route + '/unapproved', 'POST', msApp.qs.stringify(formObj), this.unresponse);
	}

	getParamsApp()
	{
		let params = {};
		params.date_from = $('#potrimshortapprovedFrm  [name=app_date_from]').val();
		params.date_to = $('#potrimshortapprovedFrm  [name=app_date_to]').val();
		params.company_id = $('#potrimshortapprovedFrm  [name=company_id]').val();
		params.supplier_id = $('#potrimshortapprovedFrm  [name=supplier_id]').val();
		return params;
	}

	getApp()
	{
		let params = this.getParamsApp();
		let d = axios.get(this.route + '/getdataapp', { params })
			.then(function (response)
			{
				$('#potrimshortapprovedTbl').datagrid('loadData', response.data);
			})
			.catch(function (error)
			{
				console.log(error);
			});
	}

	showGridApp(data)
	{
		var dg = $("#potrimshortapprovedTbl");
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
		MsPoTrimShortApproval.getApp();
	}

	unapproveButton(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoTrimShortApproval.unapprove(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Un-Approve</span></a>';
	}

	potrimButton(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoTrimShortApproval.showpotrim(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>PDF</span></a>';
	}

	showpotrim(e, id)
	{
		if (id == "") {
			alert("Select a PDF");
			return;
		}
		window.open(msApp.baseUrl() + "/potrim/reportshort?id=" + id);
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
		let d = axios.get(msApp.baseUrl() + "/potrimshortapproval/reportsummeryhtml", { params });
		d.then(function (response)
		{
			$('#potrimApprovalDetailContainer').html(response.data);
			$('#potrimApprovalDetailWindow').window('open');
		})
			.catch(function (error)
			{
				console.log(error);
			});
	}

	formatsummery(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoTrimShortApproval.showsummeryHtml(' + row.id + ')">' + row.po_no + '</a>';
	}

	potrimdetailWindow(company_id, itemclass_id, style_id)
	{

		let params = {};
		params.company_id = company_id;
		params.itemclass_id = itemclass_id;
		params.style_id = style_id;
		let d = axios.get(msApp.baseUrl() + "/potrimshortapproval/podetails", { params })
			.then(function (response)
			{
				$('#potrimshortapprovalpodetailWindow').window('open');
				$('#potrimshortapprovalpotrimdetailTbl').datagrid('loadData', response.data);
			})
			.catch(function (error)
			{
				console.log(error);
			});
	}

	showGridPODetail(data)
	{
		var dgp = $("#potrimshortapprovalpotrimdetailTbl");
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
				$('#potrimshortapprovalpotrimdetailTbl').datagrid('reloadFooter', [
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
window.MsPoTrimShortApproval = new MsPoTrimShortApprovalController(new MsPoTrimShortApprovalModel());
MsPoTrimShortApproval.showGrid([]);
MsPoTrimShortApproval.showGridApp([]);
MsPoTrimShortApproval.showGridPODetail([]);