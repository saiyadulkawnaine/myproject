let MsProdFinishAopMcSetupModel = require('./MsProdFinishAopMcSetupModel');
require('./../../datagrid-filter.js');
class MsProdFinishAopMcSetupController {
	constructor(MsProdFinishAopMcSetupModel)
	{
		this.MsProdFinishAopMcSetupModel = MsProdFinishAopMcSetupModel;
		this.formId='prodfinishaopmcsetupFrm';
		this.dataTable='#prodfinishaopmcsetupTbl';
		this.route=msApp.baseUrl()+"/prodfinishaopmcsetup"
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
			this.MsProdFinishAopMcSetupModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdFinishAopMcSetupModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdFinishAopMcSetupModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdFinishAopMcSetupModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodfinishaopmcsetupTbl').datagrid('reload');
		msApp.resetForm(this.formId);
		msApp.resetForm('prodfinishaopmcdateFrm');
		msApp.resetForm('prodfinishaopmcparameterFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsProdFinishAopMcSetupModel.get(index,row);
		msApp.resetForm('prodfinishaopmcdateFrm');
		msApp.resetForm('prodfinishaopmcparameterFrm');
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
		return '<a href="javascript:void(0)"  onClick="MsProdFinishAopMcSetup.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openFinishMachineWindow() {
		$('#prodfinishaopmachineWindow').window('open');
	}

	searchFinishMachine()
	{
		let params={};
		params.company_name=$('#prodfinishaopmachinesearchFrm  [name=company_name]').val();
		params.machine_no=$('#prodfinishaopmachinesearchFrm  [name=machine_no]').val();
		let data= axios.get(this.route+"/getfinishmachine",{params});
		data.then(function (response) {
			$('#prodfinishaopmachinesearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showFinishMachineGrid() {
		$('#prodfinishaopmachinesearchTbl').datagrid({
		border:false,
		singleSelect:true,
		fit:true,
		onClickRow: function(index,row){
			$('#prodfinishaopmcsetupFrm [name=machine_id]').val(row.id);
				$('#prodfinishaopmcsetupFrm [name=custom_no]').val(row.custom_no);
				$('#prodfinishaopmcsetupFrm [name=company_name]').val(row.company_name);
				$('#prodfinishaopmachineWindow').window('close');
			
		}}).datagrid('enableFilter');
	}

}
window.MsProdFinishAopMcSetup=new MsProdFinishAopMcSetupController(new MsProdFinishAopMcSetupModel());
MsProdFinishAopMcSetup.showGrid();
MsProdFinishAopMcSetup.showFinishMachineGrid([]);

$('#prodfinishaopmcsetuptabs').tabs({
	onSelect:function(title,index){
		let prod_finish_aop_mc_setup_id = $('#prodfinishaopmcsetupFrm  [name=id]').val();
		let prod_finish_aop_mc_date_id = $('#prodfinishaopmcdateFrm [name=id]').val();
		var data={};
	    data.prod_finish_aop_mc_setup_id=prod_finish_aop_mc_setup_id;
	    data.prod_finish_aop_mc_date_id=prod_finish_aop_mc_date_id;
	    
		if(index==1){
			if(prod_finish_aop_mc_setup_id===''){
				$('#prodfinishaopmcsetuptabs').tabs('select',0);
				msApp.showError('Select a Machine Setup First',0);
				return;
			}
			msApp.resetForm('prodfinishaopmcdateFrm');
			$('#prodfinishaopmcdateFrm  [name=prod_finish_aop_mc_setup_id]').val(prod_finish_aop_mc_setup_id);
			MsProdFinishAopMcDate.showGrid(prod_finish_aop_mc_setup_id);
		}
		if(index==2){
			if(prod_finish_aop_mc_date_id===''){
				$('#prodfinishaopmcsetuptabs').tabs('select',0);
				msApp.showError('Select a Date First',0);
				return;
			}
			msApp.resetForm('prodfinishaopmcparameterFrm');
			$('#prodfinishaopmcparameterFrm  [name=prod_finish_aop_mc_date_id]').val(prod_finish_aop_mc_date_id);
			MsProdFinishAopMcParameter.showGrid(prod_finish_aop_mc_date_id);
		}

	}
});
