<?php
    include_once '../inc/global.php';

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : null;
$method = $_SERVER['REQUEST_METHOD'];
$format = isset($_REQUEST['format']) ? $_REQUEST['format'] : 'web';
$view 	= null;

switch ($action . '_' . $method) {
	case 'create_GET':
		$model = Drinks::Blank();
		$view = "drinks/edit.php";
		break;
	case 'save_POST':
			$sub_action = empty($_REQUEST['id']) ? 'created' : 'updated';
			$errors = Drinks::Validate($_REQUEST);
			if(!$errors){
				$errors = Drinks::Save($_REQUEST);
			}
			
			if(!$errors){
				if($format == 'json'){
					header("Location: ?action=edit&format=json&id=$_REQUEST[id]");
				}else{
					header("Location: ?sub_action=$sub_action&id=$_REQUEST[id]");
				}
				die();
			}else{
				//my_print($errors);
				$model = $_REQUEST;
				$view = "drinks/edit.php";		
			}
			break;
	case 'edit_GET':
		$model = Drinks::Get($_REQUEST['id']);
		$view = "drinks/edit.php";		
		break;
	case 'delete_GET':
		$model = Drinks::Get($_REQUEST['id']);
		$view = "drinks/delete.php";		
		break;
	case 'delete_POST':
		$errors = Drinks::Delete($_REQUEST['id']);
		if($errors){
				$model = Drinks::Get($_REQUEST['id']);
				$view = "drinks/delete.php";
		}else{
				header("Location: ?sub_action=$sub_action&id=$_REQUEST[id]");
				die();			
		}
		break;
	case 'search_GET':
		$model = Drinks::Search($_REQUEST['q']);
		$view = 'drinks/index.php';		
		break;
	case 'index_GET':
	default:
		$model = Drinks::Get();
		$view = 'drinks/index.php';		
		break;
}

switch ($format) {
	case 'json':
		echo json_encode($model);
		break;
	case 'plain':
		include __DIR__ . "/../Views/$view";		
		break;		
	case 'web':
	default:
		include __DIR__ . "/../Views/shared/_Template.php";		
		break;
}