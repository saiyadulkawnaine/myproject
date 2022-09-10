//require('./jquery.easyui.min.js');
let MsGmtspartModel = require('./MsGmtspartModel');
require('./datagrid-filter.js');

class MsGmtspartController {
	constructor(MsGmtspartModel)
	{
		this.MsGmtspartModel = MsGmtspartModel;
		this.formId='gmtspartFrm';
		this.dataTable='#gmtspartTbl';
		this.route=msApp.baseUrl()+"/gmtspart"
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

		let menuId= new Array();
		$('#lstBox2 option').map(function(i, el) {
			menuId.push($(el).val());
		});
		$('#menu_id').val( menuId.join());
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsGmtspartModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsGmtspartModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsGmtspartModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsGmtspartModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#gmtspartTbl').datagrid('reload');
		//$('#GmtspartFrm  [name=id]').val(d.id);
		msApp.resetForm('gmtspartFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsGmtspartModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsGmtspart.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsGmtspart=new MsGmtspartController(new MsGmtspartModel());
MsGmtspart.showGrid();
$('#utilgmtparttabs').tabs({
    onSelect:function(title,index){
        let gmtspart_id = $('#gmtspartFrm [name=id]').val();
        
        var data={};
		    data.gmtspart_id=gmtspart_id;
        if(index==1){
			if(gmtspart_id===''){
				$('#utilgmtparttabs').tabs('select',0);
				msApp.showError('Select A Gmtspart First',0);
				return;
			}
			$('#gmtspartmenuFrm  [name=gmtspart_id]').val(gmtspart_id);
			MsGmtspartMenu.create()
		}
    }
});