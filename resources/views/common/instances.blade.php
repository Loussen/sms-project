@extends('layouts.app')

@section('content')

    <h1 class="h3 mb-2 text-gray-800">Tables {{ currentModuleName() }}</h1>
    <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below. For more
        information about DataTables, please visit the <a target="_blank" href="https://datatables.net">official
            DataTables documentation</a>.</p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Instances List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <form id="search" style="overflow: hidden;">
                    <table class="table table-bordered" id="dataTableForInstance" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th><input type="text" name="id" id="id" class="form-control"/></th>
                            <th><input type="text" name="instance_id" id="instance_id" class="form-control"/></th>
                            <th><input type="text" name="token" id="token" class="form-control"/></th>
                            <th><input type="text" name="last_login" id="last_login" class="form-control last_login"/></th>
                            <th>
                                <select class="form-control" name="statuses" id="statuses">
                                    <option value="0" selected>Select status</option>
                                    @foreach (config('global.status') as $keyStatus => $valStatus)
                                        <option value="{{ $keyStatus }}">{{ $valStatus }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <button type="reset" id="reset" class="btn btn-info">Reset</button>
                            </th>

                        </tr>

                        <tr>
                            <th width="5%">ID</th>
                            <th width="15%">Instance ID</th>
                            <th>Token</th>
                            <th>Last Login</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Instance ID</th>
                            <th>Token</th>
                            <th>Last Login</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                        <tbody>

                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ajaxModel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div>
                <div class="modal-body">
                    <form id="productForm" name="productForm" class="form-horizontal">
                        <input type="hidden" name="product_id" id="product_id">
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name"
                                       value="" maxlength="50" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Details</label>
                            <div class="col-sm-12">
                                <textarea id="detail" name="detail" required="" placeholder="Enter Details"
                                          class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('stylesheets')
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@push('scripts')

    <!-- Page level plugins -->
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script type="text/javascript">
        $(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let table = $('#dataTableForInstance').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{{ route('company-instances', ['company_id' => $companyId]) }}',
                    data: function (d) {
                        d.id = $('#id').val()
                        d.instance_id = $('#instance_id').val()
                        d.token = $('#token').val()
                        d.last_login = $('#last_login').val()
                        d.status = $('#statuses').val()
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'instance_id', name: 'instance_id'},
                    {data: 'token', name: 'token'},
                    {data: 'last_login', name: 'last_login'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                deferRender: true,
            });

            $('#id, #instance_id, #token').keyup(function () {
                table.draw();
            });

            $('input.last_login').change(function () {
                table.draw();
            });

            $('select#statuses').change(function () {
                table.draw();
            });

            $('form#search button#reset').click(function () {
                $('form#search input').val('');
                $('form#search select').val(0);
                table.draw();
            });

            $('input.last_login').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $('input.last_login').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' / ' + picker.endDate.format('YYYY-MM-DD'));
            });

            $('input.last_login').on('apply.daterangepicker', function(ev, picker) {
                table.draw();
            });

            $('#createNewProduct').click(function () {
                $('#saveBtn').val("create-product");
                $('#product_id').val('');
                $('#productForm').trigger("reset");
                $('#modelHeading').html("Create New Product");
                $('#ajaxModel').modal('show');
            });

            $('body').on('click', 'a.login-instances', function (){
                $('#ajaxModel').modal('show');

            });

        {{--$('body').on('click', '.editProduct', function () {--}}
            {{--    var product_id = $(this).data('id');--}}
            {{--    $.get("{{ route('ajaxproducts.index') }}" +'/' + product_id +'/edit', function (data) {--}}
            {{--        $('#modelHeading').html("Edit Product");--}}
            {{--        $('#saveBtn').val("edit-user");--}}
            {{--        $('#ajaxModel').modal('show');--}}
            {{--        $('#product_id').val(data.id);--}}
            {{--        $('#name').val(data.name);--}}
            {{--        $('#detail').val(data.detail);--}}
            {{--    })--}}
            {{--});--}}

            {{--$('#saveBtn').click(function (e) {--}}
            {{--    e.preventDefault();--}}
            {{--    $(this).html('Sending..');--}}

            {{--    $.ajax({--}}
            {{--        data: $('#productForm').serialize(),--}}
            {{--        url: "{{ route('ajaxproducts.store') }}",--}}
            {{--        type: "POST",--}}
            {{--        dataType: 'json',--}}
            {{--        success: function (data) {--}}
            {{--            $('#productForm').trigger("reset");--}}
            {{--            $('#ajaxModel').modal('hide');--}}
            {{--            table.draw();--}}
            {{--        },--}}
            {{--        error: function (data) {--}}
            {{--            console.log('Error:', data);--}}
            {{--            $('#saveBtn').html('Save Changes');--}}
            {{--        }--}}
            {{--    });--}}
            {{--});--}}

            {{--$('body').on('click', '.deleteProduct', function (){--}}
            {{--    var product_id = $(this).data("id");--}}
            {{--    var result = confirm("Are You sure want to delete !");--}}
            {{--    if(result){--}}
            {{--        $.ajax({--}}
            {{--            type: "DELETE",--}}
            {{--            url: "{{ route('ajaxproducts.store') }}"+'/'+product_id,--}}
            {{--            success: function (data) {--}}
            {{--                table.draw();--}}
            {{--            },--}}
            {{--            error: function (data) {--}}
            {{--                console.log('Error:', data);--}}
            {{--            }--}}
            {{--        });--}}
            {{--    }else{--}}
            {{--        return false;--}}
            {{--    }--}}
            {{--});--}}
        });
    </script>
@endpush
