let MsBuyerBranchModel = require('./MsBuyerBranchModel');
class MsBuyerBranchController {
	constructor(MsBuyerBranchModel)
	{
		this.MsBuyerBranchModel = MsBuyerBranchModel;
		this.formId='buyerbranchFrm';
		this.dataTable='#buyerbranchTbl';
		this.route=msApp.baseUrl()+"/buyerbranch"
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
			this.MsBuyerBranchModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsBuyerBranchModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsBuyerBranchModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsBuyerBranchModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#buyerbranchTbl').datagrid('reload');
		//$('#BuyerBranchFrm  [name=id]').val();
		msApp.resetForm('buyerbranchFrm');
		$('#buyerbranchFrm  [name=id]').val($('#buyerFrm  [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsBuyerBranchModel.get(index,row);
	}

	showGrid(buyer_id)
	{
		let self=this;
		var data={};
		data.buyer_id=buyer_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			queryParams:data,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsBuyerBranch.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsBuyerBranch=new MsBuyerBranchController(new MsBuyerBranchModel());

