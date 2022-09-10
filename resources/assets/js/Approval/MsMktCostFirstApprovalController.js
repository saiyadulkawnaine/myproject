let MsMktCostFirstApprovalModel = require('./MsMktCostFirstApprovalModel');
require('./../datagrid-filter.js');
class MsMktCostFirstApprovalController {
	constructor(MsMktCostFirstApprovalModel)
	{
		this.MsMktCostFirstApprovalModel = MsMktCostFirstApprovalModel;
		this.formId='mktcostfirstapprovalFrm';
		this.dataTable='#mktcostfirstapprovalTbl';
		this.route=msApp.baseUrl()+"/mktcostfirstapproval/getdata";
	}
	getParams()
	{
		let params={};
		params.buyer_id = $('#mktcostfirstapprovalFrm  [name=buyer_id]').val();
		params.team_id = $('#mktcostfirstapprovalFrm  [name=team_id]').val();
		params.teammember_id = $('#mktcostfirstapprovalFrm  [name=teammember_id]').val();
		params.style_ref = $('#mktcostfirstapprovalFrm  [name=style_ref]').val();
		params.date_from = $('#mktcostfirstapprovalFrm  [name=date_from]').val();
		params.date_to = $('#mktcostfirstapprovalFrm  [name=date_to]').val();
		params.confirm_from = $('#mktcostfirstapprovalFrm  [name=confirm_from]').val();
		params.confirm_to = $('#mktcostfirstapprovalFrm  [name=confirm_to]').val();
		params.costing_from = $('#mktcostfirstapprovalFrm  [name=costing_from]').val();
		params.costing_to = $('#mktcostfirstapprovalFrm  [name=costing_to]').val();
		return params;
	}

	approved(e,id)
	{
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
        let formObj={};
        formObj.id=id;
		this.MsMktCostFirstApprovalModel.save(msApp.baseUrl()+'/mktcostfirstapproval/approved','POST',msApp.qs.stringify(formObj),this.response);
		//formObj=this.getdata();
		
		//if(!Object.keys(formObj).length){
		//	alert("Please select at least one item");
		//	$.unblockUI();
		//	return;
		//}
		//this.MsMktCostFirstApprovalModel.save(msApp.baseUrl()+"/mktcostfirstapproval/"+aproval_type,'POST',msApp.qs.stringify(formObj),this.response);		
	}

	get()
	{
		let params=this.getParams();
        let d= axios.get(this.route,{params})
		.then(function (response) {
			$('#mktcostfirstapprovalTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

    response(d)
	{
		MsMktCostFirstApproval.get();
	}


	approveButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsMktCostFirstApproval.approved(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Approve</span></a>';
	}

	// show()
	// {
	// 	MsMktCostFirstApproval.showFinal();
	// }

	// showFinal()
	// {
	// 	let params=this.getParams();
	// 	params.approval_type_id=10;
	// 	if(!params.approval_type_id){
	// 		alert('Select Approval Type');
	// 		return;
	// 	}
	// 	let d=this.get(params);
	// 	d.then(function (response) {
	// 		$('#mktcostfirstapprovalfinalTbl').datagrid('loadData', response.data);
	// 	})
	// 	.catch(function (error) {
	// 		console.log(error);
	// 	});
	// }

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

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
	
	imageWindow(flie_src){
		var output = document.getElementById('quotationstatementImageWindowoutput');
		var fp=msApp.baseUrl()+"/images/"+flie_src;
		output.src =  fp;
		$('#quotationstatementImageWindow').window('open');
	}
	
	pdf(id){
		if(id==""){
			return;
		}
		window.open(msApp.baseUrl()+"/mktcost/report?id="+id);
	}
	
	formatpdf(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsMktCostFirstApproval.pdf('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Pdf</span></a>';
	}

	formatimage(value,row)
	{
		return '<img href="javascript:void(0)" width="15"  height="15" src="'+msApp.baseUrl()+'/images/'+row.flie_src+'" onClick="MsMktCostFirstApproval.imageWindow('+'\''+row.flie_src+'\''+')"/>';
	}
	
	quotedprice(value,row,index)
	{
		if (row.cost_per_pcs*1 > value*1){
			return 'color:red;';
		}
	}

	styleformat(value,row,index)
	{
		if (row.status == 'Confirmed'){
				return 'background-color:#8DF2AD;';
		}
		if (row.status == 'Refused'){
				return 'background-color:#E66775;';
		}
		if (row.status == 'Cancel'){
				return 'background-color:#E66775;';
		}
	}

	frofitformat(value,row,index)
	{
		if ( value <0 ){
				return 'color:red;';
		}
	}


	// getdata(){
	// 	let formObj={};
	// 	let i=1;
	// 	let selected=$('#mktcostfirstapprovalTbl').datagrid('getSelections');
	// 	$.each(selected, function (idx, val) {
	// 		formObj['id['+i+']']=val.id;
	// 		i++;
	// 	});
	// 	return formObj;
	// }

	// selectAll(table)
	// {
	// 	$(table).datagrid('selectAll');
	// }
	// unselectAll(table)
	// {
	// 	$(table).datagrid('unselectAll');
	// }


}
window.MsMktCostFirstApproval=new MsMktCostFirstApprovalController(new MsMktCostFirstApprovalModel());
MsMktCostFirstApproval.showGrid([]);

// $('#mktCostApprovalAccordion').accordion({
// 	onSelect:function(title,index){
// 		if(title==='First Approval'){
// 			//MsInvPurReqApproval.showFirst();
// 		}
// 		if(title=='Second Approval'){
// 			MsInvPurReqApproval.showSecond();
// 		}
// 		if(title=='Third Approval'){
// 			MsInvPurReqApproval.showThird();
// 		}
// 		if(title=='Final Approval'){
// 			MsInvPurReqApproval.showFinal();
// 		}
// 	}
// })
