require('./../datagrid-filter.js');
let MsPoTrimModel = require('./MsPoTrimModel');
class MsPoTrimController {
	constructor(MsPoTrimModel)
	{
		this.MsPoTrimModel = MsPoTrimModel;
		this.formId='potrimFrm';
		this.dataTable='#potrimTbl';
		this.route=msApp.baseUrl()+"/potrim"
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
			this.MsPoTrimModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoTrimModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#potrimFrm [id="supplier_id"]').combobox('setValue','');
		$('#potrimFrm [id="indentor_id"]').combobox('setValue','');
		$('#potrimFrm [id="buyer_id"]').combobox('setValue','');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPoTrimModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPoTrimModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#potrimTbl').datagrid('reload');
		msApp.resetForm('potrimFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let ptrim=this.MsPoTrimModel.get(index,row);
		ptrim.then(function (response) {
			MsPoTrimItem.get(row.id);	
			$('#potrimFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
			$('#potrimFrm [id="indentor_id"]').combobox('setValue', response.data.fromData.indentor_id);
			$('#potrimFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
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
		return '<a href="javascript:void(0)"  onClick="MsPoTrim.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	pdf(){
		var id= $('#potrimFrm  [name=id]').val();
		if(id==""){
			alert("Select a Order");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}
	pdf2(paper_type){
		var id= $('#potrimFrm  [name=id]').val();
		if(id==""){
			alert("Select a Order");
			return;
		}
		window.open(this.route+"/reportshort?id="+id+'&paper_type='+paper_type);
	}

	searchPoTrim() {
		let params = {};
		params.po_no = $('#po_no').val();
		params.supplier_search_id = $('#supplier_search_id').val();
		params.buyer_search_id = $('#buyer_search_id').val();
		let data = axios.get(this.route + "/searchpotrim", { params });
		data.then(function (response) {
			$('#potrimTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}
}
window.MsPoTrim=new MsPoTrimController(new MsPoTrimModel());
MsPoTrim.showGrid();

$('#potrimAccordion').accordion({
	onSelect:function(title,index){
		let po_trim_id = $('#potrimFrm  [name=id]').val();
		if(index==1){
			if(po_trim_id===''){
				msApp.showError('Select Purchase Order First',0);
				$('#potrimAccordion').accordion('unselect',1);
				$('#potrimAccordion').accordion('select',0);
				return;
			}
		}
		if(index==2){
			if(po_trim_id===''){
				msApp.showError('Select Purchase Order First',0);
				$('#potrimAccordion').accordion('unselect',1);
				$('#potrimAccordion').accordion('select',0);
				return;
			}
			$('#purchasetermsconditionFrm  [name=purchase_order_id]').val(po_trim_id)
			$('#purchasetermsconditionFrm  [name=menu_id]').val(2)
			MsPurchaseTermsCondition.get();
		}
	}
})