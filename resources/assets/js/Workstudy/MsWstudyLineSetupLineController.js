let MsWstudyLineSetupLineModel = require('./MsWstudyLineSetupLineModel');
class MsWstudyLineSetupLineController {
	constructor(MsWstudyLineSetupLineModel)
	{
		this.MsWstudyLineSetupLineModel = MsWstudyLineSetupLineModel;
		this.formId='wstudylinesetuplineFrm';
		this.dataTable='#wstudylinesetuplineTbl';
		this.route=msApp.baseUrl()+"/wstudylinesetupline";
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

		let formObj=msApp.get('wstudylinesetuplineFrm');
		let i=1;
		let subsection_id_arr = [];

		$.each($('#wstudylinesetuplineTbl').datagrid('getChecked'), function (idx, val) {
			formObj['subsection_id['+i+']']=val.id;
			subsection_id_arr.push(val.id*1);
			i++;
		});

		$.each($('#wstudylinesetuplinesavedTbl').datagrid('getRows'), function (idx, val) {
			subsection_id_arr.push(val.id*1);
		});

		let subsection_id_string=subsection_id_arr.sort(function(a, b){return a-b});
		formObj.subsection_id_string=subsection_id_string.join(',');

		this.MsWstudyLineSetupLineModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	create(){
		var wstudy_line_setup_id=$('#wstudylinesetupFrm  [name=id]').val()
		let data= axios.get(msApp.baseUrl()+"/wstudylinesetupline/create?wstudy_line_setup_id="+wstudy_line_setup_id);
				data.then(function (response) {
					let line_merged_id=response.data.line_merged_id*1;
					$('#wstudylinesetuplineTbl').datagrid({
						checkbox:true,
						rownumbers:true,
						singleSelect: line_merged_id ?false:true,
						data: response.data.unsaved,
						columns:[[
							{field:'ck',checkbox:true,width:40},
							{field:'name',title:'Name',width:80},
							{field:'code',title:'Line Name',width:80},
							{field:'floor_id',title:'Floor',width:120},
							{field:'sort_id',title:'Sequence',width:60}
						]],
					});
					$('#wstudylinesetuplinesavedTbl').datagrid({
						rownumbers:true,
						data: response.data.saved,
						columns:[[
							{field:'name',title:'Name',width:80},
							{field:'code',title:'Line Name',width:80},
							{field:'floor_id',title:'Floor',width:120},
							{field:'sort_id',title:'Sequence',width:60},
							{field:'action',title:'',width:60,formatter:MsWstudyLineSetupLine.formatDetail}
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
		this.MsWstudyLineSetupLineModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		let formObj=msApp.get('wstudylinesetuplineFrm');
		let subsection_id_arr = [];
		$.each($('#wstudylinesetuplinesavedTbl').datagrid('getRows'), function (idx, val) {
			if(id*1 !== val.wstudy_line_setup_line_id*1){
				subsection_id_arr.push(val.id*1);
			}
		});
		let subsection_id_string=subsection_id_arr.sort(function(a, b){return a-b});
		formObj.subsection_id_string=subsection_id_string.join(',');
		event.stopPropagation()
		//alert(id)
		this.MsWstudyLineSetupLineModel.save(this.route+"/"+id,'DELETE',msApp.qs.stringify(formObj),this.response);
	}

	response(d)
	{
		MsWstudyLineSetupLine.create();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsWstudyLineSetupLineModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsWstudyLineSetupLine.delete(event,'+row.wstudy_line_setup_line_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsWstudyLineSetupLine=new MsWstudyLineSetupLineController(new MsWstudyLineSetupLineModel());