let MsInvYarnIsuRtnModel = require('./MsInvYarnIsuRtnModel');
require('./../../datagrid-filter.js');
class MsInvYarnIsuRtnController {
	constructor(MsInvYarnIsuRtnModel)
	{
		this.MsInvYarnIsuRtnModel = MsInvYarnIsuRtnModel;
		this.formId='invyarnisurtnFrm';
		this.dataTable='#invyarnisurtnTbl';
		this.route=msApp.baseUrl()+"/invyarnisurtn"
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
			this.MsInvYarnIsuRtnModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvYarnIsuRtnModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#invyarnisurtnFrm [id="supplier_id"]').combobox('setValue', '');
		$('#invyarnisurtnFrm [id="return_from_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvYarnIsuRtnModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvYarnIsuRtnModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invyarnisurtnTbl').datagrid('reload');
		msApp.resetForm('invyarnisurtnFrm');
		$('#invyarnisurtnFrm [id="supplier_id"]').combobox('setValue', '');
		$('#invyarnisurtnFrm [id="return_from_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvYarnIsuRtnModel.get(index,row);
		data.then(function (response) {
			$('#invyarnisurtnFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
			$('#invyarnisurtnFrm [id="return_from_id"]').combobox('setValue', response.data.fromData.return_from_id);
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
			//fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsInvYarnIsuRtn.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showPdf()
	{
		var id= $('#invyarnisurtnFrm  [name=id]').val();
		if(id==""){
			alert("Select a PDF");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

}
window.MsInvYarnIsuRtn=new MsInvYarnIsuRtnController(new MsInvYarnIsuRtnModel());
MsInvYarnIsuRtn.showGrid();

$('#invyarnisurtntabs').tabs({
    onSelect:function(title,index){
        let inv_yarn_rcv_id = $('#invyarnisurtnFrm [name=inv_yarn_rcv_id]').val();
        if(index==1){
			if(inv_yarn_rcv_id==='')
			{
				$('#invyarnisurtntabs').tabs('select',0);
				msApp.showError('Select Returned Yarn Issue Entry First',0);
				return;
		    }
			$('#invyarnisurtnitemFrm  [name=inv_yarn_rcv_id]').val(inv_yarn_rcv_id);
			MsInvYarnIsuRtnItem.showGrid(inv_yarn_rcv_id);
        }
    }
});

