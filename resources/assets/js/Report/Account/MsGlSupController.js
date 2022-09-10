//require('../../jquery.easyui.min.js');
let MsGlSupModel = require('./MsGlSupModel');
require('../../datagrid-filter.js');

class MsGlSupController {
	constructor(MsGlSupModel)
	{
		this.MsGlSupModel = MsGlSupModel;
		this.formId='glsupFrm';
		this.dataTable='#glsupTbl';
		this.route=msApp.baseUrl()+"/glsup/html";
	}

	get(){
		let params={};
		params.company_id = $('#glsupFrm  [name=company_id]').val();
		params.acc_year_id = $('#glsupFrm  [name=acc_year_id]').val();
		params.date_from = $('#glsupFrm  [name=date_from]').val();
		params.date_to = $('#glsupFrm  [name=date_to]').val();
		params.code_from = $('#glsupFrm  [name=code_from]').val();
		params.code_to = $('#glsupFrm  [name=code_to]').val();
		params.supplier_id = $('#glsupFrm [name=supplier_id]').val();

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
		let d= axios.get(msApp.baseUrl()+"/glsup/getYear",{params})
		.then(function (response) {
			MsGlSup.setYear(response.data);
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
				$('#glsupFrm  [name=acc_year_id]').val(value.id);
				$('#glsupFrm  [name=date_from]').val(value.start_date);
				$('#glsupFrm  [name=date_to]').val(value.end_date);
			}
		});
	}

	getDateRange(acc_year_id){
		let params={};
		params.acc_year_id=acc_year_id;
		let d= axios.get(msApp.baseUrl()+"/glsup/getDateRange",{params})
		.then(function (response) {
			MsGlSup.setDateRange(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	setDateRange(data)
	{
		$.each(data, function(key, value) {
			$('#glsupFrm  [name=date_from]').val(value.start_date);
			$('#glsupFrm  [name=date_to]').val(value.end_date);
		});
	}
	
	showJournal(id)
	{
		window.open(msApp.baseUrl()+"/acctransprnt/journalpdf?id="+id);
	}

	pdf	(){
		let company_id = $('#glsupFrm  [name=company_id]').val();
		let acc_year_id = $('#glsupFrm  [name=acc_year_id]').val();
		let date_from = $('#glsupFrm  [name=date_from]').val();
		let date_to = $('#glsupFrm  [name=date_to]').val();
		let code_from = $('#glsupFrm  [name=code_from]').val();
		let code_to = $('#glsupFrm  [name=code_to]').val();
		let supplier_id = $('#glsupFrm [name=supplier_id]').val();
		if(company_id=='' || company_id==0){
			alert('Select Company');
			return;
		}
		if(acc_year_id=='' || acc_year_id==0){
			alert('Select Year');
			return;
		}
		window.open(msApp.baseUrl()+"/glsup/pdf?company_id="+company_id+"&acc_year_id="+acc_year_id+"&date_from="+date_from+"&date_to="+date_to+"&code_from="+code_from+"&code_to="+code_to+"&supplier_id="+supplier_id);
	}

	suppliersearch(){

		let data={};
		let name=$('#glsuppliersearchFrm [name=name]').val();
		let code=$('#glsuppliersearchFrm [name=code]').val();
		let vendor_code=$('#glsuppliersearchFrm  [name=vendor_code]').val();
		data.name=name;
		data.code=code;
		data.vendor_code=vendor_code;
		this.getSupplier(data);
	}

	opensupplierWindow()
	{
		$('#glsupFrm [name=supplier_name]').val();

		$('#glsuppliersearchWindow').window('open');
		MsGlSup.suppliersearch()
	}

	getSupplier(data){
		let trans=msApp.getJson("supplier/getSupplier",data);
		trans.then(function (response){
			$('#glsuppliersearchTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
	  });
	}

	showGridSupplier(data)
	{
		let self=this;
		$('#glsuppliersearchTbl').datagrid({
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

	closeSupplierWindow(type){

		let id=[];
		let name=[];
		let checked=$('#glsuppliersearchTbl').datagrid('getSelections');
		if(checked.lenght >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
				id.push(val.id)
				name.push(val.name)
		});
		id=id.join(',');
		$('#glsupFrm  [name=supplier_id]').val(id);
		$('#glsupFrm  [name=supplier_name]').val(name);
		$('#glsuppliersearchWindow').window('close');
		if(type=='html')
		{
			MsGlSup.get();
		}
		else if(type=='pdf')
		{
			MsGlSup.pdf();
		}
		

	}

	codefromWindow(sale_order_id)
	{
		let data= axios.get(msApp.baseUrl()+"/gl/getcode");
		data.then(function (response) {
			$('#glsupcodefromTbl').datagrid('loadData', response.data);
			$('#glsupcodefromwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	codetoWindow(sale_order_id)
	{
		let data= axios.get(msApp.baseUrl()+"/gl/getcode");
		data.then(function (response) {
			$('#glsupcodetoTbl').datagrid('loadData', response.data);
			$('#glsupcodetowindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showcodefromGrid(data)
	{
		var dg = $('#glsupcodefromTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			checkbox:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			onClickRow: function(index,row){
			 $('#glsupFrm  [name=code_from]').val(row.code);
			 $('#glsupcodefromwindow').window('close');
			},
            //groupField:'account',
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}
	showcodetoGrid(data)
	{
		var dg = $('#glsupcodetoTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			checkbox:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			onClickRow: function(index,row){
			$('#glsupFrm  [name=code_to]').val(row.code);
			$('#glsupcodetowindow').window('close');
			},
            //groupField:'account',
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

}
window.MsGlSup=new MsGlSupController(new MsGlSupModel());
MsGlSup.showGrid([]);
MsGlSup.showGridSupplier([]);
MsGlSup.showcodefromGrid([]);
MsGlSup.showcodetoGrid([]);
