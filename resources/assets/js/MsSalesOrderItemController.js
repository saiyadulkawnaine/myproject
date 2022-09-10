let MsSalesOrderItemModel = require('./MsSalesOrderItemModel');
class MsSalesOrderItemController {
	constructor(MsSalesOrderItemModel)
	{
		this.MsSalesOrderItemModel = MsSalesOrderItemModel;
		this.formId='salesorderitemFrm';
		this.dataTable='#salesorderitemTbl';
		this.route=msApp.baseUrl()+"/salesorderitem"
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
			this.MsSalesOrderItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSalesOrderItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSalesOrderItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSalesOrderItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#salesorderitemTbl').datagrid('reload');
		msApp.resetForm('salesorderitemFrm');
		$('#salesorderitemFrm  [name=id]').val(d.id);
		$('#salesordercolorsizeFrm  [name=sale_order_item_id]').val(d.id);

		$('#salesorderitemFrm  [name=job_id]').val($('#jobFrm  [name=id]').val())
		$('#salesorderitemFrm  [name=job_no]').val($('#jobFrm  [name=job_no]').val())
		$('#salesorderitemFrm  [name=sale_order_id]').val($('#salesorderFrm  [name=id]').val())
		$('#salesorderitemFrm  [name=sale_order_no]').val($('#salesorderFrm  [name=sale_order_no]').val())
		$('#salesorderitemFrm  [name=sale_order_country_id]').val($('#salesordercountryFrm  [name=id]').val())
		$('#salesorderitemFrm  [name=style_ref]').val($("#jobFrm [name=style_ref]").val());
		$('#salesorderitemFrm  [name=country_name]').val($("#salesordercountryFrm [name=country_id] option:selected").text());

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSalesOrderItemModel.get(index,row);
		//msApp.resetForm('salesordersizeFrm');
		$('#salesordercolorsizeFrm  [name=job_id]').val(row.job_id);
		$('#salesordercolorsizeFrm  [name=sale_order_id]').val(row.sale_order_id);
		$('#salesordercolorsizeFrm  [name=sale_order_country_id]').val(row.sale_order_country_id);
		$('#salesordercolorsizeFrm  [name=sale_order_item_id]').val(row.id);
	}

	showGrid(sale_order_country_id)
	{
		let data={};
		data.sale_order_country_id=sale_order_country_id;
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			queryParams:data,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSalesOrderItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsSalesOrderItem=new MsSalesOrderItemController(new MsSalesOrderItemModel());
//MsSalesOrderItem.showGrid();
