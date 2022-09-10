let MsAccBepModel = require('./MsAccBepModel');
require('./../datagrid-filter.js');
class MsAccBepController {
	constructor(MsAccBepModel)
	{
		this.MsAccBepModel = MsAccBepModel;
		this.formId='accbepFrm';
		this.dataTable='#accbepTbl';
		this.route=msApp.baseUrl()+"/accbep"
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
			this.MsAccBepModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsAccBepModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsAccBepModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsAccBepModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#accbepTbl').datagrid('reload');
		MsAccBep.resetForm();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsAccBepModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsAccBep.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsAccBep=new MsAccBepController(new MsAccBepModel());
MsAccBep.showGrid();
$('#accbeptabs').tabs({
    onSelect:function(title,index){
        let acc_bep_id = $('#accbepFrm [name=id]').val();
        
        var data={};
		    data.acc_bep_id=acc_bep_id;
        if(index==1){
				if(acc_bep_id===''){
					$('#accbeptabs').tabs('select',0);
					msApp.showError('Select a Break Even Point First',0);
					return;
			    }
				$('#accbepentryFrm  [name=acc_bep_id]').val(acc_bep_id);
				MsAccBepEntry.showGrid(acc_bep_id);
            }
    }
});
