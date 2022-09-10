let MsStyleColorModel = require('./MsStyleColorModel');
class MsStyleColorController {
	constructor(MsStyleColorModel)
	{
		this.MsStyleColorModel = MsStyleColorModel;
		this.formId='stylecolorFrm';
		this.dataTable='#stylecolorTbl';
		this.route=msApp.baseUrl()+"/stylecolor"
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
			this.MsStyleColorModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsStyleColorModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsStyleColorModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsStyleColorModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#stylecolorTbl').datagrid('reload');
		$('#stylecolorFrm  [name=id]').val('');
		$('#stylecolorFrm  [name=color_id]').val('');
		$('#stylecolorFrm  [name=color_code]').val('');
		$('#stylecolorFrm  [name=sort_id]').val('');
		//msApp.resetForm('stylecolorFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsStyleColorModel.get(index,row);
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
			fit:true,
			queryParams:data,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsStyleColor.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsStyleColor=new MsStyleColorController(new MsStyleColorModel());
//MsStyleColor.showGrid();
