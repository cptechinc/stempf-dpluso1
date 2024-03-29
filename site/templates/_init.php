<?php

/**
 * Initialization file for template files
 *
 * This file is automatically included as a result of $config->prependTemplateFile
 * option specified in your /site/config.php.
 *
 * You can initialize anything you want to here. In the case of this beginner profile,
 * we are using it just to include another file with shared functions.
 *
 */
	// APPLICATION CONFIGS
	include_once($config->paths->templates."configs/dpluso-config.php");
	$config->pages = new ProcessWire\Paths($config->rootURL);
	include_once($config->paths->templates."configs/nav-config.php");
	include_once($config->paths->templates."configs/user-roles-config.php");
	$appconfig = $pages->get('/config/');

	// INCLUDE AUTOLOAD AND NECESSARY FUNCTIONS
	include_once("./_func.php"); // include our shared functions
	include_once("./_dbfunc.php");

	// AFTER LOADING CONFIGS, CLASSES, AND FUNCTIONS CHECK FOR LOGIN
	$user->loggedin = is_validlogin(session_id());
	if ($user->loggedin) {
		setup_user(session_id());
		Dplus\Dpluso\General\SigninLog::log_signin(session_id(), $user->loginid);
	} elseif ($page->template != 'template-login' && $page->template != 'redir' && $page->template != 'template-print') {
		header('location: ' . $config->pages->login);
		exit;
	}

	// CONFIGS FOR JS
	include_once($config->paths->templates."_init.js.php");  // includes class files

	// BUILD AND INSTATIATE CLASSES
	$page->fullURL = new \Purl\Url($page->httpUrl);
	$page->fullURL->path = '';
	if (!empty($config->filename) && $config->filename != '/') {
		$page->fullURL->join($config->filename);
	}


	$page->stringerbell = new Dplus\Base\StringerBell();
	$page->htmlwriter = new Dplus\Content\HTMLWriter();
	$page->bootstrap = $page->htmlwriter;
	$page->screenformatterfactory = new Dplus\Dpluso\ScreenFormatters\ScreenFormatterFactory(session_id());
	$itemlookup = new Dplus\Dpluso\Items\ItemLookupModal();

	Dplus\Dpluso\ScreenFormatters\TableScreenMaker::set_filedirectory($config->jsonfilepath);
	Dplus\Dpluso\ScreenFormatters\TableScreenMaker::set_testfiledirectory($config->paths->vendor."cptechinc/dpluso-screen-formatters/src/examples/");
	Dplus\Dpluso\ScreenFormatters\TableScreenMaker::set_fieldfiledirectory($config->companyfiles."json/");
	Dplus\Dpluso\Configs\FormFieldsConfig::set_defaultconfigdirectory($config->paths->templates."configs/customer/");


	// ADD DEFAULT CSS FILES
	$config->styles->append(hashtemplatefile('styles/bootstrap.min.css'));
	$config->styles->append('https://fonts.googleapis.com/icon?family=Material+Icons');
	$config->styles->append(hashtemplatefile('styles/libraries.css'));
	$config->styles->append(hashtemplatefile('styles/libs/bootstrap-select.css'));
	$config->styles->append(hashtemplatefile('styles/styles.css'));

	// ADD DEFAULT JS FILES
	$config->scripts->append(hashtemplatefile('scripts/libraries.js'));
	$config->scripts->append(hashtemplatefile('scripts/libs/timepicker.js'));
	$config->scripts->append(hashtemplatefile('scripts/libs/key-listener.js'));
	$config->scripts->append(hashtemplatefile('scripts/libs/datatables.js'));
	$config->scripts->append(hashtemplatefile('scripts/libs/datatables-datetime.js'));
	$config->scripts->append(hashtemplatefile('scripts/classes.js'));
	$config->scripts->append(hashtemplatefile('scripts/libs/bootstrap-select.js'));
	$config->scripts->append(hashtemplatefile('scripts/libs/jquery-validate.js'));
	$config->scripts->append(hashtemplatefile('scripts/scripts.js'));
	$config->scripts->append(hashtemplatefile('scripts/dplus-notes.js'));


	//$config->scripts->append($config->urls->modules . 'Inputfield/InputfieldCKEditor/ckeditor-4.6.1/ckeditor.js'));

	// SET CONFIG PROPERTIES
	if ($input->get->modal) {
		$config->modal = true;
	}

	if ($input->get->json) {
		$config->json = true;
	}

	$mtwig = $modules->get('Twig');
	$config->twigloader = $mtwig->getLoader();
	$config->twig = $mtwig->getTwig();
	$config->twig->getExtension(\Twig\Extension\CoreExtension::class)->setNumberFormat(3, '.', '');
