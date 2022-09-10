let MsInvGreyFabTransOutModel = require('./MsInvGreyFabTransOutModel');
require('./../../datagrid-filter.js');
class MsInvGreyFabTransOutController {
	constructor(MsInvGreyFabTransOutModel)
	{
		this.MsInvGreyFabTransOutModel = MsInvGreyFabTransOutModel;
		this.formId='invgreyfabtransoutFrm';
		this.dataTable='#invgreyfabtransoutTbl';
		this.route=msApp.baseUrl()+"/invgreyfabtransout"
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
			this.MsInvGreyFabTransOutModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvGreyFabTransOutModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvGreyFabTransOutModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvGreyFabTransOutModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invgreyfabtransoutTbl').datagrid('reload');
		msApp.resetForm('invgreyfabtransoutFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvGreyFabTransOutModel.get(index,row);
		data.then(function (response) {
			//$('#invgreyfabisurtnFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
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
		return '<a href="javascript:void(0)"  onClick="MsInvGreyFabTransOut.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showPdf()
	{
		var id= $('#invgreyfabtransoutFrm  [name=id]').val();
		if(id==""){
			alert("Select a PDF");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}
	showPdfTwo()
	{
		var id= $('#invgreyfabtransoutFrm  [name=id]').val();
		if(id==""){
			alert("Select a PDF");
			return;
		}
		window.open(this.route+"/reporttwo?id="+id);
	}

}
window.MsInvGreyFabTransOut=new MsInvGreyFabTransOutController(new MsInvGreyFabTransOutModel());
MsInvGreyFabTransOut.showGrid();

$('#invgreyfabtransouttabs').tabs({
    onSelect:function(title,index){
        let inv_isu_id = $('#invgreyfabtransoutFrm [name=id]').val();
        if(index==1){
			if(inv_isu_id==='')
			{
				$('#invgreyfabtransouttabs').tabs('select',0);
				msApp.showError('Select  Entry First',0);
				return;
		    }
		    msApp.resetForm('invgreyfabtransoutitemFrm');
			$('#invgreyfabtransoutitemFrm  [name=inv_isu_id]').val(inv_isu_id);
			MsInvGreyFabTransOutItem.get(inv_isu_id);
			//MsInvGreyFabTransOutItem.showGrid(inv_isu_id);
        }
    }
});

