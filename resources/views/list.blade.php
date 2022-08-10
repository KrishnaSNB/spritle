@extends('nav')
@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400&display=swap');

    body{
        background-color:#eee;
        font-family: 'Poppins', sans-serif;
    }

    .card{
        border:none;
        -webkit-box-shadow: 0 2px 3px rgba(0, 0, 0, 0.03);
        box-shadow: 0 2px 3px rgba(0, 0, 0, 0.03);
    }

    .comment-text{
        font-size:12px;
    }

    .fs-10{
        font-size:12px;
        
    }
</style>

<div class="container mt-3 d-flex justify-content-center">
	<div class="row d-flex justify-content-center">
		<div class="col-md-8">
			<div class="text-left">
				<h6>All Posts</h6>
			</div>
            @foreach($post as $row)
			<div class="card p-3 mb-2">
				<div class="d-flex flex-row">
                    <img src="https://media.istockphoto.com/vectors/male-profile-flat-blue-simple-icon-with-long-shadow-vector-id522855255?k=20&m=522855255&s=612x612&w=0&h=fLLvwEbgOmSzk1_jQ0MgDATEVcVOh_kqEe0rqi7aM5A="  height="40" width="40" class="rounded-circle">
					<div class="d-flex flex-column ms-2">
						<h6 class="mb-1 text-primary">{{$row->name}}</h6>
						<p class="comment-text">{!! $row->body !!}</p>
					</div>
				</div>
				<div class="d-flex justify-content-between">
					<div class="d-flex flex-row gap-3 align-items-center">
                        <a href="javascript:void(0)">
                            <div class="d-flex align-items-center likeheart" attrid="{{ $row->id }}">
                                @if(DB::table('tbl_like_post')->where('post_id', $row->id)
                                ->where('user_id', session('loginId'))->first())
                                <i class="fa fa-heart"></i>
                                @else
                                <i class="fa fa-heart-o"></i>
                                @endif&nbsp;{{$row->liked_user_count}}
                                <span class="ms-1 fs-10">Like</span>
                            </div>
                        </a>
						
						<div class="d-flex align-items-center">
                            <a href="{{url('/comments/'.$row->id)}}">
                                <i class="fa fa-comment-o"></i>&nbsp;{{$row->cmt_user_count}}
                                <span class="ms-1 fs-10">Comments</span>
                            </a>
						</div>
					</div>
					<div class="d-flex flex-row">
                        @php($diffInDays = \Carbon\Carbon::parse($row->created_at)->diffInDays())

                        @php($showDiff = \Carbon\Carbon::parse($row->created_at)->diffForHumans())

                        @if($diffInDays > 0)

                        @php($showDiff .= ', '.\Carbon\Carbon::parse($row->created_at)->addDays($diffInDays)->diffInHours().' Hours')

                        @endif
						<span class="text-muted fw-normal fs-10">{{$showDiff}}</span>
					</div>
				</div>
			</div>
            @endforeach
		</div>
	</div>
</div>

<script>
    $(document).ready(function() {
        $(".likeheart").click(function() {
            var cid = $(this).attr("attrid");

            $.ajax({
                type: "post",
                url: "{{ url('postlike') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    cid: cid
                },

                success: function(data) {

                    console.log(data);

                    if (data.result == "true") {
                        alert('Liked Successfully');
                        location.reload();
                    } else {
                        alert('Disliked Successfully');
                        location.reload();
                    }
                }
            });

        });
    });
</script>

@stop