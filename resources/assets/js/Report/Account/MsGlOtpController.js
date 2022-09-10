//require('../../jquery.easyui.min.js');
let MsGlOtpModel = require('./MsGlOtpModel');
require('../../datagrid-filter.js');

class MsGlOtpController {
	constructor(MsGlOtpModel)
	{
		this.MsGlOtpModel = MsGlOtpModel;
		this.formId='glotpFrm';
		this.dataTable='#glotpTbl';
		this.route=msApp.baseUrl()+"/glotp/html";
	}

	get(){
		let params={};
		params.company_id = $('#glotpFrm  [name=company_id]').val();
		params.acc_year_id = $('#glotpFrm  [name=acc_year_id]').val();
		params.date_from = $('#glotpFrm  [name=date_from]').val();
		params.date_to = $('#glotpFrm  [name=date_to]').val();
		params.code_from = $('#glotpFrm  [name=code_from]').val();
		params.code_to = $('#glotpFrm  [name=code_to]').val();

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
		let d= axios.get(msApp.baseUrl()+"/glotp/getYear",{params})
		.then(function (response) {
			MsGlOtp.setYear(response.data);
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
				$('#glotpFrm  [name=acc_year_id]').val(value.id);
				$('#glotpFrm  [name=date_from]').val(value.start_date);
				$('#glotpFrm  [name=date_to]').val(value.end_date);
			}
		});
	}

	getDateRange(acc_year_id){
		let params={};
		params.acc_year_id=acc_year_id;
		let d= axios.get(msApp.baseUrl()+"/glotp/getDateRange",{params})
		.then(function (response) {
			MsGlOtp.setDateRange(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	setDateRange(data)
	{
		$.each(data, function(key, value) {
			
			$('#glotpFrm  [name=date_from]').val(value.start_date);
			$('#glotpFrm  [name=date_to]').val(value.end_date);
		});
	}
	
	showJournal(id)
	{
		window.open(msApp.baseUrl()+"/acctransprnt/journalpdf?id="+id);
	}

	pdf	(){
		let company_id = $('#glotpFrm  [name=company_id]').val();
		let acc_year_id = $('#glotpFrm  [name=acc_year_id]').val();
		let date_from = $('#glotpFrm  [name=date_from]').val();
		let date_to = $('#glotpFrm  [name=date_to]').val();
		let code_from = $('#glotpFrm  [name=code_from]').val();
		let code_to = $('#glotpFrm  [name=code_to]').val();
		let supplier_id = $('#glotpFrm  [name=supplier_id]').val();
		if(company_id=='' || company_id==0){
			alert('Select Company');
			return;
		}
		if(acc_year_id=='' || acc_year_id==0){
			alert('Select Year');
			return;
		}
		window.open(msApp.baseUrl()+"/glotp/pdf?company_id="+company_id+"&acc_year_id="+acc_year_id+"&date_from="+date_from+"&date_to="+date_to+"&code_from="+code_from+"&code_to="+code_to+"&supplier_id="+supplier_id);
	}

	otpsearch(){

		let data={};
		let name=$('#glotpsearchFrm [name=name]').val();
		let code=$('#glotpsearchFrm [name=code]').val();
		let vendor_code=$('#glotpsearchFrm  [name=vendor_code]').val();
		data.name=name;
		data.code=code;
		data.vendor_code=vendor_code;
		this.getOtp(data);
	}

	openotpWindow()
	{
		$('#glotpFrm [name=other_party_name]').val();

		$('#glotpsearchWindow').window('open');
		MsGlOtp.otpsearch()
	}

	getOtp(data){
		let trans=msApp.getJson("supplier/getOtp",data);
		trans.then(function (response){
			$('#glotpsearchTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
	  });
	}

	showGridOtp(data)
	{
		let self=this;
		$('#glotpsearchTbl').datagrid({
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

	closeOtpWindow(type){

		let id=[];
		let name=[];
		let checked=$('#glotpsearchTbl').datagrid('getSelections');
		if(checked.lenght >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
				id.push(val.id)
				name.push(val.name)
		});
		id=id.join(',');
		$('#glotpFrm  [name=supplier_id]').val(id);
		$('#glotpFrm  [name=other_party_name]').val(name);
		$('#glotpsearchWindow').window('close');
		if(type=='html')
		{
			MsGlOtp.get();
		}
		else if(type=='pdf')
		{
			MsGlOtp.pdf();
		}
		

	}

	codefromWindow(sale_order_id)
	{
		let data= axios.get(msApp.baseUrl()+"/gl/getcode");
		data.then(function (response) {
			$('#glotpcodefromTbl').datagrid('loadData', response.data);
			$('#glotpcodefromwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	codetoWindow(sale_order_id)
	{
		let data= axios.get(msApp.baseUrl()+"/gl/getcode");
		data.then(function (response) {
			$('#glotpcodetoTbl').datagrid('loadData', response.data);
			$('#glotpcodetowindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showcodefromGrid(data)
	{
		var dg = $('#glotpcodefromTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			checkbox:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			onClickRow: function(index,row){
			 $('#glotpFrm  [name=code_from]').val(row.code);
			 $('#glotpcodefromwindow').window('close');
			},
            //groupField:'account',
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}
	showcodetoGrid(data)
	{
		var dg = $('#glotpcodetoTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			checkbox:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			onClickRow: function(index,row){
			$('#glotpFrm  [name=code_to]').val(row.code);
			$('#glotpcodetowindow').window('close');
			},
            //groupField:'account',
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

}
window.MsGlOtp=new MsGlOtpController(new MsGlOtpModel());
MsGlOtp.showGrid([]);
MsGlOtp.showGridOtp([]);
MsGlOtp.showcodefromGrid([]);
MsGlOtp.showcodetoGrid([]);