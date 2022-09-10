//require('../jquery.easyui.min.js');

let MsEmployeeHRModel = require('./MsEmployeeHRModel');
require('./../datagrid-filter.js');
class MsEmployeeHRController {
	constructor(MsEmployeeHRModel)
	{
		this.MsEmployeeHRModel = MsEmployeeHRModel;
		this.formId='employeehrFrm';
		this.dataTable='#employeehrTbl';
		this.route=msApp.baseUrl()+"/employeehr"
	}

	submit()
	{
		$.blockUI({
			message: '<i class="icon-spinner4 spinner">Saving...</i>',
			overlayCSS: {
				backgroundColor: '#1b2024',
				opacity: 0.8,
				zIndex: 999999,
				cursor: 'wait'
			},
			css: {
				border: 0,
				color: '#fff',
				padding: 0,
				zIndex: 9999999,
				backgroundColor: 'transparent'
			}
		});	
		let formObj=msApp.get(this.formId);
        if(formObj.id){
            this.MsEmployeeHRModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
        }else{
            this.MsEmployeeHRModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
        }
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#employeehrFrm [id="designation_id"]').combobox('setValue', '');
		$('#employeehrFrm [id="department_id"]').combobox('setValue', '');
		$('#employeehrFrm [id="subsection_id"]').combobox('setValue', '');
		$('#employeehrFrm [id="section_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsEmployeeHRModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsEmployeeHRModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#employeehrTbl').datagrid('reload');
		msApp.resetForm('employeehrFrm');
		$('#employeehrFrm [id="designation_id"]').combobox('setValue', '');
		$('#employeehrFrm [id="department_id"]').combobox('setValue', '');
		$('#employeehrFrm [id="subsection_id"]').combobox('setValue', '');
		$('#employeehrFrm [id="section_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let empcombo=this.MsEmployeeHRModel.get(index,row);
		empcombo.then(function (response) {		
			$('#employeehrFrm [id="designation_id"]').combobox('setValue', response.data.fromData.designation_id);
			$('#employeehrFrm [id="department_id"]').combobox('setValue', response.data.fromData.department_id);
			$('#employeehrFrm [id="subsection_id"]').combobox('setValue', response.data.fromData.subsection_id);
			$('#employeehrFrm [id="section_id"]').combobox('setValue', response.data.fromData.section_id);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	getHrGrid(){
		let params={};
		params.company_id=$('#employhrsearchFrm [name=company_id]').val();
		params.designation_id=$('#employhrsearchFrm [name=designation_id]').val();
		params.department_id=$('#employhrsearchFrm [name=department_id]').val();
		params.location_id=$('#employhrsearchFrm [name=location_id]').val();
		return params;
	}

	searchshowGrid(){
		let params=this.getHrGrid();
		let grid= axios.get(this.route,{params})
		.then(function(response){
			$('#employeehrTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
		return grid;
	}

	showGrid(data)
	{
		let self=this;
		var ac=$('#employeehrTbl').datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			rownumbers:true,
			showFooter:true,
			//fitColumns:true,
			emptyMsg:'No Record Found',
			//url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var tsalary=0;
				
				for(var i=0; i<data.rows.length; i++){
				tsalary+=data.rows[i]['salary'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
					{ 
						salary: tsalary.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
				
				
			}
		});
		ac.datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsEmployeeHR.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openUserWindow(){
		$('#openuserwindow').window('open');
		
	}
	getUserParams(){
		let params={};
		params.name = $('#usersearchFrm  [name=name]').val();
		params.email = $('#usersearchFrm  [name=email]').val();
		return params;
	}
	searchUser(){
		let params=this.getUserParams();
		let usr= axios.get(this.route+'/getuser',{params})
		.then(function(response){
			$('#usersearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
		return usr;

	}
	showUserGrid(data){
		let self=this;
		var ff=$('#usersearchTbl').datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#employeehrFrm  [name=user_id]').val(row.id);
				$('#employeehrFrm  [name=user_name]').val(row.user_name);
				$('#openuserwindow').window('close')
			}
		});
		ff.datagrid('enableFilter').datagrid('loadData',data);
	}

	openEmpHrWindow(){
		$('#openemployhrwindow').window('open');
	}

	getEmployeeParams(){
		let params={};
		params.company_id=$('#employtoreportFrm [name=company_id]').val();
		params.designation_id=$('#employtoreportFrm [name=designation_id]').val();
		params.department_id=$('#employtoreportFrm [name=department_id]').val();
		return params;
	}

	searchEmployeeHr(){
		let params=this.getEmployeeParams();
		let rpt = axios.get(this.route+"/toreportemployee",{params})
		.then(function(response){
			$('#employhrsearchTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});
		return rpt;
	}

	showReportEmployeeGrid(data){
		let self=this;
		var pr=$('#employhrsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#employeehrFrm  [name=report_to_id]').val(row.id);
				$('#employeehrFrm  [name=report_to_name]').val(row.employee_name);
				$('#openemployhrwindow').window('close')
			}
		});
		pr.datagrid('enableFilter').datagrid('loadData',data);
	}

	openSignatoryWindow(){
		$('#opensignatorywindow').window('open');
	}

	getSignatoryParams(){
		let params={};
		params.company_id=$('#signatorysearchFrm [name=company_id]').val();
		params.designation_id=$('#signatorysearchFrm [name=designation_id]').val();
		params.department_id=$('#signatorysearchFrm [name=department_id]').val();
		return params;
	}

	searchSignatory(){
		let params=this.getSignatoryParams();
		let d = axios.get(this.route+"/toreportemployee",{params})
		.then(function(response){
			$('#signatorysearchTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		})
	}

	showSignatoryGrid(data){
		let self=this;
		var sg=$('#signatorysearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#employeehrFrm  [name=signatory_id]').val(row.id);
				$('#employeehrFrm  [name=signatory_name]').val(row.employee_name);
				$('#opensignatorywindow').window('close')
			}
		});
		sg.datagrid('enableFilter').datagrid('loadData',data);
	}

	pdf(){
		var id= $('#employeehrFrm  [name=id]').val();
		if(id==""){
			alert("Select an Employee");
			return;
		}
		window.open(this.route+"/appointletter?id="+id);
	}

	ndapdf(){
		var id= $('#employeehrFrm  [name=id]').val();
		if(id==""){
			alert("Select an Employee");
			return;
		}
		window.open(this.route+"/getndapdf?id="+id);
	}

}
window.MsEmployeeHR = new MsEmployeeHRController(new MsEmployeeHRModel());
MsEmployeeHR.showGrid([]);
MsEmployeeHR.showUserGrid([]);
MsEmployeeHR.showReportEmployeeGrid([]);
MsEmployeeHR.showSignatoryGrid([]);
$('#hrEmployeetabs').tabs({
	onSelect:function(title,index){
	   let employee_h_r_id = $('#employeehrFrm  [name=id]').val();

		var data={};
		data.employee_h_r_id=employee_h_r_id;

		if(index==1){
			if(employee_h_r_id===''){
				$('#hrEmployeetabs').tabs('select',0);
				msApp.showError('Select An Employee First',0);
				return;
			}
			$('#employeehrjobFrm  [name=employee_h_r_id]').val(employee_h_r_id)
			MsEmployeeHRJob.showGrid(employee_h_r_id);
		}
		if(index==2){
			if(employee_h_r_id===''){
				$('#hrEmployeetabs').tabs('select',0);
				msApp.showError('Select An Employee First',0);
				return;
			}
			$('#employeehrleaveFrm  [name=employee_h_r_id]').val(employee_h_r_id)
			MsEmployeeHRLeave.showGrid(employee_h_r_id);
		}
	}
});