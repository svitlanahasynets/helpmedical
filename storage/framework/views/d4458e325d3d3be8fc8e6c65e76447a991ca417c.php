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
	<?php if( count($schedules) > 0 ): ?>
		<div class="row-pagination margin-top-10">
			<div class="col-md-12 text-right">
				<?php echo $schedules->appends(Request::input())->render(); ?>

			</div>
		</div>
		<div class="row title margin-bottom-10">
			<div class="col-md-10">
				<?php echo e(trans('common.showing_of_results', ['from' => $resultsNum['from'], 'to' => $resultsNum['to'], 'total' => $resultsNum['total']])); ?>				
			</div>
		</div>

		<div class="box-section">
			<?php $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		    <div class="schedule-item margin-bottom-10">
		    	<div class="row">
		    		
	    			<?php if( $schedule->progress == 1 ): ?>
	    			<div class="col-sm-1">
						<div class="status">
							<span class="">
								<?php echo e(trans('common.progressed')); ?>

							</span> 
						</div>
					</div>
					<?php endif; ?>
		    		
		    		<div class="col-sm-3">
		    			<a href="<?php echo e(route('schedule.overview', ['id' => $schedule->id])); ?>" class="schedule-name"><i class="fa fa-star"></i> <?php echo e($schedule->event->event_name); ?></a>
		    		</div>
		    	</div>
		        <div class="schedule-content">
		        	<div class="row">
		        		<?php if( $schedule->progress == 1 ): ?>
		    			<div class="col-sm-1">
						</div>
						<?php endif; ?>
		        		<div class="col-sm-8">
		        			<div class="row">
		        				<div class="col-sm-3">
		        					<label>
				        				<?php echo e(trans('common.competition')); ?> : 
				        			</label>
		        				</div>
		        				<div class="col-sm-8">
									<span class="competition-name ordinary-value">
										<i class="fa fa-flag"></i> <?php echo e($schedule->competition->competition_name); ?>

									</span>
		        				</div>
		        			</div>
		        			<div class="row">
		        				<div class="col-sm-3">
		        					<label>
				        				<?php echo e(trans('common.match_date')); ?> : 
				        			</label>
		        				</div>
		        				<div class="col-sm-9">
									<span class="ordinary-value">
										<i class="fa fa-calendar"></i> <?php echo e($schedule->gameDate()); ?>

									</span>
		        				</div>
		        			</div>
		        			<div class="row">
		        				<div class="col-sm-3">
		        					<label>
				        				<?php echo e(trans('common.match_teams')); ?> : 
				        			</label>
		        				</div>
		        				<div class="col-sm-9">
									<span class="ordinary-value">
										<?php echo e($schedule->team1->team_name); ?> <?php echo e(trans('common.team')); ?> <i class="fa fa-user"></i>  :  <i class="fa fa-user"></i> <?php echo e($schedule->team2->team_name); ?> <?php echo e(trans('common.team')); ?>

									</span>
		        				</div>
		        			</div>
		        			<div class="row">
		        				<div class="col-sm-3">
		        					<label>
				        				<?php echo e(trans('common.game_result')); ?> : 
				        			</label>
		        				</div>
		        				<div class="col-sm-9">
									<div class="ordinary-value">
										<?php if( !empty($schedule->result()) ): ?>
											<div class="row margin-bottom-10">
												<div class="col-sm-6">
													<?php echo e(trans('common.winner_team')); ?> : <i class="fa fa-user"></i> <?php echo e($schedule->result()->winner->team_name); ?> <?php echo e(trans('common.team')); ?>

													<i class="fa fa-thumbs-up"></i>
												</div>
												<div class="col-sm-6">
													
												</div>
											</div>
											<?php if(strlen($schedule->result()->result_desc) > 200): ?>
												<div class="result-desc">
													<?php echo nl2br(mb_substr($schedule->result()->result_desc, 0, 200)); ?> ...
												</div>
									            <a href="<?php echo e(route('schedule.overview', ['id' => $schedule->id])); ?>" class="pull-right"> <i class="fa fa-angle-double-right"></i> <?php echo e(trans('common.view_more')); ?></a>
									        <?php else: ?>
									        	<div class="result-desc">
									            	<?php echo nl2br($schedule->result()->result_desc); ?>

									            </div>
									        <?php endif; ?>
										<?php else: ?>
											<?php echo e(trans('common.no_game_result')); ?>

										<?php endif; ?>
									</div>
		        				</div>
		        			</div>
		        			<div class="row">
		        				<div class="col-sm-3">
		        					<label>
				        				<?php echo e(trans('common.reading_rate')); ?> : 
				        			</label>
		        				</div>
		        				<div class="col-sm-9">
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
		        		<div class="col-sm-3">
	        				<?php if( !empty($schedule->result()) ): ?>
	        					<div class="files">
		        					<?php if( !empty($schedule->result()->files()) ): ?>
			        					<?php $__currentLoopData = $schedule->result()->files(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			        						<?php if( in_array($file->ext, ['jpg', 'png', 'bmp']) ): ?>
			        							<span class="photo file">
													<span class="img-wrapper">
														<a href="/files/<?php echo e($file->id); ?>/<?php echo e($file->hash); ?>">
															<i class="fa fa-camera"></i> <?php echo e($file->name); ?>

														</a>
													</span>
												</span>
			        						<?php elseif( in_array($file->ext, ['avi', 'mp4', 'mpg', '3gp']) ): ?>
			        							<span class="video file">
			        								<a href="/files/<?php echo e($file->id); ?>/<?php echo e($file->hash); ?>">
		        										<i class="fa fa-video-camera"></i> <?php echo e($file->name); ?>

		        									</a>
			        							</span>
			        						<?php else: ?>
			        							<?php echo e(trans('common.no_support_ext')); ?>

			        						<?php endif; ?>
			        					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			        					<span class="badge badge-info">
			        						<?php echo e(count($schedule->result()->files())); ?>

			        					</span>
			        				<?php else: ?>
			        					<span class="photo">
											<span class="img-wrapper">
												<img src="/assets/img/no-image.jpg" class="img-responsive" />
											</span>
										</span>
			        				<?php endif; ?>
		        				</div>
	        				<?php else: ?>
	        					
	        				<?php endif; ?>
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
				<?php echo $schedules->appends(Request::input())->render(); ?>

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
