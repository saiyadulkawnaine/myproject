let MsFloorSectionModel = require('./MsFloorSectionModel');
class MsFloorSectionController {
	constructor(MsFloorSectionModel)
	{
		this.MsFloorSectionModel = MsFloorSectionModel;
		this.formId='floorsectionFrm';
		this.dataTable='#floorsectionTbl';
		this.route=msApp.baseUrl()+"/floorsection"
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

		let formObj=msApp.get('floorsectionFrm');
		let i=1;
		$.each($('#floorsectionTbl').datagrid('getChecked'), function (idx, val) {
				formObj['floor_id['+i+']']=val.id
			i++;
		});
		this.MsFloorSectionModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	create(){
		var section_id=$('#sectionFrm  [name=id]').val()
		let data= axios.get(msApp.baseUrl()+"/floorsection/create?section_id="+section_id);
		data.then(function (response) {
		$('#floorsectionTbl').datagrid({
		checkbox:true,
		rownumbers:true,
		data: response.data.unsaved,
		
		columns:[[
		{field:'ck',checkbox:true,width:40},
		{field:'name',title:'Floor',width:120},
		]],
		});
		
		$('#floorsectionsavedTbl').datagrid({
		rownumbers:true,
		data: response.data.saved,
		columns:[[
		{field:'name',title:'Floor',width:120},
		{field:'action',title:'',width:60,formatter:MsFloorSection.formatDetail},
		]],
		});
		})
		.catch(function (error) {
		console.log(error);
		});
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsFloorSectionModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		//alert(id)
		this.MsFloorSectionModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#floorsectionTbl').datagrid('reload');
		MsFloorSection.create()
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsFloorSectionModel.get(index,row);
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
		});
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsFloorSection.delete(event,'+row.floor_section_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsFloorSection=new MsFloorSectionController(new MsFloorSectionModel());