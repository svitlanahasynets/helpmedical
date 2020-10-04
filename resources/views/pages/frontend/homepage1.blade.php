@extends('layouts.frontend.app')

@section('title')
    <title>{{ trans('page.frontend.' . $page . '.title') }} - {{ trans('page.title') }}</title>
@endsection

@section('content')
	<form role="form" class="form-horizontal" id="search_form" method="post" action="{{ Request()->url() }}">
        {{ method_field('POST') }}
        {{ csrf_field() }}
        <input type="hidden" name="page" id="page" value="{{ $results_page }}">
        <input type="hidden" name="start_date" id="start_date" value="">
		<input type="hidden" name="end_date" id="end_date" value="">

        <div class="input-group">
            <input id="search_title" name="q" class="form-control" type="text" placeholder="검색어..." value="" />
            <span class="input-group-btn">
                <input type="button" id="search_btn" class="btn btn-primary search-btn" value="검색"></input>           
            </span>         
        </div>
        <div class="clearfix">
		</div>

        <div class="margin-top-10">
            <a href="#" class="clear-filter"><i class="fa fa-recycle"></i> &nbsp;&nbsp;검색조건 지우기</a>
        </div>

        <div class="row margin-top-20">
        	<div class="col-sm-3 col-md-3"> 
        		<div id="condition_section">
        			<div class="condition-box">
        				<label class="condition-label"><i class="fa fa-trophy"></i> &nbsp;&nbsp;&nbsp;대회별 / 종목별 보기 </label>
        				<div class="condition-value">
        					<select class="form-control" name="category" id="category" autocomplete="off">
	                            <option value="competition_view" {{ $comp_flag == 1 ? 'selected' : '' }}> 경기대회별로 보기 </option>
	                            <option value="schedule_view" {{ $comp_flag != 1 ? 'selected' : '' }}> 경기종목별로 보기 </option>
	                        </select>
        				</div>
        			</div>
        			<div class="condition-box">
        				<label class="condition-label"><i class="fa fa-calendar"></i> &nbsp;&nbsp;&nbsp;날자령역별 보기 </label>
        				<div class="condition-value" style="height: 50px;">
                            <div class="input-group" id="date_range">
                                <input type="text" class="form-control">
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
        				<label class="condition-label"><i class="fa fa-star"></i> &nbsp;&nbsp;&nbsp;종목별 보기 </label>
        				<div class="condition-value">
	        				@foreach($events as $key => $event)
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" class="event-ids" name="event_ids[]" value="{{ $event->id }}" checked>
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
                            <label>정렬방식:</label>
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