let MsProdAopMcSetupModel = require('./MsProdAopMcSetupModel');
require('./../../datagrid-filter.js');
class MsProdAopMcSetupController {
	constructor(MsProdAopMcSetupModel)
	{
		this.MsProdAopMcSetupModel = MsProdAopMcSetupModel;
		this.formId='prodaopmcsetupFrm';
		this.dataTable='#prodaopmcsetupTbl';
		this.route=msApp.baseUrl()+"/prodaopmcsetup"
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
			this.MsProdAopMcSetupModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdAopMcSetupModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdAopMcSetupModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdAopMcSetupModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodaopmcsetupTbl').datagrid('reload');
		msApp.resetForm(this.formId);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsProdAopMcSetupModel.get(index,row);
		msApp.resetForm('prodaopmcdateFrm');
		msApp.resetForm('prodaopmcparameterFrm');
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
		return '<a href="javascript:void(0)"  onClick="MsProdAopMcSetup.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	machineWindow() {
		$('#prodaopmachineWindow').window('open');
	}

	searchMachine()
	{
		let params={};
		params.company_name=$('#prodaopmachinesearchFrm  [name=company_name]').val();
		params.machine_no=$('#prodaopmachinesearchFrm  [name=machine_no]').val();
		let data= axios.get(this.route+"/getaopmachine",{params});
		data.then(function (response) {
			$('#prodaopmachinesearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	showMachineGrid() {
		$('#prodaopmachinesearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#prodaopmcsetupFrm [name=machine_id]').val(row.id);
					$('#prodaopmcsetupFrm [name=custom_no]').val(row.custom_no);
					$('#prodaopmcsetupFrm [name=company_name]').val(row.company_name);
					$('#prodaopmachineWindow').window('close');
				
			}}).datagrid('enableFilter');
	}

}
window.MsProdAopMcSetup=new MsProdAopMcSetupController(new MsProdAopMcSetupModel());
MsProdAopMcSetup.showGrid();
MsProdAopMcSetup.showMachineGrid([]);

$('#prodaopmcsetuptabs').tabs({
	onSelect:function(title,index){
		let prod_aop_mc_setup_id = $('#prodaopmcsetupFrm  [name=id]').val();
		let prod_aop_mc_date_id = $('#prodaopmcdateFrm [name=id]').val();
		var data={};
	    data.prod_aop_mc_setup_id=prod_aop_mc_setup_id;
	    data.prod_aop_mc_date_id=prod_aop_mc_date_id;
	    
		if(index==1){
			if(prod_aop_mc_setup_id===''){
				$('#prodaopmcsetuptabs').tabs('select',0);
				msApp.showError('Select a Machine Setup First',0);
				return;
			}
			msApp.resetForm('prodaopmcdateFrm');
			$('#prodaopmcdateFrm  [name=prod_aop_mc_setup_id]').val(prod_aop_mc_setup_id);
			MsProdAopMcDate.showGrid(prod_aop_mc_setup_id);
		}
		if(index==2){
			if(prod_aop_mc_date_id===''){
				$('#prodaopmcsetuptabs').tabs('select',0);
				msApp.showError('Select a Date First',0);
				return;
			}
			msApp.resetForm('prodaopmcparameterFrm');
			$('#prodaopmcparameterFrm  [name=prod_aop_mc_date_id]').val(prod_aop_mc_date_id);
			MsProdAopMcParameter.showGrid(prod_aop_mc_date_id);
		}

	}
});
