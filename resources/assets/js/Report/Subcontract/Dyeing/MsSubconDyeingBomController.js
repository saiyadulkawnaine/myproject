require('./../../../datagrid-filter.js');
let MsSubconDyeingBomModel = require('./MsSubconDyeingBomModel');

class MsSubconDyeingBomController {
	constructor(MsSubconDyeingBomModel)
	{
		this.MsSubconDyeingBomModel = MsSubconDyeingBomModel;
		this.formId='subcondyeingbomFrm';
		this.dataTable='#subcondyeingbomTbl';
		this.route=msApp.baseUrl()+"/subcondyeingbom";
	}

	getParams(){
		let params={};
		params.company_id = $('#subcondyeingbomFrm  [name=company_id]').val();
		params.buyer_id = $('#subcondyeingbomFrm  [name=buyer_id]').val();
		params.exch_rate = $('#subcondyeingbomFrm  [name=exch_rate]').val();
		params.date_from = $('#subcondyeingbomFrm  [name=date_from]').val();
		params.date_to = $('#subcondyeingbomFrm  [name=date_to]').val();
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
			$('#subcondyeingbomTbl').datagrid('loadData', response.data);
			//$('#subcondyeingbomsummaryTbl').datagrid('loadData', response.summary);
		})
		.catch(function (error) {
			console.log(error);
		});

		let date_from = new Date(params.date_from)
        let formatted_date_from = date_from.getDate() + "-" + msApp.months[date_from.getMonth()] + "-" + date_from.getFullYear();
        let date_to = new Date(params.date_to)
        let formatted_date_to = date_to.getDate() + "-" + msApp.months[date_to.getMonth()] + "-" + date_to.getFullYear();
		var title='Subcontract Dyeing Bom Report : &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp From '+formatted_date_from+' &nbsp&nbspTo &nbsp&nbsp'+formatted_date_to;
		var p = $('#subcondyeingbompanel').layout('panel', 'center').panel('setTitle', title);
		
	}

	getSummary()
	{
		let params=this.getParams();
		if(!params.date_from && !params.date_to){
			alert('Select A Date Range First');
			return;
		}
		let d= axios.get(this.route+'/getsummary',{params})
		.then(function (response) {
			//$('#subcondyeingbomTbl').datagrid('loadData', response.data);
			$('#subcondyeingbomsummaryTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	getChart()
	{
		let params=this.getParams();
		if(!params.date_from && !params.date_to){
			alert('Select A Date Range First');
			return;
		}
		let d= axios.get(this.route+'/getchart',{params})
		.then(function (response) {
			//$('#subcondyeingbomTbl').datagrid('loadData', response.data);
			//$('#subcondyeingbomsummaryTbl').datagrid('loadData', response.data);
			MsSubconDyeingBom.createChart(response)
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
			nowrap:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var order_qty=0;
				var order_rate=0;
				var order_val=0;
				var fin_qty=0;
				var grey_used_qty=0;
				var fin_amount=0;
				var bal_qty=0;
				var dye_cost=0;
				var chem_cost=0;
				var dye_chem_cost=0;
				var overhead_cost=0;
				var total_cost=0;
				var profit_loss=0;
				var profit_loss_per=0;
				var rcv_qty=0;

				for(var i=0; i<data.rows.length; i++){
					order_qty+=data.rows[i]['order_qty'].replace(/,/g,'')*1;
					order_val+=data.rows[i]['order_val'].replace(/,/g,'')*1;
					fin_qty+=data.rows[i]['fin_qty'].replace(/,/g,'')*1;
					grey_used_qty+=data.rows[i]['grey_used_qty'].replace(/,/g,'')*1;
					fin_amount+=data.rows[i]['fin_amount'].replace(/,/g,'')*1;
					bal_qty+=data.rows[i]['bal_qty'].replace(/,/g,'')*1;
					dye_cost+=data.rows[i]['dye_cost'].replace(/,/g,'')*1;
					chem_cost+=data.rows[i]['chem_cost'].replace(/,/g,'')*1;
					dye_chem_cost+=data.rows[i]['dye_chem_cost'].replace(/,/g,'')*1;
					overhead_cost+=data.rows[i]['overhead_cost'].replace(/,/g,'')*1;
					total_cost+=data.rows[i]['total_cost'].replace(/,/g,'')*1;
					profit_loss+=data.rows[i]['profit_loss'].replace(/,/g,'')*1;
					rcv_qty+=data.rows[i]['rcv_qty'].replace(/,/g,'')*1;
				}
				if(order_qty){
				order_rate=order_val/order_qty;
				}
				if(order_val){
				profit_loss_per=(profit_loss/order_val)*100;
				}
				//receive_per=(receive_qty/qty)*100;
				$(this).datagrid('reloadFooter', [
				{
					order_qty: order_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					order_val: order_val.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	order_rate: order_rate.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	fin_qty: fin_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	grey_used_qty: grey_used_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	fin_amount: fin_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	bal_qty: bal_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	dye_cost: dye_cost.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	chem_cost: chem_cost.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	dye_chem_cost: dye_chem_cost.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	overhead_cost: overhead_cost.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	total_cost: total_cost.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	profit_loss: profit_loss.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	profit_loss_per: profit_loss_per.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	rcv_qty: rcv_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	showGridSummary(data)
	{
		var dg = $('#subcondyeingbomsummaryTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			nowrap:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var order_qty=0;
				var order_rate=0;
				var order_val=0;
				var fin_qty=0;
				var grey_used_qty=0;
				var fin_amount=0;
				var bal_qty=0;
				var dye_cost=0;
				var chem_cost=0;
				var dye_chem_cost=0;
				var overhead_cost=0;
				var total_cost=0;
				var profit_loss=0;
				var profit_loss_per=0;

				for(var i=0; i<data.rows.length; i++){
					order_qty+=data.rows[i]['order_qty'].replace(/,/g,'')*1;
					order_val+=data.rows[i]['order_val'].replace(/,/g,'')*1;
					fin_qty+=data.rows[i]['fin_qty'].replace(/,/g,'')*1;
					grey_used_qty+=data.rows[i]['grey_used_qty'].replace(/,/g,'')*1;
					fin_amount+=data.rows[i]['fin_amount'].replace(/,/g,'')*1;
					bal_qty+=data.rows[i]['bal_qty'].replace(/,/g,'')*1;
					dye_cost+=data.rows[i]['dye_cost'].replace(/,/g,'')*1;
					chem_cost+=data.rows[i]['chem_cost'].replace(/,/g,'')*1;
					dye_chem_cost+=data.rows[i]['dye_chem_cost'].replace(/,/g,'')*1;
					overhead_cost+=data.rows[i]['overhead_cost'].replace(/,/g,'')*1;
					total_cost+=data.rows[i]['total_cost'].replace(/,/g,'')*1;
					profit_loss+=data.rows[i]['profit_loss'].replace(/,/g,'')*1;
				}
				if(order_qty){
				order_rate=order_val/order_qty;
				}
				if(order_val){
				profit_loss_per=(profit_loss/order_val)*100;
				}
				//receive_per=(receive_qty/qty)*100;
				$(this).datagrid('reloadFooter', [
				{
					order_qty: order_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					order_val: order_val.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	order_rate: order_rate.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	fin_qty: fin_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	grey_used_qty: grey_used_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	fin_amount: fin_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	bal_qty: bal_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	dye_cost: dye_cost.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	chem_cost: chem_cost.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	dye_chem_cost: dye_chem_cost.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	overhead_cost: overhead_cost.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	total_cost: total_cost.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	profit_loss: profit_loss.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	profit_loss_per: profit_loss_per.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	createChart(response){
		var subcondyeingbomchartcontainer = document.getElementById('subcondyeingbomchartcontainer');
		subcondyeingbomchartcontainer.innerHTML = '';
		$('#subcondyeingbomchartcontainer').append('<canvas id="subcondyeingbomchartcontainercanvas"><canvas>');
		var ctx = $("#subcondyeingbomchartcontainercanvas").get(0).getContext("2d");
		ctx.height = 700;
		ctx.width = 700;
		


		var mixedChart= new Chart(ctx, {
			type: 'pie',
			data: {
				datasets: [
					{
						//label: 'Order Value',
						backgroundColor: ['#2265bc','#b50100','#f23091','#f10102','#02a74b'],
						data: [
						response.data.order_val,
						response.data.dye_cost,
						response.data.chem_cost,
						response.data.overhead_cost,
						response.data.profit_loss
						]
					},
					
				],
				labels: ['Order Value','Dyes Cost','Chemical Cost','Overhead Cost','Profit Loss']
			},
			options: {
				maintainAspectRatio: false,
				title: {
					display: true,
					text: 'Dyeing Budget'
				},
			}
		});

			

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
		return '<a href="javascript:void(0)" onClick="MsSubconDyeingBom.contactWindow('+row.buyer_id+')">'+value+'</a>';
	}

	contactWindow(buyer_id)
	{
		
		let params={};
		params.buyer_id=buyer_id;
		let data= axios.get(this.route+"/getbuyerinfo" ,{params});
		let sq=data.then(function (response) {
			$('#subcondyeingbomWindow').window('open');
			$('#subcondyeingbomcontactTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		
	}

	showGridContract(data)
	{
		var dg = $('#subcondyeingbomcontactTbl');
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

	formatorderqty(value,row){
		return '<a href="javascript:void(0)" onClick="MsSubconDyeingBom.orderqtyWindow('+row.id+')">'+value+'</a>';
	}

	orderqtyWindow(so_dyeing_id)
	{
		
		let params=this.getParams();
		if(!params.date_from && !params.date_to){
			alert('Select A Date Range First');
			return;
		}
		params.so_dyeing_id=so_dyeing_id;

		let data= axios.get(this.route+"/getorderqty" ,{params});
		let sq=data.then(function (response) {
			$('#subcondyeingbomorderqtyWindow').window('open');
			$('#subcondyeingbomorderqtyTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		
	}
	showGridOrderQty(data)
	{
		var dg = $('#subcondyeingbomorderqtyTbl');
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
				var order_val=0;
				

				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					order_val+=data.rows[i]['order_val'].replace(/,/g,'')*1;
					
				}
				if(qty){
				rate=order_val/qty;
				}
				
				$(this).datagrid('reloadFooter', [
				{
					qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					order_val: order_val.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	rate: rate.toFixed(4).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	formatdlvqty(value,row){
		return '<a href="javascript:void(0)" onClick="MsSubconDyeingBom.dlvqtyWindow('+row.id+')">'+value+'</a>';
	}

	dlvqtyWindow(so_dyeing_id)
	{
		
		//let params={};
		let params=this.getParams();
		if(!params.date_from && !params.date_to){
			alert('Select A Date Range First');
			return;
		}
		params.so_dyeing_id=so_dyeing_id;
		let data= axios.get(this.route+"/getdlvqty" ,{params});
		let sq=data.then(function (response) {
			$('#subcondyeingbomdlvqtyWindow').window('open');
			$('#subcondyeingbomdlvqtyTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		
	}
	showGridDlvQty(data)
	{
		var dg = $('#subcondyeingbomdlvqtyTbl');
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

	formatdyeqty(value,row){
		return '<a href="javascript:void(0)" onClick="MsSubconDyeingBom.dyeqtyWindow('+row.id+')">'+value+'</a>';
	}

	dyeqtyWindow(so_dyeing_id)
	{
		
		//let params={};
		let params=this.getParams();
		if(!params.date_from && !params.date_to){
			alert('Select A Date Range First');
			return;
		}
		params.so_dyeing_id=so_dyeing_id;
		let data= axios.get(this.route+"/getdyeqty" ,{params});
		let sq=data.then(function (response) {
			$('#subcondyeingbomdyeqtyWindow').window('open');
			$('#subcondyeingbomdyeqtyTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		
	}

	showGridDyeQty(data)
	{
		var dg = $('#subcondyeingbomdyeqtyTbl');
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

	formatchemqty(value,row){
		return '<a href="javascript:void(0)" onClick="MsSubconDyeingBom.chemqtyWindow('+row.id+')">'+value+'</a>';
	}

	chemqtyWindow(so_dyeing_id)
	{
		
		//let params={};
		let params=this.getParams();
		if(!params.date_from && !params.date_to){
			alert('Select A Date Range First');
			return;
		}
		params.so_dyeing_id=so_dyeing_id;
		let data= axios.get(this.route+"/getchemqty" ,{params});
		let sq=data.then(function (response) {
			$('#subcondyeingbomchemqtyWindow').window('open');
			$('#subcondyeingbomchemqtyTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		
	}

	showGridChemQty(data)
	{
		var dg = $('#subcondyeingbomchemqtyTbl');
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

	formatohqty(value,row){
		return '<a href="javascript:void(0)" onClick="MsSubconDyeingBom.ohqtyWindow('+row.id+')">'+value+'</a>';
	}

	ohqtyWindow(so_dyeing_id)
	{
		
		//let params={};
		let params=this.getParams();
		if(!params.date_from && !params.date_to){
			alert('Select A Date Range First');
			return;
		}
		params.so_dyeing_id=so_dyeing_id;
		let data= axios.get(this.route+"/getohqty" ,{params});
		let sq=data.then(function (response) {
			$('#subcondyeingbomohqtyWindow').window('open');
			$('#subcondyeingbomohqtyTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
		
	}

	showGridOhQty(data)
	{
		var dg = $('#subcondyeingbomohqtyTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			nowrap:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){

				var amount=0;
				

				for(var i=0; i<data.rows.length; i++){
					amount+=data.rows[i]['amount'].replace(/,/g,'')*1;
					
				}
				
				
				$(this).datagrid('reloadFooter', [
				{
					amount: amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

}
window.MsSubconDyeingBom=new MsSubconDyeingBomController(new MsSubconDyeingBomModel());
MsSubconDyeingBom.showGrid([]);
MsSubconDyeingBom.showGridSummary([]);
MsSubconDyeingBom.showGridContract([]);
MsSubconDyeingBom.showGridOrderQty([]);
MsSubconDyeingBom.showGridDlvQty([]);
MsSubconDyeingBom.showGridDyeQty([]);
MsSubconDyeingBom.showGridChemQty([]);
MsSubconDyeingBom.showGridOhQty([]);

$('#subcondyeingbomtabs').tabs({
	onSelect:function(title,index){
	 if(index==1)
	 {
		 MsSubconDyeingBom.getSummary();
	 }
	 if(index==2)
	 {
	 	//alert('mm')
		 MsSubconDyeingBom.getChart();
	 }
}
}); 