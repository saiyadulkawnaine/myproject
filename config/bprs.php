<?php
return [
   //***************
   'teamtype' => [1 => 'Gmt.Marketing', 2 => 'Marchandising', 3 => 'Commercial', 4 => 'Txt.Marketing',],
   'fabrictype' => [1 => 'Knit', 2 => 'Woven'],
   'week' => [1 => 'Week 1', 2 => 'Week 2', 3 => 'Week 3'],
   'designation' => [1 => 'Managing Derector', 2 => 'Manager'],
   'location' => [1 => 'Singapur', 2 => 'China'],

   'tnatask' => [
      1 => "Order Placing Date",
      2 => "Risk Evaluation",
      3 => "Order Acceptance",
      4 => "Fit Sample Submission",
      5 => "Fit Sample Approval",
      6 => "PP Sample Submission",
      7 => "PP Sample Approval",
      8 => "Size Set Sample Submission",
      9 => "Size Set Sample Approval",
      10 => "Lab Dip Submission",

      11 => "Lab Dip Approval",
      12 => "Sewing Trims Submission",
      13 => "Sewing Trims Approval",
      14 => "Printing Sample Submission",
      15 => "Printing Sample Approval",
      16 => "Embroidery Sample Submission",
      17 => "Embroidery Sample Approval",
      18 => "Garments Wash Sample Submission",
      19 => "Garments Wash Sample Approval",
      20 => "Fabric Swatch Submission",

      21 => "Fabric Swatch Approved",
      22 => "Garments Test Submission",
      23 => "Garments Test Approval",
      24 => "Yarn Inhouse",
      25 => "Fabric Work Order Issued",
      26 => "Trims Work Order Issued",
      27 => "Fabric Service Work Order Issued",
      28 => "Grey Yarn Sent to Yarn Dyeing",
      29 => "Dyed Yarn Received",
      30 => "Grey Fabric Production to be completed",

      31 => "Grey Fabric In-house",
      32 => "Dyeing Production to be completed",
      33 => "Fabric Sent For AOP",
      34 => "AOP Production to be completed",
      35 => "Finished Fabric Production to be completed",
      36 => "Finished Fabric In-house",
      37 => "Sewing Trims In-house",
      38 => "Packing/Finishing Trims In-house",
      39 => "PP Meeting Conducted",
      40 => "Cutting to be completed",

      41 => "Sent for Screen Print",
      42 => "Receive after Screen Print",
      43 => "Sewing to be completed",
      44 => "Ironing to be completed",
      45 => "Packing/Cartoning to be completed",
      46 => "Inspection to be offered",
      47 => "Inspection to be conducted",
      48 => "Ex-factory to be taken place",
      49 => "Document to be prepared",
      50 => "Document to be submitted",

      51 => "Proceeds to be realized",
      52 => "Yarn Issue To Knitting",
   ],
   'shorttype' => [
      1 => "Abnormal Process Loss",
      2 => "Bad Quality Supplied",
      3 => "Missing In Store",
      4 => "Wrong Specification Supplied",
   ],

   'keycontrolparameter' => [1 => 'Asking Profit %', 2 => 'Monthly Budgeted CM Expense', 3 => 'No. of Factory Machine ', 4 => 'CM Cost Per Minute Per Machine', 5 => 'Standard Basic GMT Price', 6 => 'Average CM Cost Per Line/Day', 7 => 'Per Unit Price For BEP', 8 => 'Dyeing Overhead/kg', 9 => 'AOP Overhead/kg', 10 => 'Screen Print Overhead/Dzn', 11 => 'Fab.Finishing Overhead/kg', 12 => 'Unlayable %', 13 => 'Cost Per KG Per Machine', 14 => 'No of Batch Per Day', 15 => 'Cash Incentive Process Loss %'],
   //**********
   'cutoff' => [1 => '1st Cutoff', 2 => '2nd Cutoff', 3 => '3rd Cutoff'],
   'uomclass' => [1 => 'Count', 2 => 'Weight', 3 => 'Lenght', 4 => 'Liquid', 5 => 'Others'],
   'status' => [0 => "In-active", 1 => "Active", 2 => "Cancel", 3 => "Refused", 4 => "Closed"],

   'companynature' => [1 => "Garment Manufacturing", 5 => "Trading", 10 => "Dyeing Service", 11 => "AOP Service", 12 => "Fab.Finish Service", 15 => "Screen Printing Service", 20 => "Non-Profit"],
   'legalstatus' => [1 => "Private Ltd", 5 => "Public Ltd", 10 => "Proprietorship", 15 => "Partnership", 20 => "Education", 25 => "NGO", 30 => "Govt Own"],
   'yesno' => [0 => "No", 1 => "Yes"],
   'economylevel' => [1 => "Developed", 5 => "Developing", 10 => "Least Developed", 15 => "Poor"],
   'politicalstability' => [1 => "Stable", 2 => "Un-Stable"],
   'productionarea' => [
      1 => "Spining",
      5 => "Yarn Dyeing",
      10 => "Knitting",
      15 => "Weaving",
      20 => "Dyeing",
      21 => "Dyeing Support",
      25 => "AOP",
      28 => "Fabric Burn Out",
      30 => "Fabric Finishing",
      35 => "Fabric Washing",
      40 => "Cutting",
      45 => "Printing",
      50 => "Embroidery",
      51 => "Special Embellishment",
      55 => "Sewing",
      58 => "GMT Dyeing",
      60 => "GMT Washing",
      65 => "Iron",
      67 => "Poly",
      70 => "GMT Finishing",
      75 => "Sweater"
   ],
   'gmtcomplexity' => [1 => "Basic", 5 => "Fancy", 10 => "Jacket", 15 => "Bledger", 20 => "Hudy", 25 => "Bottom"],
   'region' => [1 => "Europe", 5 => "America", 10 => "Australia", 15 => "Asia", 20 => "Africa", 25 => "North America", 30 => "South America"],
   'usersource' => [1 => "HRM system", 2 => "Independent"],
   'discountmethod' => [1 => "1/30/60", 2 => "2/10/30", 3 => "3/10/15"],
   'calculatorneed' => [1 => "Sewing Thread", 2 => "Poly Pack", 3 => "Poly Sticker", 4 => "Carton", 5 => "Carton Sticker"],
   'parttype' => [1 => "Top - Main", 2 => "Bottom - Main", 3 => "Top-Bottom", 4 => "Narrow Fabric", 99 => "Others"],
   'customernature' => [1 => "Garments Customer", 5 => "Knit Customer", 10 => "Dyeing Customer", 15 => "Finishing Customer", 20 => "Garments Washing Customer", 25 => "Embellishment Customer", 30 => "Yarn Dyeing Customer", 35 => "Spinning Customer", 40 => "Trims Customer", 45 => "Buying House", 50 => "Leather Customer", 55 => "Sweater Customer", 60 => "Hotel Customer", 65 => "Developing Customer", 90 => "Notifying Party", 91 => "Consignee", 92 => "LC Applicant"],
   'dayname' => [1 => "Saturday", 2 => "Sunday", 3 => "Monday", 4 => "Tuesday", 5 => "Wednesday", 6 => "Thursday", 7 => "Friday"],
   'itemcategory' => [1 => "Yarn", 5 => "Knit Gray Fabric", 10 => "Knit Finish Fabric", 15 => "Woven Gray Fabric", 20 => "Woven Finish Fabric", 25 => "Trims Fabric", 30 => "Dyes", 35 => "Chemicals", 40 => "Dyes and Chemical", 45 => "ETP Chemicals", 50 => "Printing Dyes and Chemical", 55 => "Trims/Accessories", 60 => "Knit Garments", 65 => "Woven Garments", 70 => "Sweater", 75 => "Leather Garments", 80 => "Spare Parts - Electrical", 85 => "Spare Parts - Mechanical", 90 => "Spare Parts - Vehicle", 95 => "Oil and Lubricants", 100 => "Stationery", 105 => "Grocery Item", 110 => "Cosmetic Item", 115 => "Food Item", 120 => "ICT", 125 => "Medical", 130 => "Machineries", 135 => "Other Capital Item", 140 => "Construction Item", 145 => "Paint and Varnish"],
   'membertype' => [1 => "Member", 2 => "Leader", 3 => "Factory Merchandiser"],
   'trimstype' => [1 => "Normal Sewing Trims", 2 => "Fancy Sewing Trims", 3 => "Packing Trims", 4 => "Gray Yarn", 5 => "Dyed Yarn"],
   'itemnature' => [1 => "Basic Raw Materials", 20 => "Auxiliary Raw Materials", 50 => "Indirect Materials", 100 => "Revenue Item", 200 => "Assets", 220 => "Maintenance Item", 250 => "Stationery"],

   'suppliernature' => [1 => "Yarn Supplier", 5 => "Yarn Loan Party", 10 => "Trims Supplier", 15 => "Dyes & Chemical Suppier", 20 => "Dyes & Chemical Loan Party", 25 => "Spare Parts Supplier", 30 => "Fuel & Lubricants", 35 => "Knit Subcontractor", 40 => "Dye/Fin Subcontractor", 45 => "Washing Subcontractor", 50 => "Garments Subcontractor", 55 => "Embellishment Subcontractor", 60 => "Plant & Machine Supplier", 65 => "Stationery Supplier", 70 => "AOP Subcontractor", 75 => "C & F Agent", 80 => "Transport Agent", 85 => "Labor Agent", 90 => "Civil Contractor", 95 => "Interior Contractor", 100 => "Indentor", 105 => "Inspection Company", 110 => "Auditor", 115 => "Lawyer ", 120 => "Grocery Item", 125 => "Cosmetic Item", 130 => "Food Item", 135 => "ICT", 140 => "Medical", 145 => "Courier Service"],


   'gmtcategory' => [
      1 => "T. Shirt", 5 => "Polo. Shirt", 10 => "Tank Top", 15 => "Jacket", 18 => "Cardigan", 20 => "Bledger", 23 => "Full Pant", 25 => "Half Pant",
      26 => "Payjama", 27 => "Trouser", 30 => "Skirt", 35 => "Legins", 36 => "Romper", 40 => "Dress", 45 => "Cap", 50 => "Socks", 55 => "Bra", 60 => "Under Wear", 65 => "Boxer"
   ],

   'sampletype' => [1 => "Development", 5 => "Prototype", 10 => "Size-Set", 15 => "Salesman", 20 => "PP", 25 => "Product Testing", 30 => "Bulk Production", 35 => "Shipment", 40 => "Fit", 45 => "Photo", 50 => "Advertisement", 55 => "Lab Test", 60 => "Inline", 65 => "Cartage", 70 => "Packing", 75 => "Licesor", 80 => "E-Commerce"],
   'tnaarea' => [1 => "Merchandising", 5 => "Procurement", 10 => "Textime", 15 => "GMT Production", 20 => "Shipment"],
   'delayfor' => [1 => "01. Garment Sample Production", 5 => "02. Garment Sample Approval", 10 => "03. Lab Dip Approval", 15 => "04. Yarn Purchase", 20 => "05. Yarn Deliivery", 25 => "06. Grey Production", 30 => "07. AOP", 35 => "08. Dyeing & Finishing", 40 => "09. Fabric Purchase", 45 => "10. Accessories Purchase", 50 => "11. Cutting", 55 => "12. Printing", 60 => "13. Embroidery", 65 => "14. Sewing", 70 => "15. Washing", 75 => "16. Iron", 80 => "17. Finishing"],
   'productionsource' => [1 => "In-house", 5 => "Sub Contract Outside"],
   'resourcemeans' => [1 => "Machine", 5 => "Sewing Helper", 10 => "Sewing QI", 15 => "Finishing IM", 20 => "Finishing QI", 25 => "Poly Helper", 30 => "Packing Worker"],
   'smvbasis' => [1 => "Manual Input", 5 => "Calculative"],
   'fabricshape' => [1 => "Tube", 5 => "Open", 10 => "Needle Drop", 15 => "Flat Knit"],
   'incentivebasis' => [1 => "Efficiency % Based", 5 => "Achivement % Based"],
   'configuration' => [1 => "TNA Integrated", 5 => "TNA Process Starting Date", 10 => "Sales Year Started", 15 => "Process Loss Method", 20 => "SMV Source in Order Entry", 25 => "Commercial Cost Method", 30 => "Standard service Rate Used", 35 => "Price Quotation copy to BOM", 40 => "Fabric Consumption Basis", 45 => "Standard Service Rate", 50 => "Color From List", 55 => "Auto Yarn Calculation In MOB", 60 => "Entry Edit Hour Limit", 65 => "Cost Payment Medium", 70 => "Production Entry In Machine Level", 75 => "Separate Device For Fabric Roll Barcode", 80 => "Separate Device For Bundle Barcode", 85 => "Sewing Man Power Allocation", 90 => "GMT Production Recording Level", 95 => "Standard Landed Cost %", 100 => "Purchase Order Tolerance on BOM", 105 => "Standard Level Fix up", 110 => "Sewing Hour Started", 115 => "Cut Pcs Delivery Mode", 120 => "GMT No 1 To Be Repeated in Lay", 125 => "Export Doc Progress Days Standard", 130 => "Stock Balancing Method", 135 => "Fabric in Roll Level", 140 => "Standard Overhead %"],
   'fabriclooks' => [1 => "Solid", 5 => "Solid - Dyed Yarn", 8 => "Eng.Stripe Y/D", 10 => "Stripe - Dyed Yarn", 15 => "Cross Over - Dyed Yarn", 20 => "Check - Dyed Yarn", 25 => "AOP", 30 => "Burn Out"],
   'fabricnature' => [1 => "Knit Fabric", 5 => "Woven Fabric", 10 => "Lather", 15 => "Raxin"],
   'approvalstatus' => [1 => "Yet to Submit", 5 => "Sumbitted", 10 => "Approved", 15 => "Refused ", 20 => "Re-submitted"],
   'fabricinstructions' => [1 => "Any Fabric", 5 => "Any Fabric Exact Color", 10 => "Any Fabric Exact Size", 15 => "Exact Fabric Exact Color", 20 => "Exact Fabric Exact Size", 25 => "Exact Fabric Exact Color & Exact Size", 30 => "From Trial Cut", 35 => "From Bulk Cut", 40 => "From Bulk Production"],
   'materialsourcing' => [1 => "Full Purchase", 5 => "Partial Purchase", 10 => "Full Production", 15 => "Partial Production ", 20 => "From Left Over Fully", 25 => "From Left Over Partially", 30 => "Buyer Supplied Fully", 35 => "Buyer Supplied Partially", 40 => "From Stock Fully", 45 => "From Stock Partially"],
   'costingunit' => [1 => "Pcs", 12 => "Dzn"],
   'costbearer' => [1 => "Supplier/Seller", 5 => "Importer/Buyer"],
   'othercosthead' => [1 => "Courier", 5 => "Lab Test", 10 => "Inspection Certificate", 15 => "Freight", 20 => "Operating Cost", 25 => "Depreciation", 30 => "Cost of Capital", 35 => "Insurance"],
   'deptcategory' => [1 => "Men", 5 => "Ladies", 10 => "Teen Age", 15 => "Todler", 20 => "Kids", 25 => "Infant", 30 => "New Born"],
   'operationtype' => [1 => "GMT Specific", 5 => "Independent"],
   'lineshape' => [1 => "Straight Line Double", 5 => "Straight Line Single", 10 => "U-Shape"],
   'sensivity' => [1 => "As Per GMT Color", 5 => "Contrast Color", 10 => "Size Sensitive", 15 => "Color & Size Sensitive"],
   'processlosscalculation' => [1 => "Markup Method", 5 => "Margin Method"],
   'costpaymode' => [1 => "replace it"],
   'paymode' => [1 => "IOU", 2 => "Payment After Delivery", 3 => "LC After Delivery", 4 => "LC Before Delivery", 5 => "Loan", 6 => "Cash/Other Instrument"],

   'gmtproductionrecordlevel' => [1 => "Style Level", 5 => "Order Level", 10 => "Color & Size Level"],
   'purchasesource' => [1 => "Foreign", 5 => "EPZ", 10 => "Local"],
   'cmcostformula' => [1 => "((SMV*CPM)*12 + (SMV*CPM)* Efficiency Wastage%)/Exchange Rate", 5 => "(((Cut SMV*CPM) *12 / Efficiency %)+ ((sew SMV*CPM) *12 / Efficiency %))/Exchange", 10 => "{(Monthly CM Exp./26)/NFM)*MPL)}/[{(PHL)*WH}]*12/Exchange Rate", 15 => "((SMV*CPM)*12)/Exchange Rate"],
   'standardlevelvalue' => [1 => "Cotton Stock", 5 => "Yarn Stock", 10 => "Spinning Capacity Achievement %", 15 => "Knitting Capacity Achievement %", 20 => "Weaving Capacity Achievement %", 25 => "Dyeing and Finishing Capacity Achievement %", 30 => "Cutting Target Achievement %", 35 => "Cutting Efficiency %", 40 => "Sewing Target Achievement %", 45 => "Sewing Efficiency %", 50 => "Finishing Target Achievement %", 55 => "Finishing Efficiency %"],
   'shiftname' => [1 => "Shift A", 2 => "Shift B", 3 => "Shift C", 4 => "Shift D", 5 => "Shift E", 6 => "Shift F", 7 => "Shift G"],
   'gmtdelivery' => [1 => "Bundle Wise", 5 => "Buyer Order Wise"],
   'gmtnorepeat' => [1 => "For new Job", 5 => "For new Order", 10 => "For new Size", 15 => "For next Pattern", 20 => "For New Cut Number"],
   'smvsourceinorder' => [1 => "Operation Bulletin", 5 => "Manual", 10 => "GMT Complexity Mapping"],
   'appliedfor' => [1 => "Marketing Costing", 5 => "Bills Of Material", 10 => "Service Work Order"],
   'commercialcostformula' => [1 => "Fabric Purchase Cost + Trims Fabric Cost + Yarn Cost + Trims Cost + Embellishment Cost", 5 => "Fabric Purchase Cost + Trims Fabric Cost + Yarn Cost + Trims Cost", 10 => "Fabric Purchase Cost + Trims Fabric Cost + Yarn Cost", 15 => "Amount Manually", 20 => "Rate Manually"],
   'exportdocprogressevent' => [1 => "Ex-factory", 5 => "BL/AWB/Transport Challan", 10 => "Certificate of Origin", 15 => "GSP", 20 => "Buyer Submission", 25 => "Bank Submission", 30 => "Payment Advice"],
   'stockbalancemethod' => [1 => "Weighted Average", 5 => "FIFO", 10 => "LIFO"],

   'fabricrollmaintained' => [1 => "Grey Fabric Production", 5 => "Grey Fabric receive By QC", 10 => "Grey Fabric QC", 15 => "Grey Fabric Delivery To Store", 20 => "Grey Fabric Receive By Store", 25 => "Grey Fabric Issue", 30 => "Grey Fabric Receive By Batch", 35 => "Batch Creation", 40 => "Heat Setting", 45 => "Singeing", 50 => "Dyeing", 55 => "Squeezing", 60 => "Drying", 65 => "Issue To SubCon", 70 => "Receive From SubCon", 75 => "Different Finishing", 80 => "Compacting", 85 => "Finish Fabric QC", 90 => "Finish Fabric Delivery To Store", 95 => "Finish Fabric Receive By Store", 100 => "Finish Fabric Issue", 105 => "Finish Fabric Receive By Cutting", 110 => "Finish Fabric Lay"],

   'commissionbasis' => [1 => "% on Cost", 5 => "Amount on GMT Qty", 10 => "% on Selling Price"],
   'orderstage' => [1 => "Development Stage", 5 => "Cofirm Order"],
   'sampleresource' => [1 => "Machine", 5 => "Worker", 10 => "Line"],
   'orderform' => [1 => "Direct", 5 => "Projection"],
   'assortment' => [1 => "ACAS", 2 => "SCSS", 3 => "SCAS", 4 => "ACSS", 5 => "Unassorted"],
   'breakdownbasis' => [1 => "Manual", 2 => "Packing Ratio"],
   'identity' => [1 => "Yarn", 2 => "Grey Fabric - Knit", 3 => "Grey Fabric - Woven", 4 => "Finish Fabric - Knit", 5 => "Finish Fabric - Woven", 6 => "Accessories", 7 => "Dyes", 8 => "Chemical", 9 => "General Item"],
   'calculatorneed' => [1 => "Sewing Thread", 2 => "Poly Pack", 3 => "Poly Sticker", 4 => "Carton", 5 => "Carton Sticker"],
   'dyetype' => [1 => "Single Part", 2 => "Double Part"],
   'cmmethod' => [1 => "(((SMV*CPM)*Costing per) / Efficiency %)"],
   'embelishmentsize' => [1 => "Logo", 2 => "Small", 3 => "Medium", 4 => "Large", 5 => "Extra Large", 5 => "Double Extra Large"],

   'accchartgroup' => [1 => "Owners Equity", 4 => "Non-Current Liabilities", 7 => "Current Liabilities", 10 => "Non-Current Assets", 11 => "Accumulated Depreciation", 13 => "Current Assets", 16 => "Operating Revenue", 19 => "Cost of Goods Sold", 22 => "Operating Expenses", 24 => "Financial Expenses", 25 => "Non-Operating Revenue", 28 => "Non-Operating Expenses", 50 => "Extra Ordinary Items", 55 => "Tax Expenses", 60 => "P & L Appropriation A/C"],

   'bankType' => [1 => "Commercial", 2 => "Retail", 3 => "Investment", 4 => "Cooperative", 5 => "Central"],
   'accountType' => [1 => 'Personal', 2 => 'Corporate', 3 => 'Joint'],
   'statementType' => [1 => "Balance Sheet", 2 => "Income Statements", 3 => "Retained Earnings", 4 => "contingent"],

   'controlname' => [1 => "AP", 2 => "Import Payable", 5 => "Advance Received", 6 => "Export Negotiation Liability", 7 => "Other Trade Finance", 10 => "Tax at source from Suppliers' Bill", 12 => "Tax at source from Employees' Salary", 15 => "VAT at source from Suppliers' Bill", 20 => "Security at source from Suppliers' Bill", 30 => "AR", 31 => "Export Receivable", 32 => "Other Receivable", 35 => "Advance to supplier", 36 => "Advance to purchase", 37 => "Advance to Salary", 38 => "Advance to Other Party", 40 => "Tax at source from Sales Bill", 45 => "VAT at source from Sales Bill", 50 => "Security at source from Sales Bill", 60 => "Discount Allowed", 62 => "Discount Received", 65 => "Write-off Assets", 66 => "Write-off Liability", 70 => "Other Subsidiary", 80 => "ILE Control Account", 85 => "Goods In Transit", 90 => "WIP", 95 => "Goods Loan", 100 => "Cash Sale"],


   'otherType' => [
      1 => "Cash",
      2 => "Bank",
      3 => "OD/CC",
      10 => "Foreign Sales",
      11 => "Local Sales",
      12 => "Project Sales",
      20 => "Purchase",
      21 => "Project Cost",
      25 => "Bank Charges",
      28 => "Interest on Capital Investment",
      29 => "Interest on Working Capital",
      30 => "Currency Exchange Gain/Loss - Export",
      31 => "Currency Exchange Gain/Loss - Import",
      40 => "Project Common Cost",
      90 => "Depreciation, Amortization & Depletion",
      95 => "Rental Expenses",
   ],
   'normalbalance' => [1 => "Debit", 2 => "Credit"],

   'journalType' => [0 => 'Opening Balance', 1 => 'Cash Receive', 2 => 'Bank Receive', 3 => 'Cash Payment', 4 => 'Bank Payment', 5 => 'General Journal', 6 => 'Rectifying Journal', 7 => 'Provisional Journal'],
   'ctrlheadtype' => [1 => 'Chart Of Account', 2 => 'Report Head'],

   'expenseType' => [1 => "Variable", 2 => "Fixed"],
   'assetType' => [50 => "Land", 54 => "Road", 55 => "Building", 60 => "Furniture", 62 => "Fixtures", 65 => "Machinery", 70 => "Equipment", 72 => "Power Generation", 75 => "Computer & Server", 76 => "Printer, UPS & IPS", 80 => "Network System", 83 => "CC TV System", 85 => "IP & Conference System", 90 => "Electric Appliance", 100 => "Transportation", 120 => "Security & Safety", 121 => "Software", 125 => "Attendence Device", 130 => "Cleaning Equipment"],
   'depMethod' => [1 => "Straight Line Method", 2 => "Reducing Balance Method"],
   'gender' => [1 => "M", 2 => "F"],
   'payterm' => [1 => "Usance", 2 => "At Sight", 3 => "Cash In Advance", 4 => "Open Account"],
   'incoterm' => [1 => "FOB", 2 => "CFR", 3 => "CIF", 4 => "FCA", 5 => 'Ex-Works'],
   'exportingItem' => [1 => "Knit Garments", 2 => "Woven Garments", 8 => "Trims", 10 => "Knit Subcontract", 11 => "Dyeing & Finishing Subcontract", 12 => "AOP Subcontract", 16 => "Cutting Subcontract", 17 => "Sewing Subcontract", 18 => "Finishing Subcontract", 19 => "Iron Subcontract", 25 => "Printing Subcontract", 26 => "Embroidery Subcontract", 27 => "GMT Wash Subcontract"],
   'deliveryMode' => [1 => "Sea", 2 => "Air", 3 => "Road", 5 => "Sea/Air", 6 => "Sea/Road", 7 => "Air/Road"],
   'contractNature' => [1 => "Direct", 2 => "Replaceable By LC", 3 => "Replaced By SC"],
   'replacementlc' => [1 => "Direct", 2 => "From Contract"],

   'menu' => [
      0 => "Independent",
      1 => "Fabric Purchase Order",
      2 => "Trims Purchase Order",
      3 => "Yarn Purchase Order",
      4 => "Knit Work Order",
      5 => "AOP Work Order",
      6 => "Dyeing Work Order",
      7 => "Dyes & Chemical Purchase Order",
      8 => "General Item Purchase Order",
      9 => "Yarn Dyeing Work Order",
      10 => "Embellishment Purchase Order",
      11 => "General Service Work Order",
      50 => "Knitting Plan",
      100 => "Yarn Receive",
      101 => "Yarn Issue",
      102 => "Yarn Requisition",
      103 => "Purchase Requisition",
      104 => "Yarn Issue for Sample & Security",
      105 => "Yarn Issue Return",
      106 => "Yarn Issue Return for Sample & Security",
      107 => "Yarn Transfer Out",
      108 => "Yarn Transfer In",
      109 => "Purchase Requisition",
      110 => "Local Export PI",
      111 => "Yarn Receive Return",
      200 => "Dyes & Chemical Receive",

      201 => "General Item Receive",
      202 => "General Item Issue Requisition",
      203 => "General Item Issue",
      204 => "General Item Receive Return",
      205 => "General Item Issue Return",
      206 => "General Item Transfer Out",
      207 => "General Item Transfer In",

      208 => "Dyeing Issue Requisition",
      209 => "Dyeing Additional Issue Requisition",
      210 => "AOP Issue Requisition",
      211 => "Loan & Others Issue Requisition",

      212 => "Dyes & Chemical Issue",
      213 => "Dyes & Chemical Receive Return",
      214 => "Dyes & Chemical Issue Return",
      215 => "Dyes & Chemical Transfer Out",
      216 => "Dyes & Chemical Transfer In",

      217 => "Grey Fabric Receive",
      218 => "Grey Fabric Transfer In",
      219 => "Grey Fabric Issue Return",

      220 => "Grey Fabric Issue",
      221 => "Grey Fabric Transfer Out",
      222 => "Grey Fabric Receive Return",

      223 => "Screen Print Issue Requisition",

      224 => "Finish Fabric Purchase Receive",
      225 => "Finish Fabric Receive",
      226 => "Finish Fabric Transfer In",
      227 => "Finish Fabric Issue Return",

      228 => "Finish Fabric Issue",
      229 => "Finish Fabric Transfer Out",
      230 => "Finish Fabric Receive Return",

      280 => "Subcontract Dyeing Delivery",
      281 => "Subcontract AOP Delivery",
      282 => "Subcontract Knit Fabric Delivery",
      283 => "Asset Breakdown",
      284 => "Finishing Issue Requisition",

      285 => "Inhouse Dyeing Delivery To Store",
      286 => "Inhouse Dyeing Delivery To AOP",
      287 => "Inhouse AOP Delivery To Store",

      288 => "Additional Dyeing Bill",

      300 => "Trim Receive",
      301 => "Trim Transfer In",
      302 => "Trim Issue Return",

      303 => "Trim  Issue",
      304 => "Trim  Transfer Out",
      305 => "Trim  Receive Return",

      350 => "Jhute Sale & GMT Leftover Delivery",
      351 => "Subcontract Dyeing Marketing Cost",
      352 => "Subcontract AOP Marketing Cost",

      380 => "Asset Send Out for Repair",
      381 => "Asset Send Out for Servicing",
   ],

   'pur_order_basis' => [1 => "Bom/Budget", 2 => "Requisition", 20 => "Independent"],
   'lctype' => [
      1 => "Back To Back LC",
      2 => "Margin LC",
      3 => "Telegraphic Transfer (TT)",
      4 => "Fund Building LC",
      5 => "Foreign Demand Draft (FDD)"
   ],
   'maturityform' => [1 => "Shipment Date", 2 => "Bank Acceptance", 3 => "Negotiation", 4 => "Mixed Payment"],
   'docStatus' => [1 => "Copy", 2 => "Original"],

   'commercialheadtype' => [
      1 => "LC Opening",
      2 => "LC Amendment",
      3 => "BTB Doc Accepted Bill", // Previously Doc Acceptance
      4 => "Doc. Negotiation Liability",
      5 => "PC",
      6 => "ECC",
      7 => "Means of Retirement",
      8 => "BTB Margin",
      9 => "Project Loan",
      10 => "Export Realization",
      11 => "Discount",
      12 => "ERQ",
      13 => "MDA Normal",
      14 => "MDA Special",
      15 => "MDA UR",
      16 => "Source Tax",
      17 => "Foreign Bank Charge",
      18 => "CD Account",
      19 => "Deductions",
      20 => "Discrepancy",

      21 => "Sales Commission",
      22 => "Expense/Doc Purchase",
      23 => "Central Fund",
      24 => "Different Charges",
      25 => "Exchange Rate Variance",
      26 => "FDR",
      27 => "EDF", //27-31 add at 16-04-2022 4:23PM
      28 => "BTB Liability",
      29 => "Margin LC Liability",
      30 => "TR",
      31 => "Cash Incentive Advance",
      32 => "Margin LC Accepted Bill" //32 add at 23-05-2022 10:38AM
   ],

   'loantype' => [1 => "PC", 2 => "ECC"],
   'submissiontype' => [1 => "Negotiation", 2 => "Collection", 3 => "Endorsement"],
   'months' => [1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May", 6 => "June", 7 => "July", 8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December"],
   'years' => [2016 => 2016, 2017 => 2017, 2018 => 2018, 2019 => 2019, 2020 => 2020, 2021 => 2021, 2022 => 2022, 2023 => 2023, 2024 => 2024, 2025 => 2025, 2026 => 2026, 2027 => 2027, 2028 => 2028, 2029 => 2029, 2030 => 2030, 2031 => 2031, 2032 => 2032, 2033 => 2033, 2034 => 2034, 2035 => 2035, 2036 => 2036, 2037 => 2037, 2038 => 2038, 2039 => 2039, 2040 => 2040, 2041 => 2041, 2042 => 2042, 2043 => 2043, 2044 => 2044, 2045 => 2045, 2046 => 2046, 2047 => 2047, 2048 => 2048, 2049 => 2049, 2050 => 2050, 2051 => 2051],
   'ordersource' => [1 => "Self Order", 2 => "Subcontract Order"],
   'salaryProdBill' => [1 => "Salary", 2 => "Production Bill", 3 => "Raw Material"],
   'invreceivebasis' => [
      1 => "Regular",
      2 => "Opening Balance",
      3 => "Unknown",
      4 => "Issue Return",
      5 => "Sample After Order Receive Return",
      6 => "Sample Before Order Receive Return",
      7 => "Security Return",
      9 => "Transfer In",
      10 => "Loan Receive/Return"
   ],
   //'invYarnSource' => [1=>"Non-EPZ",2=>"Abroad",3=>"EPZ"],
   'sortby' => [1 => "Order Qty", 2 => "Order Value", 3 => "Yet to Ship Qty", 4 => "Yet to Ship Value"],
   'supplyfeature' => [1 => "ZDHC Gate Way", 2 => "Bluesign", 3 => "Oekotex", 4 => "GOTS", 5 => "Solid Content", 6 => "Strength", 7 => "Thermal Stability", 8 => "Foam Height", 9 => "Fastness", 10 => "TDS", 11 => "MSDS", 12 => "Ammonia", 13 => "Packaging", 14 => "Active Content", 15 => "Containing Solvent Free", 16 => "APEO Free"],
   'filequery' => [1 => "UD Amendment Copy Missing", 2 => "BTB Related Papers Short", 3 => "Proceeds Realized Amount not matched with        
    PRC due to rounding factor"],
   'invissuebasis' => [
      1 => "Regular",
      2 => "Unknown",
      4 => "Test",
      5 => "Damaged",
      6 => "Sample After Order Receive",
      7 => "Sample Before Order Receive",
      8 => "Security",
      9 => "Transfer Out",
      10 => "Loan Receive/Return",
      11 => "Receive Return"
   ],
   'paymentType' => [1 => "Cash", 2 => "Credit Card"],

   'invtranstype' => [1 => "Receive", 2 => "Issue", 3 => "Transfer In", 4 => "Security Return", 5 => "Transfer Out", 6 => "Security Issue", 7 => "Issue Return", 8 => "Receive Return", 9 => "Purchase Return"],
   'todopriority' => [1 => "Low", 2 => "Medium", 3 => "High"],
   'generalisurqpurpose' => [1 => " Spare Parts Consumption", 2 => "Repair & Maintenance", 3 => "Construction Work", 4 => "Decoration Work", 5 => "Garments Production", 6 => "Dyeing/Finishing Production", 7 => "AOP Production", 8 => "General Consumption"],

   'dyeingsubprocess' => [
      1 => "Demineralization",
      10 => "Pre-Treatment",
      20 => "1st Neutralization",
      21 => "2nd Neutralization",
      22 => "3rd Neutralization",
      23 => "4th Neutralization",
      30 => "Biopolish",
      35 => "Dyestuff",
      40 => "Dyeing Bath",
      50 => "After Treatment",
      60 => "Finishing",
      70 => "Others"
   ],

   'dyechemrequisitionbasis' => [
      1 => "Dyeing",
      2 => "Additional",
      3 => "Aop",
      4 => "Screen Print",
      5 => "Loan Issue/Return",
      6 => "M/C Wash",
      7 => "Others",
      8 => "Fabric Finish",
   ],
   'availDocs' => [
      1 => "Available Invoice",
      2 => "Submitted for Acceptance",
      3 => "Party Acceptance Available",
      4 => "Submitted at Bank",
      5 => "Maturity Received",
      6 => "Placed to purchase",
      7 => "Realization Available",
   ],

   'tergetProcess' => [
      1 => 'Knitting',
      2 => 'Dyeing',
      3 => 'Dyeing Finishing',
      4 => 'AOP',
      5 => 'Cutting',
      6 => 'Screen Printing',
      7 => 'Embroidery',
      8 => 'Sewing',
      9 => 'Finishing',
      10 => 'Ex Factory',
      11 => 'Iron',
      12 => 'Poly',
   ],
   'rollqcresult' => [
      1 => 'A',
      2 => 'B',
      3 => 'C',
      4 => 'D',
      5 => 'E',
      6 => 'F',
   ],

   'meetingtype' => [
      1 => 'Email Communication',
      2 => 'Tele Communication',
      3 => 'Meeting In Person',
      4 => 'Video Conference'
   ],
   'credittype' => [
      1 => 'Sales Contract',
      2 => 'Export LC',
      3 => 'Replaceing LC'
   ],

   'buyerdlvstatus' => [
      1 => 'Open',
      2 => 'Under Trial',
      3 => 'Developed',
      4 => 'Refused'
   ],

   'consumptionlevel' => [
      1 => 'Fast Moving',
      2 => 'Special'
   ],
   'batchfor' => [
      1 => 'Self Order',
      2 => 'Sub Contract'
   ],
   'purpose' => [
      1 => "Customer Meeting",
      2 => "Other Meeting",
      3 => "BL/CO Collection",
      4 => "Custom Work",
      5 => "Fund Deposit",
      6 => "Cash Withdrown",
      7 => "Export Document Submission",
      8 => "Party Acceptance Collection/Submit",
      9 => "Maturity Collection",
      10 => "BGMEA Work",
      11 => "PRC Collection",
      12 => "GSP Facility Certificate from BTMA",
      13 => "Alternate Cash Assistance-BGMEA",
      14 => "Cash Assistance Certificate from BTMA",
      15 => "Import LC Proposal Sub",
      16 => "Cash Incentive Work",
      17 => "Local Export Work",
      18 => "Sample Collection/Meeting/Sampling",
      19 => "Refreshment",
      20 => "Purchase Purpose",
      21 => "Personal",
      22 => "Sickness",
      23 => "Fabric Collection",
      24 => "Maintanance",
      25 => "Office Work",
      26 => "Cash and Cheque Collection",
   ],
   'transportmode' => [
      1 => "Rickshaw",
      2 => "Auto-Rickshaw",
      3 => "Airplane",
      4 => "Boat",
      5 => "Rent-A-Car",
      6 => "Office Car",
      7 => "Bus",
      8 => "Fuel Cost/Bike/Car",
      9 => "Bus + Rickshaw",
      10 => "Ship",
      11 => "Launch",
   ],
   'decision' => [
      1 => "Send to repair outside",
      2 => "Send to repair inhouse",
      3 => "Dispose",
   ],
   'reason' => [
      1 => "Routine Maintenance",
      2 => "Program Change over",
      3 => "Technical Fault",
      4 => "Power Interruption",
      5 => "Less Air Pressure",
      6 => "Less Steam Pressure",
      7 => "Insufficient Input",
      8 => "Operator Unavailable",
      9 => "Off Day",
      10 => "Fire",
      11 => "Natural Disaster",
      12 => "Yarn Break",
      13 => "Needle Break",
      14 => "Electrical Issue",
      15 => "Mechanical Issue",
      16 => "Civil Issue",
      17 => "Batch Unavailable"
   ],
   'designationlevel' => [
      1 => "Chairman",
      2 => "Managing Director",
      3 => "Deputy Managing Director",
      6 => "Director",
      8 => "Executive Director",
      9 => "Assistant Director",
      20 => "Senior General Manager",
      21 => "General Manager",
      22 => "Deputy General Manager",
      23 => "Assistant General Manager",
      25 => "Senior Manager",
      26 => "Manager",
      27 => "Deputy Manager",
      28 => "Assistant Manager",
      30 => "Senior Executive",
      31 => "Executive",
      32 => "Junior Executive",
      40 => "Senior Officer",
      41 => "Officer",
      42 => "Assistant Officer",
      50 => "Senior Staff",
      51 => "Staff",
      52 => "Junior Staff",
      60 => "Admin Support Staff",
      65 => "Medical Staff",
      70 => "Guard",
      75 => "Utility Support Staff",
      80 => "Mechanical Support Staff",
      90 => "Operator",
      95 => "Inspector",
      100 => "Packer",
      105 => "Folderer",
      110 => "Marker Man",
      115 => "Drawer",
      120 => "Inputter",
      125 => "Numberer",
      130 => "Layerer",
      135 => "Hang Tagger",
      140 => "Receiver",
      145 => "Spreader",
      150 => "Printer",
      155 => "Stickier",
      160 => "Exposer",
      165 => "Ironer",
      170 => "Pressing Man",
      175 => "Distributor",
      180 => "Sampler",
      185 => "Bundler",
      190 => "Poly packer",
      195 => "Scissor Man",
      205 => "Color Man",
      210 => "Heat Pressing Man",
      215 => "Spot Remover",
      220 => "Loader",
      225 => "Scaler ",
      240 => "Helper",
   ],
   'invoicestatus' => [
      1 => "Draft",
      2 => "Final"
   ],
   'expdocprogressevent' => [
      1 => "BL/AWB/Transport Challan",
      2 => "Certificate of Origin",
      3 => "GSP",
      4 => "Buyer Submission",
      5 => "Bank Submission",
      6 => "Payment Advice",
      7 => "Bank to Bank Courier Date"
   ],
   'inoutcharges' => [
      1 => "Beneficiary",
      2 => "Applicant",
   ],
   'daystatus' => [
      1 => "Working Day",
      2 => "Off Day",
   ],

   'minuteadjustmentreasons' => [
      1 => "Absence After Lunch",
      2 => "Sickness After Starts",
      3 => "Unrest",
      4 => "Power Interruption",
      5 => "Incidence",
      6 => "Work Over Given Hour",
      7 => "Early Leave",
   ],
   'hrinactivefor' => [
      1 => "Resign",
      2 => "Terminate",
      3 => "Death",
      4 => "Accidental Disable",
      5 => "Government Job",
      6 => "Foreign Migrant",
      7 => "Long Absent",
      8 => "Retrenchment",
      9 => "N/A",
   ],

   'roomrequired' => [
      1 => "Individual", 2 => "Shared", 3 => "No",
   ],

   'transportrequired' => [
      1 => "Full time Car", 2 => "Pick & Drop", 3 => "Bicycle", 4 => "Motor Bike",
   ],

   'computer' => [
      1 => "Laptop", 2 => "Desktop",
   ],

   'employeetype' => [
      1 => "Regular",
      2 => "Contractual",
   ],

   'employeecategory' => [
      1 => "Management Category",
      2 => "Stuff",
      3 => "Worker",
   ],

   'startendbasis' => [
      1 => "Start From Order Receive",
      2 => "Start Before Ship date",
      3 => "End Before Ship date",
      4 => "After Depending Task",
      5 => "Start After Depending Task",
   ],

   'dofor' => [
      1 => "Jhute",
      2 => "LeftOver",
   ],


   'reportname' => [
      1 => "Payment Request",
      2 => "New Garment Order Receive",
      3 => "New Kniting Work Order Receive",
      4 => "New Dyeing Work Order Receive",
      5 => "New AOP Order Receive",
   ],

   'billfor' => [
      1 => "Finish Qty",
      2 => "Grey Qty",
   ],

   'disposaltype' => [
      1 => "Sold Out",
      2 => "Damaged",
      3 => "Donated",
      4 => "Missing",
   ]

];
