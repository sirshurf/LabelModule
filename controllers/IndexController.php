<?php
/**
 * LabelsController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class Labels_IndexController extends Zend_Controller_Action {
	
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		$objSystemMessages = new Labels_Model_Db_SystemLabels ();
		$objSystemMessagesSelect = $objSystemMessages->select ();
		
		$strUrl = $this->view->url ( array ('module' => 'labels', 'controller' => 'index', 'action' => 'edit' ), null, false, true );
		
		$arrOptions = array ("hiddengrid" => false, "editurl" => $strUrl );

		$arrOptions ['plugin'] ['pager'] ['edit'] ['beforeShowForm'] = " function(form) { $('#".Labels_Model_Db_SystemLabels::COL_ID_SYSTEM."', form).attr('disabled','disabled');  } ";
		
		
		$grid = new Ingot_JQuery_JqGrid ( 'systemmessages', new Ingot_JQuery_JqGrid_Adapter_DbTableSelect ( $objSystemMessagesSelect ), $arrOptions );
		$grid->setIdCol ( Labels_Model_Db_SystemLabels::COL_ID_SYSTEM );
				
		$objPlugin = $grid->getPager ();
		$objPlugin->setDefaultAdd ();
		$objPlugin->setDefaultEdit ();
		
		$grid->addColumn ( new Ingot_JQuery_JqGrid_Column ( Labels_Model_Db_SystemLabels::COL_ID_SYSTEM, array ("editable" => true ) ) );
		$grid->addColumn ( new Ingot_JQuery_JqGrid_Column ( Labels_Model_Db_SystemLabels::COL_CONTENT, array ("editable" => true ) ) );
		
		$grid->registerPlugin ( new Ingot_JQuery_JqGrid_Plugin_ToolbarFilter () );		
		$this->view->grid = $grid->render (null, array('DblClkEdit'=>TRUE));
		
		// Buttons set
		$arrButtons = array ();
		
//		$arrButtons [] = array ('module' => 'labels', 'controller' => 'index', "action" => "inittable", "name" => 'LBL_BUTTON_MSG_INIT_TABLE' );
		
		$this->view->arrActions = $arrButtons;
	}
	
	public function editAction() {
		
		$intId = $this->_request->getParam ( 'id' );
		
		// Get model object
		$objSystemSettings = new Labels_Model_Db_SystemLabels ();
		
		if (! empty ( $intId )) {
			$objRowSet = $objSystemSettings->find($intId);
			
			if ($objRowSet->count() > 0){
				$objRow = $objRowSet->current();
			} else {
				$this->view->data = array ("code" => "error", "msg" => $this->view->translate ( "LBL_ERROR_UNAUTHORIZED" ) );
				return;
			}			
		} else {
			$this->view->data = array ("code" => "error", "msg" => $this->view->translate ( "LBL_ERROR_UNAUTHORIZED" ) );
			return;
		}
		
		if ("del" == $this->_request->getParam ( "oper" )) {
			if ($objRow->delete ()) {
				// Deleted 
				$this->view->data = array ("code" => "ok", "msg" => "" );
			} else {
				// Delete failed
				$this->view->data = array ("code" => "error", "msg" => $this->view->translate ( "LBL_DEL_FAIL" ) );
			}
		} else {
			if ($this->_request->isPost ()) {
				$arrData = $this->_request->getPost ();
				$objRow->setFromArray ( $arrData );
				$intId = $objRow->save ();
				if (! empty ( $intId )) {
					$this->view->data = array ("code" => "ok", "msg" => "" );
				} else {
					$this->view->data = array ("code" => "error", "msg" => $this->view->translate ( "LBL_UPDATE_FAIL" ) );
				}
			} else {
				$this->view->data = array ("code" => "error", "msg" => $this->view->translate ( "LBL_UPDATE_FAIL" ) );
			
			}
		}
		Labels_Model_SystemLabels::initLables(TRUE);
	
	}
	
	
}