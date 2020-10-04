@extends('layouts.admin.app')

@section('title')
<title>{{ trans('page.admin.' . $page . '.title') }} - {{ trans('page.title') }}</title>
@endsection

@section('content')
<div class="portlet box blue">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-plus"></i>
			종업원자료갱신
		</div>                
	</div>
	<div class="portlet-body form" id="edit_employee_container">
		<form role="form" class="form-horizontal" id="edit_employee_form" method="post" action="{{ route('employee.editsave', $user->id) }}">
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
												<input type="text" name="name" id="name" autocomplete="off" class="form-control" value="{{ $user->name }}" />
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
												<input type="text" name="username" id="username" autocomplete="off"  value="{{ $user->username }}" class="form-control" />
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
											<option value="남자" {{ $user->sex == '남자' ? 'selected' : '' }}>남자</option>
											<option value="녀자" {{ $user->sex == '녀자' ? 'selected' : '' }}>녀자</option>
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
											<option value="{{ $zone->id }}" {{ $user->zone_id == $zone->id ? 'selected' : '' }}>{{ $zone->zone_name }}</option>
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
										<input class="form-control form-control-inline date-picker birthday" size="16" type="text" name="birthday" value="{{ $user->birthday }}"  data-date="1988-09-21" data-date-format="yyyy-mm-dd" data-date-viewmode="years"/>
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
											<option value="{{ $room->id }}" {{ $user->room_id == $room->id ? 'selected' : '' }}>{{ $room->room_name }}</option>
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
											<option value="로동당" {{ $user->belong == '로동당' ? 'selected' : '' }}>로동당</option>
											<option value="직맹" {{ $user->belong == '직맹' ? 'selected' : '' }}>직맹</option>
											<option value="청년동맹" {{ $user->belong == '청년동맹' ? 'selected' : '' }}>청년동맹</option>
										</select>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group row">
									<label class="col-sm-5 control-label">단체<span class="required" aria-required="true">*</span></label>
									<div class="col-sm-7">
										<select name="community" id="community" class="form-control">
											<option value="">---</option>
											@for ($i = 1; $i <= $community_counts ;  $i++)
											    <option value="{{ $i }}" {{ $user->community == $i ? 'selected' : '' }}>{{ $i }}@if ($user->belong == '로동당') 세포 @else 초급단체 @endif</option>
											@endfor
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
											<option value="관리자" {{ $user->role_id == '관리자' ? 'selected' : '' }}>관리자</option>
											<option value="일반" {{ $user->role_id == '일반' ? 'selected' : '' }}>일반</option>
										</select>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group row">
									<label class="col-sm-5 control-label">특기종목</label>
									<div class="col-sm-7">
										<input type="hidden" id="special_event" name="special_event" class="form-control select2" value="{{ $skills }}" />
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-actions">
				<input type="submit" name="submit" value="갱신" class="btn blue" id="edit_employee_button" />
			</div>
		</form>                    
	</div>
</div>
@endsection

@section('additional_js')

@endsection