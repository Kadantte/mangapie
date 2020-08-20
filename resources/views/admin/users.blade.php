@extends ('admin.layout')

@section ('title')
    Admin &middot; Users
@endsection

@section ('top-menu')
    <ul class="nav nav-pills mb-3 justify-content-center">
        <li class="nav-item"><a href="{{ URL::action('AdminController@statistics') }}" class="nav-link">Statistics</a></li>
        <li class="nav-item"><a href="{{ URL::action('AdminController@config') }}" class="nav-link">Config</a></li>
        <li class="nav-item"><a href="{{ URL::action('AdminController@libraries') }}" class="nav-link">Libraries</a></li>
        <li class="nav-item"><a href="#" class="nav-link active">Users</a></li>
        <li class="nav-item"><a href="{{ URL::action('RoleController@index') }}" class="nav-link">Roles</a></li>
    </ul>
@endsection

@section ('card-content')
    <hr>
    <h4><strong>Create</strong></h4>

    {{ Form::open(['action' => 'UserController@create']) }}
    <div class="row">
        <div class="col-12 col-md-4">
            <div class="row mt-3">
                <div class="col-12">
                    <div class="input-group">
                        <div class="input-group-prepend">
                        <span class="input-group-text">
                            <span class="fa fa-user"></span>
                        </span>
                        </div>
                        <input class="form-control" type="text" placeholder="Username" name="name">
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <div class="input-group">
                        <div class="input-group-prepend">
                        <span class="input-group-text">
                            <span class="fa fa-envelope"></span>
                        </span>
                        </div>
                        <input class="form-control" type="email" placeholder="Email" name="email">
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <div class="input-group">
                        <div class="input-group-prepend">
                        <span class="input-group-text">
                            <span class="fa fa-asterisk"></span>
                        </span>
                        </div>
                        <input class="form-control" type="password" placeholder="Password" name="password">
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <div class="input-group">
                        <div class="input-group-prepend">
                        <span class="input-group-text">
                            <span class="fa fa-asterisk"></span>
                        </span>
                        </div>
                        <input class="form-control" type="password" placeholder="Confirm password" name="password-confirm">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="row mt-3">
                <div class="col-12">
                    <h5>Roles</h5>
                </div>
                @foreach ($roles as $role)
                    <div class="col-12 col-md-6">
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" id="role-{{ $role->id }}" type="checkbox" name="roles[]" value="{{ $role->id }}">
                            <label class="custom-control-label" for="role-{{ $role->id }}">{{ $role->name }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <button class="btn btn-primary" type="submit">
                <span class="fa fa-check"></span>&nbsp;Create
            </button>
        </div>
    </div>
    {{ Form::close() }}

    <hr>
    <h4><strong>Existing</strong></h4>

    <div class="row">
        <div class="col-12">
            {{ Form::open(['action' => 'AdminController@searchUsers']) }}
            <div class="input-group">
                <input class="form-control" type="text" placeholder="Username" name="name">
                <div class="input-group-append">
                    <button class="form-control btn btn-primary" type="submit">
                        <span class="fa fa-search"></span>
                    </button>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <table class="table">
                <thead class="bg-dark">
                    <tr>
                        <td>Name</td>
                        <td>Roles</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr @if ($user->id == \Auth::user()->id) class="bg-secondary" @endif>
                            <td>
                                {{ Form::open(['action' => ['UserController@destroy', $user], 'method' => 'delete', 'class' => 'form-inline']) }}
                                <a href="{{ URL::action('UserController@show', [$user]) }}">{{ $user->name }}</a>

                                &nbsp;&verbar;&nbsp;

                                <button class="btn btn-danger btn-sm">
                                    <span class="fa fa-trash"></span>
                                </button>
                                {{ Form::close() }}
                            </td>
                            <td>
                                @php($userRoles = $user->roles)
                                @foreach ($roles as $role)
                                    @if ($user->hasRole($role->name))
                                        {{ Form::open(['action' => ['RoleController@revoke', $user, $role], 'method' => 'delete', 'style' => 'display: inline']) }}
                                        <button class="btn btn-sm btn-primary" data-hasrole="yes" type="submit">
                                            {{ $role->name }}
                                        </button>
                                        {{ Form::close() }}
                                    @else
                                        {{ Form::open(['action' => ['RoleController@grant', $user, $role], 'method' => 'patch', 'style' => 'display:inline']) }}
                                        <button class="btn btn-sm btn-outline-primary" data-hasrole="yes" type="submit">
                                            {{ $role->name }}
                                        </button>
                                        {{ Form::close() }}
                                    @endif
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @if (isset($users) && ($users instanceof \Illuminate\Pagination\LengthAwarePaginator))
                {{ $users->render('vendor.pagination.bootstrap-4') }}
            @elseif (isset($users) && ! ($users instanceof \Illuminate\Pagination\LengthAwarePaginator))
                <a href="{{ URL::action('AdminController@users') }}" class="btn btn-danger">
                    <span class="fa fa-times"></span>&nbsp;Reset
                </a>
            @endif
        </div>
    </div>
@endsection
