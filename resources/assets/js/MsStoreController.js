//require('./jquery.easyui.min.js');
let MsStoreModel = require('./MsStoreModel');
require('./datagrid-filter.js');
class MsStoreController {
	constructor(MsStoreModel)
	{
		this.MsStoreModel = MsStoreModel;
		this.formId='storeFrm';
		this.dataTable='#storeTbl';
		this.route=msApp.baseUrl()+"/store";
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
			this.MsStoreModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsStoreModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsStoreModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsStoreModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#storeTbl').datagrid('reload');
		msApp.resetForm('storeFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsStoreModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsStore.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsStore=new MsStoreController(new MsStoreModel());
MsStore.showGrid();

$('#utilstoretabs').tabs({
	onSelect:function(title,index){
	 let store_id = $('#storeFrm  [name=id]').val();
	 var data={};
	  data.store_id=store_id;
	 if(index==1){
		 if(store_id===''){
			 $('#utilstoretabs').tabs('select',0);
			 msApp.showError('Select Store First',0);
			 return;
		  }
		 $('#storeitemcategoryFrm  [name=store_id]').val(store_id);
		 MsStoreItemcategory.create()
	 }
	}
});

