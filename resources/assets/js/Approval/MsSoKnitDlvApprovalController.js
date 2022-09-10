let MsSoKnitDlvApprovalModel = require('./MsSoKnitDlvApprovalModel');
require('./../datagrid-filter.js');

class MsSoKnitDlvApprovalController {
	constructor(MsSoKnitDlvApprovalModel)
	{
		this.MsSoKnitDlvApprovalModel = MsSoKnitDlvApprovalModel;
		this.formId='soknitdlvapprovalFrm';
		this.dataTable='#soknitdlvapprovalTbl';
		this.route=msApp.baseUrl()+"/soknitdlvapproval"
	}
	
    approve(e,id){
		$.blockUI({
		message: '<i class="icon-spinner4 spinner">Just a moment...</i>',
		overlayCSS: {
		backgroundColor: '#1b2024',
		opacity: 0.8,
		zIndex: 999999,
		cursor: 'wait'
		},
		css:{
		border: 0,
		color: '#fff',
		padding: 0,
		zIndex: 9999999,
		backgroundColor: 'transparent'
		}
		});
		let formObj={}
		formObj.id=id;
		this.MsSoKnitDlvApprovalModel.save(this.route+'/approved','POST',msApp.qs.stringify(formObj),this.response);
	}
    
	getParams(){
		let params={};
		params.date_from = $('#soknitdlvapprovalFrm  [name=date_from]').val();
		params.date_to = $('#soknitdlvapprovalFrm  [name=date_to]').val();
		params.company_id = $('#soknitdlvapprovalFrm  [name=company_id]').val();
		params.buyer_id = $('#soknitdlvapprovalFrm  [name=buyer_id]').val();
		return params;
    }

    get(){
        let params=this.getParams();
        let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#soknitdlvapprovalTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
    
	showGrid(data)
	{
		var dg = $(this.dataTable);
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found'
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

    response(d){
        MsSoKnitDlvApproval.get();
    }

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	approveButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSoKnitDlvApproval.approve(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Approve</span></a>';
	}

	billButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSoKnitDlvApproval.showBill(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Bill</span></a>';
	}

	dcButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSoKnitDlvApproval.showDc(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>DC</span></a>';
	}

	showDc(e,id)
	{
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(msApp.baseUrl()+"/soknitdlv/dlvchalan?id="+id);
	}
	showBill(e,id)
	{
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(msApp.baseUrl()+"/soknitdlv/bill?id="+id);
	}

		unapprove(e,id){
		$.blockUI({
		message: '<i class="icon-spinner4 spinner">Just a moment...</i>',
		overlayCSS: {
		backgroundColor: '#1b2024',
		opacity: 0.8,
		zIndex: 999999,
		cursor: 'wait'
		},
		css:{
		border: 0,
		color: '#fff',
		padding: 0,
		zIndex: 9999999,
		backgroundColor: 'transparent'
		}
		});
		let formObj={}
		formObj.id=id;
		this.MsSoKnitDlvApprovalModel.save(this.route+'/unapproved','POST',msApp.qs.stringify(formObj),this.unresponse);
	}

	getParamsApp(){
		let params={};
		params.date_from = $('#soknitdlvapprovedFrm  [name=app_date_from]').val();
		params.date_to = $('#soknitdlvapprovedFrm  [name=app_date_to]').val();
		params.company_id = $('#soknitdlvapprovedFrm  [name=company_id]').val();
		params.buyer_id = $('#soknitdlvapprovedFrm  [name=buyer_id]').val();
		return params;
    }

    getApp(){
        let params=this.getParamsApp();
        let e= axios.get(this.route+'/getdataapp',{params})
		.then(function (response) {
			$('#soknitdlvapprovedTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridApp(data)
	{
		var dc = $("#soknitdlvapprovedTbl");
		dc.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found'
		});
		dc.datagrid('enableFilter').datagrid('loadData', data);
	}

	unresponse(d){
        MsSoKnitDlvApproval.getApp();
    }

	unapproveButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSoKnitDlvApproval.unapprove(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Un-Approve</span></a>';
	}

	
	requestletterButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSoKnitDlvApproval.showRequestLetter(event,'+row.buyer_id+','+'\''+row.as_on_date+'\''+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Total AR</span></a>';
	}

	showRequestLetter(e,buyer_id,as_on_date)
	{
		window.open(msApp.baseUrl()+"/soknitdlvapproval/pdf?buyer_id="+buyer_id+"&as_on_date="+as_on_date);
	}

}
window.MsSoKnitDlvApproval = new MsSoKnitDlvApprovalController(new MsSoKnitDlvApprovalModel());
MsSoKnitDlvApproval.showGrid([]);
MsSoKnitDlvApproval.showGridApp([]);
MsSoKnitDlvApproval.get();