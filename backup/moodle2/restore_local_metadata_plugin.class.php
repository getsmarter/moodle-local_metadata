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

defined('MOODLE_INTERNAL') || die();

/**
 * Used when restoring data created by the plugin.
 */
class restore_local_metadata_plugin extends restore_local_plugin {

    /**
     * Return the paths of the module data along with the function used for restoring that data.
    */
    protected function define_module_plugin_structure() {
        $paths = array();

        $elename = 'metadata';
        $elepath = $this->get_pathfor('/');
        $paths[] = new restore_path_element($elename, $elepath);

        return $paths;

    }

    /**
     * Restore the Local meta data for this module.
     *
     * @param object $data object The data we are restoring.
     * @throws dml_exception but catch to prevent
     * backup from restoring.
     */
    public function process_metadata($data) {
        global $DB;

        try {
            $fieldexists = $DB->record_exists(
            'local_metadata_field',
            array('shortname' => $data->shorname)
            );

            if ($fieldexists) {
                $fieldid = $DB->get_record('local_metadata_field', array('shortname' => $data->shortname))->id;
            }

            if ($fieldid != $data->fieldid) {
                $data->fieldid = $fieldid;
            }

            unset($data->shortname);

            $recexists = $DB->record_exists(
                'local_metadata',
                array('fieldid' => $data->fieldid, 'instanceid' => $this->task->get_moduleid())
            );

            if (!$recexists) {
                $data = (object)$data;
                $data->instanceid = $this->task->get_moduleid();

                $DB->insert_record('local_metadata', $data);
            }
        } catch (Exception $e) {
            
        }
    }
}
