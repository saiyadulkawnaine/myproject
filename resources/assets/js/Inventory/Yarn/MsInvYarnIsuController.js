let MsInvYarnIsuModel = require('./MsInvYarnIsuModel');
require('./../../datagrid-filter.js');
class MsInvYarnIsuController {
	constructor(MsInvYarnIsuModel)
	{
		this.MsInvYarnIsuModel = MsInvYarnIsuModel;
		this.formId='invyarnisuFrm';
		this.dataTable='#invyarnisuTbl';
		this.route=msApp.baseUrl()+"/invyarnisu"
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
			this.MsInvYarnIsuModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvYarnIsuModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#invyarnisuFrm [id="supplier_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvYarnIsuModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvYarnIsuModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invyarnisuTbl').datagrid('reload');
		msApp.resetForm('invyarnisuFrm');
		$('#invyarnisuFrm [id="supplier_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvYarnIsuModel.get(index,row);
		data.then(function(response){
			$('#invyarnisuFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
			msApp.resetForm('invyarnisuitemFrm');
		}).catch(function(error){
			console.log(error);
		})
	}

	showGrid()
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsRcvYarn.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showPdf()
	{
		var id= $('#invyarnisuFrm  [name=id]').val();
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

	showPdf2()
	{
		var id= $('#invyarnisuFrm  [name=id]').val();
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(this.route+"/report2?id="+id);
	}

}
window.MsInvYarnIsu=new MsInvYarnIsuController(new MsInvYarnIsuModel());
MsInvYarnIsu.showGrid();

$('#invyarnisutabs').tabs({
    onSelect:function(title,index){
        let inv_isu_id = $('#invyarnisuFrm [name=id]').val();
        if(index==1){
			if(inv_isu_id==='')
			{
				$('#invyarnisutabs').tabs('select',0);
				msApp.showError('Select Yarn Issue Entry First',0);
				return;
		    }
			$('#invyarnisuitemFrm  [name=inv_isu_id]').val(inv_isu_id);
			MsInvYarnIsuItem.get(inv_isu_id);
        }
    }
});

