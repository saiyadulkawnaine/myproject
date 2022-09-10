let MsSmpCostFabricModel = require('./MsSmpCostFabricModel');
class MsSmpCostFabricController {
	constructor(MsSmpCostFabricModel)
	{
		this.MsSmpCostFabricModel = MsSmpCostFabricModel;
		this.formId='smpcostfabricFrm';
		this.dataTable='#smpcostfabricTbl';
		this.route=msApp.baseUrl()+"/smpcostfabric"
	}

	create(smp_cost_id){
		let data= axios.get(this.route+"/create?smp_cost_id="+smp_cost_id)
		.then(function (response) {
			for(var key in response.data.dropDown){
				msApp.setHtml(key,response.data.dropDown[key]);
			}
		})
		.catch(function (error) {
			console.log(error);
		});
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

		let self =this;
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			let data=this.MsSmpCostFabricModel.saves(this.route+"/"+formObj.id,'put',msApp.qs.stringify(formObj),this.response);
			data.then(function (response) {
				self.create(response.data.smp_cost_id);
			})
		}else{
			let data=this.MsSmpCostFabricModel.saves(this.route,'post',msApp.qs.stringify(formObj),this.response);
			data.then(function (response) {
				self.create(response.data.smp_cost_id);
			})
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSmpCostFabricModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSmpCostFabricModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#smpcosttotalFrm  [name=total_cost]').val(d.totalcost);
	}
	openConsWindow(id){
		if(!id){
			alert('Save First');
			return;
		}
		let data= axios.get(msApp.baseUrl()+"/smpcostfabriccon/create?smp_cost_fabric_id="+id);
		let g=data.then(function (response) {
		for(var key in response.data.dropDown){
			msApp.setHtml(key,response.data.dropDown[key]);
		}
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#samplecostconsWindow').window('open');
		})
	}
	

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsSmpCostFabricModel.get(index,row);

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
		return '<a href="javascript:void(0)"  onClick="MsSmpCostFabric.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
}
window.MsSmpCostFabric=new MsSmpCostFabricController(new MsSmpCostFabricModel());
