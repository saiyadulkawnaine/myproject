let MsPlKnitItemStripeModel = require('./MsPlKnitItemStripeModel');
//require('./../../datagrid-filter.js');
class MsPlKnitItemStripeController {
	constructor(MsPlKnitItemStripeModel)
	{
		this.MsPlKnitItemStripeModel = MsPlKnitItemStripeModel;
		this.formId='plknititemstripeFrm';
		this.dataTable='#plknititemstripeTbl';
		this.route=msApp.baseUrl()+"/plknititemstripe"
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
			this.MsPlKnitItemStripeModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPlKnitItemStripeModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		//$('#plknititemstripeFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPlKnitItemStripeModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPlKnitItemStripeModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#plknititemstripeTbl').datagrid('reload');
		msApp.resetForm('plknititemstripeFrm');
		//$('#plknititemstripeFrm [id="buyer_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPlKnitItemStripeModel.get(index,row);
		/* workReceive = this.MsPlKnitItemStripeModel.get(index,row);
		workReceive.then(function(response){
			$('#plknititemstripeFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
		}).catch(function(error){
			console.log(errors)
		}); */
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
		return '<a href="javascript:void(0)"  onClick="MsPlKnitItemStripe.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsPlKnitItemStripe=new MsPlKnitItemStripeController(new MsPlKnitItemStripeModel());
//MsPlKnitItemStripe.showGrid();

 /* $('#subinbworkrcvtabs').tabs({
	onSelect:function(title,index){
	 let pl_knit_id = $('#plknititemstripeFrm  [name=id]').val();
	 var data={};
	  data.pl_knit_id=pl_knit_id;

	 if(index==1){
		 if(pl_knit_id===''){
			 $('#subinbworkrcvtabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		 $('#plknititemstripeproductFrm  [name=pl_knit_id]').val(pl_knit_id);
		 MsPlKnititemStripeProduct.showGrid(pl_knit_id);
	 }
	 if(index==2){
		if(pl_knit_id===''){
			$('#subinbworkrcvtabs').tabs('select',0);
			msApp.showError('Select a Start Up First',0);
			return;
		 }
		$('#plknititemstripefileFrm  [name=pl_knit_id]').val(pl_knit_id);
		MsPlKnititemStripeFile.showGrid(pl_knit_id);
	}
}
}); */ 
