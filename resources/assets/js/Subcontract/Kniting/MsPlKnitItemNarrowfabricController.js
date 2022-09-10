let MsPlKnitItemNarrowfabricModel = require('./MsPlKnitItemNarrowfabricModel');
//require('./../../datagrid-filter.js');
class MsPlKnitItemNarrowfabricController {
	constructor(MsPlKnitItemNarrowfabricModel)
	{
		this.MsPlKnitItemNarrowfabricModel = MsPlKnitItemNarrowfabricModel;
		this.formId='plknititemnarrowfabricFrm';
		this.dataTable='#plknititemnarrowfabricTbl';
		this.route=msApp.baseUrl()+"/plknititemnarrowfabric"
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
			this.MsPlKnitItemNarrowfabricModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPlKnitItemNarrowfabricModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		//$('#plknititemnarrowfabricFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPlKnitItemNarrowfabricModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPlKnitItemNarrowfabricModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#plknititemnarrowfabricTbl').datagrid('reload');
		msApp.resetForm('plknititemnarrowfabricFrm');
		//$('#plknititemnarrowfabricFrm [id="buyer_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPlKnitItemNarrowfabricModel.get(index,row);
		/* workReceive = this.MsPlKnitItemNarrowfabricModel.get(index,row);
		workReceive.then(function(response){
			$('#plknititemnarrowfabricFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
		}).catch(function(error){
			console.log(errors)
		}); */
	}

	showGrid(){

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

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsPlKnitItemNarrowfabric.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsPlKnitItemNarrowfabric=new MsPlKnitItemNarrowfabricController(new MsPlKnitItemNarrowfabricModel());
//MsPlKnitItemNarrowfabric.showGrid();