@extends('layouts.app')

@section('content')

    <div class="container" >
    @if (\Session::has('success'))
      <div class="alert alert-success">
        <p>{{ \Session::get('success') }}</p>
      </div><br />
     @endif
      <a style="margin-bottom:20px" href="{{action('EventController@create')}}" class="btn btn-success">AJouter un event</a>
    </br>
    <h1>Evenement du mois</h1>
      @foreach($events as $event)
      @php
      $date=date('Y-m-d', $event['date']);
      $dateMois=date('Y-m', $event['date']);
        @endphp
        @if($date>date('Y-m-d') && $dateMois==date('Y-m') && $event['eventMois']==0)
      <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">{{$event['name']}}</h2>
        </div>
        <div class="panel-body">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-md-offset-0">
                      <h3> Le {{$date}} </h3>
                        <img src="{{asset('/images')}}/{{$event['image']}}" alt="{{$event['name']}}">
                        Description : <em>{{$event['description']}}</em>
                        </br>

                    {{$voted=false}}
                    @foreach($registereds as $registered)
                      @if($registered['event_id'] == $event['id'] && $registered['user_id'] == Auth::user()->id)
                      <form  action="{{action('RegisteredController@destroy', $registered['id'])}}" method="post">
                        {{ csrf_field() }}
                          <input name="_method" type="hidden" value="DELETE">
                          <button TYPE="submit" class="btn btn-danger">Se désinscrire</button>
                        </form>
                        <!--{{$voted=true}}-->
                        @break 
                      @endif
                    @endforeach
                    @if($voted==false)
                    <form method="post" action="{{url('registereds')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                        <input value ="{{$event['id']}}" type="hidden" class="form-control" name="event_id">
                        <input value ="{{Auth::user()->id}}" type="hidden" class="form-control" name="user_id">
                        <button TYPE="submit" class="btn btn-info">Sinscrire</button>
                    </form>
                  @endif
                   <!-- {{$nbVote = 0}} -->
             @foreach($likes as $like)
                @if($like['event_id'] == $event['id'])
                  <!-- {{$nbVote++}} -->
                @endif
              @endforeach
              {{$nbVote}}
              @if(Auth::check())
              {{$voted=false}}
                @foreach($likes as $like)
                 @if($like['event_id'] == $event['id'] && $like['user_id'] == Auth::user()->id)
                                     <form  style="margin-bottom:20px" action="{{action('LikeController@destroy', $like['id'])}}" method="post">
                      {{ csrf_field() }}
                      <input name="_method" type="hidden" value="DELETE">
                      <button TYPE="submit" class="btn btn-info btn-sm">délike</button>
                      </form>
                      <!--{{$voted=true}}-->
                    @break 
                  @endif
                @endforeach
                @if($voted==false)
                  <form style="margin-bottom:20px" method="post" action="{{url('likes')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input value ="{{$event['id']}}" type="hidden" class="form-control" name="event_id">
                    <input value ="{{Auth::user()->id}}" type="hidden" class="form-control" name="user_id">
                    <button TYPE="submit" class="btn btn-info btn-sm">like</button>
                  </form>
                @endif
              @endif

                </div>
          <div class="col-md-7 col-md-offset-0">
          <ul class="list-group">
              <label for="Name">Commentaire :</label>
              <div style="overflow-y:scroll; height:200px"> 
          @foreach($comments as $comment)
            @if($comment['event_id'] == $event['id'])
               <li class="list-group-item">
                 <span class="badge">{{$comment['userName']}}</span>
               <span class="badge"><a href="{{action('CommentController@edit', $comment['id'])}}" >Modérer</a></span>
                 {{$comment['content']}}
                 </br>
               </li>
            @endif
          @endforeach
          </div>
          <li class="list-group-item">
            
            <form method="post" action="{{url('comments')}}" enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="row">
                <div class="form-group col-md-4">
                  <input style="width: 600px"type="textarea" class="form-control" name="content" maxlength="150">
                  <input value ="{{$event['id']}}" type="hidden" class="form-control" name="event_id">
                  <input value ="{{Auth::user()->name}}" type="hidden" class="form-control" name="userName">
                  <button style="margin-top:15px" type="submit" class="btn btn-info">Poster le commentaire</button>
                </div>
              </div>
            </form>
          </li>
        </ul>
        </div>
        </div>
        <table>
            <tr>
            <td>
               <a style="margin-right:20px" href="{{url('donwload-pdf', $event['id'])}}" class="btn btn-success">Liste des inscrits en PDF</a>
               <a style="margin-right:20px" href="{{url('donwload-csv', $event['id'])}}" class="btn btn-success">Liste des inscrits en CSV</a>
               </td>
                <td>
                    <a style="margin-right:20px" href="{{action('EventController@edit', $event['id'])}}" class="btn btn-warning">Edit</a>
                </td>
              <td>
          <form action="{{action('EventController@destroy', $event['id'])}}" method="post">
          {{ csrf_field() }}
            <input name="_method" type="hidden" value="DELETE">
            <button class="btn btn-danger" type="submit">Delete</button>
          </form>
              </td>  
        </tr>
        </table>
        </div>
      </div>
      </div>
      @endif
      @endforeach
    <h1>Evenements à venir</h1>
      @foreach($events as $event)
      @if($event['eventMois']==1)
      @php
      $date=date('Y-m-d', $event['date']);
      $dateMois=date('Y-m', $event['date']);
        @endphp
        @if($date>date('Y-m-d'))
      <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">{{$event['name']}}</h2>
        </div>
        <div class="panel-body">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-md-offset-0">
                      <h3> Le {{$date}} </h3>
                        <img src="{{asset('/images')}}/{{$event['image']}}" alt="{{$event['name']}}">
                        Description : <em>{{$event['description']}}</em>
                        </br>
                    {{$voted=false}}
                    @foreach($registereds as $registered)
                      @if($registered['event_id'] == $event['id'] && $registered['user_id'] == Auth::user()->id)
                      <form  action="{{action('RegisteredController@destroy', $registered['id'])}}" method="post">
                        {{ csrf_field() }}
                          <input name="_method" type="hidden" value="DELETE">
                          <button TYPE="submit" class="btn btn-danger">Se désinscrire</button>
                        </form>
                        <!--{{$voted=true}}-->
                        @break 
                      @endif
                    @endforeach
                    @if($voted==false)
                    <form method="post" action="{{url('registereds')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                        <input value ="{{$event['id']}}" type="hidden" class="form-control" name="event_id">
                        <input value ="{{Auth::user()->id}}" type="hidden" class="form-control" name="user_id">
                        <button TYPE="submit" class="btn btn-info">Sinscrire</button>
                    </form>
                  @endif
                  <!-- {{$nbVote = 0}} -->
             @foreach($likes as $like)
                @if($like['event_id'] == $event['id'])
                  <!-- {{$nbVote++}} -->
                @endif
              @endforeach
              {{$nbVote}}
              @if(Auth::check())
              {{$voted=false}}
                @foreach($likes as $like)
                 @if($like['event_id'] == $event['id'] && $like['user_id'] == Auth::user()->id)
                                     <form  action="{{action('LikeController@destroy', $like['id'])}}" method="post">
                      {{ csrf_field() }}
                      <input name="_method" type="hidden" value="DELETE">
                      <button TYPE="submit" class="btn btn-info btn-sm">délike</button>
                      </form>
                      <!--{{$voted=true}}-->
                    @break 
                  @endif
                @endforeach
                @if($voted==false)
                  <form method="post" action="{{url('likes')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input value ="{{$event['id']}}" type="hidden" class="form-control" name="event_id">
                    <input value ="{{Auth::user()->id}}" type="hidden" class="form-control" name="user_id">
                    <button TYPE="submit" class="btn btn-info btn-sm">like</button>
                  </form>
                @endif
              @endif

                </div>
          <div class="col-md-7 col-md-offset-0">
          <ul class="list-group">
              <label for="Name">Commentaire :</label>
              <div style="overflow-y:scroll; height:200px"> 
          @foreach($comments as $comment)
            @if($comment['event_id'] == $event['id'])
               <li class="list-group-item">
                 <span class="badge">{{$comment['userName']}}</span>
               <span class="badge"><a href="{{action('CommentController@edit', $comment['id'])}}" >Modérer</a></span>
                 {{$comment['content']}}
                 </br>
               </li>
            @endif
          @endforeach
          </div>
          <li class="list-group-item">
            
            <form method="post" action="{{url('comments')}}" enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="row">
                <div class="form-group col-md-4">
                  <input style="width: 600px"type="textarea" class="form-control" name="content" maxlength="150">
                  <input value ="{{$event['id']}}" type="hidden" class="form-control" name="event_id">
                  <input value ="{{Auth::user()->name}}" type="hidden" class="form-control" name="userName">
                  <button style="margin-top:15px" type="submit" class="btn btn-info">Poster le commentaire</button>
                </div>
              </div>
            </form>
          </li>
        </ul>
        </div>
        </div>
        <table>
            <tr>
            <td>
               <a style="margin-right:20px" href="{{url('donwload-pdf', $event['id'])}}" class="btn btn-success">Liste des inscrits en PDF</a>
               <a style="margin-right:20px" href="{{url('donwload-csv', $event['id'])}}" class="btn btn-success">Liste des inscrits en csv</a>
               </td>
                <td>
                    <a style="margin-right:20px" href="{{action('EventController@edit', $event['id'])}}" class="btn btn-warning">Edit</a>
                </td>
              <td>
          <form action="{{action('EventController@destroy', $event['id'])}}" method="post">
          {{ csrf_field() }}
            <input name="_method" type="hidden" value="DELETE">
            <button class="btn btn-danger" type="submit">Delete</button>
          </form>
              </td>   
        </tr>
        </table>
        </div>
      </div>
      </div>
      @endif
      @endif
      @endforeach
       <h1>Evenements passés</h1>
       @foreach($events as $event)
       @php
      $date=date('Y-m-d', $event['date']);
        @endphp
        @if($date<date('Y-m-d'))
      <div class="panel panel-default">
 
        <div class="panel-heading">
            <h2 class="panel-title">{{$event['name']}}</h2>
        </div>
        <div class="panel-body">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-md-offset-0">
                       <h3> Le {{$date}} </h3>
                        <img src="{{asset('/images')}}/{{$event['image']}}" alt="{{$event['name']}}">
                        Description : <em>{{$event['description']}}</em>
                        </br>
                </div>
          <div class="col-md-7 col-md-offset-0">
          <ul class="list-group">
              <label for="Name">Commentaire :</label>
              <div style="overflow-y:scroll; height:200px"> 
          @foreach($comments as $comment)
            @if($comment['event_id'] == $event['id'])
               <li class="list-group-item">
                 <span class="badge">{{$comment['userName']}}</span>
               <span class="badge"><a href="{{action('CommentController@edit', $comment['id'])}}" >Modérer</a></span>
                 {{$comment['content']}}
                 </br>
               </li>
            @endif
          @endforeach
          @foreach($images as $image)
            @if($image['event_id'] == $event['id'])
              <li class="list-group-item">
                 <span class="badge">{{$image['userName']}}</span>
                 <span class="badge"><a href="{{action('ImageEventController@delete', $image['id'])}}" >Supprimer</a></span>
                 <img src="{{asset('/images')}}/{{$image['image']}}" alt="{{$event['name']}}">
                 </br>
               </li>
            @endif
          @endforeach
          </div>
          <li class="list-group-item">
            <form method="post" action="{{url('comments')}}" enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="row">
                <div class="form-group col-md-4">
                  <input style="width: 600px"type="textarea" class="form-control" name="content" maxlength="150">
                  <input value ="{{$event['id']}}" type="hidden" class="form-control" name="event_id">
                  <input value ="{{Auth::user()->name}}" type="hidden" class="form-control" name="userName">
                  <button style="margin-top:15px" type="submit" class="btn btn-info">Poster le commentaire</button>
                </div>
              </div>
            </form>
          </li>
          <li class="list-group-item">
            <form method="post" action="{{url('eventimages')}}" enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="row">
                <div class="form-group col-md-4">
                  <input type="file" name="image">
                  <input value ="{{$event['id']}}" type="hidden" class="form-control" name="event_id">
                  <input value ="{{Auth::user()->name}}" type="hidden" class="form-control" name="userName">
                  <button style="margin-top:15px" type="submit" class="btn btn-info">Poster une photo</button>
                </div>
              </div>
            </form>
          </li>
        </ul>
        </div>
        </div>
        <table>
            <tr>
                <td>
                    <a style="margin-right:20px" href="{{action('EventController@edit', $event['id'])}}" class="btn btn-warning">Edit</a>
                </td>
              <td>
          <form action="{{action('EventController@destroy', $event['id'])}}" method="post">
          {{ csrf_field() }}
            <input name="_method" type="hidden" value="DELETE">
            <button class="btn btn-danger" type="submit">Delete</button>
          </form>
              </td>
        </tr>
        </table>
        </div>
      </div>
      </div>
      @endif
      @endforeach
  @endsection