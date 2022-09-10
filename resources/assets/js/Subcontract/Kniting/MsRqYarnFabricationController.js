let MsRqYarnFabricationModel = require('./MsRqYarnFabricationModel');
require('./../../datagrid-filter.js');
class MsRqYarnFabricationController {
	constructor(MsRqYarnFabricationModel)
	{
		this.MsRqYarnFabricationModel = MsRqYarnFabricationModel;
		this.formId='rqyarnfabricationFrm';
		this.dataTable='#rqyarnfabricationTbl';
		this.route=msApp.baseUrl()+"/rqyarnfabrication"
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
			this.MsRqYarnFabricationModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsRqYarnFabricationModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		let rq_yarn_id=$('#rqyarnFrm [name=id]').val();
		$('#rqyarnfabricationFrm [name=rq_yarn_id]').val(rq_yarn_id);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsRqYarnFabricationModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsRqYarnFabricationModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#rqyarnfabricationTbl').datagrid('reload');

		MsRqYarnFab.get(d.rq_yarn_id);
		msApp.resetForm('rqyarnfabricationFrm');
		$('#rqyarnfabricationFrm [name=rq_yarn_id]').val(d.rq_yarn_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsRqYarnFabricationModel.get(index,row);
	}
	get(rq_yarn_id)
	{
		let params={};
		params.rq_yarn_id=rq_yarn_id;
		let data= axios.get(this.route,{params});
		data.then(function (response) {
			$('#rqyarnfabricationTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid(data){
		let self=this;
		$(this.dataTable).datagrid({
			//method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			//url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsRqYarnFab.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	rqYarnFabricationWindowOpen(){
		$('#rqYarnFabricationWindow').window('open');

	}
	searchFabrication(){
		let pl_no=$('#rqyarnfabricationsearchFrm  [name=pl_no]').val();
		let po_no=$('#rqyarnfabricationsearchFrm  [name=po_no]').val();
		let dia=$('#rqyarnfabricationsearchFrm  [name=dia]').val();
		let gsm=$('#rqyarnfabricationsearchFrm  [name=gsm]').val();
		let rq_yarn_id=$('#rqyarnFrm  [name=id]').val();
		let data= axios.get(this.route+"/getfabrication?rq_yarn_id="+rq_yarn_id+"&pl_no="+pl_no+"&po_no="+po_no+"&dia="+dia+"&gsm="+gsm);
		data.then(function (response) {
			$('#rqyarnfabricationsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showrqyarnfabricationGrid(data){
		$('#rqyarnfabricationsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#rqyarnfabricationFrm [name=pl_knit_item_id]').val(row.pl_knit_item_id);
				$('#rqyarnfabricationFrm [name=po_knit_service_item_qty_id]').val(row.po_knit_service_item_qty_id);
				$('#rqyarnfabricationFrm [name=pl_no]').val(row.pl_no);
				$('#rqyarnfabricationFrm [name=buyer_name]').val(row.buyer_name);
				$('#rqyarnfabricationFrm [name=style_ref]').val(row.style_ref);
				$('#rqyarnfabricationFrm [name=order_no]').val(row.order_no);
				$('#rqyarnfabricationFrm [name=fabrication]').val(row.fabrication);
				$('#rqyarnfabricationFrm [name=plan_qty]').val(row.plan_qty);
				
				

				$('#rqYarnFabricationWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
}
window.MsRqYarnFab=new MsRqYarnFabricationController(new MsRqYarnFabricationModel());
MsRqYarnFab.showrqyarnfabricationGrid([]);
MsRqYarnFab.showGrid([]);