require('./../../../datagrid-filter.js');
let MsSubconKnitingTargetModel = require('./MsSubconKnitingTargetModel');

class MsSubconKnitingTargetController {
	constructor(MsSubconKnitingTargetModel)
	{
		this.MsSubconKnitingTargetModel = MsSubconKnitingTargetModel;
		this.formId='subconknitingtargetFrm';
		this.dataTable='#subconknitingtargetTbl';
		this.route=msApp.baseUrl()+"/subconknitingtarget";
	}

	getParams(){
		let params={};
		params.date_from = $('#subconknitingtargetFrm  [name=date_from]').val();
		params.date_to = $('#subconknitingtargetFrm  [name=date_to]').val();
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
			$('#subconknitingtargetTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

		let date_from = new Date(params.date_from)
        let formatted_date_from = date_from.getDate() + "-" + msApp.months[date_from.getMonth()] + "-" + date_from.getFullYear();
        let date_to = new Date(params.date_to)
        let formatted_date_to = date_to.getDate() + "-" + msApp.months[date_to.getMonth()] + "-" + date_to.getFullYear();
		var title='Subcontract Kniting Target Report : &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp From '+formatted_date_from+' &nbsp&nbspTo &nbsp&nbsp'+formatted_date_to;
		var p = $('#subconknitingtargetpanel').layout('panel', 'center').panel('setTitle', title);
		
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
				var rate=0;
				var amount=0;
				var receive_qty=0;
				var fin_qty=0;
				var grey_used_qty=0;
				var grey_used_amount=0;

				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
					receive_qty+=data.rows[i]['receive_qty'].replace(/,/g,'')*1;
					fin_qty+=data.rows[i]['fin_qty'].replace(/,/g,'')*1;
					grey_used_qty+=data.rows[i]['grey_used_qty'].replace(/,/g,'')*1;
					grey_used_amount+=data.rows[i]['grey_used_amount'].replace(/,/g,'')*1;
				}
				rate=amount/qty;
				receive_per=(receive_qty/qty)*100;
				$(this).datagrid('reloadFooter', [
				{
					qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	rate: rate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	receive_qty: receive_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	fin_qty: fin_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	grey_used_qty: grey_used_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	grey_used_amount: grey_used_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	receive_per: receive_per.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
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

	formatAttribute(value,row){
		let contact_person=row.contact_person?row.contact_person:'';
		let designation=row.designation?row.designation:'';
		let email=row.email?row.email:'';
		let cell_no=row.cell_no?row.cell_no:'';
		let address=row.address?row.address:'';
		return '<span title='+'"Contact Person: '+contact_person+'\nDesignation: '+designation+'\nEmail:'+email+'\nCell No:'+cell_no+'\nAddress: '+address+'"'+'>'+value+'</span>';
	}

	formatContact(value,row){
		return '<a href="javascript:void(0)" onClick="MsSubconKnitingTarget.contactWindow('+row.buyer_id+')">'+value+'</a>';
	}

	contactWindow(buyer_id)
	{
		
		//$('#subconknitingtargetcontactTbl').datagrid('loadData',row);
		//$('#subconknitingtargetWindow').window('open');
		//let params=this.getParams();
		let params={};
		params.buyer_id=buyer_id;
		let data= axios.get(this.route+"/getbuyerinfo" ,{params});
		let sq=data.then(function (response) {
			$('#subconknitingtargetWindow').window('open');
			$('#subconknitingtargetcontactTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		
	}

	showGridContract(data)
	{
		var dg = $('#subconknitingtargetcontactTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
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
}
window.MsSubconKnitingTarget=new MsSubconKnitingTargetController(new MsSubconKnitingTargetModel());
MsSubconKnitingTarget.showGrid([]);
MsSubconKnitingTarget.showGridContract([]);