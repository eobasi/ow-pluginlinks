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

class PLUGINLINKS_CTRL_Admin extends ADMIN_CTRL_Abstract
{
	public function settings()
    {
		$config = OW::getConfig();
		$lang = OW::getLanguage();

		$this->setPageTitle($lang->text('pluginlinks', 'spotlight_title'));
		$this->setPageHeading($lang->text('pluginlinks', 'admin_donate_heading'));
        $this->setPageHeadingIconClass('ow_ic_gear_wheel');

		$soft = $config->getValue('base', 'soft_version');
        $build = $config->getValue('base', 'soft_build');
        $theme = $config->getValue('base', 'selectedTheme');
		$siteName = $config->getValue('base', 'site_name');
		$siteEmail = $config->getValue('base', 'site_email');
		$url = OW::getRouter()->getBaseUrl();
		$plugins = BOL_PluginService::getInstance()->findActivePlugins();
		$alphabeth = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
		$plugin = array();
		$latters = array();
		$titles = array();

		$buildQuery = OW::getRequest()->buildUrlQueryString(base64_decode(PLUGINLINKS_BOL_Service::SPOTLIGHT), array(
			'u'=> $url,
			's'=> base64_encode($soft),
			'b'=> base64_encode($build),
			'n'=> base64_encode($siteName),
			't'=> base64_encode($theme),
			'e'=> base64_encode($siteEmail)
		));
		
		if( !empty($plugins) )
		{
			foreach( $plugins as $item )
			{
				if( $item->isSystem == 0 && !empty($item->adminSettingsRoute))
				{
					$plugin[] = array(
						'caps' => $item->title[0],
						'title' => $item->title,
						'desc' => $item->getDescription(),
						'url' => OW::getRouter()->urlForRoute($item->adminSettingsRoute)
					);
					$titles[] = mb_strtolower($item->title[0]);
				}

			}

			foreach( $alphabeth as $caps )
			{
				if( in_array(mb_strtolower($caps), $titles) )
				{
					$latters[] = array(
						'caps' => $caps,
						'color' => ($this->random_color() == 'ffffff') ? '000' : $this->random_color(),
						'lower' => mb_strtolower($caps)
					);
				}
			}
		}

		$this->assign('plugins', $plugin);
		$this->assign('alphabeth', $latters);
		$this->assign('url', $buildQuery);
	}

	private function random_color() 
	{
		return sprintf('%06X', mt_rand(0, 0xFFFFFF));
	}
}