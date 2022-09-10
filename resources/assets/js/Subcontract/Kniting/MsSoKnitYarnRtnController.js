let MsSoKnitYarnRtnModel = require('./MsSoKnitYarnRtnModel');
require('./../../datagrid-filter.js');
class MsSoKnitYarnRtnController {
	constructor(MsSoKnitYarnRtnModel)
	{
		this.MsSoKnitYarnRtnModel = MsSoKnitYarnRtnModel;
		this.formId='soknityarnrtnFrm';
		this.dataTable='#soknityarnrtnTbl';
		this.route=msApp.baseUrl()+"/soknityarnrtn"
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
			this.MsSoKnitYarnRtnModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoKnitYarnRtnModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#soknityarnrtnFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoKnitYarnRtnModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoKnitYarnRtnModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#soknityarnrtnTbl').datagrid('reload');
		msApp.resetForm('soknityarnrtnFrm');
		$('#soknityarnrtnFrm [id="buyer_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let soknityarnrtn=this.MsSoKnitYarnRtnModel.get(index,row);
		soknityarnrtn.then(function(response){
			$('#soknityarnrtnFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
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
		return '<a href="javascript:void(0)"  onClick="MsSoKnitYarnRtn.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}


	soWindow(){
		$('#soknityarnrtnsoWindow').window('open');
	}

	pdf(){
		var id= $('#soknityarnrtnFrm  [name=id]').val();
		if(id==""){
			alert("Select a Challan/Gate Pass");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

}
window.MsSoKnitYarnRtn=new MsSoKnitYarnRtnController(new MsSoKnitYarnRtnModel());
MsSoKnitYarnRtn.showGrid();
$('#soknityarnrtntabs').tabs({
	onSelect:function(title,index){
	 let so_knit_yarn_rtn_id = $('#soknityarnrtnFrm  [name=id]').val();
	 var data={};
	 data.so_knit_yarn_rtn_id=so_knit_yarn_rtn_id;
	 if(index==1){
		 if(so_knit_yarn_rtn_id===''){
			 $('#soknityarnrtntabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		 $('#soknityarnrtnitemFrm  [name=so_knit_yarn_rtn_id]').val(so_knit_yarn_rtn_id);
		 //MsSoKnitYarnRcvItem.showGrid([]);
		 MsSoKnitYarnRtnItem.get(so_knit_yarn_rtn_id);
	 }
}
}); 
