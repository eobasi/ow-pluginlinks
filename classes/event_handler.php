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
 
class PLUGINLINKS_CLASS_EventHandler
{
	public static $classInstance;
	
	public static function getInstance()
	{
		if( self::$classInstance === null )
		{
			self::$classInstance = new self();
		}
		
		return self::$classInstance;
	}
	
	public function init()
	{
		OW::getEventManager()->bind(OW_EventManager::ON_FINALIZE, array($this, 'onFinalize'));
		OW::getEventManager()->bind(OW_EventManager::ON_AFTER_PLUGIN_ACTIVATE, array($this, 'pluginActivate'));
        OW::getEventManager()->bind(OW_EventManager::ON_BEFORE_PLUGIN_DEACTIVATE, array($this, 'pluginDeactivate'));
        OW::getEventManager()->bind(OW_EventManager::ON_AFTER_PLUGIN_INSTALL, array($this, 'pluginInstall'));
        OW::getEventManager()->bind(OW_EventManager::ON_AFTER_PLUGIN_UNINSTALL, array($this, 'pluginUninstall'));
	}
	
	public function onFinalize( $e )
	{
		$attrs = OW::getRequestHandler()->getHandlerAttributes();
		
		if ( !is_subclass_of($attrs[OW_RequestHandler::ATTRS_KEY_CTRL], "ADMIN_CTRL_Abstract") )
		{
			return;
		}
		
		$static = OW::getPluginManager()->getPlugin('pluginlinks')->getStaticUrl() . 'img';
		
		$style = "
			.ow_admin_submenu_hover {max-height: 250px;overflow-y: auto;}
			.ow_admin_menu_item.active + .ow_admin_submenu {max-height: 200px;overflow-y: auto;}
			.ow_admin_menu_item.menu_pluginlinks {background-image: url($static/pluginlinks_icon.png);}
			.ow_page_container.ow_admin .ow_page {min-height: 600px;}
		";
		
		$document = OW::getDocument();
        $document->setMasterPage(new PLUGINLINKS_CLASS_MasterPage());
		$document->addStyleDeclaration( $style );
	}
	
    public function pluginInstall( OW_Event $e )
    {
        $params = $e->getParams();
        $key = $params['pluginKey'];
		$langService = BOL_LanguageService::getInstance();
		
		if( !empty($langService->findKey( PLUGINLINKS_BOL_Service::PREFIX, PLUGINLINKS_BOL_Service::PREFIX .'_'. $key )) )
		{
			return;
		}
		
		$lang = $langService->findByTag('en');
		
		$plugin = BOL_PluginService::getInstance()->findPluginByKey( $key );
		
		$langService->addValue($lang->id, PLUGINLINKS_BOL_Service::PREFIX, PLUGINLINKS_BOL_Service::PREFIX .'_'. $key, $plugin->title, true);
		
		
		if( $plugin->isSystem == 0 && !empty($plugin->adminSettingsRoute) )
		{
			OW::getNavigation()->addMenuItem(PLUGINLINKS_BOL_Service::MENU_TYPE_PLUGIN_SETTINGS, $plugin->adminSettingsRoute, PLUGINLINKS_BOL_Service::PREFIX, PLUGINLINKS_BOL_Service::PREFIX .'_'. $key, OW_Navigation::VISIBLE_FOR_MEMBER);
		}
    }
	
    public function pluginUninstall( OW_Event $e )
    {
        $params = $e->getParams();
        $key = $params['pluginKey'];
		$langService = BOL_LanguageService::getInstance();
		$lang = $langService->findKey( PLUGINLINKS_BOL_Service::PREFIX, $key );
		
		if( !empty($lang) )
		{
			$langService->deleteKey($lang->id, true);
		}
		
		OW::getNavigation()->deleteMenuItem(PLUGINLINKS_BOL_Service::PREFIX, PLUGINLINKS_BOL_Service::PREFIX .'_'. $key);
    }
	
    public function pluginActivate( OW_Event $e )
    {
        $params = $e->getParams();
        $key = $params['pluginKey'];
		$plugin = BOL_PluginService::getInstance()->findPluginByKey( $key );
		
		if( $plugin->isSystem != 0 || empty($plugin->adminSettingsRoute) )
		{
			return;
		}
		
		OW::getNavigation()->addMenuItem(PLUGINLINKS_BOL_Service::MENU_TYPE_PLUGIN_SETTINGS, $plugin->adminSettingsRoute, PLUGINLINKS_BOL_Service::PREFIX, PLUGINLINKS_BOL_Service::PREFIX .'_'. $key, OW_Navigation::VISIBLE_FOR_MEMBER);
    }

    public function pluginDeactivate( OW_Event $e )
    {
        $params = $e->getParams();
        $key = $params['pluginKey'];		
		
		OW::getNavigation()->deleteMenuItem(PLUGINLINKS_BOL_Service::PREFIX, PLUGINLINKS_BOL_Service::PREFIX .'_'. $key);
    }
}