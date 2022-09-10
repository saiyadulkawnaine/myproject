let MsProdGmtCapacityShipdateGraphModel = require('./MsProdGmtCapacityShipdateGraphModel');

require('./../../datagrid-filter.js');

class MsProdGmtCapacityShipdateGraphController {
	constructor(MsProdGmtCapacityShipdateGraphModel)
	{
		this.MsProdGmtCapacityShipdateGraphModel = MsProdGmtCapacityShipdateGraphModel;
		this.formId='capacityshipdategraphFrm';
		this.dataTable='#capacityshipdategraphTbl';
		this.route=msApp.baseUrl()+"/capacityshipdategraph"
	}
	
	get(route){
		
		
		var p = $('#gmtcapshipgraphwindowcontainerlayout').layout('panel', 'center').panel('setTitle', 'Capacity & Shipdate');
		let params={};
		let date_from=$('#capacityshipdategraphFrm  [name=date_from]').val();
		let date_to=$('#capacityshipdategraphFrm  [name=date_to]').val();
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
			MsProdGmtCapacityShipdateGraph.createChart(response);
		})
		.catch(function (error) {
		console.log(error);
		});
	}

	

	createChart(response){
		var gmtcapgraphwindowcontainerlayoutcenter = document.getElementById('gmtcapshipgraphwindowcontainerlayoutcenter');
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
			/*var prods = value.map(function(e,index) {
			return e.prod;
			});*/

			/*var exfs = value.map(function(e,index) {
			return e.exf;
			});*/

			
			var containerid='gmtcapshipgraphwindowcontainer'+key;
			$('#gmtcapshipgraphwindowcontainerlayoutcenter').append('<div id="'+containerid+'"></div>');
			$('#'+containerid).append('<h1 style="background:#ccc ">'+key+' (Capacity & Order Booked)</h1>');
            var canvasid='gmtcapshipgraphwindowcontainercom'+key;
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
								label: 'Order Booked.',
								backgroundColor: 'rgba(2, 20, 68, 1)',
								data: boks
							},
							/*{
								label: 'Produced',
								backgroundColor: 'rgba(108, 124, 158, 1)',
								data: prods
							},*/ 
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
							text: 'Capacity & Order Booked ('+key+')'
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
window.MsProdGmtCapacityShipdateGraph=new MsProdGmtCapacityShipdateGraphController(new MsProdGmtCapacityShipdateGraphModel());