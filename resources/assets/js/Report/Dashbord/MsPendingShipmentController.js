let MsPendingShipmentModel = require('./MsPendingShipmentModel');
require('./../../datagrid-filter.js');
class MsPendingShipmentController {
	constructor(MsPendingShipmentModel)
	{
		this.MsPendingShipmentModel = MsPendingShipmentModel;
		this.formId='pendingShipmentFrm';
		this.dataTable='#pendingShipmentTbl';
		this.route=msApp.baseUrl()+"/pendingshipment/getdata"
	}
	
	get(){
		let params={};
		params.company_id = $('#pendingShipmentFrm  [name=company_id]').val();
		params.buyer_id = $('#pendingShipmentFrm  [name=buyer_id]').val();
		params.style_ref = $('#pendingShipmentFrm  [name=style_ref]').val();
		params.job_no = $('#pendingShipmentFrm  [name=job_no]').val();
		params.date_from = $('#pendingShipmentFrm  [name=pending_date_from]').val();
		params.date_to = $('#pendingShipmentFrm  [name=pending_date_to]').val();
		params.order_status = $('#pendingShipmentFrm  [name=order_status]').val();
		let d= axios.get(this.route,{params})
		.then(function (response) {
			$('#pendingShipmentTbl').datagrid('loadData', response.data);
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
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				var tCartonQty=0;
				var tShipQty=0;
				var tShipValue=0;
				var tShipBalance=0;
				var tShipBalanceValue=0;
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				tCartonQty+=data.rows[i]['carton_qty'].replace(/,/g,'')*1;
				tShipQty+=data.rows[i]['ship_qty'].replace(/,/g,'')*1;
				tShipValue+=data.rows[i]['ship_value'].replace(/,/g,'')*1;
				tShipBalance+=data.rows[i]['ship_balance'].replace(/,/g,'')*1;
				tShipBalanceValue+=data.rows[i]['ship_balance_value'].replace(/,/g,'')*1;
				}
				tRate=(tAmout/tQty);
				$(this).datagrid('reloadFooter', [
					{ 
						qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						carton_qty: tCartonQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						ship_qty: tShipQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						ship_value: tShipValue.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						ship_balance: tShipBalance.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						ship_balance_value: tShipBalanceValue.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
	/*pendingshipmentWindow(flie_src){
		    this.get();
			$('#pendingShipmentWindow').window('open');
	}*/
	imageWindow(flie_src){
		var output = document.getElementById('dashbordReportImageWindowoutput');
		var fp=msApp.baseUrl()+"/images/"+flie_src;
		output.src =  fp;
		$('#dashbordReportImageWindow').window('open');
	}
	
	formatimage(value,row)
	{
        return '<img href="javascript:void(0)" width="15"  height="15" src="'+msApp.baseUrl()+'/images/'+row.flie_src+'" onClick="MsPendingShipment.imageWindow('+'\''+row.flie_src+'\''+')"/>';
	}

	formatSaleOrder(value,row)
	{
        return '<a href="javascript:void(0)" onClick="MsPendingShipment.detailsSaleOrderWindow('+row.id+')">'+row.qty+'</a>';
	}
}
window.MsPendingShipment=new MsPendingShipmentController(new MsPendingShipmentModel());
MsPendingShipment.showGrid({rows :{}});