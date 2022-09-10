let MsCompanySubsectionModel = require('./MsCompanySubsectionModel');
class MsCompanySubsectionController {
	constructor(MsCompanySubsectionModel)
	{
		this.MsCompanySubsectionModel = MsCompanySubsectionModel;
		this.formId='companysubsectionFrm';
		this.dataTable='#companysubsectionTbl';
		this.route=msApp.baseUrl()+"/companysubsection"
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

		let formObj=msApp.get('companysubsectionFrm');
		let i=1;
		$.each($('#companysubsectionTbl').datagrid('getChecked'), function (idx, val) {
				formObj['company_id['+i+']']=val.id
				
			i++;
		});
		this.MsCompanySubsectionModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	create(){
		var subsection_id=$('#subsectionFrm  [name=id]').val()
		let data= axios.get(msApp.baseUrl()+"/companysubsection/create?subsection_id="+subsection_id);
				data.then(function (response) {
				$('#companysubsectionTbl').datagrid({
				checkbox:true,
				rownumbers:true,
				data: response.data.unsaved,
				
				columns:[[
				{field:'ck',checkbox:true,width:40},
				{field:'name',title:'Company',width:100},
				]],
				});
				
				$('#companysubsectionsavedTbl').datagrid({
				rownumbers:true,
				data: response.data.saved,
				columns:[[
				{field:'name',title:'Company',width:100},
				{field:'action',title:'',width:60,formatter:MsCompanySubsection.formatDetail},
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
		this.MsCompanySubsectionModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		alert(id)
		this.MsCompanySubsectionModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsCompanySubsection.create()
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsCompanySubsectionModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsCompanySubsection.delete(event,'+row.company_subsection_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsCompanySubsection=new MsCompanySubsectionController(new MsCompanySubsectionModel());

