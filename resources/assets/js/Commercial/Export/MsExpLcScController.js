let MsExpLcScModel = require('./MsExpLcScModel');
require('./../../datagrid-filter.js');
class MsExpLcScController {
	constructor(MsExpLcScModel)
	{
		this.MsExpLcScModel = MsExpLcScModel;
		this.formId='explcscFrm';
		this.dataTable='#explcscTbl';
		this.route=msApp.baseUrl()+"/explcsc"
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
			this.MsExpLcScModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsExpLcScModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#explcscFrm [id="buyer_id"]').combobox('setValue', '');
      	$('#explcscFrm [id="consignee_id"]').combobox('setValue', '');
      	$('#explcscFrm [id="notifying_party_id"]').combobox('setValue', '');
      	$('#explcscFrm [id="second_notifying_party_id"]').combobox('setValue', '');
      	$('#explcscFrm [id="forwarding_agent_id"]').combobox('setValue', '');
      	$('#explcscFrm [id="shipping_line_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsExpLcScModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsExpLcScModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#explcscTbl').datagrid('reload');
		MsExpLcSc.resetForm();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;		
		let lcsc=this.MsExpLcScModel.get(index,row);	
		lcsc.then(function(response){
			$('#explcscFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
			$('#explcscFrm [id="consignee_id"]').combobox('setValue', response.data.fromData.consignee_id);
      		$('#explcscFrm [id="notifying_party_id"]').combobox('setValue', response.data.fromData.notifying_party_id);
      		$('#explcscFrm [id="second_notifying_party_id"]').combobox('setValue', response.data.fromData.second_notifying_party_id);
      		$('#explcscFrm [id="forwarding_agent_id"]').combobox('setValue', response.data.fromData.forwarding_agent_id);
      		$('#explcscFrm [id="shipping_line_id"]').combobox('setValue', response.data.fromData.shipping_line_id);
		}).catch(function (error) {
			console.log(error);
		});

	}

	showGrid(){
		let self=this;
		$('#explcscTbl').datagrid({
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
					$('#explcscTbl').datagrid('reloadFooter', [
					{ 
						lc_sc_value: lc_sc_value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),	
					}
				]);			
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsExpLcSc.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	pdf(){
		var id= $('#explcscFrm  [name=id]').val();
		if(id==""){
			alert("Select a Sales Contract");
			return;
		}
		window.open(this.route+"/reportsales?id="+id);
	}

	scLienLetter()
   	{
		var id= $('#explcscFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(this.route+"/sclienlatter?id="+id);
   	}
   	
	scAmendmentLetter()
   	{
		var id= $('#explcscFrm  [name=id]').val();
		if(id==""){
			alert("Select a Document");
			return;
		}
		window.open(this.route+"/scamendmentlatter?id="+id);
   	}

}
window.MsExpLcSc=new MsExpLcScController(new MsExpLcScModel());
MsExpLcSc.showGrid();

$('#comExSalesTabs').tabs({
	onSelect:function(title,index){
		let exp_lc_sc_id = $('#explcscFrm  [name=id]').val();

		var data={};
		data.exp_lc_sc_id=exp_lc_sc_id;
	 	if(index==1){
			if(exp_lc_sc_id===''){
				$('#comExSalesTabs').tabs('select',0);
				msApp.showError('Select an Sales Contract First',0);
				return;
			}
			$('#lcscpiFrm  [name=exp_lc_sc_id]').val(exp_lc_sc_id);
			MsExpLcScPi.showGrid(exp_lc_sc_id);
			
		}
		if(index==2){
			if(exp_lc_sc_id===''){
				$('#comExSalesTabs').tabs('select',0);
				msApp.showError('Select an Sales Contract First',0);
				return;
			 }
			$('#expreplcscFrm  [name=exp_lc_sc_id]').val(exp_lc_sc_id)
			MsExpScOrder.showGrid(exp_lc_sc_id);
		}
		if(index==3){
			if(exp_lc_sc_id===''){
				$('#comExSalesTabs').tabs('select',0);
				msApp.showError('Select an Sales Contract First',0);
				return;
			 }
			$('#expreplcscFrm  [name=exp_lc_sc_id]').val(exp_lc_sc_id)
			MsExpRepLcSc.get(exp_lc_sc_id);
		}
		if(index==4){
			if(exp_lc_sc_id===''){
				$('#comexplctabs').tabs('select',0);
				msApp.showError('Select an Sales Contract First',0);
				return;
			}
			$('#explcscreviseFrm  [name=exp_lc_sc_id]').val(exp_lc_sc_id)
			let index=null;
				let row={};
				row.id=exp_lc_sc_id;

			MsExpLcScRevise.edit(index,row);
			MsExpLcScRevise.showGrid(exp_lc_sc_id);
		}
	}
});
