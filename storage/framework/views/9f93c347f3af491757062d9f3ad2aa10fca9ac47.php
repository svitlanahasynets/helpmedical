

<?php $__env->startSection('title'); ?>
    <title><?php echo e(trans('page.frontend.' . $page . '.title')); ?> - <?php echo e(trans('page.title')); ?></title>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
	<form role="form" class="form-horizontal" id="search_form" method="get" action="<?php echo e(Request()->url()); ?>">
        <input type="hidden" name="page" id="page" value="<?php echo e($results_page); ?>">
        <input type="hidden" name="start_date" id="start_date" value="<?php echo e($start_date); ?>">
		<input type="hidden" name="end_date" id="end_date" value="<?php echo e($end_date); ?>">

        <div class="input-group">
            <input id="search_title" name="q" class="form-control" type="text" placeholder="<?php echo e(trans('common.keyword')); ?> ..." value="<?php echo e(old('q')); ?>" />
            <span class="input-group-btn">
                <input type="button" id="search_btn" class="btn btn-primary search-btn" value="<?php echo e(trans('common.search')); ?>"></input>           
            </span>         
        </div>
        <div class="clearfix">
		</div>

        <div class="margin-top-10">
            <a href="#" class="clear-filter"><i class="fa fa-recycle"></i> &nbsp;&nbsp;<?php echo e(trans('common.remove_search_terms')); ?></a>
        </div>

        <div class="row margin-top-20">
        	<div class="col-sm-3 col-md-3"> 
        		<div id="condition_section">
        			<div class="condition-box">
        				<label class="condition-label"><i class="fa fa-trophy"></i> &nbsp;&nbsp;&nbsp;<?php echo e(trans('common.competition')); ?> /  <?php echo e(trans('common.event')); ?><?php echo e(trans('common.per')); ?> <?php echo e(trans('common.view')); ?> </label>
        				<div class="condition-value">
        					<select class="form-control" name="category" id="category">
	                            <option value="competition_view" <?php echo e(old('category') == 'competition_view' ? 'selected' : ''); ?>> <?php echo e(trans('common.competition_view')); ?> </option>
	                            <option value="schedule_view" <?php echo e(old('category') == 'schedule_view' ? 'selected' : ''); ?>> <?php echo e(trans('common.game_view')); ?> </option>
	                        </select>
        				</div>
        			</div>
        			<div class="condition-box">
        				<label class="condition-label"><i class="fa fa-calendar"></i> &nbsp;&nbsp;&nbsp;<?php echo e(trans('common.date_range_view')); ?> </label>
                        <script type="text/javascript">
                            var date_range = '<?php echo e($date_range); ?>';
                            var start_date = '<?php echo e($start_date); ?>';
                            var end_date = '<?php echo e($end_date); ?>';
                        </script>
        				<div class="condition-value" style="height: 50px;">
                            <div class="input-group" id="date_range">
                                <input type="text" class="form-control" value="<?php echo e($date_range); ?>">
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
        				<label class="condition-label"><i class="fa fa-star"></i> &nbsp;&nbsp;&nbsp;<?php echo e(trans('common.event_view')); ?> </label>
        				<div class="condition-value">
	        				<?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" class="event-ids" name="event_ids[]" value="<?php echo e($event->id); ?>"  <?php if( (old('event_ids') != '' && in_array($event->id, explode(',', old('event_ids')))) || old('event_ids') == '' || $defaultSearched ): ?> checked <?php endif; ?> >
                                    <?php echo e($event->event_name); ?>

                                </label>                
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                            <label><?php echo e(trans('common.sortby')); ?> :</label>
                            <select class="form-control" name="sort" id="sort">
                                <option value="newest" <?php echo e('newest' == old('sort') ? 'selected' : ''); ?>>Newest</option>
                                <option value="oldest" <?php echo e('oldest' == old('sort') ? 'selected' : ''); ?>>Oldest</option>
                            </select>
                        </div>
                    </div>                        
                </div>

        		<div id="result_section">
                    <?php if( $comp_flag == 1 ): ?>
                        <?php echo $__env->make('pages.frontend.section.competitions_list', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    <?php else: ?>
                        <?php echo $__env->make('pages.frontend.section.events_list', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    <?php endif; ?>
        		</div>
        	</div>
        </div>

	</form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('additional_js'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>