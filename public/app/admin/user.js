$(function(){
    var form = $('#createForm');
    var roles = $("#role");
    var acceptWarning = false;
    form.on('submit',function(e){

        if(!roles.val() && !acceptWarning){
            swal({
                title: 'User with no role will be disabled',
                text: "Create User with no Role?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Create User',
                padding: '2em'
              }).then(function(result) {
                if (result.value) {
                    acceptWarning = true;
                    form.submit();
                }
              });
              return false;
        }

        return true;
    });
});
