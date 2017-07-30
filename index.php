<?php
	/*
	 * SimpleProblemsManager (Web interface)
	 * COPYRIGHT (C) 2016, KADIROV YURIJ. ALL RIGHTS ARE RESERVED.
	 * PUBLIC DISTRIBUTION RESTRICTED!
	 */
	
	/////////////////////////////////////
	//   DEVELOPMENT-ONLY VARIABLES    //
	/////////////////////////////////////
	
	ini_set('error_reporting', E_ALL); // 0 for release!
	ini_set('display_errors', 1); // 0 for release!
	ini_set('display_startup_errors', 1); // 0 for release!
	
	/////////////////////////////////////
	//         SOME SETTINGS           //
	/////////////////////////////////////
	
	session_set_cookie_params(3600*24*2);
	session_start();
	
	mb_internal_encoding('UTF-8');
	
	/////////////////////////////////////
	//        REQUIRED DEFINES         //
	/////////////////////////////////////
	
	DEFINE("SPM_GENUINE", 1); //Security define
	
	DEFINE("_S_INC_", "./inc/");
	DEFINE("_S_INC_FUNC_", _S_INC_ . "func/");
	DEFINE("_S_INC_CLASS_", _S_INC_ . "class/");
	
	DEFINE("_S_MOD_", "./modules/");
	
	DEFINE("_S_SERV_", "./services/");
	DEFINE("_S_SERV_INC_", _S_SERV_ . "inc/");
	
	DEFINE("_S_VIEW_", "./views/");
	
	DEFINE("_S_MEDIA_", "./media/");
	DEFINE("_S_MEDIA_FILES_", _S_MEDIA_ . "files/");
	DEFINE("_S_MEDIA_IMG_", _S_MEDIA_ . "img/");
	
	/////////////////////////////////////
	//        REQUIRED INCLUDES        //
	/////////////////////////////////////
	
	include_once(_S_INC_FUNC_ . "permissions_check.php");
	include_once(_S_INC_FUNC_ . "gen_tpl.php");
	include_once(_S_INC_FUNC_ . "guard.php");
	include_once(_S_INC_ . "config.php");
	include_once(_S_INC_ . "db.php");
	include_once(_S_INC_FUNC_ . "info_msg.php");
	include_once(_S_INC_FUNC_ . "user_info.php");
	include_once(_S_INC_CLASS_ . "CountryList.php");
	
	/////////////////////////////////////
	//            DEFINES-2            //
	/////////////////////////////////////
	
	DEFINE("_S_TPL_", "./tpl/" . $_SPM_CONF["BASE"]["TPL_NAME"] . "/");
	DEFINE("_S_TPL_ERR_", _S_TPL_ . "error_pages/");
	
	/////////////////////////////////////
	//    REQUIRED AUTORUN FUNCTIONS   //
	/////////////////////////////////////
	
	_spm_guard_clearAllGet();
	
	/////////////////////////////////////
	//            CHECKERS             //
	/////////////////////////////////////
	
	include_once(_S_INC_FUNC_ . "user_check.php"); // user checker
	include_once(_S_INC_FUNC_ . "olympiads_check.php"); // olympiads checker
	include_once(_S_INC_FUNC_ . "classworks_check.php"); // classworks checker
	
	/////////////////////////////////////
	//         SERVICE CHANGER         //
	/////////////////////////////////////
	
	if (isset($_GET['service']) && strlen($_GET['service']) > 0)
		$_spm_run_service = preg_replace("/[^a-zA-Z0-9.\-_\s]/", "", $_GET['service']);
	else
		$_spm_run_service = $_SPM_CONF["SERVICES"]["_AUTOSTART_SERVICE_"];
	
	if(!isset($_SESSION['uid']) && !isset($_SPM_CONF["SERVICE_NOLOGIN"][$_spm_run_service]))
		$_spm_run_service = "login";

	if (!isset($_SPM_CONF["SERVICE"][$_spm_run_service]) && !isset($_SESSION['uid'])):
		die(header('location: index.php'));
	elseif ( ( !isset($_SPM_CONF["SERVICE"][$_spm_run_service]) && isset($_SESSION['uid']) ) || !file_exists(_S_SERV_ . $_SPM_CONF["SERVICE"][$_spm_run_service])):
		die(header('location: index.php?service=error&err=404'));
	endif;
	
	/////////////////////////////////////
	//   INCLUDING CONTENT GENERATOR   //
	/////////////////////////////////////
	
	include_once(_S_SERV_ . $_SPM_CONF["SERVICE"][$_spm_run_service]);
	
	/////////////////////////////////////
	//     CLOSE CONNECTIONS, ETC.     //
	/////////////////////////////////////
	
	$db->close();
?>
