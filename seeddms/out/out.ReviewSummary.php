<?php

/**
 * MyDMS. Document Management System
 * Copyright (C) 2002-2005 Markus Westphal
 * Copyright (C) 2006-2008 Malcolm Cowe
 * Copyright (C) 2010 Matteo Lucarelli
 * Copyright (C) 2010-2024 Uwe Steinmann
 *
 * PHP version 8
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 *
 * @category SeedDMS
 * @package  SeedDMS
 * @author   Uwe Steinmann <info@seeddms.org>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://www.seeddms.org Main Site
 */

if (!isset($settings)) {
	require_once "../inc/inc.Settings.php";
}
require_once "inc/inc.Utils.php";
require_once "inc/inc.LogInit.php";
require_once "inc/inc.Language.php";
require_once "inc/inc.Init.php";
require_once "inc/inc.Extension.php";
require_once "inc/inc.DBInit.php";
require_once "inc/inc.ClassUI.php";
require_once "inc/inc.Authentication.php";

$tmp = explode('.', basename($_SERVER['SCRIPT_FILENAME']));
$view = UI::factory($theme, $tmp[1], array('dms'=>$dms, 'user'=>$user));
$accessop = new SeedDMS_AccessOperation($dms, $user, $settings);
if (!$accessop->check_view_access($view, $_GET)) {
	UI::exitError(getMLText("my_documents"),getMLText("access_denied"));
}

if ($user->isGuest()) {
	UI::exitError(getMLText("my_documents"), getMLText("access_denied"));
}

if ($view) {
	$view->setParam('showtree', showtree());
	$view->setParam('cachedir', $settings->_cacheDir);
	$view->setParam('previewWidthList', $settings->_previewWidthList);
	$view->setParam('previewConverters', isset($settings->_converters['preview']) ? $settings->_converters['preview'] : array());
	$view->setParam('timeout', $settings->_cmdTimeout);
	$view->setParam('accessobject', $accessop);
	$view->setParam('xsendfile', $settings->_enableXsendfile);
	$view->setParam('onepage', $settings->_onePageMode); // do most navigation by reloading areas of pages with ajax
	$view($_GET);
	exit;
}
