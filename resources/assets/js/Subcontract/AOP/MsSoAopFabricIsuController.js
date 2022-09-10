let MsSoAopFabricIsuModel = require('./MsSoAopFabricIsuModel');
require('./../../datagrid-filter.js');
class MsSoAopFabricIsuController {
	constructor(MsSoAopFabricIsuModel)
	{
		this.MsSoAopFabricIsuModel = MsSoAopFabricIsuModel;
		this.formId='soaopfabricisuFrm';
		this.dataTable='#soaopfabricisuTbl';
		this.route=msApp.baseUrl()+"/soaopfabricisu"
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
			this.MsSoAopFabricIsuModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoAopFabricIsuModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#soaopfabricisuFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoAopFabricIsuModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoAopFabricIsuModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#soaopfabricisuTbl').datagrid('reload');
		msApp.resetForm('soaopfabricisuFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workIssue = this.MsSoAopFabricIsuModel.get(index,row);
		workIssue.then(function(response){
			//$('#soaopfabricisuFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
		}).catch(function(error){
			console.log(errors)
		});
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

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSoAopFabricIsu.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	

	soWindow(){
		$('#soaopfabricisusoWindow').window('open');
	}
	soaopfabricisusoGrid(data){
		let self = this;
		$('#soaopfabricisusosearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#soaopfabricisuFrm [name=so_aop_id]').val(row.id);
				$('#soaopfabricisuFrm [name=sales_order_no]').val(row.sales_order_no);
				$('#soaopfabricisuFrm [name=company_id]').val(row.company_id);
				$('#soaopfabricisuFrm [name=buyer_id]').val(row.buyer_id);
				$('#soaopfabricisusoWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	getsubconorder()
	{
		let so_no=$('#soaopfabricisusosearchFrm  [name=so_no]').val();
		//let buyer_id=$('#soaopfabricisuFrm  [name=buyer_id]').val();
		let data= axios.get(this.route+"/getso?so_no="+so_no);
		data.then(function (response) {
			$('#soaopfabricisusosearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
}
window.MsSoAopFabricIsu=new MsSoAopFabricIsuController(new MsSoAopFabricIsuModel());
MsSoAopFabricIsu.showGrid();
MsSoAopFabricIsu.soaopfabricisusoGrid([]);
 $('#soaopfabricisutabs').tabs({
	onSelect:function(title,index){
	 let so_aop_fabric_isu_id = $('#soaopfabricisuFrm  [name=id]').val();
	 var data={};
	 data.so_aop_fabric_isu_id=so_aop_fabric_isu_id;
	 if(index==1){
		 if(so_aop_fabric_isu_id===''){
			 $('#soaopfabricisutabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		 $('#soaopfabricisuitemFrm  [name=so_aop_fabric_isu_id]').val(so_aop_fabric_isu_id);
		 //MsSoAopFabricIsuItem.showGrid([]);
		 MsSoAopFabricIsuItem.get(so_aop_fabric_isu_id);
	 }
}
}); 
