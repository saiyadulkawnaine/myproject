//require('./jquery.easyui.min.js');
let MsProjectionProgressModel = require('./MsProjectionProgressModel');
require('./datagrid-filter.js');

class MsProjectionProgressController {
	constructor(MsProjectionProgressModel)
	{
		this.MsProjectionProgressModel = MsProjectionProgressModel;
		this.formId='projectionFrm';
		this.dataTable='#projetionprogressTbl';
		this.route=msApp.baseUrl()+"/projectionprogress/getdata"
	}

	get(){
		let params={};
		params.company_id = $('#projectionFrm  [name=company_id]').val();
		params.buyer_id = $('#projectionFrm  [name=buyer_id]').val();
		params.style_ref = $('#projectionFrm  [name=style_ref]').val();
		params.proj_no = $('#projectionFrm  [name=proj_no]').val();
		params.date_from = $('#projectionFrm  [name=date_from]').val();
		params.date_to = $('#projectionFrm  [name=date_to]').val();
		let d= axios.get(this.route,{params})
		.then(function (response) {
			//MsProjectionProgress.showGrid(response.data.datad)
			$('#projetionprogressTbl').datagrid('loadData', response.data.datad);
				var labels = response.data.month.map(function(e) {
				return e.name;
				});
				var data = response.data.month.map(function(e) {
				return e.qty;
				});;
				var datam = response.data.month.map(function(e) {
				return e.amount;
				});

				var ctx = canvas.getContext('2d');
				var config = {
				type: 'bar',
				data: {
				labels: labels,
				datasets: [{
				label: 'Projected Qty',
				data: data,
				backgroundColor: 'rgba(255, 0, 0, 0.80)'
				}]
				}
				};
				var chart = new Chart(ctx, config);
				var ctxm = canvasam.getContext('2d');
				var configm = {
				type: 'bar',
				data: {
				labels: labels,
				datasets: [{
				label: 'Projected Amount',
				data: datam,
				backgroundColor: 'rgba(255, 0, 0, 0.80)'
				}]
				}
				};
				var chartt = new Chart(ctxm, configm);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(data)
	{

		let self=this;
		var dg = $(this.dataTable);
		dg.datagrid({
			//method:'get',
			border:false,
			singleSelect:true,
			//queryParams:data,
			fit:true,
			showFooter:true,
			//data:data,
			//url:this.route,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

}
window.MsProjectionProgress=new MsProjectionProgressController(new MsProjectionProgressModel());
MsProjectionProgress.showGrid({rows :{}});
