let MsInvYarnIsuRtnSamSecModel = require('./MsInvYarnIsuRtnSamSecModel');
require('./../../datagrid-filter.js');
class MsInvYarnIsuRtnSamSecController {
	constructor(MsInvYarnIsuRtnSamSecModel)
	{
		this.MsInvYarnIsuRtnSamSecModel = MsInvYarnIsuRtnSamSecModel;
		this.formId='invyarnisurtnsamsecFrm';
		this.dataTable='#invyarnisurtnsamsecTbl';
		this.route=msApp.baseUrl()+"/invyarnisurtnsamsec"
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
			this.MsInvYarnIsuRtnSamSecModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvYarnIsuRtnSamSecModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#invyarnisurtnsamsecFrm [id="supplier_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvYarnIsuRtnSamSecModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvYarnIsuRtnSamSecModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invyarnisurtnsamsecTbl').datagrid('reload');
		msApp.resetForm('invyarnisurtnsamsecFrm');
		$('#invyarnisurtnsamsecFrm [id="supplier_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvYarnIsuRtnSamSecModel.get(index,row);
		data.then(function (response) {
			$('#invyarnisurtnsamsecFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
		})
		.catch(function (error) {
			console.log(error);
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

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsInvYarnIsuRtnSamSec.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showPdf()
	{
		var id= $('#invyarnisurtnsamsecFrm  [name=id]').val();
		if(id==""){
			alert("Select a PDF");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

}
window.MsInvYarnIsuRtnSamSec=new MsInvYarnIsuRtnSamSecController(new MsInvYarnIsuRtnSamSecModel());
MsInvYarnIsuRtnSamSec.showGrid();

$('#invyarnisurtnsamsectabs').tabs({
    onSelect:function(title,index){
        let inv_yarn_rcv_id = $('#invyarnisurtnsamsecFrm [name=inv_yarn_rcv_id]').val();
        if(index==1){
			if(inv_yarn_rcv_id==='')
			{
				$('#invyarnisurtnsamsectabs').tabs('select',0);
				msApp.showError('Select Returned Yarn Issue Entry First',0);
				return;
		    }
			$('#invyarnisurtnitemsamsecFrm  [name=inv_yarn_rcv_id]').val(inv_yarn_rcv_id);
			MsInvYarnIsuRtnItemSamSec.showGrid(inv_yarn_rcv_id);
        }
    }
});

