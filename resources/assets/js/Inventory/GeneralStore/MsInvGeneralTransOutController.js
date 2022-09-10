let MsInvGeneralTransOutModel = require('./MsInvGeneralTransOutModel');
require('./../../datagrid-filter.js');
class MsInvGeneralTransOutController {
	constructor(MsInvGeneralTransOutModel)
	{
		this.MsInvGeneralTransOutModel = MsInvGeneralTransOutModel;
		this.formId='invgeneraltransoutFrm';
		this.dataTable='#invgeneraltransoutTbl';
		this.route=msApp.baseUrl()+"/invgeneraltransout"
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
			this.MsInvGeneralTransOutModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGeneralTransOutModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvGeneralTransOutModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvGeneralTransOutModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invgeneraltransoutTbl').datagrid('reload');
		msApp.resetForm('invgeneraltransoutFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvGeneralTransOutModel.get(index,row);
		data.then(function (response) {
			//$('#invgeneralisurtnFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
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
		return '<a href="javascript:void(0)"  onClick="MsInvGeneralTransOut.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showPdf()
	{
		var id= $('#invgeneraltransoutFrm  [name=id]').val();
		if(id==""){
			alert("Select a PDF");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

}
window.MsInvGeneralTransOut=new MsInvGeneralTransOutController(new MsInvGeneralTransOutModel());
MsInvGeneralTransOut.showGrid();

$('#invgeneraltransouttabs').tabs({
    onSelect:function(title,index){
        let inv_isu_id = $('#invgeneraltransoutFrm [name=id]').val();
        if(index==1){
			if(inv_isu_id==='')
			{
				$('#invgeneraltransouttabs').tabs('select',0);
				msApp.showError('Select  Entry First',0);
				return;
		    }
		    msApp.resetForm('invgeneraltransoutitemFrm');
			$('#invgeneraltransoutitemFrm  [name=inv_isu_id]').val(inv_isu_id);
			MsInvGeneralTransOutItem.get(inv_isu_id);
			//MsInvGeneralTransOutItem.showGrid(inv_isu_id);
        }
    }
});

