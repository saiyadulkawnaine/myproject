//require('./jquery.easyui.min.js');
let MsEmbelishmentModel = require('./MsEmbelishmentModel');
require('./datagrid-filter.js');

class MsEmbelishmentController {
	constructor(MsEmbelishmentModel)
	{
		this.MsEmbelishmentModel = MsEmbelishmentModel;
		this.formId='embelishmentFrm';
		this.dataTable='#embelishmentTbl';
		this.route=msApp.baseUrl()+"/embelishment"
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
			this.MsEmbelishmentModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsEmbelishmentModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsEmbelishmentModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsEmbelishmentModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#embelishmentTbl').datagrid('reload');
		//$('#embelishmentFrm  [name=id]').val(d.id);
		msApp.resetForm('embelishmenttypeFrm');
	    //$('#embelishmenttypeFrm  [name=embelishment_id]').val(d.id);
		//msApp.resetForm('embelishmentFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsEmbelishmentModel.get(index,row);
		//msApp.resetForm('embelishmenttypeFrm');
	  //$('#embelishmenttypeFrm  [name=embelishment_id]').val(row.id);
	 // MsEmbelishmentType.showGrid(row.id);
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
		return '<a href="javascript:void(0)"  onClick="MsEmbelishment.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsEmbelishment=new MsEmbelishmentController(new MsEmbelishmentModel());
MsEmbelishment.showGrid();

$('#libembelishmenttabs').tabs({
        onSelect:function(title,index){
   	let embelishment_id = $('#embelishmentFrm  [name=id]').val();

	var data={};
    	data.embelishment_id=embelishment_id;

	if(index==1){
		if(embelishment_id===''){
			$('#libembelishmenttabs').tabs('select',0);
			msApp.showError('Select Embelisment First',0);
			return;
	    	}
		$('#embelishmenttypeFrm  [name=embelishment_id]').val(embelishment_id)
		MsEmbelishmentType.get(embelishment_id);
	     }
        }
    });

