<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin administration pages are defined here.
 *
 * @package     block_powerbi
 * @category    admin
 * @copyright   2020 Daniel Neis Araujo <daniel@adapta.online>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    // Client ID.
    $setting = new admin_setting_configtext('block_powerbi/clientid',
        new lang_string('clientid', 'block_powerbi'),
        new lang_string('clientiddesc', 'block_powerbi'), '', PARAM_TEXT);
    $settings->add($setting);

    // Client Secret.
    $setting = new admin_setting_configtext('block_powerbi/clientsecret',
        new lang_string('clientsecret', 'block_powerbi'),
        new lang_string('clientsecretdesc', 'block_powerbi'), '', PARAM_TEXT);
    $settings->add($setting);

    // Tenant.
    $setting = new admin_setting_configtext('block_powerbi/tenant',
        new lang_string('tenant', 'block_powerbi'),
        new lang_string('tenantdesc', 'block_powerbi'), '', PARAM_TEXT);
    $settings->add($setting);
}
