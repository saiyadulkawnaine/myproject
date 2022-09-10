let MsSubInbOrderModel = require('./MsSubInbOrderModel');
require('./../../datagrid-filter.js');
class MsSubInbOrderController {
	constructor(MsSubInbOrderModel)
	{
		this.MsSubInbOrderModel = MsSubInbOrderModel;
		this.formId='subinborderFrm';
		this.dataTable='#subinborderTbl';
		this.route=msApp.baseUrl()+"/subinborder"
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
			this.MsSubInbOrderModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSubInbOrderModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#subinborderFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSubInbOrderModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSubInbOrderModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#subinborderTbl').datagrid('reload');
		msApp.resetForm('subinborderFrm');
		$('#subinborderFrm [id="buyer_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSubInbOrderModel.get(index,row);
		workReceive.then(function(response){
			$('#subinborderFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
		}).catch(function(error){
			console.log(errors)
		});
	}

	showGrid(){

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
		return '<a href="javascript:void(0)"  onClick="MsSubInbOrder.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openSubInbMktWindow(){
		$('#subinbmktwindow').window('open');
	}

	showMktGrid(){

		let data = {};
		data.company_id = $('#subinbmktsearchFrm [name="company_id"]').val();
		data.production_area_id = $('#subinbmktsearchFrm [name="production_area_id"]').val();
		data.buyer_id = $('#subinbmktsearchFrm [name="buyer_id"]').val();
		data.mkt_date = $('#subinbmktsearchFrm [name="mkt_date"]').val();
		let self = this;
		$('#subinbmktsearchTbl').datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			url:this.route+"/getmktref",
			onClickRow: function(index,row){
					$('#subinborderFrm [name=sub_inb_marketing_id]').val(row.id);
					//$('#subinborderFrm [name=production_area_id]').val(row.production_area_id);
					//$('#subinborderFrm [name=buyer_id]').val(row.buyer_id);
					$('#subinbmktwindow').window('close');
			}
			}).datagrid('enableFilter');
	}

	
}
window.MsSubInbOrder=new MsSubInbOrderController(new MsSubInbOrderModel());
MsSubInbOrder.showGrid();

 $('#subinbworkrcvtabs').tabs({
	onSelect:function(title,index){
	 let sub_inb_order_id = $('#subinborderFrm  [name=id]').val();
	 var data={};
	  data.sub_inb_order_id=sub_inb_order_id;

	 if(index==1){
		 if(sub_inb_order_id===''){
			 $('#subinbworkrcvtabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		 $('#subinborderproductFrm  [name=sub_inb_order_id]').val(sub_inb_order_id);
		 MsSubInbOrderProduct.showGrid(sub_inb_order_id);
	 }
	 if(index==2){
		if(sub_inb_order_id===''){
			$('#subinbworkrcvtabs').tabs('select',0);
			msApp.showError('Select a Start Up First',0);
			return;
		 }
		$('#subinborderfileFrm  [name=sub_inb_order_id]').val(sub_inb_order_id);
		MsSubInbOrderFile.showGrid(sub_inb_order_id);
	}
}
}); 
