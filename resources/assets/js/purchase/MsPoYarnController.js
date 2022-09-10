require('./../datagrid-filter.js');
let MsPoYarnModel = require('./MsPoYarnModel');
class MsPoYarnController {
	constructor(MsPoYarnModel)
	{
		this.MsPoYarnModel = MsPoYarnModel;
		this.formId='poyarnFrm';
		this.dataTable='#poyarnTbl';
		this.route=msApp.baseUrl()+"/poyarn"
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
			this.MsPoYarnModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoYarnModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#poyarnFrm [id="supplier_id"]').combobox('setValue','');
		$('#poyarnFrm [id="indentor_id"]').combobox('setValue','');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPoYarnModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPoYarnModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#poyarnTbl').datagrid('reload');
		msApp.resetForm('poyarnFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		pYarn=this.MsPoYarnModel.get(index,row);
		pYarn.then(function (response) {
			MsPoYarnItem.get(row.id);	
			$('#poyarnFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
			$('#poyarnFrm [id="indentor_id"]').combobox('setValue', response.data.fromData.indentor_id);
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
		return '<a href="javascript:void(0)"  onClick="MsPoYarn.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	pdf(){
		var id= $('#poyarnFrm  [name=id]').val();
		if(id==""){
			alert("Select a Order");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

	summeryPdf(){
		var id= $('#poyarnFrm  [name=id]').val();
		if(id==""){
			alert("Select a Order");
			return;
		}
		window.open(this.route+"/summeryreport?id="+id);
	}

	searchPoYarn() {
		let params={};
		params.po_no = $('#po_no').val();
		params.supplier_search_id = $('#supplier_search_id').val();
		params.from_date = $('#from_date').val();
		params.to_date = $('#to_date').val();
		let data = axios(this.route + "/searchpoyearn", { params });
		data.then(function (response) {
			$('#poyarnTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}

}
window.MsPoYarn=new MsPoYarnController(new MsPoYarnModel());
MsPoYarn.showGrid();

$('#poyarnAccordion').accordion({
	onSelect:function(title,index){
		let po_yarn_id = $('#poyarnFrm  [name=id]').val();
		if(index==1){
			if(po_yarn_id===''){
				msApp.showError('Select Purchase Order First',0);
				$('#poyarnAccordion').accordion('unselect',1);
				$('#poyarnAccordion').accordion('select',0);
				return;
			}
		}
		if(index==2){
			if(po_yarn_id===''){
				msApp.showError('Select Purchase Order First',0);
				$('#poyarnAccordion').accordion('unselect',1);
				$('#poyarnAccordion').accordion('select',0);
				return;
			}
			$('#purchasetermsconditionFrm  [name=purchase_order_id]').val(po_yarn_id)
			$('#purchasetermsconditionFrm  [name=menu_id]').val(3)
			MsPurchaseTermsCondition.get();
		}
	}
})

