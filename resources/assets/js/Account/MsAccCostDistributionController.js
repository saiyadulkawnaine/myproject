let MsAccCostDistributionModel = require('./MsAccCostDistributionModel');
require('./../datagrid-filter.js');
class MsAccCostDistributionController {
	constructor(MsAccCostDistributionModel)
	{
		this.MsAccCostDistributionModel = MsAccCostDistributionModel;
		this.formId='acccostdistributionFrm';
		this.dataTable='#acccostdistributionTbl';
		this.route=msApp.baseUrl()+"/acccostdistribution"
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
			this.MsAccCostDistributionModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsAccCostDistributionModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsAccCostDistributionModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAccCostDistributionModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#acccostdistributionTbl').datagrid('reload');
		MsAccCostDistribution.resetForm();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsAccCostDistributionModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsAccCostDistribution.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsAccCostDistribution=new MsAccCostDistributionController(new MsAccCostDistributionModel());
MsAccCostDistribution.showGrid();
