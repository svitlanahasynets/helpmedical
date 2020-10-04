<?php
/**
* Competition Detail Page 
*
* @author PYH
* @since Nov 12, 2018
*/
use App\Models\Competition;
use App\Models\Event;
use App\Models\Team;
use App\Models\Schedule;
?>


<?php $__env->startSection('title'); ?>
<title><?php echo e(trans('page.frontend.' . $page . '.title')); ?> - <?php echo e(trans('page.title')); ?></title>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="prev-page-section">
	<a href="<?php echo e(url()->previous()); ?>" class="prev-page-link"><i class="fa fa-reply"></i> <?php echo e(trans('common.prev_page')); ?></a>
</div>
<div class="portlet box red tabbable">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-trophy"></i>  <?php echo e($competition->competition_name); ?>

		</div>
	</div>
	<div class="portlet-body">
		<div class="tabbable portlet-tabs">
			<ul class="nav nav-tabs">
				<li>
					<a href="#portlet_tab_2" data-toggle="tab">
					<?php echo e(trans('common.result_view')); ?> </a>
				</li>
				<li class="active">
					<a href="#portlet_tab_1" data-toggle="tab">
					<?php echo e(trans('common.schedule_view')); ?> </a>
				</li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="portlet_tab_1">
					<div class="summary margin-bottom-15">
						<div class="created-date">
							<i class="fa fa-bullhorn"></i> <?php echo e(trans('common.created_date', ['n' => $competition->createdDate()])); ?>

							<div class="row margin-top-10">
								<div class="col-md-2">
									<label>
										<i class="fa fa-calendar"></i> <?php echo e(trans('common.game_dates')); ?> : 
									</label>
								</div>
								<div class="col-md-10">
									<?php echo e($competition->gameDates()); ?>

								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<label>
										<i class="icon icon-social-dribbble"></i> <?php echo e(trans('common.game_events')); ?> : 
									</label>
								</div>
								<div class="col-md-10">
									<?php
									$event_ids_str = $competition->event_ids;
									$event_ids = explode(',', $event_ids_str);
									?>
									<?php if( $event_ids_str != '' ): ?>
									<?php $__currentLoopData = $event_ids; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $event_id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<span class="event-name label label-default">
										<?php echo e(Event::where('id', $event_id)->value('event_name')); ?> <i class="fa fa-check"></i>
									</span>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									<?php endif; ?>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<label>
										<i class="fa fa-sitemap"></i> <?php echo e(trans('common.team_setting_mode')); ?> : 
									</label>
								</div>
								<div class="col-md-10">
									<?php echo e($competition->teamMode()); ?>

								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<label>
										<i class="fa fa-users"></i> <?php echo e(trans('common.teams')); ?> : 
									</label>
								</div>
								<div class="col-md-10 ordinary-value">
									<?php
								        $teams = Team::where('competition_id', $competition->id)->get()->all();
									?>

									<?php if( count($teams) > 0 ): ?>
										<?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<div class="row">
												<div class="col-sm-2">
													<?php echo e($team->team_name); ?><?php echo e(trans('common.team')); ?> : 
												</div>
												<div class="col-sm-10">
													( <?php echo e($team->teamMembersInfo()['name']); ?> ) - <?php echo e($team->teamMembersInfo()['count']); ?><?php echo e(trans('common.person')); ?> 
												</div>
											</div>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									<?php endif; ?>
								</div>
							</div>
							
							<div class="row">
								<div class="col-sm-2">
									<label>
										<i class="fa fa-folder-open"></i> <?php echo e(trans('common.reading_rate')); ?> : 
									</label>
								</div>
								<div class="col-sm-10">
									<?php
									$read_count_array = Competition::whereRaw(true)->pluck('read_counts')->all();
									$max_read_count = max($read_count_array);
									?>
									<div class="client-score">
										<div class="score-wrap">
											<?php if( $competition->read_counts != 0 ): ?> 
											<div class="stars" data-value="<?php echo e($competition->read_counts / $max_read_count * 100); ?>%">
											</div>
											<?php else: ?>
											<div class="stars" data-value="0%"></div>
											<?php endif; ?>
											<div class="client-score-desc" style="display:none;">
												<?php echo e(trans('common.reading_count')); ?>&nbsp;:&nbsp;<?php echo e($competition->read_counts); ?>

											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="box-section">
						<div id="calendar">
						</div>
					</div>
				</div>
				<div class="tab-pane" id="portlet_tab_2">
					<?php if( count($results) == 0 ): ?>
					<div class="alert alert-danger">
						<i class="fa fa-bullhorn"></i> <?php echo e(trans('common.you_do_not_edit_results')); ?>

					</div>
					<?php endif; ?>
					<div class="summary margin-bottom-15">
						<div class="row margin-bottom-5">
							<div class="col-sm-3">
								<i class="fa fa-circle-o-notch"></i>
								<?php echo e(trans('common.progress_percent')); ?>

							</div>
							<div class="col-sm-4">
								<i class="fa fa-bar-chart-o"></i>
								<?php echo e(trans('common.team_ranking')); ?>

							</div>
							<div class="col-sm-5">
								<i class="icon-bar-chart"></i>
								<?php echo e(trans('common.event_weight')); ?>

							</div>
						</div>
						<div class="row">
							<div class="col-sm-3">
								<div class="easy-pie-chart chart">
									<div class="number progress-percent" data-percent="<?php echo e($progress_percent); ?>">
										<span>
										<?php echo e($progress_percent); ?> </span>
										%
									</div>
									<div class="row">
										<label class="col-md-8 col-sm-8 control-label"> <?php echo e(trans('common.total_game_counts')); ?> :</label>
										<div class="col-md-4 col-sm-4">
											<?php echo e($total_game_counts); ?> 
										</div>
									</div>
									<div class="row">
										<label class="col-md-8 col-sm-8 control-label"> <?php echo e(trans('common.progressed_schedules_count')); ?> :</label>
										<div class="col-md-4 col-sm-4">
											<?php echo e($progressed_schedules_count); ?> 
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-4">
								<?php if(is_array($stats['bar'])): ?>
								<script type="text/javascript">
									var barGraphData = <?php echo json_encode($stats['bar']); ?>;
								</script>
								<?php else: ?>
								<div class="none">
									<?php echo e(trans('common.no_taken_place_game')); ?>

								</div>
								<?php endif; ?>
								<div id="bar_chart" class="chart">
								</div>
							</div>
							<div class="col-sm-5">
								<script type="text/javascript">
									var pieGraphData = <?php echo json_encode($stats['pie']); ?>;
								</script>
								<div id="pie_chart" class="chart"></div>
							</div>
						</div>
					</div>
					<?php if( count($results) > 0 ): ?>
					<div class="box-section">
						<div class="row box-header">
							<div class="col-md-3 text-center">
								<?php echo e(trans('common.game_dates')); ?>

							</div>
							<div class="col-md-9">
								<div class="row">
									<div class="col-sm-2 text-center">
										<?php echo e(trans('common.event')); ?>

									</div>
									<div class="col-sm-4 text-center">
										<?php echo e(trans('common.match_teams')); ?>

									</div>
									<div class="col-sm-4 text-center">
										<?php echo e(trans('common.game_result')); ?>

									</div>
									<div class="col-sm-2 text-center">
										
									</div>
								</div>
							</div>
						</div>
						<?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result_date => $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<div class="schedule-item margin-bottom-10">
							<div class="row">
								<div class="col-md-3">
									<div class="game-date text-center">
										<i class="fa fa-calendar-o"></i>  <?php echo e(Competition::formatDate(strtotime($result_date))); ?>

									</div>
								</div>
								<div class="col-md-9">
									<?php $__currentLoopData = $result; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $individual_result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<div class="individual">
										<div class="row">
											<div class="col-sm-2 text-center">
												<span class="event-name label label-default">
													<?php if( in_array($individual_result->schedule->event_id, Event::$ball_event_ids) ): ?>
														<i class="icon icon-social-dribbble"></i>
													<?php else: ?>
														<i class="fa fa-flag"></i>
													<?php endif; ?>
													<?php echo e($individual_result->schedule->event->event_name); ?> 
												</span>
											</div>
											<div class="col-sm-4 text-center">
												<span>
													<?php echo e($individual_result->schedule->team1->team_name); ?> <?php echo e(trans('common.team')); ?> <i class="fa fa-user"></i>  :  <i class="fa fa-user"></i> <?php echo e($individual_result->schedule->team2->team_name); ?> <?php echo e(trans('common.team')); ?>

												</span>
											</div>
											<div class="col-md-4 text-center">
												<label>
													<?php echo e(trans('common.winner_team')); ?> : 
												</label>
												<i class="fa fa-thumbs-up"></i>
												<?php echo e($individual_result->winner->team_name); ?><?php echo e(trans('common.team')); ?>

												( <?php echo e($individual_result->winner_score); ?> : <?php echo e($individual_result->loser_score); ?> )
											</div>
											<div class="col-md-2 text-center">
												<a href="<?php echo e(route('schedule.overview', ['id' => $individual_result->schedule->id])); ?>" class="detail-view">
													<i class="fa fa-eye"></i> <?php echo e(trans('common.view_more')); ?>

												</a>
											</div>
										</div>
									</div>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								</div>
							</div>
						</div>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</div>
					<?php else: ?>
					<div class="not-found-result">
						<div class="row">
							<div class="col-md-12 text-center">
								<div class="heading"><?php echo e(trans('common.you_have_no_results')); ?></div>
							</div>
						</div>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('additional_js'); ?>
<script type="text/javascript">
	// start year, month, day setting !
	var start_year = '<?php echo e(date('Y', strtotime($start_date))); ?>';
	var start_month = parseInt('<?php echo e(date('n', strtotime($start_date))); ?>') - 1;
	var start_day = '<?php echo e(date('ja', strtotime($start_date))); ?>';

	// event_content making !
	var event_content = new Array();
	var dt = new Date();
	var y = dt.getFullYear();
	var m = dt.getMonth();
	var d = dt.getDate();

	<?php $__currentLoopData = $json_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

		var title = '<?php echo e($data['title']); ?>';
		var diff_year  = parseInt('<?php echo e($data['diff_year']); ?>');
		var diff_month = parseInt('<?php echo e($data['diff_month']); ?>');
		var diff_day   = parseInt('<?php echo e($data['diff_day']); ?>');
		var progress   = parseInt('<?php echo e($data['progress']); ?>');

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
		
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</script>

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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>