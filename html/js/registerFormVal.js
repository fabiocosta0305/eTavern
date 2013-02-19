function registerFormValidation(){
    $("#registerForm").validate(
        {
            rules:
            {
                username: { required: true, minlenght: 6, noSpace: true  },
                email:    { required: true, email: true },
                password: { required: true, minlenght: 6 },
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
            }
        }
    )
}
