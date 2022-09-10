let MsSoAopModel = require('./MsSoAopModel');
require('./../../datagrid-filter.js');
class MsSoAopController {
	constructor(MsSoAopModel)
	{
		this.MsSoAopModel = MsSoAopModel;
		this.formId='soaopFrm';
		this.dataTable='#soaopTbl';
		this.route=msApp.baseUrl()+"/soaop"
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
			this.MsSoAopModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoAopModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#soaopFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoAopModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoAopModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#soaopTbl').datagrid('reload');
		msApp.resetForm('soaopFrm');
		$('#soaopFrm [id="buyer_id"]').combobox('setValue', '');
		$('#soaopFrm [id="teammember_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		aopService = this.MsSoAopModel.get(index,row);
		aopService.then(function(response){
			$('#soaopFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
			$('#soaopFrm [id="teammember_id"]').combobox('setValue', response.data.fromData.teammember_id);
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
		return '<a href="javascript:void(0)"  onClick="MsSoAop.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
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
				$('#soaopFrm [name=sub_inb_marketing_id]').val(row.id);
				$('#subinbmktwindow').window('close');
			}
		}).datagrid('enableFilter');
	}

	soaoppoWindowOpen(){
		$('#soaoppoWindow').window('open');
		//$('#soaopposearchTbl').datagrid('loadData')
	}
	showsoaoppoGrid(data){
		let self = this;
		$('#soaopposearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#soaopFrm [name=po_aop_service_id]').val(row.id);
				$('#soaopFrm [name=sales_order_no]').val(row.po_no);
				$('#soaopFrm [name=receive_date]').val(row.po_date);
				$('#soaopFrm [name=currency_id]').val(row.currency_id);
				$('#soaopFrm [name=exch_rate]').val(row.exch_rate);
				$('#soaoppoWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	getsoaoppo()
	{
		let po_no=$('#soaopposearchFrm  [name=po_no]').val();
		let buyer_id=$('#soaopFrm  [name=buyer_id]').val();
		let data= axios.get(this.route+"/getpo?po_no="+po_no+"&buyer_id="+buyer_id);
		data.then(function (response) {
			$('#soaopposearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	getTeammember(){
		let buyer_id=$('#soaopFrm  [name=buyer_id]').val();
		const instance = axios.create();
		let data= instance.get(this.route+"/getteammember?buyer_id="+buyer_id);
		data.then(function (response) {
			   $('select[name="teammember_id"]').empty();
				$('select[name="teammember_id"]').append('<option value="">-Select-</option>');
                $.each(response.data, function(key, value) {
					$('select[name="teammember_id"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                });
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	searchSoAopList(){
		let params={};
		params.customer_id=$('#customer_id').val();
		params.from_date=$('#from_date').val();
		params.to_date=$('#to_date').val();
		let data= axios.get(this.route+"/getsoaoplist",{params});
		data.then(function (response) {
			$('#soaopTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
}
window.MsSoAop=new MsSoAopController(new MsSoAopModel());
MsSoAop.showGrid();
MsSoAop.showsoaoppoGrid([]);
	$('#subaopworkrcvtabs').tabs({
		onSelect:function(title,index){
		let so_aop_id = $('#soaopFrm  [name=id]').val();
		var data={};
		data.so_aop_id=so_aop_id;
		if(index==1){
			if(so_aop_id===''){
				$('#subaopworkrcvtabs').tabs('select',0);
				msApp.showError('Select a Start Up First',0);
				return;
			}
			$('#soaopitemFrm  [name=so_aop_id]').val(so_aop_id);
			
			MsSoAopItem.get(so_aop_id);
		}
		if(index==2){
			if(so_aop_id===''){
				$('#subaopworkrcvtabs').tabs('select',0);
				msApp.showError('Select a Start Up First',0);
				return;
			}
			$('#soaopfileFrm  [name=so_aop_id]').val(so_aop_id);
			MsSoAopFile.showGrid(so_aop_id);
		}
	}
});