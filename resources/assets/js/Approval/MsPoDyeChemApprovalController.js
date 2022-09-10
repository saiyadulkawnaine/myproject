let MsPoDyeChemApprovalModel = require('./MsPoDyeChemApprovalModel');
require('./../datagrid-filter.js');

class MsPoDyeChemApprovalController {
	constructor(MsPoDyeChemApprovalModel)
	{
		this.MsPoDyeChemApprovalModel = MsPoDyeChemApprovalModel;
		this.formId='podyechemapprovalFrm';
		this.dataTable='#podyechemapprovalTbl';
		this.route=msApp.baseUrl()+"/podyechemapproval"
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
		this.MsPoDyeChemApprovalModel.save(this.route+'/approved','POST',msApp.qs.stringify(formObj),this.response);
		
	}
    
	getParams(){
		let params={};
		params.company_id = $('#podyechemapprovalFrm [name=company_id]').val();
		params.supplier_id = $('#podyechemapprovalFrm [name=supplier_id]').val();
		params.menu_id = $('#podyechemapprovalFrm  [name=menu_id]').val();
		params.lc_to_id = $('#podyechemapprovalFrm  [name=lc_to_id]').val();
		return params;
 	}

 	get(){
		let params=this.getParams();
		let d= axios.get(this.route+'/getdata',{params}).then(function (response) {
			$('#podyechemapprovalTbl').datagrid('loadData', response.data);
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
	  MsPoDyeChemApproval.get();
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}


	approveButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoDyeChemApproval.approve(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Approve</span></a>';
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
		this.MsPoDyeChemApprovalModel.save(this.route+'/unapproved','POST',msApp.qs.stringify(formObj),this.unresponse);
	}

	getParamsApp(){
		let params={};
		params.company_id = $('#podyechemapprovedFrm  [name=company_id]').val();
		params.supplier_id = $('#podyechemapprovedFrm [name=supplier_id]').val();
		params.menu_id = $('#podyechemapprovedFrm  [name=menu_id]').val();
		params.lc_to_id = $('#podyechemapprovedFrm  [name=lc_to_id]').val();
		return params;
 	}

	getApp(){
		let params=this.getParamsApp();
		let d= axios.get(this.route+'/getdataapp',{params})
			.then(function (response) {
			$('#podyechemapprovedTbl').datagrid('loadData', response.data);
			})
			.catch(function (error) {
			console.log(error);
	  });
	}

	showGridApp(data)
	{
		var dga = $("#podyechemapprovedTbl");
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
	  MsPoDyeChemApproval.getApp();
	}

	unapproveButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoDyeChemApproval.unapprove(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Un-Approve</span></a>';
	}

	pdf(id){
		window.open(msApp.baseUrl()+"/podyechem/report?id="+id);
	}
	
	formatpdf(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsPoDyeChemApproval.pdf('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>PDF</span></a>';
	}

	
	rcvdyechemWindow(id){
		let params=this.getParams();
		params.id=id;
		let data= axios.get(msApp.baseUrl()+"/podyechemapproval/getrcvno",{params})
		.then(function (response) {
		    $('#dyechemrcvdetailTbl').datagrid('loadData', response.data);
		    $('#dyechemrcvdetailWindow').window('open');	
		})
		.catch(function (error) {
			console.log(error);
        });
        return data;
	}

	showDyeChemRcvNo(data){
		var rc = $('#dyechemrcvdetailTbl');
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
				$('#dyechemrcvdetailTbl').datagrid('reloadFooter', [
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
			return '<a href="javascript:void(0)" onClick="MsPoDyeChemApproval.rcvdyechemWindow('+row.id+')">'+row.rcv_qty+'</a>';
		}
	}
}
window.MsPoDyeChemApproval = new MsPoDyeChemApprovalController(new MsPoDyeChemApprovalModel());
MsPoDyeChemApproval.showGrid([]);
MsPoDyeChemApproval.showGridApp([]);
MsPoDyeChemApproval.showDyeChemRcvNo([]);