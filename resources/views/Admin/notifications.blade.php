@extends('Includes.admin-base')
@section('title','پست ها')
@section('content')
    <div class="mt-4 mx-2">
        <div class="container-fluid">
            <div>
                @forelse($notifications as $notification)
                    <div class=" bg-notification p-2 m-2 d-flex align-content-around">
                        @if($notification->type==='App\Notifications\PostLikeNotification')
                            <p>
                                {{$notification ->data['user_name']}}
                                پست
                                {{$notification->data['post_slug']}}
                                شما را لایک کرد
                            </p>
                            @if($notification->read_at===null)
                                <p>
                                    <a href="{{route('markNotification',[$notification->id])}}">خوانده شده</a>
                                </p>
                            @else
                                <p>
                                    <a href="">خوانده شد</a>
                                </p>
                            @endif

                        @endif
                        @if($notification->type==='App\Notifications\CommentNotification')
                                <p>
                                    {{$notification ->data['user_name']}}
                                    برای پست شما
                                        {{$notification->data['post']}}
                                    یک کامنت گذاشت
                                </p>
                                @if($notification->read_at===null)
                                    <p>
                                        <a href="{{route('markNotification',[$notification->id])}}">خوانده شده</a>
                                    </p>
                                @else
                                    <p>
                                        <a href="">خوانده شد</a>
                                    </p>
                                @endif

                            @endif
                    </div>
                @empty

                        <p>هیج اعلانی فعلا موجود نیست</p>
                @endforelse


            </div>
        </div>
    </div>
@endsection
