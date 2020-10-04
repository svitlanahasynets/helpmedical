@extends('layouts.admin.app')

@section('title')
	<title>{{ trans('page.admin.' . $page . '.title') }} - {{ trans('page.title') }}</title>
@endsection

@section('additional_css')
<link href="/assets/plugins/jquery-file-upload/blueimp-gallery/blueimp-gallery.min.css" rel="stylesheet"/>
<link href="/assets/plugins/jquery-file-upload/css/jquery.fileupload.css" rel="stylesheet"/>
<link href="/assets/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet"/>
@endsection

@section('content')
	<div class="container"  data-token="{{ csrf_token() }}">
		<form role="form" class="form-horizontal" id="edit_result_form" method="post" action="{{ Request()->url() }}">
	        {{ method_field('POST') }}
	        {{ csrf_field() }}

	        <input type="hidden" name="competition_id" id="competition_id" value="{{ $competition_id }}">

	        <div class="portlet box blue margin-bottom-20">
			    <div class="portlet-title">
			        <div class="caption">
			            <i class="fa fa-sitemap"></i>
			            결과편집가능한 경기대회들
			        </div>                
			    </div>
			    <div class="portlet-body" id="editable_schedules">
			    	<div class="progressed-competition-area select2">
						<select name="role" class="form-control form-filter input-sm select2" id="progressed_competitions" data-rule-required="true">
							@foreach ($progressed_competitions as $key => $progressed_competition)
							<option value="{{ $progressed_competition->id }}" {{ ($competition_id == $progressed_competition->id) ? 'selected' : ''}} >{{ $progressed_competition->competition_name }}</option>
							@endforeach
						</select>
					</div>
			    </div>
			</div>
	        
	        <div class="portlet box blue calendar">
			    <div class="portlet-title">
			        <div class="caption">
			            <i class="fa fa-trophy"></i>
			            {{ $competition_name }}
			        </div>                
			    </div>
			    <div class="portlet-body" id="edit_result">
			    	<button class="btn green proxy" data-date="" data-toggle="modal" data-target="#resultModal" type="button">Edit <i class="fa fa-plus"></i></button>
			    	<div id="calendar">
					</div>
			    </div>
			</div>
		</form>
	</div>

	@include('pages.admin.section.edit_result_modal')
	
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

			var color = '';

			if ( progress == 1 ) {
				color = 'green';
			} else {
				if ( diff_year > 0 ) {
					color = 'red';
				}
				else if (diff_year < 0) {
					color = 'blue';
				}
				else {
					if (diff_month > 0) {
						color = 'red';
					}
					else if (diff_month < 0) {
						color = 'blue';
					}
					else {
						if (diff_day > 0) {
							color = 'red';
						} else {
							color = 'blue';
						}
					}
				}
			}

            event_content.push({
				title: title,
				start: new Date(y-diff_year, m-diff_month, d-diff_day),
				backgroundColor: Metronic.getBrandColor(color)
			});
			
        @endforeach

	</script>
	<script src="/assets/plugins/jquery-file-upload/js/vendor/jquery.ui.widget.js"></script>
	<!-- The Templates plugin is included to render the upload/download listings -->
	<script src="/assets/plugins/jquery-file-upload/js/vendor/tmpl.min.js"></script>
	<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
	<script src="/assets/plugins/jquery-file-upload/js/vendor/load-image.min.js"></script>
	<!-- The Canvas to Blob plugin is included for image resizing functionality -->
	<script src="/assets/plugins/jquery-file-upload/js/vendor/canvas-to-blob.min.js"></script>
	<!-- blueimp Gallery script -->
	<script src="/assets/plugins/jquery-file-upload/blueimp-gallery/jquery.blueimp-gallery.min.js"></script>
	<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
	<script src="/assets/plugins/jquery-file-upload/js/jquery.iframe-transport.js"></script>
	<!-- The basic File Upload plugin -->
	<script src="/assets/plugins/jquery-file-upload/js/jquery.fileupload.js"></script>
	<!-- The File Upload processing plugin -->
	<script src="/assets/plugins/jquery-file-upload/js/jquery.fileupload-process.js"></script>
	<!-- The File Upload image preview & resize plugin -->
	<script src="/assets/plugins/jquery-file-upload/js/jquery.fileupload-image.js"></script>
	<!-- The File Upload audio preview plugin -->
	<script src="/assets/plugins/jquery-file-upload/js/jquery.fileupload-audio.js"></script>
	<!-- The File Upload video preview plugin -->
	<script src="/assets/plugins/jquery-file-upload/js/jquery.fileupload-video.js"></script>
	<!-- The File Upload validation plugin -->
	<script src="/assets/plugins/jquery-file-upload/js/jquery.fileupload-validate.js"></script>
	<!-- The File Upload user interface plugin -->
	<script src="/assets/plugins/jquery-file-upload/js/jquery.fileupload-ui.js"></script>
@endsection