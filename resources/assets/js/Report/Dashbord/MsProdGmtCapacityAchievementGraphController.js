let MsProdGmtCapacityAchievementGraphModel = require('./MsProdGmtCapacityAchievementGraphModel');
require('./../../datagrid-filter.js');

class MsProdGmtCapacityAchievementGraphController {
	constructor(MsProdGmtCapacityAchievementGraphModel)
	{
		this.MsProdGmtCapacityAchievementGraphModel = MsProdGmtCapacityAchievementGraphModel;
		this.formId='capacityachivmentgraphFrm';
		this.dataTable='#capacityachivmentgraphTbl';
		this.route=msApp.baseUrl()+"/capacityachivmentgraph"
	}
	
	get(route){
		var is_amount=0;
		if(route=='getgraphqty'){
		var title='Sewing Central Plan On Qty';
		is_amount=0;
		}
		else if(route=='getgraphqtycut'){
		var title='Cutting Central Plan On Qty';
		is_amount=0;
		}
		else if(route=='getgraphqtysp'){
		var title='Screen Print Central Plan On Qty';
		is_amount=0;
		}

		else if(route=='getgraphqtyemb'){
		var title='Embroidery Central Plan On Qty';
		is_amount=0;
		}
		else if(route=='getgraphamount')
		{
		var title='Sewing Central Plan On Amount';
		is_amount=1;
		}
		else if( route=='getgraphamountcut')
		{
		var title='Cutting Central Plan On Amount';
		is_amount=1;
	    }
	    else if(route=='getgraphamountsp')
		{
		var title='Screen Print Central Plan On Amount';
		is_amount=1;
	    }
	    else if(route=='getgraphamountemb')
		{
		var title='Embroidery Central Plan On Amount';
		is_amount=1;
	    }
		
		var p = $('#gmtcapgraphwindowcontainerlayout').layout('panel', 'center').panel('setTitle', title);
		let params={};
		let date_from=$('#capacityachivmentgraphFrm  [name=date_from]').val();
		let date_to=$('#capacityachivmentgraphFrm  [name=date_to]').val();
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


		
		let d= axios.get(this.route+"/"+route,{params})
		.then(function (response) {
			if(route=='getgraphsewmintprod'){
			   MsProdGmtCapacityAchievementGraph.createChartMint(response);
			}
			else{
				MsProdGmtCapacityAchievementGraph.createChart(response,is_amount);
			}
		})
		.catch(function (error) {
		console.log(error);
		});
	}

	createChart(response,is_amount){
		var gmtcapgraphwindowcontainerlayoutcenter = document.getElementById('gmtcapgraphwindowcontainerlayoutcenter');
		gmtcapgraphwindowcontainerlayoutcenter.innerHTML = '';
		$.each(response.data, function(key, value) {
			var labels = value.map(function(e) {
			return e.name;
			});
			var caps = value.map(function(e,index) {
			return e.cap;
			});
			var boks = value.map(function(e,index) {
			return e.bok;
			});
			var prods = value.map(function(e,index) {
			return e.prod;
			});

			var exfs = value.map(function(e,index) {
			return e.exf;
			});

			
			var containerid='gmtcapgraphwindowcontainer'+key;
			$('#gmtcapgraphwindowcontainerlayoutcenter').append('<div id="'+containerid+'"></div>');
			$('#'+containerid).append('<h1 style="background:#ccc ">'+key+' (Central Plan)</h1>');
            var canvasid='gmtcapgraphwindowcontainercom'+key;
			$('#'+containerid).append('<canvas id="'+canvasid+'"><canvas>');
			var ctx1 = $("#"+canvasid).get(0).getContext("2d");
					var mixedChart= new Chart(ctx1, {
					type: 'bar',
					data: {
						datasets: [
							{
								label: 'Capacity',
								data: caps,
								borderColor:'rgba(255, 0, 0, 1)',
								borderWidth:1,
								type: 'line'
							},
							{
								label: 'Target',
								backgroundColor: 'rgba(124, 31, 24, 1)',
								data: boks
							},
							{
								label: 'Produced',
								backgroundColor: 'rgba(108, 124, 158, 1)',
								data: prods
							}, 
							{
								label: 'Delivery',
								backgroundColor: 'rgba(178, 113, 49, 1)',
								data: exfs
							}
						],
						labels: labels
					},
					options: {
						scales: {
							yAxes: [{
								ticks: {
									beginAtZero: true
								}
							}]
						},
						title: {
							display: true,
							text: 'Central Plan ('+key+')'
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

	createChartMint(response){
		var gmtcapgraphwindowcontainerlayoutcenter = document.getElementById('gmtcapgraphwindowcontainerlayoutcenter');
		gmtcapgraphwindowcontainerlayoutcenter.innerHTML = '';
		$.each(response.data, function(key, value) {
			var labels = value.map(function(e) {
			return e.name;
			});
			var caps = value.map(function(e,index) {
			return e.cap;
			});
			var boks = value.map(function(e,index) {
			return e.bok;
			});
			var prods = value.map(function(e,index) {
			return e.prod;
			});

			/*var exfs = value.map(function(e,index) {
			return e.exf;
			});*/

			
			var containerid='gmtcapgraphwindowcontainer'+key;
			$('#gmtcapgraphwindowcontainerlayoutcenter').append('<div id="'+containerid+'"></div>');
			$('#'+containerid).append('<h1 style="background:#ccc ">'+key+' (Central Plan)</h1>');
            var canvasid='gmtcapgraphwindowcontainercom'+key;
			$('#'+containerid).append('<canvas id="'+canvasid+'"><canvas>');
			var ctx1 = $("#"+canvasid).get(0).getContext("2d");
					var mixedChart= new Chart(ctx1, {
					type: 'bar',
					data: {
						datasets: [
							{
								label: 'Capacity',
								data: caps,
								borderColor:'rgba(255, 0, 0, 1)',
								borderWidth:1,
								type: 'line'
							},
							{
								label: 'Actual Tgt.',
								backgroundColor: 'rgba(178, 113, 49, 1)',
								data: boks
							},
							{
								label: 'Produced',
								backgroundColor: 'rgba(108, 124, 158, 1)',
								data: prods
							}, 
							/*{
								label: 'Delivery',
								backgroundColor: 'rgba(178, 113, 49, 1)',
								data: exfs
							}*/
						],
						labels: labels
					},
					options: {
						scales: {
							yAxes: [{
								ticks: {
									beginAtZero: true
								}
							}]
						},
						title: {
							display: true,
							text: 'Central Plan ('+key+')'
						},
						tooltips: {
							callbacks: {
								label: function(tooltipItem, data) {
									var label = data.datasets[tooltipItem.datasetIndex].label || '';							
									return label + " Minute:  " + Number(tooltipItem.yLabel).toFixed(0).replace(/./g, function(c, i, a) {
									return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
									});
									
									
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
window.MsProdGmtCapacityAchievementGraph=new MsProdGmtCapacityAchievementGraphController(new MsProdGmtCapacityAchievementGraphModel());