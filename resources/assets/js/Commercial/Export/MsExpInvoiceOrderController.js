let MsExpInvoiceOrderModel = require('./MsExpInvoiceOrderModel');
class MsExpInvoiceOrderController {
	constructor(MsExpInvoiceOrderModel)
	{
		this.MsExpInvoiceOrderModel = MsExpInvoiceOrderModel;
		this.formId='expinvoiceorderFrm';
		this.dataTable='#expinvoiceorderTbl';
		this.route=msApp.baseUrl()+"/expinvoiceorder"
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
			this.MsExpInvoiceOrderModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsExpInvoiceOrderModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsExpInvoiceOrderModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsExpInvoiceOrderModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#expinvoiceorderTbl').datagrid('reload');
		msApp.resetForm('expinvoiceorderFrm');
		$('#expinvoiceorderFrm  [name=exp_invoice_id]').val($('#expinvoiceFrm  [name=id]').val());
		MsExpInvoiceOrder.create(d.exp_invoice_id);

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsExpInvoiceOrderModel.get(index,row);
	}

	showGrid(exp_invoice_id)
	{
		let self=this;
      var data={};
		data.exp_invoice_id=exp_invoice_id;
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
		return '<a href="javascript:void(0)"  onClick="MsExpInvoiceOrder.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	create(exp_invoice_id)
	{
        let data= axios.get(this.route+"/create"+"?exp_invoice_id="+exp_invoice_id)
		.then(function (response) {
			$('#expinvoiceordermatrix').html(response.data);
			//$('#exppiordersearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	calculate(i)
	{
		let qty=$('#expinvoiceorderFrm [name="qty['+i+']"]').val();
		let rate=$('#expinvoiceorderFrm  [name="rate['+i+']"]').val();
		let amount=msApp.multiply(qty,rate);
		$('#expinvoiceorderFrm  [name="amount['+i+']"]').val(amount);
	}
}
window.MsExpInvoiceOrder=new MsExpInvoiceOrderController(new MsExpInvoiceOrderModel());
