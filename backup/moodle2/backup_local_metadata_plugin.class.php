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
 * @package    local_metadata
 * @version    1.0
 * @copyright  &copy; 2020 Kurvin Hendricks khendricks@2u.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


class backup_local_metadata_plugin extends backup_local_plugin {
    /**
     * Define (add) particular settings this activity can have
     */
    protected function define_my_settings() {
        // No particular settings for this activity.
    }
    protected function define_course_plugin_structure() {

        $plugin = $this->get_plugin_element(null, null, null);
        $pluginwrapper = new backup_nested_element(
            $this->get_recommended_name(),
            array('id'),
            array('instanceid', 'fieldid', 'data', 'dataformat')
        );
        // Connect the visible container ASAP.
        $plugin->add_child($pluginwrapper);

        $courseid = $this->task->get_courseid();
        // Set source to populate the data.
        $pluginwrapper->set_source_sql("SELECT id, instanceid, fieldid, data, dataformat FROM {local_metadata} WHERE instanceid = $courseid", array());

        return $plugin;
    }
}
