let MsInvPurReqItemModel = require('./MsInvPurReqItemModel');
class MsInvPurReqItemController {
	constructor(MsInvPurReqItemModel)
	{
		this.MsInvPurReqItemModel = MsInvPurReqItemModel;
		this.formId='invpurreqitemFrm';
		this.dataTable='#invpurreqitemTbl';
		this.route=msApp.baseUrl()+"/invpurreqitem"
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
			this.MsInvPurReqItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvPurReqItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#invpurreqitemFrm [name=inv_pur_req_id]').val($('#invpurreqFrm [name=id]').val());
		$('#invpurreqitemFrm [id="department_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvPurReqItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvPurReqItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invpurreqitemTbl').datagrid('reload');
		msApp.resetForm('invpurreqitemFrm');
		$('#invpurreqitemFrm [name=inv_pur_req_id]').val($('#invpurreqFrm [name=id]').val());
		$('#invpurreqitemFrm [id="department_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let prItem=this.MsInvPurReqItemModel.get(index,row);
		prItem.then(function(response){
			$('#invpurreqitemFrm [id="department_id"]').combobox('setValue', response.data.fromData.department_id);
		}).catch(function(error){
			console.log(error);
		});
	}
	
	calculateAmount(){
		let qty;
		let rate;
		qty=$('#invpurreqitemFrm [name=qty]').val();
		rate=$('#invpurreqitemFrm [name=rate]').val();
		let amount=qty*rate;
		$('#invpurreqitemFrm [name=amount]').val(amount);
	}
	 

	 showGrid(inv_pur_req_id)
	 {
		 let self=this;
		 var data={};
		 data.inv_pur_req_id=inv_pur_req_id;
		 $(this.dataTable).datagrid({
			 method:'get',
			 border:false,
			 singleSelect:true,
			 fit:true,
			 fitColumns:false,
			 queryParams:data,
			 showFooter:true,
			 url:this.route,
			 onClickRow: function(index,row){
				 self.edit(index,row);
			 },
			onLoadSuccess:function(data){
				var tQty = 0 ;
				var tAmount = 0 ;
				for(var i=0; i<data.rows.length; i++){
					tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					tAmount+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
					{
						qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: tAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);

			}
		 }).datagrid('enableFilter');
	 }

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsInvPurReqItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
///////////////////////////////////////

openPurchaseItemWindow(){
	$('#newWindow').window('open');
}
getParams(){
	let params={};
	params.item_account_id=$('#itemsearchFrm [name=item_account_id]').val();
	params.itemcategory_id=$('#itemsearchFrm [name=itemcategory_id]').val();
	params.itemclass_id=$('#itemsearchFrm [name=itemclass_id]').val();
	return params;
}
searchPurchaseItemGrid(){
	let params=MsInvPurReqItem.getParams();
	let d=axios.get(msApp.baseUrl()+"/invpurreqitem/getitemaccount",{params})
	.then(function(response){
		$('#itemsearchTbl').datagrid('loadData',response.data);
	}).catch(function(error){
		console.log(error);
	})
}
showPurchaseItemGrid(data)
 {
	let self=this;
	var item=$('#itemsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
			$('#invpurreqitemFrm [name=item_account_id]').val(row.id);
			$('#invpurreqitemFrm [name=item_description]').val(row.sub_class_name+','+row.item_description+','+row.specification);
			$('#invpurreqitemFrm [name=reorder_level]').val(row.reorder_level);
			$('#invpurreqitemFrm [name=itemcategory_id]').val(row.itemcategory_id);
			$('#invpurreqitemFrm [name=itemclass_id]').val(row.itemclass_id);
			$('#invpurreqitemFrm [name=uom_code]').val(row.uom_code);
			$('#itemsearchTbl').datagrid('loadData',[]);
			$('#newWindow').window('close');
			}
		 });
		 item.datagrid('enableFilter').datagrid('loadData',data);
}
}
window.MsInvPurReqItem=new MsInvPurReqItemController(new MsInvPurReqItemModel());
MsInvPurReqItem.showPurchaseItemGrid([]);