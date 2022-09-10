let MsCashIncentiveFileQueryModel = require('./MsCashIncentiveFileQueryModel');
require('./../../datagrid-filter.js');
class MsCashIncentiveFileQueryController {
	constructor(MsCashIncentiveFileQueryModel)
	{
		this.MsCashIncentiveFileQueryModel = MsCashIncentiveFileQueryModel;
		this.formId='cashincentivefilequeryFrm';
		this.dataTable='#cashincentivefilequeryTbl';
		this.route=msApp.baseUrl()+"/cashincentivefilequery"
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
			this.MsCashIncentiveFileQueryModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsCashIncentiveFileQueryModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
        msApp.resetForm(this.formId);
        $('#cashincentivefilequeryFrm  [name=cash_incentive_file_id]').val($('#cashincentivefileFrm  [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsCashIncentiveFileQueryModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsCashIncentiveFileQueryModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#cashincentivefilequeryTbl').datagrid('reload');
		msApp.resetForm('cashincentivefilequeryFrm');
		let cash_incentive_file_id=$('#cashincentivefileFrm  [name=id]').val();
		$('#cashincentivefilequeryFrm  [name=cash_incentive_file_id]').val(cash_incentive_file_id);
		//MsCashIncentiveFileQuery.showGrid(cash_incentive_file_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsCashIncentiveFileQueryModel.get(index,row);
	}

	showGrid(cash_incentive_file_id)
	{
		let self=this;
		var data={};
		data.cash_incentive_file_id=cash_incentive_file_id;
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
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsCashIncentiveFileQuery.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	// create(data){
	// 	let d = axios.get(msApp.baseUrl()+"/cashincentivefilequery",data);
	// 	d.then(function(response){
	// 		$('#cashincentivefilequeryTbl').datagrid('loadData',response.data);
	// 	});
	// }
	
	/*openQueryWindow()
	{
		 let data = {};
		 let cash_incentive_file_id = $('#cashincentivefileFrm [name=id]').val();
		 data.cash_incentive_file_id = cash_incentive_file_id;
		 $('#cashincentivefilequeryFrm [name=cash_incentive_file_id]').val(cash_incentive_file_id);
		 //this.create(data);
		 if(cash_incentive_file_id==''){
			alert('Select a File Movement');
			return;
		}else{	 
			$('#filequerywindow').window('open');
			MsCashIncentiveFileQuery.showGrid(cash_incentive_file_id);
		}

	}*/

}
window.MsCashIncentiveFileQuery=new MsCashIncentiveFileQueryController(new MsCashIncentiveFileQueryModel());
//let cash_incentive_file_id=$('#cashincentivefileFrm  [name=id]').val();
//MsCashIncentiveFileQuery.showGrid(cash_incentive_file_id);