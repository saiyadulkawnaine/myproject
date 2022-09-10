require('./../datagrid-filter.js');
let MsPoGeneralServiceModel = require('./MsPoGeneralServiceModel');
class MsPoGeneralServiceController {
	constructor(MsPoGeneralServiceModel)
	{
		this.MsPoGeneralServiceModel = MsPoGeneralServiceModel;
		this.formId='pogeneralserviceFrm';
		this.dataTable='#pogeneralserviceTbl';
		this.route=msApp.baseUrl()+"/pogeneralservice"
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
			this.MsPoGeneralServiceModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoGeneralServiceModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#pogeneralserviceFrm [id="supplier_id"]').combobox('setValue','');
		$('#pogeneralserviceFrm [id="indentor_id"]').combobox('setValue','');
		$('#pogeneralserviceFrm [id="price_verified_by_id"]').combobox('setValue','');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPoGeneralServiceModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPoGeneralServiceModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#pogeneralserviceTbl').datagrid('reload');
		msApp.resetForm('pogeneralserviceFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let ptrim=this.MsPoGeneralServiceModel.get(index,row);
		ptrim.then(function (response) {
			MsPoGeneralServiceItem.get(row.id);	
			$('#pogeneralserviceFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
			$('#pogeneralserviceFrm [id="indentor_id"]').combobox('setValue', response.data.fromData.indentor_id);
			$('#pogeneralserviceFrm [id="price_verified_by_id"]').combobox('setValue', response.data.fromData.price_verified_by_id);
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
				$('#pogeneralservicetopsheetTbl').datagrid('appendRow',row);
			},
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['item_qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}			
				
				$(this).datagrid('reloadFooter', [
					{ 
						item_qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
				
				
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoGeneralService.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	pdf(){
		var id= $('#pogeneralserviceFrm  [name=id]').val();
		if(id==""){
			alert("Select a Order");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

	
}
window.MsPoGeneralService=new MsPoGeneralServiceController(new MsPoGeneralServiceModel());
MsPoGeneralService.showGrid();

$('#pogeneralserviceAccordion').accordion({
	onSelect:function(title,index){
		let po_general_service_id = $('#pogeneralserviceFrm  [name=id]').val();
		let menu_id = 11 ;
		if(index==1){
			if(po_general_service_id===''){
				msApp.showError('Select Purchase Order First',0);
				$('#pogeneralserviceAccordion').accordion('unselect',1);
				$('#pogeneralserviceAccordion').accordion('select',0);
				return;
			}
		}
		if(index==2){
			if(po_general_service_id===''){
				msApp.showError('Select Purchase Order First',0);
				$('#pogeneralserviceAccordion').accordion('unselect',1);
				$('#pogeneralserviceAccordion').accordion('select',0);
				return;
			}
			$('#purchasetermsconditionFrm  [name=purchase_order_id]').val(po_general_service_id)
			$('#purchasetermsconditionFrm  [name=menu_id]').val(menu_id)
			MsPurchaseTermsCondition.get();
		}
	}
})