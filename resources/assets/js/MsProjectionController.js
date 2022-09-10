//require('./jquery.easyui.min.js');
require('./datagrid-filter.js');
let MsProjectionModel = require('./MsProjectionModel');
class MsProjectionController {
	constructor(MsProjectionModel)
	{
		this.MsProjectionModel = MsProjectionModel;
		this.formId='projectionFrm';
		this.dataTable='#projectionTbl';
		this.route=msApp.baseUrl()+"/projection"
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
			this.MsProjectionModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProjectionModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProjectionModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProjectionModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#projectionTbl').datagrid('reload');
		msApp.resetForm('projectioncountryFrm');
		$('#projectioncountryFrm  [name=projection_id]').val(d.id)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsProjectionModel.get(index,row);
		msApp.resetForm('projectioncountryFrm');
		$('#projectioncountryFrm  [name=projection_id]').val(row.id);
		MsProjectionCountry.showGrid(row.id);
	}

	showGrid()
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsProjection.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	openStyleWindow()
	{
		$('#w').window('open');
    }
	showStyleGrid()
	{
		let data={};
		data.buyer_id = $('#stylesearch  [name=buyer_id]').val();
		data.style_ref = $('#stylesearch  [name=style_ref]').val();
		data.style_description = $('#stylesearch  [name=style_description]').val();
		let self=this;
		var ff=$('#styleTbl').datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			url:msApp.baseUrl()+"/style",
			onClickRow: function(index,row){
				$('#projectionFrm  [name=style_id]').val(row.id);
				$('#projectionFrm  [name=style_ref]').val(row.style_ref);
				$('#projectionFrm  [name=buyer_id]').val(row.buyer_id);
				$('#projectionFrm  [name=uom_id]').val(row.uom_id);
				$('#projectionFrm  [name=season_id]').val(row.season_id);
				$('#w').window('close')
			}
		});
		ff.datagrid('enableFilter');
	}

}
window.MsProjection=new MsProjectionController(new MsProjectionModel());
MsProjection.showGrid();

$('#projectiontabs').tabs({
	onSelect:function(title,index){
		let projection_id = $('#projectionFrm  [name=id]').val();
		let proj_no = $('#projectionFrm  [name=proj_no]').val();
		var data={};
		data.projection_id=projection_id;
		if(index==1){
			if(projection_id===''){
				$('#projectiontabs').tabs('select',0);
				msApp.showError('Select Projection First',0);
				return;
			}
			$('#projectioncountryFrm  [name=projection_id]').val(projection_id)
			$('#projectioncountryFrm  [name=proj_no]').val(proj_no)		}
	}
});
