let MsSmpCostEmbModel = require('./MsSmpCostEmbModel');
class MsSmpCostEmbController {
	constructor(MsSmpCostEmbModel)
	{
		this.MsSmpCostEmbModel = MsSmpCostEmbModel;
		this.formId='smpcostembFrm';
		this.dataTable='#smpcostembTbl';
		this.route=msApp.baseUrl()+"/smpcostemb"
	}

	create(smp_cost_id)
	{
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

		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsSmpCostEmbModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSmpCostEmbModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSmpCostEmbModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSmpCostEmbModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#smpcostembTbl').datagrid('reload');
		MsSmpCostEmb.create(d.smp_cost_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSmpCostEmbModel.get(index,row);
	}

	showGrid(smp_cost_id)
	{
		let self=this;
		var data={};
		data.smp_cost_id=smp_cost_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			url:this.route,
			queryParams:data,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSmpCostEmb.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
	openConsWindow(id){
		if(!id){
			alert('Save First');
			return;
		}
		let data= axios.get(msApp.baseUrl()+"/smpcostembcon/create?smp_cost_emb_id="+id);
		let g=data.then(function (response) {
		for(var key in response.data.dropDown){
			msApp.setHtml(key,response.data.dropDown[key]);
		}
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#smpcostembconsWindow').window('open');
		})
	}
}
window.MsSmpCostEmb=new MsSmpCostEmbController(new MsSmpCostEmbModel());
MsSmpCostEmb.showGrid();
