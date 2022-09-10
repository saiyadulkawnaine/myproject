let MsProdGmtPolyModel = require('./MsProdGmtPolyModel');
require('./../../datagrid-filter.js');
class MsProdGmtPolyController {
	constructor(MsProdGmtPolyModel)
	{
		this.MsProdGmtPolyModel = MsProdGmtPolyModel;
		this.formId='prodgmtpolyFrm';
		this.dataTable='#prodgmtpolyTbl';
		this.route=msApp.baseUrl()+"/prodgmtpoly"
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
			this.MsProdGmtPolyModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdGmtPolyModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtPolyModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdGmtPolyModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodgmtpolyTbl').datagrid('reload');
		msApp.resetForm('prodgmtpolyFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsProdGmtPolyModel.get(index,row);
		
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
		return '<a href="javascript:void(0)"  onClick="MsProdGmtPoly.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}


	
}
window.MsProdGmtPoly=new MsProdGmtPolyController(new MsProdGmtPolyModel());
MsProdGmtPoly.showGrid();

 $('#prodgmtpolytabs').tabs({
	onSelect:function(title,index){
	 let prod_gmt_poly_id = $('#prodgmtpolyFrm  [name=id]').val();
	 var data={};
	  data.prod_gmt_poly_id=prod_gmt_poly_id;

	 if(index==1){
		 if(prod_gmt_poly_id===''){
			 $('#prodgmtpolytabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		  msApp.resetForm('prodgmtpolyorderFrm');
		  $('#prodgmtpolyorderFrm  [name=prod_gmt_poly_id]').val(prod_gmt_poly_id);
		  MsProdGmtPolyOrder.showGrid(prod_gmt_poly_id);
	  }

   }
}); 
