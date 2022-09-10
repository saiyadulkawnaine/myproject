let MsProdGmtIronModel = require('./MsProdGmtIronModel');
require('./../../datagrid-filter.js');
class MsProdGmtIronController {
	constructor(MsProdGmtIronModel)
	{
		this.MsProdGmtIronModel = MsProdGmtIronModel;
		this.formId='prodgmtironFrm';
		this.dataTable='#prodgmtironTbl';
		this.route=msApp.baseUrl()+"/prodgmtiron"
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
			this.MsProdGmtIronModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdGmtIronModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtIronModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdGmtIronModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodgmtironTbl').datagrid('reload');
		msApp.resetForm('prodgmtironFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsProdGmtIronModel.get(index,row);
		
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
		return '<a href="javascript:void(0)"  onClick="MsProdGmtIron.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}


	
}
window.MsProdGmtIron=new MsProdGmtIronController(new MsProdGmtIronModel());
MsProdGmtIron.showGrid();

 $('#prodgmtirontabs').tabs({
	onSelect:function(title,index){
	 let prod_gmt_iron_id = $('#prodgmtironFrm  [name=id]').val();
	 var data={};
	  data.prod_gmt_iron_id=prod_gmt_iron_id;

	 if(index==1){
		 if(prod_gmt_iron_id===''){
			 $('#prodgmtirontabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		  msApp.resetForm('prodgmtironorderFrm');
		  $('#prodgmtironorderFrm  [name=prod_gmt_iron_id]').val(prod_gmt_iron_id);
		  MsProdGmtIronOrder.showGrid(prod_gmt_iron_id);
	  }

   }
}); 
