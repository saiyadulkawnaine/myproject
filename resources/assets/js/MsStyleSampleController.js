let MsStyleSampleModel = require('./MsStyleSampleModel');
class MsStyleSampleController {
	constructor(MsStyleSampleModel)
	{
		this.MsStyleSampleModel = MsStyleSampleModel;
		this.formId='stylesampleFrm';
		this.dataTable='#stylesampleTbl';
		this.route=msApp.baseUrl()+"/stylesample"
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
			this.MsStyleSampleModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsStyleSampleModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsStyleSampleModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsStyleSampleModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#stylesampleTbl').datagrid('reload');
		//$('#StyleSampleFrm  [name=id]').val(d.id);
		msApp.resetForm('stylesampleFrm');
		$('#styleembelishmentFrm  [name=style_ref]').val($('#styleFrm  [name=style_ref]').val());
		$('#styleembelishmentFrm  [name=style_id]').val($('#styleFrm  [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsStyleSampleModel.get(index,row);
		msApp.resetForm('stylesamplecsFrm');
		$('#stylesamplecsFrm  [name=style_sample_id]').val(row.id);
	}

	showGrid(style_id)
	{
		let self=this;
		var data={};
		data.style_id=style_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			queryParams:data,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsStyleSample.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsStyleSample=new MsStyleSampleController(new MsStyleSampleModel());
//MsStyleSample.showGrid();
