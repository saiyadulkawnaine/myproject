let MsSoAopFabricRcvInhModel = require('./MsSoAopFabricRcvInhModel');
require('./../../datagrid-filter.js');
class MsSoAopFabricRcvInhController {
	constructor(MsSoAopFabricRcvInhModel)
	{
		this.MsSoAopFabricRcvInhModel = MsSoAopFabricRcvInhModel;
		this.formId='soaopfabricrcvinhFrm';
		this.dataTable='#soaopfabricrcvinhTbl';
		this.route=msApp.baseUrl()+"/soaopfabricrcvinh"
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
			this.MsSoAopFabricRcvInhModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoAopFabricRcvInhModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#soaopfabricrcvinhFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoAopFabricRcvInhModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoAopFabricRcvInhModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#soaopfabricrcvinhTbl').datagrid('reload');
		msApp.resetForm('soaopfabricrcvinhFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoAopFabricRcvInhModel.get(index,row);
		workReceive.then(function(response){
			//$('#soaopfabricrcvinhFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
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
		return '<a href="javascript:void(0)"  onClick="MsSoAopFabricRcvInh.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	

	soWindow(){
		$('#soaopfabricrcvinhsoWindow').window('open');
	}
	soaopfabricrcvinhsoGrid(data){
		let self = this;
		$('#soaopfabricrcvinhsosearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#soaopfabricrcvinhFrm [name=so_aop_id]').val(row.id);
				$('#soaopfabricrcvinhFrm [name=sales_order_no]').val(row.sales_order_no);
				$('#soaopfabricrcvinhFrm [name=company_id]').val(row.company_id);
				$('#soaopfabricrcvinhFrm [name=buyer_id]').val(row.buyer_id);
				$('#soaopfabricrcvinhsoWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	getsubconorder()
	{
		let so_no=$('#soaopfabricrcvinhsosearchFrm  [name=so_no]').val();
		let prod_finish_dlv_id=$('#soaopfabricrcvinhFrm  [name=prod_finish_dlv_id]').val();
		let params={};
		params.so_no=so_no;
		params.prod_finish_dlv_id=prod_finish_dlv_id;
		if(params.prod_finish_dlv_id ==''){
			alert('Select Challan No First');
			return;
		}

		let data= axios.get(this.route+"/getso",{params});
		data.then(function (response) {
			$('#soaopfabricrcvinhsosearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	challanWindow(){
		$('#soaopfabricrcvinhchallanWindow').window('open');
	}

	getchallan(){
		let challan_no=$('#soaopfabricrcvinhchallansearchFrm  [name=challan_no]').val();
		let from_dlv_date=$('#soaopfabricrcvinhchallansearchFrm  [name=from_dlv_date]').val();
		let to_dlv_date=$('#soaopfabricrcvinhchallansearchFrm  [name=to_dlv_date]').val();
		//let buyer_id=$('#soaopfabricrcvinhFrm  [name=buyer_id]').val();
		let params={};
		params.dlv_no=challan_no;
		params.from_dlv_date=from_dlv_date;
		params.to_dlv_date=to_dlv_date;

		let data= axios.get(this.route+"/getchallan",{params});
		data.then(function (response) {
			$('#soaopfabricrcvinhchallansearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	challanGrid(data){
		let self = this;
		$('#soaopfabricrcvinhchallansearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#soaopfabricrcvinhFrm [name=prod_finish_dlv_id]').val(row.id);
				$('#soaopfabricrcvinhFrm [name=challan_no]').val(row.dlv_no);
				$('#soaopfabricrcvinhFrm [name=buyer_id]').val(row.buyer_id);
				$('#soaopfabricrcvinhchallanWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	
}
window.MsSoAopFabricRcvInh=new MsSoAopFabricRcvInhController(new MsSoAopFabricRcvInhModel());
MsSoAopFabricRcvInh.showGrid();
MsSoAopFabricRcvInh.soaopfabricrcvinhsoGrid([]);
MsSoAopFabricRcvInh.challanGrid([]);
 $('#soaopfabricrcvinhtabs').tabs({
	onSelect:function(title,index){
	 let so_aop_fabric_rcv_id = $('#soaopfabricrcvinhFrm  [name=id]').val();
	 //let so_aop_fabric_rcv_item_id = $('#soaopfabricrcvinhitemFrm  [name=id]').val();

	 
	 if(index==1){
		 if(so_aop_fabric_rcv_id===''){
			 $('#soaopfabricrcvinhtabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		 $('#soaopfabricrcvinhitemFrm  [name=so_aop_fabric_rcv_id]').val(so_aop_fabric_rcv_id);
		 //MsSoAopFabricRcvInhItem.showGrid([]);
		 MsSoAopFabricRcvInhItem.get(so_aop_fabric_rcv_id);
	 }
	  if(index==2){
		let row = $('#soaopfabricrcvinhitemTbl').datagrid('getSelected');
		let so_aop_fabric_rcv_item_id=row.id;
		 if(so_aop_fabric_rcv_item_id===''){
			 $('#soaopfabricrcvinhtabs').tabs('select',1);
			 msApp.showError('Select a Item First',0);
			 return;
		  }
		 MsSoAopFabricRcvInhRol.get(so_aop_fabric_rcv_item_id);
	 }
}
}); 
