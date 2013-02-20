function registerFormValidation(){
    jQuery.validator.addMethod("noSpace", function(value, element) { 
        return value.indexOf(" ") < 0 && value != ""; 
    }, "Space are not allowed");
    
    jQuery.validator.addMethod("minSize", function(value, element) { 
        return value.length >= 6; 
    }, "needs at least 6 characters");
    
    $('#registerForm').validate(
        {
            rules:
            {
                username: { required: true, minSize: true, noSpace: true  },
                email:    { required: true, email: true },
                password: { required: true, minSize: true },
                confirmation: { required: true, minSize: true, equalTo: "#password" }
            },
            messages:
            {
                username:
                {
                    required: "Provide an username",
                    minSize: "Username needs to be at least 6 characters long",
                    noSpace: "Spaces are not allowed on username"
                },
                email:
                {
                    required: "Provide an email",
                    email: "Invalid email"
                },
                password:
                {
                    required: "Provide a password",
                    minSize: "Confirmation needs to be at least 6 characters long"
                },
                confirmation:
                {
                    required: "Confirm your password",
                    minSize: "Confirmation needs to be at least 6 characters long",
                    equalTo: "The password doesn't match with the confirmation"
                    
                }
            },
            highlight: function(label) {
                $(label).closest('.control-group').addClass('error');
            },
            submitHandler: function(form){
                form.submit();
            }
        }
    );
}
