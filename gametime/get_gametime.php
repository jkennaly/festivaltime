<?php
/*
//Copyright (c) 2013-2014 Jason Kennaly.
//All rights reserved. This program and the accompanying materials
//are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
//http://www.gnu.org/licenses/agpl.html
//
//Contributors:
//    Jason Kennaly - initial API and implementation
*/


?>

<div id="content">


    <?php
    $right_required = "CreateNotes";
    If (!isset($_SESSION['level']) || !CheckRights($_SESSION['level'], $right_required)) {
        die("<div id=\"content\">You do not have rights to access this page. You can login or register here: <a href=\"" . $basepage . "?disp=login\">FestivalTime</a></div> <!-- end #content -->");
    }

    //Choose a festival

    //After festival is chosen, generate data files.

    $myFestivals = userFestivals($user);
    $gametimeKey = getGametimeKey($user);
    if (empty($myFestivals)) echo "You are not signed up for any festivals.";
    else {

        ?>
        <h3>My Festivals</h3>
        <menu type="toolbar">
            <?php
            foreach ($myFestivals as $myF) {
                $festDates = getAllDates($myF);
                foreach ($festDates as $fD) {
                    ?>
                    <button type="button" class="mobile-button" data-fest="<?php echo $myF; ?>"
                            data-date="<?php echo $fD['id']; ?>"><?php echo getFname($myF) . " " . $fD['name']; ?></button>
                <?php
                }
            }
            ?>
        </menu>
    <?php
    }

    ?>
</div> <!-- end #content -->
<script>
    $(document).ready(function () {
        $(".mobile-button").click(function () {
            var targetFest = $(this).data('fest');
            var targetDate = $(this).data('date');
            var basepage = "<?php echo $basepage; ?>";
            var key = "<?php echo $gametimeKey; ?>";
            $.ajax({

                type: "POST",
                url: basepage + "gametime/create_files.php",
                data: {fest: $(this).data('fest'), user: '<?php echo $user; ?>', key: '<?php echo $gametimeKey; ?>'},
                success: function (data) {
                    document.cookie = "user=" + <?php echo $user; ?>;
                    document.cookie = "fest=" + targetFest;
                    document.cookie = "date=" + targetDate;
                    document.cookie = "key=" + key;
                    window.localStorage.setItem('data', JSON.stringify(data));
                    window.location = basepage + "gametime/app";
                },
                error: function () {
                    alert('You are fucked');
                }

            });

            return false;
        });
    });
</script>