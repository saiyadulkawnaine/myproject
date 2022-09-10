require('./datagrid-filter.js');
let MsSewingCapacityModel = require('./MsSewingCapacityModel');
class MsSewingCapacityController {
	constructor(MsSewingCapacityModel)
	{
		this.MsSewingCapacityModel = MsSewingCapacityModel;
		this.formId='sewingcapacityFrm';
		this.dataTable='#sewingcapacityTbl';
		this.route=msApp.baseUrl()+"/sewingcapacity"
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
			this.MsSewingCapacityModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSewingCapacityModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSewingCapacityModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSewingCapacityModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsSewingCapacity.get();
		msApp.resetForm('sewingcapacityFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSewingCapacityModel.get(index,row);
	}

	get()
	{
		let d= axios.get(this.route)
		.then(function (response) {
			$('#sewingcapacityTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(data)
	{
		let self=this;
		$(this.dataTable).datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	pdf(){
		var id= $('#sewingcapacityFrm  [name=id]').val();
		if(id==""){
			alert("Select a Capacity");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSewingCapacity.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsSewingCapacity=new MsSewingCapacityController(new MsSewingCapacityModel());
MsSewingCapacity.showGrid([]);
MsSewingCapacity.get();
$('#utilsewingcapacitytabs').tabs({
	onSelect:function(title,index){
		let sewing_capacity_id = $('#sewingcapacityFrm  [name=id]').val();
		if(index==1){
			if(sewing_capacity_id===''){
				$('#utilsewingcapacitytabs').tabs('select',0);
				msApp.showError('Select Buyer First',0);
				return;
			}
			msApp.resetForm('sewingcapacitydateFrm');
			$('#sewingcapacitydateFrm  [name=sewing_capacity_id]').val(sewing_capacity_id)
			MsSewingCapacityDate.create()
		}
	}
});
