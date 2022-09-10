<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::resource('/', 'System\Layout\HomeController');
Route::resource('dashboard', 'System\Layout\DashboardController');
Route::get('menu/getjson', 'System\MenuController@getjson');
Route::resource('menu', 'System\MenuController');
Route::resource('permission', 'System\PermissionController');
Route::resource('role', 'System\Auth\RoleController');
Route::resource('permissionrole', 'System\Auth\PermissionRoleController');
Route::resource('user', 'System\Auth\UserController');
Route::get('ileconfig/getitemgroup', 'System\Configuration\IleConfigController@getItemGroup');
Route::resource('ileconfig', 'System\Configuration\IleConfigController');
Route::resource('coststandard', 'System\Configuration\CostStandardController');
Route::resource('coststandardhead', 'System\Configuration\CostStandardHeadController');
Route::resource('expdocprepstdday', 'System\Configuration\ExpDocPrepStdDayController');
Route::resource('companyuser', 'Util\CompanyUserController');
Route::resource('buyeruser', 'Util\BuyerUserController');
Route::resource('supplieruser', 'Util\SupplierUserController');
Route::resource('suppliersetting', 'Util\SupplierSettingController');
Route::resource('permissionuser', 'System\PermissionUserController');
Route::resource('itemcategoryuser', 'Util\ItemcategoryUserController');
Route::resource('signatureuser', 'Util\SignatureUserController');
Route::resource('myaccount', 'System\Auth\MyaccountController');
Route::resource('company', 'Util\CompanyController');
Route::resource('cgroup', 'Util\CgroupController');
Route::resource('region', 'Util\RegionController');
Route::resource('location', 'Util\LocationController');
Route::resource('division', 'Util\DivisionController');
Route::resource('floor', 'Util\FloorController');
Route::resource('department', 'Util\DepartmentController');
Route::resource('departmentfloor', 'Util\DepartmentFloorController');
Route::resource('section', 'Util\SectionController');
Route::resource('floorsection', 'Util\FloorSectionController');
Route::resource('subsection', 'Util\SubsectionController');
Route::resource('companysubsection', 'Util\CompanySubsectionController');
Route::resource('profitcenter', 'Util\ProfitcenterController');
Route::resource('companyprofitcenter', 'Util\CompanyProfitcenterController');
Route::resource('country', 'Util\CountryController');
Route::resource('currency', 'Util\CurrencyController');
Route::resource('uom', 'Util\UomController');
Route::resource('uomconversion', 'Util\UomconversionController');
Route::resource('exchangerate', 'Util\ExchangerateController');
Route::resource('itemcategory', 'Util\ItemcategoryController');
Route::get('itemclass/getUomCodes', 'Util\ItemclassController@getUomCodes');
Route::resource('itemclass', 'Util\ItemclassController');
Route::resource('itemclassprofitcenter', 'Util\ItemclassProfitcenterController');
Route::resource('team', 'Util\TeamController');
Route::resource('teammember', 'Util\TeammemberController');
Route::resource('gmtssample', 'Util\GmtssampleController');
Route::get('supplier/getSupplier', 'Util\SupplierController@getSupplier');
Route::get('supplier/getOtp', 'Util\SupplierController@getOtherParty');
Route::resource('supplier', 'Util\SupplierController');
Route::resource('suppliernature', 'Util\SupplierNatureController');
Route::resource('companysupplier', 'Util\CompanySupplierController');
Route::get('buyer/getBuyer', 'Util\BuyerController@getBuyer');
Route::resource('buyer', 'Util\BuyerController');
Route::resource('buyernature', 'Util\BuyerNatureController');
Route::resource('companybuyer', 'Util\CompanyBuyerController');
Route::resource('buyerbranch', 'Util\BuyerBranchController');
Route::resource('buyerbranchshipday', 'Util\BuyerBranchShipdayController');
Route::resource('composition', 'Util\CompositionController');
Route::resource('compositionitemcategory', 'Util\CompositionItemcategoryController');
Route::resource('yarncount', 'Util\YarncountController');
Route::resource('yarntype', 'Util\YarntypeController');
Route::resource('construction', 'Util\ConstructionController');
Route::resource('colorrange', 'Util\ColorrangeController');
Route::resource('fabricprocessloss', 'Util\FabricprocesslossController');
Route::resource('gmtspart', 'Util\GmtspartController');
Route::resource('gmtspartmenu', 'Util\GmtspartMenuController');
Route::get('color/getcolor', 'Util\ColorController@getcolor');
Route::resource('color', 'Util\ColorController');
Route::resource('buyercolor', 'Util\BuyerColorController');
Route::get('size/getsize', 'Util\SizeController@getsize');
Route::resource('size', 'Util\SizeController');
Route::resource('buyersize', 'Util\BuyerSizeController');
Route::resource('productionprocess', 'Util\ProductionProcessController');
Route::resource('productdepartment', 'Util\ProductdepartmentController');
Route::resource('resource', 'Util\ResourceController');
Route::resource('attachment', 'Util\AttachmentController');
Route::get('operation/getfabric', 'Util\OperationController@getFabric');
Route::resource('operation', 'Util\OperationController');
Route::resource('attachmentoperation', 'Util\AttachmentOperationController');
Route::resource('knitcharge', 'Util\KnitChargeController');
Route::resource('knitchargesupplier', 'Util\KnitChargeSupplierController');
Route::resource('buyerknitcharge', 'Util\BuyerKnitChargeController');
Route::get('aopcharge/getfabric', 'Util\AopChargeController@getFabric');
Route::resource('aopcharge', 'Util\AopChargeController');
Route::resource('aopsuppliercharge', 'Util\AopSupplierChargeController');
Route::resource('aopbuyercharge', 'Util\AopBuyerChargeController');
Route::get('dyingcharge/getfabric', 'Util\DyingChargeController@getFabric');
Route::resource('dyingcharge', 'Util\DyingChargeController');
Route::resource('dyingchargesupplier', 'Util\DyingChargeSupplierController');
Route::resource('buyerdyingcharge', 'Util\BuyerDyingChargeController');
Route::resource('yarndyingcharge', 'Util\YarnDyingChargeController');
Route::resource('buyeryarndyingcharge', 'Util\BuyerYarnDyingChargeController');
Route::resource('supplieryarndyingcharge', 'Util\SupplierYarnDyingChargeController');
Route::resource('embelishment', 'Util\EmbelishmentController');
Route::resource('embelishmenttype', 'Util\EmbelishmentTypeController');
Route::get('washcharge/embtype', 'Util\WashChargeController@getEmbtype');
Route::resource('washcharge', 'Util\WashChargeController');
Route::resource('supplierwashcharge', 'Util\SupplierWashChargeController');
Route::resource('fabricprocesslosspercent', 'Util\FabricprocesslossPercentController');
Route::resource('autoyarn', 'Util\AutoyarnController');
Route::resource('autoyarnratio', 'Util\AutoyarnratioController');
Route::resource('smvchart', 'Util\SmvChartController');
Route::resource('incentive', 'Util\IncentiveController');
Route::resource('season', 'Util\SeasonController');
Route::resource('wagevariable', 'Util\WagevariableController');
Route::resource('gmtsprocessloss', 'Util\GmtsProcessLossController');
Route::resource('gmtsprocesslossper', 'Util\GmtsProcessLossPerController');
Route::resource('delaycause', 'Util\DelaycauseController');
Route::resource('designation', 'Util\DesignationController');
Route::get('sewingcapacity/report', 'Util\SewingCapacityController@getPdf');
Route::resource('sewingcapacity', 'Util\SewingCapacityController');
Route::resource('sewingcapacitydate', 'Util\SewingCapacityDateController');
Route::resource('capacitydist', 'Util\CapacityDistController');
Route::resource('capacitydistbuyer', 'Util\CapacityDistBuyerController');
Route::resource('capacitydistbuyerteam', 'Util\CapacityDistBuyerTeamController');
Route::resource('keycontrol', 'Util\KeycontrolController');
Route::resource('keycontrolparameter', 'Util\KeycontrolParameterController');
Route::resource('trimcosttemplete', 'Util\TrimcosttempleteController');
Route::resource('tnatask', 'Util\TnataskController');
Route::get('itemaccount/getItemAccount', 'Util\ItemAccountController@getItemAccount');
Route::get('itemaccount/getitemdescription', 'Util\ItemAccountController@getitemdescription');
Route::resource('itemaccount', 'Util\ItemAccountController');
Route::resource('itemaccountratio', 'Util\ItemAccountRatioController');
Route::resource('itemaccountsupplier', 'Util\ItemAccountSupplierController');
Route::get('itemaccountsupplierrate/getitemsupplier', 'Util\ItemAccountSupplierRateController@getItemSupplier');
Route::get('itemaccountsupplierrate/custom', 'Util\ItemAccountSupplierRateController@getCustomName');
Route::resource('itemaccountsupplierrate', 'Util\ItemAccountSupplierRateController');
Route::resource('itemaccountsupplierfeat', 'Util\ItemAccountSupplierFeatController');
Route::resource('termscondition', 'Util\TermsConditionController');
Route::resource('weightmachine', 'Util\WeightMachineController');
Route::resource('smssetup', 'Util\SmsSetupController');
Route::get('smssetupsmsto/getemployee', 'Util\SmsSetupSmsToController@getEmployee');
Route::resource('smssetupsmsto', 'Util\SmsSetupSmsToController');
Route::resource('mailsetup', 'Util\MailSetupController');
Route::resource('mailsetupemailto', 'Util\MailSetupEmailToController');

Route::resource('weightmachineuser', 'Util\WeightMachineUserController');
Route::resource('targetprocesssetup', 'Util\TargetProcessSetupController');
Route::resource('productdefect', 'Util\ProductDefectController');

Route::get('style/getstyle', 'Marketing\StyleController@getstyle');
Route::get('style/getstyledescription', 'Marketing\StyleController@getstyledescription');
Route::get('style/getcontact', 'Marketing\StyleController@getcontact');
Route::post('style/upload', 'Marketing\StyleController@upload');
Route::get('style/getoldstyle', 'Marketing\StyleController@getOldStyle');
Route::resource('style', 'Marketing\StyleController');
Route::resource('stylegmts', 'Marketing\StyleGmtsController');
Route::get('stylegmtcolorsize/getgmtcolor', 'Marketing\StyleGmtColorSizeController@getGmtColor');
Route::resource('stylegmtcolorsize', 'Marketing\StyleGmtColorSizeController');
Route::get('styleembelishment/embtype', 'Marketing\StyleEmbelishmentController@getEmbtype');
Route::resource('styleembelishment', 'Marketing\StyleEmbelishmentController');
Route::resource('stylecolor', 'Marketing\StyleColorController');
Route::resource('stylesize', 'Marketing\StyleSizeController');
Route::get('stylefabrication/getfabric', 'Marketing\StyleFabricationController@getFabric');
Route::resource('stylefabrication', 'Marketing\StyleFabricationController');
Route::resource('stylefabricationstripe', 'Marketing\StyleFabricationStripeController');
Route::resource('stylesizemsure', 'Marketing\StyleSizeMsureController');
Route::resource('stylesizemsureval', 'Marketing\StyleSizeMsureValController');
Route::resource('stylesample', 'Marketing\StyleSampleController');
Route::resource('stylesamplecs', 'Marketing\StyleSampleCsController');
Route::resource('stylepoly', 'Marketing\StylePolyController');
Route::resource('stylepolyratio', 'Marketing\StylePolyRatioController');

Route::get('stylepkg/getspec', 'Marketing\StylePkgController@getSpec');
Route::get('stylepkg/getassortmentname', 'Marketing\StylePkgController@getAssortmentName');
Route::resource('stylepkg', 'Marketing\StylePkgController');

Route::resource('stylepkgratio', 'Marketing\StylePkgRatioController');
Route::resource('styleevaluation', 'Marketing\StyleEvaluationController');
Route::resource('stylefileupload', 'Marketing\StyleFileUploadController');
Route::get('mktcost/report', 'Marketing\MktCostController@getPdf');
Route::get('mktcost/html', 'Marketing\MktCostController@getHtml');
Route::get('mktcost/reportquote', 'Marketing\MktCostController@getPdfQuote');
Route::get('mktcost/getbuyerdevelopmentorderqty', 'Marketing\MktCostController@getBuyerDevelopmentOrderQty');
Route::resource('mktcost', 'Marketing\MktCostController');
Route::resource('mktcostfabric', 'Marketing\MktCostFabricController');
Route::resource('mktcostfabriccon', 'Marketing\MktCostFabricConController');
Route::resource('mktcostother', 'Marketing\MktCostOtherController');
Route::resource('mktcostcommercial', 'Marketing\MktCostCommercialController');
Route::get('mktcostcm/gmtitem', 'Marketing\MktCostCmController@getGmtItem');
Route::resource('mktcostcm', 'Marketing\MktCostCmController');
Route::get('mktcosttrim/setuom', 'Marketing\MktCostTrimController@setuom');
Route::resource('mktcosttrim', 'Marketing\MktCostTrimController');
Route::resource('mktcostcommission', 'Marketing\MktCostCommissionController');
Route::resource('mktcostprofit', 'Marketing\MktCostProfitController');
Route::resource('mktcostquoteprice', 'Marketing\MktCostQuotePriceController');
Route::resource('mktcosttargetprice', 'Marketing\MktCostTargetPriceController');
Route::get('mktcostemb/getrate', 'Marketing\MktCostEmbController@getrate');
Route::resource('mktcostemb', 'Marketing\MktCostEmbController');
Route::get('mktcostyarn/popuplist', 'Marketing\MktCostYarnController@getPopuplist');
Route::get('mktcostyarn/getyarn', 'Marketing\MktCostYarnController@getyarn');
Route::resource('mktcostyarn', 'Marketing\MktCostYarnController');
Route::get('mktcostfabricprod/cons', 'Marketing\MktCostFabricProdController@getCons');
Route::get('mktcostfabricprod/yarncount', 'Marketing\MktCostFabricProdController@getYarncount');
Route::get('mktcostfabricprod/getrate', 'Marketing\MktCostFabricProdController@getrate');
Route::get('mktcostfabricprod/productionarea', 'Marketing\MktCostFabricProdController@getproductionarea');
Route::resource('mktcostfabricprod', 'Marketing\MktCostFabricProdController');
Route::get('budget/report', 'Bom\BudgetController@getPdf');
Route::get('budget/mos', 'Bom\BudgetController@getMos');
Route::get('budget/mosbyshipdate', 'Bom\BudgetController@getMosByShipDate');
Route::get('budget/searchbudget', 'Bom\BudgetController@searchBudget');
Route::resource('budget', 'Bom\BudgetController');
Route::resource('budgetfabric', 'Bom\BudgetFabricController');
Route::resource('budgetfabriccon', 'Bom\BudgetFabricConController');
Route::resource('budgetother', 'Bom\BudgetOtherController');
Route::resource('budgetcommercial', 'Bom\BudgetCommercialController');
Route::get('budgetcm/gmtitem', 'Bom\BudgetCmController@getGmtItem');
Route::resource('budgetcm', 'Bom\BudgetCmController');
Route::get('budgettrim/setuom', 'Bom\BudgetTrimController@setuom');
Route::resource('budgettrim', 'Bom\BudgetTrimController');
Route::resource('budgettrimcon', 'Bom\BudgetTrimConController');
Route::resource('budgettrimdtm', 'Bom\BudgetTrimDtmController');
Route::resource('budgetcommission', 'Bom\BudgetCommissionController');
Route::resource('budgetemb', 'Bom\BudgetEmbController');
Route::resource('budgetembcon', 'Bom\BudgetEmbConController');
Route::get('budgetyarn/popuplist', 'Bom\BudgetYarnController@getPopuplist');
Route::get('budgetyarn/getbudgetyarn', 'Bom\BudgetYarnController@getbudgetyarn');
Route::resource('budgetyarn', 'Bom\BudgetYarnController');
Route::get('budgetfabricprod/cons', 'Bom\BudgetFabricProdController@getCons');
Route::get('budgetfabricprod/processChange', 'Bom\BudgetFabricProdController@processChange');
Route::resource('budgetfabricprod', 'Bom\BudgetFabricProdController');
Route::resource('budgetfabricprodcon', 'Bom\BudgetFabricProdConController');

Route::get('budgetyarndyeing/cons', 'Bom\BudgetYarnDyeingController@getCons');
Route::get('budgetyarndyeing/processChange', 'Bom\BudgetYarnDyeingController@processChange');
Route::resource('budgetyarndyeing', 'Bom\BudgetYarnDyeingController');
Route::resource('budgetyarndyeingcon', 'Bom\BudgetYarnDyeingConController');
Route::resource('budgetreadytoapproved', 'Bom\BudgetReadyToApprovedController');

Route::resource('job', 'Sales\JobController');
Route::resource('projection', 'Sales\ProjectionController');
Route::resource('projectioncountry', 'Sales\ProjectionCountryController');
Route::resource('projectionqty', 'Sales\ProjectionQtyController');
Route::resource('salesorder', 'Sales\SalesOrderController');
Route::resource('salesordercountry', 'Sales\SalesOrderCountryController');
Route::resource('salesordercolor', 'Sales\SalesOrderColorController');
Route::resource('salesordersize', 'Sales\SalesOrderSizeController');
Route::resource('salesorderitem', 'Sales\SalesOrderItemController');
Route::resource('salesordergmtcolorsize', 'Sales\SalesOrderGmtColorSizeController');
Route::get('salesordershipdatechange/getsalesorder', 'Sales\SalesOrderShipDateChangeController@getSalesOrder');
Route::get('salesordershipdatechange/getallchangedshipdate', 'Sales\SalesOrderShipDateChangeController@getAllChangedShipDate');
Route::resource('salesordershipdatechange', 'Sales\SalesOrderShipDateChangeController');
Route::resource('cad', 'Bom\CadController');
Route::resource('cadcon', 'Bom\CadConController');

Route::resource('renewalitem', 'Util\Renewal\RenewalItemController');
Route::resource('renewalitemdoc', 'Util\Renewal\RenewalItemDocController');

Route::get('pofabric/getpospdf', 'Purchase\PoFabricController@getPosPdf');
Route::get('pofabric/getpodpdf', 'Purchase\PoFabricController@getPodPdf');
Route::resource('pofabric', 'Purchase\PoFabricController');
Route::get('pofabricitem/importfabric', 'Purchase\PoFabricItemController@importfabric');
Route::resource('pofabricitem', 'Purchase\PoFabricItemController');
Route::resource('pofabricitemqty', 'Purchase\PoFabricItemQtyController');

Route::get('pofabricshort/getpospdf', 'Purchase\PoFabricShortController@getPosPdf');
Route::get('pofabricshort/getpodpdf', 'Purchase\PoFabricShortController@getPodPdf');
Route::resource('pofabricshort', 'Purchase\PoFabricShortController');
Route::get('pofabricshortitem/importfabric', 'Purchase\PoFabricShortItemController@importfabric');
Route::resource('pofabricshortitem', 'Purchase\PoFabricShortItemController');
Route::resource('pofabricshortitemqty', 'Purchase\PoFabricShortItemQtyController');
Route::get('pofabricshortitemresp/getemployeehr', 'Purchase\PoFabricShortItemRespController@getEmployeeHr');
Route::resource('pofabricshortitemresp', 'Purchase\PoFabricShortItemRespController');

// Route::resource('addtionalfabricpurchase', 'Purchase\BulkAdditionalFabricPurchaseController');
// Route::resource('projectionfabricpurchase', 'Purchase\BulkProjectionFabricPurchaseController');

// Route::get('purordertrim/report','Purchase\PurOrderTrimController@getPdf');
// Route::resource('purordertrim', 'Purchase\PurOrderTrimController');
// Route::get('purtrim/importtrim', 'Purchase\PurTrimController@importtrim');
// Route::resource('purtrim', 'Purchase\PurTrimController');
// Route::resource('purtrimqty', 'Purchase\PurTrimQtyController');

Route::get('potrim/report', 'Purchase\PoTrimController@getPdf');
Route::get('potrim/reportshort', 'Purchase\PoTrimController@getPdfShort');
Route::resource('potrim', 'Purchase\PoTrimController');
Route::get('potrimitem/importtrim', 'Purchase\PoTrimItemController@importtrim');
Route::resource('potrimitem', 'Purchase\PoTrimItemController');
Route::resource('potrimitemqty', 'Purchase\PoTrimItemQtyController');

Route::get('potrimshort/report', 'Purchase\PoTrimShortController@getPdf');
Route::get('potrimshort/reportshort', 'Purchase\PoTrimShortController@getPdfShort');
Route::resource('potrimshort', 'Purchase\PoTrimShortController');
Route::get('potrimshortitem/importtrim', 'Purchase\PoTrimShortItemController@importtrim');
Route::resource('potrimshortitem', 'Purchase\PoTrimShortItemController');
Route::resource('potrimshortitemqty', 'Purchase\PoTrimShortItemQtyController');
Route::get('potrimshortitemresp/getemployeehr', 'Purchase\PoTrimShortItemRespController@getEmployeeHr');
Route::resource('potrimshortitemresp', 'Purchase\PoTrimShortItemRespController');

Route::get('podyechem/report', 'Purchase\PoDyeChemController@getPdf');
Route::get('podyechem/reportshort', 'Purchase\PoDyeChemController@getPdfShort');
Route::get('podyechem/reporttopsheet', 'Purchase\PoDyeChemController@getPdfTopSheet');
Route::resource('podyechem', 'Purchase\PoDyeChemController');
Route::get('podyechemitem/importitem', 'Purchase\PoDyeChemItemController@importItem');
Route::resource('podyechemitem', 'Purchase\PoDyeChemItemController');

Route::get('pogeneral/report', 'Purchase\PoGeneralController@getPdf');
Route::get('pogeneral/reportshort', 'Purchase\PoGeneralController@getPdfShort');
Route::get('pogeneral/reporttopsheet', 'Purchase\PoGeneralController@getPdfTopSheet');
Route::resource('pogeneral', 'Purchase\PoGeneralController');
Route::get('pogeneralitem/importitem', 'Purchase\PoGeneralItemController@importItem');
Route::resource('pogeneralitem', 'Purchase\PoGeneralItemController');

Route::get('pogeneralservice/report', 'Purchase\PoGeneralServiceController@getPdf');
Route::resource('pogeneralservice', 'Purchase\PoGeneralServiceController');
Route::get('pogeneralserviceitem/getasset', 'Purchase\PoGeneralServiceItemController@getAsset');
Route::resource('pogeneralserviceitem', 'Purchase\PoGeneralServiceItemController');

// Route::resource('purorderyarn', 'Purchase\PurOrderYarnController');
// Route::get('puryarn/importyarn', 'Purchase\PurYarnController@importyarn');
// Route::resource('puryarn', 'Purchase\PurYarnController');
// Route::resource('puryarnqty', 'Purchase\PurYarnQtyController');

Route::get('poyarn/report', 'Purchase\PoYarnController@getPdf');
Route::get('poyarn/summeryreport', 'Purchase\PoYarnController@getSummeryPdf');
Route::resource('poyarn', 'Purchase\PoYarnController');
Route::get('poyarnitem/importyarn', 'Purchase\PoYarnItemController@importyarn');
Route::resource('poyarnitem', 'Purchase\PoYarnItemController');
Route::get('poyarnitembomqty/getorder', 'Purchase\PoYarnItemBomQtyController@getOrder');
Route::resource('poyarnitembomqty', 'Purchase\PoYarnItemBomQtyController');

Route::get('poyarndyeing/report', 'Purchase\PoYarnDyeingController@getPdf');
Route::resource('poyarndyeing', 'Purchase\PoYarnDyeingController');
Route::get('poyarndyeingitem/getinvrcvyarnitem', 'Purchase\PoYarnDyeingItemController@getRcvYarnItem');
Route::resource('poyarndyeingitem', 'Purchase\PoYarnDyeingItemController');

//Route::get('poyarndyeingitembomqty/getorder', 'Purchase\PoYarnDyeingItemBomQtyController@getOrder');
Route::get('poyarndyeingitembomqty/getyarndyesaleorder', 'Purchase\PoYarnDyeingItemBomQtyController@getYarnDyeSaleOrder');
Route::resource('poyarndyeingitembomqty', 'Purchase\PoYarnDyeingItemBomQtyController');

Route::get('poyarndyeingshort/report', 'Purchase\PoYarnDyeingShortController@getPdf');
Route::resource('poyarndyeingshort', 'Purchase\PoYarnDyeingShortController');
Route::get('poyarndyeingshortitem/getinvrcvyarnitem', 'Purchase\PoYarnDyeingShortItemController@getRcvYarnItem');
Route::resource('poyarndyeingshortitem', 'Purchase\PoYarnDyeingShortItemController');
Route::get('poyarndyeingshortitemresp/getemployeehr', 'Purchase\PoYarnDyeingShortItemRespController@getEmployeeHr');
Route::resource('poyarndyeingshortitemresp', 'Purchase\PoYarnDyeingShortItemRespController');

//Route::get('poyarndyeingshortitembomqty/getorder', 'Purchase\PoYarnDyeingShortItemBomQtyController@getOrder');
Route::get('poyarndyeingshortitembomqty/getyarndyesaleorder', 'Purchase\PoYarnDyeingShortItemBomQtyController@getYarnDyeSaleOrder');
Route::resource('poyarndyeingshortitembomqty', 'Purchase\PoYarnDyeingShortItemBomQtyController');

Route::get('poknitservice/report', 'Purchase\PoKnitServiceController@getPdf');
Route::resource('poknitservice', 'Purchase\PoKnitServiceController');
Route::get('poknitserviceitem/importfabric', 'Purchase\PoKnitServiceItemController@importfabric');
Route::resource('poknitserviceitem', 'Purchase\PoKnitServiceItemController');
Route::resource('poknitserviceitemqty', 'Purchase\PoKnitServiceItemQtyController');

Route::get('poknitserviceshort/report', 'Purchase\PoKnitServiceShortController@getPdf');
Route::resource('poknitserviceshort', 'Purchase\PoKnitServiceShortController');
Route::get('poknitserviceshortitem/importfabric', 'Purchase\PoKnitServiceShortItemController@importfabric');
Route::resource('poknitserviceshortitem', 'Purchase\PoKnitServiceShortItemController');
Route::resource('poknitserviceshortitemqty', 'Purchase\PoKnitServiceShortItemQtyController');
Route::get('poknitserviceshortitemresp/getemployeehr', 'Purchase\PoKnitServiceShortItemRespController@getEmployeeHr');
Route::resource('poknitserviceshortitemresp', 'Purchase\PoKnitServiceShortItemRespController');

Route::get('podyeingservice/report', 'Purchase\PoDyeingServiceController@getPdf');
Route::resource('podyeingservice', 'Purchase\PoDyeingServiceController');
Route::get('podyeingserviceitem/importfabric', 'Purchase\PoDyeingServiceItemController@importfabric');
Route::resource('podyeingserviceitem', 'Purchase\PoDyeingServiceItemController');
Route::resource('podyeingserviceitemqty', 'Purchase\PoDyeingServiceItemQtyController');

Route::get('podyeingserviceshort/report', 'Purchase\PoDyeingServiceShortController@getPdf');
Route::resource('podyeingserviceshort', 'Purchase\PoDyeingServiceShortController');
Route::get('podyeingserviceshortitem/importfabric', 'Purchase\PoDyeingServiceShortItemController@importfabric');
Route::resource('podyeingserviceshortitem', 'Purchase\PoDyeingServiceShortItemController');
Route::resource('podyeingserviceshortitemqty', 'Purchase\PoDyeingServiceShortItemQtyController');
Route::get('podyeingserviceshortitemresp/getemployeehr', 'Purchase\PoDyeingServiceShortItemRespController@getEmployeeHr');
Route::resource('podyeingserviceshortitemresp', 'Purchase\PoDyeingServiceShortItemRespController');

Route::get('poaopservice/report', 'Purchase\PoAopServiceController@getPdf');
Route::resource('poaopservice', 'Purchase\PoAopServiceController');
Route::get('poaopserviceitem/importfabric', 'Purchase\PoAopServiceItemController@importfabric');
Route::resource('poaopserviceitem', 'Purchase\PoAopServiceItemController');
Route::resource('poaopserviceitemqty', 'Purchase\PoAopServiceItemQtyController');

Route::get('poaopserviceshort/report', 'Purchase\PoAopServiceShortController@getPdf');
Route::resource('poaopserviceshort', 'Purchase\PoAopServiceShortController');
Route::get('poaopserviceshortitem/importfabric', 'Purchase\PoAopServiceShortItemController@importfabric');
Route::resource('poaopserviceshortitem', 'Purchase\PoAopServiceShortItemController');
Route::resource('poaopserviceshortitemqty', 'Purchase\PoAopServiceShortItemQtyController');
Route::get('poaopserviceshortitemresp/getemployeehr', 'Purchase\PoAopServiceShortItemRespController@getEmployeeHr');
Route::resource('poaopserviceshortitemresp', 'Purchase\PoAopServiceShortItemRespController');

Route::get('poembservice/report', 'Purchase\PoEmbServiceController@getPdf');
Route::resource('poembservice', 'Purchase\PoEmbServiceController');
Route::get('poembserviceitem/importfabric', 'Purchase\PoEmbServiceItemController@importfabric');
Route::resource('poembserviceitem', 'Purchase\PoEmbServiceItemController');
Route::resource('poembserviceitemqty', 'Purchase\PoEmbServiceItemQtyController');

Route::get('poembserviceshort/report', 'Purchase\PoEmbServiceShortController@getPdf');
Route::resource('poembserviceshort', 'Purchase\PoEmbServiceShortController');
Route::get('poembserviceshortitem/importfabric', 'Purchase\PoEmbServiceShortItemController@importfabric');
Route::resource('poembserviceshortitem', 'Purchase\PoEmbServiceShortItemController');
Route::get('poembserviceshortitemresp/getemployeehr', 'Purchase\PoEmbServiceShortItemRespController@getEmployeeHr');
Route::resource('poembserviceshortitemresp', 'Purchase\PoEmbServiceShortItemRespController');
Route::resource('poembserviceshortitemqty', 'Purchase\PoEmbServiceShortItemQtyController');


Route::resource('gmtsproductionworkorder', 'Purchase\GmtsProductionWorkOrderController');
Route::resource('purchasetermscondition', 'Purchase\PurchaseTermsConditionController');
Route::get('accyear/getBycompany', 'Account\AccYearController@getBycompany');
Route::resource('accyear', 'Account\AccYearController');
Route::resource('accchartsubgroup', 'Account\AccChartSubGroupController');
Route::get('accchartctrlhead/getroot', 'Account\AccChartCtrlHeadController@getroot');
Route::get('accchartctrlhead/retainedearningaccount', 'Account\AccChartCtrlHeadController@retainedearningaccount');
Route::get('accchartctrlhead/getjsonbycode', 'Account\AccChartCtrlHeadController@getjsonbycode');
Route::resource('accchartctrlhead', 'Account\AccChartCtrlHeadController');
Route::resource('accchartlocation', 'Account\AccChartLocationController');
Route::resource('accchartdivision', 'Account\AccChartDivisionController');
Route::resource('accchartdepartment', 'Account\AccChartDepartmentController');
Route::resource('accchartsection', 'Account\AccChartSectionController');

Route::get('accchartctrlheadmapping/getassethead', 'Account\AccChartCtrlHeadMappingController@getAssetHead');
Route::get('accchartctrlheadmapping/getaccumulatedhead', 'Account\AccChartCtrlHeadMappingController@getAccumulatedHead');
Route::resource('accchartctrlheadmapping', 'Account\AccChartCtrlHeadMappingController');

Route::get('/acctransprnt/journalpdf', 'Account\AccTransPrntController@journalpdf');

Route::get('/acctransprnt/mrpdf', 'Account\AccTransPrntController@mrpdf');
Route::get('/acctransprnt/cqpdf', 'Account\AccTransPrntController@cqpdf');
Route::resource('acctransprnt', 'Account\AccTransPrntController');
Route::get('acctranschld/getbillno', 'Account\AccTransChldController@getbillno');
Route::get('acctranschld/getimportlc', 'Account\AccTransChldController@getimportlc');
Route::get('acctranschld/getexportlc', 'Account\AccTransChldController@getexportlc');
Route::get('acctranschld/getloanref', 'Account\AccTransChldController@getloanrefno');
Route::get('acctranschld/getotherref', 'Account\AccTransChldController@getotherrefno');
Route::resource('acctranschld', 'Account\AccTransChldController');

Route::get('accbep/getmasteraccbep', 'Account\AccBepController@getMasterAccBep');
Route::get('accbep/copyaccbepmaster', 'Account\AccBepController@copyAccBep');
Route::resource('accbep', 'Account\AccBepController');
Route::resource('accbepentry', 'Account\AccBepEntryController');
Route::resource('accperiod', 'Account\AccPeriodController');

Route::resource('acccostdistribution', 'Account\AccCostDistributionController');

Route::get('acccostdistributiondtl/getsalesorder', 'Account\AccCostDistributionDtlController@getSalesOrder');
Route::resource('acccostdistributiondtl', 'Account\AccCostDistributionDtlController');

Route::get('acctermloan/getbankaccount', 'Account\AccTermLoanController@getBankAccount');
Route::resource('acctermloan', 'Account\AccTermLoanController');
Route::resource('acctermloaninstallment', 'Account\AccTermLoanInstallmentController');
Route::resource('acctermloanpayment', 'Account\AccTermLoanPaymentController');

Route::get('accothertradefinance/getbankaccount', 'Account\AccOtherTradeFinanceController@getBankAccount');
Route::resource('accothertradefinance', 'Account\AccOtherTradeFinanceController');

Route::get('acctermloanadjustment/gettermloan', 'Account\AccTermLoanAdjustmentController@getTermLoan');
Route::resource('acctermloanadjustment', 'Account\AccTermLoanAdjustmentController');

Route::get('employee/getEmployee', 'HRM\EmployeeController@getEmployee');
Route::resource('employee', 'HRM\EmployeeController');
Route::resource('employeeattendence', 'HRM\EmployeeAttendenceController');
Route::get('renewalentry/getData', 'HRM\RenewalEntryController@getData');
Route::get('renewalentry/getrenewalpdf', 'HRM\RenewalEntryController@getRenewalPdf');
Route::resource('renewalentry', 'HRM\RenewalEntryController');
Route::get('employeeincrement/sendtoapi', 'HRM\EmployeeIncrementController@sendToApi');
Route::resource('employeeincrement', 'HRM\EmployeeIncrementController');

Route::get('registervisitor/getvisitor', 'HRM\RegisterVisitorController@getVisitorName');
Route::get('registervisitor/getapprovedpdf', 'HRM\RegisterVisitorController@getApprovedPdf');
Route::resource('registervisitor', 'HRM\RegisterVisitorController');

Route::resource('agreement', 'HRM\AgreementController');
Route::resource('agreementfile', 'HRM\AgreementFileController');
Route::get('agreementpo/importpo', 'HRM\AgreementPoController@getPurchaseOrder');
Route::resource('agreementpo', 'HRM\AgreementPoController');

Route::resource('employeebudget', 'HRM\EmployeeBudgetController');
Route::get('employeebudgetposition/getdesignation', 'HRM\EmployeeBudgetPositionController@getDesignation');
Route::resource('employeebudgetposition', 'HRM\EmployeeBudgetPositionController');

Route::get('employeerecruitreq/requisitionform', 'HRM\EmployeeRecruitReqController@requisitionFormPdf');
Route::get('employeerecruitreq/getemployeebudget', 'HRM\EmployeeRecruitReqController@getEmployeeBudget');
Route::get('employeerecruitreq/getreportemployee', 'HRM\EmployeeRecruitReqController@getEmployee');
Route::resource('employeerecruitreq', 'HRM\EmployeeRecruitReqController');
Route::get('employeerecruitreqreplace/getreplaceemployee', 'HRM\EmployeeRecruitReqReplaceController@getReplaceEmployee');
Route::resource('employeerecruitreqreplace', 'HRM\EmployeeRecruitReqReplaceController');
Route::resource('employeerecruitreqjob', 'HRM\EmployeeRecruitReqJobController');

Route::get('invpurreq/html', 'Inventory\GeneralStore\InvPurReqController@getHtml');
Route::get('invpurreq/getprpdf', 'Inventory\GeneralStore\InvPurReqController@getPrPdf');
Route::get('invpurreq/getallinvpurreq', 'Inventory\GeneralStore\InvPurReqController@getAllInvPurReq');
Route::resource('invpurreq', 'Inventory\GeneralStore\InvPurReqController');
Route::get('invpurreqitem/getitemaccount', 'Inventory\GeneralStore\InvPurReqItemController@getItemAccount');
Route::resource('invpurreqitem', 'Inventory\GeneralStore\InvPurReqItemController');
Route::resource('invpurreqpaid', 'Inventory\GeneralStore\InvPurReqPaidController');
Route::get('invpurreqassetbreakdown/getassetbreakdown', 'Inventory\GeneralStore\InvPurReqAssetBreakdownController@getAssetBreakdown');
Route::resource('invpurreqassetbreakdown', 'Inventory\GeneralStore\InvPurReqAssetBreakdownController');
Route::get('invcasreq/getcrpdf', 'Inventory\GeneralStore\InvCasReqController@getCrPdf');
Route::resource('invcasreq', 'Inventory\GeneralStore\InvCasReqController');
Route::resource('invcasreqitem', 'Inventory\GeneralStore\InvCasReqItemController');
Route::resource('invcasreqpaid', 'Inventory\GeneralStore\InvCasReqPaidController');

Route::resource('bank', 'Util\BankController');
Route::get('bankaccount/getdebitaccount', 'Util\BankAccountController@getDebitAccount');
Route::resource('bankaccount', 'Util\BankAccountController');
Route::resource('bankbranch', 'Util\BankBranchController');

//Route::resource('invyarnrcv','Inventory\Yarn\InvYarnRcvController');
//Route::get('invyarnrcvpurord/getpurchaseyarn', 'Inventory\Yarn\InvYarnRcvPurOrdController@getPoYarn');
//Route::resource('invyarnrcvpurord','Inventory\Yarn\InvYarnRcvPurOrdController');
//Route::resource('invyarnrcvpurordqty','Inventory\Yarn\InvYarnRcvPurOrdQtyController');
//Route::get('invyarnopbalance/getyarndesc', 'Inventory\Yarn\InvYarnRcvOpBalanceController@getPoYarn');
//Route::resource('invyarnopbalance','Inventory\Yarn\InvYarnRcvOpBalanceController');

Route::get('invyarnrcv/storereport', 'Inventory\Yarn\InvYarnRcvController@getStorePdf');
Route::get('invyarnrcv/report', 'Inventory\Yarn\InvYarnRcvController@getPdf');
Route::resource('invyarnrcv', 'Inventory\Yarn\InvYarnRcvController');
Route::get('invyarnrcvitem/getyarnitem', 'Inventory\Yarn\InvYarnRcvItemController@getYarnItem');
Route::get('invyarnrcvitem/importyarn', 'Inventory\Yarn\InvYarnRcvItemController@importyarn');
Route::resource('invyarnrcvitem', 'Inventory\Yarn\InvYarnRcvItemController');
Route::get('invyarnrcvitemsos/getsalesorder', 'Inventory\Yarn\InvYarnRcvItemSosController@getSalesOrder');
Route::resource('invyarnrcvitemsos', 'Inventory\Yarn\InvYarnRcvItemSosController');

Route::get('invyarnisu/report', 'Inventory\Yarn\InvYarnIsuController@getPdf');
Route::get('invyarnisu/report2', 'Inventory\Yarn\InvYarnIsuController@getPdf2');
Route::resource('invyarnisu', 'Inventory\Yarn\InvYarnIsuController');
Route::get('invyarnisuitem/getyarnisuitem', 'Inventory\Yarn\InvYarnIsuItemController@getYarnItem');
Route::resource('invyarnisuitem', 'Inventory\Yarn\InvYarnIsuItemController');

//Route::get('rcvyarnbalance/getitemyarnblc', 'Inventory\Yarn\RcvYarnBalanceController@GetItemYarn');
//Route::resource('rcvyarnbalance','Inventory\Yarn\RcvYarnBalanceController');

Route::get('invyarnisurtn/report', 'Inventory\Yarn\InvYarnIsuRtnController@getPdf');
Route::resource('invyarnisurtn', 'Inventory\Yarn\InvYarnIsuRtnController');

Route::get('invyarnisurtnitem/getyarnisuitem', 'Inventory\Yarn\InvYarnIsuRtnItemController@getYarnItem');
Route::get('invyarnisurtnitem/getinvrcvyarnitem', 'Inventory\Yarn\InvYarnIsuRtnItemController@getRtnYarnItem');
Route::get('invyarnisurtnitem/getyarnsalesorder', 'Inventory\Yarn\InvYarnIsuRtnItemController@getRtnSaleOrder');
Route::get('invyarnisurtnitem/getrate', 'Inventory\Yarn\InvYarnIsuRtnItemController@getRate');
Route::resource('invyarnisurtnitem', 'Inventory\Yarn\InvYarnIsuRtnItemController');

Route::get('invyarnportn/report', 'Inventory\Yarn\InvYarnPoRtnController@getPdf');
Route::resource('invyarnportn', 'Inventory\Yarn\InvYarnPoRtnController');
Route::get('invyarnportnitem/getmrritem', 'Inventory\Yarn\InvYarnPoRtnItemController@getMrrItem');
Route::resource('invyarnportnitem', 'Inventory\Yarn\InvYarnPoRtnItemController');


Route::get('invyarntransout/report', 'Inventory\Yarn\InvYarnTransOutController@getPdf');
Route::resource('invyarntransout', 'Inventory\Yarn\InvYarnTransOutController');

Route::get('invyarntransoutitem/getyarnitem', 'Inventory\Yarn\InvYarnTransOutItemController@getYarnItem');
Route::get('invyarntransoutitem/getrate', 'Inventory\Yarn\InvYarnTransOutItemController@getRate');
Route::resource('invyarntransoutitem', 'Inventory\Yarn\InvYarnTransOutItemController');

Route::get('invyarntransin/report', 'Inventory\Yarn\InvYarnTransInController@getPdf');
Route::resource('invyarntransin', 'Inventory\Yarn\InvYarnTransInController');

Route::get('invyarntransinitem/getyarnitem', 'Inventory\Yarn\InvYarnTransInItemController@getYarnItem');
//Route::get('invyarntransinitem/getrate', 'Inventory\Yarn\InvYarnTransInItemController@getRate');
Route::resource('invyarntransinitem', 'Inventory\Yarn\InvYarnTransInItemController');


Route::get('invyarnisusamsec/report', 'Inventory\Yarn\InvYarnIsuSamSecController@getPdf');
Route::get('invyarnisusamsec/report2', 'Inventory\Yarn\InvYarnIsuSamSecController@getPdf2');
Route::resource('invyarnisusamsec', 'Inventory\Yarn\InvYarnIsuSamSecController');

Route::get('invyarnisusamsecitem/getyarnitem', 'Inventory\Yarn\InvYarnIsuSamSecItemController@getYarnItem');
Route::get('invyarnisusamsecitem/getstyle', 'Inventory\Yarn\InvYarnIsuSamSecItemController@getStyle');
Route::get('invyarnisusamsecitem/getsample', 'Inventory\Yarn\InvYarnIsuSamSecItemController@getSample');
Route::get('invyarnisusamsecitem/getorder', 'Inventory\Yarn\InvYarnIsuSamSecItemController@getOrder');
Route::resource('invyarnisusamsecitem', 'Inventory\Yarn\InvYarnIsuSamSecItemController');

Route::get('invyarnisurtnsamsec/report', 'Inventory\Yarn\InvYarnIsuRtnSamSecController@getPdf');
Route::resource('invyarnisurtnsamsec', 'Inventory\Yarn\InvYarnIsuRtnSamSecController');

Route::get('invyarnisurtnitem/getyarnisuitem', 'Inventory\Yarn\InvYarnIsuRtnItemController@getYarnItem');

Route::get('invyarnisurtnitemsamsec/getinvrcvyarnitem', 'Inventory\Yarn\InvYarnIsuRtnItemSamSecController@getRtnYarnItem');

Route::get('invyarnisurtnitemsamsec/getyarnsalesorder', 'Inventory\Yarn\InvYarnIsuRtnItemSamSecController@getRtnSaleOrder');

Route::get('invyarnisurtnitemsamsec/getstyle', 'Inventory\Yarn\InvYarnIsuRtnItemSamSecController@getStyle');
Route::get('invyarnisurtnitemsamsec/getsample', 'Inventory\Yarn\InvYarnIsuRtnItemSamSecController@getSample');

Route::get('invyarnisurtnitemsamsec/getrate', 'Inventory\Yarn\InvYarnIsuRtnItemSamSecController@getRate');

Route::resource('invyarnisurtnitemsamsec', 'Inventory\Yarn\InvYarnIsuRtnItemSamSecController');


Route::get('invdyechemrcv/report', 'Inventory\DyeChem\InvDyeChemRcvController@getPdf');
Route::resource('invdyechemrcv', 'Inventory\DyeChem\InvDyeChemRcvController');

Route::get('invdyechemrcvitem/getitem', 'Inventory\DyeChem\InvDyeChemRcvItemController@getItem');
Route::resource('invdyechemrcvitem', 'Inventory\DyeChem\InvDyeChemRcvItemController');

Route::get('invdyechemisurq/report', 'Inventory\DyeChem\InvDyeChemIsuRqController@getPdf');

Route::get('invdyechemisurq/getfabric', 'Inventory\DyeChem\InvDyeChemIsuRqController@getFabric');
Route::get('invdyechemisurq/getbatch', 'Inventory\DyeChem\InvDyeChemIsuRqController@getBatch');
Route::get('invdyechemisurq/operatoremployee', 'Inventory\DyeChem\InvDyeChemIsuRqController@getEmployeeHr');
Route::get('invdyechemisurq/employeeincharge', 'Inventory\DyeChem\InvDyeChemIsuRqController@getEmployeeHr');
Route::get('invdyechemisurq/getrq', 'Inventory\DyeChem\InvDyeChemIsuRqController@getRq');

Route::resource('invdyechemisurq', 'Inventory\DyeChem\InvDyeChemIsuRqController');

Route::get('invdyechemisurqitem/getitem', 'Inventory\DyeChem\InvDyeChemIsuRqItemController@getItem');
Route::get('invdyechemisurqitem/getmasterrq', 'Inventory\DyeChem\InvDyeChemIsuRqItemController@getMasterRq');
Route::get('invdyechemisurqitem/copyitem', 'Inventory\DyeChem\InvDyeChemIsuRqItemController@copyItem');
Route::resource('invdyechemisurqitem', 'Inventory\DyeChem\InvDyeChemIsuRqItemController');


Route::get('invdyechemisurqadd/report', 'Inventory\DyeChem\InvDyeChemIsuRqAddController@getPdf');
Route::get('invdyechemisurqadd/getrequisition', 'Inventory\DyeChem\InvDyeChemIsuRqAddController@getRequisition');
Route::get('invdyechemisurqadd/getrq', 'Inventory\DyeChem\InvDyeChemIsuRqAddController@getRq');
Route::resource('invdyechemisurqadd', 'Inventory\DyeChem\InvDyeChemIsuRqAddController');

Route::get('invdyechemisurqitemadd/getitem', 'Inventory\DyeChem\InvDyeChemIsuRqItemAddController@getItem');
Route::resource('invdyechemisurqitemadd', 'Inventory\DyeChem\InvDyeChemIsuRqItemAddController');


Route::get('invdyechemisurqaop/report', 'Inventory\DyeChem\InvDyeChemIsuRqAopController@getPdf');
Route::get('invdyechemisurqaop/reportold', 'Inventory\DyeChem\InvDyeChemIsuRqAopController@getPdfOld');
Route::get('invdyechemisurqaop/getbatch', 'Inventory\DyeChem\InvDyeChemIsuRqAopController@getBatch');
Route::get('invdyechemisurqaop/getrq', 'Inventory\DyeChem\InvDyeChemIsuRqAopController@getRq');
Route::get('invdyechemisurqaop/oldrq', 'Inventory\DyeChem\InvDyeChemIsuRqAopController@oldlist');
Route::resource('invdyechemisurqaop', 'Inventory\DyeChem\InvDyeChemIsuRqAopController');

Route::get('invdyechemisurqitemaop/getitem', 'Inventory\DyeChem\InvDyeChemIsuRqItemAopController@getItem');
//Route::get('invdyechemisurqitemaop/getorder', 'Inventory\DyeChem\InvDyeChemIsuRqItemAopController@getOrder');
//Route::get('invdyechemisurqitemaop/getprinttype', 'Inventory\DyeChem\InvDyeChemIsuRqItemAopController@getPrintType');

Route::get('invdyechemisurqitemaop/getmasterrq', 'Inventory\DyeChem\InvDyeChemIsuRqItemAopController@getMasterRq');
Route::get('invdyechemisurqitemaop/copyitem', 'Inventory\DyeChem\InvDyeChemIsuRqItemAopController@copyItem');

Route::resource('invdyechemisurqitemaop', 'Inventory\DyeChem\InvDyeChemIsuRqItemAopController');


Route::get('invdyechemisurqsrp/report', 'Inventory\DyeChem\InvDyeChemIsuRqSrpController@getPdf');
Route::get('invdyechemisurqsrp/getbatch', 'Inventory\DyeChem\InvDyeChemIsuRqSrpController@getBatch');
Route::get('invdyechemisurqsrp/getrq', 'Inventory\DyeChem\InvDyeChemIsuRqSrpController@getRq');
Route::resource('invdyechemisurqsrp', 'Inventory\DyeChem\InvDyeChemIsuRqSrpController');
Route::get('invdyechemisurqitemsrp/getitem', 'Inventory\DyeChem\InvDyeChemIsuRqItemSrpController@getItem');
Route::get('invdyechemisurqitemsrp/getorder', 'Inventory\DyeChem\InvDyeChemIsuRqItemSrpController@getOrder');
Route::get('invdyechemisurqitemsrp/getmasterrq', 'Inventory\DyeChem\InvDyeChemIsuRqItemSrpController@getMasterRq');
Route::get('invdyechemisurqitemsrp/copyitem', 'Inventory\DyeChem\InvDyeChemIsuRqItemSrpController@copyItem');
Route::resource('invdyechemisurqitemsrp', 'Inventory\DyeChem\InvDyeChemIsuRqItemSrpController');

Route::get('invdyechemisurqloan/report', 'Inventory\DyeChem\InvDyeChemIsuRqLoanController@getPdf');
Route::get('invdyechemisurqloan/getrq', 'Inventory\DyeChem\InvDyeChemIsuRqLoanController@getRq');
Route::resource('invdyechemisurqloan', 'Inventory\DyeChem\InvDyeChemIsuRqLoanController');


Route::get('invdyechemisurqitemloan/getitem', 'Inventory\DyeChem\InvDyeChemIsuRqItemLoanController@getItem');
Route::get('invdyechemisurqitemloan/getmachine', 'Inventory\DyeChem\InvDyeChemIsuRqItemLoanController@getMachine');
Route::resource('invdyechemisurqitemloan', 'Inventory\DyeChem\InvDyeChemIsuRqItemLoanController');

Route::get('invdyechemisu/report', 'Inventory\DyeChem\InvDyeChemIsuController@getPdf');
//Route::get('invdyechemisu/getrequisition', 'Inventory\DyeChem\InvDyeChemIsuController@getRequisition');
Route::get('invdyechemisu/getinvdyechemisulist', 'Inventory\DyeChem\InvDyeChemIsuController@getDyeChemIsuList');
Route::resource('invdyechemisu', 'Inventory\DyeChem\InvDyeChemIsuController');

Route::get('invdyechemisuitem/getitem', 'Inventory\DyeChem\InvDyeChemIsuItemController@getItem');
Route::resource('invdyechemisuitem', 'Inventory\DyeChem\InvDyeChemIsuItemController');


Route::get('invdyechemtransout/report', 'Inventory\DyeChem\InvDyeChemTransOutController@getPdf');
Route::resource('invdyechemtransout', 'Inventory\DyeChem\InvDyeChemTransOutController');

Route::get('invdyechemtransoutitem/getitem', 'Inventory\DyeChem\InvDyeChemTransOutItemController@getItem');
Route::get('invdyechemtransoutitem/getrate', 'Inventory\DyeChem\InvDyeChemTransOutItemController@getRate');
Route::resource('invdyechemtransoutitem', 'Inventory\DyeChem\InvDyeChemTransOutItemController');


Route::get('invdyechemtransin/report', 'Inventory\DyeChem\InvDyeChemTransInController@getPdf');
Route::resource('invdyechemtransin', 'Inventory\DyeChem\InvDyeChemTransInController');

Route::get('invdyechemtransinitem/getitem', 'Inventory\DyeChem\InvDyeChemTransInItemController@getItem');
Route::resource('invdyechemtransinitem', 'Inventory\DyeChem\InvDyeChemTransInItemController');


Route::get('invdyechemisurtn/report', 'Inventory\DyeChem\InvDyeChemIsuRtnController@getPdf');
Route::resource('invdyechemisurtn', 'Inventory\DyeChem\InvDyeChemIsuRtnController');

Route::get('invdyechemisurtnitem/getitem', 'Inventory\DyeChem\InvDyeChemIsuRtnItemController@getItem');
Route::get('invdyechemisurtnitem/getrate', 'Inventory\DyeChem\InvDyeChemIsuRtnItemController@getRate');
Route::get('invdyechemisurtnitem/getorder', 'Inventory\DyeChem\InvDyeChemIsuRtnItemController@getOrder');
Route::resource('invdyechemisurtnitem', 'Inventory\DyeChem\InvDyeChemIsuRtnItemController');

Route::get('invdyechemrcvrtn/report', 'Inventory\DyeChem\InvDyeChemRcvRtnController@getPdf');
Route::resource('invdyechemrcvrtn', 'Inventory\DyeChem\InvDyeChemRcvRtnController');

Route::get('invdyechemrcvrtnitem/getitem', 'Inventory\DyeChem\InvDyeChemRcvRtnItemController@getItem');
Route::resource('invdyechemrcvrtnitem', 'Inventory\DyeChem\InvDyeChemRcvRtnItemController');

Route::get('invtrimrcv/report', 'Inventory\Trim\InvTrimRcvController@getPdf');
Route::resource('invtrimrcv', 'Inventory\Trim\InvTrimRcvController');

Route::get('invtrimrcvitem/getitem', 'Inventory\Trim\InvTrimRcvItemController@getItem');
Route::resource('invtrimrcvitem', 'Inventory\Trim\InvTrimRcvItemController');




Route::get('invgeneralrcv/report', 'Inventory\GeneralStore\InvGeneralRcvController@getPdf');
Route::resource('invgeneralrcv', 'Inventory\GeneralStore\InvGeneralRcvController');

Route::get('invgeneralrcvitem/getitem', 'Inventory\GeneralStore\InvGeneralRcvItemController@getItem');
Route::resource('invgeneralrcvitem', 'Inventory\GeneralStore\InvGeneralRcvItemController');
Route::resource('invgeneralrcvitemdtl', 'Inventory\GeneralStore\InvGeneralRcvItemDtlController');

Route::get('invgeneralisurq/report', 'Inventory\GeneralStore\InvGeneralIsuRqController@getPdf');
Route::resource('invgeneralisurq', 'Inventory\GeneralStore\InvGeneralIsuRqController');

Route::get('invgeneralisurqitem/getitem', 'Inventory\GeneralStore\InvGeneralIsuRqItemController@getItem');
Route::get('invgeneralisurqitem/getorder', 'Inventory\GeneralStore\InvGeneralIsuRqItemController@getOrder');
Route::get('invgeneralisurqitem/getmachine', 'Inventory\GeneralStore\InvGeneralIsuRqItemController@getMachine');
Route::resource('invgeneralisurqitem', 'Inventory\GeneralStore\InvGeneralIsuRqItemController');

Route::get('jhutesaledlvorder/getdlvorderpdf', 'JhuteSale\JhuteSaleDlvOrderController@getDlvOrderPdf');
Route::resource('jhutesaledlvorder', 'JhuteSale\JhuteSaleDlvOrderController');
Route::resource('jhutesaledlvorderitem', 'JhuteSale\JhuteSaleDlvOrderItemController');
Route::resource('jhutesaledlvorderadviseby', 'JhuteSale\JhuteSaleDlvOrderAdviseByController');
Route::resource('jhutesaledlvorderpriceverify', 'JhuteSale\JhuteSaleDlvOrderPriceVerifyController');
Route::resource('jhutesaledlvorderpayment', 'JhuteSale\JhuteSaleDlvOrderPaymentController');

Route::get('gmtleftoversaleorder/getpdf', 'JhuteSale\GmtLeftoverSaleOrderController@getPdf');
Route::resource('gmtleftoversaleorder', 'JhuteSale\GmtLeftoverSaleOrderController');
Route::get(
 'gmtleftoversaleorderstyledtl/gmtleftoverstyle',
 'JhuteSale\GmtLeftoverSaleOrderStyleDtlController@getGmtLeftoverStyle'
);
Route::resource('gmtleftoversaleorderstyledtl', 'JhuteSale\GmtLeftoverSaleOrderStyleDtlController');
Route::resource('gmtleftoversaleorderqty', 'JhuteSale\GmtLeftoverSaleOrderQtyController');
Route::resource('gmtleftoversaleorderpayment', 'JhuteSale\GmtLeftoverSaleOrderPaymentController');

Route::get('jhutesaledlv/getbillpdf', 'JhuteSale\JhuteSaleDlvController@getBillPdf');
Route::get('jhutesaledlv/getchallanpdf', 'JhuteSale\JhuteSaleDlvController@getChallanPdf');
Route::get('jhutesaledlv/getjhutesaledlvorder', 'JhuteSale\JhuteSaleDlvController@getJhuteSaleDlvOrder');
Route::resource('jhutesaledlv', 'JhuteSale\JhuteSaleDlvController');
Route::get('jhutesaledlvitem/getjhutesaledlvorderitem', 'JhuteSale\JhuteSaleDlvItemController@getJhuteSaleDlvOrderItem');
Route::resource('jhutesaledlvitem', 'JhuteSale\JhuteSaleDlvItemController');

Route::get('invgeneralisu/report', 'Inventory\GeneralStore\InvGeneralIsuController@getPdf');
Route::resource('invgeneralisu', 'Inventory\GeneralStore\InvGeneralIsuController');

Route::get('invgeneralisuitem/getitem', 'Inventory\GeneralStore\InvGeneralIsuItemController@getItem');
Route::get('invgeneralisuitem/getorder', 'Inventory\GeneralStore\InvGeneralIsuItemController@getOrder');
Route::resource('invgeneralisuitem', 'Inventory\GeneralStore\InvGeneralIsuItemController');


Route::get('invgeneraltransout/report', 'Inventory\GeneralStore\InvGeneralTransOutController@getPdf');
Route::resource('invgeneraltransout', 'Inventory\GeneralStore\InvGeneralTransOutController');

Route::get('invgeneraltransoutitem/getitem', 'Inventory\GeneralStore\InvGeneralTransOutItemController@getItem');
Route::get('invgeneraltransoutitem/getrate', 'Inventory\GeneralStore\InvGeneralTransOutItemController@getRate');
Route::resource('invgeneraltransoutitem', 'Inventory\GeneralStore\InvGeneralTransOutItemController');

Route::get('invgeneraltransin/report', 'Inventory\GeneralStore\InvGeneralTransInController@getPdf');
Route::resource('invgeneraltransin', 'Inventory\GeneralStore\InvGeneralTransInController');

Route::get('invgeneraltransinitem/getitem', 'Inventory\GeneralStore\InvGeneralTransInItemController@getItem');
Route::resource('invgeneraltransinitem', 'Inventory\GeneralStore\InvGeneralTransInItemController');

Route::get('invgeneralisurtn/report', 'Inventory\GeneralStore\InvGeneralIsuRtnController@getPdf');
Route::resource('invgeneralisurtn', 'Inventory\GeneralStore\InvGeneralIsuRtnController');

Route::get('invgeneralisurtnitem/getitem', 'Inventory\GeneralStore\InvGeneralIsuRtnItemController@getItem');
Route::get('invgeneralisurtnitem/getrate', 'Inventory\GeneralStore\InvGeneralIsuRtnItemController@getRate');
Route::get('invgeneralisurtnitem/getorder', 'Inventory\GeneralStore\InvGeneralIsuRtnItemController@getOrder');
Route::resource('invgeneralisurtnitem', 'Inventory\GeneralStore\InvGeneralIsuRtnItemController');


Route::get('invgeneralrcvrtn/report', 'Inventory\GeneralStore\InvGeneralRcvRtnController@getPdf');
Route::resource('invgeneralrcvrtn', 'Inventory\GeneralStore\InvGeneralRcvRtnController');

Route::get('invgeneralrcvrtnitem/getitem', 'Inventory\GeneralStore\InvGeneralRcvRtnItemController@getItem');
Route::resource('invgeneralrcvrtnitem', 'Inventory\GeneralStore\InvGeneralRcvRtnItemController');


Route::get('invgreyfabrcv/getchallan', 'Inventory\GreyFabric\InvGreyFabRcvController@getChallan');
Route::get('invgreyfabrcv/report', 'Inventory\GreyFabric\InvGreyFabRcvController@getPdf');
Route::get('invgreyfabrcv/reporttwo', 'Inventory\GreyFabric\InvGreyFabRcvController@getPdfTwo');
Route::resource('invgreyfabrcv', 'Inventory\GreyFabric\InvGreyFabRcvController');

Route::get('invgreyfabrcvitem/getgreyfabitem', 'Inventory\GreyFabric\InvGreyFabRcvItemController@getGreyFabItem');
Route::resource('invgreyfabrcvitem', 'Inventory\GreyFabric\InvGreyFabRcvItemController');


//Route::get('invgreyfabisu/getchallan', 'Inventory\GreyFabric\InvGreyFabIsuController@getChallan');
Route::get('invgreyfabisu/report', 'Inventory\GreyFabric\InvGreyFabIsuController@getPdf');
Route::get('invgreyfabisu/reporttwo', 'Inventory\GreyFabric\InvGreyFabIsuController@getPdfTwo');
Route::get('invgreyfabisu/getinvgreyfabisulist', 'Inventory\GreyFabric\InvGreyFabIsuController@getInvGreyFabIsuList');
Route::resource('invgreyfabisu', 'Inventory\GreyFabric\InvGreyFabIsuController');

Route::get('invgreyfabisuitem/getgreyfabitem', 'Inventory\GreyFabric\InvGreyFabIsuItemController@getGreyFabItem');
Route::get('invgreyfabisuitem/getorder', 'Inventory\GreyFabric\InvGreyFabIsuItemController@getOrder');
Route::get('invgreyfabisuitem/getstyle', 'Inventory\GreyFabric\InvGreyFabIsuItemController@getStyle');
Route::resource('invgreyfabisuitem', 'Inventory\GreyFabric\InvGreyFabIsuItemController');


Route::get('invgreyfabtransout/report', 'Inventory\GreyFabric\InvGreyFabTransOutController@getPdf');
Route::get('invgreyfabtransout/reporttwo', 'Inventory\GreyFabric\InvGreyFabTransOutController@getPdfTwo');
Route::resource('invgreyfabtransout', 'Inventory\GreyFabric\InvGreyFabTransOutController');

Route::get('invgreyfabtransoutitem/getgreyfabitem', 'Inventory\GreyFabric\InvGreyFabTransOutItemController@getGreyFabItem');
Route::get('invgreyfabtransoutitem/getorder', 'Inventory\GreyFabric\InvGreyFabTransOutItemController@getOrder');
Route::get('invgreyfabtransoutitem/getstyle', 'Inventory\GreyFabric\InvGreyFabTransOutItemController@getStyle');
Route::resource('invgreyfabtransoutitem', 'Inventory\GreyFabric\InvGreyFabTransOutItemController');


Route::get('invgreyfabtransin/getchallan', 'Inventory\GreyFabric\InvGreyFabTransInController@getChallan');
Route::get('invgreyfabtransin/report', 'Inventory\GreyFabric\InvGreyFabTransInController@getPdf');
Route::get('invgreyfabtransin/reporttwo', 'Inventory\GreyFabric\InvGreyFabTransInController@getPdfTwo');
Route::resource('invgreyfabtransin', 'Inventory\GreyFabric\InvGreyFabTransInController');

Route::get('invgreyfabtransinitem/getitem', 'Inventory\GreyFabric\InvGreyFabTransInItemController@getItem');
Route::resource('invgreyfabtransinitem', 'Inventory\GreyFabric\InvGreyFabTransInItemController');

Route::get('invgreyfabisurtn/report', 'Inventory\GreyFabric\InvGreyFabIsuRtnController@getPdf');
Route::get('invgreyfabisurtn/reporttwo', 'Inventory\GreyFabric\InvGreyFabIsuRtnController@getPdfTwo');
Route::resource('invgreyfabisurtn', 'Inventory\GreyFabric\InvGreyFabIsuRtnController');

Route::get('invgreyfabisurtnitem/getitem', 'Inventory\GreyFabric\InvGreyFabIsuRtnItemController@getItem');
Route::resource('invgreyfabisurtnitem', 'Inventory\GreyFabric\InvGreyFabIsuRtnItemController');

//*************************FinishFabric********************
Route::get('invfinishfabrcv/getchallan', 'Inventory\FinishFabric\InvFinishFabRcvController@getChallan');
Route::get('invfinishfabrcv/report', 'Inventory\FinishFabric\InvFinishFabRcvController@getPdf');
Route::get('invfinishfabrcv/reporttwo', 'Inventory\FinishFabric\InvFinishFabRcvController@getPdfTwo');
Route::resource('invfinishfabrcv', 'Inventory\FinishFabric\InvFinishFabRcvController');

Route::get('invfinishfabrcvitem/getfinishfabitem', 'Inventory\FinishFabric\InvFinishFabRcvItemController@getFinishFabItem');
Route::resource('invfinishfabrcvitem', 'Inventory\FinishFabric\InvFinishFabRcvItemController');

Route::get('invfinishfabrcvpur/report', 'Inventory\FinishFabric\InvFinishFabRcvPurController@getPdf');
Route::get('invfinishfabrcvpur/getchallan', 'Inventory\FinishFabric\InvFinishFabRcvPurController@getPdfTwo');
Route::get('invfinishfabrcvpur/getpo', 'Inventory\FinishFabric\InvFinishFabRcvPurController@getPo');
Route::resource('invfinishfabrcvpur', 'Inventory\FinishFabric\InvFinishFabRcvPurController');

Route::get('invfinishfabrcvpurfabric/getfabric', 'Inventory\FinishFabric\InvFinishFabRcvPurFabricController@getFabric');
Route::resource('invfinishfabrcvpurfabric', 'Inventory\FinishFabric\InvFinishFabRcvPurFabricController');

Route::resource('invfinishfabrcvpuritem', 'Inventory\FinishFabric\InvFinishFabRcvPurItemController');


//Route::get('invfinishfabisu/getchallan', 'Inventory\FinishFabric\InvFinishFabIsuController@getChallan');
Route::get('invfinishfabisu/report', 'Inventory\FinishFabric\InvFinishFabIsuController@getPdf');
Route::get('invfinishfabisu/reporttwo', 'Inventory\FinishFabric\InvFinishFabIsuController@getPdfTwo');
Route::resource('invfinishfabisu', 'Inventory\FinishFabric\InvFinishFabIsuController');

Route::get('invfinishfabisuitem/getfinishfabitem', 'Inventory\FinishFabric\InvFinishFabIsuItemController@getFinishFabItem');
Route::get('invfinishfabisuitem/getorder', 'Inventory\FinishFabric\InvFinishFabIsuItemController@getOrder');
Route::get('invfinishfabisuitem/getstyle', 'Inventory\FinishFabric\InvFinishFabIsuItemController@getStyle');
Route::resource('invfinishfabisuitem', 'Inventory\FinishFabric\InvFinishFabIsuItemController');


Route::get('invfinishfabtransout/report', 'Inventory\FinishFabric\InvFinishFabTransOutController@getPdf');
Route::get('invfinishfabtransout/reporttwo', 'Inventory\FinishFabric\InvFinishFabTransOutController@getPdfTwo');
Route::resource('invfinishfabtransout', 'Inventory\FinishFabric\InvFinishFabTransOutController');

Route::get('invfinishfabtransoutitem/getfinishfabitem', 'Inventory\FinishFabric\InvFinishFabTransOutItemController@getFinishFabItem');
Route::get('invfinishfabtransoutitem/getorder', 'Inventory\FinishFabric\InvFinishFabTransOutItemController@getOrder');
Route::get('invfinishfabtransoutitem/getstyle', 'Inventory\FinishFabric\InvFinishFabTransOutItemController@getStyle');
Route::resource('invfinishfabtransoutitem', 'Inventory\FinishFabric\InvFinishFabTransOutItemController');


Route::get('invfinishfabtransin/getchallan', 'Inventory\FinishFabric\InvFinishFabTransInController@getChallan');
Route::get('invfinishfabtransin/report', 'Inventory\FinishFabric\InvFinishFabTransInController@getPdf');
Route::get('invfinishfabtransin/reporttwo', 'Inventory\FinishFabric\InvFinishFabTransInController@getPdfTwo');
Route::resource('invfinishfabtransin', 'Inventory\FinishFabric\InvFinishFabTransInController');

Route::get('invfinishfabtransinitem/getitem', 'Inventory\FinishFabric\InvFinishFabTransInItemController@getItem');
Route::resource('invfinishfabtransinitem', 'Inventory\FinishFabric\InvFinishFabTransInItemController');

Route::get('invfinishfabisurtn/report', 'Inventory\FinishFabric\InvFinishFabIsuRtnController@getPdf');
Route::get('invfinishfabisurtn/reporttwo', 'Inventory\FinishFabric\InvFinishFabIsuRtnController@getPdfTwo');
Route::resource('invfinishfabisurtn', 'Inventory\FinishFabric\InvFinishFabIsuRtnController');

Route::get('invfinishfabisurtnitem/getitem', 'Inventory\FinishFabric\InvFinishFabIsuRtnItemController@getItem');
Route::resource('invfinishfabisurtnitem', 'Inventory\FinishFabric\InvFinishFabIsuRtnItemController');




Route::resource('assetacquisition', 'FAMS\AssetAcquisitionController');
Route::resource('assetdepreciation', 'FAMS\AssetDepreciationController');
Route::resource('assetquantitycost', 'FAMS\AssetQuantityCostController');

Route::resource('assettechfeature', 'FAMS\AssetTechnicalFeatureController');
Route::resource('assetutilitydetail', 'FAMS\AssetUtilityDetailController');
Route::resource('assetmaintenance', 'FAMS\AssetMaintenanceController');

Route::resource('assettechfileupload', 'FAMS\AssetTechFileUploadController');
Route::resource('assettechimage', 'FAMS\AssetTechImageController');

Route::get('assetmanpower/getmachine', 'FAMS\AssetManpowerController@getMachine');
Route::get('assetmanpower/getemployee', 'FAMS\AssetManpowerController@getEmployee');
Route::resource('assetmanpower', 'FAMS\AssetManpowerController');

Route::get('assetbreakdown/getassetdtls', 'FAMS\AssetBreakdownController@getAssetDtls');
Route::get('assetbreakdown/getbreakdownlist', 'FAMS\AssetBreakdownController@getBreakdownList');
Route::get('assetbreakdown/getemployee', 'FAMS\AssetBreakdownController@getEmployeeHr');
Route::resource('assetbreakdown', 'FAMS\AssetBreakdownController');
Route::resource('assetrecovery', 'FAMS\AssetRecoveryController');

Route::get('assetdisposal/getdisposalpdf', 'FAMS\AssetDisposalController@getDisposalPdf');
Route::get('assetdisposal/getasset', 'FAMS\AssetDisposalController@getAsset');
Route::resource('assetdisposal', 'FAMS\AssetDisposalController');

Route::get('assetservicerepair/getassetbreakdown', 'FAMS\AssetServiceRepairController@getAssetBreakdown');
Route::get('assetservicerepair/getassetrepairpdf', 'FAMS\AssetServiceRepairController@getAssetRepairPdf');
Route::resource('assetservicerepair', 'FAMS\AssetServiceRepairController');
Route::get('assetservicerepairpart/getassetservicerepairpart', 'FAMS\AssetServiceRepairPartController@getAssetServicePart');
Route::resource('assetservicerepairpart', 'FAMS\AssetServiceRepairPartController');

Route::get('assetservice/getassetservicepdf', 'FAMS\AssetServiceController@getAssetServicePdf');
Route::resource('assetservice', 'FAMS\AssetServiceController');
Route::get('assetservicedetail/getasset', 'FAMS\AssetServiceDetailController@getAsset');
Route::resource('assetservicedetail', 'FAMS\AssetServiceDetailController');

Route::get('employeehr/getuser', 'HRM\EmployeeHRController@getUser');
Route::get('employeehr/toreportemployee', 'HRM\EmployeeHRController@getReportEmployee');
Route::get('employeehr/appointletter', 'HRM\EmployeeHRController@getAppointLetter');
Route::get('employeehr/getndapdf', 'HRM\EmployeeHRController@getNDAPdf');
//Route::get('employeehr/getuser', 'HRM\EmployeeHRController@getUser');

Route::resource('employeehr', 'HRM\EmployeeHRController');
Route::resource('employeehrjob', 'HRM\EmployeeHRJobController');
Route::resource('employeetodolist', 'HRM\EmployeeToDoListController');
Route::resource('employeetodolisttask', 'HRM\EmployeeToDoListTaskController');
Route::resource('employeetodolisttaskbar', 'HRM\EmployeeToDoListTaskBarController');
Route::resource('employeehrleave', 'HRM\EmployeeHRLeaveController');
Route::get('employeehrstatus/getemployeehr', 'HRM\EmployeeHRStatusController@getEmployeeHr');
Route::get('employeehrstatus/sendtoaip', 'HRM\EmployeeHRStatusController@sendToApi');
Route::get('employeehrstatus/getallemployeestatus', 'HRM\EmployeeHRStatusController@getAllEmployeeStatus');
Route::resource('employeehrstatus', 'HRM\EmployeeHRStatusController');
Route::get('employeemovement/getemployeehrm', 'HRM\EmployeeMovementController@getEmployee');
Route::get('employeemovement/getempticket', 'HRM\EmployeeMovementController@empTicket');
Route::resource('employeemovement', 'HRM\EmployeeMovementController');
Route::resource('employeemovementdtl', 'HRM\EmployeeMovementDtlController');

Route::get('employeetransfer/getemployeehr', 'HRM\EmployeeTransferController@getEmployeeHr');
Route::get('employeetransfer/toreportemployee', 'HRM\EmployeeTransferController@getReportEmployee');
Route::resource('employeetransfer', 'HRM\EmployeeTransferController');
Route::resource('employeetransferjob', 'HRM\EmployeeTransferJobController');


Route::get('employeepromotion/getemployeehr', 'HRM\EmployeePromotionController@getEmployeeHr');
Route::get('employeepromotion/toreportemployee', 'HRM\EmployeePromotionController@getReportEmployee');
Route::resource('employeepromotion', 'HRM\EmployeePromotionController');
Route::resource('employeepromotionjob', 'HRM\EmployeePromotionJobController');

Route::resource('store', 'Util\StoreController');
Route::resource('storeitemcategory', 'Util\StoreItemcategoryController');

Route::resource('exppi', 'Commercial\Export\ExpPiController');
Route::get('exppiorder/importorder', 'Commercial\Export\ExpPiOrderController@importorder');
Route::resource('exppiorder', 'Commercial\Export\ExpPiOrderController');

/* Route::get('expsalescontract/reportsales', 'Commercial\Export\ExpSalesContractController@getPdf');
Route::resource('expsalescontract', 'Commercial\Export\ExpSalesContractController');


Route::get('exprepsalescon/importrepsc', 'Commercial\Export\ExpRepSalesContractController@importrepsc');
Route::resource('exprepsalescon', 'Commercial\Export\ExpRepSalesContractController');
Route::get('salescontractpi/importpi', 'Commercial\Export\ExpSalesContractPiController@importpi');
Route::resource('salescontractpi', 'Commercial\Export\ExpSalesContractPiController'); */


Route::get('explcsc/reportsales', 'Commercial\Export\ExpLcScController@getPdf');
Route::get('explcsc/sclienlatter', 'Commercial\Export\ExpLcScController@getScLienLetter');
Route::get('explcsc/scamendmentlatter', 'Commercial\Export\ExpLcScController@getScAmendmentLetter');
Route::resource('explcsc', 'Commercial\Export\ExpLcScController');
Route::get('expreplcsc/importrepsc', 'Commercial\Export\ExpRepLcScController@importrepsc');
Route::resource('expreplcsc', 'Commercial\Export\ExpRepLcScController');
Route::get('lcscpi/importpi', 'Commercial\Export\ExpLcScPiController@importpi');
Route::resource('lcscpi', 'Commercial\Export\ExpLcScPiController');

Route::get('expscorder/importorder', 'Commercial\Export\ExpScOrderController@importorder');
Route::resource('expscorder', 'Commercial\Export\ExpScOrderController');

Route::get('explc/lclienlatter', 'Commercial\Export\ExpLcController@getLcLetterPdf');
Route::get('explc/lcamendmentlatter', 'Commercial\Export\ExpLcController@getLcAmendmentLetter');
Route::resource('explc', 'Commercial\Export\ExpLcController');
Route::get('explctagpi/importpi', 'Commercial\Export\ExpLcTagPiController@importpi');
Route::resource('explctagpi', 'Commercial\Export\ExpLcTagPiController');
Route::get('explcorder/importorder', 'Commercial\Export\ExpLcOrderController@importorder');
Route::resource('explcorder', 'Commercial\Export\ExpLcOrderController');
Route::get('expreplc/importreplc', 'Commercial\Export\ExpRepLcController@importreplc');
Route::resource('expreplc', 'Commercial\Export\ExpRepLcController');

Route::get('expprecredit/getpc', 'Commercial\Export\ExpPreCreditController@getPc');
Route::get('expprecredit/getbankaccount', 'Commercial\Export\ExpPreCreditController@getBankAccount');
Route::resource('expprecredit', 'Commercial\Export\ExpPreCreditController');
Route::get('expprecreditlcsc/getExpLcSc', 'Commercial\Export\ExpPreCreditLcScController@getExpLcSc');
Route::resource('expprecreditlcsc', 'Commercial\Export\ExpPreCreditLcScController');

Route::get('expadvinvoice/getlcsc', 'Commercial\Export\ExpAdvInvoiceController@getLcSc');
Route::get('expadvinvoice/orderwiseinvoice', 'Commercial\Export\ExpAdvInvoiceController@getOrderWiseAdvExpCi');
Route::get('expadvinvoice/billofexchange', 'Commercial\Export\ExpAdvInvoiceController@getBoe');
Route::get('expadvinvoice/forward', 'Commercial\Export\ExpAdvInvoiceController@getForwardLetter');
Route::resource('expadvinvoice', 'Commercial\Export\ExpAdvInvoiceController');
Route::resource('expadvinvoiceorder', 'Commercial\Export\ExpAdvInvoiceOrderController');
Route::resource('expadvinvoiceorderdtl', 'Commercial\Export\ExpAdvInvoiceOrderDtlController');

Route::get('expinvoice/getlcsc', 'Commercial\Export\ExpInvoiceController@getLcSc');
Route::get('expinvoice/getadvanceinvoice', 'Commercial\Export\ExpInvoiceController@getAdvanceInvoice');
Route::get('expinvoice/openexpci', 'Commercial\Export\ExpInvoiceController@OpenExpCi');
Route::get('expinvoice/orderwiseinvoice', 'Commercial\Export\ExpInvoiceController@getOrderWiseExpCi');
Route::get('expinvoice/colorsizeinvoice', 'Commercial\Export\ExpInvoiceController@getColorSizeExpCi');
Route::get('expinvoice/colorwiseinvoice', 'Commercial\Export\ExpInvoiceController@getColorWiseExpCi');
Route::get('expinvoice/sizewiseinvoice', 'Commercial\Export\ExpInvoiceController@getSizeWiseExpCi');
Route::get('expinvoice/bnfdeclaration', 'Commercial\Export\ExpInvoiceController@bnfDeclaration');
Route::get('expinvoice/confirmletter', 'Commercial\Export\ExpInvoiceController@confirmLetter');
Route::get('expinvoice/shipperconfirm', 'Commercial\Export\ExpInvoiceController@shipperConfirm');
Route::get('expinvoice/shippercertificatedeclare', 'Commercial\Export\ExpInvoiceController@shipperCertificateDeclare');
Route::get('expinvoice/bnfconfirmazo', 'Commercial\Export\ExpInvoiceController@bnfConfirmAzo');
Route::get('expinvoice/certifybanazo', 'Commercial\Export\ExpInvoiceController@certifyBanAzo');
Route::get('expinvoice/portofentry', 'Commercial\Export\ExpInvoiceController@getPortOfEntry');
Route::get('expinvoice/portofloading', 'Commercial\Export\ExpInvoiceController@getPortOfLoading');
Route::get('expinvoice/portofdischarge', 'Commercial\Export\ExpInvoiceController@getPortOfDischarge');
Route::resource('expinvoice', 'Commercial\Export\ExpInvoiceController');
Route::resource('expinvoiceorder', 'Commercial\Export\ExpInvoiceOrderController');
Route::resource('expinvoiceorderdtl', 'Commercial\Export\ExpInvoiceOrderDtlController');

Route::resource('explcscrevise', 'Commercial\Export\ExpLcScReviseController');
Route::resource('explcrevise', 'Commercial\Export\ExpLcScReviseController');

Route::get('exfactory/getSalesJS', 'Sales\ExFactoryController@getSalesJS');
Route::resource('exfactory', 'Sales\ExFactoryController');

Route::get('implc/latter', 'Commercial\Import\ImpLcController@getLatter');
Route::get('implc/creditlatter', 'Commercial\Import\ImpLcController@getCreditLatter');
Route::get('implc/getimplcbankaccount', 'Commercial\Import\ImpLcController@GetimplcBankAccount');
Route::resource('implc', 'Commercial\Import\ImpLcController');
Route::get('implcpo/importpo', 'Commercial\Import\ImpLcPoController@importpo');
Route::resource('implcpo', 'Commercial\Import\ImpLcPoController');
Route::get('impbackedexplcsc/importlcsc', 'Commercial\Import\ImpBackedExpLcScController@importlcsc');
Route::resource('impbackedexplcsc', 'Commercial\Import\ImpBackedExpLcScController');
Route::resource('implcfile', 'Commercial\Import\ImpLcFileController');

Route::get('expprorlz/gethead', 'Commercial\Export\ExpProRlzController@gethead');
Route::get('expprorlz/importdocsubmission', 'Commercial\Export\ExpProRlzController@importdocsubmission');
Route::get('expprorlz/getloanref', 'Commercial\Export\ExpProRlzController@getLoanRef');
Route::resource('expprorlz', 'Commercial\Export\ExpProRlzController');
Route::resource('expprorlzdeduct', 'Commercial\Export\ExpProRlzDeductController');
Route::resource('expprorlzamount', 'Commercial\Export\ExpProRlzAmountController');

Route::get('expdocsubmission/getdocsublc', 'Commercial\Export\ExpDocSubmissionController@getDocSubLc');
Route::get('expdocsubmission/latter', 'Commercial\Export\ExpDocSubmissionController@getLatter');
Route::get('expdocsubmission/forward', 'Commercial\Export\ExpDocSubmissionController@getForwardLetter');
Route::get('expdocsubmission/billofexchange', 'Commercial\Export\ExpDocSubmissionController@getBoe');
Route::resource('expdocsubmission', 'Commercial\Export\ExpDocSubmissionController');
Route::resource('expdocsubinvoice', 'Commercial\Export\ExpDocSubInvoiceController');
Route::get('expdocsubtransection/getbankaccount', 'Commercial\Export\ExpDocSubTransectionController@getBankAccount');
Route::resource('expdocsubtransection', 'Commercial\Export\ExpDocSubTransectionController');

Route::get('expdocsubmissionbuyer/getdocsubbuyerlc', 'Commercial\Export\ExpDocSubmissionBuyerController@getDocSubBuyerLc');
Route::resource('expdocsubmissionbuyer', 'Commercial\Export\ExpDocSubmissionBuyerController');
Route::resource('expdocsubbuyerinvoice', 'Commercial\Export\ExpDocSubBuyerInvoiceController');

Route::resource('impbankcharge', 'Commercial\Import\ImpBankChargeController');
Route::resource('impshippingmark', 'Commercial\Import\ImpShippingMarkController');

Route::get('impdocaccept/getbankaccount', 'Commercial\Import\ImpDocAcceptController@getBankAccount');
Route::get('impdocaccept/getImportLc', 'Commercial\Import\ImpDocAcceptController@getImportLc');
Route::get('impdocaccept/mlatter', 'Commercial\Import\ImpDocAcceptController@getMatureLetter');
Route::resource('impdocaccept', 'Commercial\Import\ImpDocAcceptController');
Route::resource('impacccomdetail', 'Commercial\Import\ImpAccComDetailController');
Route::get('impdocacceptmaturity/getimpmaturedoc', 'Commercial\Import\ImpDocAcceptMaturityController@getImpDocAccept');
Route::resource('impdocacceptmaturity', 'Commercial\Import\ImpDocAcceptMaturityController');

Route::resource('impdocmaturity', 'Commercial\Import\ImpDocMaturityController');
Route::get('impdocmaturitydtl/getimpmaturedoc', 'Commercial\Import\ImpDocMaturityDtlController@getImpDocAccept');
Route::resource('impdocmaturitydtl', 'Commercial\Import\ImpDocMaturityDtlController');

Route::resource('commercialhead', 'Util\CommercialHeadController');

Route::get('impliabilityadjust/GetImpDocAccept', 'Commercial\Import\ImpLiabilityAdjustController@GetImpDocAccept');
Route::resource('impliabilityadjust', 'Commercial\Import\ImpLiabilityAdjustController');
Route::get('impliabilityadjustchld/impgetbankaccount', 'Commercial\Import\ImpLiabilityAdjustChldController@getBankAccount');
Route::resource('impliabilityadjustchld', 'Commercial\Import\ImpLiabilityAdjustChldController');

Route::get('cashincentiveref/explccashref', 'Commercial\CashIncentive\CashIncentiveRefController@expLcCashRef');
Route::get('cashincentiveref/getkhaform', 'Commercial\CashIncentive\CashIncentiveRefController@getKhaForm');
Route::get('cashincentiveref/getcop', 'Commercial\CashIncentive\CashIncentiveRefController@getCOP');
Route::get('cashincentiveref/getdeclare', 'Commercial\CashIncentive\CashIncentiveRefController@declareLetter');
Route::get('cashincentiveref/forwardletter', 'Commercial\CashIncentive\CashIncentiveRefController@forwardLetter');
Route::get('cashincentiveref/getnetwgt', 'Commercial\CashIncentive\CashIncentiveRefController@getNetWgt');
Route::get('cashincentiveref/btbcertificate', 'Commercial\CashIncentive\CashIncentiveRefController@BTBcertificate');
Route::get('cashincentiveref/undertaking', 'Commercial\CashIncentive\CashIncentiveRefController@underTaking');
Route::resource('cashincentiveref', 'Commercial\CashIncentive\CashIncentiveRefController');

Route::get('cashincentiveadv/getadvanceletter', 'Commercial\CashIncentive\CashIncentiveAdvController@advanceLetter');
Route::resource('cashincentiveadv', 'Commercial\CashIncentive\CashIncentiveAdvController');
Route::get('cashincentiveadvclaim/getcashincentiveref', 'Commercial\CashIncentive\CashIncentiveAdvClaimController@getCashRef');
Route::resource('cashincentiveadvclaim', 'Commercial\CashIncentive\CashIncentiveAdvClaimController');

Route::get('/localexppireport', 'Report\Commercial\LocalExpPiReportController@index')->name('index');
Route::get('/localexppireport/getdata', 'Report\Commercial\LocalExpPiReportController@getData');
Route::get('/localexppireport/getlocalexportitem', 'Report\Commercial\LocalExpPiReportController@getExpItemDetail');
Route::get('/pendingimplcporeport', 'Report\Commercial\PendingImpLcPoReportController@index')->name('index');
Route::get('/pendingimplcporeport/getdata', 'Report\Commercial\PendingImpLcPoReportController@reportData');



Route::resource('cashincentivedocprep', 'Commercial\CashIncentive\CashIncentiveDocPrepController');

Route::get('cashincentiveyarnbtblc/getbtbimplc', 'Commercial\CashIncentive\CashIncentiveYarnBtbLcController@getBtbImpLc');
Route::get('cashincentiveyarnbtblc/getbtbpoyarnitemdesc', 'Commercial\CashIncentive\CashIncentiveYarnBtbLcController@getYarnBtpItemDesc');
Route::resource('cashincentiveyarnbtblc', 'Commercial\CashIncentive\CashIncentiveYarnBtbLcController');
Route::get('cashincentiveclaim/getexpdocinvoice', 'Commercial\CashIncentive\CashIncentiveClaimController@getDocInvoice');
Route::resource('cashincentiveclaim', 'Commercial\CashIncentive\CashIncentiveClaimController');

Route::get('cashincentiveloan/getclaim', 'Commercial\CashIncentive\CashIncentiveLoanController@getCashRef');
Route::resource('cashincentiveloan', 'Commercial\CashIncentive\CashIncentiveLoanController');

Route::resource('cashincentivefile', 'Commercial\CashIncentive\CashIncentiveFileController');
Route::resource('cashincentivefilequery', 'Commercial\CashIncentive\CashIncentiveFileQueryController');

Route::get('cashincentiverealize/cashreference', 'Commercial\CashIncentive\CashIncentiveRealizeController@getCashReference');
Route::resource('cashincentiverealize', 'Commercial\CashIncentive\CashIncentiveRealizeController');
Route::resource('cashincentiverealizercv', 'Commercial\CashIncentive\CashIncentiveRealizeRcvController');
//Local Export
Route::get('localexppi/getbank', 'Commercial\LocalExport\LocalExpPiController@getAdviseBank');
Route::get('localexppi/getlocalpireport', 'Commercial\LocalExport\LocalExpPiController@localPiPdf');
Route::get('localexppi/getshortpi', 'Commercial\LocalExport\LocalExpPiController@localShortPiPdf');
Route::resource('localexppi', 'Commercial\LocalExport\LocalExpPiController');
Route::get('localexppiorder/importlocalorder', 'Commercial\LocalExport\LocalExpPiOrderController@getInboundSaleOrder');
Route::resource('localexppiorder', 'Commercial\LocalExport\LocalExpPiOrderController');

Route::resource('localexplc', 'Commercial\LocalExport\LocalExpLcController');
Route::get('localexplctagpi/importlocalpi', 'Commercial\LocalExport\LocalExpLcTagPiController@importLocalPi');
Route::resource('localexplctagpi', 'Commercial\LocalExport\LocalExpLcTagPiController');

Route::get('localexpinvoice/getlocallc', 'Commercial\LocalExport\LocalExpInvoiceController@getLocalLc');
Route::resource('localexpinvoice', 'Commercial\LocalExport\LocalExpInvoiceController');
Route::resource('localexpinvoiceorder', 'Commercial\LocalExport\LocalExpInvoiceOrderController');

Route::get('localexpdocsubaccept/getlocalexportlc', 'Commercial\LocalExport\LocalExpDocSubAcceptController@getLocalExportLc');
Route::get('localexpdocsubaccept/openCi', 'Commercial\LocalExport\LocalExpDocSubAcceptController@OpenCi');
Route::get('localexpdocsubaccept/getci', 'Commercial\LocalExport\LocalExpDocSubAcceptController@getCIPdf');
Route::get('localexpdocsubaccept/deliverychallan', 'Commercial\LocalExport\LocalExpDocSubAcceptController@getDCPdf');
Route::get('localexpdocsubaccept/billofexchange', 'Commercial\LocalExport\LocalExpDocSubAcceptController@getBOEPdf');
Route::get('localexpdocsubaccept/packinglist', 'Commercial\LocalExport\LocalExpDocSubAcceptController@getPLPdf');
Route::get('localexpdocsubaccept/certificateoforigin', 'Commercial\LocalExport\LocalExpDocSubAcceptController@getCOEPdf');
Route::get('localexpdocsubaccept/bnfcertificate', 'Commercial\LocalExport\LocalExpDocSubAcceptController@getBCPdf');
Route::get('localexpdocsubaccept/forwardingletter', 'Commercial\LocalExport\LocalExpDocSubAcceptController@getForwardLetterPdf');
Route::get('localexpdocsubaccept/getcishort', 'Commercial\LocalExport\LocalExpDocSubAcceptController@getCIShortPdf');
Route::get('localexpdocsubaccept/getdcshort', 'Commercial\LocalExport\LocalExpDocSubAcceptController@getDCShortPdf');
Route::get('localexpdocsubaccept/packinglistshort', 'Commercial\LocalExport\LocalExpDocSubAcceptController@getPLShortPdf');
Route::resource('localexpdocsubaccept', 'Commercial\LocalExport\LocalExpDocSubAcceptController');
Route::resource('localexpdocsubinvoice', 'Commercial\LocalExport\LocalExpDocSubInvoiceController');

Route::get('localexpdocsubbank/getlocaldocsubaccept', 'Commercial\LocalExport\LocalExpDocSubBankController@getDocSubAccept');
Route::get('localexpdocsubbank/latter', 'Commercial\LocalExport\LocalExpDocSubBankController@getLatter');
Route::resource('localexpdocsubbank', 'Commercial\LocalExport\LocalExpDocSubBankController');
Route::resource('localexpdocsubtrans', 'Commercial\LocalExport\LocalExpDocSubTransController');

Route::get('localexpprorlz/gethead', 'Commercial\LocalExport\LocalExpProRlzController@gethead');
Route::get('localexpprorlz/importdocsubbank', 'Commercial\LocalExport\LocalExpProRlzController@importLocalDocSubBank');
Route::resource('localexpprorlz', 'Commercial\LocalExport\LocalExpProRlzController');
Route::resource('localexpprorlzdeduct', 'Commercial\LocalExport\LocalExpProRlzDeductController');
Route::resource('localexpprorlzamount', 'Commercial\LocalExport\LocalExpProRlzAmountController');

Route::get('subinbmarketing/getbuyerbranch', 'Subcontract\Inbound\SubInbMarketingController@getBuyerBranch');
Route::resource('subinbmarketing', 'Subcontract\Inbound\SubInbMarketingController');
Route::resource('subinbevent', 'Subcontract\Inbound\SubInbEventController');
Route::resource('subinbservice', 'Subcontract\Inbound\SubInbServiceController');
Route::resource('subinbimage', 'Subcontract\Inbound\SubInbImageController');


/*******Marketing Target Transfer*******/

Route::get('targettransfer/gettargettransfer', 'Marketing\TargetTransferController@getTargetTransfer');
Route::get('targettransfer/getinfo', 'Marketing\TargetTransferController@getInfo');
Route::resource('targettransfer', 'Marketing\TargetTransferController');

Route::get('daytargettransfer/getdaytargettransfer', 'Marketing\DayTargetTransferController@getTargetTransfer');
Route::get('daytargettransfer/sendsms', 'Marketing\DayTargetTransferController@sendSms');
Route::resource('daytargettransfer', 'Marketing\DayTargetTransferController');
Route::resource('buyerdevelopment', 'Marketing\BuyerDevelopmentController');
Route::resource('buyerdevelopmentevent', 'Marketing\BuyerDevelopmentEventController');
Route::resource('buyerdevelopmentintm', 'Marketing\BuyerDevelopmentIntmController');
Route::resource('buyerdevelopmentdoc', 'Marketing\BuyerDevelopmentDocController');
Route::resource('buyerdevelopmentorder', 'Marketing\BuyerDevelopmentOrderController');
Route::resource('buyerdevelopmentorderqty', 'Marketing\BuyerDevelopmentOrderQtyController');

/*
Route::get('subinborder/getmktref', 'Subcontract\Inbound\SubInbOrderController@getMktRef');
Route::get('subinborder/getstyleref', 'Subcontract\Inbound\SubInbOrderController@getStyleRef');
Route::get('subinborder/getbuyer', 'Subcontract\Inbound\SubInbOrderController@getBuyer');
Route::get('subinborder/getorder', 'Subcontract\Inbound\SubInbOrderController@getOrder');
Route::resource('subinborder', 'Subcontract\Inbound\SubInbOrderController');
*/
Route::get('soknit/getmktref', 'Subcontract\Kniting\SoKnitController@getMktRef');
Route::get('soknit/getstyleref', 'Subcontract\Kniting\SoKnitController@getStyleRef');
Route::get('soknit/getbuyer', 'Subcontract\Kniting\SoKnitController@getBuyer');
Route::get('soknit/getorder', 'Subcontract\Kniting\SoKnitController@getOrder');
Route::get('soknit/getpo', 'Subcontract\Kniting\SoKnitController@getPo');
Route::get('soknit/getsoknit', 'Subcontract\Kniting\SoKnitController@getSoKnit');
Route::resource('soknit', 'Subcontract\Kniting\SoKnitController');

Route::resource('soknittarget', 'Subcontract\Kniting\SoKnitTargetController');

Route::get('soknititem/getitem', 'Subcontract\Kniting\SoKnitItemController@getItem');
Route::get('soknititem/getcolor', 'Subcontract\Kniting\SoKnitItemController@getColor');
Route::get('soknititem/getsize', 'Subcontract\Kniting\SoKnitItemController@getSize');
Route::resource('soknititem', 'Subcontract\Kniting\SoKnitItemController');

Route::resource('soknitfile', 'Subcontract\Kniting\SoKnitFileController');

Route::get('soknityarnrcv/getso', 'Subcontract\Kniting\SoKnitYarnRcvController@getSo');
Route::resource('soknityarnrcv', 'Subcontract\Kniting\SoKnitYarnRcvController');

Route::get('soknityarnrcvitem/getitem', 'Subcontract\Kniting\SoKnitYarnRcvItemController@getItem');
Route::resource('soknityarnrcvitem', 'Subcontract\Kniting\SoKnitYarnRcvItemController');
Route::get('soknityarnrtn/report', 'Subcontract\Kniting\SoKnitYarnRtnController@getPdf');
Route::get('soknityarnrtn/getyarnrtn', 'Subcontract\Kniting\SoKnitYarnRtnController@getYarnRtn');
Route::resource('soknityarnrtn', 'Subcontract\Kniting\SoKnitYarnRtnController');

Route::get('soknityarnrtnitem/getitem', 'Subcontract\Kniting\SoKnitYarnRtnItemController@getItem');
Route::get('soknityarnrtnitem/getrtnitem', 'Subcontract\Kniting\SoKnitYarnRtnItemController@getrtnItem');
Route::resource('soknityarnrtnitem', 'Subcontract\Kniting\SoKnitYarnRtnItemController');

Route::get('soknitdlv/dlvchalan', 'Subcontract\Kniting\SoKnitDlvController@getDlvChalan');
Route::get('soknitdlv/bill', 'Subcontract\Kniting\SoKnitDlvController@getBill');

Route::resource('soknitdlv', 'Subcontract\Kniting\SoKnitDlvController');
Route::resource('soknitdlvitem', 'Subcontract\Kniting\SoKnitDlvItemController');

Route::get('soknitdlvitemyarn/getitem', 'Subcontract\Kniting\SoKnitDlvItemYarnController@getItem');
Route::resource('soknitdlvitemyarn', 'Subcontract\Kniting\SoKnitDlvItemYarnController');

Route::get('pldyeing/getmachine', 'Subcontract\Dyeing\PlDyeingController@getMachine');
Route::resource('pldyeing', 'Subcontract\Dyeing\PlDyeingController');

Route::get('pldyeingitem/getitem', 'Subcontract\Dyeing\PlDyeingItemController@getItem');
Route::get('pldyeingitem/getmachine', 'Subcontract\Dyeing\PlDyeingItemController@getMachine');
Route::get('pldyeingitem/report', 'Subcontract\Dyeing\PlDyeingItemController@getPdf');
Route::resource('pldyeingitem', 'Subcontract\Dyeing\PlDyeingItemController');
Route::resource('pldyeingitemqty', 'Subcontract\Dyeing\PlDyeingItemQtyController');


Route::get('sodyeing/getmktref', 'Subcontract\Dyeing\SoDyeingController@getMktRef');
Route::get('sodyeing/getstyleref', 'Subcontract\Dyeing\SoDyeingController@getStyleRef');
Route::get('sodyeing/getbuyer', 'Subcontract\Dyeing\SoDyeingController@getBuyer');
Route::get('sodyeing/getorder', 'Subcontract\Dyeing\SoDyeingController@getOrder');
Route::get('sodyeing/getpo', 'Subcontract\Dyeing\SoDyeingController@getPo');
Route::get('sodyeing/getteammember', 'Subcontract\Dyeing\SoDyeingController@getTeammember');
Route::get('sodyeing/getsodyeinglist', 'Subcontract\Dyeing\SoDyeingController@getSoDyeingList');
Route::resource('sodyeing', 'Subcontract\Dyeing\SoDyeingController');
Route::get('sodyeingitem/getitem', 'Subcontract\Dyeing\SoDyeingItemController@getItem');
Route::get('sodyeingitem/getcolor', 'Subcontract\Dyeing\SoDyeingItemController@getColor');
Route::get('sodyeingitem/getsize', 'Subcontract\Dyeing\SoDyeingItemController@getSize');
Route::resource('sodyeingitem', 'Subcontract\Dyeing\SoDyeingItemController');
Route::resource('sodyeingfile', 'Subcontract\Dyeing\SoDyeingFileController');


Route::get('sodyeingfabricrcv/getso', 'Subcontract\Dyeing\SoDyeingFabricRcvController@getSo');
Route::get('sodyeingfabricrcv/getdyeingfabricreceive', 'Subcontract\Dyeing\SoDyeingFabricRcvController@getDyeingFabricReceive');
Route::resource('sodyeingfabricrcv', 'Subcontract\Dyeing\SoDyeingFabricRcvController');
Route::resource('sodyeingfabricrcvitem', 'Subcontract\Dyeing\SoDyeingFabricRcvItemController');
Route::resource('sodyeingfabricrcvrol', 'Subcontract\Dyeing\SoDyeingFabricRcvRolController');

Route::get('sodyeingfabricrcvinh/getso', 'Subcontract\Dyeing\SoDyeingFabricRcvInhController@getSo');
Route::resource('sodyeingfabricrcvinh', 'Subcontract\Dyeing\SoDyeingFabricRcvInhController');
Route::resource('sodyeingfabricrcvinhitem', 'Subcontract\Dyeing\SoDyeingFabricRcvInhItemController');
Route::resource('sodyeingfabricrcvinhrol', 'Subcontract\Dyeing\SoDyeingFabricRcvInhRolController');

Route::get('sodyeingdlv/dlvchalan', 'Subcontract\Dyeing\SoDyeingDlvController@getDlvChalan');
Route::get('sodyeingdlv/bill', 'Subcontract\Dyeing\SoDyeingDlvController@getBill');
Route::get('sodyeingdlv/getsodyeingdlvlist', 'Subcontract\Dyeing\SoDyeingDlvController@getSoDyeingDlvList');
Route::resource('sodyeingdlv', 'Subcontract\Dyeing\SoDyeingDlvController');
Route::resource('sodyeingdlvitem', 'Subcontract\Dyeing\SoDyeingDlvItemController');

Route::get('sodyeingtarget/getteammember', 'Subcontract\Dyeing\SoDyeingTargetController@getTeammember');
Route::resource('sodyeingtarget', 'Subcontract\Dyeing\SoDyeingTargetController');
Route::get('sodyeingfabricrtn/report', 'Subcontract\Dyeing\SoDyeingFabricRtnController@getPdf');
Route::resource('sodyeingfabricrtn', 'Subcontract\Dyeing\SoDyeingFabricRtnController');

Route::get('sodyeingfabricrtnitem/getitem', 'Subcontract\Dyeing\SoDyeingFabricRtnItemController@getItem');
Route::resource('sodyeingfabricrtnitem', 'Subcontract\Dyeing\SoDyeingFabricRtnItemController');

Route::get('sodyeingbom/getso', 'Subcontract\Dyeing\SoDyeingBomController@getSo');
Route::get('sodyeingbom/getpdf', 'Subcontract\Dyeing\SoDyeingBomController@getPdf');
Route::resource('sodyeingbom', 'Subcontract\Dyeing\SoDyeingBomController');
Route::get('sodyeingbomfabric/getfabric', 'Subcontract\Dyeing\SoDyeingBomFabricController@getFabric');
Route::resource('sodyeingbomfabric', 'Subcontract\Dyeing\SoDyeingBomFabricController');
Route::get('sodyeingbomfabricitem/getitem', 'Subcontract\Dyeing\SoDyeingBomFabricItemController@getItem');

Route::get('sodyeingbomfabricitem/getmastercopyfabric', 'Subcontract\Dyeing\SoDyeingBomFabricItemController@getMasterCopyFabric');
Route::get('sodyeingbomfabricitem/copyitem', 'Subcontract\Dyeing\SoDyeingBomFabricItemController@copyItem');

Route::resource('sodyeingbomfabricitem', 'Subcontract\Dyeing\SoDyeingBomFabricItemController');

Route::get('sodyeingbomoverhead/getcost', 'Subcontract\Dyeing\SoDyeingBomOverheadController@getCost');
Route::resource('sodyeingbomoverhead', 'Subcontract\Dyeing\SoDyeingBomOverheadController');

Route::get('sodyeingmktcost/getsubinbservice', 'Subcontract\Dyeing\SoDyeingMktCostController@getSubInbService');
Route::resource('sodyeingmktcost', 'Subcontract\Dyeing\SoDyeingMktCostController');
Route::get('sodyeingmktcostfab/getautoyarn', 'Subcontract\Dyeing\SoDyeingMktCostFabController@getAutoYarn');
Route::resource('sodyeingmktcostfab', 'Subcontract\Dyeing\SoDyeingMktCostFabController');

Route::get('sodyeingmktcostfabitem/getitem', 'Subcontract\Dyeing\SoDyeingMktCostFabItemController@getItem');
Route::get('sodyeingmktcostfabitem/getmastercopyfabric', 'Subcontract\Dyeing\SoDyeingMktCostFabItemController@getMasterCopyFabric');
Route::get('sodyeingmktcostfabitem/copyitem', 'Subcontract\Dyeing\SoDyeingMktCostFabItemController@copyItem');
Route::resource('sodyeingmktcostfabitem', 'Subcontract\Dyeing\SoDyeingMktCostFabItemController');
Route::resource('sodyeingmktcostfabfin', 'Subcontract\Dyeing\SoDyeingMktCostFabFinController');
Route::get('sodyeingmktcostqprice/pdf', 'Subcontract\Dyeing\SoDyeingMktCostQpriceController@getPdf');
Route::get('sodyeingmktcostqprice/html', 'Subcontract\Dyeing\SoDyeingMktCostQpriceController@getHtml');
Route::resource('sodyeingmktcostqprice', 'Subcontract\Dyeing\SoDyeingMktCostQpriceController');
Route::resource('sodyeingmktcostqpricedtl', 'Subcontract\Dyeing\SoDyeingMktCostQpricedtlController');


Route::get('soaopmktcost/getsubinbservice', 'Subcontract\AOP\SoAopMktCostController@getSubInbService');
Route::resource('soaopmktcost', 'Subcontract\AOP\SoAopMktCostController');
Route::resource('soaopmktcostparam', 'Subcontract\AOP\SoAopMktCostParamController');
Route::get('soaopmktcostparamitem/getitem', 'Subcontract\AOP\SoAopMktCostParamItemController@getItem');
Route::get('soaopmktcostparamitem/getmastercopyparameter', 'Subcontract\AOP\SoAopMktCostParamItemController@getMasterCopyParameter');
Route::get('soaopmktcostparamitem/copyitem', 'Subcontract\AOP\SoAopMktCostParamItemController@copyItem');
Route::resource('soaopmktcostparamitem', 'Subcontract\AOP\SoAopMktCostParamItemController');
Route::resource('soaopmktcostparamfin', 'Subcontract\AOP\SoAopMktCostParamFinController');
Route::get('soaopmktcostqprice/pdf', 'Subcontract\AOP\SoAopMktCostQpriceController@getPdf');
Route::get('soaopmktcostqprice/html', 'Subcontract\AOP\SoAopMktCostQpriceController@getHtml');
Route::resource('soaopmktcostqprice', 'Subcontract\AOP\SoAopMktCostQpriceController');
Route::resource('soaopmktcostqpricedtl', 'Subcontract\AOP\SoAopMktCostQpricedtlController');

Route::get('soaop/getmktref', 'Subcontract\AOP\SoAopController@getMktRef');
Route::get('soaop/getstyleref', 'Subcontract\AOP\SoAopController@getStyleRef');
Route::get('soaop/getbuyer', 'Subcontract\AOP\SoAopController@getBuyer');
Route::get('soaop/getorder', 'Subcontract\AOP\SoAopController@getOrder');
Route::get('soaop/getpo', 'Subcontract\AOP\SoAopController@getPo');
Route::get('soaop/getteammember', 'Subcontract\AOP\SoAopController@getTeammember');
Route::get('soaop/getsoaoplist', 'Subcontract\AOP\SoAopController@getSoAopList');
Route::resource('soaop', 'Subcontract\AOP\SoAopController');
Route::get('soaopitem/getitem', 'Subcontract\AOP\SoAopItemController@getItem');
Route::get('soaopitem/getcolor', 'Subcontract\AOP\SoAopItemController@getColor');
Route::get('soaopitem/getsize', 'Subcontract\AOP\SoAopItemController@getSize');
Route::resource('soaopitem', 'Subcontract\AOP\SoAopItemController');
Route::resource('soaopfile', 'Subcontract\AOP\SoAopFileController');

Route::get('soaoptarget/getteammember', 'Subcontract\AOP\SoAopTargetController@getTeammember');
Route::resource('soaoptarget', 'Subcontract\AOP\SoAopTargetController');

Route::get('soaopfabricrcv/getso', 'Subcontract\AOP\SoAopFabricRcvController@getSo');
Route::resource('soaopfabricrcv', 'Subcontract\AOP\SoAopFabricRcvController');
Route::resource('soaopfabricrcvitem', 'Subcontract\AOP\SoAopFabricRcvItemController');
Route::resource('soaopfabricrcvrol', 'Subcontract\AOP\SoAopFabricRcvRolController');

Route::get('soaopfabricrcvinh/getso', 'Subcontract\AOP\SoAopFabricRcvInhController@getSo');
Route::get('soaopfabricrcvinh/getchallan', 'Subcontract\AOP\SoAopFabricRcvInhController@getChallan');
Route::resource('soaopfabricrcvinh', 'Subcontract\AOP\SoAopFabricRcvInhController');
Route::resource('soaopfabricrcvinhitem', 'Subcontract\AOP\SoAopFabricRcvInhItemController');
Route::get('soaopfabricrcvinhrol/getroll', 'Subcontract\AOP\SoAopFabricRcvInhRolController@getRoll');
Route::resource('soaopfabricrcvinhrol', 'Subcontract\AOP\SoAopFabricRcvInhRolController');

Route::get('soaopfabricisu/getso', 'Subcontract\AOP\SoAopFabricIsuController@getSo');
Route::resource('soaopfabricisu', 'Subcontract\AOP\SoAopFabricIsuController');
Route::get('soaopfabricisuitem/getitem', 'Subcontract\AOP\SoAopFabricIsuItemController@getItem');
Route::resource('soaopfabricisuitem', 'Subcontract\AOP\SoAopFabricIsuItemController');

Route::get('soaopdlv/dlvchalan', 'Subcontract\AOP\SoAopDlvController@getDlvChalan');
Route::get('soaopdlv/bill', 'Subcontract\AOP\SoAopDlvController@getBill');
Route::get('soaopdlv/getsoaopdlvlist', 'Subcontract\AOP\SoAopDlvController@getSoAopDlvList');
Route::resource('soaopdlv', 'Subcontract\AOP\SoAopDlvController');
Route::resource('soaopdlvitem', 'Subcontract\AOP\SoAopDlvItemController');
Route::get('soaopfabricrtn/report', 'Subcontract\AOP\SoAopFabricRtnController@getPdf');
Route::resource('soaopfabricrtn', 'Subcontract\AOP\SoAopFabricRtnController');

Route::get('soaopfabricrtnitem/getitem', 'Subcontract\AOP\SoAopFabricRtnItemController@getItem');
Route::resource('soaopfabricrtnitem', 'Subcontract\AOP\SoAopFabricRtnItemController');

Route::get('soemb/getmktref', 'Subcontract\Embelishment\SoEmbController@getMktRef');
Route::get('soemb/getstyleref', 'Subcontract\Embelishment\SoEmbController@getStyleRef');
Route::get('soemb/getbuyer', 'Subcontract\Embelishment\SoEmbController@getBuyer');
Route::get('soemb/getorder', 'Subcontract\Embelishment\SoEmbController@getOrder');
Route::get('soemb/getpo', 'Subcontract\Embelishment\SoEmbController@getPo');
Route::resource('soemb', 'Subcontract\Embelishment\SoEmbController');

Route::get('soembitem/getcolor', 'Subcontract\Embelishment\SoEmbItemController@getColor');
Route::get('soembitem/getsize', 'Subcontract\Embelishment\SoEmbItemController@getSize');
Route::get('soembitem/embtype', 'Subcontract\Embelishment\SoEmbItemController@getEmbtype');

Route::resource('soembitem', 'Subcontract\Embelishment\SoEmbItemController');
Route::resource('soembfile', 'Subcontract\Embelishment\SoEmbFileController');
Route::resource('soembtarget', 'Subcontract\Embelishment\SoEmbTargetController');

Route::get('soembprintrcv/getsoemb', 'Subcontract\Embelishment\SoEmbPrintRcvController@getSoEmb');
Route::resource('soembprintrcv', 'Subcontract\Embelishment\SoEmbPrintRcvController');
Route::resource('soembprintrcvitem', 'Subcontract\Embelishment\SoEmbPrintRcvItemController');


Route::get('soembmktcost/getsubinbservice', 'Subcontract\Embelishment\SoEmbMktCostController@getSubInbService');
Route::resource('soembmktcost', 'Subcontract\Embelishment\SoEmbMktCostController');
Route::resource('soembmktcostparam', 'Subcontract\Embelishment\SoEmbMktCostParamController');
Route::get('soembmktcostparamitem/getitem', 'Subcontract\Embelishment\SoEmbMktCostParamItemController@getItem');
Route::get('soembmktcostparamitem/getmastercopyparameter', 'Subcontract\Embelishment\SoEmbMktCostParamItemController@getMasterCopyParameter');
Route::get('soembmktcostparamitem/copyitem', 'Subcontract\Embelishment\SoEmbMktCostParamItemController@copyItem');
Route::resource('soembmktcostparamitem', 'Subcontract\Embelishment\SoEmbMktCostParamItemController');
Route::resource('soembmktcostparamfin', 'Subcontract\Embelishment\SoEmbMktCostParamFinController');
Route::get('soembmktcostqprice/pdf', 'Subcontract\Embelishment\SoEmbMktCostQpriceController@getPdf');
Route::get('soembmktcostqprice/html', 'Subcontract\Embelishment\SoEmbMktCostQpriceController@getHtml');
Route::resource('soembmktcostqprice', 'Subcontract\Embelishment\SoEmbMktCostQpriceController');
Route::resource('soembmktcostqpricedtl', 'Subcontract\Embelishment\SoEmbMktCostQpricedtlController');

Route::resource('soembcutpanelrcv', 'Subcontract\Embelishment\SoEmbCutpanelRcvController');
Route::get('soembcutpanelrcvorder/getsoemb', 'Subcontract\Embelishment\SoEmbCutpanelRcvOrderController@getSoEmb');
Route::resource('soembcutpanelrcvorder', 'Subcontract\Embelishment\SoEmbCutpanelRcvOrderController');
Route::get('soembcutpanelrcvqty/getsoembitem', 'Subcontract\Embelishment\SoEmbCutpanelRcvQtyController@getSoEmbItem');
Route::resource('soembcutpanelrcvqty', 'Subcontract\Embelishment\SoEmbCutpanelRcvQtyController');

Route::get('plknit/getmktref', 'Subcontract\Kniting\PlKnitController@getMktRef');
Route::get('plknit/getstyleref', 'Subcontract\Kniting\PlKnitController@getStyleRef');
Route::get('plknit/getbuyer', 'Subcontract\Kniting\PlKnitController@getBuyer');
Route::get('plknit/getorder', 'Subcontract\Kniting\PlKnitController@getOrder');
Route::get('plknit/report', 'Subcontract\Kniting\PlKnitController@getPdf');
Route::get('plknit/getplan', 'Subcontract\Kniting\PlKnitController@getPlKnit');
Route::resource('plknit', 'Subcontract\Kniting\PlKnitController');


Route::get('plknititem/getitem', 'Subcontract\Kniting\PlKnitItemController@getItem');
Route::get('plknititem/getmachine', 'Subcontract\Kniting\PlKnitItemController@getMachine');
Route::get('plknititem/report', 'Subcontract\Kniting\PlKnitItemController@getPdf');
Route::resource('plknititem', 'Subcontract\Kniting\PlKnitItemController');
Route::resource('plknititemqty', 'Subcontract\Kniting\PlKnitItemQtyController');


Route::get('plknititemstripe/getmktref', 'Subcontract\Kniting\PlKnitItemStripeController@getMktRef');
Route::get('plknititemstripe/getstyleref', 'Subcontract\Kniting\PlKnitItemStripeController@getStyleRef');
Route::get('plknititemstripe/getbuyer', 'Subcontract\Kniting\PlKnitItemStripeController@getBuyer');
Route::get('plknititemstripe/getorder', 'Subcontract\Kniting\PlKnitItemStripeController@getOrder');
Route::resource('plknititemstripe', 'Subcontract\Kniting\PlKnitItemStripeController');


Route::get('plknititemnarrowfabric/getsize', 'Subcontract\Kniting\PlKnitItemNarrowfabricController@getsize');
Route::resource('plknititemnarrowfabric', 'Subcontract\Kniting\PlKnitItemNarrowfabricController');

Route::get('rqyarn/report', 'Subcontract\Kniting\RqYarnController@getPdf');
Route::resource('rqyarn', 'Subcontract\Kniting\RqYarnController');

Route::get('rqyarnfabrication/getfabrication', 'Subcontract\Kniting\RqYarnFabricationController@getFabrication');
Route::resource('rqyarnfabrication', 'Subcontract\Kniting\RqYarnFabricationController');
Route::get('rqyarnitem/getitem', 'Subcontract\Kniting\RqYarnItemController@getItem');
Route::resource('rqyarnitem', 'Subcontract\Kniting\RqYarnItemController');

/*Route::get('subinborderproduct/getItemDescription', 'Subcontract\Inbound\SubInbOrderProductController@getItemDescription');
Route::get('subinborderproduct/getcolor', 'Subcontract\Inbound\SubInbOrderProductController@getColor');
Route::get('subinborderproduct/getsize', 'Subcontract\Inbound\SubInbOrderProductController@getSize');

Route::resource('subinborderproduct', 'Subcontract\Inbound\SubInbOrderProductController');*/

Route::resource('subinborderfile', 'Subcontract\Inbound\SubInbOrderFileController');

Route::resource('prodgmtcartonentry', 'Production\Garments\ProdGmtCartonEntryController');
Route::get('prodgmtcartondetail/getcartoncountry', 'Production\Garments\ProdGmtCartonDetailController@getCartonSalesOrder');
Route::get('prodgmtcartondetail/getpkgratio', 'Production\Garments\ProdGmtCartonDetailController@getpkgratio');
Route::resource('prodgmtcartondetail', 'Production\Garments\ProdGmtCartonDetailController');
Route::get('prodgmtcartondetailunassorted/getpkgratio', 'Production\Garments\ProdGmtCartonDetailUnassortedController@getpkgratio');
Route::resource('prodgmtcartondetailunassorted', 'Production\Garments\ProdGmtCartonDetailUnassortedController');

Route::resource('prodgmtcartondetailqty', 'Production\Garments\ProdGmtCartonDetailQtyController');

//Route::get('prodgmtexfactory/getcarton', 'Production\Garments\ProdGmtExFactoryController@getCarton');
Route::get('prodgmtexfactory/getexpinvoice', 'Production\Garments\ProdGmtExFactoryController@getExpInvoice');
Route::get('prodgmtexfactory/exfactorypdf', 'Production\Garments\ProdGmtExFactoryController@ExfactoryPdf');
Route::resource('prodgmtexfactory', 'Production\Garments\ProdGmtExFactoryController');
Route::get('prodgmtexfactoryqty/exstyle', 'Production\Garments\ProdGmtExFactoryQtyController@exStyle');
Route::resource('prodgmtexfactoryqty', 'Production\Garments\ProdGmtExFactoryQtyController');

Route::resource('prodgmtiron', 'Production\Garments\ProdGmtIronController');
Route::get('prodgmtironorder/getironorder', 'Production\Garments\ProdGmtIronOrderController@getIronOrder');
// Route::get('prodgmtironorder/getline', 'Production\Garments\ProdGmtIronOrderController@getLine');
Route::get('prodgmtironorder/gettable', 'Production\Garments\ProdGmtIronOrderController@getTable');
Route::resource('prodgmtironorder', 'Production\Garments\ProdGmtIronOrderController');
Route::resource('prodgmtironqty', 'Production\Garments\ProdGmtIronQtyController');

Route::resource('prodgmtpoly', 'Production\Garments\ProdGmtPolyController');
Route::get('prodgmtpolyorder/getpolyorder', 'Production\Garments\ProdGmtPolyOrderController@getPolyOrder');
Route::get('prodgmtpolyorder/getline', 'Production\Garments\ProdGmtPolyOrderController@getLine');
Route::resource('prodgmtpolyorder', 'Production\Garments\ProdGmtPolyOrderController');
Route::resource('prodgmtpolyqty', 'Production\Garments\ProdGmtPolyQtyController');

Route::resource('prodgmtsewing', 'Production\Garments\ProdGmtSewingController');
Route::get('prodgmtsewingorder/getsewingorder', 'Production\Garments\ProdGmtSewingOrderController@getSewingOrder');
Route::get('prodgmtsewingorder/getline', 'Production\Garments\ProdGmtSewingOrderController@getLine');
Route::resource('prodgmtsewingorder', 'Production\Garments\ProdGmtSewingOrderController');
Route::resource('prodgmtsewingqty', 'Production\Garments\ProdGmtSewingQtyController');
/*******ProdGmtSewingLine, ProdGmtSewingLineOrder, ProdGmtSewingLineQty*******/
Route::get('prodgmtsewingline/sewinglinepdf', 'Production\Garments\ProdGmtSewingLineController@SewingLinePdf');
Route::get('prodgmtsewingline/sewinglineshortpdf', 'Production\Garments\ProdGmtSewingLineController@SewingLineShortPdf');
Route::resource('prodgmtsewingline', 'Production\Garments\ProdGmtSewingLineController');
Route::get('prodgmtsewinglineorder/getsewinglineorder', 'Production\Garments\ProdGmtSewingLineOrderController@getSewingLineOrder');
Route::get('prodgmtsewinglineorder/getline', 'Production\Garments\ProdGmtSewingLineOrderController@getLine');
Route::resource('prodgmtsewinglineorder', 'Production\Garments\ProdGmtSewingLineOrderController');
Route::resource('prodgmtsewinglineqty', 'Production\Garments\ProdGmtSewingLineQtyController');

/*******ProdGmtCutting, ProdGmtCuttingOrder, ProdGmtCuttingQty*******/
Route::resource('prodgmtcutting', 'Production\Garments\ProdGmtCuttingController');
Route::get('prodgmtcuttingorder/getcuttingorder', 'Production\Garments\ProdGmtCuttingOrderController@getcuttingOrder');
//Route::get('prodgmtcuttingorder/getline', 'Production\Garments\ProdGmtCuttingOrderController@getLine');
Route::resource('prodgmtcuttingorder', 'Production\Garments\ProdGmtCuttingOrderController');
Route::resource('prodgmtcuttingqty', 'Production\Garments\ProdGmtCuttingQtyController');

/*******ProdGmtDlvInput, ProdGmtDlvInputOrder, ProdGmtDlvInputQty*******/
Route::get('prodgmtdlvinput/inputpdf', 'Production\Garments\ProdGmtDlvInputController@InputPdf');
Route::resource('prodgmtdlvinput', 'Production\Garments\ProdGmtDlvInputController');
// Route::get('prodgmtdlvinputorder/getdlvinputorder', 'Production\Garments\ProdGmtDlvInputOrderController@getDlvInputOrder');
// Route::resource('prodgmtdlvinputorder', 'Production\Garments\ProdGmtDlvInputOrderController');
Route::resource('prodgmtdlvinputqty', 'Production\Garments\ProdGmtDlvInputQtyController');

Route::get('/prodknitdailyreport', 'Report\FabricProduction\ProdKnitDailyReportController@index')->name('index');
Route::get('/prodknitdailyreport/getdata', 'Report\FabricProduction\ProdKnitDailyReportController@reportData');

Route::get('/proddyeingdailyreport', 'Report\FabricProduction\ProdDyeingDailyReportController@index')->name('index');
Route::get('/proddyeingdailyreport/getdata', 'Report\FabricProduction\ProdDyeingDailyReportController@reportData');
Route::get('/proddyeingdailyreport/getdyeingisuerq', 'Report\FabricProduction\ProdDyeingDailyReportController@getDyeingIsuRq');

Route::get('/proddyeingdailyloadreport', 'Report\FabricProduction\ProdDyeingDailyLoadReportController@index')->name('index');
Route::get('/proddyeingdailyloadreport/getdata', 'Report\FabricProduction\ProdDyeingDailyLoadReportController@reportData');
Route::get('/proddyeingdailyloadreport/getdyeingisuerq', 'Report\FabricProduction\ProdDyeingDailyLoadReportController@getDyeingIsuRq');
Route::get('/proddyeingdailyloadreport/getdyeingloadsummery', 'Report\FabricProduction\ProdDyeingDailyLoadReportController@getDyeingLoadSummery');

Route::get('/proddyefindailyloadreport', 'Report\FabricProduction\ProdDyeFinDailyLoadReportController@index')->name('index');
Route::get('/proddyefindailyloadreport/getdata', 'Report\FabricProduction\ProdDyeFinDailyLoadReportController@reportData');

Route::get('/prodaopfindailyloadreport', 'Report\FabricProduction\ProdAopFinDailyLoadReportController@index')->name('index');
Route::get('/prodaopfindailyloadreport/getdata', 'Report\FabricProduction\ProdAopFinDailyLoadReportController@reportData');

Route::get('/prodknittingdailyloadreport', 'Report\FabricProduction\ProdKnittingDailyLoadReportController@index')->name('index');
Route::get('/prodknittingdailyloadreport/getdata', 'Report\FabricProduction\ProdKnittingDailyLoadReportController@reportData');

Route::get('/finishfabricrolldumping', 'Report\FabricProduction\FinishFabricRollDumpingController@index')->name('index');
Route::get('/finishfabricrolldumping/getdata', 'Report\FabricProduction\FinishFabricRollDumpingController@reportData');

Route::get('prodembprintmc/getprintmcsetup', 'Production\Embelishment\ProdEmbPrintMcController@getPrintMcsetup');
Route::resource('prodembprintmc', 'Production\Embelishment\ProdEmbPrintMcController');
Route::get('prodembprintmcdtl/getemployee', 'Production\Embelishment\ProdEmbPrintMcDtlController@getEmployee');
Route::resource('prodembprintmcdtl', 'Production\Embelishment\ProdEmbPrintMcDtlController');
Route::get('prodembprintmcdtlord/getsalesorder', 'Production\Embelishment\ProdEmbPrintMcDtlOrdController@getSalesOrder');
Route::resource('prodembprintmcdtlord', 'Production\Embelishment\ProdEmbPrintMcDtlOrdController');
Route::resource('prodembprintmcdtlminaj', 'Production\Embelishment\ProdEmbPrintMcDtlMinajController');


Route::get('/prodfinishqcbatchcosting', 'Report\FabricProduction\ProdFinishQcBatchCostingController@index')->name('index');
Route::get('/prodfinishqcbatchcosting/getdata', 'Report\FabricProduction\ProdFinishQcBatchCostingController@reportData');
Route::get('/prodfinishqcbatchcosting/getcostsheet', 'Report\FabricProduction\ProdFinishQcBatchCostingController@getCostSheet');
Route::get('/prodfinishqcbatchcosting/searchbatch', 'Report\FabricProduction\ProdFinishQcBatchCostingController@searchBatch');
Route::get('/prodfinishqcbatchcosting/getsodyeingdtl', 'Report\FabricProduction\ProdFinishQcBatchCostingController@getSoDyeingDtl');
/*******ProdGmtRcvInput, ProdGmtRcvInputOrder, ProdGmtRcvInputQty*******/
Route::get('prodgmtrcvinput/getdeliverychallan', 'Production\Garments\ProdGmtRcvInputController@getDeliveryChallan');
Route::resource('prodgmtrcvinput', 'Production\Garments\ProdGmtRcvInputController');

Route::get('prodgmtrcvinputorder/getrcvinputorder', 'Production\Garments\ProdGmtRcvInputOrderController@getRcvInputOrder');
Route::get('prodgmtrcvinputorder/embtype', 'Marketing\Production\Garments\ProdGmtRcvInputOrderController@getEmbtype');
Route::resource('prodgmtrcvinputorder', 'Production\Garments\ProdGmtRcvInputOrderController');
Route::resource('prodgmtrcvinputqty', 'Production\Garments\ProdGmtRcvInputQtyController');

/*******ProdGmtDlvPrint, ProdGmtCuttingOrder, ProdGmtCuttingQty*******/
Route::get('prodgmtdlvprint/printpdf', 'Production\Garments\ProdGmtDlvPrintController@printPdf');
Route::resource('prodgmtdlvprint', 'Production\Garments\ProdGmtDlvPrintController');
Route::get('prodgmtdlvprintorder/getdlvprintorder', 'Production\Garments\ProdGmtDlvPrintOrderController@getdlvprintOrder');
Route::get('prodgmtdlvprintorder/getpoembservice', 'Production\Garments\ProdGmtDlvPrintOrderController@getPoEmbService');
Route::resource('prodgmtdlvprintorder', 'Production\Garments\ProdGmtDlvPrintOrderController');
Route::resource('prodgmtdlvprintqty', 'Production\Garments\ProdGmtDlvPrintQtyController');

/*******ProdGmtDlvToEmb, ProdGmtDlvToEmbOrder, ProdGmtDlvToEmbQty*******/
Route::get('prodgmtdlvtoemb/embpdf', 'Production\Garments\ProdGmtDlvToEmbController@EmbPdf');
Route::get('prodgmtembrcv/getdlvtoemb', 'Production\Garments\ProdGmtEmbRcvController@getDlvToEmb');
Route::resource('prodgmtdlvtoemb', 'Production\Garments\ProdGmtDlvToEmbController');
Route::get('prodgmtdlvtoemborder/getdlvtoemborder', 'Production\Garments\ProdGmtDlvToEmbOrderController@getDlvToEmbOrder');
Route::resource('prodgmtdlvtoemborder', 'Production\Garments\ProdGmtDlvToEmbOrderController');
Route::resource('prodgmtdlvtoembqty', 'Production\Garments\ProdGmtDlvToEmbQtyController');

/*******ProdGmtPrintRcv, ProdGmtPrintRcvOrder, ProdGmtPrintRcvQty*******/
Route::get('prodgmtprintrcv/getdlvprint', 'Production\Garments\ProdGmtPrintRcvController@getPrintChallan');
Route::resource('prodgmtprintrcv', 'Production\Garments\ProdGmtPrintRcvController');
Route::get('prodgmtprintrcvorder/getprintorder', 'Production\Garments\ProdGmtPrintRcvOrderController@getPrintRcvOrder');
Route::resource('prodgmtprintrcvorder', 'Production\Garments\ProdGmtPrintRcvOrderController');
Route::resource('prodgmtprintrcvqty', 'Production\Garments\ProdGmtPrintRcvQtyController');
/*******ProdGmtEmbRcv, ProdGmtEmbRcvOrder, ProdGmtEmbRcvQty*******/
Route::resource('prodgmtembrcv', 'Production\Garments\ProdGmtEmbRcvController');
Route::get('prodgmtembrcvorder/getembrcvorder', 'Production\Garments\ProdGmtEmbRcvOrderController@getEmbRcvOrder');
Route::resource('prodgmtembrcvorder', 'Production\Garments\ProdGmtEmbRcvOrderController');
Route::resource('prodgmtembrcvqty', 'Production\Garments\ProdGmtEmbRcvQtyController');
/* Inspection Garments */
Route::get('prodgmtinspection/getsalesordercountry', 'Production\Garments\ProdGmtInspectionController@getSalesOrderCountry');
Route::resource('prodgmtinspection', 'Production\Garments\ProdGmtInspectionController');
Route::resource('prodgmtinspectionorder', 'Production\Garments\ProdGmtInspectionOrderController');

Route::get('soembcutpanelrcvinh/getpartychallan', 'Subcontract\Embelishment\SoEmbCutpanelRcvInhController@getPartyChallan');
Route::resource('soembcutpanelrcvinh', 'Subcontract\Embelishment\SoEmbCutpanelRcvInhController');
Route::get('soembcutpanelrcvinhorder/getcutpanelorder', 'Subcontract\Embelishment\SoEmbCutpanelRcvInhOrderController@getSoEmb');
Route::get('soembcutpanelrcvinhorder/getembelishmentsalesorder', 'Subcontract\Embelishment\SoEmbCutpanelRcvInhOrderController@getEmbelishmentSalesOrder');
Route::resource('soembcutpanelrcvinhorder', 'Subcontract\Embelishment\SoEmbCutpanelRcvInhOrderController');
Route::get('soembcutpanelrcvinhqty/getsoembitemref', 'Subcontract\Embelishment\SoEmbCutpanelRcvInhQtyController@getSoEmbItemRef');
Route::resource('soembcutpanelrcvinhqty', 'Subcontract\Embelishment\SoEmbCutpanelRcvInhQtyController');

/* Production Dye */
Route::get('prodbatch/getmachine', 'Production\Dyeing\ProdBatchController@getMachine');
Route::get('prodbatch/getbatch', 'Production\Dyeing\ProdBatchController@getBatch');
Route::get('prodbatch/report', 'Production\Dyeing\ProdBatchController@getPdf');
Route::get('prodbatch/reportroll', 'Production\Dyeing\ProdBatchController@getPdfRoll');
Route::resource('prodbatch', 'Production\Dyeing\ProdBatchController');
Route::resource('prodbatchroll', 'Production\Dyeing\ProdBatchRollController');
Route::get('prodbatchtrim/gettrim', 'Production\Dyeing\ProdBatchTrimController@getTrim');
Route::resource('prodbatchtrim', 'Production\Dyeing\ProdBatchTrimController');
Route::resource('prodbatchprocess', 'Production\Dyeing\ProdBatchProcessController');
Route::get('prodbatchrd/getmachine', 'Production\Dyeing\ProdBatchRdController@getMachine');
Route::get('prodbatchrd/getbatch', 'Production\Dyeing\ProdBatchRdController@getBatch');
Route::get('prodbatchrd/getrootbatch', 'Production\Dyeing\ProdBatchRdController@getRootbatch');
Route::resource('prodbatchrd', 'Production\Dyeing\ProdBatchRdController');
Route::resource('prodbatchrdroll', 'Production\Dyeing\ProdBatchRdRollController');
Route::get('prodbatchrdtrim/gettrim', 'Production\Dyeing\ProdBatchRdTrimController@getTrim');
Route::resource('prodbatchrdtrim', 'Production\Dyeing\ProdBatchRdTrimController');
Route::resource('prodbatchrdprocess', 'Production\Dyeing\ProdBatchRdProcessController');

Route::get('prodbatchload/getbatch', 'Production\Dyeing\ProdBatchLoadController@getBatch');
Route::get('prodbatchload/getlist', 'Production\Dyeing\ProdBatchLoadController@getList');
Route::get('prodbatchload/getroll', 'Production\Dyeing\ProdBatchLoadController@getRoll');
Route::resource('prodbatchload', 'Production\Dyeing\ProdBatchLoadController');


Route::get('prodbatchunload/getbatch', 'Production\Dyeing\ProdBatchUnloadController@getBatch');
Route::get('prodbatchunload/getlist', 'Production\Dyeing\ProdBatchUnloadController@getList');
Route::get('prodbatchunload/getroll', 'Production\Dyeing\ProdBatchUnloadController@getRoll');
Route::resource('prodbatchunload', 'Production\Dyeing\ProdBatchUnloadController');

Route::get('prodbatchfinishprog/getbatch', 'Production\Dyeing\ProdBatchFinishProgController@getBatch');
Route::get('prodbatchfinishprog/getmachine', 'Production\Dyeing\ProdBatchFinishProgController@getMachine');
Route::get('prodbatchfinishprog/operatoremployee', 'Production\Dyeing\ProdBatchFinishProgController@getEmployeeHr');
Route::get('prodbatchfinishprog/getlist', 'Production\Dyeing\ProdBatchFinishProgController@getList');

Route::resource('prodbatchfinishprog', 'Production\Dyeing\ProdBatchFinishProgController');

Route::get('prodbatchfinishprogroll/getroll', 'Production\Dyeing\ProdBatchFinishProgRollController@getRoll');
Route::resource('prodbatchfinishprogroll', 'Production\Dyeing\ProdBatchFinishProgRollController');

Route::get('prodbatchfinishprogchem/getitem', 'Production\Dyeing\ProdBatchFinishProgChemController@getItem');
Route::get('prodbatchfinishprogchem/genreq', 'Production\Dyeing\ProdBatchFinishProgChemController@genReq');
Route::resource('prodbatchfinishprogchem', 'Production\Dyeing\ProdBatchFinishProgChemController');

Route::get('prodbatchfinishqc/getbatch', 'Production\Dyeing\ProdBatchFinishQcController@getBatch');
Route::get('prodbatchfinishqc/getmachine', 'Production\Dyeing\ProdBatchFinishQcController@getMachine');
Route::get('prodbatchfinishqc/operatoremployee', 'Production\Dyeing\ProdBatchFinishQcController@getEmployeeHr');
Route::get('prodbatchfinishqc/exportcsv', 'Production\Dyeing\ProdBatchFinishQcController@exportCsv');
Route::get('prodbatchfinishqc/getlist', 'Production\Dyeing\ProdBatchFinishQcController@getList');
Route::resource('prodbatchfinishqc', 'Production\Dyeing\ProdBatchFinishQcController');

Route::get('prodbatchfinishqcroll/getroll', 'Production\Dyeing\ProdBatchFinishQcRollController@getRoll');
Route::post('prodbatchfinishqcroll/import', 'Production\Dyeing\ProdBatchFinishQcRollController@importRoll');
Route::resource('prodbatchfinishqcroll', 'Production\Dyeing\ProdBatchFinishQcRollController');

Route::get('prodfinishdlv/report', 'Production\Dyeing\ProdFinishDlvController@getPdf');
Route::get('prodfinishdlv/reportshort', 'Production\Dyeing\ProdFinishDlvController@getPdfShort');
Route::get('prodfinishdlv/getchallan', 'Production\Dyeing\ProdFinishDlvController@getChallan');
Route::resource('prodfinishdlv', 'Production\Dyeing\ProdFinishDlvController');
Route::get('prodfinishdlvroll/importroll', 'Production\Dyeing\ProdFinishDlvRollController@importProdFinishQcRoll');
Route::resource('prodfinishdlvroll', 'Production\Dyeing\ProdFinishDlvRollController');

Route::get('prodfinishdlvaop/report', 'Production\Dyeing\ProdFinishDlvAopController@getPdf');
Route::get('prodfinishdlvaop/reportshort', 'Production\Dyeing\ProdFinishDlvAopController@getPdfShort');
Route::get('prodfinishdlvaop/getchallan', 'Production\Dyeing\ProdFinishDlvAopController@getChallan');
Route::resource('prodfinishdlvaop', 'Production\Dyeing\ProdFinishDlvAopController');
Route::get('prodfinishdlvaoproll/importaoproll', 'Production\Dyeing\ProdFinishDlvAopRollController@importAopProdFinishQcRoll');
Route::resource('prodfinishdlvaoproll', 'Production\Dyeing\ProdFinishDlvAopRollController');

Route::get('prodfinishmcsetup/getfinishmachine', 'Production\Dyeing\ProdFinishMcSetupController@getMachine');
Route::resource('prodfinishmcsetup', 'Production\Dyeing\ProdFinishMcSetupController');
Route::resource('prodfinishmcdate', 'Production\Dyeing\ProdFinishMcDateController');
Route::get('prodfinishmcparameter/getfinishmcparameterbatch', 'Production\Dyeing\ProdFinishMcParameterController@getFinishMcParameterBatch');
Route::get('prodfinishmcparameter/getemployee', 'Production\Dyeing\ProdFinishMcParameterController@getEmployee');
Route::resource('prodfinishmcparameter', 'Production\Dyeing\ProdFinishMcParameterController');

Route::get('prodfinishqcbill/report', 'Production\Dyeing\ProdFinishQcBillController@getPdf');
Route::resource('prodfinishqcbill', 'Production\Dyeing\ProdFinishQcBillController');
Route::get('prodfinishqcbillitem/getitem', 'Production\Dyeing\ProdFinishQcBillItemController@getItem');
Route::get('prodfinishqcbillitem/getqcprodbatch', 'Production\Dyeing\ProdFinishQcBillItemController@getProdBatchFinishQc');
Route::resource('prodfinishqcbillitem', 'Production\Dyeing\ProdFinishQcBillItemController');

Route::get('prodaopbatch/getso', 'Production\AOP\ProdAopBatchController@getSo');
Route::get('prodaopbatch/getbatch', 'Production\AOP\ProdAopBatchController@getBatch');
Route::get('prodaopbatch/report', 'Production\AOP\ProdAopBatchController@getPdf');
Route::get('prodaopbatch/reportroll', 'Production\AOP\ProdAopBatchController@getPdfRoll');
Route::resource('prodaopbatch', 'Production\AOP\ProdAopBatchController');

Route::resource('prodaopbatchroll', 'Production\AOP\ProdAopBatchRollController');
Route::get('prodaopbatchprocess/getmachine', 'Production\AOP\ProdAopBatchProcessController@getMachine');
Route::get('prodaopbatchprocess/getsupervisor', 'Production\AOP\ProdAopBatchProcessController@getEmployeeHr');
Route::resource('prodaopbatchprocess', 'Production\AOP\ProdAopBatchProcessController');

Route::get('prodaopbatchfinishprog/getbatch', 'Production\AOP\ProdAopBatchFinishProgController@getBatch');
Route::get('prodaopbatchfinishprog/getmachine', 'Production\AOP\ProdAopBatchFinishProgController@getMachine');
Route::get('prodaopbatchfinishprog/operatoremployee', 'Production\AOP\ProdAopBatchFinishProgController@getEmployeeHr');
Route::get('prodaopbatchfinishprog/getlist', 'Production\AOP\ProdAopBatchFinishProgController@getList');

Route::resource('prodaopbatchfinishprog', 'Production\AOP\ProdAopBatchFinishProgController');

Route::get('prodaopbatchfinishprogroll/getroll', 'Production\AOP\ProdAopBatchFinishProgRollController@getRoll');
Route::resource('prodaopbatchfinishprogroll', 'Production\AOP\ProdAopBatchFinishProgRollController');

Route::get('prodaopbatchfinishprogchem/getitem', 'Production\AOP\ProdAopBatchFinishProgChemController@getItem');
Route::resource('prodaopbatchfinishprogchem', 'Production\AOP\ProdAopBatchFinishProgChemController');


Route::get('prodaopbatchfinishqc/getbatch', 'Production\AOP\ProdAopBatchFinishQcController@getBatch');
Route::get('prodaopbatchfinishqc/operatoremployee', 'Production\AOP\ProdAopBatchFinishQcController@getEmployeeHr');
Route::get('prodaopbatchfinishqc/exportcsv', 'Production\AOP\ProdAopBatchFinishQcController@exportCsv');
Route::get('prodaopbatchfinishqc/getlist', 'Production\AOP\ProdAopBatchFinishQcController@getList');
Route::resource('prodaopbatchfinishqc', 'Production\AOP\ProdAopBatchFinishQcController');

Route::get('prodaopbatchfinishqcroll/getroll', 'Production\AOP\ProdAopBatchFinishQcRollController@getRoll');
Route::post('prodaopbatchfinishqcroll/import', 'Production\AOP\ProdAopBatchFinishQcRollController@importRoll');
Route::resource('prodaopbatchfinishqcroll', 'Production\AOP\ProdAopBatchFinishQcRollController');

Route::get('prodaopfinishdlv/report', 'Production\AOP\ProdAopFinishDlvController@getPdf');
Route::get('prodaopfinishdlv/reportshort', 'Production\AOP\ProdAopFinishDlvController@getPdfShort');
Route::get('prodaopfinishdlv/getchallan', 'Production\AOP\ProdAopFinishDlvController@getChallan');
Route::resource('prodaopfinishdlv', 'Production\AOP\ProdAopFinishDlvController');

Route::get('prodaopfinishdlvroll/importroll', 'Production\AOP\ProdAopFinishDlvRollController@importProdFinishQcRoll');
Route::resource('prodaopfinishdlvroll', 'Production\AOP\ProdAopFinishDlvRollController');

Route::get('prodaopmcsetup/getaopmachine', 'Production\AOP\ProdAopMcSetupController@getMachine');
Route::resource('prodaopmcsetup', 'Production\AOP\ProdAopMcSetupController');
Route::resource('prodaopmcdate', 'Production\AOP\ProdAopMcDateController');
Route::get('prodaopmcparameter/getbatch', 'Production\AOP\ProdAopMcParameterController@getBatch');
Route::get('prodaopmcparameter/getemployee', 'Production\AOP\ProdAopMcParameterController@getEmployee');
Route::resource('prodaopmcparameter', 'Production\AOP\ProdAopMcParameterController');

Route::get('prodfinishaopmcsetup/getfinishmachine', 'Production\AOP\ProdFinishAopMcSetupController@getFinishMachine');
Route::resource('prodfinishaopmcsetup', 'Production\AOP\ProdFinishAopMcSetupController');
Route::resource('prodfinishaopmcdate', 'Production\AOP\ProdFinishAopMcDateController');
Route::get('prodfinishaopmcparameter/getaopbatch', 'Production\AOP\ProdFinishAopMcParameterController@getAopBatch');
Route::get('prodfinishaopmcparameter/getemployee', 'Production\AOP\ProdFinishAopMcParameterController@getEmployee');
Route::resource('prodfinishaopmcparameter', 'Production\AOP\ProdFinishAopMcParameterController');

//Work Study
Route::resource('wstudylinesetup', 'Workstudy\WstudyLineSetupController');
Route::get('wstudylinesetupdtl/linesetupstyleref', 'Workstudy\WstudyLineSetupDtlController@lineSetupStyleRef');
Route::get('wstudylinesetupdtl/getchiefname', 'Workstudy\WstudyLineSetupDtlController@getChiefName');
Route::resource('wstudylinesetupdtl', 'Workstudy\WstudyLineSetupDtlController');
Route::resource('wstudylinesetupline', 'Workstudy\WstudyLineSetupLineController');
Route::get('wstudylinesetupdtlord/linesetupstyleref', 'Workstudy\WstudyLineSetupDtlOrdController@lineSetupStyleRef');
Route::resource('wstudylinesetupdtlord', 'Workstudy\WstudyLineSetupDtlOrdController');
Route::resource('wstudylinesetupminadj', 'Workstudy\WstudyLineSetupMinAdjController');

//Planing
Route::resource('tnaord', 'Planing\TnaOrdController');
Route::get('planingboard/loadresource', 'Planing\PlaningBoardController@loadResource');
Route::get('planingboard/loadplan', 'Planing\PlaningBoardController@loadPlan');
Route::resource('planingboard', 'Planing\PlaningBoardController');
Route::get('tnaactual/getsalesorder', 'Planing\TnaActualController@getSalesOrder');
Route::resource('tnaactual', 'Planing\TnaActualController');

Route::get('tnaprogressdelay/gettnasalesorder', 'Planing\TnaProgressDelayController@getTnaSalesOrder');
Route::resource('tnaprogressdelay', 'Planing\TnaProgressDelayController');
Route::get('tnaprogressdelaydtl/getemployeehr', 'Planing\TnaProgressDelayDtlController@getEmployeeHr');
Route::resource('tnaprogressdelaydtl', 'Planing\TnaProgressDelayDtlController');

Route::resource('tnatemplate', 'Planing\TnaTemplateController');
Route::resource('tnatemplatedtl', 'Planing\TnaTemplateDtlController');

Route::get('smpcost/report', 'Sample\Costing\SmpCostController@getPdf');
Route::get('smpcost/reportquote', 'Sample\Costing\SmpCostController@getPdfQuote');
Route::get('smpcost/getstylesample', 'Sample\Costing\SmpCostController@getStyleSample');
Route::get('smpcost/getTotal', 'Sample\Costing\SmpCostController@getTotal');
Route::resource('smpcost', 'Sample\Costing\SmpCostController');
Route::resource('smpcostfabric', 'Sample\Costing\SmpCostFabricController');
Route::resource('smpcostfabriccon', 'Sample\Costing\SmpCostFabricConController');

Route::get('smpcostyarn/fabriclist', 'Sample\Costing\SmpCostYarnController@getfabriclist');
Route::get('smpcostyarn/popuplist', 'Sample\Costing\SmpCostYarnController@getPopuplist');
Route::get('smpcostyarn/getyarn', 'Sample\Costing\SmpCostYarnController@getyarn');
Route::resource('smpcostyarn', 'Sample\Costing\SmpCostYarnController');

Route::get('smpcostfabricprod/cons', 'Sample\Costing\SmpCostFabricProdController@getCons');
Route::get('smpcostfabricprod/yarncount', 'Sample\Costing\SmpCostFabricProdController@getYarncount');
Route::get('smpcostfabricprod/getrate', 'Sample\Costing\SmpCostFabricProdController@getrate');
Route::get('smpcostfabricprod/productionarea', 'Sample\Costing\SmpCostFabricProdController@getproductionarea');
Route::resource('smpcostfabricprod', 'Sample\Costing\SmpCostFabricProdController');
Route::resource('smpcostfabricprodcon', 'Sample\Costing\SmpCostFabricProdConController');
Route::get('smpcosttrim/setuom', 'Sample\Costing\SmpCostTrimController@setuom');
Route::resource('smpcosttrim', 'Sample\Costing\SmpCostTrimController');
Route::resource('smpcosttrimcon', 'Sample\Costing\SmpCostTrimConController');
Route::get('smpcostemb/getrate', 'Sample\Costing\SmpCostEmbController@getrate');
Route::resource('smpcostemb', 'Sample\Costing\SmpCostEmbController');
Route::resource('smpcostembcon', 'Sample\Costing\SmpCostEmbConController');
Route::resource('smpcostcm', 'Sample\Costing\SmpCostCmController');
Route::resource('workinghoursetup', 'Util\WorkingHourSetupController');

Route::get('prodknit/search', 'Production\Kniting\ProdKnitController@getProd');
Route::resource('prodknit', 'Production\Kniting\ProdKnitController');
Route::get('prodknititem/getitem', 'Production\Kniting\ProdKnitItemController@getItem');
Route::get('prodknititem/getmachine', 'Production\Kniting\ProdKnitItemController@getMachine');
Route::get('prodknititem/getoperator', 'Production\Kniting\ProdKnitItemController@getOperator');

Route::resource('prodknititem', 'Production\Kniting\ProdKnitItemController');
Route::get('prodknititemroll/getfabriccolor', 'Production\Kniting\ProdKnitItemRollController@getFabricColor');
Route::get('prodknititemroll/getsample', 'Production\Kniting\ProdKnitItemRollController@getSample');
Route::get('prodknititemroll/getwgt', 'Production\Kniting\ProdKnitItemRollController@getWgt');



Route::resource('prodknititemroll', 'Production\Kniting\ProdKnitItemRollController');

Route::get('prodknititemyarn/getyarn', 'Production\Kniting\ProdKnitItemYarnController@getYarn');
Route::resource('prodknititemyarn', 'Production\Kniting\ProdKnitItemYarnController');


Route::get('gateentry/getpurchaseitem', 'GateEntry\GateEntryController@getPurchaseItem');
Route::resource('gateentry', 'GateEntry\GateEntryController');
Route::resource('gateentryitem', 'GateEntry\GateEntryItemController');

Route::get('gateout/getmenuitem', 'GateEntry\GateOutController@getMenuItem');
Route::get('gateout/getoutentry', 'GateEntry\GateOutController@getOutEntry');
Route::resource('gateout', 'GateEntry\GateOutController');

//Route::resource('prodknitref', 'Production\Kniting\ProdKnitRefController');
//Route::resource('prodknitrefplitem', 'Production\Kniting\ProdKnitRefPlItemController');
//Route::resource('prodknitrefpoitem', 'Production\Kniting\ProdKnitRefPoItemController');
//Route::resource('prodknitrefitem', 'Production\Kniting\ProdKnitRefItemController');
Route::get('prodknitrcvbyqc/importroll', 'Production\Kniting\ProdKnitRcvByQcController@importProdKnitRoll');
Route::resource('prodknitrcvbyqc', 'Production\Kniting\ProdKnitRcvByQcController');

Route::get('prodknitqc/importroll', 'Production\Kniting\ProdKnitQcController@importProdKnitRoll');
Route::get('prodknitqc/searchroll', 'Production\Kniting\ProdKnitQcController@searchRoll');

Route::resource('prodknitqc', 'Production\Kniting\ProdKnitQcController');

Route::get('prodknitdlv/report', 'Production\Kniting\ProdKnitDlvController@getPdf');
Route::get('prodknitdlv/bill', 'Production\Kniting\ProdKnitDlvController@getBill');
Route::get('prodknitdlv/getchallan', 'Production\Kniting\ProdKnitDlvController@getChallan');
Route::get('prodknitdlv/searchprodknit', 'Production\Kniting\ProdKnitDlvController@searchProdKnit');
Route::resource('prodknitdlv', 'Production\Kniting\ProdKnitDlvController');
Route::get('prodknitdlvroll/importroll', 'Production\Kniting\ProdKnitDlvRollController@importProdKnitQcRoll');
Route::resource('prodknitdlvroll', 'Production\Kniting\ProdKnitDlvRollController');

Route::get('srmproductreceive/getexpinvoice', 'ShowRoom\SrmProductReceiveController@getExpInvoiceNo');
Route::resource('srmproductreceive', 'ShowRoom\SrmProductReceiveController');

Route::get('srmproductreceivedtl/receivebercodepdf', 'ShowRoom\SrmProductReceiveDtlController@getBercodePdf');
Route::resource('srmproductreceivedtl', 'ShowRoom\SrmProductReceiveDtlController');

Route::get('srmproductsale/getproduct', 'ShowRoom\SrmProductSaleController@getProduct');
Route::get('srmproductsale/getinvoice', 'ShowRoom\SrmProductSaleController@getInvoice');
Route::get('srmproductsale/getdetailinvoice', 'ShowRoom\SrmProductSaleController@getDtailInvoicePdf');
Route::resource('srmproductsale', 'ShowRoom\SrmProductSaleController');

Route::get('/orderprogress', 'Report\OrderProgressController@index')->name('index');
Route::get('/orderprogress/getdata', 'Report\OrderProgressController@reportData');
Route::get('/orderprogress/getdatacom', 'Report\OrderProgressController@reportDataCom');
Route::get('/orderprogress/getdatabuy', 'Report\OrderProgressController@reportDataBuy');

//Route::get('/orderprogress/getsummarydata', 'Report\OrderProgressController@reportSummaryData');
//Route::get('/orderprogress/getsummarycompanydata', 'Report\OrderProgressController@getCompanySummery');
//Route::get('/orderprogress/getsummarybuyerdata', 'Report\OrderProgressController@getBuyerSummery');
//Route::get('/orderprogress/getsummarybuyerbuyinghousedata', 'Report\OrderProgressController@getBuyerAndBuyingHouse');
//Route::get('/orderprogress/getdetaildata', 'Report\OrderProgressController@reportdetaildata');

Route::get('/orderprogress/getdlmerchant', 'Report\OrderProgressController@getDealMerchant');
Route::get('/orderprogress/getbuyhouse', 'Report\OrderProgressController@getBuyingHouse');
Route::get('/orderprogress/getopfile', 'Report\OrderProgressController@getOpFileSrc');
Route::get('/orderprogress/getlcsc', 'Report\OrderProgressController@getLCSc');
Route::get('/orderprogress/getorderqty', 'Report\OrderProgressController@getOrderQty');

Route::get('/orderprogress/getdyedyarnrq', 'Report\OrderProgressController@getDyedYarnRq');
Route::get('/orderprogress/getgreyyarntodye', 'Report\OrderProgressController@getGreyYarnToDye');
Route::get('/orderprogress/getdyedyarnrcv', 'Report\OrderProgressController@getDyedYarnRcv');

Route::get('/orderprogress/getyarnrq', 'Report\OrderProgressController@getYarnRq');
Route::get('/orderprogress/getyarnisuinh', 'Report\OrderProgressController@getYarnIsuInh');
Route::get('/orderprogress/getyarnisuout', 'Report\OrderProgressController@getYarnIsuOut');
Route::get('/orderprogress/getknit', 'Report\OrderProgressController@getKnit');
Route::get('/orderprogress/getcutqty', 'Report\OrderProgressController@getCutQty');
Route::get('/orderprogress/getscrqty', 'Report\OrderProgressController@getScrQty');
Route::get('/orderprogress/getsewqty', 'Report\OrderProgressController@getSewQty');
Route::get('/orderprogress/getcarqty', 'Report\OrderProgressController@getCarQty');
Route::get('/orderprogress/getinspqty', 'Report\OrderProgressController@getInspQty');
Route::get('/orderprogress/getexfqty', 'Report\OrderProgressController@getExfQty');
Route::get('/orderprogress/ordpstyle', 'Report\OrderProgressController@getOrderStyle');
Route::get('/orderprogress/ordteammemberdlm', 'Report\OrderProgressController@getTeamMemberDlm');
Route::get('/orderprogress/orderprogresssummery', 'Report\OrderProgressController@orderProgressSummery');

Route::get('/neworderentryreport', 'Report\NewOrderEntryReportController@index')->name('index');
Route::get('/neworderentryreport/getdata', 'Report\NewOrderEntryReportController@reportData');
Route::get('/neworderentryreport/getdlmerchant', 'Report\NewOrderEntryReportController@getDealMerchant');
Route::get('/neworderentryreport/getbuyhouse', 'Report\NewOrderEntryReportController@getBuyingHouse');
Route::get('/neworderentryreport/getopfile', 'Report\NewOrderEntryReportController@getOpFileSrc');
Route::get('/neworderentryreport/getlcsc', 'Report\NewOrderEntryReportController@getLCSc');
Route::get('/neworderentryreport/getorderqty', 'Report\NewOrderEntryReportController@getOrderQty');
Route::get('/neworderentryreport/ordpstyle', 'Report\NewOrderEntryReportController@getOrderStyle');
Route::get('/neworderentryreport/ordteammemberdlm', 'Report\NewOrderEntryReportController@getTeamMemberDlm');

Route::get('/orderpending', 'Report\OrderPendingController@index')->name('index');
Route::get('/orderpending/getdata', 'Report\OrderPendingController@reportData');
Route::get('/orderpending/getorderreceived', 'Report\OrderPendingController@getOrderReceived');
Route::get('/orderpending/getdlmerchant', 'Report\OrderPendingController@getDealMerchant');
Route::get('/orderpending/getbuyhouse', 'Report\OrderPendingController@getBuyingHouse');
Route::get('/orderpending/getopfile', 'Report\OrderPendingController@getOpFileSrc');
Route::get('/orderpending/getstyle', 'Report\OrderPendingController@getStyle');
Route::get('/orderpending/ordteammemberdlm', 'Report\OrderPendingController@getTeamMemberDlm');

Route::get('/orderwiseyarnreport', 'Report\OrderWiseYarnReportController@index')->name('index');
Route::get('/orderwiseyarnreport/getdata', 'Report\OrderWiseYarnReportController@reportData');
Route::get('/orderwiseyarnreport/getopfile', 'Report\OrderWiseYarnReportController@getOpFileSrc');

Route::get('/mktteamperformance/getdata', 'Report\MktTeamPerformanceController@getData');
Route::get('/mktteamperformance', 'Report\MktTeamPerformanceController@index')->name('index');

Route::get('/fabricprodprogress', 'Report\FabricProduction\FabricProdProgressController@index')->name('index');
Route::get('/fabricprodprogress/getdata', 'Report\FabricProduction\FabricProdProgressController@reportData');
Route::get('/fabricprodprogress/ordpstyle', 'Report\FabricProduction\FabricProdProgressController@getOrderStyle');
Route::get('/fabricprodprogress/ordteammemberdlm', 'Report\FabricProduction\FabricProdProgressController@getTeamMemberDlm');
Route::get('/fabricprodprogress/companybuyersummery', 'Report\FabricProduction\FabricProdProgressController@getCompanyBuyerSummery');

Route::get('/batchreport', 'Report\FabricProduction\BatchReportController@index')->name('index');
Route::get('/batchreport/getdata', 'Report\FabricProduction\BatchReportController@reportData');
Route::get('/batchreport/getroll', 'Report\FabricProduction\BatchReportController@getRoll');


Route::get('/orderwisebudget', 'Report\OrderWiseBudgetController@index')->name('index');
Route::get('/orderwisebudget/getdata', 'Report\OrderWiseBudgetController@formatOne');
Route::get('/orderwisebudget/getyarn', 'Report\OrderWiseBudgetController@getyarn');
Route::get('/orderwisebudget/gettrim', 'Report\OrderWiseBudgetController@gettrim');

Route::get('/orderwisebudget/getknit', 'Report\OrderWiseBudgetController@getknit');
Route::get('/orderwisebudget/getyarndyeing', 'Report\OrderWiseBudgetController@getyarndyeing');
Route::get('/orderwisebudget/getdyeing', 'Report\OrderWiseBudgetController@getdyeing');
Route::get('/orderwisebudget/getaop', 'Report\OrderWiseBudgetController@getaop');
Route::get('/orderwisebudget/getboc', 'Report\OrderWiseBudgetController@getboc');
Route::get('/orderwisebudget/getfwc', 'Report\OrderWiseBudgetController@getfwc');
Route::get('/orderwisebudget/getgpc', 'Report\OrderWiseBudgetController@getgpc');
Route::get('/orderwisebudget/getgec', 'Report\OrderWiseBudgetController@getgec');
Route::get('/orderwisebudget/getgsec', 'Report\OrderWiseBudgetController@getgsec');
Route::get('/orderwisebudget/getgdc', 'Report\OrderWiseBudgetController@getgdc');
Route::get('/orderwisebudget/getgwc', 'Report\OrderWiseBudgetController@getgwc');
Route::get('/orderwisebudget/getoth', 'Report\OrderWiseBudgetController@getoth');
Route::get('/orderwisebudget/getsalesorder', 'Report\OrderWiseBudgetController@getsalesorder');




Route::get('/budgetandcostingcomparison', 'Report\BudgetAndCostingComparisonController@index')->name('index');
Route::get('/budgetandcostingcomparison/getdata', 'Report\BudgetAndCostingComparisonController@formatOne');
Route::get('/budgetandcostingcomparison/formatTow', 'Report\BudgetAndCostingComparisonController@formatTow');
Route::get('/budgetandcostingcomparison/getfabpur', 'Report\BudgetAndCostingComparisonController@getfabpur');
Route::get('/budgetandcostingcomparison/getyarn', 'Report\BudgetAndCostingComparisonController@getyarn');
Route::get('/budgetandcostingcomparison/gettrim', 'Report\BudgetAndCostingComparisonController@gettrim');
Route::get('/budgetandcostingcomparison/getknit', 'Report\BudgetAndCostingComparisonController@getknit');
Route::get('/budgetandcostingcomparison/getyarndyeing', 'Report\BudgetAndCostingComparisonController@getyarndyeing');
Route::get('/budgetandcostingcomparison/getdyeing', 'Report\BudgetAndCostingComparisonController@getdyeing');
Route::get('/budgetandcostingcomparison/getaop', 'Report\BudgetAndCostingComparisonController@getaop');
Route::get('/budgetandcostingcomparison/getboc', 'Report\BudgetAndCostingComparisonController@getboc');
Route::get('/budgetandcostingcomparison/getfwc', 'Report\BudgetAndCostingComparisonController@getfwc');
Route::get('/budgetandcostingcomparison/getgpc', 'Report\BudgetAndCostingComparisonController@getgpc');
Route::get('/budgetandcostingcomparison/getgec', 'Report\BudgetAndCostingComparisonController@getgec');
Route::get('/budgetandcostingcomparison/getgsec', 'Report\BudgetAndCostingComparisonController@getgsec');
Route::get('/budgetandcostingcomparison/getgdc', 'Report\BudgetAndCostingComparisonController@getgdc');
Route::get('/budgetandcostingcomparison/getgwc', 'Report\BudgetAndCostingComparisonController@getgwc');
Route::get('/budgetandcostingcomparison/getoth', 'Report\BudgetAndCostingComparisonController@getoth');
Route::get('/budgetandcostingcomparison/getsalesorder', 'Report\BudgetAndCostingComparisonController@getsalesorder');
Route::get('/budgetandcostingcomparison/getdlmerchant', 'Report\BudgetAndCostingComparisonController@getDealMerchant');
Route::get('/budgetandcostingcomparison/getbuyhouse', 'Report\BudgetAndCostingComparisonController@getBuyingHouse');
//Route::get('/budgetandcostingcomparison/getfile', 'Report\BudgetAndCostingComparisonController@getFileSrc');
Route::get('/budgetandcostingcomparison/getbacfile', 'Report\BudgetAndCostingComparisonController@getBacFileSrc');
Route::get('/budgetandcostingcomparison/getconsdzn', 'Report\BudgetAndCostingComparisonController@getconsdzn');
Route::get('/budgetandcostingcomparison/getbep', 'Report\BudgetAndCostingComparisonController@getBep');
Route::get('/budgetandcostingcomparison/getbepaop', 'Report\BudgetAndCostingComparisonController@getBepAop');
Route::get('/budgetandcostingcomparison/getbepgpc', 'Report\BudgetAndCostingComparisonController@getBepGpc');

Route::get('/orderinhand/getdata', 'Report\OrderInHandController@formatTwo');
Route::get('/orderinhand', 'Report\OrderInHandController@index')->name('index');
Route::get('/orderinhand/getyarn', 'Report\OrderInHandController@getyarn');
Route::get('/orderinhand/gettrim', 'Report\OrderInHandController@gettrim');
Route::get('/orderinhand/getfabpur', 'Report\OrderInHandController@getfabpur');

Route::get('/budgetsummary', 'Report\BudgetSummaryController@index')->name('index');
Route::get('/budgetsummary/getdata', 'Report\BudgetSummaryController@reportData');

Route::get('/orderinhand/getknit', 'Report\OrderInHandController@getknit');
Route::get('/orderinhand/getyarndyeing', 'Report\OrderInHandController@getyarndyeing');
Route::get('/orderinhand/getdyeing', 'Report\OrderInHandController@getdyeing');
Route::get('/orderinhand/getaop', 'Report\OrderInHandController@getaop');
Route::get('/orderinhand/getboc', 'Report\OrderInHandController@getboc');
Route::get('/orderinhand/getfwc', 'Report\OrderInHandController@getfwc');
Route::get('/orderinhand/getgpc', 'Report\OrderInHandController@getgpc');
Route::get('/orderinhand/getgec', 'Report\OrderInHandController@getgec');
Route::get('/orderinhand/getgsec', 'Report\OrderInHandController@getgsec');
Route::get('/orderinhand/getgdc', 'Report\OrderInHandController@getgdc');
Route::get('/orderinhand/getgwc', 'Report\OrderInHandController@getgwc');
Route::get('/orderinhand/getoth', 'Report\OrderInHandController@getoth');
Route::get('/orderinhand/getsalesorder', 'Report\OrderInHandController@getsalesorder');
Route::get('/orderinhand/getfile', 'Report\OrderInHandController@getFileSrc');
Route::get('/orderinhand/getconsdzn', 'Report\OrderInHandController@getconsdzn');
Route::get('/orderinhand/getbep', 'Report\OrderInHandController@getbep');
Route::get('/orderinhand/getlcsc', 'Report\OrderInHandController@getLcSc');

Route::get('/orderinhandknit', 'Report\OrderInHandKnitController@index')->name('index');
Route::get('/orderinhandknit/getdata', 'Report\OrderInHandKnitController@formatTwo');
Route::get('/orderinhandknit/getknit', 'Report\OrderInHandKnitController@getknit');
Route::get('/orderinhandknit/getsalesorder', 'Report\OrderInHandKnitController@getsalesorder');
Route::get('/orderinhandknit/getfile', 'Report\OrderInHandKnitController@getFileSrc');
Route::get('/orderinhandknit/getconsdzn', 'Report\OrderInHandKnitController@getconsdzn');

Route::get('/orderinhanddyeing', 'Report\OrderInHandDyeingController@index')->name('index');
Route::get('/orderinhanddyeing/getdata', 'Report\OrderInHandDyeingController@formatTwo');
Route::get('/orderinhanddyeing/getdyeing', 'Report\OrderInHandDyeingController@getdyeing');
Route::get('/orderinhanddyeing/getsalesorder', 'Report\OrderInHandDyeingController@getsalesorder');
Route::get('/orderinhanddyeing/getfile', 'Report\OrderInHandDyeingController@getFileSrc');
Route::get('/orderinhanddyeing/getconsdzn', 'Report\OrderInHandDyeingController@getconsdzn');

Route::get('/orderinhandaop', 'Report\OrderInHandAopController@index')->name('index');
Route::get('/orderinhandaop/getdata', 'Report\OrderInHandAopController@formatTwo');
Route::get('/orderinhandaop/getaop', 'Report\OrderInHandAopController@getaop');
Route::get('/orderinhandaop/getsalesorder', 'Report\OrderInHandAopController@getsalesorder');
Route::get('/orderinhandaop/getfile', 'Report\OrderInHandAopController@getFileSrc');
Route::get('/orderinhandaop/getconsdzn', 'Report\OrderInHandAopController@getconsdzn');

Route::get('/orderinhandgmt', 'Report\OrderInHandGmtController@index')->name('index');
Route::get('/orderinhandgmt/getdata', 'Report\OrderInHandGmtController@formatTwo');
Route::get('/orderinhandgmt/getsalesorder', 'Report\OrderInHandGmtController@getsalesorder');
Route::get('/orderinhandgmt/getfile', 'Report\OrderInHandGmtController@getFileSrc');

Route::get('/capacityordercashflowreport', 'Report\CapacityOrderCashflowReportController@index')->name('index');
Route::get('/capacityordercashflowreport/getdata', 'Report\CapacityOrderCashflowReportController@reportData');
Route::get('/capacityordercashflowreport/gettextiledata', 'Report\CapacityOrderCashflowReportController@getDataTextile');
Route::get('/capacityordercashflowreport/gethrgmtemployee', 'Report\CapacityOrderCashflowReportController@getHrGarmentsEmployee');

Route::get('/buyerdevelopmentrpt', 'Report\BuyerDevelopmentReportController@index')->name('index');
Route::get('/buyerdevelopmentrpt/getdata', 'Report\BuyerDevelopmentReportController@reportData');
Route::get('/buyerdevelopmentrpt/getevents', 'Report\BuyerDevelopmentReportController@getEvents');
Route::get('/buyerdevelopmentrpt/getintms', 'Report\BuyerDevelopmentReportController@getIntms');
Route::get('/buyerdevelopmentrpt/getdocs', 'Report\BuyerDevelopmentReportController@getDocs');
Route::get('/buyerdevelopmentrpt/getbuys', 'Report\BuyerDevelopmentReportController@getBuys');
Route::get('/buyerdevelopmentrpt/getbuycont', 'Report\BuyerDevelopmentReportController@getBuyCont');
Route::get('/buyerdevelopmentrpt/getorderforcasting', 'Report\BuyerDevelopmentReportController@getOrderForcasting');
Route::get('/buyerdevelopmentrpt/getmktcost', 'Report\BuyerDevelopmentReportController@getMktCost');

Route::get('/pendingshipment/getdata', 'Report\PendingShipmentController@formatOne');
Route::get('/pendingshipment', 'Report\PendingShipmentController@index')->name('index');

Route::get('/todayShipment',        'Report\TodayShipmentController@index')->name('index');
Route::get('/todayShipment/getdata', 'Report\TodayShipmentController@formatOne');
Route::get('/todayaccount',        'Report\TodayAccountController@index')->name('index');
Route::get('/todayaccount/getdata', 'Report\TodayAccountController@reportData');
Route::get('/todayaccount/todayinflow', 'Report\TodayAccountController@todayInflow');
Route::get('/todayaccount/monthinflow', 'Report\TodayAccountController@monthInflow');
Route::get('/todayaccount/todayoutflow', 'Report\TodayAccountController@todayOutflow');
Route::get('/todayaccount/monthoutflow', 'Report\TodayAccountController@monthOutflow');
Route::get('/todayaccount/todayrevenue', 'Report\TodayAccountController@todayRevenue');
Route::get('/todayaccount/monthrevenue', 'Report\TodayAccountController@monthrevenue');


Route::get('/receiptspaymentsaccount',        'Report\ReceiptsPaymentsAccountController@index')->name('index');
Route::get('/receiptspaymentsaccount/getdata', 'Report\ReceiptsPaymentsAccountController@reportData');
Route::get('/receiptspaymentsaccount/getdatatoday', 'Report\ReceiptsPaymentsAccountController@reportDataToday');
Route::get('/receiptspaymentsaccount/receipt', 'Report\ReceiptsPaymentsAccountController@getReceipt');
Route::get('/receiptspaymentsaccount/multipleheadreceipt', 'Report\ReceiptsPaymentsAccountController@getMultipleHeadReceipt');
Route::get('/receiptspaymentsaccount/payment', 'Report\ReceiptsPaymentsAccountController@getPayment');
Route::get('/receiptspaymentsaccount/multipleheadpayment', 'Report\ReceiptsPaymentsAccountController@getMultipleHeadPayment');

Route::get('/todayinventoryreport',        'Report\TodayInventoryReportController@index')->name('index');
Route::get('/todayinventoryreport/getdata', 'Report\TodayInventoryReportController@reportData');
Route::get('/todayinventoryreport/generalrcv', 'Report\TodayInventoryReportController@generalRcv');
Route::get('/todayinventoryreport/generalisu', 'Report\TodayInventoryReportController@generalIsu');
Route::get('/todayinventoryreport/yarnrcv', 'Report\TodayInventoryReportController@yarnRcv');
Route::get('/todayinventoryreport/yarnisu', 'Report\TodayInventoryReportController@yarnIsu');
Route::get('/todayinventoryreport/dyechemrcv', 'Report\TodayInventoryReportController@dyechemRcv');
Route::get('/todayinventoryreport/dyechemisu', 'Report\TodayInventoryReportController@dyechemIsu');
Route::get('/todayinventoryreport/greyfabrcv', 'Report\TodayInventoryReportController@greyfabRcv');
Route::get('/todayinventoryreport/greyfabisu', 'Report\TodayInventoryReportController@greyfabIsu');



Route::get('/prodgmtcapacityachievement',        'Report\ProdGmtCapacityAchievementController@index')->name('index');
Route::get('/prodgmtcapacityachievement/getdata', 'Report\ProdGmtCapacityAchievementController@formatOne');
Route::get('/prodgmtcapacityachievement/getsewing', 'Report\ProdGmtCapacityAchievementController@getSewing');
Route::get('/prodgmtcapacityachievement/getbep', 'Report\ProdGmtCapacityAchievementController@getbep');
Route::get('/prodgmtcapacityachievement/getcm', 'Report\ProdGmtCapacityAchievementController@getcm');
Route::get('/prodgmtcapacityachievement/getcarton', 'Report\ProdGmtCapacityAchievementController@getCarton');
Route::get('/prodgmtcapacityachievement/getcartonmonth', 'Report\ProdGmtCapacityAchievementController@getCartonMonth');
Route::get('/prodgmtcapacityachievement/getdataall', 'Report\ProdGmtCapacityAchievementController@getDataAll');
Route::get('/prodgmtcapacityachievement/getdataallformonth', 'Report\ProdGmtCapacityAchievementController@getDataAllForMonth');
Route::get('/prodgmtcapacityachievement/getcutting', 'Report\ProdGmtCapacityAchievementController@getCutting');
Route::get('/prodgmtcapacityachievement/getcuttingmonth', 'Report\ProdGmtCapacityAchievementController@getCuttingMonth');
Route::get('/prodgmtcapacityachievement/getscprint', 'Report\ProdGmtCapacityAchievementController@getScprint');
Route::get('/prodgmtcapacityachievement/getscprintmonth', 'Report\ProdGmtCapacityAchievementController@getScprintMonth');
Route::get('/prodgmtcapacityachievement/getscprintmonthtgt', 'Report\ProdGmtCapacityAchievementController@getScprintMonthTgt');
Route::get('/prodgmtcapacityachievement/getemb', 'Report\ProdGmtCapacityAchievementController@getEmb');
Route::get('/prodgmtcapacityachievement/getembmonth', 'Report\ProdGmtCapacityAchievementController@getEmbMonth');
Route::get('/prodgmtcapacityachievement/getembmonthtgt', 'Report\ProdGmtCapacityAchievementController@getEmbMonthTgt');
Route::get('/prodgmtcapacityachievement/getaopmonthtgt', 'Report\ProdGmtCapacityAchievementController@getAopMonthTgt');
Route::get('/prodgmtcapacityachievement/getexfactory', 'Report\ProdGmtCapacityAchievementController@getExfactory');
Route::get('/prodgmtcapacityachievement/getexfactorymonth', 'Report\ProdGmtCapacityAchievementController@getExfactoryMonth');
Route::get('/prodgmtcapacityachievement/getsewingmonth', 'Report\ProdGmtCapacityAchievementController@getSewingMonth');
Route::get('/prodgmtcapacityachievement/getinvoicemonth', 'Report\ProdGmtCapacityAchievementController@getInvoiceMonth');
Route::get('/prodgmtcapacityachievement/getsewingqtymonth', 'Report\ProdGmtCapacityAchievementController@getSewingQtyMonth');
/* Route::get('/prodgmtcapacityachievement/getfinishingqtymonth', 'Report\ProdGmtCapacityAchievementController@getFinishingQtyMonth');
Route::get('/prodgmtcapacityachievement/getexfactoryqtymonth', 'Report\ProdGmtCapacityAchievementController@getExfactoryQtyMonth'); */

Route::get('/prodfabriccapacityachievement',        'Report\ProdFabricCapacityAchievementController@index')->name('index');
Route::get('/prodfabriccapacityachievement/getdata', 'Report\ProdFabricCapacityAchievementController@formatOne');
Route::get('/prodfabriccapacityachievement/fabricmonthtarget', 'Report\ProdFabricCapacityAchievementController@fabricmonthTarget');
Route::get('/prodfabriccapacityachievement/knittodayachieve', 'Report\ProdFabricCapacityAchievementController@knitTodayAchive');
Route::get('/prodfabriccapacityachievement/todayachievercvyarn', 'Report\ProdFabricCapacityAchievementController@todayAchiveRcvYarn');
Route::get('/prodfabriccapacityachievement/todayachieveknityarnissue', 'Report\ProdFabricCapacityAchievementController@todayAchiveKnitYarnIssue');
Route::get('/prodfabriccapacityachievement/todayachievedye', 'Report\ProdFabricCapacityAchievementController@todayAchiveDyeing');
Route::get('/prodfabriccapacityachievement/monthachievercvyarn', 'Report\ProdFabricCapacityAchievementController@MonthAchieveRcvYarn');
Route::get('/prodfabriccapacityachievement/monthachieveyarnissueknit', 'Report\ProdFabricCapacityAchievementController@MonthAchieveKnitYarnIssue');
Route::get('/prodfabriccapacityachievement/monthachieveknit', 'Report\ProdFabricCapacityAchievementController@MonthAchieveKnitting');

Route::get('/todaybep',        'Report\TodayBepController@index')->name('index');
Route::get('/todaybep/getdata', 'Report\TodayBepController@formatOne');


Route::get('/projectionprogress', 'Report\ProjectionProgressController@index')->name('index');
Route::get('/projectionprogress/getdata', 'Report\ProjectionProgressController@reportData');

Route::get('/quotationstatement', 'Report\QuotationStatementController@index')->name('index');
Route::get('/quotationstatement/getdata', 'Report\QuotationStatementController@reportData');
//Route::get('/quotationstatement/getfile', 'Report\QuotationStatementController@getFileSrc');
Route::get('/quotationstatement/getmktcostfilesrc', 'Report\QuotationStatementController@getMktCostFileSrc');
Route::get('/quotationstatement/getmktcostquoteprice', 'Report\QuotationStatementController@getMktCostQuotePrice');

Route::get('/costingnegoti', 'Report\CostingNegotiController@index')->name('index');
Route::get('/costingnegoti/getdata', 'Report\CostingNegotiController@reportData');
//Route::get('/costingnegoti/getfile', 'Report\QuotationStatementController@getFileSrc');
Route::get('/costingnegoti/getmktcostfilesrc', 'Report\CostingNegotiController@getMktCostFileSrc');
Route::get('/costingnegoti/getmktcostquoteprice', 'Report\CostingNegotiController@getMktCostQuotePrice');
Route::get('/costingnegoti/getdlmerchant', 'Report\CostingNegotiController@getTeamMemberDlm');


Route::get('/samplerequirement', 'Report\SampleRequirementController@index')->name('index');
Route::get('/samplerequirement/getdata', 'Report\SampleRequirementController@reportData');
Route::get('/samplerequirement/getdlmerchant', 'Report\SampleRequirementController@getDealMerchant');
Route::get('/samplerequirement/getbuyhouse', 'Report\SampleRequirementController@getBuyingHouse');
Route::get('/samplerequirement/getfile', 'Report\SampleRequirementController@getFileSrc');

Route::get('/prodgmtcartonqty', 'Report\GmtProduction\ProdGmtCartonQtyController@index')->name('index');
Route::get('/prodgmtcartonqty/getdata', 'Report\GmtProduction\ProdGmtCartonQtyController@reportData');

Route::get('/prodgmtlinewisehourly', 'Report\GmtProduction\ProdGmtLineWiseHourlyController@index')->name('index');
Route::get('/prodgmtlinewisehourly/getdata', 'Report\GmtProduction\ProdGmtLineWiseHourlyController@reportData');
Route::get('/prodgmtlinewisehourly/getdatadetails', 'Report\GmtProduction\ProdGmtLineWiseHourlyController@reportDataDetails');

Route::get('/prodgmtdailyreport', 'Report\GmtProduction\ProdGmtDailyReportController@index')->name('index');
Route::get('/prodgmtdailyreport/getdata', 'Report\GmtProduction\ProdGmtDailyReportController@reportData');
Route::get('/prodgmtdailyreport/prodgmtdlmerchant', 'Report\GmtProduction\ProdGmtDailyReportController@getProdGmtDlmerchant');
Route::get('/prodgmtdailyreport/getprodgmtfile', 'Report\GmtProduction\ProdGmtDailyReportController@getProdGmtFile');
Route::get('/dailyefficiencyreport', 'Report\GmtProduction\DailyEfficiencyReportController@index')->name('index');
Route::get('/dailyefficiencyreport/getdata', 'Report\GmtProduction\DailyEfficiencyReportController@reportData');
Route::get('/dailyefficiencyreport/getdatadetails', 'Report\GmtProduction\DailyEfficiencyReportController@reportDataDetails');
Route::get('/dailyefficiencyreport/getdatamonthly', 'Report\GmtProduction\DailyEfficiencyReportController@reportDataMonthly');

Route::get('/txtdailyefficiencyreport', 'Report\FabricProduction\TxtDailyEfficiencyReportController@index')->name('index');
Route::get('/txtdailyefficiencyreport/getdata', 'Report\FabricProduction\TxtDailyEfficiencyReportController@reportData');
Route::get('/txtdailyefficiencyreport/getdatamonthly', 'Report\FabricProduction\TxtDailyEfficiencyReportController@reportDataMonthly');

Route::get('/targetachievementreport', 'Report\TargetAchievementReportController@index')->name('index');
Route::get('/targetachievementreport/getdata', 'Report\TargetAchievementReportController@getData')->name('index');

Route::get('/groupsales', 'Report\GroupSaleReportController@index')->name('index');
Route::get('/groupsales/getdata', 'Report\GroupSaleReportController@reportData');
Route::get('/groupsales/getdyeingdetails', 'Report\GroupSaleReportController@getDyeingDetails');
Route::get('/groupsales/getaopdetails', 'Report\GroupSaleReportController@getAopDetails');
Route::get('/groupsales/getknitingdetails', 'Report\GroupSaleReportController@getKnitingDetails');
Route::get('/groupsales/getgmtdetails', 'Report\GroupSaleReportController@getGmtDetails');

Route::get('/centralbudgets', 'Report\CentralBudgetReportController@index')->name('index');
Route::get('/centralbudgets/getdata', 'Report\CentralBudgetReportController@reportData');
Route::get('/centralbudgets/getdetail', 'Report\CentralBudgetReportController@reportDetail');
Route::get('/centralbudgets/getdatabudvsacl', 'Report\CentralBudgetReportController@reportBudVsAcl');
Route::get('/centralbudgets/getdetailbudvsacl', 'Report\CentralBudgetReportController@reportDetailBudVsAcl');

Route::get('/monthlysewingcapacityreport', 'Report\GmtProduction\MonthlySewingCapacityReportController@index')->name('index');
Route::get('/monthlysewingcapacityreport/getdata', 'Report\GmtProduction\MonthlySewingCapacityReportController@reportData');

Route::get('/prodgmtexfactorydailyreport', 'Report\GmtProduction\ProdGmtDailyExFactoryReportController@index')->name('index');
Route::get('/prodgmtexfactorydailyreport/getdata', 'Report\GmtProduction\ProdGmtDailyExFactoryReportController@reportData');


Route::get('/prodgmtsewingproduction', 'Report\GmtProduction\ProdGmtSewingProductionController@index')->name('index');
Route::get('/prodgmtsewingproduction/getorder', 'Report\GmtProduction\ProdGmtSewingProductionController@getOrder');
Route::get('/prodgmtsewingproduction/getstyle', 'Report\GmtProduction\ProdGmtSewingProductionController@getStyle');
Route::get('/prodgmtsewingproduction/getdata', 'Report\GmtProduction\ProdGmtSewingProductionController@reportData');
Route::get('/prodgmtsewingproduction/prodgmtdlmerchant', 'Report\GmtProduction\ProdGmtSewingProductionController@getProdGmtDlmerchant');
Route::get('/prodgmtsewingproduction/getprodgmtfile', 'Report\GmtProduction\ProdGmtSewingProductionController@getProdGmtFile');
Route::get('/prodgmtsewingproduction/getbuyer', 'Report\GmtProduction\ProdGmtSewingProductionController@getBuyer');
Route::get('/prodgmtsewingproduction/getserviceprovider', 'Report\GmtProduction\ProdGmtSewingProductionController@getServiceProvider');
Route::get('/prodgmtsewingproduction/report', 'Report\GmtProduction\ProdGmtSewingProductionController@getPdf');

Route::get('/prodgmtstatusreport', 'Report\GmtProduction\ProdGmtStatusReportController@index')->name('index');
Route::get('/prodgmtstatusreport/getdata', 'Report\GmtProduction\ProdGmtStatusReportController@reportData');
Route::get('/prodgmtstatusreport/getsewingdetails', 'Report\GmtProduction\ProdGmtStatusReportController@getSewingData');
Route::get('/prodgmtstatusreport/gmtstylesearch', 'Report\GmtProduction\ProdGmtStatusReportController@getGmtStyle');
Route::get('/prodgmtstatusreport/getpdf', 'Report\GmtProduction\ProdGmtStatusReportController@reportPdf');

Route::get('/offerstatement', 'Report\OfferStatementController@index')->name('index');
Route::get('/offerstatement/getdata', 'Report\OfferStatementController@reportData');
Route::get('/offerstatement/pdf', 'Report\OfferStatementController@getpdf');
Route::get('/offerstatement/getfile', 'Report\OfferStatementController@getFileSrc');


Route::get('/coa', 'Report\Account\CoaController@index')->name('index');
Route::get('/coa/html', 'Report\Account\CoaController@html');
Route::get('/coa/pdf', 'Report\Account\CoaController@pdf');

Route::get('/gl', 'Report\Account\GlController@index')->name('index');
Route::get('/gl/getYear', 'Report\Account\GlController@getYear');
Route::get('/gl/getDateRange', 'Report\Account\GlController@getDateRange');
Route::get('/gl/html', 'Report\Account\GlController@html');
Route::get('/gl/pdf', 'Report\Account\GlController@pdf');
Route::get('/gl/getcode', 'Report\Account\GlController@getCode');

Route::get('/tb', 'Report\Account\TbController@index')->name('index');
Route::get('/tb/getYear', 'Report\Account\TbController@getYear');
Route::get('/tb/getDateRange', 'Report\Account\TbController@getDateRange');
Route::get('/tb/html', 'Report\Account\TbController@html');
Route::get('/tb/pdf', 'Report\Account\TbController@pdf');

Route::get('/glemp', 'Report\Account\GlEmpController@index')->name('index');
Route::get('/glemp/getYear', 'Report\Account\GlEmpController@getYear');
Route::get('/glemp/getDateRange', 'Report\Account\GlEmpController@getDateRange');
Route::get('/glemp/html', 'Report\Account\GlEmpController@html');
Route::get('/glemp/pdf', 'Report\Account\GlEmpController@pdf');


Route::get('/glbuy', 'Report\Account\GlBuyController@index')->name('index');
Route::get('/glbuy/getYear', 'Report\Account\GlBuyController@getYear');
Route::get('/glbuy/getDateRange', 'Report\Account\GlBuyController@getDateRange');
Route::get('/glbuy/html', 'Report\Account\GlBuyController@html');
Route::get('/glbuy/pdf', 'Report\Account\GlBuyController@pdf');

Route::get('/glsup', 'Report\Account\GlSupController@index')->name('index');
Route::get('/glsup/getYear', 'Report\Account\GlSupController@getYear');
Route::get('/glsup/getDateRange', 'Report\Account\GlSupController@getDateRange');
Route::get('/glsup/html', 'Report\Account\GlSupController@html');
Route::get('/glsup/pdf', 'Report\Account\GlSupController@pdf');

Route::get('/glotp', 'Report\Account\GlOtpController@index')->name('index');
Route::get('/glotp/getYear', 'Report\Account\GlOtpController@getYear');
Route::get('/glotp/getDateRange', 'Report\Account\GlOtpController@getDateRange');
Route::get('/glotp/html', 'Report\Account\GlOtpController@html');
Route::get('/glotp/pdf', 'Report\Account\GlOtpController@pdf');

Route::get('/glloanref', 'Report\Account\GlLoanRefController@index')->name('index');
Route::get('/glloanref/getYear', 'Report\Account\GlLoanRefController@getYear');
Route::get('/glloanref/getDateRange', 'Report\Account\GlLoanRefController@getDateRange');
Route::get('/glloanref/html', 'Report\Account\GlLoanRefController@html');
Route::get('/glloanref/pdf', 'Report\Account\GlLoanRefController@pdf');

Route::get('/glimplcref', 'Report\Account\GlImpLcRefController@index')->name('index');
Route::get('/glimplcref/getYear', 'Report\Account\GlImpLcRefController@getYear');
Route::get('/glimplcref/getDateRange', 'Report\Account\GlImpLcRefController@getDateRange');
Route::get('/glimplcref/html', 'Report\Account\GlImpLcRefController@html');
Route::get('/glimplcref/getacimport', 'Report\Account\GlImpLcRefController@getImportLcRef');
Route::get('/glimplcref/pdf', 'Report\Account\GlImpLcRefController@pdf');

Route::get('/glotherref', 'Report\Account\GlOtherRefController@index')->name('index');
Route::get('/glotherref/getYear', 'Report\Account\GlOtherRefController@getYear');
Route::get('/glotherref/getDateRange', 'Report\Account\GlOtherRefController@getDateRange');
Route::get('/glotherref/html', 'Report\Account\GlOtherRefController@html');
Route::get('/glotherref/pdf', 'Report\Account\GlOtherRefController@pdf');
Route::get('/glotherref/getreferenceno', 'Report\Account\GlOtherRefController@getOtpRefNo');


Route::get('/glopn', 'Report\Account\GlOpnController@index')->name('index');
Route::get('/glopn/getYear', 'Report\Account\GlOpnController@getYear');
Route::get('/glopn/getDateRange', 'Report\Account\GlOpnController@getDateRange');
Route::get('/glopn/html', 'Report\Account\GlOpnController@html');
Route::get('/glopn/pdf', 'Report\Account\GlOpnController@pdf');
Route::get('/glopn/getcode', 'Report\Account\GlOpnController@getCode');

Route::get('/receivable', 'Report\Account\ReceivableController@index')->name('index');
Route::get('/receivable/getcode', 'Report\Account\ReceivableController@getCode');
Route::get('/receivable/html', 'Report\Account\ReceivableController@html');
Route::get('/receivable/htmll', 'Report\Account\ReceivableController@htmll');
Route::get('/receivable/htmld', 'Report\Account\ReceivableController@htmld');
Route::get('/receivable/pdf', 'Report\Account\ReceivableController@pdf');

Route::get('/payable', 'Report\Account\PayableController@index')->name('index');
Route::get('/payable/getcode', 'Report\Account\PayableController@getCode');
Route::get('/payable/html', 'Report\Account\PayableController@html');
Route::get('/payable/htmll', 'Report\Account\PayableController@htmll');
Route::get('/payable/htmld', 'Report\Account\PayableController@htmld');
Route::get('/payable/getdpdf', 'Report\Account\PayableController@getpdfd');

Route::get('/groupreceivables', 'Report\Account\GroupReceivableController@index')->name('index');
Route::get('/groupreceivables/html', 'Report\Account\GroupReceivableController@html');
Route::get('/groupreceivables/getbuyerdetails', 'Report\Account\GroupReceivableController@getBuyerDetails');

Route::get('/bankloanreport', 'Report\Account\BankLoanReportController@index')->name('index');
Route::get('/bankloanreport/getdata', 'Report\Account\BankLoanReportController@getData');

Route::get('/incomestatement', 'Report\Account\IncomeStatementController@index')->name('index');
Route::get('/incomestatement/getYear', 'Report\Account\IncomeStatementController@getYear');
Route::get('/incomestatement/getPeriods', 'Report\Account\IncomeStatementController@getPeriods');
Route::get('/incomestatement/html', 'Report\Account\IncomeStatementController@html');
Route::get('/incomestatement/pdf', 'Report\Account\IncomeStatementController@pdf');

Route::get('/expensestatement', 'Report\Account\ExpenseStatementController@index')->name('index');
Route::get('/expensestatement/getYear', 'Report\Account\ExpenseStatementController@getYear');
Route::get('/expensestatement/getPeriods', 'Report\Account\ExpenseStatementController@getPeriods');
Route::get('/expensestatement/html', 'Report\Account\ExpenseStatementController@html');
Route::get('/expensestatement/pdf', 'Report\Account\ExpenseStatementController@pdf');

Route::get('/balancesheet', 'Report\Account\BalanceSheetController@index')->name('index');
Route::get('/balancesheet/getYear', 'Report\Account\IncomeStatementController@getYear');
Route::get('/balancesheet/getPeriods', 'Report\Account\BalanceSheetController@getPeriods');
Route::get('/balancesheet/html', 'Report\Account\BalanceSheetController@html');
Route::get('/balancesheet/pdf', 'Report\Account\BalanceSheetController@pdf');

Route::get('/mrrcheck', 'Report\Account\MrrCheckController@index')->name('index');
Route::get('/mrrcheck/html', 'Report\Account\MrrCheckController@html');

Route::get('/orderwisematerialcost', 'Report\Account\OrderWiseMaterialCostController@index')->name('index');
Route::get('/orderwisematerialcost/getyarn', 'Report\Account\OrderWiseMaterialCostController@getDataYarn');
Route::get('/orderwisematerialcost/getfabric', 'Report\Account\OrderWiseMaterialCostController@getDataFabric');
Route::get('/orderwisematerialcost/getknitting', 'Report\Account\OrderWiseMaterialCostController@getDataKnit');
Route::get('/orderwisematerialcost/getdyeing', 'Report\Account\OrderWiseMaterialCostController@getDataDyeing');
Route::get('/orderwisematerialcost/getaop', 'Report\Account\OrderWiseMaterialCostController@getDataAop');
Route::get('/orderwisematerialcost/gettrims', 'Report\Account\OrderWiseMaterialCostController@getDataTrim');
Route::get('/orderwisematerialcost/getpurchaseorderdtl', 'Report\Account\OrderWiseMaterialCostController@getPurchaseOrderDtl');


Route::get('approval/create', 'Approval\ApprovalController@create')->name('create');

// Route::get('/mktcostfirstapproval', 'Approval\MktCostFirstApprovalController@index');
// Route::get('/mktcostfirstapproval/getdata', 'Approval\MktCostFirstApprovalController@reportData');
// Route::post('/mktcostfirstapproval/approved', 'Approval\MktCostFirstApprovalController@approved');

Route::get('/mktcostconfirmation', 'Approval\MktCostConfirmationController@index');
Route::get('/mktcostconfirmation/getdata', 'Approval\MktCostConfirmationController@reportData');
Route::get('/mktcostconfirmation/getdataapproved', 'Approval\MktCostConfirmationController@reportDataApproved');
Route::get('/mktcostconfirmation/getdatareturned', 'Approval\MktCostConfirmationController@reportDataReturned');
Route::post('/mktcostconfirmation/confirmed', 'Approval\MktCostConfirmationController@confirmed');

Route::get('/mktcostapproval', 'Approval\MktCostApprovalController@index');
Route::get('/mktcostapproval/getdata', 'Approval\MktCostApprovalController@reportData');
Route::post('/mktcostapproval/firstapproved', 'Approval\MktCostApprovalController@firstapproved');
Route::post('/mktcostapproval/secondapproved', 'Approval\MktCostApprovalController@secondapproved');
Route::post('/mktcostapproval/thirdapproved', 'Approval\MktCostApprovalController@thirdapproved');
Route::post('/mktcostapproval/finalapproved', 'Approval\MktCostApprovalController@finalapproved');

Route::post('/mktcostapprovalreturn', 'Approval\MktCostApprovalController@approvalReturn');


Route::get('/sodyeingmktcostqpriceapproval', 'Approval\SoDyeingMktCostQpriceApprovalController@index');
Route::get('/sodyeingmktcostqpriceapproval/getdata', 'Approval\SoDyeingMktCostQpriceApprovalController@reportData');
Route::post('/sodyeingmktcostqpriceapproval/firstapproved', 'Approval\SoDyeingMktCostQpriceApprovalController@firstapproved');
Route::post('/sodyeingmktcostqpriceapproval/secondapproved', 'Approval\SoDyeingMktCostQpriceApprovalController@secondapproved');
Route::post('/sodyeingmktcostqpriceapproval/thirdapproved', 'Approval\SoDyeingMktCostQpriceApprovalController@thirdapproved');
Route::post('/sodyeingmktcostqpriceapproval/finalapproved', 'Approval\SoDyeingMktCostQpriceApprovalController@finalapproved');
Route::post('/sodyeingmktcostqpriceapprovalreturn', 'Approval\SoDyeingMktCostQpriceApprovalController@approvalReturn');

Route::get('/soaopmktcostqpriceapproval', 'Approval\SoAopMktCostQpriceApprovalController@index');
Route::get('/soaopmktcostqpriceapproval/getdata', 'Approval\SoAopMktCostQpriceApprovalController@reportData');
Route::post('/soaopmktcostqpriceapproval/firstapproved', 'Approval\SoAopMktCostQpriceApprovalController@firstapproved');
Route::post('/soaopmktcostqpriceapproval/secondapproved', 'Approval\SoAopMktCostQpriceApprovalController@secondapproved');
Route::post('/soaopmktcostqpriceapproval/thirdapproved', 'Approval\SoAopMktCostQpriceApprovalController@thirdapproved');
Route::post('/soaopmktcostqpriceapproval/finalapproved', 'Approval\SoAopMktCostQpriceApprovalController@finalapproved');
Route::post('/soaopmktcostqpriceapprovalreturn', 'Approval\SoAopMktCostQpriceApprovalController@approvalReturn');

Route::get('invpurreqapproval', 'Approval\InvPurReqApprovalController@index')->name('index');
Route::get('/invpurreqapproval/getdata', 'Approval\InvPurReqApprovalController@reportData');
Route::post('/invpurreqapproval/firstapproved', 'Approval\InvPurReqApprovalController@firstapproved');
Route::post('/invpurreqapproval/secondapproved', 'Approval\InvPurReqApprovalController@secondapproved');
Route::post('/invpurreqapproval/thirdapproved', 'Approval\InvPurReqApprovalController@thirdapproved');
Route::post('/invpurreqapproval/finalapproved', 'Approval\InvPurReqApprovalController@finalapproved');
Route::post('/invpurreqapprovalreturn', 'Approval\InvPurReqApprovalController@approvalReturn');

Route::get('invgeneralitemisureqapproval', 'Approval\InvGeneralItemIsuReqApprovalController@index')->name('index');
Route::get('/invgeneralitemisureqapproval/getdata', 'Approval\InvGeneralItemIsuReqApprovalController@reportData');
Route::post('/invgeneralitemisureqapproval/firstapproved', 'Approval\InvGeneralItemIsuReqApprovalController@firstapproved');
Route::post('/invgeneralitemisureqapproval/secondapproved', 'Approval\InvGeneralItemIsuReqApprovalController@secondapproved');
Route::post('/invgeneralitemisureqapproval/thirdapproved', 'Approval\InvGeneralItemIsuReqApprovalController@thirdapproved');
Route::post('/invgeneralitemisureqapproval/finalapproved', 'Approval\InvGeneralItemIsuReqApprovalController@finalapproved');

Route::get('approvalvisitor', 'Approval\ApprovalVisitorController@index')->name('index');
Route::get('/approvalvisitor/getdata', 'Approval\ApprovalVisitorController@reportData');
Route::get('/approvalvisitor/approvedvisit', 'Approval\ApprovalVisitorController@visitApproved');
Route::post('/approvalvisitor/approved', 'Approval\ApprovalVisitorController@approved');


Route::get('soknitdlvapproval', 'Approval\SoKnitDlvApprovalController@index')->name('index');
Route::get('/soknitdlvapproval/getdata', 'Approval\SoKnitDlvApprovalController@reportData');
Route::get('/soknitdlvapproval/pdf', 'Approval\SoKnitDlvApprovalController@pdf');
Route::post('/soknitdlvapproval/approved', 'Approval\SoKnitDlvApprovalController@approved');
Route::get('/soknitdlvapproval/getdataapp', 'Approval\SoKnitDlvApprovalController@reportDataApp');
Route::post('/soknitdlvapproval/unapproved', 'Approval\SoKnitDlvApprovalController@unapproved');

Route::get('sodyeingdlvapproval', 'Approval\SoDyeingDlvApprovalController@index')->name('index');
Route::get('/sodyeingdlvapproval/getdata', 'Approval\SoDyeingDlvApprovalController@reportData');
Route::get('/sodyeingdlvapproval/pdf', 'Approval\SoDyeingDlvApprovalController@pdf');
Route::post('/sodyeingdlvapproval/approved', 'Approval\SoDyeingDlvApprovalController@approved');
Route::get('/sodyeingdlvapproval/getdataapp', 'Approval\SoDyeingDlvApprovalController@reportDataApp');
Route::post('/sodyeingdlvapproval/unapproved', 'Approval\SoDyeingDlvApprovalController@unapproved');

Route::get('soaopdlvapproval', 'Approval\SoAopDlvApprovalController@index')->name('index');
Route::get('/soaopdlvapproval/getdata', 'Approval\SoAopDlvApprovalController@reportData');
Route::get('/soaopdlvapproval/pdf', 'Approval\SoAopDlvApprovalController@pdf');
Route::post('/soaopdlvapproval/approved', 'Approval\SoAopDlvApprovalController@approved');
Route::get('/soaopdlvapproval/getdataapp', 'Approval\SoAopDlvApprovalController@reportDataApp');
Route::post('/soaopdlvapproval/unapproved', 'Approval\SoAopDlvApprovalController@unapproved');

Route::get('prodbatchapproval', 'Approval\ProdBatchApprovalController@index')->name('index');
Route::get('/prodbatchapproval/getdata', 'Approval\ProdBatchApprovalController@reportData');
Route::post('/prodbatchapproval/approved', 'Approval\ProdBatchApprovalController@approved');
Route::get('/prodbatchapproval/getdataapp', 'Approval\ProdBatchApprovalController@reportDataApp');
Route::post('/prodbatchapproval/unapproved', 'Approval\ProdBatchApprovalController@unapproved');

Route::get('/employeemovementapproval', 'Approval\EmployeeMovementApprovalController@index')->name('index');
Route::get('/employeemovementapproval/getdata', 'Approval\EmployeeMovementApprovalController@reportData');
Route::post('/employeemovementapproval/approved', 'Approval\EmployeeMovementApprovalController@approved');
Route::get('budgetapproval', 'Approval\BudgetApprovalController@index')->name('index');
Route::get('/budgetapproval/getdata', 'Approval\BudgetApprovalController@reportData');
Route::post('/budgetapproval/approved', 'Approval\BudgetApprovalController@approved');
Route::get('/budgetapproval/getdataapp', 'Approval\BudgetApprovalController@reportDataApp');
Route::post('/budgetapproval/unapproved', 'Approval\BudgetApprovalController@unapproved');

//Route::get('budgetfabricapproval', 'Approval\BudgetFabricApprovalController@index')->name('index');
Route::get('/budgetfabricapproval/getdata', 'Approval\BudgetFabricApprovalController@reportData');

Route::post('/budgetfabricapproval/firstapproved', 'Approval\BudgetFabricApprovalController@firstapproved');
Route::post('/budgetfabricapproval/secondapproved', 'Approval\BudgetFabricApprovalController@secondapproved');
Route::post('/budgetfabricapproval/thirdapproved', 'Approval\BudgetFabricApprovalController@thirdapproved');
Route::post('/budgetfabricapproval/finalapproved', 'Approval\BudgetFabricApprovalController@finalapproved');
Route::post('/budgetfabricapprovalreturn', 'Approval\BudgetFabricApprovalController@approvalReturn');

//Route::get('budgetyarnapproval', 'Approval\BudgetYarnApprovalController@index')->name('index');
Route::get('/budgetyarnapproval/getdata', 'Approval\BudgetYarnApprovalController@reportData');

Route::post('/budgetyarnapproval/firstapproved', 'Approval\BudgetYarnApprovalController@firstapproved');
Route::post('/budgetyarnapproval/secondapproved', 'Approval\BudgetYarnApprovalController@secondapproved');
Route::post('/budgetyarnapproval/thirdapproved', 'Approval\BudgetYarnApprovalController@thirdapproved');
Route::post('/budgetyarnapproval/finalapproved', 'Approval\BudgetYarnApprovalController@finalapproved');
Route::post('/budgetyarnapprovalreturn', 'Approval\BudgetYarnApprovalController@approvalReturn');

//Route::get('budgetyarndyeapproval', 'Approval\BudgetYarndyeApprovalController@index')->name('index');
Route::get('/budgetyarndyeapproval/getdata', 'Approval\BudgetYarndyeApprovalController@reportData');

Route::post('/budgetyarndyeapproval/firstapproved', 'Approval\BudgetYarndyeApprovalController@firstapproved');
Route::post('/budgetyarndyeapproval/secondapproved', 'Approval\BudgetYarndyeApprovalController@secondapproved');
Route::post('/budgetyarndyeapproval/thirdapproved', 'Approval\BudgetYarndyeApprovalController@thirdapproved');
Route::post('/budgetyarndyeapproval/finalapproved', 'Approval\BudgetYarndyeApprovalController@finalapproved');
Route::post('/budgetyarndyeapprovalreturn', 'Approval\BudgetYarndyeApprovalController@approvalReturn');

//Route::get('budgetfabricprodapproval', 'Approval\BudgetFabricprodApprovalController@index')->name('index');
Route::get('/budgetfabricprodapproval/getdata', 'Approval\BudgetFabricprodApprovalController@reportData');

Route::post('/budgetfabricprodapproval/firstapproved', 'Approval\BudgetFabricprodApprovalController@firstapproved');
Route::post('/budgetfabricprodapproval/secondapproved', 'Approval\BudgetFabricprodApprovalController@secondapproved');
Route::post('/budgetfabricprodapproval/thirdapproved', 'Approval\BudgetFabricprodApprovalController@thirdapproved');
Route::post('/budgetfabricprodapproval/finalapproved', 'Approval\BudgetFabricprodApprovalController@finalapproved');
Route::post('/budgetfabricprodapprovalreturn', 'Approval\BudgetFabricprodApprovalController@approvalReturn');

//Route::get('budgetembelapproval', 'Approval\BudgetEmbelApprovalController@index')->name('index');
Route::get('/budgetembelapproval/getdata', 'Approval\BudgetEmbelApprovalController@reportData');

Route::post('/budgetembelapproval/firstapproved', 'Approval\BudgetEmbelApprovalController@firstapproved');
Route::post('/budgetembelapproval/secondapproved', 'Approval\BudgetEmbelApprovalController@secondapproved');
Route::post('/budgetembelapproval/thirdapproved', 'Approval\BudgetEmbelApprovalController@thirdapproved');
Route::post('/budgetembelapproval/finalapproved', 'Approval\BudgetEmbelApprovalController@finalapproved');
Route::post('/budgetembelapprovalreturn', 'Approval\BudgetEmbelApprovalController@approvalReturn');

//Route::get('budgettrimapproval', 'Approval\BudgetTrimApprovalController@index')->name('index');
Route::get('/budgettrimapproval/getdata', 'Approval\BudgetTrimApprovalController@reportData');

Route::post('/budgettrimapproval/firstapproved', 'Approval\BudgetTrimApprovalController@firstapproved');
Route::post('/budgettrimapproval/secondapproved', 'Approval\BudgetTrimApprovalController@secondapproved');
Route::post('/budgettrimapproval/thirdapproved', 'Approval\BudgetTrimApprovalController@thirdapproved');
Route::post('/budgettrimapproval/finalapproved', 'Approval\BudgetTrimApprovalController@finalapproved');
Route::post('/budgettrimapprovalreturn', 'Approval\BudgetTrimApprovalController@approvalReturn');

//Route::get('budgetotherapproval', 'Approval\BudgetOtherApprovalController@index')->name('index');
Route::get('/budgetotherapproval/getdata', 'Approval\BudgetOtherApprovalController@reportData');

Route::post('/budgetotherapproval/firstapproved', 'Approval\BudgetOtherApprovalController@firstapproved');
Route::post('/budgetotherapproval/secondapproved', 'Approval\BudgetOtherApprovalController@secondapproved');
Route::post('/budgetotherapproval/thirdapproved', 'Approval\BudgetOtherApprovalController@thirdapproved');
Route::post('/budgetotherapproval/finalapproved', 'Approval\BudgetOtherApprovalController@finalapproved');
Route::post('/budgetotherapprovalreturn', 'Approval\BudgetOtherApprovalController@approvalReturn');

//Route::get('budgetallapproval', 'Approval\BudgetAllApprovalController@index')->name('index');
Route::get('/budgetallapproval/getdata', 'Approval\BudgetAllApprovalController@reportData');

Route::post('/budgetallapproval/firstapproved', 'Approval\BudgetAllApprovalController@firstapproved');
Route::post('/budgetallapproval/secondapproved', 'Approval\BudgetAllApprovalController@secondapproved');
Route::post('/budgetallapproval/thirdapproved', 'Approval\BudgetAllApprovalController@thirdapproved');
Route::post('/budgetallapproval/finalapproved', 'Approval\BudgetAllApprovalController@finalapproved');
Route::post('/budgetallapprovalreturn', 'Approval\BudgetAllApprovalController@approvalReturn');

Route::get('budgetapprovalstatus', 'Approval\BudgetApprovalStatusController@index')->name('index');
Route::get('/budgetapprovalstatus/getdata', 'Approval\BudgetApprovalStatusController@reportData');
Route::get('/budgetapprovalstatus/getdatareturn', 'Approval\BudgetApprovalStatusController@reportRtnData');


Route::get('pofabricapproval', 'Approval\PoFabricApprovalController@index')->name('index');
Route::get('/pofabricapproval/reportsummeryhtml', 'Approval\PoFabricApprovalController@getFabricSummery');
Route::get('/pofabricapproval/getrcvno', 'Approval\PoFabricApprovalController@getRcvNo');
Route::get('/pofabricapproval/podetails', 'Approval\PoFabricApprovalController@poDetails');
Route::get('/pofabricapproval/getdata', 'Approval\PoFabricApprovalController@reportData');
Route::post('/pofabricapproval/approved', 'Approval\PoFabricApprovalController@approved');
Route::get('/pofabricapproval/getdataapp', 'Approval\PoFabricApprovalController@reportDataApp');
Route::post('/pofabricapproval/unapproved', 'Approval\PoFabricApprovalController@unapproved');

Route::get('potrimapproval', 'Approval\PoTrimApprovalController@index')->name('index');
Route::get('/potrimapproval/reportsummeryhtml', 'Approval\PoTrimApprovalController@getTrimsSummery');
Route::get('/potrimapproval/getrcvno', 'Approval\PoTrimApprovalController@getRcvNo');
Route::get('/potrimapproval/podetails', 'Approval\PoTrimApprovalController@poDetails');
Route::get('/potrimapproval/getdata', 'Approval\PoTrimApprovalController@reportData');
Route::post('/potrimapproval/approved', 'Approval\PoTrimApprovalController@approved');
Route::get('/potrimapproval/getdataapp', 'Approval\PoTrimApprovalController@reportDataApp');
Route::post('/potrimapproval/unapproved', 'Approval\PoTrimApprovalController@unapproved');

Route::get('employeehrapproval', 'Approval\EmployeeHRApprovalController@index')->name('index');
Route::get('/employeehrapproval/getdata', 'Approval\EmployeeHRApprovalController@reportData');
Route::post('/employeehrapproval/approved', 'Approval\EmployeeHRApprovalController@approved');

Route::get('employeehrstatusapproval', 'Approval\EmployeeHRStatusApprovalController@index')->name('index');
Route::get('/employeehrstatusapproval/getdata', 'Approval\EmployeeHRStatusApprovalController@reportData');
Route::post('/employeehrstatusapproval/approved', 'Approval\EmployeeHRStatusApprovalController@approved');

Route::get('employeerecruitreqapproval', 'Approval\EmployeeRecruitReqApprovalController@index')->name('index');
Route::get('/employeerecruitreqapproval/getdata', 'Approval\EmployeeRecruitReqApprovalController@reportData');
Route::get('/employeerecruitreqapproval/getempreplace', 'Approval\EmployeeRecruitReqApprovalController@getEmpReplaced');
Route::get('/employeerecruitreqapproval/getemprecruitreqjod', 'Approval\EmployeeRecruitReqApprovalController@getEmpRecruitReqJobDesc');
Route::post('/employeerecruitreqapproval/approved', 'Approval\EmployeeRecruitReqApprovalController@approved');

Route::get('salesordershipdatechangeapproval', 'Approval\SalesOrderShipDateChangeApprovalController@index')->name('index');
Route::get('/salesordershipdatechangeapproval/getdata', 'Approval\SalesOrderShipDateChangeApprovalController@reportData');
Route::get('/salesordershipdatechangeapproval/salesorderprogress', 'Approval\SalesOrderShipDateChangeApprovalController@orderProgress');
Route::post('/salesordershipdatechangeapproval/approved', 'Approval\SalesOrderShipDateChangeApprovalController@approved');


Route::get('poyarnapproval', 'Approval\PoYarnApprovalController@index')->name('index');
Route::get('/poyarnapproval/getdata', 'Approval\PoYarnApprovalController@reportData');
Route::post('/poyarnapproval/approved', 'Approval\PoYarnApprovalController@approved');
Route::get('/poyarnapproval/getrcvno', 'Approval\PoYarnApprovalController@getRcvNo');
Route::get('/poyarnapproval/getdataapp', 'Approval\PoYarnApprovalController@reportDataApp');
Route::post('/poyarnapproval/unapproved', 'Approval\PoYarnApprovalController@unapproved');

Route::get('poaopserviceapproval', 'Approval\PoAopServiceApprovalController@index')->name('index');
Route::get('/poaopserviceapproval/reportsummeryhtml', 'Approval\PoAopServiceApprovalController@getAopServiceSummery');
Route::get('/poaopserviceapproval/podetails', 'Approval\PoAopServiceApprovalController@poDetails');
Route::get('/poaopserviceapproval/getdata', 'Approval\PoAopServiceApprovalController@reportData');
Route::post('/poaopserviceapproval/approved', 'Approval\PoAopServiceApprovalController@approved');
Route::get('/poaopserviceapproval/getdataapp', 'Approval\PoAopServiceApprovalController@reportDataApp');
Route::post('/poaopserviceapproval/unapproved', 'Approval\PoAopServiceApprovalController@unapproved');

Route::get('poknitserviceapproval', 'Approval\PoKnitServiceApprovalController@index')->name('index');
Route::get('/poknitserviceapproval/reportsummeryhtml', 'Approval\PoKnitServiceApprovalController@getKnitServiceSummery');
Route::get('/poknitserviceapproval/podetails', 'Approval\PoKnitServiceApprovalController@poDetails');
Route::get('/poknitserviceapproval/getdata', 'Approval\PoKnitServiceApprovalController@reportData');
Route::post('/poknitserviceapproval/approved', 'Approval\PoKnitServiceApprovalController@approved');
Route::get('/poknitserviceapproval/getdataapp', 'Approval\PoKnitServiceApprovalController@reportDataApp');
Route::post('/poknitserviceapproval/unapproved', 'Approval\PoKnitServiceApprovalController@unapproved');

Route::get('podyeingserviceapproval', 'Approval\PoDyeingServiceApprovalController@index')->name('index');
Route::get('/podyeingserviceapproval/reportsummeryhtml', 'Approval\PoDyeingServiceApprovalController@getDyeingServiceSummery');
Route::get('/podyeingserviceapproval/podetails', 'Approval\PoDyeingServiceApprovalController@poDetails');
Route::get('/podyeingserviceapproval/getdata', 'Approval\PoDyeingServiceApprovalController@reportData');
Route::post('/podyeingserviceapproval/approved', 'Approval\PoDyeingServiceApprovalController@approved');
Route::get('/podyeingserviceapproval/getdataapp', 'Approval\PoDyeingServiceApprovalController@reportDataApp');
Route::post('/podyeingserviceapproval/unapproved', 'Approval\PoDyeingServiceApprovalController@unapproved');

Route::get('podyechemapproval', 'Approval\PoDyeChemApprovalController@index')->name('index');
Route::get('podyechemapproval/getdata', 'Approval\PoDyeChemApprovalController@reportData');
Route::post('podyechemapproval/approved', 'Approval\PoDyeChemApprovalController@approved');
Route::get('podyechemapproval/getdataapp', 'Approval\PoDyeChemApprovalController@reportDataApp');
Route::get('/podyechemapproval/getrcvno', 'Approval\PoDyeChemApprovalController@getRcvNo');
Route::post('podyechemapproval/unapproved', 'Approval\PoDyeChemApprovalController@unapproved');

Route::get('poembserviceapproval', 'Approval\PoEmbServiceApprovalController@index')->name('index');
Route::get('poembserviceapproval/getdata', 'Approval\PoEmbServiceApprovalController@reportData');
Route::get('/poembserviceapproval/reportsummeryhtml', 'Approval\PoEmbServiceApprovalController@getEmbServiceSummery');
Route::get('/poembserviceapproval/podetails', 'Approval\PoEmbServiceApprovalController@poDetails');
Route::post('poembserviceapproval/approved', 'Approval\PoEmbServiceApprovalController@approved');
Route::get('poembserviceapproval/getdataapp', 'Approval\PoEmbServiceApprovalController@reportDataApp');
Route::post('poembserviceapproval/unapproved', 'Approval\PoEmbServiceApprovalController@unapproved');

Route::get('pogeneralapproval', 'Approval\PoGeneralApprovalController@index')->name('index');
Route::get('/pogeneralapproval/getdata', 'Approval\PoGeneralApprovalController@reportData');
Route::post('/pogeneralapproval/approved', 'Approval\PoGeneralApprovalController@approved');
Route::get('/pogeneralapproval/getdataapp', 'Approval\PoGeneralApprovalController@reportDataApp');
Route::get('/pogeneralapproval/getrcvno', 'Approval\PoGeneralApprovalController@getRcvNo');
Route::post('/pogeneralapproval/unapproved', 'Approval\PoGeneralApprovalController@unapproved');

Route::get('pogeneralserviceapproval', 'Approval\PoGeneralServiceApprovalController@index')->name('index');
Route::get('/pogeneralserviceapproval/getdata', 'Approval\PoGeneralServiceApprovalController@reportData');
Route::post('/pogeneralserviceapproval/approved', 'Approval\PoGeneralServiceApprovalController@approved');
Route::get('/pogeneralserviceapproval/getdataapp', 'Approval\PoGeneralServiceApprovalController@reportDataApp');
Route::post('/pogeneralserviceapproval/unapproved', 'Approval\PoGeneralServiceApprovalController@unapproved');

Route::get('poyarndyeingapproval', 'Approval\PoYarnDyeingApprovalController@index')->name('index');
Route::get('/poyarndyeingapproval/getdata', 'Approval\PoYarnDyeingApprovalController@reportData');
Route::post('/poyarndyeingapproval/approved', 'Approval\PoYarnDyeingApprovalController@approved');
Route::get('/poyarndyeingapproval/getdataapp', 'Approval\PoYarnDyeingApprovalController@reportDataApp');
Route::post('/poyarndyeingapproval/unapproved', 'Approval\PoYarnDyeingApprovalController@unapproved');

Route::get('rqyarnapproval', 'Approval\RqYarnApprovalController@index')->name('index');
Route::get('/rqyarnapproval/getdata', 'Approval\RqYarnApprovalController@reportData');
Route::post('/rqyarnapproval/approved', 'Approval\RqYarnApprovalController@approved');
Route::get('/rqyarnapproval/getdataapp', 'Approval\RqYarnApprovalController@reportDataApp');
Route::post('/rqyarnapproval/unapproved', 'Approval\RqYarnApprovalController@unapproved');


Route::get('jhutesaledlvorderapproval', 'Approval\JhuteSaleDlvOrderApprovalController@index')->name('index');
Route::get('/jhutesaledlvorderapproval/getdata', 'Approval\JhuteSaleDlvOrderApprovalController@reportData');
Route::post('/jhutesaledlvorderapproval/approved', 'Approval\JhuteSaleDlvOrderApprovalController@approved');
Route::get('/jhutesaledlvorderapproval/getdataapp', 'Approval\JhuteSaleDlvOrderApprovalController@reportDataApp');
Route::post('/jhutesaledlvorderapproval/unapproved', 'Approval\JhuteSaleDlvOrderApprovalController@unapproved');

Route::get('jhutesaledlvorderapproval', 'Approval\JhuteSaleDlvOrderApprovalController@index')->name('index');
Route::get('/jhutesaledlvorderapproval/getdata', 'Approval\JhuteSaleDlvOrderApprovalController@reportData');
Route::post('/jhutesaledlvorderapproval/approved', 'Approval\JhuteSaleDlvOrderApprovalController@approved');
Route::get('/jhutesaledlvorderapproval/getdataapp', 'Approval\JhuteSaleDlvOrderApprovalController@reportDataApp');
Route::post('/jhutesaledlvorderapproval/unapproved', 'Approval\JhuteSaleDlvOrderApprovalController@unapproved');

Route::get('implcapproval', 'Approval\ImpLcApprovalController@index')->name('index');
Route::get('implcapproval/getdata', 'Approval\ImpLcApprovalController@reportData');
Route::get('implcapproval/pdf', 'Approval\ImpLcApprovalController@impLcProposalPdf');
Route::post('/implcapproval/approved', 'Approval\ImpLcApprovalController@approved');
Route::get('implcapproval/getdataapp', 'Approval\ImpLcApprovalController@reportDataApp');
Route::post('/implcapproval/unapproved', 'Approval\ImpLcApprovalController@unapproved');

Route::get('poaopserviceshortapproval', 'Approval\PoAopServiceShortApprovalController@index')->name('index');
Route::get('/poaopserviceshortapproval/getdata', 'Approval\PoAopServiceShortApprovalController@reportData');
Route::post('/poaopserviceshortapproval/approved', 'Approval\PoAopServiceShortApprovalController@approved');
Route::get('/poaopserviceshortapproval/getdataapp', 'Approval\PoAopServiceShortApprovalController@reportDataApp');
Route::post('/poaopserviceshortapproval/unapproved', 'Approval\PoAopServiceShortApprovalController@unapproved');

Route::get('podyeingserviceshortapproval', 'Approval\PoDyeingServiceShortApprovalController@index')->name('index');
Route::get('/podyeingserviceshortapproval/getdata', 'Approval\PoDyeingServiceShortApprovalController@reportData');
Route::post('/podyeingserviceshortapproval/approved', 'Approval\PoDyeingServiceShortApprovalController@approved');
Route::get('/podyeingserviceshortapproval/getdataapp', 'Approval\PoDyeingServiceShortApprovalController@reportDataApp');
Route::post('/podyeingserviceshortapproval/unapproved', 'Approval\PoDyeingServiceShortApprovalController@unapproved');

Route::get('poembserviceshortapproval', 'Approval\PoEmbServiceShortApprovalController@index')->name('index');
Route::get('poembserviceshortapproval/getdata', 'Approval\PoEmbServiceShortApprovalController@reportData');
Route::post('poembserviceshortapproval/approved', 'Approval\PoEmbServiceShortApprovalController@approved');
Route::get('poembserviceshortapproval/getdataapp', 'Approval\PoEmbServiceShortApprovalController@reportDataApp');
Route::post('poembserviceshortapproval/unapproved', 'Approval\PoEmbServiceShortApprovalController@unapproved');

Route::get('pofabricshortapproval', 'Approval\PoFabricShortApprovalController@index')->name('index');
Route::get('/pofabricshortapproval/getdata', 'Approval\PoFabricShortApprovalController@reportData');
Route::post('/pofabricshortapproval/approved', 'Approval\PoFabricShortApprovalController@approved');
Route::get('/pofabricshortapproval/getdataapp', 'Approval\PoFabricShortApprovalController@reportDataApp');
Route::post('/pofabricshortapproval/unapproved', 'Approval\PoFabricShortApprovalController@unapproved');

Route::get('poknitserviceshortapproval', 'Approval\PoKnitServiceShortApprovalController@index')->name('index');
Route::get('/poknitserviceshortapproval/getdata', 'Approval\PoKnitServiceShortApprovalController@reportData');
Route::post('/poknitserviceshortapproval/approved', 'Approval\PoKnitServiceShortApprovalController@approved');
Route::get('/poknitserviceshortapproval/getdataapp', 'Approval\PoKnitServiceShortApprovalController@reportDataApp');
Route::post('/poknitserviceshortapproval/unapproved', 'Approval\PoKnitServiceShortApprovalController@unapproved');

Route::get('potrimshortapproval', 'Approval\PoTrimShortApprovalController@index')->name('index');
Route::get('/potrimshortapproval/getdata', 'Approval\PoTrimShortApprovalController@reportData');
Route::post('/potrimshortapproval/approved', 'Approval\PoTrimShortApprovalController@approved');
Route::get('/potrimshortapproval/getdataapp', 'Approval\PoTrimShortApprovalController@reportDataApp');
Route::post('/potrimshortapproval/unapproved', 'Approval\PoTrimShortApprovalController@unapproved');

Route::get('poyarndyeingshortapproval', 'Approval\PoYarnDyeingShortApprovalController@index')->name('index');
Route::get('/poyarndyeingshortapproval/getdata', 'Approval\PoYarnDyeingShortApprovalController@reportData');
Route::post('/poyarndyeingshortapproval/approved', 'Approval\PoYarnDyeingShortApprovalController@approved');
Route::get('/poyarndyeingshortapproval/getdataapp', 'Approval\PoYarnDyeingShortApprovalController@reportDataApp');
Route::post('/poyarndyeingshortapproval/unapproved', 'Approval\PoYarnDyeingShortApprovalController@unapproved');

Route::get('/employeelist', 'Report\HRM\EmployeeListController@index')->name('index');
Route::get('/employeelist/getdata', 'Report\HRM\EmployeeListController@html');
Route::get('/employeelist/report', 'Report\HRM\EmployeeListController@getpdf');

Route::get('/employeeinformation', 'Report\HRM\EmployeeInformationController@index')->name('index');
Route::get('/employeeinformation/getdata', 'Report\HRM\EmployeeInformationController@html');

Route::get('/employeejoiningsummery', 'Report\HRM\EmployeeJoiningSummeryController@index')->name('index');
Route::get('/employeejoiningsummery/getdata', 'Report\HRM\EmployeeJoiningSummeryController@getDepartmentData');
Route::get('/employeejoiningsummery/getsectiondata', 'Report\HRM\EmployeeJoiningSummeryController@getSectionData');
Route::get('/employeejoiningsummery/getsubsectiondata', 'Report\HRM\EmployeeJoiningSummeryController@getSubsectionData');
Route::get('/employeejoiningsummery/getdesignationdata', 'Report\HRM\EmployeeJoiningSummeryController@getDesignationData');
Route::get('/employeejoiningsummery/getsectionemployee', 'Report\HRM\EmployeeJoiningSummeryController@getSectionEmployee');
Route::get('/employeejoiningsummery/getsubsectionemployee', 'Report\HRM\EmployeeJoiningSummeryController@getSubSectionEmployee');
Route::get('/employeejoiningsummery/getdesignationemployee', 'Report\HRM\EmployeeJoiningSummeryController@getDesignationEmployee');
Route::get('/employeejoiningsummery/getdepartmentemployee', 'Report\HRM\EmployeeJoiningSummeryController@getDepartmentEmployee');

Route::get('/employeeinactivesummery', 'Report\HRM\EmployeeInactiveSummeryController@index')->name('index');
Route::get('/employeeinactivesummery/getdata', 'Report\HRM\EmployeeInactiveSummeryController@getDepartmentData');
Route::get('/employeeinactivesummery/getsectiondata', 'Report\HRM\EmployeeInactiveSummeryController@getSectionData');
Route::get('/employeeinactivesummery/getsubsectiondata', 'Report\HRM\EmployeeInactiveSummeryController@getSubsectionData');
Route::get('/employeeinactivesummery/getdesignationdata', 'Report\HRM\EmployeeInactiveSummeryController@getDesignationData');
Route::get('/employeeinactivesummery/getsectionemployee', 'Report\HRM\EmployeeInactiveSummeryController@getSectionEmployee');
Route::get('/employeeinactivesummery/getsubsectionemployee', 'Report\HRM\EmployeeInactiveSummeryController@getSubSectionEmployee');
Route::get('/employeeinactivesummery/getdesignationemployee', 'Report\HRM\EmployeeInactiveSummeryController@getDesignationEmployee');
Route::get('/employeeinactivesummery/getdepartmentemployee', 'Report\HRM\EmployeeInactiveSummeryController@getDepartmentEmployee');

Route::get('/dailyattendencereport', 'Report\HRM\DailyAttendenceReportController@index')->name('index');
Route::get('/dailyattendencereport/getdata', 'Report\HRM\DailyAttendenceReportController@html');
Route::get('/dailyattendencereport/getdatadept', 'Report\HRM\DailyAttendenceReportController@getdataDept');
Route::get('/dailyattendencereport/getdatasect', 'Report\HRM\DailyAttendenceReportController@getdataSect');
Route::get('/dailyattendencereport/getdatasubsect', 'Report\HRM\DailyAttendenceReportController@getdataSubSect');
Route::get('/dailyattendencereport/getdatadegn', 'Report\HRM\DailyAttendenceReportController@getdataDegn');
Route::get('/dailyattendencereport/getdataempl', 'Report\HRM\DailyAttendenceReportController@getdataEmpl');

Route::get('/registervisitorreport', 'Report\HRM\RegisterVisitorReportController@index')->name('index');
Route::get('/registervisitorreport/getdata', 'Report\HRM\RegisterVisitorReportController@html');

Route::get('/employeetodolistreport', 'Report\HRM\EmployeeToDoListReportController@index')->name('index');
Route::get('/employeetodolistreport/getdata', 'Report\HRM\EmployeeToDoListReportController@reportData');
Route::get('/employeetodolistreport/report', 'Report\HRM\EmployeeToDoListReportController@getPdf');

Route::get('/employeemovementreport', 'Report\HRM\EmployeeMovementReportController@index')->name('index');
Route::get('/employeemovementreport/getdata', 'Report\HRM\EmployeeMovementReportController@reportData');
Route::get('/employeemovementreport/getdeparmentwise', 'Report\HRM\EmployeeMovementReportController@departmentWise');
Route::get('/employeemovementreport/getdepemp', 'Report\HRM\EmployeeMovementReportController@dEmployeeDtl');

Route::get('/famlist', 'Report\FAM\FamListController@index')->name('index');
Route::get('/famlist/getdata', 'Report\FAM\FamListController@html');
Route::get('/famlist/assetticket', 'Report\FAM\FamListController@assetTicket');

Route::get('/assetbreakdownreport', 'Report\FAM\AssetBreakdownReportController@index')->name('index');
Route::get('/assetbreakdownreport/getdata', 'Report\FAM\AssetBreakdownReportController@reportData');
Route::get('/assetbreakdownreport/getpurchaserequisition', 'Report\FAM\AssetBreakdownReportController@getPurchaseRequisition');
Route::get('/purchaserequisitionreport/getrequisition', 'Report\ItemBank\PurchaseRequisitionReportController@getRequisition');

Route::get('/subinbmarketingreport', 'Report\Subcontract\Inbound\SubInbMarketingReportController@index')->name('index');
//Route::get('/subinbmarketingreport/getdata', 'Report\Subcontract\Inbound\SubInbMarketingReportController@html');
Route::get('/subinbmarketingreport/getdata', 'Report\Subcontract\Inbound\SubInbMarketingReportController@getData');
Route::get('/subinbmarketingreport/getdetail', 'Report\Subcontract\Inbound\SubInbMarketingReportController@getdetail');

Route::get('/plknitreport', 'Report\Subcontract\Kniting\PlKnitReportController@index')->name('index');
Route::get('/plknitreport/html', 'Report\Subcontract\Kniting\PlKnitReportController@html');

Route::get('/plknitexireport', 'Report\Subcontract\Kniting\PlKnitExiReportController@index')->name('index');
Route::get('/plknitexireport/html', 'Report\Subcontract\Kniting\PlKnitExiReportController@html');

Route::get('/finishfabricdeliverykniting', 'Report\Subcontract\Kniting\FinishFabricDeliveryKnitingController@index')->name('index');
Route::get('/finishfabricdeliverykniting/getdataself', 'Report\Subcontract\Kniting\FinishFabricDeliveryKnitingController@getSelfData');
Route::get('/finishfabricdeliverykniting/getdatasubcontract', 'Report\Subcontract\Kniting\FinishFabricDeliveryKnitingController@getSubcontractData');

Route::get('/pldyeingreport', 'Report\Subcontract\Dyeing\PlDyeingReportController@index')->name('index');
Route::get('/pldyeingreport/html', 'Report\Subcontract\Dyeing\PlDyeingReportController@html');
Route::get('/pldyeingexireport', 'Report\Subcontract\Dyeing\PlDyeingExiReportController@index')->name('index');
Route::get('/pldyeingexireport/html', 'Report\Subcontract\Dyeing\PlDyeingExiReportController@html');


Route::get('/finishfabricdelivery', 'Report\Subcontract\Dyeing\FinishFabricDeliveryController@index')->name('index');
Route::get('/finishfabricdelivery/getdataself', 'Report\Subcontract\Dyeing\FinishFabricDeliveryController@getSelfData');
Route::get('/finishfabricdelivery/getdatasubcontract', 'Report\Subcontract\Dyeing\FinishFabricDeliveryController@getSubcontractData');

Route::get('/finishfabricdeliveryaop', 'Report\Subcontract\AOP\FinishFabricDeliveryAopController@index')->name('index');
Route::get('/finishfabricdeliveryaop/getdataself', 'Report\Subcontract\AOP\FinishFabricDeliveryAopController@getSelfData');
Route::get('/finishfabricdeliveryaop/getdatasubcontract', 'Report\Subcontract\AOP\FinishFabricDeliveryAopController@getSubcontractData');


Route::get('/liabilitycoveragereport', 'Report\Commercial\LiabilityCoverageReportController@index')->name('index');
Route::get('/liabilitycoveragereport/html', 'Report\Commercial\LiabilityCoverageReportController@html');
Route::get('/liabilitycoveragereport/htmlgrid', 'Report\Commercial\LiabilityCoverageReportController@htmlgrid');
Route::get('/liabilitycoveragereport/order', 'Report\Commercial\LiabilityCoverageReportController@order');
Route::get('/liabilitycoveragereport/invoiceqty', 'Report\Commercial\LiabilityCoverageReportController@getInvoiceQty');
Route::get('/liabilitycoveragereport/lcsc', 'Report\Commercial\LiabilityCoverageReportController@lcsc');
Route::get('/liabilitycoveragereport/btbopen', 'Report\Commercial\LiabilityCoverageReportController@btbopen');
Route::get('/liabilitycoveragereport/btbadjust', 'Report\Commercial\LiabilityCoverageReportController@btbadjust');
Route::get('/liabilitycoveragereport/btbadjustacceptdtail', 'Report\Commercial\LiabilityCoverageReportController@btbadjustAcceptDtail');
Route::get('/liabilitycoveragereport/pctaken', 'Report\Commercial\LiabilityCoverageReportController@pctaken');
Route::get('/liabilitycoveragereport/pcadjust', 'Report\Commercial\LiabilityCoverageReportController@pcadjust');
Route::get('/liabilitycoveragereport/docpur', 'Report\Commercial\LiabilityCoverageReportController@docpur');
Route::get('/liabilitycoveragereport/docadjust', 'Report\Commercial\LiabilityCoverageReportController@docadjust');
Route::get('/liabilitycoveragereport/getyarn', 'Report\Commercial\LiabilityCoverageReportController@getYarnRq');
Route::get('/liabilitycoveragereport/getfinfab', 'Report\Commercial\LiabilityCoverageReportController@getFinFabRq');
Route::get('/liabilitycoveragereport/getfilepdf', 'Report\Commercial\LiabilityCoverageReportController@getFilePdf');
Route::get('/liabilitycoveragereport/cashincetiveDtl', 'Report\Commercial\LiabilityCoverageReportController@getCashIncentiveDtails');

Route::get('/negotiationreport', 'Report\Commercial\NegotiationReportController@index')->name('index');
Route::get('/negotiationreport/htmlgrid', 'Report\Commercial\NegotiationReportController@htmlgrid');
Route::get('/negotiationreport/buyersummery', 'Report\Commercial\NegotiationReportController@buyersummery');
Route::get('/negotiationreport/buyerfollowupdata', 'Report\Commercial\NegotiationReportController@buyerFollowUp');

Route::get('/importconsignmentreport', 'Report\Commercial\ImportConsignmentReportController@index')->name('index');
Route::get('/importconsignmentreport/getfile', 'Report\Commercial\ImportConsignmentReportController@getImpLcFile');
Route::get('/importconsignmentreport/htmlgrid', 'Report\Commercial\ImportConsignmentReportController@htmlgrid');
Route::get('/importconsignmentreport/bankpending', 'Report\Commercial\ImportConsignmentReportController@bankPending');

Route::get('/expprorlzreport', 'Report\Commercial\ExpProRlzReportController@index')->name('index');
Route::get('/expprorlzreport/getdata', 'Report\Commercial\ExpProRlzReportController@reportData');



Route::get('/cashincentivereport', 'Report\Commercial\CashIncentiveReportController@index')->name('index');
Route::get('/cashincentivereport/getdata', 'Report\Commercial\CashIncentiveReportController@getData');
Route::get('/cashincentivereport/getclaim', 'Report\Commercial\CashIncentiveReportController@getClaim');
Route::get('/cashincentivereport/getdocprep', 'Report\Commercial\CashIncentiveReportController@getDocPrep');

Route::get('/monthlyexpinvoicereport', 'Report\Commercial\MonthlyExpInvoiceReportController@index')->name('index');
Route::get('/monthlyexpinvoicereport/getdata', 'Report\Commercial\MonthlyExpInvoiceReportController@getData')->name('index');
Route::get('/monthlyexpinvoicereport/getinvoicedata', 'Report\Commercial\MonthlyExpInvoiceReportController@invoiceDetails');

Route::get('advexpinvoicereport', 'Report\Commercial\AdvExpInvoiceReportController@index')->name('index');
Route::get('advexpinvoicereport/getexplcsc', 'Report\Commercial\AdvExpInvoiceReportController@getExportLcSc');
Route::get('advexpinvoicereport/getinvoice', 'Report\Commercial\AdvExpInvoiceReportController@getInvoice');
Route::get('/advexpinvoicereport/getdata', 'Report\Commercial\AdvExpInvoiceReportController@getData');

Route::get('/renewalreport',        'Report\Renewal\RenewalReportController@index')->name('index');
Route::get('/renewalreport/getdata', 'Report\Renewal\RenewalReportController@getData');
Route::get('/renewalreport/getrenewremarks', 'Report\Renewal\RenewalReportController@getRenewEntryRemarks');

Route::get('/itembank', 'Report\ItemBank\ItemBankController@index')->name('index');
Route::get('/itembank/getdata', 'Report\ItemBank\ItemBankController@reportData');
Route::get('/itembank/report', 'Report\ItemBank\ItemBankController@getpdf');

Route::get('/purchaseorderreport', 'Report\ItemBank\PurchaseOrderReportController@index')->name('index');
Route::get('/purchaseorderreport/getdata', 'Report\ItemBank\PurchaseOrderReportController@reportData');
Route::get('/purchaseorderreport/getrcvno', 'Report\ItemBank\PurchaseOrderReportController@getRcvNo');

Route::get('/trimsorderprogressreport', 'Report\ItemBank\TrimsOrderProgressReportController@index')->name('index');
Route::get('/trimsorderprogressreport/getdata', 'Report\ItemBank\TrimsOrderProgressReportController@reportData');
Route::get('/trimsorderprogressreport/gettrimsstyle', 'Report\ItemBank\TrimsOrderProgressReportController@getTrimsStyle');
Route::get('/trimsorderprogressreport/gettrimsteammemberdlm', 'Report\ItemBank\TrimsOrderProgressReportController@getTrimsDlMerchant');
Route::get('/trimsorderprogressreport/getpotrimqty', 'Report\ItemBank\TrimsOrderProgressReportController@getPoTrimQty');
Route::get('/trimsorderprogressreport/getrcvtrimqty', 'Report\ItemBank\TrimsOrderProgressReportController@getRcvTrimQty');

Route::get('/purchaserequisitionreport', 'Report\ItemBank\PurchaseRequisitionReportController@index')->name('index');
Route::get('/purchaserequisitionreport/getdata', 'Report\ItemBank\PurchaseRequisitionReportController@reportData');

Route::get('/garmentstockreport', 'Report\POS\GarmentStockReportController@index')->name('index');
Route::get('/garmentstockreport/getdata', 'Report\POS\GarmentStockReportController@reportData');
Route::get('/garmentstockreport/getreceiveqty', 'Report\POS\GarmentStockReportController@getReceiveQty');
Route::get('/garmentstockreport/getsalesqty', 'Report\POS\GarmentStockReportController@getSalesQty');


Route::get('/gateentryreport', 'Report\POS\GateEntryReportController@index')->name('index');
Route::get('/gateentryreport/getpo', 'Report\POS\GateEntryReportController@getPrPo');
Route::get('/gateentryreport/getdata', 'Report\POS\GateEntryReportController@reportData');


Route::get('/yarnstock', 'Report\Inventory\YarnStockController@index')->name('index');
Route::get('/yarnstock/getdata', 'Report\Inventory\YarnStockController@reportData');
Route::get('/yarnstock/getreceiveqty', 'Report\Inventory\YarnStockController@getReceiveQty');
Route::get('/yarnstock/getsalesqty', 'Report\Inventory\YarnStockController@getSalesQty');

Route::get('/generalstock', 'Report\Inventory\GeneralStockController@index')->name('index');
Route::get('/generalstock/getdata', 'Report\Inventory\GeneralStockController@reportData');

Route::get('/generalstockatreorderlevel', 'Report\Inventory\GeneralStockAtReorderLevelController@index')->name('index');
Route::get('/generalstockatreorderlevel/getdata', 'Report\Inventory\GeneralStockAtReorderLevelController@reportData');

Route::get('/dyechemstock', 'Report\Inventory\DyeChemStockController@index')->name('index');
Route::get('/dyechemstock/getdata', 'Report\Inventory\DyeChemStockController@reportData');

Route::get('/dyeissuereceive', 'Report\Inventory\DyeIssueReceiveController@index')->name('index');
Route::get('/dyeissuereceive/getdata', 'Report\Inventory\DyeIssueReceiveController@reportData');
Route::get('/dyeissuereceive/report', 'Report\Inventory\DyeIssueReceiveController@receivePdf');
Route::get('/dyeissuereceive/getissuedata', 'Report\Inventory\DyeIssueReceiveController@issueData');
Route::get('/dyeissuereceive/issuereport', 'Report\Inventory\DyeIssueReceiveController@issuePdf');

Route::get('/dyeissuereceivesummery', 'Report\Inventory\DyeIssueReceiveSummeryController@index')->name('index');
Route::get('/dyeissuereceivesummery/getissuedata', 'Report\Inventory\DyeIssueReceiveSummeryController@issueData');
Route::get('/dyeissuereceivesummery/getdata', 'Report\Inventory\DyeIssueReceiveSummeryController@reportData');

Route::get('/dyeissuereceivesummery/getregulardtl', 'Report\Inventory\DyeIssueReceiveSummeryController@getRegularDtl');
Route::get('/dyeissuereceivesummery/gettransdtl', 'Report\Inventory\DyeIssueReceiveSummeryController@getTransDtl');
Route::get('/dyeissuereceivesummery/getrcvrtndtl', 'Report\Inventory\DyeIssueReceiveSummeryController@getRcvRtnDtl');
Route::get('/dyeissuereceivesummery/getloandtl', 'Report\Inventory\DyeIssueReceiveSummeryController@getLoanDtl');
Route::get('/dyeissuereceivesummery/getrcvregular', 'Report\Inventory\DyeIssueReceiveSummeryController@getRcvRegular');
Route::get('/dyeissuereceivesummery/getrcvtransin', 'Report\Inventory\DyeIssueReceiveSummeryController@getRcvTransIn');
Route::get('/dyeissuereceivesummery/getisurtn', 'Report\Inventory\DyeIssueReceiveSummeryController@getIsuRtn');
Route::get('/dyeissuereceivesummery/getrcvloan', 'Report\Inventory\DyeIssueReceiveSummeryController@getRcvLoan');

Route::get('/dyechemloanledger', 'Report\Inventory\DyeChemLoanLedgerController@index')->name('index');
Route::get('/dyechemloanledger/getdata', 'Report\Inventory\DyeChemLoanLedgerController@reportData');
Route::get('/dyechemloanledger/getpdf', 'Report\Inventory\DyeChemLoanLedgerController@ledgerPdf');

Route::get('/dyechemstockatreorderlevel', 'Report\Inventory\DyeChemStockAtReorderLevelController@index')->name('index');
Route::get('/dyechemstockatreorderlevel/getdata', 'Report\Inventory\DyeChemStockAtReorderLevelController@reportData');

Route::get('/greyfabstock', 'Report\Inventory\GreyFabStockController@index')->name('index');
Route::get('/greyfabstock/getdata', 'Report\Inventory\GreyFabStockController@reportData');

Route::get('/yarnpurchaseratetrend', 'Report\Inventory\YarnPurchaseRateTrendController@index')->name('index');
Route::get('/yarnpurchaseratetrend/getdata', 'Report\Inventory\YarnPurchaseRateTrendController@reportData');

Route::get('/yarnpurchasesummery', 'Report\Inventory\YarnPurchaseSummeryController@index')->name('index');
Route::get('/yarnpurchasesummery/getdata', 'Report\Inventory\YarnPurchaseSummeryController@reportData');
Route::get('/yarnpurchasesummery/getrcvqtydtl', 'Report\Inventory\YarnPurchaseSummeryController@getRcvQtyDtl');


Route::get('/yarnpurchaselcwise', 'Report\Inventory\YarnPurchaseLcWiseController@index')->name('index');
Route::get('/yarnpurchaselcwise/getdata', 'Report\Inventory\YarnPurchaseLcWiseController@reportData');
Route::get('/yarnpurchaselcwise/getlcqtydtl', 'Report\Inventory\YarnPurchaseLcWiseController@getLcQtyDtl');
Route::get('/yarnpurchaselcwise/getrcvqtydtl', 'Report\Inventory\YarnPurchaseLcWiseController@getRcvQtyDtl');

Route::get('/dyechempurchaselcwise', 'Report\Inventory\DyeChemPurchaseLcWiseController@index')->name('index');
Route::get('/dyechempurchaselcwise/getdata', 'Report\Inventory\DyeChemPurchaseLcWiseController@reportData');
Route::get('/dyechempurchaselcwise/getlcqtydtl', 'Report\Inventory\DyeChemPurchaseLcWiseController@getLcQtyDtl');
Route::get('/dyechempurchaselcwise/getrcvqtydtl', 'Report\Inventory\DyeChemPurchaseLcWiseController@getRcvQtyDtl');

Route::get('/yarnreceivesummery', 'Report\Inventory\YarnReceiveSummeryController@index')->name('index');
Route::get('/yarnreceivesummery/getdata', 'Report\Inventory\YarnReceiveSummeryController@reportData');
Route::get('/yarnreceivesummery/getmrr', 'Report\Inventory\YarnReceiveSummeryController@getMrr');

Route::get('/yarnstockknitingparty', 'Report\Inventory\YarnStockKnitingPartyController@index')->name('index');
Route::get('/yarnstockknitingparty/getdata', 'Report\Inventory\YarnStockKnitingPartyController@reportData');
Route::get('/yarnstockknitingparty/issuedtl', 'Report\Inventory\YarnStockKnitingPartyController@issueDtl');
Route::get('/yarnstockknitingparty/returndtl', 'Report\Inventory\YarnStockKnitingPartyController@returnDtl');
Route::get('/yarnstockknitingparty/useddtl', 'Report\Inventory\YarnStockKnitingPartyController@usedDtl');

Route::get('/yarnstockyarndyeingparty', 'Report\Inventory\YarnStockYarnDyeingPartyController@index')->name('index');
Route::get('/yarnstockyarndyeingparty/getdata', 'Report\Inventory\YarnStockYarnDyeingPartyController@reportData');
Route::get('/yarnstockyarndyeingparty/issuedtl', 'Report\Inventory\YarnStockYarnDyeingPartyController@issueDtl');
Route::get('/yarnstockyarndyeingparty/returndtl', 'Report\Inventory\YarnStockYarnDyeingPartyController@returnDtl');
Route::get('/yarnstockyarndyeingparty/useddtl', 'Report\Inventory\YarnStockYarnDyeingPartyController@usedDtl');

Route::get('/receivedelivery', 'Report\Inventory\ReceiveDeliveryController@index')->name('index');
Route::get('/receivedelivery/getdata', 'Report\Inventory\ReceiveDeliveryController@reportData');


Route::get('/fabricstocksubcondyeingparty', 'Report\Subcontract\Dyeing\FabricStockDyeingSubConPartyController@index')->name('index');
Route::get('/fabricstocksubcondyeingparty/getdata', 'Report\Subcontract\Dyeing\FabricStockDyeingSubConPartyController@reportData');

Route::get('/fabricstocksubcondyeingparty/receivedtl', 'Report\Subcontract\Dyeing\FabricStockDyeingSubConPartyController@receiveDtl');
Route::get('/fabricstocksubcondyeingparty/useddtl', 'Report\Subcontract\Dyeing\FabricStockDyeingSubConPartyController@usedDtl');
Route::get('/fabricstocksubcondyeingparty/returndtl', 'Report\Subcontract\Dyeing\FabricStockDyeingSubConPartyController@returnDtl');
Route::get('/fabricstocksubcondyeingparty/closingdtl', 'Report\Subcontract\Dyeing\FabricStockDyeingSubConPartyController@closingDtl');

Route::get('/subcondyeingtarget', 'Report\Subcontract\Dyeing\SubConDyeingTargetController@index')->name('index');
Route::get('/subcondyeingtarget/getbuyerinfo', 'Report\Subcontract\Dyeing\SubConDyeingTargetController@getBuyerInfo');
Route::get('/subcondyeingtarget/getdata', 'Report\Subcontract\Dyeing\SubConDyeingTargetController@reportData');

Route::get('/subcondyeingbom', 'Report\Subcontract\Dyeing\SubConDyeingBomController@index')->name('index');
Route::get('/subcondyeingbom/getbuyerinfo', 'Report\Subcontract\Dyeing\SubConDyeingBomController@getBuyerInfo');
Route::get('/subcondyeingbom/getdata', 'Report\Subcontract\Dyeing\SubConDyeingBomController@reportData');
Route::get('/subcondyeingbom/getsummary', 'Report\Subcontract\Dyeing\SubConDyeingBomController@reportSummary');
Route::get('/subcondyeingbom/getchart', 'Report\Subcontract\Dyeing\SubConDyeingBomController@reportChart');
Route::get('/subcondyeingbom/getorderqty', 'Report\Subcontract\Dyeing\SubConDyeingBomController@getOrderQty');
Route::get('/subcondyeingbom/getdlvqty', 'Report\Subcontract\Dyeing\SubConDyeingBomController@getDlvQty');
Route::get('/subcondyeingbom/getdyeqty', 'Report\Subcontract\Dyeing\SubConDyeingBomController@getDyeQty');
Route::get('/subcondyeingbom/getchemqty', 'Report\Subcontract\Dyeing\SubConDyeingBomController@getChemQty');
Route::get('/subcondyeingbom/getohqty', 'Report\Subcontract\Dyeing\SubConDyeingBomController@getOhQty');

Route::get('/subcondyeingdelivery', 'Report\Subcontract\Dyeing\SubConDyeingDeliveryController@index')->name('index');
Route::get('/subcondyeingdelivery/getdata', 'Report\Subcontract\Dyeing\SubConDyeingDeliveryController@reportData');
Route::get('/subcondyeingdelivery/getsubcondlvitem', 'Report\Subcontract\Dyeing\SubConDyeingDeliveryController@getSoDlvItem');

Route::get('/fabricstocksubconaopparty', 'Report\Subcontract\AOP\FabricStockAopSubConPartyController@index')->name('index');
Route::get('/fabricstocksubconaopparty/getdata', 'Report\Subcontract\AOP\FabricStockAopSubConPartyController@reportData');
Route::get('/fabricstocksubconaopparty/receivedtl', 'Report\Subcontract\AOP\FabricStockAopSubConPartyController@receiveDtl');

Route::get('/fabricstocksubconaopparty/useddtl', 'Report\Subcontract\AOP\FabricStockAopSubConPartyController@usedDtl');
Route::get('/fabricstocksubconaopparty/returndtl', 'Report\Subcontract\AOP\FabricStockAopSubConPartyController@returnDtl');
Route::get('/fabricstocksubconaopparty/closingdtl', 'Report\Subcontract\AOP\FabricStockAopSubConPartyController@closingDtl');

Route::get('/subconaoptarget', 'Report\Subcontract\AOP\SubConAopTargetController@index')->name('index');
Route::get('/subconaoptarget/getbuyerinfo', 'Report\Subcontract\AOP\SubConAopTargetController@getBuyerInfo');
Route::get('/subconaoptarget/getdata', 'Report\Subcontract\AOP\SubConAopTargetController@reportData');


Route::get('/yarnstocksubconknitingparty', 'Report\Subcontract\Kniting\YarnStockKnitingSubConPartyController@index')->name('index');
Route::get('/yarnstocksubconknitingparty/getdata', 'Report\Subcontract\Kniting\YarnStockKnitingSubConPartyController@reportData');

Route::get('/yarnstocksubconknitingparty/receivedtl', 'Report\Subcontract\Kniting\YarnStockKnitingSubConPartyController@receiveDtl');
Route::get('/yarnstocksubconknitingparty/useddtl', 'Report\Subcontract\Kniting\YarnStockKnitingSubConPartyController@usedDtl');
Route::get('/yarnstocksubconknitingparty/returndtl', 'Report\Subcontract\Kniting\YarnStockKnitingSubConPartyController@returnDtl');
Route::get('/yarnstocksubconknitingparty/closingdtl', 'Report\Subcontract\Kniting\YarnStockKnitingSubConPartyController@closingDtl');

Route::get('/subconknitingtarget', 'Report\Subcontract\Kniting\SubConKnitingTargetController@index')->name('index');
Route::get('/subconknitingtarget/getbuyerinfo', 'Report\Subcontract\Kniting\SubConKnitingTargetController@getBuyerInfo');
Route::get('/subconknitingtarget/getdata', 'Report\Subcontract\Kniting\SubConKnitingTargetController@reportData');





Route::get('/localexplcprogressreport', 'Report\Commercial\LocalExpLcProgressReportController@index')->name('index');
Route::get('/localexplcprogressreport/getdata', 'Report\Commercial\LocalExpLcProgressReportController@getData');
Route::get('/localexplcprogressreport/getlocalinvoice', 'Report\Commercial\LocalExpLcProgressReportController@getLocalInvoice');
Route::get('/localexplcprogressreport/getlocaltransection', 'Report\Commercial\LocalExpLcProgressReportController@getLocalTransec');
Route::get('/localexplcprogressreport/getlocallc', 'Report\Commercial\LocalExpLcProgressReportController@getLocalLc');

Route::get('/centralsewingplan', 'Report\CentralSewingPlanController@index')->name('index');
Route::get('/centralsewingplan/getdata', 'Report\CentralSewingPlanController@reportData');
Route::get('/centralcuttingplan', 'Report\CentralCuttingPlanController@index')->name('index');
Route::get('/centralcuttingplan/getdata', 'Report\CentralCuttingPlanController@reportData');

Route::get('/centraldyeingplan', 'Report\CentralDyeingPlanController@index')->name('index');
Route::get('/centraldyeingplan/getdata', 'Report\CentralDyeingPlanController@reportData');

Route::get('/tnareport', 'Report\TnaReportController@index')->name('index');
Route::get('/tnareport/getdata', 'Report\TnaReportController@reportData');

Route::get('/capacityachivmentgraph', 'Report\ProdGmtCapacityAchievementGraphController@index')->name('index');

Route::get('/capacityachivmentgraph/getgraphqty', 'Report\ProdGmtCapacityAchievementGraphController@getGraphQty');
Route::get('/capacityachivmentgraph/getgraphamount', 'Report\ProdGmtCapacityAchievementGraphController@getGraphAmount');

Route::get('/capacityachivmentgraph/getgraphqtycut', 'Report\ProdGmtCapacityAchievementGraphController@getGraphQtyCut');
Route::get('/capacityachivmentgraph/getgraphamountcut', 'Report\ProdGmtCapacityAchievementGraphController@getGraphAmountCut');
Route::get('/capacityachivmentgraph/getgraphqtysp', 'Report\ProdGmtCapacityAchievementGraphController@getGraphQtySp');

Route::get('/capacityachivmentgraph/getgraphamountsp', 'Report\ProdGmtCapacityAchievementGraphController@getGraphAmountSp');

Route::get('/capacityachivmentgraph/getgraphqtyemb', 'Report\ProdGmtCapacityAchievementGraphController@getGraphQtyEmb');
Route::get('/capacityachivmentgraph/getgraphamountemb', 'Report\ProdGmtCapacityAchievementGraphController@getGraphAmountEmb');
Route::get('/capacityachivmentgraph/getgraphsewmintprod', 'Report\ProdGmtCapacityAchievementGraphController@getGraphMint');

Route::get('/orderforcast', 'Report\BuyerDevelopmentReportController@index')->name('index');

Route::get('/capacityshipdategraph', 'Report\ProdGmtCapacityShipdateGraphController@index')->name('index');
Route::get('/capacityshipdategraph/getgraphqty', 'Report\ProdGmtCapacityShipdateGraphController@getGraphQty');
Route::get('/capacityshipdategraph/getgraphsewmintprod', 'Report\ProdGmtCapacityShipdateGraphController@getGraphMint');



Route::get('/capacityachivmentgraphday', 'Report\ProdGmtCapacityAchievementGraphDayController@index')->name('index');
Route::get('/capacityachivmentgraphday/getgraph', 'Report\ProdGmtCapacityAchievementGraphDayController@getGraph');
Route::get('/capacityachivmentgraphday/getgraphcut', 'Report\ProdGmtCapacityAchievementGraphDayController@getGraphCut');
Route::get('/capacityachivmentgraphday/getgraphsp', 'Report\ProdGmtCapacityAchievementGraphDayController@getGraphSp');
Route::get('/capacityachivmentgraphday/getgraphemb', 'Report\ProdGmtCapacityAchievementGraphDayController@getGraphEmb');
Route::get('/capacityachivmentgraphday/getgraphfin', 'Report\ProdGmtCapacityAchievementGraphDayController@getGraphFin');
Route::get('/capacityachivmentgraphday/getgraphiron', 'Report\ProdGmtCapacityAchievementGraphDayController@getGraphIron');
Route::get('/capacityachivmentgraphday/getgraphpoly', 'Report\ProdGmtCapacityAchievementGraphDayController@getGraphPoly');

Route::get('/capacityachivmentgraphday/getgraphsewmintprod', 'Report\ProdGmtCapacityAchievementGraphDayController@getGraphSewMintProd');



Route::get('/allcapacityachivmentgraph', 'Report\ProdGmtAllAchievementGraphController@index')->name('index');
Route::get('/allcapacityachivmentgraph/getgraphqty', 'Report\ProdGmtAllAchievementGraphController@getGraphQty');

Route::get('/txtcapacityachivmentgraph', 'Report\ProdTxtCapacityAchievementGraphController@index')->name('index');
Route::get('/txtcapacityachivmentgraph/getgraphqty', 'Report\ProdTxtCapacityAchievementGraphController@getGraphQty');
Route::get('/txtcapacityachivmentgraph/getgraphamount', 'Report\ProdTxtCapacityAchievementGraphController@getGraphAmount');

Route::get('/txtcapacityachivmentgraphday', 'Report\ProdTxtCapacityAchievementGraphDayController@index')->name('index');
Route::get('/txtcapacityachivmentgraphday/getgraph', 'Report\ProdTxtCapacityAchievementGraphDayController@getGraph');

Route::get('/todaysewingachivementgraph', 'Report\TodaySewingAchievementGraphController@index')->name('index');
Route::get('/todaysewingachivementgraph/getline', 'Report\TodaySewingAchievementGraphController@getLine');
Route::get('/todaysewingachivementgraph/getgraph', 'Report\TodaySewingAchievementGraphController@getGraph');
Route::get('/todaysewingachivementgraph/getgraphtwo', 'Report\TodaySewingAchievementGraphController@getGraphTwo');

Route::get('/todaydyeingachivementgraph', 'Report\TodayDyeingAchievementGraphController@index')->name('index');
Route::get('/todaydyeingachivementgraph/getgraph', 'Report\TodayDyeingAchievementGraphController@getGraph');

Route::get('/todayaopachivementgraph', 'Report\TodayAopAchievementGraphController@index')->name('index');
Route::get('/todayaopachivementgraph/getgraph', 'Report\TodayAopAchievementGraphController@getGraph');

Route::get('/todayknitingachivementgraph', 'Report\TodayKnitingAchievementGraphController@index')->name('index');
Route::get('/todayknitingachivementgraph/getgraph', 'Report\TodayKnitingAchievementGraphController@getGraph');

Route::get('/yarnissuereceive', 'Report\Inventory\YarnIssueReceiveController@index')->name('index');
Route::get('/yarnissuereceive/getyarnimplc', 'Report\Inventory\YarnIssueReceiveController@getYarnImportLc');
Route::get('/yarnissuereceive/getdata', 'Report\Inventory\YarnIssueReceiveController@reportData');
Route::get('/yarnissuereceive/getissuedata', 'Report\Inventory\YarnIssueReceiveController@issueData');
Route::get('/yarnissuereceive/getissregular', 'Report\Inventory\YarnIssueReceiveController@IsuRegular');
Route::get('/yarnissuereceive/getisstransfer', 'Report\Inventory\YarnIssueReceiveController@issueTransOut');
Route::get('/yarnissuereceive/getisspurrtn', 'Report\Inventory\YarnIssueReceiveController@IssuePurRtn');
Route::get('/yarnissuereceive/getdtlmrrpo', 'Report\Inventory\YarnIssueReceiveController@IsuMrrPo');

Route::get('/yarnprocurementreport', 'Report\Inventory\YarnProcurementReportController@index')->name('index');
Route::get('/yarnprocurementreport/getdata', 'Report\Inventory\YarnProcurementReportController@reportData');
Route::get('/yarnprocurementreport/getstyle', 'Report\Inventory\YarnProcurementReportController@getStyle');
Route::get('/yarnprocurementreport/getorder', 'Report\Inventory\YarnProcurementReportController@getOrder');
Route::get('/yarnprocurementreport/getdlmerchant', 'Report\Inventory\YarnProcurementReportController@getDealMerchant');
Route::get('/yarnprocurementreport/getpoqtydtl', 'Report\Inventory\YarnProcurementReportController@getPoQty');
Route::get('/yarnprocurementreport/yarnprocurementsummery', 'Report\Inventory\YarnProcurementReportController@yarnSummery');

Route::get('/subcondyeingorderprogress', 'Report\Subcontract\Dyeing\SubconDyeingOrderProgressController@index')->name('index');
Route::get('/subcondyeingorderprogress/getdata', 'Report\Subcontract\Dyeing\SubconDyeingOrderProgressController@html');

Route::get('/subcondyeingfabricreport', 'Report\Subcontract\Dyeing\SubConDyeingFabricReportController@index')->name('index');
Route::get('/subcondyeingfabricreport/getdata', 'Report\Subcontract\Dyeing\SubConDyeingFabricReportController@html');

Route::get('/subconaoporderprogress', 'Report\Subcontract\AOP\SubconAopOrderProgressController@index')->name('index');
Route::get('/subconaoporderprogress/getdata', 'Report\Subcontract\AOP\SubconAopOrderProgressController@html');

Route::get('/orderpending', 'Report\OrderPendingController@index')->name('index');
Route::get('/orderpending/getdata', 'Report\OrderPendingController@reportData');
Route::get('/orderpending/getorderreceived', 'Report\OrderPendingController@getOrderReceived');
Route::get('/orderpending/getdlmerchant', 'Report\OrderPendingController@getDealMerchant');
Route::get('/orderpending/getbuyhouse', 'Report\OrderPendingController@getBuyingHouse');
Route::get('/orderpending/getopfile', 'Report\OrderPendingController@getOpFileSrc');
Route::get('/orderpending/getstyle', 'Report\OrderPendingController@getStyle');
Route::get('/orderpending/ordteammemberdlm', 'Report\OrderPendingController@getTeamMemberDlm');

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
