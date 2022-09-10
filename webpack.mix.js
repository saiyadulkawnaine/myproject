let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

/* ===================================  Accounting  ================================= */
mix.js('resources/assets/js/MsDashBordController.js', 'public/js/Dashbord');

//mix.js('resources/assets/js/Account/MsAccChartMasterController.js', 'public/js/Account');
//mix.js('resources/assets/js/Account/MsAccTransEmployeeController.js', 'public/js/Account');
//mix.js('resources/assets/js/Account/MsAccTransLoanRefController.js', 'public/js/Account');
//mix.js('resources/assets/js/Account/MsAccTransOtherPartyController.js', 'public/js/Account');
//mix.js('resources/assets/js/Account/MsAccTransOtherRefController.js', 'public/js/Account');
//mix.js('resources/assets/js/Account/MsAccTransPurchaseController.js', 'public/js/Account');
//mix.js('resources/assets/js/Account/MsAccTransSalesController.js', 'public/js/Account');
//mix.js('resources/assets/js/Account/MsAccChartSubGroupController.js', 'public/js/Account');
//mix.js('resources/assets/js/Account/MsAllAccountController.js', 'public/js/Account');//AcChart
// mix.js('resources/assets/js/Account/MsAllAccYearController.js', 'public/js/Account');
// mix.js('resources/assets/js/Account/MsAllTransController.js', 'public/js/Account');
// mix.js('resources/assets/js/Account/MsAccChartCtrlHeadMappingController.js', 'public/js/Account');
// mix.js('resources/assets/js/Account/MsAllAccTermLoanController.js', 'public/js/Account');
// mix.js('resources/assets/js/Account/MsAccOtherTradeFinanceController.js', 'public/js/Account');
// mix.js('resources/assets/js/Account/MsAccTermLoanAdjustmentController.js', 'public/js/Account');
/* ==================================Approval====================================== */
// mix.js('resources/assets/js/Approval/MsMktCostApprovalController.js', 'public/js/approval');
// mix.js('resources/assets/js/Approval/MsMktCostConfirmationController.js', 'public/js/approval');
// mix.js('resources/assets/js/Approval/MsMktCostFirstApprovalController.js', 'public/js/approval');
// mix.js('resources/assets/js/Approval/MsInvPurReqApprovalController.js', 'public/js/approval');
// mix.js('resources/assets/js/Approval/MsInvGeneralItemIsuReqApprovalController.js', 'public/js/approval');
// mix.js('resources/assets/js/Approval/MsAllApprovalController.js', 'public/js/approval');
// mix.js('resources/assets/js/Approval/MsApprovalVisitorController.js', 'public/js/approval');
// mix.js('resources/assets/js/Approval/MsSoKnitDlvApprovalController.js', 'public/js/approval');
// mix.js('resources/assets/js/Approval/MsSoDyeingDlvApprovalController.js', 'public/js/approval');
// mix.js('resources/assets/js/Approval/MsSoAopDlvApprovalController.js', 'public/js/approval');
// mix.js('resources/assets/js/Approval/MsProdBatchApprovalController.js', 'public/js/approval');
// mix.js('resources/assets/js/Approval/MsEmployeeMovementApprovalController.js', 'public/js/approval');
//  mix.js('resources/assets/js/Approval/MsBudgetApprovalController.js', 'public/js/approval');
mix.js('resources/assets/js/Approval/MsPoTrimApprovalController.js', 'public/js/approval');
mix.js('resources/assets/js/Approval/MsPoTrimShortApprovalController.js', 'public/js/approval');
// mix.js('resources/assets/js/Approval/MsPoYarnApprovalController.js', 'public/js/approval');
// mix.js('resources/assets/js/Approval/MsEmployeeHRApprovalController.js', 'public/js/approval');
// mix.js('resources/assets/js/Approval/MsEmployeeHRStatusApprovalController.js', 'public/js/approval');
// mix.js('resources/assets/js/Approval/MsEmployeeRecruitReqApprovalController.js', 'public/js/approval');
// mix.js('resources/assets/js/Approval/MsSalesOrderShipDateChangeApprovalController.js', 'public/js/approval');
mix.js('resources/assets/js/Approval/MsSalesOrderCloseApprovalController.js', 'public/js/approval');
// mix.js('resources/assets/js/Approval/MsPoDyeingServiceApprovalController.js', 'public/js/approval');
mix.js('resources/assets/js/Approval/MsPoDyeingServiceShortApprovalController.js', 'public/js/approval');
mix.js('resources/assets/js/Approval/MsPoKnitServiceApprovalController.js', 'public/js/approval');
mix.js('resources/assets/js/Approval/MsPoKnitServiceShortApprovalController.js', 'public/js/approval');
// mix.js('resources/assets/js/Approval/MsPoDyeChemApprovalController.js', 'public/js/approval');
mix.js('resources/assets/js/Approval/MsPoEmbServiceApprovalController.js', 'public/js/approval');
mix.js('resources/assets/js/Approval/MsPoEmbServiceShortApprovalController.js', 'public/js/approval');
// mix.js('resources/assets/js/Approval/MsPoGeneralApprovalController.js', 'public/js/approval');
// mix.js('resources/assets/js/Approval/MsPoGeneralServiceApprovalController.js', 'public/js/approval');
mix.js('resources/assets/js/Approval/MsPoFabricApprovalController.js', 'public/js/approval');
mix.js('resources/assets/js/Approval/MsPoFabricShortApprovalController.js', 'public/js/approval');
mix.js('resources/assets/js/Approval/MsPoAopServiceApprovalController.js', 'public/js/approval');
mix.js('resources/assets/js/Approval/MsPoAopServiceShortApprovalController.js', 'public/js/approval');
mix.js('resources/assets/js/Approval/MsPoYarnDyeingApprovalController.js', 'public/js/approval');
mix.js('resources/assets/js/Approval/MsPoYarnDyeingShortApprovalController.js', 'public/js/approval');
// mix.js('resources/assets/js/Approval/MsRqYarnApprovalController.js', 'public/js/approval');
// mix.js('resources/assets/js/Approval/MsJhuteStockApprovalController.js', 'public/js/approval');
// mix.js('resources/assets/js/Approval/MsImpLcApprovalController.js', 'public/js/approval');
// mix.js('resources/assets/js/Approval/MsSoDyeingMktCostQpriceApprovalController.js', 'public/js/approval');
/* ==================================  BOM  ==================================== */
// mix.js('resources/assets/js/MsAllBudgetController.js', 'public/js/Util');
/* ==================================Commercial============================== */
/*============ Export ==========*/
// mix.js('resources/assets/js/Commercial/Export/MsAllExpLcScController.js', 'public/js/Commercial/Export');
// mix.js('resources/assets/js/Commercial/Export/MsAllLcController.js', 'public/js/Commercial/Export');
// mix.js('resources/assets/js/Commercial/Export/MsAllExpPreCreditController.js', 'public/js/Commercial/Export');
// mix.js('resources/assets/js/Commercial/Export/MsAllExpAdvInvoiceController.js', 'public/js/Commercial/Export');
// mix.js('resources/assets/js/Commercial/Export/MsAllExpInvoiceController.js', 'public/js/Commercial/Export');
// mix.js('resources/assets/js/Commercial/Export/MsAllMsExpDocSubmissionController.js', 'public/js/Commercial/Export');
//mix.js('resources/assets/js/Commercial/Export/MsAllExpDocSubmissionBuyerController.js', 'public/js/Commercial/Export');
// mix.js('resources/assets/js/Commercial/Export/MsAllExpProRlzController.js', 'public/js/Commercial/Export');
/*============ Import ============*/
// mix.js('resources/assets/js/Commercial/Import/MsAllImpLcController.js', 'public/js/Commercial/Import');
// mix.js('resources/assets/js/Commercial/Import/MsAllImpLiabilityAdjustController.js', 'public/js/Commercial/Import');
// mix.js('resources/assets/js/Commercial/Import/MsAllImpShipDocController.js', 'public/js/Commercial/Import');
// mix.js('resources/assets/js/Commercial/Import/MsAllImpDocMaturityController.js', 'public/js/Commercial/Import');
/* ============== Cash Incentive  ================== */
// mix.js('resources/assets/js/Commercial/CashIncentive/MsAllCashIncentiveController.js', 'public/js/Commercial/CashIncentive');
// mix.js('resources/assets/js/Commercial/CashIncentive/MsAllCashIncentiveAdvanceController.js', 'public/js/Commercial/CashIncentive');
mix.js('resources/assets/js/Commercial/CashIncentive/MsAllCashIncentiveRealizeController.js', 'public/js/Commercial/CashIncentive');
/* ==================  Local Export  ================= */
mix.js('resources/assets/js/Commercial/LocalExport/MsAllLocalExpController.js', 'public/js/Commercial/LocalExport');
//  mix.js('resources/assets/js/Commercial/LocalExport/MsAllLocalLcController.js', 'public/js/Commercial/LocalExport');
//  mix.js('resources/assets/js/Commercial/LocalExport/MsAllLocalExpInvoiceController.js', 'public/js/Commercial/LocalExport');
//  mix.js('resources/assets/js/Commercial/LocalExport/MsAllLocalExpDocSubAcceptController.js', 'public/js/Commercial/LocalExport');
// mix.js('resources/assets/js/Commercial/LocalExport/MsAllLocalExpDocSubBankController.js', 'public/js/Commercial/LocalExport');
// mix.js('resources/assets/js/Commercial/LocalExport/MsAllLocalExpProRlzController.js', 'public/js/Commercial/LocalExport');
/*============================== FAMS  ================================ */
// mix.js('resources/assets/js/FAMS/MsAllAssetAcquisitionController.js', 'public/js/FAMS');
// mix.js('resources/assets/js/FAMS/MsAllAssetBreakdownController.js', 'public/js/FAMS');
// mix.js('resources/assets/js/FAMS/MsAssetDisposalController.js', 'public/js/FAMS');
// mix.js('resources/assets/js/FAMS/MsAllAssetServiceRepairController.js', 'public/js/FAMS');
// mix.js('resources/assets/js/FAMS/MsAllAssetServiceController.js', 'public/js/FAMS');
// mix.js('resources/assets/js/FAMS/MsAllAssetReturnController.js', 'public/js/FAMS');
/*========================   HRM   ============================*/
//mix.js('resources/assets/js/HRM/MsEmployeeController.js', 'public/js/HRM');
// mix.js('resources/assets/js/HRM/MsAllEmployeeHRController.js', 'public/js/HRM');
// mix.js('resources/assets/js/HRM/MsEmployeeHRStatusController.js', 'public/js/HRM');
// mix.js('resources/assets/js/HRM/MsAllEmployeeTransferController.js', 'public/js/HRM');
// mix.js('resources/assets/js/HRM/MsAllEmployeePromotionController.js', 'public/js/HRM');
// mix.js('resources/assets/js/HRM/MsAllEmployeeMovementController.js', 'public/js/HRM');
//mix.js('resources/assets/js/HRM/MsEmployeeAttendenceController.js', 'public/js/HRM');
//mix.js('resources/assets/js/HRM/MsRenewalEntryController.js', 'public/js/HRM');
// mix.js('resources/assets/js/HRM/MsAllEmployeeToDoListController.js', 'public/js/HRM');
// mix.js('resources/assets/js/HRM/MsRegisterVisitorController.js', 'public/js/HRM');
// mix.js('resources/assets/js/HRM/MsAllAgreementController.js', 'public/js/HRM');
// mix.js('resources/assets/js/HRM/MsEmployeeIncrementController.js', 'public/js/HRM');
// mix.js('resources/assets/js/HRM/MsAllEmployeeBudgetController.js', 'public/js/HRM');
// mix.js('resources/assets/js/HRM/MsAllEmployeeRecruitReqController.js', 'public/js/HRM');
/*==========================   Inventory  =================================*/
//=======================General Store==
// mix.js('resources/assets/js/Inventory/GeneralStore/MsAllInventoryController.js', 'public/js/Inventory/GeneralStore');
// mix.js('resources/assets/js/Inventory/GeneralStore/MsAllCashController.js', 'public/js/Inventory/GeneralStore');
// mix.js('resources/assets/js/Inventory/GeneralStore/MsAllInvGeneralRcvController.js', 'public/js/Inventory/GeneralStore');
// mix.js('resources/assets/js/Inventory/GeneralStore/MsAllInvGeneralIsuRqController.js', 'public/js/Inventory/GeneralStore');
// mix.js('resources/assets/js/Inventory/GeneralStore/MsAllInvGeneralIsuController.js', 'public/js/Inventory/GeneralStore');
// mix.js('resources/assets/js/Inventory/GeneralStore/MsAllInvGeneralTransOutController.js', 'public/js/Inventory/GeneralStore');
// mix.js('resources/assets/js/Inventory/GeneralStore/MsAllInvGeneralTransInController.js', 'public/js/Inventory/GeneralStore');
// mix.js('resources/assets/js/Inventory/GeneralStore/MsAllInvGeneralIsuRtnController.js', 'public/js/Inventory/GeneralStore');
// mix.js('resources/assets/js/Inventory/GeneralStore/MsAllInvGeneralRcvRtnController.js', 'public/js/Inventory/GeneralStore');

//============================YARN==
// mix.js('resources/assets/js/Inventory/Yarn/MsAllInvYarnRcvController.js', 'public/js/Inventory/Yarn');
// mix.js('resources/assets/js/Inventory/Yarn/MsAllInvYarnIsuController.js', 'public/js/Inventory/Yarn');
// mix.js('resources/assets/js/Inventory/Yarn/MsAllInvYarnIsuRtnController.js', 'public/js/Inventory/Yarn');
// mix.js('resources/assets/js/Inventory/Yarn/MsAllInvYarnPoRtnController.js', 'public/js/Inventory/Yarn');
// mix.js('resources/assets/js/Inventory/Yarn/MsAllInvYarnTransOutController.js', 'public/js/Inventory/Yarn');
// mix.js('resources/assets/js/Inventory/Yarn/MsAllInvYarnTransInController.js', 'public/js/Inventory/Yarn');

// mix.js('resources/assets/js/Inventory/Yarn/MsAllInvYarnIsuSamSecController.js', 'public/js/Inventory/Yarn');
// mix.js('resources/assets/js/Inventory/Yarn/MsAllInvYarnIsuRtnSamSecController.js', 'public/js/Inventory/Yarn');

/*============================Dyes & Chem Rcv======================*/
// mix.js('resources/assets/js/Inventory/DyeChem/MsAllInvDyeChemRcvController.js', 'public/js/Inventory/DyeChem');
// mix.js('resources/assets/js/Inventory/DyeChem/MsAllInvDyeChemIsuRqController.js', 'public/js/Inventory/DyeChem');
// mix.js('resources/assets/js/Inventory/DyeChem/MsAllInvDyeChemIsuRqAddController.js', 'public/js/Inventory/DyeChem');
// mix.js('resources/assets/js/Inventory/DyeChem/MsAllInvDyeChemIsuRqAopController.js', 'public/js/Inventory/DyeChem');
// mix.js('resources/assets/js/Inventory/DyeChem/MsAllInvDyeChemIsuRqSrpController.js', 'public/js/Inventory/DyeChem');
// mix.js('resources/assets/js/Inventory/DyeChem/MsAllInvDyeChemIsuRqLoanController.js', 'public/js/Inventory/DyeChem');
// mix.js('resources/assets/js/Inventory/DyeChem/MsAllInvDyeChemIsuController.js', 'public/js/Inventory/DyeChem');
// mix.js('resources/assets/js/Inventory/DyeChem/MsAllInvDyeChemTransOutController.js', 'public/js/Inventory/DyeChem');
// mix.js('resources/assets/js/Inventory/DyeChem/MsAllInvDyeChemTransInController.js', 'public/js/Inventory/DyeChem');
// mix.js('resources/assets/js/Inventory/DyeChem/MsAllInvDyeChemIsuRtnController.js', 'public/js/Inventory/DyeChem');
// mix.js('resources/assets/js/Inventory/DyeChem/MsAllInvDyeChemRcvRtnController.js', 'public/js/Inventory/DyeChem');

/*============================ Trim ======================*/
// mix.js('resources/assets/js/Inventory/Trim/MsAllInvTrimRcvController.js', 'public/js/Inventory/Trim');

/*============================Grey Fabric======================*/
// mix.js('resources/assets/js/Inventory/GreyFabric/MsAllInvGreyFabRcvController.js', 'public/js/Inventory/GreyFabric');
// mix.js('resources/assets/js/Inventory/GreyFabric/MsAllInvGreyFabIsuController.js', 'public/js/Inventory/GreyFabric');
// mix.js('resources/assets/js/Inventory/GreyFabric/MsAllInvGreyFabTransOutController.js', 'public/js/Inventory/GreyFabric');
// mix.js('resources/assets/js/Inventory/GreyFabric/MsAllInvGreyFabTransInController.js', 'public/js/Inventory/GreyFabric');
// mix.js('resources/assets/js/Inventory/GreyFabric/MsAllInvGreyFabIsuRtnController.js', 'public/js/Inventory/GreyFabric');
/*============================Finish Fabric======================*/
// mix.js('resources/assets/js/Inventory/FinishFabric/MsAllInvFinishFabRcvController.js', 'public/js/Inventory/FinishFabric');
// mix.js('resources/assets/js/Inventory/FinishFabric/MsAllInvFinishFabRcvPurController.js', 'public/js/Inventory/FinishFabric');
// mix.js('resources/assets/js/Inventory/FinishFabric/MsAllInvFinishFabIsuController.js', 'public/js/Inventory/FinishFabric');
// mix.js('resources/assets/js/Inventory/FinishFabric/MsAllInvFinishFabTransOutController.js', 'public/js/Inventory/FinishFabric');
// mix.js('resources/assets/js/Inventory/FinishFabric/MsAllInvFinishFabTransInController.js', 'public/js/Inventory/FinishFabric');
// mix.js('resources/assets/js/Inventory/FinishFabric/MsAllInvFinishFabIsuRtnController.js', 'public/js/Inventory/FinishFabric');
/*===========================  PurChase ================================*/
//mix.js('resources/assets/js/purchase/MsPurChemController.js', 'public/js/Util');
//mix.js('resources/assets/js/purchase/MsPurChemQtyController.js', 'public/js/Util');
//mix.js('resources/assets/js/purchase/MsPurFabricController.js', 'public/js/Util');
//mix.js('resources/assets/js/purchase/MsPurFabricQtyController.js', 'public/js/Util');
//mix.js('resources/assets/js/purchase/MsPurOrderChemController.js', 'public/js/Util');
//mix.js('resources/assets/js/purchase/MsPurOrderFabricController.js', 'public/js/Util');
//mix.js('resources/assets/js/purchase/MsPurOrderYarnController.js', 'public/js/Util');
//mix.js('resources/assets/js/purchase/MsPurOrderTrimController.js', 'public/js/Util');
//mix.js('resources/assets/js/purchase/MsPurTrimController.js', 'public/js/Util');
//mix.js('resources/assets/js/purchase/MsPurTrimQtyController.js', 'public/js/Util');
//mix.js('resources/assets/js/purchase/MsPurYarnController.js', 'public/js/Util');
//mix.js('resources/assets/js/purchase/MsPurYarnQtyController.js', 'public/js/Util');

// mix.js('resources/assets/js/purchase/MsPoFabricController.js', 'public/js/Util');
// mix.js('resources/assets/js/purchase/MsPoFabricItemController.js', 'public/js/Util');
// mix.js('resources/assets/js/purchase/MsPoFabricItemQtyController.js', 'public/js/Util');

mix.js('resources/assets/js/purchase/MsPoKnitServiceController.js', 'public/js/Util');
//mix.js('resources/assets/js/purchase/MsPoKnitServiceItemController.js', 'public/js/Util');
//mix.js('resources/assets/js/purchase/MsPoKnitServiceItemQtyController.js', 'public/js/Util');
mix.js('resources/assets/js/purchase/MsPoDyeingServiceController.js', 'public/js/Util');
//mix.js('resources/assets/js/purchase/MsPoDyeingServiceItemController.js', 'public/js/Util');
//mix.js('resources/assets/js/purchase/MsPoDyeingServiceItemQtyController.js', 'public/js/Util');

// mix.js('resources/assets/js/purchase/MsPoAopServiceController.js', 'public/js/Util');
// mix.js('resources/assets/js/purchase/MsPoAopServiceItemController.js', 'public/js/Util');
// mix.js('resources/assets/js/purchase/MsPoAopServiceItemQtyController.js', 'public/js/Util');

mix.js('resources/assets/js/purchase/MsPoTrimController.js', 'public/js/Util');
// mix.js('resources/assets/js/purchase/MsPoTrimItemController.js', 'public/js/Util');
// mix.js('resources/assets/js/purchase/MsPoTrimItemQtyController.js', 'public/js/Util');

// mix.js('resources/assets/js/purchase/MsPoYarnController.js', 'public/js/Util');
//mix.js('resources/assets/js/purchase/MsPoYarnItemController.js', 'public/js/Util');
//mix.js('resources/assets/js/purchase/MsPoYarnItemBomQtyController.js', 'public/js/Util');
// mix.js('resources/assets/js/purchase/MsAllPoYarnDyeingController.js', 'public/js/Util');
mix.js('resources/assets/js/purchase/MsAllPoYarnDyeingShortController.js', 'public/js/Util');
mix.js('resources/assets/js/purchase/MsPoDyeChemController.js', 'public/js/Util');
//mix.js('resources/assets/js/purchase/MsPoDyeChemItemController.js', 'public/js/Util');

mix.js('resources/assets/js/purchase/MsPoGeneralController.js', 'public/js/Util');
//mix.js('resources/assets/js/purchase/MsPoGeneralItemController.js', 'public/js/Util');

// mix.js('resources/assets/js/purchase/MsPoGeneralServiceController.js', 'public/js/Util');
// mix.js('resources/assets/js/purchase/MsPoGeneralServiceItemController.js', 'public/js/Util');

// mix.js('resources/assets/js/purchase/MsPoEmbServiceController.js', 'public/js/Util');
// mix.js('resources/assets/js/purchase/MsPoEmbServiceItemController.js', 'public/js/Util');
// mix.js('resources/assets/js/purchase/MsPoEmbServiceItemQtyController.js', 'public/js/Util');

//=============SHOWROOM==================//
// mix.js('resources/assets/js/ShowRoom/MsAllSrmProductReceiveController.js', 'public/js/ShowRoom');
// mix.js('resources/assets/js/ShowRoom/MsAllSrmProductSaleController.js', 'public/js/ShowRoom');

//=============GateEntry==================//
// mix.js('resources/assets/js/GateEntry/MsGateEntryController.js', 'public/js/GateEntry');
// mix.js('resources/assets/js/GateEntry/MsGateEntryItemController.js', 'public/js/GateEntry');
// mix.js('resources/assets/js/GateEntry/MsGateOutController.js', 'public/js/GateEntry');

/*============================ ALL  REPORT ================================== */
//mix.js('resources/assets/js/report/Account/MsCoaController.js', 'public/js/report/Account');
//mix.js('resources/assets/js/report/Account/MsGlEmpController.js', 'public/js/report/Account');
//mix.js('resources/assets/js/report/Account/MsGlBuyController.js', 'public/js/report/Account');
//mix.js('resources/assets/js/report/Account/MsGlSupController.js', 'public/js/report/Account');
//mix.js('resources/assets/js/report/Account/MsGlOtpController.js', 'public/js/report/Account');
//mix.js('resources/assets/js/report/Account/MsGlLoanRefController.js', 'public/js/report/Account');
// mix.js('resources/assets/js/report/Account/MsGlOtherRefController.js', 'public/js/report/Account');
//mix.js('resources/assets/js/report/Account/MsTbController.js', 'public/js/report/Account');
//mix.js('resources/assets/js/report/Account/MsGlController.js', 'public/js/report/Account');

// mix.js('resources/assets/js/report/Account/MsGlImpLcRefController.js', 'public/js/report/Account');
//mix.js('resources/assets/js/report/Account/MsGlOpnController.js', 'public/js/report/Account');
// mix.js('resources/assets/js/report/Account/MsIncomeStatementController.js', 'public/js/report/Account');
// mix.js('resources/assets/js/report/Account/MsExpenseStatementController.js', 'public/js/report/Account');
//mix.js('resources/assets/js/report/Account/MsBalanceSheetController.js', 'public/js/report/Account');
//mix.js('resources/assets/js/Account/MsAllAccBepEntryController.js', 'public/js/Account');
mix.js('resources/assets/js/Account/MsAccCostDistributionController.js', 'public/js/Account');
mix.js('resources/assets/js/Account/MsAccCostDistributionDtlController.js', 'public/js/Account');
// mix.js('resources/assets/js/report/Account/MsReceivableController.js', 'public/js/report/Account');
// mix.js('resources/assets/js/report/Account/MsPayableController.js', 'public/js/report/Account');
// mix.js('resources/assets/js/report/Account/MsMrrCheckController.js', 'public/js/report/Account');
//    mix.js('resources/assets/js/report/Account/MsBankLoanReportController.js', 'public/js/report/Account');
// mix.js('resources/assets/js/report/Account/MsOrderWiseMaterialCostController.js', 'public/js/report/Account');
/* ========HRM */
mix.js('resources/assets/js/Report/HRM/MsEmployeeListController.js', 'public/js/report/HRM');
// mix.js('resources/assets/js/Report/HRM/MsEmployeeInformationController.js', 'public/js/report/HRM');
//mix.js('resources/assets/js/Report/HRM/MsEmployeeJoiningSummeryController.js', 'public/js/report/HRM');
//mix.js('resources/assets/js/Report/HRM/MsEmployeeInactiveSummeryController.js', 'public/js/report/HRM');
// mix.js('resources/assets/js/Report/HRM/MsEmployeeToDoListReportController.js', 'public/js/report/HRM');
// mix.js('resources/assets/js/Report/HRM/MsRegisterVisitorReportController.js', 'public/js/report/HRM');
//mix.js('resources/assets/js/Report/HRM/MsEmployeeMovementReportController.js', 'public/js/report/HRM');
//mix.js('resources/assets/js/Report/HRM/MsDailyAttendenceReportController.js', 'public/js/report/HRM');
/* ========ItemBank */
// mix.js('resources/assets/js/Report/ItemBank/MsItemBankController.js', 'public/js/report/ItemBank');
// mix.js('resources/assets/js/Report/ItemBank/MsPurchaseOrderReportController.js', 'public/js/report/itembank');
// mix.js('resources/assets/js/Report/ItemBank/MsPurchaseRequisitionReportController.js', 'public/js/report/itembank');
// mix.js('resources/assets/js/Report/ItemBank/MsTrimsOrderProgressReportController.js', 'public/js/report/itembank');
/* ========Renewal */
//mix.js('resources/assets/js/Report/Renewal/MsRenewalReportController.js', 'public/js/report/Renewal');
/* =====FAM */
// mix.js('resources/assets/js/Report/FAM/MsFamListController.js', 'public/js/Report/FAM');
// mix.js('resources/assets/js/Report/FAM/MsAssetBreakdownReportController.js', 'public/js/Report/FAM');
mix.js('resources/assets/js/Report/FAM/MsAssetRepairBackReportController.js', 'public/js/Report/FAM');
//===========Commercial
// mix.js('resources/assets/js/report/Commercial/MsLiabilityCoverageReportController.js', 'public/js/report/Commercial');
// mix.js('resources/assets/js/report/Commercial/MsCashIncentiveReportController.js', 'public/js/report/Commercial');
// mix.js('resources/assets/js/report/Commercial/MsMonthlyExpInvoiceReportController.js', 'public/js/report/Commercial');
// mix.js('resources/assets/js/report/Commercial/MsAdvExpInvoiceReportController.js', 'public/js/report/Commercial');
// mix.js('resources/assets/js/report/Commercial/MsNegotiationReportController.js', 'public/js/report/Commercial');
// mix.js('resources/assets/js/report/Commercial/MsImportConsignmentReportController.js', 'public/js/report/Commercial');
// mix.js('resources/assets/js/report/Commercial/MsLocalExpPiReportController.js', 'public/js/report/Commercial');
// mix.js('resources/assets/js/report/Commercial/MsLocalExpLcProgressReportController.js', 'public/js/report/Commercial');
// mix.js('resources/assets/js/report/Commercial/MsPendingImpLcPoReportController.js', 'public/js/report/Commercial');
//=====Subcontract
//mix.js('resources/assets/js/Report/Subcontract/Inbound/MsSubInbMarketingReportController.js', 'public/js/report/Subcontract/Inbound');
// mix.js('resources/assets/js/Report/Subcontract/Dyeing/MsPlDyeingReportController.js', 'public/js/report/Subcontract/Dyeing');
// mix.js('resources/assets/js/Report/Subcontract/Dyeing/MsPlDyeingExiReportController.js', 'public/js/report/Subcontract/Dyeing');
// mix.js('resources/assets/js/Report/Subcontract/Dyeing/MsFabricStockSubconDyeingPartyController.js', 'public/js/report/Subcontract/Dyeing');
// mix.js('resources/assets/js/Report/Subcontract/Dyeing/MsSubconDyeingTargetController.js', 'public/js/report/Subcontract/Dyeing');
// mix.js('resources/assets/js/Report/Subcontract/Dyeing/MsSubconDyeingBomController.js', 'public/js/report/Subcontract/Dyeing');
// mix.js('resources/assets/js/Report/Subcontract/Dyeing/MsSubConDyeingDeliveryController.js', 'public/js/report/Subcontract/Dyeing');
//mix.js('resources/assets/js/Report/Subcontract/Dyeing/MsSubconDyeingOrderProgressController.js', 'public/js/report/Subcontract/Dyeing');
// mix.js('resources/assets/js/Report/Subcontract/Dyeing/MsSubconDyeingFabricReportController.js', 'public/js/report/Subcontract/Dyeing');
//  mix.js('resources/assets/js/report/Subcontract/Dyeing/MsFinishFabricDeliveryController.js', 'public/js/report/Subcontract/Dyeing');

// mix.js('resources/assets/js/report/Subcontract/AOP/MsFinishFabricDeliveryAopController.js', 'public/js/report/Subcontract/AOP');
// mix.js('resources/assets/js/Report/Subcontract/AOP/MsFabricStockSubconAopPartyController.js', 'public/js/report/Subcontract/AOP');
// mix.js('resources/assets/js/Report/Subcontract/AOP/MsSubconAopTargetController.js', 'public/js/report/Subcontract/AOP');

//mix.js('resources/assets/js/Report/Subcontract/Kniting/MsPlKnitReportController.js', 'public/js/report/Subcontract/Kniting');
// mix.js('resources/assets/js/Report/Subcontract/Kniting/MsPlKnitExiReportController.js', 'public/js/report/Subcontract/Kniting');
// mix.js('resources/assets/js/Report/Subcontract/Kniting/MsYarnStockSubconKnitingPartyController.js', 'public/js/report/Subcontract/Kniting');
// mix.js('resources/assets/js/Report/Subcontract/Kniting/MsSubconKnitingTargetController.js', 'public/js/report/Subcontract/Kniting');
// mix.js('resources/assets/js/Report/Subcontract/Kniting/MsFinishFabricDeliveryKnitingController.js', 'public/js/report/Subcontract/Kniting');

//===========ShowRoom(Garment Stock Report)
// mix.js('resources/assets/js/Report/POS/MsGarmentStockReportController.js', 'public/js/report/POS');
// mix.js('resources/assets/js/Report/POS/MsGateEntryReportController.js', 'public/js/report/POS');
//===========Inventory(Inventory Report)
// mix.js('resources/assets/js/Report/Inventory/MsYarnStockController.js', 'public/js/report/Inventory');
// mix.js('resources/assets/js/Report/Inventory/MsGeneralStockController.js', 'public/js/report/Inventory');
// mix.js('resources/assets/js/Report/Inventory/MsGeneralStockAtReorderLevelController.js', 'public/js/report/Inventory');

// mix.js('resources/assets/js/Report/Inventory/MsDyeChemStockController.js', 'public/js/report/Inventory');
// mix.js('resources/assets/js/Report/Inventory/MsDyeIssueReceiveController.js', 'public/js/report/Inventory');
// mix.js('resources/assets/js/Report/Inventory/MsDyeIssueReceiveSummeryController.js', 'public/js/report/Inventory');
// mix.js('resources/assets/js/Report/Inventory/MsDyeChemStockAtReorderLevelController.js', 'public/js/report/Inventory');
// mix.js('resources/assets/js/Report/Inventory/MsGreyFabStockController.js', 'public/js/report/Inventory');
// mix.js('resources/assets/js/Report/Inventory/MsYarnPurchaseRateTrendController.js', 'public/js/report/Inventory');
// mix.js('resources/assets/js/Report/Inventory/MsYarnPurchaseSummeryController.js', 'public/js/report/Inventory');
// mix.js('resources/assets/js/Report/Inventory/MsYarnPurchaseLcWiseController.js', 'public/js/report/Inventory');
// mix.js('resources/assets/js/Report/Inventory/MsDyeChemPurchaseLcWiseController.js', 'public/js/report/Inventory');
// mix.js('resources/assets/js/Report/Inventory/MsYarnReceiveSummeryController.js', 'public/js/report/Inventory');
// mix.js('resources/assets/js/Report/Inventory/MsYarnStockKnitingPartyController.js', 'public/js/report/Inventory');
// mix.js('resources/assets/js/Report/Inventory/MsYarnStockYarnDyeingPartyController.js', 'public/js/report/Inventory');
// mix.js('resources/assets/js/Report/Inventory/MsReceiveDeliveryController.js', 'public/js/report/Inventory');
// mix.js('resources/assets/js/Report/Inventory/MsYarnIssueReceiveController.js', 'public/js/report/Inventory');
// mix.js('resources/assets/js/Report/Inventory/MsDyeChemLoanLedgerController.js', 'public/js/report/Inventory');
// mix.js('resources/assets/js/Report/Inventory/MsYarnProcurementReportController.js', 'public/js/report/Inventory');
//========Marketing
//mix.js('resources/assets/js/MsOrderWiseBudgetController.js', 'public/js/report');
// mix.js('resources/assets/js/MsBudgetAndCostingComparisonController.js', 'public/js/report');
// mix.js('resources/assets/js/MsOrderInHandController.js', 'public/js/report');
// mix.js('resources/assets/js/MsBudgetSummaryController.js', 'public/js/report');
// mix.js('resources/assets/js/MsQuotationStatementController.js', 'public/js/report');
// mix.js('resources/assets/js/MsCostingNegotiController.js', 'public/js/report');
//mix.js('resources/assets/js/report/Dashbord/MsSampleRequirementController.js', 'public/js/report/Dashbord');
// mix.js('resources/assets/js/MsOrderProgressController.js', 'public/js/report');
// mix.js('resources/assets/js/MsNewOrderEntryReportController.js', 'public/js/report');
// mix.js('resources/assets/js/MsOrderwiseYarnReportController.js', 'public/js/report');

// mix.js('resources/assets/js/MsBuyerDevelopmentReportController.js', 'public/js/report');
//mix.js('resources/assets/js/MsCentralSewingPlanController.js', 'public/js/report');
//mix.js('resources/assets/js/MsCentralCuttingPlanController.js', 'public/js/report');
//mix.js('resources/assets/js/MsCentralDyeingPlanController.js', 'public/js/report');
//mix.js('resources/assets/js/MsTnaReportController.js', 'public/js/report');
mix.js('resources/assets/js/MsOrderPendingController.js', 'public/js/report');
// mix.js('resources/assets/js/MsMktTeamPerformanceController.js', 'public/js/report');
//mix.js('resources/assets/js/MsProjectionProgressController.js', 'public/js/report');
//mix.js('resources/assets/js/MsOfferStatementController.js', 'public/js/report'); 
//  mix.js('resources/assets/js/Marketing/MsTargetTransferController.js', 'public/js/Marketing');
// mix.js('resources/assets/js/Marketing/MsDayTargetTransferController.js', 'public/js/Marketing');

//========Dashboard
//mix.js('resources/assets/js/report/Dashbord/MsTodayShipmentController.js', 'public/js/report/Dashbord');
//mix.js('resources/assets/js/report/Dashbord/MsPendingShipmentController.js', 'public/js/report/Dashbord');
//  mix.js('resources/assets/js/report/Dashbord/MsProdGmtCapacityAchievementController.js', 'public/js/report/Dashbord');
//  mix.js('resources/assets/js/report/Dashbord/MsProdFabricCapacityAchievementController.js', 'public/js/report/Dashbord');
//  mix.js('resources/assets/js/report/Dashbord/MsTodayAccountController.js', 'public/js/report/Dashbord');
//  mix.js('resources/assets/js/report/Dashbord/MsReceiptsPaymentsAccountController.js', 'public/js/report/Dashbord');
//  mix.js('resources/assets/js/report/Dashbord/MsTodayBepController.js', 'public/js/report/Dashbord');
//  mix.js('resources/assets/js/report/Dashbord/MsTodayInventoryReportController.js', 'public/js/report/Dashbord');
//  mix.js('resources/assets/js/report/Dashbord/MsProdGmtCapacityAchievementGraphDayController.js', 'public/js/report/Dashbord');
//  mix.js('resources/assets/js/report/Dashbord/MsProdGmtCapacityAchievementGraphController.js', 'public/js/report/Dashbord');
//  mix.js('resources/assets/js/report/Dashbord/MsProdGmtCapacityShipdateGraphController.js', 'public/js/report/Dashbord');
//  mix.js('resources/assets/js/report/Dashbord/MsProdGmtAllAchievementGraphController.js', 'public/js/report/Dashbord');

//  mix.js('resources/assets/js/report/Dashbord/MsProdTxtCapacityAchievementGraphDayController.js', 'public/js/report/Dashbord');
//  mix.js('resources/assets/js/report/Dashbord/MsProdTxtCapacityAchievementGraphController.js', 'public/js/report/Dashbord');
//  mix.js('resources/assets/js/report/Dashbord/MsTodaySewingAchievementGraphController.js', 'public/js/report/Dashbord');
//  mix.js('resources/assets/js/report/Dashbord/MsTodayDyeingAchievementGraphController.js', 'public/js/report/Dashbord');
//  mix.js('resources/assets/js/report/Dashbord/MsTodayAopAchievementGraphController.js', 'public/js/report/Dashbord');
//  mix.js('resources/assets/js/report/Dashbord/MsTodayKnitingAchievementGraphController.js', 'public/js/report/Dashbord');
//  mix.js('resources/assets/js/report/Dashbord/MsGroupSaleController.js', 'public/js/report/Dashbord');
//  mix.js('resources/assets/js/report/Dashbord/MsGroupReceivableReportController.js', 'public/js/report/Dashbord');
//  mix.js('resources/assets/js/report/Dashbord/MsCentralBudgetController.js', 'public/js/report/Dashbord');
//  mix.js('resources/assets/js/report/Dashbord/MsTargetAchievementReportController.js', 'public/js/report/Dashbord');


//========Fabric Production
// mix.js('resources/assets/js/report/FabricProduction/MsProdKnitDailyReportController.js', 'public/js/report/FabricProduction');
// mix.js('resources/assets/js/report/FabricProduction/MsProdDyeingDailyReportController.js', 'public/js/report/FabricProduction');
// mix.js('resources/assets/js/report/FabricProduction/MsProdDyeingDailyLoadReportController.js', 'public/js/report/FabricProduction');
// mix.js('resources/assets/js/Report/FabricProduction/MsFabricProdProgressController.js', 'public/js/report/FabricProduction');
// mix.js('resources/assets/js/Report/FabricProduction/MsBatchReportController.js', 'public/js/report/FabricProduction');
// mix.js('resources/assets/js/Report/FabricProduction/MsProdKnittingDailyLoadReportController.js', 'public/js/report/FabricProduction');
// mix.js('resources/assets/js/report/FabricProduction/MsProdDyeFinDailyLoadReportController.js', 'public/js/report/FabricProduction');
// mix.js('resources/assets/js/report/FabricProduction/MsProdAopFinDailyLoadReportController.js', 'public/js/report/FabricProduction');
// mix.js('resources/assets/js/report/FabricProduction/MsProdFinishQcBatchCostingController.js', 'public/js/report/FabricProduction');
//========GMT Production
//mix.js('resources/assets/js/report/GmtProduction/MsProdGmtCartonQtyController.js', 'public/js/report/GmtProduction');
//  mix.js('resources/assets/js/report/GmtProduction/MsProdGmtLineWiseHourlyController.js', 'public/js/report/GmtProduction');
// mix.js('resources/assets/js/report/GmtProduction/MsProdGmtDailyReportController.js', 'public/js/report/GmtProduction');
//mix.js('resources/assets/js/report/GmtProduction/MsProdGmtDailyExFactoryReportController.js', 'public/js/report/GmtProduction');
// mix.js('resources/assets/js/report/GmtProduction/MsProdGmtSewingProductionController.js', 'public/js/report/GmtProduction');
//  mix.js('resources/assets/js/report/GmtProduction/MsDailyEfficiencyReportController.js', 'public/js/report/GmtProduction');
//  mix.js('resources/assets/js/report/FabricProduction/MsTxtDailyEfficiencyReportController.js', 'public/js/report/FabricProduction');
// mix.js('resources/assets/js/report/GmtProduction/MsMonthlyEfficiencyReportController.js', 'public/js/report/GmtProduction');
// mix.js('resources/assets/js/report/GmtProduction/MsProdGmtStatusReportController.js', 'public/js/report/GmtProduction');
/*========================   Subcontract  ==========================*/
//========Inbound
// mix.js('resources/assets/js/Subcontract/Inbound/MsAllSubInboundController.js', 'public/js/Subcontract/Inbound');
//mix.js('resources/assets/js/Subcontract/Inbound/MsAllSubInbOrderReceivedController.js', 'public/js/Subcontract/Inbound');
//mix.js('resources/assets/js/app.js', 'public/js');
//========Knitting
mix.js('resources/assets/js/Subcontract/Kniting/MsSoKnitController.js', 'public/js/Subcontract/Kniting');
// mix.js('resources/assets/js/Subcontract/Kniting/MsSoKnitItemController.js', 'public/js/Subcontract/Kniting');
//mix.js('resources/assets/js/Subcontract/Kniting/MsSoKnitFileController.js', 'public/js/Subcontract/Kniting');
// mix.js('resources/assets/js/Subcontract/Kniting/MsAllPlKnitController.js', 'public/js/Subcontract/Kniting');

mix.js('resources/assets/js/Subcontract/Kniting/MsRqYarnController.js', 'public/js/Subcontract/Kniting');
// mix.js('resources/assets/js/Subcontract/Kniting/MsRqYarnFabricationController.js', 'public/js/Subcontract/Kniting');
// mix.js('resources/assets/js/Subcontract/Kniting/MsRqYarnItemController.js', 'public/js/Subcontract/Kniting');
// mix.js('resources/assets/js/Subcontract/Kniting/MsSoKnitYarnRcvController.js', 'public/js/Subcontract/Kniting');
// mix.js('resources/assets/js/Subcontract/Kniting/MsSoKnitYarnRcvItemController.js', 'public/js/Subcontract/Kniting');

// mix.js('resources/assets/js/Subcontract/Kniting/MsSoKnitDlvController.js', 'public/js/Subcontract/Kniting');
// mix.js('resources/assets/js/Subcontract/Kniting/MsSoKnitDlvItemController.js', 'public/js/Subcontract/Kniting');
// mix.js('resources/assets/js/Subcontract/Kniting/MsSoKnitDlvItemYarnController.js', 'public/js/Subcontract/Kniting');

// mix.js('resources/assets/js/Subcontract/Kniting/MsSoKnitYarnRtnController.js', 'public/js/Subcontract/Kniting');
// mix.js('resources/assets/js/Subcontract/Kniting/MsSoKnitYarnRtnItemController.js', 'public/js/Subcontract/Kniting');

/* ===================SoKnitTarget===*/
// mix.js('resources/assets/js/Subcontract/Kniting/MsSoKnitTargetController.js', 'public/js/Subcontract/Kniting');


//=========Dyeing
// mix.js('resources/assets/js/Subcontract/Dyeing/MsSoDyeingController.js', 'public/js/Subcontract/Dyeing');
// mix.js('resources/assets/js/Subcontract/Dyeing/MsSoDyeingItemController.js', 'public/js/Subcontract/Dyeing');
//mix.js('resources/assets/js/Subcontract/Dyeing/MsSoDyeingFileController.js', 'public/js/Subcontract/Dyeing');
// mix.js('resources/assets/js/Subcontract/Dyeing/MsSoDyeingFabricRcvController.js', 'public/js/Subcontract/Dyeing');
// mix.js('resources/assets/js/Subcontract/Dyeing/MsSoDyeingFabricRcvItemController.js', 'public/js/Subcontract/Dyeing');
// mix.js('resources/assets/js/Subcontract/Dyeing/MsSoDyeingFabricRcvRolController.js', 'public/js/Subcontract/Dyeing');

// mix.js('resources/assets/js/Subcontract/Dyeing/MsSoDyeingFabricRcvInhController.js', 'public/js/Subcontract/Dyeing');
// mix.js('resources/assets/js/Subcontract/Dyeing/MsSoDyeingFabricRcvInhItemController.js', 'public/js/Subcontract/Dyeing');
// mix.js('resources/assets/js/Subcontract/Dyeing/MsSoDyeingFabricRcvInhRolController.js', 'public/js/Subcontract/Dyeing');

// mix.js('resources/assets/js/Subcontract/Dyeing/MsAllPlDyeingController.js', 'public/js/Subcontract/Dyeing');

mix.js('resources/assets/js/Subcontract/Dyeing/MsSoDyeingDlvController.js', 'public/js/Subcontract/Dyeing');
// mix.js('resources/assets/js/Subcontract/Dyeing/MsSoDyeingDlvItemController.js', 'public/js/Subcontract/Dyeing');

// mix.js('resources/assets/js/Subcontract/Dyeing/MsSoDyeingTargetController.js', 'public/js/Subcontract/Dyeing');

// mix.js('resources/assets/js/Subcontract/Dyeing/MsSoDyeingFabricRtnController.js', 'public/js/Subcontract/Dyeing');
// mix.js('resources/assets/js/Subcontract/Dyeing/MsSoDyeingFabricRtnItemController.js', 'public/js/Subcontract/Dyeing');
// mix.js('resources/assets/js/Subcontract/Dyeing/MsSoDyeingBomController.js', 'public/js/Subcontract/Dyeing');
// mix.js('resources/assets/js/Subcontract/Dyeing/MsSoDyeingBomFabricController.js', 'public/js/Subcontract/Dyeing');
// mix.js('resources/assets/js/Subcontract/Dyeing/MsSoDyeingBomFabricItemController.js', 'public/js/Subcontract/Dyeing');
// mix.js('resources/assets/js/Subcontract/Dyeing/MsSoDyeingBomOverheadController.js', 'public/js/Subcontract/Dyeing');
// mix.js('resources/assets/js/Subcontract/Dyeing/MsAllDyeingMktCostController.js', 'public/js/Subcontract/Dyeing');
//=========AOP
// mix.js('resources/assets/js/Subcontract/AOP/MsSoAopController.js', 'public/js/Subcontract/AOP');
// mix.js('resources/assets/js/Subcontract/AOP/MsSoAopItemController.js', 'public/js/Subcontract/AOP');
// mix.js('resources/assets/js/Subcontract/AOP/MsSoAopFileController.js', 'public/js/Subcontract/AOP');
// mix.js('resources/assets/js/Subcontract/AOP/MsSoAopFabricRcvController.js', 'public/js/Subcontract/AOP');
// mix.js('resources/assets/js/Subcontract/AOP/MsSoAopFabricRcvItemController.js', 'public/js/Subcontract/AOP');
// mix.js('resources/assets/js/Subcontract/AOP/MsSoAopFabricRcvRolController.js', 'public/js/Subcontract/AOP');
// mix.js('resources/assets/js/Subcontract/AOP/MsSoAopFabricRcvInhController.js', 'public/js/Subcontract/AOP');
// mix.js('resources/assets/js/Subcontract/AOP/MsSoAopFabricRcvInhItemController.js', 'public/js/Subcontract/AOP');
// mix.js('resources/assets/js/Subcontract/AOP/MsSoAopFabricRcvInhRolController.js', 'public/js/Subcontract/AOP');
// mix.js('resources/assets/js/Subcontract/AOP/MsSoAopFabricIsuController.js', 'public/js/Subcontract/AOP');
// mix.js('resources/assets/js/Subcontract/AOP/MsSoAopFabricIsuItemController.js', 'public/js/Subcontract/AOP');

// mix.js('resources/assets/js/Subcontract/AOP/MsSoAopDlvController.js', 'public/js/Subcontract/AOP');
// mix.js('resources/assets/js/Subcontract/AOP/MsSoAopDlvItemController.js', 'public/js/Subcontract/AOP');

// mix.js('resources/assets/js/Subcontract/AOP/MsSoAopTargetController.js', 'public/js/Subcontract/AOP');

// mix.js('resources/assets/js/Subcontract/AOP/MsSoAopFabricRtnController.js', 'public/js/Subcontract/AOP');
// mix.js('resources/assets/js/Subcontract/AOP/MsSoAopFabricRtnItemController.js', 'public/js/Subcontract/AOP');
//===============Embelishment
// mix.js('resources/assets/js/Subcontract/Embelishment/MsSoEmbController.js', 'public/js/Subcontract/Embelishment');
// mix.js('resources/assets/js/Subcontract/Embelishment/MsSoEmbItemController.js', 'public/js/Subcontract/Embelishment');
// mix.js('resources/assets/js/Subcontract/Embelishment/MsSoEmbFileController.js', 'public/js/Subcontract/Embelishment');
// mix.js('resources/assets/js/Subcontract/Embelishment/MsSoEmbTargetController.js', 'public/js/Subcontract/Embelishment');

// subcontract
mix.js('resources/assets/js/Subcontract/Embelishment/MsAllSoEmbCutpanelRcvController.js', 'public/js/Subcontract/Embelishment');

// is Safe
mix.js('resources/assets/js/Subcontract/Embelishment/MsAllSoEmbCutpanelRcvInhController.js', 'public/js/Subcontract/Embelishment');

// mix.js('resources/assets/js/Approval/MsSoAopMktCostQpriceApprovalController.js', 'public/js/approval');
/*===========================  Production ================================*/
/*============Garments===============*/
//mix.js('resources/assets/js/Production/Garments/MsAllProductionController.js', 'public/js/Production/Garments');
// mix.js('resources/assets/js/Production/Garments/MsAllExFactoryController.js', 'public/js/Production/Garments');
// mix.js('resources/assets/js/Production/Garments/MsAllIronController.js', 'public/js/Production/Garments');
// mix.js('resources/assets/js/Production/Garments/MsAllPolyController.js', 'public/js/Production/Garments');
//mix.js('resources/assets/js/Production/Garments/MsAllSewingController.js', 'public/js/Production/Garments');
//mix.js('resources/assets/js/Production/Garments/MsAllCuttingController.js', 'public/js/Production/Garments');
//mix.js('resources/assets/js/Production/Garments/MsAllDlvInputController.js', 'public/js/Production/Garments');
mix.js('resources/assets/js/Production/Garments/MsAllDlvPrintController.js', 'public/js/Production/Garments');
//mix.js('resources/assets/js/Production/Garments/MsAllDlvToEmbController.js', 'public/js/Production/Garments');
//mix.js('resources/assets/js/Production/Garments/MsAllRcvInputController.js', 'public/js/Production/Garments');
//mix.js('resources/assets/js/Production/Garments/MsAllPrintRcvController.js', 'public/js/Production/Garments');
//mix.js('resources/assets/js/Production/Garments/MsAllEmbRcvController.js', 'public/js/Production/Garments');
//mix.js('resources/assets/js/Production/Garments/MsAllSewingLineController.js', 'public/js/Production/Garments');
//mix.js('resources/assets/js/Production/Garments/MsAllInspectionController.js', 'public/js/Production/Garments');
/*============Knitting===============*/
// mix.js('resources/assets/js/Production/Kniting/MsProdKnitController.js', 'public/js/Production/Kniting');
//mix.js('resources/assets/js/Production/Kniting/MsProdKnitItemController.js', 'public/js/Production/Kniting');
// mix.js('resources/assets/js/Production/Kniting/MsProdKnitItemRollController.js', 'public/js/Production/Kniting');
// mix.js('resources/assets/js/Production/Kniting/MsProdKnitItemYarnController.js', 'public/js/Production/Kniting');
//mix.js('resources/assets/js/Production/Kniting/MsProdKnitRcvByQcController.js', 'public/js/Production/Kniting');
// mix.js('resources/assets/js/Production/Kniting/MsProdKnitQcController.js', 'public/js/Production/Kniting');
mix.js('resources/assets/js/Production/Kniting/MsProdKnitDlvController.js', 'public/js/Production/Kniting');
// mix.js('resources/assets/js/Production/Kniting/MsProdKnitDlvRollController.js', 'public/js/Production/Kniting');
/*============Dyeing===============*/
// mix.js('resources/assets/js/Production/Dyeing/MsAllProdBatchController.js', 'public/js/Production/Dyeing');
// mix.js('resources/assets/js/Production/Dyeing/MsAllProdBatchRdController.js', 'public/js/Production/Dyeing');
// mix.js('resources/assets/js/Production/Dyeing/MsProdBatchLoadController.js', 'public/js/Production/Dyeing');
// mix.js('resources/assets/js/Production/Dyeing/MsProdBatchUnloadController.js', 'public/js/Production/Dyeing');
// mix.js('resources/assets/js/Production/Dyeing/MsAllProdBatchFinishProgController.js', 'public/js/Production/Dyeing');
// mix.js('resources/assets/js/Production/Dyeing/MsAllProdBatchFinishQcController.js', 'public/js/Production/Dyeing');
// mix.js('resources/assets/js/Production/Dyeing/MsProdFinishDlvController.js', 'public/js/Production/Dyeing');
// mix.js('resources/assets/js/Production/Dyeing/MsProdFinishDlvRollController.js', 'public/js/Production/Dyeing');
// mix.js('resources/assets/js/Production/Dyeing/MsProdFinishDlvAopController.js', 'public/js/Production/Dyeing');
// mix.js('resources/assets/js/Production/Dyeing/MsProdFinishDlvAopRollController.js', 'public/js/Production/Dyeing');
// mix.js('resources/assets/js/Production/Dyeing/MsAllProdFinishQcBillController.js', 'public/js/Production/Dyeing');
// mix.js('resources/assets/js/Production/Dyeing/MsAllProdFinishMcController.js', 'public/js/Production/Dyeing');
/*============AOP===============*/
// mix.js('resources/assets/js/Production/AOP/MsAllProdAopBatchController.js', 'public/js/Production/AOP');
// mix.js('resources/assets/js/Production/AOP/MsAllProdAopBatchFinishProgController.js', 'public/js/Production/AOP');
// mix.js('resources/assets/js/Production/AOP/MsAllProdAopBatchFinishQcController.js', 'public/js/Production/AOP');
// mix.js('resources/assets/js/Production/AOP/MsAllProdAopFinishDlvController.js', 'public/js/Production/AOP');
// mix.js('resources/assets/js/Production/AOP/MsAllProdAopMcController.js', 'public/js/Production/AOP');
// mix.js('resources/assets/js/Production/AOP/MsAllProdFinishAopMcSetupController.js', 'public/js/Production/AOP');
/*===========================  Work Study ================================*/
// mix.js('resources/assets/js/Workstudy/MsAllWstudyLineSetUpController.js', 'public/js/Workstudy');
/*==========================  Production Emb Print Mc ===============================*/
mix.js('resources/assets/js/Subcontract/Embelishment/MsAllSoEmbPrintMcController.js', 'public/js/Subcontract/Embelishment');

mix.js('resources/assets/js/Subcontract/Embelishment/MsAllSoEmbPrintEntryController.js', 'public/js/Subcontract/Embelishment');

// print Qc 
mix.js('resources/assets/js/Subcontract/Embelishment/MsAllSoEmbPrintQcController.js', 'public/js/Subcontract/Embelishment');

// print Delivery
mix.js('resources/assets/js/Subcontract/Embelishment/MsAllSoEmbPrintDlvController.js', 'public/js/Subcontract/Embelishment');

mix.js('resources/assets/js/Subcontract/Embelishment/MsAllSoEmbPrintDlvInhController.js', 'public/js/Subcontract/Embelishment');
/*===========================  Planing ================================*/
// mix.js('resources/assets/js/Planing/MsTnaOrdController.js', 'public/js/Planing');
// mix.js('resources/assets/js/Planing/MsTnaActualController.js', 'public/js/Planing');
// mix.js('resources/assets/js/Planing/MsAllTnaProgressDelayController.js', 'public/js/Planing');
// mix.js('resources/assets/js/Planing/MsPlaningBoardController.js', 'public/js/Planing');
// mix.js('resources/assets/js/Planing/MsAllTnaTemplateController.js', 'public/js/Planing');
/*  ====================   System   ======================     */
// mix.js('resources/assets/js/app.js', 'public/js');
//mix.sass('resources/assets/sass/app.scss', 'public/css');
// mix.js('resources/assets/js/MsMenuController.js', 'public/js/menu');
// mix.js('resources/assets/js/MsPermissionController.js', 'public/js/menu');
//mix.js('resources/assets/js/MsAllRoleController.js', 'public/js/user-accounts');
// mix.js('resources/assets/js/MsAllUserController.js', 'public/js/user-accounts');
// mix.js('resources/assets/js/MsMyaccountController.js', 'public/js/user-accounts');

/* ===============Cost Centre */
//mix.js('resources/assets/js/MsCgroupController.js', 'public/js/Util');
//  mix.js('resources/assets/js/MsCompanyController.js', 'public/js/Util');
//  mix.js('resources/assets/js/MsRegionController.js', 'public/js/Util');
//  mix.js('resources/assets/js/MsLocationController.js', 'public/js/Util');
//  mix.js('resources/assets/js/MsDivisionController.js', 'public/js/Util');
//  mix.js('resources/assets/js/MsDepartmentController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsAllDepartmentController.js', 'public/js/Util');
//  mix.js('resources/assets/js/MsDesignationController.js', 'public/js/Util');
//  mix.js('resources/assets/js/MsFloorController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsAllSectionController.js', 'public/js/Util');
//  mix.js('resources/assets/js/MsAllSubsectionController.js', 'public/js/Util');
//  mix.js('resources/assets/js/MsCountryController.js', 'public/js/Util');
//  mix.js('resources/assets/js/MsCurrencyController.js', 'public/js/Util');
//  mix.js('resources/assets/js/MsExchangerateController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsAllProfitcenterController.js', 'public/js/Util');

/* ================Item Structure */
//  mix.js('resources/assets/js/MsItemcategoryController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsAllItemclassController.js', 'public/js/Util');
//  mix.js('resources/assets/js/MsConstructionController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsAllCompositionController.js', 'public/js/Util');
//  mix.js('resources/assets/js/MsYarncountController.js', 'public/js/Util');
//  mix.js('resources/assets/js/MsYarntypeController.js', 'public/js/Util');
//  mix.js('resources/assets/js/MsAutoyarnController.js', 'public/js/Util');
//  mix.js('resources/assets/js/MsAutoyarnratioController.js', 'public/js/Util');
//  mix.js('resources/assets/js/MsUomController.js', 'public/js/Util');
//  mix.js('resources/assets/js/MsUomconversionController.js', 'public/js/Util');
//  mix.js('resources/assets/js/MsGmtsProcessLossController.js', 'public/js/Util');
//  mix.js('resources/assets/js/MsGmtsProcessLossPerController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsItemAccountController.js', 'public/js/Util');
//  mix.js('resources/assets/js/MsItemAccountRatioController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsItemAccountSupplierController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsItemAccountSupplierRateController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsItemAccountSupplierFeatController.js', 'public/js/Util');
//  mix.js('resources/assets/js/MsAllStoreController.js', 'public/js/Util');

/* ================== Standard Setup */
//  mix.js('resources/assets/js/MsGmtssampleController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsAllGmtspartController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsAllColorController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsAllSizeController.js', 'public/js/Util');
//  mix.js('resources/assets/js/MsProductionProcessController.js', 'public/js/Util');
mix.js('resources/assets/js/MsProductDefectController.js', 'public/js/Util');
//  mix.js('resources/assets/js/MsColorrangeController.js', 'public/js/Util');
/* 
mix.js('resources/assets/js/MsFabricprocesslossController.js', 'public/js/Util');
mix.js('resources/assets/js/MsFabricprocesslossPercentController.js', 'public/js/Util');
 */
// mix.js('resources/assets/js/MsProductdepartmentController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsSeasonController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsWagevariableController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsResourceController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsAttachmentController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsAllOperationController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsIncentiveController.js', 'public/js/Util');

/* mix.js('resources/assets/js/MsKnitChargeController.js', 'public/js/Util');
mix.js('resources/assets/js/MsKnitChargeSupplierController.js', 'public/js/Util');
mix.js('resources/assets/js/MsBuyerKnitChargeController.js', 'public/js/Util');
 */
//mix.js('resources/assets/js/MsSmvChartController.js', 'public/js/Util');
/*
mix.js('resources/assets/js/MsDyingChargeController.js', 'public/js/Util');
mix.js('resources/assets/js/MsDyingChargeSupplierController.js', 'public/js/Util');
mix.js('resources/assets/js/MsBuyerDyingChargeController.js', 'public/js/Util');
 */
/* mix.js('resources/assets/js/MsYarnDyingChargeController.js', 'public/js/Util');
mix.js('resources/assets/js/MsBuyerYarnDyingChargeController.js', 'public/js/Util');
mix.js('resources/assets/js/MsSupplierYarnDyingChargeController.js', 'public/js/Util'); */

/* mix.js('resources/assets/js/MsEmbelishmentController.js', 'public/js/Util');
mix.js('resources/assets/js/MsEmbelishmentTypeController.js', 'public/js/Util'); */

/* mix.js('resources/assets/js/MsWashChargeController.js', 'public/js/Util');
mix.js('resources/assets/js/MsSupplierWashChargeController.js', 'public/js/Util'); */

//mix.js('resources/assets/js/MsAllKeycontrolController.js', 'public/js/Util');

/* mix.js('resources/assets/js/MsAopChargeController.js', 'public/js/Util');
mix.js('resources/assets/js/MsAopSupplierChargeController.js', 'public/js/Util');
mix.js('resources/assets/js/MsAopBuyerChargeController.js', 'public/js/Util'); */
//mix.js('resources/assets/js/System/Configuration/MsIleConfigController.js', 'public/js/System/Configuration');
// mix.js('resources/assets/js/System/Configuration/MsCostStandardController.js', 'public/js/System/Configuration');
// mix.js('resources/assets/js/System/Configuration/MsCostStandardHeadController.js', 'public/js/System/Configuration');
// mix.js('resources/assets/js/System/Configuration/MsExpDocPrepStdDayController.js', 'public/js/System/Configuration');
// mix.js('resources/assets/js/MsAllSmsSetupController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsAllMailSetupController.js', 'public/js/Util');

/* ===============Contact */
//mix.js('resources/assets/js/MsAllSupplierController.js', 'public/js/Util');
//mix.js('resources/assets/js/MsAllBuyerController.js', 'public/js/Util');
//mix.js('resources/assets/js/MsTeamController.js', 'public/js/Util');
//mix.js('resources/assets/js/MsTeammemberController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsSupplierSettingController.js', 'public/js/Util');

//  mix.js('resources/assets/js/MsBuyerCommissionController.js', 'public/js/Util');
//  mix.js('resources/assets/js/MsBuyerBranchShipdayController.js', 'public/js/Util');

/*  ===============Accounting  */
//mix.js('resources/assets/js/MsAllBankController.js', 'public/js/Util');
//mix.js('resources/assets/js/MsCommercialHeadController.js', 'public/js/Util');

/* ================Renewal */
//mix.js('resources/assets/js/Util/Renewal/MsAllRenewalItemController.js', 'public/js/Util/Renewal');

/* ==================Marketing Cost */
//  mix.js('resources/assets/js/MsAllStyleController.js', 'public/js/Util');
// mix.js('resources/assets/js/Marketing/MsAllBuyerDevelopmentController.js', 'public/js/Marketing');
// mix.js('resources/assets/js/MShipDateChangesCadController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsCadConController.js', 'public/js/Util');
//  mix.js('resources/assets/js/MsAllMktCostController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsProjectionController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsProjectionCountryController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsProjectionQtyController.js', 'public/js/Util');
//mix.js('resources/assets/js/MsAllSaleOrderController.js', 'public/js/Util');
mix.js('resources/assets/js/MsSalesOrderShipDateChangeController.js', 'public/js/Util');
mix.js('resources/assets/js/MsSalesOrderCloseController.js', 'public/js/Util');
// mix.js('resources/assets/js/purchase/MsAdditionalFabricPurchaseController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsExFactoryController.js', 'public/js/Util');
// mix.js('resources/assets/js/purchase/MsProjectionFabricPurchaseController.js', 'public/js/Util');


// mix.js('resources/assets/js/MsDelaycauseController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsSewingCapacityController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsSewingCapacityDateController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsCapacityDistController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsCapacityDistBuyerController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsCapacityDistBuyerTeamController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsTrimcosttempleteController.js', 'public/js/Util');
//mix.js('resources/assets/js/MsTnataskController.js', 'public/js/Util');

/* ==================Sample Cost */
//mix.js('resources/assets/js/Sample/Costing/MsAllSmpCostController.js', 'public/js/Sample/Costing');
//mix.js('resources/assets/js/purchase/MsPurchaseTermsConditionController.js', 'public/js/Util');

/* mix.js('resources/assets/js/purchase/MsShortTrimPurchaseController.js', 'public/js/Util');
 mix.js('resources/assets/js/purchase/MsFabricServiceController.js', 'public/js/Util');
 mix.js('resources/assets/js/purchase/MsYarnDyingController.js', 'public/js/Util');
 mix.js('resources/assets/js/purchase/MsYarnReconingController.js', 'public/js/Util');
 mix.js('resources/assets/js/purchase/MsYarnTwistingController.js', 'public/js/Util');
 mix.js('resources/assets/js/purchase/MsYarnPurchaseOrderController.js', 'public/js/Util');
 mix.js('resources/assets/js/purchase/MsDyesChemicalPurchaseOrderController.js', 'public/js/Util');
 mix.js('resources/assets/js/purchase/MsPrintingStationaryPurchaseOrderController.js', 'public/js/Util');
 mix.js('resources/assets/js/purchase/MsGeneralItemPurchaseOrderController.js', 'public/js/Util');
 mix.js('resources/assets/js/purchase/MsEmbelishmentWorkOrderController.js', 'public/js/Util');
 mix.js('resources/assets/js/purchase/MsGmtsProductionWorkOrderController.js', 'public/js/Util');
 mix.js('resources/assets/js/MsTermsConditionController.js', 'public/js/Util');*/
// mix.js('resources/assets/js/MsAllWeightMachineController.js', 'public/js/Util');
// mix.js('resources/assets/js/MsTargetProcessSetupController.js', 'public/js/Util');
/*===============JhuteSale==================*/
// mix.js('resources/assets/js/JhuteSale/MsAllJhuteSaleDlvOrderController.js', 'public/js/JhuteSale');
mix.js('resources/assets/js/JhuteSale/MsAllJhuteStockController.js', 'public/js/JhuteSale');
// mix.js('resources/assets/js/JhuteSale/MsAllGmtLeftoverSaleOrderController.js', 'public/js/JhuteSale');
// mix.js('resources/assets/js/JhuteSale/MsAllJhuteSaleDlvController.js', 'public/js/JhuteSale');


