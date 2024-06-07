<div>
    <h3>Contact Persons</h3>
    <table id="contact-person-table" class="table table-bordered">
        <thead>
        <tr>
            <th>Role</th>
            <th>Title</th>
            <th>Phone</th>
            <th>Email</th>
            <th>
                <a id="add-contact-person"> Add <i class="fas fa-plus"></i></a>
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($contactPeople ?? [] as $item)
            <tr>
                <td>
                    <input class="form-control" name="contactPeople[role][]"
                           value="{{ $item->role }}">
                </td>
                <td>
                    <input class="form-control" name="contactPeople[title][]"
                           value="{{ $item->title }}">
                </td>
                <td>
                    <input class="form-control" name="contactPeople[phone][]"
                           value="{{ $item->phone }}">
                </td>
                <td>
                    <input class="form-control" type="email" name="contactPeople[email][]"
                           value="{{ $item->email }}">
                </td>
                <td style="width:85px;">
                    <button type="button" class="btn btn-danger removeContact"><i
                                class="fa fa-trash"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@push('scripts')
    @include('master.customers._contact_people_js')
@endpush