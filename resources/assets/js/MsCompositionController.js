//require('./jquery.easyui.min.js');
let MsCompositionModel = require('./MsCompositionModel');
require('./datagrid-filter.js');

class MsCompositionController {
	constructor(MsCompositionModel)
	{
		this.MsCompositionModel = MsCompositionModel;
		this.formId='compositionFrm';
		this.dataTable='#compositionTbl';
		this.route=msApp.baseUrl()+"/composition"
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
				composition: '#fff',
				padding: 0,
				zIndex: 9999999,
				backgroundColor: 'transparent'
			}
		});

		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsCompositionModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsCompositionModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsCompositionModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsCompositionModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#compositionTbl').datagrid('reload');
		//$('#CompositionFrm  [name=id]').val(d.id);
		msApp.resetForm('compositionFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsCompositionModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsComposition.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsComposition=new MsCompositionController(new MsCompositionModel());
MsComposition.showGrid();
$('#utilcompositiontabs').tabs({
    onSelect:function(title,index){
        let composition_id = $('#compositionFrm [name=id]').val();
        
        var data={};
		data.composition_id=composition_id;
        if(index==1){
			if(composition_id===''){
				$('#utilcompositiontabs').tabs('select',0);
				msApp.showError('Select A Composition First',0);
				return;
			}
			$('#compositionitemcategoryFrm  [name=composition_id]').val(composition_id);
			MsCompositionItemcategory.create(composition_id)
		}
    }
});