let MsFamListModel = require('./MsFamListModel');
require('./../../datagrid-filter.js');

class MsFamListController {
	constructor(MsFamListModel)
	{
		this.MsFamListModel = MsFamListModel;
		this.formId='famlistFrm';
		this.dataTable='#famlistTbl';
		this.route=msApp.baseUrl()+"/famlist/getdata"
	}
	
	get(){
		let params={};
		params.company_id = $('#famlistFrm  [name=company_id]').val();
		params.location_id = $('#famlistFrm  [name=location_id]').val();
		params.name = $('#famlistFrm  [name=name]').val();
		params.type_id = $('#famlistFrm  [name=type_id]').val();
		params.production_area_id = $('#famlistFrm  [name=production_area_id]').val();
		params.date_from = $('#famlistFrm  [name=date_from]').val();
		params.date_to = $('#famlistFrm  [name=date_to]').val();
		let d= axios.get(this.route,{params})
		.then(function (response) {

			$('#famlistTbl').datagrid('loadData', response.data);
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
			singleSelect:false,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var ttotal_cost=0;
				var tvendor_price=0;
				var llanded_price=0;
				var lcivil_cost=0;
				var lelectrical_cost=0;
				for(var i=0; i<data.rows.length; i++){
				ttotal_cost+=data.rows[i]['total_cost'].replace(/,/g,'')*1;
				//tvendor_price+=data.rows[i]['vendor_price'].replace(/,/g,'')*1;
				//llanded_price+=data.rows[i]['landed_price'].replace(/,/g,'')*1;
				//lmachanical_cost+=data.rows[i]['machanical_cost'].replace(/,/g,'')*1;
				//lcivil_cost+=data.rows[i]['civil_cost'].replace(/,/g,'')*1;
				//lelectrical_cost+=data.rows[i]['electrical_cost'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{total_cost: ttotal_cost.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}
				//{vendor_price:tvendor_price.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),landed_price:llanded_price.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),machanical_cost:lmachanical_cost.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),civil_cost:lcivil_cost.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),electrical_cost:lelectrical_cost.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),total_cost: ttotal_cost.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	assetticketpdf(asset_quantity_cost_id){
		window.open(msApp.baseUrl()+"/famlist/assetticket?asset_quantity_cost_id="+asset_quantity_cost_id);
	}

	multiassetticketpdf(asset_quantity_cost_id){
		let formObj=[];
		let i=0;
		$.each($('#famlistTbl').datagrid('getSelections'), function(idx, val){
			formObj[i]=val.asset_quantity_cost_id;
			i++;
		});
		var asset_quantity_cost_id=formObj.join(',');
		window.open(msApp.baseUrl()+"/famlist/assetticket?asset_quantity_cost_id="+asset_quantity_cost_id);
	}
	   
	formatAssetTicketPdf(value,row){
		return '<a href="javascript:void(0)" onClick="MsFamList.assetticketpdf('+row.asset_quantity_cost_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>PDF</span></a>';
	}

}
window.MsFamList = new MsFamListController(new MsFamListModel());
MsFamList.showGrid([]);