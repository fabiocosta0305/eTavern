function charFormValidation(){
    jQuery.validator.addMethod("noSpace", function(value, element) { 
        return value.indexOf(" ") < 0 && value != ""; 
    }, "Space are not allowed");
    
    jQuery.validator.addMethod("minSize", function(value, element) { 
        return value.length >= 6; 
    }, "needs at least 6 characters");
   
    $('#chars').validate(
        {
            rules:
            {
                char_name: {required: true, minSize:true},
                system:    {required: true},
                base_desc: {required: true, minSize: true}

            },
            messages:
            {
                char_name:
                {
                    required: "Character Name required",
                    minSize: "Character name need to be at least 6 letters long"
                },
                system:
                {
                    required: "System required"
                },
                base_desc:
                {
                    required: "Basic Description required",
                    minSize: "Description needs to be at least 6 letters long"
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
