let MsExpAdvInvoiceOrderModel = require('./MsExpAdvInvoiceOrderModel');
class MsExpAdvInvoiceOrderController {
	constructor(MsExpAdvInvoiceOrderModel)
	{
		this.MsExpAdvInvoiceOrderModel = MsExpAdvInvoiceOrderModel;
		this.formId='expadvinvoiceorderFrm';
		this.dataTable='#expadvinvoiceorderTbl';
		this.route=msApp.baseUrl()+"/expadvinvoiceorder"
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
			this.MsExpAdvInvoiceOrderModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsExpAdvInvoiceOrderModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsExpAdvInvoiceOrderModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsExpAdvInvoiceOrderModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#expadvinvoiceorderTbl').datagrid('reload');
		msApp.resetForm('expadvinvoiceorderFrm');
		$('#expadvinvoiceorderFrm  [name=exp_adv_invoice_id]').val($('#expadvinvoiceFrm  [name=id]').val());
		MsExpAdvInvoiceOrder.create(d.exp_adv_invoice_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsExpAdvInvoiceOrderModel.get(index,row);
	}

	showGrid(exp_adv_invoice_id)
	{
		let self=this;
      var data={};
		data.exp_adv_invoice_id=exp_adv_invoice_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
         queryParams:data,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		});
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsExpAdvInvoiceOrder.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	create(exp_adv_invoice_id)
	{
        let data= axios.get(this.route+"/create"+"?exp_adv_invoice_id="+exp_adv_invoice_id)
		.then(function (response) {
			$('#expadvinvoiceordermatrix').html(response.data);
			//$('#exppiordersearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	calculate(i)
	{
		let qty=$('#expadvinvoiceorderFrm [name="qty['+i+']"]').val();
		let rate=$('#expadvinvoiceorderFrm  [name="rate['+i+']"]').val();
		let amount=msApp.multiply(qty,rate);
		$('#expadvinvoiceorderFrm  [name="amount['+i+']"]').val(amount);
	}
}
window.MsExpAdvInvoiceOrder=new MsExpAdvInvoiceOrderController(new MsExpAdvInvoiceOrderModel());
