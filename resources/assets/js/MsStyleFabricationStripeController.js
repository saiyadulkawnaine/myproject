require('./datagrid-filter.js');
let MsStyleFabricationStripeModel = require('./MsStyleFabricationStripeModel');
class MsStyleFabricationStripeController {
	constructor(MsStyleFabricationStripeModel)
	{
		this.MsStyleFabricationStripeModel = MsStyleFabricationStripeModel;
		this.formId='stylefabricationstripeFrm';
		this.dataTable='#stylefabricationstripeTbl';
		this.route=msApp.baseUrl()+"/stylefabricationstripe"
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

		if($('#stylefabricationFrm  [name=is_stripe]').val()==0){
			alert("No Need Stripe Details");
			return;
		}
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsStyleFabricationStripeModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsStyleFabricationStripeModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsStyleFabricationStripeModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsStyleFabricationStripeModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#stylefabricationstripeTbl').datagrid('reload');
		//$('#StyleFabricationStripeFrm  [name=id]').val(d.id);
		msApp.resetForm('stylefabricationstripeFrm');
		let stylefabricationid=$('#stylefabricationFrm  [name=id]').val();
		$('#stylefabricationstripeFrm  [name=style_fabrication_id]').val(stylefabricationid)
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsStyleFabricationStripeModel.get(index,row);
	}

	showGrid(style_fabrication_id)
	{
		var data={};
		data.style_fabrication_id=style_fabrication_id;
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			queryParams:data,
			url:this.route,
			fit:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsStyleFabricationStripe.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsStyleFabricationStripe=new MsStyleFabricationStripeController(new MsStyleFabricationStripeModel());
//MsStyleFabricationStripe.showGrid();
