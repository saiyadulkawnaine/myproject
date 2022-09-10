

let MsStylePolyRatioModel = require('./MsStylePolyRatioModel');
class MsStylePolyRatioController {
	constructor(MsStylePolyRatioModel)
	{
		this.MsStylePolyRatioModel = MsStylePolyRatioModel;
		this.formId='stylepolyratioFrm';
		this.dataTable='#stylepolyratioTbl';
		this.route=msApp.baseUrl()+"/stylepolyratio"
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
			this.MsStylePolyRatioModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsStylePolyRatioModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsStylePolyRatioModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsStylePolyRatioModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#stylepolyratioTbl').datagrid('reload');
		//$('#StylePolyRatioFrm  [name=id]').val(d.id);
		msApp.resetForm('stylepolyratioFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsStylePolyRatioModel.get(index,row);
	}

	showGrid(id)
	{
		let self=this;
		var data={};
		data.style_poly_id=id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			queryParams:data,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsStylePolyRatio.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}


}
window.MsStylePolyRatio=new MsStylePolyRatioController(new MsStylePolyRatioModel());
//MsStylePolyRatio.showGrid();
