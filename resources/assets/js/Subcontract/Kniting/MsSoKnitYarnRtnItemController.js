let MsSoKnitYarnRtnItemModel = require('./MsSoKnitYarnRtnItemModel');
require('./../../datagrid-filter.js');
class MsSoKnitYarnRtnItemController {
	constructor(MsSoKnitYarnRtnItemModel)
	{
		this.MsSoKnitYarnRtnItemModel = MsSoKnitYarnRtnItemModel;
		this.formId='soknityarnrtnitemFrm';
		this.dataTable='#soknityarnrtnitemTbl';
		this.route=msApp.baseUrl()+"/soknityarnrtnitem"
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
		let so_knit_yarn_rtn_id = $('#soknityarnrtnFrm  [name=id]').val();
		formObj.so_knit_yarn_rtn_id=so_knit_yarn_rtn_id;
		if(formObj.id){
			this.MsSoKnitYarnRtnItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoKnitYarnRtnItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoKnitYarnRtnItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoKnitYarnRtnItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsSoKnitYarnRtnItem.get(d.so_knit_yarn_rtn_id)
		msApp.resetForm('soknityarnrtnitemFrm');
					//$('#soknityarnrtnitemFrm [id="uom_id"]').combobox('setValue', '');

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoKnitYarnRtnItemModel.get(index,row);
		workReceive.then(function(response){
			//$('#soknityarnrtnitemFrm [id="uom_id"]').combobox('setValue', response.data.fromData.uom_id);
		}).catch(function(error){
			console.log(errors);
		});
	}

	get(so_knit_yarn_rtn_id)
	{
		let data= axios.get(this.route+"?so_knit_yarn_rtn_id="+so_knit_yarn_rtn_id );
		data.then(function (response) {
			$('#soknityarnrtnitemTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(data)
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			//fitColumns:true,
			//url:this.route,
			showFooter:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var tQty=0;
				var tRate=0;
				var tAmount=0;
				
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tRate+=data.rows[i]['rate'].replace(/,/g,'')*1;
				tAmount+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSoKnitYarnRtnItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	soWindow(){
		$('#soknityarnrtnsoWindow').window('open');
	}
	soknityarnrtnsoGrid(data){
		let self = this;
		$('#soknityarnrtnitemsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#soknityarnrtnitemFrm [name=so_knit_id]').val(row.id);
				$('#soknityarnrtnitemFrm [name=sales_order_no]').val(row.sales_order_no);
				$('#soknityarnrtnitemFrm [name=company_id]').val(row.company_id);
				$('#soknityarnrtnitemFrm [name=buyer_id]').val(row.buyer_id);
				$('#soknityarnrtnsoWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	getrtnitem()
	{
		let so_no=$('#soknityarnrtnsosearchFrm  [name=so_no]').val();
		//let buyer_id=$('#soknityarnrcvFrm  [name=buyer_id]').val();
		let data= axios.get(this.route+"/getrtnitem?so_no="+so_no);
		data.then(function (response) {
			$('#soknityarnrtnitemsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	itemWindow(){
		$('#soknityarnrtnitemWindow').window('open');
	}
	itemGrid(data){
		let self = this;
		$('#soknityarnrtnitemsrchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#soknityarnrtnitemFrm [name=so_knit_yarn_rcv_item_id]').val(row.id);
				$('#soknityarnrtnitemFrm [name=count]').val(row.count);
				$('#soknityarnrtnitemFrm [name=item_description]').val(row.composition_name);
				$('#soknityarnrtnitemFrm [name=lot]').val(row.lot);
				$('#soknityarnrtnitemFrm [name=yarn_type]').val(row.name);
				$('#soknityarnrtnitemFrm [name=supplier_name]').val(row.supplier_name);
				$('#soknityarnrtnitemFrm [name=color_id]').val(row.color_id);
				$('#soknityarnrtnitemFrm [name=uom_id]').val(row.uom_id);
				$('#soknityarnrtnitemFrm [name=rate]').val(row.rate);
				$('#soknityarnrtnitemWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	getitem()
	{
		let count_name=$('#soknityarnrtnitemsearchFrm  [name=count_name]').val();
		let type_name=$('#soknityarnrtnitemsearchFrm  [name=type_name]').val();
		//let buyer_id=$('#soknityarnrcvFrm  [name=buyer_id]').val();
		let data= axios.get(this.route+"/getitem?type_name="+type_name+'&count_name='+count_name);
		data.then(function (response) {
			$('#soknityarnrtnitemsrchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}


	calculate()
	{
		let qty = $('#soknityarnrtnitemFrm  [name=qty]').val();
		let rate = $('#soknityarnrtnitemFrm  [name=rate]').val();
		let amount=qty*rate;
		$('#soknityarnrtnitemFrm  [name=amount]').val(amount);
	}
}
window.MsSoKnitYarnRtnItem=new MsSoKnitYarnRtnItemController(new MsSoKnitYarnRtnItemModel());
MsSoKnitYarnRtnItem.showGrid([]);
MsSoKnitYarnRtnItem.soknityarnrtnsoGrid([]);
MsSoKnitYarnRtnItem.itemGrid([]);

 
