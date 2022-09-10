let MsProdGmtAllAchievementGraphModel = require('./MsProdGmtAllAchievementGraphModel');
require('./../../datagrid-filter.js');

class MsProdGmtAllAchievementGraphController {
	constructor(MsProdGmtAllAchievementGraphModel)
	{
		this.MsProdGmtAllAchievementGraphModel = MsProdGmtAllAchievementGraphModel;
		this.formId='allcapacityachivmentgraphFrm';
		this.dataTable='#allcapacityachivmentgraphTbl';
		this.route=msApp.baseUrl()+"/allcapacityachivmentgraph"
	}
	
	get(route){
		var is_amount=0;
		if(route=='getgraphqty'){
		var title='Production on Qty';
		is_amount=0;
		}
		
		else if(route=='getgraphamount')
		{
		var title='Production on Amount';
		is_amount=1;
		}
		
		
		var p = $('#gmtallcapgraphwindowcontainerlayout').layout('panel', 'center').panel('setTitle', title);
		let params={};
		let date_from=$('#allcapacityachivmentgraphFrm  [name=date_from]').val();
		let date_to=$('#allcapacityachivmentgraphFrm  [name=date_to]').val();
		params.date_from = date_from
		params.date_to = date_to;

		if( date_from==''){
			alert('Please Select a date range ');
			return;
		}

		if(date_to==''){
			alert('Please Select a date range');
			return;
		}

		/*let from=new Date(date_from);
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

		if(diff>11 && toyyyy != fromyyyy){
			alert('Maximum 12 months allowed');
			return;
		}*/


		
		let d= axios.get(this.route+"/"+route,{params})
		.then(function (response) {
			MsProdGmtAllAchievementGraph.createChart(response,is_amount);
		})
		.catch(function (error) {
		console.log(error);
		});
	}

	createChart(response,is_amount){
		var gmtallcapgraphwindowcontainerlayoutcenter = document.getElementById('gmtallcapgraphwindowcontainerlayoutcenter');
		gmtallcapgraphwindowcontainerlayoutcenter.innerHTML = '';
			Chart.plugins.register({
			afterDatasetsUpdate: function(chart) {
				Chart.helpers.each(chart.getDatasetMeta(0).data, function(rectangle, index) {
				rectangle._view.width = rectangle._model.width = 30;
				});
				/*Chart.helpers.each(chart.getDatasetMeta(1).data, function(rectangle, index) {
				rectangle._view.width = rectangle._model.width = 20;
				});*/
				},
			})
		$.each(response.data, function(key, value) {
			var labels = value.map(function(e) {
			return e.name;
			});
			/*var caps = value.map(function(e,index) {
			return e.cap;
			});*/
			var boks = value.map(function(e,index) {
			return e.bok;
			});
			var prods = value.map(function(e,index) {
			return e.prod;
			});

			var exfs = value.map(function(e,index) {
			return e.exf;
			});

			
			var containerid='gmtallcapgraphwindowcontainer'+key;
			$('#gmtallcapgraphwindowcontainerlayoutcenter').append('<div id="'+containerid+'"></div>');
			$('#'+containerid).append('<h1 style="background:#ccc ">'+key+'</h1>');
            var canvasid='gmtallcapgraphwindowcontainercom'+key;
			$('#'+containerid).append('<canvas id="'+canvasid+'"><canvas>');
			var ctx1 = $("#"+canvasid).get(0).getContext("2d");
					var mixedChart= new Chart(ctx1, {
					type: 'bar',
					data: {
						datasets: [
							/*{
								label: 'Capacity',
								data: caps,
								borderColor:'rgba(255, 0, 0, 1)',
								borderWidth:1,
								type: 'line'
							},*/
							{
								label: 'Produced',
								backgroundColor:'rgba(47, 248, 242, 1)',
								data: prods,
								xAxisID: "bar-x-axis1",
							},
							{
								label: 'Target',
								backgroundColor:'rgba(2, 20, 68, 1)',
								data: boks,
								xAxisID: "bar-x-axis1",
							},
							/*, 
							{
								label: 'Delivery',
								backgroundColor: 'rgba(178, 113, 49, 1)',
								data: exfs
							}*/
						],
						labels: labels
					},
					options: {
						scales: {
							xAxes: [{
								stacked: true,
								id: "bar-x-axis1",
								barThickness: 70,
							}],
							yAxes: [{
								stacked: false,
								ticks: {
								beginAtZero: true
								},
							}]
						},
						title: {
							display: true,
							text: 'Production ('+key+')'
						},
						tooltips: {
							callbacks: {
								label: function(tooltipItem, data) {
									var label = data.datasets[tooltipItem.datasetIndex].label || '';							
									if(is_amount==1){
										return label + " Amount: $ " + Number(tooltipItem.yLabel).toFixed(2).replace(/./g, function(c, i, a) {
										return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
										});
									}
									else
									{
										return label+ " Qty: " + Number(tooltipItem.yLabel).toFixed(0).replace(/./g, function(c, i, a) {
										return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
										});
								    }
								}
							}
						}
					}
				});
		
		});
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
}	
window.MsProdGmtAllAchievementGraph=new MsProdGmtAllAchievementGraphController(new MsProdGmtAllAchievementGraphModel());