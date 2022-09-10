let MsExpDocSubInvoiceModel = require('./MsExpDocSubInvoiceModel');
class MsExpDocSubInvoiceController {
	constructor(MsExpDocSubInvoiceModel)
	{
		this.MsExpDocSubInvoiceModel = MsExpDocSubInvoiceModel;
		this.formId='expdocsubinvoiceFrm';
		this.dataTable='#expdocsubinvoiceTbl';
		this.route=msApp.baseUrl()+"/expdocsubinvoice"
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
			this.MsExpDocSubInvoiceModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsExpDocSubInvoiceModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsExpDocSubInvoiceModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsExpDocSubInvoiceModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#expdocsubinvoiceTbl').datagrid('reload');
		msApp.resetForm('expdocsubinvoiceFrm');
		$('#expdocsubinvoiceFrm [name=exp_doc_submission_id]').val($('#expdocsubmissionFrm [name=id]').val());
		MsExpDocSubInvoice.create ($('#expdocsubmissionFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		this.MsExpDocSubInvoiceModel.get(index,row);

	}

	showGrid(data)
	{
		/*let self=this;
		var data={};
		data.exp_doc_submission_id=exp_doc_submission_id;*/
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
				var invoice_value=0;
				var discount_amount=0;
				var bonus_amount=0;
				var claim_amount=0;
				var commission=0;
				var net_inv_value=0;
				for(var i=0; i<data.rows.length; i++){
					invoice_value+=data.rows[i]['invoice_value'].replace(/,/g,'')*1;
					discount_amount+=data.rows[i]['discount_amount'].replace(/,/g,'')*1;
					bonus_amount+=data.rows[i]['bonus_amount'].replace(/,/g,'')*1;
					claim_amount+=data.rows[i]['claim_amount'].replace(/,/g,'')*1;
					commission+=data.rows[i]['commission'].replace(/,/g,'')*1;
					net_inv_value+=data.rows[i]['net_inv_value'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					invoice_value: invoice_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					discount_amount: discount_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					bonus_amount: bonus_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					claim_amount: claim_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					commission: commission.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					net_inv_value: net_inv_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
				}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	showGrid2(data)
	{
		
		$('#expdocsubinvoiceTbl2').datagrid({
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
				var invoice_value=0;
				var discount_amount=0;
				var bonus_amount=0;
				var claim_amount=0;
				var commission=0;
				var net_inv_value=0;
				for(var i=0; i<data.rows.length; i++){
					invoice_value+=data.rows[i]['invoice_value'].replace(/,/g,'')*1;
					discount_amount+=data.rows[i]['discount_amount'].replace(/,/g,'')*1;
					bonus_amount+=data.rows[i]['bonus_amount'].replace(/,/g,'')*1;
					claim_amount+=data.rows[i]['claim_amount'].replace(/,/g,'')*1;
					commission+=data.rows[i]['commission'].replace(/,/g,'')*1;
					net_inv_value+=data.rows[i]['net_inv_value'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					invoice_value: invoice_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					discount_amount: discount_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					bonus_amount: bonus_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					claim_amount: claim_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					commission: commission.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					net_inv_value: net_inv_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
				}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsExpDocSubInvoice.delete(event,'+row.exp_doc_sub_invoice_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	create (exp_doc_submission_id)
	{
	let data= axios.get(this.route+"/create"+"?exp_doc_submission_id="+exp_doc_submission_id)
		.then(function (response) {
			//$('#expinvoiceordermatrix').html(response.data);
			//$('#expdocsubinvoiceTbl2').datagrid('loadData', response.data.new);
			//$('#expdocsubinvoiceTbl').datagrid('loadData', response.data.saved);
			MsExpDocSubInvoice.showGrid(response.data.saved)
			MsExpDocSubInvoice.showGrid2(response.data.new)
		})
		.catch(function (error) {
			console.log(error);
		});	
	}
	getSelections()
	{
		let formObj={};
		formObj.exp_doc_submission_id=$('#expdocsubmissionFrm  [name=id]').val();
		let i=1;
		$.each($('#expdocsubinvoiceTbl2').datagrid('getSelections'), function (idx, val) {
			formObj['exp_invoice_id['+i+']']=val.exp_invoice_id
			i++;
		});
		return formObj;
	}

}
window.MsExpDocSubInvoice=new MsExpDocSubInvoiceController(new MsExpDocSubInvoiceModel());
MsExpDocSubInvoice.showGrid2([]);
MsExpDocSubInvoice.showGrid([]);