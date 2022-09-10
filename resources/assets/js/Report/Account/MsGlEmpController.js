let MsGlEmpModel = require('./MsGlEmpModel');
require('./../../datagrid-filter.js');

class MsGlEmpController {
	constructor(MsGlEmpModel)
	{
		this.MsGlEmpModel = MsGlEmpModel;
		this.formId='glempFrm';
		this.dataTable='#glempTbl';
		this.route=msApp.baseUrl()+"/glemp/html";
	}

	get(){
		let params={};
		params.company_id = $('#glempFrm  [name=company_id]').val();
		params.acc_year_id = $('#glempFrm  [name=acc_year_id]').val();
		params.date_from = $('#glempFrm  [name=date_from]').val();
		params.date_to = $('#glempFrm  [name=date_to]').val();
		params.code_from = $('#glempFrm  [name=code_from]').val();
		params.code_to = $('#glempFrm  [name=code_to]').val();
        params.employee_id = $('#glempFrm  [name=employee_id]').val();

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
			alert('Error Found')
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
		let d= axios.get(msApp.baseUrl()+"/glemp/getYear",{params})
		.then(function (response) {
			MsGlEmp.setYear(response.data);
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
				$('#glempFrm  [name=acc_year_id]').val(value.id);
				$('#glempFrm  [name=date_from]').val(value.start_date);
				$('#glempFrm  [name=date_to]').val(value.end_date);
			}
		});
	}

	getDateRange(acc_year_id){
		let params={};
		params.acc_year_id=acc_year_id;
		let d= axios.get(msApp.baseUrl()+"/glemp/getDateRange",{params})
		.then(function (response) {
			MsGlEmp.setDateRange(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	setDateRange(data)
	{
		$.each(data, function(key, value) {
			
			$('#glempFrm  [name=date_from]').val(value.start_date);
			$('#glempFrm  [name=date_to]').val(value.end_date);
		});
	}
	
	showJournal(id)
	{
		window.open(msApp.baseUrl()+"/acctransprnt/journalpdf?id="+id);
	}

	pdf	(){
		let company_id = $('#glempFrm  [name=company_id]').val();
		let acc_year_id = $('#glempFrm  [name=acc_year_id]').val();
		let date_from = $('#glempFrm  [name=date_from]').val();
		let date_to = $('#glempFrm  [name=date_to]').val();
		let code_from = $('#glempFrm  [name=code_from]').val();
		let code_to = $('#glempFrm  [name=code_to]').val();
		let employee_id = $('#glempFrm  [name=employee_id]').val();
		if(company_id=='' || company_id==0){
			alert('Select Company');
			return;
		}
		if(acc_year_id=='' || acc_year_id==0){
			alert('Select Year');
			return;
		}
		window.open(msApp.baseUrl()+"/glemp/pdf?company_id="+company_id+"&acc_year_id="+acc_year_id+"&date_from="+date_from+"&date_to="+date_to+"&code_from="+code_from+"&code_to="+code_to+"&employee_id="+employee_id);
	}

	employeesearch(){

		let data={};
		let name=$('#glemployeesearchFrm  [name=name]').val();
		let code=$('#glemployeesearchFrm  [name=code]').val();
		let contact=$('#glemployeesearchFrm  [name=contact]').val();
		let email=$('#glemployeesearchFrm  [name=email]').val();
		data.name=name;
        data.code=code;
        data.contact=contact;
        data.email=email;
        this.getEmployee(data);

	}

	openemployeeWindow()
	{
		$('#glempFrm  [name=employee_name]').val();
		
		$('#glemployeesearchWindow').window('open');
		MsGlEmp.employeesearch()
		
    }
    getEmployee(data){
		let trans=msApp.getJson("employee/getEmployee",data);
		trans.then(function (response) {
			$('#glemployeesearchTbl').datagrid('loadData', response.data);

            //MsTransPrnt.showGrid(response.data);
        })
        .catch(function (error) {
            console.log(error);
        });
	}

	showGridEmployee(data)
	{
		let self=this;
		$('#glemployeesearchTbl').datagrid({
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

	closeEmployeeWindow(type){

		let id=[];
		let name=[];
		let checked=$('#glemployeesearchTbl').datagrid('getSelections');
		if(checked.lenght >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
				id.push(val.id)
				name.push(val.name)
		});
		id=id.join(',');
		$('#glempFrm  [name=employee_id]').val(id);
		$('#glempFrm  [name=employee_name]').val(name);
		$('#glemployeesearchWindow').window('close');
		if(type=='html')
		{
			MsGlEmp.get();
		}
		else if(type=='pdf')
		{
			MsGlEmp.pdf();
		}
		

	}

	codefromWindow(sale_order_id)
	{
		let data= axios.get(msApp.baseUrl()+"/gl/getcode");
		data.then(function (response) {
			$('#glempcodefromTbl').datagrid('loadData', response.data);
			$('#glempcodefromwindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	codetoWindow(sale_order_id)
	{
		let data= axios.get(msApp.baseUrl()+"/gl/getcode");
		data.then(function (response) {
			$('#glempcodetoTbl').datagrid('loadData', response.data);
			$('#glempcodetowindow').window('open');	    
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showcodefromGrid(data)
	{
		var dg = $('#glempcodefromTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			checkbox:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			onClickRow: function(index,row){
			 $('#glempFrm  [name=code_from]').val(row.code);
			 $('#glempcodefromwindow').window('close');
			},
            //groupField:'account',
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}
	showcodetoGrid(data)
	{
		var dg = $('#glempcodetoTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			checkbox:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			onClickRow: function(index,row){
			$('#glempFrm  [name=code_to]').val(row.code);
			$('#glempcodetowindow').window('close');
			},
            //groupField:'account',
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	


}
window.MsGlEmp=new MsGlEmpController(new MsGlEmpModel());
MsGlEmp.showGrid([]);
MsGlEmp.showGridEmployee([]);
MsGlEmp.showcodefromGrid([]);
MsGlEmp.showcodetoGrid([]);
