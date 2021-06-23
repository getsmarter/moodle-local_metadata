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

class backup_local_metadata_plugin extends backup_local_plugin {

    /**
     * Required by Moodle's backup tool to define the plugin structure.
     *
     * @return backup_plugin_element
     * @throws backup_step_exception
     * @throws base_element_struct_exception
     */    
    protected function define_module_plugin_structure() {
        $plugin = $this->get_plugin_element();
        $pluginwrapper = new backup_nested_element(
            $this->get_recommended_name(),
            array('id'),
            array('instanceid', 'fieldid', 'data', 'dataformat', 'shortname')
        );
        $plugin->add_child($pluginwrapper);

        // Set source to populate the data.
        $pluginwrapper->set_source_sql("SELECT lm.id, instanceid, fieldid, data, dataformat, shortname FROM {local_metadata} lm left join {local_metadata_field} lmf on lm.fieldid = lmf.id  where instanceid = ?", array(backup_helper::is_sqlparam($this->task->get_moduleid())));

        return $plugin;
    }
}
