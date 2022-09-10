let MsExpInvoiceOrderDtlModel = require('./MsExpInvoiceOrderDtlModel');
class MsExpInvoiceOrderDtlController {
	constructor(MsExpInvoiceOrderDtlModel)
	{
		this.MsExpInvoiceOrderDtlModel = MsExpInvoiceOrderDtlModel;
		this.formId='expinvoiceorderdtlFrm';
		this.dataTable='#expinvoiceorderdtlTbl';
		this.route=msApp.baseUrl()+"/expinvoiceorderdtl"
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
			this.MsExpInvoiceOrderDtlModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsExpInvoiceOrderDtlModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsExpInvoiceOrderDtlModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsExpInvoiceOrderDtlModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
        $('#expinvoiceorderdtlTbl').datagrid('reload');
        msApp.resetForm('expinvoiceorderdtlFrm');
        $('#expinvoiceorderdtlFrm  [name=exp_invoice_order_id]').val($('#expinvoiceorderFrm  [name=id]').val());
        MsExpInvoiceOrderDtl.createOrderDtl(d.exp_invoice_order_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsExpInvoiceOrderDtlModel.get(index,row);
	}

	showGrid(exp_invoice_order_id)
	{
		let self=this;
      var data={};
		data.exp_invoice_order_id=exp_invoice_order_id;
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
		return '<a href="javascript:void(0)"  onClick="MsExpInvoiceOrderDtl.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	/* create(exp_invoice_order_id)
	{
        let data= axios.get(this.route+"/create"+"?exp_invoice_order_id="+exp_invoice_order_id)
		.then(function (response) {
            $('#openinvoicesaleorderWindow').window('open');
			$('#expinvoiceorderdtlmatrix').html(response.data);
			//$('#exppiordersearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	} */
	calculate(i)
	{
		let qty=$('#expinvoiceorderdtlFrm [name="qty['+i+']"]').val();
		let rate=$('#expinvoiceorderdtlFrm  [name="rate['+i+']"]').val();
		let amount=msApp.multiply(qty,rate);
        $('#expinvoiceorderdtlFrm  [name="amount['+i+']"]').val(amount);

    }

    createOrderDtl(exp_invoice_order_id,invoice_qty){
        
        let data= axios.get(this.route+"/create"+"?exp_invoice_order_id="+exp_invoice_order_id+"&invoice_qty="+invoice_qty)
		.then(function (response) {
           // alert(exp_invoice_order_id);
            $('#openinvoicesaleorderWindow').window('open');
            $('#expinvoiceorderdtlmatrix').html(response.data);
            //alert()
		})
		.catch(function (error) {
			console.log(error);
		});
	}

}
window.MsExpInvoiceOrderDtl=new MsExpInvoiceOrderDtlController(new MsExpInvoiceOrderDtlModel());