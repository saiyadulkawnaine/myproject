let MsSoDyeingBomOverheadModel = require('./MsSoDyeingBomOverheadModel');
require('./../../datagrid-filter.js');
class MsSoDyeingBomOverheadController {
	constructor(MsSoDyeingBomOverheadModel)
	{
		this.MsSoDyeingBomOverheadModel = MsSoDyeingBomOverheadModel;
		this.formId='sodyeingbomoverheadFrm';
		this.dataTable='#sodyeingbomoverheadTbl';
		this.route=msApp.baseUrl()+"/sodyeingbomoverhead"
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
			this.MsSoDyeingBomOverheadModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoDyeingBomOverheadModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#sodyeingbomoverheadFrm [id="acc_chart_ctrl_head_id"]').combobox('setValue', '');
		let order_val = $('#sodyeingbomFrm  [name=order_val]').val();
		$('#sodyeingbomoverheadFrm  [name=order_val]').val(order_val);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoDyeingBomOverheadModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoDyeingBomOverheadModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#sodyeingbomoverheadWindow').window('close');
		MsSoDyeingBomOverhead.get(d.so_dyeing_bom_id)
		MsSoDyeingBomOverhead.resetForm();
		//$('#sodyeingbomoverheadFrm [id="acc_chart_ctrl_head_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoDyeingBomOverheadModel.get(index,row);
		workReceive.then(function(response){
		$('#sodyeingbomoverheadFrm [id="acc_chart_ctrl_head_id"]').combobox('setValue', response.data.fromData.acc_chart_ctrl_head_id);
		let order_val = $('#sodyeingbomFrm  [name=order_val]').val();
		$('#sodyeingbomoverheadFrm  [name=order_val]').val(order_val);

		}).catch(function(error){
			console.log(errors)
		});
	}

	get(so_dyeing_bom_id)
	{
		let data= axios.get(this.route+"?so_dyeing_bom_id="+so_dyeing_bom_id);
		data.then(function (response) {
			$('#sodyeingbomoverheadTbl').datagrid('loadData', response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsSoDyeingBomOverhead.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	getCost()
	{
		let acc_chart_ctrl_head_id=$('#sodyeingbomoverheadFrm [name=acc_chart_ctrl_head_id]').val();
		//let acc_chart_ctrl_head_id=$('#sodyeingbomoverheadFrm [id="acc_chart_ctrl_head_id"]').combobox('getValue');
		let cost_per=$('#sodyeingbomoverheadFrm input[name=cost_per]').val();
		let order_val=$('#sodyeingbomFrm input[name=order_val]').val();
		let company_id=$('#sodyeingbomFrm [name=company_id]').val();
		let params={};
		params.acc_chart_ctrl_head_id=acc_chart_ctrl_head_id;
		params.company_id=company_id;
		let data= axios.get(this.route+"/getcost",{params});
		data.then(function (response) {
			$('#sodyeingbomoverheadFrm input[name=cost_per]').val(response.data);
			MsSoDyeingBomOverhead.calculate();
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	calculate(){
		let cost_per=$('#sodyeingbomoverheadFrm input[name=cost_per]').val();
		let order_val=$('#sodyeingbomFrm input[name=order_val]').val();
		let cost =(order_val*1)*((cost_per*1)/100);
		$('#sodyeingbomoverheadFrm input[name=amount]').val(cost);
	}
	
}
window.MsSoDyeingBomOverhead=new MsSoDyeingBomOverheadController(new MsSoDyeingBomOverheadModel());
MsSoDyeingBomOverhead.showGrid([]);
