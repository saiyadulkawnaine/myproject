let MsEmployeeHRApprovalModel = require('./MsEmployeeHRApprovalModel');
require('./../datagrid-filter.js');

class MsEmployeeHRApprovalController {
	constructor(MsEmployeeHRApprovalModel)
	{
		this.MsEmployeeHRApprovalModel = MsEmployeeHRApprovalModel;
		this.formId='employeehrapprovalFrm';
		this.dataTable='#employeehrapprovalTbl';
		this.route=msApp.baseUrl()+"/employeehrapproval"
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
		this.MsEmployeeHRApprovalModel.save(this.route+'/approved','POST',msApp.qs.stringify(formObj),this.response);
	}
    
	getParams(){
		let params={};
		params.date_from = $('#employeehrapprovalFrm  [name=date_from]').val();
		params.date_to = $('#employeehrapprovalFrm  [name=date_to]').val();
		params.company_id = $('#employeehrapprovalFrm  [name=company_id]').val();
		return params;
    }

    get(){
        let params=this.getParams();
        let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#employeehrapprovalTbl').datagrid('loadData', response.data);
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
        MsEmployeeHRApproval.get();
    }

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	approveButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsEmployeeHRApproval.approve(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Approve</span></a>';
	}

	aplButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsEmployeeHRApproval.pdf(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>APP. Letter</span></a>';
	}

	pdf(e,id){
		if(id==""){
			alert("Select an Employee");
			return;
		}
		window.open(msApp.baseUrl()+"/employeehr/appointletter?id="+id);
	}

	showExcel(table_id,file_name){
		let params=this.getParams();
		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#employeehrapprovalTbl').datagrid('loadData', response.data);
			$('#employeehrapprovalTbl').datagrid('toExcel','Employee Approval.xls');
			//msApp.toExcel(table_id,file_name);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	
}
window.MsEmployeeHRApproval = new MsEmployeeHRApprovalController(new MsEmployeeHRApprovalModel());
MsEmployeeHRApproval.showGrid([]);
