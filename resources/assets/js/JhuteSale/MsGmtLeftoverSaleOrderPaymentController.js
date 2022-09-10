let MsGmtLeftoverSaleOrderPaymentModel = require('./MsGmtLeftoverSaleOrderPaymentModel');
class MsGmtLeftoverSaleOrderPaymentController {
	constructor(MsGmtLeftoverSaleOrderPaymentModel)
	{
		this.MsGmtLeftoverSaleOrderPaymentModel = MsGmtLeftoverSaleOrderPaymentModel;
		this.formId='gmtleftoversaleorderpaymentFrm';
		this.dataTable='#gmtleftoversaleorderpaymentTbl';
		this.route=msApp.baseUrl()+"/gmtleftoversaleorderpayment"
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
			this.MsGmtLeftoverSaleOrderPaymentModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsGmtLeftoverSaleOrderPaymentModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}


	resetForm ()
	{
		msApp.resetForm(this.formId);
			$('#gmtleftoversaleorderpaymentFrm [name=jhute_sale_dlv_order_id]').val($('#gmtleftoversaleorderFrm [name=id]').val());
			$('#gmtleftoversaleorderpaymentFrm [id="receive_by_id"]').combobox('setValue','');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsGmtLeftoverSaleOrderPaymentModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsGmtLeftoverSaleOrderPaymentModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#gmtleftoversaleorderpaymentTbl').datagrid('reload');
		msApp.resetForm('gmtleftoversaleorderpaymentFrm');
			$('#gmtleftoversaleorderpaymentFrm [name=jhute_sale_dlv_order_id]').val($('#gmtleftoversaleorderFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
  		let payment=this.MsGmtLeftoverSaleOrderPaymentModel.get(index, row);
		payment.then(function(response){
			$('#gmtleftoversaleorderpaymentFrm [id="receive_by_id"]').combobox('setValue',response.data.fromData.receive_by_id);
		}).catch(function(error){
			console.log(error);
		})
	}

	showGrid(jhute_sale_dlv_order_id)
	{
		let self=this;
		var data={};
		data.jhute_sale_dlv_order_id=jhute_sale_dlv_order_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			queryParams:data,
			url:this.route,
			showFooter:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess:function(data){
				var tAmount = 0 ;
				for(var i=0; i<data.rows.length; i++){
					tAmount+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
					{
						amount: tAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
					}
				]);

			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsGmtLeftoverSaleOrderPayment.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsGmtLeftoverSaleOrderPayment = new MsGmtLeftoverSaleOrderPaymentController(new MsGmtLeftoverSaleOrderPaymentModel());


