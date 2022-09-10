let MsSmpCostEmbConModel = require('./MsSmpCostEmbConModel');
class MsSmpCostEmbConController {
	constructor(MsSmpCostEmbConModel)
	{
		this.MsSmpCostEmbConModel = MsSmpCostEmbConModel;
		this.formId='smpcostembconFrm';
		this.dataTable='#smpcostembconTbl';
		this.route=msApp.baseUrl()+"/smpcostembcon"
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
			this.MsSmpCostEmbConModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSmpCostEmbConModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSmpCostEmbConModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSmpCostEmbConModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#smpcosttrimconTbl').datagrid('reload');
		MsSmpCostEmb.create(d.smp_cost_id);
		$('#smpcostembconsWindow').window('close');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSmpCostEmbConModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsSmpCostEmbCon.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	calculate(iteration,count,field){
		let costing_unit=$('#smpcostFrm [name=costing_unit_id]').val();
		let cons=$('#smpcostembconFrm input[name="cons['+iteration+']"]').val();
		let plun_cut_qty=$('#smpcostembconFrm input[name="plun_cut_qty['+iteration+']"]').val();
		let rate=$('#smpcostembconFrm input[name="rate['+iteration+']"]').val();
		var req_cons=(cons/costing_unit)*plun_cut_qty;
		$('#smpcostembconFrm input[name="req_cons['+iteration+']"]').val(req_cons);

		if($('#smpcostembconFrm  #is_copy').is(":checked")){
			if(field==='cons'){
				this.copyCons(cons,iteration,count);
			}

			else if(field==='rate'){
				this.copyRate(rate,iteration,count);
			}
		}
	}
	copyCons(cons,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
            let costing_unit=$('#smpcostFrm [name=costing_unit_id]').val();
			let plun_cut_qty=$('#smpcostembconFrm input[name="plun_cut_qty['+i+']"]').val();


			var req_cons=(cons/costing_unit)*plun_cut_qty;

			$('#smpcostembconFrm input[name="cons['+i+']"]').val(cons)
			$('#smpcostembconFrm input[name="req_cons['+i+']"]').val(req_cons)
			let rate=$('#smpcostembconFrm input[name="rate['+i+']"]').val();
			let amount=msApp.multiply(req_cons,(rate/costing_unit));
			$('#smpcostembconFrm input[name="amount['+i+']"]').val(amount)
		}
	}


	copyRate(rate,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			let costing_unit=$('#smpcostFrm [name=costing_unit_id]').val();
			let req_cons=$('#smpcostembconFrm input[name="req_cons['+i+']"]').val();
			let amount=msApp.multiply(req_cons,(rate/costing_unit));
			$('#smpcostembconFrm input[name="rate['+i+']"]').val(rate)
			$('#smpcostembconFrm input[name="amount['+i+']"]').val(amount)
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}
}
window.MsSmpCostEmbCon=new MsSmpCostEmbConController(new MsSmpCostEmbConModel());
