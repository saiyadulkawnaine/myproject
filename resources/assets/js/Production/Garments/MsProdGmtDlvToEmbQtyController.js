let MsProdGmtDlvToEmbQtyModel = require('./MsProdGmtDlvToEmbQtyModel');

class MsProdGmtDlvToEmbQtyController {
	constructor(MsProdGmtDlvToEmbQtyModel)
	{
		this.MsProdGmtDlvToEmbQtyModel = MsProdGmtDlvToEmbQtyModel;
		this.formId='prodgmtdlvtoembqtyFrm';	             
		this.dataTable='#prodgmtdlvtoembqtyTbl';
		this.route=msApp.baseUrl()+"/prodgmtdlvtoembqty"
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
		let prod_gmt_dlv_to_emb_order_id=$('#prodgmtdlvtoemborderFrm [name=id]').val()
		let formObj=msApp.get(this.formId);
		formObj.prod_gmt_dlv_to_emb_order_id=prod_gmt_dlv_to_emb_order_id;
		if(formObj.id){
			this.MsProdGmtDlvToEmbQtyModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdGmtDlvToEmbQtyModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		MsProdGmtDlvToEmbOrder.resetForm();
		$('#dlvtoembgmtcosi').html('');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtDlvToEmbQtyModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdGmtDlvToEmbQtyModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#prodgmtdlvtoembqtyTbl').datagrid('reload');
		//msApp.resetForm('prodgmtdlvtoembqtyFrm');
		MsProdGmtDlvToEmbQty.resetForm()
		$('#dlvtoembgmtcosi').html('');
		//$('#prodgmtdlvtoembqtyFrm [name=prod_gmt_dlvtoemb_order_id]').val($('#prodgmtdlvtoemborderFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsProdGmtDlvToEmbQtyModel.get(index,row);

	}

	showGrid(prod_gmt_dlv_to_emb_order_id){
		let self=this;
		let data = {};
		data.prod_gmt_dlv_to_emb_order_id=prod_gmt_dlv_to_emb_order_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdGmtDlvToEmbQty.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsProdGmtDlvToEmbQty=new MsProdGmtDlvToEmbQtyController(new MsProdGmtDlvToEmbQtyModel());