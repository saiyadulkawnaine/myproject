require('./../../datagrid-filter.js');
let MsYarnReceiveSummeryModel = require('./MsYarnReceiveSummeryModel');

class MsYarnReceiveSummeryController {
	constructor(MsYarnReceiveSummeryModel)
	{
		this.MsYarnReceiveSummeryModel = MsYarnReceiveSummeryModel;
		this.formId='yarnreceivesummeryFrm';
		this.dataTable='#yarnreceivesummeryTbl';
		this.route=msApp.baseUrl()+"/yarnreceivesummery/getdata";
	}

	getParams(){
		let params={};
		params.date_from = $('#yarnreceivesummeryFrm  [name=date_from]').val();
		params.date_to = $('#yarnreceivesummeryFrm  [name=date_to]').val();
		params.company_id = $('#yarnreceivesummeryFrm  [name=company_id]').val();
		params.supplier_id = $('#yarnreceivesummeryFrm  [name=supplier_id]').val();
		params.rcv_id = $('#yarnreceivesummeryFrm  [name=id]').val();
		return params;
	}

	get()
	{
		let params=this.getParams();
		if(!params.date_from && !params.date_to){
			alert('Select A Date Range First');
			return;
		}
		let d= axios.get(this.route,{params})
		.then(function (response) {
			$('#yarnreceivesummeryTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

		let date_from = new Date(params.date_from)
        let formatted_date_from = date_from.getDate() + "-" + msApp.months[date_from.getMonth()] + "-" + date_from.getFullYear();
        let date_to = new Date(params.date_to)
        let formatted_date_to = date_to.getDate() + "-" + msApp.months[date_to.getMonth()] + "-" + date_to.getFullYear();
		var title='Yarn Receive Summery Report : &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp From '+formatted_date_from+' &nbsp&nbspTo &nbsp&nbsp'+formatted_date_to;
		var p = $('#yarnreceivesummeryPanel').layout('panel', 'center').panel('setTitle', title);
		
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

				var qty=0;
				var amount=0;
				var no_of_bag=0;
				var rate=0;
				var store_amount=0;
				var store_rate=0;
				var wgt_per_bag=0;

				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
					no_of_bag+=data.rows[i]['no_of_bag'].replace(/,/g,'')*1;
					store_amount+=data.rows[i]['store_amount'].replace(/,/g,'')*1;
					wgt_per_bag+=data.rows[i]['wgt_per_bag'].replace(/,/g,'')*1;

				}
				rate=amount/qty;
				store_rate=store_amount/qty;
				$(this).datagrid('reloadFooter', [
				{
					qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					no_of_bag: no_of_bag.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					store_amount: store_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					store_rate: store_rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					wgt_per_bag: wgt_per_bag.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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
	openMrrWindow(){
		let params=this.getParams();
		if(!params.date_from && !params.date_to){
			alert('Select A Date Range First');
			return;
		}
		let data= axios.get(msApp.baseUrl()+"/yarnreceivesummery/getmrr",{params});
		let g=data.then(function (response) {
		$('#yarnreceivesummerymrrTbl').datagrid('loadData', response.data);
		$('#yarnreceivesummerymrrWindow').window('open');	
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGridMrr(data)
	{
		var dg = $('#yarnreceivesummerymrrTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			showFooter:true,
			fit:true,
			nowrap:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	closeMrrWindow(){

		let formObj=[];
		let receiveNo=[];
		let i=0;
		$.each($('#yarnreceivesummerymrrTbl').datagrid('getSelections'), function (idx, val) {
			formObj[i]=val.id;
			receiveNo[i]=val.receive_no;
			i++;
		});
		var id=formObj.join(',');
		var receive_no=receiveNo.join(',');
		$('#yarnreceivesummeryFrm  [name=id]').val(id);
		$('#yarnreceivesummeryFrm  [name=receive_no]').val(receive_no);
		
		$('#yarnreceivesummerymrrTbl').datagrid('clearSelections');
		$('#yarnreceivesummerymrrWindow').window('close');
		return formObj;

	}
}
window.MsYarnReceiveSummery=new MsYarnReceiveSummeryController(new MsYarnReceiveSummeryModel());
MsYarnReceiveSummery.showGrid([]);
MsYarnReceiveSummery.showGridMrr([]);