let MsSmsSetupSmsToModel = require('./MsSmsSetupSmsToModel');
class MsSmsSetupSmsToController {
	constructor(MsSmsSetupSmsToModel)
	{
		this.MsSmsSetupSmsToModel = MsSmsSetupSmsToModel;
		this.formId='smssetupsmstoFrm';
		this.dataTable='#smssetupsmstoTbl';
		this.route=msApp.baseUrl()+"/smssetupsmsto";
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
			this.MsSmsSetupSmsToModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSmsSetupSmsToModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#smssetupsmstoFrm [name=sms_setup_id]').val($('#smssetupFrm [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsSmsSetupSmsToModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSmsSetupSmsToModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#smssetupsmstoTbl').datagrid('reload');
		msApp.resetForm('smssetupsmstoFrm');
		$('#smssetupsmstoFrm [name=sms_setup_id]').val($('#smssetupFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSmsSetupSmsToModel.get(index,row);
	}

	showGrid(sms_setup_id)
	{
		let self=this;
		let data={};
		data.sms_setup_id=sms_setup_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			queryParams:data,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSmsSetupSmsTo.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	openSmsEmployee(){
		$('#smssetupsmstoEmployeeWindow').window('open');
	}

	getParams(){
		let params = {}
		params.designation_id=$('#smssetupsmstoSearchFrm [name=designation_id]').val();
		params.department_id=$('#smssetupsmstoSearchFrm [name=department_id]').val();
		params.company_id=$('#smssetupsmstoSearchFrm [name=company_id]').val();
		return params;
	}
	searchSmsEmployeeGrid(){
		let params=this.getParams();
		let emp = axios.get(this.route + '/getemployee', { params }).then(function(response){
			$('#smssetupsmstoSearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		});

	}

	showEmployeeGrid(data){
		let self = this;
		$('#smssetupsmstoSearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				// self.edit(index,row);
				$('#smssetupsmstoFrm [name=employee_h_r_id]').val(row.id);
				$('#smssetupsmstoFrm [name=name]').val(row.name);
				$('#smssetupsmstoSearchTbl').datagrid('loadData',[]);
				$('#smssetupsmstoEmployeeWindow').window('close');
			}
		}).datagrid('enableFilter');
	}
}
window.MsSmsSetupSmsTo=new MsSmsSetupSmsToController(new MsSmsSetupSmsToModel());
MsSmsSetupSmsTo.showEmployeeGrid([]);