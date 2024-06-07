<script type="text/javascript">
    $(document).ready(function () {

        $('.add-button').on('click', addRow)

        $(document).on('click', '.save-new-button', function () {
            let newRow = $(this).closest('tr')
            saveNewRowData(newRow)
        })

        $(document).on('click', '.delete-button', function () {
            let row = $(this).closest('tr')
            let id = $(this).data('id')
            deleteRow(row, id)
        })

        $(document).on('click', '.edit-button', function () {
            let row = $(this).closest('tr')
            let id = $(this).data('id')
            toggleEdit(row, id)
        })


        function addRow() {
            let duplicateRow = $('.add-row')
            let newRow = duplicateRow.clone().removeClass('d-none').removeClass('add-row').show()
            newRow.insertBefore(duplicateRow)
        }

        function saveNewRowData(newRow) {
            let formData = {}

            newRow.find("input").each(function () {
                let columnName = $(this).attr('name')
                formData[columnName] = $(this).val()
            });

            axios.post('{{ route('port-charges.store') }}', formData)
                .then(function (response) {
                    let createdId = response.data.id
                    newRow.find('td').each(function () {
                        let value = $(this).find('input').val()
                        $(this).text(value)
                    })

                    newRow.find('.save-new-button').replaceWith(`<button class="btn btn-info edit-button" data-id="${createdId}">Edit</button>`);
                })
                .catch(function (error) {
                    console.error(error)
                })
        }

        function deleteRow(row, id) {
            swal({
                title: `Are you sure you want to delete?`,
                text: "This action cannot be undone.",
                icon: "warning",
                buttons: true,
                dangerMode: true
            }).then((willDelete) => {
                if (willDelete) {
                    axios.post('{{ route('port-charges.delete-row') }}', {id: id})
                        .then(function () {
                            row.remove()
                        })
                        .catch(function (error) {
                            console.error(error)
                        })
                }
            })
        }

        function toggleEdit(row, id) {
            let isEditing = row.hasClass('editing')

            if (isEditing) {
                let formData = {}
                row.find("input:not([hidden])").each(function (index) {
                    formData[index] = $(this).val()
                })
                formData['id'] = id
                axios.post('{{ route('port-charges.edit-row') }}', formData)
                    .catch(function (error) {
                        console.error(error)
                    })

                row.find('td.editable').each(function () {
                    var value = $(this).find('input').val()
                    $(this).text(value)
                })
                row.find('.edit-button').replaceWith(`<button class="btn btn-info edit-button" data-id="${id}">Edit</button>`)
            } else {
                row.find('td.editable').each(function () {
                    let value = $(this).text()
                    $(this).html(`<input class="form-control" type="text" value="` + value + '">')
                })
                row.find('.edit-button').replaceWith(`<button class="btn btn-info edit-button" data-id="${id}">Save</button>`)
            }

            row.toggleClass('editing')
        }

    })

</script>