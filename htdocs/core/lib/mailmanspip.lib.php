<?php
/* Copyright (C) 2006-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * or see http://www.gnu.org/
 */

/**
 *	    \file       htdocs/core/lib/member.lib.php
 *		\brief      Ensemble de fonctions de base pour les adherents
 */

/**
 *  Return array head with list of tabs to view object informations
 *
 *  @return array Tabs of the module
 */
function mailmanspip_admin_prepare_head()
{
    global $langs;

    return array(
        array(
            DOL_URL_ROOT.'/adherents/admin/mailman.php',
            $langs->trans('Mailman'),
            'mailman'
        ),
        array(
            DOL_URL_ROOT.'/adherents/admin/spip.php',
            $langs->trans('SPIP'),
            'spip'
        )
    );
}

?>