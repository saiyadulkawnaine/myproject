let MsPoTrimApprovalModel = require('./MsPoTrimApprovalModel');
require('./../datagrid-filter.js');

class MsPoTrimApprovalController
{
	constructor(MsPoTrimApprovalModel)
	{
		this.MsPoTrimApprovalModel = MsPoTrimApprovalModel;
		this.formId = 'potrimapprovalFrm';
		this.dataTable = '#potrimapprovalTbl';
		this.route = msApp.baseUrl() + "/potrimapproval"
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
		this.MsPoTrimApprovalModel.save(this.route + '/approved', 'POST', msApp.qs.stringify(formObj), this.response);
	}

	getParams()
	{
		let params = {};
		params.date_from = $('#potrimapprovalFrm  [name=date_from]').val();
		params.date_to = $('#potrimapprovalFrm  [name=date_to]').val();
		params.company_id = $('#potrimapprovalFrm  [name=company_id]').val();
		params.supplier_id = $('#potrimapprovalFrm  [name=supplier_id]').val();
		return params;
	}

	get()
	{
		let params = this.getParams();
		let d = axios.get(this.route + '/getdata', { params })
			.then(function (response)
			{
				$('#potrimapprovalTbl').datagrid('loadData', response.data);
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
		MsPoTrimApproval.get();
	}

	resetForm()
	{
		msApp.resetForm(this.formId);
	}

	approveButton(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoTrimApproval.approve(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Approve</span></a>';
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
		this.MsPoTrimApprovalModel.save(this.route + '/unapproved', 'POST', msApp.qs.stringify(formObj), this.unresponse);
	}

	getParamsApp()
	{
		let params = {};
		params.date_from = $('#potrimapprovedFrm  [name=app_date_from]').val();
		params.date_to = $('#potrimapprovedFrm  [name=app_date_to]').val();
		params.company_id = $('#potrimapprovedFrm  [name=company_id]').val();
		params.supplier_id = $('#potrimapprovedFrm  [name=supplier_id]').val();
		return params;
	}

	getApp()
	{
		let params = this.getParamsApp();
		let d = axios.get(this.route + '/getdataapp', { params })
			.then(function (response)
			{
				$('#potrimapprovedTbl').datagrid('loadData', response.data);
			})
			.catch(function (error)
			{
				console.log(error);
			});
	}

	showGridApp(data)
	{
		var dg = $("#potrimapprovedTbl");
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
		MsPoTrimApproval.getApp();
	}

	unapproveButton(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoTrimApproval.unapprove(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Un-Approve</span></a>';
	}

	potrimButton(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoTrimApproval.showpotrim(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>PDF</span></a>';
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
		let d = axios.get(msApp.baseUrl() + "/potrimapproval/reportsummeryhtml", { params });
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
		return '<a href="javascript:void(0)"  onClick="MsPoTrimApproval.showsummeryHtml(' + row.id + ')">' + row.po_no + '</a>';
	}

	potrimdetailWindow(company_id, itemclass_id, style_id)
	{

		let params = {};
		params.company_id = company_id;
		params.itemclass_id = itemclass_id;
		params.style_id = style_id;
		let d = axios.get(msApp.baseUrl() + "/potrimapproval/podetails", { params })
			.then(function (response)
			{
				$('#potrimapprovalpodetailWindow').window('open');
				$('#potrimapprovalpotrimdetailTbl').datagrid('loadData', response.data);
			})
			.catch(function (error)
			{
				console.log(error);
			});
	}

	showGridPODetail(data)
	{
		var dgp = $("#potrimapprovalpotrimdetailTbl");
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
				$('#potrimapprovalpotrimdetailTbl').datagrid('reloadFooter', [
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

	rcvtrimsWindow(id)
	{
		let params = this.getParams();
		params.id = id;
		let data = axios.get(msApp.baseUrl() + "/potrimapproval/getrcvno", { params })
			.then(function (response)
			{
				$('#rcvdetailTbl').datagrid('loadData', response.data);
				$('#rcvdetailWindow').window('open');
			})
			.catch(function (error)
			{
				console.log(error);
			});
		return data;
	}

	showTrimsRcvNo(data)
	{
		var rc = $('#rcvdetailTbl');
		rc.datagrid({
			border: false,
			singleSelect: true,
			showFooter: true,
			fit: true,
			rownumbers: true,
			emptyMsg: 'No Record Found',
			onLoadSuccess: function (data)
			{
				var tQty = 0;
				var tRate = 0;
				var tAmount = 0;
				var tStoreQty = 0;
				var tStoreAmount = 0;
				for (var i = 0; i < data.rows.length; i++) {
					tQty += data.rows[i]['qty'].replace(/,/g, '') * 1;

					tAmount += data.rows[i]['amount'].replace(/,/g, '') * 1;
					tStoreQty += data.rows[i]['store_qty'].replace(/,/g, '') * 1;
					tStoreAmount += data.rows[i]['store_amount'].replace(/,/g, '') * 1;
				}
				tRate = tAmount / tQty;
				$('#rcvdetailTbl').datagrid('reloadFooter', [
					{
						qty: tQty.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						rate: tRate.toFixed(4).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						amount: tAmount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						store_qty: tStoreQty.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						store_amount: tStoreAmount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
					}
				]);
			}
		});
		rc.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatRcvNo(value, row)
	{
		if (row.rcv_qty) {
			return '<a href="javascript:void(0)" onClick="MsPoTrimApproval.rcvtrimsWindow(' + row.id + ')">' + row.rcv_qty + '</a>';
		}
	}

}
window.MsPoTrimApproval = new MsPoTrimApprovalController(new MsPoTrimApprovalModel());
MsPoTrimApproval.showGrid([]);
MsPoTrimApproval.showGridApp([]);
MsPoTrimApproval.showGridPODetail([]);
MsPoTrimApproval.showTrimsRcvNo([]);