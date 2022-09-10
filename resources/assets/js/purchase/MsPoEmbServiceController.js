require('./../datagrid-filter.js');
let MsPoEmbServiceModel = require('./MsPoEmbServiceModel');
class MsPoEmbServiceController {
	constructor(MsPoEmbServiceModel)
	{
		this.MsPoEmbServiceModel = MsPoEmbServiceModel;
		this.formId='poembserviceFrm';
		this.dataTable='#poembserviceTbl';
		this.route=msApp.baseUrl()+"/poembservice"
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
			this.MsPoEmbServiceModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoEmbServiceModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPoEmbServiceModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPoEmbServiceModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#poembserviceTbl').datagrid('reload');
		msApp.resetForm('poembserviceFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let poemb=this.MsPoEmbServiceModel.get(index,row);
		poemb.then(function (response) {
			MsPoEmbServiceItem.get(row.id);	
			$('#poembserviceFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
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
		return '<a href="javascript:void(0)"  onClick="MsPoEmbService.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	pdf(){
		var id= $('#poembserviceFrm  [name=id]').val();
		if(id==""){
			alert("Select a Order");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}
}
window.MsPoEmbService=new MsPoEmbServiceController(new MsPoEmbServiceModel());
MsPoEmbService.showGrid();
 $('#poembserviceAccordion').accordion({
	onSelect:function(title,index){
		let po_emb_service_id = $('#poembserviceFrm  [name=id]').val();
		if(index==1){
			if(po_emb_service_id===''){
				msApp.showError('Select Purchase Order First',0);
				$('#poembserviceAccordion').accordion('unselect',1);
				$('#poembserviceAccordion').accordion('select',0);
				return;
			}
		}
		if(index==2){
			if(po_emb_service_id===''){
				msApp.showError('Select Purchase Order First',0);
				$('#poembserviceAccordion').accordion('unselect',1);
				$('#poembserviceAccordion').accordion('select',0);
				return;
			}
			$('#purchasetermsconditionFrm  [name=purchase_order_id]').val(po_emb_service_id)
			$('#purchasetermsconditionFrm  [name=menu_id]').val(10)
			MsPurchaseTermsCondition.get();
		}
	}
}) 
