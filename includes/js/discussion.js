/*
 //Copyright (c) 2013-2014 Jason Kennaly.
 //All rights reserved. This program and the accompanying materials
 //are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
 //http://www.gnu.org/licenses/agpl.html
 //
 //Contributors:
 //    Jason Kennaly - initial API and implementation
 */

$(document).ready(function () {

    $(".viewDiscussion button[type=button]").click(function () {
        var messageID = $(this).data('messageid');

        $.ajax({

            type: "POST",
            url: basepage + "?disp=view_discussion",
            data: {message: $(this).data('messageid'), user: $(this).data('viewinguser')},
            success: function (data) {
                $("#discussion-" + messageID).show();
                $(this).hide();
            }

        });


        //  return false;
    });

});



