$(function(){
    var selectAllBtn = $('#selectAll');
    selectAllBtn.on('click',function(){
        var self = $(this);
        if(self.data('selected') == '0'){
            $("input[type=checkbox]").prop('checked',true);
            self.data('selected','1');
        }else{
            $("input[type=checkbox]").prop('checked',false);
            self.data('selected','0');
        }
    });
    var form = $('#createForm');

    form.on('submit',function(e){
        var roleName = $('#roleName');
        if(!roleName.val().trim()){
            swal.fire({
                title:"Role name is required",
                text:'',
                type: 'error',
            });
            return false;
        }
        var items = $("input[type=checkbox]:checked");
        if(items.length <= 0){
            swal.fire({
                title:"No Permission Seleted",
                text:'Role must have permission',
                type: 'error',
            });
            return false;
        }

        return true;
    });

    var checkbox = $("input[type=checkbox]");
    checkbox.on('click',function(){
        var item = $(this);
        if(!item.data('name').includes('List') && item.prop('checked')){
            $('input[data-name*=List]',item.closest('td')).prop('checked',true);
            return;
        }
        if(item.data('name').includes('List') && !item.prop('checked')){
            $('input[type=checkbox]',item.closest('td')).prop('checked',false);
            return;
        }
    });
});
