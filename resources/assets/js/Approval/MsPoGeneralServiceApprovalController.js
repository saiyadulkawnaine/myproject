let MsPoGeneralServiceApprovalModel = require('./MsPoGeneralServiceApprovalModel');
require('./../datagrid-filter.js');

class MsPoGeneralServiceApprovalController {
	constructor(MsPoGeneralServiceApprovalModel)
	{
		this.MsPoGeneralServiceApprovalModel = MsPoGeneralServiceApprovalModel;
		this.formId='pogeneralserviceapprovalFrm';
		this.dataTable='#pogeneralserviceapprovalTbl';
		this.route=msApp.baseUrl()+"/pogeneralserviceapproval"
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
		this.MsPoGeneralServiceApprovalModel.save(this.route+'/approved','POST',msApp.qs.stringify(formObj),this.response);
	}
    
	getParams(){
		let params={};
		params.date_from = $('#pogeneralserviceapprovalFrm  [name=date_from]').val();
		params.date_to = $('#pogeneralserviceapprovalFrm  [name=date_to]').val();
		params.company_id = $('#pogeneralserviceapprovalFrm  [name=company_id]').val();
		params.supplier_id = $('#pogeneralserviceapprovalFrm  [name=supplier_id]').val();
		return params;
    }

    get(){
        let params=this.getParams();
        let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#pogeneralserviceapprovalTbl').datagrid('loadData', response.data);
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
        MsPoGeneralServiceApproval.get();
    }

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	approveButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoGeneralServiceApproval.approve(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Approve</span></a>';
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
		this.MsPoGeneralServiceApprovalModel.save(this.route+'/unapproved','POST',msApp.qs.stringify(formObj),this.unresponse);
	}

	getParamsApp(){
		let params={};
		params.date_from = $('#pogeneralserviceapprovedFrm  [name=app_date_from]').val();
		params.date_to = $('#pogeneralserviceapprovedFrm  [name=app_date_to]').val();
		params.company_id = $('#pogeneralserviceapprovedFrm  [name=company_id]').val();
		params.supplier_id = $('#pogeneralserviceapprovedFrm  [name=supplier_id]').val();
		return params;
    }

    getApp(){
        let params=this.getParamsApp();
        let d= axios.get(this.route+'/getdataapp',{params})
		.then(function (response) {
			$('#pogeneralserviceapprovedTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridApp(data)
	{
		var dg = $("#pogeneralserviceapprovedTbl");
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
        MsPoGeneralServiceApproval.getApp();
    }

	unapproveButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoGeneralServiceApproval.unapprove(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Un-Approve</span></a>';
	}

	pogeneralserviceButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoGeneralServiceApproval.showpogeneralservice(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>PDF</span></a>';
	}

	showpogeneralservice(e,id)
	{
		window.open(msApp.baseUrl()+"/pogeneralservice/report?id="+id);
	}
}
window.MsPoGeneralServiceApproval = new MsPoGeneralServiceApprovalController(new MsPoGeneralServiceApprovalModel());
MsPoGeneralServiceApproval.showGrid([]);
MsPoGeneralServiceApproval.showGridApp([]);