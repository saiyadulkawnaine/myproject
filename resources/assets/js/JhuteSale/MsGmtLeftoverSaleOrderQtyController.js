let MsGmtLeftoverSaleOrderQtyModel = require('./MsGmtLeftoverSaleOrderQtyModel');

class MsGmtLeftoverSaleOrderQtyController {
	constructor(MsGmtLeftoverSaleOrderQtyModel)
	{
		this.MsGmtLeftoverSaleOrderQtyModel = MsGmtLeftoverSaleOrderQtyModel;
		this.formId='gmtleftoversaleorderqtyFrm';	             
		this.dataTable='#gmtleftoversaleorderqtyTbl';
		this.route=msApp.baseUrl()+"/gmtleftoversaleorderqty"
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
		let jhute_sale_dlv_order_item_id=$('#gmtleftoversaleorderstyledtlFrm [name=id]').val()
		let formObj=msApp.get(this.formId);
		formObj.jhute_sale_dlv_order_item_id=jhute_sale_dlv_order_item_id;
		if(formObj.id){
			this.MsGmtLeftoverSaleOrderQtyModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsGmtLeftoverSaleOrderQtyModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		MsGmtLeftoverSaleOrderStyleDtl.resetForm();
		$('#gmtleftoversalecosi').html('');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsGmtLeftoverSaleOrderQtyModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsGmtLeftoverSaleOrderQtyModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsGmtLeftoverSaleOrderQty.resetForm()
		$('#gmtleftoversalecosi').html('');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsGmtLeftoverSaleOrderQtyModel.get(index,row);

	}

	showGrid(jhute_sale_dlv_order_item_id){
		let self=this;
		let data = {};
		data.jhute_sale_dlv_order_item_id=jhute_sale_dlv_order_item_id;
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
		return '<a href="javascript:void(0)"  onClick="MsGmtLeftoverSaleOrderQty.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	calculate(i){
		let rate=$('#gmtleftoversaleorderqtyFrm input[name="rate['+i+']"]').val();
		let qty=$('#gmtleftoversaleorderqtyFrm input[name="qty['+i+']"]').val();
	 let amount=msApp.multiply(qty,rate);
		$('#gmtleftoversaleorderqtyFrm input[name="amount['+i+']"]').val(amount)
	}
	

	calculateAmount(iteration,count,field){
		let rate=($('#gmtleftoversaleorderqtyFrm input[name="rate['+iteration+']"]').val())*1;
		let qty=($('#gmtleftoversaleorderqtyFrm input[name="qty['+iteration+']"]').val())*1;
	    let amount=msApp.multiply(qty,rate);
		$('#gmtleftoversaleorderqtyFrm input[name="amount['+iteration+']"]').val(amount)
		if(field==='qty'){
		this.copyQty(qty,iteration,count);
		}
		else if(field==='rate'){
		this.copyRate(rate,iteration,count);
		}
	}
	
	copyQty(qty,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			let rate=($('#gmtleftoversaleorderqtyFrm input[name="rate['+i+']"]').val())*1;
			let amount=msApp.multiply(qty,rate);
			$('#gmtleftoversaleorderqtyFrm input[name="qty['+i+']"]').val(qty)
			$('#gmtleftoversaleorderqtyFrm input[name="amount['+i+']"]').val(amount)
		}
	}


	copyRate(rate,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			let qty=($('#gmtleftoversaleorderqtyFrm input[name="qty['+i+']"]').val())*1;
			let amount=msApp.multiply(qty,rate);
			$('#gmtleftoversaleorderqtyFrm input[name="rate['+i+']"]').val(rate)
			$('#gmtleftoversaleorderqtyFrm input[name="amount['+i+']"]').val(amount)
		}
	}

}
window.MsGmtLeftoverSaleOrderQty=new MsGmtLeftoverSaleOrderQtyController(new MsGmtLeftoverSaleOrderQtyModel());