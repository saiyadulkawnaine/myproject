let MsInvGreyFabIsuModel = require('./MsInvGreyFabIsuModel');
require('./../../datagrid-filter.js');
class MsInvGreyFabIsuController {
	constructor(MsInvGreyFabIsuModel)
	{
		this.MsInvGreyFabIsuModel = MsInvGreyFabIsuModel;
		this.formId='invgreyfabisuFrm';
		this.dataTable='#invgreyfabisuTbl';
		this.route=msApp.baseUrl()+"/invgreyfabisu"
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
			this.MsInvGreyFabIsuModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGreyFabIsuModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#invgreyfabisuFrm [id="supplier_id"]').combobox('setValue', '');
		$('#invgreyfabisuFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvGreyFabIsuModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvGreyFabIsuModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invgreyfabisuTbl').datagrid('reload');
		msApp.resetForm('invgreyfabisuFrm');
		$('#invgreyfabisuFrm [id="supplier_id"]').combobox('setValue', '');
		$('#invgreyfabisuFrm [id="buyer_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvGreyFabIsuModel.get(index,row);
		data.then(function(response){
			$('#invgreyfabisuFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
			$('#invgreyfabisuFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
			msApp.resetForm('invgreyfabisuitemFrm');
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
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsRcvGreyFab.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showPdf()
	{
		var id= $('#invgreyfabisuFrm  [name=id]').val();
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}
	showPdfTwo()
	{
		var id= $('#invgreyfabisuFrm  [name=id]').val();
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(this.route+"/reporttwo?id="+id);
	}
}
window.MsInvGreyFabIsu=new MsInvGreyFabIsuController(new MsInvGreyFabIsuModel());
MsInvGreyFabIsu.showGrid();

$('#invgreyfabisutabs').tabs({
    onSelect:function(title,index){
        let inv_isu_id = $('#invgreyfabisuFrm [name=id]').val();
        if(index==1){
			if(inv_isu_id==='')
			{
				$('#invgreyfabisutabs').tabs('select',0);
				msApp.showError('Select Grey Fab Issue Entry First',0);
				return;
		    }
			$('#invgreyfabisuitemFrm  [name=inv_isu_id]').val(inv_isu_id);
			MsInvGreyFabIsuItem.get(inv_isu_id);
        }
    }
});

