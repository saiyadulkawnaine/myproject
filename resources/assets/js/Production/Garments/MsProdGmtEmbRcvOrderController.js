let MsProdGmtEmbRcvOrderModel = require('./MsProdGmtEmbRcvOrderModel');

class MsProdGmtEmbRcvOrderController {
	constructor(MsProdGmtEmbRcvOrderModel)
	{
		this.MsProdGmtEmbRcvOrderModel = MsProdGmtEmbRcvOrderModel;
		this.formId='prodgmtembrcvorderFrm';
		this.dataTable='#prodgmtembrcvorderTbl';
		this.route=msApp.baseUrl()+"/prodgmtembrcvorder"
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
			this.MsProdGmtEmbRcvOrderModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdGmtEmbRcvOrderModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#embrcvgmtcosi').html('');
		let prod_gmt_emb_rcv_id = $('#prodgmtembrcvFrm  [name=id]').val();
		$('#prodgmtembrcvorderFrm  [name=prod_gmt_emb_rcv_id]').val(prod_gmt_emb_rcv_id);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtEmbRcvOrderModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdGmtEmbRcvOrderModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodgmtembrcvorderTbl').datagrid('reload');
		msApp.resetForm('prodgmtembrcvorderFrm');
		MsProdGmtEmbRcvQty.resetForm();
		$('#prodgmtembrcvorderFrm [name=prod_gmt_emb_rcv_id]').val($('#prodgmtembrcvFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let delv=this.MsProdGmtEmbRcvOrderModel.get(index,row);
		delv.then(function(response){
			MsProdGmtEmbRcvOrder.setClass(response.data.fromData.ctrlhead_type_id);
		}).catch(function(error){
			console.log(error);
		});

	}

	showGrid(prod_gmt_emb_rcv_id){
		let self=this;
		let data = {};
		data.prod_gmt_emb_rcv_id=prod_gmt_emb_rcv_id;
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
		return '<a href="javascript:void(0)"  onClick="MsProdGmtEmbRcvOrder.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	
	openOrderEmbRcvWindow(){
		$('#openorderembrcvwindow').window('open');
	}

	getParams(){
		let params={};
		params.style_ref=$('#orderembrcvsearchFrm [name=style_ref]').val();
		params.job_no=$('#orderembrcvsearchFrm [name=job_no]').val();
		params.sale_order_no=$('#orderembrcvsearchFrm [name=sale_order_no]').val();
		return params;
	}
	searchEmbRcvOrderGrid(){
		let params=this.getParams();
		let d= axios.get(this.route+'/getembrcvorder',{params})
		.then(function(response){
			$('#orderembrcvsearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}
	showEmbRcvOrderGrid(data){
		let self=this;
		$('#orderembrcvsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#prodgmtembrcvorderFrm [name=sales_order_country_id]').val(row.sales_order_country_id);
				$('#prodgmtembrcvorderFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#prodgmtembrcvorderFrm [name=order_qty]').val(row.order_qty);
				$('#prodgmtembrcvorderFrm [name=country_id]').val(row.country_id);
				$('#prodgmtembrcvorderFrm [name=job_no]').val(row.job_no);
				$('#prodgmtembrcvorderFrm [name=company_id]').val(row.company_id);
				$('#prodgmtembrcvorderFrm [name=buyer_name]').val(row.buyer_name);
				$('#prodgmtembrcvorderFrm [name=produced_company_id]').val(row.produced_company_id);
				$('#prodgmtembrcvorderFrm [name=produced_company_name]').val(row.produced_company_name);
				$('#prodgmtembrcvorderFrm [name=ship_date]').val(row.ship_date);
				$('#prodgmtembrcvorderFrm [name=fabric_look_id]').val(row.fabric_looks);
				$('#openorderembrcvwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}

	setClass(){

	}


}
window.MsProdGmtEmbRcvOrder=new MsProdGmtEmbRcvOrderController(new MsProdGmtEmbRcvOrderModel());
MsProdGmtEmbRcvOrder.showEmbRcvOrderGrid([]);