let MsLocalExpInvoiceOrderModel = require('./MsLocalExpInvoiceOrderModel');
class MsLocalExpInvoiceOrderController {
	constructor(MsLocalExpInvoiceOrderModel)
	{
		this.MsLocalExpInvoiceOrderModel = MsLocalExpInvoiceOrderModel;
		this.formId='localexpinvoiceorderFrm';
		this.dataTable='#localexpinvoiceorderTbl';
		this.route=msApp.baseUrl()+"/localexpinvoiceorder"
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
			this.MsLocalExpInvoiceOrderModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsLocalExpInvoiceOrderModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsLocalExpInvoiceOrderModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsLocalExpInvoiceOrderModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
	  $('#localexpinvoiceorderTbl').datagrid('reload');
		msApp.resetForm('localexpinvoiceorderFrm');
      $('#localexpinvoiceorderFrm  [name=local_exp_invoice_id]').val($('#localexpinvoiceFrm  [name=id]').val());
	  MsLocalExpInvoiceOrder.create(d.local_exp_invoice_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsLocalExpInvoiceOrderModel.get(index,row);
	}

	showGrid(local_exp_invoice_id)
	{
		let self=this;
      	var data={};
		data.local_exp_invoice_id=local_exp_invoice_id;
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
		return '<a href="javascript:void(0)"  onClick="MsLocalExpInvoiceOrder.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	create(local_exp_invoice_id)
	{
        let data= axios.get(this.route+"/create"+"?local_exp_invoice_id="+local_exp_invoice_id)
		.then(function (response) {
			$('#localexpinvoiceordermatrix').html(response.data);
			//$('#exppiordersearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	calculate(i)
	{
		let qty=$('#localexpinvoiceorderFrm [name="qty['+i+']"]').val();
		let rate=$('#localexpinvoiceorderFrm  [name="rate['+i+']"]').val();
		let amount=msApp.multiply(qty,rate);
		$('#localexpinvoiceorderFrm  [name="amount['+i+']"]').val(amount);
	}
}
window.MsLocalExpInvoiceOrder=new MsLocalExpInvoiceOrderController(new MsLocalExpInvoiceOrderModel());