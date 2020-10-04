@extends('layouts.admin.app')

@section('title')
<title>{{ trans('page.admin.' . $page . '.title') }} - {{ trans('page.title') }}</title>
@endsection

@section('content')
<div class="portlet box blue">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-edit"></i>
			괸리자정보변경
		</div>                
	</div>
	<div class="portlet-body form" id="user_profile_container">
		<form role="form" class="form-horizontal" id="user_profile_form" method="post" action="{{ route('profile.save') }}">
			{{ method_field('POST') }}
			{{ csrf_field() }}

			<div class="form-body">
				<div class="row"><div class="col-lg-8">
					<div class="row">  
						<div class="col-sm-6">
							<div class="form-group row">
								<label class="col-sm-5 control-label">관리자아이디<span class="required" aria-required="true">*</span></label>
								<div class="col-sm-7">
									<span class="form-control-static">
										<input type="text" name="username" id="username" autocomplete="off" class="form-control" value="{{ $admin->username }}" />
									</span>                                
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group row">
								<label class="col-sm-5 control-label">이름<span class="required" aria-required="true">*</span></label>
								<div class="col-sm-7">
									<div class="form-control-static">
										{{ $admin->name }}
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">  
						<div class="col-sm-6">
							<div class="form-group row">
								<label class="col-sm-5 control-label">암호<span class="required" aria-required="true">*</span></label>
								<div class="col-sm-7">
									<div class="input-group">
										<span class="input-icon">
											<i class="fa fa-lock fa-fw"></i>
											<input type="password" name="password" autocomplete="off" id="password" class="form-control" />
										</span>                                    
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group row">
								<label class="col-sm-5 control-label">암호확인<span class="required" aria-required="true">*</span></label>
								<div class="col-sm-7">
									<div class="input-group">
										<span class="input-icon">
											<i class="fa fa-lock fa-fw"></i>
											<input type="password" name="password_confirmation" autocomplete="off" id="password_confirmation" class="form-control" />
										</span>                                    
									</div>
								</div>
							</div>
						</div>
					</div>
				</div></div></div>
				<div class="form-actions">
					<input type="submit" name="submit" value="보관" class="btn blue" />
				</div>
			</form>                    
		</div>
	</div>
	@endsection

	@section('additional_js')

	@endsection