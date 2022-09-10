let MsTnaOrdModel = require('./MsTnaOrdModel');
require('./../datagrid-filter.js');
class MsTnaOrdController {
	constructor(MsTnaOrdModel)
	{
		this.MsTnaOrdModel = MsTnaOrdModel;
		this.formId='tnaordFrm';
		this.dataTable='#tnaordTbl';
		this.route=msApp.baseUrl()+"/tnaord"
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
            this.MsTnaOrdModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
        }else{
            this.MsTnaOrdModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
        }
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsTnaOrdModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsTnaOrdModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#tnaordTbl').datagrid('reload');
		msApp.resetForm('tnaordFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsTnaOrdModel.get(index,row);
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
		return '<a href="javascript:void(0)" onClick="MsTnaOrd.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsTnaOrd = new MsTnaOrdController(new MsTnaOrdModel());
MsTnaOrd.showGrid();

$('#tnaordtabs').tabs({
	onSelect:function(title,index){
		let tna_ord_id = $('#tnaordFrm [name=id]').val();
		if(index==1){
			if(tna_ord_id===''){
				$('#tnaordtabs').tabs('select',0);
				msApp.showError('Select a Reference Details First',0);
				return;
			}
		}
	}
});