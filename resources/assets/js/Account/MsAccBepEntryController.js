let MsAccBepEntryModel = require('./MsAccBepEntryModel');
class MsAccBepEntryController {
	constructor(MsAccBepEntryModel)
	{
		this.MsAccBepEntryModel = MsAccBepEntryModel;
		this.formId='accbepentryFrm';
		this.dataTable='#accbepentryTbl';
		this.route=msApp.baseUrl()+"/accbepentry"
	}

	submit()
	{
		/*$.blockUI({
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
		});*/

		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsAccBepEntryModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsAccBepEntryModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#accbepentryFrm [id="acc_chart_ctrl_head_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsAccBepEntryModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAccBepEntryModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#accbepentryTbl').datagrid('reload');
		MsAccBepEntry.resetForm();
		$('#accbepentryFrm  [name=acc_bep_id]').val($('#accbepFrm  [name=id]').val());
		MsAccBepEntry.showGrid($('#accbepFrm  [name=id]').val())
		$('#accbepentryFrm [id="acc_chart_ctrl_head_id"]').combotree('setValue', 0);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let bep=this.MsAccBepEntryModel.get(index,row);
		bep.then(function (response) {
			$('#accbepentryFrm [id="acc_chart_ctrl_head_id"]').combobox('setValue', response.data.fromData.acc_chart_ctrl_head_id);
			
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(acc_bep_id)
	{
		let data={};
		data.acc_bep_id=acc_bep_id;
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			//showFooter:true,
            queryParams:data,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsAccBepEntry.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsAccBepEntry=new MsAccBepEntryController(new MsAccBepEntryModel());