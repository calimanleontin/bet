@extends('app')
@section('title1')
    Edit the users
@endsection


@section('content1')

    @if ( !$users->count() )
        For now there are no users for now.
    @else
        <div class="">
            @foreach( $users as $user )
                @if($user->role != 'admin')
                <div class="list-group">

                    <div class="list-group-item">

                        <article>
                            <span>
                            {{$user->name}} is now {{$user->role}}
                                <form method="post" action='{{ url("/edit-privileges") }}'>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="author" value="{{ $user->name }}">
                                    <select name="role">
                                        @foreach($roles as $role )
                                            <option value="{{$role}}">{{$role}}</option>
                                        @endforeach
                                    </select>
                                    <input type="submit" value="Change">
                                </form>
                                </span>
                        </article>

                    </div>

                </div>
                @endif
            @endforeach
        </div>
    @endif


@endsection