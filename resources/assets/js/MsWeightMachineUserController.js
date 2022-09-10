let MsWeightMachineUserModel = require('./MsWeightMachineUserModel');
class MsWeightMachineUserController {
	constructor(MsWeightMachineUserModel)
	{
		this.MsWeightMachineUserModel = MsWeightMachineUserModel;
		this.formId='weightmachineuserFrm';
		this.dataTable='#weightmachineuserTbl';
		this.route=msApp.baseUrl()+"/weightmachineuser"
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

		var weight_machine_id=$('#weightmachineFrm  [name=id]').val()

		let formObj={};
		formObj.weight_machine_id=weight_machine_id;
		let i=1;
		$.each($('#weightmachineuserTbl').datagrid('getChecked'), function (idx, val) {
				formObj['user_id['+i+']']=val.id
				
			i++;
		});
		this.MsWeightMachineUserModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	create(){
		var weight_machine_id=$('#weightmachineFrm  [name=id]').val()
		let data= axios.get(msApp.baseUrl()+"/weightmachineuser/create?weight_machine_id="+weight_machine_id);
				data.then(function (response) {
				$('#weightmachineuserTbl').datagrid({
				checkbox:true,
				rownumbers:true,
				data: response.data.unsaved,
				
				columns:[[
				{field:'ck',checkbox:true,width:40},
				{field:'name',title:'User',width:100},
				]],
				}).datagrid('enableFilter');
				
				$('#weightmachineusersavedTbl').datagrid({
				rownumbers:true,
				data: response.data.saved,
				columns:[[
				{field:'name',title:'User',width:100},
				{field:'action',title:'',width:60,formatter:MsWeightMachineUser.formatDetail},
				]],
				}).datagrid('enableFilter');
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
		this.MsWeightMachineUserModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsWeightMachineUserModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#buyernatureTbl').datagrid('reload');
		MsWeightMachineUser.create()
		//msApp.resetForm('buyernatureFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsWeightMachineUserModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsWeightMachineUser.delete(event,'+row.weight_machine_user_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsWeightMachineUser=new MsWeightMachineUserController(new MsWeightMachineUserModel());

