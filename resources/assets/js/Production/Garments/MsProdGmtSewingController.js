let MsProdGmtSewingModel = require('./MsProdGmtSewingModel');
require('./../../datagrid-filter.js');
class MsProdGmtSewingController {
	constructor(MsProdGmtSewingModel)
	{
		this.MsProdGmtSewingModel = MsProdGmtSewingModel;
		this.formId='prodgmtsewingFrm';
		this.dataTable='#prodgmtsewingTbl';
		this.route=msApp.baseUrl()+"/prodgmtsewing"
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
			this.MsProdGmtSewingModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdGmtSewingModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtSewingModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdGmtSewingModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodgmtsewingTbl').datagrid('reload');
		msApp.resetForm('prodgmtsewingFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsProdGmtSewingModel.get(index,row);
		
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
		return '<a href="javascript:void(0)"  onClick="MsProdGmtSewing.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}


	
}
window.MsProdGmtSewing=new MsProdGmtSewingController(new MsProdGmtSewingModel());
MsProdGmtSewing.showGrid();

 $('#prodgmtsewingtabs').tabs({
	onSelect:function(title,index){
	 let prod_gmt_sewing_id = $('#prodgmtsewingFrm  [name=id]').val();
	 var data={};
	  data.prod_gmt_sewing_id=prod_gmt_sewing_id;

	 if(index==1){
		 if(prod_gmt_sewing_id===''){
			 $('#prodgmtsewingtabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		  msApp.resetForm('prodgmtsewingorderFrm');
		  $('#prodgmtsewingorderFrm  [name=prod_gmt_sewing_id]').val(prod_gmt_sewing_id);
		  MsProdGmtSewingOrder.showGrid(prod_gmt_sewing_id);
	  }

	 /*  if(index==2){
		 if(prod_gmt_sewing_id===''){
			 $('#prodgmtsewingtabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		  msApp.resetForm('prodgmtsewingqtyFrm');

		  $('#prodgmtsewingqtyFrm  [name=prod_gmt_sewing_id]').val(prod_gmt_sewing_id);
		  MsProdGmtSewingQty.showGrid(prod_gmt_sewing_id);
	  } */
   }
}); 
