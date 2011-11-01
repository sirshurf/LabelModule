<?php
/**
 * User Model Class
 * 
 * @author shurf
 */
class Labels_Model_SystemLabels
{
	
	public static function initLables ($boolForceReload = false)
	{
		
		$session_user = new Zend_Session_Namespace('labels');
		$objTable = new Labels_Model_Db_SystemLabels();
		
		if ($boolForceReload || empty($session_user->lables)) {
			
			$objRowSet = $objTable->fetchAll();
			
			$arrLables = array();
			
			foreach ($objRowSet as $objRow) {
				$arrLables[$objRow->{Labels_Model_Db_SystemLabels::COL_ID_SYSTEM}] = $objRow->{Labels_Model_Db_SystemLabels::COL_CONTENT};
			}
			
			$session_user->lables = $arrLables;
		
		}
		
		$objConfig = new Zend_Config($session_user->lables);
		
		$translate = new Zend_Translate(array(
			'adapter' => 'array', 'content' => $objConfig->toArray(), 'locale' => 'en'
		));

		// Create a log instance
		$writer = new Labels_Model_Log($objTable->getAdapter(), Labels_Model_Db_SystemLabels::TBL_NAME);
		
		$log = new Zend_Log($writer);
		
		// Attach it to the translation instance
		$translate->setOptions(array(
			'log' => $log, 'logMessage' => "%message%", 'logUntranslated' => true
		));
		
		Zend_Registry::set('Zend_Translate', $translate);
	
	}
	

	public static function setJgrowlMessage($strMessageLabel) {
		$messages = new Zend_Session_Namespace ( 'msg' );
		$messages->msg [] = $strMessageLabel;
	}
	
	public static function sendJgrowlMessages($objView) {
		$messages = new Zend_Session_Namespace ( 'msg' );
		
		foreach ( ( array ) $messages->msg as $strMessageLabel ) {
			$strMsgText = $objView->translate ( $strMessageLabel );
			if (! empty ( $strMessageLabel ) && ! empty ( $strMsgText )) {
				$objView->jQuery ()->addOnLoad ( '$.jGrowl("' . $strMsgText . '", { sticky: true });' );
			}
		}
		$messages->msg = array ();
	}

}