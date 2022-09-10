//require('./jquery.easyui.min.js');
require('./datagrid-filter.js');
let MsJobModel = require('./MsJobModel');
class MsJobController {
	constructor(MsJobModel)
	{
		this.MsJobModel = MsJobModel;
		this.formId='jobFrm';
		this.dataTable='#jobTbl';
		this.route=msApp.baseUrl()+"/job"
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

		let formData=$( "#"+ this.formId).serialize();
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsJobModel.save(this.route+"/"+formObj.id,'PUT',formData,this.response);
		}else{
			this.MsJobModel.save(this.route,'POST',formData,this.response);
		}
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsJobModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsJobModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#jobTbl').datagrid('reload');
		$('#jobFrm  [name=id]').val(d.id);
		$('#jobFrm  [name=job_no]').val(d.job_no);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsJobModel.get(index,row);
	}

	showGrid()
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsJob.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	openStyleWindow()
	{
		$('#jobw').window('open');
    }
	showStyleGrid()
	{
		let data={};
		data.buyer_id = $('#jobstylesearch  [name=buyer_id]').val();
		data.style_ref = $('#jobstylesearch  [name=style_ref]').val();
		data.style_description = $('#jobstylesearch  [name=style_description]').val();
		let self=this;
		var ff=$('#jobstyleTbl').datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			url:msApp.baseUrl()+"/style",
			onClickRow: function(index,row){
				$('#jobFrm  [name=style_id]').val(row.id);
				$('#jobFrm  [name=style_ref]').val(row.style_ref);
				$('#jobFrm  [name=buyer_id]').val(row.buyer_id);
				$('#jobFrm  [name=uom_id]').val(row.uom_id);
				$('#jobFrm  [name=season_id]').val(row.season_id);
				$('#jobw').window('close')
			}
		});
		ff.datagrid('enableFilter');
	}
}
window.MsJob=new MsJobController(new MsJobModel());
MsJob.showGrid();

$('#jobtabs').tabs({
	onSelect:function(title,index){
		let job_id = $('#jobFrm  [name=id]').val();
		let job_no = $('#jobFrm  [name=job_no]').val();
		let style_id = $('#jobFrm  [name=style_id]').val();
		var data={};
		data.style_id=style_id;
		if(index==0){
			
			msApp.resetForm('salesorderFrm');
			msApp.resetForm('salesordercountryFrm');
		}
		if(index==1){
			if(job_id===''){
				$('#jobtabs').tabs('select',0);
				msApp.showError('Select Job First',0);
				return;
			}
			msApp.resetForm('salesorderFrm');
			$('#salesorderFrm  [name=job_id]').val(job_id)
			$('#salesorderFrm  [name=job_no]').val(job_no)
			MsSalesOrder.showGrid(job_id);
		}
		if(index==2){
			$('#gmtcosi').html('');
			msApp.resetForm('salesordercountryFrm');
			if(job_id===''){
				$('#jobtabs').tabs('select',0);
				msApp.showError('Select Job First',0);
				return;
			}
			if($('#salesorderFrm  [name=id]').val()===''){
				$('#jobtabs').tabs('select',1);
				msApp.showError('Select Sales Order First',0);
				return;
			}
			MsSalesOrderCountry.showGrid($('#salesorderFrm  [name=id]').val());
			$('#salesordercountryFrm  [name=job_id]').val(job_id)
			$('#salesordercountryFrm  [name=sale_order_id]').val($('#salesorderFrm  [name=id]').val())
			$('#salesordercountryFrm  [name=sale_order_no]').val($('#salesorderFrm  [name=sale_order_no]').val())
			$('#salesordercountryFrm  [name=cut_off_date]').val($('#salesorderFrm  [name=ship_date]').val())
			$('#salesordercountryFrm  [name=country_ship_date]').val($('#salesorderFrm  [name=ship_date]').val())

			if($('#salesordercountryFrm  [name=id]').val()===''){
				let stylegmts = msApp.getJson('stylegmts',data);
				stylegmts.then(function (response) {
					$('#salesordercountryFrm [name="style_gmt_id"]').empty();
					$('#salesordercountryFrm [name="style_gmt_id"]').append('<option value="">-Select-</option>');
					$.each(response.data, function(key, value) {
						$('#salesordercountryFrm [name="style_gmt_id"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
					});
				})
				.catch(function (error) {
					console.log(error);
				});
			}
		}
	}
});
