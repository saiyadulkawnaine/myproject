require('./../datagrid-filter.js');
let MsTnaTemplateModel = require('./MsTnaTemplateModel');
class MsTnaTemplateController {
	constructor(MsTnaTemplateModel)
	{
		this.MsTnaTemplateModel = MsTnaTemplateModel;
		this.formId='tnatemplateFrm';
		this.dataTable='#tnatemplateTbl';
		this.route=msApp.baseUrl()+"/tnatemplate"
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
            this.MsTnaTemplateModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
        }else{
            this.MsTnaTemplateModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
        }
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#tnatemplateFrm [id="buyer_id"]').combobox('setValue','');

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsTnaTemplateModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsTnaTemplateModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#tnatemplateTbl').datagrid('reload');
		msApp.resetForm('tnatemplateFrm');
		$('#tnatemplateFrm [id="buyer_id"]').combobox('setValue','');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let task=this.MsTnaTemplateModel.get(index,row);
		task.then(function (response) {
			$('#tnatemplateFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
		}).catch(function (error) {
			console.log(error);
		});

	}

	showGrid()
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsTnaTemplate.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsTnaTemplate = new MsTnaTemplateController(new MsTnaTemplateModel());
MsTnaTemplate.showGrid();

$('#tnatemplatetabs').tabs({
	onSelect:function(title,index){
		let tna_template_id = $('#tnatemplateFrm [name=id]').val();
		 
		var data={};
		data.tna_template_id=tna_template_id;

		if(index==1){
			if(tna_template_id===''){
				$('#tnatemplatetabs').tabs('select',0);
				msApp.showError('Select a Template First',0);
				return;
			}
			//msApp.resetForm('tnatemplatedtlFrm');
			$('#tnatemplatedtlFrm [name=tna_template_id]').val(tna_template_id);
			MsTnaTemplateDtl.showGrid(tna_template_id);
		}
					
	}
});