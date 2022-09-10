//require('./jquery.easyui.min.js');
let MsWeightMachineModel = require('./MsWeightMachineModel');
require('./datagrid-filter.js');

class MsWeightMachineController {
	constructor(MsWeightMachineModel)
	{
		this.MsWeightMachineModel = MsWeightMachineModel;
		this.formId='weightmachineFrm';
		this.dataTable='#weightmachineTbl';
		this.route=msApp.baseUrl()+"/weightmachine"
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
			this.MsWeightMachineModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsWeightMachineModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsWeightMachineModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsWeightMachineModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#weightmachineTbl').datagrid('reload');
		$('#weightmachineFrm  [name=id]').val(d.id);
		//$('#teammemberFrm  [name=team_id]').val(d.id);
		//msApp.resetForm('teamFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsWeightMachineModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsWeightMachine.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsWeightMachine=new MsWeightMachineController(new MsWeightMachineModel());
MsWeightMachine.showGrid();
	$('#utilweightmachinetabs').tabs({
		onSelect:function(title,index){
			let weight_machine_id = $('#weightmachineFrm  [name=id]').val();
			if(index==1){
				if(weight_machine_id===''){
					$('#utilusertabs').tabs('select',0);
					msApp.showError('Select Machine First',0);
					return;
				}
				$('#weightmachineuserFrm  [name=weight_machine_id]').val(weight_machine_id)
				MsWeightMachineUser.create()
			}
		}
	});
