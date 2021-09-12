@extends('vendor.crud-generator.layouts.app')

@section('content')

    @if (Session::has('success_message'))
        <div class="alert alert-success">
            <span class="glyphicon glyphicon-ok"></span>
            {!! session('success_message') !!}

            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>

        </div>
    @endif

    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="far fa-user"></i>
                    </div>
                    @if($role == 'admin')
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{ __('dashboard.total_patients') }}</h4>
                            </div>
                            <div class="card-body">
                                {{ $total_patients }}
                            </div>
                        </div>
                    @else
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{ __('dashboard.my_patients') }}</h4>
                            </div>
                            <div class="card-body">
                                {{ $total_patients }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    @if($role == 'admin')
                        <div class="card-icon bg-info">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{ __('dashboard.total_professionals') }}</h4>
                            </div>
                            <div class="card-body">
                                {{ $total_professionals }}
                            </div>
                        </div>
                    @else
                        <div class="card-icon bg-success">
                            <i class="far fa-check-circle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{ __('dashboard.finished_sessions') }}</h4>
                            </div>
                            <div class="card-body">
                                {{ $total_finished }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    @if($role == 'admin')
                        <div class="card-icon bg-success">
                            <i class="far fa-check-circle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{ __('dashboard.finished_sessions') }}</h4>
                            </div>
                            <div class="card-body">
                                {{ $total_finished }}
                            </div>
                        </div>
                    @else
                        <div class="card-icon bg-warning">
                            <i class="fas fa-comment-medical"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{ __('dashboard.upcoming_sessions') }}</h4>
                            </div>
                            <div class="card-body">
                                {{ $total_upcoming }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    @if($role == 'admin')
                        <div class="card-icon bg-warning">
                            <i class="fas fa-comment-medical"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{ __('dashboard.upcoming_sessions') }}</h4>
                            </div>
                            <div class="card-body">
                                {{ $total_pending }}
                            </div>
                        </div>
                    @else
                        <div class="card-icon bg-info">
                            @if ($unread_messages >= 1)
                                <i class="fas fa-envelope-open-text"></i>
                            @else
                                <i class="fas fa-envelope-open"></i>
                            @endif
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{ __('dashboard.unread_messages') }}</h4>
                            </div>
                            <div class="card-body">
                                {{ $unread_messages }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('dashboard.next_sessions') }}</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        @if($role == 'admin')
                                            <th>{{ __('dashboard.table.professional') }}</th>
                                        @endif
                                        <th>{{ __('my_sessions.table.patient') }}</th>
                                        <th>{{ __('my_sessions.table.consultation-type') }}</th>
                                        <th>{{ __('my_sessions.table.date') }}</th>
                                        <th>{{ __('my_sessions.table.hour') }}</th>
                                        <th>{{ __('my_sessions.table.status') }}</th>
                                        <th>{{ __('my_sessions.table.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($sessions as $session)
                                        @php
                                            $month_number = sprintf("%02d", $session->timeSlot->week);
                                            $day_number = sprintf("%02d",$session->timeSlot->day);
                                            $date = date('Y-m-d H:i:s',strtotime($session->timeSlot->year.'-'.$month_number.'-'.$day_number.' +'.$session->timeSlot->hour.' hours'));
                                        @endphp
                                        <tr>
                                            @if($role == 'admin')
                                                <td class="align-middle">
                                                    <img alt="image" src="
                                                    @if (isset($session->professional->profile->image) && !empty($session->professional->profile->image))
                                                        {{ asset($session->professional->profile->image) }}
                                                    @else 
                                                        {{ asset('cms/assets/img/avatar/avatar-1.png') }} 
                                                    @endif
                                                    " class="rounded-circle mr-1" width="35" data-toggle="tooltip">{{ $session->professional->name }} {{ $session->professional->lastname }}
                                                </td>
                                            @endif
                                            <td class="align-middle">
                                                <img alt="image" src="
                                                @if (isset($session->patient->profile->image) && !empty($session->patient->profile->image))
                                                    {{ asset($session->patient->profile->image) }}
                                                @else 
                                                    {{ asset('cms/assets/img/avatar/avatar-1.png') }} 
                                                @endif
                                                " class="rounded-circle mr-1" width="35" data-toggle="tooltip">{{ $session->patient->name }} {{ $session->patient->lastname }}
                                            </td>
                                            <td class="align-middle">
                                                {{ $session->consultationType->name }}
                                            </td>
                                            <td class="align-middle">
                                                {{ \Carbon\Carbon::parse($date)->isoFormat('D MMM YY') }}
                                            </td>
                                            <td class="align-middle">
                                                {{ \Carbon\Carbon::parse($date)->format('H') }} h
                                            </td>
                                            <td class="align-middle">
                                                <div class="badge badge-{{ $session->status === "Pendiente" ? "warning" : ($session->status === "Finalizada" ? "success" : "info") }}">
                                                    {{ $session->status }}
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                @if($role == 'professional')
                                                    @if ($session->status === "Pendiente")
                                                        <button onclick="sweetAlert({{$session->patient->id}},{{$session->id}})"  class="btn btn-icon btn-success" title="{{ __('my_sessions.table.actions.hover.chat') }}">
                                                            <i class="fas fa-comments"></i>
                                                        </button>
                                                    @endif
                                                @endif
                                                <a href="{{ route('patients.show', $session->patient->id) }}" class="btn btn-icon btn-info" title="{{ __('my_sessions.table.actions.hover.consultation') }}">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                                <a href="{{ route('patients.file', $session->patient->id) }}" class="btn btn-icon btn-primary" title="{{ __('my_sessions.table.actions.hover.clinic-history') }}">
                                                    <i class="fas fa-file-medical"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        </tbody>
                                    </table>
                                    <div class="alert alert-warning alert-has-icon mx-2">
                                        <div class="alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
                                        <div class="alert-body ml-1">
                                            <div class="alert-title">{{ __('dashboard.warning') }}</div>
                                            {{ __('dashboard.warning-text') }}
                                        </div>
                                    </div>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center pt-1 pb-1 mb-3">
                            @if($role == 'admin')
                                <a href="{{ route('admin_sessions.index') }}" class="btn btn-primary btn-lg btn-round">
                                    {{ __('dashboard.see-all') }}
                                </a>
                            @else
                                <a href="{{ route('sessions.index') }}" class="btn btn-primary btn-lg btn-round">
                                    {{ __('dashboard.see-all') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <ul class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

    </section>
@endsection

@section('scripts')
    <script src="{{asset('cms/assets/js/sweetalert.js')}}"></script>

    <script>
        function sweetAlert(paciente_id,session_id){
            swal({
                type: "info",
                title: "¿Quieres iniciar la sesion ahora?",
                text: "Se le enviara un email al paciente para que pueda conectarse.",
                confirmButtonText: 'Aceptar',
                cancelButtonText: "Cancelar",
                showCancelButton: true,
                showConfirmButton: true,
                closeOnConfirm: true,
                closeOnCancel: true
                },
                function(isConfirm) {
                    if (isConfirm) {
                        window.location.href = "/chat/"+paciente_id+"/"+session_id;
                    } else {
                        swal({
                            type: "error",
                            title: "Cancelado",
                            text: "Podras iniciar la sesion, mas tarde.",
                            confirmButtonText: 'Aceptar',
                            showConfirmButton: true,
                            closeOnConfirm: true,
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                swal.close()
                            }
                        }
                        );
                    }
            });
        }
    </script>

@endsection
