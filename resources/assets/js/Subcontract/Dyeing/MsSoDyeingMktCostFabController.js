let MsSoDyeingMktCostFabModel = require('./MsSoDyeingMktCostFabModel');

class MsSoDyeingMktCostFabController {
	constructor(MsSoDyeingMktCostFabModel)
	{
		this.MsSoDyeingMktCostFabModel = MsSoDyeingMktCostFabModel;
		this.formId='sodyeingmktcostfabFrm';
		this.dataTable='#sodyeingmktcostfabTbl';
		this.route=msApp.baseUrl()+"/sodyeingmktcostfab"
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
		//let so_dyeing_mkt_cost_id=$('#sodyeingmktcostFrm [name=id]').val();
		let formObj=msApp.get(this.formId);
		//formObj.so_dyeing_mkt_cost_id=so_dyeing_mkt_cost_id;
		if(formObj.id){
			this.MsSoDyeingMktCostFabModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSoDyeingMktCostFabModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#sodyeingmktcostfabFrm [name=so_dyeing_mkt_cost_id]').val($('#sodyeingmktcostFrm [name=id]').val());
		$('#sodyeingmktcostqpricedtlcosi').html('');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoDyeingMktCostFabModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoDyeingMktCostFabModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		
		msApp.resetForm('sodyeingmktcostfabFrm');
		$('#sodyeingmktcostfabFrm [name=so_dyeing_mkt_cost_id]').val($('#sodyeingmktcostFrm [name=id]').val());
		MsSoDyeingMktCostFab.get($('#sodyeingmktcostFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSoDyeingMktCostFabModel.get(index,row);
	}
	get(so_dyeing_mkt_cost_id)
	{
		let data= axios.get(this.route+"?so_dyeing_mkt_cost_id="+so_dyeing_mkt_cost_id);
		data.then(function (response) {
			$('#sodyeingmktcostfabTbl').datagrid('loadData', response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsSoDyeingMktCostFab.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
    }

	calculate(){
		let self = this;
		let fabric_wgt=$('#sodyeingmktcostfabFrm  [name=fabric_wgt]').val();
		let liqure_ratio=$('#sodyeingmktcostfabFrm  [name=liqure_ratio]').val();
		let liqure_wgt=msApp.multiply(fabric_wgt,liqure_ratio);
		$('#sodyeingmktcostfabFrm  [name=liqure_wgt]').val(liqure_wgt);
	}

	fabricItemWindowOpen(){
		$('#sodyeingmktcostfabricitemWindow').window('open');
	}

	searchFabricItem() 
	{
		let construction_name=$('#sodyeingmktcostfabricitemsearchFrm  [name=construction_name]').val();
		let composition_name=$('#sodyeingmktcostfabricitemsearchFrm  [name=composition_name]').val();
		let data= axios.get(this.route+"/getautoyarn?construction_name="+construction_name+"&composition_name="+composition_name);
		data.then(function (response) {
			$('#sodyeingmktcostfabricitemsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGridItemDescription(data){
		$('#sodyeingmktcostfabricitemsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#sodyeingmktcostfabFrm [name=autoyarn_id]').val(row.id);
				$('#sodyeingmktcostfabFrm  [name=fabrication]').val(row.name+","+row.composition_name);
				$('#sodyeingmktcostfabricitemsearchTbl').datagrid('loadData', []);
				$('#sodyeingmktcostfabricitemWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}


}
window.MsSoDyeingMktCostFab=new MsSoDyeingMktCostFabController(new MsSoDyeingMktCostFabModel());
MsSoDyeingMktCostFab.showGrid([]);
MsSoDyeingMktCostFab.showGridItemDescription([]);