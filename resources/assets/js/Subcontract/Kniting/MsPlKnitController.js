let MsPlKnitModel = require('./MsPlKnitModel');
require('./../../datagrid-filter.js');
class MsPlKnitController {
	constructor(MsPlKnitModel)
	{
		this.MsPlKnitModel = MsPlKnitModel;
		this.formId='plknitFrm';
		this.dataTable='#plknitTbl';
		this.route=msApp.baseUrl()+"/plknit"
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
			this.MsPlKnitModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPlKnitModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#plknitFrm [id="supplier_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPlKnitModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPlKnitModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#plknitTbl').datagrid('reload');
		msApp.resetForm('plknitFrm');
		$('#plknitFrm [id="supplier_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let workReceive=this.MsPlKnitModel.get(index,row);
		workReceive.then(function(response){
			//$('#pldyeingFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
			$('#plknitFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
		}).catch(function(error){
			console.log(errors)
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
			rownumbers:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsPlKnit.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	 pdf()
	 {
		var id= $('#plknitFrm  [name=id]').val();
		var company_id= $('#plknitFrm  [name=company_id]').val();
		if(id==""){
			alert("Select a Costing");
			return;
		}
		window.open(this.route+"/report?id="+id+'&company_id='+company_id);
	}

		
	searchPlan()
	{
		let params={};
		params.from_date=$('#from_date').val();
		params.to_date=$('#to_date').val();
		let data= axios.get(this.route+"/getplan",{params});
		data.then(function (response) {
			$('#plknitTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
}
window.MsPlKnit=new MsPlKnitController(new MsPlKnitModel());
MsPlKnit.showGrid();

  $('#plknittabs').tabs({
	onSelect:function(title,index){
	 let pl_knit_id = $('#plknitFrm  [name=id]').val();
	 let pl_knit_item_id = $('#plknititemFrm  [name=id]').val();
	 var data={};
	 data.pl_knit_id=pl_knit_id;

	 if(index==1){
		 if(pl_knit_id===''){
			 $('#plknittabs').tabs('select',0);
			 msApp.showError('Select a Plan First',0);
			 return;
		  }
		 MsPlKnitItem.resetForm();
		 $('#plknititemFrm  [name=pl_knit_id]').val(pl_knit_id);
		 MsPlKnitItem.get(pl_knit_id);
	 }
	 if(index==2){
		 if(pl_knit_item_id===''){
			 $('#plknittabs').tabs('select',1);
			 msApp.showError('Select a Plan First',0);
			 return;
		  }
		  MsPlKnitItemQty.resetForm();
		 $('#plknititemqtyFrm  [name=pl_knit_item_id]').val(pl_knit_item_id);
		 MsPlKnitItemQty.get(pl_knit_item_id);
	 }
	 if(index==3){
		 if(pl_knit_item_id===''){
			 $('#plknittabs').tabs('select',1);
			 msApp.showError('Select a Item First',0);
			 return;
		  }
		  MsPlKnitItemStripe.resetForm();
		 $('#plknititemstripeFrm  [name=pl_knit_item_id]').val(pl_knit_item_id);
		 MsPlKnitItemStripe.showGrid();
	 }
	 if(index==4){
		 if(pl_knit_item_id===''){
			 $('#plknittabs').tabs('select',1);
			 msApp.showError('Select a Item First',0);
			 return;
		  }
		 //MsPlKnitItemStripe.resetForm();
		 MsPlKnitItemNarrowfabric.resetForm();
		 $('#plknititemnarrowfabricFrm  [name=pl_knit_item_id]').val(pl_knit_item_id);
		 MsPlKnitItemNarrowfabric.showGrid();
	 }
}
});  
