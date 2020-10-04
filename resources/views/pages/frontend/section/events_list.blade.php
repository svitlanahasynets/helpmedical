<?php
/**
 * Event Result Page 
 *
 * @author PYH
 * @since Nov 8, 2018
 */

use App\Models\Competition;
use App\Models\Event;
use App\Models\Team;
use App\Models\Schedule;
?>
<div id="schedule_section">
	@if ( count($schedules) > 0 )
		<div class="row-pagination margin-top-10">
			<div class="col-md-12 text-right">
				{!! $schedules->appends(Request::input())->render() !!}
			</div>
		</div>
		<div class="row title margin-bottom-10">
			<div class="col-md-10">
				{{ trans('common.showing_of_results', ['from' => $resultsNum['from'], 'to' => $resultsNum['to'], 'total' => $resultsNum['total']]) }}				
			</div>
		</div>

		<div class="box-section">
			@foreach($schedules as $key => $schedule)
		    <div class="schedule-item margin-bottom-10">
		    	<div class="row">
		    		
	    			@if( $schedule->progress == 1 )
	    			<div class="col-sm-1">
						<div class="status">
							<span class="">
								{{ trans('common.progressed') }}
							</span> 
						</div>
					</div>
					@endif
		    		
		    		<div class="col-sm-3">
		    			<a href="{{ route('schedule.overview', ['id' => $schedule->id]) }}" class="schedule-name"><i class="fa fa-star"></i> {{ $schedule->event->event_name }}</a>
		    		</div>
		    	</div>
		        <div class="schedule-content">
		        	<div class="row">
		        		@if( $schedule->progress == 1 )
		    			<div class="col-sm-1">
						</div>
						@endif
		        		<div class="col-sm-8">
		        			<div class="row">
		        				<div class="col-sm-3">
		        					<label>
				        				{{ trans('common.competition') }} : 
				        			</label>
		        				</div>
		        				<div class="col-sm-8">
									<span class="competition-name ordinary-value">
										<i class="fa fa-flag"></i> {{ $schedule->competition->competition_name }}
									</span>
		        				</div>
		        			</div>
		        			<div class="row">
		        				<div class="col-sm-3">
		        					<label>
				        				{{ trans('common.match_date') }} : 
				        			</label>
		        				</div>
		        				<div class="col-sm-9">
									<span class="ordinary-value">
										<i class="fa fa-calendar"></i> {{ $schedule->gameDate() }}
									</span>
		        				</div>
		        			</div>
		        			<div class="row">
		        				<div class="col-sm-3">
		        					<label>
				        				{{ trans('common.match_teams') }} : 
				        			</label>
		        				</div>
		        				<div class="col-sm-9">
									<span class="ordinary-value">
										{{ $schedule->team1->team_name }} {{ trans('common.team') }} <i class="fa fa-user"></i>  :  <i class="fa fa-user"></i> {{ $schedule->team2->team_name }} {{ trans('common.team') }}
									</span>
		        				</div>
		        			</div>
		        			<div class="row">
		        				<div class="col-sm-3">
		        					<label>
				        				{{ trans('common.game_result') }} : 
				        			</label>
		        				</div>
		        				<div class="col-sm-9">
									<div class="ordinary-value">
										@if( !empty($schedule->result()) )
											<div class="row margin-bottom-10">
												<div class="col-sm-6">
													{{ trans('common.winner_team') }} : <i class="fa fa-user"></i> {{ $schedule->result()->winner->team_name }} {{ trans('common.team') }}
													<i class="fa fa-thumbs-up"></i>
												</div>
												<div class="col-sm-6">
													
												</div>
											</div>
											@if (strlen($schedule->result()->result_desc) > 200)
												<div class="result-desc">
													{!! nl2br(mb_substr($schedule->result()->result_desc, 0, 200)) !!} ...
												</div>
									            <a href="{{ route('schedule.overview', ['id' => $schedule->id]) }}" class="pull-right"> <i class="fa fa-angle-double-right"></i> {{trans('common.view_more')}}</a>
									        @else
									        	<div class="result-desc">
									            	{!! nl2br($schedule->result()->result_desc) !!}
									            </div>
									        @endif
										@else
											{{ trans('common.no_game_result') }}
										@endif
									</div>
		        				</div>
		        			</div>
		        			<div class="row">
		        				<div class="col-sm-3">
		        					<label>
				        				{{ trans('common.reading_rate') }} : 
				        			</label>
		        				</div>
		        				<div class="col-sm-9">
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
		        		<div class="col-sm-3">
	        				@if( !empty($schedule->result()) )
	        					<div class="files">
		        					@if( !empty($schedule->result()->files()) )
			        					@foreach($schedule->result()->files() as $key => $file)
			        						@if( in_array($file->ext, ['jpg', 'png', 'bmp']) )
			        							<span class="photo file">
													<span class="img-wrapper">
														<a href="/files/{{ $file->id }}/{{ $file->hash }}">
															<i class="fa fa-camera"></i> {{ $file->name }}
														</a>
													</span>
												</span>
			        						@elseif( in_array($file->ext, ['avi', 'mp4', 'mpg', '3gp']) )
			        							<span class="video file">
			        								<a href="/files/{{ $file->id }}/{{ $file->hash }}">
		        										<i class="fa fa-video-camera"></i> {{ $file->name }}
		        									</a>
			        							</span>
			        						@else
			        							{{ trans('common.no_support_ext') }}
			        						@endif
			        					@endforeach
			        					<span class="badge badge-info">
			        						{{ count($schedule->result()->files()) }}
			        					</span>
			        				@else
			        					<span class="photo">
											<span class="img-wrapper">
												<img src="/assets/img/no-image.jpg" class="img-responsive" />
											</span>
										</span>
			        				@endif
		        				</div>
	        				@else
	        					
	        				@endif
		        		</div>
		        	</div>
		        </div>               
		    </div>
		    @endforeach
		</div>

		<div class="row margin-top-10">
			<div class="col-md-10 show-num">
				{{ trans('common.showing_of_results', ['from' => $resultsNum['from'], 'to' => $resultsNum['to'], 'total' => $resultsNum['total']]) }}		
			</div>
		</div>
		<div class="row-pagination">
			<div class="col-md-12 text-right">
				{!! $schedules->appends(Request::input())->render() !!}
			</div>
		</div>
	@else
		<div class="not-found-result">
			<div class="row">
				<div class="col-md-12 text-center">
					<div class="heading">{{ trans('common.you_have_no_results') }}</div>
				</div>
			</div>
		</div>
	@endif
</div>
