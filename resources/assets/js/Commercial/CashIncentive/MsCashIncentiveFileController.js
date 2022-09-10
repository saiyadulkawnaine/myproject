let MsCashIncentiveFileModel = require('./MsCashIncentiveFileModel');
class MsCashIncentiveFileController {
	constructor(MsCashIncentiveFileModel)
	{
		this.MsCashIncentiveFileModel = MsCashIncentiveFileModel;
		this.formId='cashincentivefileFrm';
		this.dataTable='#cashincentivefileTbl';
		this.route=msApp.baseUrl()+"/cashincentivefile"
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
			this.MsCashIncentiveFileModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsCashIncentiveFileModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
        msApp.resetForm(this.formId);
        $('#cashincentivefileFrm  [name=cash_incentive_ref_id]').val($('#cashincentiverefFrm  [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsCashIncentiveFileModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsCashIncentiveFileModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#cashincentivefileTbl').datagrid('reload');
		msApp.resetForm('cashincentivefileFrm');
		$('#cashincentivefileFrm  [name=cash_incentive_ref_id]').val($('#cashincentiverefFrm  [name=id]').val());
		//MsCashIncentiveFileQuery.showGrid();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsCashIncentiveFileModel.get(index,row);
	}

	showGrid(cash_incentive_ref_id)
	{
		let self=this;
		var data={};
		data.cash_incentive_ref_id=cash_incentive_ref_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			//showFooter:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData', data);
	}

	

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsCashIncentiveFile.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openQueryWindow()
	{
		 //let data = {};
		 let cash_incentive_file_id = $('#cashincentivefileFrm [name=id]').val();
		 //data.cash_incentive_file_id = cash_incentive_file_id;
		 $('#cashincentivefilequeryFrm [name=cash_incentive_file_id]').val(cash_incentive_file_id);
		 //this.create(data);
		 if(cash_incentive_file_id==''){
			alert('Select a File Movement');
			return;
		}else{	 
			$('#filequerywindow').window('open');
			MsCashIncentiveFileQuery.showGrid(cash_incentive_file_id);
		}

	}

}
window.MsCashIncentiveFile=new MsCashIncentiveFileController(new MsCashIncentiveFileModel());