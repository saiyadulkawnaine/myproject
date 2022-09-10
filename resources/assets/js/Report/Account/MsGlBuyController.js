
let MsGlBuyModel = require('./MsGlBuyModel');
require('../../datagrid-filter.js');

class MsGlBuyController {
	constructor(MsGlBuyModel)
	{
		this.MsGlBuyModel = MsGlBuyModel;
		this.formId='glbuyFrm';
		this.dataTable='#glbuyTbl';
		this.route=msApp.baseUrl()+"/glbuy/html";
	}

	get(){
		let params={};
		params.company_id = $('#glbuyFrm  [name=company_id]').val();
		params.acc_year_id = $('#glbuyFrm  [name=acc_year_id]').val();
		params.date_from = $('#glbuyFrm  [name=date_from]').val();
		params.date_to = $('#glbuyFrm  [name=date_to]').val();
		params.code_from = $('#glbuyFrm  [name=code_from]').val();
		params.code_to = $('#glbuyFrm  [name=code_to]').val();
		params.buyer_id = $('#glbuyFrm [name=buyer_id]').val();

		if(params.company_id=='' || params.company_id==0){
			alert('Select Company');
			return;
		}
		if(params.acc_year_id=='' || params.acc_year_id==0){
			alert('Select Year');
			return;
		}
		
		let d= axios.get(this.route,{params})
		.then(function (response) {
			$('#glcontainer').html(response.data);
		})
		.catch(function (error) {
			alert('vvvv')
			console.log(error);
		});
	}

	showGrid(data)
	{
		var dg = $(this.dataTable);
		dg.datagrid({
			border:false,
			singleSelect:false,
			checkbox:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
            groupField:'account',
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	getYear(company_id){
		let params={};
		params.company_id=company_id;
		let d= axios.get(msApp.baseUrl()+"/glbuy/getYear",{params})
		.then(function (response) {
			MsGlBuy.setYear(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	setYear(data)
	{
		$('select[name="acc_year_id"]').empty();
		$('select[name="acc_year_id"]').append('<option value="">-Select-</option>');
		$.each(data, function(key, value) {
			$('select[name="acc_year_id"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
			if(value.is_current==1)
			{
				$('#glbuyFrm  [name=acc_year_id]').val(value.id);
				$('#glbuyFrm  [name=date_from]').val(value.start_date);
				$('#glbuyFrm  [name=date_to]').val(value.end_date);
			}
		});
	}

	getDateRange(acc_year_id){
		let params={};
		params.acc_year_id=acc_year_id;
		let d= axios.get(msApp.baseUrl()+"/glbuy/getDateRange",{params})
		.then(function (response) {
			MsGlBuy.setDateRange(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	setDateRange(data)
	{
		$.each(data, function(key, value) {
			
			$('#glbuyFrm  [name=date_from]').val(value.start_date);
			$('#glbuyFrm  [name=date_to]').val(value.end_date);
		});
	}
	
	showJournal(id)
	{
		window.open(msApp.baseUrl()+"/acctransprnt/journalpdf?id="+id);
	}

	pdf	(){
		let company_id = $('#glbuyFrm  [name=company_id]').val();
		let acc_year_id = $('#glbuyFrm  [name=acc_year_id]').val();
		let date_from = $('#glbuyFrm  [name=date_from]').val();
		let date_to = $('#glbuyFrm  [name=date_to]').val();
		let code_from = $('#glbuyFrm  [name=code_from]').val();
		let code_to = $('#glbuyFrm  [name=code_to]').val();
		let buyer_id = $('#glbuyFrm [name=buyer_id]').val();
		if(company_id=='' || company_id==0){
			alert('Select Company');
			return;
		}
		if(acc_year_id=='' || acc_year_id==0){
			alert('Select Year');
			return;
		}
		window.open(msApp.baseUrl()+"/glbuy/pdf?company_id="+company_id+"&acc_year_id="+acc_year_id+"&date_from="+date_from+"&date_to="+date_to+"&code_from="+code_from+"&code_to="+code_to+"&buyer_id="+buyer_id);
	}

	buyersearch(){

		let data={};
		let name=$('#glbuyersearchFrm  [name=name]').val();
		let code=$('#glbuyersearchFrm  [name=code]').val();
		let vendor_code=$('#glbuyersearchFrm  [name=vendor_code]').val();
		data.name=name;
        data.code=code;
        data.vendor_code=vendor_code;
        this.getBuyer(data);

	}

	openbuyerWindow()
	{
		$('#glbuyFrm  [name=buyer_name]').val();
		
		$('#glbuyersearchWindow').window('open');
		MsGlBuy.buyersearch()
		
    }
    getBuyer(data){
		let trans=msApp.getJson("buyer/getBuyer",data);
		trans.then(function (response) {
			$('#glbuyersearchTbl').datagrid('loadData', response.data);

            //MsTransPrnt.showGrid(response.data);
        })
        .catch(function (error) {
            console.log(error);
        });
	}

	showGridBuyer(data)
	{
		let self=this;
		$('#glbuyersearchTbl').datagrid({
			//method:'get',
			border:false,
			singleSelect:false,
			fit:true,
			fitColumns:true,
			//url:this.route,
			//checkbox:true,
			onClickRow: function(index,row){
				//self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	closeBuyerWindow(type){

		let id=[];
		let name=[];
		let checked=$('#glbuyersearchTbl').datagrid('getSelections');
		if(checked.length >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
				id.push(val.id)
				name.push(val.name)
		});
		id=id.join(',');
		$('#glbuyFrm  [name=buyer_id]').val(id);
		$('#glbuyFrm  [name=buyer_name]').val(name);
		$('#glbuyersearchWindow').window('close');
		if(type=='html')
		{
			MsGlBuy.get();
		}
		else if(type=='pdf')
		{
			MsGlBuy.pdf();
		}
		

	}

	codefromWindow(sale_order_id)
	{
		let data= axios.get(msApp.baseUrl()+"/gl/getcode");
		data.then(function (response) {
			$('#glbuycodefromTbl').datagrid('loadData', response.data);
			$('#glbuycodefromwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	codetoWindow(sale_order_id)
	{
		let data= axios.get(msApp.baseUrl()+"/gl/getcode");
		data.then(function (response) {
			$('#glbuycodetoTbl').datagrid('loadData', response.data);
			$('#glbuycodetowindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showcodefromGrid(data)
	{
		var dg = $('#glbuycodefromTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			checkbox:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			onClickRow: function(index,row){
			 $('#glbuyFrm  [name=code_from]').val(row.code);
			 $('#glbuycodefromwindow').window('close');
			},
            //groupField:'account',
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}
	showcodetoGrid(data)
	{
		var dg = $('#glbuycodetoTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			checkbox:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			onClickRow: function(index,row){
			$('#glbuyFrm  [name=code_to]').val(row.code);
			$('#glbuycodetowindow').window('close');
			},
            //groupField:'account',
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}


}
window.MsGlBuy=new MsGlBuyController(new MsGlBuyModel());
MsGlBuy.showGrid([]);
MsGlBuy.showGridBuyer([]);
MsGlBuy.showcodefromGrid([]);
MsGlBuy.showcodetoGrid([]);