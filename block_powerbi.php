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
 * Block powerbi is defined here.
 *
 * @package     block_powerbi
 * @copyright   2020 Daniel Neis Araujo <daniel@adapta.online>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * powerbi block.
 *
 * @package    block_powerbi
 * @copyright  2020 Daniel Neis Araujo <daniel@adapta.online>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_powerbi extends block_base {

    /**
     * Initializes class member variables.
     */
    public function init() {
        $this->title = get_config('block_powerbi', 'title');
    }

    /**
     * Returns the block contents.
     *
     * @return stdClass The block contents.
     */
    public function get_content() {

        if ($this->content !== null) {
            return $this->content;
        }

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->text = null;

        $ctx = context_system::instance();

        if (has_capability('block/powerbi:viewreports', $ctx)) {
            $output = $this->page->get_renderer('block_powerbi');
            $list = new \block_powerbi\output\reports_list();
            $this->content->text = $output->render($list);
        }

        if (has_capability('block/powerbi:managereports', $ctx)) {
            $this->content->footer = html_writer::link(
                new moodle_url('/blocks/powerbi/report.php'),
                get_string('managereports', 'block_powerbi')
            );
        }

        return $this->content;
    }

    /**
     * Defines configuration data.
     *
     * The function is called immediatly after init().
     */
    public function specialization() {
    }

    /**
     * Enables global configuration of the block in settings.php.
     *
     * @return bool True if the global configuration is enabled.
     */
    public function has_config() {
        return true;
    }

    /**
     * Sets the applicable formats for the block.
     *
     * @return string[] Array of pages and permissions.
     */
    public function applicable_formats() {
        return [
            'all' => true,
        ];
    }
}
