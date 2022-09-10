let MsSoAopFabricRtnModel = require('./MsSoAopFabricRtnModel');
require('./../../datagrid-filter.js');
class MsSoAopFabricRtnController {
	constructor(MsSoAopFabricRtnModel)
	{
		this.MsSoAopFabricRtnModel = MsSoAopFabricRtnModel;
		this.formId='soaopfabricrtnFrm';
		this.dataTable='#soaopfabricrtnTbl';
		this.route=msApp.baseUrl()+"/soaopfabricrtn"
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
			this.MsSoAopFabricRtnModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoAopFabricRtnModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#soaopfabricrtnFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoAopFabricRtnModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoAopFabricRtnModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#soaopfabricrtnTbl').datagrid('reload');
		MsSoAopFabricRtn.resetForm();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let soaopfabricrtn=this.MsSoAopFabricRtnModel.get(index,row);
		soaopfabricrtn.then(function(response){
			$('#soaopfabricrtnFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
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
		return '<a href="javascript:void(0)"  onClick="MsSoAopFabricRtn.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	pdf(){
		var id= $('#soaopfabricrtnFrm  [name=id]').val();
		if(id==""){
			alert("Select a Challan/Gate Pass");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

}
window.MsSoAopFabricRtn=new MsSoAopFabricRtnController(new MsSoAopFabricRtnModel());
MsSoAopFabricRtn.showGrid();
$('#soaopfabricrtntabs').tabs({
	onSelect:function(title,index){
	 let so_aop_fabric_rtn_id = $('#soaopfabricrtnFrm  [name=id]').val();
	 if(index==1){
		 if(so_aop_fabric_rtn_id===''){
			 $('#soaopfabricrtntabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		 $('#soaopfabricrtnitemFrm  [name=so_aop_fabric_rtn_id]').val(so_aop_fabric_rtn_id);
		 MsSoAopFabricRtnItem.get(so_aop_fabric_rtn_id);
	 }
}
}); 
