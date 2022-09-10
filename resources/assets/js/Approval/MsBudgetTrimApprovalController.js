let MsBudgetTrimApprovalModel = require('./MsBudgetTrimApprovalModel');
require('./../datagrid-filter.js');

class MsBudgetTrimApprovalController {
	constructor(MsBudgetTrimApprovalModel)
	{
		this.MsBudgetTrimApprovalModel = MsBudgetTrimApprovalModel;
		this.formId='budgettrimapprovalFrm';
		this.dataTable='#budgettrimapprovalTbl';
		this.route=msApp.baseUrl()+"/budgettrimapproval"
	}
	
    
    
	getParams(){
		let params={};
		params.date_from = $('#budgettrimapprovalFrm  [name=date_from]').val();
		params.date_to = $('#budgettrimapprovalFrm  [name=date_to]').val();
		params.company_id = $('#budgettrimapprovalFrm  [name=company_id]').val();
		params.buyer_id = $('#budgettrimapprovalFrm  [name=buyer_id]').val();
		return params;
    }

    show()
	{
		var pp = $('#budgetTrimApprovalAccordion').accordion('getSelected');
		var index = $('#budgetTrimApprovalAccordion').accordion('getPanelIndex', pp);
	    if(index==0)
	    {
			MsBudgetTrimApproval.showFirst();
		}
		if(index==1)
		{
			MsBudgetTrimApproval.showSecond();
		}
		if(index==2)
		{
			MsBudgetTrimApproval.showThird();
		}
		if(index==3)
		{
			MsBudgetTrimApproval.showFinal();
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
			$('#budgettrimapprovalfirstTbl').datagrid('loadData', response.data);
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
			$('#budgettrimapprovalsecondTbl').datagrid('loadData', response.data);
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
			$('#budgettrimapprovalthirdTbl').datagrid('loadData', response.data);
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
			$('#budgettrimapprovalfinalTbl').datagrid('loadData', response.data);
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
		params.type='MsBudgetTrimApproval';
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
		return '<a href="javascript:void(0)"  onClick="MsBudgetTrimApproval.pdf('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Pdf</span></a>';
	}

	formatHtmlFirst(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsBudgetTrimApproval.showHtml('+row.id+','+row.job_id+','+row.style_id+',\'firstapproved\')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Show</span></a>';
	}
	formatHtmlSecond(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsBudgetTrimApproval.showHtml('+row.id+','+row.job_id+','+row.style_id+',\'secondapproved\')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Show</span></a>';
	}
	formatHtmlThird(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsBudgetTrimApproval.showHtml('+row.id+','+row.job_id+','+row.style_id+',\'thirdapproved\')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Show</span></a>';
	}
	formatHtmlFinal(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsBudgetTrimApproval.showHtml('+row.id+','+row.job_id+','+row.style_id+',\'finalapproved\')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Show</span></a>';
	}

	budVsMktButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsBudgetTrimApproval.budVsMktHtml('+row.id+','+row.job_id+','+row.style_id+')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Details</span></a>';
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
		this.MsBudgetTrimApprovalModel.save(msApp.baseUrl()+"/budgettrimapproval/"+aproval_type,'POST',msApp.qs.stringify(formObj),this.response);		
	}
    response(d)
	{
		if(d.type=='firstapproved')
		{
			 MsBudgetTrimApproval.showFirst();
		}
		if(d.type=='secondapproved')
		{
			 MsBudgetTrimApproval.showSecond();
		}
		if(d.type=='thirdapproved')
		{
			 MsBudgetTrimApproval.showThird();
		}
		if(d.type=='finalapproved')
		{
			 MsBudgetTrimApproval.showFinal();
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
			 selected=$('#budgettrimapprovalfirstTbl').datagrid('getSelections');
		}
		if(aproval_type=='secondapproved')
		{
			 selected=$('#budgettrimapprovalsecondTbl').datagrid('getSelections');
		}
		if(aproval_type=='thirdapproved')
		{
			 selected=$('#budgettrimapprovalthirdTbl').datagrid('getSelections');
		}
		if(aproval_type=='finalapproved')
		{
			 selected=$('#budgettrimapprovalfinalTbl').datagrid('getSelections');
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
		this.MsBudgetTrimApprovalModel.save(msApp.baseUrl()+"/budgettrimapproval/"+aproval_type,'POST',msApp.qs.stringify(formObj),this.response);
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
		this.MsBudgetTrimApprovalModel.save(msApp.baseUrl()+"/budgettrimapprovalreturn",'POST',msApp.qs.stringify(formObj),this.response);
	}

	closeWindow(){
		$('#budgetApprovalDetailContainer').html('');
		$('#budgetApprovalDetailWindow').window('close');
	}

}
window.MsBudgetTrimApproval = new MsBudgetTrimApprovalController(new MsBudgetTrimApprovalModel());

MsBudgetTrimApproval.showGrid([],'#budgettrimapprovalfirstTbl');
MsBudgetTrimApproval.showGrid([],'#budgettrimapprovalsecondTbl');
MsBudgetTrimApproval.showGrid([],'#budgettrimapprovalthirdTbl');
MsBudgetTrimApproval.showGrid([],'#budgettrimapprovalfinalTbl');

$('#budgetTrimApprovalAccordion').accordion({
	onSelect:function(title,index){
		if(title==='First Approval'){
			//MsBudgetTrimApproval.showFirst();
		}
		if(title=='Second Approval'){
			MsBudgetTrimApproval.showSecond();
		}
		if(title=='Third Approval'){
			MsBudgetTrimApproval.showThird();
		}
		if(title=='Final Approval'){
			MsBudgetTrimApproval.showFinal();
		}
	}
})