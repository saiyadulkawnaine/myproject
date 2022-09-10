//require('./jquery.easyui.min.js');
let MsSizeModel = require('./MsSizeModel');
require('./datagrid-filter.js');

class MsSizeController {
	constructor(MsSizeModel)
	{
		this.MsSizeModel = MsSizeModel;
		this.formId='sizeFrm';
		this.dataTable='#sizeTbl';
		this.route=msApp.baseUrl()+"/size"
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
			this.MsSizeModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSizeModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSizeModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSizeModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#sizeTbl').datagrid('reload');
		//$('#sizeFrm  [name=id]').val(d.id);
		msApp.resetForm('sizeFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSizeModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsSize.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsSize=new MsSizeController(new MsSizeModel());
MsSize.showGrid();
$('#utilsizetabs').tabs({
    onSelect:function(title,index){
        let size_id = $('#sizeFrm [name=id]').val();
        
        var data={};
		    data.size_id=size_id;
        if(index==1){
			if(size_id===''){
				$('#utilsizetabs').tabs('select',0);
				msApp.showError('Select A Size First',0);
				return;
			}
			$('#buyersizeFrm  [name=size_id]').val(size_id);
			MsBuyerSize.create()
		}
    }
});