let MsGlLoanRefModel = require('./MsGlLoanRefModel');
require('./../../datagrid-filter.js');

class MsGlLoanRefController {
	constructor(MsGlLoanRefModel)
	{
		this.MsGlLoanRefModel = MsGlLoanRefModel;
		this.formId='glloanrefFrm';
		this.dataTable='#glloanrefTbl';
		this.route=msApp.baseUrl()+"/glloanref/html";
	}

	get(){
		let params={};
		params.company_id = $('#glloanrefFrm  [name=company_id]').val();
		params.acc_year_id = $('#glloanrefFrm  [name=acc_year_id]').val();
		params.date_from = $('#glloanrefFrm  [name=date_from]').val();
		params.date_to = $('#glloanrefFrm  [name=date_to]').val();
		params.code_from = $('#glloanrefFrm  [name=code_from]').val();
		params.code_to = $('#glloanrefFrm  [name=code_to]').val();
		params.loan_ref_name = $('#glloanrefFrm  [name=loan_ref_name]').val();


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
		let d= axios.get(msApp.baseUrl()+"/glloanref/getYear",{params})
		.then(function (response) {
			MsGlLoanRef.setYear(response.data);
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
				$('#glloanrefFrm  [name=acc_year_id]').val(value.id);
				$('#glloanrefFrm  [name=date_from]').val(value.start_date);
				$('#glloanrefFrm  [name=date_to]').val(value.end_date);
			}
		});
	}

	getDateRange(acc_year_id){
		let params={};
		params.acc_year_id=acc_year_id;
		let d= axios.get(msApp.baseUrl()+"/glloanref/getDateRange",{params})
		.then(function (response) {
			MsGlLoanRef.setDateRange(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	setDateRange(data)
	{
		$.each(data, function(key, value) {
			
			$('#glloanrefFrm  [name=date_from]').val(value.start_date);
			$('#glloanrefFrm  [name=date_to]').val(value.end_date);
		});
	}
	
	showJournal(id)
	{
		window.open(msApp.baseUrl()+"/acctransprnt/journalpdf?id="+id);
	}

	pdf	(){
		let company_id = $('#glloanrefFrm  [name=company_id]').val();
		let acc_year_id = $('#glloanrefFrm  [name=acc_year_id]').val();
		let date_from = $('#glloanrefFrm  [name=date_from]').val();
		let date_to = $('#glloanrefFrm  [name=date_to]').val();
		let code_from = $('#glloanrefFrm  [name=code_from]').val();
		let code_to = $('#glloanrefFrm  [name=code_to]').val();
		let supplier_id = $('#glloanrefFrm  [name=supplier_id]').val();
		let loan_ref_name = $('#glloanrefFrm  [name=loan_ref_name]').val();

		if(company_id=='' || company_id==0){
			alert('Select Company');
			return;
		}
		if(acc_year_id=='' || acc_year_id==0){
			alert('Select Year');
			return;
		}
		window.open(msApp.baseUrl()+"/glloanref/pdf?company_id="+company_id+"&acc_year_id="+acc_year_id+"&date_from="+date_from+"&date_to="+date_to+"&code_from="+code_from+"&code_to="+code_to+"&supplier_id="+supplier_id+"loan_ref_name="+loan_ref_name);
	}

	loanrefsearch(){

		let data={};
		let name=$('#glloanrefsearchFrm [name=name]').val();
		let code=$('#glloanrefsearchFrm [name=code]').val();
		let vendor_code=$('#glloanrefsearchFrm  [name=vendor_code]').val();
		data.name=name;
		data.code=code;
		data.vendor_code=vendor_code;
		this.getLoanRef(data);
	}

	openloanrefWindow()
	{
		$('#glloanrefFrm [name=loan_ref_name]').val();

		$('#glloanrefsearchWindow').window('open');
		MsGlLoanRef.loanrefsearch()
	}

	getLoanRef(data){
		let trans=msApp.getJson("supplier/getLoanRef",data);
		trans.then(function (response){
			$('#glloanrefsearchTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
	  });
	}

	showGridLoanRef(data)
	{
		let self=this;
		$('#glloanrefsearchTbl').datagrid({
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

	closeLoanRefWindow(type){

		let id=[];
		let name=[];
		let checked=$('#glloanrefsearchTbl').datagrid('getSelections');
		if(checked.lenght >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
				id.push(val.id)
				name.push(val.name)
		});
		id=id.join(',');
		$('#glloanrefFrm  [name=supplier_id]').val(id);
		$('#glloanrefFrm  [name=loan_ref_name]').val(name);
		$('#glloanrefsearchWindow').window('close');
		if(type=='html')
		{
			MsGlLoanRef.get();
		}
		else if(type=='pdf')
		{
			MsGlLoanRef.pdf();
		}
		

	}

	codefromWindow(sale_order_id)
	{
		let data= axios.get(msApp.baseUrl()+"/gl/getcode");
		data.then(function (response) {
			$('#glloanrefcodefromTbl').datagrid('loadData', response.data);
			$('#glloanrefcodefromwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	codetoWindow(sale_order_id)
	{
		let data= axios.get(msApp.baseUrl()+"/gl/getcode");
		data.then(function (response) {
			$('#glloanrefcodetoTbl').datagrid('loadData', response.data);
			$('#glloanrefcodetowindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showcodefromGrid(data)
	{
		var dg = $('#glloanrefcodefromTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			checkbox:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			onClickRow: function(index,row){
			 $('#glloanrefFrm  [name=code_from]').val(row.code);
			 $('#glloanrefcodefromwindow').window('close');
			},
            //groupField:'account',
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}
	showcodetoGrid(data)
	{
		var dg = $('#glloanrefcodetoTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			checkbox:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			onClickRow: function(index,row){
			$('#glloanrefFrm  [name=code_to]').val(row.code);
			$('#glloanrefcodetowindow').window('close');
			},
            //groupField:'account',
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

}
window.MsGlLoanRef=new MsGlLoanRefController(new MsGlLoanRefModel());
MsGlLoanRef.showGrid([]);
MsGlLoanRef.showGridLoanRef([]);

MsGlLoanRef.showcodefromGrid([]);
MsGlLoanRef.showcodetoGrid([]);