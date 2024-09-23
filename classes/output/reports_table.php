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
 * Reports table
 *
 * @package   block_powerbi
 * @copyright 2020 Daniel Neis Araujo <daniel@adapta.online>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_powerbi\output;

use moodle_url;
use renderable;
use templatable;
use renderer_base;

/**
 * Reports table renderable class.
 *
 * @package   block_powerbi
 * @copyright 2020 Daniel Neis Araujo <daniel@adapta.online>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class reports_table implements renderable, templatable {

    /** @var array $reports */
    public $reports = [];

    /**
     * Constructor.
     */
    public function __construct() {
        global $DB;
        $this->reports = $DB->get_records('block_powerbi_reports');
        foreach ($this->reports as $key => $r) {
            $url = new moodle_url('/blocks/powerbi/edit_report.php', ['id' => $r->id]);
            $this->reports[$key]->editurl = $url->out();

            $url = new moodle_url('/blocks/powerbi/view.php', ['id' => $r->id]);
            $this->reports[$key]->viewurl = $url->out();

            $url = new moodle_url('/blocks/powerbi/delete_report.php', ['id' => $r->id, 'sesskey' => sesskey()]);
            $this->reports[$key]->deleteurl = $url->out();
        }
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        return (object)[
            'hasreports' => !empty($this->reports),
            'reports' => array_values($this->reports),
            'addreporturl' => (new moodle_url('/blocks/powerbi/edit_report.php'))->out(),
        ];
    }
}
