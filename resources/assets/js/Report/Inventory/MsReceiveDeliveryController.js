require('./../../datagrid-filter.js');
let MsReceiveDeliveryModel = require('./MsReceiveDeliveryModel');

class MsReceiveDeliveryController {
	constructor(MsReceiveDeliveryModel)
	{
		this.MsReceiveDeliveryModel = MsReceiveDeliveryModel;
		this.formId='receivedeliveryFrm';
		this.dataTable='#receivedeliveryTbl';
		this.route=msApp.baseUrl()+"/receivedelivery";
	}

	getParams(){
		let params={};
		params.company_id = $('#receivedeliveryFrm  [name=company_id]').val();
		params.buyer_id = $('#receivedeliveryFrm  [name=buyer_id]').val();
		params.date_from = $('#receivedeliveryFrm  [name=date_from]').val();
		params.date_to = $('#receivedeliveryFrm  [name=date_to]').val();
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
			$('#receivedeliveryTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

		let date_from = new Date(params.date_from)
        let formatted_date_from = date_from.getDate() + "-" + msApp.months[date_from.getMonth()] + "-" + date_from.getFullYear();
        let date_to = new Date(params.date_to)
        let formatted_date_to = date_to.getDate() + "-" + msApp.months[date_to.getMonth()] + "-" + date_to.getFullYear();
		var title='Date Wise Receive Delivery : &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp From '+formatted_date_from+' &nbsp&nbspTo &nbsp&nbsp'+formatted_date_to;
		var p = $('#receivedeliverypanel').layout('panel', 'center').panel('setTitle', title);
	
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
				var rcv_qty=0;
				var dlv_qty=0;
				var rtn_qty=0;
				var grey_used_qty=0;
				var no_of_roll=0;

				for(var i=0; i<data.rows.length; i++){
					rcv_qty+=data.rows[i]['rcv_qty'].replace(/,/g,'')*1;
					dlv_qty+=data.rows[i]['dlv_qty'].replace(/,/g,'')*1;
					rtn_qty+=data.rows[i]['rtn_qty'].replace(/,/g,'')*1;
					grey_used_qty+=data.rows[i]['grey_used_qty'].replace(/,/g,'')*1;
					no_of_roll+=data.rows[i]['no_of_roll'].replace(/,/g,'')*1;
				}
				
				$(this).datagrid('reloadFooter', [
				{
					rcv_qty: rcv_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					dlv_qty: dlv_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rtn_qty: rtn_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					grey_used_qty: grey_used_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					no_of_roll: no_of_roll.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatrefno(value,row){
		if(row.trans_type==1){
		return value;
		}
		if(row.trans_type==2){
		return '<a href="javascript:void(0)" onClick="MsReceiveDelivery.getpdf('+row.id+','+row.trans_type+')">'+value+'</a>';
		}
		if(row.trans_type==3){
		return '<a href="javascript:void(0)" onClick="MsReceiveDelivery.getpdf('+row.id+','+row.trans_type+')">'+value+'</a>';
		}
	}

	getpdf(id,type){
		if(type==1){
		//window.open(msApp.baseUrl()+"/subcondyeingdelivery/pdf?id="+id);
		}
		if(type==2){
		window.open(msApp.baseUrl()+"/sodyeingdlv/bill?id="+id);
		}
		if(type==3){
		window.open(msApp.baseUrl()+"/sodyeingfabricrtn/report?id="+id);
		}
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
		return '<a href="javascript:void(0)" onClick="MsReceiveDelivery.dlvItemDtlWindow('+row.id+')">Click</a>';
	}

	/*getpdf(so_dyeing_dlv_id){
		window.open(msApp.baseUrl()+"/subcondyeingdelivery/pdf?id="+so_dyeing_dlv_id);
	}
	
	formatpdf(value,row)
	{		
		return '<a href="javascript:void(0)" onClick="MsSubConDyeingDelivery.getpdf('+row.so_dyeing_dlv_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Billl</span></a>';
	}*/



}
window.MsReceiveDelivery=new MsReceiveDeliveryController(new MsReceiveDeliveryModel());
MsReceiveDelivery.showGrid([]);
MsReceiveDelivery.showGridDlvItemDtl([]);