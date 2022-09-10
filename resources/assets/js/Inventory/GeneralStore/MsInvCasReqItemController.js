let MsInvCasReqItemModel = require('./MsInvCasReqItemModel');
class MsInvCasReqItemController {
	constructor(MsInvCasReqItemModel)
	{
		this.MsInvCasReqItemModel = MsInvCasReqItemModel;
		this.formId='invcasreqitemFrm';
		this.dataTable='#invcasreqitemTbl';
		this.route=msApp.baseUrl()+"/invcasreqitem"
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
			this.MsInvCasReqItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvCasReqItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	calculateAmount(){
		let qty;
		let rate;
		qty=$('#invcasreqitemFrm [name=qty]').val();
		rate=$('#invcasreqitemFrm [name=rate]').val();
		let amount=qty*rate;
		$('#invcasreqitemFrm [name=amount]').val(amount);

	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#invcasreqitemFrm  [name=inv_pur_req_id]').val($('#invcasreqFrm  [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvCasReqItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvCasReqItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invcasreqitemTbl').datagrid('reload');
		msApp.resetForm('invcasreqitemFrm');
		$('#invcasreqitemFrm  [name=inv_pur_req_id]').val($('#invcasreqFrm  [name=id]').val());

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsInvCasReqItemModel.get(index,row);
	}

	showGrid(inv_pur_req_id)
	{
		let self=this;
		var data={};
		data.inv_pur_req_id=inv_pur_req_id;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			fitColumns:true,
			showFooter:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess:function(data){
				var tQty = 0 ;
				var tAmount = 0 ;
				for(var i=0; i<data.rows.length; i++){
					tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					tAmount+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
					{
						qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: tAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);

			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsInvCasReqItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
}
window.MsInvCasReqItem=new MsInvCasReqItemController(new MsInvCasReqItemModel());