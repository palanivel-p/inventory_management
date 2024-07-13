<?php
Include("../includes/connection.php");
if(isset($_GET['logout'])==1) {


    setcookie("panel_api_key", "", time() + (3600 * 1), "/"); // To empty the cookie
    setcookie("user_id", "", time() + (3600 * 1), "/"); // To empty the cookie
    setcookie("role", '', time() + (3600 * 1), "/"); // To set Login for 1 hr
    setcookie("user_name", '', time() + (3600 * 1), "/"); // To set Login for 1 hr


}

?>

<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login form</title>

    <link rel="icon" type="image/png" sizes="16x16" href="https://bhims.ca/piloting/img/favicon_New.png">
    <link href="../css/style.css" rel="stylesheet">



</head>
<body class="h-100">
<div class="   h-100">
    <div class="container h-100">
        <div class="row justify-content-center h-100 align-items-center">
            <div class="col-md-6">
                <div class="authincation-content">
                    <div class="row no-gutters">
                        <div class="col-xl-12">
                            <div class="auth-form">
                                <div class="text-center mb-3">
                                    <a href="javascript:void(0)"><img src="https://bhims.ca/piloting/img/logo_footer.png" alt=""></a>
                                </div>
                                <h4 class="text-center mb-4 text-white">Sign In Your Account</h4>
                                <form>
                                    <div class="form-group">
                                        <label class="mb-1 text-white"><strong>Email</strong></label>
                                        <input type="email" class="form-control" id="mail" style="color: black;" placeholder="Enter Email">
                                    </div>
<!--                                    <div class="form-group">-->
<!--                                        <label class="mb-1 text-white"><strong>Password</strong></label>-->
<!--                                        <input type="password" class="form-control" id="pwd" style="color: black;">-->
                                        <div class="form-group">
                                            <label style="color: white;">Password</label>
                                            <div class="input-group mb-3 input-primary">
                                                <input type="password" id="pwd" name="password" class="form-control" placeholder="Enter Password" style="color: black;">
                                                <div class="input-group-append">
                                                        <span class="input-group-text" onclick="pwdToggle()"  style="cursor: pointer;color:black;background-color: white;">
                                                            <i id="togglePassword" class="fa fa-eye-slash" aria-hidden="true"></i>

                                                        </span>
                                                </div>
                                            </div>
                                        </div>


                                    <div class="text-center" style="margin-top: 63px;">
                                        <button class="btn bg-white text-primary btn-block" id="btn" onclick="login()" type="button">Sign In</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="../vendor/global/global.min.js"></script>
<script src="../vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
<script src="../js/custom.min.js"></script>
<script src="../js/dlabnav-init.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>

    function login() {

        var email = document.getElementById("mail").value;
        var password = document.getElementById("pwd").value;

        if(email != "") {

            if (password != "") {

                if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) {

                    document.getElementById('btn').style.pointerEvents="none";
                    document.getElementById('btn').style.cursor="default";
                    $("#btn").html("<i class=\"fa fa-spinner fa-spin\"></i> Loading...");

                    $.ajax({

                        type: "POST",
                        url: "login.php",
                        data: $.param({email: email,password: password}),
                        dataType: "json",

                        success: function(res){
                            if(res.result=='success')
                            {
                                // window.location.href = '../dashboard/';
                                window.location.href = '../';
                                document.getElementById('btn').style.pointerEvents="auto";
                                document.getElementById('btn').style.cursor="pointer";
                                $("#btn").html("Sign In");
                            }

                            else if(res.result=='failure')
                            {

                                Swal.fire(
                                    {
                                        title: "Password Was Wrong",
                                        text: "Check Your Password",
                                        icon: "warning",
                                        button: "OK",
                                        allowOutsideClick: false,
                                        allowEscapeKey: false,
                                        closeOnClickOutside: false,
                                        heightAuto: false,

                                    }
                                )
                                    .then((value) => {

                                        // window.location.reload();
                                        // $(this).unbind('click');
                                        // document.getElementById("btn").disabled = false;
                                        $('#btn').css('pointer-events','');
                                        $('#btn').css('cursor','pointer');

                                        $("#btn").html("Sign In");
                                    });

                            }
                        },

                        error: function() {
                            swal('Check your network connection')

                            document.getElementById("btn").disabled = true;
                            $("#btn").html("<span class=\"text\">Sign In</span>");
                        }

                    });
                }

            }

            else {
                //password
                swal(
                    'password required',
                    'password cannot be empty',
                    'warning')
            }

        }
        else {
            //email
            swal(
                'email required',
                'email cannot be empty',
                'warning')
        }


    }



    //password toggle
    function pwdToggle() {

        var x = document.getElementById("pwd");

        if (x.type === "password") {

            x.type = "text";
            $("#togglePassword").removeClass("fa-eye-slash");
            $("#togglePassword").addClass("fa-eye");




        } else {
            x.type = "password";
            $("#togglePassword").removeClass("fa-eye");
            $("#togglePassword").addClass("fa-eye-slash");

        }
    }


    //enter key
    $("#pwd").keyup(function(event) {
        if (event.keyCode === 13) {
            login();
        }
    });



</script>


</body>
</html>

