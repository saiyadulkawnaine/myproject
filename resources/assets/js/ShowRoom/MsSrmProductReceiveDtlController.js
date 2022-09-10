let MsSrmProductReceiveDtlModel = require('./MsSrmProductReceiveDtlModel');

class MsSrmProductReceiveDtlController {
	constructor(MsSrmProductReceiveDtlModel)
	{
		this.MsSrmProductReceiveDtlModel = MsSrmProductReceiveDtlModel;
		this.formId='srmproductreceivedtlFrm';	             
		this.dataTable='#srmproductreceivedtlTbl';
		this.route=msApp.baseUrl()+"/srmproductreceivedtl"
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
		let srm_product_receive_id=$('#srmproductreceiveFrm [name=id]').val();
		let formObj=msApp.get(this.formId);
		formObj.srm_product_receive_id=srm_product_receive_id;
		if(formObj.id){
			this.MsSrmProductReceiveDtlModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSrmProductReceiveDtlModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	/* create(srm_product_receive_id)
	{
		//let srm_product_receive_id=$('#srmproductreceiveFrm [name=id]').val();
        let data= axios.get(this.route+"/create"+"?srm_product_receive_id="+srm_product_receive_id)
		.then(function (response) {
			$('#receivedtalicosi').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	} */

	resetForm ()
	{
		msApp.resetForm(this.formId);
		MsSrmProductReceive.resetForm();
		$('#receivedtalicosi').html('');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSrmProductReceiveDtlModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSrmProductReceiveDtlModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsSrmProductReceiveDtl.resetForm()
		$('#receivedtalicosi').html('');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSrmProductReceiveDtlModel.get(index,row);
	}

	showGrid(srm_product_receive_id){
		let self=this;
		let data = {};
		data.srm_product_receive_id=srm_product_receive_id;
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
		return '<a href="javascript:void(0)"  onClick="MsSrmProductReceiveDtl.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	

	bercodePdf(srm_product_receive_dtl_id){	   
	   window.open(this.route+"/receivebercodepdf?id="+srm_product_receive_dtl_id);
	}
	orderCalculate(i)
	{
		let qty=$('#srmproductreceivedtlFrm [name="qty['+i+']"]').val();
		let rate=$('#srmproductreceivedtlFrm  [name="rate['+i+']"]').val();
		let amount=msApp.multiply(qty,rate);
        $('#srmproductreceivedtlFrm  [name="amount['+i+']"]').val(amount);

	}

	copy(iteration,count,field){
		let sales_rate=$('#srmproductreceivedtlFrm [name="sales_rate['+iteration+']"]').val();
		let vat_per=$('#srmproductreceivedtlFrm  [name="vat_per['+iteration+']"]').val();
		let source_tax_per=$('#srmproductreceivedtlFrm  [name="source_tax_per['+iteration+']"]').val();
		//let amount=msApp.multiply(qty,rate);
		//$('#srmproductreceivedtlFrm  [name="amount['+iteration+']"]').val(amount);
		if($('#srmproductreceivedtlFrm  #is_copy').is(":checked")){
			if(field==='sales_rate'){
				for(var i=iteration;i<=count;i++)
				{
					$('#srmproductreceivedtlFrm  [name="sales_rate['+i+']"]').val(sales_rate)
				}
			}
			else if(field==='vat_per'){
				for(var i=iteration;i<=count;i++)
				{
				$('#srmproductreceivedtlFrm  [name="vat_per['+i+']"]').val(vat_per)
				}
			}
			else if(field==='source_tax_per'){
				for(var i=iteration;i<=count;i++)
				{
				$('#srmproductreceivedtlFrm  [name="source_tax_per['+i+']"]').val(source_tax_per)
				}
			}
		}
	}

}
window.MsSrmProductReceiveDtl = new MsSrmProductReceiveDtlController(new MsSrmProductReceiveDtlModel());