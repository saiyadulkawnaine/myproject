//require('./jquery.easyui.min.js');
let MsColorModel = require('./MsColorModel');
require('./datagrid-filter.js');

class MsColorController {
	constructor(MsColorModel)
	{
		this.MsColorModel = MsColorModel;
		this.formId='colorFrm';
		this.dataTable='#colorTbl';
		this.route=msApp.baseUrl()+"/color"
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
			this.MsColorModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsColorModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsColorModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsColorModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#colorTbl').datagrid('reload');
		//$('#ProfitcenterFrm  [name=id]').val(d.id);
		msApp.resetForm('colorFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsColorModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsColor.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsColor=new MsColorController(new MsColorModel());
MsColor.showGrid();
$('#utilcolortabs').tabs({
    onSelect:function(title,index){
        let color_id = $('#colorFrm [name=id]').val();
        
        var data={};
		    data.color_id=color_id;
        if(index==1){
			if(color_id===''){
				$('#utilcolortabs').tabs('select',0);
				msApp.showError('Select A Color First',0);
				return;
			}
			$('#buyercolorFrm  [name=color_id]').val(color_id);
			MsBuyerColor.create()
		}
    }
});