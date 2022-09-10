let MsImpLcApprovalModel = require('./MsImpLcApprovalModel');
require('./../datagrid-filter.js');

class MsImpLcApprovalController {
	constructor(MsImpLcApprovalModel)
	{
		this.MsImpLcApprovalModel = MsImpLcApprovalModel;
		this.formId='implcapprovalFrm';
		this.dataTable='#implcapprovalTbl';
		this.route=msApp.baseUrl()+"/implcapproval"
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
		this.MsImpLcApprovalModel.save(this.route+'/approved','POST',msApp.qs.stringify(formObj),this.response);
		
	}

	confirmApprove(e,id){
		let formObj={}
		formObj.id=id;
		$.messager.confirm('Approve', 'This LC Value will exceed LC Opening Limit. Would you approve?', function(r){
			if (r){
				MsImpLcApproval.MsImpLcApprovalModel.save(MsImpLcApproval.route+'/approved','POST',msApp.qs.stringify(formObj),MsImpLcApproval.response);
			}
		})
	}
    
	getParams(){
		let params={};
		params.company_id = $('#implcapprovalFrm [name=company_id]').val();
		params.supplier_id = $('#implcapprovalFrm [name=supplier_id]').val();
		params.menu_id = $('#implcapprovalFrm  [name=menu_id]').val();
		params.lc_to_id = $('#implcapprovalFrm  [name=lc_to_id]').val();
		return params;
 	}

 	get(){
		let params=this.getParams();
		let d= axios.get(this.route+'/getdata',{params}).then(function (response) {
			$('#implcapprovalTbl').datagrid('loadData', response.data);
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
		MsImpLcApproval.get();
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}


	approveButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsImpLcApproval.approve(event,'+row.id+',event)"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Approve</span></a>';
	}

	approveButton(value,row)
	{
		let bt='<a href="javascript:void(0)"  onClick="MsImpLcApproval.approve(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Approve</span></a>';
		if (row.lc_type_id=='Back To Back LC' && row.lc_amount*1>row.fund_available*1) {
			bt='<a href="javascript:void(0)"  onClick="MsImpLcApproval.confirmApprove(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Approve</span></a>';
		}
		// if (row.lc_amount*1<=row.fund_available*1) {
		// 	 bt='<a href="javascript:void(0)"  onClick="MsImpLcApproval.approve(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Approve</span></a>';
		// }
		return bt;
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
		this.MsImpLcApprovalModel.save(this.route+'/unapproved','POST',msApp.qs.stringify(formObj),this.unresponse);
	}

	getParamsApp(){
		let params={};
		params.company_id = $('#implcapprovedFrm  [name=company_id]').val();
		params.supplier_id = $('#implcapprovedFrm [name=supplier_id]').val();
		params.menu_id = $('#implcapprovedFrm  [name=menu_id]').val();
		params.lc_to_id = $('#implcapprovedFrm  [name=lc_to_id]').val();
		return params;
 	}

 	getApp(){
		let params=this.getParamsApp();
		let d= axios.get(this.route+'/getdataapp',{params})
			.then(function (response) {
			$('#implcapprovedTbl').datagrid('loadData', response.data);
			})
			.catch(function (error) {
			console.log(error);
		});
	}

	showGridApp(data)
	{
		var dga = $("#implcapprovedTbl");
		dga.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found'
		});
		dga.datagrid('enableFilter').datagrid('loadData', data);
	}

	unresponse(d){
	  MsImpLcApproval.getApp();
	}

	unapproveButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsImpLcApproval.unapprove(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Un-Approve</span></a>';
	}

	pdf(id){
		window.open(msApp.baseUrl()+"/implcapproval/pdf?id="+id);
	}
	
	formatpdf(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsImpLcApproval.pdf('+row.id+')">'+row.id+'</a>';
	}

}
window.MsImpLcApproval = new MsImpLcApprovalController(new MsImpLcApprovalModel());
MsImpLcApproval.showGrid([]);
MsImpLcApproval.get();
MsImpLcApproval.showGridApp([]);