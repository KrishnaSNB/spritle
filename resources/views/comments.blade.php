@extends('nav')
@section('content')

<head>

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background-color: #eee;
            
        }

        .bdge {
        height: 21px;
        background-color: orange;
        color: #fff;
        font-size: 11px;
        padding: 8px;
        border-radius: 4px;
        line-height: 3px;
        }

        .comments {
        text-decoration: underline;
        text-underline-position: under;
        cursor: pointer;
        }

        .dot {
        height: 7px;
        width: 7px;
        margin-top: 3px;
        background-color: #bbb;
        border-radius: 50%;
        display: inline-block;
        }

        .hit-voting:hover {
        color: blue;
        }

        .hit-voting {
        cursor: pointer;
        }
    </style>
</head>

<div class="container mt-5 mb-5">
	<div class="d-flex justify-content-center row">
		<div class="d-flex flex-column col-md-8">
			<div class="d-flex flex-row align-items-center text-left comment-top p-2 bg-white border-bottom px-4">
				<div class="profile-image"><img class="rounded-circle" src="https://media.istockphoto.com/vectors/male-profile-flat-blue-simple-icon-with-long-shadow-vector-id522855255?k=20&m=522855255&s=612x612&w=0&h=fLLvwEbgOmSzk1_jQ0MgDATEVcVOh_kqEe0rqi7aM5A=" width="70"></div>
				<div class="d-flex flex-column ml-3">
					<div class="d-flex flex-row post-title">
						<h5>{!! $head->body !!}</h5>
						
					</div>
					<div class="d-flex flex-row align-items-center align-content-center post-title"><span class="mr-2 comments">{{$cmt_user_count}}&nbsp;Comments	</span><span class="mr-2 dot"></span><span>{{$head->created_at}}</span></div>
				</div>
			</div>
			<div class="coment-bottom bg-white p-2 px-4">
				<div class="d-flex flex-row add-comment-section mt-4 mb-4">
					<form action="{{url('/comment')}}" method="post" enctype="multipart/form-data">
						@csrf
						<input type="hidden" name="post_id" value="{{$post_id}}">
						<div class="row">
							<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
								<textarea class="form-control mr-3 ck_body" id="body" name="comment"></textarea> 
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3">
								<button class="btn btn-primary" type="submit">Comment</button> 
							</div>
						</div>
					</form>
				</div>
				
				@foreach($comments as $row)
				<hr>
				<div class="commented-section mt-2">
					<div class="d-flex flex-row align-items-center commented-user">
						<h5 class="mr-2">{{$row->name}}</h5>
						
						@php($diffInDays = \Carbon\Carbon::parse($row->created_at)->diffInDays())

						@php($showDiff = \Carbon\Carbon::parse($row->created_at)->diffForHumans())

						@if($diffInDays > 0)

						@php($showDiff .= ', '.\Carbon\Carbon::parse($row->created_at)->addDays($diffInDays)->diffInHours().' Hours')

						@endif

						<span class="dot mb-1"></span><span class="mb-1 ml-2">{{$showDiff}}</span>
					</div>
					<div class="comment-text-sm"><span>{!! $row->comment !!}</span></div>
					<div class="reply-section">
						<div class="d-flex flex-row align-items-center voting-icons">
							<p class="text-primary" style="cursor:pointer;" data-bs-toggle="collapse" data-bs-target="#reply_{{$row->id}}" onclick="loadEditor('editer_{{$row->id}}')" aria-expanded="false" aria-controls="reply_{{$row->id}}">Reply</p>
						</div>
					</div>
					
						@if(isset($reply))
							@foreach($reply as $rep)
								@if($row->id == $rep->parrent_id)
								<div style="margin-left: 30px; background: #80808036;">
									<div class="d-flex flex-row align-items-center commented-user">
										<h5 class="mr-2">{{$rep->name}}</h5>
									</div>
									<div class="comment-text-sm"><span>{!! $rep->comment !!}</span></div>
									<div class="reply-section">
										<div class="d-flex flex-row align-items-center voting-icons">
											
										</div>
									</div>
								</div>
								@endif
							@endforeach
						@endif
					
					<div class="col">
						<div class="collapse multi-collapse" id="reply_{{$row->id}}">
							<div class="card card-body">
							<form action="{{url('/reply')}}" method="post" id="form_{{$row->id}}" enctype="multipart/form-data">
								@csrf
								<input type="hidden" name="post_id" value="{{$post_id}}">
								<input type="hidden" name="comment_id" value="{{$row->id}}">
								<textarea id="editer_{{$row->id}}" class="form-control ck_body" name="comment"></textarea>
								<div class="modal-footer">
									<button type="button" class="btn btn-primary" onclick="handleSubmit('form_{{$row->id}}')">Reply</button>
									<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        						</div>
							</form> 
							</div>
						</div>
  					</div>
				</div>
				@endforeach
				
			</div>
		</div>
	</div>
</div>


  <script
  src="https://code.jquery.com/jquery-3.6.0.slim.min.js"
  integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI="
  crossorigin="anonymous"></script>

  <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>

<script>
	function handleSubmit (id) {
		document.getElementById(id).submit();
	}

	function open() {
		
	}
	let CKEDITOR=[];
	function loadEditor(id) {
		// CKEDITOR.replace(id);
		let ins = document.querySelector('#'+id ).ckeditorInstance;
		if(CKEDITOR[id]) {
			CKEDITOR[id].destroy();
		}
		ClassicEditor
            .create( document.querySelector( '#'+id ) )
            .then( editor => {
					CKEDITOR[id] = editor;
            } )
            .catch( error => {
                    console.error( error );
            } );
	}
	
	loadEditor('body')
	
</script>

@stop	