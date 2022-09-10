require('./../datagrid-filter.js');
let MsSrmProductSaleModel= require('./MsSrmProductSaleModel');
class MsSrmProductSaleController {
	constructor(MsSrmProductSaleModel)
	{
		this.MsSrmProductSaleModel= MsSrmProductSaleModel;
		this.formId='srmproductsaleFrm';
		this.dataTable='#srmproductsaleTbl';
		this.route=msApp.baseUrl()+"/srmproductsale"
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
		//let formObj=msApp.get(this.formId);
		let formObj=this.getData();
		if(formObj.id){
			this.MsSrmProductSaleModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSrmProductSaleModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
		
		
	}

	getData(){
		let formObj=msApp.get('srmproductsaleFrm');
		let i=1;
		$.each($('#srmproductscanTbl').datagrid('getRows'),function(idx,val){
			//formObj['srm_product_sale_id['+i+']']=val.srm_product_sale_id;
			formObj['srm_product_receive_dtl_id['+i+']']=val.srm_product_receive_dtl_id;
			formObj['qty['+i+']']=val.qty;
			formObj['sales_rate['+i+']']=val.sales_rate;
			formObj['amount['+i+']']=val.amount;
			formObj['vat_per['+i+']']=val.vat;
			formObj['source_tax_per['+i+']']=val.source_tax;
			formObj['gross_amount['+i+']']=val.gross_amount;
			i++;
		});
		return formObj;
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
		//$('#inspectionordergmtcosi').html('');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSrmProductSaleModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSrmProductSaleModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsSrmProductSale.get();
		$('#srmproductscanTbl').datagrid('loadData',[]);
		msApp.resetForm('srmproductsaleFrm');
		MsSrmProductSale.resetForm();
		MsSrmProductSale.getInvoice(d.id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSrmProductSaleModel.get(index,row);	
	}
	get()
	{
		let d= axios.get(this.route)
		.then(function (response) {
			$('#srmproductsaleTbl').datagrid('loadData',response.data);
			/*if(response.data.qty){
			$('#srmproductscanTbl').datagrid('appendRow',response.data);
			let data=MsSrmProductSale.getsum();
			MsSrmProductSale.reloadFooter(data);
			$('#srmproductsaleFrm [name=gross_amount]').val(data.total_gross_amount.toFixed(2));
			}*/
			
		})
		.catch(function (error) {
			console.log(error);
		});

	}
	showGrid(data)
	{

		let self=this;
		$(this.dataTable).datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			showFooter:true,
			onClickRow: function(index,row){
				//self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}
 
	

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSrmProductSale.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showScanGrid(data){
		let self=this;
		$('#srmproductscanTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			showFooter:true,
			nowrap:false,
			rownumbers:true,
			onClickRow: function(index,row){
			$('#srmproductscanTbl').datagrid('selectRow',index);
            $(this).datagrid('beginEdit', index);
			},
			onBeginEdit:function(rowIndex){
				var editors = $('#srmproductscanTbl').datagrid('getEditors', rowIndex);
				var n1 = $(editors[0].target);

				n1.numberbox({
				onChange:function(){
					let qty = n1.numberbox('getValue');
					$('#srmproductscanTbl').datagrid('endEdit', rowIndex);
				}
				})
			},
            onEndEdit:function(index,row){
            	    row.amount=row.qty*row.sales_rate;
					row.gross_amount=(row.amount+(row.vat*1)+(row.source_tax*1)).toFixed(2);
					$('#srmproductscanTbl').datagrid('updateRow',{
					index: index,
					row: row
					});
               
            },
            onAfterEdit:function(index,row){
               row.editing = false;
               $(this).datagrid('refreshRow', index);
				let data=MsSrmProductSale.getsum();
				MsSrmProductSale.reloadFooter(data);
				$('#srmproductsaleFrm [name=gross_amount]').val(data.total_gross_amount.toFixed(2));
            }
		}).datagrid('loadData', data);
	}

	getProduct(){
		let srm_product_receive_dtl_id=$('#srm_product_receive_dtl_id').val();
		$('#srm_product_receive_dtl_id').val('');
		$( "#srm_product_receive_dtl_id" ).focus();
		let params={};
		params.srm_product_receive_dtl_id = srm_product_receive_dtl_id;
		let d= axios.get(this.route+'/getproduct',{params})
		.then(function (response) {
			if(response.data.qty){
			$('#srmproductscanTbl').datagrid('appendRow',response.data);
			let data=MsSrmProductSale.getsum();
			MsSrmProductSale.reloadFooter(data);
			$('#srmproductsaleFrm [name=gross_amount]').val(data.total_gross_amount.toFixed(2));
			}
			
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	getsum(){
		let data={};
		let total_qty=0;
		let total_amount=0;
		let total_gross_amount=0;
		$.each($('#srmproductscanTbl').datagrid('getRows'), function (idx, val) {
			if(val.qty){
				total_qty+=val.display_qty*1;
			}
			if (val.amount) {
				total_amount+=val.display_amount*1;
			}		
			if (val.gross_amount) {
				total_gross_amount+=val.gross_amount*1;
			}	
		});
		data.total_qty=total_qty;
		data.total_amount=total_amount;
		data.total_gross_amount=total_gross_amount;
		return data;
	}

	reloadFooter(data)
	{
		$('#srmproductscanTbl').datagrid('reloadFooter', [
		{ 
			item_desc:'Total',
			qty: data.total_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),amount:data.total_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
			gross_amount:data.total_gross_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
		}
	]);
	}

	afterDiscount(){
		let self = this;
		let gross_amount;
		let discount_amount;
		gross_amount=$('#srmproductsaleFrm [name=gross_amount]').val()*1;
		discount_amount=$('#srmproductsaleFrm [name=discount_amount]').val()*1;
		let afterdiscount=gross_amount-discount_amount;
		$('#srmproductsaleFrm [name=net_paid_amount]').val(afterdiscount)
	}

	cashReturn(){
		let self = this;
		let net_paid_amount;
		let paid_amount;
		net_paid_amount=$('#srmproductsaleFrm [name=net_paid_amount]').val()*1;
		paid_amount=$('#srmproductsaleFrm [name=paid_amount]').val()*1;
		let return_amount=paid_amount-net_paid_amount;
		$('#srmproductsaleFrm [name=return_amount]').val(return_amount);
	}


	getInvoice(id){
		
		let params={};
		params.id = id;
		let d= axios.get(this.route+'/getinvoice',{params})
		.then(function (response) {
			//$('#printit').html(response.data)
			w=window.open();
			w.document.write(response.data);
			w.print();
			w.close();
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	print()
	{
	w=window.open();
	w.document.write($('#printit').html());
	w.print();
	w.close();
	}

	showPdf(id)
	{
		if(id==""){
			alert("Select a Invoice");
			return;
		}
		window.open(msApp.baseUrl()+"/srmproductsale/getdetailinvoice?id="+id);
	}

	formatPdf(value,row){
		return '<a href="javascript:void(0)" onClick="MsSrmProductSale.showPdf('+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Pdf</span></a>';
	}
	
}
window.MsSrmProductSale=new MsSrmProductSaleController(new MsSrmProductSaleModel());
MsSrmProductSale.showGrid([]);
MsSrmProductSale.showScanGrid([]);
MsSrmProductSale.get();