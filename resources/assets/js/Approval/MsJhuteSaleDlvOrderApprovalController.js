let MsJhuteSaleDlvOrderApprovalModel = require('./MsJhuteSaleDlvOrderApprovalModel');
require('./../datagrid-filter.js');

class MsJhuteSaleDlvOrderApprovalController {
	constructor(MsJhuteSaleDlvOrderApprovalModel)
	{
		this.MsJhuteSaleDlvOrderApprovalModel = MsJhuteSaleDlvOrderApprovalModel;
		this.formId='jhutesaledlvorderapprovalFrm';
		this.dataTable='#jhutesaledlvorderapprovalTbl';
		this.route=msApp.baseUrl()+"/jhutesaledlvorderapproval"
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
		this.MsJhuteSaleDlvOrderApprovalModel.save(this.route+'/approved','POST',msApp.qs.stringify(formObj),this.response);
	}

	confirmApprove(e,id){
		let formObj={}
		formObj.id=id;
		$.messager.confirm('Approve', 'Received Amount is Less Than Do Value. Are you sure you want to Confirm?', function(r){
			if (r){
				MsJhuteSaleDlvOrderApproval.MsJhuteSaleDlvOrderApprovalModel.save(MsJhuteSaleDlvOrderApproval.route+'/approved','POST',msApp.qs.stringify(formObj),MsJhuteSaleDlvOrderApproval.response);
			}
		})
	}
    
	getParams(){
		let params={};
		params.date_from = $('#jhutesaledlvorderapprovalFrm  [name=date_from]').val();
		params.date_to = $('#jhutesaledlvorderapprovalFrm  [name=date_to]').val();
		params.company_id = $('#jhutesaledlvorderapprovalFrm  [name=company_id]').val();
		params.buyer_id = $('#jhutesaledlvorderapprovalFrm  [name=buyer_id]').val();
		return params;
    }

    get(){
        let params=this.getParams();
        let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#jhutesaledlvorderapprovalTbl').datagrid('loadData', response.data);
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
			method:'get',
			url:this.route+'/getdata',
			emptyMsg:'No Record Found'
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

    response(d){
    	$(this.dataTable).datagrid('reload');
        MsJhuteSaleDlvOrderApproval.get();
    }

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	approveButton(value,row)
	{
		let bt='';
		if (row.item_amount*1>row.paid_amount*1) {
			bt='<a href="javascript:void(0)"  onClick="MsJhuteSaleDlvOrderApproval.confirmApprove(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Approve</span></a>';
		}
		if (row.item_amount*1<=row.paid_amount*1) {
			bt='<a href="javascript:void(0)"  onClick="MsJhuteSaleDlvOrderApproval.approve(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Approve</span></a>';
		}
		return bt;
	}

	pdf(id){
		window.open(msApp.baseUrl()+"/jhutesaledlvorder/getdlvorderpdf?id="+id);
	}
	
	formatpdf(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsJhuteSaleDlvOrderApproval.pdf('+row.id+')">'+row.do_no+'</a>';
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
		this.MsJhuteSaleDlvOrderApprovalModel.save(this.route+'/unapproved','POST',msApp.qs.stringify(formObj),this.unresponse);
	}

	getParamsApp(){
		let params={};
		params.date_from = $('#jhutesaledlvorderapprovedFrm  [name=app_date_from]').val();
		params.date_to = $('#jhutesaledlvorderapprovedFrm  [name=app_date_to]').val();
		params.company_id = $('#jhutesaledlvorderapprovedFrm  [name=company_id]').val();
		params.buyer_id = $('#jhutesaledlvorderapprovedFrm  [name=buyer_id]').val();
		return params;
    }

    getApp(){
        let params=this.getParamsApp();
        let d= axios.get(this.route+'/getdataapp',{params})
		.then(function (response) {
			$('#jhutesaledlvorderapprovedTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridApp(data)
	{
		var dga = $("#jhutesaledlvorderapprovedTbl");
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
        MsJhuteSaleDlvOrderApproval.getApp();
    }

	unapproveButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsJhuteSaleDlvOrderApproval.unapprove(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Un-Approve</span></a>';
	}

}
window.MsJhuteSaleDlvOrderApproval = new MsJhuteSaleDlvOrderApprovalController(new MsJhuteSaleDlvOrderApprovalModel());
MsJhuteSaleDlvOrderApproval.showGrid([]);
MsJhuteSaleDlvOrderApproval.showGridApp([]);