let MsLocalExpPiModel = require('./MsLocalExpPiModel');
require('./../../datagrid-filter.js');
//require('./../../datagrid-cellediting.js');
class MsLocalExpPiController {
	constructor(MsLocalExpPiModel)
	{
		this.MsLocalExpPiModel = MsLocalExpPiModel;
		this.formId='localexppiFrm';
		this.dataTable='#localexppiTbl';
		this.route=msApp.baseUrl()+"/localexppi"
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
			this.MsLocalExpPiModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsLocalExpPiModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#localexppiFrm [id="buyer_id"]').combobox('setValue','');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsLocalExpPiModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsLocalExpPiModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#localexppiTbl').datagrid('reload');
		msApp.resetForm('localexppiFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;	
		let localpi=this.MsLocalExpPiModel.get(index,row);
		localpi.then(function(response){
			//alert(response.data.fromData.buyer_id)
			$('#localexppiFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
		}).catch(function (error) {
			console.log(error);
		});
		//return localpi;

	}

	showGrid(){
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

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsLocalExpPi.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	pdf(){
		var id = $('#localexppiFrm  [name="id"]').val();
		if(id==""){
			alert("Select an Local Pi First");
			return;
		}
		window.open(this.route+"/getlocalpireport?id="+id);
	}

	pdfshort(){
		var id = $('#localexppiFrm  [name="id"]').val();
		if(id==""){
			alert("Select an Local Pi First");
			return;
		}
		window.open(this.route+"/getshortpi?id="+id);
	}

	searchExpPi()
	{
		let params = {};
		params.company_search_id = $('#company_search_id').val();
		params.buyer_search_id = $('#buyer_search_id').val();
		params.from_date = $('#from_date').val();
		params.to_date = $('#to_date').val();
		let data = axios.get(this.route + "/searchexp", { params });
		data.then(function (response)
		{
			$('#localexppiTbl').datagrid('loadData', response.data);
		}).catch(function (error)
		{
			console.log(error);
		});
	}

}
window.MsLocalExpPi=new MsLocalExpPiController(new MsLocalExpPiModel());
MsLocalExpPi.showGrid();

$('#comlocalexppiTabs').tabs({
	onSelect:function(title,index){
	 let local_exp_pi_id = $('#localexppiFrm  [name=id]').val();

	 var data={};
	  data.local_exp_pi_id=local_exp_pi_id;

	 if(index==1){
		 if(local_exp_pi_id===''){
			 $('#comlocalexppiTabs').tabs('select',0);
			 msApp.showError('Select a Local ExpPI First',0);
			 return;
		  }
		 $('#localexppiorderFrm  [name=local_exp_pi_id]').val(local_exp_pi_id)
		 MsLocalExpPiOrder.get(local_exp_pi_id);
	 }
	 if(index==2){
		if(local_exp_pi_id===''){
			$('#comlocalexppiTabs').tabs('select',0);
			msApp.showError('Select Local ExpPI First',0);
			return;
		}
		$('#purchasetermsconditionFrm  [name=purchase_order_id]').val(local_exp_pi_id)
		$('#purchasetermsconditionFrm  [name=menu_id]').val(110)
		MsPurchaseTermsCondition.get();
	}
}
});
