let MsSoKnitModel = require('./MsSoKnitModel');
require('./../../datagrid-filter.js');
class MsSoKnitController {
	constructor(MsSoKnitModel)
	{
		this.MsSoKnitModel = MsSoKnitModel;
		this.formId='soknitFrm';
		this.dataTable='#soknitTbl';
		this.route=msApp.baseUrl()+"/soknit"
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
			this.MsSoKnitModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoKnitModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#soknitFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoKnitModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoKnitModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#soknitTbl').datagrid('reload');
		msApp.resetForm('soknitFrm');
		$('#soknitFrm [id="buyer_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoKnitModel.get(index,row);
		workReceive.then(function(response){
			$('#soknitFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
		}).catch(function(error){
			console.log(errors)
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
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSoKnit.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	openSubInbMktWindow(){
		$('#subinbmktwindow').window('open');
	}

	showMktGrid(){
		let data = {};
		data.company_id = $('#subinbmktsearchFrm [name="company_id"]').val();
		data.production_area_id = $('#subinbmktsearchFrm [name="production_area_id"]').val();
		data.buyer_id = $('#subinbmktsearchFrm [name="buyer_id"]').val();
		data.mkt_date = $('#subinbmktsearchFrm [name="mkt_date"]').val();
		let self = this;
		$('#subinbmktsearchTbl').datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			queryParams:data,
			url:this.route+"/getmktref",
			onClickRow: function(index,row)
			{
				$('#soknitFrm [name=sub_inb_marketing_id]').val(row.id);
				$('#subinbmktwindow').window('close');
			}
		}).datagrid('enableFilter');
	}

	soknitpoWindowOpen(){
		$('#soknitpoWindow').window('open');
		//$('#soknitposearchTbl').datagrid('loadData')
	}
	showsoknitpoGrid(data){
		let self = this;
		$('#soknitposearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#soknitFrm [name=po_knit_service_id]').val(row.id);
				$('#soknitFrm [name=sales_order_no]').val(row.po_no);
				$('#soknitFrm [name=receive_date]').val(row.po_date);
				$('#soknitFrm [name=currency_id]').val(row.currency_id);
				$('#soknitFrm [name=exch_rate]').val(row.exch_rate);
				$('#soknitpoWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	getsoknitpo()
	{
		let po_no=$('#soknitposearchFrm  [name=po_no]').val();
		let buyer_id=$('#soknitFrm  [name=buyer_id]').val();
		let data= axios.get(this.route+"/getpo?po_no="+po_no+"&buyer_id="+buyer_id);
		data.then(function (response) {
			$('#soknitposearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	searchSoKnit() {
		let params = {};
		params.date_from = $('#date_from').val();
		params.date_to = $('#date_to').val();
		params.search_buyer_id = $('#search_buyer_id').val();
		let data = axios.get(this.route + "/getsoknit", { params });
		data.then(function (response) {
			$('#soknitTbl').datagrid('loadData', response.data);
		}).catch(function (error) {
			console.log(error);
		});
	}

}
window.MsSoKnit=new MsSoKnitController(new MsSoKnitModel());
MsSoKnit.showGrid();
MsSoKnit.showsoknitpoGrid([]);
 $('#subinbworkrcvtabs').tabs({
	onSelect:function(title,index){
	 let so_knit_id = $('#soknitFrm  [name=id]').val();
	 var data={};
	 data.so_knit_id=so_knit_id;
	 if(index==1){
		 if(so_knit_id===''){
			 $('#subinbworkrcvtabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		 $('#soknititemFrm  [name=so_knit_id]').val(so_knit_id);
		 MsSoKnitItem.showGrid([]);
		 MsSoKnitItem.get(so_knit_id);
	 }
	 if(index==2){
		if(so_knit_id===''){
			$('#subinbworkrcvtabs').tabs('select',0);
			msApp.showError('Select a Start Up First',0);
			return;
		 }
		$('#soknitfileFrm  [name=so_knit_id]').val(so_knit_id);
		MsSoKnitFile.showGrid(so_knit_id);
	}
}
}); 
