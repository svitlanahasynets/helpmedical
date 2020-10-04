@extends('layouts.frontend.app')

@section('title')
    <title>{{ trans('page.frontend.' . $page . '.title') }} - {{ trans('page.title') }}</title>
@endsection

@section('content')
	<form role="form" class="form-horizontal" id="search_form" method="get" action="{{ Request()->url() }}">
        <input type="hidden" name="page" id="page" value="{{ $results_page }}">
        <input type="hidden" name="start_date" id="start_date" value="{{ $start_date }}">
		<input type="hidden" name="end_date" id="end_date" value="{{ $end_date }}">

        <div class="input-group">
            <input id="search_title" name="q" class="form-control" type="text" placeholder="{{ trans('common.keyword') }} ..." value="{{ old('q') }}" />
            <span class="input-group-btn">
                <input type="button" id="search_btn" class="btn btn-primary search-btn" value="{{ trans('common.search') }}"></input>           
            </span>         
        </div>
        <div class="clearfix">
		</div>

        <div class="margin-top-10">
            <a href="#" class="clear-filter"><i class="fa fa-recycle"></i> &nbsp;&nbsp;{{ trans('common.remove_search_terms') }}</a>
        </div>

        <div class="row margin-top-20">
        	<div class="col-sm-3 col-md-3"> 
        		<div id="condition_section">
        			<div class="condition-box">
        				<label class="condition-label"><i class="fa fa-trophy"></i> &nbsp;&nbsp;&nbsp;{{ trans('common.competition') }} /  {{ trans('common.event') }}{{ trans('common.per') }} {{ trans('common.view') }} </label>
        				<div class="condition-value">
        					<select class="form-control" name="category" id="category">
	                            <option value="competition_view" {{ old('category') == 'competition_view' ? 'selected' : '' }}> {{ trans('common.competition_view') }} </option>
	                            <option value="schedule_view" {{ old('category') == 'schedule_view' ? 'selected' : '' }}> {{ trans('common.game_view') }} </option>
	                        </select>
        				</div>
        			</div>
        			<div class="condition-box">
        				<label class="condition-label"><i class="fa fa-calendar"></i> &nbsp;&nbsp;&nbsp;{{ trans('common.date_range_view') }} </label>
                        <script type="text/javascript">
                            var date_range = '{{ $date_range }}';
                            var start_date = '{{ $start_date }}';
                            var end_date = '{{ $end_date }}';
                        </script>
        				<div class="condition-value" style="height: 50px;">
                            <div class="input-group" id="date_range">
                                <input type="text" class="form-control" value="{{ $date_range }}">
                                <span class="input-group-btn">
                                <button class="btn default date-range-toggle" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
						</div>
        			</div>
        			<!-- <div class="condition-box" style="height: 100px;">
        				<label class="condition-label"><i class="fa fa-flag"></i> &nbsp;&nbsp;&nbsp;날자별 보기 </label>
        				<div class="condition-value">
	        				<div class="input-group input-medium date date-picker" data-date="" data-date-format="MM dd, yyyy" data-date-viewmode="years">
                                <input type="text" class="form-control" readonly>
                                <span class="input-group-btn">
                                <button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
						</div>
        			</div> -->
        			<div class="condition-box events-view">
        				<label class="condition-label"><i class="fa fa-star"></i> &nbsp;&nbsp;&nbsp;{{ trans('common.event_view') }} </label>
        				<div class="condition-value">
	        				@foreach($events as $key => $event)
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" class="event-ids" name="event_ids[]" value="{{ $event->id }}"  @if ( (old('event_ids') != '' && in_array($event->id, explode(',', old('event_ids')))) || old('event_ids') == '' || $defaultSearched ) checked @endif >
                                    {{ $event->event_name }}
                                </label>                
                            </div>
                            @endforeach
						</div>
        			</div>
        		</div>
        	</div>
        	<div class="col-sm-9 col-md-9">
                <div class="row margin-bottom-10">
                    <div class="col-sm-6 form-inline">
                        <div class="form-group">
                            <span class="result-count"></span>
                        </div>
                    </div>
                    <div class="col-sm-6 form-inline text-right">
                        <div class="form-group sortby">
                            <label>{{ trans('common.sortby') }} :</label>
                            <select class="form-control" name="sort" id="sort">
                                <option value="newest" {{ 'newest' == old('sort') ? 'selected' : '' }}>Newest</option>
                                <option value="oldest" {{ 'oldest' == old('sort') ? 'selected' : '' }}>Oldest</option>
                            </select>
                        </div>
                    </div>                        
                </div>

        		<div id="result_section">
                    @if ( $comp_flag == 1 )
                        @include ('pages.frontend.section.competitions_list')
                    @else
                        @include ('pages.frontend.section.events_list')
                    @endif
        		</div>
        	</div>
        </div>

	</form>
@endsection

@section('additional_js')

@endsection