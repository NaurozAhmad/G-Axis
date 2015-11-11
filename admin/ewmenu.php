<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(1, "mi_news", $Language->MenuPhrase("1", "MenuText"), "newslist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(2, "mi_category", $Language->MenuPhrase("2", "MenuText"), "categorylist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(3, "mi_sub_category", $Language->MenuPhrase("3", "MenuText"), "sub_categorylist.php?cmd=resetall", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(4, "mi_products", $Language->MenuPhrase("4", "MenuText"), "productslist.php?cmd=resetall", -1, "", TRUE, FALSE);
$RootMenu->Render();
?>
<!-- End Main Menu -->
