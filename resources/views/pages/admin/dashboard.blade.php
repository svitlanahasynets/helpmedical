@extends('layouts.admin.app')

@section('title')
<title>{{ trans('page.admin.' . $page . '.title') }} - {{ trans('page.title') }}</title>
@endsection

@section('content')
<div class="row  margin-bottom-10">
	<div class="col-md-6 col-sm-6 col-xs-12">
		<div class="portlet box blue">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-tasks"></i>
					{{ trans('common.recent') }} {{ trans('common.competition') }}
				</div>                
			</div>
			<div class="portlet-body height-180">
				<div class="row padding-top-20">
					<label class="col-md-4 col-sm-4 control-label"> {{ trans('common.competition') }}{{ trans('common.name') }} :</label>
					<div class="col-sm-7">
						{{ $competition_name }}
					</div>
				</div>
				<div class="row">
					<label class="col-md-4 col-sm-4 control-label"> {{ trans('common.competition') }}{{ trans('common.period') }} :</label>
					<div class="col-sm-7">
						{{ $start_date }} ~ {{ $end_date }}
					</div>
				</div>
				<div class="row">
					<label class="col-md-4 col-sm-4 control-label"> {{ trans('common.game_events') }}{{ trans('common.count') }} :</label>
					<div class="col-sm-7">
						{{ $event_count }} 
					</div>
				</div>
				<div class="row">
					<label class="col-md-4 col-sm-4 control-label"> {{ trans('common.game') }}{{ trans('common.teams') }}{{ trans('common.count') }} :</label>
					<div class="col-sm-7">
						{{ $team_count }} 
					</div>
				</div>	                   
			</div>
		</div>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<div class="portlet box green">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-circle-o-notch"></i>
					{{ trans('common.progress_percent') }}
				</div>                
			</div>
			<div class="portlet-body height-180">
				<div class="row">
					<div class="col-md-6">
						<div class="easy-pie-chart">
							<div class="number progress-percent" data-percent="{{ $progress_percent }}">
								<span>
								{{ $progress_percent }} </span>
								%
							</div>
						</div>
					</div>
					<div class="col-md-6 padding-top-30">
						<div class="row">
							<label class="col-md-8 col-sm-8 control-label"> {{ trans('common.total_game_counts') }} :</label>
							<div class="col-md-4 col-sm-4">
								{{ $total_game_counts }} 
							</div>
						</div>
						<div class="row">
							<label class="col-md-8 col-sm-8 control-label"> {{ trans('common.progressed_schedules_count') }} :</label>
							<div class="col-md-4 col-sm-4">
								{{ $progressed_schedules_count }} 
							</div>
						</div>
					</div>
				</div>

				
			</div>
		</div>
	</div>


</div>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<div class="portlet box yellow">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-bar-chart-o"></i>
					{{ trans('common.team_ranking') }}
				</div>                
			</div>
			<div class="portlet-body height-350">
				@if (is_array($stats['bar']))
				<script type="text/javascript">
					var barGraphData = {!! json_encode($stats['bar']) !!};
				</script>
				@else
				<div class="none">
					{{ trans('common.no_taken_place_game') }}
				</div>
				@endif
				<div id="bar_chart" class="chart">
				</div>
				
			</div>
		</div>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<div class="portlet box red">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-bar-chart"></i>
					{{ trans('common.event_weight') }}
				</div>                
			</div>
			<div class="portlet-body height-350">
				<script type="text/javascript">
					var pieGraphData = {!! json_encode($stats['pie']) !!};
				</script>
				<div id="pie_chart" class="chart"></div>                   
			</div>
		</div>
	</div>

</div>
<div class="portlet box purple-wisteria"">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-calendar"></i>
			{{ trans('common.competition') }}{{ trans('common.track') }}
		</div>                
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="competition_list_table">
			<thead>
				<tr>
					<th class="center">{{ trans('common.competition') }}{{ trans('common.name') }}</th>
					<th class="center">{{ trans('common.competition') }}{{ trans('common.period') }}</th>
					<th class="center">{{ trans('common.game') }}{{ trans('common.teams') }}{{ trans('common.count') }}</th>
					<th class="center">{{ trans('common.game_events') }}{{ trans('common.count') }}</th>
					<th class="center">{{ trans('common.total') }}{{ trans('common.game') }}{{ trans('common.count') }}</th>
					<th class="center">{{ trans('common.winner') }}</th>
					<th class="center"></th>
				</tr>
			</thead>
			<tbody>
				@foreach ($competitions_array as $i=>$competition_array)
				<tr>
					<td class="center">{{ $competition_array['competition_name'] }}</td>
					<td class="center">{{ $competition_array['start_date'] }} ~ {{ $competition_array['end_date'] }}</td>
					<td class="center">{{ $competition_array['team_count'] }}</td>
					<td class="center">{{ $competition_array['event_count'] }}</td>
					<td class="center">{{ $competition_array['total_game_count'] }}</td>
					<td class="center">{{ $competition_array['winner_team_name'] }}팀</td>
					<td class="center">
						<a href="{{ route('dashboard', ['id' => $i]) }}" class="detail-view">
							<i class="fa fa-eye"></i> {{ trans('common.view_more') }}
						</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>                  
	</div>
</div>
<div class="clearfix"></div>

@endsection

@section('additional_js')
<script src="/assets/plugins/amcharts/amcharts/amcharts.js" type="text/javascript"></script>
<script src="/assets/plugins/amcharts/amcharts/serial.js" type="text/javascript"></script>
<script src="/assets/plugins/amcharts/amcharts/pie.js" type="text/javascript"></script>
<script src="/assets/plugins/amcharts/amcharts/radar.js" type="text/javascript"></script>
<script src="/assets/plugins/amcharts/amcharts/themes/light.js" type="text/javascript"></script>
<script src="/assets/plugins/amcharts/amcharts/themes/red.js" type="text/javascript"></script>
<script src="/assets/plugins/amcharts/ammap/ammap.js" type="text/javascript"></script>
<script src="/assets/plugins/amcharts/ammap/maps/js/worldLow.js" type="text/javascript"></script>
<script src="/assets/plugins/amcharts/amstockcharts/amstock.js" type="text/javascript"></script>

<script src="/assets/plugins/jquery-easypiechart/jquery.easypiechart.min.js" type="text/javascript"></script>
<script src="/assets/plugins/jquery.sparkline.min.js" type="text/javascript"></script>

<script src="/assets/plugins/datatables/media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js" type="text/javascript"></script>
@endsection