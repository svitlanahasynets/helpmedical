<div class="modal fade" id="resultModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<form role="form" class="form-horizontal" id="result_modal_form" method="post" action="{{ Request()->url() }}">
				{{ method_field('POST') }}
				{{ csrf_field() }}

				<input type="hidden" name="modal_event" id="modal_event" value="">
				<input type="hidden" name="date" id="modal_date" value="">
				<input type="hidden" name="competition_id" id="modal_competition_id" value="">
				<input type="hidden" name="schedule_id" id="schedule_id" value="">

				<div class="modal-header">
					<div class="row">
						<div class="col-md-11">
							<div class="result-name">
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
						<table class="table table-striped table-hover table-bordered" id="result_edit_table">
							<thead>
								<tr>
									<th width="20%" class="center">
										경기종목
									</th>
									<th width="10%" class="center">
										대전팀
									</th>
									<th width="8%" class="center">
										종합성적
									</th>
									<th width="" class="center">
										경기결과
									</th>
									<th width="25%" class="center">
										다매체추가
									</th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div><!-- .modal-body -->
				<div class="modal-footer">
					<div class="buttons pull-right">
						<button type="button" class="btn btn-primary edit-modal-save" data-date=""><i class="fa fa-check"></i> 보관</button>
						<button type="button" class="btn btn-link" data-dismiss="modal"><i class="fa fa-check"></i> 취소</button>
					</div>
				</div>

			</form>

		</div><!-- .modal-content -->
	</div>
</div><!-- .modal -->