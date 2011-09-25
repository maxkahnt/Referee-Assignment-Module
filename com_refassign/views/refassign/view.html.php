<?php
defined('_JEXEC') or die('Can only be loaded from within Joomla');

jimport('joomla.application.component.view');

class RefAssignViewRefAssign extends JView {
	function display($tpl = null) {
		$model = &$this->getModel();

		if($model->canSeeTable()) {
			$this->assign('cansee', true);
			$this->assignRef('gamedata', $model->getTable());
		} else {
			$this->assign('cansee', false);
			$this->assign('msg', 'Darfst du ja gar nicht.');
		}
		
		parent::display($tpl);	
	}
}
?>