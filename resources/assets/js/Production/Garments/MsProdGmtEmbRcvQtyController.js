let MsProdGmtEmbRcvQtyModel = require('./MsProdGmtEmbRcvQtyModel');

class MsProdGmtEmbRcvQtyController {
	constructor(MsProdGmtEmbRcvQtyModel)
	{
		this.MsProdGmtEmbRcvQtyModel = MsProdGmtEmbRcvQtyModel;
		this.formId='prodgmtembrcvqtyFrm';	             
		this.dataTable='#prodgmtembrcvqtyTbl';
		this.route=msApp.baseUrl()+"/prodgmtembrcvqty"
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
		let prod_gmt_emb_rcv_order_id=$('#prodgmtembrcvorderFrm [name=id]').val()
		let formObj=msApp.get(this.formId);
		formObj.prod_gmt_emb_rcv_order_id=prod_gmt_emb_rcv_order_id;
		if(formObj.id){
			this.MsProdGmtEmbRcvQtyModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdGmtEmbRcvQtyModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		MsProdGmtEmbRcvOrder.resetForm();
		$('#embrcvgmtcosi').html('');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtEmbRcvQtyModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdGmtEmbRcvQtyModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#prodgmtembrcvqtyTbl').datagrid('reload');
		//msApp.resetForm('prodgmtembrcvqtyFrm');
		MsProdGmtEmbRcvQty.resetForm()
		$('#embrcvgmtcosi').html('');
		//$('#prodgmtembrcvqtyFrm [name=prod_gmt_emb_rcv_order_id]').val($('#prodgmtembrcvorderFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsProdGmtEmbRcvQtyModel.get(index,row);

	}

	showGrid(prod_gmt_emb_rcv_order_id){
		let self=this;
		let data = {};
		data.prod_gmt_emb_rcv_order_id=prod_gmt_emb_rcv_order_id;
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
		return '<a href="javascript:void(0)"  onClick="MsProdGmtEmbRcvQty.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsProdGmtEmbRcvQty=new MsProdGmtEmbRcvQtyController(new MsProdGmtEmbRcvQtyModel());