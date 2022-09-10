<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Repositories\Contracts\System\MenuRepository;
use App\Repositories\Implementations\Eloquent\System\MenuImplementation;

use App\Repositories\Contracts\System\PermissionRepository;
use App\Repositories\Implementations\Eloquent\System\PermissionImplementation;

use App\Repositories\Contracts\System\Auth\RoleRepository;
use App\Repositories\Implementations\Eloquent\System\Auth\RoleImplementation;

use App\Repositories\Contracts\System\Auth\PermissionRoleRepository;
use App\Repositories\Implementations\Eloquent\System\Auth\PermissionRoleImplementation;

use App\Repositories\Contracts\System\Auth\UserRepository;
use App\Repositories\Implementations\Eloquent\System\Auth\UserImplementation;

use App\Repositories\Contracts\System\Configuration\IleConfigRepository;
use App\Repositories\Implementations\Eloquent\System\Configuration\IleConfigImplementation;

use App\Repositories\Contracts\System\Configuration\CostStandardRepository;
use App\Repositories\Implementations\Eloquent\System\Configuration\CostStandardImplementation;

use App\Repositories\Contracts\System\Configuration\CostStandardHeadRepository;
use App\Repositories\Implementations\Eloquent\System\Configuration\CostStandardHeadImplementation;

use App\Repositories\Contracts\System\Configuration\ExpDocPrepStdDayRepository;
use App\Repositories\Implementations\Eloquent\System\Configuration\ExpDocPrepStdDayImplementation;

use App\Repositories\Contracts\Util\CgroupRepository;
use App\Repositories\Implementations\Eloquent\Util\CgroupImplementation;

use App\Repositories\Contracts\Util\CompanyRepository;
use App\Repositories\Implementations\Eloquent\Util\CompanyImplementation;

use App\Repositories\Contracts\Util\RegionRepository;
use App\Repositories\Implementations\Eloquent\Util\RegionImplementation;

use App\Repositories\Contracts\Util\LocationRepository;
use App\Repositories\Implementations\Eloquent\Util\LocationImplementation;

use App\Repositories\Contracts\Util\DivisionRepository;
use App\Repositories\Implementations\Eloquent\Util\DivisionImplementation;

use App\Repositories\Contracts\Util\FloorRepository;
use App\Repositories\Implementations\Eloquent\Util\FloorImplementation;

use App\Repositories\Contracts\Util\DepartmentRepository;
use App\Repositories\Implementations\Eloquent\Util\DepartmentImplementation;

use App\Repositories\Contracts\Util\DepartmentFloorRepository;
use App\Repositories\Implementations\Eloquent\Util\DepartmentFloorImplementation;

use App\Repositories\Contracts\Util\SectionRepository;
use App\Repositories\Implementations\Eloquent\Util\SectionImplementation;

use App\Repositories\Contracts\Util\FloorSectionRepository;
use App\Repositories\Implementations\Eloquent\Util\FloorSectionImplementation;

use App\Repositories\Contracts\Util\SubsectionRepository;
use App\Repositories\Implementations\Eloquent\Util\SubsectionImplementation;

use App\Repositories\Contracts\Util\CompanySubsectionRepository;
use App\Repositories\Implementations\Eloquent\Util\CompanySubsectionImplementation;

use App\Repositories\Contracts\Util\ProfitcenterRepository;
use App\Repositories\Implementations\Eloquent\Util\ProfitcenterImplementation;

use App\Repositories\Contracts\Util\CompanyProfitcenterRepository;
use App\Repositories\Implementations\Eloquent\Util\CompanyProfitcenterImplementation;

use App\Repositories\Contracts\Util\CountryRepository;
use App\Repositories\Implementations\Eloquent\Util\CountryImplementation;

use App\Repositories\Contracts\Util\CurrencyRepository;
use App\Repositories\Implementations\Eloquent\Util\CurrencyImplementation;

use App\Repositories\Contracts\Util\UomRepository;
use App\Repositories\Implementations\Eloquent\Util\UomImplementation;

use App\Repositories\Contracts\Util\UomconversionRepository;
use App\Repositories\Implementations\Eloquent\Util\UomconversionImplementation;

use App\Repositories\Contracts\Util\ExchangerateRepository;
use App\Repositories\Implementations\Eloquent\Util\ExchangerateImplementation;

use App\Repositories\Contracts\Util\ItemcategoryRepository;
use App\Repositories\Implementations\Eloquent\Util\ItemcategoryImplementation;

use App\Repositories\Contracts\Util\ItemclassRepository;
use App\Repositories\Implementations\Eloquent\Util\ItemclassImplementation;

use App\Repositories\Contracts\Util\ItemclassProfitcenterRepository;
use App\Repositories\Implementations\Eloquent\Util\ItemclassProfitcenterImplementation;

use App\Repositories\Contracts\Util\TeamRepository;
use App\Repositories\Implementations\Eloquent\Util\TeamImplementation;

use App\Repositories\Contracts\Util\TeammemberRepository;
use App\Repositories\Implementations\Eloquent\Util\TeammemberImplementation;

use App\Repositories\Contracts\Util\GmtssampleRepository;
use App\Repositories\Implementations\Eloquent\Util\GmtssampleImplementation;

use App\Repositories\Contracts\Util\SupplierRepository;
use App\Repositories\Implementations\Eloquent\Util\SupplierImplementation;

use App\Repositories\Contracts\Util\BuyerRepository;
use App\Repositories\Implementations\Eloquent\Util\BuyerImplementation;
use App\Repositories\Contracts\Util\BuyerNatureRepository;
use App\Repositories\Implementations\Eloquent\Util\BuyerNatureImplementation;

use App\Repositories\Contracts\Util\CompanyBuyerRepository;
use App\Repositories\Implementations\Eloquent\Util\CompanyBuyerImplementation;

use App\Repositories\Contracts\Util\CompanyUserRepository;
use App\Repositories\Implementations\Eloquent\Util\CompanyUserImplementation;

use App\Repositories\Contracts\Util\ItemcategoryUserRepository;
use App\Repositories\Implementations\Eloquent\Util\ItemcategoryUserImplementation;

use App\Repositories\Contracts\Util\BuyerUserRepository;
use App\Repositories\Implementations\Eloquent\Util\BuyerUserImplementation;

use App\Repositories\Contracts\Util\SupplierUserRepository;
use App\Repositories\Implementations\Eloquent\Util\SupplierUserImplementation;
use App\Repositories\Contracts\System\PermissionUserRepository;
use App\Repositories\Implementations\Eloquent\System\PermissionUserImplementation;

use App\Repositories\Contracts\Util\ContactNatureRepository;
use App\Repositories\Implementations\Eloquent\Util\ContactNatureImplementation;


use App\Repositories\Contracts\Util\CompositionRepository;
use App\Repositories\Implementations\Eloquent\Util\CompositionImplementation;

use App\Repositories\Contracts\Util\CompositionItemcategoryRepository;
use App\Repositories\Implementations\Eloquent\Util\CompositionItemcategoryImplementation;

use App\Repositories\Contracts\Util\YarncountRepository;
use App\Repositories\Implementations\Eloquent\Util\YarncountImplementation;

use App\Repositories\Contracts\Util\ConstructionRepository;
use App\Repositories\Implementations\Eloquent\Util\ConstructionImplementation;

use App\Repositories\Contracts\Util\ColorrangeRepository;
use App\Repositories\Implementations\Eloquent\Util\ColorrangeImplementation;

use App\Repositories\Contracts\Util\FabricprocesslossRepository;
use App\Repositories\Implementations\Eloquent\Util\FabricprocesslossImplementation;

use App\Repositories\Contracts\Util\GmtspartRepository;
use App\Repositories\Implementations\Eloquent\Util\GmtspartImplementation;

use App\Repositories\Contracts\Util\GmtspartMenuRepository;
use App\Repositories\Implementations\Eloquent\Util\GmtspartMenuImplementation;

use App\Repositories\Contracts\Util\ColorRepository;
use App\Repositories\Implementations\Eloquent\Util\ColorImplementation;

use App\Repositories\Contracts\Util\BuyerColorRepository;
use App\Repositories\Implementations\Eloquent\Util\BuyerColorImplementation;

use App\Repositories\Contracts\Util\SizeRepository;
use App\Repositories\Implementations\Eloquent\Util\SizeImplementation;

use App\Repositories\Contracts\Util\BuyerSizeRepository;
use App\Repositories\Implementations\Eloquent\Util\BuyerSizeImplementation;

use App\Repositories\Contracts\Util\ProductionProcessRepository;
use App\Repositories\Implementations\Eloquent\Util\ProductionProcessImplementation;
use App\Repositories\Contracts\Util\ProductDefectRepository;
use App\Repositories\Implementations\Eloquent\Util\ProductDefectImplementation;

use App\Repositories\Contracts\Util\ProductdepartmentRepository;
use App\Repositories\Implementations\Eloquent\Util\ProductdepartmentImplementation;

use App\Repositories\Contracts\Util\SeasonRepository;
use App\Repositories\Implementations\Eloquent\Util\SeasonImplementation;

use App\Repositories\Contracts\Util\YarntypeRepository;
use App\Repositories\Implementations\Eloquent\Util\YarntypeImplementation;

use App\Repositories\Contracts\Util\WagevariableRepository;
use App\Repositories\Implementations\Eloquent\Util\WagevariableImplementation;

use App\Repositories\Contracts\Util\ResourceRepository;
use App\Repositories\Implementations\Eloquent\Util\ResourceImplementation;

use App\Repositories\Contracts\Util\AttachmentRepository;
use App\Repositories\Implementations\Eloquent\Util\AttachmentImplementation;

use App\Repositories\Contracts\Util\OperationRepository;
use App\Repositories\Implementations\Eloquent\Util\OperationImplementation;

use App\Repositories\Contracts\Util\AttachmentOperationRepository;
use App\Repositories\Implementations\Eloquent\Util\AttachmentOperationImplementation;

use App\Repositories\Contracts\Util\IncentiveRepository;
use App\Repositories\Implementations\Eloquent\Util\IncentiveImplementation;

use App\Repositories\Contracts\Util\SmvChartRepository;
use App\Repositories\Implementations\Eloquent\Util\SmvChartImplementation;

use App\Repositories\Contracts\Util\KnitChargeRepository;
use App\Repositories\Implementations\Eloquent\Util\KnitChargeImplementation;

use App\Repositories\Contracts\Util\KnitChargeSupplierRepository;
use App\Repositories\Implementations\Eloquent\Util\KnitChargeSupplierImplementation;

use App\Repositories\Contracts\Util\BuyerKnitChargeRepository;
use App\Repositories\Implementations\Eloquent\Util\BuyerKnitChargeImplementation;


use App\Repositories\Contracts\Util\AopChargeRepository;
use App\Repositories\Implementations\Eloquent\Util\AopChargeImplementation;

use App\Repositories\Contracts\Util\AopSupplierChargeRepository;
use App\Repositories\Implementations\Eloquent\Util\AopSupplierChargeImplementation;

use App\Repositories\Contracts\Util\AopBuyerChargeRepository;
use App\Repositories\Implementations\Eloquent\Util\AopBuyerChargeImplementation;


use App\Repositories\Contracts\Util\DyingChargeRepository;
use App\Repositories\Implementations\Eloquent\Util\DyingChargeImplementation;

use App\Repositories\Contracts\Util\DyingChargeSupplierRepository;
use App\Repositories\Implementations\Eloquent\Util\DyingChargeSupplierImplementation;

use App\Repositories\Contracts\Util\BuyerDyingChargeRepository;
use App\Repositories\Implementations\Eloquent\Util\BuyerDyingChargeImplementation;

use App\Repositories\Contracts\Util\YarnDyingChargeRepository;
use App\Repositories\Implementations\Eloquent\Util\YarnDyingChargeImplementation;

use App\Repositories\Contracts\Util\BuyerYarnDyingChargeRepository;
use App\Repositories\Implementations\Eloquent\Util\BuyerYarnDyingChargeImplementation;

use App\Repositories\Contracts\Util\SupplierYarnDyingChargeRepository;
use App\Repositories\Implementations\Eloquent\Util\SupplierYarnDyingChargeImplementation;

use App\Repositories\Contracts\Util\EmbelishmentRepository;
use App\Repositories\Implementations\Eloquent\Util\EmbelishmentImplementation;

use App\Repositories\Contracts\Util\EmbelishmentTypeRepository;
use App\Repositories\Implementations\Eloquent\Util\EmbelishmentTypeImplementation;

use App\Repositories\Contracts\Util\WashChargeRepository;
use App\Repositories\Implementations\Eloquent\Util\WashChargeImplementation;

use App\Repositories\Contracts\Util\SupplierWashChargeRepository;
use App\Repositories\Implementations\Eloquent\Util\SupplierWashChargeImplementation;

use App\Repositories\Contracts\Util\FabricprocesslossPercentRepository;
use App\Repositories\Implementations\Eloquent\Util\FabricprocesslossPercentImplementation;

use App\Repositories\Contracts\Util\AutoyarnRepository;
use App\Repositories\Implementations\Eloquent\Util\AutoyarnImplementation;

use App\Repositories\Contracts\Util\AutoyarnratioRepository;
use App\Repositories\Implementations\Eloquent\Util\AutoyarnratioImplementation;

use App\Repositories\Contracts\Util\GmtsProcessLossRepository;
use App\Repositories\Implementations\Eloquent\Util\GmtsProcessLossImplementation;

use App\Repositories\Contracts\Util\GmtsProcessLossPerRepository;
use App\Repositories\Implementations\Eloquent\Util\GmtsProcessLossPerImplementation;

use App\Repositories\Contracts\Util\BuyerBranchRepository;
use App\Repositories\Implementations\Eloquent\Util\BuyerBranchImplementation;

use App\Repositories\Contracts\Util\BuyerBranchShipdayRepository;
use App\Repositories\Implementations\Eloquent\Util\BuyerBranchShipdayImplementation;

use App\Repositories\Contracts\Util\DelaycauseRepository;
use App\Repositories\Implementations\Eloquent\Util\DelaycauseImplementation;

use App\Repositories\Contracts\Util\DesignationRepository;
use App\Repositories\Implementations\Eloquent\Util\DesignationImplementation;

use App\Repositories\Contracts\Util\SewingCapacityRepository;
use App\Repositories\Implementations\Eloquent\Util\SewingCapacityImplementation;

use App\Repositories\Contracts\Util\SewingCapacityDateRepository;
use App\Repositories\Implementations\Eloquent\Util\SewingCapacityDateImplementation;

use App\Repositories\Contracts\Util\CapacityDistRepository;
use App\Repositories\Implementations\Eloquent\Util\CapacityDistImplementation;

use App\Repositories\Contracts\Util\CapacityDistBuyerRepository;
use App\Repositories\Implementations\Eloquent\Util\CapacityDistBuyerImplementation;

use App\Repositories\Contracts\Util\Renewal\RenewalItemRepository;
use App\Repositories\Implementations\Eloquent\Util\Renewal\RenewalItemImplementation;

use App\Repositories\Contracts\Util\Renewal\RenewalItemDocRepository;
use App\Repositories\Implementations\Eloquent\Util\Renewal\RenewalItemDocImplementation;

use App\Repositories\Contracts\Util\CapacityDistBuyerTeamRepository;
use App\Repositories\Implementations\Eloquent\Util\CapacityDistBuyerTeamImplementation;

use App\Repositories\Contracts\Util\KeycontrolRepository;
use App\Repositories\Implementations\Eloquent\Util\KeycontrolImplementation;

use App\Repositories\Contracts\Util\KeycontrolParameterRepository;
use App\Repositories\Implementations\Eloquent\Util\KeycontrolParameterImplementation;

use App\Repositories\Contracts\Util\TrimcosttempleteRepository;
use App\Repositories\Implementations\Eloquent\Util\TrimcosttempleteImplementation;

use App\Repositories\Contracts\Util\TnataskRepository;
use App\Repositories\Implementations\Eloquent\Util\TnataskImplementation;

use App\Repositories\Contracts\Util\ItemAccountRepository;
use App\Repositories\Implementations\Eloquent\Util\ItemAccountImplementation;

use App\Repositories\Contracts\Util\ItemAccountRatioRepository;
use App\Repositories\Implementations\Eloquent\Util\ItemAccountRatioImplementation;

use App\Repositories\Contracts\Util\ItemAccountSupplierRepository;
use App\Repositories\Implementations\Eloquent\Util\ItemAccountSupplierImplementation;

use App\Repositories\Contracts\Util\ItemAccountSupplierRateRepository;
use App\Repositories\Implementations\Eloquent\Util\ItemAccountSupplierRateImplementation;

use App\Repositories\Contracts\Util\ItemAccountSupplierFeatRepository;
use App\Repositories\Implementations\Eloquent\Util\ItemAccountSupplierFeatImplementation;

use App\Repositories\Contracts\Util\TermsConditionRepository;
use App\Repositories\Implementations\Eloquent\Util\TermsConditionImplementation;
use App\Repositories\Contracts\Util\WeightMachineRepository;
use App\Repositories\Implementations\Eloquent\Util\WeightMachineImplementation;
use App\Repositories\Contracts\Util\WeightMachineUserRepository;
use App\Repositories\Implementations\Eloquent\Util\WeightMachineUserImplementation;

use App\Repositories\Contracts\Util\WorkingHourSetupRepository;
use App\Repositories\Implementations\Eloquent\Util\WorkingHourSetupImplementation;

use App\Repositories\Contracts\Util\TargetProcessSetupRepository;
use App\Repositories\Implementations\Eloquent\Util\TargetProcessSetupImplementation;

use App\Repositories\Contracts\Util\SupplierSettingRepository;
use App\Repositories\Implementations\Eloquent\Util\SupplierSettingImplementation;

use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Repositories\Implementations\Eloquent\Marketing\StyleImplementation;

use App\Repositories\Contracts\Marketing\StyleGmtsRepository;
use App\Repositories\Implementations\Eloquent\Marketing\StyleGmtsImplementation;


use App\Repositories\Contracts\Marketing\StyleEmbelishmentRepository;
use App\Repositories\Implementations\Eloquent\Marketing\StyleEmbelishmentImplementation;

use App\Repositories\Contracts\Marketing\StyleColorRepository;
use App\Repositories\Implementations\Eloquent\Marketing\StyleColorImplementation;

use App\Repositories\Contracts\Marketing\StyleSizeRepository;
use App\Repositories\Implementations\Eloquent\Marketing\StyleSizeImplementation;

use App\Repositories\Contracts\Marketing\StyleGmtColorSizeRepository;
use App\Repositories\Implementations\Eloquent\Marketing\StyleGmtColorSizeImplementation;


use App\Repositories\Contracts\Marketing\StyleFabricationRepository;
use App\Repositories\Implementations\Eloquent\Marketing\StyleFabricationImplementation;


use App\Repositories\Contracts\Marketing\StyleFabricationStripeRepository;
use App\Repositories\Implementations\Eloquent\Marketing\StyleFabricationStripeImplementation;


use App\Repositories\Contracts\Marketing\StyleSizeMsureRepository;
use App\Repositories\Implementations\Eloquent\Marketing\StyleSizeMsureImplementation;

use App\Repositories\Contracts\Marketing\StyleSizeMsureValRepository;
use App\Repositories\Implementations\Eloquent\Marketing\StyleSizeMsureValImplementation;

use App\Repositories\Contracts\Marketing\StyleSampleRepository;
use App\Repositories\Implementations\Eloquent\Marketing\StyleSampleImplementation;

use App\Repositories\Contracts\Marketing\StyleSampleCsRepository;
use App\Repositories\Implementations\Eloquent\Marketing\StyleSampleCsImplementation;

use App\Repositories\Contracts\Marketing\StylePolyRepository;
use App\Repositories\Implementations\Eloquent\Marketing\StylePolyImplementation;

use App\Repositories\Contracts\Marketing\StylePolyRatioRepository;
use App\Repositories\Implementations\Eloquent\Marketing\StylePolyRatioImplementation;

use App\Repositories\Contracts\Marketing\StylePkgRepository;
use App\Repositories\Implementations\Eloquent\Marketing\StylePkgImplementation;

use App\Repositories\Contracts\Marketing\StylePkgRatioRepository;
use App\Repositories\Implementations\Eloquent\Marketing\StylePkgRatioImplementation;

use App\Repositories\Contracts\Marketing\StyleEvaluationRepository;
use App\Repositories\Implementations\Eloquent\Marketing\StyleEvaluationImplementation;

use App\Repositories\Contracts\Marketing\MktCostRepository;
use App\Repositories\Implementations\Eloquent\Marketing\MktCostImplementation;

use App\Repositories\Contracts\Marketing\MktCostFabricRepository;
use App\Repositories\Implementations\Eloquent\Marketing\MktCostFabricImplementation;

use App\Repositories\Contracts\Marketing\MktCostFabricConRepository;
use App\Repositories\Implementations\Eloquent\Marketing\MktCostFabricConImplementation;

use App\Repositories\Contracts\Marketing\MktCostOtherRepository;
use App\Repositories\Implementations\Eloquent\Marketing\MktCostOtherImplementation;

use App\Repositories\Contracts\Marketing\MktCostCommercialRepository;
use App\Repositories\Implementations\Eloquent\Marketing\MktCostCommercialImplementation;

use App\Repositories\Contracts\Marketing\MktCostCmRepository;
use App\Repositories\Implementations\Eloquent\Marketing\MktCostCmImplementation;

use App\Repositories\Contracts\Marketing\MktCostTrimRepository;
use App\Repositories\Implementations\Eloquent\Marketing\MktCostTrimImplementation;
use App\Repositories\Contracts\Marketing\MktCostCommissionRepository;
use App\Repositories\Implementations\Eloquent\Marketing\MktCostCommissionImplementation;

/******* Marketing ********/

use App\Repositories\Contracts\Marketing\TargetTransferRepository;
use App\Repositories\Implementations\Eloquent\Marketing\TargetTransferImplementation;

use App\Repositories\Contracts\Marketing\DayTargetTransferRepository;
use App\Repositories\Implementations\Eloquent\Marketing\DayTargetTransferImplementation;

use App\Repositories\Contracts\Marketing\BuyerDevelopmentRepository;
use App\Repositories\Implementations\Eloquent\Marketing\BuyerDevelopmentImplementation;
use App\Repositories\Contracts\Marketing\BuyerDevelopmentEventRepository;
use App\Repositories\Implementations\Eloquent\Marketing\BuyerDevelopmentEventImplementation;
use App\Repositories\Contracts\Marketing\BuyerDevelopmentIntmRepository;
use App\Repositories\Implementations\Eloquent\Marketing\BuyerDevelopmentIntmImplementation;
use App\Repositories\Contracts\Marketing\BuyerDevelopmentDocRepository;
use App\Repositories\Implementations\Eloquent\Marketing\BuyerDevelopmentDocImplementation;
use App\Repositories\Contracts\Marketing\BuyerDevelopmentOrderRepository;
use App\Repositories\Implementations\Eloquent\Marketing\BuyerDevelopmentOrderImplementation;
use App\Repositories\Contracts\Marketing\BuyerDevelopmentOrderQtyRepository;
use App\Repositories\Implementations\Eloquent\Marketing\BuyerDevelopmentOrderQtyImplementation;


use App\Repositories\Contracts\Marketing\MktCostYarnRepository;
use App\Repositories\Implementations\Eloquent\Marketing\MktCostYarnImplementation;

use App\Repositories\Contracts\Marketing\MktCostFabricProdRepository;
use App\Repositories\Implementations\Eloquent\Marketing\MktCostFabricProdImplementation;

use App\Repositories\Contracts\Marketing\MktCostProfitRepository;
use App\Repositories\Implementations\Eloquent\Marketing\MktCostProfitImplementation;

use App\Repositories\Contracts\Marketing\MktCostQuotePriceRepository;
use App\Repositories\Implementations\Eloquent\Marketing\MktCostQuotePriceImplementation;

use App\Repositories\Contracts\Marketing\MktCostTargetPriceRepository;
use App\Repositories\Implementations\Eloquent\Marketing\MktCostTargetPriceImplementation;


use App\Repositories\Contracts\Marketing\MktCostEmbRepository;
use App\Repositories\Implementations\Eloquent\Marketing\MktCostEmbImplementation;

use App\Repositories\Contracts\Marketing\StyleFileUploadRepository;
use App\Repositories\Implementations\Eloquent\Marketing\StyleFileUploadImplementation;

use App\Repositories\Contracts\Bom\CadRepository;
use App\Repositories\Implementations\Eloquent\Bom\CadImplementation;

use App\Repositories\Contracts\Bom\CadConRepository;
use App\Repositories\Implementations\Eloquent\Bom\CadConImplementation;

use App\Repositories\Contracts\Bom\BudgetRepository;
use App\Repositories\Implementations\Eloquent\Bom\BudgetImplementation;

use App\Repositories\Contracts\Bom\BudgetFabricRepository;
use App\Repositories\Implementations\Eloquent\Bom\BudgetFabricImplementation;

use App\Repositories\Contracts\Bom\BudgetFabricConRepository;
use App\Repositories\Implementations\Eloquent\Bom\BudgetFabricConImplementation;

use App\Repositories\Contracts\Bom\BudgetOtherRepository;
use App\Repositories\Implementations\Eloquent\Bom\BudgetOtherImplementation;

use App\Repositories\Contracts\Bom\BudgetCommercialRepository;
use App\Repositories\Implementations\Eloquent\Bom\BudgetCommercialImplementation;

use App\Repositories\Contracts\Bom\BudgetCmRepository;
use App\Repositories\Implementations\Eloquent\Bom\BudgetCmImplementation;

use App\Repositories\Contracts\Bom\BudgetTrimRepository;
use App\Repositories\Implementations\Eloquent\Bom\BudgetTrimImplementation;

use App\Repositories\Contracts\Bom\BudgetTrimConRepository;
use App\Repositories\Implementations\Eloquent\Bom\BudgetTrimConImplementation;

use App\Repositories\Contracts\Bom\BudgetTrimDtmRepository;
use App\Repositories\Implementations\Eloquent\Bom\BudgetTrimDtmImplementation;

use App\Repositories\Contracts\Bom\BudgetCommissionRepository;
use App\Repositories\Implementations\Eloquent\Bom\BudgetCommissionImplementation;

use App\Repositories\Contracts\Bom\BudgetYarnRepository;
use App\Repositories\Implementations\Eloquent\Bom\BudgetYarnImplementation;

use App\Repositories\Contracts\Bom\BudgetFabricProdRepository;
use App\Repositories\Implementations\Eloquent\Bom\BudgetFabricProdImplementation;

use App\Repositories\Contracts\Bom\BudgetFabricProdConRepository;
use App\Repositories\Implementations\Eloquent\Bom\BudgetFabricProdConImplementation;

use App\Repositories\Contracts\Bom\BudgetEmbRepository;
use App\Repositories\Implementations\Eloquent\Bom\BudgetEmbImplementation;

use App\Repositories\Contracts\Bom\BudgetEmbConRepository;
use App\Repositories\Implementations\Eloquent\Bom\BudgetEmbConImplementation;

use App\Repositories\Contracts\Bom\BudgetYarnDyeingRepository;
use App\Repositories\Implementations\Eloquent\Bom\BudgetYarnDyeingImplementation;

use App\Repositories\Contracts\Bom\BudgetYarnDyeingConRepository;
use App\Repositories\Implementations\Eloquent\Bom\BudgetYarnDyeingConImplementation;


use App\Repositories\Contracts\Sales\JobRepository;
use App\Repositories\Implementations\Eloquent\Sales\JobImplementation;

use App\Repositories\Contracts\Sales\ProjectionRepository;
use App\Repositories\Implementations\Eloquent\Sales\ProjectionImplementation;

use App\Repositories\Contracts\Sales\ProjectionCountryRepository;
use App\Repositories\Implementations\Eloquent\Sales\ProjectionCountryImplementation;

use App\Repositories\Contracts\Sales\ProjectionQtyRepository;
use App\Repositories\Implementations\Eloquent\Sales\ProjectionQtyImplementation;

use App\Repositories\Contracts\Sales\SalesOrderRepository;
use App\Repositories\Implementations\Eloquent\Sales\SalesOrderImplementation;

use App\Repositories\Contracts\Sales\SalesOrderCountryRepository;
use App\Repositories\Implementations\Eloquent\Sales\SalesOrderCountryImplementation;

use App\Repositories\Contracts\Sales\SalesOrderColorRepository;
use App\Repositories\Implementations\Eloquent\Sales\SalesOrderColorImplementation;

use App\Repositories\Contracts\Sales\SalesOrderSizeRepository;
use App\Repositories\Implementations\Eloquent\Sales\SalesOrderSizeImplementation;

use App\Repositories\Contracts\Sales\SalesOrderItemRepository;
use App\Repositories\Implementations\Eloquent\Sales\SalesOrderItemImplementation;

use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Repositories\Implementations\Eloquent\Sales\SalesOrderGmtColorSizeImplementation;

use App\Repositories\Contracts\Sales\ExFactoryRepository;
use App\Repositories\Implementations\Eloquent\Sales\ExFactoryImplementation;

use App\Repositories\Contracts\Sales\SalesOrderShipDateChangeRepository;
use App\Repositories\Implementations\Eloquent\Sales\SalesOrderShipDateChangeImplementation;

use App\Repositories\Contracts\Sales\SalesOrderCloseRepository;
use App\Repositories\Implementations\Eloquent\Sales\SalesOrderCloseImplementation;


use App\Repositories\Contracts\Purchase\PurchaseOrderRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PurchaseOrderImplementation;

use App\Repositories\Contracts\Purchase\PurchaseTermsConditionRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PurchaseTermsConditionImplementation;

use App\Repositories\Contracts\Purchase\PoFabricRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoFabricImplementation;
use App\Repositories\Contracts\Purchase\PoFabricItemRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoFabricItemImplementation;
use App\Repositories\Contracts\Purchase\PoFabricItemQtyRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoFabricItemQtyImplementation;

use App\Repositories\Contracts\Purchase\PoKnitServiceRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoKnitServiceImplementation;
use App\Repositories\Contracts\Purchase\PoKnitServiceItemRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoKnitServiceItemImplementation;
use App\Repositories\Contracts\Purchase\PoKnitServiceItemQtyRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoKnitServiceItemQtyImplementation;

use App\Repositories\Contracts\Purchase\PoDyeingServiceRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoDyeingServiceImplementation;
use App\Repositories\Contracts\Purchase\PoDyeingServiceItemRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoDyeingServiceItemImplementation;
use App\Repositories\Contracts\Purchase\PoDyeingServiceItemQtyRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoDyeingServiceItemQtyImplementation;


use App\Repositories\Contracts\Purchase\PoAopServiceRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoAopServiceImplementation;

use App\Repositories\Contracts\Purchase\PoAopServiceItemRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoAopServiceItemImplementation;

use App\Repositories\Contracts\Purchase\PoAopServiceItemQtyRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoAopServiceItemQtyImplementation;

use App\Repositories\Contracts\Purchase\PoTrimRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoTrimImplementation;
use App\Repositories\Contracts\Purchase\PoTrimItemRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoTrimItemImplementation;
use App\Repositories\Contracts\Purchase\PoTrimItemQtyRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoTrimItemQtyImplementation;

use App\Repositories\Contracts\Purchase\PoYarnRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoYarnImplementation;
use App\Repositories\Contracts\Purchase\PoYarnItemRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoYarnItemImplementation;
use App\Repositories\Contracts\Purchase\PoYarnItemBomQtyRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoYarnItemBomQtyImplementation;

use App\Repositories\Contracts\Purchase\PoYarnDyeingRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoYarnDyeingImplementation;

use App\Repositories\Contracts\Purchase\PoYarnDyeingItemRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoYarnDyeingItemImplementation;

use App\Repositories\Contracts\Purchase\PoYarnDyeingItemBomQtyRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoYarnDyeingItemBomQtyImplementation;

use App\Repositories\Contracts\Purchase\PoYarnDyeingItemRespRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoYarnDyeingItemRespImplementation;

use App\Repositories\Contracts\Purchase\PoDyeChemRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoDyeChemImplementation;

use App\Repositories\Contracts\Purchase\PoDyeChemItemRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoDyeChemItemImplementation;

use App\Repositories\Contracts\Purchase\PoGeneralRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoGeneralImplementation;

use App\Repositories\Contracts\Purchase\PoGeneralItemRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoGeneralItemImplementation;

use App\Repositories\Contracts\Purchase\PoGeneralServiceRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoGeneralServiceImplementation;

use App\Repositories\Contracts\Purchase\PoGeneralServiceItemRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoGeneralServiceItemImplementation;

use App\Repositories\Contracts\Purchase\PoEmbServiceRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoEmbServiceImplementation;
use App\Repositories\Contracts\Purchase\PoEmbServiceItemRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoEmbServiceItemImplementation;
use App\Repositories\Contracts\Purchase\PoEmbServiceItemQtyRepository;
use App\Repositories\Implementations\Eloquent\Purchase\PoEmbServiceItemQtyImplementation;


use App\Repositories\Contracts\Account\AccYearRepository;
use App\Repositories\Implementations\Eloquent\Account\AccYearImplementation;

use App\Repositories\Contracts\Account\AccPeriodRepository;
use App\Repositories\Implementations\Eloquent\Account\AccPeriodImplementation;

use App\Repositories\Contracts\Account\AccChartSubGroupRepository;
use App\Repositories\Implementations\Eloquent\Account\AccChartSubGroupImplementation;

use App\Repositories\Contracts\Account\AccChartCtrlHeadRepository;
use App\Repositories\Implementations\Eloquent\Account\AccChartCtrlHeadImplementation;

use App\Repositories\Contracts\Account\AccChartLocationRepository;
use App\Repositories\Implementations\Eloquent\Account\AccChartLocationImplementation;

use App\Repositories\Contracts\Account\AccChartDivisionRepository;
use App\Repositories\Implementations\Eloquent\Account\AccChartDivisionImplementation;

use App\Repositories\Contracts\Account\AccChartDepartmentRepository;
use App\Repositories\Implementations\Eloquent\Account\AccChartDepartmentImplementation;

use App\Repositories\Contracts\Account\AccChartSectionRepository;
use App\Repositories\Implementations\Eloquent\Account\AccChartSectionImplementation;

use App\Repositories\Contracts\Account\AccChartCtrlHeadMappingRepository;
use App\Repositories\Implementations\Eloquent\Account\AccChartCtrlHeadMappingImplementation;

use App\Repositories\Contracts\Account\AccTransPrntRepository;
use App\Repositories\Implementations\Eloquent\Account\AccTransPrntImplementation;

use App\Repositories\Contracts\Account\AccTransChldRepository;
use App\Repositories\Implementations\Eloquent\Account\AccTransChldImplementation;

use App\Repositories\Contracts\Account\AccTransSalesRepository;
use App\Repositories\Implementations\Eloquent\Account\AccTransSalesImplementation;

use App\Repositories\Contracts\Account\AccTransPurchaseRepository;
use App\Repositories\Implementations\Eloquent\Account\AccTransPurchaseImplementation;

use App\Repositories\Contracts\Account\AccTransEmployeeRepository;
use App\Repositories\Implementations\Eloquent\Account\AccTransEmployeeImplementation;

use App\Repositories\Contracts\Account\AccTransLoanRefRepository;
use App\Repositories\Implementations\Eloquent\Account\AccTransLoanRefImplementation;

use App\Repositories\Contracts\Account\AccTransOtherPartyRepository;
use App\Repositories\Implementations\Eloquent\Account\AccTransOtherPartyImplementation;
use App\Repositories\Contracts\Account\AccTransOtherRefRepository;
use App\Repositories\Implementations\Eloquent\Account\AccTransOtherRefImplementation;

use App\Repositories\Contracts\Account\AccBepRepository;
use App\Repositories\Implementations\Eloquent\Account\AccBepImplementation;

use App\Repositories\Contracts\Account\AccBepEntryRepository;
use App\Repositories\Implementations\Eloquent\Account\AccBepEntryImplementation;

use App\Repositories\Contracts\Account\AccTermLoanRepository;
use App\Repositories\Implementations\Eloquent\Account\AccTermLoanImplementation;

use App\Repositories\Contracts\Account\AccTermLoanInstallmentRepository;
use App\Repositories\Implementations\Eloquent\Account\AccTermLoanInstallmentImplementation;

use App\Repositories\Contracts\Account\AccTermLoanPaymentRepository;
use App\Repositories\Implementations\Eloquent\Account\AccTermLoanPaymentImplementation;

use App\Repositories\Contracts\Account\AccTermLoanAdjustmentRepository;
use App\Repositories\Implementations\Eloquent\Account\AccTermLoanAdjustmentImplementation;

use App\Repositories\Contracts\Account\AccCostDistributionRepository;
use App\Repositories\Implementations\Eloquent\Account\AccCostDistributionImplementation;

use App\Repositories\Contracts\Account\AccCostDistributionDtlRepository;
use App\Repositories\Implementations\Eloquent\Account\AccCostDistributionDtlImplementation;

use App\Repositories\Contracts\HRM\EmployeeRepository;
use App\Repositories\Implementations\Eloquent\HRM\EmployeeImplementation;

use App\Repositories\Contracts\HRM\RenewalEntryRepository;
use App\Repositories\Implementations\Eloquent\HRM\RenewalEntryImplementation;

use App\Repositories\Contracts\HRM\RegisterVisitorRepository;
use App\Repositories\Implementations\Eloquent\HRM\RegisterVisitorImplementation;

use App\Repositories\Contracts\HRM\AgreementRepository;
use App\Repositories\Implementations\Eloquent\HRM\AgreementImplementation;

use App\Repositories\Contracts\HRM\AgreementFileRepository;
use App\Repositories\Implementations\Eloquent\HRM\AgreementFileImplementation;

use App\Repositories\Contracts\HRM\AgreementPoRepository;
use App\Repositories\Implementations\Eloquent\HRM\AgreementPoImplementation;

use App\Repositories\Contracts\HRM\EmployeeBudgetRepository;
use App\Repositories\Implementations\Eloquent\HRM\EmployeeBudgetImplementation;

use App\Repositories\Contracts\HRM\EmployeeBudgetPositionRepository;
use App\Repositories\Implementations\Eloquent\HRM\EmployeeBudgetPositionImplementation;

use App\Repositories\Contracts\HRM\EmployeeRecruitReqRepository;
use App\Repositories\Implementations\Eloquent\HRM\EmployeeRecruitReqImplementation;

use App\Repositories\Contracts\HRM\EmployeeRecruitReqReplaceRepository;
use App\Repositories\Implementations\Eloquent\HRM\EmployeeRecruitReqReplaceImplementation;

use App\Repositories\Contracts\HRM\EmployeeRecruitReqJobRepository;
use App\Repositories\Implementations\Eloquent\HRM\EmployeeRecruitReqJobImplementation;

use App\Repositories\Contracts\Util\SupplierNatureRepository;
use App\Repositories\Implementations\Eloquent\Util\SupplierNatureImplementation;

use App\Repositories\Contracts\Util\CompanySupplierRepository;
use App\Repositories\Implementations\Eloquent\Util\CompanySupplierImplementation;

use App\Repositories\Contracts\Inventory\GeneralStore\InvPurReqRepository;
use App\Repositories\Implementations\Eloquent\Inventory\GeneralStore\InvPurReqImplementation;

use App\Repositories\Contracts\Inventory\GeneralStore\InvPurReqItemRepository;
use App\Repositories\Implementations\Eloquent\Inventory\GeneralStore\InvPurReqItemImplementation;

use App\Repositories\Contracts\Inventory\GeneralStore\InvPurReqAssetBreakdownRepository;
use App\Repositories\Implementations\Eloquent\Inventory\GeneralStore\InvPurReqAssetBreakdownImplementation;

use App\Repositories\Contracts\Inventory\GeneralStore\InvPurReqPaidRepository;
use App\Repositories\Implementations\Eloquent\Inventory\GeneralStore\InvPurReqPaidImplementation;

use App\Repositories\Contracts\Inventory\GeneralStore\InvCasReqRepository;
use App\Repositories\Implementations\Eloquent\Inventory\GeneralStore\InvCasReqImplementation;

use App\Repositories\Contracts\Inventory\GeneralStore\InvCasReqItemRepository;
use App\Repositories\Implementations\Eloquent\Inventory\GeneralStore\InvCasReqItemImplementation;

use App\Repositories\Contracts\Inventory\GeneralStore\InvCasReqPaidRepository;
use App\Repositories\Implementations\Eloquent\Inventory\GeneralStore\InvCasReqPaidImplementation;

use App\Repositories\Contracts\Inventory\InvRcvRepository;
use App\Repositories\Implementations\Eloquent\Inventory\InvRcvImplementation;

use App\Repositories\Contracts\Inventory\Yarn\InvYarnRcvRepository;
use App\Repositories\Implementations\Eloquent\Inventory\Yarn\InvYarnRcvImplementation;

use App\Repositories\Contracts\Inventory\Yarn\InvYarnItemRepository;
use App\Repositories\Implementations\Eloquent\Inventory\Yarn\InvYarnItemImplementation;

use App\Repositories\Contracts\Inventory\Yarn\InvYarnRcvItemRepository;
use App\Repositories\Implementations\Eloquent\Inventory\Yarn\InvYarnRcvItemImplementation;

use App\Repositories\Contracts\Inventory\Yarn\InvYarnRcvItemSosRepository;
use App\Repositories\Implementations\Eloquent\Inventory\Yarn\InvYarnRcvItemSosImplementation;

use App\Repositories\Contracts\Inventory\Yarn\InvYarnTransactionRepository;
use App\Repositories\Implementations\Eloquent\Inventory\Yarn\InvYarnTransactionImplementation;


//use App\Repositories\Contracts\Inventory\Yarn\RcvYarnBalanceRepository;
//use App\Repositories\Implementations\Eloquent\Inventory\Yarn\RcvYarnBalanceImplementation;

use App\Repositories\Contracts\Inventory\InvIsuRepository;
use App\Repositories\Implementations\Eloquent\Inventory\InvIsuImplementation;

use App\Repositories\Contracts\Inventory\Yarn\InvYarnIsuRepository;
use App\Repositories\Implementations\Eloquent\Inventory\Yarn\InvYarnIsuImplementation;

use App\Repositories\Contracts\Inventory\Yarn\InvYarnIsuItemRepository;
use App\Repositories\Implementations\Eloquent\Inventory\Yarn\InvYarnIsuItemImplementation;

use App\Repositories\Contracts\Inventory\Yarn\InvYarnIsuRtnRepository;
use App\Repositories\Implementations\Eloquent\Inventory\Yarn\InvYarnIsuRtnImplementation;

use App\Repositories\Contracts\Inventory\Yarn\InvYarnIsuRtnItemRepository;
use App\Repositories\Implementations\Eloquent\Inventory\Yarn\InvYarnIsuRtnItemImplementation;

use App\Repositories\Contracts\Inventory\Yarn\InvYarnPoRtnRepository;
use App\Repositories\Implementations\Eloquent\Inventory\Yarn\InvYarnPoRtnImplementation;

use App\Repositories\Contracts\Inventory\Yarn\InvYarnPoRtnItemRepository;
use App\Repositories\Implementations\Eloquent\Inventory\Yarn\InvYarnPoRtnItemImplementation;

use App\Repositories\Contracts\Inventory\Yarn\InvYarnTransOutRepository;
use App\Repositories\Implementations\Eloquent\Inventory\Yarn\InvYarnTransOutImplementation;

use App\Repositories\Contracts\Inventory\Yarn\InvYarnTransOutItemRepository;
use App\Repositories\Implementations\Eloquent\Inventory\Yarn\InvYarnTransOutItemImplementation;

use App\Repositories\Contracts\Inventory\Yarn\InvYarnTransInRepository;
use App\Repositories\Implementations\Eloquent\Inventory\Yarn\InvYarnTransInImplementation;
use App\Repositories\Contracts\Inventory\Yarn\InvYarnTransInItemRepository;
use App\Repositories\Implementations\Eloquent\Inventory\Yarn\InvYarnTransInItemImplementation;

use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemRcvRepository;
use App\Repositories\Implementations\Eloquent\Inventory\DyeChem\InvDyeChemRcvImplementation;

use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemRcvItemRepository;
use App\Repositories\Implementations\Eloquent\Inventory\DyeChem\InvDyeChemRcvItemImplementation;

use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemTransactionRepository;
use App\Repositories\Implementations\Eloquent\Inventory\DyeChem\InvDyeChemTransactionImplementation;

use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemIsuRqRepository;
use App\Repositories\Implementations\Eloquent\Inventory\DyeChem\InvDyeChemIsuRqImplementation;

use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemIsuRqItemRepository;
use App\Repositories\Implementations\Eloquent\Inventory\DyeChem\InvDyeChemIsuRqItemImplementation;

use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemIsuRepository;
use App\Repositories\Implementations\Eloquent\Inventory\DyeChem\InvDyeChemIsuImplementation;

use App\Repositories\Contracts\Inventory\DyeChem\InvDyeChemIsuItemRepository;
use App\Repositories\Implementations\Eloquent\Inventory\DyeChem\InvDyeChemIsuItemImplementation;


use App\Repositories\Contracts\Inventory\Trim\InvTrimRcvRepository;
use App\Repositories\Implementations\Eloquent\Inventory\Trim\InvTrimRcvImplementation;
use App\Repositories\Contracts\Inventory\Trim\InvTrimItemRepository;
use App\Repositories\Implementations\Eloquent\Inventory\Trim\InvTrimItemImplementation;

use App\Repositories\Contracts\Inventory\Trim\InvTrimRcvItemRepository;
use App\Repositories\Implementations\Eloquent\Inventory\Trim\InvTrimRcvItemImplementation;

use App\Repositories\Contracts\Inventory\Trim\InvTrimTransactionRepository;
use App\Repositories\Implementations\Eloquent\Inventory\Trim\InvTrimTransactionImplementation;


use App\Repositories\Contracts\Inventory\GeneralStore\InvGeneralRcvRepository;
use App\Repositories\Implementations\Eloquent\Inventory\GeneralStore\InvGeneralRcvImplementation;

use App\Repositories\Contracts\Inventory\GeneralStore\InvGeneralRcvItemRepository;
use App\Repositories\Implementations\Eloquent\Inventory\GeneralStore\InvGeneralRcvItemImplementation;
use App\Repositories\Contracts\Inventory\GeneralStore\InvGeneralTransactionRepository;
use App\Repositories\Implementations\Eloquent\Inventory\GeneralStore\InvGeneralTransactionImplementation;

use App\Repositories\Contracts\Inventory\GeneralStore\InvGeneralRcvItemDtlRepository;
use App\Repositories\Implementations\Eloquent\Inventory\GeneralStore\InvGeneralRcvItemDtlImplementation;

use App\Repositories\Contracts\Inventory\GeneralStore\InvGeneralIsuRqRepository;
use App\Repositories\Implementations\Eloquent\Inventory\GeneralStore\InvGeneralIsuRqImplementation;

use App\Repositories\Contracts\Inventory\GeneralStore\InvGeneralIsuRqItemRepository;
use App\Repositories\Implementations\Eloquent\Inventory\GeneralStore\InvGeneralIsuRqItemImplementation;

use App\Repositories\Contracts\Inventory\GeneralStore\InvGeneralIsuRepository;
use App\Repositories\Implementations\Eloquent\Inventory\GeneralStore\InvGeneralIsuImplementation;

use App\Repositories\Contracts\Inventory\GeneralStore\InvGeneralIsuItemRepository;
use App\Repositories\Implementations\Eloquent\Inventory\GeneralStore\InvGeneralIsuItemImplementation;

use App\Repositories\Contracts\Inventory\GreyFabric\InvGreyFabRcvRepository;
use App\Repositories\Implementations\Eloquent\Inventory\GreyFabric\InvGreyFabRcvImplementation;
use App\Repositories\Contracts\Inventory\GreyFabric\InvGreyFabRcvItemRepository;
use App\Repositories\Implementations\Eloquent\Inventory\GreyFabric\InvGreyFabRcvItemImplementation;
use App\Repositories\Contracts\Inventory\GreyFabric\InvGreyFabItemRepository;
use App\Repositories\Implementations\Eloquent\Inventory\GreyFabric\InvGreyFabItemImplementation;
use App\Repositories\Contracts\Inventory\GreyFabric\InvGreyFabTransactionRepository;
use App\Repositories\Implementations\Eloquent\Inventory\GreyFabric\InvGreyFabTransactionImplementation;

use App\Repositories\Contracts\Inventory\GreyFabric\InvGreyFabIsuRepository;
use App\Repositories\Implementations\Eloquent\Inventory\GreyFabric\InvGreyFabIsuImplementation;
use App\Repositories\Contracts\Inventory\GreyFabric\InvGreyFabIsuItemRepository;
use App\Repositories\Implementations\Eloquent\Inventory\GreyFabric\InvGreyFabIsuItemImplementation;

use App\Repositories\Contracts\Inventory\FinishFabric\InvFinishFabRcvRepository;
use App\Repositories\Implementations\Eloquent\Inventory\FinishFabric\InvFinishFabRcvImplementation;
use App\Repositories\Contracts\Inventory\FinishFabric\InvFinishFabRcvItemRepository;
use App\Repositories\Implementations\Eloquent\Inventory\FinishFabric\InvFinishFabRcvItemImplementation;
use App\Repositories\Contracts\Inventory\FinishFabric\InvFinishFabItemRepository;
use App\Repositories\Implementations\Eloquent\Inventory\FinishFabric\InvFinishFabItemImplementation;
use App\Repositories\Contracts\Inventory\FinishFabric\InvFinishFabTransactionRepository;
use App\Repositories\Implementations\Eloquent\Inventory\FinishFabric\InvFinishFabTransactionImplementation;

use App\Repositories\Contracts\Inventory\FinishFabric\InvFinishFabIsuRepository;
use App\Repositories\Implementations\Eloquent\Inventory\FinishFabric\InvFinishFabIsuImplementation;
use App\Repositories\Contracts\Inventory\FinishFabric\InvFinishFabIsuItemRepository;
use App\Repositories\Implementations\Eloquent\Inventory\FinishFabric\InvFinishFabIsuItemImplementation;

use App\Repositories\Contracts\Inventory\FinishFabric\InvFinishFabRcvFabricRepository;
use App\Repositories\Implementations\Eloquent\Inventory\FinishFabric\InvFinishFabRcvFabricImplementation;


use App\Repositories\Contracts\Util\BankRepository;
use App\Repositories\Implementations\Eloquent\Util\BankImplementation;

use App\Repositories\Contracts\Util\BankBranchRepository;
use App\Repositories\Implementations\Eloquent\Util\BankBranchImplementation;

use App\Repositories\Contracts\Util\BankAccountRepository;
use App\Repositories\Implementations\Eloquent\Util\BankAccountImplementation;

use App\Repositories\Contracts\FAMS\AssetAcquisitionRepository;
use App\Repositories\Implementations\Eloquent\FAMS\AssetAcquisitionImplementation;
use App\Repositories\Contracts\FAMS\AssetDepreciationRepository;
use App\Repositories\Implementations\Eloquent\FAMS\AssetDepreciationImplementation;
use App\Repositories\Contracts\FAMS\AssetQuantityCostRepository;
use App\Repositories\Implementations\Eloquent\FAMS\AssetQuantityCostImplementation;

use App\Repositories\Contracts\FAMS\AssetTechnicalFeatureRepository;
use App\Repositories\Implementations\Eloquent\FAMS\AssetTechnicalFeatureImplementation;

use App\Repositories\Contracts\FAMS\AssetTechFileUploadRepository;
use App\Repositories\Implementations\Eloquent\FAMS\AssetTechFileUploadImplementation;
use App\Repositories\Contracts\FAMS\AssetTechImageRepository;
use App\Repositories\Implementations\Eloquent\FAMS\AssetTechImageImplementation;

use App\Repositories\Contracts\FAMS\AssetMaintenanceRepository;
use App\Repositories\Implementations\Eloquent\FAMS\AssetMaintenanceImplementation;

use App\Repositories\Contracts\FAMS\AssetUtilityDetailRepository;
use App\Repositories\Implementations\Eloquent\FAMS\AssetUtilityDetailImplementation;

use App\Repositories\Contracts\FAMS\AssetManpowerRepository;
use App\Repositories\Implementations\Eloquent\FAMS\AssetManpowerImplementation;

use App\Repositories\Contracts\FAMS\AssetBreakdownRepository;
use App\Repositories\Implementations\Eloquent\FAMS\AssetBreakdownImplementation;

use App\Repositories\Contracts\FAMS\AssetRecoveryRepository;
use App\Repositories\Implementations\Eloquent\FAMS\AssetRecoveryImplementation;

use App\Repositories\Contracts\FAMS\AssetDisposalRepository;
use App\Repositories\Implementations\Eloquent\FAMS\AssetDisposalImplementation;

use App\Repositories\Contracts\FAMS\AssetServiceRepairRepository;
use App\Repositories\Implementations\Eloquent\FAMS\AssetServiceRepairImplementation;

use App\Repositories\Contracts\FAMS\AssetServiceRepairPartRepository;
use App\Repositories\Implementations\Eloquent\FAMS\AssetServiceRepairPartImplementation;

use App\Repositories\Contracts\FAMS\AssetServiceRepository;
use App\Repositories\Implementations\Eloquent\FAMS\AssetServiceImplementation;

use App\Repositories\Contracts\FAMS\AssetServiceDetailRepository;
use App\Repositories\Implementations\Eloquent\FAMS\AssetServiceDetailImplementation;

use App\Repositories\Contracts\FAMS\AssetReturnRepository;
use App\Repositories\Implementations\Eloquent\FAMS\AssetReturnImplementation;

use App\Repositories\Contracts\FAMS\AssetReturnDetailRepository;
use App\Repositories\Implementations\Eloquent\FAMS\AssetReturnDetailImplementation;

use App\Repositories\Contracts\FAMS\AssetReturnDetailCostRepository;
use App\Repositories\Implementations\Eloquent\FAMS\AssetReturnDetailCostImplementation;

use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Repositories\Implementations\Eloquent\HRM\EmployeeHRImplementation;

use App\Repositories\Contracts\HRM\EmployeeHRJobRepository;
use App\Repositories\Implementations\Eloquent\HRM\EmployeeHRJobImplementation;

use App\Repositories\Contracts\HRM\EmployeeAttendenceRepository;
use App\Repositories\Implementations\Eloquent\HRM\EmployeeAttendenceImplementation;

use App\Repositories\Contracts\HRM\EmployeeHRLeaveRepository;
use App\Repositories\Implementations\Eloquent\HRM\EmployeeHRLeaveImplementation;

use App\Repositories\Contracts\HRM\EmployeeHRStatusRepository;
use App\Repositories\Implementations\Eloquent\HRM\EmployeeHRStatusImplementation;

use App\Repositories\Contracts\HRM\EmployeeMovementRepository;
use App\Repositories\Implementations\Eloquent\HRM\EmployeeMovementImplementation;

use App\Repositories\Contracts\HRM\EmployeeMovementDtlRepository;
use App\Repositories\Implementations\Eloquent\HRM\EmployeeMovementDtlImplementation;

use App\Repositories\Contracts\HRM\EmployeeTransferRepository;
use App\Repositories\Implementations\Eloquent\HRM\EmployeeTransferImplementation;



use App\Repositories\Contracts\HRM\EmployeeJobHistoryRepository;
use App\Repositories\Implementations\Eloquent\HRM\EmployeeJobHistoryImplementation;

use App\Repositories\Contracts\HRM\EmployeePromotionRepository;
use App\Repositories\Implementations\Eloquent\HRM\EmployeePromotionImplementation;



use App\Repositories\Contracts\HRM\EmployeeToDoListRepository;
use App\Repositories\Implementations\Eloquent\HRM\EmployeeToDoListImplementation;
use App\Repositories\Contracts\HRM\EmployeeToDoListTaskRepository;
use App\Repositories\Implementations\Eloquent\HRM\EmployeeToDoListTaskImplementation;
use App\Repositories\Contracts\HRM\EmployeeToDoListTaskBarRepository;
use App\Repositories\Implementations\Eloquent\HRM\EmployeeToDoListTaskBarImplementation;

use App\Repositories\Contracts\HRM\EmployeeIncrementRepository;
use App\Repositories\Implementations\Eloquent\HRM\EmployeeIncrementImplementation;

use App\Repositories\Contracts\Util\StoreRepository;
use App\Repositories\Implementations\Eloquent\Util\StoreImplementation;

use App\Repositories\Contracts\Util\StoreItemcategoryRepository;
use App\Repositories\Implementations\Eloquent\Util\StoreItemcategoryImplementation;

use App\Repositories\Contracts\Util\SmsSetupRepository;
use App\Repositories\Implementations\Eloquent\Util\SmsSetupImplementation;

use App\Repositories\Contracts\Util\SmsSetupSmsToRepository;
use App\Repositories\Implementations\Eloquent\Util\SmsSetupSmsToImplementation;

use App\Repositories\Contracts\Util\MailSetupRepository;
use App\Repositories\Implementations\Eloquent\Util\MailSetupImplementation;

use App\Repositories\Contracts\Util\MailSetupEmailToRepository;
use App\Repositories\Implementations\Eloquent\Util\MailSetupEmailToImplementation;

use App\Repositories\Contracts\Commercial\Export\ExpPiRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Export\ExpPiImplementation;

use App\Repositories\Contracts\Commercial\Export\ExpPiOrderRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Export\ExpPiOrderImplementation;

use App\Repositories\Contracts\Commercial\Export\ExpLcScRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Export\ExpLcScImplementation;

use App\Repositories\Contracts\Commercial\Export\ExpRepLcScRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Export\ExpRepLcScImplementation;

use App\Repositories\Contracts\Commercial\Export\ExpLcScPiRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Export\ExpLcScPiImplementation;



use App\Repositories\Contracts\Commercial\Export\ExpScOrderRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Export\ExpScOrderImplementation;

use App\Repositories\Contracts\Commercial\Export\ExpLcRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Export\ExpLcImplementation;
use App\Repositories\Contracts\Commercial\Export\ExpLcTagPiRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Export\ExpLcTagPiImplementation;
use App\Repositories\Contracts\Commercial\Export\ExpRepLcRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Export\ExpRepLcImplementation;
use App\Repositories\Contracts\Commercial\Export\ExpLcOrderRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Export\ExpLcOrderImplementation;

use App\Repositories\Contracts\Commercial\Export\ExpPreCreditRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Export\ExpPreCreditImplementation;

use App\Repositories\Contracts\Commercial\Export\ExpPreCreditLcScRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Export\ExpPreCreditLcScImplementation;

use App\Repositories\Contracts\Commercial\Export\ExpInvoiceRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Export\ExpInvoiceImplementation;

use App\Repositories\Contracts\Commercial\Export\ExpInvoiceOrderRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Export\ExpInvoiceOrderImplementation;

use App\Repositories\Contracts\Commercial\Export\ExpInvoiceOrderDtlRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Export\ExpInvoiceOrderDtlImplementation;

use App\Repositories\Contracts\Commercial\Export\ExpAdvInvoiceRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Export\ExpAdvInvoiceImplementation;

use App\Repositories\Contracts\Commercial\Export\ExpAdvInvoiceOrderRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Export\ExpAdvInvoiceOrderImplementation;

use App\Repositories\Contracts\Commercial\Export\ExpAdvInvoiceOrderDtlRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Export\ExpAdvInvoiceOrderDtlImplementation;

use App\Repositories\Contracts\Commercial\Export\ExpDocSubmissionRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Export\ExpDocSubmissionImplementation;
use App\Repositories\Contracts\Commercial\Export\ExpDocSubInvoiceRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Export\ExpDocSubInvoiceImplementation;
use App\Repositories\Contracts\Commercial\Export\ExpDocSubTransectionRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Export\ExpDocSubTransectionImplementation;

use App\Repositories\Contracts\Commercial\Export\ExpProRlzRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Export\ExpProRlzImplementation;

use App\Repositories\Contracts\Commercial\Export\ExpProRlzDeductRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Export\ExpProRlzDeductImplementation;

use App\Repositories\Contracts\Commercial\Export\ExpProRlzAmountRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Export\ExpProRlzAmountImplementation;


use App\Repositories\Contracts\Commercial\Export\ExpLcScReviseRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Export\ExpLcScReviseImplementation;

use App\Repositories\Contracts\Commercial\Import\ImpLcRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Import\ImpLcImplementation;
use App\Repositories\Contracts\Commercial\Import\ImpLcPoRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Import\ImpLcPoImplementation;
use App\Repositories\Contracts\Commercial\Import\ImpBackedExpLcScRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Import\ImpBackedExpLcScImplementation;
use App\Repositories\Contracts\Commercial\Import\ImpBankChargeRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Import\ImpBankChargeImplementation;
use App\Repositories\Contracts\Commercial\Import\ImpShippingMarkRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Import\ImpShippingMarkImplementation;
use App\Repositories\Contracts\Commercial\Import\ImpLcFileRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Import\ImpLcFileImplementation;

use App\Repositories\Contracts\Commercial\Import\ImpDocAcceptRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Import\ImpDocAcceptImplementation;
use App\Repositories\Contracts\Commercial\Import\ImpAccComDetailRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Import\ImpAccComDetailImplementation;
use App\Repositories\Contracts\Commercial\Import\ImpDocAcceptMaturityRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Import\ImpDocAcceptMaturityImplementation;

use App\Repositories\Contracts\Commercial\Import\ImpDocMaturityRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Import\ImpDocMaturityImplementation;


use App\Repositories\Contracts\Commercial\Import\ImpDocMaturityDtlRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Import\ImpDocMaturityDtlImplementation;

use App\Repositories\Contracts\Util\CommercialHeadRepository;
use App\Repositories\Implementations\Eloquent\Util\CommercialHeadImplementation;

use App\Repositories\Contracts\Commercial\Import\ImpLiabilityAdjustRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Import\ImpLiabilityAdjustImplementation;
use App\Repositories\Contracts\Commercial\Import\ImpLiabilityAdjustChldRepository;
use App\Repositories\Implementations\Eloquent\Commercial\Import\ImpLiabilityAdjustChldImplementation;


use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveRefRepository;
use App\Repositories\Implementations\Eloquent\Commercial\CashIncentive\CashIncentiveRefImplementation;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveDocPrepRepository;
use App\Repositories\Implementations\Eloquent\Commercial\CashIncentive\CashIncentiveDocPrepImplementation;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveYarnBtbLcRepository;
use App\Repositories\Implementations\Eloquent\Commercial\CashIncentive\CashIncentiveYarnBtbLcImplementation;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveClaimRepository;
use App\Repositories\Implementations\Eloquent\Commercial\CashIncentive\CashIncentiveClaimImplementation;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveLoanRepository;
use App\Repositories\Implementations\Eloquent\Commercial\CashIncentive\CashIncentiveLoanImplementation;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveFileRepository;
use App\Repositories\Implementations\Eloquent\Commercial\CashIncentive\CashIncentiveFileImplementation;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveFileQueryRepository;
use App\Repositories\Implementations\Eloquent\Commercial\CashIncentive\CashIncentiveFileQueryImplementation;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveRealizeRepository;
use App\Repositories\Implementations\Eloquent\Commercial\CashIncentive\CashIncentiveRealizeImplementation;
use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveRealizeRcvRepository;
use App\Repositories\Implementations\Eloquent\Commercial\CashIncentive\CashIncentiveRealizeRcvImplementation;


use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveAdvRepository;
use App\Repositories\Implementations\Eloquent\Commercial\CashIncentive\CashIncentiveAdvImplementation;

use App\Repositories\Contracts\Commercial\CashIncentive\CashIncentiveAdvClaimRepository;
use App\Repositories\Implementations\Eloquent\Commercial\CashIncentive\CashIncentiveAdvClaimImplementation;

use App\Repositories\Contracts\Commercial\LocalExport\LocalExpPiRepository;
use App\Repositories\Implementations\Eloquent\Commercial\LocalExport\LocalExpPiImplementation;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpPiOrderRepository;
use App\Repositories\Implementations\Eloquent\Commercial\LocalExport\LocalExpPiOrderImplementation;

use App\Repositories\Contracts\Commercial\LocalExport\LocalExpLcRepository;
use App\Repositories\Implementations\Eloquent\Commercial\LocalExport\LocalExpLcImplementation;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpLcTagPiRepository;
use App\Repositories\Implementations\Eloquent\Commercial\LocalExport\LocalExpLcTagPiImplementation;

use App\Repositories\Contracts\Commercial\LocalExport\LocalExpInvoiceRepository;
use App\Repositories\Implementations\Eloquent\Commercial\LocalExport\LocalExpInvoiceImplementation;

use App\Repositories\Contracts\Commercial\LocalExport\LocalExpInvoiceOrderRepository;
use App\Repositories\Implementations\Eloquent\Commercial\LocalExport\LocalExpInvoiceOrderImplementation;


use App\Repositories\Contracts\Commercial\LocalExport\LocalExpDocSubAcceptRepository;
use App\Repositories\Implementations\Eloquent\Commercial\LocalExport\LocalExpDocSubAcceptImplementation;

use App\Repositories\Contracts\Commercial\LocalExport\LocalExpDocSubInvoiceRepository;
use App\Repositories\Implementations\Eloquent\Commercial\LocalExport\LocalExpDocSubInvoiceImplementation;

use App\Repositories\Contracts\Commercial\LocalExport\LocalExpDocSubBankRepository;
use App\Repositories\Implementations\Eloquent\Commercial\LocalExport\LocalExpDocSubBankImplementation;

use App\Repositories\Contracts\Commercial\LocalExport\LocalExpDocSubTransRepository;
use App\Repositories\Implementations\Eloquent\Commercial\LocalExport\LocalExpDocSubTransImplementation;

use App\Repositories\Contracts\Commercial\LocalExport\LocalExpProRlzRepository;
use App\Repositories\Implementations\Eloquent\Commercial\LocalExport\LocalExpProRlzImplementation;

use App\Repositories\Contracts\Commercial\LocalExport\LocalExpProRlzDeductRepository;
use App\Repositories\Implementations\Eloquent\Commercial\LocalExport\LocalExpProRlzDeductImplementation;

use App\Repositories\Contracts\Commercial\LocalExport\LocalExpProRlzAmountRepository;
use App\Repositories\Implementations\Eloquent\Commercial\LocalExport\LocalExpProRlzAmountImplementation;

use App\Repositories\Contracts\Subcontract\Inbound\SubInbMarketingRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Inbound\SubInbMarketingImplementation;

use App\Repositories\Contracts\Subcontract\Inbound\SubInbEventRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Inbound\SubInbEventImplementation;

use App\Repositories\Contracts\Subcontract\Inbound\SubInbServiceRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Inbound\SubInbServiceImplementation;

use App\Repositories\Contracts\Subcontract\Inbound\SubInbImageRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Inbound\SubInbImageImplementation;


use App\Repositories\Contracts\Subcontract\Inbound\SubInbOrderRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Inbound\SubInbOrderImplementation;
use App\Repositories\Contracts\Subcontract\Inbound\SubInbOrderProductRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Inbound\SubInbOrderProductImplementation;

use App\Repositories\Contracts\Subcontract\Kniting\SoKnitRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Kniting\SoKnitImplementation;

use App\Repositories\Contracts\Subcontract\Kniting\SoKnitRefRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Kniting\SoKnitRefImplementation;

use App\Repositories\Contracts\Subcontract\Kniting\SoKnitPoRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Kniting\SoKnitPoImplementation;

use App\Repositories\Contracts\Subcontract\Kniting\SoKnitPoItemRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Kniting\SoKnitPoItemImplementation;

use App\Repositories\Contracts\Subcontract\Kniting\SoKnitItemRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Kniting\SoKnitItemImplementation;

use App\Repositories\Contracts\Subcontract\Kniting\SoKnitFileRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Kniting\SoKnitFileImplementation;

use App\Repositories\Contracts\Subcontract\Kniting\SoKnitYarnRcvRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Kniting\SoKnitYarnRcvImplementation;

use App\Repositories\Contracts\Subcontract\Kniting\SoKnitYarnRtnRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Kniting\SoKnitYarnRtnImplementation;

use App\Repositories\Contracts\Subcontract\Kniting\SoKnitYarnRcvItemRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Kniting\SoKnitYarnRcvItemImplementation;

use App\Repositories\Contracts\Subcontract\Kniting\SoKnitYarnRtnItemRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Kniting\SoKnitYarnRtnItemImplementation;

use App\Repositories\Contracts\Subcontract\Kniting\SoKnitDlvRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Kniting\SoKnitDlvImplementation;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitDlvItemRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Kniting\SoKnitDlvItemImplementation;

use App\Repositories\Contracts\Subcontract\Kniting\SoKnitDlvItemYarnRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Kniting\SoKnitDlvItemYarnImplementation;

use App\Repositories\Contracts\Subcontract\Kniting\SoKnitTargetRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Kniting\SoKnitTargetImplementation;


use App\Repositories\Contracts\Subcontract\Dyeing\PlDyeingRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Dyeing\PlDyeingImplementation;
use App\Repositories\Contracts\Subcontract\Dyeing\PlDyeingItemRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Dyeing\PlDyeingItemImplementation;
use App\Repositories\Contracts\Subcontract\Dyeing\PlDyeingItemQtyRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Dyeing\PlDyeingItemQtyImplementation;

use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Dyeing\SoDyeingImplementation;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingRefRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Dyeing\SoDyeingRefImplementation;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingPoRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Dyeing\SoDyeingPoImplementation;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingPoItemRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Dyeing\SoDyeingPoItemImplementation;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingItemRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Dyeing\SoDyeingItemImplementation;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingFileRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Dyeing\SoDyeingFileImplementation;

use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingFabricRcvRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Dyeing\SoDyeingFabricRcvImplementation;

use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingFabricRcvItemRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Dyeing\SoDyeingFabricRcvItemImplementation;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingFabricRcvRolRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Dyeing\SoDyeingFabricRcvRolImplementation;

use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingDlvRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Dyeing\SoDyeingDlvImplementation;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingDlvItemRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Dyeing\SoDyeingDlvItemImplementation;

use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingTargetRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Dyeing\SoDyeingTargetImplementation;

use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingFabricRtnRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Dyeing\SoDyeingFabricRtnImplementation;

use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingFabricRtnItemRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Dyeing\SoDyeingFabricRtnItemImplementation;

use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingBomRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Dyeing\SoDyeingBomImplementation;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingBomFabricRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Dyeing\SoDyeingBomFabricImplementation;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingBomFabricItemRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Dyeing\SoDyeingBomFabricItemImplementation;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingBomOverheadRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Dyeing\SoDyeingBomOverheadImplementation;

use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Dyeing\SoDyeingMktCostImplementation;

use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostFabRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Dyeing\SoDyeingMktCostFabImplementation;

use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostFabItemRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Dyeing\SoDyeingMktCostFabItemImplementation;

use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostFabOvhedRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Dyeing\SoDyeingMktCostFabOvhedImplementation;

use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostFabFinRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Dyeing\SoDyeingMktCostFabFinImplementation;

use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostQpriceRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Dyeing\SoDyeingMktCostQpriceImplementation;

use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostQpricedtlRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Dyeing\SoDyeingMktCostQpricedtlImplementation;

use App\Repositories\Contracts\Subcontract\AOP\SoAopRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\AOP\SoAopImplementation;
use App\Repositories\Contracts\Subcontract\AOP\SoAopRefRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\AOP\SoAopRefImplementation;
use App\Repositories\Contracts\Subcontract\AOP\SoAopPoRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\AOP\SoAopPoImplementation;
use App\Repositories\Contracts\Subcontract\AOP\SoAopPoItemRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\AOP\SoAopPoItemImplementation;
use App\Repositories\Contracts\Subcontract\AOP\SoAopItemRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\AOP\SoAopItemImplementation;
use App\Repositories\Contracts\Subcontract\AOP\SoAopFileRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\AOP\SoAopFileImplementation;

use App\Repositories\Contracts\Subcontract\AOP\SoAopFabricRcvRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\AOP\SoAopFabricRcvImplementation;

use App\Repositories\Contracts\Subcontract\AOP\SoAopFabricRcvItemRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\AOP\SoAopFabricRcvItemImplementation;

use App\Repositories\Contracts\Subcontract\AOP\SoAopFabricRcvRolRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\AOP\SoAopFabricRcvRolImplementation;

use App\Repositories\Contracts\Subcontract\AOP\SoAopFabricIsuRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\AOP\SoAopFabricIsuImplementation;
use App\Repositories\Contracts\Subcontract\AOP\SoAopFabricIsuItemRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\AOP\SoAopFabricIsuItemImplementation;

use App\Repositories\Contracts\Subcontract\AOP\SoAopDlvRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\AOP\SoAopDlvImplementation;
use App\Repositories\Contracts\Subcontract\AOP\SoAopDlvItemRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\AOP\SoAopDlvItemImplementation;

use App\Repositories\Contracts\Subcontract\AOP\SoAopTargetRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\AOP\SoAopTargetImplementation;

use App\Repositories\Contracts\Subcontract\AOP\SoAopFabricRtnRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\AOP\SoAopFabricRtnImplementation;

use App\Repositories\Contracts\Subcontract\AOP\SoAopFabricRtnItemRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\AOP\SoAopFabricRtnItemImplementation;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Embelishment\SoEmbImplementation;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbRefRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Embelishment\SoEmbRefImplementation;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPoRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Embelishment\SoEmbPoImplementation;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPoItemRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Embelishment\SoEmbPoItemImplementation;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbItemRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Embelishment\SoEmbItemImplementation;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbFileRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Embelishment\SoEmbFileImplementation;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbTargetRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Embelishment\SoEmbTargetImplementation;


use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbCutpanelRcvRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Embelishment\SoEmbCutpanelRcvImplementation;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbCutpanelRcvOrderRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Embelishment\SoEmbCutpanelRcvOrderImplementation;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbCutpanelRcvQtyRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Embelishment\SoEmbCutpanelRcvQtyImplementation;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintDlvRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Embelishment\SoEmbPrintDlvImplementation;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintDlvItemRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Embelishment\SoEmbPrintDlvItemImplementation;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbMktCostRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Embelishment\SoEmbMktCostImplementation;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbMktCostParamRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Embelishment\SoEmbMktCostParamImplementation;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbMktCostParamItemRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Embelishment\SoEmbMktCostParamItemImplementation;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbMktCostParamFinRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Embelishment\SoEmbMktCostParamFinImplementation;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbMktCostQpriceRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Embelishment\SoEmbMktCostQpriceImplementation;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbMktCostQpricedtlRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Embelishment\SoEmbMktCostQpricedtlImplementation;

use App\Repositories\Contracts\Subcontract\Kniting\PlKnitRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Kniting\PlKnitImplementation;


use App\Repositories\Contracts\Subcontract\Kniting\PlKnitItemRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Kniting\PlKnitItemImplementation;


use App\Repositories\Contracts\Subcontract\Kniting\PlKnitItemQtyRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Kniting\PlKnitItemQtyImplementation;

use App\Repositories\Contracts\Subcontract\Kniting\PlKnitItemStripeRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Kniting\PlKnitItemStripeImplementation;

use App\Repositories\Contracts\Subcontract\Kniting\PlKnitItemNarrowfabricRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Kniting\PlKnitItemNarrowfabricImplementation;

use App\Repositories\Contracts\Subcontract\Kniting\RqYarnRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Kniting\RqYarnImplementation;
use App\Repositories\Contracts\Subcontract\Kniting\RqYarnFabricationRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Kniting\RqYarnFabricationImplementation;
use App\Repositories\Contracts\Subcontract\Kniting\RqYarnItemRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Kniting\RqYarnItemImplementation;





use App\Repositories\Contracts\Subcontract\Inbound\SubInbOrderFileRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Inbound\SubInbOrderFileImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtCartonEntryRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtCartonEntryImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtCartonDetailRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtCartonDetailImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtExFactoryRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtExFactoryImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtExFactoryQtyRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtExFactoryQtyImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtCartonDetailQtyRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtCartonDetailQtyImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtIronRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtIronImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtIronOrderRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtIronOrderImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtIronQtyRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtIronQtyImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtPolyRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtPolyImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtPolyOrderRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtPolyOrderImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtPolyQtyRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtPolyQtyImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtSewingRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtSewingImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtSewingOrderRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtSewingOrderImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtSewingQtyRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtSewingQtyImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtSewingLineRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtSewingLineImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtSewingLineOrderRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtSewingLineOrderImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtSewingLineQtyRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtSewingLineQtyImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtCuttingRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtCuttingImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtCuttingOrderRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtCuttingOrderImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtCuttingQtyRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtCuttingQtyImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtDlvInputRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtDlvInputImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtDlvInputOrderRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtDlvInputOrderImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtDlvInputQtyRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtDlvInputQtyImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtRcvInputRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtRcvInputImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtRcvInputOrderRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtRcvInputOrderImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtRcvInputQtyRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtRcvInputQtyImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtDlvPrintRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtDlvPrintImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtDlvPrintOrderRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtDlvPrintOrderImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtDlvPrintQtyRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtDlvPrintQtyImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtDlvToEmbRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtDlvToEmbImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtDlvToEmbOrderRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtDlvToEmbOrderImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtDlvToEmbQtyRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtDlvToEmbQtyImplementation;

/******* ProdGmtDlvToEmb Start  End********/

/******* ProdGmtPrintReceive Start ********/

use App\Repositories\Contracts\Production\Garments\ProdGmtPrintRcvRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtPrintRcvImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtPrintRcvOrderRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtPrintRcvOrderImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtPrintRcvQtyRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtPrintRcvQtyImplementation;

/******* ProdGmtPrintRcv Start  End********/

/******* ProdGmtEmbRcv Start ********/

use App\Repositories\Contracts\Production\Garments\ProdGmtEmbRcvRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtEmbRcvImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtEmbRcvOrderRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtEmbRcvOrderImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtEmbRcvQtyRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtEmbRcvQtyImplementation;

/******* ProdGmtEmbRcv Start  End********/

use App\Repositories\Contracts\Production\Garments\ProdGmtInspectionRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtInspectionImplementation;

use App\Repositories\Contracts\Production\Garments\ProdGmtInspectionOrderRepository;
use App\Repositories\Implementations\Eloquent\Production\Garments\ProdGmtInspectionOrderImplementation;

use App\Repositories\Contracts\Workstudy\WstudyLineSetupRepository;
use App\Repositories\Implementations\Eloquent\Workstudy\WstudyLineSetupImplementation;

use App\Repositories\Contracts\Workstudy\WstudyLineSetupDtlRepository;
use App\Repositories\Implementations\Eloquent\Workstudy\WstudyLineSetupDtlImplementation;

use App\Repositories\Contracts\Workstudy\WstudyLineSetupLineRepository;
use App\Repositories\Implementations\Eloquent\Workstudy\WstudyLineSetupLineImplementation;

use App\Repositories\Contracts\Workstudy\WstudyLineSetupDtlOrdRepository;
use App\Repositories\Implementations\Eloquent\Workstudy\WstudyLineSetupDtlOrdImplementation;

use App\Repositories\Contracts\Workstudy\WstudyLineSetupMinAdjRepository;
use App\Repositories\Implementations\Eloquent\Workstudy\WstudyLineSetupMinAdjImplementation;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintMcRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Embelishment\SoEmbPrintMcImplementation;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintMcDtlRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Embelishment\SoEmbPrintMcDtlImplementation;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintMcDtlOrdRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Embelishment\SoEmbPrintMcDtlOrdImplementation;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintMcDtlMinajRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Embelishment\SoEmbPrintMcDtlMinajImplementation;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintEntryRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Embelishment\SoEmbPrintEntryImplementation;
use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintEntOrderRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Embelishment\SoEmbPrintEntOrderImplementation;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintQcRepository;

use App\Repositories\Implementations\Eloquent\Subcontract\Embelishment\SoEmbPrintQcImplementation;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintQcDtlRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Embelishment\SoEmbPrintQcDtlImplementation;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintQcDtlDeftRepository;
use App\Repositories\Implementations\Eloquent\Subcontract\Embelishment\SoEmbPrintQcDtlDeftImplementation;

use App\Repositories\Contracts\Production\Embelishment\ProductionRepository;
use App\Repositories\Implementations\Eloquent\Production\Embelishment\ProductionImplementation;

use App\Repositories\Contracts\Planing\TnaOrdRepository;
use App\Repositories\Implementations\Eloquent\Planing\TnaOrdImplementation;

use App\Repositories\Contracts\Planing\TnaProgressDelayRepository;
use App\Repositories\Implementations\Eloquent\Planing\TnaProgressDelayImplementation;

use App\Repositories\Contracts\Planing\TnaProgressDelayDtlRepository;
use App\Repositories\Implementations\Eloquent\Planing\TnaProgressDelayDtlImplementation;

use App\Repositories\Contracts\Planing\TnaTemplateRepository;
use App\Repositories\Implementations\Eloquent\Planing\TnaTemplateImplementation;

use App\Repositories\Contracts\Planing\TnaTemplateDtlRepository;
use App\Repositories\Implementations\Eloquent\Planing\TnaTemplateDtlImplementation;

use App\Repositories\Contracts\Sample\Costing\SmpCostRepository;
use App\Repositories\Implementations\Eloquent\Sample\Costing\SmpCostImplementation;

use App\Repositories\Contracts\Sample\Costing\SmpCostFabricRepository;
use App\Repositories\Implementations\Eloquent\Sample\Costing\SmpCostFabricImplementation;

use App\Repositories\Contracts\Sample\Costing\SmpCostFabricConRepository;
use App\Repositories\Implementations\Eloquent\Sample\Costing\SmpCostFabricConImplementation;

use App\Repositories\Contracts\Sample\Costing\SmpCostYarnRepository;
use App\Repositories\Implementations\Eloquent\Sample\Costing\SmpCostYarnImplementation;

use App\Repositories\Contracts\Sample\Costing\SmpCostFabricProdRepository;
use App\Repositories\Implementations\Eloquent\Sample\Costing\SmpCostFabricProdImplementation;

use App\Repositories\Contracts\Sample\Costing\SmpCostFabricProdConRepository;
use App\Repositories\Implementations\Eloquent\Sample\Costing\SmpCostFabricProdConImplementation;

use App\Repositories\Contracts\Sample\Costing\SmpCostTrimRepository;
use App\Repositories\Implementations\Eloquent\Sample\Costing\SmpCostTrimImplementation;

use App\Repositories\Contracts\Sample\Costing\SmpCostTrimConRepository;
use App\Repositories\Implementations\Eloquent\Sample\Costing\SmpCostTrimConImplementation;

use App\Repositories\Contracts\Sample\Costing\SmpCostEmbRepository;
use App\Repositories\Implementations\Eloquent\Sample\Costing\SmpCostEmbImplementation;

use App\Repositories\Contracts\Sample\Costing\SmpCostEmbConRepository;
use App\Repositories\Implementations\Eloquent\Sample\Costing\SmpCostEmbConImplementation;

use App\Repositories\Contracts\Sample\Costing\SmpCostCmRepository;
use App\Repositories\Implementations\Eloquent\Sample\Costing\SmpCostCmImplementation;

use App\Repositories\Contracts\Production\Kniting\ProdKnitRepository;
use App\Repositories\Implementations\Eloquent\Production\Kniting\ProdKnitImplementation;

use App\Repositories\Contracts\Production\Kniting\ProdKnitItemRepository;
use App\Repositories\Implementations\Eloquent\Production\Kniting\ProdKnitItemImplementation;

use App\Repositories\Contracts\Production\Kniting\ProdKnitItemRollRepository;
use App\Repositories\Implementations\Eloquent\Production\Kniting\ProdKnitItemRollImplementation;
use App\Repositories\Contracts\Production\Kniting\ProdKnitItemYarnRepository;
use App\Repositories\Implementations\Eloquent\Production\Kniting\ProdKnitItemYarnImplementation;

use App\Repositories\Contracts\Production\Kniting\ProdKnitRcvByQcRepository;
use App\Repositories\Implementations\Eloquent\Production\Kniting\ProdKnitRcvByQcImplementation;
use App\Repositories\Contracts\Production\Kniting\ProdKnitQcRepository;
use App\Repositories\Implementations\Eloquent\Production\Kniting\ProdKnitQcImplementation;

use App\Repositories\Contracts\Production\Kniting\ProdKnitDlvRepository;
use App\Repositories\Implementations\Eloquent\Production\Kniting\ProdKnitDlvImplementation;
use App\Repositories\Contracts\Production\Kniting\ProdKnitDlvRollRepository;
use App\Repositories\Implementations\Eloquent\Production\Kniting\ProdKnitDlvRollImplementation;


use App\Repositories\Contracts\Production\Dyeing\ProdBatchRepository;
use App\Repositories\Implementations\Eloquent\Production\Dyeing\ProdBatchImplementation;

use App\Repositories\Contracts\Production\Dyeing\ProdBatchRollRepository;
use App\Repositories\Implementations\Eloquent\Production\Dyeing\ProdBatchRollImplementation;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchTrimRepository;
use App\Repositories\Implementations\Eloquent\Production\Dyeing\ProdBatchTrimImplementation;

use App\Repositories\Contracts\Production\Dyeing\ProdBatchProcessRepository;
use App\Repositories\Implementations\Eloquent\Production\Dyeing\ProdBatchProcessImplementation;

use App\Repositories\Contracts\Production\Dyeing\ProdBatchFinishProgRepository;
use App\Repositories\Implementations\Eloquent\Production\Dyeing\ProdBatchFinishProgImplementation;

use App\Repositories\Contracts\Production\Dyeing\ProdBatchFinishProgChemRepository;
use App\Repositories\Implementations\Eloquent\Production\Dyeing\ProdBatchFinishProgChemImplementation;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchFinishProgRollRepository;
use App\Repositories\Implementations\Eloquent\Production\Dyeing\ProdBatchFinishProgRollImplementation;

use App\Repositories\Contracts\Production\Dyeing\ProdBatchFinishQcRepository;
use App\Repositories\Implementations\Eloquent\Production\Dyeing\ProdBatchFinishQcImplementation;
use App\Repositories\Contracts\Production\Dyeing\ProdBatchFinishQcRollRepository;
use App\Repositories\Implementations\Eloquent\Production\Dyeing\ProdBatchFinishQcRollImplementation;

use App\Repositories\Contracts\Production\Dyeing\ProdFinishDlvRepository;
use App\Repositories\Implementations\Eloquent\Production\Dyeing\ProdFinishDlvImplementation;

use App\Repositories\Contracts\Production\Dyeing\ProdFinishDlvRollRepository;
use App\Repositories\Implementations\Eloquent\Production\Dyeing\ProdFinishDlvRollImplementation;

use App\Repositories\Contracts\Production\Dyeing\ProdFinishMcSetupRepository;
use App\Repositories\Implementations\Eloquent\Production\Dyeing\ProdFinishMcSetupImplementation;

use App\Repositories\Contracts\Production\Dyeing\ProdFinishMcDateRepository;
use App\Repositories\Implementations\Eloquent\Production\Dyeing\ProdFinishMcDateImplementation;

use App\Repositories\Contracts\Production\Dyeing\ProdFinishMcParameterRepository;
use App\Repositories\Implementations\Eloquent\Production\Dyeing\ProdFinishMcParameterImplementation;

use App\Repositories\Contracts\Production\Dyeing\ProdFinishQcBillItemRepository;
use App\Repositories\Implementations\Eloquent\Production\Dyeing\ProdFinishQcBillItemImplementation;

use App\Repositories\Contracts\Production\AOP\ProdAopBatchRepository;
use App\Repositories\Implementations\Eloquent\Production\AOP\ProdAopBatchImplementation;

use App\Repositories\Contracts\Production\AOP\ProdAopBatchRollRepository;
use App\Repositories\Implementations\Eloquent\Production\AOP\ProdAopBatchRollImplementation;
use App\Repositories\Contracts\Production\AOP\ProdAopBatchProcessRepository;
use App\Repositories\Implementations\Eloquent\Production\AOP\ProdAopBatchProcessImplementation;

use App\Repositories\Contracts\Production\AOP\ProdAopMcSetupRepository;
use App\Repositories\Implementations\Eloquent\Production\AOP\ProdAopMcSetupImplementation;

use App\Repositories\Contracts\Production\AOP\ProdAopMcDateRepository;
use App\Repositories\Implementations\Eloquent\Production\AOP\ProdAopMcDateImplementation;

use App\Repositories\Contracts\Production\AOP\ProdAopMcParameterRepository;
use App\Repositories\Implementations\Eloquent\Production\AOP\ProdAopMcParameterImplementation;

use App\Repositories\Contracts\Production\AOP\ProdFinishAopMcSetupRepository;
use App\Repositories\Implementations\Eloquent\Production\AOP\ProdFinishAopMcSetupImplementation;

use App\Repositories\Contracts\Production\AOP\ProdFinishAopMcDateRepository;
use App\Repositories\Implementations\Eloquent\Production\AOP\ProdFinishAopMcDateImplementation;

use App\Repositories\Contracts\Production\AOP\ProdFinishAopMcParameterRepository;
use App\Repositories\Implementations\Eloquent\Production\AOP\ProdFinishAopMcParameterImplementation;


use App\Repositories\Contracts\ShowRoom\SrmProductReceiveRepository;
use App\Repositories\Implementations\Eloquent\ShowRoom\SrmProductReceiveImplementation;
use App\Repositories\Contracts\ShowRoom\SrmProductReceiveDtlRepository;
use App\Repositories\Implementations\Eloquent\ShowRoom\SrmProductReceiveDtlImplementation;

use App\Repositories\Contracts\ShowRoom\SrmProductSaleRepository;
use App\Repositories\Implementations\Eloquent\ShowRoom\SrmProductSaleImplementation;
use App\Repositories\Contracts\ShowRoom\SrmProductScanRepository;
use App\Repositories\Implementations\Eloquent\ShowRoom\SrmProductScanImplementation;

//use App\Repositories\Contracts\Production\Kniting\ProdKnitRefRepository;
//use App\Repositories\Implementations\Eloquent\Production\Kniting\ProdKnitRefImplementation;

//use App\Repositories\Contracts\Production\Kniting\ProdKnitRefPlItemRepository;
//use App\Repositories\Implementations\Eloquent\Production\Kniting\ProdKnitRefPlItemImplementation;

//use App\Repositories\Contracts\Production\Kniting\ProdKnitRefPoItemRepository;
//use App\Repositories\Implementations\Eloquent\Production\Kniting\ProdKnitRefPoItemImplementation;

//use App\Repositories\Contracts\Production\Kniting\ProdKnitRefItemRepository;
//use App\Repositories\Implementations\Eloquent\Production\Kniting\ProdKnitRefItemImplementation;
use App\Repositories\Contracts\GateEntry\GateEntryRepository;
use App\Repositories\Implementations\Eloquent\GateEntry\GateEntryImplementation;

use App\Repositories\Contracts\GateEntry\GateEntryItemRepository;
use App\Repositories\Implementations\Eloquent\GateEntry\GateEntryItemImplementation;
use App\Repositories\Contracts\GateEntry\GateOutRepository;
use App\Repositories\Implementations\Eloquent\GateEntry\GateOutImplementation;
use App\Repositories\Contracts\GateEntry\GateOutItemRepository;
use App\Repositories\Implementations\Eloquent\GateEntry\GateOutItemImplementation;

use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvOrderRepository;
use App\Repositories\Implementations\Eloquent\JhuteSale\JhuteSaleDlvOrderImplementation;

use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvOrderItemRepository;
use App\Repositories\Implementations\Eloquent\JhuteSale\JhuteSaleDlvOrderItemImplementation;

use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvOrderPaymentRepository;
use App\Repositories\Implementations\Eloquent\JhuteSale\JhuteSaleDlvOrderPaymentImplementation;

use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvOrderQtyRepository;
use App\Repositories\Implementations\Eloquent\JhuteSale\JhuteSaleDlvOrderQtyImplementation;

use App\Repositories\Contracts\JhuteSale\JhuteStockRepository;
use App\Repositories\Implementations\Eloquent\JhuteSale\JhuteStockImplementation;

use App\Repositories\Contracts\JhuteSale\JhuteStockItemRepository;
use App\Repositories\Implementations\Eloquent\JhuteSale\JhuteStockItemImplementation;

use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvRepository;
use App\Repositories\Implementations\Eloquent\JhuteSale\JhuteSaleDlvImplementation;

use App\Repositories\Contracts\JhuteSale\JhuteSaleDlvItemRepository;
use App\Repositories\Implementations\Eloquent\JhuteSale\JhuteSaleDlvItemImplementation;

use App\Repositories\Contracts\Approval\ApprovalCommentHistoryRepository;
use App\Repositories\Implementations\Eloquent\Approval\ApprovalCommentHistoryImplementation;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		Schema::defaultStringLength(191);
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton(MenuRepository::class, MenuImplementation::class);
		$this->app->singleton(PermissionRepository::class, PermissionImplementation::class);
		$this->app->singleton(RoleRepository::class, RoleImplementation::class);
		$this->app->singleton(UserRepository::class, UserImplementation::class);
		$this->app->singleton(CgroupRepository::class, CgroupImplementation::class);
		$this->app->singleton(CompanyRepository::class, CompanyImplementation::class);
		$this->app->singleton(RegionRepository::class, RegionImplementation::class);
		$this->app->singleton(LocationRepository::class, LocationImplementation::class);
		$this->app->singleton(DivisionRepository::class, DivisionImplementation::class);
		$this->app->singleton(FloorRepository::class, FloorImplementation::class);
		$this->app->singleton(DepartmentRepository::class, DepartmentImplementation::class);
		$this->app->singleton(DepartmentFloorRepository::class, DepartmentFloorImplementation::class);
		$this->app->singleton(SectionRepository::class, SectionImplementation::class);
		$this->app->singleton(FloorSectionRepository::class, FloorSectionImplementation::class);
		$this->app->singleton(SubsectionRepository::class, SubsectionImplementation::class);
		$this->app->singleton(CompanySubsectionRepository::class, CompanySubsectionImplementation::class);
		$this->app->singleton(ProfitcenterRepository::class, ProfitcenterImplementation::class);
		$this->app->singleton(CompanyProfitcenterRepository::class, CompanyProfitcenterImplementation::class);
		$this->app->singleton(CountryRepository::class, CountryImplementation::class);
		$this->app->singleton(CurrencyRepository::class, CurrencyImplementation::class);
		$this->app->singleton(UomRepository::class, UomImplementation::class);
		$this->app->singleton(UomconversionRepository::class, UomconversionImplementation::class);
		$this->app->singleton(ExchangerateRepository::class, ExchangerateImplementation::class);
		$this->app->singleton(ItemcategoryRepository::class, ItemcategoryImplementation::class);
		$this->app->singleton(ItemclassRepository::class, ItemclassImplementation::class);
		$this->app->singleton(ItemclassProfitcenterRepository::class, ItemclassProfitcenterImplementation::class);
		$this->app->singleton(TeamRepository::class, TeamImplementation::class);
		$this->app->singleton(TeammemberRepository::class, TeammemberImplementation::class);
		$this->app->singleton(GmtssampleRepository::class, GmtssampleImplementation::class);
		$this->app->singleton(SupplierRepository::class, SupplierImplementation::class);
		$this->app->singleton(BuyerRepository::class, BuyerImplementation::class);
		$this->app->singleton(BuyerNatureRepository::class, BuyerNatureImplementation::class);
		$this->app->singleton(CompanyBuyerRepository::class, CompanyBuyerImplementation::class);

		$this->app->singleton(RenewalEntryRepository::class, RenewalEntryImplementation::class);
		$this->app->singleton(RenewalItemRepository::class, RenewalItemImplementation::class);
		$this->app->singleton(RenewalItemDocRepository::class, RenewalItemDocImplementation::class);

		$this->app->singleton(CompanyUserRepository::class, CompanyUserImplementation::class);
		$this->app->singleton(BuyerUserRepository::class, BuyerUserImplementation::class);
		$this->app->singleton(SupplierUserRepository::class, SupplierUserImplementation::class);
		$this->app->singleton(PermissionUserRepository::class, PermissionUserImplementation::class);
		$this->app->singleton(ContactNatureRepository::class, ContactNatureImplementation::class);
		$this->app->singleton(CompositionRepository::class, CompositionImplementation::class);
		$this->app->singleton(CompositionItemcategoryRepository::class, CompositionItemcategoryImplementation::class);
		$this->app->singleton(YarncountRepository::class, YarncountImplementation::class);
		$this->app->singleton(ConstructionRepository::class, ConstructionImplementation::class);
		$this->app->singleton(ColorrangeRepository::class, ColorrangeImplementation::class);
		$this->app->singleton(FabricprocesslossRepository::class, FabricprocesslossImplementation::class);
		$this->app->singleton(GmtspartRepository::class, GmtspartImplementation::class);
		$this->app->singleton(GmtspartMenuRepository::class, GmtspartMenuImplementation::class);
		$this->app->singleton(ColorRepository::class, ColorImplementation::class);
		$this->app->singleton(BuyerColorRepository::class, BuyerColorImplementation::class);
		$this->app->singleton(SizeRepository::class, SizeImplementation::class);
		$this->app->singleton(BuyerSizeRepository::class, BuyerSizeImplementation::class);
		$this->app->singleton(ProductionProcessRepository::class, ProductionProcessImplementation::class);
		$this->app->singleton(ProductDefectRepository::class, ProductDefectImplementation::class);
		$this->app->singleton(ProductdepartmentRepository::class, ProductdepartmentImplementation::class);
		$this->app->singleton(SeasonRepository::class, SeasonImplementation::class);
		$this->app->singleton(YarntypeRepository::class, YarntypeImplementation::class);
		$this->app->singleton(ResourceRepository::class, ResourceImplementation::class);
		$this->app->singleton(AttachmentRepository::class, AttachmentImplementation::class);
		$this->app->singleton(OperationRepository::class, OperationImplementation::class);
		$this->app->singleton(AttachmentOperationRepository::class, AttachmentOperationImplementation::class);
		$this->app->singleton(IncentiveRepository::class, IncentiveImplementation::class);
		$this->app->singleton(SmvChartRepository::class, SmvChartImplementation::class);
		$this->app->singleton(KnitChargeRepository::class, KnitChargeImplementation::class);
		$this->app->singleton(KnitChargeSupplierRepository::class, KnitChargeSupplierImplementation::class);
		$this->app->singleton(BuyerKnitChargeRepository::class, BuyerKnitChargeImplementation::class);

		$this->app->singleton(AopChargeRepository::class, AopChargeImplementation::class);
		$this->app->singleton(AopSupplierChargeRepository::class, AopSupplierChargeImplementation::class);
		$this->app->singleton(AopBuyerChargeRepository::class, AopBuyerChargeImplementation::class);

		$this->app->singleton(DyingChargeRepository::class, DyingChargeImplementation::class);
		$this->app->singleton(DyingChargeRepository::class, DyingChargeImplementation::class);
		$this->app->singleton(DyingChargeSupplierRepository::class, DyingChargeSupplierImplementation::class);
		$this->app->singleton(BuyerDyingChargeRepository::class, BuyerDyingChargeImplementation::class);
		$this->app->singleton(YarnDyingChargeRepository::class, YarnDyingChargeImplementation::class);
		$this->app->singleton(BuyerYarnDyingChargeRepository::class, BuyerYarnDyingChargeImplementation::class);
		$this->app->singleton(SupplierYarnDyingChargeRepository::class, SupplierYarnDyingChargeImplementation::class);
		$this->app->singleton(EmbelishmentRepository::class, EmbelishmentImplementation::class);
		$this->app->singleton(EmbelishmentTypeRepository::class, EmbelishmentTypeImplementation::class);
		$this->app->singleton(WashChargeRepository::class, WashChargeImplementation::class);
		$this->app->singleton(SupplierWashChargeRepository::class, SupplierWashChargeImplementation::class);
		$this->app->singleton(FabricprocesslossPercentRepository::class, FabricprocesslossPercentImplementation::class);
		$this->app->singleton(AutoyarnRepository::class, AutoyarnImplementation::class);
		$this->app->singleton(AutoyarnratioRepository::class, AutoyarnratioImplementation::class);
		$this->app->singleton(WagevariableRepository::class, WagevariableImplementation::class);
		$this->app->singleton(GmtsProcessLossRepository::class, GmtsProcessLossImplementation::class);
		$this->app->singleton(GmtsProcessLossPerRepository::class, GmtsProcessLossPerImplementation::class);
		$this->app->singleton(BuyerBranchRepository::class, BuyerBranchImplementation::class);
		$this->app->singleton(BuyerBranchShipdayRepository::class, BuyerBranchShipdayImplementation::class);
		$this->app->singleton(DelaycauseRepository::class, DelaycauseImplementation::class);
		$this->app->singleton(DesignationRepository::class, DesignationImplementation::class);
		$this->app->singleton(SewingCapacityRepository::class, SewingCapacityImplementation::class);
		$this->app->singleton(SewingCapacityDateRepository::class, SewingCapacityDateImplementation::class);
		$this->app->singleton(CapacityDistRepository::class, CapacityDistImplementation::class);
		$this->app->singleton(CapacityDistBuyerRepository::class, CapacityDistBuyerImplementation::class);
		$this->app->singleton(CapacityDistBuyerTeamRepository::class, CapacityDistBuyerTeamImplementation::class);
		$this->app->singleton(KeycontrolRepository::class, KeycontrolImplementation::class);
		$this->app->singleton(KeycontrolParameterRepository::class, KeycontrolParameterImplementation::class);
		$this->app->singleton(TrimcosttempleteRepository::class, TrimcosttempleteImplementation::class);
		$this->app->singleton(TnataskRepository::class, TnataskImplementation::class);
		$this->app->singleton(ItemAccountRepository::class, ItemAccountImplementation::class);
		$this->app->singleton(ItemAccountRatioRepository::class, ItemAccountRatioImplementation::class);
		$this->app->singleton(ItemAccountSupplierRepository::class, ItemAccountSupplierImplementation::class);
		$this->app->singleton(ItemAccountSupplierRateRepository::class, ItemAccountSupplierRateImplementation::class);
		$this->app->singleton(ItemAccountSupplierFeatRepository::class, ItemAccountSupplierFeatImplementation::class);
		$this->app->singleton(StyleRepository::class, StyleImplementation::class);
		$this->app->singleton(StyleGmtsRepository::class, StyleGmtsImplementation::class);
		$this->app->singleton(StyleEmbelishmentRepository::class, StyleEmbelishmentImplementation::class);
		$this->app->singleton(StyleColorRepository::class, StyleColorImplementation::class);
		$this->app->singleton(StyleSizeRepository::class, StyleSizeImplementation::class);
		$this->app->singleton(StyleGmtColorSizeRepository::class, StyleGmtColorSizeImplementation::class);
		$this->app->singleton(StyleFabricationRepository::class, StyleFabricationImplementation::class);
		$this->app->singleton(StyleFabricationStripeRepository::class, StyleFabricationStripeImplementation::class);
		$this->app->singleton(StyleSizeMsureRepository::class, StyleSizeMsureImplementation::class);
		$this->app->singleton(StyleSizeMsureValRepository::class, StyleSizeMsureValImplementation::class);
		$this->app->singleton(StyleSampleRepository::class, StyleSampleImplementation::class);
		$this->app->singleton(StyleSampleCsRepository::class, StyleSampleCsImplementation::class);
		$this->app->singleton(StylePolyRepository::class, StylePolyImplementation::class);
		$this->app->singleton(StylePolyRatioRepository::class, StylePolyRatioImplementation::class);
		$this->app->singleton(StylePkgRepository::class, StylePkgImplementation::class);
		$this->app->singleton(StylePkgRatioRepository::class, StylePkgRatioImplementation::class);
		$this->app->singleton(StyleEvaluationRepository::class, StyleEvaluationImplementation::class);


		$this->app->singleton(MktCostRepository::class, MktCostImplementation::class);
		$this->app->singleton(MktCostFabricRepository::class, MktCostFabricImplementation::class);
		$this->app->singleton(MktCostFabricConRepository::class, MktCostFabricConImplementation::class);
		$this->app->singleton(MktCostOtherRepository::class, MktCostOtherImplementation::class);
		$this->app->singleton(MktCostCommercialRepository::class, MktCostCommercialImplementation::class);
		$this->app->singleton(MktCostCmRepository::class, MktCostCmImplementation::class);
		$this->app->singleton(MktCostTrimRepository::class, MktCostTrimImplementation::class);
		$this->app->singleton(MktCostCommissionRepository::class, MktCostCommissionImplementation::class);
		$this->app->singleton(MktCostProfitRepository::class, MktCostProfitImplementation::class);
		$this->app->singleton(MktCostQuotePriceRepository::class, MktCostQuotePriceImplementation::class);
		$this->app->singleton(MktCostTargetPriceRepository::class, MktCostTargetPriceImplementation::class);
		$this->app->singleton(MktCostEmbRepository::class, MktCostEmbImplementation::class);
		$this->app->singleton(MktCostYarnRepository::class, MktCostYarnImplementation::class);
		$this->app->singleton(MktCostFabricProdRepository::class, MktCostFabricProdImplementation::class);
		$this->app->singleton(BudgetRepository::class, BudgetImplementation::class);
		$this->app->singleton(BudgetFabricRepository::class, BudgetFabricImplementation::class);
		$this->app->singleton(BudgetFabricConRepository::class, BudgetFabricConImplementation::class);
		$this->app->singleton(BudgetOtherRepository::class, BudgetOtherImplementation::class);
		$this->app->singleton(BudgetCommercialRepository::class, BudgetCommercialImplementation::class);
		$this->app->singleton(BudgetCmRepository::class, BudgetCmImplementation::class);
		$this->app->singleton(BudgetTrimRepository::class, BudgetTrimImplementation::class);
		$this->app->singleton(BudgetTrimConRepository::class, BudgetTrimConImplementation::class);
		$this->app->singleton(BudgetTrimDtmRepository::class, BudgetTrimDtmImplementation::class);
		$this->app->singleton(BudgetCommissionRepository::class, BudgetCommissionImplementation::class);
		$this->app->singleton(BudgetEmbRepository::class, BudgetEmbImplementation::class);
		$this->app->singleton(BudgetEmbConRepository::class, BudgetEmbConImplementation::class);
		$this->app->singleton(BudgetYarnRepository::class, BudgetYarnImplementation::class);
		$this->app->singleton(BudgetFabricProdRepository::class, BudgetFabricProdImplementation::class);

		$this->app->singleton(BudgetFabricProdConRepository::class, BudgetFabricProdConImplementation::class);
		$this->app->singleton(BudgetYarnDyeingRepository::class, BudgetYarnDyeingImplementation::class);
		$this->app->singleton(BudgetYarnDyeingConRepository::class, BudgetYarnDyeingConImplementation::class);
		$this->app->singleton(JobRepository::class, JobImplementation::class);
		$this->app->singleton(ProjectionRepository::class, ProjectionImplementation::class);
		$this->app->singleton(ProjectionCountryRepository::class, ProjectionCountryImplementation::class);
		$this->app->singleton(ProjectionQtyRepository::class, ProjectionQtyImplementation::class);
		$this->app->singleton(SalesOrderRepository::class, SalesOrderImplementation::class);
		$this->app->singleton(SalesOrderCountryRepository::class, SalesOrderCountryImplementation::class);
		$this->app->singleton(SalesOrderColorRepository::class, SalesOrderColorImplementation::class);
		$this->app->singleton(SalesOrderSizeRepository::class, SalesOrderSizeImplementation::class);
		$this->app->singleton(SalesOrderItemRepository::class, SalesOrderItemImplementation::class);
		$this->app->singleton(SalesOrderGmtColorSizeRepository::class, SalesOrderGmtColorSizeImplementation::class);
		$this->app->singleton(SalesOrderShipDateChangeRepository::class, SalesOrderShipDateChangeImplementation::class);
		$this->app->singleton(SalesOrderCloseRepository::class, SalesOrderCloseImplementation::class);
		$this->app->singleton(CadRepository::class, CadImplementation::class);
		$this->app->singleton(CadConRepository::class, CadConImplementation::class);

		$this->app->singleton(SoDyeingTargetRepository::class, SoDyeingTargetImplementation::class);

		$this->app->singleton(PurchaseOrderRepository::class, PurchaseOrderImplementation::class);

		$this->app->singleton(PoKnitServiceRepository::class, PoKnitServiceImplementation::class);
		$this->app->singleton(PoKnitServiceItemRepository::class, PoKnitServiceItemImplementation::class);
		$this->app->singleton(PoKnitServiceItemQtyRepository::class, PoKnitServiceItemQtyImplementation::class);

		$this->app->singleton(PoDyeingServiceRepository::class, PoDyeingServiceImplementation::class);
		$this->app->singleton(PoDyeingServiceItemRepository::class, PoDyeingServiceItemImplementation::class);
		$this->app->singleton(PoDyeingServiceItemQtyRepository::class, PoDyeingServiceItemQtyImplementation::class);

		$this->app->singleton(PoAopServiceRepository::class, PoAopServiceImplementation::class);
		$this->app->singleton(PoAopServiceItemRepository::class, PoAopServiceItemImplementation::class);
		$this->app->singleton(PoAopServiceItemQtyRepository::class, PoAopServiceItemQtyImplementation::class);

		$this->app->singleton(PoTrimRepository::class, PoTrimImplementation::class);
		$this->app->singleton(PoTrimItemRepository::class, PoTrimItemImplementation::class);
		$this->app->singleton(PoTrimItemQtyRepository::class, PoTrimItemQtyImplementation::class);

		$this->app->singleton(PoYarnRepository::class, PoYarnImplementation::class);
		$this->app->singleton(PoYarnItemRepository::class, PoYarnItemImplementation::class);
		$this->app->singleton(PoYarnItemBomQtyRepository::class, PoYarnItemBomQtyImplementation::class);
		$this->app->singleton(PoYarnDyeingRepository::class, PoYarnDyeingImplementation::class);
		$this->app->singleton(PoYarnDyeingItemRepository::class, PoYarnDyeingItemImplementation::class);
		$this->app->singleton(PoYarnDyeingItemBomQtyRepository::class, PoYarnDyeingItemBomQtyImplementation::class);
		$this->app->singleton(PoYarnDyeingItemRespRepository::class, PoYarnDyeingItemRespImplementation::class);
		$this->app->singleton(PoDyeChemRepository::class, PoDyeChemImplementation::class);
		$this->app->singleton(PoDyeChemItemRepository::class, PoDyeChemItemImplementation::class);
		$this->app->singleton(PoGeneralRepository::class, PoGeneralImplementation::class);
		$this->app->singleton(PoGeneralItemRepository::class, PoGeneralItemImplementation::class);
		$this->app->singleton(PoGeneralServiceRepository::class, PoGeneralServiceImplementation::class);
		$this->app->singleton(PoGeneralServiceItemRepository::class, PoGeneralServiceItemImplementation::class);
		$this->app->singleton(PoEmbServiceRepository::class, PoEmbServiceImplementation::class);
		$this->app->singleton(PoEmbServiceItemRepository::class, PoEmbServiceItemImplementation::class);
		$this->app->singleton(PoEmbServiceItemQtyRepository::class, PoEmbServiceItemQtyImplementation::class);

		$this->app->singleton(PoFabricRepository::class, PoFabricImplementation::class);
		$this->app->singleton(PoFabricItemRepository::class, PoFabricItemImplementation::class);
		$this->app->singleton(PoFabricItemQtyRepository::class, PoFabricItemQtyImplementation::class);

		$this->app->singleton(TermsConditionRepository::class, TermsConditionImplementation::class);
		$this->app->singleton(PurchaseTermsConditionRepository::class, PurchaseTermsConditionImplementation::class);
		$this->app->singleton(WeightMachineRepository::class, WeightMachineImplementation::class);
		$this->app->singleton(WeightMachineUserRepository::class, WeightMachineUserImplementation::class);

		$this->app->singleton(AccYearRepository::class, AccYearImplementation::class);
		$this->app->singleton(AccChartSubGroupRepository::class, AccChartSubGroupImplementation::class);
		$this->app->singleton(AccChartCtrlHeadRepository::class, AccChartCtrlHeadImplementation::class);
		$this->app->singleton(AccChartLocationRepository::class, AccChartLocationImplementation::class);
		$this->app->singleton(AccChartDivisionRepository::class, AccChartDivisionImplementation::class);
		$this->app->singleton(AccChartDepartmentRepository::class, AccChartDepartmentImplementation::class);
		$this->app->singleton(AccChartSectionRepository::class, AccChartSectionImplementation::class);
		$this->app->singleton(AccChartCtrlHeadMappingRepository::class, AccChartCtrlHeadMappingImplementation::class);

		$this->app->singleton(AccTransPrntRepository::class, AccTransPrntImplementation::class);
		$this->app->singleton(AccTransChldRepository::class, AccTransChldImplementation::class);
		$this->app->singleton(AccTransPurchaseRepository::class, AccTransPurchaseImplementation::class);
		$this->app->singleton(AccTransSalesRepository::class, AccTransSalesImplementation::class);
		$this->app->singleton(AccTransEmployeeRepository::class, AccTransEmployeeImplementation::class);

		$this->app->singleton(AccTransOtherPartyRepository::class, AccTransOtherPartyImplementation::class);
		$this->app->singleton(AccTransLoanRefRepository::class, AccTransLoanRefImplementation::class);
		$this->app->singleton(AccTransOtherRefRepository::class, AccTransOtherRefImplementation::class);
		$this->app->singleton(AccBepRepository::class, AccBepImplementation::class);
		$this->app->singleton(AccBepEntryRepository::class, AccBepEntryImplementation::class);
		$this->app->singleton(AccTermLoanRepository::class, AccTermLoanImplementation::class);
		$this->app->singleton(AccTermLoanInstallmentRepository::class, AccTermLoanInstallmentImplementation::class);
		$this->app->singleton(AccTermLoanPaymentRepository::class, AccTermLoanPaymentImplementation::class);
		$this->app->singleton(AccTermLoanAdjustmentRepository::class, AccTermLoanAdjustmentImplementation::class);

		$this->app->singleton(EmployeeRepository::class, EmployeeImplementation::class);
		$this->app->singleton(AccPeriodRepository::class, AccPeriodImplementation::class);

		$this->app->singleton(AccCostDistributionRepository::class, AccCostDistributionImplementation::class);

		$this->app->singleton(AccCostDistributionDtlRepository::class, AccCostDistributionDtlImplementation::class);

		$this->app->singleton(SupplierNatureRepository::class, SupplierNatureImplementation::class);



		$this->app->singleton(BankRepository::class, BankImplementation::class);
		$this->app->singleton(BankBranchRepository::class, BankBranchImplementation::class);
		$this->app->singleton(BankAccountRepository::class, BankAccountImplementation::class);

		$this->app->singleton(AssetAcquisitionRepository::class, AssetAcquisitionImplementation::class);
		$this->app->singleton(AssetDepreciationRepository::class, AssetDepreciationImplementation::class);
		$this->app->singleton(AssetQuantityCostRepository::class, AssetQuantityCostImplementation::class);

		$this->app->singleton(AssetTechnicalFeatureRepository::class, AssetTechnicalFeatureImplementation::class);
		$this->app->singleton(AssetUtilityDetailRepository::class, AssetUtilityDetailImplementation::class);
		$this->app->singleton(AssetMaintenanceRepository::class, AssetMaintenanceImplementation::class);
		$this->app->singleton(AssetTechFileUploadRepository::class, AssetTechFileUploadImplementation::class);
		$this->app->singleton(AssetTechImageRepository::class, AssetTechImageImplementation::class);
		$this->app->singleton(AssetManpowerRepository::class, AssetManpowerImplementation::class);
		$this->app->singleton(AssetBreakdownRepository::class, AssetBreakdownImplementation::class);
		$this->app->singleton(AssetRecoveryRepository::class, AssetRecoveryImplementation::class);
		$this->app->singleton(AssetDisposalRepository::class, AssetDisposalImplementation::class);
		$this->app->singleton(AssetServiceRepairRepository::class, AssetServiceRepairImplementation::class);
		$this->app->singleton(AssetServiceRepairPartRepository::class, AssetServiceRepairPartImplementation::class);
		$this->app->singleton(AssetServiceRepository::class, AssetServiceImplementation::class);
		$this->app->singleton(AssetServiceDetailRepository::class, AssetServiceDetailImplementation::class);
		$this->app->singleton(AssetReturnRepository::class, AssetReturnImplementation::class);
		$this->app->singleton(AssetReturnDetailRepository::class, AssetReturnDetailImplementation::class);
		$this->app->singleton(AssetReturnDetailCostRepository::class, AssetReturnDetailCostImplementation::class);

		$this->app->singleton(EmployeeHRRepository::class, EmployeeHRImplementation::class);
		$this->app->singleton(EmployeeHRJobRepository::class, EmployeeHRJobImplementation::class);
		$this->app->singleton(EmployeeHRLeaveRepository::class, EmployeeHRLeaveImplementation::class);
		$this->app->singleton(EmployeeHRStatusRepository::class, EmployeeHRStatusImplementation::class);

		$this->app->singleton(EmployeeToDoListRepository::class, EmployeeToDoListImplementation::class);
		$this->app->singleton(EmployeeToDoListTaskRepository::class, EmployeeToDoListTaskImplementation::class);
		$this->app->singleton(EmployeeToDoListTaskBarRepository::class, EmployeeToDoListTaskBarImplementation::class);
		$this->app->singleton(EmployeeMovementRepository::class, EmployeeMovementImplementation::class);
		$this->app->singleton(EmployeeMovementDtlRepository::class, EmployeeMovementDtlImplementation::class);

		$this->app->singleton(CompanySupplierRepository::class, CompanySupplierImplementation::class);

		$this->app->singleton(InvPurReqRepository::class, InvPurReqImplementation::class);
		$this->app->singleton(InvPurReqItemRepository::class, InvPurReqItemImplementation::class);
		$this->app->singleton(InvPurReqPaidRepository::class, InvPurReqPaidImplementation::class);
		$this->app->singleton(InvPurReqAssetBreakdownRepository::class, InvPurReqAssetBreakdownImplementation::class);
		$this->app->singleton(InvCasReqRepository::class, InvCasReqImplementation::class);
		$this->app->singleton(InvCasReqItemRepository::class, InvCasReqItemImplementation::class);
		$this->app->singleton(InvCasReqPaidRepository::class, InvCasReqPaidImplementation::class);
		$this->app->singleton(StoreRepository::class, StoreImplementation::class);
		$this->app->singleton(StoreItemcategoryRepository::class, StoreItemcategoryImplementation::class);

		$this->app->singleton(EmployeeTransferRepository::class, EmployeeTransferImplementation::class);
		$this->app->singleton(EmployeeJobHistoryRepository::class, EmployeeJobHistoryImplementation::class);

		$this->app->singleton(EmployeePromotionRepository::class, EmployeePromotionImplementation::class);

		$this->app->singleton(ExpPiRepository::class, ExpPiImplementation::class);
		$this->app->singleton(ExpPiOrderRepository::class, ExpPiOrderImplementation::class);

		$this->app->singleton(ExpLcScRepository::class, ExpLcScImplementation::class);
		$this->app->singleton(ExpRepLcScRepository::class, ExpRepLcScImplementation::class);
		$this->app->singleton(ExpLcScPiRepository::class, ExpLcScPiImplementation::class);

		$this->app->singleton(ExpScOrderRepository::class, ExpScOrderImplementation::class);

		$this->app->singleton(ExpLcRepository::class, ExpLcImplementation::class);
		$this->app->singleton(ExpLcTagPiRepository::class, ExpLcTagPiImplementation::class);
		$this->app->singleton(ExpRepLcRepository::class, ExpRepLcImplementation::class);
		$this->app->singleton(ExpLcOrderRepository::class, ExpLcOrderImplementation::class);

		$this->app->singleton(ItemcategoryUserRepository::class, ItemcategoryUserImplementation::class);

		$this->app->singleton(ExFactoryRepository::class, ExFactoryImplementation::class);

		$this->app->singleton(ImpLcRepository::class, ImpLcImplementation::class);
		$this->app->singleton(ImpLcPoRepository::class, ImpLcPoImplementation::class);
		$this->app->singleton(ImpBackedExpLcScRepository::class, ImpBackedExpLcScImplementation::class);
		$this->app->singleton(ImpBankChargeRepository::class, ImpBankChargeImplementation::class);
		$this->app->singleton(ImpShippingMarkRepository::class, ImpShippingMarkImplementation::class);
		$this->app->singleton(ImpLcFileRepository::class, ImpLcFileImplementation::class);
		$this->app->singleton(ImpDocAcceptRepository::class, ImpDocAcceptImplementation::class);
		$this->app->singleton(ImpDocAcceptMaturityRepository::class, ImpDocAcceptMaturityImplementation::class);
		$this->app->singleton(ImpDocMaturityRepository::class, ImpDocMaturityImplementation::class);
		$this->app->singleton(ImpDocMaturityDtlRepository::class, ImpDocMaturityDtlImplementation::class);
		$this->app->singleton(CommercialHeadRepository::class, CommercialHeadImplementation::class);
		$this->app->singleton(ImpLiabilityAdjustRepository::class, ImpLiabilityAdjustImplementation::class);
		$this->app->singleton(ImpLiabilityAdjustChldRepository::class, ImpLiabilityAdjustChldImplementation::class);
		$this->app->singleton(ImpAccComDetailRepository::class, ImpAccComDetailImplementation::class);
		$this->app->singleton(ExpPreCreditRepository::class, ExpPreCreditImplementation::class);
		$this->app->singleton(ExpPreCreditLcScRepository::class, ExpPreCreditLcScImplementation::class);
		$this->app->singleton(ExpInvoiceRepository::class, ExpInvoiceImplementation::class);
		$this->app->singleton(ExpInvoiceOrderRepository::class, ExpInvoiceOrderImplementation::class);
		$this->app->singleton(ExpInvoiceOrderDtlRepository::class, ExpInvoiceOrderDtlImplementation::class);

		$this->app->singleton(ExpAdvInvoiceRepository::class, ExpAdvInvoiceImplementation::class);
		$this->app->singleton(ExpAdvInvoiceOrderRepository::class, ExpAdvInvoiceOrderImplementation::class);
		$this->app->singleton(ExpAdvInvoiceOrderDtlRepository::class, ExpAdvInvoiceOrderDtlImplementation::class);

		$this->app->singleton(SubInbMarketingRepository::class, SubInbMarketingImplementation::class);
		$this->app->singleton(SubInbEventRepository::class, SubInbEventImplementation::class);
		$this->app->singleton(SubInbServiceRepository::class, SubInbServiceImplementation::class);
		$this->app->singleton(SubInbImageRepository::class, SubInbImageImplementation::class);
		$this->app->singleton(SubInbOrderRepository::class, SubInbOrderImplementation::class);
		$this->app->singleton(SubInbOrderProductRepository::class, SubInbOrderProductImplementation::class);
		/* Marketing  */
		$this->app->singleton(TargetTransferRepository::class, TargetTransferImplementation::class);
		$this->app->singleton(DayTargetTransferRepository::class, DayTargetTransferImplementation::class);
		$this->app->singleton(BuyerDevelopmentRepository::class, BuyerDevelopmentImplementation::class);
		$this->app->singleton(BuyerDevelopmentEventRepository::class, BuyerDevelopmentEventImplementation::class);
		$this->app->singleton(BuyerDevelopmentIntmRepository::class, BuyerDevelopmentIntmImplementation::class);
		$this->app->singleton(BuyerDevelopmentDocRepository::class, BuyerDevelopmentDocImplementation::class);
		$this->app->singleton(BuyerDevelopmentOrderRepository::class, BuyerDevelopmentOrderImplementation::class);
		$this->app->singleton(BuyerDevelopmentOrderQtyRepository::class, BuyerDevelopmentOrderQtyImplementation::class);

		$this->app->singleton(SoKnitRepository::class, SoKnitImplementation::class);
		$this->app->singleton(SoKnitRefRepository::class, SoKnitRefImplementation::class);
		$this->app->singleton(SoKnitPoRepository::class, SoKnitPoImplementation::class);
		$this->app->singleton(SoKnitPoItemRepository::class, SoKnitPoItemImplementation::class);
		$this->app->singleton(SoKnitItemRepository::class, SoKnitItemImplementation::class);
		$this->app->singleton(SoKnitFileRepository::class, SoKnitFileImplementation::class);
		$this->app->singleton(SoKnitTargetRepository::class, SoKnitTargetImplementation::class);

		$this->app->singleton(PlKnitRepository::class, PlKnitImplementation::class);
		$this->app->singleton(PlKnitItemRepository::class, PlKnitItemImplementation::class);

		$this->app->singleton(PlKnitItemQtyRepository::class, PlKnitItemQtyImplementation::class);

		$this->app->singleton(PlKnitItemStripeRepository::class, PlKnitItemStripeImplementation::class);
		$this->app->singleton(PlKnitItemNarrowfabricRepository::class, PlKnitItemNarrowfabricImplementation::class);

		$this->app->singleton(SoKnitYarnRcvRepository::class, SoKnitYarnRcvImplementation::class);
		$this->app->singleton(SoKnitYarnRcvItemRepository::class, SoKnitYarnRcvItemImplementation::class);

		$this->app->singleton(SoKnitYarnRtnRepository::class, SoKnitYarnRtnImplementation::class);
		$this->app->singleton(SoKnitYarnRtnItemRepository::class, SoKnitYarnRtnItemImplementation::class);

		$this->app->singleton(SoKnitDlvRepository::class, SoKnitDlvImplementation::class);
		$this->app->singleton(SoKnitDlvItemRepository::class, SoKnitDlvItemImplementation::class);
		$this->app->singleton(SoKnitDlvItemYarnRepository::class, SoKnitDlvItemYarnImplementation::class);

		$this->app->singleton(SoDyeingDlvRepository::class, SoDyeingDlvImplementation::class);
		$this->app->singleton(SoDyeingDlvItemRepository::class, SoDyeingDlvItemImplementation::class);

		$this->app->singleton(SoAopTargetRepository::class, SoAopTargetImplementation::class);



		$this->app->singleton(SubInbOrderFileRepository::class, SubInbOrderFileImplementation::class);
		$this->app->singleton(RqYarnRepository::class, RqYarnImplementation::class);

		$this->app->singleton(RqYarnFabricationRepository::class, RqYarnFabricationImplementation::class);
		$this->app->singleton(RqYarnItemRepository::class, RqYarnItemImplementation::class);



		$this->app->singleton(PlDyeingRepository::class, PlDyeingImplementation::class);
		$this->app->singleton(PlDyeingItemRepository::class, PlDyeingItemImplementation::class);
		$this->app->singleton(PlDyeingItemQtyRepository::class, PlDyeingItemQtyImplementation::class);
		$this->app->singleton(SoDyeingRepository::class, SoDyeingImplementation::class);
		$this->app->singleton(SoDyeingRefRepository::class, SoDyeingRefImplementation::class);
		$this->app->singleton(SoDyeingPoRepository::class, SoDyeingPoImplementation::class);
		$this->app->singleton(SoDyeingPoItemRepository::class, SoDyeingPoItemImplementation::class);
		$this->app->singleton(SoDyeingItemRepository::class, SoDyeingItemImplementation::class);
		$this->app->singleton(SoDyeingFileRepository::class, SoDyeingFileImplementation::class);
		$this->app->singleton(SoDyeingFabricRcvRepository::class, SoDyeingFabricRcvImplementation::class);
		$this->app->singleton(SoDyeingFabricRcvItemRepository::class, SoDyeingFabricRcvItemImplementation::class);
		$this->app->singleton(SoDyeingFabricRcvRolRepository::class, SoDyeingFabricRcvRolImplementation::class);

		$this->app->singleton(SoDyeingFabricRtnRepository::class, SoDyeingFabricRtnImplementation::class);
		$this->app->singleton(SoDyeingFabricRtnItemRepository::class, SoDyeingFabricRtnItemImplementation::class);
		$this->app->singleton(SoDyeingBomRepository::class, SoDyeingBomImplementation::class);
		$this->app->singleton(SoDyeingBomFabricRepository::class, SoDyeingBomFabricImplementation::class);
		$this->app->singleton(SoDyeingBomFabricItemRepository::class, SoDyeingBomFabricItemImplementation::class);
		$this->app->singleton(SoDyeingBomOverheadRepository::class, SoDyeingBomOverheadImplementation::class);

		$this->app->singleton(SoDyeingMktCostRepository::class, SoDyeingMktCostImplementation::class);
		$this->app->singleton(SoDyeingMktCostFabRepository::class, SoDyeingMktCostFabImplementation::class);
		$this->app->singleton(SoDyeingMktCostFabItemRepository::class, SoDyeingMktCostFabItemImplementation::class);
		$this->app->singleton(SoDyeingMktCostFabOvhedRepository::class, SoDyeingMktCostFabOvhedImplementation::class);
		$this->app->singleton(SoDyeingMktCostFabFinRepository::class, SoDyeingMktCostFabFinImplementation::class);
		$this->app->singleton(SoDyeingMktCostQpriceRepository::class, SoDyeingMktCostQpriceImplementation::class);
		$this->app->singleton(SoDyeingMktCostQpricedtlRepository::class, SoDyeingMktCostQpricedtlImplementation::class);


		$this->app->singleton(SoAopRepository::class, SoAopImplementation::class);
		$this->app->singleton(SoAopRefRepository::class, SoAopRefImplementation::class);
		$this->app->singleton(SoAopPoRepository::class, SoAopPoImplementation::class);
		$this->app->singleton(SoAopPoItemRepository::class, SoAopPoItemImplementation::class);
		$this->app->singleton(SoAopItemRepository::class, SoAopItemImplementation::class);
		$this->app->singleton(SoAopFileRepository::class, SoAopFileImplementation::class);

		$this->app->singleton(SoAopFabricRcvRepository::class, SoAopFabricRcvImplementation::class);
		$this->app->singleton(SoAopFabricRcvItemRepository::class, SoAopFabricRcvItemImplementation::class);
		$this->app->singleton(SoAopFabricRcvRolRepository::class, SoAopFabricRcvRolImplementation::class);
		$this->app->singleton(SoAopFabricIsuRepository::class, SoAopFabricIsuImplementation::class);
		$this->app->singleton(SoAopFabricIsuItemRepository::class, SoAopFabricIsuItemImplementation::class);


		$this->app->singleton(SoAopDlvRepository::class, SoAopDlvImplementation::class);
		$this->app->singleton(SoAopDlvItemRepository::class, SoAopDlvItemImplementation::class);

		$this->app->singleton(SoAopFabricRtnRepository::class, SoAopFabricRtnImplementation::class);
		$this->app->singleton(SoAopFabricRtnItemRepository::class, SoAopFabricRtnItemImplementation::class);

		$this->app->singleton(SoEmbRepository::class, SoEmbImplementation::class);
		$this->app->singleton(SoEmbRefRepository::class, SoEmbRefImplementation::class);
		$this->app->singleton(SoEmbPoRepository::class, SoEmbPoImplementation::class);
		$this->app->singleton(SoEmbPoItemRepository::class, SoEmbPoItemImplementation::class);
		$this->app->singleton(SoEmbItemRepository::class, SoEmbItemImplementation::class);
		$this->app->singleton(SoEmbFileRepository::class, SoEmbFileImplementation::class);
		$this->app->singleton(SoEmbTargetRepository::class, SoEmbTargetImplementation::class);
		$this->app->singleton(SoEmbCutpanelRcvRepository::class, SoEmbCutpanelRcvImplementation::class);
		$this->app->singleton(SoEmbCutpanelRcvOrderRepository::class, SoEmbCutpanelRcvOrderImplementation::class);
		$this->app->singleton(SoEmbCutpanelRcvQtyRepository::class, SoEmbCutpanelRcvQtyImplementation::class);

		$this->app->singleton(SoEmbPrintDlvRepository::class, SoEmbPrintDlvImplementation::class);

		$this->app->singleton(SoEmbPrintDlvItemRepository::class, SoEmbPrintDlvItemImplementation::class);

		$this->app->singleton(SoEmbMktCostRepository::class, SoEmbMktCostImplementation::class);
		$this->app->singleton(SoEmbMktCostParamRepository::class, SoEmbMktCostParamImplementation::class);
		$this->app->singleton(SoEmbMktCostParamItemRepository::class, SoEmbMktCostParamItemImplementation::class);
		$this->app->singleton(SoEmbMktCostParamFinRepository::class, SoEmbMktCostParamFinImplementation::class);
		$this->app->singleton(SoEmbMktCostQpriceRepository::class, SoEmbMktCostQpriceImplementation::class);
		$this->app->singleton(SoEmbMktCostQpricedtlRepository::class, SoEmbMktCostQpricedtlImplementation::class);

		$this->app->singleton(ExpDocSubmissionRepository::class, ExpDocSubmissionImplementation::class);
		$this->app->singleton(ExpDocSubInvoiceRepository::class, ExpDocSubInvoiceImplementation::class);
		$this->app->singleton(ExpDocSubTransectionRepository::class, ExpDocSubTransectionImplementation::class);
		$this->app->singleton(ExpProRlzRepository::class, ExpProRlzImplementation::class);
		$this->app->singleton(ExpProRlzDeductRepository::class, ExpProRlzDeductImplementation::class);
		$this->app->singleton(ExpProRlzAmountRepository::class, ExpProRlzAmountImplementation::class);
		$this->app->singleton(ExpLcScReviseRepository::class, ExpLcScReviseImplementation::class);
		$this->app->singleton(ProdGmtCartonEntryRepository::class, ProdGmtCartonEntryImplementation::class);
		$this->app->singleton(
			ProdGmtCartonDetailRepository::class,
			ProdGmtCartonDetailImplementation::class
		);

		$this->app->singleton(ProdGmtExFactoryRepository::class, ProdGmtExFactoryImplementation::class);
		$this->app->singleton(ProdGmtExFactoryQtyRepository::class, ProdGmtExFactoryQtyImplementation::class);

		$this->app->singleton(ProdGmtCartonDetailQtyRepository::class, ProdGmtCartonDetailQtyImplementation::class);
		$this->app->singleton(ProdGmtIronRepository::class, ProdGmtIronImplementation::class);
		$this->app->singleton(ProdGmtIronOrderRepository::class, ProdGmtIronOrderImplementation::class);
		$this->app->singleton(ProdGmtIronQtyRepository::class, ProdGmtIronQtyImplementation::class);

		$this->app->singleton(ProdGmtPolyRepository::class, ProdGmtPolyImplementation::class);
		$this->app->singleton(ProdGmtPolyOrderRepository::class, ProdGmtPolyOrderImplementation::class);
		$this->app->singleton(ProdGmtPolyQtyRepository::class, ProdGmtPolyQtyImplementation::class);

		$this->app->singleton(ProdGmtSewingRepository::class, ProdGmtSewingImplementation::class);
		$this->app->singleton(ProdGmtSewingOrderRepository::class, ProdGmtSewingOrderImplementation::class);
		$this->app->singleton(ProdGmtSewingQtyRepository::class, ProdGmtSewingQtyImplementation::class);

		$this->app->singleton(
			ProdGmtCuttingRepository::class,
			ProdGmtCuttingImplementation::class
		);
		$this->app->singleton(ProdGmtCuttingOrderRepository::class, ProdGmtCuttingOrderImplementation::class);
		$this->app->singleton(ProdGmtCuttingQtyRepository::class, ProdGmtCuttingQtyImplementation::class);

		$this->app->singleton(
			ProdGmtDlvInputRepository::class,
			ProdGmtDlvInputImplementation::class
		);
		$this->app->singleton(ProdGmtDlvInputOrderRepository::class, ProdGmtDlvInputOrderImplementation::class);
		$this->app->singleton(ProdGmtDlvInputQtyRepository::class, ProdGmtDlvInputQtyImplementation::class);

		$this->app->singleton(
			ProdGmtRcvInputRepository::class,
			ProdGmtRcvInputImplementation::class
		);
		$this->app->singleton(ProdGmtRcvInputOrderRepository::class, ProdGmtRcvInputOrderImplementation::class);
		$this->app->singleton(ProdGmtRcvInputQtyRepository::class, ProdGmtRcvInputQtyImplementation::class);

		$this->app->singleton(ProdGmtDlvPrintRepository::class, ProdGmtDlvPrintImplementation::class);
		$this->app->singleton(ProdGmtDlvPrintOrderRepository::class, ProdGmtDlvPrintOrderImplementation::class);
		$this->app->singleton(ProdGmtDlvPrintQtyRepository::class, ProdGmtDlvPrintQtyImplementation::class);

		$this->app->singleton(
			ProdGmtDlvToEmbRepository::class,
			ProdGmtDlvToEmbImplementation::class
		);
		$this->app->singleton(ProdGmtDlvToEmbOrderRepository::class, ProdGmtDlvToEmbOrderImplementation::class);
		$this->app->singleton(ProdGmtDlvToEmbQtyRepository::class, ProdGmtDlvToEmbQtyImplementation::class);

		$this->app->singleton(
			ProdGmtPrintRcvRepository::class,
			ProdGmtPrintRcvImplementation::class
		);
		$this->app->singleton(ProdGmtPrintRcvOrderRepository::class, ProdGmtPrintRcvOrderImplementation::class);
		$this->app->singleton(ProdGmtPrintRcvQtyRepository::class, ProdGmtPrintRcvQtyImplementation::class);

		$this->app->singleton(
			ProdGmtEmbRcvRepository::class,
			ProdGmtEmbRcvImplementation::class
		);
		$this->app->singleton(ProdGmtEmbRcvOrderRepository::class, ProdGmtEmbRcvOrderImplementation::class);
		$this->app->singleton(ProdGmtEmbRcvQtyRepository::class, ProdGmtEmbRcvQtyImplementation::class);

		$this->app->singleton(
			ProdGmtSewingLineRepository::class,
			ProdGmtSewingLineImplementation::class
		);
		$this->app->singleton(ProdGmtSewingLineOrderRepository::class, ProdGmtSewingLineOrderImplementation::class);
		$this->app->singleton(ProdGmtSewingLineQtyRepository::class, ProdGmtSewingLineQtyImplementation::class);

		$this->app->singleton(ProdGmtInspectionRepository::class, ProdGmtInspectionImplementation::class);
		$this->app->singleton(ProdGmtInspectionOrderRepository::class, ProdGmtInspectionOrderImplementation::class);
		$this->app->singleton(WstudyLineSetupRepository::class, WstudyLineSetupImplementation::class);
		$this->app->singleton(WstudyLineSetupDtlRepository::class, WstudyLineSetupDtlImplementation::class);
		$this->app->singleton(WstudyLineSetupLineRepository::class, WstudyLineSetupLineImplementation::class);
		$this->app->singleton(WstudyLineSetupDtlOrdRepository::class, WstudyLineSetupDtlOrdImplementation::class);
		$this->app->singleton(WstudyLineSetupMinAdjRepository::class, WstudyLineSetupMinAdjImplementation::class);
		$this->app->singleton(SoEmbPrintMcRepository::class, SoEmbPrintMcImplementation::class);
		$this->app->singleton(SoEmbPrintMcDtlRepository::class, SoEmbPrintMcDtlImplementation::class);
		$this->app->singleton(SoEmbPrintMcDtlOrdRepository::class, SoEmbPrintMcDtlOrdImplementation::class);
		$this->app->singleton(SoEmbPrintMcDtlMinajRepository::class, SoEmbPrintMcDtlMinajImplementation::class);

		$this->app->singleton(SoEmbPrintEntryRepository::class, SoEmbPrintEntryImplementation::class);
		$this->app->singleton(SoEmbPrintEntOrderRepository::class, SoEmbPrintEntOrderImplementation::class);

		$this->app->singleton(SoEmbPrintQcRepository::class, SoEmbPrintQcImplementation::class);
		$this->app->singleton(SoEmbPrintQcDtlRepository::class, SoEmbPrintQcDtlImplementation::class);
		$this->app->singleton(SoEmbPrintQcDtlDeftRepository::class, SoEmbPrintQcDtlDeftImplementation::class);

		$this->app->singleton(ProdEmbPrintDlvRepository::class, ProdEmbPrintDlvImplementation::class);

		$this->app->singleton(ProdEmbPrintDlvItemRepository::class, ProdEmbPrintDlvItemImplementation::class);

		$this->app->singleton(TnaOrdRepository::class, TnaOrdImplementation::class);
		$this->app->singleton(TnaProgressDelayRepository::class, TnaProgressDelayImplementation::class);
		$this->app->singleton(TnaProgressDelayDtlRepository::class, TnaProgressDelayDtlImplementation::class);
		$this->app->singleton(TnaTemplateRepository::class, TnaTemplateImplementation::class);
		$this->app->singleton(TnaTemplateDtlRepository::class, TnaTemplateDtlImplementation::class);
		$this->app->singleton(EmployeeAttendenceRepository::class, EmployeeAttendenceImplementation::class);
		$this->app->singleton(EmployeeIncrementRepository::class, EmployeeIncrementImplementation::class);
		$this->app->singleton(PermissionRoleRepository::class, PermissionRoleImplementation::class);
		$this->app->singleton(SmpCostRepository::class, SmpCostImplementation::class);
		$this->app->singleton(SmpCostFabricRepository::class, SmpCostFabricImplementation::class);
		$this->app->singleton(SmpCostFabricConRepository::class, SmpCostFabricConImplementation::class);
		$this->app->singleton(SmpCostYarnRepository::class, SmpCostYarnImplementation::class);
		$this->app->singleton(SmpCostFabricProdRepository::class, SmpCostFabricProdImplementation::class);
		$this->app->singleton(SmpCostFabricProdConRepository::class, SmpCostFabricProdConImplementation::class);
		$this->app->singleton(SmpCostTrimRepository::class, SmpCostTrimImplementation::class);
		$this->app->singleton(SmpCostTrimConRepository::class, SmpCostTrimConImplementation::class);
		$this->app->singleton(SmpCostEmbRepository::class, SmpCostEmbImplementation::class);
		$this->app->singleton(SmpCostEmbConRepository::class, SmpCostEmbConImplementation::class);
		$this->app->singleton(SmpCostCmRepository::class, SmpCostCmImplementation::class);
		$this->app->singleton(StyleFileUploadRepository::class, StyleFileUploadImplementation::class);
		$this->app->singleton(WorkingHourSetupRepository::class, WorkingHourSetupImplementation::class);
		$this->app->singleton(TargetProcessSetupRepository::class, TargetProcessSetupImplementation::class);
		$this->app->singleton(SupplierSettingRepository::class, SupplierSettingImplementation::class);

		$this->app->singleton(InvRcvRepository::class, InvRcvImplementation::class);
		$this->app->singleton(InvYarnRcvRepository::class, InvYarnRcvImplementation::class);
		$this->app->singleton(InvYarnItemRepository::class, InvYarnItemImplementation::class);
		$this->app->singleton(InvYarnRcvItemRepository::class, InvYarnRcvItemImplementation::class);
		$this->app->singleton(InvYarnRcvItemSosRepository::class, InvYarnRcvItemSosImplementation::class);
		$this->app->singleton(InvYarnTransactionRepository::class, InvYarnTransactionImplementation::class);
		$this->app->singleton(InvIsuRepository::class, InvIsuImplementation::class);
		$this->app->singleton(InvYarnIsuRepository::class, InvYarnIsuImplementation::class);
		$this->app->singleton(InvYarnIsuItemRepository::class, InvYarnIsuItemImplementation::class);
		$this->app->singleton(InvYarnIsuRtnRepository::class, InvYarnIsuRtnImplementation::class);
		$this->app->singleton(InvYarnIsuRtnItemRepository::class, InvYarnIsuRtnItemImplementation::class);

		$this->app->singleton(InvYarnPoRtnRepository::class, InvYarnPoRtnImplementation::class);
		$this->app->singleton(InvYarnPoRtnItemRepository::class, InvYarnPoRtnItemImplementation::class);
		$this->app->singleton(InvYarnTransOutRepository::class, InvYarnTransOutImplementation::class);
		$this->app->singleton(InvYarnTransOutItemRepository::class, InvYarnTransOutItemImplementation::class);

		$this->app->singleton(InvYarnTransInRepository::class, InvYarnTransInImplementation::class);
		$this->app->singleton(InvYarnTransInItemRepository::class, InvYarnTransInItemImplementation::class);

		$this->app->singleton(InvDyeChemRcvRepository::class, InvDyeChemRcvImplementation::class);
		$this->app->singleton(InvDyeChemRcvItemRepository::class, InvDyeChemRcvItemImplementation::class);
		$this->app->singleton(InvDyeChemTransactionRepository::class, InvDyeChemTransactionImplementation::class);

		$this->app->singleton(InvDyeChemIsuRqRepository::class, InvDyeChemIsuRqImplementation::class);
		$this->app->singleton(InvDyeChemIsuRqItemRepository::class, InvDyeChemIsuRqItemImplementation::class);
		$this->app->singleton(InvDyeChemIsuRepository::class, InvDyeChemIsuImplementation::class);
		$this->app->singleton(InvDyeChemIsuItemRepository::class, InvDyeChemIsuItemImplementation::class);

		$this->app->singleton(InvTrimRcvRepository::class, InvTrimRcvImplementation::class);
		$this->app->singleton(InvTrimItemRepository::class, InvTrimItemImplementation::class);
		$this->app->singleton(InvTrimRcvItemRepository::class, InvTrimRcvItemImplementation::class);
		$this->app->singleton(InvTrimTransactionRepository::class, InvTrimTransactionImplementation::class);


		$this->app->singleton(InvGeneralRcvRepository::class, InvGeneralRcvImplementation::class);
		$this->app->singleton(InvGeneralRcvItemRepository::class, InvGeneralRcvItemImplementation::class);
		$this->app->singleton(InvGeneralTransactionRepository::class, InvGeneralTransactionImplementation::class);
		$this->app->singleton(InvGeneralRcvItemDtlRepository::class, InvGeneralRcvItemDtlImplementation::class);
		$this->app->singleton(InvGeneralIsuRqRepository::class, InvGeneralIsuRqImplementation::class);
		$this->app->singleton(InvGeneralIsuRqItemRepository::class, InvGeneralIsuRqItemImplementation::class);
		$this->app->singleton(InvGeneralIsuRepository::class, InvGeneralIsuImplementation::class);
		$this->app->singleton(InvGeneralIsuItemRepository::class, InvGeneralIsuItemImplementation::class);

		$this->app->singleton(InvGreyFabRcvRepository::class, InvGreyFabRcvImplementation::class);
		$this->app->singleton(InvGreyFabRcvItemRepository::class, InvGreyFabRcvItemImplementation::class);
		$this->app->singleton(InvGreyFabItemRepository::class, InvGreyFabItemImplementation::class);
		$this->app->singleton(InvGreyFabTransactionRepository::class, InvGreyFabTransactionImplementation::class);
		$this->app->singleton(InvGreyFabIsuRepository::class, InvGreyFabIsuImplementation::class);
		$this->app->singleton(InvGreyFabIsuItemRepository::class, InvGreyFabIsuItemImplementation::class);

		$this->app->singleton(InvFinishFabRcvRepository::class, InvFinishFabRcvImplementation::class);
		$this->app->singleton(InvFinishFabRcvItemRepository::class, InvFinishFabRcvItemImplementation::class);
		$this->app->singleton(InvFinishFabItemRepository::class, InvFinishFabItemImplementation::class);
		$this->app->singleton(InvFinishFabTransactionRepository::class, InvFinishFabTransactionImplementation::class);
		$this->app->singleton(InvFinishFabIsuRepository::class, InvFinishFabIsuImplementation::class);
		$this->app->singleton(InvFinishFabIsuItemRepository::class, InvFinishFabIsuItemImplementation::class);
		$this->app->singleton(InvFinishFabRcvFabricRepository::class, InvFinishFabRcvFabricImplementation::class);



		//$this->app->singleton(RcvYarnBalanceRepository::class,RcvYarnBalanceImplementation::class);
		$this->app->singleton(IleConfigRepository::class, IleConfigImplementation::class);
		$this->app->singleton(CostStandardRepository::class, CostStandardImplementation::class);
		$this->app->singleton(CostStandardHeadRepository::class, CostStandardHeadImplementation::class);
		$this->app->singleton(ExpDocPrepStdDayRepository::class, ExpDocPrepStdDayImplementation::class);

		$this->app->singleton(ProdKnitRepository::class, ProdKnitImplementation::class);
		$this->app->singleton(ProdKnitItemRepository::class, ProdKnitItemImplementation::class);
		$this->app->singleton(ProdKnitItemRollRepository::class, ProdKnitItemRollImplementation::class);
		$this->app->singleton(ProdKnitItemYarnRepository::class, ProdKnitItemYarnImplementation::class);
		$this->app->singleton(ProdKnitRcvByQcRepository::class, ProdKnitRcvByQcImplementation::class);
		$this->app->singleton(ProdKnitQcRepository::class, ProdKnitQcImplementation::class);
		$this->app->singleton(ProdKnitDlvRepository::class, ProdKnitDlvImplementation::class);
		$this->app->singleton(ProdKnitDlvRollRepository::class, ProdKnitDlvRollImplementation::class);

		$this->app->singleton(ProdBatchRepository::class, ProdBatchImplementation::class);

		$this->app->singleton(ProdBatchRollRepository::class, ProdBatchRollImplementation::class);

		$this->app->singleton(ProdBatchTrimRepository::class, ProdBatchTrimImplementation::class);
		$this->app->singleton(ProdBatchProcessRepository::class, ProdBatchProcessImplementation::class);
		$this->app->singleton(ProdBatchFinishProgRepository::class, ProdBatchFinishProgImplementation::class);
		$this->app->singleton(ProdBatchFinishProgChemRepository::class, ProdBatchFinishProgChemImplementation::class);
		$this->app->singleton(ProdBatchFinishProgRollRepository::class, ProdBatchFinishProgRollImplementation::class);
		$this->app->singleton(ProdBatchFinishQcRepository::class, ProdBatchFinishQcImplementation::class);
		$this->app->singleton(ProdBatchFinishQcRollRepository::class, ProdBatchFinishQcRollImplementation::class);
		$this->app->singleton(ProdFinishDlvRepository::class, ProdFinishDlvImplementation::class);
		$this->app->singleton(ProdFinishDlvRollRepository::class, ProdFinishDlvRollImplementation::class);

		$this->app->singleton(ProdAopBatchRepository::class, ProdAopBatchImplementation::class);
		$this->app->singleton(ProdAopBatchRollRepository::class, ProdAopBatchRollImplementation::class);
		$this->app->singleton(ProdAopBatchProcessRepository::class, ProdAopBatchProcessImplementation::class);

		$this->app->singleton(ProdFinishMcSetupRepository::class, ProdFinishMcSetupImplementation::class);
		$this->app->singleton(ProdFinishMcDateRepository::class, ProdFinishMcDateImplementation::class);
		$this->app->singleton(ProdFinishMcParameterRepository::class, ProdFinishMcParameterImplementation::class);
		$this->app->singleton(ProdFinishQcBillItemRepository::class, ProdFinishQcBillItemImplementation::class);
		$this->app->singleton(ProdAopMcSetupRepository::class, ProdAopMcSetupImplementation::class);
		$this->app->singleton(ProdAopMcDateRepository::class, ProdAopMcDateImplementation::class);
		$this->app->singleton(ProdAopMcParameterRepository::class, ProdAopMcParameterImplementation::class);
		$this->app->singleton(ProdFinishAopMcSetupRepository::class, ProdFinishAopMcSetupImplementation::class);
		$this->app->singleton(ProdFinishAopMcDateRepository::class, ProdFinishAopMcDateImplementation::class);
		$this->app->singleton(ProdFinishAopMcParameterRepository::class, ProdFinishAopMcParameterImplementation::class);
		//$this->app->singleton(ProdKnitRefRepository::class,ProdKnitRefImplementation::class);
		//$this->app->singleton(ProdKnitRefPlItemRepository::class,ProdKnitRefPlItemImplementation::class);
		//$this->app->singleton(ProdKnitRefPoItemRepository::class,ProdKnitRefPoItemImplementation::class);
		//$this->app->singleton(ProdKnitRefItemRepository::class,ProdKnitRefItemImplementation::class);

		$this->app->singleton(CashIncentiveRefRepository::class, CashIncentiveRefImplementation::class);
		$this->app->singleton(CashIncentiveDocPrepRepository::class, CashIncentiveDocPrepImplementation::class);
		$this->app->singleton(CashIncentiveYarnBtbLcRepository::class, CashIncentiveYarnBtbLcImplementation::class);
		$this->app->singleton(CashIncentiveClaimRepository::class, CashIncentiveClaimImplementation::class);
		$this->app->singleton(CashIncentiveLoanRepository::class, CashIncentiveLoanImplementation::class);
		$this->app->singleton(CashIncentiveFileRepository::class, CashIncentiveFileImplementation::class);
		$this->app->singleton(CashIncentiveFileQueryRepository::class, CashIncentiveFileQueryImplementation::class);
		$this->app->singleton(CashIncentiveRealizeRepository::class, CashIncentiveRealizeImplementation::class);
		$this->app->singleton(CashIncentiveRealizeRcvRepository::class, CashIncentiveRealizeRcvImplementation::class);

		$this->app->singleton(CashIncentiveAdvRepository::class, CashIncentiveAdvImplementation::class);
		$this->app->singleton(CashIncentiveAdvClaimRepository::class, CashIncentiveAdvClaimImplementation::class);

		$this->app->singleton(LocalExpPiRepository::class, LocalExpPiImplementation::class);
		$this->app->singleton(LocalExpPiOrderRepository::class, LocalExpPiOrderImplementation::class);
		$this->app->singleton(LocalExpLcRepository::class, LocalExpLcImplementation::class);
		$this->app->singleton(LocalExpLcTagPiRepository::class, LocalExpLcTagPiImplementation::class);
		$this->app->singleton(LocalExpInvoiceRepository::class, LocalExpInvoiceImplementation::class);
		$this->app->singleton(LocalExpInvoiceOrderRepository::class, LocalExpInvoiceOrderImplementation::class);
		$this->app->singleton(LocalExpDocSubAcceptRepository::class, LocalExpDocSubAcceptImplementation::class);
		$this->app->singleton(LocalExpDocSubInvoiceRepository::class, LocalExpDocSubInvoiceImplementation::class);
		$this->app->singleton(LocalExpDocSubBankRepository::class, LocalExpDocSubBankImplementation::class);
		$this->app->singleton(LocalExpDocSubTransRepository::class, LocalExpDocSubTransImplementation::class);
		$this->app->singleton(LocalExpProRlzRepository::class, LocalExpProRlzImplementation::class);
		$this->app->singleton(LocalExpProRlzDeductRepository::class, LocalExpProRlzDeductImplementation::class);
		$this->app->singleton(LocalExpProRlzAmountRepository::class, LocalExpProRlzAmountImplementation::class);
		$this->app->singleton(SrmProductReceiveRepository::class, SrmProductReceiveImplementation::class);
		$this->app->singleton(SrmProductReceiveDtlRepository::class, SrmProductReceiveDtlImplementation::class);
		$this->app->singleton(SrmProductSaleRepository::class, SrmProductSaleImplementation::class);
		$this->app->singleton(SrmProductScanRepository::class, SrmProductScanImplementation::class);
		$this->app->singleton(GateEntryRepository::class, GateEntryImplementation::class);
		$this->app->singleton(GateEntryItemRepository::class, GateEntryItemImplementation::class);
		$this->app->singleton(GateOutRepository::class, GateOutImplementation::class);
		$this->app->singleton(GateOutItemRepository::class, GateOutItemImplementation::class);
		$this->app->singleton(RegisterVisitorRepository::class, RegisterVisitorImplementation::class);
		$this->app->singleton(AgreementRepository::class, AgreementImplementation::class);
		$this->app->singleton(AgreementFileRepository::class, AgreementFileImplementation::class);
		$this->app->singleton(AgreementPoRepository::class, AgreementPoImplementation::class);
		$this->app->singleton(EmployeeBudgetRepository::class, EmployeeBudgetImplementation::class);
		$this->app->singleton(EmployeeBudgetPositionRepository::class, EmployeeBudgetPositionImplementation::class);
		$this->app->singleton(EmployeeRecruitReqRepository::class, EmployeeRecruitReqImplementation::class);
		$this->app->singleton(EmployeeRecruitReqReplaceRepository::class, EmployeeRecruitReqReplaceImplementation::class);
		$this->app->singleton(EmployeeRecruitReqJobRepository::class, EmployeeRecruitReqJobImplementation::class);

		$this->app->singleton(JhuteSaleDlvOrderRepository::class, JhuteSaleDlvOrderImplementation::class);
		$this->app->singleton(JhuteSaleDlvOrderItemRepository::class, JhuteSaleDlvOrderItemImplementation::class);
		$this->app->singleton(JhuteSaleDlvOrderPaymentRepository::class, JhuteSaleDlvOrderPaymentImplementation::class);
		$this->app->singleton(JhuteSaleDlvOrderQtyRepository::class, JhuteSaleDlvOrderQtyImplementation::class);
		$this->app->singleton(JhuteStockRepository::class, JhuteStockImplementation::class);
		$this->app->singleton(JhuteStockItemRepository::class, JhuteStockItemImplementation::class);
		$this->app->singleton(JhuteSaleDlvRepository::class, JhuteSaleDlvImplementation::class);
		$this->app->singleton(JhuteSaleDlvItemRepository::class, JhuteSaleDlvItemImplementation::class);

		$this->app->singleton(ApprovalCommentHistoryRepository::class, ApprovalCommentHistoryImplementation::class);

		$this->app->singleton(SmsSetupRepository::class, SmsSetupImplementation::class);
		$this->app->singleton(SmsSetupSmsToRepository::class, SmsSetupSmsToImplementation::class);
		$this->app->singleton(MailSetupRepository::class, MailSetupImplementation::class);
		$this->app->singleton(MailSetupEmailToRepository::class, MailSetupEmailToImplementation::class);
	}
}
