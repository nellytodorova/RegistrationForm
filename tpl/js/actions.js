function validate(formData, jqForm, options) {
    var form = jqForm[0];
    
    if (!form.username.value || form.username.value.length < 2) {
        form.username.focus();
        alert('Please enter correct username!');
        return false;
    }
    
    if (!form.password.value || form.password.value.length < 2) {
        form.password.focus();
        alert('Please enter correct password!');
        return false;
    }

    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!form.email.value || !filter.test(form.email.value)) {
        form.email.focus();
        alert('Please enter correct email!');
        return false;
    }
}

$(document).ready(function() {
    var options = {
        beforeSend: function(){
            $("#result").addClass('loading');
        },
        beforeSubmit: validate, 
        type: 'post',
        data: { ajaxSubmit: '1'},
        dataType: 'json',
        success: function(data){
                $("#result").removeClass('loading');
                $('#result').html(data.message);   
                $("#result").addClass(data.class);
                
          },
        clearForm: true,
        resetForm: true
    };
    
    $('#regForm').submit(function() {
        $('#result').html('');
    
        $(this).ajaxSubmit(options);
        return false;
    });
});