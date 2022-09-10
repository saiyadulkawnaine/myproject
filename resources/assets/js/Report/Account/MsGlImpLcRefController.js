let MsGlImpLcRefModel = require('./MsGlImpLcRefModel');
require('./../../datagrid-filter.js');

class MsGlImpLcRefController {
	constructor(MsGlImpLcRefModel)
	{
		this.MsGlImpLcRefModel = MsGlImpLcRefModel;
		this.formId='glimplcrefFrm';
		this.dataTable='#glimplcrefTbl';
		this.route=msApp.baseUrl()+"/glimplcref/html";
	}

	get(){
		let params={};
		params.company_id = $('#glimplcrefFrm  [name=company_id]').val();
		params.acc_year_id = $('#glimplcrefFrm  [name=acc_year_id]').val();
		params.date_from = $('#glimplcrefFrm  [name=date_from]').val();
		params.date_to = $('#glimplcrefFrm  [name=date_to]').val();
		params.code_from = $('#glimplcrefFrm  [name=code_from]').val();
		params.code_to = $('#glimplcrefFrm  [name=code_to]').val();
		params.import_lc_ref_name = $('#glimplcrefFrm  [name=import_lc_ref_name]').val();

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
		let d= axios.get(msApp.baseUrl()+"/glimplcref/getYear",{params})
		.then(function (response) {
			MsGlImpLcRef.setYear(response.data);
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
				$('#glimplcrefFrm  [name=acc_year_id]').val(value.id);
				$('#glimplcrefFrm  [name=date_from]').val(value.start_date);
				$('#glimplcrefFrm  [name=date_to]').val(value.end_date);
			}
		});
	}

	getDateRange(acc_year_id){
		let params={};
		params.acc_year_id=acc_year_id;
		let d= axios.get(msApp.baseUrl()+"/glimplcref/getDateRange",{params})
		.then(function (response) {
			MsGlImpLcRef.setDateRange(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	setDateRange(data)
	{
		$.each(data, function(key, value) {
			
			$('#glimplcrefFrm  [name=date_from]').val(value.start_date);
			$('#glimplcrefFrm  [name=date_to]').val(value.end_date);
		});
	}
	
	showJournal(id)
	{
		window.open(msApp.baseUrl()+"/acctransprnt/journalpdf?id="+id);
	}

	pdf	(){
		let company_id = $('#glimplcrefFrm  [name=company_id]').val();
		let acc_year_id = $('#glimplcrefFrm  [name=acc_year_id]').val();
		let date_from = $('#glimplcrefFrm  [name=date_from]').val();
		let date_to = $('#glimplcrefFrm  [name=date_to]').val();
		let code_from = $('#glimplcrefFrm  [name=code_from]').val();
		let code_to = $('#glimplcrefFrm  [name=code_to]').val();
		let import_lc_ref_name = $('#glimplcrefFrm  [name=import_lc_ref_name]').val();
		//let supplier_id = $('#glimplcrefFrm  [name=supplier_id]').val();
		if(company_id=='' || company_id==0){
			alert('Select Company');
			return;
		}
		if(acc_year_id=='' || acc_year_id==0){
			alert('Select Year');
			return;
		}
		window.open(msApp.baseUrl()+"/glimplcref/pdf?company_id="+company_id+"&acc_year_id="+acc_year_id+"&date_from="+date_from+"&date_to="+date_to+"&code_from="+code_from+"&code_to="+code_to+"&import_lc_ref_name="+import_lc_ref_name);
	}

	implcrefsearch(){

		let data={};
		let name=$('#glimplcrefsearchFrm [name=name]').val();
		let code=$('#glimplcrefsearchFrm [name=code]').val();
		let vendor_code=$('#glimplcrefsearchFrm  [name=vendor_code]').val();
		data.name=name;
		data.code=code;
		data.vendor_code=vendor_code;
		this.getImpLcRef(data);
    }
    
	openImpLcRefWindow()
	{
		$('#glimplcrefFrm [name=import_lc_ref_no]').val();
		$('#glimplcrefsearchWindow').window('open');
		MsGlImpLcRef.implcrefsearch()
	}

	getImpLcRef(data){
		let trans=msApp.getJson("/glimplcref/getacimport",data);
		trans.then(function (response){
			$('#glimplcrefsearchTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
	  });
	}

	showGridImpLcRef(data)
	{
		let self=this;
		$('#glimplcrefsearchTbl').datagrid({
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

	closeImpLcRefWindow(type){

		let id=[];
		let name=[];
		let checked=$('#glimplcrefsearchTbl').datagrid('getSelections');
		if(checked.lenght >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
				id.push(val.id)
				name.push(val.lc_no)
		});
		id=id.join(',');
		$('#glimplcrefFrm  [name=import_lc_id]').val(id);
		$('#glimplcrefFrm  [name=import_lc_ref_name]').val(name);
		$('#glimplcrefsearchWindow').window('close');
		if(type=='html')
		{
			MsGlImpLcRef.get();
		}
		else if(type=='pdf')
		{
			MsGlImpLcRef.pdf();
		}
		

	}

	codefromWindow(sale_order_id)
	{
		let data= axios.get(msApp.baseUrl()+"/gl/getcode");
		data.then(function (response) {
			$('#glimplcrefcodefromTbl').datagrid('loadData', response.data);
			$('#glimplcrefcodefromwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	codetoWindow(sale_order_id)
	{
		let data= axios.get(msApp.baseUrl()+"/gl/getcode");
		data.then(function (response) {
			$('#glimplcrefcodetoTbl').datagrid('loadData', response.data);
			$('#glimplcrefcodetowindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showcodefromGrid(data)
	{
		var dg = $('#glimplcrefcodefromTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			checkbox:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			onClickRow: function(index,row){
			 $('#glimplcrefFrm  [name=code_from]').val(row.code);
			 $('#glimplcrefcodefromwindow').window('close');
			},
            //groupField:'account',
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}
	showcodetoGrid(data)
	{
		var dg = $('#glimplcrefcodetoTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			checkbox:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			onClickRow: function(index,row){
			$('#glimplcrefFrm  [name=code_to]').val(row.code);
			$('#glimplcrefcodetowindow').window('close');
			},
            //groupField:'account',
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

}
window.MsGlImpLcRef=new MsGlImpLcRefController(new MsGlImpLcRefModel());
MsGlImpLcRef.showGrid([]);
MsGlImpLcRef.showGridImpLcRef([]);

MsGlImpLcRef.showcodefromGrid([]);
MsGlImpLcRef.showcodetoGrid([]);