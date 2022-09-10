let MsSubInbEventModel = require('./MsSubInbEventModel');
class MsSubInbEventController {
	constructor(MsSubInbEventModel)
	{
		this.MsSubInbEventModel = MsSubInbEventModel;
		this.formId='subinbeventFrm';
		this.dataTable='#subinbeventTbl';
		this.route=msApp.baseUrl()+"/subinbevent"
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
			this.MsSubInbEventModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSubInbEventModel.save(this.route,'POST',msApp.qs.stringify(formObj) ,this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#subinbeventFrm  [name=sub_inb_marketing_id]').val($('#subinbmarketingFrm  [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSubInbEventModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSubInbEventModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsSubInbEvent.resetForm();
		let sub_inb_marketing_id = $('#subinbmarketingFrm  [name=id]').val();
		MsSubInbEvent.get(sub_inb_marketing_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSubInbEventModel.get(index,row);
	}

	get(sub_inb_marketing_id){
		let data= axios.get(this.route+"?sub_inb_marketing_id="+sub_inb_marketing_id);
		data.then(function (response) {
			$('#subinbeventTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid(data){
		let self=this;
		$(this.dataTable).datagrid({
			//method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			//url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSubInbEvent.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}


window.MsSubInbEvent=new MsSubInbEventController(new MsSubInbEventModel());
MsSubInbEvent.showGrid([]);