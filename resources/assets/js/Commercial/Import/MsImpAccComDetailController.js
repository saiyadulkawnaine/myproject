let MsImpAccComDetailModel = require('./MsImpAccComDetailModel');
class MsImpAccComDetailController {
	constructor(MsImpAccComDetailModel)
	{
		this.MsImpAccComDetailModel = MsImpAccComDetailModel;
		this.formId='impacccomdetailFrm';
		this.dataTable='#impacccomdetailTbl';
		this.route=msApp.baseUrl()+"/impacccomdetail"
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
			this.MsImpAccComDetailModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsImpAccComDetailModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsImpAccComDetailModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsImpAccComDetailModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#impacccomdetailTbl').datagrid('reload');
		msApp.resetForm('impacccomdetailFrm');
      $('#impacccomdetailFrm  [name=imp_doc_accept_id]').val($('#impdocacceptFrm  [name=id]').val());
      MsImpAccComDetail.create(d.imp_doc_accept_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsImpAccComDetailModel.get(index,row);
	}

	showGrid(imp_doc_accept_id)
	{
		let self=this;
      var data={};
		data.imp_doc_accept_id=imp_doc_accept_id;
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
		return '<a href="javascript:void(0)"  onClick="MsImpAccComDetail.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	create(imp_doc_accept_id)
	{
        let data= axios.get(this.route+"/create"+"?imp_doc_accept_id="+imp_doc_accept_id)
		.then(function (response) {
			$('#impacccomdetailmatrix').html(response.data);
			//$('#exppiordersearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
}
window.MsImpAccComDetail=new MsImpAccComDetailController(new MsImpAccComDetailModel());
