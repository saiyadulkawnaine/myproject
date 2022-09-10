let MsTnaActualModel = require('./MsTnaActualModel');
require('./../datagrid-filter.js');
class MsTnaActualController {
	constructor(MsTnaActualModel)
	{
		this.MsTnaActualModel = MsTnaActualModel;
		this.formId='tnaactualFrm';
		this.dataTable='#tnaactualTbl';
		this.route=msApp.baseUrl()+"/tnaactual"
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
		//let id=$('#tnaactualFrm [name=id]').val();
		let formObj=msApp.get(this.formId);
		//formObj.id=id;
        if(formObj.id){
            this.MsTnaActualModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
        }else{
            this.MsTnaActualModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
        }
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsTnaActualModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsTnaActualModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#tnaactualTbl').datagrid('reload');
		msApp.resetForm('tnaactualFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsTnaActualModel.get(index,row);
	}

	showGrid()
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsTnaActual.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openOrderWindow(){
		$('#openorderwindow').window('open');
	}

	getParams(){
		let params={};
		params.buyer_id=$('#ordersearchFrm [name=buyer_id]').val();
		params.date_from=$('#ordersearchFrm [name=date_from]').val();
		params.date_to=$('#ordersearchFrm [name=date_to]').val();
		params.style_ref=$('#ordersearchFrm [name=style_ref]').val();
		params.job_no=$('#ordersearchFrm [name=job_no]').val();
		params.sale_order_no=$('#ordersearchFrm [name=sale_order_no]').val();
		return params;
	}
	searchOrder(){
		let params=this.getParams();
		let d= axios.get(this.route+'/getsalesorder',{params})
		.then(function(response){
			$('#ordersearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}
	showOrderGrid(data){
		let self=this;
		$('#ordersearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			rownumbers:true,
			onClickRow: function(index,row){
				$('#tnaactualFrm [name=id]').val(row.id);
				$('#tnaactualFrm [name=tna_task_id]').val(row.tna_task_id);
				$('#tnaactualFrm [name=sales_order_id]').val(row.sales_order_id);
				$('#tnaactualFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#tnaactualFrm [name=task_name]').val(row.task_name);
				$('#tnaactualFrm [name=style_ref]').val(row.style_ref);
				$('#tnaactualFrm [name=beneficiary]').val(row.company_name);
				$('#tnaactualFrm [name=buyer_name]').val(row.buyer_name);
				$('#tnaactualFrm [name=produced_company_name]').val(row.produced_company_name);
				$('#tnaactualFrm [name=ship_date]').val(row.ship_date);
				$('#tnaactualFrm [name=tna_start_date]').val(row.tna_start_date);
				$('#tnaactualFrm [name=tna_end_date]').val(row.tna_end_date);
				$('#ordersearchTbl').datagrid('loadData',[]);
				$('#openorderwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}
}
window.MsTnaActual = new MsTnaActualController(new MsTnaActualModel());
MsTnaActual.showGrid();
MsTnaActual.showOrderGrid([]);