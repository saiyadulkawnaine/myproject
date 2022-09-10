let MsProdBatchApprovalModel = require('./MsProdBatchApprovalModel');
require('./../datagrid-filter.js');

class MsProdBatchApprovalController {
	constructor(MsProdBatchApprovalModel)
	{
		this.MsProdBatchApprovalModel = MsProdBatchApprovalModel;
		this.formId='prodbatchapprovalFrm';
		this.dataTable='#prodbatchapprovalTbl';
		this.route=msApp.baseUrl()+"/prodbatchapproval"
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
		this.MsProdBatchApprovalModel.save(this.route+'/approved','POST',msApp.qs.stringify(formObj),this.response);
	}
    
	getParams(){
		let params={};
		params.date_from = $('#prodbatchapprovalFrm  [name=date_from]').val();
		params.date_to = $('#prodbatchapprovalFrm  [name=date_to]').val();
		params.company_id = $('#prodbatchapprovalFrm  [name=company_id]').val();
		params.batch_for = $('#prodbatchapprovalFrm  [name=batch_for]').val();
		return params;
    }

    get(){
        let params=this.getParams();
        let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#prodbatchapprovalTbl').datagrid('loadData', response.data);
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
        MsProdBatchApproval.get();
    }

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	approveButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsProdBatchApproval.approve(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Approve</span></a>';
	}

	batchCardButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsProdBatchApproval.showBatchCard(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Batch Card</span></a>';
	}

	
	showBatchCard(e,id)
	{
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(msApp.baseUrl()+"/prodbatch/report?id="+id);
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
		this.MsProdBatchApprovalModel.save(this.route+'/unapproved','POST',msApp.qs.stringify(formObj),this.unresponse);
	}

	getParamsApp(){
		let params={};
		params.date_from = $('#prodbatchapprovedFrm  [name=app_date_from]').val();
		params.date_to = $('#prodbatchapprovedFrm  [name=app_date_to]').val();
		params.company_id = $('#prodbatchapprovedFrm  [name=company_id]').val();
		params.buyer_id = $('#prodbatchapprovedFrm  [name=buyer_id]').val();
		return params;
    }

    getApp(){
        let params=this.getParamsApp();
        let e= axios.get(this.route+'/getdataapp',{params})
		.then(function (response) {
			$('#prodbatchapprovedTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridApp(data)
	{
		var dc = $("#prodbatchapprovedTbl");
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
        MsProdBatchApproval.getApp();
    }

	unapproveButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsProdBatchApproval.unapprove(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Un-Approve</span></a>';
	}
}
window.MsProdBatchApproval = new MsProdBatchApprovalController(new MsProdBatchApprovalModel());
MsProdBatchApproval.showGrid([]);
MsProdBatchApproval.showGridApp([]);