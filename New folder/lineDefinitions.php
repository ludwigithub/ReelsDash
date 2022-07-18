<?php
/*
Hartselle Nailwood Line Defintions
2018.10.05
Nicholas West
 */
$spsNS = 0;
$spsMS = 1;
$spsBD = 2;
$spsCO = 3;
$spsSuSd = 4;
$spsSPD = 5; 
$spsPM = 6;
$spsMR = 7;

// ---------- Line 1 Nailer ---------- 
$lID = 6;
$tblSetup[$lID]['name'] = 'flange_setup_info_1n';
$tblSetup[$lID]['id'] = 'id_fs_1n';
$tblProd[$lID]['name'] = 'flange_production_1n';
$tblProd[$lID]['id'] = 'id_fp_1n';
$tblProd[$lID]['ts'] = 'ts';
$tblProd[$lID]['cnt'] = 'NailerQty';
$tblLoss[$lID]['name'] = 'dt_event_1n';
$tblLoss[$lID]['id'] = 'id_dte_1n';
$lTitle[$lID] = 'Line 1 Nailer';
$lLabel[$lID] = 'L1 N';
$lType[$lID]="nailer";
$yieldAvailable[$lID] = True;
$uom[$lID] = "Blanks";
$equipNS[$lID] = 24;
$dtcNS[$lID] = 278;

// ---------- Line 1 Final Flange ---------- 
$lID = 1;
$tblSetup[$lID]['name'] = 'flange_setup_info_1ff';
$tblSetup[$lID]['id'] = 'id_fs_1ff';
$tblProd[$lID]['name'] = 'flange_production_1ff';
$tblProd[$lID]['id'] = 'id_fp_1ff';
$tblProd[$lID]['ts'] = 'ts';
$tblProd[$lID]['cnt'] = 'flanges';
$tblLoss[$lID]['name'] = 'dt_event_1ff';
$tblLoss[$lID]['id'] = 'id_dte_1ff';
$lTitle[$lID] = 'Line 1 Final Flange';
$lLabel[$lID] = 'L1 FF';
$lType[$lID]="final";
$yieldAvailable[$lID] = False;
$uom[$lID] = "Flanges";
$equipNS[$lID] = 69;
$dtcNS[$lID] = 557;

// ---------- Line 3 Layup Table ---------- 
$lID = 30;
$tblSetup[$lID]['name'] = 'flange_setup_info_1lt';
$tblSetup[$lID]['id'] = 'id_fs_1lt';
$tblProd[$lID]['name'] = 'flange_production_1lt';
$tblProd[$lID]['id'] = 'id_fp_1lt';
$tblProd[$lID]['ts'] = 'ts';
$tblProd[$lID]['cnt'] = 'flanges';
$tblLoss[$lID]['name'] = 'dt_event_1lt';
$tblLoss[$lID]['id'] = 'id_dte_1lt';
$lTitle[$lID] = 'Line 3 Layup Table';
$lLabel[$lID] = 'L3 LAY';
$lType[$lID]="final";
$yieldAvailable[$lID] = False;
$uom[$lID] = "Blanks";
$equipNS[$lID] = 81;
$dtcNS[$lID] = 633;

// ---------- Line 3 Final Flange ---------- 
$lID = 3;
$tblSetup[$lID]['name'] = 'flange_setup_info_3ff';
$tblSetup[$lID]['id'] = 'id_fs_3ff';
$tblProd[$lID]['name'] = 'flange_production_3ff';
$tblProd[$lID]['id'] = 'id_fp_3ff';
$tblProd[$lID]['ts'] = 'ts';
$tblProd[$lID]['cnt'] = 'flanges';
$tblLoss[$lID]['name'] = 'dt_event_3ff';
$tblLoss[$lID]['id'] = 'id_dte_3ff';
$lTitle[$lID] = 'Line 3 Final Flange';
$lLabel[$lID] = 'L3 FF';
$lType[$lID]="final";
$yieldAvailable[$lID] = False;
$uom[$lID] = "Flanges";
$equipNS[$lID] = 43;
$dtcNS[$lID] = 380;

// ---------- Line 4 Final Flange ---------- 
$lID = 4;
$tblSetup[$lID]['name'] = 'flange_setup_info_4ff';
$tblSetup[$lID]['id'] = 'id_fs_4ff';
$tblProd[$lID]['name'] = 'flange_production_4ff';
$tblProd[$lID]['id'] = 'id_fp_4ff';
$tblProd[$lID]['ts'] = 'ts';
$tblProd[$lID]['cnt'] = 'flanges';
$tblLoss[$lID]['name'] = 'dt_event_4ff';
$tblLoss[$lID]['id'] = 'id_dte_4ff';
$lTitle[$lID] = 'Line 4 Final Flange';
$lLabel[$lID] = 'L4 FF';
$lType[$lID]="final";
$yieldAvailable[$lID] = False;
$uom[$lID] = "Flanges";
$equipNS[$lID] = 56;
$dtcNS[$lID] = 468;

// ---------- Line 5  ---------- 
$lID = 5;
$tblSetup[$lID]['name'] = 'flange_setup_info';
$tblSetup[$lID]['id'] = 'id';
$tblProd[$lID]['name'] = 'flange_production';
$tblProd[$lID]['id'] = 'ID';
$tblProd[$lID]['ts'] = 'TimeStamp';
$tblProd[$lID]['cnt'] = 'NailerQty';
$tblLoss[$lID]['name'] = 'dt_event';
$tblLoss[$lID]['id'] = 'id';
$lTitle[$lID] = 'Line 5';
$lLabel[$lID] = 'L5';
$lType[$lID]="final";
$yieldAvailable[$lID] = False;
$uom[$lID] = "Flanges";
$equipNS[$lID] = 0;
$dtcNS[$lID] = 0;

// ---------- Staves Line 1  ---------- 
$lID = 20;
$tblSetup[$lID]['name'] = '';
$tblSetup[$lID]['id'] = '';
$tblProd[$lID]['name'] = 'staves_production_s1';
$tblProd[$lID]['id'] = 'id_sp_s1';
$tblProd[$lID]['ts'] = 'ts';
$tblProd[$lID]['cnt'] = 'LF';
$tblLoss[$lID]['name'] = 'dt_event_s1';
$tblLoss[$lID]['id'] = 'id_dte_s1';
$lTitle[$lID] = 'Staves Line 1';
$lLabel[$lID] = 'SL1';
$lType[$lID]="staves";
$yieldAvailable[$lID] = False;
$uom[$lID] = "Linear Ft.";
$equipNS[$lID] = 34;
$dtcNS[$lID] = 378;

// ---------- Re-Saw Line 1  ---------- 
$lID = 40;
$tblSetup[$lID]['name'] = '';
$tblSetup[$lID]['id'] = '';
$tblProd[$lID]['name'] = 'production_rs1';
$tblProd[$lID]['id'] = 'id_p_rs1';
$tblProd[$lID]['ts'] = 'ts';
$tblProd[$lID]['cnt'] = 'qty';
$tblLoss[$lID]['name'] = 'dt_event_rs1';
$tblLoss[$lID]['id'] = 'id_dte_rs1';
$lTitle[$lID] = 'Re-Saw Line 1';
$lLabel[$lID] = 'RS1';
$lType[$lID]="resaw";
$yieldAvailable[$lID] = False;
$uom[$lID] = "Linear Ft.";
$equipNS[$lID] = 88;
$dtcNS[$lID] = 748;

// ---------- Line 3 Nailer ---------- 
$lID = 8;
$tblSetup[$lID]['name'] = '';
$tblSetup[$lID]['id'] = '';
$tblProd[$lID]['name'] = 'flange_production_3n';
$tblProd[$lID]['id'] = 'id_fp_3n';
$tblProd[$lID]['ts'] = 'ts';
$tblProd[$lID]['cnt'] = 'NailerQty';
$tblLoss[$lID]['name'] = 'dt_event_3n';
$tblLoss[$lID]['id'] = 'id_dte_3n';
$lTitle[$lID] = 'Line 3 Nailer';
$lLabel[$lID] = 'L3 N';
$lType[$lID]="nailer";
$yieldAvailable[$lID] = True;
$uom[$lID] = "Blanks";
$equipNS[$lID] = 89;
$dtcNS[$lID] = 749;

// ---------- Cutup Saw Line 1 ---------- 
$lID = 50;
$tblSetup[$lID]['name'] = '';
$tblSetup[$lID]['id'] = '';
$tblProd[$lID]['name'] = 'production_cs1';
$tblProd[$lID]['id'] = 'id_p_cs1';
$tblProd[$lID]['ts'] = 'ts';
$tblProd[$lID]['cnt'] = 'units';
$tblLoss[$lID]['name'] = 'dt_event_cs1';
$tblLoss[$lID]['id'] = 'id_dte_cs1';
$lTitle[$lID] = 'Cutup Saw Line 1';
$lLabel[$lID] = 'CS1';
$lType[$lID]="final";
$yieldAvailable[$lID] = True;
$uom[$lID] = "Bundles";
$equipNS[$lID] = 94;
$dtcNS[$lID] = 795;

// ---------- Line 4 Nailer ---------- 
$lID = 9;
$tblSetup[$lID]['name'] = '';
$tblSetup[$lID]['id'] = '';
$tblProd[$lID]['name'] = 'flange_production_4n';
$tblProd[$lID]['id'] = 'id_fp_4n';
$tblProd[$lID]['ts'] = 'ts';
$tblProd[$lID]['cnt'] = 'NailerQty';
$tblLoss[$lID]['name'] = 'dt_event_4n';
$tblLoss[$lID]['id'] = 'id_dte_4n';
$lTitle[$lID] = 'Line 4 Nailer';
$lLabel[$lID] = 'L4 N';
$lType[$lID]="nailer";
$yieldAvailable[$lID] = True;
$uom[$lID] = "Blanks";
$equipNS[$lID] = 99;
$dtcNS[$lID] = 862;

// ---------- Plywood Line 1  ---------- 
$lID = 60;
$tblSetup[$lID]['name'] = 'setup_info_pw1';
$tblSetup[$lID]['id'] = 'id_fs_pw1';
$tblProd[$lID]['name'] = 'production_pw1';
$tblProd[$lID]['id'] = 'id_p_pw1';
$tblProd[$lID]['ts'] = 'ts';
$tblProd[$lID]['cnt'] = 'qty';
$tblLoss[$lID]['name'] = 'dt_event_pw1';
$tblLoss[$lID]['id'] = 'id_dte_pw1';
$lTitle[$lID] = 'Plywood Line 1';
$lLabel[$lID] = 'PW1';
$lType[$lID]="plywood";
$yieldAvailable[$lID] = False;
$uom[$lID] = "Flanges";
$equipNS[$lID] = 103;
$dtcNS[$lID] = 907;

// ---------- Plywood Line 3  ---------- 
$lID = 62;
$tblSetup[$lID]['name'] = 'setup_info_pw3';
$tblSetup[$lID]['id'] = 'id_fs_pw3';
$tblProd[$lID]['name'] = 'production_pw3';
$tblProd[$lID]['id'] = 'id_p_pw3';
$tblProd[$lID]['ts'] = 'ts';
$tblProd[$lID]['cnt'] = 'qty';
$tblLoss[$lID]['name'] = 'dt_event_pw3';
$tblLoss[$lID]['id'] = 'id_dte_pw3';
$lTitle[$lID] = 'Plywood Line 3';
$lLabel[$lID] = 'PW3';
$lType[$lID]="plywood";
$yieldAvailable[$lID] = False;
$uom[$lID] = "Flanges";
$equipNS[$lID] = 129;
$dtcNS[$lID] = 1075;

// ---------- Line 2 Nailer ---------- 
$lID = 7;
$tblSetup[$lID]['name'] = '';
$tblSetup[$lID]['id'] = '';
$tblProd[$lID]['name'] = 'production_2n';
$tblProd[$lID]['id'] = 'id_p_2n';
$tblProd[$lID]['ts'] = 'ts';
$tblProd[$lID]['cnt'] = 'qty';
$tblLoss[$lID]['name'] = 'dt_event_2n';
$tblLoss[$lID]['id'] = 'id_dte_2n';
$lTitle[$lID] = 'Line 2 Nailer';
$lLabel[$lID] = 'L2N';
$lType[$lID]="nailer";
$yieldAvailable[$lID] = False; 
$uom[$lID] = "Flanges";
$equipNS[$lID] = 112;
$dtcNS[$lID] = 937;


// ---------- Optimization Saw ---------- 
$lID = 51;
$tblSetup[$lID]['name'] = '';
$tblSetup[$lID]['id'] = '';
$tblProd[$lID]['name'] = 'production_os1';
$tblProd[$lID]['id'] = 'id_p_os1';
$tblProd[$lID]['ts'] = 'ts';
$tblProd[$lID]['cnt'] = 'qty';
$tblLoss[$lID]['name'] = 'dt_event_os1';
$tblLoss[$lID]['id'] = 'id_dte_os1';
$lTitle[$lID] = 'Opt Saw';
$lLabel[$lID] = 'OSAW';
$lType[$lID]="saw";
$yieldAvailable[$lID] = False; 
$uom[$lID] = "Ft";
$equipNS[$lID] = 140;
$dtcNS[$lID] = 1131;

// ---------- Bolt Machine 1 ---------- 
$lID = 70;
$tblSetup[$lID]['name'] = '';
$tblSetup[$lID]['id'] = '';
$tblProd[$lID]['name'] = 'production_1b';
$tblProd[$lID]['id'] = 'id_p_1b';
$tblProd[$lID]['ts'] = 'ts';
$tblProd[$lID]['cnt'] = 'qty';
$tblLoss[$lID]['name'] = 'dt_event_1b';
$tblLoss[$lID]['id'] = 'id_dte_1b';
$lTitle[$lID] = 'Bolt Machine 1';
$lLabel[$lID] = 'BOLTS';
$lType[$lID]="bolts";
$yieldAvailable[$lID] = False; 
$uom[$lID] = "Bolts";
$equipNS[$lID] = 156;
$dtcNS[$lID] = 1228;
