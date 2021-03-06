<?php

namespace PHPMaker2021\silpa;

// Menu Language
if ($Language && function_exists(PROJECT_NAMESPACE . "Config") && $Language->LanguageFolder == Config("LANGUAGE_FOLDER")) {
    $MenuRelativePath = "";
    $MenuLanguage = &$Language;
} else { // Compat reports
    $LANGUAGE_FOLDER = "../lang/";
    $MenuRelativePath = "../";
    $MenuLanguage = Container("language");
}

// Navbar menu
$topMenu = new Menu("navbar", true, true);
$topMenu->addMenuItem(32, "mci_Home", $MenuLanguage->MenuPhrase("32", "MenuText"), $MenuRelativePath . "http://regsikd.acehprov.go.id/silpa", -1, "", true, false, true, "", "", true);
$topMenu->addMenuItem(4, "mci_Managemen_User", $MenuLanguage->MenuPhrase("4", "MenuText"), "", -1, "", true, false, true, "fas fa-users", "", true);
$topMenu->addMenuItem(3, "mi_users", $MenuLanguage->MenuPhrase("3", "MenuText"), $MenuRelativePath . "userslist", 4, "", AllowListMenu('{8FB2C16F-E090-4B20-9B83-115D69E60354}users'), false, false, "far fa-circle", "", true);
$topMenu->addMenuItem(1, "mi_levels", $MenuLanguage->MenuPhrase("1", "MenuText"), $MenuRelativePath . "levelslist", 4, "", AllowListMenu('{8FB2C16F-E090-4B20-9B83-115D69E60354}levels'), false, false, "far fa-circle", "", true);
$topMenu->addMenuItem(2, "mi_permissions2", $MenuLanguage->MenuPhrase("2", "MenuText"), $MenuRelativePath . "permissions2list", 4, "", AllowListMenu('{8FB2C16F-E090-4B20-9B83-115D69E60354}permissions'), false, false, "far fa-circle", "", true);
$topMenu->addMenuItem(15, "mci_Data_Master", $MenuLanguage->MenuPhrase("15", "MenuText"), "", -1, "", true, false, true, "fas fa-database", "", true);
$topMenu->addMenuItem(6, "mi_satkers", $MenuLanguage->MenuPhrase("6", "MenuText"), $MenuRelativePath . "satkerslist", 15, "", AllowListMenu('{8FB2C16F-E090-4B20-9B83-115D69E60354}satkers'), false, false, "far fa-circle", "", true);
$topMenu->addMenuItem(8, "mi_wilayah", $MenuLanguage->MenuPhrase("8", "MenuText"), $MenuRelativePath . "wilayahlist", 15, "", AllowListMenu('{8FB2C16F-E090-4B20-9B83-115D69E60354}wilayah'), false, false, "far fa-circle", "", true);
$topMenu->addMenuItem(7, "mi_tahapan", $MenuLanguage->MenuPhrase("7", "MenuText"), $MenuRelativePath . "tahapanlist", 15, "", AllowListMenu('{8FB2C16F-E090-4B20-9B83-115D69E60354}tahapan'), false, false, "far fa-circle", "", true);
$topMenu->addMenuItem(54, "mi_tahun", $MenuLanguage->MenuPhrase("54", "MenuText"), $MenuRelativePath . "tahunlist", 15, "", AllowListMenu('{8FB2C16F-E090-4B20-9B83-115D69E60354}tahun'), false, false, "far fa-circle", "", true);
echo $topMenu->toScript();

// Sidebar menu
$sideMenu = new Menu("menu", true, false);
$sideMenu->addMenuItem(32, "mci_Home", $MenuLanguage->MenuPhrase("32", "MenuText"), $MenuRelativePath . "http://regsikd.acehprov.go.id/silpa", -1, "", true, false, true, "", "", true);
$sideMenu->addMenuItem(4, "mci_Managemen_User", $MenuLanguage->MenuPhrase("4", "MenuText"), "", -1, "", true, false, true, "fas fa-users", "", true);
$sideMenu->addMenuItem(3, "mi_users", $MenuLanguage->MenuPhrase("3", "MenuText"), $MenuRelativePath . "userslist", 4, "", AllowListMenu('{8FB2C16F-E090-4B20-9B83-115D69E60354}users'), false, false, "far fa-circle", "", true);
$sideMenu->addMenuItem(1, "mi_levels", $MenuLanguage->MenuPhrase("1", "MenuText"), $MenuRelativePath . "levelslist", 4, "", AllowListMenu('{8FB2C16F-E090-4B20-9B83-115D69E60354}levels'), false, false, "far fa-circle", "", true);
$sideMenu->addMenuItem(2, "mi_permissions2", $MenuLanguage->MenuPhrase("2", "MenuText"), $MenuRelativePath . "permissions2list", 4, "", AllowListMenu('{8FB2C16F-E090-4B20-9B83-115D69E60354}permissions'), false, false, "far fa-circle", "", true);
$sideMenu->addMenuItem(15, "mci_Data_Master", $MenuLanguage->MenuPhrase("15", "MenuText"), "", -1, "", true, false, true, "fas fa-database", "", true);
$sideMenu->addMenuItem(6, "mi_satkers", $MenuLanguage->MenuPhrase("6", "MenuText"), $MenuRelativePath . "satkerslist", 15, "", AllowListMenu('{8FB2C16F-E090-4B20-9B83-115D69E60354}satkers'), false, false, "far fa-circle", "", true);
$sideMenu->addMenuItem(8, "mi_wilayah", $MenuLanguage->MenuPhrase("8", "MenuText"), $MenuRelativePath . "wilayahlist", 15, "", AllowListMenu('{8FB2C16F-E090-4B20-9B83-115D69E60354}wilayah'), false, false, "far fa-circle", "", true);
$sideMenu->addMenuItem(7, "mi_tahapan", $MenuLanguage->MenuPhrase("7", "MenuText"), $MenuRelativePath . "tahapanlist", 15, "", AllowListMenu('{8FB2C16F-E090-4B20-9B83-115D69E60354}tahapan'), false, false, "far fa-circle", "", true);
$sideMenu->addMenuItem(54, "mi_tahun", $MenuLanguage->MenuPhrase("54", "MenuText"), $MenuRelativePath . "tahunlist", 15, "", AllowListMenu('{8FB2C16F-E090-4B20-9B83-115D69E60354}tahun'), false, false, "far fa-circle", "", true);
$sideMenu->addMenuItem(16, "mci_Menu_Evaluator", $MenuLanguage->MenuPhrase("16", "MenuText"), "", -1, "", true, false, true, "fas fa-align-justify", "", false);
$sideMenu->addMenuItem(5, "mi_evaluators", $MenuLanguage->MenuPhrase("5", "MenuText"), $MenuRelativePath . "evaluatorslist", 16, "", AllowListMenu('{8FB2C16F-E090-4B20-9B83-115D69E60354}evaluators'), false, false, "far fa-circle", "", false);
$sideMenu->addMenuItem(53, "mci_Evaluasi", $MenuLanguage->MenuPhrase("53", "MenuText"), "", -1, "", true, false, true, "fas fa-align-justify", "", false);
$sideMenu->addMenuItem(55, "mi_pertanggungjawaban2022", $MenuLanguage->MenuPhrase("55", "MenuText"), $MenuRelativePath . "pertanggungjawaban2022list", 53, "", AllowListMenu('{8FB2C16F-E090-4B20-9B83-115D69E60354}pertanggungjawaban2022'), false, false, "far fa-circle", "", false);
$sideMenu->addMenuItem(78, "mi_view_pertanggungjawaban_2022_ev", $MenuLanguage->MenuPhrase("78", "MenuText"), $MenuRelativePath . "viewpertanggungjawaban2022evlist", 53, "", AllowListMenu('{8FB2C16F-E090-4B20-9B83-115D69E60354}view_pertanggungjawaban_2022_ev'), false, false, "far fa-circle", "", false);
$sideMenu->addMenuItem(77, "mci_Template", $MenuLanguage->MenuPhrase("77", "MenuText"), "", -1, "", true, false, true, "fas fa-align-justify", "", false);
echo $sideMenu->toScript();
