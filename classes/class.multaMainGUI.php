<?php
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MultiAssign/classes/User/class.multaUserGUI.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MultiAssign/classes/Course/class.multaCourseGUI.php');
require_once('class.ilMultiAssignPlugin.php');
use srag\DIC\MultiAssign\DICTrait;
/**
 * Class multaMainGUI
 *
 * @author            Fabian Schmid <fs@studer-raimann.ch>
 * @version           1.0.0
 *
 * @ilCtrl_Calls      multaMainGUI : multaUserGUI
 * @ilCtrl_Calls      multaMainGUI : multaCourseGUI
 * @ilCtrl_IsCalledBy multaMainGUI : ilRouterGUI, ilUIPluginRouterGUI
 */
class multaMainGUI {
	use DICTrait;
	public function __construct() {
		global $ilCtrl, $tpl, $lng, $ilTabs;
		/**
		 * @var ilCtrl     $ilCtrl
		 * @var ilTemplate $tpl
		 * @var ilLanguage $lng
		 * @var ilTabsGUI  $ilTabs
		 */
		$this->ilCtrl = $ilCtrl;
		$this->tpl = $tpl;
		$this->lng = $lng;
		$this->tabs = $ilTabs;
		$this->pl = ilMultiAssignPlugin::getInstance();
		//		$this->pl->updateLanguageFiles();
	}


	protected function initHeader() {
		$this->tpl->setTitle($this->pl->txt('header_title'));
		$this->tpl->setDescription($this->pl->txt('header_description'));
		$this->tpl->setTitleIcon(ilUtil::getImagePath('icon_usr.svg'));
	}


	public function executeCommand() {
		if (!multaAccess::hasAccess()) {
			ilUtil::sendFailure($this->pl->txt('access_denied'), true);
			if (self::version()->is6()) {
                $this->ilCtrl->redirectByClass(ilDashboardGUI::class);
            } else {
			$this->ilCtrl->redirectByClass(ilPersonalDesktopGUI::class);
			}
		}
		$this->initHeader();
		$next_class = $this->ilCtrl->getNextClass();
		switch ($next_class) {
			case '':
			case 'multausergui':
				$gui = new multaUserGUI();
				$this->ilCtrl->forwardCommand($gui);
				break;
			case 'multacoursegui':
				$gui = new multaCourseGUI();
				$this->ilCtrl->forwardCommand($gui);
				break;
		}
		if (self::version()->is6()) {
		    $this->tpl->loadStandardTemplate();
		    $this->tpl->printToStdout();
        } else {
		$this->tpl->getStandardTemplate();
		$this->tpl->show();
		}
	}
}
