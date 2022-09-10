let MsSalesOrderShipDateChangeModel = require('./MsSalesOrderShipDateChangeModel');
require('./datagrid-filter.js');
class MsSalesOrderShipDateChangeController {
	constructor(MsSalesOrderShipDateChangeModel)
	{
		this.MsSalesOrderShipDateChangeModel = MsSalesOrderShipDateChangeModel;
		this.formId='salesordershipdatechangeFrm';
		this.dataTable='#salesordershipdatechangeTbl';
		this.route=msApp.baseUrl()+"/salesordershipdatechange"
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
            this.MsSalesOrderShipDateChangeModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
        }else{
            this.MsSalesOrderShipDateChangeModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
        }
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);	
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsSalesOrderShipDateChangeModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSalesOrderShipDateChangeModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsSalesOrderShipDateChange.get();
		msApp.resetForm('salesordershipdatechangeFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSalesOrderShipDateChangeModel.get(index,row);
	}
	get(){
		let grid= axios.get(this.route)
		.then(function(response){
			$('#salesordershipdatechangeTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
		return grid;
	}

	searchAllShipDateChange()
	{
		let params={};
		params.date_from=$('#date_from').val();
		params.date_to=$('#date_to').val();
		let data= axios.get(this.route+"/getallchangedshipdate",{params});
		data.then(function (response) {
			$('#salesordershipdatechangeTbl').datagrid('loadData', response.data);
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
		return '<a href="javascript:void(0)" onClick="MsSalesOrderShipDateChange.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openSalesOrderSearchWindow(){
		$('#opensalesordersearchwindow').window('open');
	}

	getparams(){
		let params={};
		params.style_ref=$('#salesordersearchFrm [name=style_ref]').val();
		params.job_no=$('#salesordersearchFrm [name=job_no]').val();
		params.sale_order_no=$('#salesordersearchFrm [name=sale_order_no]').val();
		return params;
	}

	searchSalesOrder(){
		let params=this.getparams();
		let grid= axios.get(this.route+"/getsalesorder",{params})
		.then(function(response){
			$('#salesordersearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
		return grid;
	}

	showSalesOrderGrid(data){
		let self=this;
		$('#salesordersearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				$('#salesordershipdatechangeFrm  [name=sale_order_id]').val(row.id);
				$('#salesordershipdatechangeFrm  [name=sale_order_no]').val(row.sale_order_no);
				$('#salesordershipdatechangeFrm  [name=job_no]').val(row.job_no);
				$('#salesordershipdatechangeFrm  [name=style_ref]').val(row.style_ref);
				$('#salesordershipdatechangeFrm  [name=old_ship_date]').val(row.ship_date);
				$('#salesordershipdatechangeFrm  [name=receive_date]').val(row.receive_date);
				$('#salesordershipdatechangeFrm  [name=place_date]').val(row.place_date);
				$('#salesordershipdatechangeFrm  [name=receive_date]').val(row.receive_date);
				$('#salesordershipdatechangeFrm  [name=produced_company_id]').val(row.produced_company_id);
				$('#salesordershipdatechangeFrm  [name=file_no]').val(row.file_no);
				$('#salesordershipdatechangeFrm  [name=internal_ref]').val(row.internal_ref);
				$('#salesordershipdatechangeFrm  [name=tna_to]').val(row.tna_to);
				$('#salesordershipdatechangeFrm  [name=tna_from]').val(row.tna_from);
				$('#salesordershipdatechangeFrm  [name=order_status]').val(row.order_status);
				
				$('#salesordersearchTbl').datagrid('loadData',[]);
				$('#opensalesordersearchwindow').window('close')
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

}
window.MsSalesOrderShipDateChange = new MsSalesOrderShipDateChangeController(new MsSalesOrderShipDateChangeModel());
MsSalesOrderShipDateChange.showGrid([]);
MsSalesOrderShipDateChange.showSalesOrderGrid([]);
MsSalesOrderShipDateChange.get();