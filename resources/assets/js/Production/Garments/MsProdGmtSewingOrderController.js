let MsProdGmtSewingOrderModel = require('./MsProdGmtSewingOrderModel');

class MsProdGmtSewingOrderController {
	constructor(MsProdGmtSewingOrderModel)
	{
		this.MsProdGmtSewingOrderModel = MsProdGmtSewingOrderModel;
		this.formId='prodgmtsewingorderFrm';
		this.dataTable='#prodgmtsewingorderTbl';
		this.route=msApp.baseUrl()+"/prodgmtsewingorder"
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
			this.MsProdGmtSewingOrderModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdGmtSewingOrderModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#sewinggmtcosi').html('');
		let prod_gmt_sewing_id = $('#prodgmtsewingFrm  [name=id]').val();
		$('#prodgmtsewingorderFrm  [name=prod_gmt_sewing_id]').val(prod_gmt_sewing_id);
		$('#prodgmtsewingorderFrm [id="supplier_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtSewingOrderModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdGmtSewingOrderModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodgmtsewingorderTbl').datagrid('reload');
		msApp.resetForm('prodgmtsewingorderFrm');
		MsProdGmtSewingOrder.resetForm();
		$('#prodgmtsewingorderFrm [name=prod_gmt_sewing_id]').val($('#prodgmtsewingFrm [name=id]').val());
		$('#prodgmtsewingorderFrm [id="supplier_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let prod=this.MsProdGmtSewingOrderModel.get(index,row);	
		prod.then(function (response) {	
		$('#prodgmtsewingorderFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid(prod_gmt_sewing_id){
		let self=this;
		let data = {};
		data.prod_gmt_sewing_id=prod_gmt_sewing_id;
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
		return '<a href="javascript:void(0)"  onClick="MsProdGmtSewingOrder.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	
	openOrderSewingWindow(){
		$('#openordersewingwindow').window('open');
	}

	getParams(){
		let params={};
		params.style_ref=$('#ordersewingsearchFrm [name=style_ref]').val();
		params.job_no=$('#ordersewingsearchFrm [name=job_no]').val();
		params.sale_order_no=$('#ordersewingsearchFrm [name=sale_order_no]').val();
		return params;
	}
	searchSewingOrderGrid(){
		let params=this.getParams();
		let d= axios.get(this.route+'/getsewingorder',{params})
		.then(function(response){
			$('#ordersewingsearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}
	showSewingOrderGrid(data){
		let self=this;
		$('#ordersewingsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#prodgmtsewingorderFrm [name=sales_order_country_id]').val(row.sales_order_country_id);
				$('#prodgmtsewingorderFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#prodgmtsewingorderFrm [name=order_qty]').val(row.order_qty);
				$('#prodgmtsewingorderFrm [name=country_id]').val(row.country_id);
				$('#prodgmtsewingorderFrm [name=job_no]').val(row.job_no);
				$('#prodgmtsewingorderFrm [name=company_id]').val(row.company_id);
				$('#prodgmtsewingorderFrm [name=buyer_name]').val(row.buyer_name);
				$('#prodgmtsewingorderFrm [name=produced_company_id]').val(row.produced_company_id);
				$('#prodgmtsewingorderFrm [name=produced_company_name]').val(row.produced_company_name);
				$('#prodgmtsewingorderFrm [name=ship_date]').val(row.ship_date);
				$('#openordersewingwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}

	/* openLineNoWindow(){
		$('#openlinenowindow').window('open');
	}
	searchLineNoGrid(){
		let data={};
		data.line_merged_id=$('#linenosearchFrm [name=line_merged_id]').val();
		data.produced_company_id=$('#prodgmtsewingorderFrm [name=produced_company_id]').val();
		data.prod_gmt_sewing_id=$('#prodgmtsewingFrm [name=id]').val();
		let self = this;
		$('#linenosearchTbl').datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			url:msApp.baseUrl()+"/prodgmtsewingorder/getline",
			onClickRow: function(index,row){
				$('#prodgmtsewingorderFrm [name=wstudy_line_setup_id]').val(row.id);
				$('#prodgmtsewingorderFrm [name=line_name]').val(row.line_code);
				$('#prodgmtsewingorderFrm [name=location_id]').val(row.location_name);
				$('#openlinenowindow').window('close');
				$('#linenosearchTbl').datagrid('loadData', []);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	} */

	openLineNoWindow(){
		$('#openlinenowindow').window('open');
	}

	getLineParams(){
		let params = {};
		params.line_merged_id=$('#linenosearchFrm [name=line_merged_id]').val();
		params.produced_company_id=$('#prodgmtsewingorderFrm [name=produced_company_id]').val();
		params.prod_gmt_sewing_id=$('#prodgmtsewingFrm [name=id]').val();
		return params;
	}
	searchLineNoGrid(){
		let params = this.getLineParams();
		let d = axios.get(this.route+'/getline',{params})
		.then(function(response){
			$('#linenosearchTbl').datagrid('loadData', response.data);
		}).catch(function(error){
			console.log(error);
		});
	}

	showLineGrid(data){
		let self = this;
		$('#linenosearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#prodgmtsewingorderFrm [name=wstudy_line_setup_id]').val(row.id);
				$('#prodgmtsewingorderFrm [name=line_name]').val(row.line_code);
				$('#prodgmtsewingorderFrm [name=location_id]').val(row.location_name);
				$('#openlinenowindow').window('close');
				$('#linenosearchTbl').datagrid('loadData', []);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}


}
window.MsProdGmtSewingOrder=new MsProdGmtSewingOrderController(new MsProdGmtSewingOrderModel());
MsProdGmtSewingOrder.showSewingOrderGrid([]);
MsProdGmtSewingOrder.showLineGrid([]);