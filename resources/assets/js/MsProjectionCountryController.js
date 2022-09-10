//require('./jquery.easyui.min.js');
require('./datagrid-filter.js');
let MsProjectionCountryModel = require('./MsProjectionCountryModel');
class MsProjectionController {
	constructor(MsProjectionCountryModel)
	{
		this.MsProjectionCountryModel = MsProjectionCountryModel;
		this.formId='projectioncountryFrm';
		this.dataTable='#projectioncountryTbl';
		this.route=msApp.baseUrl()+"/projectioncountry"
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
			this.MsProjectionCountryModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsProjectionCountryModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsProjectionCountryModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsProjectionCountryModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#projectioncountryTbl').datagrid('reload');
		//$('#ProjectionFrm  [name=id]').val(d.id);
		msApp.resetForm('projectioncountryFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsProjectionCountryModel.get(index,row);
		$('#projectionqtyFrm  [name=projection_country_id]').val(row.id);
		let data={};
		data.projection_country_id=row.id;
		let html=msApp.getHtml('projectionqty/create',data);
		html.then(function (response) {

			$('#gmtmatrix').html( response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(projection_id)
	{
		let self=this;
		let data={};
		data.projection_id=projection_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsProjectionCountry.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	setCutOffDate(cutoff){
		let ship_date='';
		let d = new Date($('#projectioncountryFrm  [name=cut_off_date]').val());
		if(cutoff==1){
			ship_date= msApp.subDays(d,1);
		}
		else if(cutoff==2){
			ship_date= msApp.addDays(d,1);
		}
		else if(cutoff==3){
			ship_date= msApp.addDays(d,2);
		}
		$('#projectioncountryFrm  [name=country_ship_date]').val(ship_date);
	}
}
window.MsProjectionCountry=new MsProjectionController(new MsProjectionCountryModel());
