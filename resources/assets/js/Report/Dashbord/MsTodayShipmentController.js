let MsTodayShipmentModel = require('./MsTodayShipmentModel');
require('./../../datagrid-filter.js');

class MsTodayShipmentController {
	constructor(MsTodayShipmentModel)
	{
		this.MsTodayShipmentModel = MsTodayShipmentModel;
		this.formId='todayShipmentFrm';
		this.dataTable='#todayShipmentTbl';
		this.route=msApp.baseUrl()+"/todayShipment/getdata"
	}
	
	get(){
		let params={};
		params.company_id = $('#todayShipmentFrm  [name=company_id]').val();
		params.buyer_id = $('#todayShipmentFrm  [name=buyer_id]').val();
		params.style_ref = $('#todayShipmentFrm  [name=style_ref]').val();
		params.job_no = $('#todayShipmentFrm  [name=job_no]').val();
		params.date_from = $('#todayShipmentFrm  [name=today_date_from]').val();
		params.date_to = $('#todayShipmentFrm  [name=today_date_to]').val();
		params.order_status = $('#todayShipmentFrm  [name=order_status]').val();
		
		let d= axios.get(this.route,{params})
		.then(function (response) {
			$('#todayShipmentTbl').datagrid('loadData', response.data);
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
			nowrap:false,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				//var tTotal=0;
				//var tProfit=0;
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				//tTotal+=data.rows[i]['total_amount'].replace(/,/g,'')*1;
				//tProfit+=data.rows[i]['total_profit'].replace(/,/g,'')*1;
				}
				//var tProfitPer=0;
				var tRate=0;
				//if(tAmout){
					//tProfitPer=(tProfit/tAmout)*100;
				//}
				if(tQty){
				   tRate=(tAmout/tQty);	
				}
				
				
				$(this).datagrid('reloadFooter', [
					{ 
						qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						//total_amount: tTotal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						//total_profit: tProfit.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						//total_profit_per: tProfitPer.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
				/*if(!tEmb){
					$(this).datagrid('hideColumn', 'emb_amount');
				}*/
				
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
	/*todayshipmentWindow(){
		    this.get();
			$('#todayShipmentWindow').window('open');
	}*/
	imageWindow(flie_src){
		var output = document.getElementById('dashbordReportImageWindowoutput');
		var fp=msApp.baseUrl()+"/images/"+flie_src;
		output.src =  fp;
		$('#dashbordReportImageWindow').window('open');
	}
	
	formatimage(value,row)
	{
        return '<img href="javascript:void(0)" width="15"  height="15" src="'+msApp.baseUrl()+'/images/'+row.flie_src+'" onClick="MsTodayShipment.imageWindow('+'\''+row.flie_src+'\''+')"/>';
	}

}	
window.MsTodayShipment=new MsTodayShipmentController(new MsTodayShipmentModel());
MsTodayShipment.showGrid({rows :{}});

