<script>
    document.addEventListener("DOMContentLoaded", function () {
        addRow();
        document.getElementById("add-contact-person").addEventListener("click", function () {
            addRow()
        })
    })

    const refreshRemoveButton = () => {
        $(".removeContact").on('click', e => {
            e.target.closest("tr").remove()
        })
    }

    function addRow() {
        let table = document.getElementById("contact-person-table")
        let newRow = table.insertRow(-1);

        let roleCell = newRow.insertCell(0)
        let titleCell = newRow.insertCell(1)
        let phoneCell = newRow.insertCell(2)
        let emailCell = newRow.insertCell(3)
        let removeCell = newRow.insertCell(4)


        roleCell.innerHTML = '<input class="form-control" name="contactPeople[role][]">'
        titleCell.innerHTML = '<input class="form-control" name="contactPeople[title][]">'
        phoneCell.innerHTML = '<input class="form-control" name="contactPeople[phone][]">'
        emailCell.innerHTML = '<input class="form-control" type="email" name="contactPeople[email][]">'
        removeCell.innerHTML = '<button type="button" class="btn btn-danger removeContact"><i class="fa fa-trash"></i></button>'
        refreshRemoveButton()
    }
</script>