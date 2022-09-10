let MsCompositionItemcategoryModel = require('./MsCompositionItemcategoryModel');
class MsCompositionItemcategoryController {
	constructor(MsCompositionItemcategoryModel)
	{
		this.MsCompositionItemcategoryModel = MsCompositionItemcategoryModel;
		this.formId='compositionitemcategoryFrm';
		this.dataTable='#compositionitemcategoryTbl';
		this.route=msApp.baseUrl()+"/compositionitemcategory"
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

		let formObj=msApp.get('compositionitemcategoryFrm');
		let i=1;
		$.each($('#compositionitemcategoryTbl').datagrid('getChecked'), function (idx, val) {
				formObj['itemcategory_id['+i+']']=val.id
				
			i++;
		});
		this.MsCompositionItemcategoryModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	create(){
		var composition_id=$('#compositionFrm  [name=id]').val()
		let data= axios.get(msApp.baseUrl()+"/compositionitemcategory/create?composition_id="+composition_id);
            data.then(function (response) {
                $('#compositionitemcategoryTbl').datagrid({
                    checkbox:true,
                    rownumbers:true,
                    data: response.data.unsaved,
                    columns:[[
                        {field:'ck',checkbox:true,width:40},
                        {field:'name',title:'Available',width:100},
                    ]],
                }).datagrid('enableFilter');
                
                $('#compositionitemcategorysavedTbl').datagrid({
                    rownumbers:true,
                    data: response.data.saved,
                    columns:[[
                        {field:'name',title:'Saved',width:100},
                        {field:'action',title:'',width:60,formatter:MsCompositionItemcategory.formatDetail},
                    ]],
                }).datagrid('enableFilter');
            })
            .catch(function (error) {
            console.log(error);
            });
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsCompositionItemcategoryModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsCompositionItemcategoryModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#buyernatureTbl').datagrid('reload');
		MsCompositionItemcategory.create()
		//msApp.resetForm('buyernatureFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsCompositionItemcategoryModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsCompositionItemcategory.delete(event,'+row.composition_itemcategory_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsCompositionItemcategory=new MsCompositionItemcategoryController(new MsCompositionItemcategoryModel());