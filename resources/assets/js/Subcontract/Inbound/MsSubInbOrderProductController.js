
let MsSubInbOrderProductModel = require('./MsSubInbOrderProductModel');

class MsSubInbOrderProductController {
	constructor(MsSubInbOrderProductModel)
	{
		this.MsSubInbOrderProductModel = MsSubInbOrderProductModel;
		this.formId='subinborderproductFrm';
		this.dataTable='#subinborderproductTbl';
		this.route=msApp.baseUrl()+"/subinborderproduct"
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
			this.MsSubInbOrderProductModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSubInbOrderProductModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#subinborderproductFrm [id="uom_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSubInbOrderProductModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSubInbOrderProductModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#subinborderproductTbl').datagrid('reload');
		msApp.resetForm('subinborderproductFrm');
		$('#subinborderproductFrm [name=sub_inb_order_id]').val($('#subinborderFrm [name=id]').val());
		$('#subinborderproductFrm [id="uom_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let workorder = this.MsSubInbOrderProductModel.get(index,row);
		workorder.then(function(response){
			$('#subinborderproductFrm [id="uom_id"]').combobox('setValue', response.data.fromData.uom_id);
		}).catch(function(error){
			console.log(errors);
		});

	}

	showGrid(sub_inb_order_id){
		let self=this;
		let data = {};
		data.sub_inb_order_id=sub_inb_order_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSubInbOrderProduct.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
    }

    calculate()
	{
		let qty = $('#subinborderproductFrm  [name=qty]').val();
		let rate = $('#subinborderproductFrm  [name=rate]').val();
		let amount=qty*rate;
		$('#subinborderproductFrm  [name=amount]').val(amount);
	}
	
	openItemDesWindow(){
		$('#OpenItemWindow').window('open');
	}

	showItemDescription(){
		let data={};
		data.itemcategory_id=$('#itemsearchFrm [name=itemcategory_id]').val();
		data.itemclass_id=$('#itemsearchFrm [name=itemclass_id]').val();
		let self = this;
		$('#itemsearchTbl').datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			url:this.route+"/getItemDescription",
			onClickRow: function(index,row){
				$('#subinborderproductFrm [name=item_account_id]').val(row.id);
				$('#subinborderproductFrm [name=item_description]').val(row.item_description);
				$('#OpenItemWindow').window('close');
			}
		}).datagrid('enableFilter');
	}
  
}
window.MsSubInbOrderProduct=new MsSubInbOrderProductController(new MsSubInbOrderProductModel());
