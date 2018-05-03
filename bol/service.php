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
 
class PLUGINLINKS_BOL_Service
{
	const SPOTLIGHT = 'aHR0cDovL3Nwb3RsaWdodC5ld3RuZXQudXMv';
	const MENU_TYPE_PLUGIN_SETTINGS = 'pluginlinks_settings';
	const PREFIX = 'pluginlinks';
	
	public static $classInstance;
	
	public static function getInstance()
	{
		if( self::$classInstance === null )
		{
			self::$classInstance = new self();
		}
		
		return self::$classInstance;
	}
	
}