let MsBudgetYarnDyeingConModel = require('./MsBudgetYarnDyeingConModel');
class MsBudgetYarnDyeingConController {
	constructor(MsBudgetYarnDyeingConModel)
	{
		this.MsBudgetYarnDyeingConModel = MsBudgetYarnDyeingConModel;
		this.formId='budgetyarndyeingconFrm';
		this.dataTable='#budgetyarndyeingconTbl';
		this.route=msApp.baseUrl()+"/budgetyarndyeingcon"
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
			this.MsBudgetYarnDyeingConModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsBudgetYarnDyeingConModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsBudgetYarnDyeingConModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsBudgetYarnDyeingConModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		msApp.resetForm('budgetyarndyeingFrm');
		$('#budgetyarndyeingFrm  [name=budget_id]').val(d.budget_id);
		$('#budgettotalFrm  [name=total_cost]').val(d.totalcost);
		MsBudgetYarnDyeing.get(d.budget_id);
		MsBudgetCommercial.get(d.budget_id);
		$('#BudgetYarnDyeingConsWindow').window('close')
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsBudgetYarnDyeingConModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsBudgetYarnDyeingCon.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}



	calculate(iteration,count,field){
		let bom_qty=$('#budgetyarndyeingconFrm input[name="bom_qty['+iteration+']"]').val();
		let rate=$('#budgetyarndyeingconFrm input[name="rate['+iteration+']"]').val();
		let amount=msApp.multiply(bom_qty,rate);
		$('#budgetyarndyeingconFrm input[name="amount['+iteration+']"]').val(amount)

		if($('#budgetyarndyeingconFrm  #is_copy').is(":checked")){
			if(field==='bom_qty'){
				this.copyCons(bom_qty,iteration,count);
			}

			else if(field==='rate'){
				this.copyRate(rate,iteration,count);
			}
		}
	}
	copyCons(bom_qty,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{

			$('#budgetyarndyeingconFrm input[name="bom_qty['+i+']"]').val(bom_qty)

			let rate=$('#budgetyarndyeingconFrm input[name="rate['+i+']"]').val();
			let amount=msApp.multiply(bom_qty,rate);
			$('#budgetyarndyeingconFrm input[name="amount['+i+']"]').val(amount)
		}
	}


	copyRate(rate,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			let bom_qty=$('#budgetyarndyeingconFrm input[name="bom_qty['+i+']"]').val();
			let amount=msApp.multiply(bom_qty,rate);
			$('#budgetyarndyeingconFrm input[name="rate['+i+']"]').val(rate)
			$('#budgetyarndyeingconFrm input[name="amount['+i+']"]').val(amount)
		}
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	setGreyAsBom(iteration,count,field,grey_fab)
	{
		$('#budgetyarndyeingconFrm input[name="bom_qty['+iteration+']"]').val(grey_fab);
		MsBudgetYarnDyeingCon.calculate(iteration,count,field);

	}

}
window.MsBudgetYarnDyeingCon=new MsBudgetYarnDyeingConController(new MsBudgetYarnDyeingConModel());
