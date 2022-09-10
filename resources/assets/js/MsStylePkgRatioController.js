let MsStylePkgRatioModel = require('./MsStylePkgRatioModel');
class MsStylePkgRatioController {
	constructor(MsStylePkgRatioModel)
	{
		this.MsStylePkgRatioModel = MsStylePkgRatioModel;
		this.formId='stylepkgratioFrm';
		this.dataTable='#stylepkgratioTbl';
		this.route=msApp.baseUrl()+"/stylepkgratio"
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
		/*var str = $( "#"+ this.formId).serialize();
		axios.post(this.route,str)
		.then(function (response) {
		console.log(response.data);
		})
		.catch(function (error) {
		console.log(error);
		});*/
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsStylePkgRatioModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsStylePkgRatioModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		let style_id = $('#styleFrm  [name=id]').val();
		let style_ref = $('#styleFrm  [name=style_ref]').val();
		msApp.resetForm(this.formId);
		MsStylePkg.resetForm ();
		$('#stylepkgFrm  [name=style_ref]').val(style_ref)
		$('#stylepkgFrm  [name=style_id]').val(style_id)
		$('#stylepkgFrm  [name=itemclass_id]').val(62)
		$('#pkgcs').html('');
		
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsStylePkgRatioModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsStylePkgRatioModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#stylepkgratioTbl').datagrid('reload');
		//$('#StylePkgRatioFrm  [name=id]').val(d.id);
		//msApp.resetForm('stylepkgratioFrm');
		MsStylePkgRatio.resetForm ();
		MsStylePkg.resetForm ();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsStylePkgRatioModel.get(index,row);
	}

	showGrid()
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsStylePkgRatio.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	copyQty(qty,iteration,count){
		if($('#is_copy').is(":checked")){
			for(var i =iteration; i<=count; i++){
				$('#stylepkgratioFrm [name="qty['+i+']"]').val(qty);
			}
		}
	}
}
window.MsStylePkgRatio=new MsStylePkgRatioController(new MsStylePkgRatioModel());
//MsStylePkgRatio.showGrid();
