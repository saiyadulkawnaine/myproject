let MsSoAopFabricRcvModel = require('./MsSoAopFabricRcvModel');
require('./../../datagrid-filter.js');
class MsSoAopFabricRcvController {
	constructor(MsSoAopFabricRcvModel)
	{
		this.MsSoAopFabricRcvModel = MsSoAopFabricRcvModel;
		this.formId='soaopfabricrcvFrm';
		this.dataTable='#soaopfabricrcvTbl';
		this.route=msApp.baseUrl()+"/soaopfabricrcv"
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
			this.MsSoAopFabricRcvModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoAopFabricRcvModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#soaopfabricrcvFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoAopFabricRcvModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoAopFabricRcvModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#soaopfabricrcvTbl').datagrid('reload');
		msApp.resetForm('soaopfabricrcvFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoAopFabricRcvModel.get(index,row);
		workReceive.then(function(response){
			//$('#soaopfabricrcvFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
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
		return '<a href="javascript:void(0)"  onClick="MsSoAopFabricRcv.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	

	soWindow(){
		$('#soaopfabricrcvsoWindow').window('open');
	}
	soaopfabricrcvsoGrid(data){
		let self = this;
		$('#soaopfabricrcvsosearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#soaopfabricrcvFrm [name=so_aop_id]').val(row.id);
				$('#soaopfabricrcvFrm [name=sales_order_no]').val(row.sales_order_no);
				$('#soaopfabricrcvFrm [name=company_id]').val(row.company_id);
				$('#soaopfabricrcvFrm [name=buyer_id]').val(row.buyer_id);
				$('#soaopfabricrcvsoWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	getsubconorder()
	{
		let so_no=$('#soaopfabricrcvsosearchFrm  [name=so_no]').val();
		//let buyer_id=$('#soaopfabricrcvFrm  [name=buyer_id]').val();
		let data= axios.get(this.route+"/getso?so_no="+so_no);
		data.then(function (response) {
			$('#soaopfabricrcvsosearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
}
window.MsSoAopFabricRcv=new MsSoAopFabricRcvController(new MsSoAopFabricRcvModel());
MsSoAopFabricRcv.showGrid();
MsSoAopFabricRcv.soaopfabricrcvsoGrid([]);
 $('#soaopfabricrcvtabs').tabs({
	onSelect:function(title,index){
	 let so_aop_fabric_rcv_id = $('#soaopfabricrcvFrm  [name=id]').val();
	 let so_aop_fabric_rcv_item_id = $('#soaopfabricrcvitemFrm  [name=id]').val();
	 
	 if(index==1){
		 if(so_aop_fabric_rcv_id===''){
			 $('#soaopfabricrcvtabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		 $('#soaopfabricrcvitemFrm  [name=so_aop_fabric_rcv_id]').val(so_aop_fabric_rcv_id);
		 //MsSoAopFabricRcvItem.showGrid([]);
		 msApp.resetForm('soaopfabricrcvitemFrm')
		 MsSoAopFabricRcvItem.get(so_aop_fabric_rcv_id);
	 }
	 if(index==2){
		
		 if(so_aop_fabric_rcv_item_id===''){
			 $('#soaopfabricrcvinhtabs').tabs('select',1);
			 msApp.showError('Select a Item First',0);
			 return;
		  }
		  msApp.resetForm('soaopfabricrcvrolFrm')
		 MsSoAopFabricRcvRol.get(so_aop_fabric_rcv_item_id);
	 }
}
}); 
