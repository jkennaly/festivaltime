/*
 //Copyright (c) 2013 Jason Kennaly.
 //All rights reserved. This program and the accompanying materials
 //are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
 //http://www.gnu.org/licenses/agpl.html
 //
 //Contributors:
 //    Jason Kennaly - initial API and implementation
 */

$(document).ready(
    function () {
        $('#setprivaybutton').click(
            function (e) {
                $('#context-selector-1').css('visibility', 'visible');
                $('#context-selector-2').css('visibility', 'visible');
                $('#context-selector-2').text(basepage);
                $.getJSON(basepage + "includes/php/demo_ajax_json.php", function (result) {
                    $.each(result, function (i, field) {
                        $("#context-selector-2").append(field + " ");
                        $('#context-selector-2').css('visibility', 'visible');
                    });
                });
            }
        );
    }
);