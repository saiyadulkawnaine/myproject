let MsPoGeneralApprovalModel = require('./MsPoGeneralApprovalModel');
require('./../datagrid-filter.js');

class MsPoGeneralApprovalController {
	constructor(MsPoGeneralApprovalModel)
	{
		this.MsPoGeneralApprovalModel = MsPoGeneralApprovalModel;
		this.formId='pogeneralapprovalFrm';
		this.dataTable='#pogeneralapprovalTbl';
		this.route=msApp.baseUrl()+"/pogeneralapproval"
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
		this.MsPoGeneralApprovalModel.save(this.route+'/approved','POST',msApp.qs.stringify(formObj),this.response);
	}
    
	getParams(){
		let params={};
		params.date_from = $('#pogeneralapprovalFrm  [name=date_from]').val();
		params.date_to = $('#pogeneralapprovalFrm  [name=date_to]').val();
		params.company_id = $('#pogeneralapprovalFrm  [name=company_id]').val();
		params.supplier_id = $('#pogeneralapprovalFrm  [name=supplier_id]').val();
		return params;
    }

    get(){
        let params=this.getParams();
        let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#pogeneralapprovalTbl').datagrid('loadData', response.data);
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
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found'
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

    response(d){
        MsPoGeneralApproval.get();
    }

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	approveButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoGeneralApproval.approve(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Approve</span></a>';
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
		this.MsPoGeneralApprovalModel.save(this.route+'/unapproved','POST',msApp.qs.stringify(formObj),this.unresponse);
	}

	getParamsApp(){
		let params={};
		params.date_from = $('#pogeneralapprovedFrm  [name=app_date_from]').val();
		params.date_to = $('#pogeneralapprovedFrm  [name=app_date_to]').val();
		params.company_id = $('#pogeneralapprovedFrm  [name=company_id]').val();
		params.supplier_id = $('#pogeneralapprovedFrm  [name=supplier_id]').val();
		return params;
    }

    getApp(){
        let params=this.getParamsApp();
        let d= axios.get(this.route+'/getdataapp',{params})
		.then(function (response) {
			$('#pogeneralapprovedTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridApp(data)
	{
		var dga = $("#pogeneralapprovedTbl");
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
        MsPoGeneralApproval.getApp();
    }

	unapproveButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoGeneralApproval.unapprove(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Un-Approve</span></a>';
	}

	formatpdf(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoGeneralApproval.pdf(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>PDF</span></a>';
	}
	
	pdf(e,id)
	{
		if(id==""){
			alert("Select a PDF");
			return;
		}
		window.open(msApp.baseUrl()+"/pogeneral/report?id="+id);
	}

	rcvgeneralWindow(id){
		let params=this.getParams();
		params.id=id;
		let data= axios.get(msApp.baseUrl()+"/pogeneralapproval/getrcvno",{params})
		.then(function (response) {
		    $('#generalrcvdetailTbl').datagrid('loadData', response.data);
		    $('#generalrcvdetailWindow').window('open');	
		})
		.catch(function (error) {
			console.log(error);
        });
        return data;
	}

	showGeneralRcvNo(data){
		var rc = $('#generalrcvdetailTbl');
		rc.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tQty=0;
				var tRate=0;
				var tAmount=0;
				var tStoreQty=0;
				var tStoreAmount=0;
				for(var i=0; i<data.rows.length; i++){
					tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					
					tAmount+=data.rows[i]['amount'].replace(/,/g,'')*1;
					tStoreQty+=data.rows[i]['store_qty'].replace(/,/g,'')*1;
					tStoreAmount+=data.rows[i]['store_amount'].replace(/,/g,'')*1;
				}
				tRate=tAmount/tQty;
				$('#generalrcvdetailTbl').datagrid('reloadFooter', [
					{ 
						qty: tQty.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						rate: tRate.toFixed(4).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						amount: tAmount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						store_qty: tStoreQty.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						store_amount: tStoreAmount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
					}
				]);
			}
		});
		rc.datagrid('enableFilter').datagrid('loadData', data);
	}
 
	formatRcvNo(value,row){
		if (row.rcv_qty) {
			return '<a href="javascript:void(0)" onClick="MsPoGeneralApproval.rcvgeneralWindow('+row.id+')">'+row.rcv_qty+'</a>';
		}
	}

}
window.MsPoGeneralApproval = new MsPoGeneralApprovalController(new MsPoGeneralApprovalModel());
MsPoGeneralApproval.showGrid([]);
MsPoGeneralApproval.showGridApp([]);
MsPoGeneralApproval.showGeneralRcvNo([]);