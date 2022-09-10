let MsBudgetYarndyeApprovalModel = require('./MsBudgetYarndyeApprovalModel');
require('./../datagrid-filter.js');

class MsBudgetYarndyeApprovalController {
	constructor(MsBudgetYarndyeApprovalModel)
	{
		this.MsBudgetYarndyeApprovalModel = MsBudgetYarndyeApprovalModel;
		this.formId='budgetyarndyeapprovalFrm';
		this.dataTable='#budgetyarndyeapprovalTbl';
		this.route=msApp.baseUrl()+"/budgetyarndyeapproval"
	}
	
    
    
	getParams(){
		let params={};
		params.date_from = $('#budgetyarndyeapprovalFrm  [name=date_from]').val();
		params.date_to = $('#budgetyarndyeapprovalFrm  [name=date_to]').val();
		params.company_id = $('#budgetyarndyeapprovalFrm  [name=company_id]').val();
		params.buyer_id = $('#budgetyarndyeapprovalFrm  [name=buyer_id]').val();
		return params;
    }

    show()
	{
		var pp = $('#budgetYarndyeApprovalAccordion').accordion('getSelected');
		var index = $('#budgetYarndyeApprovalAccordion').accordion('getPanelIndex', pp);
	    if(index==0)
	    {
			MsBudgetYarndyeApproval.showFirst();
		}
		if(index==1)
		{
			MsBudgetYarndyeApproval.showSecond();
		}
		if(index==2)
		{
			MsBudgetYarndyeApproval.showThird();
		}
		if(index==3)
		{
			MsBudgetYarndyeApproval.showFinal();
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
			$('#budgetyarndyeapprovalfirstTbl').datagrid('loadData', response.data);
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
			$('#budgetyarndyeapprovalsecondTbl').datagrid('loadData', response.data);
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
			$('#budgetyarndyeapprovalthirdTbl').datagrid('loadData', response.data);
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
			$('#budgetyarndyeapprovalfinalTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	get(params)
	{
		let d= axios.get(this.route+'/getdata',{params});
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
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
	
	
	
	pdf(id){
		if(id==""){
			return;
		}
		window.open(msApp.baseUrl()+"/budget/report?id="+id);
	}

	showHtml(budget_id,job_id,style_id,approval_type){
		/*let params={};
		params.id=id;
		params.approval_type=approval_type;
		let d= axios.get(msApp.baseUrl()+"/budget/html",{params});
		d.then(function (response) {
			$('#budgetApprovalDetailContainer').html(response.data);
			$('#budgetApprovalDetailWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});*/
		let params={};
		params.id=budget_id;
		params.job_id=job_id;
		params.style_id=style_id;
		params.type='MsBudgetYarndyeApproval';
		params.approval_type=approval_type;
		let d= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/formatThree",{params});
		d.then(function (response) {
			$('#budgetApprovalDetailContainer').html(response.data);
			$('#budgetApprovalDetailWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	formatpdf(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsBudgetYarndyeApproval.pdf('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Pdf</span></a>';
	}

	formatHtmlFirst(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsBudgetYarndyeApproval.showHtml('+row.id+','+row.job_id+','+row.style_id+',\'firstapproved\')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Show</span></a>';
	}
	formatHtmlSecond(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsBudgetYarndyeApproval.showHtml('+row.id+','+row.job_id+','+row.style_id+',\'secondapproved\')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Show</span></a>';
	}
	formatHtmlThird(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsBudgetYarndyeApproval.showHtml('+row.id+','+row.job_id+','+row.style_id+',\'thirdapproved\')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Show</span></a>';
	}
	formatHtmlFinal(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsBudgetYarndyeApproval.showHtml('+row.id+','+row.job_id+','+row.style_id+',\'finalapproved\')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Show</span></a>';
	}

	budVsMktButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsBudgetYarndyeApproval.budVsMktHtml('+row.id+','+row.job_id+','+row.style_id+')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Details</span></a>';
	}

	budVsMktHtml(budget_id,job_id,style_id){
		let params={};
		params.id=budget_id;
		params.job_id=job_id;
		params.style_id=style_id;
		//params.approval_type=approval_type;
		let d= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/formatFour",{params});
		d.then(function (response) {
			$('#budgetApprovalDetailContainer').html('');
			$('#budgetApprovalDetailContainer').html(response.data);
			$('#budgetApprovalDetailWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	
	
	

	

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
		this.MsBudgetYarndyeApprovalModel.save(msApp.baseUrl()+"/budgetyarndyeapproval/"+aproval_type,'POST',msApp.qs.stringify(formObj),this.response);		
	}
    response(d)
	{
		if(d.type=='firstapproved')
		{
			 MsBudgetYarndyeApproval.showFirst();
		}
		if(d.type=='secondapproved')
		{
			 MsBudgetYarndyeApproval.showSecond();
		}
		if(d.type=='thirdapproved')
		{
			 MsBudgetYarndyeApproval.showThird();
		}
		if(d.type=='finalapproved')
		{
			 MsBudgetYarndyeApproval.showFinal();
		}

		$('#budgetApprovalDetailContainer').html('');
		$('#budgetApprovalDetailWindow').window('close');
		
	}

	getdata(aproval_type){
		let formObj={};
		let i=1;
		let selected='';
		if(aproval_type=='firstapproved')
		{
			 selected=$('#budgetyarndyeapprovalfirstTbl').datagrid('getSelections');
		}
		if(aproval_type=='secondapproved')
		{
			 selected=$('#budgetyarndyeapprovalsecondTbl').datagrid('getSelections');
		}
		if(aproval_type=='thirdapproved')
		{
			 selected=$('#budgetyarndyeapprovalthirdTbl').datagrid('getSelections');
		}
		if(aproval_type=='finalapproved')
		{
			 selected=$('#budgetyarndyeapprovalfinalTbl').datagrid('getSelections');
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

	approveSingle(aproval_type,budget_id){
		if(budget_id==''){
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
		formObj['id[1]']=budget_id;
		this.MsBudgetYarndyeApprovalModel.save(msApp.baseUrl()+"/budgetyarndyeapproval/"+aproval_type,'POST',msApp.qs.stringify(formObj),this.response);
	}

	appReturn(aproval_type,budget_id){
		if(budget_id==''){
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
		let returned_coments=$('#budgetaprovalreturncommentFrm  [name=budget_aproval_return_comments]').val();
		let formObj={};
		formObj.id=budget_id;
		formObj.returned_coments=returned_coments;
		formObj.aproval_type=aproval_type;
		this.MsBudgetYarndyeApprovalModel.save(msApp.baseUrl()+"/budgetyarndyeapprovalreturn",'POST',msApp.qs.stringify(formObj),this.response);
	}

	closeWindow(){
		$('#budgetApprovalDetailContainer').html('');
		$('#budgetApprovalDetailWindow').window('close');
	}

}
window.MsBudgetYarndyeApproval = new MsBudgetYarndyeApprovalController(new MsBudgetYarndyeApprovalModel());

MsBudgetYarndyeApproval.showGrid([],'#budgetyarndyeapprovalfirstTbl');
MsBudgetYarndyeApproval.showGrid([],'#budgetyarndyeapprovalsecondTbl');
MsBudgetYarndyeApproval.showGrid([],'#budgetyarndyeapprovalthirdTbl');
MsBudgetYarndyeApproval.showGrid([],'#budgetyarndyeapprovalfinalTbl');

$('#budgetYarndyeApprovalAccordion').accordion({
	onSelect:function(title,index){
		if(title==='First Approval'){
			//MsBudgetYarndyeApproval.showFirst();
		}
		if(title=='Second Approval'){
			MsBudgetYarndyeApproval.showSecond();
		}
		if(title=='Third Approval'){
			MsBudgetYarndyeApproval.showThird();
		}
		if(title=='Final Approval'){
			MsBudgetYarndyeApproval.showFinal();
		}
	}
})