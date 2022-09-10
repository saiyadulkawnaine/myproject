let MsPoYarnDyeingItemModel = require('./MsPoYarnDyeingItemModel');
class MsPoYarnDyeingItemController {
	constructor(MsPoYarnDyeingItemModel)
	{
		this.MsPoYarnDyeingItemModel = MsPoYarnDyeingItemModel;
		this.formId='poyarndyeingitemFrm';
		this.dataTable='#poyarndyeingitemTbl';
		this.route=msApp.baseUrl()+"/poyarndyeingitem"
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
			this.MsPoYarnDyeingItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPoYarnDyeingItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
		let po_yarn_dyeing_id=$('#poyarndyeingFrm  [name=id]').val();
		$('#poyarndyeingitemFrm [name=po_yarn_dyeing_id]').val(po_yarn_dyeing_id);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPoYarnDyeingItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPoYarnDyeingItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		//$('#poyarndyeingitemTbl').datagrid('reload');
		let po_yarn_dyeing_id=$('#poyarndyeingFrm  [name=id]').val();
		MsPoYarnDyeingItem.get(po_yarn_dyeing_id);
		msApp.resetForm(this.formId);
		//$('#poyarndyeingitemFrm [name=po_yarn_dyeing_id]').val($('#poyarndyeingFrm  [name=id]').val());

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPoYarnDyeingItemModel.get(index,row);	
	}

	get(po_yarn_dyeing_id)
	{
		let d = axios.get(this.route+"?po_yarn_dyeing_id="+po_yarn_dyeing_id)
		.then(function(response){
			$('#poyarndyeingitemTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});
	}

	showGrid(data)
	{
		let self=this;
		//let data={}
		//data.po_yarn_dyeing_id=po_yarn_dyeing_id;
		var pyi = $('#poyarndyeingitemTbl');
		pyi.datagrid({
			//method:'get',
			border:false,
			fit:true,
			singleSelect:true,
			rownumbers:true,
			showFooter: 'true',
			//queryParams:data,
			//url:this.route,
			emptyMsg:'No Record Found',
			onClickRow: function(index,row){
				self.edit(index,row);
			},
			onLoadSuccess: function(data)
			{
				var tQty=0;
				var tAmout=0;
				for(var i=0; i<data.rows.length; i++){
				tQty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				tAmout+=data.rows[i]['amount'].replace(/,/g,'')*1;
				}
				var tRate=0;
				
				if(tQty){
				   tRate=(tAmout/tQty);	
				}	
				$(this).datagrid('reloadFooter', [
					{ 
						qty: tQty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						amount: tAmout.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
						rate: tRate.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
					}
				]);

			}
		});
		pyi.datagrid('enableFilter').datagrid('loadData', data);
	}
	openYarnDyeInvYarnWindow()
	{
		$('#dyeinvyarnitemwindow').window('open');
	}

	getRcvYarnItemParams(){
		let params={}
		params.color_id=$('#yarndyeinvyarnitemsearchFrm [name=color_id]').val();
		params.brand=$('#yarndyeinvyarnitemsearchFrm [name=brand]').val();
		params.itemcategory_id=$('#yarndyeinvyarnitemsearchFrm [name=itemcategory_id]').val();
		return params;
	}

	searchYarnDyeRcvYarnItem(){
		let params=this.getRcvYarnItemParams();
		let d = axios.get(this.route+"/getinvrcvyarnitem",{params})
		.then(function(response){
			$('#yarndyeinvyarnitemsearchTbl').datagrid('loadData',response.data);
		})
		.catch(function(error){
			console.log(error);
		});
		
	}

	showYarnDyeRcvYarnItemGrid(data){
		let self=this;
		var ryt = $('#yarndyeinvyarnitemsearchTbl');
		ryt.datagrid({
			border:false,
			fit:true,
			singleSelect:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onClickRow: function(index,row){
				$('#poyarndyeingitemFrm  [name=inv_yarn_item_id]').val(row.id);
				$('#poyarndyeingitemFrm  [name=yarn_des]').val(row.yarn_des);
				$('#poyarndyeingitemFrm  [name=supplier_name]').val(row.supplier_name);
				$('#poyarndyeingitemFrm  [name=lot]').val(row.lot);
				$('#poyarndyeingitemFrm  [name=brand]').val(row.brand);
				$('#poyarndyeingitemFrm  [name=yarn_color_name]').val(row.yarn_color_name);
				$('#dyeinvyarnitemwindow').window('close');
			},
		});
		ryt.datagrid('enableFilter').datagrid('loadData', data);
	}

	

	openpoyarndyeitembomqtyWindow(id)
	{
         $('#poyarndyeqtywindow').window('open');
	}
	
}
window.MsPoYarnDyeingItem=new MsPoYarnDyeingItemController(new MsPoYarnDyeingItemModel());
MsPoYarnDyeingItem.showGrid([]);
MsPoYarnDyeingItem.showYarnDyeRcvYarnItemGrid([]);