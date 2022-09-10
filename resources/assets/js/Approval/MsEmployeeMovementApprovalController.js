let MsEmployeeMovementApprovalModel = require('./MsEmployeeMovementApprovalModel');
require('./../datagrid-filter.js');

class MsEmployeeMovementApprovalController {
	constructor(MsEmployeeMovementApprovalModel)
	{
		this.MsEmployeeMovementApprovalModel = MsEmployeeMovementApprovalModel;
		this.formId='employeemovementapprovalFrm';
		this.dataTable='#employeemovementapprovalTbl';
		this.route=msApp.baseUrl()+"/employeemovementapproval"
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
		this.MsEmployeeMovementApprovalModel.save(this.route+'/approved','POST',msApp.qs.stringify(formObj),this.response);
	}
    
	getParams(){
		let params={};
		params.date_from = $('#employeemovementapprovalFrm  [name=date_from]').val();
		params.date_to = $('#employeemovementapprovalFrm  [name=date_to]').val();
		params.company_id = $('#employeemovementapprovalFrm  [name=company_id]').val();
		params.buyer_id = $('#employeemovementapprovalFrm  [name=buyer_id]').val();
		return params;
    }

    get(){
        let params=this.getParams();
        let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#employeemovementapprovalTbl').datagrid('loadData', response.data);
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
        MsEmployeeMovementApproval.get();
    }

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	approveButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsEmployeeMovementApproval.approve(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Approve</span></a>';
	}

	ticketButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsEmployeeMovementApproval.showTicket(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Ticket</span></a>';
	}

	showTicket(e,id)
	{
		if(id==""){
			alert("Select a Ticket");
			return;
		}
		window.open(msApp.baseUrl()+"/employeemovement/getempticket?id="+id);
	}

}
window.MsEmployeeMovementApproval = new MsEmployeeMovementApprovalController(new MsEmployeeMovementApprovalModel());
MsEmployeeMovementApproval.showGrid([]);
//MsEmployeeMovementApproval.get();