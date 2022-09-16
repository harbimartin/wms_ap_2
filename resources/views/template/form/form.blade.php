@if (isset($form))
    @if (isset($form['fancy']))
        <div class="panel panel-default" style="margin-bottom: 0;">
        @else
            <div class="panel panel-default">
    @endif
    <div class="panel-heading">
        <h3 class="panel-title">
            <i class="fa fa-edit"></i> {!! $form['tittle'] ?? null !!}
        </h3>
    </div>
    @if (!empty(Session::get('notif_msg')))
        <div class="alert alert-dismissable alert-success" style="margin-bottom: 0;">
            {{ Session::get('notif_msg') }}
        </div>
    @else
        <div class="panel-body">

            @include('template/form/formMessage')

            {!! $form['open'] ?? null !!}
            {{-- {{ json_encode($form) }} --}}
            @foreach ($form['field'] as $fo)
                <div class="form-group">
                    {{-- {{ json_encode($fo) }} --}}
                    <label>{!! $fo['label'] ?? null !!}</label>
                    {!! $fo['main'] ?? null !!}
                    {!! $fo['extra'] ?? null !!}
                </div>
            @endforeach
            {!! Formz::close() !!}
        </div>
    @endif
    </div>
@endif
