let MsSoAopDlvApprovalModel = require('./MsSoAopDlvApprovalModel');
require('./../datagrid-filter.js');

class MsSoAopDlvApprovalController {
	constructor(MsSoAopDlvApprovalModel)
	{
		this.MsSoAopDlvApprovalModel = MsSoAopDlvApprovalModel;
		this.formId='soaopdlvapprovalFrm';
		this.dataTable='#soaopdlvapprovalTbl';
		this.route=msApp.baseUrl()+"/soaopdlvapproval"
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
		this.MsSoAopDlvApprovalModel.save(this.route+'/approved','POST',msApp.qs.stringify(formObj),this.response);
	}
    
	getParams(){
		let params={};
		params.date_from = $('#soaopdlvapprovalFrm  [name=date_from]').val();
		params.date_to = $('#soaopdlvapprovalFrm  [name=date_to]').val();
		params.company_id = $('#soaopdlvapprovalFrm  [name=company_id]').val();
		params.buyer_id = $('#soaopdlvapprovalFrm  [name=buyer_id]').val();
		return params;
    }

    get(){
        let params=this.getParams();
        let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#soaopdlvapprovalTbl').datagrid('loadData', response.data);
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
        MsSoAopDlvApproval.get();
    }

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	approveButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSoAopDlvApproval.approve(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Approve</span></a>';
	}

	billButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSoAopDlvApproval.showBill(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Bill</span></a>';
	}

	dcButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSoAopDlvApproval.showDc(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>DC</span></a>';
	}

	showDc(e,id)
	{
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(msApp.baseUrl()+"/soaopdlv/dlvchalan?id="+id);
	}
	showBill(e,id)
	{
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(msApp.baseUrl()+"/soaopdlv/bill?id="+id);
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
		this.MsSoAopDlvApprovalModel.save(this.route+'/unapproved','POST',msApp.qs.stringify(formObj),this.unresponse);
	}

	getParamsApp(){
		let params={};
		params.date_from = $('#aopdlvapprovedFrm  [name=app_date_from]').val();
		params.date_to = $('#aopdlvapprovedFrm  [name=app_date_to]').val();
		params.company_id = $('#aopdlvapprovedFrm  [name=company_id]').val();
		params.buyer_id = $('#aopdlvapprovedFrm  [name=buyer_id]').val();
		return params;
    }

    getApp(){
        let params=this.getParamsApp();
        let r= axios.get(this.route+'/getdataapp',{params})
		.then(function (response) {
			$('#aopdlvapprovedTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridApp(data)
	{
		var bc = $("#aopdlvapprovedTbl");
		bc.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found'
		});
		bc.datagrid('enableFilter').datagrid('loadData', data);
	}

	unresponse(d){
       MsSoAopDlvApproval.getApp();
   }

	unapproveButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSoAopDlvApproval.unapprove(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Un-Approve</span></a>';
	}

		
	requestletterButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSoAopDlvApproval.showRequestLetter(event,'+row.buyer_id+','+'\''+row.as_on_date+'\''+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Total AR</span></a>';
	}

	showRequestLetter(e,buyer_id,as_on_date)
	{
		window.open(msApp.baseUrl()+"/soaopdlvapproval/pdf?buyer_id="+buyer_id+"&as_on_date="+as_on_date);
	}

}
window.MsSoAopDlvApproval = new MsSoAopDlvApprovalController(new MsSoAopDlvApprovalModel());
MsSoAopDlvApproval.showGrid([]);
MsSoAopDlvApproval.get();
MsSoAopDlvApproval.showGridApp([]);