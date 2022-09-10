let MsCentralSewingPlanModel = require('./MsCentralSewingPlanModel');
require('./datagrid-filter.js');

class MsCentralSewingPlanController {
	constructor(MsCentralSewingPlanModel)
	{
		this.MsCentralSewingPlanModel = MsCentralSewingPlanModel;
		this.formId='centralsewingplanFrm';
		this.dataTable='#centralsewingplanTbl';
		this.route=msApp.baseUrl()+"/centralsewingplan"
	}
	getParams(){
		let params={};
		params.company_id = $('#centralsewingplanFrm  [name=company_id]').val();
		params.buyer_id = $('#centralsewingplanFrm  [name=buyer_id]').val();
		params.date_from = $('#centralsewingplanFrm  [name=date_from]').val();
		params.date_to = $('#centralsewingplanFrm  [name=date_to]').val();
		return params;
	}
	
	get()
	{
		let params=this.getParams();
		if( params.date_from==''){
			alert('Please Select a date range ');
			return;
		}

		if(params.date_to==''){
			alert('Please Select a date range');
			return;
		}

		let from=new Date(params.date_from);
		let to=new Date(params.date_to);

		var fromDate = new Date(
		from.getFullYear(),
		from.getMonth(),
		from.getDate(),
		from.getHours(),
		from.getMinutes(),
		from.getSeconds()
		);
		var fromyyyy = fromDate.getFullYear().toString();                                    
		var frommm = (fromDate.getMonth()+1).toString();//getMonth() is zero-based

		var toDate = new Date(
		to.getFullYear(),
		to.getMonth(),
		to.getDate(),
		to.getHours(),
		to.getMinutes(),
		to.getSeconds()
		);
		var toyyyy = toDate.getFullYear().toString();                                    
		var tomm = (toDate.getMonth()+1).toString();//getMonth() is zero-based

		if(fromyyyy != toyyyy){
			alert('Cross Year not allowed ');
			return;
		}

		if(frommm != tomm){
			alert('Cross month not allowed ');
			return;
		}

		//let comp=$( "#myselect option:selected" ).text();
		let comp=$('#centralsewingplanFrm  [name=company_id] option:selected').text()

		let formatted_month =msApp.months[toDate.getMonth()] + "-" + toDate.getFullYear();
		var title='Ship Date Wise Central Sewing Plan : &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp'+comp+'  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Production Month : '+formatted_month;
		var p = $('#centralsewingplanlayout').layout('panel', 'center').panel('setTitle', title);
		

		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#centralsewingplanTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(data)
	{
		var dg = $(this.dataTable);
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var qty=0;
				var smv=0;
				var booked_minute=0;
				var booked_hour=0;
				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
					smv+=data.rows[i]['smv'].replace(/,/g,'')*1;
					booked_minute+=data.rows[i]['booked_minute'].replace(/,/g,'')*1;
					booked_hour+=data.rows[i]['booked_hour'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{
				 	qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	smv: smv.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	booked_minute: booked_minute.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				 	booked_hour: booked_hour.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
				
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	imageWindow(flie_src)
	{
		var output = document.getElementById('centralsewingplanImageWindowoutput');
		var fp=msApp.baseUrl()+"/images/"+flie_src;
		output.src =  fp;
		$('#centralsewingplanImageWindow').window('open');
	}
	
	formatimage(value,row)
	{
		return '<img href="javascript:void(0)" width="15"  height="15" src="'+msApp.baseUrl()+'/images/'+row.flie_src+'" onClick="MsCentralSewingPlan.imageWindow('+'\''+row.flie_src+'\''+')"/>';
	}

	
}
window.MsCentralSewingPlan=new MsCentralSewingPlanController(new MsCentralSewingPlanModel());
MsCentralSewingPlan.showGrid([]);
