
let MsSalesOrderColorModel = require('./MsSalesOrderColorModel');
class MsSalesOrderColorController {
	constructor(MsSalesOrderColorModel)
	{
		this.MsSalesOrderColorModel = MsSalesOrderColorModel;
		this.formId='salesordercolorFrm';
		this.dataTable='#salesordercolorTbl';
		this.route=msApp.baseUrl()+"/salesordercolor"
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
			this.MsSalesOrderColorModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSalesOrderColorModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSalesOrderColorModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSalesOrderColorModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#salesordercolorTbl').datagrid('reload');
		msApp.resetForm('salesordercolorFrm');
		$('#salesordercolorFrm  [name=id]').val(d.id);
		$('#salesordersizeFrm  [name=sale_order_color_id]').val(d.id);

		$('#salesordercolorFrm  [name=job_id]').val($('#jobFrm  [name=id]').val())
		$('#salesordercolorFrm  [name=job_no]').val($('#jobFrm  [name=job_no]').val())
		$('#salesordercolorFrm  [name=sale_order_id]').val($('#salesorderFrm  [name=id]').val())
		$('#salesordercolorFrm  [name=sale_order_no]').val($('#salesorderFrm  [name=sale_order_no]').val())
		$('#salesordercolorFrm  [name=sale_order_country_id]').val($('#salesordercountryFrm  [name=id]').val())
		$('#salesordercolorFrm  [name=style_ref]').val($("#jobFrm [name=style_ref]").val());
		$('#salesordercolorFrm  [name=country_name]').val($("#salesordercountryFrm [name=country_id] option:selected").text());

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSalesOrderColorModel.get(index,row);
		//msApp.resetForm('salesordersizeFrm');
		$('#salesordersizeFrm  [name=job_id]').val(row.job_id);
		$('#salesordersizeFrm  [name=sale_order_id]').val(row.sale_order_id);
		$('#salesordersizeFrm  [name=sale_order_country_id]').val(row.sale_order_country_id);
		$('#salesordersizeFrm  [name=sale_order_color_id]').val(row.id);
	}

	showGrid(sale_order_country_id)
	{
		let data={};
		data.sale_order_country_id=sale_order_country_id;
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			queryParams:data,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSalesOrderColor.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	setColorDetails(style_color_id){
		axios.get(msApp.baseUrl()+'/stylecolor/'+style_color_id+"/edit")
		.then(function (response) {
			$('#salesordercolorFrm  [name=sort_id]').val(response.data.fromData.sort_id);
			$('#salesordercolorFrm  [name=color_code]').val(response.data.fromData.color_code);
		})
		.catch(function (error) {
		console.log(error);
		});
	}
}
window.MsSalesOrderColor=new MsSalesOrderColorController(new MsSalesOrderColorModel());
//MsSalesOrderColor.showGrid();
