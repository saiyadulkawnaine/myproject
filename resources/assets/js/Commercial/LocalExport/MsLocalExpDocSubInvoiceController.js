let MsLocalExpDocSubInvoiceModel = require('./MsLocalExpDocSubInvoiceModel');
class MsLocalExpDocSubInvoiceController {
	constructor(MsLocalExpDocSubInvoiceModel)
	{
		this.MsLocalExpDocSubInvoiceModel = MsLocalExpDocSubInvoiceModel;
		this.formId='localexpdocsubinvoiceFrm';
		this.dataTable='#localexpdocsubinvoiceTbl';
		this.route=msApp.baseUrl()+"/localexpdocsubinvoice"
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

		let formObj=this.getSelections();
		if(formObj.id){
			this.MsLocalExpDocSubInvoiceModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsLocalExpDocSubInvoiceModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsLocalExpDocSubInvoiceModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsLocalExpDocSubInvoiceModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#expdocsubinvoiceTbl').datagrid('reload');
		msApp.resetForm('localexpdocsubinvoiceFrm');
		$('#localexpdocsubinvoiceFrm [name=local_exp_doc_sub_accept_id]').val($('#localexpdocsubacceptFrm [name=id]').val());
		MsLocalExpDocSubInvoice.create ($('#localexpdocsubacceptFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsLocalExpDocSubInvoiceModel.get(index,row);

	}

	showGrid(data)
	{
		/*let self=this;
		var data={};
		data.local_exp_doc_sub_accept_id=local_exp_doc_sub_accept_id;*/
		$(this.dataTable).datagrid({
			//method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//queryParams:data,
			showFooter:true,
			fitColumns:true,
			data:data,
			//url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var local_invoice_value=0;
				for(var i=0; i<data.rows.length; i++){
					local_invoice_value+=data.rows[i]['local_invoice_value'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
					{ 
						local_invoice_value: local_invoice_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
			}
		}).datagrid('enableFilter')/* .datagrid('loadData', data) */;
	}

	showGrid2(data)
	{
		
		$('#localexpdocsubinvoiceTbl2').datagrid({
			//method:'get',
			border:false,
			singleSelect:false,
			fit:true,
			//queryParams:data,
			fitColumns:true,
			showFooter:true,
			///url:this.route,
			data:data,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var local_invoice_value=0;
				for(var i=0; i<data.rows.length; i++){
					local_invoice_value+=data.rows[i]['local_invoice_value'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					local_invoice_value: local_invoice_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		}).datagrid('enableFilter')/* .datagrid('loadData', data) */;
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsLocalExpDocSubInvoice.delete(event,'+row.local_exp_doc_sub_invoice_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	create (local_exp_doc_sub_accept_id)
	{
	let data= axios.get(this.route+"/create"+"?local_exp_doc_sub_accept_id="+local_exp_doc_sub_accept_id)
		.then(function (response) {
			MsLocalExpDocSubInvoice.showGrid(response.data.saved)
			MsLocalExpDocSubInvoice.showGrid2(response.data.new)
		})
		.catch(function (error) {
			console.log(error);
		});	
	}
	
	getSelections()
	{
		let formObj={};
		formObj.local_exp_doc_sub_accept_id=$('#localexpdocsubacceptFrm  [name=id]').val();
		let i=1;
		$.each($('#localexpdocsubinvoiceTbl2').datagrid('getSelections'), function (idx, val) {
			formObj['local_exp_invoice_id['+i+']']=val.local_exp_invoice_id
			i++;
		});
		return formObj;
	}

}
window.MsLocalExpDocSubInvoice=new MsLocalExpDocSubInvoiceController(new MsLocalExpDocSubInvoiceModel());
MsLocalExpDocSubInvoice.showGrid2([]);
MsLocalExpDocSubInvoice.showGrid([]);