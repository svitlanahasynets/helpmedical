<div class="modal fade" id="edit_schedule_modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="row">
					<div class="col-md-11">
						<div class="schedule-name">
							<label></label>
						</div>
					</div>
					<div class="col-md-1">
						<button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('job.close') }}"><span aria-hidden="true">&times;</span></button>
					</div>
				</div>
			</div>
			<div class="modal-body">
				<div class="modal-box">
					<table class="table table-striped table-hover table-bordered" id="schedule_edit_table">
						<thead>
							<tr>
								<th width="30%" class="center">
									경기종목
								</th>
								<th width="20%" class="center">
									대전팀1
								</th>
								<th width="20%" class="center">
									대전팀2
								</th>
								<th>
									
								</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
				<div class="addition-box">
					<label>추가할수 있는 일정</label>
					<table class="table table-striped table-hover table-bordered" id="schedule_addition_table">
						<thead>
							<tr>
								<th width="30%" class="center">
									경기종목
								</th>
								<th width="20%" class="center">
									대전팀1
								</th>
								<th width="20%" class="center">
									대전팀2
								</th>
								<th>
									
								</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
				<div class="buttons pull-right">
					<button type="button" class="btn btn-primary edit-modal-save" data-date=""><i class="fa fa-check"></i> 보관</button>
					<button type="button" class="btn btn-link" data-dismiss="modal"><i class="fa fa-check"></i> 취소</button>
				</div>
			</div><!-- .modal-body -->
		</div><!-- .modal-content -->
	</div>
</div><!-- .modal -->