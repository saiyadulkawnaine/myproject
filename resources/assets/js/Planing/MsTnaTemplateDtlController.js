let MsTnaTemplateDtlModel = require('./MsTnaTemplateDtlModel');
class MsTnaTemplateDtlController {
	constructor(MsTnaTemplateDtlModel)
	{
		this.MsTnaTemplateDtlModel = MsTnaTemplateDtlModel;
		this.formId='tnatemplatedtlFrm';
		this.dataTable='#tnatemplatedtlTbl';
		this.route=msApp.baseUrl()+"/tnatemplatedtl"
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
			this.MsTnaTemplateDtlModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsTnaTemplateDtlModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#tnatemplatedtlFrm [id="tnatask_id"]').combobox('setValue', '');
		$('#tnatemplatedtlFrm [id="depending_task_id"]').combobox('setValue','');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsTnaTemplateDtlModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsTnaTemplateDtlModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#tnatemplatedtlTbl').datagrid('reload');
		msApp.resetForm('tnatemplatedtlFrm');
		$('#tnatemplatedtlFrm [name=tna_template_id]').val($('#tnatemplateFrm [name=id]').val());
		$('#tnatemplatedtlFrm [id="tnatask_id"]').combobox('setValue', '');
		$('#tnatemplatedtlFrm [id="depending_task_id"]').combobox('setValue','');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		let taskdtl=this.MsTnaTemplateDtlModel.get(index,row);
		taskdtl.then(function (response) {
			$('#tnatemplatedtlFrm [id="tnatask_id"]').combobox('setValue', response.data.fromData.tnatask_id);
			$('#tnatemplatedtlFrm [id="depending_task_id"]').combobox('setValue', response.data.fromData.depending_task_id);
		}).catch(function (error) {
			console.log(error);
		});

	}

	showGrid(tna_template_id)
	{
		let self=this;
		var data={};
		data.tna_template_id=tna_template_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsTnaTemplateDtl.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}


}
window.MsTnaTemplateDtl=new MsTnaTemplateDtlController(new MsTnaTemplateDtlModel());