@extends('layouts.main-layout', ['page_title' => 'Salaries'])
@section('styles')
<link href="{{asset('plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/bootstrap-select/css/bootstrap-select.min.css')}}" rel="stylesheet"/>
<link href="{{asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet"/>
<style>
    .btn-outline{
        background-color:transparent;
        border: 1px solid #666666;
        border-radius: 4px;
    }
</style>
@endsection
@section('content')
<div class="row">
    <div class="col-lg-3 col-sm-12">
        <h6 class="header-title mt-0 m-b-20">Select client and month</h6>
        <div class="panel-body">

            <form action="#" id="view_salaries">
                @csrf
                <div class="col-lg-12">
                    <div class="p-20 mt-4 dtpkr">
                        <div class="text-center">
                            <div id="datepicker-inline" class=""></div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 text-center">                               
                    <button type="button" class="btn btn-custom" onclick="view(this)">View Salaries</button>
                </div>
            </form>

        </div>
    </div>
    <div class="col-lg-8 col-sm-12">
        <div class="card-box">
            <table class="table table-striped table-responsive">
                <thead>
                    <th>Guard Name</th>
                    <th>Bank Name</th>
                    <th>Bank Branch</th>
                    <th>Account Number</th>
                    <th>Salary</th>
                    <th>Deductions</th>
                    <th>Amount Payable</th>
                    <th>Status</th>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('modals')
<div id="emptySetModal" class="modal fade">
    <div class="modal-dialog modal-confirm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">No records found</h4>	
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <p>No salary records have been generated for the selected month. Do you want to generate the records now?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-custom" onclick="generate(this)">Generate</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.bootstrap4.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>

<script src="{{asset('plugins/bootstrap-select/js/bootstrap-select.js')}}" type="text/javascript"></script>
<script>
let selected_month = null;
let temp_date = null;

const table = $('.table').DataTable({
    "language": {
        "emptyTable":     "Salaries not generated for specified month"
    },
    "bLengthChange": false,
    dom: 'Blfrtip',
    buttons: [ 
        {
            extend: 'excelHtml5',
            title: name
        },
        {
            extend: 'pdfHtml5',
            title: name
        }, 'copy', 'print' ]
});

$('#datepicker-inline').datepicker({
        autoclose: true,
        format: 'mm/yyyy',
        viewMode: 1, 
        minViewMode: "months",
        maxDate: new Date,
}).on('changeMonth', function(e){
    selected_month = dateToYMD(e.date);
});

const view = (e) => {
    let btn = $(e);
    
    btn.closest('form').find('.text-danger').css('display', 'none');

    const initial = btn.html();
    let error = false;

    if(selected_month == null){
        $('.dtpkr').closest('div').append('<p class="text-small text-danger">Select a date to continue</p>');
        error = true;
        return false;
    }

    if((new Date(selected_month).getMonth() > new Date().getMonth() && new Date(selected_month).getYear() >= new Date().getYear()) || new Date(selected_month).getYear() > new Date().getYear()){
        $('.dtpkr').closest('div').append('<p class="text-small text-danger">Selected date must not be in the future</p>');
        error = true;
        return false;
    }

    if(!error){
        applyLoading(btn);
        const data = {
            date: selected_month
        }

        $.ajax({
            url : '/api/salaries/get',
            data : data,
            method : 'GET',
            success : ({salaries, date}) => {
                removeLoading(btn, initial);
                
                if(salaries.length < 1){
                    $('#emptySetModal').modal('show');
                    temp_date = date;
                }else{
                    let kount = salaries.length;
                    table.clear().draw();
                    
                    let salaries_array = [];
                    
                    for(let salary of salaries){
                        let temp = [salary.guard_salary.firstname,salary.bank_name,salary.bank_branch,salary.account_number,salary.amount,salary.total_deductions,salary.amount - salary.total_deductions,salary.status == 0 ? '<span class="badge badge-primary">Pending</span>' : '<span class="badge badge-success">Paid</span>'];
                        salaries_array.push(temp);
                    }

                    table.rows.add(salaries_array).draw();
                }
            },
            error : (err) => {
                removeLoading(btn, initial);
                $.toast({
                    text : 'Network error',
                    heading : 'Error',
                    position: 'top-right',
                    showHideTransition : 'slide', 
                    bgColor: '#d9534f'
                });
            }
        });
    }
}

const generate = (e) => {
    let btn = $(e);

    const initial = btn.html();

    applyLoading(btn);

    $.ajax({
        url : '/api/salaries/generate',
        method : 'POST',
        data : 'date='+temp_date,
        success : (data) => {
            removeLoading(btn, initial);
            if(data.error){
                $.toast({
                    text : data.message,
                    heading : 'Error',
                    position: 'top-right',
                    showHideTransition : 'slide', 
                    bgColor: '#d9534f'
                });
            }else{
                $('#emptySetModal').modal('hide');
                $.toast({
                    text : data.message,
                    heading : 'Done',
                    position: 'top-right',
                    bgColor : '#5cb85c',
                    showHideTransition : 'slide'
                });
            }
        },
        error : (error) => {
            removeLoading(btn, initial);
            $.toast({
                text : 'Network error',
                heading : 'Error',
                position: 'top-right',
                showHideTransition : 'slide', 
                bgColor: '#d9534f'
            });
        }
    });
}

const dateToYMD = (date) => {
    const d = date.getDate();
    const m = date.getMonth() + 1;
    const y = date.getFullYear();
    return y + '-' + (m <= 9 ? '0' + m : m) + '-' + (d <= 9 ? '0' + d : d);
}

</script>
@endsection