<div class="row flex-row border @if($topic->pinned===1) border-danger bg_pinned @else border-light {{$topic->status ===0 ? 'bg_closed' : 'bg_primary' }} @endif my-3 rounded shadow_m">
        <div class="col-md-2 col-sm-3 col-3 f_center flex-column">
            <img src="{{asset($topic->user->getAvatar())}}" class="avatar_sm" alt="">
            <a class="my-2 user_link" href="{{route('users.see.user', ['slug'=>$topic->user->slug])}}">{{$topic->user->username}}</a>

        </div>
        <div class="col-md-5 col-sm-6 col-6 py-2">
        <div class="d-flex ">
                @if($topic->pinned===1)
                <i class="bi bi-pin-angle-fill"></i>&nbsp;
                @endif
                @if($topic->status === 0)
                <i class="bi bi-lock-fill"></i>&nbsp;
                @endif
                <a class="topic_link" href="{{route('users.show.topic', ['slug'=>$topic->slug])}}">
                    <p>{{$topic->title}}</p>
                </a>
            </div>
            <div class="d-flex d_none_small">
            @if(count($topic->tags))
            Tags:&nbsp; &nbsp;    
            @foreach($topic->tags as $tag)
                <a href="{{route('users.topics.by.tag', ['slug'=>$tag->slug])}}"><span class="badge bg-info">{{$tag->name}}</span></a>&nbsp;&nbsp;
            @endforeach
            @endif
            </div>
        </div>
        <div class="col-md-2 col-sm-3 col-3 f_center">
            <a href="{{route('users.topics.by.category', ['slug'=>$topic->category->slug])}}" ><span class="badge" style="background-color:{{$topic->category->color}}">{{$topic->category->name}}</span></a>&nbsp;&nbsp;
        </div>
        <div class="col-md-1 f_center d_none_small">
        {{$topic->likes_count}}
        </div>
        <div class="col-md-1 f_center  d_none_small">
            {{$topic->comments_count}}
        </div>
        <div class="col-md-1 f_center  d_none_small">
        {{$topic->views}}

        </div>
    </div>