let MsSoKnitYarnRcvItemModel = require('./MsSoKnitYarnRcvItemModel');
require('./../../datagrid-filter.js');
class MsSoKnitYarnRcvItemController {
	constructor(MsSoKnitYarnRcvItemModel)
	{
		this.MsSoKnitYarnRcvItemModel = MsSoKnitYarnRcvItemModel;
		this.formId='soknityarnrcvitemFrm';
		this.dataTable='#soknityarnrcvitemTbl';
		this.route=msApp.baseUrl()+"/soknityarnrcvitem"
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
		let so_knit_yarn_rcv_id = $('#soknityarnrcvFrm  [name=id]').val();
		formObj.so_knit_yarn_rcv_id=so_knit_yarn_rcv_id;
		if(formObj.id){
			this.MsSoKnitYarnRcvItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoKnitYarnRcvItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoKnitYarnRcvItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoKnitYarnRcvItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		MsSoKnitYarnRcvItem.get(d.so_knit_yarn_rcv_id)
		msApp.resetForm('soknityarnrcvitemFrm');
					$('#soknityarnrcvitemFrm [id="uom_id"]').combobox('setValue', '');

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoKnitYarnRcvItemModel.get(index,row);
		workReceive.then(function(response){
			$('#soknityarnrcvitemFrm [id="uom_id"]').combobox('setValue', response.data.fromData.uom_id);
		}).catch(function(error){
			console.log(errors);
		});
	}

	get(so_knit_yarn_rcv_id)
	{
		let data= axios.get(this.route+"?so_knit_yarn_rcv_id="+so_knit_yarn_rcv_id);
		data.then(function (response) {
			$('#soknityarnrcvitemTbl').datagrid('loadData', response.data);
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
				var tAmount=0;
				
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmount+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{ 
					qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					amount: tAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSoKnitYarnRcvItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	

	itemWindow(){
		$('#soknityarnrcvitemWindow').window('open');
	}
	itemGrid(data){
		let self = this;
		$('#soknityarnrcvitemsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#soknityarnrcvitemFrm [name=item_account_id]').val(row.id);
				$('#soknityarnrcvitemFrm [name=count]').val(row.count);
				$('#soknityarnrcvitemFrm [name=item_description]').val(row.composition_name);
				$('#soknityarnrcvitemWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	getitem()
	{
		let count_name=$('#soknityarnrcvitemsearchFrm  [name=count_name]').val();
		let type_name=$('#soknityarnrcvitemsearchFrm  [name=type_name]').val();
		//let buyer_id=$('#soknityarnrcvFrm  [name=buyer_id]').val();
		let data= axios.get(this.route+"/getitem?type_name="+type_name+'&count_name='+count_name);
		data.then(function (response) {
			$('#soknityarnrcvitemsearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
	calculate()
	{
		let qty = $('#soknityarnrcvitemFrm  [name=qty]').val();
		let rate = $('#soknityarnrcvitemFrm  [name=rate]').val();
		let amount=qty*rate;
		$('#soknityarnrcvitemFrm  [name=amount]').val(amount);
	}
}
window.MsSoKnitYarnRcvItem=new MsSoKnitYarnRcvItemController(new MsSoKnitYarnRcvItemModel());
MsSoKnitYarnRcvItem.showGrid([]);
MsSoKnitYarnRcvItem.itemGrid([]);
 
