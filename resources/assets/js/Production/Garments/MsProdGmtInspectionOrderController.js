let MsProdGmtInspectionOrderModel = require('./MsProdGmtInspectionOrderModel');

class MsProdGmtInspectionOrderController {
	constructor(MsProdGmtInspectionOrderModel)
	{
		this.MsProdGmtInspectionOrderModel = MsProdGmtInspectionOrderModel;
		this.formId='prodgmtinspectionorderFrm';	             
		this.dataTable='#prodgmtinspectionorderTbl';
		this.route=msApp.baseUrl()+"/prodgmtinspectionorder"
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
		let prod_gmt_inspection_id=$('#prodgmtinspectionFrm [name=id]').val();
		let formObj=msApp.get(this.formId);
		formObj.prod_gmt_inspection_id=prod_gmt_inspection_id;
		if(formObj.id){
			this.MsProdGmtInspectionOrderModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProdGmtInspectionOrderModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	/* create(prod_gmt_inspection_id)
	{
		//let prod_gmt_inspection_id=$('#prodgmtinspectionFrm [name=id]').val();
        let data= axios.get(this.route+"/create"+"?prod_gmt_inspection_id="+prod_gmt_inspection_id)
		.then(function (response) {
			$('#inspectionordergmtcosi').html(response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	} */

	resetForm ()
	{
		msApp.resetForm(this.formId);
		MsProdGmtInspection.resetForm();
		$('#inspectionordergmtcosi').html('');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProdGmtInspectionOrderModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProdGmtInspectionOrderModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{

		MsProdGmtInspectionOrder.resetForm()
		$('#inspectionordergmtcosi').html('');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsProdGmtInspectionOrderModel.get(index,row);

	}

	showGrid(prod_gmt_inspection_id){
		let self=this;
		let data = {};
		data.prod_gmt_inspection_id=prod_gmt_inspection_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsProdGmtInspectionOrder.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

}
window.MsProdGmtInspectionOrder = new MsProdGmtInspectionOrderController(new MsProdGmtInspectionOrderModel());