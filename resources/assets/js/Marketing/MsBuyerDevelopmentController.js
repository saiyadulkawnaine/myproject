require('./../datagrid-filter.js');
let MsBuyerDevelopmentModel = require('./MsBuyerDevelopmentModel');
class MsBuyerDevelopmentController {
	constructor(MsBuyerDevelopmentModel)
	{
		this.MsBuyerDevelopmentModel = MsBuyerDevelopmentModel;
		this.formId='buyerdevelopmentFrm';
		this.dataTable='#buyerdevelopmentTbl';
		this.route=msApp.baseUrl()+"/buyerdevelopment"
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
			this.MsBuyerDevelopmentModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsBuyerDevelopmentModel.save(this.route,'POST',msApp.qs.stringify(formObj) ,this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		//$('#buyerdevelopmentFrm [id="team_id"]').combobox('setValue', '');
		$('#buyerdevelopmentFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsBuyerDevelopmentModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsBuyerDevelopmentModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#buyerdevelopmentTbl').datagrid('reload');
		MsBuyerDevelopment.resetForm();		
		//$('#buyerdevelopmentFrm  [name=id]').val(d.id);

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsBuyerDevelopmentModel.get(index,row);
		data.then(function(response){
			//$('#buyerdevelopmentFrm [id="team_id"]').combobox('setValue', response.data.fromData.team_id);
			$('#buyerdevelopmentFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
			let Presponse=response
			self.getTeamMember (response.data.fromData.team_id).then(function(){
				msApp.set(index,row,Presponse.data)
			});
			//MsTargetTransfer.getInfo($('#targettransferFrm  [name=process_id] option:selected').val())

		}).catch(function(error){
			console.log(error);
		});
		
	}

	showGrid(){

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

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsBuyerDevelopment.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	getTeamMember (team_id){
		let data={};
		data.team_id=team_id;
		let team=msApp.getJson('teammember',data)
		.then(function (response) {
			    $('select[name="teammember_id"]').empty();
				$('select[name="teammember_id"]').append('<option value="">-Select-</option>');
                $.each(response.data, function(key, value) {
					if(value.type_id==1 || value.type_id==2){
						$('select[name="teammember_id"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
					}
                });
		})
		.catch(function (error) {
			console.log(error);
		});
		return team;
	}

}


window.MsBuyerDevelopment=new MsBuyerDevelopmentController(new MsBuyerDevelopmentModel());
MsBuyerDevelopment.showGrid();

$('#buyerdevelopmenttabs').tabs({
	onSelect:function(title,index){
	 let buyer_development_id = $('#buyerdevelopmentFrm  [name=id]').val();
	 let buyer_development_intm_id = $('#buyerdevelopmentintmFrm  [name=id]').val();
	 
	 if(index==1){
		 if(buyer_development_id===''){
			 $('#buyerdevelopmenttabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		 msApp.resetForm('buyerdevelopmenteventFrm');
		 $('#buyerdevelopmenteventFrm  [name=buyer_development_id]').val(buyer_development_id);
		 MsBuyerDevelopmentEvent.get(buyer_development_id);
	 }

	 if(index==2){
		 if(buyer_development_id===''){
			 $('#buyerdevelopmenttabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		 msApp.resetForm('buyerdevelopmentintmFrm');
		 $('#buyerdevelopmentintmFrm  [name=buyer_development_id]').val(buyer_development_id);
		 MsBuyerDevelopmentIntm.get(buyer_development_id);
	 }
	 if(index==3){
		 if(buyer_development_id===''){
			 $('#buyerdevelopmenttabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		 msApp.resetForm('buyerdevelopmentdocFrm');
		 $('#buyerdevelopmentdocFrm  [name=buyer_development_id]').val(buyer_development_id);
		 MsBuyerDevelopmentDoc.get(buyer_development_id);
	 }
	 if(index==4){
		if(buyer_development_intm_id===''){
			$('#buyerdevelopmenttabs').tabs('select',2);
			msApp.showError('Select a Start Up First',2);
			return;
		}
		msApp.resetForm('buyerdevelopmentorderFrm');
		$('#buyerdevelopmentorderFrm  [name=buyer_development_intm_id]').val(buyer_development_intm_id);
		MsBuyerDevelopmentOrder.get(buyer_development_intm_id);
	}
}
}); 