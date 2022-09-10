let MsRqYarnApprovalModel = require('./MsRqYarnApprovalModel');
require('./../datagrid-filter.js');

class MsRqYarnApprovalController {
	constructor(MsRqYarnApprovalModel)
	{
		this.MsRqYarnApprovalModel = MsRqYarnApprovalModel;
		this.formId='rqyarnapprovalFrm';
		this.dataTable='#rqyarnapprovalTbl';
		this.route=msApp.baseUrl()+"/rqyarnapproval"
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
		this.MsRqYarnApprovalModel.save(this.route+'/approved','POST',msApp.qs.stringify(formObj),this.response);
	}
    
	getParams(){
		let params={};
		params.date_from = $('#rqyarnapprovalFrm  [name=date_from]').val();
		params.date_to = $('#rqyarnapprovalFrm  [name=date_to]').val();
		params.company_id = $('#rqyarnapprovalFrm  [name=company_id]').val();
		params.supplier_id = $('#rqyarnapprovalFrm  [name=supplier_id]').val();
		return params;
    }

    get(){
        let params=this.getParams();
        let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#rqyarnapprovalTbl').datagrid('loadData', response.data);
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
        MsRqYarnApproval.get();
    }

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	approveButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsRqYarnApproval.approve(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Approve</span></a>';
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
		this.MsRqYarnApprovalModel.save(this.route+'/unapproved','POST',msApp.qs.stringify(formObj),this.unresponse);
	}

	getParamsApp(){
		let params={};
		params.date_from = $('#rqyarnapprovedFrm  [name=app_date_from]').val();
		params.date_to = $('#rqyarnapprovedFrm  [name=app_date_to]').val();
		params.company_id = $('#rqyarnapprovedFrm  [name=company_id]').val();
		params.supplier_id = $('#rqyarnapprovedFrm  [name=supplier_id]').val();
		return params;
    }

    getApp(){
        let params=this.getParamsApp();
        let d= axios.get(this.route+'/getdataapp',{params})
		.then(function (response) {
			$('#rqyarnapprovedTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridApp(data)
	{
		var dg = $("#rqyarnapprovedTbl");
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

	unresponse(d){
        MsRqYarnApproval.getApp();
    }

	unapproveButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsRqYarnApproval.unapprove(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Un-Approve</span></a>';
	}

	rqyarnButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsRqYarnApproval.showrqyarn(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>PDF</span></a>';
	}

	
	showrqyarn(e,id)
	{
		if(id==""){
			alert("Select a PDF");
			return;
		}
		window.open(msApp.baseUrl()+"/rqyarn/report?id="+id);
	}

}
window.MsRqYarnApproval = new MsRqYarnApprovalController(new MsRqYarnApprovalModel());
MsRqYarnApproval.showGrid([]);
MsRqYarnApproval.showGridApp([]);