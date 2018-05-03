<?php

/**
 * This software is intended for use with Oxwall Free Community Software http://www.oxwall.org/ and is a proprietary licensed product. 
 * For more information see License.txt in the plugin folder.

 * ---
 * Copyright (c) 2016, Ebenezer Obasi
 * All rights reserved.
 * info@eobai.com.

 * Redistribution and use in source and binary forms, with or without modification, are not permitted provided.

 * This plugin should be bought from the developer. For details contact info@eobasi.com.

 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
 * INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
 
class PLUGINLINKS_CLASS_MasterPage extends ADMIN_CLASS_MasterPage
{
	private $menuCmps = array();
	 
	/**
     * @see ADMIN_CLASS_MasterPage::init()
     */
    protected function init()
    {
		$language = OW::getLanguage();
		
		parent::init();

        $menuTypes = array(
            BOL_NavigationService::MENU_TYPE_ADMIN, 
			BOL_NavigationService::MENU_TYPE_APPEARANCE,
            BOL_NavigationService::MENU_TYPE_PAGES, 
			BOL_NavigationService::MENU_TYPE_PLUGINS, 
			BOL_NavigationService::MENU_TYPE_SETTINGS, 
			PLUGINLINKS_BOL_Service::MENU_TYPE_PLUGIN_SETTINGS,
            BOL_NavigationService::MENU_TYPE_USERS, 
			BOL_NavigationService::MENU_TYPE_MOBILE
        );

        $menuItems = BOL_NavigationService::getInstance()->findMenuItemsForMenuList($menuTypes);

        if ( defined('OW_PLUGIN_XP') )
        {
            foreach ( $menuItems as $key1 => $menuType )
            {
                foreach ( $menuType as $key2 => $menuItem )
                {
                    if ( in_array($menuItem['key'], array('sidebar_menu_plugins_add', 'sidebar_menu_themes_add')) )
                    {
                        unset($menuItems[$key1][$key2]);
                    }
                }
            }
        }

        $menuDataArray = array(
            'menu_admin' => BOL_NavigationService::MENU_TYPE_ADMIN,
            'menu_users' => BOL_NavigationService::MENU_TYPE_USERS,
            'menu_settings' => BOL_NavigationService::MENU_TYPE_SETTINGS,
            'menu_pluginlinks' => PLUGINLINKS_BOL_Service::MENU_TYPE_PLUGIN_SETTINGS,
            'menu_appearance' => BOL_NavigationService::MENU_TYPE_APPEARANCE,
            'menu_pages' => BOL_NavigationService::MENU_TYPE_PAGES,
            'menu_plugins' => BOL_NavigationService::MENU_TYPE_PLUGINS,
            'menu_mobile' => BOL_NavigationService::MENU_TYPE_MOBILE
        );

        foreach ( $menuDataArray as $key => $value )
        {
            $this->menuCmps[$key] = new ADMIN_CMP_AdminMenu($menuItems[$value]);
            $this->addMenu($value, $this->menuCmps[$key]);
        }
	}

    public function onBeforeRender()
    {
        parent::onBeforeRender();
        $language = OW::getLanguage();

        $arrayToAssign = array();

        /* @var $value ADMIN_CMP_AdminMenu */
        foreach ( $this->menuCmps as $key => $value )
        {
            //check if there are any items in the menu
            if ( $value->getElementsCount() <= 0 )
            {
                continue;
            }

            $id = UTIL_HtmlTag::generateAutoId("mi");

            $value->setCategory($key);
            $value->onBeforeRender();

            $menuItem = $value->getFirstElement();

            $arrayToAssign[$key] = array('id' => $id, 'firstLink' => $menuItem->getUrl(), 'key' => $key, 'isActive' => $value->isActive(), 'label' => $language->text('admin', 'sidebar_' . $key), 'sub_menu' => ( $value->getElementsCount() < 2 ) ? '' : $value->render(), 'active_sub_menu' => ( $value->getElementsCount() < 2 ) ? '' : $value->render('ow_admin_submenu'));
        }

        $this->assign('menuArr', $arrayToAssign);
    }
}