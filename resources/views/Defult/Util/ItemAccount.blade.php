<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="itemaccountstabs">
  <div title="Item Account" style="padding:1px" data-options="selected:true">
      @includeFirst(['Util.Item', 'Defult.Util.Item'])
  </div>
  <div title="Yarn Ratio" style="padding:1px" data-options="selected:true">
      @includeFirst(['Util.ItemAccountRatio', 'Defult.Util.ItemAccountRatio'])
  </div>
  <div title="Tag Supplier" style="padding:1px" data-options="selected:true">
      @includeFirst(['Util.ItemAccountSupplier', 'Defult.Util.ItemAccountSupplier'])
  </div>
  <div title="Supplier Rate" style="padding:1px" data-options="selected:true">
      @includeFirst(['Util.ItemAccountSupplier', 'Defult.Util.ItemAccountSupplierRate'])
  </div>
  <div title="Supplier Features" style="padding:1px" data-options="selected:true">
      @includeFirst(['Util.ItemAccountSupplierFeat', 'Defult.Util.ItemAccountSupplierFeat'])
  </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsItemAccountController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsItemAccountSupplierController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsItemAccountSupplierRateController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsItemAccountSupplierFeatController.js"></script>
<script type="text/javascript">

$(document).ready(function() {

	 $('#itemsearchFrm [id="itemcategory_id"]').combobox();
 $('#itemsearchFrm [id="itemclass_id"]').combobox();
 $('#itemsearchFrm [id="item_nature_id"]').combobox();
 $('#itemsearchFrm [id="yarncount_id"]').combobox();

	var bloodhound = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.whitespace,
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: {
		url: msApp.baseUrl()+'/itemaccount/getitemdescription?q=%QUERY%',
		wildcard: '%QUERY%'
		},
	});
$('#item_description').typeahead({
		hint: true,
		highlight: true,
		minLength: 1
	}, {
		name: 'itemaccounts',
		limit:1000,
		source: bloodhound,
		display: function(data) {
			return data.name  //Input value to be set when you select a suggestion. 
		},
		templates: {
			empty: [
				'<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
			],
			header: [
				'<div class="list-group search-results-dropdown">'
			],
			suggestion: function(data) {
				return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item">' + data.name + '</div></div>'
			}
	    }
	});
});
</script>
