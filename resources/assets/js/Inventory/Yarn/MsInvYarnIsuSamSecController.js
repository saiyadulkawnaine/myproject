let MsInvYarnIsuSamSecModel = require('./MsInvYarnIsuSamSecModel');
require('./../../datagrid-filter.js');
class MsInvYarnIsuSamSecController {
	constructor(MsInvYarnIsuSamSecModel)
	{
		this.MsInvYarnIsuSamSecModel = MsInvYarnIsuSamSecModel;
		this.formId='invyarnisusamsecFrm';
		this.dataTable='#invyarnisusamsecTbl';
		this.route=msApp.baseUrl()+"/invyarnisusamsec"
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
			this.MsInvYarnIsuSamSecModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvYarnIsuSamSecModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#invyarnisusamsecFrm [id="supplier_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvYarnIsuSamSecModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvYarnIsuSamSecModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invyarnisusamsecTbl').datagrid('reload');
		msApp.resetForm('invyarnisusamsecFrm');
		$('#invyarnisusamsecFrm [id="supplier_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvYarnIsuSamSecModel.get(index,row);
		data.then(function(response){
			$('#invyarnisusamsecFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
			msApp.resetForm('invyarnisuitemsamsecFrm');
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
		return '<a href="javascript:void(0)"  onClick="MsInvYarnIsuSamSec.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showPdf()
	{
		var id= $('#invyarnisusamsecFrm  [name=id]').val();
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}
	
	showPdf2()
	{
		var id= $('#invyarnisusamsecFrm  [name=id]').val();
		if(id==""){
			alert("Select a GIN");
			return;
		}
		window.open(this.route+"/report2?id="+id);
	}

}
window.MsInvYarnIsuSamSec=new MsInvYarnIsuSamSecController(new MsInvYarnIsuSamSecModel());
MsInvYarnIsuSamSec.showGrid();

$('#invyarnisusamsectabs').tabs({
    onSelect:function(title,index){
        let inv_isu_id = $('#invyarnisusamsecFrm [name=id]').val();
        if(index==1){
			if(inv_isu_id==='')
			{
				$('#invyarnisusamsectabs').tabs('select',0);
				msApp.showError('Select Yarn Issue Entry First',0);
				return;
		    }
		    msApp.resetForm('invyarnisusamsecitemFrm');
			$('#invyarnisusamsecitemFrm  [name=inv_isu_id]').val(inv_isu_id);
			MsInvYarnIsuSamSecItem.get(inv_isu_id);
        }
    }
});

