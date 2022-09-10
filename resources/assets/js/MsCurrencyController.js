//require('./jquery.easyui.min.js');
let MsCurrencyModel = require('./MsCurrencyModel');
require('./datagrid-filter.js');

class MsCurrencyController {
	constructor(MsCurrencyModel)
	{
		this.MsCurrencyModel = MsCurrencyModel;
		this.formId='currencyFrm';
		this.dataTable='#currencyTbl';
		this.route=msApp.baseUrl()+"/currency"
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
			this.MsCurrencyModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsCurrencyModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsCurrencyModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsCurrencyModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#currencyTbl').datagrid('reload');
		//$('#CurrencyFrm  [name=id]').val(d.id);
		msApp.resetForm('currencyFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsCurrencyModel.get(index,row);
		$('#ccd').combotree('setValue', row.id);
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

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsCurrency.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	ct(data){
		$('#ccd').combotree({
		required: true,
		editable:true,
		enableFiltering:true,
		valueField:'id',
		textField:'text',
		filter: function(q, node){
			return MsCurrency.dfg(q, node);
        }
		}).combotree('loadData', data);
	}
	dfg(q, node){
		if (node.text.toLowerCase()== q.toLowerCase()){
			$('#ccd').combotree('setValue', node.id);
		}
		return node.text.toLowerCase().indexOf(q.toLowerCase()) > -1;
	}
}
window.MsCurrency=new MsCurrencyController(new MsCurrencyModel());
MsCurrency.showGrid();
MsCurrency.ct([{"id":1,"root_id":"0","text":"System","children":[{"id":2,"root_id":"1","text":"Navigation","children":[{"id":3,"root_id":"2","text":"Menu"},{"id":4,"root_id":"2","text":"Permission"}]},{"id":5,"root_id":"1","text":"User Accounts","children":[{"id":6,"root_id":"5","text":"Role"},{"id":7,"root_id":"5","text":"User"},{"id":128,"root_id":"5","text":"My Account"}]}]},{"id":8,"root_id":"0","text":"Utilities","children":[{"id":24,"root_id":"8","text":"Cost-centre","children":[{"id":9,"root_id":"24","text":"Group"},{"id":17,"root_id":"24","text":"Company"},{"id":18,"root_id":"24","text":"Region"},{"id":19,"root_id":"24","text":"Division"},{"id":20,"root_id":"24","text":"Department"},{"id":21,"root_id":"24","text":"Floor"},{"id":22,"root_id":"24","text":"Section"},{"id":23,"root_id":"24","text":"Sub-section"},{"id":25,"root_id":"24","text":"Country"},{"id":33,"root_id":"24","text":"Currency"},{"id":42,"root_id":"24","text":"Exchangerate"},{"id":57,"root_id":"24","text":"Profit Center"}]},{"id":65,"root_id":"8","text":"Item Structure","children":[{"id":41,"root_id":"65","text":"UOM"},{"id":46,"root_id":"65","text":"Item account"},{"id":49,"root_id":"65","text":"UOM Conversion"},{"id":66,"root_id":"65","text":"Item Category"},{"id":67,"root_id":"65","text":"Item Class"},{"id":70,"root_id":"65","text":"GMT Process Loss"},{"id":79,"root_id":"65","text":"Composition"},{"id":80,"root_id":"65","text":"Yarn count"},{"id":81,"root_id":"65","text":"Construction"},{"id":95,"root_id":"65","text":"Yarn Type"},{"id":117,"root_id":"65","text":"Fabric Description"}]},{"id":73,"root_id":"8","text":"Standards Setup","children":[{"id":75,"root_id":"73","text":"GMT Sample"},{"id":82,"root_id":"73","text":"Color Range"},{"id":83,"root_id":"73","text":"Fabric Process Loss"},{"id":89,"root_id":"73","text":"GMT Part"},{"id":90,"root_id":"73","text":"Color"},{"id":91,"root_id":"73","text":"Size"},{"id":92,"root_id":"73","text":"Production Process"},{"id":93,"root_id":"73","text":"Product Department"},{"id":94,"root_id":"73","text":"Season"},{"id":96,"root_id":"73","text":"Wage Variable"},{"id":97,"root_id":"73","text":"Resource"},{"id":98,"root_id":"73","text":"Attachment"},{"id":99,"root_id":"73","text":"Operation"},{"id":105,"root_id":"73","text":"Incentives"},{"id":106,"root_id":"73","text":"Smv Chart"},{"id":108,"root_id":"73","text":"Knit Charge"},{"id":113,"root_id":"73","text":"Dyeing Charge"},{"id":114,"root_id":"73","text":"Yarn Dyeing Charge"},{"id":115,"root_id":"73","text":"Embelishment"},{"id":116,"root_id":"73","text":"Embellishment Charge"},{"id":118,"root_id":"73","text":"Key Control"},{"id":135,"root_id":"73","text":"Aop Chrge"},{"id":142,"root_id":"73","text":"Key control"}]},{"id":76,"root_id":"8","text":"Contact","children":[{"id":74,"root_id":"76","text":"Team"},{"id":77,"root_id":"76","text":"Supplier"},{"id":78,"root_id":"76","text":"Buyer"}]}]},{"id":44,"root_id":"0","text":"Marketing","children":[{"id":45,"root_id":"44","text":"Style"},{"id":50,"root_id":"44","text":"Sales Order"},{"id":58,"root_id":"44","text":"CAD"},{"id":64,"root_id":"44","text":"Projection"},{"id":71,"root_id":"44","text":"Marketing Cost"},{"id":119,"root_id":"44","text":"Budget"}]},{"id":68,"root_id":"0","text":"Reports","children":[{"id":69,"root_id":"68","text":"Order Progress"},{"id":85,"root_id":"68","text":"Quotation Statement"},{"id":86,"root_id":"68","text":"Projection Progress"},{"id":149,"root_id":"68","text":"Offer Statement"}]},{"id":120,"root_id":"0","text":"Purchase","children":[{"id":100,"root_id":"120","text":"Fabric Purchase Order"},{"id":101,"root_id":"120","text":"Short Fabric Purchase Order"},{"id":102,"root_id":"120","text":"Fabric Purchase Order - Projection"},{"id":103,"root_id":"120","text":"Trim Purchase Order"},{"id":104,"root_id":"120","text":"Short Trim Purchase Order"},{"id":121,"root_id":"120","text":"Fabric Service Work Order"},{"id":122,"root_id":"120","text":"Yarn Dyeing Work Order"},{"id":123,"root_id":"120","text":"Yarn Reconing Work Order"},{"id":124,"root_id":"120","text":"Yarn Twisting Work Order"},{"id":125,"root_id":"120","text":"Embelishment Work Order"},{"id":126,"root_id":"120","text":"GMT Production Work Order"}]}]);

