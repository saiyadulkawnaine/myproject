let MsGroupSaleModel = require('./MsGroupSaleModel');
require('./../../datagrid-filter.js');
class MsGroupSaleController {
	constructor(MsGroupSaleModel)
	{
		this.MsGroupSaleModel = MsGroupSaleModel;
		this.formId='groupsaleFrm';
		this.dataTable='#groupsaleTbl';
		this.route=msApp.baseUrl()+"/groupsales/getdata"
	}
	
	get(){
		let params={};
		
		params.date_from = $('#groupsaleFrm  [name=date_from]').val();
		params.date_to = $('#groupsaleFrm  [name=date_to]').val();
		if(params.date_from==''){
			alert('Please Select Date Range')
			return;
		}
		if(params.date_to==''){
			alert('Please Select Date Range')
			return;
		}
		let d= axios.get(this.route,{params})
		.then(function (response) {
			$('#groupslaehwindowcontainerlayoutcenter').html(response.data)
			//$('#pendingShipmentTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	getDyeingDelails(date_from,date_to,company_id){
		let params={};
		params.date_from=date_from;
		params.date_to=date_to;
		params.company_id=company_id;
		let d= axios.get(msApp.baseUrl()+'/groupsales/getdyeingdetails',{params})
		.then(function (response) {
			//$('#groupslaehwindowcontainerlayoutcenter').html(response.data)
			$('#groupslaedyeingsubdetailTbl').datagrid('loadData', response.data.sub);
			$('#groupslaedyeinginhdetailTbl').datagrid('loadData', response.data.inh);
			$('#groupslaedyeingdetailWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	dyeinginhGrid(data)
	{
		var dg = $('#groupslaedyeinginhdetailTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			nowrap:false,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var qty=0;
				var amount_bdt=0;
				
				
				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					amount_bdt+=data.rows[i]['amount_bdt'].replace(/,/g,'')*1;
				}
				/*rate=amount_bdt*qty;
				if(tCarton_qty){
					tCmRate=(tCmAmount/tCarton_qty)*12;
				}*/
				
				$('#groupslaedyeinginhdetailTbl').datagrid('reloadFooter', [
					{ 
						qty: qty.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						amount_bdt: amount_bdt.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
					}
				]);
				$('#groupslaeInhTotalAmount').val(amount_bdt);
				var subtot=$('#groupslaeSubTotalAmount').val()
				var tot=(subtot*1)+amount_bdt;
				$('#groupslaeTotalAmount').html(tot.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"))
				
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}
	dyeingsubGrid(data)
	{
		var dg = $('#groupslaedyeingsubdetailTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			nowrap:false,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var qty=0;
				var amount_bdt=0;
				
				
				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					amount_bdt+=data.rows[i]['amount_bdt'].replace(/,/g,'')*1;
				}
				/*rate=amount_bdt*qty;
				if(tCarton_qty){
					tCmRate=(tCmAmount/tCarton_qty)*12;
				}*/
				
				$('#groupslaedyeingsubdetailTbl').datagrid('reloadFooter', [
					{ 
						qty: qty.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						amount_bdt: amount_bdt.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
					}
				]);
				$('#groupslaeSubTotalAmount').val(amount_bdt);
				/*var rowsinh = $('#groupslaedyeinginhdetailTbl').datagrid('getFooterRows');
				var rowssub = $('#groupslaedyeingsubdetailTbl').datagrid('getFooterRows');*/
				var inhtot=$('#groupslaeInhTotalAmount').val()
				var tot=(inhtot*1)+amount_bdt;
				$('#groupslaeTotalAmount').html(tot.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"))
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	subdyeshowDc(id)
	{
		
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(msApp.baseUrl()+"/sodyeingdlv/dlvchalan?id="+id);
	}
	subdyeshowBill(id)
	{
		
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(msApp.baseUrl()+"/sodyeingdlv/bill?id="+id);
	}
	formatSubDyeDc(value,row){
		return '<a href="javascript:void(0)"  onClick="MsGroupSale.subdyeshowDc('+row.id+')">'+row.id+'</a>';
	}
	formatSubDyeBill(value,row){
		return '<a href="javascript:void(0)"  onClick="MsGroupSale.subdyeshowBill('+row.id+')">'+row.issue_no+'</a>';
	}

	inhdyeshowDc(id)
	{
		
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(msApp.baseUrl()+"/prodfinishdlv/getchallan?id="+id);
	}
	inhdyeshowBill(id)
	{
		
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(msApp.baseUrl()+"/prodfinishdlv/reportshort?id="+id);
	}
	formatInhDyeDc(value,row){
		return '<a href="javascript:void(0)"  onClick="MsGroupSale.inhdyeshowDc('+row.id+')">'+row.id+'</a>';
	}
	formatInhDyesBill(value,row){
		return '<a href="javascript:void(0)"  onClick="MsGroupSale.inhdyeshowBill('+row.id+')">'+row.issue_no+'</a>';
	}

	getAopDelails(date_from,date_to,company_id){
		let params={};
		params.date_from=date_from;
		params.date_to=date_to;
		params.company_id=company_id;
		let d= axios.get(msApp.baseUrl()+'/groupsales/getaopdetails',{params})
		.then(function (response) {
			//$('#groupslaehwindowcontainerlayoutcenter').html(response.data)
			$('#groupslaeaopsubdetailTbl').datagrid('loadData', response.data.sub);
			$('#groupslaeaopinhdetailTbl').datagrid('loadData', response.data.inh);
			$('#groupslaeaopdetailWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	aopinhGrid(data)
	{
		var dg = $('#groupslaeaopinhdetailTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			nowrap:false,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var qty=0;
				var amount_bdt=0;
				
				
				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					amount_bdt+=data.rows[i]['amount_bdt'].replace(/,/g,'')*1;
				}
				/*rate=amount_bdt*qty;
				if(tCarton_qty){
					tCmRate=(tCmAmount/tCarton_qty)*12;
				}*/
				
				$('#groupslaeaopinhdetailTbl').datagrid('reloadFooter', [
					{ 
						qty: qty.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						amount_bdt: amount_bdt.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
					}
				]);
				$('#groupslaeaopInhTotalAmount').val(amount_bdt);
				var subtot=$('#groupslaeaopSubTotalAmount').val()
				var tot=(subtot*1)+amount_bdt;
				$('#groupslaeaopTotalAmount').html(tot.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"))
				
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}
	aopsubGrid(data)
	{
		var dg = $('#groupslaeaopsubdetailTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			nowrap:false,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var qty=0;
				var amount_bdt=0;
				
				
				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					amount_bdt+=data.rows[i]['amount_bdt'].replace(/,/g,'')*1;
				}
				/*rate=amount_bdt*qty;
				if(tCarton_qty){
					tCmRate=(tCmAmount/tCarton_qty)*12;
				}*/
				
				$('#groupslaeaopsubdetailTbl').datagrid('reloadFooter', [
					{ 
						qty: qty.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						amount_bdt: amount_bdt.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
					}
				]);
				$('#groupslaeaopSubTotalAmount').val(amount_bdt);
				/*var rowsinh = $('#groupslaedyeinginhdetailTbl').datagrid('getFooterRows');
				var rowssub = $('#groupslaedyeingsubdetailTbl').datagrid('getFooterRows');*/
				var inhtot=$('#groupslaeaopInhTotalAmount').val()
				var tot=(inhtot*1)+amount_bdt;
				$('#groupslaeaopTotalAmount').html(tot.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"))
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}


	subaopshowDc(id)
	{
		
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(msApp.baseUrl()+"/soaopdlv/dlvchalan?id="+id);
	}
	subaopshowBill(id)
	{
		
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(msApp.baseUrl()+"/soaopdlv/bill?id="+id);
	}
	formatSubAopDc(value,row){
		return '<a href="javascript:void(0)"  onClick="MsGroupSale.subaopshowDc('+row.id+')">'+row.id+'</a>';
	}
	formatSubAopBill(value,row){
		return '<a href="javascript:void(0)"  onClick="MsGroupSale.subaopshowBill('+row.id+')">'+row.issue_no+'</a>';
	}


	inhaopshowDc(id)
	{
		
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(msApp.baseUrl()+"/prodaopfinishdlv/getchallan?id="+id);
	}
	inhaopshowBill(id)
	{
		
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(msApp.baseUrl()+"/prodaopfinishdlv/reportshort?id="+id);
	}
	formatInhAopDc(value,row){
		return '<a href="javascript:void(0)"  onClick="MsGroupSale.inhaopshowDc('+row.id+')">'+row.id+'</a>';
	}
	formatInhAopBill(value,row){
		return '<a href="javascript:void(0)"  onClick="MsGroupSale.inhaopshowBill('+row.id+')">'+row.issue_no+'</a>';
	}


	getKnitingDelails(date_from,date_to,company_id){
		let params={};
		params.date_from=date_from;
		params.date_to=date_to;
		params.company_id=company_id;
		let d= axios.get(msApp.baseUrl()+'/groupsales/getknitingdetails',{params})
		.then(function (response) {
			//$('#groupslaehwindowcontainerlayoutcenter').html(response.data)
			$('#groupslaeknitingsubdetailTbl').datagrid('loadData', response.data.sub);
			$('#groupslaeknitinginhdetailTbl').datagrid('loadData', response.data.inh);
			$('#groupslaeknitingdetailWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	knitinginhGrid(data)
	{
		var dg = $('#groupslaeknitinginhdetailTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			nowrap:false,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var qty=0;
				var amount_bdt=0;
				
				
				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					amount_bdt+=data.rows[i]['amount_bdt'].replace(/,/g,'')*1;
				}
				/*rate=amount_bdt*qty;
				if(tCarton_qty){
					tCmRate=(tCmAmount/tCarton_qty)*12;
				}*/
				
				$('#groupslaeknitinginhdetailTbl').datagrid('reloadFooter', [
					{ 
						qty: qty.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						amount_bdt: amount_bdt.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
					}
				]);
				$('#groupslaeknitingInhTotalAmount').val(amount_bdt);
				var subtot=$('#groupslaeknitingSubTotalAmount').val()
				var tot=(subtot*1)+amount_bdt;
				$('#groupslaeknitingTotalAmount').html(tot.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"))
				
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}
	knitingsubGrid(data)
	{
		var dg = $('#groupslaeknitingsubdetailTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			nowrap:false,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var qty=0;
				var amount_bdt=0;
				
				
				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					amount_bdt+=data.rows[i]['amount_bdt'].replace(/,/g,'')*1;
				}
				/*rate=amount_bdt*qty;
				if(tCarton_qty){
					tCmRate=(tCmAmount/tCarton_qty)*12;
				}*/
				
				$('#groupslaeknitingsubdetailTbl').datagrid('reloadFooter', [
					{ 
						qty: qty.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						amount_bdt: amount_bdt.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
					}
				]);
				$('#groupslaeknitingSubTotalAmount').val(amount_bdt);
				/*var rowsinh = $('#groupslaedyeinginhdetailTbl').datagrid('getFooterRows');
				var rowssub = $('#groupslaedyeingsubdetailTbl').datagrid('getFooterRows');*/
				var inhtot=$('#groupslaeknitingInhTotalAmount').val()
				var tot=(inhtot*1)+amount_bdt;
				$('#groupslaeknitingTotalAmount').html(tot.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"))
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}


	subknitingshowDc(id)
	{
		
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(msApp.baseUrl()+"/soknitdlv/dlvchalan?id="+id);
	}
	subknitingshowBill(id)
	{
		
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(msApp.baseUrl()+"/soknitdlv/bill?id="+id);
	}
	formatSubKnitingDc(value,row){
		return '<a href="javascript:void(0)"  onClick="MsGroupSale.subknitingshowDc('+row.id+')">'+row.id+'</a>';
	}
	formatSubKnitingBill(value,row){
		return '<a href="javascript:void(0)"  onClick="MsGroupSale.subknitingshowBill('+row.id+')">'+row.issue_no+'</a>';
	}


	inhknitingshowDc(id)
	{
		
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(msApp.baseUrl()+"/prodknitdlv/getchallan?id="+id);
	}
	inhknitingshowBill(id)
	{
		
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(msApp.baseUrl()+"/prodknitdlv/bill?id="+id);
	}
	formatInhKnitingDc(value,row){
		return '<a href="javascript:void(0)"  onClick="MsGroupSale.inhknitingshowDc('+row.id+')">'+row.id+'</a>';
	}
	formatInhKnitingBill(value,row){
		return '<a href="javascript:void(0)"  onClick="MsGroupSale.inhknitingshowBill('+row.id+')">'+row.issue_no+'</a>';
	}


	getGmtDelails(date_from,date_to,company_id){
		let params={};
		params.date_from=date_from;
		params.date_to=date_to;
		params.company_id=company_id;
		let d= axios.get(msApp.baseUrl()+'/groupsales/getgmtdetails',{params})
		.then(function (response) {
			$('#groupslaegmtdetailTbl').datagrid('loadData', response.data);
			$('#groupslaegmtdetailWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	gmtGrid(data)
	{
		var dg = $('#groupslaegmtdetailTbl');
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			nowrap:false,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var qty=0;
				var amount_bdt=0;
				
				
				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					amount_bdt+=data.rows[i]['amount_bdt'].replace(/,/g,'')*1;
				}
				
				$('#groupslaegmtdetailTbl').datagrid('reloadFooter', [
					{ 
						qty: qty.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
						amount_bdt: amount_bdt.toFixed().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"),
					}
				]);
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	gmtshowBill(id)
	{
		
		if(id==""){
			alert("Select a INvoice");
			return;
		}
		window.open(msApp.baseUrl()+"/expinvoice/orderwiseinvoice?id="+id);
	}
	formatGmtBill(value,row){
		return '<a href="javascript:void(0)"  onClick="MsGroupSale.gmtshowBill('+row.id+')">'+row.invoice_no+'</a>';
	}
}
window.MsGroupSale=new MsGroupSaleController(new MsGroupSaleModel());
MsGroupSale.dyeinginhGrid([]);
MsGroupSale.dyeingsubGrid([]);
MsGroupSale.aopinhGrid([]);
MsGroupSale.aopsubGrid([]);
MsGroupSale.knitinginhGrid([]);
MsGroupSale.knitingsubGrid([]);
MsGroupSale.gmtGrid([]);
