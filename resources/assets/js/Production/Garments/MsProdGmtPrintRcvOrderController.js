let MsProdGmtPrintRcvOrderModel = require('./MsProdGmtPrintRcvOrderModel');

class MsProdGmtPrintRcvOrderController {
	constructor(MsProdGmtPrintRcvOrderModel)
	{
		this.MsProdGmtPrintRcvOrderModel = MsProdGmtPrintRcvOrderModel;
		this.formId='prodgmtprintrcvorderFrm';
		this.dataTable='#prodgmtprintrcvorderTbl';
		this.route=msApp.baseUrl()+"/prodgmtprintrcvorder"
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
			this.MsProdGmtPrintRcvOrderModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdGmtPrintRcvOrderModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#printreceivegmtcosi').html('');
		let prod_gmt_print_rcv_id = $('#prodgmtprintrcvFrm  [name=id]').val();
		$('#prodgmtprintrcvorderFrm  [name=prod_gmt_print_rcv_id]').val(prod_gmt_print_rcv_id);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtPrintRcvOrderModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdGmtPrintRcvOrderModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodgmtprintrcvorderTbl').datagrid('reload');
		msApp.resetForm('prodgmtprintrcvorderFrm');
		MsProdGmtPrintRcvQty.resetForm();
		$('#prodgmtprintrcvorderFrm [name=prod_gmt_print_rcv_id]').val($('#prodgmtprintrcvFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let delv=this.MsProdGmtPrintRcvOrderModel.get(index,row);
		delv.then(function(response){
			MsProdGmtPrintRcvOrder.setClass(response.data.fromData.ctrlhead_type_id);
		}).catch(function(error){
			console.log(error);
		});

	}

	showGrid(prod_gmt_print_rcv_id){
		let self=this;
		let data = {};
		data.prod_gmt_print_rcv_id=prod_gmt_print_rcv_id;
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
		return '<a href="javascript:void(0)"  onClick="MsProdGmtPrintRcvOrder.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	
	openOrderPrintRcvWindow(){
		$('#openprintorderwindow').window('open');
	}

	getParams(){
		let params={};
		params.style_ref=$('#printordersearchFrm [name=style_ref]').val();
		params.job_no=$('#printordersearchFrm [name=job_no]').val();
		params.sale_order_no=$('#printordersearchFrm [name=sale_order_no]').val();
		return params;
	}
	searchPrintReceiveOrderGrid(){
		let params=this.getParams();
		let d= axios.get(this.route+'/getprintorder',{params})
		.then(function(response){
			$('#printordersearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}
	showPrintOrderGrid(data){
		let self=this;
		$('#printordersearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#prodgmtprintrcvorderFrm [name=sales_order_country_id]').val(row.sales_order_country_id);
				$('#prodgmtprintrcvorderFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#prodgmtprintrcvorderFrm [name=order_qty]').val(row.order_qty);
				$('#prodgmtprintrcvorderFrm [name=country_id]').val(row.country_id);
				$('#prodgmtprintrcvorderFrm [name=job_no]').val(row.job_no);
				$('#prodgmtprintrcvorderFrm [name=company_id]').val(row.company_id);
				$('#prodgmtprintrcvorderFrm [name=buyer_name]').val(row.buyer_name);
				//$('#prodgmtprintrcvorderFrm [name=produced_company_id]').val(row.produced_company_id);
				//$('#prodgmtprintrcvorderFrm [name=produced_company_name]').val(row.produced_company_name);
				$('#prodgmtprintrcvorderFrm [name=ship_date]').val(row.ship_date);
				$('#prodgmtprintrcvorderFrm [name=fabric_look_id]').val(row.fabric_looks);
				$('#openprintorderwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}

	setClass(){

	}


}
window.MsProdGmtPrintRcvOrder=new MsProdGmtPrintRcvOrderController(new MsProdGmtPrintRcvOrderModel());
MsProdGmtPrintRcvOrder.showPrintOrderGrid([]);