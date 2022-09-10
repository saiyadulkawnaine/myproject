require('./datagrid-filter.js');
let MsKeycontrolModel = require('./MsKeycontrolModel');
class MsKeycontrolController {
	constructor(MsKeycontrolModel)
	{
		this.MsKeycontrolModel = MsKeycontrolModel;
		this.formId='keycontrolFrm';
		this.dataTable='#keycontrolTbl';
		this.route=msApp.baseUrl()+"/keycontrol"
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
			this.MsKeycontrolModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsKeycontrolModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsKeycontrolModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsKeycontrolModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#keycontrolTbl').datagrid('reload');
	  //$('#keycontrolFrm  [name=id]').val(d.id);
		msApp.resetForm('keycontrolFrm');
	  //$('#keycontrolparameterFrm  [name=keycontrol_id]').val(d.id);
		//msApp.resetForm('keycontrolFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsKeycontrolModel.get(index,row);
	//msApp.resetForm('keycontrolparameterFrm');
	  //$('#keycontrolparameterFrm  [name=keycontrol_id]').val(row.id);
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
		});
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsKeycontrol.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsKeycontrol=new MsKeycontrolController(new MsKeycontrolModel());
MsKeycontrol.showGrid();
$('#UtilKeyControls').tabs({
	onSelect:function(title,index){
		let keycontrol_id = $('#keycontrolFrm  [name=id]').val();

		 var data={};
		 data.keycontrol_id=keycontrol_id;

		 if(index==1){
			 if(keycontrol_id===''){
				 $('#UtilKeyControls').tabs('select',0);
				 msApp.showError('Select A Key Control First',0);
				 return;
			 }
			 $('#keycontrolparameterFrm  [name=keycontrol_id]').val(keycontrol_id)
			 MsKeycontrolParameter.showGrid(keycontrol_id);
		 }
 	}
});