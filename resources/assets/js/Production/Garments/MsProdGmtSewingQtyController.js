let MsProdGmtSewingQtyModel = require('./MsProdGmtSewingQtyModel');

class MsProdGmtSewingQtyController {
	constructor(MsProdGmtSewingQtyModel)
	{
		this.MsProdGmtSewingQtyModel = MsProdGmtSewingQtyModel;
		this.formId='prodgmtsewingqtyFrm';
		             
		this.dataTable='#prodgmtsewingqtyTbl';
		this.route=msApp.baseUrl()+"/prodgmtsewingqty"
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
		let prod_gmt_sewing_order_id=$('#prodgmtsewingorderFrm [name=id]').val()
		let formObj=msApp.get(this.formId);
		formObj.prod_gmt_sewing_order_id=prod_gmt_sewing_order_id;
		if(formObj.id){
			this.MsProdGmtSewingQtyModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdGmtSewingQtyModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		MsProdGmtSewingOrder.resetForm();
		$('#sewinggmtcosi').html('');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtSewingQtyModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdGmtSewingQtyModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#prodgmtsewingqtyTbl').datagrid('reload');
		//msApp.resetForm('prodgmtsewingqtyFrm');
		MsProdGmtSewingQty.resetForm()
		$('#sewinggmtcosi').html('');
		//$('#prodgmtsewingqtyFrm [name=prod_gmt_sewing_order_id]').val($('#prodgmtsewingorderFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsProdGmtSewingQtyModel.get(index,row);

	}

	showGrid(prod_gmt_sewing_order_id){
		let self=this;
		let data = {};
		data.prod_gmt_sewing_order_id=prod_gmt_sewing_order_id;
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
		return '<a href="javascript:void(0)"  onClick="MsProdGmtSewingQty.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}


}
window.MsProdGmtSewingQty=new MsProdGmtSewingQtyController(new MsProdGmtSewingQtyModel());
