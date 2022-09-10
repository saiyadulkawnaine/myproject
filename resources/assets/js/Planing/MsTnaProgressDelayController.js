let MsTnaProgressDelayModel = require('./MsTnaProgressDelayModel');
require('./../datagrid-filter.js');
class MsTnaProgressDelayController {
	constructor(MsTnaProgressDelayModel)
	{
		this.MsTnaProgressDelayModel = MsTnaProgressDelayModel;
		this.formId='tnaprogressdelayFrm';
		this.dataTable='#tnaprogressdelayTbl';
		this.route=msApp.baseUrl()+"/tnaprogressdelay"
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
            this.MsTnaProgressDelayModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
        }else{
            this.MsTnaProgressDelayModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
        }
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsTnaProgressDelayModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsTnaProgressDelayModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#tnaprogressdelayTbl').datagrid('reload');
		msApp.resetForm('tnaprogressdelayFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsTnaProgressDelayModel.get(index,row);
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
		return '<a href="javascript:void(0)" onClick="MsTnaProgressDelay.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openOrderWindow(){
		$('#opendelayorderwindow').window('open');
	}

	getParams(){
		let params={};
		params.buyer_id=$('#tnaordersearchFrm [name=buyer_id]').val();
		params.date_from=$('#tnaordersearchFrm [name=date_from]').val();
		params.date_to=$('#tnaordersearchFrm [name=date_to]').val();
		params.style_ref=$('#tnaordersearchFrm [name=style_ref]').val();
		params.job_no=$('#tnaordersearchFrm [name=job_no]').val();
		params.sale_order_no=$('#tnaordersearchFrm [name=sale_order_no]').val();
		return params;
	}

	searchOrder(){
		let params=this.getParams();
		let d= axios.get(this.route+'/gettnasalesorder',{params})
		.then(function(response){
			$('#tnaordersearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}

	showDelayOrderGrid(data){
		let self=this;
		$('#tnaordersearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			rownumbers:true,
			onClickRow: function(index,row){
				$('#tnaprogressdelayFrm [name=tna_ord_id]').val(row.id);
				$('#tnaprogressdelayFrm [name=sale_order_no]').val(row.sale_order_no);
				$('#tnaprogressdelayFrm [name=task_name]').val(row.task_name);
				$('#tnaprogressdelayFrm [name=style_ref]').val(row.style_ref);
				$('#tnaprogressdelayFrm [name=company_code]').val(row.company_name);
				$('#tnaprogressdelayFrm [name=buyer_name]').val(row.buyer_name);
				$('#tnaprogressdelayFrm [name=produced_company_name]').val(row.produced_company_name);
				$('#tnaprogressdelayFrm [name=ship_date]').val(row.ship_date);
				$('#tnaprogressdelayFrm [name=tna_start_date]').val(row.tna_start_date);
				$('#tnaprogressdelayFrm [name=tna_end_date]').val(row.tna_end_date);
				//$('#tnaprogressdelayFrm [name=acl_start_date]').val(row.acl_start_date);
				//$('#tnaprogressdelayFrm [name=acl_end_date]').val(row.acl_end_date);
				$('#tnaordersearchTbl').datagrid('loadData',[]);
				$('#opendelayorderwindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);	
	}

}
window.MsTnaProgressDelay = new MsTnaProgressDelayController(new MsTnaProgressDelayModel());
MsTnaProgressDelay.showGrid();
MsTnaProgressDelay.showDelayOrderGrid([]);
$('#tnadelaytabs').tabs({
	onSelect:function(title,index){
		let tna_progress_delay_id = $('#tnaprogressdelayFrm [name=id]').val();
		 
		var data={};
		data.tna_progress_delay_id=tna_progress_delay_id;

		if(index==1){
			if(tna_progress_delay_id===''){
				$('#tnadelaytabs').tabs('select',0);
				msApp.showError('Select a Delay Progress First',0);
				return;
			}
			//alert(tna_progress_delay_id)
			$('#tnaprogressdelaydtlFrm [name=tna_progress_delay_id]').val(tna_progress_delay_id);
			MsTnaProgressDelayDtl.showGrid(tna_progress_delay_id);
		}
					
	}
});