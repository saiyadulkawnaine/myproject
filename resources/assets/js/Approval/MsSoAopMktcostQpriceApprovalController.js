let MsSoAopMktCostQpriceApprovalModel = require('./MsSoAopMktCostQpriceApprovalModel');
require('./../datagrid-filter.js');
class MsSoAopMktCostQpriceApprovalController {
	constructor(MsSoAopMktCostQpriceApprovalModel)
	{
		this.MsSoAopMktCostQpriceApprovalModel = MsSoAopMktCostQpriceApprovalModel;
		this.formId='soaopmktcostqpriceapprovalFrm';
		this.dataTable='#soaopmktcostqpriceapprovalTbl';
		this.route=msApp.baseUrl()+"/soaopmktcostqpriceapproval/getdata";
	}
	getParams()
	{
		let params={};
		params.buyer_id = $('#soaopmktcostqpriceapprovalFrm  [name=buyer_id]').val();
		params.team_id = $('#soaopmktcostqpriceapprovalFrm  [name=team_id]').val();
		params.teammember_id = $('#soaopmktcostqpriceapprovalFrm  [name=teammember_id]').val();
		params.style_ref = $('#soaopmktcostqpriceapprovalFrm  [name=style_ref]').val();
		params.date_from = $('#soaopmktcostqpriceapprovalFrm  [name=date_from]').val();
		params.date_to = $('#soaopmktcostqpriceapprovalFrm  [name=date_to]').val();
		params.confirm_from = $('#soaopmktcostqpriceapprovalFrm  [name=confirm_from]').val();
		params.confirm_to = $('#soaopmktcostqpriceapprovalFrm  [name=confirm_to]').val();
		params.costing_from = $('#soaopmktcostqpriceapprovalFrm  [name=costing_from]').val();
		params.costing_to = $('#soaopmktcostqpriceapprovalFrm  [name=costing_to]').val();
		return params;
	}

	show()
	{
		var pp = $('#aopMktCostApprovalAccordion').accordion('getSelected');
		var index = $('#aopMktCostApprovalAccordion').accordion('getPanelIndex', pp);
	    if(index==0)
	    {
			MsSoAopMktCostQpriceApproval.showFirst();
		}
		if(index==1)
		{
			MsSoAopMktCostQpriceApproval.showSecond();
		}
		if(index==2)
		{
			MsSoAopMktCostQpriceApproval.showThird();
		}
		if(index==3)
		{
			MsSoAopMktCostQpriceApproval.showFinal();
		}
	}

	showFirst()
	{
		let params=this.getParams();
		params.approval_type_id=1;
		if(!params.approval_type_id){
			alert('Select Approval Type');
			return;
		}
		let d=this.get(params);
		d.then(function (response) {
			$('#soaopmktcostqpriceapprovalfirstTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showSecond()
	{
		let params=this.getParams();
		params.approval_type_id=2;
		if(!params.approval_type_id){
			alert('Select Approval Type');
			return;
		}
		let d=this.get(params);
		d.then(function (response) {
			$('#soaopmktcostqpriceapprovalsecondTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	showThird()
	{
		let params=this.getParams();
		params.approval_type_id=3;
		if(!params.approval_type_id){
			alert('Select Approval Type');
			return;
		}
		let d=this.get(params);
		d.then(function (response) {
			$('#soaopmktcostqpriceapprovalthirdTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showFinal()
	{
		let params=this.getParams();
		params.approval_type_id=10;
		if(!params.approval_type_id){
			alert('Select Approval Type');
			return;
		}
		let d=this.get(params);
		d.then(function (response) {
			$('#soaopmktcostqpriceapprovalfinalTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	get(params)
	{
		let d= axios.get(this.route,{params});
		return d;
	}
	showGrid(data,table)
	{
		var dg = $(table);
		dg.datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			rowStyler:function(index,row){
				if(row.return_to_third_approved_at){
					return 'background-color:red;';
				}
			}
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
		window.open(msApp.baseUrl()+"/soaopmktcostqprice/pdf?id="+id);
	}

	showHtml(id,approval_type){
		let params={};
		params.id=id;
		params.approval_type=approval_type;
		let d= axios.get(msApp.baseUrl()+"/soaopmktcostqprice/html",{params});
		d.then(function (response) {
			$('#soaopMktCostQpriceApprovalDetailContainer').html(response.data);
			$('#soaopMktCostQpriceApprovalDetailWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	formatpdf(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsSoAopMktCostQpriceApproval.pdf('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Pdf</span></a>';
	}
	formatHtmlFirst(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsSoAopMktCostQpriceApproval.showHtml('+row.id+',\'firstapproved\')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Show</span></a>';
	}
	formatHtmlSecond(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsSoAopMktCostQpriceApproval.showHtml('+row.id+',\'secondapproved\')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Show</span></a>';
	}
	formatHtmlThird(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsSoAopMktCostQpriceApproval.showHtml('+row.id+',\'thirdapproved\')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Show</span></a>';
	}
	formatHtmlFinal(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsSoAopMktCostQpriceApproval.showHtml('+row.id+',\'finalapproved\')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Show</span></a>';
	}

	// formatimage(value,row)
	// {
	// 	return '<img href="javascript:void(0)" width="15"  height="15" src="'+msApp.baseUrl()+'/images/'+row.flie_src+'" onClick="MsSoAopMktCostQpriceApproval.imageWindow('+'\''+row.flie_src+'\''+')"/>';
	// }
	
	// quotedprice(value,row,index)
	// {
	// 	if (row.cost_per_pcs*1 > value*1){
	// 		return 'color:red;';
	// 	}
	// }

	// styleformat(value,row,index)
	// {
	// 	if (row.status == 'Confirmed'){
	// 			return 'background-color:#8DF2AD;';
	// 	}
	// 	if (row.status == 'Refused'){
	// 			return 'background-color:#E66775;';
	// 	}
	// 	if (row.status == 'Cancel'){
	// 			return 'background-color:#E66775;';
	// 	}
	// }

	// frofitformat(value,row,index)
	// {
	// 	if ( value <0 ){
	// 			return 'color:red;';
	// 	}
	// }

	approved(aproval_type)
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
		formObj=this.getdata(aproval_type);
		
		if(!Object.keys(formObj).length){
			alert("Please select at least one item");
			$.unblockUI();
			return;
		}
		this.MsSoAopMktCostQpriceApprovalModel.save(msApp.baseUrl()+"/soaopmktcostqpriceapproval/"+aproval_type,'POST',msApp.qs.stringify(formObj),this.response);		
	}
    response(d)
	{
		if(d.type=='firstapproved')
		{
			 MsSoAopMktCostQpriceApproval.showFirst();
		}
		if(d.type=='secondapproved')
		{
			 MsSoAopMktCostQpriceApproval.showSecond();
		}
		if(d.type=='thirdapproved')
		{
			 MsSoAopMktCostQpriceApproval.showThird();
		}
		if(d.type=='finalapproved')
		{
			 MsSoAopMktCostQpriceApproval.showFinal();
		}

		$('#soaopMktCostQpriceApprovalDetailContainer').html('');
		$('#soaopMktCostQpriceApprovalDetailWindow').window('close');
		
	}

	getdata(aproval_type){
		let formObj={};
		let i=1;
		let selected='';
		if(aproval_type=='firstapproved')
		{
			 selected=$('#soaopmktcostqpriceapprovalfirstTbl').datagrid('getSelections');
		}
		if(aproval_type=='secondapproved')
		{
			 selected=$('#soaopmktcostqpriceapprovalsecondTbl').datagrid('getSelections');
		}
		if(aproval_type=='thirdapproved')
		{
			 selected=$('#soaopmktcostqpriceapprovalthirdTbl').datagrid('getSelections');
		}
		if(aproval_type=='finalapproved')
		{
			 selected=$('#soaopmktcostqpriceapprovalfinalTbl').datagrid('getSelections');
		}
		$.each(selected, function (idx, val) {
			formObj['id['+i+']']=val.id;
			i++;
		});
		return formObj;
	}

	selectAll(table)
	{
		$(table).datagrid('selectAll');
	}
	unselectAll(table)
	{
		$(table).datagrid('unselectAll');
	}

	approveSingle(aproval_type,so_aop_mkt_cost_qprice_id){
		if(so_aop_mkt_cost_qprice_id==''){
			alert("ID not found");
			return;
		}

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
		formObj['id[1]']=so_aop_mkt_cost_qprice_id;
		this.MsSoAopMktCostQpriceApprovalModel.save(msApp.baseUrl()+"/soaopmktcostqpriceapproval/"+aproval_type,'POST',msApp.qs.stringify(formObj),this.response);
	}

	appReturn(aproval_type,so_aop_mkt_cost_qprice_id){
		if(so_aop_mkt_cost_qprice_id==''){
			alert("ID not found");
			return;
		}

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
		let returned_coments=$('#soaopmktcostqpriceapprovalreturncommentFrm  [name=mkt_cost_aproval_return_comments]').val();
		let formObj={};
		formObj.id=so_aop_mkt_cost_qprice_id;
		formObj.returned_coments=returned_coments;
		formObj.aproval_type=aproval_type;
		this.MsSoAopMktCostQpriceApprovalModel.save(msApp.baseUrl()+"/soaopmktcostqpriceapprovalreturn",'POST',msApp.qs.stringify(formObj),this.response);
	}

	closeWindow(){
		$('#mktcostqpriceApprovalDetailContainer').html('');
		$('#mktcostqpriceApprovalDetailWindow').window('close');
	}


}
window.MsSoAopMktCostQpriceApproval=new MsSoAopMktCostQpriceApprovalController(new MsSoAopMktCostQpriceApprovalModel());
MsSoAopMktCostQpriceApproval.showGrid([],'#soaopmktcostqpriceapprovalfirstTbl');
MsSoAopMktCostQpriceApproval.showGrid([],'#soaopmktcostqpriceapprovalsecondTbl');
MsSoAopMktCostQpriceApproval.showGrid([],'#soaopmktcostqpriceapprovalthirdTbl');
MsSoAopMktCostQpriceApproval.showGrid([],'#soaopmktcostqpriceapprovalfinalTbl');

$('#aopMktCostApprovalAccordion').accordion({
	onSelect:function(title,index){
		if(title==='First Approval'){
			//MsSoAopMktCostQpriceApproval.showFirst();
		}
		if(title=='Second Approval'){
			MsSoAopMktCostQpriceApproval.showSecond();
		}
		if(title=='Third Approval'){
			MsSoAopMktCostQpriceApproval.showThird();
		}
		if(title=='Final Approval'){
			MsSoAopMktCostQpriceApproval.showFinal();
		}
	}
})
