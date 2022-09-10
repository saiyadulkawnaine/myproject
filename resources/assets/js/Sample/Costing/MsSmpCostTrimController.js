let MsSmpCostTrimModel = require('./MsSmpCostTrimModel');
class MsSmpCostTrimController {
	constructor(MsSmpCostTrimModel)
	{
		this.MsSmpCostTrimModel = MsSmpCostTrimModel;
		this.formId='smpcosttrimFrm';
		this.dataTable='#smpcosttrimTbl';
		this.route=msApp.baseUrl()+"/smpcosttrim"
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
		formObj['smp_cost_id']=$('#smpcostFrm  [name=id]').val();
		if(formObj.id){
			this.MsSmpCostTrimModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSmpCostTrimModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSmpCostTrimModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSmpCostTrimModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		msApp.resetForm('smpcosttrimFrm');
		let smp_cost_id = $('#smpcostFrm  [name=id]').val();
		$('#smpcosttrimFrm  [name=smp_cost_id]').val(smp_cost_id);
		MsSmpCostTrim.get(smp_cost_id);
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSmpCostTrimModel.get(index,row);
	}
	get(smp_cost_id){
		let data= axios.get(this.route+"?smp_cost_id="+smp_cost_id)
		.then(function (response) {
			MsSmpCostTrim.showGrid(response.data)
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
			showFooter:true,
			fit:true,
			singleSelect:true,
			data: data,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSmpCostTrim.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	calculatemount(){
		let cons;
		let rate;
		let qty;
		qty=$('#smpcostFrm  [name=qty]').val();
		cons= $('#smpcosttrimFrm  [name=cons]').val();
		rate=$('#smpcosttrimFrm  [name=rate]').val();
		let bom_qty=(cons/12)*qty;
		let amount=bom_qty*rate;
		$('#smpcosttrimFrm  [name=bom_qty]').val(bom_qty)
		$('#smpcosttrimFrm  [name=amount]').val(amount)
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	formatAddCons(value,row)
	{
		if(row.id){
		return '<a href="javascript:void(0)"  onClick="MsSmpCostTrim.openConsWindow(event,'+row.id+')"><span class="btn btn-warning btn-xs"><i class="fa fa-search"></i>Click</span></a>';
		}
	}

	openConsWindow(event,id){
		if (!event) var event = window.event;                // Get the window event
		event.cancelBubble = true;                       // IE Stop propagation
		if (event.stopPropagation) event.stopPropagation();

		if(!id){
			alert('Save First');
			return;
		}

		let data= axios.get(msApp.baseUrl()+"/smpcosttrimcon/create?smp_cost_trim_id="+id);
		let g=data.then(function (response) {
		for(var key in response.data.dropDown){
			msApp.setHtml(key,response.data.dropDown[key]);
		}
		})
		.catch(function (error) {
			console.log(error);
		});
		g.then(function (response) {
			$('#smpcosttrimconsWindow').window('open');
		})
	}
	setUom(itemclass_id){
     let data= axios.get(msApp.baseUrl()+"/smpcosttrim/setuom?itemclass_id="+itemclass_id);
     let g=data.then(function (response) {
     	$('#smpcosttrimFrm  [name=uom_id]').val(response.data.costing_uom_id);
     	$('#smpcosttrimFrm  [name=uom_name]').val(response.data.costing_uom_id);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
}
window.MsSmpCostTrim=new MsSmpCostTrimController(new MsSmpCostTrimModel());