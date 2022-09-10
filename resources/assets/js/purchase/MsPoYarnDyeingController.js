require('./../datagrid-filter.js');
let MsPoYarnDyeingModel = require('./MsPoYarnDyeingModel');
class MsPoYarnDyeingController {
	constructor(MsPoYarnDyeingModel)
	{
		this.MsPoYarnDyeingModel = MsPoYarnDyeingModel;
		this.formId='poyarndyeingFrm';
		this.dataTable='#poyarndyeingTbl';
		this.route=msApp.baseUrl()+"/poyarndyeing"
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
			this.MsPoYarnDyeingModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoYarnDyeingModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#poyarndyeingFrm [id="supplier_id"]').combobox('setValue','');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPoYarnDyeingModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPoYarnDyeingModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#poyarndyeingTbl').datagrid('reload');
		msApp.resetForm('poyarndyeingFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let poyarndye=this.MsPoYarnDyeingModel.get(index,row);
		poyarndye.then(function(response){
			$('#poyarndyeingFrm [id="supplier_id"]').combobox('setValue',response.data.fromData.supplier_id);
		})
		.catch(function(error){
			console.log(error);
		})
	}

	showGrid()
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			showFooter:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data){
				var tQty=0;
				var tAmout=0;
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['item_qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}			
				
				$(this).datagrid('reloadFooter', [
					{ 
						item_qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);
				
				
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsPoYarnDyeing.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	pdf(){
		var id= $('#poyarndyeingFrm  [name=id]').val();
		if(id==""){
			alert("Select an Order");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}
}
window.MsPoYarnDyeing=new MsPoYarnDyeingController(new MsPoYarnDyeingModel());
MsPoYarnDyeing.showGrid();

$('#poyarndyeingtabs').tabs({
	onSelect:function(title,index){
		let po_yarn_dyeing_id = $('#poyarndyeingFrm  [name=id]').val();
		if(index==1){
			if(po_yarn_dyeing_id===''){
				$('#poyarndyeingtabs').tabs('select',0);
				msApp.showError('Select Purchase Order First',0);
				return;
			}
			msApp.resetForm('poyarndyeingitemFrm');
			$('#poyarndyeingitemFrm [name=po_yarn_dyeing_id]').val(po_yarn_dyeing_id);
			MsPoYarnDyeingItem.get(po_yarn_dyeing_id);
		}
		if(index==2){
			let po_yarn_dyeing_item_id=$('#poyarndyeingitemFrm  [name=id]').val();
			msApp.resetForm('poyarndyeingitembomqtyFrm');
			if(po_yarn_dyeing_item_id===''){
				$('#poyarndyeingtabs').tabs('select',1);
				msApp.showError('Select Yarn First',0);
				return;
			}
			MsPoYarnDyeingItemBomQty.get(po_yarn_dyeing_item_id);
		}
		if(index==3){
			if(po_yarn_dyeing_id===''){
				$('#poyarndyeingtabs').tabs('select',0);
				msApp.showError('Select Purchase Order First',0);
				return;
			}
			$('#purchasetermsconditionFrm  [name=purchase_order_id]').val(po_yarn_dyeing_id)
			$('#purchasetermsconditionFrm  [name=menu_id]').val(9)
			MsPurchaseTermsCondition.get();
		}
	}
})