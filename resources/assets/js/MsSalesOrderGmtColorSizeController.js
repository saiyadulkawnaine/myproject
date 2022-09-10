let MsSalesOrderGmtColorSizeModel = require('./MsSalesOrderGmtColorSizeModel');
class MsSalesOrderGmtColorSizeController {
	constructor(MsSalesOrderGmtColorSizeModel)
	{
		this.MsSalesOrderGmtColorSizeModel = MsSalesOrderGmtColorSizeModel;
		this.formId='salesordergmtcolorsizeFrm';
		this.dataTable='#salesordergmtcolorsizeTbl';
		this.route=msApp.baseUrl()+"/salesordergmtcolorsize"
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
		 //alert('ll')
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsSalesOrderGmtColorSizeModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSalesOrderGmtColorSizeModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSalesOrderGmtColorSizeModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSalesOrderGmtColorSizeModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#salesordergmtcolorsizeTbl').datagrid('reload');
		msApp.resetForm('salesordergmtcolorsizeFrm');
		//$('#salesordergmtcolorsizeFrm #sizetable').empty();
		//MsSalesOrderColor.showGrid($('#salesordercountryFrm  [name=id]').val());

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSalesOrderGmtColorSizeModel.get(index,row);
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

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsSalesOrderSize.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	calculate(iteration,count,field){
		let qty=$('#salesordergmtcolorsizeFrm [name="qty['+iteration+']"]').val();
		let rate=$('#salesordergmtcolorsizeFrm  [name="rate['+iteration+']"]').val();
		let amount=msApp.multiply(qty,rate);
		$('#salesordergmtcolorsizeFrm  [name="amount['+iteration+']"]').val(amount);
		if($('#salesordergmtcolorsizeFrm  #is_copy').is(":checked")){
			if(field==='qty'){
				this.copyQty(qty,iteration,count);
			}
			else if(field==='rate'){
				this.copyRate(rate,iteration,count);
			}
		}
	}
	copyQty(qty,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			let rate=$('#salesordergmtcolorsizeFrm  [name="rate['+i+']"]').val();
			let amount=msApp.multiply(qty,rate);
			$('#salesordergmtcolorsizeFrm  [name="qty['+i+']"]').val(qty)
			$('#salesordergmtcolorsizeFrm  [name="amount['+i+']"]').val(amount)
		}
	}
	copyRate(rate,iteration,count)
	{
		for(var i=iteration;i<=count;i++)
		{
			let qty=$('input[name="qty['+i+']"]').val();
			let amount=msApp.multiply(qty,rate);
			$('input[name="rate['+i+']"]').val(rate)
			$('input[name="amount['+i+']"]').val(amount)
		}
	}
}
window.MsSalesOrderGmtColorSize=new MsSalesOrderGmtColorSizeController(new MsSalesOrderGmtColorSizeModel());
//MsSalesOrderSize.showGrid();
