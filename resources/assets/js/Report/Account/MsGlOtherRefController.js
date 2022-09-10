let MsGlOtherRefModel = require('./MsGlOtherRefModel');
require('../../datagrid-filter.js');

class MsGlOtherRefController {
	constructor(MsGlOtherRefModel)
	{
		this.MsGlOtherRefModel = MsGlOtherRefModel;
		this.formId='glotherrefFrm';
		this.dataTable='#glotherrefTbl';
		this.route=msApp.baseUrl()+"/glotherref/html";
	}

	get(){
		let params={};
		params.company_id = $('#glotherrefFrm  [name=company_id]').val();
		params.acc_year_id = $('#glotherrefFrm  [name=acc_year_id]').val();
		params.date_from = $('#glotherrefFrm  [name=date_from]').val();
		params.date_to = $('#glotherrefFrm  [name=date_to]').val();
		params.code_from = $('#glotherrefFrm  [name=code_from]').val();
		params.code_to = $('#glotherrefFrm  [name=code_to]').val();
		params.other_ref_no = $('#glotherrefFrm  [name=other_ref_name]').val();

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
		let d= axios.get(msApp.baseUrl()+"/glotherref/getYear",{params})
		.then(function (response) {
			MsGlOtherRef.setYear(response.data);
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
				$('#glotherrefFrm  [name=acc_year_id]').val(value.id);
				$('#glotherrefFrm  [name=date_from]').val(value.start_date);
				$('#glotherrefFrm  [name=date_to]').val(value.end_date);
			}
		});
	}

	getDateRange(acc_year_id){
		let params={};
		params.acc_year_id=acc_year_id;
		let d= axios.get(msApp.baseUrl()+"/glotherref/getDateRange",{params})
		.then(function (response) {
			MsGlOtherref.setDateRange(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	setDateRange(data)
	{
		$.each(data, function(key, value) {
			
			$('#glotherrefFrm  [name=date_from]').val(value.start_date);
			$('#glotherrefFrm  [name=date_to]').val(value.end_date);
		});
	}
	
	showJournal(id)
	{
		window.open(msApp.baseUrl()+"/acctransprnt/journalpdf?id="+id);
	}

	pdf	(){
		let company_id = $('#glotherrefFrm  [name=company_id]').val();
		let acc_year_id = $('#glotherrefFrm  [name=acc_year_id]').val();
		let date_from = $('#glotherrefFrm  [name=date_from]').val();
		let date_to = $('#glotherrefFrm  [name=date_to]').val();
		let code_from = $('#glotherrefFrm  [name=code_from]').val();
		let code_to = $('#glotherrefFrm  [name=code_to]').val();
		let other_ref_no = $('#glotherrefFrm  [name=other_ref_name]').val();
		if(company_id=='' || company_id==0){
			alert('Select Company');
			return;
		}
		if(acc_year_id=='' || acc_year_id==0){
			alert('Select Year');
			return;
		}
		window.open(msApp.baseUrl()+"/glotherref/pdf?company_id="+company_id+"&acc_year_id="+acc_year_id+"&date_from="+date_from+"&date_to="+date_to+"&code_from="+code_from+"&code_to="+code_to+"&other_ref_no="+other_ref_no);
	}

	otherrefsearch(){

		let data={};
		let name=$('#glotherrefsearchFrm [name=name]').val();
		let code=$('#glotherrefsearchFrm [name=code]').val();
		let vendor_code=$('#glotherrefsearchFrm  [name=vendor_code]').val();
		data.name=name;
		data.code=code;
		data.vendor_code=vendor_code;
		this.getOtherRef(data);
	}

	openotherrefWindow()
	{
		$('#glotherrefFrm [name=other_ref_name]').val();

		$('#glotherrefsearchWindow').window('open');
		MsGlOtherRef.otherrefsearch()
	}

	getOtherRef(data){
		let trans=msApp.getJson("supplier/getOtherref",data);
		trans.then(function (response){
			$('#glotherrefsearchTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
	  });
	}

	showGridOtherRef(data)
	{
		let self=this;
		$('#glotherrefsearchTbl').datagrid({
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

	closeOtherRefWindow(type){

		let id=[];
		let name=[];
		let checked=$('#glotherrefsearchTbl').datagrid('getSelections');
		if(checked.lenght >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
				id.push(val.id)
				name.push(val.name)
		});
		id=id.join(',');
		$('#glotherrefFrm  [name=supplier_id]').val(id);
		$('#glotherrefFrm  [name=other_ref_name]').val(name);
		$('#glotherrefsearchWindow').window('close');
		if(type=='html')
		{
			MsGlOtherRef.get();
		}
		else if(type=='pdf')
		{
			MsGlOtherRef.pdf();
		}	

	}
///////////////////////////////////
	openRefNoWindow()
	{
		let params={};
		params.company_id = $('#glotherrefFrm  [name=company_id]').val();
		if(params.company_id=='' || params.company_id==0){
			alert('Select Company');
			return;
		}
		$('#refsearchWindow').window('open');
	}

	getRefNo(data){
		let company_id = $('#glotherrefFrm  [name=company_id]').val();
		if(company_id=='' || company_id==0){
			alert('Select Company');
			return;
		}
		$('#refsearchWindow').window('open');
		let trans=msApp.getJson("glotherref/getreferenceno",data);
		trans.then(function (response){
			$('#refnosearchTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
	  });

	}

	showGridRefNo(data)
	{
		let self=this;
		$('#refnosearchTbl').datagrid({
			//method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			//url:this.route,
			//checkbox:true,
			onClickRow: function(index,row){
				//$('#glotherrefFrm  [name=supplier_id]').val(row.id);
				$('#glotherrefFrm  [name=other_ref_name]').val(row.other_ref_no);
				$('#refsearchWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	codefromWindow(sale_order_id)
	{
		let data= axios.get(msApp.baseUrl()+"/gl/getcode");
		data.then(function (response) {
			$('#glotherrefcodefromTbl').datagrid('loadData', response.data);
			$('#glotherrefcodefromwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	codetoWindow(sale_order_id)
	{
		let data= axios.get(msApp.baseUrl()+"/gl/getcode");
		data.then(function (response) {
			$('#glotherrefcodetoTbl').datagrid('loadData', response.data);
			$('#glotherrefcodetowindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showcodefromGrid(data)
	{
		var dg = $('#glotherrefcodefromTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			checkbox:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			onClickRow: function(index,row){
			 $('#glotherrefFrm  [name=code_from]').val(row.code);
			 $('#glotherrefcodefromwindow').window('close');
			},
            //groupField:'account',
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}
	showcodetoGrid(data)
	{
		var dg = $('#glotherrefcodetoTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			checkbox:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			onClickRow: function(index,row){
			$('#glotherrefFrm  [name=code_to]').val(row.code);
			$('#glotherrefcodetowindow').window('close');
			},
            //groupField:'account',
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

}
window.MsGlOtherRef=new MsGlOtherRefController(new MsGlOtherRefModel());
MsGlOtherRef.showGrid([]);
MsGlOtherRef.showGridOtherRef([]);
MsGlOtherRef.showGridRefNo([]);

MsGlOtherRef.showcodefromGrid([]);
MsGlOtherRef.showcodetoGrid([]);