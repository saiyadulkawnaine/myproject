require('./../../datagrid-filter.js');
let MsYarnPurchaseRateTrendModel = require('./MsYarnPurchaseRateTrendModel');

class MsYarnPurchaseRateTrendController {
	constructor(MsYarnPurchaseRateTrendModel)
	{
		this.MsYarnPurchaseRateTrendModel = MsYarnPurchaseRateTrendModel;
		this.formId='yarnpurchaseratetrendFrm';
		this.dataTable='#yarnpurchaseratetrendTbl';
		this.route=msApp.baseUrl()+"/yarnpurchaseratetrend/getdata";
	}

	getParams(){
		let params={};
		params.date_from = $('#yarnpurchaseratetrendFrm  [name=date_from]').val();
		params.date_to = $('#yarnpurchaseratetrendFrm  [name=date_to]').val();
		return params;
	}

	get()
	{
		let params=this.getParams();
		if(!params.date_from && !params.date_to){
			alert('Select A Date Range First');
			return;
		}
		let d= axios.get(this.route,{params})
		.then(function (response) {
			$('#yarnpurchaseratetrendTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

		let date_from = new Date(params.date_from)
        let formatted_date_from = date_from.getDate() + "-" + msApp.months[date_from.getMonth()] + "-" + date_from.getFullYear();
        let date_to = new Date(params.date_to)
        let formatted_date_to = date_to.getDate() + "-" + msApp.months[date_to.getMonth()] + "-" + date_to.getFullYear();
		var title='Yarn Purchase Rate Trend Report : &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp From '+formatted_date_from+' &nbsp&nbspTo &nbsp&nbsp'+formatted_date_to;
		var p = $('#yarnpurchaseratetrendPanel').layout('panel', 'center').panel('setTitle', title);
		
	}


	showGrid(data)
	{
		var dg = $(this.dataTable);
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			nowrap:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
}
window.MsYarnPurchaseRateTrend=new MsYarnPurchaseRateTrendController(new MsYarnPurchaseRateTrendModel());
MsYarnPurchaseRateTrend.showGrid([]);