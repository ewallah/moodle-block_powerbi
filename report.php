<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * List reports
 *
 * @package   block_powerbi
 * @copyright 2020 Daniel Neis Araujo <daniel@adapta.online>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

$ctx = context_system::instance();

require_login();
require_capability('block/powerbi:addinstance', $ctx);

$PAGE->set_context($ctx);
$PAGE->set_url('/blocks/powerbi/report.php');
$PAGE->set_heading(new lang_string('pluginname', 'block_powerbi'));

$output = $PAGE->get_renderer('block_powerbi');

$list = new \block_powerbi\output\reports_list();

echo $output->header(),
     $output->heading(get_string('managereports', 'block_powerbi')),
     $output->render($list),
     $output->footer();
