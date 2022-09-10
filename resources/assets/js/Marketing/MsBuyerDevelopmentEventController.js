require('./../datagrid-filter.js');
let MsBuyerDevelopmentEventModel = require('./MsBuyerDevelopmentEventModel');
class MsBuyerDevelopmentEventController {
	constructor(MsBuyerDevelopmentEventModel)
	{
		this.MsBuyerDevelopmentEventModel = MsBuyerDevelopmentEventModel;
		this.formId='buyerdevelopmenteventFrm';
		this.dataTable='#buyerdevelopmenteventTbl';
		this.route=msApp.baseUrl()+"/buyerdevelopmentevent"
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
		let buyer_development_id = $('#buyerdevelopmentFrm  [name=id]').val();
		let formObj=msApp.get(this.formId);
		formObj.buyer_development_id;
		if(formObj.id){
			this.MsBuyerDevelopmentEventModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsBuyerDevelopmentEventModel.save(this.route,'POST',msApp.qs.stringify(formObj) ,this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#buyerdevelopmenteventFrm [id="meeting_type_id"]').combobox('setValue', '');
		//$('#buyerdevelopmenteventFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsBuyerDevelopmentEventModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsBuyerDevelopmentEventModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		let buyer_development_id = $('#buyerdevelopmentFrm  [name=id]').val();
		MsBuyerDevelopmentEvent.get(buyer_development_id);
		MsBuyerDevelopmentEvent.resetForm();		
		//$('#buyerdevelopmenteventFrm  [name=id]').val(d.id);

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsBuyerDevelopmentEventModel.get(index,row);
		data.then(function(response){
			$('#buyerdevelopmenteventFrm [id="meeting_type_id"]').combobox('setValue', response.data.fromData.meeting_type_id);
			//$('#buyerdevelopmenteventFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
			//MsTargetTransfer.getInfo($('#targettransferFrm  [name=process_id] option:selected').val())

		}).catch(function(error){
			console.log(error);
		});
		
	}

	get(buyer_development_id){
		let data= axios.get(this.route+"?buyer_development_id="+buyer_development_id);
		data.then(function (response) {
			$('#buyerdevelopmenteventTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid(data){

		let self=this;
		$(this.dataTable).datagrid({
			//method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			//url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsBuyerDevelopmentEvent.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}


window.MsBuyerDevelopmentEvent=new MsBuyerDevelopmentEventController(new MsBuyerDevelopmentEventModel());
MsBuyerDevelopmentEvent.showGrid([]);