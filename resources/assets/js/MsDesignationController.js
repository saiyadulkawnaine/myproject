//require('./jquery.easyui.min.js');
let MsDesignationModel = require('./MsDesignationModel');
require('./datagrid-filter.js');

class MsDesignationController {
	constructor(MsDesignationModel)
	{
		this.MsDesignationModel = MsDesignationModel;
		this.formId='designationFrm';
		this.dataTable='#designationTbl';
		this.route=msApp.baseUrl()+"/designation"
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
			this.MsDesignationModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsDesignationModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#designationFrm [id="designation_level_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsDesignationModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsDesignationModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#designationTbl').datagrid('reload');
		//$('#DesignationFrm  [name=id]').val(d.id);
		msApp.resetForm('designationFrm');
		$('#designationFrm [id="designation_level_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let desg=this.MsDesignationModel.get(index,row);
		desg.then(function (response) {		
			$('#designationFrm [id="designation_level_id"]').combobox('setValue', response.data.fromData.designation_level_id);
})
		.catch(function (error) {
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
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsDesignation.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsDesignation=new MsDesignationController(new MsDesignationModel());
MsDesignation.showGrid();
