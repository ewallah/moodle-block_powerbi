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

namespace block_powerbi;

use core\hook\output\before_footer_html_generation;

use stdClass;

/**
 * Block powerbi plugin hook listener
 *
 * @package     block_powerbi
 * @copyright   2022 Daniel Neis Araujo <danielneis@gmail.com>
 * @author      Renaat Debleu <info@eWallah.net>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class hook_listener {

    /**
     * Callback for the before_footer_html_genaration.
     *
     * @param \core\hook\output\before_footer_html_generation $hook
     */
    public static function before_footer_html_generation(before_footer_html_generation $hook): void {
        global $CFG;
        $hook->add_html("<script src='$CFG->wwwroot/blocks/powerbi/js/powerbi.js'></script>");
    }
}
