let MsProdGmtCuttingOrderModel = require('./MsProdGmtCuttingOrderModel');

class MsProdGmtCuttingOrderController {
	constructor(MsProdGmtCuttingOrderModel)
	{
		this.MsProdGmtCuttingOrderModel = MsProdGmtCuttingOrderModel;
		this.formId='prodgmtcuttingorderFrm';
		this.dataTable='#prodgmtcuttingorderTbl';
		this.route=msApp.baseUrl()+"/prodgmtcuttingorder"
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
			this.MsProdGmtCuttingOrderModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdGmtCuttingOrderModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#cuttinggmtcosi').html('');
		let prod_gmt_cutting_id = $('#prodgmtcuttingFrm  [name=id]').val();
		$('#prodgmtcuttingorderFrm  [name=prod_gmt_cutting_id]').val(prod_gmt_cutting_id);
		$('#prodgmtcuttingorderFrm [id="supplier_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtCuttingOrderModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdGmtCuttingOrderModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodgmtcuttingorderTbl').datagrid('reload');
		msApp.resetForm('prodgmtcuttingorderFrm');
		MsProdGmtCuttingQty.resetForm();
		$('#prodgmtcuttingorderFrm [name=prod_gmt_cutting_id]').val($('#prodgmtcuttingFrm [name=id]').val());
		$('#prodgmtcuttingorderFrm [id="supplier_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let cutting=this.MsProdGmtCuttingOrderModel.get(index,row);
		cutting.then(function (response) {
			$('#prodgmtcuttingorderFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
		}).catch(function (error) {
			console.log(error);
		});
	}

	showGrid(prod_gmt_cutting_id){
		let self=this;
		let data = {};
		data.prod_gmt_cutting_id=prod_gmt_cutting_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			fitColumns:true,
			showFooter:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdGmtCuttingOrder.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	
	openOrderCuttingWindow(){
		$('#openordercuttingwindow').window('open');
	}

	getParams(){
		let params={};
		params.style_ref=$('#ordercuttingsearchFrm [name=style_ref]').val();
		params.job_no=$('#ordercuttingsearchFrm [name=job_no]').val();
		params.sale_order_no=$('#ordercuttingsearchFrm [name=sale_order_no]').val();
		return params;
	}
	searchCuttingOrderGrid(){
		let params=this.getParams();
		let d= axios.get(this.route+'/getcuttingorder',{params})
		.then(function(response){
			$('#ordercuttingsearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}
	showCuttingOrderGrid(data){
		let self=this;
		$('#ordercuttingsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#prodgmtcuttingorderFrm [name=sales_order_country_id]').val(row.sales_order_country_id);
				$('#prodgmtcuttingorderFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#prodgmtcuttingorderFrm [name=order_qty]').val(row.order_qty);
				$('#prodgmtcuttingorderFrm [name=country_id]').val(row.country_id);
				$('#prodgmtcuttingorderFrm [name=job_no]').val(row.job_no);
				$('#prodgmtcuttingorderFrm [name=company_id]').val(row.company_id);
				$('#prodgmtcuttingorderFrm [name=buyer_name]').val(row.buyer_name);
				$('#prodgmtcuttingorderFrm [name=ship_date]').val(row.ship_date);
				$('#openordercuttingwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}

}
window.MsProdGmtCuttingOrder=new MsProdGmtCuttingOrderController(new MsProdGmtCuttingOrderModel());
MsProdGmtCuttingOrder.showCuttingOrderGrid([]);