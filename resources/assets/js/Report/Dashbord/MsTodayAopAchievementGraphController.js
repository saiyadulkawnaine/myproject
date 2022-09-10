let MsTodayDyeingAchivementGraphModel = require('./MsTodayDyeingAchivementGraphModel');
require('./../../ext.chart.js');

class MsTodayAopAchivementGraphModel {
	constructor(MsTodayAopAchivementGraphModel)
	{
		this.MsTodayAopAchivementGraphModel = MsTodayAopAchivementGraphModel;
		this.formId='todayaopachivmentgraphFrm';
		this.dataTable='#todayaopachivmentgraphTbl';
		this.route=msApp.baseUrl()+"/todayaopachivementgraph"
	}

	
	get(){
		let params={};
		let date_to=$('#todayaopachivmentgraphFrm  [name=date_to]').val();
		params.date_to = date_to
		if(date_to==''){
			alert('Please Select a Date ');
			return;
		}
		let d= axios.get(this.route+"/getgraph",{params})
		.then(function (response) {
			MsTodayAopAchievementGraph.createChart(response.data.dayCusQtydatas,1,0,'Today Quantity');
			MsTodayAopAchievementGraph.createChart(response.data.dayCusAmtdatas,2,1,'Today Amount');

			MsTodayAopAchievementGraph.createChart(response.data.monCusQtydatas,3,0,'Current Month Quantity');
			MsTodayAopAchievementGraph.createChart(response.data.monCusAmtdatas,4,1,'Current Month Amount');

			MsTodayAopAchievementGraph.createChart(response.data.dayMktQtydatas,5,0,'Today Quantity');
			MsTodayAopAchievementGraph.createChart(response.data.dayMktAmtdatas,6,1,'Today Amount');

			MsTodayAopAchievementGraph.createChart(response.data.monMktQtydatas,7,0,'Current Month Quantity');
			MsTodayAopAchievementGraph.createChart(response.data.monMktAmtdatas,8,1,'Current Month Amount');
		})
		.catch(function (error) {
		console.log(error);
		});
	}


	

	createChart(data,divid,is_amount,title_tex){
		//var todaysewingachivementgraphwindowcontainerlayoutwest = document.getElementById('todaysewingachivementgraphwindowcontainerlayoutwest');
		//todaysewingachivementgraphwindowcontainerlayoutwest.innerHTML = '';
		//$.each(response.data.graphdata, function(key, value) {
			/*var title_tex='Quantity';
			if(is_amount){
				 title_tex='Amount';
			}
			else{
                 title_tex='Quantity';
			}*/
			Chart.plugins.register({
			afterDatasetsUpdate: function(chart) {
			Chart.helpers.each(chart.getDatasetMeta(0).data, function(rectangle, index) {
			rectangle._view.width = rectangle._model.height = 5;
			});
			Chart.helpers.each(chart.getDatasetMeta(1).data, function(rectangle, index) {
			rectangle._view.width = rectangle._model.height = 10;
			});
			},
			})
			var labels = data.graphdata.map(function(e,index) {
			return e.name;
			});
			var tgts = data.graphdata.map(function(e,index) {
			return e.tgt;
			});
			var rcvs = data.graphdata.map(function(e,index) {
			return e.rcv;
			});
			var dlvs = data.graphdata.map(function(e,index) {
			return e.dlv;
			});
			
			document.getElementById('divtodayaopachivmentgraph_'+divid).innerHTML='';
            var canvasid='canvastodayaopachivmentgraph_'+divid;
            $('#divtodayaopachivmentgraph_'+divid).append('<canvas id="'+canvasid+'"><canvas>');
            $('#divtodayaopachivmentgraph_'+divid).append(data.htmldata.substr(1).slice(0, -1));

			var ctx1 = $("#"+canvasid).get(0).getContext("2d");
					var mixedChart= new Chart(ctx1, {
					type: 'horizontalBar',
					data: {
						datasets: [
							
							{
								label: 'Delv',
								data: dlvs,
								backgroundColor:'rgba(163, 0, 23, 1)',
								borderWidth:1,
								yAxisID: "bar-y-axis1",
							},
							{
								label: 'G. Rcv',
								data: rcvs,
								backgroundColor:'rgba(47, 248, 242, 1)',
								borderWidth:1,
								yAxisID: "bar-y-axis1",
							},
							{
								label: 'Tgts',
								data: tgts,
								backgroundColor:'rgba(2, 20, 68, 1)',
								borderWidth:1,
								yAxisID: "bar-y-axis1",
							},
						],
						labels: labels
					},
					options: {
						responsive: true,
                        maintainAspectRatio: false,
						scales: {
							yAxes: [{
								stacked: true,
								id: "bar-y-axis1",
								barThickness: 20,
							}],
							xAxes: [{
								stacked: false,
								ticks: {
								beginAtZero: true
								},
							}]
						},
						title: {
							display: true,
							text: title_tex,
						},
						tooltips: {
							callbacks: {
								label: function(tooltipItem, data) {
									var label = data.datasets[tooltipItem.datasetIndex].label || '';
									if(is_amount){
										return label + " Amount: BDT " + Number(tooltipItem.xLabel).toFixed(2).replace(/./g, function(c, i, a) {
										return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
										});
									}
									else{
										return label + " Qty: " + Number(tooltipItem.xLabel).toFixed(2).replace(/./g, function(c, i, a) {
										return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
										});
									}
																
								}
							}
						}
					}
				});
		//});

		//$('#graphinfo1').html(response.data.tempdata.substr(1).slice(0, -1));
	}

	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
}	
window.MsTodayAopAchievementGraph=new MsTodayAopAchivementGraphModel(new MsTodayAopAchivementGraphModel());