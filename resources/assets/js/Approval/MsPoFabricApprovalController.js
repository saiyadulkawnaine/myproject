let MsPoFabricApprovalModel = require('./MsPoFabricApprovalModel');
require('./../datagrid-filter.js');

class MsPoFabricApprovalController
{
	constructor(MsPoFabricApprovalModel)
	{
		this.MsPoFabricApprovalModel = MsPoFabricApprovalModel;
		this.formId = 'pofabricapprovalFrm';
		this.dataTable = '#pofabricapprovalTbl';
		this.route = msApp.baseUrl() + "/pofabricapproval"
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
		this.MsPoFabricApprovalModel.save(this.route + '/approved', 'POST', msApp.qs.stringify(formObj), this.response);
	}

	getParams()
	{
		let params = {};
		params.date_from = $('#pofabricapprovalFrm  [name=date_from]').val();
		params.date_to = $('#pofabricapprovalFrm  [name=date_to]').val();
		params.company_id = $('#pofabricapprovalFrm  [name=company_id]').val();
		params.supplier_id = $('#pofabricapprovalFrm  [name=supplier_id]').val();
		return params;
	}

	get()
	{
		let params = this.getParams();
		let d = axios.get(this.route + '/getdata', { params })
			.then(function (response)
			{
				$('#pofabricapprovalTbl').datagrid('loadData', response.data);
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
		MsPoFabricApproval.get();
	}

	resetForm()
	{
		msApp.resetForm(this.formId);
	}

	approveButton(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoFabricApproval.approve(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Approve</span></a>';
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
		this.MsPoFabricApprovalModel.save(this.route + '/unapproved', 'POST', msApp.qs.stringify(formObj), this.unresponse);
	}

	getParamsApp()
	{
		let params = {};
		params.date_from = $('#pofabricapprovedFrm  [name=app_date_from]').val();
		params.date_to = $('#pofabricapprovedFrm  [name=app_date_to]').val();
		params.company_id = $('#pofabricapprovedFrm  [name=company_id]').val();
		params.supplier_id = $('#pofabricapprovedFrm  [name=supplier_id]').val();
		return params;
	}

	getApp()
	{
		let params = this.getParamsApp();
		let d = axios.get(this.route + '/getdataapp', { params })
			.then(function (response)
			{
				$('#pofabricapprovedTbl').datagrid('loadData', response.data);
			})
			.catch(function (error)
			{
				console.log(error);
			});
	}

	showGridApp(data)
	{
		var dg = $("#pofabricapprovedTbl");
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
		MsPoFabricApproval.getApp();
	}

	unapproveButton(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoFabricApproval.unapprove(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Un-Approve</span></a>';
	}

	formatpdf(value, row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoFabricApproval.showpofabric(event,' + row.id + ')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>PDF</span></a>';
	}


	showpofabric(e, id)
	{
		window.open(msApp.baseUrl() + "/pofabric/getpospdf?id=" + id);
	}

	// formatpotype(value,row,index)
	// {
	// 	if (row.po_type=='Short'){
	// 	    return 'color:red;font:bold;font-size: 15px';
	//     }
	// }

	showsummeryHtml(id)
	{
		let params = {};
		params.id = id;
		let d = axios.get(msApp.baseUrl() + "/pofabricapproval/reportsummeryhtml", { params });
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
		return '<a href="javascript:void(0)"  onClick="MsPoFabricApproval.showsummeryHtml(' + row.id + ')">' + row.po_no + '</a>';
	}

	pofabricdetailWindow(company_id, style_fabrication_id, style_id)
	{
		let params = {};
		params.company_id = company_id;
		params.style_fabrication_id = style_fabrication_id;
		params.style_id = style_id;
		let d = axios.get(msApp.baseUrl() + "/pofabricapproval/podetails", { params })
			.then(function (response)
			{
				$('#pofabricapprovalpodetailWindow').window('open');
				$('#pofabricapprovalpofabricdetailTbl').datagrid('loadData', response.data);
			})
			.catch(function (error)
			{
				console.log(error);
			});
	}

	showGridPODetail(data)
	{
		var dgp = $("#pofabricapprovalpofabricdetailTbl");
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
				$('#pofabricapprovalpofabricdetailTbl').datagrid('reloadFooter', [
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

	rcvWindow(id)
	{
		let params = this.getParams();
		params.id = id;
		let data = axios.get(msApp.baseUrl() + "/pofabricapproval/getrcvno", { params })
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

	showRcvNo(data)
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
			return '<a href="javascript:void(0)" onClick="MsPoFabricApproval.rcvWindow(' + row.id + ')">' + row.rcv_qty + '</a>';
		}
	}


}
window.MsPoFabricApproval = new MsPoFabricApprovalController(new MsPoFabricApprovalModel());
MsPoFabricApproval.showGrid([]);
MsPoFabricApproval.showGridApp([]);
MsPoFabricApproval.showGridPODetail([]);
MsPoFabricApproval.showRcvNo([]);