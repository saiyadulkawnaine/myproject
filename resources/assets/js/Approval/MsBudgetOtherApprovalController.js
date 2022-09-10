let MsBudgetOtherApprovalModel = require('./MsBudgetOtherApprovalModel');
require('./../datagrid-filter.js');

class MsBudgetOtherApprovalController {
	constructor(MsBudgetOtherApprovalModel)
	{
		this.MsBudgetOtherApprovalModel = MsBudgetOtherApprovalModel;
		this.formId='budgetotherapprovalFrm';
		this.dataTable='#budgetotherapprovalTbl';
		this.route=msApp.baseUrl()+"/budgetotherapproval"
	}
	
    
    
	getParams(){
		let params={};
		params.date_from = $('#budgetotherapprovalFrm  [name=date_from]').val();
		params.date_to = $('#budgetotherapprovalFrm  [name=date_to]').val();
		params.company_id = $('#budgetotherapprovalFrm  [name=company_id]').val();
		params.buyer_id = $('#budgetotherapprovalFrm  [name=buyer_id]').val();
		return params;
    }

    show()
	{
		var pp = $('#budgetOtherApprovalAccordion').accordion('getSelected');
		var index = $('#budgetOtherApprovalAccordion').accordion('getPanelIndex', pp);
	    if(index==0)
	    {
			MsBudgetOtherApproval.showFirst();
		}
		if(index==1)
		{
			MsBudgetOtherApproval.showSecond();
		}
		if(index==2)
		{
			MsBudgetOtherApproval.showThird();
		}
		if(index==3)
		{
			MsBudgetOtherApproval.showFinal();
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
			$('#budgetotherapprovalfirstTbl').datagrid('loadData', response.data);
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
			$('#budgetotherapprovalsecondTbl').datagrid('loadData', response.data);
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
			$('#budgetotherapprovalthirdTbl').datagrid('loadData', response.data);
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
			$('#budgetotherapprovalfinalTbl').datagrid('loadData', response.data);
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
		params.type='MsBudgetOtherApproval';
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
		return '<a href="javascript:void(0)"  onClick="MsBudgetOtherApproval.pdf('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Pdf</span></a>';
	}

	formatHtmlFirst(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsBudgetOtherApproval.showHtml('+row.id+','+row.job_id+','+row.style_id+',\'firstapproved\')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Show</span></a>';
	}
	formatHtmlSecond(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsBudgetOtherApproval.showHtml('+row.id+','+row.job_id+','+row.style_id+',\'secondapproved\')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Show</span></a>';
	}
	formatHtmlThird(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsBudgetOtherApproval.showHtml('+row.id+','+row.job_id+','+row.style_id+',\'thirdapproved\')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Show</span></a>';
	}
	formatHtmlFinal(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsBudgetOtherApproval.showHtml('+row.id+','+row.job_id+','+row.style_id+',\'finalapproved\')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Show</span></a>';
	}

	budVsMktButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsBudgetOtherApproval.budVsMktHtml('+row.id+','+row.job_id+','+row.style_id+')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Details</span></a>';
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
		this.MsBudgetOtherApprovalModel.save(msApp.baseUrl()+"/budgetotherapproval/"+aproval_type,'POST',msApp.qs.stringify(formObj),this.response);		
	}
    response(d)
	{
		if(d.type=='firstapproved')
		{
			 MsBudgetOtherApproval.showFirst();
		}
		if(d.type=='secondapproved')
		{
			 MsBudgetOtherApproval.showSecond();
		}
		if(d.type=='thirdapproved')
		{
			 MsBudgetOtherApproval.showThird();
		}
		if(d.type=='finalapproved')
		{
			 MsBudgetOtherApproval.showFinal();
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
			 selected=$('#budgetotherapprovalfirstTbl').datagrid('getSelections');
		}
		if(aproval_type=='secondapproved')
		{
			 selected=$('#budgetotherapprovalsecondTbl').datagrid('getSelections');
		}
		if(aproval_type=='thirdapproved')
		{
			 selected=$('#budgetotherapprovalthirdTbl').datagrid('getSelections');
		}
		if(aproval_type=='finalapproved')
		{
			 selected=$('#budgetotherapprovalfinalTbl').datagrid('getSelections');
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
		this.MsBudgetOtherApprovalModel.save(msApp.baseUrl()+"/budgetotherapproval/"+aproval_type,'POST',msApp.qs.stringify(formObj),this.response);
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
		this.MsBudgetOtherApprovalModel.save(msApp.baseUrl()+"/budgetotherapprovalreturn",'POST',msApp.qs.stringify(formObj),this.response);
	}

	closeWindow(){
		$('#budgetApprovalDetailContainer').html('');
		$('#budgetApprovalDetailWindow').window('close');
	}

}
window.MsBudgetOtherApproval = new MsBudgetOtherApprovalController(new MsBudgetOtherApprovalModel());

MsBudgetOtherApproval.showGrid([],'#budgetotherapprovalfirstTbl');
MsBudgetOtherApproval.showGrid([],'#budgetotherapprovalsecondTbl');
MsBudgetOtherApproval.showGrid([],'#budgetotherapprovalthirdTbl');
MsBudgetOtherApproval.showGrid([],'#budgetotherapprovalfinalTbl');

$('#budgetOtherApprovalAccordion').accordion({
	onSelect:function(title,index){
		if(title==='First Approval'){
			//MsBudgetOtherApproval.showFirst();
		}
		if(title=='Second Approval'){
			MsBudgetOtherApproval.showSecond();
		}
		if(title=='Third Approval'){
			MsBudgetOtherApproval.showThird();
		}
		if(title=='Final Approval'){
			MsBudgetOtherApproval.showFinal();
		}
	}
})