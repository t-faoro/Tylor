<?php 
session_start("TFSite");
require_once "config.php";


$TylorFaoro = new Layout();
$DB = new Database();
$nav = new Navigation("mainNav", "navigation");

//:: Declare Content Blocks
$siteContainer   	= new Content();
$headerContainer 	= new Content();
$logo			 	= new Content();
$headerContent	 	= new Content();
$contentContainer 	= new Content();
$content			= new Content();
//$sideBar			= new Content();
$footerContainer	= new Content();
$footerContent		= new Content();

//:: Instantiate Content Blocks
$siteContainer->newBlock("container");
$headerContainer->newBlock("headerContainer");
$logo->newBlock("logo");
$headerContent->newBlock("headerContent");
$contentContainer->newBlock("contentContainer");
$content->newBlock("content");
//$sideBar->newBlock("sideBar");
$footerContainer->newBlock("footerContainer");
$footerContent->newBlock("footerContent");

//:: Declare Stylesheets
$TylorFaoro->addCSS("style.css");

//:: Declare Javascript
$TylorFaoro->addJS("script.js");

//:: Nest content blocks
$headerContainer->add( $logo->buildBlock() );
$headerContainer->add( $headerContent->buildBlock() );

$contentContainer->add( $content->buildBlock() );
//$contentContainer->add( $sideBar->buildBlock() );

$footerContainer->add( $footerContent->buildBlock() );

//:: Wrap Nested content blocks
$siteContainer->add( $headerContainer->buildBlock() );
$siteContainer->add( $contentContainer->buildBlock() );
$siteContainer->add( $footerContainer->buildBlock() );


//:::: Start of Page Declarations
echo $TylorFaoro->setPage();
echo $TylorFaoro->setHeader();
echo $TylorFaoro->openBody();


//:::: Body Content Goes Here

echo $siteContainer->buildBlock();


//:::: End of Page Declarations

echo $TylorFaoro->closeBody();
echo $TylorFaoro->endPage();







