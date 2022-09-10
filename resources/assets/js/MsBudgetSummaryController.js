//require('./jquery.easyui.min.js');
let MsBudgetSummaryModel = require('./MsBudgetSummaryModel');
require('./datagrid-filter.js');

class MsBudgetSummaryController {
	constructor(MsBudgetSummaryModel)
	{
		this.MsBudgetSummaryModel = MsBudgetSummaryModel;
		this.formId='budgetsummaryFrm';
		this.dataTable='#budgetsummaryTbl';
		this.route=msApp.baseUrl()+"/budgetsummary"
	}
	getParams()
	{
		let params={};
		//params.company_id = $('#budgetsummaryFrm  [name=company_id]').val();
		//params.buyer_id = $('#budgetsummaryFrm  [name=buyer_id]').val();
		//params.style_ref = $('#budgetsummaryFrm  [name=style_ref]').val();
		//params.job_no = $('#budgetsummaryFrm  [name=job_no]').val();
		params.date_from = $('#budgetsummaryFrm  [name=date_from]').val();
		params.date_to = $('#budgetsummaryFrm  [name=date_to]').val();
		//params.order_status = $('#budgetsummaryFrm  [name=order_status]').val();
		return params;
	}
	
	get(){
		let date_from=$('#budgetsummaryFrm  [name=date_from]').val();
		let date_to=$('#budgetsummaryFrm  [name=date_to]').val();

		if( date_from==''){
			alert('Please Select a date range ');
			return;
		}

		if(date_to==''){
			alert('Please Select a date range');
			return;
		}

		let from=new Date(date_from);
		let to=new Date(date_to);

		var fromDate = new Date(
		from.getFullYear(),
		from.getMonth(),
		from.getDate(),
		from.getHours(),
		from.getMinutes(),
		from.getSeconds()
		);
		var fromyyyy = fromDate.getFullYear().toString();                                    
		var frommm = (fromDate.getMonth()+1).toString();//getMonth() is zero-based

		var toDate = new Date(
		to.getFullYear(),
		to.getMonth(),
		to.getDate(),
		to.getHours(),
		to.getMinutes(),
		to.getSeconds()
		);
		var toyyyy = toDate.getFullYear().toString();                                    
		var tomm = (toDate.getMonth()+1).toString();//getMonth() is zero-based
		
		let diff=(12-(frommm*1))+(tomm*1);
		//alert(diff);

		if(diff>11 && toyyyy != fromyyyy){
			alert('Maximum 12 months allowed');
			return;
		}

		let params=this.getParams();
		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#budgetsummarymatrix').html(response.data);
			//$('#budgetandcostingcomparisonTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
	

}
window.MsBudgetSummary=new MsBudgetSummaryController(new MsBudgetSummaryModel());