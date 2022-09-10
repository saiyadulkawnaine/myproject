let MsProdFinishDlvAopModel = require('./MsProdFinishDlvAopModel');
require('./../../datagrid-filter.js');
class MsProdFinishDlvAopController {
	constructor(MsProdFinishDlvAopModel)
	{
		this.MsProdFinishDlvAopModel = MsProdFinishDlvAopModel;
		this.formId='prodfinishdlvaopFrm';
		this.dataTable='#prodfinishdlvaopTbl';
		this.route=msApp.baseUrl()+"/prodfinishdlvaop"
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
			this.MsProdFinishDlvAopModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdFinishDlvAopModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#prodfinishdlvaopFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdFinishDlvAopModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdFinishDlvAopModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodfinishdlvaopTbl').datagrid('reload');
		msApp.resetForm('prodfinishdlvaopFrm');
		$('#prodfinishdlvaopFrm [id="buyer_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let carton=this.MsProdFinishDlvAopModel.get(index,row);
		carton.then(function(response){
			$('#prodfinishdlvaopFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
		})
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
		return '<a href="javascript:void(0)"  onClick="MsProdFinishDlvAop.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	pdf()
	{
		var id= $('#prodfinishdlvaopFrm  [name=id]').val();
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}
	challan()
	{
		var id= $('#prodfinishdlvaopFrm  [name=id]').val();
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(this.route+"/getchallan?id="+id);
	}

	pdfshort()
	{
		var id= $('#prodfinishdlvaopFrm  [name=id]').val();
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(this.route+"/reportshort?id="+id);
	}
}
window.MsProdFinishDlvAop=new MsProdFinishDlvAopController(new MsProdFinishDlvAopModel());
MsProdFinishDlvAop.showGrid();
 $('#prodfinishdlvaoptabs').tabs({
	onSelect:function(title,index){
	 let prod_finish_dlv_id = $('#prodfinishdlvaopFrm  [name=id]').val();

	 if(index==1){
		 if(prod_finish_dlv_id===''){
			 $('#prodfinishdlvaoptabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		  /*msApp.resetForm('prodfinishitemFrm');
		  $('#prodfinishitemFrm  [name=prod_finish_id]').val(prod_finish_id);*/
		  MsProdFinishDlvAopRoll.get(prod_finish_dlv_id);
	  }
   }
}); 
