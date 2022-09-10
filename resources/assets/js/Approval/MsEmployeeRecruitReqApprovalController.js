let MsEmployeeRecruitReqApprovalModel = require('./MsEmployeeRecruitReqApprovalModel');
require('./../datagrid-filter.js');

class MsEmployeeRecruitReqApprovalController {
	constructor(MsEmployeeRecruitReqApprovalModel)
	{
		this.MsEmployeeRecruitReqApprovalModel = MsEmployeeRecruitReqApprovalModel;
		this.formId='employeerecruitreqapprovalFrm';
		this.dataTable='#employeerecruitreqapprovalTbl';
		this.route=msApp.baseUrl()+"/employeerecruitreqapproval"
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
		this.MsEmployeeRecruitReqApprovalModel.save(this.route+'/approved','POST',msApp.qs.stringify(formObj),this.response);
	}
    
	getParams(){
		let params={};
		params.date_from = $('#employeerecruitreqapprovalFrm  [name=date_from]').val();
		params.date_to = $('#employeerecruitreqapprovalFrm  [name=date_to]').val();
		params.company_id = $('#employeerecruitreqapprovalFrm  [name=company_id]').val();
		return params;
    }

    get(){
        let params=this.getParams();
        let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#employeerecruitreqapprovalTbl').datagrid('loadData', response.data);
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
        MsEmployeeRecruitReqApproval.get();
    }

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	approveButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsEmployeeRecruitReqApproval.approve(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Approve</span></a>';
	}

    empreplacementWindow(id){
		let data= axios.get(msApp.baseUrl()+"/employeerecruitreqapproval/getempreplace?id="+id);
		let ic=data.then(function (response) {
			$('#empreplacementWindow').window('open');
			$('#empreplacementTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		//return ic;
	}

	showGridEmpReplacement(data){
		var dgr = $('#empreplacementTbl');
		dgr.datagrid({
		border:false,
		singleSelect:true,
		//showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		});
		dgr.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatempreplacement(value,row){
		return '<a href="javascript:void(0)" onClick="MsEmployeeRecruitReqApproval.empreplacementWindow('+row.id+')">Replacement</a>';
	}

	// pdf(e,id){
	// 	if(id==""){
	// 		alert("Select a Requestion");
	// 		return;
	// 	}
	// 	window.open(msApp.baseUrl()+"/employeerecruitreqapproval/approveletter?id="+id);
	// }

    employeerecruitjobdescWindow(id){
		let data= axios.get(msApp.baseUrl()+"/employeerecruitreqapproval/getemprecruitreqjod?id="+id);
		let ic=data.then(function (response) {
			$('#employeerecruitjobdescWindow').window('open');
			$('#employeerecruitjobdescTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		//return ic;
	}

	showGridJobDesc(data){
		var dgj = $('#employeerecruitjobdescTbl');
		dgj.datagrid({
		border:false,
		singleSelect:true,
		//showFooter:true,
		fit:true,
		rownumbers:true,
		emptyMsg:'No Record Found',
		});
		dgj.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatemployeerecruitjobdesc(value,row){
		return '<a href="javascript:void(0)" onClick="MsEmployeeRecruitReqApproval.employeerecruitjobdescWindow('+row.id+')">Job Description</a>';
	}

}
window.MsEmployeeRecruitReqApproval = new MsEmployeeRecruitReqApprovalController(new MsEmployeeRecruitReqApprovalModel());
MsEmployeeRecruitReqApproval.showGrid([]);
MsEmployeeRecruitReqApproval.showGridJobDesc([]);
MsEmployeeRecruitReqApproval.showGridEmpReplacement([]);