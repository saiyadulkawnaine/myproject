let MsProdGmtSewingLineOrderModel = require('./MsProdGmtSewingLineOrderModel');

class MsProdGmtSewingLineOrderController {
	constructor(MsProdGmtSewingLineOrderModel)
	{
		this.MsProdGmtSewingLineOrderModel = MsProdGmtSewingLineOrderModel;
		this.formId='prodgmtsewinglineorderFrm';
		this.dataTable='#prodgmtsewinglineorderTbl';
		this.route=msApp.baseUrl()+"/prodgmtsewinglineorder"
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
			this.MsProdGmtSewingLineOrderModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdGmtSewingLineOrderModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#sewinglinegmtcosi').html('');
		let prod_gmt_sewing_line_id = $('#prodgmtsewinglineFrm  [name=id]').val();
		$('#prodgmtsewinglineorderFrm  [name=prod_gmt_sewing_line_id]').val(prod_gmt_sewing_line_id);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtSewingLineOrderModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdGmtSewingLineOrderModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodgmtsewinglineorderTbl').datagrid('reload');
		msApp.resetForm('prodgmtsewinglineorderFrm');
		MsProdGmtSewingLineQty.resetForm();
		$('#prodgmtsewinglineorderFrm [name=prod_gmt_sewing_line_id]').val($('#prodgmtsewinglineFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsProdGmtSewingLineOrderModel.get(index,row);
		/* let delv=this.MsProdGmtSewingLineOrderModel.get(index,row);
		delv.then(function(response){
			MsProdGmtSewingLineOrder.setClass(response.data.fromData.ctrlhead_type_id);
		}).catch(function(error){
			console.log(error);
		}); */

	}

	showGrid(prod_gmt_sewing_line_id){
		let self=this;
		let data = {};
		data.prod_gmt_sewing_line_id=prod_gmt_sewing_line_id;
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
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdGmtSewingLineOrder.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	
	openOrderSewingLineWindow(){
		$('#openordersewinglinewindow').window('open');
	}

	getParams(){
		let params={};
		params.style_ref=$('#ordersewinglinesearchFrm [name=style_ref]').val();
		params.job_no=$('#ordersewinglinesearchFrm [name=job_no]').val();
		params.sale_order_no=$('#ordersewinglinesearchFrm [name=sale_order_no]').val();
		params.prodgmtsewinglineid=$('#prodgmtsewinglineFrm [name=id]').val();
		return params;
	}
	searchSewingLineOrderGrid(){
		let params=this.getParams();
		let d= axios.get(this.route+'/getsewinglineorder',{params})
		.then(function(response){
			$('#ordersewinglinesearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}
	showSewingLineOrderGrid(data){
		let self=this;
		$('#ordersewinglinesearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#prodgmtsewinglineorderFrm [name=sales_order_country_id]').val(row.sales_order_country_id);
				$('#prodgmtsewinglineorderFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#prodgmtsewinglineorderFrm [name=country_id]').val(row.country_id);
				$('#prodgmtsewinglineorderFrm [name=company_id]').val(row.company_id);
				$('#prodgmtsewinglineorderFrm [name=company_name]').val(row.company_name);
				$('#prodgmtsewinglineorderFrm [name=buyer_name]').val(row.buyer_name);
				$('#prodgmtsewinglineorderFrm [name=job_no]').val(row.job_no);
				$('#prodgmtsewinglineorderFrm [name=style_ref]').val(row.style_ref);
				$('#prodgmtsewinglineorderFrm [name=ship_date]').val(row.ship_date);
				$('#openordersewinglinewindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}
	openSewingLineNoWindow(){
		$('#opensewinglinewindow').window('open');
	}

	getSewingLineParams(){
		let params = {};
		params.line_merged_id=$('#sewinglinenosearchFrm [name=line_merged_id]').val();
		params.company_id=$('#prodgmtsewinglineFrm [name=company_id]').val();
		params.prod_gmt_sewing_line_id=$('#prodgmtsewinglineFrm [name=id]').val();
		return params;
	}
	searchSewingLineGrid(){
		let params = this.getSewingLineParams();
		let d = axios.get(this.route+'/getline',{params})
		.then(function(response){
			$('#sewinglinenosearchTbl').datagrid('loadData', response.data);
		}).catch(function(error){
			console.log(error);
		});
	}

	showSewingLineGrid(data){
		let self = this;
		$('#sewinglinenosearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#prodgmtsewinglineorderFrm [name=wstudy_line_setup_id]').val(row.id);
				$('#prodgmtsewinglineorderFrm [name=line_name]').val(row.line_code);
				$('#opensewinglinewindow').window('close');
				$('#sewinglinenosearchTbl').datagrid('loadData', []);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}

}
window.MsProdGmtSewingLineOrder=new MsProdGmtSewingLineOrderController(new MsProdGmtSewingLineOrderModel());
MsProdGmtSewingLineOrder.showSewingLineOrderGrid([]);
MsProdGmtSewingLineOrder.showSewingLineGrid([]);