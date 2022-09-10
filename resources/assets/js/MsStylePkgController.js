
let MsStylePkgModel = require('./MsStylePkgModel');
class MsStylePkgController {
	constructor(MsStylePkgModel)
	{
		this.MsStylePkgModel = MsStylePkgModel;
		this.formId='stylepkgFrm';
		this.dataTable='#stylepkgTbl';
		this.route=msApp.baseUrl()+"/stylepkg"
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
			this.MsStylePkgModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsStylePkgModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		let style_id = $('#styleFrm  [name=id]').val();
		let style_ref = $('#styleFrm  [name=style_ref]').val();
		msApp.resetForm(this.formId);
		$('#stylepkgFrm  [name=style_ref]').val(style_ref)
		$('#stylepkgFrm  [name=style_id]').val(style_id)
		$('#stylepkgFrm  [name=itemclass_id]').val(62)
		$('#pkgcs').html('');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsStylePkgModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsStylePkgModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#stylepkgTbl').datagrid('reload');
		//$('#StylePkgFrm  [name=id]').val(d.id);
		msApp.resetForm('stylepkgFrm');
		$('#stylepkgFrm  [name=style_ref]').val($('#styleFrm  [name=style_ref]').val());
		$('#stylepkgFrm  [name=style_id]').val($('#styleFrm  [name=id]').val());
		$('#stylepkgFrm  [name=itemclass_id]').val(62)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsStylePkgModel.get(index,row);
		$('#stylepkgratioFrm  [name=style_pkg_id]').val(row.id);
		$('#stylepkgratioFrm  [name=style_id]').val(row.style_id);
		MsStylePkgRatio.showGrid(row.id);
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
		return '<a href="javascript:void(0)"  onClick="MsStylePkg.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsStylePkg=new MsStylePkgController(new MsStylePkgModel());
