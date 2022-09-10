let MsSalesOrderCloseModel = require('./MsSalesOrderCloseModel');
require('./datagrid-filter.js');
class MsSalesOrderCloseController {
	constructor(MsSalesOrderCloseModel)
	{
		this.MsSalesOrderCloseModel = MsSalesOrderCloseModel;
		this.formId='salesordercloseFrm';
		this.dataTable='#salesordercloseTbl';
		this.route=msApp.baseUrl()+"/salesorderclose"
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
						this.MsSalesOrderCloseModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
						this.MsSalesOrderCloseModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);	
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSalesOrderCloseModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSalesOrderCloseModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsSalesOrderClose.get();
		msApp.resetForm('salesordercloseFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSalesOrderCloseModel.get(index,row);
	}
	
	get(){
		let grid= axios.get(this.route)
		.then(function(response){
			$('#salesordercloseTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
		return grid;
	}

	searchAllClose()
	{
		let params={};
		params.date_from=$('#date_from').val();
		params.date_to=$('#date_to').val();
		let data= axios.get(this.route+"/getallclose",{params});
		data.then(function (response) {
			$('#salesordercloseTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(data)
	{
		let self=this;
		$(this.dataTable).datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)" onClick="MsSalesOrderClose.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openSalesOrderCloseSearchWindow(){
		$('#opensalesorderclosesearchwindow').window('open');
	}

	getparams(){
		let params={};
		params.style_ref=$('#salesorderclosesearchFrm [name=style_ref]').val();
		params.job_no=$('#salesorderclosesearchFrm [name=job_no]').val();
		params.sale_order_no=$('#salesorderclosesearchFrm [name=sale_order_no]').val();
		return params;
	}

	searchSalesOrder(){
		let params=this.getparams();
		let grid= axios.get(this.route+"/getsalesorder",{params})
		.then(function(response){
			$('#salesorderclosesearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
		return grid;
	}

	showSalesOrderGrid(data){
		let self=this;
		$('#salesorderclosesearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#salesordercloseFrm  [name=sale_order_id]').val(row.id);
				$('#salesordercloseFrm  [name=sale_order_no]').val(row.sale_order_no);
				$('#salesordercloseFrm  [name=job_no]').val(row.job_no);
				$('#salesordercloseFrm  [name=style_ref]').val(row.style_ref);
				$('#salesordercloseFrm  [name=old_ship_date]').val(row.ship_date);
				$('#salesordercloseFrm  [name=receive_date]').val(row.receive_date);
				$('#salesordercloseFrm  [name=place_date]').val(row.place_date);
				$('#salesordercloseFrm  [name=receive_date]').val(row.receive_date);
				$('#salesordercloseFrm  [name=produced_company_id]').val(row.produced_company_id);
				$('#salesordercloseFrm  [name=file_no]').val(row.file_no);
				$('#salesordercloseFrm  [name=internal_ref]').val(row.internal_ref);
				$('#salesordercloseFrm  [name=tna_to]').val(row.tna_to);
				$('#salesordercloseFrm  [name=tna_from]').val(row.tna_from);
				$('#salesordercloseFrm  [name=order_status]').val(row.order_status);
				
				$('#salesorderclosesearchTbl').datagrid('loadData',[]);
				$('#opensalesorderclosesearchwindow').window('close')
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

}
window.MsSalesOrderClose = new MsSalesOrderCloseController(new MsSalesOrderCloseModel());
MsSalesOrderClose.showGrid([]);
MsSalesOrderClose.showSalesOrderGrid([]);
MsSalesOrderClose.get();