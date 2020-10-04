<?php
/**
 * Schedule Page 
 *
 * @author PYH
 * @since Oct 26, 2018
 */

use App\Models\EventMeter;
use App\Models\Team;
use App\Models\Schedule;
?>
@extends('layouts.admin.app')

@section('title')
<title>{{ trans('page.admin.' . $page . '.title') }} - {{ trans('page.title') }}</title>
@endsection

@section('additional_css')
<link rel="stylesheet" href="/assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet">
@endsection

@section('content')

<div class="tab-section">
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active">
			<a href="#create_new_competition_tab" role="tab" data-toggle="tab">새 경기일정창조</a>
		</li>
		<li role="presentation">
			<a href="#view_schedules" role="tab" data-toggle="tab" class="tab-dispute">창조된 일정보기</a>
		</li>
	</ul>
</div>

<div class="tab-content">
	<div id="create_new_competition_tab" role="tabpanel" class="tab-pane active">
		<div class="tab-inner">
			<div class="example" data-text="">
				<div class="wizard"
				data-role="wizard"
				data-buttons='{"finish": {"show": "true", "title": "일정공개하기", "cls": "success", "group": "left"}}'
				data-stepper-clickable="true">
				<div class="steps container" data-token="{{ csrf_token() }}">
					<form role="form" class="form-horizontal" id="new_competition_form" method="post" action="{{ Request()->url() }}">
						{{ method_field('POST') }}
						{{ csrf_field() }}

						<input type="hidden" name="competition_id" id="competition_id" value="">

						<input type="hidden" name="current_step" id="current_step" value="">
						<input type="hidden" name="start_date" id="start_date" value="">
						<input type="hidden" name="end_date" id="end_date" value="">
						<input type="hidden" name="next_url" id="next_url" value="{{ route('result') }}">
						<div class="step">
							<div class="portlet box blue">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-flag"></i>
										새 경기대회창조
									</div>                
								</div>
								<div class="portlet-body" id="create_new_competition">
									<div class="form-body">
										<div class="form-group row">
											<label class="col-sm-3 control-label">경기대회이름<span class="required" aria-required="true">*</span></label>
											<div class="col-sm-6">
												<div class="input-group">
													<span class="input-icon">
														<i class="fa fa-globe"></i>
														<input type="text" name="competition_name" id="competition_name" class="form-control" value="" />

													</span>
												</div>
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-3 control-label">경기대회기간<span class="required" aria-required="true">*</span></label>
											<div class="col-sm-6">
												<div id="reportrange" class="btn default">
													<i class="fa fa-calendar"></i>
													&nbsp; <span>
													</span>
													<b class="fa fa-angle-down"></b>
												</div>
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-3 control-label">경기종목선정<span class="required" aria-required="true">*</span></label>
											<div class="col-sm-6">
												<table class="table table-striped table-hover table-bordered" id="event_editable_1">
													<thead>
														<tr>
															<th width="5%" class="center"><input type="checkbox" class="group-checkable"/></th>
															<th width="" class="center">
																경기종목
															</th>
															<th width="20%" class="center">
																비중
															</th>
															<th width="10%" class="center">
																회전수
															</th>
														</tr>
													</thead>
													<tbody>
														@foreach ($events as $key => $event)
														<tr>
															<td align="center">
																<input type="checkbox" class="checkboxes" name="eventIds[]" value="{{ $event->id }}"/>
															</td>
															<td align="center">
																{{ $event->event_name }}
															</td>
															<td align="center">
																<input type="text" class="form-control only-number" name="event_weight_{{$event->id}}" value="{{ $event->default_weight }}" />
															</td>
															<td align="center">
																<input type="text" class="form-control only-number" name="event_slalom_{{$event->id}}" value="{{ $event->default_slalom }}" />
															</td>
														</tr>
														@endforeach
													</tbody>
												</table>
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-3 control-label">새 종목 추가</label>
											<div class="col-sm-6">
												<table class="table table-striped table-hover table-bordered" id="additional_events">
													<thead>
														<tr>
															<th width="" class="center">
																추가경기종목
															</th>
															<th width="20%" class="center">
																비중
															</th>
															<th width="10%" class="center">
																회전수
															</th>
															<th width="10%" class="center"></th>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
												<a href="javascript:;" class="btn default addition"><i class="fa fa-plus"></i> 추가</a>
											</div>
										</div>
										<div class="form-group row">
											<div class="col-sm-offset-3 col-sm-6 alert-comp-message margin-top-10">
											</div>
										</div>

										<div class="form-group row">
											<div class="col-sm-offset-5 col-sm-6">
												<button id="create_new_competition_button" class="btn btn-primary" type="button"><i class="fa fa-check"></i> 보관</button>
											</div>
										</div>
									</div>                  
								</div>
							</div>
						</div>
						<div class="step">
							<div class="portlet box blue">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-sitemap"></i>
										팀창조
									</div>
								</div>
								<div class="portlet-body" id="create_new_competition">
									<div class="form-body">
										<div class="row">
											<div class="col-sm-4">
												<div class="form-group row">
													<label class="col-sm-6 control-label">팀지정방식<span class="required" aria-required="true">*</span></label>
													<div class="col-sm-6" style="margin-top: -5px;">
														<select id="team_setting_mode" name="team_setting_mode" class="form-control select2" data-width="200">
															<option value=""> --- </option>
															@foreach ($team_modes as $key => $team_mode)
															<option value="{{ $team_mode->id }}">{{ $team_mode->team_mode_name }}</option>
															@endforeach
														</select>
														<span class="team-setting-alert"></span>
													</div>
												</div>
											</div>
											<div class="col-sm-4">
												<div class="form-group row">
													<label class="col-sm-6 control-label">팀개수<span class="required" aria-required="true">*</span></label>
													<div class="col-sm-6">
														<div class="input-group">
															<span class="input-icon">
																<i class="fa fa-group"></i>
																<input type="text" name="team_count" id="team_count" class="form-control only-number" value="" />
															</span>
														</div>
														<span class="team-count-alert"></span>
													</div>
												</div>
											</div>
										</div>

										<div class="team-snippet">

										</div>
										<div class="team-block">

										</div>

										<div class="form-group row">
											<div class="col-sm-offset-3 col-sm-6 alert-team-message">
											</div>
										</div>

										<div class="form-group row">
											<div class="col-sm-offset-5 col-sm-6">
												<button id="create_team_button" class="btn btn-primary" type="button"><i class="fa fa-check"></i> 보관</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="step">
							<div class="portlet box blue calendar">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-calendar"></i>
										경기일정편집
									</div>
								</div>
								<div class="portlet-body" id="create_new_competition">
									<div class="form-body">
										<div class="row">
											<div class="col-sm-2">
												<button id="auto_create_schedule" class="btn btn-primary" type="button"><i class="fa fa-check"></i> 일정자동생성</button>
												<button class="btn green proxy" data-date="" data-toggle="modal" data-target="#edit_schedule_modal" type="button">Edit <i class="fa fa-plus"></i></button>
											</div>
											<div class="col-sm-10">
												<div id="calendar">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>   
				</div>
				@include('pages.admin.section.edit_schedule_modal')
			</div>
		</div>
	</div><!-- .tab-inner -->
</div><!-- .tab-pane -->
<div id="view_schedules" role="tabpanel" class="tab-pane">
	<div class="tab-inner">
		<table class="table table-striped table-bordered table-hover" id="created_competition_list_table">
			<thead>
				<tr>
					<th class="center">경기대회이름</th>
					<th class="center">경기대회기간</th>
					<th class="center">상세보기</th>
					<th class="center">편집가능성</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($created_competitions as $i=>$created_competition)
				<tr>
					<td class="center">{{ $created_competition['competition_name'] }}</td>
					<td class="center">{{ date('Y.n.j', strtotime($created_competition['start_date'])) }} ~ {{ date('Y.n.j', strtotime($created_competition['end_date'])) }}</td>
					<td class="center"><a href="{{ route('schedule.schedule_view', ['id'=>$created_competition->id]) }}"><button class="btn blue"><i class="fa fa-eye"></i></button></a></td>
					<?php
						$event_counts = count(EventMeter::where('competition_id', $created_competition->id)->get());
				        $team_counts = count(Team::where('competition_id', $created_competition->id)->get());
				        $event_game_counts = $team_counts * ($team_counts-1) / 2;
				        $total_game_counts = $event_game_counts * $event_counts;
				        $current_result_counts = count(Schedule::where('competition_id', $created_competition->id)->where('progress', 1)->get());
					?>
					<td class="center">
						@if ( $total_game_counts > $current_result_counts )
						<a href="{{ route('schedule.schedule_edit', ['id'=>$created_competition->id]) }}"><button class="btn green"><i class="fa fa-edit"></i></button></a>
						@endif
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div><!-- .tab-inner -->
</div><!-- .tab-pane -->
</div>


@endsection			


@section('additional_js')
<script src="/assets/plugins/bootstrap-daterangepicker/moment.min.js"></script>
<script src="/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="/assets/plugins/metro/metro.js"></script>
@endsection