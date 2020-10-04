<?php
/**
 * Schedule Detail View Page 
 *
 * @author PYH
 * @since Nov 13, 2018
 */

use App\Models\Competition;
use App\Models\Event;
use App\Models\Team;
use App\Models\Schedule;
?>
@extends('layouts.frontend.app')

@section('title')
    <title>{{ trans('page.frontend.' . $page . '.title') }} - {{ trans('page.title') }}</title>
@endsection

@section('additional_css')

@endsection

@section('content')
<div class="prev-page-section">
	<a href="{{ url()->previous() }}" class="prev-page-link"><i class="fa fa-reply"></i> {{ trans('common.prev_page') }}</a>
</div>
<div class="portlet box red">
	<div class="portlet-title">
		<div class="caption">
			@if( in_array($schedule->event_id, Event::$ball_event_ids) )
				<i class="icon icon-social-dribbble"></i>
			@else
				<i class="fa fa-flag"></i>
			@endif
			{{ $schedule->event->event_name }} 
		</div>
	</div>
	<div class="portlet-body">
		<div class="row">
			@if( !empty($schedule->result()) )
			<div class="col-md-4">
				<div class="files">
					@if( !empty($schedule->result()->files()) )
						<div id="myCarousel" class="carousel slide" style="display: none;" data-interval="5000" data-ride="carousel">
							<!-- Indicators -->
							<ol class="carousel-indicators">
								@foreach($schedule->result()->files() as $key => $file)
									<li data-target="#myCarousel" data-slide-to="{{ $key }}"></li>
								@endforeach
							</ol>
							<div class="carousel-inner">
								@foreach($schedule->result()->files() as $key => $file)
		    						@if( in_array($file->ext, ['jpg', 'png', 'bmp']) )
		    							<span class="photo item">
											<a href="/files/{{ $file->id }}/{{ $file->hash }}">
												<img src="/files/{{ $file->id }}/{{ $file->hash }}" alt="" class="img-responsive">
											</a>
										</span>
		    						@elseif( in_array($file->ext, ['avi', 'mp4', 'mpg', '3gp']) )
		    							<span class="video item">
											<!-- <i class="fa fa-video-camera"></i> {{ $file->name }} -->
											<video controls>
												<source src="/files/{{ $file->id }}/{{ $file->hash }}" type="">
											</video>
		    							</span>
		    						@else
		    							{{ trans('common.no_support_ext') }}
		    						@endif
		    					@endforeach
							</div>
						</div>
    				@else
    					<span class="photo no-image">
							<img src="/assets/img/no-image.jpg" class="img-responsive" />
						</span>
    				@endif
				</div>
			</div>
			@endif
			<div class="{{ !empty($schedule->result()) ? 'col-md-8' : 'col-md-12' }}">
				<div class="row margin-bottom-5">
					<div class="col-md-2">
						<label>
		    				{{ trans('common.competition') }} : 
		    			</label>
					</div>
					<div class="col-md-10">
						<span class="ordinary-value">
							<i class="fa fa-flag"></i> {{ $schedule->competition->competition_name }}
						</span>
					</div>
				</div>
				<div class="row margin-bottom-5">
					<div class="col-md-2">
						<label>
		    				{{ trans('common.match_date') }} : 
		    			</label>
					</div>
					<div class="col-md-10">
						<span class="ordinary-value">
							<i class="fa fa-calendar"></i> {{ $schedule->gameDate() }}
						</span>
					</div>
				</div>
				<div class="row margin-bottom-10">
					<div class="col-md-2">
						<label>
		    				{{ trans('common.match_teams') }} : 
		    			</label>
					</div>
					<div class="col-md-10">
						<div class="ordinary-value">
							<div class="row">
								<div class="col-md-3">
									<i class="fa fa-users"></i> {{ $schedule->team1->team_name }} {{ trans('common.team') }} :
								</div>
								<div class="col-md-9">
									<div>
										{{ $schedule->team1->teamMembersInfo()['name'] }} - {{ $schedule->team1->teamMembersInfo()['count'] }}{{ trans('common.person') }}
									</div>
									<div>
										( <i class="fa fa-male"></i> {{ trans('common.special_members') }} : {{ $schedule->team1->teamMembersInfo($schedule->event_id)['special'] }})
									</div>
								</div>
							</div>
						</div>
						<div class="ordinary-value">
							<div class="row">
								<div class="col-md-3">
									<i class="fa fa-users"></i> {{ $schedule->team2->team_name }} {{ trans('common.team') }} :
								</div>
								<div class="col-md-9">
									<div>
										{{ $schedule->team2->teamMembersInfo()['name'] }} - {{ $schedule->team2->teamMembersInfo()['count'] }}{{ trans('common.person') }}
									</div>
									<div>
										( <i class="fa fa-male"></i> {{ trans('common.special_members') }} : {{ $schedule->team2->teamMembersInfo($schedule->event_id)['special'] }})
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row margin-bottom-5">
					<div class="col-md-2">
						<label>
		    				{{ trans('common.game_result') }} : 
		    			</label>
					</div>
					<div class="col-md-10">
						<div class="ordinary-value">
							@if( !empty($schedule->result()) )
								<div class="row margin-bottom-10">
									<div class="col-sm-3">
										{{ trans('common.winner_team') }} : {{ $schedule->result()->winner->team_name }} {{ trans('common.team') }}
										<i class="fa fa-thumbs-up"></i>
									</div>
									<div class="col-sm-3">
										( {{ $schedule->result()->winner_score }} : {{ $schedule->result()->loser_score }} )
									</div>
								</div>
								<div class="result-desc">
									{!! nl2br($schedule->result()->result_desc) !!}
								</div>
							@else
								{{ trans('common.no_game_result') }}
							@endif
						</div>
					</div>
				</div>
				<div class="row margin-bottom-5">
					<div class="col-md-2">
						<label>
		    				{{ trans('common.reading_rate') }} : 
		    			</label>
					</div>
					<div class="col-md-10">
						<?php
		    				$read_count_array = Schedule::whereRaw(true)->pluck('read_counts')->all();
					        $max_read_count = max($read_count_array);
						?>
						<div class="client-score">
				            <div class="score-wrap">
				                @if ( $schedule->read_counts != 0 ) 
				                <div class="stars" data-value="{{ $schedule->read_counts / $max_read_count * 100 }}%">
				                </div>
				                @else
				                <div class="stars" data-value="0%"></div>
				                @endif
				                <div class="client-score-desc" style="display:none;">
				                    {{ trans('common.reading_count') }}&nbsp;:&nbsp;{{ $schedule->read_counts }}
				                </div>
				            </div>
				        </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('additional_js')

@endsection