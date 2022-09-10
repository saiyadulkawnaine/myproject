let MsSoDyeingBomFabricModel = require('./MsSoDyeingBomFabricModel');
require('./../../datagrid-filter.js');
class MsSoDyeingBomFabricController {
	constructor(MsSoDyeingBomFabricModel)
	{
		this.MsSoDyeingBomFabricModel = MsSoDyeingBomFabricModel;
		this.formId='sodyeingbomfabricFrm';
		this.dataTable='#sodyeingbomfabricTbl';
		this.route=msApp.baseUrl()+"/sodyeingbomfabric"
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
		let so_dyeing_bom_id = $('#sodyeingbomFrm  [name=id]').val();	
		let formObj=msApp.get(this.formId);
		formObj.so_dyeing_bom_id=so_dyeing_bom_id;
		if(formObj.id){
			this.MsSoDyeingBomFabricModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoDyeingBomFabricModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoDyeingBomFabricModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoDyeingBomFabricModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#sodyeingbomfabricWindow').window('close');
		MsSoDyeingBomFabric.get(d.so_dyeing_bom_id)
		msApp.resetForm('sodyeingbomfabricFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoDyeingBomFabricModel.get(index,row);
		workReceive.then(function(response){
		}).catch(function(error){
			console.log(errors)
		});
	}

	get(so_dyeing_bom_id)
	{
		let data= axios.get(this.route+"?so_dyeing_bom_id="+so_dyeing_bom_id);
		data.then(function (response) {
			$('#sodyeingbomfabricTbl').datagrid('loadData', response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsSoDyeingBomFabric.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	

	/*import(){
		$('#sodyeingbomfabricWindow').window('open');
	}*/
	sodyeingbomfabricsearchGrid(data){
		let self = this;
		$('#sodyeingbomfabricsearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#sodyeingbomfabricFrm [name=so_dyeing_ref_id]').val(row.id);
				$('#sodyeingbomfabricFrm [name=fabrication]').val(row.fabrication);
				$('#sodyeingbomfabricFrm [name=gmtspart]').val(row.gmtspart);
				$('#sodyeingbomfabricFrm [name=fabriclooks]').val(row.fabriclooks);
				$('#sodyeingbomfabricFrm [name=fabricshape]').val(row.fabricshape);
				$('#sodyeingbomfabricFrm [name=gsm_weight]').val(row.gsm_weight);
				$('#sodyeingbomfabricFrm [name=colorrange_id]').val(row.colorrange_id);
				$('#sodyeingbomfabricFrm [name=dyeingtype]').val(row.dyeingtype);
				$('#sodyeingbomfabricFrm [name=fabric_wgt]').val(row.qty);
				$('#sodyeingbomfabricFrm [name=order_val]').val(row.amount);
				$('#sodyeingbomfabricWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	import()
	{
		//$('#sodyeingbomfabricWindow').window('open');
		let so_dyeing_id=$('#sodyeingbomFrm  [name=so_dyeing_id]').val();
		let data= axios.get(this.route+"/getfabric?so_dyeing_id="+so_dyeing_id);
		data.then(function (response) {
			$('#sodyeingbomfabricsearchTbl').datagrid('loadData', response.data);
			$('#sodyeingbomfabricWindow').window('open');

		})
		.catch(function (error) {
			console.log(error);
		});
	}

	calculate(iteration,count){
		let fabric_wgt=$('#sodyeingbomfabricFrm  [name=fabric_wgt]').val();
		let liqure_ratio=$('#sodyeingbomfabricFrm  [name=liqure_ratio]').val();
		let liqure_wgt=msApp.multiply(fabric_wgt,liqure_ratio);
		$('#sodyeingbomfabricFrm  [name=liqure_wgt]').val(liqure_wgt);
	}
	
}
window.MsSoDyeingBomFabric=new MsSoDyeingBomFabricController(new MsSoDyeingBomFabricModel());
MsSoDyeingBomFabric.showGrid([]);
MsSoDyeingBomFabric.sodyeingbomfabricsearchGrid([]);
//MsSoDyeingBomFabric.sodyeingbomfabricsoGrid([]);