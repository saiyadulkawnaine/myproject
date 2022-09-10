let MsSoDyeingDlvApprovalModel = require('./MsSoDyeingDlvApprovalModel');
require('./../datagrid-filter.js');

class MsSoDyeingDlvApprovalController {
	constructor(MsSoDyeingDlvApprovalModel)
	{
		this.MsSoDyeingDlvApprovalModel = MsSoDyeingDlvApprovalModel;
		this.formId='sodyeingdlvapprovalFrm';
		this.dataTable='#sodyeingdlvapprovalTbl';
		this.route=msApp.baseUrl()+"/sodyeingdlvapproval"
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
		this.MsSoDyeingDlvApprovalModel.save(this.route+'/approved','POST',msApp.qs.stringify(formObj),this.response);
	}
    
	getParams(){
		let params={};
		params.date_from = $('#sodyeingdlvapprovalFrm  [name=date_from]').val();
		params.date_to = $('#sodyeingdlvapprovalFrm  [name=date_to]').val();
		params.company_id = $('#sodyeingdlvapprovalFrm  [name=company_id]').val();
		params.buyer_id = $('#sodyeingdlvapprovalFrm  [name=buyer_id]').val();
		return params;
    }

    get(){
        let params=this.getParams();
        let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#sodyeingdlvapprovalTbl').datagrid('loadData', response.data);
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
        MsSoDyeingDlvApproval.get();
    }

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	approveButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSoDyeingDlvApproval.approve(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Approve</span></a>';
	}

	billButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSoDyeingDlvApproval.showBill(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Bill</span></a>';
	}

	dcButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSoDyeingDlvApproval.showDc(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>DC</span></a>';
	}

	showDc(e,id)
	{
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(msApp.baseUrl()+"/sodyeingdlv/dlvchalan?id="+id);
	}
	showBill(e,id)
	{
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(msApp.baseUrl()+"/sodyeingdlv/bill?id="+id);
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
		this.MsSoDyeingDlvApprovalModel.save(this.route+'/unapproved','POST',msApp.qs.stringify(formObj),this.unresponse);
	}

	getParamsApp(){
		let params={};
		params.date_from = $('#sodyeingdlvapprovedFrm  [name=app_date_from]').val();
		params.date_to = $('#sodyeingdlvapprovedFrm  [name=app_date_to]').val();
		params.company_id = $('#sodyeingdlvapprovedFrm  [name=company_id]').val();
		params.buyer_id = $('#sodyeingdlvapprovedFrm  [name=buyer_id]').val();
		return params;
    }

    getApp(){
        let params=this.getParamsApp();
        let e= axios.get(this.route+'/getdataapp',{params})
		.then(function (response) {
			$('#sodyeingdlvapprovedTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridApp(data)
	{
		var dc = $("#sodyeingdlvapprovedTbl");
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
        MsSoDyeingDlvApproval.getApp();
    }

	unapproveButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSoDyeingDlvApproval.unapprove(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Un-Approve</span></a>';
	}

	
	requestletterButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSoDyeingDlvApproval.showRequestLetter(event,'+row.buyer_id+','+'\''+row.as_on_date+'\''+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Total AR</span></a>';
	}

	showRequestLetter(e,buyer_id,as_on_date)
	{
		window.open(msApp.baseUrl()+"/sodyeingdlvapproval/pdf?buyer_id="+buyer_id+"&as_on_date="+as_on_date);
	}

}
window.MsSoDyeingDlvApproval = new MsSoDyeingDlvApprovalController(new MsSoDyeingDlvApprovalModel());
MsSoDyeingDlvApproval.showGrid([]);
MsSoDyeingDlvApproval.get();
MsSoDyeingDlvApproval.showGridApp([]);