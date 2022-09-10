let MsInvFinishFabRcvPurFabricModel = require('./MsInvFinishFabRcvPurFabricModel');
class MsInvFinishFabRcvPurFabricController {
	constructor(MsInvFinishFabRcvPurFabricModel)
	{
		this.MsInvFinishFabRcvPurFabricModel = MsInvFinishFabRcvPurFabricModel;
		this.formId='invfinishfabrcvpurfabricFrm';
		this.dataTable='#invfinishfabrcvpurfabricTbl';
		this.route=msApp.baseUrl()+"/invfinishfabrcvpurfabric"
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
		let inv_finish_fab_rcv_id=$('#invfinishfabrcvpurFrm  [name=inv_finish_fab_rcv_id]').val();
		let formObj=msApp.get(this.formId);
		formObj.inv_finish_fab_rcv_id=inv_finish_fab_rcv_id;
		if(formObj.id){
			this.MsInvFinishFabRcvPurFabricModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvFinishFabRcvPurFabricModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvFinishFabRcvPurFabricModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvFinishFabRcvPurFabricModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invfinishfabrcvpurfabricTbl').datagrid('reload');
		msApp.resetForm('invfinishfabrcvpurfabricFrm');
		MsInvFinishFabRcvPurFabric.get(d.inv_finish_fab_rcv_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvFinishFabRcvPurFabricModel.get(index,row);
		data.then(function(response){
			
		}).catch(function(error){
			console.log(error);
		})
	}

	get(inv_finish_fab_rcv_id){
		let params={};
		params.inv_finish_fab_rcv_id=inv_finish_fab_rcv_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invfinishfabrcvpurfabricTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})

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
		return '<a href="javascript:void(0)"  onClick="MsRcvFinishFab.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	getFabric()
	{

		let params={};
		let po_fabric_id=$('#invfinishfabrcvpurFrm  [name=po_fabric_id]').val();
		params.po_fabric_id=po_fabric_id;
		let data= axios.get(this.route+'/getfabric',{params})
		.then(function (response) {
			$('#invfinishfabrcvpurfabricsearchTbl').datagrid('loadData', response.data).datagrid('enableFilter');
			$('#invfinishfabrcvpurfabricWindow').window('open');
			
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGridFabric(data){
		let self=this;
		var dg=$('#invfinishfabrcvpurfabricsearchTbl');
		dg.datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			idField:"id",
			rownumbers:true,
			onClickRow: function(index,row){
				$('#invfinishfabrcvpurfabricFrm  [name=po_fabric_item_id]').val(row.po_fabric_item_id);
				$('#invfinishfabrcvpurfabricFrm  [name=fabrication]').val(row.gmtspart+" "+row.fabric_description+" "+row.gsm_weight);
				$('#invfinishfabrcvpurfabricFrm  [name=req_dia]').val(row.dia);
				$('#invfinishfabrcvpurfabricFrm  [name=fabric_color]').val(row.fabric_color_name);
				$('#invfinishfabrcvpurfabricFrm  [name=fabric_color_id]').val(row.fabric_color);
				$('#invfinishfabrcvpurfabricFrm  [name=sales_order_no]').val(row.sale_order_no);
				$('#invfinishfabrcvpurfabricFrm  [name=sales_order_id]').val(row.sales_order_id);
				$('#invfinishfabrcvpurfabricFrm  [name=rate]').val(row.rate);
				$('#invfinishfabrcvpurfabricWindow').window('close');
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

}
window.MsInvFinishFabRcvPurFabric=new MsInvFinishFabRcvPurFabricController(new MsInvFinishFabRcvPurFabricModel());
MsInvFinishFabRcvPurFabric.showGrid();
MsInvFinishFabRcvPurFabric.showGridFabric([]);
