let MsInvYarnRcvItemModel = require('./MsInvYarnRcvItemModel');

class MsInvYarnRcvItemController {
	constructor(MsInvYarnRcvItemModel)
	{
		this.MsInvYarnRcvItemModel = MsInvYarnRcvItemModel;
		this.formId='invyarnrcvitemFrm';	             
		this.dataTable='#invyarnrcvitemTbl';
		this.route=msApp.baseUrl()+"/invyarnrcvitem"
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
		let inv_rcv_id=$('#invyarnrcvFrm [name=id]').val()
		let inv_yarn_rcv_id=$('#invyarnrcvFrm [name=inv_yarn_rcv_id]').val();
		let formObj=msApp.get(this.formId);
		formObj.inv_yarn_rcv_id=inv_yarn_rcv_id;
		formObj.inv_rcv_id=inv_rcv_id;
		if(formObj.id){
			this.MsInvYarnRcvItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvYarnRcvItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
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
		let inv_rcv_id=$('#invyarnrcvFrm [name=id]').val()
		let inv_yarn_rcv_id=$('#invyarnrcvFrm [name=inv_yarn_rcv_id]').val()
		let formObj=msApp.get('invyarnrcvitemmatrixFrm');

		formObj.inv_rcv_id=inv_rcv_id;
		formObj.inv_yarn_rcv_id=inv_yarn_rcv_id;
		if(formObj.id){
			this.MsInvYarnRcvItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvYarnRcvItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
		

	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvYarnRcvItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvYarnRcvItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		
		MsInvYarnRcvItem.resetForm();
		MsInvYarnRcvItem.get(d.inv_yarn_rcv_id)
		$('#invyarnrcvitemWindow').window('close');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsInvYarnRcvItemModel.get(index,row);

	}
	get(inv_yarn_rcv_id){
		let params={};
		params.inv_yarn_rcv_id=inv_yarn_rcv_id;
		let d=axios.get(this.route,{params})
		.then(function(response){
			$('#invyarnrcvitemTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})

	}

	showGrid(data){
		let self=this;
		$(this.dataTable).datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsInvYarnRcvItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	import()
	{
		MsInvYarnRcvItem.itemSearchGrid([]);
		$('#openinvyarnrcvitemwindow').window('open');

	}

	itemSearchGrid(data){
		let self=this;
		$('#invyarnrcvitemsearchTbl').datagrid({
			border:false,
			singleSelect:false,
			fit:true,
			onClickRow: function(index,row){
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	serachItem(){
		let po_no=$('#invyarnrcvitemsearchFrm [name=po_no]').val();
		let pi_no=$('#invyarnrcvitemsearchFrm [name=pi_no]').val();
		let inv_rcv_id=$('#invyarnrcvFrm [name=id]').val();
		let supplier_id=$('#invyarnrcvFrm [name=supplier_id]').val();
		let params={};
		params.po_no=po_no;
		params.pi_no=pi_no;
		params.inv_rcv_id=inv_rcv_id;
		params.supplier_id=supplier_id;
		let d=axios.get(this.route+'/getyarnitem',{params})
		.then(function(response){
			$('#invyarnrcvitemsearchTbl').datagrid('loadData',response.data);
		}).catch(function(error){
			console.log(error);
		})
	}

	closeinvyarnrcvitemWindow()
	{
		let inv_rcv_id=$('#invyarnrcvFrm  [name=id]').val();
		let inv_yarn_rcv_id=$('#invyarnrcvFrm  [name=inv_yarn_rcv_id]').val();
		/*let item_account_id=[];
		let name=[];
		let checked=$('#poyarnitemsearchTbl').datagrid('getSelections');

		if(checked.lenght >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
			item_account_id.push(val.id)
		});
		item_account_id=item_account_id.join(',');
		$('#poyarnitemsearchTbl').datagrid('clearSelections');
		$('#poyarnitemimportWindow').window('close');*/
		let po_yarn_item_id=this.getSelection();

		let data= axios.get(this.route+"/create"+"?po_yarn_item_id="+po_yarn_item_id+'&inv_yarn_rcv_id='+inv_yarn_rcv_id)
		.then(function (response) {
			$('#invyarnrcvitemscs').html(response.data);
			$('#invyarnrcvitemWindow').window('open');
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	getSelection(){
		let po_yarn_item_id=[];
		let name=[];
		let checked=$('#invyarnrcvitemsearchTbl').datagrid('getSelections');
		if(checked.lenght >100 ){
			alert("More Than 100 checked not allowed");
			return;
		}
		$.each(checked, function (idx, val) {
			po_yarn_item_id.push(val.po_yarn_item_id)
		});
		po_yarn_item_id=po_yarn_item_id.join(',');
		$('#invyarnrcvitemsearchTbl').datagrid('clearSelections');
		$('#openinvyarnrcvitemwindow').window('close');
		return po_yarn_item_id;

	}
	calculate_qty(iteration,count)
	{
		let cone_per_bag=$('#invyarnrcvitemmatrixFrm input[name="cone_per_bag['+iteration+']"]').val();
		let wgt_per_cone=$('#invyarnrcvitemmatrixFrm input[name="wgt_per_cone['+iteration+']"]').val();
		let no_of_bag=$('#invyarnrcvitemmatrixFrm input[name="no_of_bag['+iteration+']"]').val();
		if(Number.isInteger(no_of_bag*1)==false){
              alert('Decimal not allowed in no of bag');
              $('#invyarnrcvitemmatrixFrm input[name="no_of_bag['+iteration+']"]').val('')
              return;
		}
		wgt_per_cone=wgt_per_cone*1;
		wgt_per_cone=wgt_per_cone.toFixed(4);
		$('#invyarnrcvitemmatrixFrm input[name="wgt_per_cone['+iteration+']"]').val(wgt_per_cone)
		let qty=cone_per_bag*wgt_per_cone*no_of_bag;
		let rate=$('#invyarnrcvitemmatrixFrm input[name="rate['+iteration+']"]').val();
		let amount=qty*rate;
		$('#invyarnrcvitemmatrixFrm input[name="qty['+iteration+']"]').val(qty);
		$('#invyarnrcvitemmatrixFrm input[name="amount['+iteration+']"]').val(amount);
	}

	calculate_qty_form(iteration,count)
	{
		let cone_per_bag=$('#invyarnrcvitemFrm input[name=cone_per_bag]').val();
		let wgt_per_cone=$('#invyarnrcvitemFrm input[name=wgt_per_cone]').val();
		let no_of_bag=$('#invyarnrcvitemFrm input[name=no_of_bag]').val();
		if(Number.isInteger(no_of_bag*1)==false){
              alert('Decimal not allowed in no of bag');
              $('#invyarnrcvitemFrm input[name=no_of_bag]').val('')
              return;
		}
		wgt_per_cone=wgt_per_cone*1;
		wgt_per_cone=wgt_per_cone.toFixed(4);
		$('#invyarnrcvitemFrm input[name=wgt_per_cone]').val(wgt_per_cone);

		let qty=cone_per_bag*wgt_per_cone*no_of_bag;
		let rate=$('#invyarnrcvitemFrm input[name=rate]').val();
		let amount=qty*rate;
		$('#invyarnrcvitemFrm input[name=qty]').val(qty);
		$('#invyarnrcvitemFrm input[name=amount]').val(amount);
	}

	openyarnWindow(id)
	{
		$('#invyarnrcvlibraryitemserchWindow').window('open');
	}

	searchYarn(){
		let yarn_count=$('#invyarnrcvlibraryitemserchFrm  [name=yarn_count]').val();
		let yarn_type=$('#invyarnrcvlibraryitemserchFrm  [name=yarn_type]').val();
		let data= axios.get(this.route+"/importyarn"+"?yarn_count="+yarn_count+"&yarn_type="+yarn_type)
		.then(function (response) {
			$('#invyarnrcvlibraryitemserchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	yarnSearchGrid(data)
	{
		var dg = $('#invyarnrcvlibraryitemserchTbl');
		dg.datagrid({
			border:false,
			fit:true,
			singleSelect:true,
			idField:"id",
			rownumbers:true,
			emptyMsg:'No Record Found',
			onClickRow: function(index,row){
				$('#invyarnrcvitemFrm  [name=composition]').val(row.composition);
				$('#invyarnrcvitemFrm  [name=item_account_id]').val(row.item_account_id);
				$('#invyarnrcvitemFrm  [name=itemcategory_name]').val(row.itemcategory_name);
				$('#invyarnrcvitemFrm  [name=itemclass_name]').val(row.itemclass_name);
				$('#invyarnrcvitemFrm  [name=yarn_count]').val(row.yarn_count);
				$('#invyarnrcvitemFrm  [name=yarn_type]').val(row.yarn_type);
				$('#invyarnrcvitemFrm  [name=uom]').val(row.uom);
				$('#invyarnrcvlibraryitemserchWindow').window('close');
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	addSoDetails(value,row){
		return '<a href="javascript:void(0)"  onClick="MsInvYarnRcvItem.openSoPopUp(event,'+row.id+','+row.rate+')"><span class="btn btn-warning btn-xs"><i class="fa fa-search"></i>Click</span></a>';
	}

	openSoPopUp(e,id,rate)
	{
		$('#invyarnrcvitemsosFrm  [name=inv_yarn_rcv_item_id]').val(id);
		$('#invyarnrcvitemsosFrm  [name=rate]').val(rate);
		MsInvYarnRcvItemSos.get(id);
		$('#invyarnrcvitemsoWindow').window('open');
	}

}
window.MsInvYarnRcvItem=new MsInvYarnRcvItemController(new MsInvYarnRcvItemModel());
MsInvYarnRcvItem.itemSearchGrid([]);
MsInvYarnRcvItem.showGrid([]);
MsInvYarnRcvItem.yarnSearchGrid([]);