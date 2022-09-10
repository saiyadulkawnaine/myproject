require('./datagrid-filter.js');
let MsSewingCapacityDateModel = require('./MsSewingCapacityDateModel');
class MsSewingCapacityDateController {
	constructor(MsSewingCapacityDateModel)
	{
		this.MsSewingCapacityDateModel = MsSewingCapacityDateModel;
		this.formId='sewingcapacitydateFrm';
		this.dataTable='#sewingcapacitydateTbl';
		this.route=msApp.baseUrl()+"/sewingcapacitydate"
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
			this.MsSewingCapacityDateModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSewingCapacityDateModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSewingCapacityDateModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSewingCapacityDateModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsSewingCapacityDate.create();
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSewingCapacityDateModel.get(index,row);
	}
	create(){
		let sewing_capacity_id = $('#sewingcapacityFrm  [name=id]').val();
		let data= axios.get(msApp.baseUrl()+"/sewingcapacitydate/create?sewing_capacity_id="+sewing_capacity_id);
		data.then(function (response) {
			$('#sewingcapacitydatematrix').html(response.data);
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

	copyResourceQty(value,iteration,count){
		for(var i=iteration;i<=count;i++)
		{
			let day_status=$('#sewingcapacitydateFrm select[name="day_status['+i+']"]').val()
			if(day_status==1){
				$('#sewingcapacitydateFrm input[name="resource_qty['+i+']"]').val(value)
			}
			else if(day_status==2)
			{
				$('#sewingcapacitydateFrm input[name="resource_qty['+i+']"]').val(0)
			}
		}
	}
	dayStatusChange(value,iteration,count){
		if(value==2){
		    $('#sewingcapacitydateFrm input[name="resource_qty['+iteration+']"]').val(0)
		}
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSewingCapacityDate.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsSewingCapacityDate=new MsSewingCapacityDateController(new MsSewingCapacityDateModel());
MsSewingCapacityDate.showGrid();
