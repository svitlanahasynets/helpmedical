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


<?php $__env->startSection('title'); ?>
    <title><?php echo e(trans('page.frontend.' . $page . '.title')); ?> - <?php echo e(trans('page.title')); ?></title>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('additional_css'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="prev-page-section">
	<a href="<?php echo e(url()->previous()); ?>" class="prev-page-link"><i class="fa fa-reply"></i> <?php echo e(trans('common.prev_page')); ?></a>
</div>
<div class="portlet box red">
	<div class="portlet-title">
		<div class="caption">
			<?php if( in_array($schedule->event_id, Event::$ball_event_ids) ): ?>
				<i class="icon icon-social-dribbble"></i>
			<?php else: ?>
				<i class="fa fa-flag"></i>
			<?php endif; ?>
			<?php echo e($schedule->event->event_name); ?> 
		</div>
	</div>
	<div class="portlet-body">
		<div class="row">
			<?php if( !empty($schedule->result()) ): ?>
			<div class="col-md-4">
				<div class="files">
					<?php if( !empty($schedule->result()->files()) ): ?>
						<div id="myCarousel" class="carousel slide" style="display: none;" data-interval="5000" data-ride="carousel">
							<!-- Indicators -->
							<ol class="carousel-indicators">
								<?php $__currentLoopData = $schedule->result()->files(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<li data-target="#myCarousel" data-slide-to="<?php echo e($key); ?>"></li>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</ol>
							<div class="carousel-inner">
								<?php $__currentLoopData = $schedule->result()->files(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		    						<?php if( in_array($file->ext, ['jpg', 'png', 'bmp']) ): ?>
		    							<span class="photo item">
											<a href="/files/<?php echo e($file->id); ?>/<?php echo e($file->hash); ?>">
												<img src="/files/<?php echo e($file->id); ?>/<?php echo e($file->hash); ?>" alt="" class="img-responsive">
											</a>
										</span>
		    						<?php elseif( in_array($file->ext, ['avi', 'mp4', 'mpg', '3gp']) ): ?>
		    							<span class="video item">
											<!-- <i class="fa fa-video-camera"></i> <?php echo e($file->name); ?> -->
											<video controls>
												<source src="/files/<?php echo e($file->id); ?>/<?php echo e($file->hash); ?>" type="">
											</video>
		    							</span>
		    						<?php else: ?>
		    							<?php echo e(trans('common.no_support_ext')); ?>

		    						<?php endif; ?>
		    					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</div>
						</div>
    				<?php else: ?>
    					<span class="photo no-image">
							<img src="/assets/img/no-image.jpg" class="img-responsive" />
						</span>
    				<?php endif; ?>
				</div>
			</div>
			<?php endif; ?>
			<div class="<?php echo e(!empty($schedule->result()) ? 'col-md-8' : 'col-md-12'); ?>">
				<div class="row margin-bottom-5">
					<div class="col-md-2">
						<label>
		    				<?php echo e(trans('common.competition')); ?> : 
		    			</label>
					</div>
					<div class="col-md-10">
						<span class="ordinary-value">
							<i class="fa fa-flag"></i> <?php echo e($schedule->competition->competition_name); ?>

						</span>
					</div>
				</div>
				<div class="row margin-bottom-5">
					<div class="col-md-2">
						<label>
		    				<?php echo e(trans('common.match_date')); ?> : 
		    			</label>
					</div>
					<div class="col-md-10">
						<span class="ordinary-value">
							<i class="fa fa-calendar"></i> <?php echo e($schedule->gameDate()); ?>

						</span>
					</div>
				</div>
				<div class="row margin-bottom-10">
					<div class="col-md-2">
						<label>
		    				<?php echo e(trans('common.match_teams')); ?> : 
		    			</label>
					</div>
					<div class="col-md-10">
						<div class="ordinary-value">
							<div class="row">
								<div class="col-md-3">
									<i class="fa fa-users"></i> <?php echo e($schedule->team1->team_name); ?> <?php echo e(trans('common.team')); ?> :
								</div>
								<div class="col-md-9">
									<div>
										<?php echo e($schedule->team1->teamMembersInfo()['name']); ?> - <?php echo e($schedule->team1->teamMembersInfo()['count']); ?><?php echo e(trans('common.person')); ?>

									</div>
									<div>
										( <i class="fa fa-male"></i> <?php echo e(trans('common.special_members')); ?> : <?php echo e($schedule->team1->teamMembersInfo($schedule->event_id)['special']); ?>)
									</div>
								</div>
							</div>
						</div>
						<div class="ordinary-value">
							<div class="row">
								<div class="col-md-3">
									<i class="fa fa-users"></i> <?php echo e($schedule->team2->team_name); ?> <?php echo e(trans('common.team')); ?> :
								</div>
								<div class="col-md-9">
									<div>
										<?php echo e($schedule->team2->teamMembersInfo()['name']); ?> - <?php echo e($schedule->team2->teamMembersInfo()['count']); ?><?php echo e(trans('common.person')); ?>

									</div>
									<div>
										( <i class="fa fa-male"></i> <?php echo e(trans('common.special_members')); ?> : <?php echo e($schedule->team2->teamMembersInfo($schedule->event_id)['special']); ?>)
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row margin-bottom-5">
					<div class="col-md-2">
						<label>
		    				<?php echo e(trans('common.game_result')); ?> : 
		    			</label>
					</div>
					<div class="col-md-10">
						<div class="ordinary-value">
							<?php if( !empty($schedule->result()) ): ?>
								<div class="row margin-bottom-10">
									<div class="col-sm-3">
										<?php echo e(trans('common.winner_team')); ?> : <?php echo e($schedule->result()->winner->team_name); ?> <?php echo e(trans('common.team')); ?>

										<i class="fa fa-thumbs-up"></i>
									</div>
									<div class="col-sm-3">
										( <?php echo e($schedule->result()->winner_score); ?> : <?php echo e($schedule->result()->loser_score); ?> )
									</div>
								</div>
								<div class="result-desc">
									<?php echo nl2br($schedule->result()->result_desc); ?>

								</div>
							<?php else: ?>
								<?php echo e(trans('common.no_game_result')); ?>

							<?php endif; ?>
						</div>
					</div>
				</div>
				<div class="row margin-bottom-5">
					<div class="col-md-2">
						<label>
		    				<?php echo e(trans('common.reading_rate')); ?> : 
		    			</label>
					</div>
					<div class="col-md-10">
						<?php
		    				$read_count_array = Schedule::whereRaw(true)->pluck('read_counts')->all();
					        $max_read_count = max($read_count_array);
						?>
						<div class="client-score">
				            <div class="score-wrap">
				                <?php if( $schedule->read_counts != 0 ): ?> 
				                <div class="stars" data-value="<?php echo e($schedule->read_counts / $max_read_count * 100); ?>%">
				                </div>
				                <?php else: ?>
				                <div class="stars" data-value="0%"></div>
				                <?php endif; ?>
				                <div class="client-score-desc" style="display:none;">
				                    <?php echo e(trans('common.reading_count')); ?>&nbsp;:&nbsp;<?php echo e($schedule->read_counts); ?>

				                </div>
				            </div>
				        </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('additional_js'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>