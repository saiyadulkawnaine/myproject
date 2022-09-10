let MsBudgetApprovalStatusModel = require('./MsBudgetApprovalStatusModel');
require('./../datagrid-filter.js');

class MsBudgetApprovalStatusController {
	constructor(MsBudgetApprovalStatusModel)
	{
		this.MsBudgetApprovalStatusModel = MsBudgetApprovalStatusModel;
		this.formId='budgetapprovalstatusFrm';
		this.dataTable='#budgetapprovalstatusTbl';
		this.route=msApp.baseUrl()+"/budgetapprovalstatus"
	}
	
    
    
	getParams(){
		let params={};
		params.date_from = $('#budgetapprovalstatusFrm  [name=date_from]').val();
		params.date_to = $('#budgetapprovalstatusFrm  [name=date_to]').val();
		params.company_id = $('#budgetapprovalstatusFrm  [name=company_id]').val();
		params.buyer_id = $('#budgetapprovalstatusFrm  [name=buyer_id]').val();
		return params;
    }

    get(){
        let params=this.getParams();
        let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#budgetapprovalstatusTbl').datagrid('loadData', response.data);
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


	getParamsRtn(){
		let params={};
		params.date_from = $('#budgetreturnedstatusFrm  [name=date_from]').val();
		params.date_to = $('#budgetreturnedstatusFrm  [name=date_to]').val();
		params.company_id = $('#budgetreturnedstatusFrm  [name=company_id]').val();
		params.buyer_id = $('#budgetreturnedstatusFrm  [name=buyer_id]').val();
		return params;
    }

    getRtn(){
        let params=this.getParams();
        let d= axios.get(this.route+'/getdatareturn',{params})
		.then(function (response) {
			$('#budgetreturnedstatusTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
    
	showGridRtn(data)
	{
		var dg = $("#budgetreturnedstatusTbl");
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
		params.type='MsBudgetFabricApproval';
		params.approval_type=approval_type;
		let d= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/formatThree",{params});
		d.then(function (response) {
			$('#budgetApprovalStatusDetailContainer').html('');
			$('#budgetApprovalStatusDetailContainer').html(response.data);
			$('#budgetApprovalStatusDetailWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}



	formatpdf(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsBudgetApprovalStatus.pdf('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Pdf</span></a>';
	}

	formatHtmlFirst(value,row)
	{		
		return '<a href="javascript:void(0)"  onClick="MsBudgetApprovalStatus.showHtml('+row.id+','+row.job_id+','+row.style_id+',\'firstapproved\')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Show</span></a>';
	}

	budVsMktButton(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsBudgetApprovalStatus.budVsMktHtml('+row.id+','+row.job_id+','+row.style_id+')"><span class="btn btn-success btn-xs"><i class="fa fa-search"></i>Details</span></a>';
	}

	budVsMktHtml(budget_id,job_id,style_id){
		let params={};
		params.id=budget_id;
		params.job_id=job_id;
		params.style_id=style_id;
		//params.approval_type=approval_type;
		let d= axios.get(msApp.baseUrl()+"/budgetandcostingcomparison/formatFour",{params});
		d.then(function (response) {
			$('#budgetApprovalStatusDetailContainer').html('');
			$('#budgetApprovalStatusDetailContainer').html(response.data);
			$('#budgetApprovalStatusDetailWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

}
window.MsBudgetApprovalStatus = new MsBudgetApprovalStatusController(new MsBudgetApprovalStatusModel());
MsBudgetApprovalStatus.showGrid([]);
MsBudgetApprovalStatus.showGridRtn([]);
//MsBudgetApprovalStatus.get();