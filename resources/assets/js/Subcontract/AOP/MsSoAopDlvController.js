let MsSoAopDlvModel = require('./MsSoAopDlvModel');
require('./../../datagrid-filter.js');
class MsSoAopDlvController {
	constructor(MsSoAopDlvModel)
	{
		this.MsSoAopDlvModel = MsSoAopDlvModel;
		this.formId='soaopdlvFrm';
		this.dataTable='#soaopdlvTbl';
		this.route=msApp.baseUrl()+"/soaopdlv"
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
			this.MsSoAopDlvModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoAopDlvModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#soaopdlvFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoAopDlvModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoAopDlvModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#soaopdlvTbl').datagrid('reload');
		msApp.resetForm('soaopdlvFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoAopDlvModel.get(index,row);
		workReceive.then(function(response){
			$('#soaopdlvFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
		}).catch(function(error){
			console.log(errors)
		});
	}

	showGrid()
	{
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
		return '<a href="javascript:void(0)"  onClick="MsSoAopDlv.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showDc()
	{
		var id= $('#soaopdlvFrm  [name=id]').val();
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(this.route+"/dlvchalan?id="+id);
	}
	showBill()
	{
		var id= $('#soaopdlvFrm  [name=id]').val();
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(this.route+"/bill?id="+id);
	}

	searchSoAopDlvList(){
		let params={};
		params.customer_id=$('#customer_id').val();
		params.from_date=$('#from_date').val();
		params.to_date=$('#to_date').val();
		let data= axios.get(this.route+"/getsoaopdlvlist",{params});
		data.then(function (response) {
			$('#soaopdlvTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});	
	}

	
}
window.MsSoAopDlv=new MsSoAopDlvController(new MsSoAopDlvModel());
MsSoAopDlv.showGrid();
 $('#soaopdlvtabs').tabs({
	onSelect:function(title,index){
	 let so_aop_dlv_id = $('#soaopdlvFrm  [name=id]').val();
	 var data={};
	 data.so_aop_dlv_id=so_aop_dlv_id;
	 if(index==1){
		 if(so_aop_dlv_id===''){
			 $('#soaopdlvtabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		 $('#soaopdlvitemFrm  [name=so_aop_dlv_id]').val(so_aop_dlv_id);
		 MsSoAopDlvItem.get(so_aop_dlv_id);
	 }
}
}); 
