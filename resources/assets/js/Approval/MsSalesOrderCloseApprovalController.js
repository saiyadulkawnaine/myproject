let MsSalesOrderCloseApprovalModel = require('./MsSalesOrderCloseApprovalModel');
require('./../datagrid-filter.js');

class MsSalesOrderCloseApprovalController {
	constructor(MsSalesOrderCloseApprovalModel)
	{
		this.MsSalesOrderCloseApprovalModel = MsSalesOrderCloseApprovalModel;
		this.formId='salesordercloseapprovalFrm';
		this.dataTable='#salesordercloseapprovalTbl';
		this.route=msApp.baseUrl()+"/salesordercloseapproval"
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
		this.MsSalesOrderCloseApprovalModel.save(this.route+'/approved','POST',msApp.qs.stringify(formObj),this.response);
	}
    
	getParams(){
		let params={};
		params.date_from = $('#salesordercloseapprovalFrm  [name=date_from]').val();
		params.date_to = $('#salesordercloseapprovalFrm  [name=date_to]').val();
		params.company_id = $('#salesordercloseapprovalFrm  [name=company_id]').val();
		return params;
 }
 get() {
   let params=this.getParams();
   let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#salesordercloseapprovalTbl').datagrid('loadData', response.data);
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
        MsSalesOrderCloseApproval.get();
    }

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	approveButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSalesOrderCloseApproval.approve(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Approve</span></a>';
	}

	orderProgressButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSalesOrderCloseApproval.pdf(event,'+row.sale_order_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Order Progress</span></a>';
	}

	pdf(e,sale_order_id){
		if(sale_order_id==""){
			alert("Select a Salesorder");
			return;
		}
		window.open(msApp.baseUrl()+"/salesordercloseapproval/salesorderprogress?sale_order_id="+sale_order_id);
	}

	

	
}
window.MsSalesOrderCloseApproval = new MsSalesOrderCloseApprovalController(new MsSalesOrderCloseApprovalModel());
MsSalesOrderCloseApproval.showGrid([]);
