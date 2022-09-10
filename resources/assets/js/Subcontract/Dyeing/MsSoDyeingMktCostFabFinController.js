let MsSoDyeingMktCostFabFinModel = require('./MsSoDyeingMktCostFabFinModel');

class MsSoDyeingMktCostFabFinController {
	constructor(MsSoDyeingMktCostFabFinModel)
	{
		this.MsSoDyeingMktCostFabFinModel = MsSoDyeingMktCostFabFinModel;
		this.formId='sodyeingmktcostfabfinFrm';
		this.dataTable='#sodyeingmktcostfabfinTbl';
		this.route=msApp.baseUrl()+"/sodyeingmktcostfabfin"
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
			this.MsSoDyeingMktCostFabFinModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSoDyeingMktCostFabFinModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoDyeingMktCostFabFinModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoDyeingMktCostFabFinModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		msApp.resetForm('sodyeingmktcostfabfinFrm');
		$('#sodyeingmktcostfabfinFrm [name=so_dyeing_mkt_cost_fab_id]').val($('#sodyeingmktcostfabFrm [name=id]').val());
		MsSoDyeingMktCostFabFin.get($('#sodyeingmktcostfabFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSoDyeingMktCostFabFinModel.get(index,row);
	}
	get(so_dyeing_mkt_cost_fab_id)
	{
		let data= axios.get(this.route+"?so_dyeing_mkt_cost_fab_id="+so_dyeing_mkt_cost_fab_id);
		data.then(function (response) {
			$('#sodyeingmktcostfabfinTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid(data){
		let self=this;
		$(this.dataTable).datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSoDyeingMktCostFabFin.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
    }

}
window.MsSoDyeingMktCostFabFin=new MsSoDyeingMktCostFabFinController(new MsSoDyeingMktCostFabFinModel());
MsSoDyeingMktCostFabFin.showGrid([]);