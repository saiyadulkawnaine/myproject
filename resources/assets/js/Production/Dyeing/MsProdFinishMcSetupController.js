let MsProdFinishMcSetupModel = require('./MsProdFinishMcSetupModel');
require('./../../datagrid-filter.js');
class MsProdFinishMcSetupController {
	constructor(MsProdFinishMcSetupModel)
	{
		this.MsProdFinishMcSetupModel = MsProdFinishMcSetupModel;
		this.formId='prodfinishmcsetupFrm';
		this.dataTable='#prodfinishmcsetupTbl';
		this.route=msApp.baseUrl()+"/prodfinishmcsetup"
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
			this.MsProdFinishMcSetupModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdFinishMcSetupModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdFinishMcSetupModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdFinishMcSetupModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodfinishmcsetupTbl').datagrid('reload');
		msApp.resetForm(this.formId);
		msApp.resetForm('prodfinishmcdateFrm');
		msApp.resetForm('prodfinishmcparameterFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsProdFinishMcSetupModel.get(index,row);
		msApp.resetForm('prodfinishmcdateFrm');
		msApp.resetForm('prodfinishmcparameterFrm');
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
			showFooter:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsProdFinishMcSetup.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	machineWindow() {
		$('#prodfinishmachineWindow').window('open');
	}

	searchMachine()
	{
		let params={};
		params.company_name=$('#prodfinishmachinesearchFrm  [name=company_name]').val();
		params.machine_no=$('#prodfinishmachinesearchFrm  [name=machine_no]').val();
		let data= axios.get(this.route+"/getfinishmachine",{params});
		data.then(function (response) {
			$('#prodfinishmachinesearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	showMachineGrid() {
		$('#prodfinishmachinesearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#prodfinishmcsetupFrm [name=machine_id]').val(row.id);
					$('#prodfinishmcsetupFrm [name=machine_no]').val(row.custom_no);
					$('#prodfinishmcsetupFrm [name=company_name]').val(row.company_name);
					$('#prodfinishmachineWindow').window('close');
				
			}}).datagrid('enableFilter');
	}

}
window.MsProdFinishMcSetup=new MsProdFinishMcSetupController(new MsProdFinishMcSetupModel());
MsProdFinishMcSetup.showGrid();
MsProdFinishMcSetup.showMachineGrid([]);
$('#prodfinishmcsetuptabs').tabs({
	onSelect:function(title,index){
		let prod_finish_mc_setup_id = $('#prodfinishmcsetupFrm  [name=id]').val();
		let prod_finish_mc_date_id = $('#prodfinishmcdateFrm [name=id]').val();
		 var data={};
	    data.prod_finish_mc_setup_id=prod_finish_mc_setup_id;
	    data.prod_finish_mc_date_id=prod_finish_mc_date_id;
		if(index==1){
			if(prod_finish_mc_setup_id===''){
				$('#prodfinishmcsetuptabs').tabs('select',0);
				msApp.showError('Select a Machine Setup First',0);
				return;
			}
			msApp.resetForm('prodfinishmcdateFrm');
			$('#prodfinishmcdateFrm  [name=prod_finish_mc_setup_id]').val(prod_finish_mc_setup_id);
			MsProdFinishMcDate.showGrid(prod_finish_mc_setup_id);
		}
		if(index==2){
			if(prod_finish_mc_date_id===''){
				$('#prodfinishmcsetuptabs').tabs('select',0);
				msApp.showError('Select a Date First',0);
				return;
			}
			msApp.resetForm('prodfinishmcparameterFrm');
			$('#prodfinishmcparameterFrm  [name=prod_finish_mc_date_id]').val(prod_finish_mc_date_id);
			MsProdFinishMcParameter.showGrid(prod_finish_mc_date_id);
		}

	}
});
