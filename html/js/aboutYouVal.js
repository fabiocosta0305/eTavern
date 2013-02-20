function aboutYouFormValidation(){
    jQuery.validator.addMethod("minSize", function(value, element) { 
        return value.length >= 10; 
    }, "needs at least 6 characters");
   
    $('#aboutMe').validate(
        {
            rules:
            {
                realname: {required: true, minSize:true},
            },
            messages:
            {
                realname:
                {
                    required: "Real Name required",
                    minSize: "Real name needs to be at least 10 letters long"
                },

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
