let MsSoDyeingFabricRcvInhItemModel = require('./MsSoDyeingFabricRcvInhItemModel');
require('./../../datagrid-filter.js');
class MsSoDyeingFabricRcvInhItemController {
	constructor(MsSoDyeingFabricRcvInhItemModel)
	{
		this.MsSoDyeingFabricRcvInhItemModel = MsSoDyeingFabricRcvInhItemModel;
		this.formId='sodyeingfabricrcvinhitemFrm';
		this.dataTable='#sodyeingfabricrcvinhitemTbl';
		this.route=msApp.baseUrl()+"/sodyeingfabricrcvinhitem"
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
			this.MsSoDyeingFabricRcvInhItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoDyeingFabricRcvInhItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	submitBatch()
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
		let so_dyeing_fabric_rcv_id=$('#sodyeingfabricrcvinhFrm [name=id]').val();
		let so_dyeing_ref_id=MsSoDyeingFabricRcvInhItem.getSelections();
		let formObj={};
		formObj.so_dyeing_fabric_rcv_id=so_dyeing_fabric_rcv_id;
		formObj.so_dyeing_ref_id=so_dyeing_ref_id;

		if(formObj.id){
			this.MsSoDyeingFabricRcvInhItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSoDyeingFabricRcvInhItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
		

	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoDyeingFabricRcvInhItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoDyeingFabricRcvInhItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#sodyeingfabricrcvitemWindow').window('close');
		MsSoDyeingFabricRcvInhItem.get(d.so_dyeing_fabric_rcv_id)
		msApp.resetForm('sodyeingfabricrcvinhitemFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoDyeingFabricRcvInhItemModel.get(index,row);
		workReceive.then(function(response){
		}).catch(function(error){
			console.log(errors)
		});
	}

	get(so_dyeing_fabric_rcv_id)
	{
		let data= axios.get(this.route+"?so_dyeing_fabric_rcv_id="+so_dyeing_fabric_rcv_id);
		data.then(function (response) {
			$('#sodyeingfabricrcvinhitemTbl').datagrid('loadData', response.data);
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
			showFooter:true,
			//fitColumns:true,
			//url:this.route,
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
		return '<a href="javascript:void(0)"  onClick="MsSoDyeingFabricRcvInhItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	

	/*import(){
		$('#sodyeingfabricrcvitemWindow').window('open');
	}*/
	sodyeingfabricrcvitemsearchGrid(data){
		let self = this;
		$('#sodyeingfabricrcvinhitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	import()
	{
		let so_dyeing_fabric_rcv_id=$('#sodyeingfabricrcvinhFrm  [name=id]').val();
		let data= axios.get(this.route+"/create?so_dyeing_fabric_rcv_id="+so_dyeing_fabric_rcv_id);
		data.then(function (response) {
			//$('#sodyeingfabricrcvitemWindowscsinh').html(response.data);
			$('#sodyeingfabricrcvinhitemsearchTbl').datagrid('loadData', response.data);
			$('#sodyeingfabricrcviteminhWindow').window('open');

		})
		.catch(function (error) {
			console.log(error);
		});
	}

	/*calculate(iteration,count){
		let qty=$('#sodyeingfabricrcvitemmatrixFrm input[name="qty['+iteration+']"]').val();
		let rate=$('#sodyeingfabricrcvitemmatrixFrm input[name="rate['+iteration+']"]').val();
		let amount=msApp.multiply(qty,rate);
		$('#sodyeingfabricrcvitemmatrixFrm input[name="amount['+iteration+']"]').val(amount)
	}
	calculate_form(){
		
		let qty=$('#sodyeingfabricrcvinhitemFrm  [name=qty]').val();
		let rate=$('#sodyeingfabricrcvinhitemFrm  [name=rate]').val();
		let amount=msApp.multiply(qty,rate);
		$('#sodyeingfabricrcvinhitemFrm  [name=amount]').val(amount);
	}*/

	getSelections(){
		let so_dyeing_ref_id=[];
		let checked=$('#sodyeingfabricrcvinhitemsearchTbl').datagrid('getSelections');
		if(checked.lenght >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
			so_dyeing_ref_id.push(val.so_dyeing_ref_id)
		});
		so_dyeing_ref_id=so_dyeing_ref_id.join(',');
		$('#sodyeingfabricrcvinhitemsearchTbl').datagrid('clearSelections');
		MsSoDyeingFabricRcvInhItem.sodyeingfabricrcvitemsearchGrid([]);
		$('#sodyeingfabricrcviteminhWindow').window('close');
		return so_dyeing_ref_id;
	}
}
window.MsSoDyeingFabricRcvInhItem=new MsSoDyeingFabricRcvInhItemController(new MsSoDyeingFabricRcvInhItemModel());
MsSoDyeingFabricRcvInhItem.showGrid([]);
MsSoDyeingFabricRcvInhItem.sodyeingfabricrcvitemsearchGrid([]);