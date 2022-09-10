let MsSalesOrderShipDateChangeApprovalModel = require('./MsSalesOrderShipDateChangeApprovalModel');
require('./../datagrid-filter.js');

class MsSalesOrderShipDateChangeApprovalController {
	constructor(MsSalesOrderShipDateChangeApprovalModel)
	{
		this.MsSalesOrderShipDateChangeApprovalModel = MsSalesOrderShipDateChangeApprovalModel;
		this.formId='salesordershipdatechangeapprovalFrm';
		this.dataTable='#salesordershipdatechangeapprovalTbl';
		this.route=msApp.baseUrl()+"/salesordershipdatechangeapproval"
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
		this.MsSalesOrderShipDateChangeApprovalModel.save(this.route+'/approved','POST',msApp.qs.stringify(formObj),this.response);
	}
    
	getParams(){
		let params={};
		params.date_from = $('#salesordershipdatechangeapprovalFrm  [name=date_from]').val();
		params.date_to = $('#salesordershipdatechangeapprovalFrm  [name=date_to]').val();
		params.company_id = $('#salesordershipdatechangeapprovalFrm  [name=company_id]').val();
		return params;
    }

    get(){
        let params=this.getParams();
        let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#salesordershipdatechangeapprovalTbl').datagrid('loadData', response.data);
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
        MsSalesOrderShipDateChangeApproval.get();
    }

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	approveButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSalesOrderShipDateChangeApproval.approve(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Approve</span></a>';
	}

	orderProgressButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSalesOrderShipDateChangeApproval.pdf(event,'+row.sale_order_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Order Progress</span></a>';
	}

	pdf(e,sale_order_id){
		if(sale_order_id==""){
			alert("Select a Salesorder");
			return;
		}
		window.open(msApp.baseUrl()+"/salesordershipdatechangeapproval/salesorderprogress?sale_order_id="+sale_order_id);
	}

	

	
}
window.MsSalesOrderShipDateChangeApproval = new MsSalesOrderShipDateChangeApprovalController(new MsSalesOrderShipDateChangeApprovalModel());
MsSalesOrderShipDateChangeApproval.showGrid([]);
