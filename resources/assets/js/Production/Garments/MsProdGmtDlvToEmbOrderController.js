let MsProdGmtDlvToEmbOrderModel = require('./MsProdGmtDlvToEmbOrderModel');

class MsProdGmtDlvToEmbOrderController {
	constructor(MsProdGmtDlvToEmbOrderModel)
	{
		this.MsProdGmtDlvToEmbOrderModel = MsProdGmtDlvToEmbOrderModel;
		this.formId='prodgmtdlvtoemborderFrm';
		this.dataTable='#prodgmtdlvtoemborderTbl';
		this.route=msApp.baseUrl()+"/prodgmtdlvtoemborder"
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
			this.MsProdGmtDlvToEmbOrderModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdGmtDlvToEmbOrderModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#dlvtoembgmtcosi').html('');
		let prod_gmt_dlv_to_emb_id = $('#prodgmtdlvtoembFrm  [name=id]').val();
		$('#prodgmtdlvtoemborderFrm  [name=prod_gmt_dlv_to_emb_id]').val(prod_gmt_dlv_to_emb_id);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtDlvToEmbOrderModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdGmtDlvToEmbOrderModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodgmtdlvtoemborderTbl').datagrid('reload');
		msApp.resetForm('prodgmtdlvtoemborderFrm');
		MsProdGmtDlvToEmbQty.resetForm();
		$('#prodgmtdlvtoemborderFrm [name=prod_gmt_dlv_to_emb_id]').val($('#prodgmtdlvtoembFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let delv=this.MsProdGmtDlvToEmbOrderModel.get(index,row);
		delv.then(function(response){
			MsProdGmtDlvToEmbOrder.setClass(response.data.fromData.ctrlhead_type_id);
		}).catch(function(error){
			console.log(error);
		});

	}

	showGrid(prod_gmt_dlv_to_emb_id){
		let self=this;
		let data = {};
		data.prod_gmt_dlv_to_emb_id=prod_gmt_dlv_to_emb_id;
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
		return '<a href="javascript:void(0)"  onClick="MsProdGmtDlvToEmbOrder.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	
	openOrderDlvToEmbWindow(){
		$('#openorderdlvtoembwindow').window('open');
	}

	getParams(){
		let params={};
		params.style_ref=$('#orderdlvtoembsearchFrm [name=style_ref]').val();
		params.job_no=$('#orderdlvtoembsearchFrm [name=job_no]').val();
		params.sale_order_no=$('#orderdlvtoembsearchFrm [name=sale_order_no]').val();
		params.prodgmtdlvtoembid=$('#prodgmtdlvtoembFrm [name=id]').val();
		return params;
	}
	searchDlvToEmbOrderGrid(){
		let params=this.getParams();
		let d= axios.get(this.route+'/getdlvtoemborder',{params})
		.then(function(response){
			$('#orderdlvtoembsearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}
	showDlvToEmbOrderGrid(data){
		let self=this;
		$('#orderdlvtoembsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#prodgmtdlvtoemborderFrm [name=sales_order_country_id]').val(row.sales_order_country_id);
				$('#prodgmtdlvtoemborderFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#prodgmtdlvtoemborderFrm [name=order_qty]').val(row.order_qty);
				$('#prodgmtdlvtoemborderFrm [name=country_id]').val(row.country_id);
				$('#prodgmtdlvtoemborderFrm [name=style_ref]').val(row.style_ref);
				$('#prodgmtdlvtoemborderFrm [name=job_no]').val(row.job_no);
				$('#prodgmtdlvtoemborderFrm [name=company_id]').val(row.company_id);
				$('#prodgmtdlvtoemborderFrm [name=buyer_name]').val(row.buyer_name);
				$('#prodgmtdlvtoemborderFrm [name=produced_company_id]').val(row.produced_company_id);
				$('#prodgmtdlvtoemborderFrm [name=produced_company_name]').val(row.produced_company_name);
				$('#prodgmtdlvtoemborderFrm [name=ship_date]').val(row.ship_date);
				$('#openorderdlvtoembwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}

	setClass(){

	}


}
window.MsProdGmtDlvToEmbOrder=new MsProdGmtDlvToEmbOrderController(new MsProdGmtDlvToEmbOrderModel());
MsProdGmtDlvToEmbOrder.showDlvToEmbOrderGrid([]);