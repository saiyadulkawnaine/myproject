let MsCentralBudgetModel = require('./MsCentralBudgetModel');
require('./../../datagrid-filter.js');
class MsCentralBudgetController {
	constructor(MsCentralBudgetModel)
	{
		this.MsCentralBudgetModel = MsCentralBudgetModel;
		this.formId='centralbudgetFrm';
		this.dataTable='#centralbudgetTbl';
		this.route=msApp.baseUrl()+"/centralbudgets"
	}
	
	get(){
		let params={};
		
		params.date_from = $('#centralbudgetFrm  [name=date_from]').val();
		params.date_to = $('#centralbudgetFrm  [name=date_to]').val();
		if(params.date_from==''){
			alert('Please Select Date Range')
			return;
		}
		if(params.date_to==''){
			alert('Please Select Date Range')
			return;
		}
		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#centralbudgetwindowcontainerlayoutcenter').html(response.data)
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	getDetail(date_from,date_to,company_id){
		let params={};
		var company=company_id.split('-');
		params.date_from =date_from;
		params.date_to = date_to;
		params.company_id = company[0];
		params.profitcenter_id = company[1];
		if(params.date_from==''){
			alert('Please Select Date Range')
			return;
		}
		if(params.date_to==''){
			alert('Please Select Date Range')
			return;
		}
		if(params.company_id==''){
			alert('Please Select Company')
			return;
		}
		let d= axios.get(this.route+'/getdetail',{params})
		.then(function (response) {
			$('#centralbudgetdetailwindowcontainer').html(response.data)
			$('#centralbudgetDetailwindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	getBudVsAcl(){
		let params={};
		
		params.date_from = $('#centralbudgetFrm  [name=date_from]').val();
		params.date_to = $('#centralbudgetFrm  [name=date_to]').val();
		if(params.date_from==''){
			alert('Please Select Date Range')
			return;
		}
		if(params.date_to==''){
			alert('Please Select Date Range')
			return;
		}
		let d= axios.get(this.route+'/getdatabudvsacl',{params})
		.then(function (response) {
			$('#centralbudgetwindowcontainerlayoutcenter').html(response.data)
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	getDetailBudVsAcl(date_from,date_to,company_id){
		let params={};
		var company=company_id.split('-');
		params.date_from =date_from;
		params.date_to = date_to;
		params.company_id = company[0];
		params.profitcenter_id = company[1];
		if(params.date_from==''){
			alert('Please Select Date Range')
			return;
		}
		if(params.date_to==''){
			alert('Please Select Date Range')
			return;
		}
		if(params.company_id==''){
			alert('Please Select Company')
			return;
		}
		let d= axios.get(this.route+'/getdetailbudvsacl',{params})
		.then(function (response) {
			$('#centralbudgetdetailwindowcontainer').html('')
			$('#centralbudgetdetailwindowcontainer').html(response.data)
			$('#centralbudgetDetailwindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	remarksWindow(data){
		$('#centralbudgetremarkswindowcontainer').html(data)
		$('#centralbudgetRemarkswindow').window('open');
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
}
window.MsCentralBudget=new MsCentralBudgetController(new MsCentralBudgetModel());

