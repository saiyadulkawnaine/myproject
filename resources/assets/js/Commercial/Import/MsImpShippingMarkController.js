let MsImpShippingMarkModel = require('./MsImpShippingMarkModel');
class MsImpShippingMarkController {
	constructor(MsImpShippingMarkModel)
	{
		this.MsImpShippingMarkModel = MsImpShippingMarkModel;
		this.formId='impshippingmarkFrm';
		this.dataTable='#impshippingmarkTbl';
		this.route=msApp.baseUrl()+"/impshippingmark"
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
			this.MsImpShippingMarkModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsImpShippingMarkModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsImpShippingMarkModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsImpShippingMarkModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#impshippingmarkTbl').datagrid('reload');
		msApp.resetForm('impshippingmarkFrm');
      $('#impshippingmarkFrm  [name=imp_lc_id]').val($('#implc  [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsImpShippingMarkModel.get(index,row);
	}

	showGrid(imp_lc_id)
	{
		let self=this;
      var data={};
		data.imp_lc_id=imp_lc_id;
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
		});
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsImpShippingMark.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsImpShippingMark=new MsImpShippingMarkController(new MsImpShippingMarkModel());
