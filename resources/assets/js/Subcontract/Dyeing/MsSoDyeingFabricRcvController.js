let MsSoDyeingFabricRcvModel = require('./MsSoDyeingFabricRcvModel');
require('./../../datagrid-filter.js');
class MsSoDyeingFabricRcvController {
	constructor(MsSoDyeingFabricRcvModel)
	{
		this.MsSoDyeingFabricRcvModel = MsSoDyeingFabricRcvModel;
		this.formId='sodyeingfabricrcvFrm';
		this.dataTable='#sodyeingfabricrcvTbl';
		this.route=msApp.baseUrl()+"/sodyeingfabricrcv"
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
			this.MsSoDyeingFabricRcvModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoDyeingFabricRcvModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#sodyeingfabricrcvFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoDyeingFabricRcvModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoDyeingFabricRcvModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#sodyeingfabricrcvTbl').datagrid('reload');
		msApp.resetForm('sodyeingfabricrcvFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoDyeingFabricRcvModel.get(index,row);
		workReceive.then(function(response){
			//$('#sodyeingfabricrcvFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
		}).catch(function(error){
			console.log(errors)
		});
	}

	showDyeingFabricReceive(){
		let params={};
		params.date_from=$('#date_from').val();
		params.date_to=$('#date_to').val();
		let sdg= axios.get(this.route+"/getdyeingfabricreceive",{params});
		sdg.then(function (response) {
			$('#sodyeingfabricrcvTbl').datagrid('loadData', response.data);
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
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSoDyeingFabricRcv.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	soWindow(){
		$('#sodyeingfabricrcvsoWindow').window('open');
	}
	
	sodyeingfabricrcvsoGrid(data){
		let self = this;
		$('#sodyeingfabricrcvsosearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#sodyeingfabricrcvFrm [name=so_dyeing_id]').val(row.id);
				$('#sodyeingfabricrcvFrm [name=sales_order_no]').val(row.sales_order_no);
				$('#sodyeingfabricrcvFrm [name=company_id]').val(row.company_id);
				$('#sodyeingfabricrcvFrm [name=buyer_id]').val(row.buyer_id);
				$('#sodyeingfabricrcvsoWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	getsubconorder()
	{
		let so_no=$('#sodyeingfabricrcvsosearchFrm  [name=so_no]').val();
		//let buyer_id=$('#sodyeingfabricrcvFrm  [name=buyer_id]').val();
		let data= axios.get(this.route+"/getso?so_no="+so_no);
		data.then(function (response) {
			$('#sodyeingfabricrcvsosearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
}
window.MsSoDyeingFabricRcv=new MsSoDyeingFabricRcvController(new MsSoDyeingFabricRcvModel());
MsSoDyeingFabricRcv.showGrid();
MsSoDyeingFabricRcv.sodyeingfabricrcvsoGrid([]);
$('#sodyeingfabricrcvtabs').tabs({
	onSelect:function(title,index){
		let so_dyeing_fabric_rcv_id = $('#sodyeingfabricrcvFrm  [name=id]').val();
		let so_dyeing_fabric_rcv_item_id = $('#sodyeingfabricrcvitemFrm  [name=id]').val();
		if(index==1){
			 if(so_dyeing_fabric_rcv_id===''){
				 $('#sodyeingfabricrcvtabs').tabs('select',0);
				 msApp.showError('Select a Start Up First',0);
				 return;
			  }
			 $('#sodyeingfabricrcvitemFrm  [name=so_dyeing_fabric_rcv_id]').val(so_dyeing_fabric_rcv_id);
			 MsSoDyeingFabricRcvItem.get(so_dyeing_fabric_rcv_id);
		}
		if(index==2){
			if(so_dyeing_fabric_rcv_item_id===''){
				$('#sodyeingfabricrcvtabs').tabs('select',1);
				msApp.showError('Select a Item First',0);
				return;
			 }
			 msApp.resetForm('sodyeingfabricrcvrolFrm')
			MsSoDyeingFabricRcvRol.get(so_dyeing_fabric_rcv_item_id);
		}
	}
});  
