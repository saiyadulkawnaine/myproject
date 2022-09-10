let MsEmployeeIncrementModel = require('./MsEmployeeIncrementModel');
require('./../datagrid-filter.js');
class MsEmployeeIncrementController {
	constructor(MsEmployeeIncrementModel)
	{
		this.MsEmployeeIncrementModel = MsEmployeeIncrementModel;
		this.formId='employeeincrementFrm';
		this.dataTable='#employeeincrementTbl';
		this.route=msApp.baseUrl()+"/employeeincrement"
	}

	submit()
	{
		/*$.blockUI({
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
		});*/

		//var id = $('#stylefileuploadFrm [name=id]').val();
		//var style_id = $('#stylefileuploadFrm [name=style_id]').val();
		//var original_name = $('#stylefileuploadFrm [name=original_name]').val();
		
		var formData = new FormData();
		//formData.append('id',id);
		//formData.append('style_id',style_id);
		//formData.append('original_name',original_name);
		var file = document.getElementById('increment_file');
		formData.append('file_src',file.files[0]);
		this.MsEmployeeIncrementModel.upload(this.route,'POST',formData,this.response);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
        this.MsEmployeeIncrementModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsEmployeeIncrementModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#employeeincrementTbl').datagrid('reload');
		msApp.resetForm('employeeincrementFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsEmployeeIncrementModel.get(index,row);
	}

	showGrid()
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsEmployeeIncrement.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	sendtoapi(){
		let id=$('#employeeincrementFrm  [name=id]').val();
		let params={};
		params.id=id;
		let usr= axios.get(this.route+'/sendtoapi',{params})
		.then(function(response){
			//$('#usersearchTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});

	}
}
window.MsEmployeeIncrement = new MsEmployeeIncrementController(new MsEmployeeIncrementModel());
MsEmployeeIncrement.showGrid();

