let MsEmbelishmentTypeModel = require('./MsEmbelishmentTypeModel');

class MsEmbelishmentTypeController {
	constructor(MsEmbelishmentTypeModel)
	{
		this.MsEmbelishmentTypeModel = MsEmbelishmentTypeModel;
		this.formId='embelishmenttypeFrm';
		this.dataTable='#embelishmenttypeTbl';
		this.route=msApp.baseUrl()+"/embelishmenttype"
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
			this.MsEmbelishmentTypeModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsEmbelishmentTypeModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#embelishmenttypeFrm  [name=embelishment_id]').val($('#embelishmentFrm  [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsEmbelishmentTypeModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsEmbelishmentTypeModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	get(embelishment_id){
		let e= axios.get(this.route+"?embelishment_id="+embelishment_id)
		.then(function (response) {
			$('#embelishmenttypeTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	response(d)
	{
		MsEmbelishmentType.get($('#embelishmentFrm  [name=id]').val());
		msApp.resetForm('embelishmenttypeFrm');
		$('#embelishmenttypeFrm  [name=embelishment_id]').val($('#embelishmentFrm  [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsEmbelishmentTypeModel.get(index,row);
	}

	showGrid(data)
	{
		let self=this;
		//var data={};
		//data.embelishment_id=embelishment_id;
		$(this.dataTable).datagrid({
		//	method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
		//	queryParams:data,
		//	url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsEmbelishmentType.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsEmbelishmentType=new MsEmbelishmentTypeController(new MsEmbelishmentTypeModel());
MsEmbelishmentType.showGrid([]);