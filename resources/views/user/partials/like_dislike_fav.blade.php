@auth 
                    <a href="javascript:void(0)" class="text-decoration-none">
                    @endauth
                        <i class="thumbs_btn bi bi-hand-thumbs-up-fill @if($topic->likedByAuthUser()) success_text @else text-black @endif" data-id="{{$topic->id}}" data-model="topic" data-type="like"></i>
                    @auth
                    </a>
                    @endauth
                    <span class="@if($topic->likedByAuthUser()) success_text @else text-black @endif">{{$likes_count}}</span>
                    
                    @auth
                    <a href="javascript:void(0)" class="text-decoration-none">
                    @endauth
                        <i class="thumbs_btn bi bi-hand-thumbs-down-fill @if($topic->dislikedByAuthUser()) success_text @else text-black @endif" data-id="{{$topic->id}}" data-model="topic" data-type="dislike"></i>
                    @auth
                    </a>
                    @endauth
                    <span class="@if($topic->dislikedByAuthUser()) success_text @else text-black @endif">{{$dislikes_count}}</span>
                    
                    @auth 
                    <a href="javascript:void(0)" class="text-decoration-none">
                    @endauth
                        <i class="bi bi-heart-fill @if($topic->favouritedByAuthUser()) success_text @else text-black @endif" id="fav_btn" data-id="{{$topic->id}}"></i>
                    @auth 
                    </a>
                    @endauth 
                    <span id="fav_count" class="@if($topic->favouritedByAuthUser()) success_text @else text-black @endif">{{$fav_count}}</span>