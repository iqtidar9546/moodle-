<?php
echo "<link rel='stylesheet' type='text/css' href='style.css'>";

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
 * This file is the entry point to the assign module. All pages are rendered from here
 *
 * @package   mod_assign
 * @copyright 2012 NetSpot {@link http://www.netspot.com.au}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot . '/mod/assign/locallib.php');

$id = required_param('id', PARAM_INT);

list ($course, $cm) = get_course_and_cm_from_cmid($id, 'assign');

require_login($course, true, $cm);

$context = context_module::instance($cm->id);

require_capability('mod/assign:view', $context);

$assign = new assign($context, $cm, $course);
$urlparams = array('id' => $id,
                  'action' => optional_param('action', '', PARAM_ALPHA),
                  'rownum' => optional_param('rownum', 0, PARAM_INT),
                  'useridlistid' => optional_param('useridlistid', $assign->get_useridlist_key_id(), PARAM_ALPHANUM));

$url = new moodle_url('/mod/assign/view.php', $urlparams);
$PAGE->set_url($url);

// Update module completion status.
$assign->set_module_viewed();

// Apply overrides.
$assign->update_effective_access($USER->id);

// Get the assign class to
// render the page.
// print_r(optional_param('action', '', PARAM_ALPHA));
// die();
echo $assign->view(optional_param('action', '', PARAM_ALPHA));
?>
<script>
    $(document).ready(function () {
        $('.header.c0').empty().append('<i class="fa fa-check"></i>');
        $('.header.c1').empty().append('<i class="fa fa-image" title="Profile Image"></i>');
        $('.header.c2').empty().append('Name');

        $('.header.c3').empty().append('Email');
        $('.header.c4').empty().append('Status');

        $('.header.c5').empty().append('Grade');
        $('.header.c6').empty().append('Edit');
        
        $('.header.c7').empty().append('');

        $('.header.c8').empty().append('');

        $('.header.c9').empty().append('Comments');
        $('.header.c10').empty().append('Modified grade');

        $('.header.c11').empty().append('Feedback');
        $('.header.c12').empty().append('');
        $('.header.c13').empty().append('Final Grade');





    });
</script>