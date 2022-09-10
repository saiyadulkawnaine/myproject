let MsProdFinishDlvModel = require('./MsProdFinishDlvModel');
require('./../../datagrid-filter.js');
class MsProdFinishDlvController {
	constructor(MsProdFinishDlvModel)
	{
		this.MsProdFinishDlvModel = MsProdFinishDlvModel;
		this.formId='prodfinishdlvFrm';
		this.dataTable='#prodfinishdlvTbl';
		this.route=msApp.baseUrl()+"/prodfinishdlv"
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
			this.MsProdFinishDlvModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdFinishDlvModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#prodfinishdlvFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdFinishDlvModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdFinishDlvModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#prodfinishdlvTbl').datagrid('reload');
		msApp.resetForm('prodfinishdlvFrm');
		$('#prodfinishdlvFrm [id="buyer_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let carton=this.MsProdFinishDlvModel.get(index,row);
		carton.then(function(response){
			$('#prodfinishdlvFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
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
		return '<a href="javascript:void(0)"  onClick="MsProdFinishDlv.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Del</span></a>';
	}

	pdf()
	{
		var id= $('#prodfinishdlvFrm  [name=id]').val();
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

	pdfshort()
	{
		var id= $('#prodfinishdlvFrm  [name=id]').val();
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(this.route+"/reportshort?id="+id);
	}

	challan()
	{
		var id= $('#prodfinishdlvFrm  [name=id]').val();
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(this.route+"/getchallan?id="+id);
	}
}
window.MsProdFinishDlv=new MsProdFinishDlvController(new MsProdFinishDlvModel());
MsProdFinishDlv.showGrid();
 $('#prodfinishdlvtabs').tabs({
	onSelect:function(title,index){
	 let prod_finish_dlv_id = $('#prodfinishdlvFrm  [name=id]').val();

	 if(index==1){
		 if(prod_finish_dlv_id===''){
			 $('#prodfinishdlvtabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		  /*msApp.resetForm('prodfinishitemFrm');
		  $('#prodfinishitemFrm  [name=prod_finish_id]').val(prod_finish_id);*/
		  MsProdFinishDlvRoll.get(prod_finish_dlv_id);
	  }
   }
}); 
