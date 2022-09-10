let MsSmpCostTrimConModel = require('./MsSmpCostTrimConModel');
class MsSmpCostTrimConController {
	constructor(MsSmpCostTrimConModel)
	{
		this.MsSmpCostTrimConModel = MsSmpCostTrimConModel;
		this.formId='smpcosttrimconFrm';
		this.dataTable='#smpcosttrimconTbl';
		this.route=msApp.baseUrl()+"/smpcosttrimcon"
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
			this.MsSmpCostTrimConModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSmpCostTrimConModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSmpCostTrimConModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSmpCostTrimConModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#smpcosttrimconTbl').datagrid('reload');
		MsSmpCostTrim.get(d.smp_cost_id);
		$('#smpcosttrimconsWindow').window('close');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSmpCostTrimConModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsSmpCostTrimCon.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	calculate(iteration,count,field){
		let costing_unit=$('#smpcostFrm [name=costing_unit_id]').val();
		let plun_cut_qty=$('#smpcosttrimconFrm input[name="plun_cut_qty['+iteration+']"]').val();
		let cons=$('#smpcosttrimconFrm input[name="cons['+iteration+']"]').val();
		let process_loss=$('#smpcosttrimconFrm input[name="process_loss['+iteration+']"]').val();
		let rate=$('#smpcosttrimconFrm input[name="rate['+iteration+']"]').val();
		var devided_val = 1-(process_loss/100);
		var req_cons=parseFloat(cons/devided_val);

		var req_trim=(cons/costing_unit)*plun_cut_qty;
		var bom_trim=(req_cons/costing_unit)*plun_cut_qty;

		$('#smpcosttrimconFrm input[name="req_cons['+iteration+']"]').val(req_cons);
		$('#smpcosttrimconFrm input[name="req_trim['+iteration+']"]').val(req_trim)
		$('#smpcosttrimconFrm input[name="bom_trim['+iteration+']"]').val(bom_trim)
		
		let amount=msApp.multiply(bom_trim,rate);
		$('#smpcosttrimconFrm input[name="amount['+iteration+']"]').val(amount)

		if($('#smpcosttrimconFrm  #is_copy').is(":checked")){
			if(field==='cons'){
				this.copyCons(cons,costing_unit,iteration,count);
			}
			else if(field==='process_loss'){
				this.copyProcessLoss(process_loss,costing_unit,iteration,count);
			}
			else if(field==='rate'){
				this.copyRate(rate,iteration,count);
			}
		}
	}
	copyCons(cons,costing_unit,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			let process_loss=$('#smpcosttrimconFrm input[name="process_loss['+iteration+']"]').val();
			let plun_cut_qty=$('#smpcosttrimconFrm input[name="plun_cut_qty['+i+']"]').val();
			let devided_val = 1-(process_loss/100);
			let req_cons=parseFloat(cons/devided_val);
			var req_trim=(cons/costing_unit)*plun_cut_qty;
			var bom_trim=(req_cons/costing_unit)*plun_cut_qty;

			$('#smpcosttrimconFrm input[name="cons['+i+']"]').val(cons)
			$('#smpcosttrimconFrm input[name="req_trim['+i+']"]').val(req_trim)
			$('#smpcosttrimconFrm input[name="process_loss['+i+']"]').val(process_loss)
			$('#smpcosttrimconFrm input[name="req_cons['+i+']"]').val(req_cons)
			$('#smpcosttrimconFrm input[name="bom_trim['+i+']"]').val(bom_trim)

			let rate=$('#smpcosttrimconFrm input[name="rate['+i+']"]').val();
			let amount=msApp.multiply(bom_trim,rate);
			$('#smpcosttrimconFrm input[name="amount['+i+']"]').val(amount)
		}
	}
	
	copyProcessLoss(process_loss,costing_unit,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			let cons=$('#smpcosttrimconFrm input[name="cons['+i+']"]').val();
			let plun_cut_qty=$('#smpcosttrimconFrm input[name="plun_cut_qty['+i+']"]').val();
			let devided_val = 1-(process_loss/100);
			let req_cons=parseFloat(cons/devided_val);
			var req_trim=(cons/costing_unit)*plun_cut_qty;
			var bom_trim=(req_cons/costing_unit)*plun_cut_qty;
			
			$('#smpcosttrimconFrm input[name="process_loss['+i+']"]').val(process_loss)
			$('#smpcosttrimconFrm input[name="req_cons['+i+']"]').val(req_cons)
			
			$('#smpcosttrimconFrm input[name="req_trim['+i+']"]').val(req_trim)
			$('#smpcosttrimconFrm input[name="bom_trim['+i+']"]').val(bom_trim)
			
			let rate=$('#smpcosttrimconFrm input[name="rate['+i+']"]').val();
			let amount=msApp.multiply(bom_trim,rate);
			$('#smpcosttrimconFrm input[name="amount['+i+']"]').val(amount)
		}
	}


	copyRate(rate,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			let req_cons=$('#smpcosttrimconFrm input[name="req_cons['+i+']"]').val();
			let bom_trim=$('#smpcosttrimconFrm input[name="bom_trim['+i+']"]').val();
			let amount=msApp.multiply(bom_trim,rate);
			$('#smpcosttrimconFrm input[name="rate['+i+']"]').val(rate)
			$('#smpcosttrimconFrm input[name="amount['+i+']"]').val(amount)
		}
	}
}
window.MsSmpCostTrimCon=new MsSmpCostTrimConController(new MsSmpCostTrimConModel());
