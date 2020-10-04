@extends('layouts.admin.app')

@section('title')
	<title>{{ trans('page.admin.' . $page . '.title') }} - {{ trans('page.title') }}</title>
@endsection

@section('additional_css')
@endsection

@section('content')
	<a class="btn btn-blue back" href="{{ route('schedule') }}"><i class="fa fa-mail-reply"></i> 일정편집페지로</a>
	<div class="container"  data-token="{{ csrf_token() }}">

		<form role="form" class="form-horizontal" id="edit_schedule_form" method="post" action="{{ Request()->url() }}">
	        {{ method_field('POST') }}
	        {{ csrf_field() }}

	        <input type="hidden" name="competition_id" id="competition_id" value="{{ $competition_id }}">
	        
	        <div class="portlet box blue calendar">
			    <div class="portlet-title">
			        <div class="caption">
			            <i class="fa fa-trophy"></i>
			            {{ $competition_name }}
			        </div>                
			    </div>
			    <div class="portlet-body" id="edit_schedule">
			    	<button class="btn green proxy" data-date="" data-toggle="modal" data-target="#edit_schedule_modal" type="button">Edit <i class="fa fa-plus"></i></button>
			    	<div id="calendar">
					</div>
			    </div>
			</div>
		</form>
	</div>

	@include('pages.admin.section.edit_schedule_modal')
	
@endsection

@section('additional_js')
	<script type="text/javascript">
		// start year, month, day setting !
		var start_year = '{{ date('Y', strtotime($start_date)) }}';
		var start_month = parseInt('{{ date('n', strtotime($start_date)) }}') - 1;
		var start_day = '{{ date('ja', strtotime($start_date)) }}';

		// event_content making !
		var event_content = new Array();
		var dt = new Date();
		var y = dt.getFullYear();
		var m = dt.getMonth();
		var d = dt.getDate();

		@foreach ($json_data as $key=>$data)

			var title = '{{ $data['title'] }}';
			var diff_year  = parseInt('{{ $data['diff_year'] }}');
			var diff_month = parseInt('{{ $data['diff_month'] }}');
			var diff_day   = parseInt('{{ $data['diff_day'] }}');
			var progress   = parseInt('{{ $data['progress'] }}');

			var color = 'blue';

            event_content.push({
				title: title,
				start: new Date(y-diff_year, m-diff_month, d-diff_day),
				backgroundColor: Metronic.getBrandColor(color)
			});
			
        @endforeach

	</script>
@endsection