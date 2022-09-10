let MsSalesOrderSizeModel = require('./MsSalesOrderSizeModel');
class MsSalesOrderSizeController {
	constructor(MsSalesOrderSizeModel)
	{
		this.MsSalesOrderSizeModel = MsSalesOrderSizeModel;
		this.formId='salesordersizeFrm';
		this.dataTable='#salesordersizeTbl';
		this.route=msApp.baseUrl()+"/salesordersize"
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
			this.MsSalesOrderSizeModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSalesOrderSizeModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSalesOrderSizeModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSalesOrderSizeModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#salesordersizeTbl').datagrid('reload');
		msApp.resetForm('salesordersizeFrm');
		$('#salesordersizeFrm #sizetable').empty();
		MsSalesOrderColor.showGrid($('#salesordercountryFrm  [name=id]').val());

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSalesOrderSizeModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsSalesOrderSize.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	calculate(iteration,count,field){
		let qty=$('input[name="qty['+iteration+']"]').val();
		let rate=$('input[name="rate['+iteration+']"]').val();
		let amount=msApp.multiply(qty,rate);
		$('input[name="amount['+iteration+']"]').val(amount);
		if($('#is_copy').is(":checked")){
			if(field==='qty'){
				this.copyQty(qty,iteration,count);
			}
			else if(field==='rate'){
				this.copyRate(rate,iteration,count);
			}
		}
	}
	copyQty(qty,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			let rate=$('input[name="rate['+i+']"]').val();
			let amount=msApp.multiply(qty,rate);
			$('input[name="qty['+i+']"]').val(qty)
			$('input[name="amount['+i+']"]').val(amount)
		}
	}
	copyRate(rate,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			let qty=$('input[name="qty['+i+']"]').val();
			let amount=msApp.multiply(qty,rate);
			$('input[name="rate['+i+']"]').val(rate)
			$('input[name="amount['+i+']"]').val(amount)
		}
	}
}
window.MsSalesOrderSize=new MsSalesOrderSizeController(new MsSalesOrderSizeModel());
//MsSalesOrderSize.showGrid();
