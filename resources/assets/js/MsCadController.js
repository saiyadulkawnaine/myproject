//require('./jquery.easyui.min.js');
require('./datagrid-filter.js');
let MsCadModel = require('./MsCadModel');
class MsCadController {
    constructor(MsCadModel)
    {
        this.MsCadModel = MsCadModel;
        this.formId='cadFrm';
        this.dataTable='#cadTbl';
        this.route=msApp.baseUrl()+"/cad"
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
            this.MsCadModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
        }else{
            this.MsCadModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
        }
    }

  	resetForm ()
  	{
  		msApp.resetForm(this.formId);
  	}

    remove()
    {
        let formObj=msApp.get(this.formId);
        this.MsCadModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
    }

    delete(event,id)
    {
        event.stopPropagation()
        this.MsCadModel.save(this.route+"/"+id,'DELETE',null,this.response);
    }

    response(d)
    {
        $('#cadTbl').datagrid('reload');
        //$('#KeycontrolFrm  [name=id]').val(d.id);
        msApp.resetForm('cadFrm');
    }

    edit(index,row)
    {
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsCadModel.get(index,row);
		data.then(function (response) {
			/*$('#dg').datagrid({
				idField:'id',
				data: response.data.extra,
				columns:[[
					{field:'stylegmts',title:'Style GMT',width:150},
					{field:'gmtspart',title:'GMT Part',width:150},
					{field:'fabricnature',title:'Fabric Nature',width:80},
					{field:'fabriclooks',title:'Fabric Looks',width:80},
					{field:'fabricshape',title:'Fabric Shape',width:80},
					{field:'fabrication',title:'Fabrication',width:180},
					{field:'name',title:'Size',width:60},
					{field:'uom_name',title:'UOM',width:40},
					{field:'qty',title:'Qty',width:60,align:'right',editor:'numberbox'},
					{field:'action',title:'Action',width:80,align:'center',
						formatter:function(value,row,index){
							if (row.editing){
								var s = '<a href="javascript:void(0)" onclick="MsCad.saverow(this)">Save</a> ';
								var c = '<a href="javascript:void(0)" onclick="MsCad.cancelrow(this)">Cancel</a>';
								return s+c;
							} else {
								var e = '<a href="javascript:void(0)" onclick="MsCad.editrow(this)">Edit</a> ';
								var d = '<a href="javascript:void(0)" onclick="MsCad.deleterow(this)">Delete</a>';
								return e+d;
							}
						}
					}
				]],
				onEndEdit:function(index,row,changes){
				},
				onBeforeEdit:function(index,row){
					row.editing = true;
					$(this).datagrid('refreshRow', index);
				},
				onAfterEdit:function(index,row,changes){
					row.editing = false;
					$(this).datagrid('refreshRow', index);
					let formObj={};
					formObj.id=row.id;
					formObj.cad_id=row.cad_id;
					formObj.style_fabrication_id=row.style_fabrication_id;
					formObj.style_size_id=row.style_size_id;
					formObj.cons=row.qty;
					//alert(msApp.qs.stringify(formObj));
				},
				onCancelEdit:function(index,row){
					row.editing = false;
					$(this).datagrid('refreshRow', index);
				}
			});*/
		})
		.catch(function (error) {
			console.log(error);
		});
    }
	getRowIndex(target){
		var tr = $(target).closest('tr.datagrid-row');
		return parseInt(tr.attr('datagrid-row-index'));
	}
	editrow(target){
		$('#dg').datagrid('beginEdit', this.getRowIndex(target));
	}
	deleterow(target){
		let self=this;
		$.messager.confirm('Confirm','Are you sure?',function(r){
			if (r){
			$('#dg').datagrid('deleteRow', self.getRowIndex(target));
			}
		});
	}
	saverow(target){
		$('#dg').datagrid('endEdit', this.getRowIndex(target));
	}
	cancelrow(target){
		$('#dg').datagrid('cancelEdit', this.getRowIndex(target));
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

    openStyleWindow()
    {
        $('#cadw').window('open');

    }

      getStyle(){
            let data={};
            data.buyer_id = $('#cadstylesearch  [name=buyer_id]').val();
            data.style_ref = $('#cadstylesearch  [name=style_ref]').val();
            data.style_description = $('#cadstylesearch  [name=style_description]').val();

            let d= axios.get(msApp.baseUrl()+"/style/getoldstyle",{data})
            .then(function (response) {
            $('#cadstyleTbl').datagrid('loadData', response.data);
            })
            .catch(function (error) {
            console.log(error);
            });
    }

      showStyleGrid(data)
    	{
    		//let data={};
    		//data.buyer_id = $('#cadstylesearch  [name=buyer_id]').val();
    		//data.style_ref = $('#cadstylesearch  [name=style_ref]').val();
    		//data.style_description = $('#cadstylesearch  [name=style_description]').val();
    		let self=this;
    		$('#cadstyleTbl').datagrid({
    			//method:'get',
    			border:false,
    			singleSelect:true,
    			fit:true,
    			//queryParams:data,
    			//url:msApp.baseUrl()+"/style",
    			onClickRow: function(index,row){
    				$('#cadFrm  [name=style_id]').val(row.id);
    				$('#cadFrm  [name=style_ref]').val(row.style_ref);
    				$('#cadFrm  [name=buyer_id]').val(row.buyer_id);
    				$('#cadw').window('close')
    			}
    		}).datagrid('enableFilter').datagrid('loadData', data);
    	}

    formatDetail(value,row)
    {
        return '<a href="javascript:void(0)"  onClick="MsCad.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
    }
}
window.MsCad=new MsCadController(new MsCadModel());
MsCad.showGrid();
MsCad.showStyleGrid([]);
