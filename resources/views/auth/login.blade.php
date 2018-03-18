@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-xs-offset-3 col-xs-3">
		<div class="panel panel-default">
			<div class="panel-heading">ログイン</div>
			<div class="panel-body">
				{!! Form::open(['route' => 'login.post']) !!}
					<div class="form-group">
						{!! Form::label('email', 'メールアドレス') !!}
						{!! Form::text('email', old('email'), ['class' => 'form-control']) !!}
					</div>
				
					<div class="form-group">
						{!! Form::label('password', 'パスワード') !!}
						{!! Form::password('password', ['class' => 'form-control']) !!}
					</div>

					<div class="text-right">
						{!! Form::submit('Log in', ['class' => 'bth btn-success']) !!}
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection
