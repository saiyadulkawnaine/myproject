require('./../datagrid-filter.js');
let MsPoKnitServiceModel = require('./MsPoKnitServiceModel');
class MsPoKnitServiceController {
	constructor(MsPoKnitServiceModel)
	{
		this.MsPoKnitServiceModel = MsPoKnitServiceModel;
		this.formId='poknitserviceFrm';
		this.dataTable='#poknitserviceTbl';
		this.route=msApp.baseUrl()+"/poknitservice"
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
			this.MsPoKnitServiceModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoKnitServiceModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPoKnitServiceModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPoKnitServiceModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#poknitserviceTbl').datagrid('reload');
		msApp.resetForm('poknitserviceFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPoKnitServiceModel.get(index,row);
		MsPoKnitServiceItem.get(row.id);
	}

	showGrid()
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			showFooter:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				//var tQty=0;
				var tAmout=0;
				for(var i=0; i<data.rows.length; i++){
				//tQty+=data.rows[i]['item_qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}			
				
				$(this).datagrid('reloadFooter', [
					{ 
						//item_qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
				
				
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoKnitService.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	pdf(){
		var id= $('#poknitserviceFrm  [name=id]').val();
		if(id==""){
			alert("Select a Order");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

	searchPoKnitService(){
		let params = {};
		params.po_no = $('#po_no').val();
		params.supplier_search_id = $('#supplier_search_id').val();
		params.company_search_id = $('#company_search_id').val();
		params.from_date = $('#from_date').val();
		params.to_date = $('#to_date').val();
		let data = axios.get(this.route + "/getsearchpokint", { params });
		data.then(function (response)
		{
			$('#poknitserviceTbl').datagrid('loadData', response.data);
		}).catch(function (error)
		{
			console.log(error);
		});
	}
}
window.MsPoKnitService=new MsPoKnitServiceController(new MsPoKnitServiceModel());
MsPoKnitService.showGrid();
 $('#poknitserviceAccordion').accordion({
	onSelect:function(title,index){
		let po_knit_service_id = $('#poknitserviceFrm  [name=id]').val();
		if(index==1){
			if(po_knit_service_id===''){
				msApp.showError('Select Purchase Order First',0);
				$('#poknitserviceAccordion').accordion('unselect',1);
				$('#poknitserviceAccordion').accordion('select',0);
				return;
			}
		}
		if(index==2){
			if(po_knit_service_id===''){
				msApp.showError('Select Purchase Order First',0);
				$('#poknitserviceAccordion').accordion('unselect',1);
				$('#poknitserviceAccordion').accordion('select',0);
				return;
			}
			$('#purchasetermsconditionFrm  [name=purchase_order_id]').val(po_knit_service_id)
			$('#purchasetermsconditionFrm  [name=menu_id]').val(4)
			MsPurchaseTermsCondition.get();
		}
	}
}) 
