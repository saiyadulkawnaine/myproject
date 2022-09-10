let MsSalesOrderCountryModel = require('./MsSalesOrderCountryModel');
class MsSalesOrderCountryController {
	constructor(MsSalesOrderCountryModel)
	{
		this.MsSalesOrderCountryModel = MsSalesOrderCountryModel;
		this.formId='salesordercountryFrm';
		this.dataTable='#salesordercountryTbl';
		this.route=msApp.baseUrl()+"/salesordercountry"
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
			this.MsSalesOrderCountryModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSalesOrderCountryModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSalesOrderCountryModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSalesOrderCountryModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#salesordercountryTbl').datagrid('reload');
		msApp.resetForm('salesordercountryFrm');
		$('#salesordercountryFrm  [name=job_id]').val($('#jobFrm  [name=id]').val())
		$('#salesordercountryFrm  [name=sale_order_id]').val($('#salesorderFrm  [name=id]').val())
		$('#salesordercountryFrm  [name=sale_order_no]').val($('#salesorderFrm  [name=sale_order_no]').val())
		$('#salesordercountryFrm  [name=cut_off_date]').val($('#salesorderFrm  [name=ship_date]').val())
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSalesOrderCountryModel.get(index,row);
		$('#salesordergmtcolorsizeFrm  [name=job_id]').val($('#jobFrm  [name=id]').val())
		$('#salesordergmtcolorsizeFrm  [name=sale_order_id]').val($('#salesorderFrm  [name=id]').val())
		$('#salesordergmtcolorsizeFrm  [name=sale_order_country_id]').val(row.id)
		//$('#wgmtcosi').window('open');
	}

	showGrid(sale_order_id)
	{
		let data={};
		data.sale_order_id=sale_order_id;
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
		return '<a href="javascript:void(0)"  onClick="MsSalesOrderCountry.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	setCutOffDate(cutoff){
		let ship_date='';
		let d = new Date($('#salesordercountryFrm  [name=cut_off_date]').val());
		if(cutoff==1){
			ship_date= msApp.subDays(d,1);
		}
		else if(cutoff==2){
			ship_date= msApp.addDays(d,1);
		}
		else if(cutoff==3){
			ship_date= msApp.addDays(d,2);
		}
		$('#salesordercountryFrm  [name=country_ship_date]').val(ship_date);
	}
}
window.MsSalesOrderCountry=new MsSalesOrderCountryController(new MsSalesOrderCountryModel());
//MsSalesOrderCountry.showGrid();
