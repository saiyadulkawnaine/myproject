let MsSoDyeingFabricRtnModel = require('./MsSoDyeingFabricRtnModel');
require('./../../datagrid-filter.js');
class MsSoDyeingFabricRtnController {
	constructor(MsSoDyeingFabricRtnModel)
	{
		this.MsSoDyeingFabricRtnModel = MsSoDyeingFabricRtnModel;
		this.formId='sodyeingfabricrtnFrm';
		this.dataTable='#sodyeingfabricrtnTbl';
		this.route=msApp.baseUrl()+"/sodyeingfabricrtn"
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
			this.MsSoDyeingFabricRtnModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoDyeingFabricRtnModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#sodyeingfabricrtnFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoDyeingFabricRtnModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoDyeingFabricRtnModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#sodyeingfabricrtnTbl').datagrid('reload');
		MsSoDyeingFabricRtn.resetForm();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let sodyeingfabricrtn=this.MsSoDyeingFabricRtnModel.get(index,row);
		sodyeingfabricrtn.then(function(response){
			$('#sodyeingfabricrtnFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
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
		return '<a href="javascript:void(0)"  onClick="MsSoDyeingFabricRtn.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	
	pdf(){
		var id= $('#sodyeingfabricrtnFrm  [name=id]').val();
		if(id==""){
			alert("Select a Challan/Gate Pass");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

}
window.MsSoDyeingFabricRtn=new MsSoDyeingFabricRtnController(new MsSoDyeingFabricRtnModel());
MsSoDyeingFabricRtn.showGrid();
$('#sodyeingfabricrtntabs').tabs({
	onSelect:function(title,index){
	 let so_dyeing_fabric_rtn_id = $('#sodyeingfabricrtnFrm  [name=id]').val();
	 if(index==1){
		 if(so_dyeing_fabric_rtn_id===''){
			 $('#sodyeingfabricrtntabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		 $('#sodyeingfabricrtnitemFrm  [name=so_dyeing_fabric_rtn_id]').val(so_dyeing_fabric_rtn_id);
		 MsSoDyeingFabricRtnItem.get(so_dyeing_fabric_rtn_id);
	 }
}
}); 
