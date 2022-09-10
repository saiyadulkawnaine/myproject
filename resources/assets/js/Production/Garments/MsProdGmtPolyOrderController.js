let MsProdGmtPolyOrderModel = require('./MsProdGmtPolyOrderModel');

class MsProdGmtPolyOrderController {
	constructor(MsProdGmtPolyOrderModel)
	{
		this.MsProdGmtPolyOrderModel = MsProdGmtPolyOrderModel;
		this.formId='prodgmtpolyorderFrm';
		this.dataTable='#prodgmtpolyorderTbl';
		this.route=msApp.baseUrl()+"/prodgmtpolyorder"
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
			this.MsProdGmtPolyOrderModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdGmtPolyOrderModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#polygmtcosi').html('');
		let prod_gmt_poly_id = $('#prodgmtpolyFrm  [name=id]').val();
		$('#prodgmtpolyorderFrm  [name=prod_gmt_poly_id]').val(prod_gmt_poly_id);
		$('#prodgmtpolyorderFrm [id="supplier_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtPolyOrderModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdGmtPolyOrderModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodgmtpolyorderTbl').datagrid('reload');
		msApp.resetForm('prodgmtpolyorderFrm');
		MsProdGmtPolyOrder.resetForm();
		$('#prodgmtpolyorderFrm [name=prod_gmt_poly_id]').val($('#prodgmtpolyFrm [name=id]').val());
		$('#prodgmtpolyorderFrm [id="supplier_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let prod=this.MsProdGmtPolyOrderModel.get(index,row);	
		prod.then(function (response) {	
		$('#prodgmtpolyorderFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid(prod_gmt_poly_id){
		let self=this;
		let data = {};
		data.prod_gmt_poly_id=prod_gmt_poly_id;
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
		return '<a href="javascript:void(0)"  onClick="MsProdGmtPolyOrder.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	
	openOrderPolyWindow(){
		$('#openorderpolywindow').window('open');
	}

	getParams(){
		let params={};
		params.style_ref=$('#orderpolysearchFrm [name=style_ref]').val();
		params.job_no=$('#orderpolysearchFrm [name=job_no]').val();
		params.sale_order_no=$('#orderpolysearchFrm [name=sale_order_no]').val();
		return params;
	}
	searchPolyOrderGrid(){
		let params=this.getParams();
		let d= axios.get(this.route+'/getpolyorder',{params})
		.then(function(response){
			$('#orderpolysearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}
	showPolyOrderGrid(data){
		let self=this;
		$('#orderpolysearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#prodgmtpolyorderFrm [name=sales_order_country_id]').val(row.sales_order_country_id);
				$('#prodgmtpolyorderFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#prodgmtpolyorderFrm [name=order_qty]').val(row.order_qty);
				$('#prodgmtpolyorderFrm [name=country_id]').val(row.country_id);
				$('#prodgmtpolyorderFrm [name=job_no]').val(row.job_no);
				$('#prodgmtpolyorderFrm [name=company_id]').val(row.company_id);
				$('#prodgmtpolyorderFrm [name=buyer_name]').val(row.buyer_name);
				$('#prodgmtpolyorderFrm [name=produced_company_id]').val(row.produced_company_id);
				$('#prodgmtpolyorderFrm [name=produced_company_name]').val(row.produced_company_name);
				$('#prodgmtpolyorderFrm [name=ship_date]').val(row.ship_date);
				$('#openorderpolywindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}

	openLineNoWindow(){
		$('#openlinenowindow').window('open');
	}

	getLineParams(){
		let params = {};
		params.line_merged_id=$('#linenosearchFrm [name=line_merged_id]').val();
		params.produced_company_id=$('#prodgmtpolyorderFrm [name=produced_company_id]').val();
		params.prod_gmt_poly_id=$('#prodgmtpolyFrm [name=id]').val();
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
				$('#prodgmtpolyorderFrm [name=wstudy_line_setup_id]').val(row.id);
				$('#prodgmtpolyorderFrm [name=line_name]').val(row.line_code);
				$('#prodgmtpolyorderFrm [name=location_id]').val(row.location_name);
				$('#openlinenowindow').window('close');
				$('#linenosearchTbl').datagrid('loadData', []);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}


}
window.MsProdGmtPolyOrder=new MsProdGmtPolyOrderController(new MsProdGmtPolyOrderModel());
MsProdGmtPolyOrder.showPolyOrderGrid([]);
MsProdGmtPolyOrder.showLineGrid([]);