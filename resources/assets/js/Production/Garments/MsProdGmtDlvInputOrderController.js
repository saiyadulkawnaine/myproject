let MsProdGmtDlvInputOrderModel = require('./MsProdGmtDlvInputOrderModel');

class MsProdGmtDlvInputOrderController {
	constructor(MsProdGmtDlvInputOrderModel)
	{
		this.MsProdGmtDlvInputOrderModel = MsProdGmtDlvInputOrderModel;
		this.formId='prodgmtdlvinputorderFrm';
		this.dataTable='#prodgmtdlvinputorderTbl';
		this.route=msApp.baseUrl()+"/prodgmtdlvinputorder"
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
			this.MsProdGmtDlvInputOrderModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdGmtDlvInputOrderModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#dlvinputgmtcosi').html('');
		let prod_gmt_dlv_input_id = $('#prodgmtdlvinputFrm  [name=id]').val();
		$('#prodgmtdlvinputorderFrm  [name=prod_gmt_dlv_input_id]').val(prod_gmt_dlv_input_id);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtDlvInputOrderModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdGmtDlvInputOrderModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodgmtdlvinputorderTbl').datagrid('reload');
		msApp.resetForm('prodgmtdlvinputorderFrm');
		MsProdGmtDlvInputQty.resetForm();
		$('#prodgmtdlvinputorderFrm [name=prod_gmt_dlv_input_id]').val($('#prodgmtdlvinputFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let delv=this.MsProdGmtDlvInputOrderModel.get(index,row);
		delv.then(function(response){
			MsProdGmtDlvInputOrder.setClass(response.data.fromData.ctrlhead_type_id);
		}).catch(function(error){
			console.log(error);
		});

	}

	showGrid(prod_gmt_dlv_input_id){
		let self=this;
		let data = {};
		data.prod_gmt_dlv_input_id=prod_gmt_dlv_input_id;
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
		return '<a href="javascript:void(0)"  onClick="MsProdGmtDlvInputOrder.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	
	openOrderDlvInputWindow(){
		$('#openorderdlvinputwindow').window('open');
	}

	getParams(){
		let params={};
		params.style_ref=$('#orderdlvinputsearchFrm [name=style_ref]').val();
		params.job_no=$('#orderdlvinputsearchFrm [name=job_no]').val();
		params.sale_order_no=$('#orderdlvinputsearchFrm [name=sale_order_no]').val();
		params.prodgmtdlvinputid=$('#prodgmtdlvinputFrm [name=id]').val();
		return params;
	}
	searchDlvInputOrderGrid(){
		let params=this.getParams();
		let d= axios.get(this.route+'/getdlvinputorder',{params})
		.then(function(response){
			$('#orderdlvinputsearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}
	showDlvInputOrderGrid(data){
		let self=this;
		$('#orderdlvinputsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#prodgmtdlvinputorderFrm [name=sales_order_country_id]').val(row.sales_order_country_id);
				$('#prodgmtdlvinputorderFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#prodgmtdlvinputorderFrm [name=order_qty]').val(row.order_qty);
				$('#prodgmtdlvinputorderFrm [name=country_id]').val(row.country_id);
				$('#prodgmtdlvinputorderFrm [name=job_no]').val(row.job_no);
				$('#prodgmtdlvinputorderFrm [name=company_id]').val(row.company_id);
				$('#prodgmtdlvinputorderFrm [name=buyer_name]').val(row.buyer_name);
				$('#prodgmtdlvinputorderFrm [name=produced_company_id]').val(row.produced_company_id);
				$('#prodgmtdlvinputorderFrm [name=produced_company_name]').val(row.produced_company_name);
				$('#prodgmtdlvinputorderFrm [name=ship_date]').val(row.ship_date);
				$('#openorderdlvinputwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}

	setClass(){

	}


}
window.MsProdGmtDlvInputOrder=new MsProdGmtDlvInputOrderController(new MsProdGmtDlvInputOrderModel());
MsProdGmtDlvInputOrder.showDlvInputOrderGrid([]);