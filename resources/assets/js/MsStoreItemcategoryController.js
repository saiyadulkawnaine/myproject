let MsStoreItemcategoryModel = require('./MsStoreItemcategoryModel');
class MsStoreItemcategoryController {
	constructor(MsStoreItemcategoryModel)
	{
		this.MsStoreItemcategoryModel = MsStoreItemcategoryModel;
		this.formId='storeitemcategoryFrm';
		this.dataTable='#storeitemcategoryTbl';
		this.route=msApp.baseUrl()+"/storeitemcategory"
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

		let formObj=msApp.get('storeitemcategoryFrm');
		let i=1;
		$.each($('#storeitemcategoryTbl').datagrid('getChecked'), function (idx, val) {
				formObj['itemcategory_id['+i+']']=val.id
				
			i++;
		});
		this.MsStoreItemcategoryModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
	}
	create(){
		var store_id=$('#storeFrm  [name=id]').val()
		let data= axios.get(msApp.baseUrl()+"/storeitemcategory/create?store_id="+store_id);
				data.then(function (response) {
				$('#storeitemcategoryTbl').datagrid({
				checkbox:true,
				rownumbers:true,
				data: response.data.unsaved,
				
				columns:[[
				{field:'ck',checkbox:true,width:40},
				{field:'name',title:'Itemcategory',width:100},
				]],
				});
				
				$('#storeitemcategorysavedTbl').datagrid({
				rownumbers:true,
				data: response.data.saved,
				columns:[[
				{field:'name',title:'Itemcategory',width:100},
				{field:'action',title:'',width:60,formatter:MsStoreItemcategory.formatDetail},
				]],
				});
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
		this.MsStoreItemcategoryModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsStoreItemcategoryModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsStoreItemcategory.create()
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsStoreItemcategoryModel.get(index,row);
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
		});
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsStoreItemcategory.delete(event,'+row.store_itemcategory_id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsStoreItemcategory=new MsStoreItemcategoryController(new MsStoreItemcategoryModel());

