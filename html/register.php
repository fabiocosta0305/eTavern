<?php

    // configuration
    require("../includes/config.php"); 

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission
        if (empty($_POST["username"]))
        {
            apologize("You must provide your username.");
        }
        else if (empty($_POST["password"]))
        {
            apologize("You must provide your password.");
        }
        else if (empty($_POST["confirmation"]))
        {
            apologize("You must confirm your password.");
        }
        else if (empty($_POST["email"]))
        {
            apologize("You must provide an email.");
        }
        else if ($_POST["password"] !== $_POST["confirmation"])
        {
            apologize("The password and confirmation didn't match.");
        }

        // query database for user
        $rows = query("SELECT * FROM user WHERE username = ?", $_POST["username"]);

        // if we found user, check password
        if (count($rows) == 1)
            apologize("User already exists.");

        // query database for email
        $rows = query("SELECT * FROM user WHERE email = ?", $_POST["email"]);

        // if we found user, check password
        if (count($rows) == 1)
            apologize("Email already registered.");

        $realname=empty($_POST["realname"])?DEFAULT_REALNAME:$_POST["realname"];
        // register user
        $rows = query("INSERT INTO user (username,realname,email,password) values (?,?,?,?)",
                      $_POST["username"], $realname, $_POST["email"], crypt($_POST["password"]));

        if ($result === false)
            apologize("We had some problem on database. Please contact the administrator!");
        else
        {
            $rows = query("SELECT LAST_INSERT_ID() AS id");
            $id = $rows[0]["id"];
            $_SESSION["id"]=$id;
            $_SESSION["username"]=$_POST["username"];
            $_SESSION["realname"]=$realname;
            
            // redirect to portfolio
            redirect("/");
        }
    }
    else
    {
          $js_function=<<<FUNCTION
              $(document).ready(function(){
                      jQuery.validator.addMethod("noSpace", function(value, element) { 
                              return value.indexOf(" ") < 0 && value != ""; 
                          }, "Space are not allowed");
                        
                      $('#registerForm').validate(
                      {
                        rules:
                          {
                            username: { required: true, noSpace: true  },
                email:    { required: true, email: true },
                password: { required: true },
                confirmation: { required: true, equalTo: "#password" }
                          },
            messages:
            {
                username:
                {
                    required: "Provide an username",
                    minlenght: "Username size is at least 2",
                    noSpace: "Usernames can't have spaces"
                },
                email:
                {
                    required: "Provide an email",
                    email: "Invalid email"
                },
                password:
                {
                    required: "Provide a password",
                    minlenght: "Password size is at least 6"
                },
                confirmation:
                {
                    required: "Confirm your password",
                    minlenght: "Confirmation size is at least 6",
                    equalTo: "The password doesn't match with the confirmation"
                    
                }
            },
                highlight: function(label) {
                $(label).closest('.control-group').addClass('error');
            },
                submitHandler: function(form){
                form.submit();
            }});});
FUNCTION;

        // else render form
render("register_form.php", ["title" => "Register",
                             "extraJS" => ["js/registerFormVal.js","js/jquery.validate.js"],
                             "jquery"=>$js_function]);
    }

?>
