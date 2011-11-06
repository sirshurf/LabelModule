<?php

class Labels_Form_UploadBkp extends ZendX_JQuery_Form
{

	public function init ()
	{
		/* Form Elements & Other Definitions Here ... */
		
		$this->setName('bkpFileForm');
		$this->setAttrib('id', 'bkpFileForm');
		$this->setAction($this->getView()
			->url(array('module' => 'labels', 'controller' => 'index', 'action' => 'loadbackup'), null, true));
		 $this->setAttrib('enctype', 'multipart/form-data');
		

		// creating object for Zend_Form_Element_File
		$objFile = new Zend_Form_Element_File('bkpFile');
		$objFile->setLabel('LBL_MENU_BKP_FILE')
//            ->setDestination('/tmp')
            ->setRequired(true);
		$this->addElement($objFile);
	
	}

}

