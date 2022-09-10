let MsCostStandardModel = require('./MsCostStandardModel');
require('./../../datagrid-filter.js');
class MsCostStandardController {
	constructor(MsCostStandardModel)
	{
		this.MsCostStandardModel = MsCostStandardModel;
		this.formId='coststandardFrm';
		this.dataTable='#coststandardTbl';
		this.route=msApp.baseUrl()+"/coststandard";
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
			this.MsCostStandardModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsCostStandardModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsCostStandardModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsCostStandardModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#coststandardTbl').datagrid('reload');
		//MsCostStandard.showGrid();
		msApp.resetForm('coststandardFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsCostStandardModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsCostStandard.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsCostStandard=new MsCostStandardController(new MsCostStandardModel());

MsCostStandard.showGrid();
$('#coststandardtabs').tabs({
	onSelect:function(title,index){
	let cost_standard_id = $('#coststandardFrm  [name=id]').val();
	if(index==1){
		if(cost_standard_id===''){
			$('#coststandardtabs').tabs('select',0);
			msApp.showError('Select A Cost Standard First',0);
			return;
			}
			$('#coststandardheadFrm  [name=cost_standard_id]').val(cost_standard_id)
			MsCostStandardHead.get(cost_standard_id);
		}
	}
});