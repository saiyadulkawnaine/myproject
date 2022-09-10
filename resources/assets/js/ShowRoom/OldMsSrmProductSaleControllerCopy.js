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
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsSrmProductSaleModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSrmProductSaleModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#inspectionordergmtcosi').html('');

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
		$('#srmproductsaleTbl').datagrid('reload');
		msApp.resetForm('srmproductsaleFrm');
		MsSrmProductSaleDtl.resetForm();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSrmProductSaleModel.get(index,row);
		
	}

	showGrid(data){

		let self=this;
		$(this.dataTable).datagrid({
			//method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			showFooter:true,
			//fitColumns:true,
			//url:this.route,
			onClickRow: function(index,row){
				//self.edit(index,row);
			}
		}).datagrid('loadData', data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSrmProductSale.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showScanGrid(data){
		let self=this;
		$('#showroomproductscanTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			showFooter:true,
			onClickRow: function(index,row){
				//self.edit(index,row);
			}
		}).datagrid('loadData', data);
	}

	getProduct(){
		let bar_code_no=$('#bar_code_no').val();
		let params={};
		params.bar_code_no = bar_code_no;
		let d= axios.get(this.route+'/getproduct',{params})
		.then(function (response) {
			//$('#showroomproductscanTbl').datagrid('loadData', response.data);
			$('#showroomproductscanTbl').datagrid('appendRow',response.data);
			$('#srmproductsaleTbl').datagrid('appendRow',response.data);
			let data=MsSrmProductSale.getsum();
			MsSrmProductSale.reloadFooter(data);
			$('#bar_code_no').val('');
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	getsum(){
		let data={};
		let total_qty=0;
		let total_amount=0;
		$.each($('#showroomproductscanTbl').datagrid('getRows'), function (idx, val) {
				
				if(val.qty){
					total_qty+=val.qty*1;
				}
				if (val.amount) {
					total_amount+=val.amount*1;
				}
		});
		//let balance=parseFloat((total_debit-total_credit).toFixed(2));
		data.total_qty=total_qty;
		data.total_amount=total_amount;
		return data;
	}

	reloadFooter(data)
	{
		$('#showroomproductscanTbl').datagrid('reloadFooter', [
		{ item_desc:'Total',qty: data.total_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),amount:data.total_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}
		]);
		$('#srmproductsaleTbl').datagrid('reloadFooter', [
		{ item_desc:'Total',qty: data.total_qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),amount:data.total_amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}
		]);
	}

	
	
}
window.MsSrmProductSale=new MsSrmProductSaleController(new MsSrmProductSaleModel());
MsSrmProductSale.showGrid([]);
MsSrmProductSale.showScanGrid([]);
