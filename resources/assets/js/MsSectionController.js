let MsSectionModel = require('./MsSectionModel');
require('./datagrid-filter.js');

class MsSectionController {
	constructor(MsSectionModel)
	{
		this.MsSectionModel = MsSectionModel;
		this.formId='sectionFrm';
		this.dataTable='#sectionTbl';
		this.route=msApp.baseUrl()+"/section"
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

		// let floorId= new Array();
		// $('#floorBox2 option').map(function(i, el) {
		// 	floorId.push($(el).val());
		// });
		// $('#floor_id').val( floorId.join());
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsSectionModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSectionModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSectionModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSectionModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#sectionTbl').datagrid('reload');
		msApp.resetForm('sectionFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSectionModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsSection.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsSection=new MsSectionController(new MsSectionModel());
MsSection.showGrid();
$('#utilsectiontabs').tabs({
	onSelect:function(title,index){
	   let section_id = $('#sectionFrm  [name=id]').val();
		var data={};
		data.section_id=section_id;
		if(index==1){
			if(section_id===''){
				$('#utilsectiontabs').tabs('select',0);
				msApp.showError('Select A Section First',0);
				return;
			}
			$('#floorsectionFrm  [name=section_id]').val(section_id)
			MsFloorSection.create(section_id)
		}
	}
});