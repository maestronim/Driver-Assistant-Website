<?php
	include_once '../requests/http_requests.php';
	session_start();

	if(isset($_REQUEST['register-submit'])) {
    	if($_REQUEST['confirm-password'] == $_REQUEST['password']) {
    	
            //API Url
            $url = '../api/user-info/create.php';

            //The JSON data.
            $jsonData = array(
                "username" => $_REQUEST['username'],
                "email" => $_REQUEST['email'],
                "password" => $_REQUEST['password']
            );
            
            $result = postRequest($url, $jsonData);
            
            $decoded_result = json_decode($result);

            if($decoded_result->success == "no") {
                $error = "Registration failed";
            } else {
				$_SESSION['username'] = $_REQUEST['username'];
        		header('Location: ../index.php');
                exit();
        	}
    	} else {
        	$error = "Passwords don't match";
        }
    } else if(isset($_REQUEST['login-submit'])) {
    	//API Url
        $url = '../api/user-info/check_credentials.php';

        //The JSON data.
        $jsonData = array(
          "username" => $_REQUEST['username'],
          "password" => $_REQUEST['password']
        );

        $result = postRequest($url, $jsonData);

        $decoded_result = json_decode($result);

        if($decoded_result->valid == "no") {
          	$error = "Login failed";
        } else {
        	if(isset($_REQUEST['remember'])) {
				$_SESSION['username'] = $_REQUEST['username'];
                header('Location: ../index.php');
                exit();
            } else {
          		postRedirect('../index.php?username=' . $_REQUEST['username']);
            	exit();
          	}
        }
    }
?>
<!DOCTYPE html>
<html>
	<head>
    	<title>authentication</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        
        <script src="script.js"></script>
        <link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
    <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="panel panel-login">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-6">
                                    <a href="#" class="active" id="login-form-link">Login</a>
                                </div>
                                <div class="col-xs-6">
                                    <a href="#" id="register-form-link">Register</a>
                                </div>
                            </div>
                            <hr>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form id="login-form" action="#" method="post" role="form" style="display: block;">
                                        <div class="form-group">
                                            <input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Username" value="">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password">
                                        </div>
                                        <div class="form-group text-center">
                                            <input type="checkbox" tabindex="3" class="" name="remember" id="remember">
                                            <label for="remember"> Remember Me</label>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6 col-sm-offset-3">
                                                    <input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-login" value="Log In">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <form id="register-form" action="#" method="post" role="form" style="display: none;">
                                        <div class="form-group">
                                            <input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Username" value="">
                                        </div>
                                        <div class="form-group">
                                            <input type="email" name="email" id="email" tabindex="1" class="form-control" placeholder="Email Address" value="">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="confirm-password" id="confirm-password" tabindex="2" class="form-control" placeholder="Confirm Password">
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6 col-sm-offset-3">
                                                    <input type="submit" name="register-submit" id="register-submit" tabindex="4" class="form-control btn btn-register" value="Register Now">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php
                            	if(isset($error)){
                                	echo '<div class="row">';
                                    echo '<div class="col-lg-12">';
                                    echo '<div class="alert alert-warning">
  									<strong>' . $error . '</strong>
									</div>';
                                    echo '</div></div>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</body>
</html>