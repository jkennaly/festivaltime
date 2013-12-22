/*
 //Copyright (c) 2013 Jason Kennaly.
 //All rights reserved. This program and the accompanying materials
 //are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
 //http://www.gnu.org/licenses/agpl.html
 //
 //Contributors:
 //    Jason Kennaly - initial API and implementation
 */

$(document).ready(function () {
    $('#newfestseries').click(function () {
        $("#overlay_form").css('visibility', 'visible');
//			alert('It coming');
        positionPopup();
    });

    $('#newfestvenue').click(function () {
        $("#overlay_form").css('visibility', 'visible');
//			alert('It coming');
        positionPopup();
    });

    //close popup
    $("#cancel").click(function () {
        $("#overlay_form").css('visibility', 'hidden');
    });

    $("#show-complete").click(function () {
        $("#festivalstatuscompleted").toggle();
    });

    $("#show-incomplete").click(function () {
        $("#festivalstatusincomplete").toggle();
    });

    $("#show-verifreq").click(function () {
        $("#festivalstatusverifreq").toggle();
    });

    $("#show-festbuttons").click(function () {
        $("#showfestivalbuttons").toggle();
        $("#show-festbuttons").hide();
    });

    $("#show-more-functions").click(function () {
        $("#showMoreFunctions").show();
        $("#show-more-functions").hide();
    });

    $(".feststatususer button[type=button]").click(function () {
        window.location = $(this).data('target');
    });

    $("#create-festival").click(function () {
        window.location = basepage + "?disp=create_festival" + "&fest=0";
    });

    $(".unlockbutton").click(function () {
        var targetFest = $(this).data('fest');

        $.ajax({

            type: "POST",
            url: basepage + "?disp=unlock",
            data: {fest: $(this).data('fest'), field: $(this).data('field')},
            success: function (data) {
                window.location = basepage + "?disp=festival_status" + "&fest=" + targetFest;
            }

        });

        return false;
    });

    $("#feststagsecomplete").click(function () {

        $.ajax({
            type: "POST",
            url: basepage + "?disp=entry_complete",
            data: {fest: $(this).data('fest'), field: "stages"},
            success: function (data) {
                window.location = basepage + "?disp=festival_status";
            }

        });

        return false;
    });

    $("#festbandlistcomplete").click(function () {

        $.ajax({
            type: "POST",
            url: basepage + "?disp=entry_complete",
            data: {fest: $(this).data('fest'), field: "band_list"},
            success: function (data) {
                window.location = basepage + "?disp=festival_status";
            }

        });

        return false;
    });

    $("#festbandprioritiescomplete").click(function () {

        $.ajax({
            type: "POST",
            url: basepage + "?disp=entry_complete",
            data: {fest: $(this).data('fest'), field: "band_priority"},
            success: function (data) {
                window.location = basepage + "?disp=festival_status";
            }

        });

        return false;
    });

    $("#festbanddayscomplete").click(function () {

        $.ajax({
            type: "POST",
            url: basepage + "?disp=entry_complete",
            data: {fest: $(this).data('fest'), field: "band_days"},
            success: function (data) {
                window.location = basepage + "?disp=festival_status";
            }

        });

        return false;
    });

    $("#festbandstagescomplete").click(function () {

        $.ajax({
            type: "POST",
            url: basepage + "?disp=entry_complete",
            data: {fest: $(this).data('fest'), field: "band_stages"},
            success: function (data) {
                window.location = basepage + "?disp=festival_status";
            }

        });

        return false;
    });

    $("#festbandsetimescomplete").click(function () {

        $.ajax({
            type: "POST",
            url: basepage + "?disp=entry_complete",
            data: {fest: $(this).data('fest'), field: "set_times"},
            success: function (data) {
                window.location = basepage + "?disp=festival_status";
            }

        });

        return false;
    });

    $('#festselectdates').click(function () {
        window.location = basepage + "?disp=festival_status";
    });

    $('#festaddmorebands').click(function () {
        window.location = basepage + "?disp=edit_band_list";
    });

    $('#festcheckstatus').click(function () {
        window.location = basepage + "?disp=festival_status";
    });

    $('#stopfestcreation').click(function () {
        window.location = basepage + "?disp=home";
    });


});

//position the popup at the center of the page
function positionPopup() {
    if (!$("#overlay_form").is(':visible')) {
        return;
    }

    $("#overlay_form").css({
        left: ($(window).width() - $('#overlay_form').width()) / 2,
        top: ($(window).width() - $('#overlay_form').width()) / 7,
        position: 'absolute'
    });
}


//maintain the popup at center of the page when browser resized
//$(window).bind('resize',positionPopup);


