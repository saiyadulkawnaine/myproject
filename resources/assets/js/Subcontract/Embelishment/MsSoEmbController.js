let MsSoEmbModel = require('./MsSoEmbModel');
require('./../../datagrid-filter.js');
class MsSoEmbController {
	constructor(MsSoEmbModel)
	{
		this.MsSoEmbModel = MsSoEmbModel;
		this.formId='soembFrm';
		this.dataTable='#soembTbl';
		this.route=msApp.baseUrl()+"/soemb"
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
			this.MsSoEmbModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoEmbModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#soembFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoEmbModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoEmbModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#soembTbl').datagrid('reload');
		msApp.resetForm('soembFrm');
		$('#soembFrm [id="buyer_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoEmbModel.get(index,row);
		workReceive.then(function(response){
			$('#soembFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
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
		return '<a href="javascript:void(0)"  onClick="MsSoEmb.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
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
				$('#soembFrm [name=sub_inb_marketing_id]').val(row.id);
				$('#subinbmktwindow').window('close');
			}
		}).datagrid('enableFilter');
	}

	soembpoWindowOpen(){
		$('#soembpoWindow').window('open');
		//$('#soembposearchTbl').datagrid('loadData')
	}
	showsoembpoGrid(data){
		let self = this;
		$('#soembposearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#soembFrm [name=po_emb_service_id]').val(row.id);
				$('#soembFrm [name=sales_order_no]').val(row.po_no);
				$('#soembFrm [name=receive_date]').val(row.po_date);
				$('#soembpoWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	getsoembpo()
	{
		let po_no=$('#soembposearchFrm  [name=po_no]').val();
		let buyer_id=$('#soembFrm  [name=buyer_id]').val();
		let currency_id=$('#soembFrm  [name=currency_id]').val();
		let production_area_id=$('#soembFrm  [name=production_area_id]').val();
		if (production_area_id=='' || currency_id=='') {
			alert('Select Currency and Production Area First');
			return ;
		}
		let data= axios.get(this.route+"/getpo?po_no="+po_no+"&buyer_id="+buyer_id+"&currency_id="+currency_id+"&production_area_id="+production_area_id);
		data.then(function (response) {
			$('#soembposearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
}


window.MsSoEmb=new MsSoEmbController(new MsSoEmbModel());
MsSoEmb.showGrid();
MsSoEmb.showsoembpoGrid([]);
 $('#subinbworkrcvtabs').tabs({
	onSelect:function(title,index){
	 let so_emb_id = $('#soembFrm  [name=id]').val();
	 let production_area_id = $('#soembFrm  [name=production_area_id]').val();
	 var data={};
	 data.so_emb_id=so_emb_id;
	 data.production_area_id=production_area_id;
	 if(index==1){
		 if(so_emb_id===''){
			 $('#subinbworkrcvtabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		 $('#soembitemFrm  [name=so_emb_id]').val(so_emb_id);
		 if (production_area_id==45) {
			$('#soembitemFrm  [name=embelishment_id]').val(1);
			MsSoEmbItem.embnameChange(1); //embelishment_id=printing
		 }
		 if (production_area_id==50) {
			$('#soembitemFrm  [name=embelishment_id]').val(21);
			MsSoEmbItem.embnameChange(21); //embelishment_id=embroydary
		 }
		 MsSoEmbItem.get(so_emb_id);
	 }
	 if(index==2){
		if(so_emb_id===''){
			$('#subinbworkrcvtabs').tabs('select',0);
			msApp.showError('Select a Start Up First',0);
			return;
		 }
		$('#soembfileFrm  [name=so_emb_id]').val(so_emb_id);
		MsSoEmbFile.showGrid(so_emb_id);
	}
}
}); 
