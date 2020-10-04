@extends('layouts.admin.app')

@section('title')
<title>{{ trans('page.admin.' . $page . '.title') }} - {{ trans('page.title') }}</title>
@endsection

@section('additional_css')
<link rel="stylesheet" href="/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css" rel="stylesheet">
@endsection

@section('content')
<div class="portlet box blue">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-plus"></i>
			종업원추가
		</div>                
	</div>
	<div class="portlet-body form" id="create_employee_container">
		<form role="form" class="form-horizontal" id="create_employee_form" method="post" action="{{ route('employee.store') }}">
			{{ method_field('POST') }}
			{{ csrf_field() }}

			<div class="form-body">
				<div class="row">
					<div class="col-lg-8">
						<div class="row">  
							<div class="col-sm-6">
								<div class="form-group row">
									<label class="col-sm-5 control-label">이름<span class="required" aria-required="true">*</span></label>
									<div class="col-sm-7">
										<div class="input-group">
											<span class="input-icon">
												<i class="fa fa-user"></i>
												<input type="text" name="name" id="name" autocomplete="off" class="form-control" value="" />
											</span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group row">
									<label class="col-sm-5 control-label">아이디<span class="required" aria-required="true">*</span></label>
									<div class="col-sm-7">
										<div class="input-group">
		                                    <span class="input-icon">
		                                        <i class="fa fa-lock fa-fw"></i>
		                                        <input type="text" name="username" id="username" autocomplete="off"  value="" class="form-control" />
		                                    </span>
		                                    
		                                </div>
										
									</div>
								</div>
							</div>
						</div>
						<div class="row">  
							<div class="col-sm-6">
								<div class="form-group row">
									<label class="col-sm-5 control-label">성별<span class="required" aria-required="true">*</span></label>
									<div class="col-sm-7">
										<select name="sex" id="sex" class="form-control">
											<option value="">---</option>
											<option value="남자">남자</option>
											<option value="녀자">녀자</option>
										</select>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group row">
									<label class="col-sm-5 control-label">사는곳<span class="required" aria-required="true">*</span></label>
									<div class="col-sm-7">
										<select name="zone" id="zone" class="form-control">
											<option value="">---</option>
											@foreach ($zones as $zone)
											<option value="{{ $zone->id }}">{{ $zone->zone_name }}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="row">  
							<div class="col-sm-6">
								<div class="form-group row">
									<label class="col-sm-5 control-label">생년월일<span class="required" aria-required="true">*</span></label>
									<div class="col-sm-7">
										<input class="form-control form-control-inline date-picker birthday" size="16" type="text" name="birthday" value=""  data-date="1988-09-21" data-date-format="yyyy-mm-dd" data-date-viewmode="years"/>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group row">
									<label class="col-sm-5 control-label">실별<span class="required" aria-required="true">*</span></label>
									<div class="col-sm-7">
										<select name="room" id="room" class="form-control">
											<option value="">---</option>
											@foreach ($rooms as $room)
											<option value="{{ $room->id }}">{{ $room->room_name }}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="row">  
							<div class="col-sm-6">
								<div class="form-group row">
									<label class="col-sm-5 control-label">조직별<span class="required" aria-required="true">*</span></label>
									<div class="col-sm-7">
										<select name="belong" id="belong" class="form-control">
											<option value="">---</option>
											<option value="로동당">로동당</option>
											<option value="직맹">직맹</option>
											<option value="청년동맹">청년동맹</option>
										</select>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group row">
									<label class="col-sm-5 control-label">단체<span class="required" aria-required="true">*</span></label>
									<div class="col-sm-7">
										<select name="community" id="community" class="form-control">
											
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="row">  
							<div class="col-sm-6">
								<div class="form-group row">
									<label class="col-sm-5 control-label">권한<span class="required" aria-required="true">*</span></label>
									<div class="col-sm-7">
										<select name="role" id="role" class="form-control">
											<option value="">---</option>
											<option value="관리자">관리자</option>
											<option value="일반">일반</option>
										</select>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group row">
									<label class="col-sm-5 control-label">특기종목</label>
									<div class="col-sm-7">
										<input type="hidden" id="special_event" name="special_event" class="form-control select2" value="" />
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-actions">
				<input type="submit" name="submit" value="추가" class="btn blue" id="create_employee_button" />
			</div>
		</form>                    
	</div>
</div>

<div class="portlet box yellow">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-users"></i>
			사용자목록
		</div>                
	</div>
	<div class="portlet-body">
		<table class="table table-striped table-bordered table-hover" id="employee_list_table">
			<thead>
				<tr>
					<th width="20">#</th>
					<th>이름</th>
					<th>아이디</th>
					<th>단위</th>
					<th>소속</th>
					<th>사는곳</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach ($users as $i=>$tuser)
				<tr>
					<td>{{ $tuser->id }}</td>
					<td>{{ $tuser->name }}</td>
					<td>{{ $tuser->username }}</td>
					<td>{{ $tuser->room ? $tuser->room->room_name : '' }}</td>
					<td>{{ $tuser->belong }}</td>
					<td>{{ $tuser->zone ? $tuser->zone->zone_name : '' }}</td>
					<td>
						<a href="{{ route('employee.edit', $tuser->id) }}" class="btn btn-icon-only blue"><i class="fa fa-edit"></i></a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endsection

@section('additional_js')
<script src="/assets/plugins/datatables/media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js" type="text/javascript"></script>
@endsection