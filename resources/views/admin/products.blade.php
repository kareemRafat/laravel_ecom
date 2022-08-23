@extends('admin.layouts.app')
@push('custom-styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Products</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Products</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Main row -->
            <div class="row mr-1">
                <!-- Left col -->
                <section class="col-lg-12 connectedSortable mb-3">
                    <button type="button" class="btn btn-primary mb-4" data-toggle="modal" data-target="#add-product-modal">
                        Add product
                      </button>
                    <!-- Custom tabs (Charts with tabs)-->
                        {!! $dataTable -> table(['class' => 'table table-bordered table-striped']) !!}
                    <!-- /.card -->
                </section>
                <!-- /.Left col -->
                <!-- right col (We are only adding the ID to make the widgets sortable)-->

                <!-- right col -->
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

@include('admin.modals.product-modal')


@endsection
@push('custom-scripts')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    {!! $dataTable -> scripts() !!}
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        console.log('test');
        $('.add-product-form').submit(function(e){
            e.preventDefault();
            let formData = new FormData(this);


            $.ajax({
                method : 'post' ,
                url : "{{ route('admin.products.store') }}",
                dataType : 'json',
                data : formData,
                processData : false ,
                contentType : false ,
                beforeSend(){
                    $('.mySpinner').html(`
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>`)
                },
                success(data){
                    $('small').text('');
                    $('.mySpinner').html('');

                    $('#add-product-modal').modal('hide');

                    //to reset data in datatables
                    //ajax.reload(callback = null , resetPaging = true)
                    $('table').DataTable().ajax.reload(null , false);
                },
                error(error,exception){
                    $('small').text('');
                    let keys = Object.keys(error.responseJSON.errors);
                    let values = Object.values(error.responseJSON.errors);

                    // to print the errors in the small element for each element
                    keys.forEach((item , index)=> {
                        let errors = values[index].join(',');
                        $(`.input-${item}`).text(errors);
                    })

                }
            })

        })
    </script>

@endpush
