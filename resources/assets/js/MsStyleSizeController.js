let MsStyleSizeModel = require('./MsStyleSizeModel');
class MsStyleSizeController {
	constructor(MsStyleSizeModel)
	{
		this.MsStyleSizeModel = MsStyleSizeModel;
		this.formId='stylesizeFrm';
		this.dataTable='#stylesizeTbl';
		this.route=msApp.baseUrl()+"/stylesize"
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
			this.MsStyleSizeModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsStyleSizeModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsStyleSizeModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsStyleSizeModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#stylesizeTbl').datagrid('reload');
		$('#stylesizeFrm  [name=id]').val('');
		$('#stylesizeFrm  [name=size_id]').val('');
		$('#stylesizeFrm  [name=size_code]').val('');
		$('#stylesizeFrm  [name=sort_id]').val('');
		//msApp.resetForm('stylesizeFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsStyleSizeModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsStyleSize.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsStyleSize=new MsStyleSizeController(new MsStyleSizeModel());
//MsStyleSize.showGrid();
