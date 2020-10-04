<?php
/**
 * Competition Result Page 
 *
 * @author PYH
 * @since Nov 8, 2018
 */

use App\Models\Competition;
use App\Models\Event;
use App\Models\Team;
use App\Models\Schedule;
?>
<div id="competition_section">
	<?php if( count($competitions) > 0 ): ?>
		<div class="row-pagination margin-top-10">
			<div class="col-md-12 text-right">
				<?php echo $competitions->appends(Request::input())->render(); ?>

			</div>
		</div>
		<div class="row title margin-bottom-10">
			<div class="col-md-6">
				<?php echo e(trans('common.showing_of_results', ['from' => $resultsNum['from'], 'to' => $resultsNum['to'], 'total' => $resultsNum['total']])); ?>				
			</div>
		</div>

		<div class="box-section">
			<?php $__currentLoopData = $competitions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $competition): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		    <div class="competition-item margin-bottom-10">
		    	<a href="<?php echo e(route('competition.overview', ['id' => $competition->id])); ?>" class="competition-name"><i class="fa fa-flag"></i> <?php echo e($competition->competition_name); ?></a>
		        <div class="competition-content">
		        	<div class="row">
		        		<div class="col-sm-2">
		        			<label>
		        				<?php echo e(trans('common.game_dates')); ?> : 
		        			</label>
		        		</div>
		        		<div class="col-sm-10">
							<span class="ordinary-value">
								<?php echo e($competition->gameDates()); ?>

							</span>
		        		</div>
		        	</div>
		        	<div class="row">
		        		<div class="col-sm-2">
		        			<label>
		        				<?php echo e(trans('common.game_events')); ?> : 
		        			</label>
		        		</div>
		        		<div class="col-sm-10">
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
		        		<div class="col-sm-2">
		        			<label>
		        				<?php echo e(trans('common.teams')); ?> : 
		        			</label>
		        		</div>
		        		<div class="col-sm-10">
							<span class="ordinary-value">
								<?php echo e($competition->participationTeams()); ?>

							</span>
		        		</div>
		        	</div>
		        	<div class="row">
		        		<div class="col-sm-2">
		        			<label>
		        				<?php echo e(trans('common.winner')); ?> : 
		        			</label>
		        		</div>
		        		<div class="col-sm-10">
							<span class="ordinary-value">
								<i class="fa fa-thumbs-o-up"></i>
								<?php echo e($competition->winner()); ?><?php echo e(trans('common.team')); ?>

							</span>
		        		</div>
		        	</div>
		        	<div class="row" style="margin-bottom: 7px;">
		        		<div class="col-sm-2">
		        			<label style="margin-top: 7px;">
		        				<?php echo e(trans('common.competition_progressed_percent')); ?> : 
		        			</label>
		        		</div>
		        		<div class="col-sm-6">
		        			<?php
						        $event_count = count(explode(",", $competition->event_ids));
						        $team_count = count(Team::where('competition_id', $competition->id)->get()->all());

						        $event_game_counts = $team_count * ($team_count-1) / 2;
						        $total_game_counts = $event_game_counts * $event_count;

						        $progressed_schedules = Schedule::where('competition_id', $competition->id)
						                                        ->where('progress', 1)
						                                        ->get()->all();

						        $progressed_schedules_count = count($progressed_schedules);
						        $progress_percent = round(($progressed_schedules_count / $total_game_counts) * 100);
							?>
							<div class="progress-percent ordinary-value">
	                            <?php echo e(trans('common.progressed_percent', ['n' => $progress_percent])); ?> <?php echo e($total_game_counts); ?>/<?php echo e($progressed_schedules_count); ?> 
	                        </div>
	                        <div class="wrapper">
	                            <div class="competition-progress" style="width: <?php echo e($progress_percent); ?>%;">
	                            </div>
	                        </div>
		        		</div>
		        	</div>
		        	<div class="row">
		        		<div class="col-sm-2">
		        			<label>
		        				<?php echo e(trans('common.reading_rate')); ?> : 
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
		    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</div>

		<div class="row margin-top-10">
			<div class="col-md-10 show-num">
				<?php echo e(trans('common.showing_of_results', ['from' => $resultsNum['from'], 'to' => $resultsNum['to'], 'total' => $resultsNum['total']])); ?>		
			</div>
		</div>
		<div class="row-pagination">
			<div class="col-md-12 text-right">
				<?php echo $competitions->appends(Request::input())->render(); ?>

			</div>
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
