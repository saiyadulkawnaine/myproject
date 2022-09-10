let MsExpLcModel = require('./MsExpLcModel');
require('./../../datagrid-filter.js');
class MsExpLcController {
	constructor(MsExpLcModel)
	{
		this.MsExpLcModel = MsExpLcModel;
		this.formId='explcFrm';
		this.dataTable='#explcTbl';
		this.route=msApp.baseUrl()+"/explc"
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
			this.MsExpLcModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsExpLcModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	
	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#explcFrm [id="buyer_id"]').combobox('setValue', '');;
      	$('#explcFrm [id="consignee_id"]').combobox('setValue', '');
      	$('#explcFrm [id="notifying_party_id"]').combobox('setValue', '');
      	$('#explcFrm [id="second_notifying_party_id"]').combobox('setValue', '');
      	$('#explcFrm [id="forwarding_agent_id"]').combobox('setValue', '');
      	$('#explcFrm [id="shipping_line_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsExpLcModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsExpLcModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#explcTbl').datagrid('reload');
		MsExpLc.resetForm();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		let expLC=this.MsExpLcModel.get(index,row);	
		expLC.then(function(response){
			$('#explcFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
			$('#explcFrm [id="consignee_id"]').combobox('setValue', response.data.fromData.consignee_id);
      		$('#explcFrm [id="notifying_party_id"]').combobox('setValue', response.data.fromData.notifying_party_id);
      		$('#explcFrm [id="second_notifying_party_id"]').combobox('setValue', response.data.fromData.second_notifying_party_id);
      		$('#explcFrm [id="forwarding_agent_id"]').combobox('setValue', response.data.fromData.forwarding_agent_id);
      		$('#explcFrm [id="shipping_line_id"]').combobox('setValue', response.data.fromData.shipping_line_id);
		}).catch(function (error) {
			console.log(error);
		});;

	}

	showGrid(){
		let self=this;
		$('#explcTbl').datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var lc_sc_value=0;
					for(var i=0; i<data.rows.length; i++){
						lc_sc_value+=data.rows[i]['lc_sc_value'].replace(/,/g,'')*1;
					}
					$('#explcTbl').datagrid('reloadFooter', [
					{ 
						lc_sc_value: lc_sc_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),	
					}
				]);			
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsExpLc.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	lcLienLetter()
   {
		var id= $('#explcFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(this.route+"/lclienlatter?id="+id);
   }

   lcAmendmentLetter()
   {
		var id= $('#explcFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(this.route+"/lcamendmentlatter?id="+id);
   }

}
window.MsExpLc=new MsExpLcController(new MsExpLcModel());
MsExpLc.showGrid();

 $('#comexplctabs').tabs({
	onSelect:function(title,index){
	 let exp_lc_sc_id = $('#explcFrm  [name=id]').val();

	 var data={};
	  data.exp_lc_sc_id=exp_lc_sc_id;

	 if(index==1){
		 if(exp_lc_sc_id===''){
			 $('#comexplctabs').tabs('select',0);
			 msApp.showError('Select an Export LC First',0);
			 return;
		  }
		 $('#explctagpiFrm  [name=exp_lc_sc_id]').val(exp_lc_sc_id);
		MsExpLcTagPi.showGrid(exp_lc_sc_id);
	 }
	 if(index==2){
		if(exp_lc_sc_id===''){
			$('#comexplctabs').tabs('select',0);
			msApp.showError('Select an Export LC First',0);
			return;
		 }
		$('#explcorderFrm  [name=exp_lc_sc_id]').val(exp_lc_sc_id);
		MsExpLcOrder.showGrid(exp_lc_sc_id);
	}
	 if(index==3){
		 if(exp_lc_sc_id===''){
			 $('#comexplctabs').tabs('select',0);
			 msApp.showError('Select an Sales Contract First',0);
			 return;
		  }
		 $('#expreplcFrm  [name=exp_lc_sc_id]').val(exp_lc_sc_id)
		 MsExpRepLc.get(exp_lc_sc_id);
	}
	if(index==4){
		if(exp_lc_sc_id===''){
			$('#comexplctabs').tabs('select',0);
			msApp.showError('Select an Sales Contract First',0);
			return;
		}
		$('#explcreviseFrm  [name=exp_lc_sc_id]').val(exp_lc_sc_id);
		let index=null;
			let row={};
			row.id=exp_lc_sc_id;

			MsExpLcRevise.edit(index,row);
		MsExpLcRevise.showGrid(exp_lc_sc_id);
	}
}
}); 
