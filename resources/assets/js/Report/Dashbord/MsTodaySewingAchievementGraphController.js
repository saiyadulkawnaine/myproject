let MsTodaySewingAchivementGraphModel = require('./MsTodaySewingAchivementGraphModel');
require('./../../datagrid-filter.js');
require('./../../ext.chart.js');

class MsTodaySewingAchievementGraphController {
	constructor(MsTodaySewingAchivementGraphModel)
	{
		this.MsTodaySewingAchivementGraphModel = MsTodaySewingAchivementGraphModel;
		this.formId='todaysewingachivmentgraphFrm';
		this.dataTable='#todaysewingachivmentgraphTbl';
		this.route=msApp.baseUrl()+"/todaysewingachivementgraph"
	}

	getLine(){
        let company_id=$('#todaysewingachivmentgraphFrm  [name=company_id]').val();
        let date_to=$('#todaysewingachivmentgraphFrm  [name=date_to]').val();
		let params={};
		params.company_id=company_id;
		params.date_to=date_to;
		//let line=msApp.getJson('getline',data)
		const instance = axios.create();
		let line= instance.get(this.route+"/getline",{params})
		.then(function (response) {
			    $('select[name="recall_line_id"]').empty();
				$('select[name="recall_line_id"]').append('<option value="">-Select-</option>');
                $.each(response.data, function(key, value) {
						$('select[name="recall_line_id"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
					
                });
		})
		.catch(function (error) {
			console.log(error);
		});
		return line;

	}
	
	get(){
		let params={};
		let company_id=$('#todaysewingachivmentgraphFrm  [name=company_id]').val();
		let date_to=$('#todaysewingachivmentgraphFrm  [name=date_to]').val();
		params.company_id = company_id
		params.date_to = date_to

		if(company_id==''){
			alert('Please Select a Company ');
			return;
		}

		if(date_to==''){
			alert('Please Select a Date ');
			return;
		}
		let d= axios.get(this.route+"/getgraph",{params})
		.then(function (response) {
			MsTodaySewingAchievementGraph.createChart(response);
		})
		.catch(function (error) {
		console.log(error);
		});
	}


	autoget(){
		let params={};
		let company_id=$('#todaysewingachivmentgraphFrm  [name=company_id]').val();
		let date_to=$('#todaysewingachivmentgraphFrm  [name=date_to]').val();
        let auto_update=$('#todaysewingachivmentgraphFrm  [name=auto_update]').val();
		params.company_id = company_id
		params.date_to = date_to

		if(company_id==''){
			return;
		}
		if(date_to==''){
			return;
		}
		if(auto_update==0){
			return;
		}
		const instance = axios.create();
		
		let d= instance.get(this.route+"/getgraph",{params})
		.then(function (response) {
			MsTodaySewingAchievementGraph.createChart(response);
		})
		.catch(function (error) {
		console.log(error);
		});
	}

	createChart(response){
		var todaysewingachivementgraphwindowcontainerlayoutwest = document.getElementById('todaysewingachivementgraphwindowcontainerlayoutwest');
		todaysewingachivementgraphwindowcontainerlayoutwest.innerHTML = '';
		$.each(response.data.graphdata, function(key, value) {
			Chart.plugins.register({
			afterDatasetsUpdate: function(chart) {
			Chart.helpers.each(chart.getDatasetMeta(0).data, function(rectangle, index) {
			rectangle._view.width = rectangle._model.height = 10;
			});
			/*Chart.helpers.each(chart.getDatasetMeta(1).data, function(rectangle, index) {
			rectangle._view.width = rectangle._model.height = 20;
			});*/
			},
			})
			
			var labels = value.map(function(e) {
			return e.name;
			});
			var tgts = value.map(function(e,index) {
			return e.tgt;
			});
			var prods = value.map(function(e,index) {
			return e.prod;
			});
			
			var containerid='todaysewingachivementgraphwindowcontainer';
			$('#todaysewingachivementgraphwindowcontainerlayoutwest').append('<div id="'+containerid+'" style="width:100%;height:90%; padding-bottom:20px"></div><div id="graphinfo1" class="flex-container" style="height:200px; margin-top:30px"></div>');
			$('#'+containerid).append('<h1 style=" font-size: 18px;font-weight: bold; border-bottom:none;background:#021344;color:#ffffff;">'+key+'</h1>');

            var canvasid='todaysewingachivementgraphwindowcontainercom';
			$('#'+containerid).append('<canvas id="'+canvasid+'"><canvas>');
			var ctx1 = $("#"+canvasid).get(0).getContext("2d");
					var mixedChart= new Chart(ctx1, {
					type: 'horizontalBar',
					data: {
						datasets: [
							
							{
								label: 'Earned CM',
								data: prods,
								backgroundColor:'rgba(47, 248, 242, 1)',
								borderWidth:1,
								yAxisID: "bar-y-axis1",
							},
							{
								label: 'Tgt. CM',
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
							display: false,
							text: 'Today Sewing Performance ('+key+')'
						},
						tooltips: {
							callbacks: {
								label: function(tooltipItem, data) {
									var label = data.datasets[tooltipItem.datasetIndex].label || '';
									return label + " Amount: $ " + Number(tooltipItem.xLabel).toFixed(2).replace(/./g, function(c, i, a) {
										return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
									});							
								}
							}
						}
					}
				});
		});

		$('#graphinfo1').html(response.data.tempdata.substr(1).slice(0, -1));
	}

	gettwo(){
		$('#todaysewingachivmentgraphFrm  [name=line_id]').val('');
		$('#todaysewingachivmentgraphFrm  [name=auto_update]').val(0)
		let params={};
		let company_id=$('#todaysewingachivmentgraphFrm  [name=company_id]').val();
		let date_to=$('#todaysewingachivmentgraphFrm  [name=date_to]').val();
        let auto_update=$('#todaysewingachivmentgraphFrm  [name=auto_update]').val();
        let recall_line_id=$('#todaysewingachivmentgraphFrm  [name=recall_line_id]').val();
        let line_id='';
		params.company_id = company_id
		params.date_to = date_to
		params.line_id = line_id
		params.recall_line_id = recall_line_id

		if(company_id==''){
			return;
		}
		if(date_to==''){
			return;
		}
		/*if(auto_update==0){
			return;
		}*/
		const instance = axios.create();
		
		let d= instance.get(this.route+"/getgraphtwo",{params})
		.then(function (response) {
			MsTodaySewingAchievementGraph.createCharttwo(response);
		})
		.catch(function (error) {
			$('#todaysewingachivmentgraphFrm  [name=line_id]').val('')
		console.log(error);
		});
	}

	autogettwo(){
		let params={};
		let company_id=$('#todaysewingachivmentgraphFrm  [name=company_id]').val();
		let date_to=$('#todaysewingachivmentgraphFrm  [name=date_to]').val();
        let auto_update=$('#todaysewingachivmentgraphFrm  [name=auto_update]').val();
        let line_id=$('#todaysewingachivmentgraphFrm  [name=line_id]').val();
		params.company_id = company_id
		params.date_to = date_to
		params.line_id = line_id

		if(company_id==''){
			return;
		}
		if(date_to==''){
			return;
		}
		if(auto_update==0){
			return;
		}
		const instance = axios.create();
		
		let d= instance.get(this.route+"/getgraphtwo",{params})
		.then(function (response) {
			MsTodaySewingAchievementGraph.createCharttwo(response);
		})
		.catch(function (error) {
			$('#todaysewingachivmentgraphFrm  [name=line_id]').val('')
		console.log(error);
		});
	}

	

	createCharttwo(response){
		//var mli=0;
		//i++;
		
		$.each(response.data.graphdata, function(key, value) {
			/*mli++;
			//alert(i)
			setTimeout(function() {*/
			$('#todaysewingachivmentgraphFrm  [name=line_id]').val(key);
			$('#todaysewingachivmentgraphFrm  [name=recall_line_id]').val(key);
			 
			var todaysewingachivementgraphwindowcontainerlayoutcenter = document.getElementById('todaysewingachivementgraphwindowcontainerlayoutcenter');
		    todaysewingachivementgraphwindowcontainerlayoutcenter.innerHTML = '';
			Chart.plugins.register({
			afterDatasetsUpdate: function(chart) {
			Chart.helpers.each(chart.getDatasetMeta(0).data, function(rectangle, index) {
			rectangle._view.width = rectangle._model.width = 10;
			});
			Chart.helpers.each(chart.getDatasetMeta(1).data, function(rectangle, index) {
			rectangle._view.width = rectangle._model.width = 20;
			});
			},
			})
			var linenames = value.map(function(e) {
			return e.linename;
			});
			var linechefs = value.map(function(e) {
			return e.linechef;
			});

			
			
			var labels = value.map(function(e) {
			return e.name;
			});
			var tgts = value.map(function(e,index) {
			return e.tgt;
			});
			var prods = value.map(function(e,index) {
			return e.prod;
			});
			
			var containerid='todaysewingachivementgraphwindowcontainertwo';
			$('#todaysewingachivementgraphwindowcontainerlayoutcenter').append('<div id="'+containerid+'" style="width:100%;height:65%; padding-bottom:20px"></div><div id="graphinfo" style="width:100%;height:35%;"></div>');
			$('#'+containerid).append('<h1 style=" font-size: 18px;font-weight: bold; border-bottom:none;background:#021344;color:#ffffff;cursor:pointer" onclick="MsTodaySewingAchievementGraph.lineDetail()">Line : '+linenames[0]+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Supervisor : '+linechefs[0]+'</h1>');
            var canvasid='todaysewingachivementgraphwindowcontainercomtwo';
			$('#'+containerid).append('<canvas id="'+canvasid+'"><canvas>');
			var ctx1 = $("#"+canvasid).get(0).getContext("2d");
					var mixedChart= new Chart(ctx1, {
					type: 'bar',
					data: {
						datasets: [
							
							{
								label: 'Earned CM',
								data: prods,
								backgroundColor:'rgba(47, 248, 242, 1)',
								borderWidth:1,
								xAxisID: "bar-x-axis1",
							},
							{
								label: 'Tgt. CM',
								data: tgts,
								backgroundColor:'rgba(2, 20, 68, 1)',
								borderWidth:1,
								xAxisID: "bar-x-axis1",
							},
						],
						labels: labels
					},
					options: {
						responsive: true,
                        maintainAspectRatio: false,
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
							display: false,
							text: 'Line : '+linenames[0]
						},
						tooltips: {
							callbacks: {
								label: function(tooltipItem, data) {
									var label = data.datasets[tooltipItem.datasetIndex].label || '';
									return label + " Amount: $ " + Number(tooltipItem.yLabel).toFixed(2).replace(/./g, function(c, i, a) {
										return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
									});							
								}
							}
						}
					}
				});
		});
		//}, 15000 * mli);
		$('#graphinfo').html(response.data.tempdata.substr(1).slice(0, -1));
		
	}

	lineDetail(){
		let line_id=$('#todaysewingachivmentgraphFrm  [name=line_id]').val();
		let company_id=$('#todaysewingachivmentgraphFrm  [name=company_id]').val();
		let date_to=$('#todaysewingachivmentgraphFrm  [name=date_to]').val();

		$('#todaysewingachivementgraphlinedetailWindow').window('open');
		let d= axios.get(msApp.baseUrl()+"/prodgmtlinewisehourly")
		.then(function (response) {
			$('#todaysewingachivementgraphlinedetailWindowContainer').html(response.data);
			$.parser.parse('#todaysewingachivementgraphlinedetailWindowContainer');
			$('#prodgmtlinewisehourlyFrm  [name=company_id]').val(company_id);
			$('#prodgmtlinewisehourlyFrm  [name=date_to]').val(date_to);
			$('#prodgmtlinewisehourlyFrm  [name=line_id]').val(line_id);
			//MsProdGmtLineWiseHourly.get();
		})
		.catch(function (error) {
			console.log(error);
		});

		d.then(function (response) {
			MsProdGmtLineWiseHourly.get();
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
window.MsTodaySewingAchievementGraph=new MsTodaySewingAchievementGraphController(new MsTodaySewingAchivementGraphModel());