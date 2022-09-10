require('./../../../datagrid-filter.js');
let MsSubConDyeingDeliveryModel = require('./MsSubConDyeingDeliveryModel');

class MsSubConDyeingDeliveryController {
	constructor(MsSubConDyeingDeliveryModel)
	{
		this.MsSubConDyeingDeliveryModel = MsSubConDyeingDeliveryModel;
		this.formId='subcondyeingdeliveryFrm';
		this.dataTable='#subcondyeingdeliveryTbl';
		this.route=msApp.baseUrl()+"/subcondyeingdelivery";
	}

	getParams(){
		let params={};
		params.company_id = $('#subcondyeingdeliveryFrm  [name=company_id]').val();
		params.buyer_id = $('#subcondyeingdeliveryFrm  [name=buyer_id]').val();
		params.date_from = $('#subcondyeingdeliveryFrm  [name=date_from]').val();
		params.date_to = $('#subcondyeingdeliveryFrm  [name=date_to]').val();
		return params;
	}

	get()
	{
		let params=this.getParams();
		if(!params.date_from && !params.date_to){
			alert('Select A Date Range First');
			return;
		}
		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#subcondyeingdeliveryTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

		let date_from = new Date(params.date_from)
        let formatted_date_from = date_from.getDate() + "-" + msApp.months[date_from.getMonth()] + "-" + date_from.getFullYear();
        let date_to = new Date(params.date_to)
        let formatted_date_to = date_to.getDate() + "-" + msApp.months[date_to.getMonth()] + "-" + date_to.getFullYear();
		var title='Date Wise Dyeing Delivery : &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp From '+formatted_date_from+' &nbsp&nbspTo &nbsp&nbsp'+formatted_date_to;
		var p = $('#subcondyeingdeliverypanel').layout('panel', 'center').panel('setTitle', title);
	
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
				var dlv_qty=0;
				var avg_rate=0;
				var grey_used_qty=0;
				var amount=0;
				var amount_bdt=0;

				for(var i=0; i<data.rows.length; i++){
					dlv_qty+=data.rows[i]['dlv_qty'].replace(/,/g,'')*1;
					grey_used_qty+=data.rows[i]['grey_used_qty'].replace(/,/g,'')*1;
					amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
					amount_bdt+=data.rows[i]['amount_bdt'].replace(/,/g,'')*1;
				}
				if(dlv_qty){
					avg_rate=amount/dlv_qty;
				}

				//receive_per=(receive_qty/qty)*100;
				$(this).datagrid('reloadFooter', [
				{
					dlv_qty: dlv_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					avg_rate: avg_rate.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					grey_used_qty: grey_used_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount_bdt: amount_bdt.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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

	dlvItemDtlWindow(so_dyeing_dlv_id)
	{
		//let params={};
		let params=this.getParams();
		if(!params.date_from && !params.date_to){
			alert('Select A Date Range First');
			return;
		}
		params.so_dyeing_dlv_id=so_dyeing_dlv_id;
		let data= axios.get(this.route+"/getsubcondlvitem" ,{params});
		let sq=data.then(function (response) {
			$('#subcondyeingdeliveryWindow').window('open');
			$('#subcondyeingdlvitemTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		
	}
	showGridDlvItemDtl(data)
	{
		var dg = $('#subcondyeingdlvitemTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			nowrap:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var qty=0;
				var rate=0;
				var amount=0;

				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				if(qty){
					rate=amount/qty;
				}
				
				$(this).datagrid('reloadFooter', [
				{
					qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	rate: rate.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatdyeingdlvitemdtl(value,row){
		return '<a href="javascript:void(0)" onClick="MsSubConDyeingDelivery.dlvItemDtlWindow('+row.so_dyeing_dlv_id+')">Click</a>';
	}

	/*getpdf(so_dyeing_dlv_id){
		window.open(msApp.baseUrl()+"/subcondyeingdelivery/pdf?id="+so_dyeing_dlv_id);
	}
	
	formatpdf(value,row)
	{		
		return '<a href="javascript:void(0)" onClick="MsSubConDyeingDelivery.getpdf('+row.so_dyeing_dlv_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Billl</span></a>';
	}*/



}
window.MsSubConDyeingDelivery=new MsSubConDyeingDeliveryController(new MsSubConDyeingDeliveryModel());
MsSubConDyeingDelivery.showGrid([]);
MsSubConDyeingDelivery.showGridDlvItemDtl([]);