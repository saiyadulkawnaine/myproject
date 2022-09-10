let MsSoDyeingModel = require('./MsSoDyeingModel');
require('./../../datagrid-filter.js');
class MsSoDyeingController {
	constructor(MsSoDyeingModel)
	{
		this.MsSoDyeingModel = MsSoDyeingModel;
		this.formId='sodyeingFrm';
		this.dataTable='#sodyeingTbl';
		this.route=msApp.baseUrl()+"/sodyeing"
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
			this.MsSoDyeingModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoDyeingModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#sodyeingFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoDyeingModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoDyeingModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#sodyeingTbl').datagrid('reload');
		msApp.resetForm('sodyeingFrm');
		$('#sodyeingFrm [id="buyer_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoDyeingModel.get(index,row);
		workReceive.then(function(response){
			$('#sodyeingFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
			$('#sodyeingFrm [id="teammember_id"]').combobox('setValue', response.data.fromData.teammember_id);
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
		return '<a href="javascript:void(0)"  onClick="MsSoDyeing.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
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
				$('#sodyeingFrm [name=sub_inb_marketing_id]').val(row.id);
				$('#subinbmktwindow').window('close');
			}
		}).datagrid('enableFilter');
	}

	sodyeingpoWindowOpen(){
		$('#sodyeingpoWindow').window('open');
		//$('#sodyeingposearchTbl').datagrid('loadData')
	}
	showsodyeingpoGrid(data){
		let self = this;
		$('#sodyeingposearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#sodyeingFrm [name=po_dyeing_service_id]').val(row.id);
				$('#sodyeingFrm [name=sales_order_no]').val(row.po_no);
				$('#sodyeingFrm [name=receive_date]').val(row.po_date);
				$('#sodyeingFrm [name=currency_id]').val(row.currency_id);
				$('#sodyeingFrm [name=exch_rate]').val(row.exch_rate);
				$('#sodyeingpoWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	getsodyeingpo()
	{
		let po_no=$('#sodyeingposearchFrm  [name=po_no]').val();
		let buyer_id=$('#sodyeingFrm  [name=buyer_id]').val();
		let data= axios.get(this.route+"/getpo?po_no="+po_no+"&buyer_id="+buyer_id);
		data.then(function (response) {
			$('#sodyeingposearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	getTeammember(){
		let buyer_id=$('#sodyeingFrm  [name=buyer_id]').val();
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

	searchSoDyeingList(){
		let params={};
		params.customer_id=$('#customer_id').val();
		params.from_receive_date=$('#from_receive_date').val();
		params.to_receive_date=$('#to_receive_date').val();
		let data= axios.get(this.route+"/getsodyeinglist",{params});
		data.then(function (response) {
			$('#sodyeingTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
}
window.MsSoDyeing=new MsSoDyeingController(new MsSoDyeingModel());
MsSoDyeing.showGrid();
MsSoDyeing.showsodyeingpoGrid([]);
	$('#subdyeingworkrcvtabs').tabs({
		onSelect:function(title,index){
		let so_dyeing_id = $('#sodyeingFrm  [name=id]').val();
		var data={};
		data.so_dyeing_id=so_dyeing_id;
		if(index==1){
			if(so_dyeing_id===''){
				$('#subdyeingworkrcvtabs').tabs('select',0);
				msApp.showError('Select a Start Up First',0);
				return;
			}
			$('#sodyeingitemFrm  [name=so_dyeing_id]').val(so_dyeing_id);
			MsSoDyeingItem.showGrid([]);
			MsSoDyeingItem.get(so_dyeing_id);
		}
		if(index==2){
			if(so_dyeing_id===''){
				$('#subdyeingworkrcvtabs').tabs('select',0);
				msApp.showError('Select a Start Up First',0);
				return;
			}
			$('#sodyeingfileFrm  [name=so_dyeing_id]').val(so_dyeing_id);
			MsSoDyeingFile.showGrid(so_dyeing_id);
		}
	}
});