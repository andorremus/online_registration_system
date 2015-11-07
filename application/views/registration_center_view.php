<script>

    $(document).ready(function()
    {
        window.organiserLoggedIn = false;
        window.managerLoggedIn = false;

        hideTheRest = function (selected)       // this hides all of the forms except the one passed as an argument
        {
            $(".forms").hide();
            //alert(selected);
            $("#" + selected + "").show();
        };
        updateSeminars();   // populate available seminars at the beginning for the user to register

        hideTheRest('seminar_registration_form');  // show only the seminar registration form at start-up

        var seminarUpdater = setInterval(updateSeminars, 10000);  // Add any more seminars if they're added in the meantime


        function updateSeminars()
        {
            $.ajax({
                type: "GET",
                url: "<?php echo base_url(); ?>registration_center/retrieve_seminars",   // Call the controller method to retrieve the
                dataType: 'json',                                                         // available seminars
                success: function (json) {
                    //alert(json[0].seminarId);
                    $.each(json, function (key, value) {
                        //alert($("#"+value.seminarId).length > 0);
                        var alreadyInserted = $("#" + value.seminarId).length > 0;  // Check if the seminar has already been inserted in the select tag
                        //alert(alreadyInserted);
                        if (!alreadyInserted) {            // If it hasn't been already, insert it
                            $("<option id=" + value.seminarId + ">" + value.seminarId + "</option>").appendTo("#seminars");
                        }
                    });

                }
            });
        }


        function populateSeminarOverview(username)
        {
            $.ajax({          // Call the controller method to retrieve the
                type: "GET",  //  seminars belonging to the seminar organiser
                url: "<?php echo base_url(); ?>registration_center/retrieve_seminar_overview/"+username+"",
                dataType: 'json',
                success: function (json)
                {

                    $('#wrapper').html('');
                    $("<h3>The following seminars are organised by you: </h3>").appendTo("#wrapper");
                    $.each(json, function (key, value)
                    {
                        var table = $('<table class="seminarTable">');
                        $("<caption> Seminar No:" + value.seminarId + "<caption>").appendTo(table);
                        $("<tr><td> Seminar Title:</td><td>" + value.title+ "</td></tr>").appendTo(table);
                        $("<tr><td> Organiser's Name:</td><td>" + value.firstName +" "+ value.lastName+ "</td></tr>").appendTo(table);
                        $("<tr><td> Seminar Start Time:</td><td>" + value.startTime + "</td></tr>").appendTo(table);
                        $("<tr><td> Seminar End Time:</td><td>" + value.endTime + "</td></tr>").appendTo(table);
                        $("<tr><td> Seminar Description:</td><td>" + value.description + "</td></tr>").appendTo(table);
                        $("<tr><td> Places Filled So Far:</td><td>" + value.placesFilledSoFar +" out of "+value.placesAvailable  + " available </td></tr>").appendTo(table);
                        $(table).appendTo("#wrapper");
                    });

                    $.ajax({
                        url: "<?php echo base_url(); ?>registration_center/retrieve_seminars_stats/"+username+"",
                        dataType: 'json',
                        success: function (json) {
                            $("<h3> Institutions participating</h3>").appendTo("#wrapper");
                            $.each(json, function (key, value)
                            {
                                var tableStats = $('<table class="seminarTable">');
                                $("<tr><td> Number of People : </td><td>" + value.noOfPeople + "</td></tr>").appendTo(tableStats);
                                $("<tr><td> Institution : </td><td>" + value.institution + "</td></tr>").appendTo(tableStats);
                                $(tableStats).appendTo("#wrapper");
                            });
                        }
                    });
                }
            });
        };



        $('#seminar_overview_login_form').submit(function(event)
        {
            event.preventDefault();
            var username = $(this).find("input[name='username']").val();
            var formInputValues=$('#login_form').serialize();

            $.ajax({
                type:"GET",
                url: "<?php echo base_url(); ?>registration_center/validate_seminar_organiser_login",
                data : formInputValues,

                success: function(data)
                {
                    if(data == "TRUE")
                    {
                        window.organiserLoggedIn = true;
                        alert("Login was successful. Welcome ");
                        populateSeminarOverview(username);
                        // Populate the overview the first time and the set it to update it each 12 seconds
                        window.populateOverviewUpdater = setInterval(function() { populateSeminarOverview(username) },12000);
                        hideTheRest('seminar_overview_display');
                    }
                    else
                    {
                        alert("Incorrect credentials! Please try again");
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        });

        $('#organiser_logout_button').click(function()
        {
            window.organiserLoggedIn = false;
            alert("Logout was successful. Goodbye! ");
            hideTheRest('seminar_overview_login_form');
            clearInterval(window.populateOverviewUpdater);
        });

        function populateManagerOverview()
        {   // Get the past seminars and display them
            $.ajax
            ({          // Call the controller method to retrieve the
                type: "GET",  //  seminars overview
                url: "<?php echo base_url(); ?>registration_center/retrieve_past_seminars",
                dataType: 'json',
                success: function (json)
                {

                    $('#manager_wrapper').html('');
                    $("<h2> Past Seminars</h2>").appendTo("#manager_wrapper");
                    $.each(json, function (key, value)
                    {
                        var table = $('<table class="seminarTable">');
                        $("<caption> Seminar No:" + value.seminarId + "<caption>").appendTo(table);
                        $("<tr><td> Seminar Title:</td><td>" + value.title+ "</td></tr>").appendTo(table);
                        $("<tr><td> Seminar Description:</td><td>" + value.description + "</td></tr>").appendTo(table);
                        $("<tr><td> Seminar Start Time:</td><td>" + value.startTime + "</td></tr>").appendTo(table);
                        $("<tr><td> Seminar End Time:</td><td>" + value.endTime + "</td></tr>").appendTo(table);
                        $("<tr><td> Attendance:</td><td>" + value.attendance+" </td></tr>").appendTo(table);
                        $(table).appendTo("#manager_wrapper");
                    });

                }
            });

            // Get the upcoming seminars and display them

            $.ajax
            ({
                url: "<?php echo base_url(); ?>registration_center/retrieve_upcoming_seminars",
                dataType: 'json',
                success: function (json) {
                    $("<h2> Upcoming Seminars</h2>").appendTo("#manager_wrapper");
                    $.each(json, function (key, value)
                    {
                        var table = $('<table class="seminarTable">');
                        $("<caption> Seminar No:" + value.seminarId + "<caption>").appendTo(table);
                        $("<tr><td> Seminar Title:</td><td>" + value.title+ "</td></tr>").appendTo(table);
                        $("<tr><td> Seminar Description:</td><td>" + value.description + "</td></tr>").appendTo(table);
                        $("<tr><td> Seminar Start Time:</td><td>" + value.startTime + "</td></tr>").appendTo(table);
                        $("<tr><td> Seminar End Time:</td><td>" + value.endTime + "</td></tr>").appendTo(table);
                        getExpectedAttendance(value.room,table);  // Get the expected attendance for the seminar based on
                        $(table).appendTo("#manager_wrapper");      //   the room history
                    });
                }
            });
            // Get the rooom popularity and display it
            $.ajax
            ({
                url: "<?php echo base_url(); ?>registration_center/retrieve_room_popularity",
                dataType: 'json',
                success: function (json) {
                    $("<h2> Room popularity</h2>").appendTo("#manager_wrapper");
                    $.each(json, function (key, value)
                    {
                        var table = $('<table class="seminarTable">');
                        $("<tr><td> Room Id:</td><td>" + value.roomId+ "</td></tr>").appendTo(table);
                        $("<tr><td> Seminars Hosted:</td><td>" + value.seminarsHosted + "</td></tr>").appendTo(table);
                        $(table).appendTo("#manager_wrapper");
                    });
                }
            });
        }

        function getExpectedAttendance(roomId,table)
        {
            $.ajax
            ({
                url: "<?php echo base_url(); ?>registration_center/retrieve_expected_attendance",
                dataType: 'json',
                success: function (json) {
                    $.each(json, function (key, value)
                    {
                        if(value.roomId == roomId)
                        {
                            $("<tr><td> Expected Attendance:</td><td>" + Math.floor(value.average) + "</td></tr>").appendTo(table);
                        }
                    });
                }
            });

        }
        $('#centre_management_login_form').submit(function(event)
        {
            event.preventDefault();
            var formInputValues=$('#manager_login_form').serialize();
            $.ajax({
                type:"GET",
                url: "<?php echo base_url(); ?>registration_center/validate_centre_manager_login",
                data : formInputValues,
                success: function(data)
                {
                    if(data == "TRUE")
                    {
                        window.managerLoggedIn = true;
                        alert("Login was successful. Welcome ");
                        populateManagerOverview();
                        // Populate the overview the first time and the set it to update it each 12 seconds
                        window.populateManagerOverviewUpdater = setInterval(function() { populateManagerOverview() },12000);
                        hideTheRest('manager_overview_display');
                    }
                    else
                    {
                        alert("Incorrect credentials! Please try again");
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        });

        $('#manager_logout_button').click(function()
        {
            alert("Logout was successful. Goodbye! ");
            window.managerLoggedIn = false;
            hideTheRest('centre_management_login_form');
            clearInterval(window.populateManagerOverviewUpdater);
        });

        var capArray = [];
        var roomDetails = [];
        updateRoomDetails();

        function updateRoomDetails()
        {
            $.ajax({
                type: "GET",
                url: "<?php echo base_url(); ?>registration_center/retrieve_room_details", // Call the controller method to retrieve the
                dataType: 'json',                                                         // available roms
                async:false,
                success: function (json) {
                    //alert(json[0].seminarId);
                    $.each(json, function (key, value) {
                        //alert(value.roomId);
                        //alert(value.roomName);
                        //alert(value.capacity);
                        //alert(value.location);
                        $("<option class='rooms'>" + value.roomId + "</option>").appendTo("#room");
                        capArray.push(value.capacity);
                        roomDetails.push(value.roomName);
                        roomDetails.push(value.location);
                    });
                }
            });
        }

        function resetForm($form)
        {
            $form.find('input:text, input:password, input:file, select, textarea').val('');
            $form.find('input:radio, input:checkbox')
                .removeAttr('checked').removeAttr('selected');
        }

        function chooseCapacity()   // JS function to get the details of a particular room based on the room chosen
        {
            var roomChosen = document.getElementById("room").value;
            //alert(roomChosen);

            //alert(capArray[roomChosen]);

            if(!isNaN(roomChosen))
            {
                document.getElementById("capacity").max = capArray[roomChosen-1];
            }
            document.getElementById("roomHeld").innerHTML = "Room to be Held In: " + roomDetails[(roomChosen*2)-2] + " - " + roomDetails[(roomChosen*2)-1];
        }
        document.getElementById('room').addEventListener("change",chooseCapacity);  // Every time the user changes the room, trigger this function

        $('#attendee_registration_form').submit(function(event)
        {
            event.preventDefault();
            var formInputValues = $(this).find('#attendee_registration_form').serialize();

            var seminarId = $(this).find("select[name='seminars']").val();
            $('#attendee_withdrawal_form').find("input[name='seminarId']").val(seminarId);

            var firstName = $(this).find("input[name='attendee_f_name']").val();
            $('#attendee_withdrawal_form').find("input[name='firstName']").val(firstName);

            var lastName= $(this).find("input[name='attendee_l_name']").val();
            $('#attendee_withdrawal_form').find("input[name='lastName']").val(lastName);

            var email = $(this).find("input[name='attendee_email']").val();
            $('#attendee_withdrawal_form').find("input[name='email']").val(email);

            var institution = $(this).find("input[name='attendee_institution']").val();
            $('#attendee_withdrawal_form').find("input[name='institution']").val(institution);

            $.ajax({
                type:"GET",
                url: "<?php echo base_url(); ?>registration_center/validate_attendee_registration_form",
                data : formInputValues,

                success: function(data)
                {
                    if(data == "AR")
                    {
                        alert("You are already registered for this seminar!");
                        return true;
                    }
                    else if(data == "NPA")
                    {
                        alert("There are no more places available for this seminar");
                    }
                    else if(data == "REG")
                    {
                        alert("You have been registered successfully");
                        populateTicketTable(email,seminarId);
                        hideTheRest('attendee_ticket_table');
                    }
                    else if(data = "FAIL")
                    {
                        alert("There was an error");
                    }

                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });

            return false;

        });

        var populateTicketTable = function(email,seminarId)
        {

            $.ajax({
                type: "GET",
                url: "<?php echo base_url(); ?>registration_center/retrieve_ticket_details/"+email+"/"+seminarId+"", // Call the controller method to retrieve the
                dataType: 'json',                                                         // available roms
                success: function (json) {
                    //alert(json[0].seminarId);
                    $.each(json, function (key, value) {
                        //alert(value.roomId);
                        //alert(value.roomName);
                        //alert(value.capacity);
                        //alert(value.location);
                        //$("<option class='rooms'>" + value.roomId + "</option>").appendTo("#room");
                        $('#tableTicket').find('#seminarNo').html("<h2>Seminar No :"+value.seminarId+ "</h2>");
                        $('#tableTicket').find('#names').html(value.firstName+" "+ value.lastName);
                        $('#tableTicket').find('#ticketId').html("<h2>Ticket Id :"+value.ticketId+ "</h2><br>");
                        $('#tableTicket').find('#title').html(value.title);
                        $('#tableTicket').find('#locationAndTime').html(value.roomName+ "<br> Starting time and date : "+value.startTime);
                        $('#tableTicket').find('#description').html(value.description);
                        $('#tableTicket').find('#unsubscribeLink').html("You can unsubscribe by following this link : </br>" +
                        "<?php echo base_url(); ?><br>registration_center/withdraw_attendee/"+value.email+"/"+value.seminarId );
                    });
                }
            });

        };

        $('#attendee_withdrawal_form').submit(function(event)
        {
            event.preventDefault();
            var seminarId = $(this).find("input[name='seminarId']").val();
            var email = $(this).find("input[name='email']").val();
            $.ajax
            ({
                type: "GET",
                url: "<?php echo base_url(); ?>registration_center/withdraw_attendee/" + email + "/" + seminarId + "",
                success: function (data)
                {
                    if (data) {
                        alert("You have been unregistered successfully!");
                        hideTheRest("attendee_registration_form");
                    }
                    else
                    {
                        alert("Unregistration failed!");
                    }
                }

            });

        });

        $('#unregisterButton').click(function () {
            hideTheRest("attendee_withdrawal_form");
        });

        $('#seminar_registration_form').submit(function(event)
        {
            event.preventDefault();
            var formInputValues = $(this).find(':input').serialize();

            $.ajax({
                type:"GET",
                url: "<?php echo base_url(); ?>registration_center/create_new_seminar",
                data : formInputValues,

                success: function(data)
                {
                    if(data == 'SUCCESS')
                    {
                        alert("The seminar has been registered successfully");
                        resetForm($('#seminar_registration_form'));
                    }
                    else if( data == 'BUSY')
                    {
                        alert("The Room is busy at that time. Please try choosing another date");
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });

            return false;

        });

        // Menu buttons handlers

        $("#seminar_register_menu_button").click(function()
        {
            //alert("Button clicked");
            hideTheRest("seminar_registration_form");
        });

        $("#attendee_register_menu_button").click(function()
        {
            hideTheRest("attendee_registration_form");
        });

        $("#seminar_overview_login_menu_button").click(function()
        {
            if(window.organiserLoggedIn)
            {
                hideTheRest('seminar_overview_display');
            }
            else
            {
                hideTheRest("seminar_overview_login_form");
            }
        });

        $("#centre_manager_menu_button").click(function()
        {
            if(window.managerLoggedIn)
            {
                hideTheRest('manager_overview_display');
            }
            else {

                hideTheRest("centre_management_login_form");
            }
        });

    });


    // Google Maps API

    function initialize() {
        var mapOptions = {
            zoom: 11,
            center: new google.maps.LatLng(52.253815, -0.889153)
        };

        var map = new google.maps.Map(document.getElementById('map-canvas'),
            mapOptions);
    }

    function loadScript() {
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp' +
        '&signed_in=true&callback=initialize';
        document.body.appendChild(script);
    }

    window.onload = loadScript;

    var mapOptions = {
        center: new google.maps.LatLng(52.25122, 150.644),
        zoom: 8
    };

    var map = new google.maps.Map(document.getElementById("map-canvas"),
        mapOptions);

    google.maps.event.addDomListener(window, 'load', initialize);

</script>

<?php
$this->load->helper('form');
$this->load->library('table');
?>

<div id="contentDiv">
    <div id="seminar_registration_form" class="forms">    <!-- This is the seminar registration form -->

        <form action="" method="post">
            <table class="regForm">
                <caption><b>Seminar Registration Form<b></caption> <!-- The form which will be sent to the add.php script for registering the seminar  -->
                <tr><td>Title:</td><td><input type="text" name="title" value=""></td></tr>

                <tr><td>Starting Time and Date (YYYY-MM-DD HH-MM-SS):</td><td> <input type="text" name="startTime" value=""></td></tr>

                <tr><td>Ending Time and Date (YYYY-MM-DD HH-MM-SS) :</td><td> <input type="text" name="endTime" value=""></td></tr>

                <tr><td>Description:</td><td><input type="text" name="description" value=""></td></tr>

                <tr><td id="roomHeld">Room to be Held In:

                    </td><td><select id="room" name="room" > <!-- On changing the option value, the function triggers and displays the room name and location -->

                            <!--  <option selected="selected">Choose a room</option>-->
                        </select></td></tr>

                <tr><td>Places Available in Seminar:</td><td> <input type="number" min="1" max="" name="capacity" id="capacity" value=""></td></tr>
            </table>

            <table class="regForm">
                <caption><b>Seminar Speakers Registration Form<b></caption> <!-- The form which will be sent to the add.php script for registering the speaker  -->
                <tr><td>First Name:</td><td><input type="text" name="firstName" value=""></td></tr>

                <tr><td>Last Name:</td><td><input type="text" name="lastName" value=""></td></tr>

                <tr><td>E-mail:</td><td><input type="text" name="email" value=""></td></tr>

                <tr><td>Address:</td><td><input type="text" name="address" value=""></td></tr>

                <tr><td>Institution:</td><td><input type="text" name="institution" value=""></td></tr>





                <tr><td><input type="submit" value="Add Seminar"></td></tr>

            </table>



        </form>
    </div>  <!--  Here ends the seminar registration form -->

    <div id="attendee_registration_form" class="forms">  <!--  This is the attendee registration form -->
        <?php
        $attributes = array('id' => 'attendee_registration_form');
        echo form_open('',$attributes);
        ?> <!--  The form which will send all the attendee details for further processing -->
        <table class="regForm">

            <caption><b>Attendance Registration Form<b></caption>
            <tr><td>First Name :</td><td> <?php echo form_input('attendee_f_name', ''); ?></td></tr>

            <tr><td>Last Name :</td><td> <?php echo form_input('attendee_l_name', '')  ?></td></tr>

            <tr><td>E-mail :</td><td> <?php echo form_input('attendee_email', '') ?></td></tr>

            <tr><td>Institution :</td><td><?php echo form_input('attendee_institution', '') ?></td></tr>

            <tr><td>Seminar Id :</td><td>
                    <select id="seminars" name="seminars">
                        <option selected="selected">Choose a seminar</option>
                    </select></td></tr>
            <tr><td><?php echo form_submit('submit', 'Register') ?></td></tr>
        </table>
        <?php  echo form_close();?>

    </div> <!-- Here ends the attendee registration form -->
    <div id="attendee_withdrawal_form" class="forms"> <!-- Here starts the attendee withdrawal form -->

        <form action="" method="post">
            <table class="regForm">

                <caption><b>Attendance Withdrawal Form<b></caption>
                <tr>
                    <td>First Name :</td>
                    <td><input type="text" name="firstName" value="" readonly></td>
                </tr>

                <tr>
                    <td>Last Name :</td>
                    <td><input type="text" name="lastName" value="" readonly></td>
                </tr>

                <tr>
                    <td>E-mail :</td>
                    <td><input type="text" name="email" value="" readonly></td>
                </tr>

                <tr>
                    <td>Institution :</td>
                    <td><input type="text" name="institution" value="" readonly></td>
                </tr>

                <tr>
                    <td>Seminar :</td>
                    <td><input type="text" name="seminarId" value="" readonly></td>
                </tr>

                <tr>
                    <td><input type="submit" value="Unregister" name="submit"></td>
                </tr>

            </table>


        </form>

    </div><!-- Here ends the attendee withdrawal form -->

    <div id="seminar_overview_login_form" class="forms">
        <form id="login_form" action="" method="post">
            <table class="regForm">

                <caption><b>Seminar Organiser Login: <b></caption>
                <tr>
                    <td>Login Name :</td>
                    <td><input type="text" name="username" value=""></td>
                </tr>

                <tr>
                    <td>Password :</td>
                    <td><input type="password" name="password" value=""></td>
                </tr>

                <tr>
                    <td><input type="submit" value="Login" name="submit"></td>
                </tr>
            </table>
        </form>
    </div>

    <div id="seminar_overview_display" class="forms">
        <div id="wrapper">
        </div>
        <input type="button" value="Logout" id="organiser_logout_button">
    </div>

    <div id="centre_management_login_form" class="forms">
        <form id="manager_login_form" method="post">
            <table class="regForm">
                <caption><b>Centre Management Login : <b></caption>
                <tr>
                    <td>Login Name :</td>
                    <td><input type="text" name="username" value=""></td>
                </tr>
                <tr>
                    <td>Password :</td>
                    <td><input type="password" name="password" value=""></td>
                </tr>
                <tr>
                    <td><input type="submit" value="Login" name="submit"></td>
                </tr>
            </table>
        </form>
    </div>

    <div id="manager_overview_display" class="forms">
        <div id="manager_wrapper">
        </div>
        <input type="button" value="Logout" id="manager_logout_button">
    </div>


    <div id="attendee_ticket_table" class="forms">
        <br><br><h2> The following ticket will be your proof of registration: </h2><br><br><br>

        <table id="tableTicket">
            <tr>
                <td id="seminarNo" rowspan="5" class="ticketText" ></td>
                <td id="names" class="ticketText"> </td>
                <td id="ticketId" rowspan="5" class="ticketText"></td>
            </tr>

            <tr>
                <td id="title" class="ticketText"> </td>
            </tr>

            <tr>
                <td id="description" class="ticketText"></td>
            </tr>

            <tr>
                <td id="locationAndTime" class="ticketText"> room Location  <br> </td>
            </tr>

            <tr>
                <td id="unsubscribeLink" class="ticketText"></td>
            </tr>
        </table>

        </br>
        <h2>If you'd like to unregister from the Seminar, please click the following link:</h2><br>
        <input type='button' value='Unregister' id="unregisterButton"/>


    </div>
</div>
</div><!--Here ends the content-->

<div id="mapsDiv">

    <div id="map-canvas" style="width: 100%; height: 100%"></div>

</div>





















