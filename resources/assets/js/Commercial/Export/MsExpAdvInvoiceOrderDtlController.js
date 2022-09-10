let MsExpAdvInvoiceOrderDtlModel = require('./MsExpAdvInvoiceOrderDtlModel');
class MsExpAdvInvoiceOrderDtlController {
	constructor(MsExpAdvInvoiceOrderDtlModel)
	{
		this.MsExpAdvInvoiceOrderDtlModel = MsExpAdvInvoiceOrderDtlModel;
		this.formId='expadvinvoiceorderdtlFrm';
		this.dataTable='#expadvinvoiceorderdtlTbl';
		this.route=msApp.baseUrl()+"/expadvinvoiceorderdtl"
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
			this.MsExpAdvInvoiceOrderDtlModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsExpAdvInvoiceOrderDtlModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsExpAdvInvoiceOrderDtlModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsExpAdvInvoiceOrderDtlModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
        $('#expadvinvoiceorderdtlTbl').datagrid('reload');
        msApp.resetForm('expadvinvoiceorderdtlFrm');
        $('#expadvinvoiceorderdtlFrm  [name=exp_adv_invoice_order_id]').val($('#expadvinvoiceorderFrm  [name=id]').val());
        MsExpAdvInvoiceOrderDtl.createOrderDtl(d.exp_adv_invoice_order_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsExpAdvInvoiceOrderDtlModel.get(index,row);
	}

	showGrid(exp_adv_invoice_order_id)
	{
		let self=this;
      var data={};
		data.exp_adv_invoice_order_id=exp_adv_invoice_order_id;
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
		return '<a href="javascript:void(0)"  onClick="MsExpAdvInvoiceOrderDtl.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	/* create(exp_adv_invoice_order_id)
	{
        let data= axios.get(this.route+"/create"+"?exp_adv_invoice_order_id="+exp_adv_invoice_order_id)
		.then(function (response) {
            $('#openinvoicesaleorderWindow').window('open');
			$('#expadvinvoiceorderdtlmatrix').html(response.data);
			//$('#exppiordersearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	} */
	calculate(i)
	{
		let qty=$('#expadvinvoiceorderdtlFrm [name="qty['+i+']"]').val();
		let rate=$('#expadvinvoiceorderdtlFrm  [name="rate['+i+']"]').val();
		let amount=msApp.multiply(qty,rate);
        $('#expadvinvoiceorderdtlFrm  [name="amount['+i+']"]').val(amount);

    }

    createOrderDtl(exp_adv_invoice_order_id,invoice_qty){
        
        let data= axios.get(this.route+"/create"+"?exp_adv_invoice_order_id="+exp_adv_invoice_order_id+"&invoice_qty="+invoice_qty)
		.then(function (response) {
           // alert(exp_adv_invoice_order_id);
            $('#openinvoicesaleorderWindow').window('open');
            $('#expadvinvoiceorderdtlmatrix').html(response.data);
            //alert()
		})
		.catch(function (error) {
			console.log(error);
		});
	}

}
window.MsExpAdvInvoiceOrderDtl=new MsExpAdvInvoiceOrderDtlController(new MsExpAdvInvoiceOrderDtlModel());